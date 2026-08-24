<?php

declare(strict_types=1);

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2021 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Webauthn;

use Assert\Assertion;
use CBOR\Decoder;
use CBOR\OtherObject\OtherObjectManager;
use CBOR\Tag\TagObjectManager;
use Cose\Algorithm\Manager;
use Cose\Algorithm\Signature\Signature;
use Cose\Key\Key;
use function count;
use function in_array;
use function is_string;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use function Safe\parse_url;
use Throwable;
use Webauthn\AuthenticationExtensions\AuthenticationExtensionsClientInputs;
use Webauthn\AuthenticationExtensions\AuthenticationExtensionsClientOutputs;
use Webauthn\AuthenticationExtensions\ExtensionOutputCheckerHandler;
use Webauthn\Counter\CounterChecker;
use Webauthn\Counter\ThrowExceptionIfInvalid;
use Webauthn\TokenBinding\TokenBindingHandler;
use Webauthn\Util\CoseSignatureFixer;

class AuthenticatorAssertionResponseValidator
{
    /**
     * @var PublicKeyCredentialSourceRepository
     */
    private $publicKeyCredentialSourceRepository;

    /**
     * @var Decoder
     */
    private $decoder;

    /**
     * @var TokenBindingHandler
     */
    private $tokenBindingHandler;

    /**
     * @var ExtensionOutputCheckerHandler
     */
    private $extensionOutputCheckerHandler;

    /**
     * @var Manager|null
     */
    private $algorithmManager;

    /**
     * @var CounterChecker
     */
    private $counterChecker;

    /**
     * @var LoggerInterface|null
     */
    private $logger;

    public function __construct(PublicKeyCredentialSourceRepository $publicKeyCredentialSourceRepository, TokenBindingHandler $tokenBindingHandler, ExtensionOutputCheckerHandler $extensionOutputCheckerHandler, Manager $algorithmManager, ?CounterChecker $counterChecker = null, ?LoggerInterface $logger = null)
    {
        if (null !== $logger) {
            @trigger_error('The argument "logger" is deprecated since version 3.3 and will be removed in 4.0. Please use the method "setLogger".', E_USER_DEPRECATED);
        }
        if (null !== $counterChecker) {
            @trigger_error('The argument "counterChecker" is deprecated since version 3.3 and will be removed in 4.0. Please use the method "setCounterChecker".', E_USER_DEPRECATED);
        }
        $this->publicKeyCredentialSourceRepository = $publicKeyCredentialSourceRepository;
        $this->decoder = new Decoder(new TagObjectManager(), new OtherObjectManager());
        $this->tokenBindingHandler = $tokenBindingHandler;
        $this->extensionOutputCheckerHandler = $extensionOutputCheckerHandler;
        $this->algorithmManager = $algorithmManager;
        $this->counterChecker = $counterChecker ?? new ThrowExceptionIfInvalid();
        $this->logger = $logger ?? new NullLogger();
    }

    /**
     * @see https://www.w3.org/TR/webauthn/#verifying-assertion
     */
    public function check(string $credentialId, AuthenticatorAssertionResponse $authenticatorAssertionResponse, PublicKeyCredentialRequestOptions $publicKeyCredentialRequestOptions, ServerRequestInterface $request, ?string $userHandle, array $securedRelyingPartyId = []): PublicKeyCredentialSource
    {
        try {
            $this->logger->info('Checking the authenticator assertion response', [
                'credentialId' => $credentialId,
                'authenticatorAssertionResponse' => $authenticatorAssertionResponse,
                'publicKeyCredentialRequestOptions' => $publicKeyCredentialRequestOptions,
                'host' => $request->getUri()->getHost(),
                'userHandle' => $userHandle,
            ]);
            
            if (0 !== count($publicKeyCredentialRequestOptions->getAllowCredentials())) {
                Assertion::true($this->isCredentialIdAllowed($credentialId, $publicKeyCredentialRequestOptions->getAllowCredentials()), 'The credential ID is not allowed.');
            }

            
            $publicKeyCredentialSource = $this->publicKeyCredentialSourceRepository->findOneByCredentialId($credentialId);
            Assertion::notNull($publicKeyCredentialSource, 'The credential ID is invalid.');

            
            $attestedCredentialData = $publicKeyCredentialSource->getAttestedCredentialData();
            $credentialUserHandle = $publicKeyCredentialSource->getUserHandle();
            $responseUserHandle = $authenticatorAssertionResponse->getUserHandle();

            
            if (null !== $userHandle) { //If the user was identified before the authentication ceremony was initiated,
                Assertion::eq($credentialUserHandle, $userHandle, 'Invalid user handle');
                if (null !== $responseUserHandle && '' !== $responseUserHandle) {
                    Assertion::eq($credentialUserHandle, $responseUserHandle, 'Invalid user handle');
                }
            } else {
                Assertion::notEmpty($responseUserHandle, 'User handle is mandatory');
                Assertion::eq($credentialUserHandle, $responseUserHandle, 'Invalid user handle');
            }

            $credentialPublicKey = $attestedCredentialData->getCredentialPublicKey();
            $isU2F = U2FPublicKey::isU2FKey($credentialPublicKey);
            if ($isU2F) {
                $credentialPublicKey = U2FPublicKey::createCOSEKey($credentialPublicKey);
            }
            Assertion::notNull($credentialPublicKey, 'No public key available.');
            $stream = new StringStream($credentialPublicKey);
            $credentialPublicKeyStream = $this->decoder->decode($stream);
            Assertion::true($stream->isEOF(), 'Invalid key. Presence of extra bytes.');
            $stream->close();

            
            

            
            $C = $authenticatorAssertionResponse->getClientDataJSON();

            
            Assertion::eq('webauthn.get', $C->getType(), 'The client data type is not "webauthn.get".');

            
            Assertion::true(hash_equals($publicKeyCredentialRequestOptions->getChallenge(), $C->getChallenge()), 'Invalid challenge.');

            
            $rpId = $publicKeyCredentialRequestOptions->getRpId() ?? $request->getUri()->getHost();
            $facetId = $this->getFacetId($rpId, $publicKeyCredentialRequestOptions->getExtensions(), $authenticatorAssertionResponse->getAuthenticatorData()->getExtensions());
            $parsedRelyingPartyId = parse_url($C->getOrigin());
            Assertion::isArray($parsedRelyingPartyId, 'Invalid origin');
            if (!in_array($facetId, $securedRelyingPartyId, true)) {
                $scheme = $parsedRelyingPartyId['scheme'] ?? '';
                Assertion::eq('https', $scheme, 'Invalid scheme. HTTPS required.');
            }
            $clientDataRpId = $parsedRelyingPartyId['host'] ?? '';
            Assertion::notEmpty($clientDataRpId, 'Invalid origin rpId.');
            $rpIdLength = mb_strlen($facetId);
            Assertion::eq(mb_substr('.'.$clientDataRpId, -($rpIdLength + 1)), '.'.$facetId, 'rpId mismatch.');

            
            if (null !== $C->getTokenBinding()) {
                $this->tokenBindingHandler->check($C->getTokenBinding(), $request);
            }

            $expectedRpIdHash = $isU2F ? $C->getOrigin() : $facetId;
            
            $rpIdHash = hash('sha256', $expectedRpIdHash, true);
            Assertion::true(hash_equals($rpIdHash, $authenticatorAssertionResponse->getAuthenticatorData()->getRpIdHash()), 'rpId hash mismatch.');

            
            Assertion::true($authenticatorAssertionResponse->getAuthenticatorData()->isUserPresent(), 'User was not present');
            
            if (AuthenticatorSelectionCriteria::USER_VERIFICATION_REQUIREMENT_REQUIRED === $publicKeyCredentialRequestOptions->getUserVerification()) {
                Assertion::true($authenticatorAssertionResponse->getAuthenticatorData()->isUserVerified(), 'User authentication required.');
            }

            
            $extensionsClientOutputs = $authenticatorAssertionResponse->getAuthenticatorData()->getExtensions();
            if (null !== $extensionsClientOutputs) {
                $this->extensionOutputCheckerHandler->check(
                    $publicKeyCredentialRequestOptions->getExtensions(),
                    $extensionsClientOutputs
                );
            }

            
            $getClientDataJSONHash = hash('sha256', $authenticatorAssertionResponse->getClientDataJSON()->getRawData(), true);

            
            $dataToVerify = $authenticatorAssertionResponse->getAuthenticatorData()->getAuthData().$getClientDataJSONHash;
            $signature = $authenticatorAssertionResponse->getSignature();
            $coseKey = new Key($credentialPublicKeyStream->getNormalizedData());
            $algorithm = $this->algorithmManager->get($coseKey->alg());
            Assertion::isInstanceOf($algorithm, Signature::class, 'Invalid algorithm identifier. Should refer to a signature algorithm');
            $signature = CoseSignatureFixer::fix($signature, $algorithm);
            Assertion::true($algorithm->verify($dataToVerify, $coseKey, $signature), 'Invalid signature.');

            
            $storedCounter = $publicKeyCredentialSource->getCounter();
            $responseCounter = $authenticatorAssertionResponse->getAuthenticatorData()->getSignCount();
            if (0 !== $responseCounter || 0 !== $storedCounter) {
                $this->counterChecker->check($publicKeyCredentialSource, $responseCounter);
            }
            $publicKeyCredentialSource->setCounter($responseCounter);
            $this->publicKeyCredentialSourceRepository->saveCredentialSource($publicKeyCredentialSource);

            
            $this->logger->info('The assertion is valid');
            $this->logger->debug('Public Key Credential Source', ['publicKeyCredentialSource' => $publicKeyCredentialSource]);

            return $publicKeyCredentialSource;
        } catch (Throwable $throwable) {
            $this->logger->error('An error occurred', [
                'exception' => $throwable,
            ]);
            throw $throwable;
        }
    }

    public function setLogger(LoggerInterface $logger): self
    {
        $this->logger = $logger;

        return $this;
    }

    public function setCounterChecker(CounterChecker $counterChecker): self
    {
        $this->counterChecker = $counterChecker;

        return $this;
    }

    /**
     * @param array<PublicKeyCredentialDescriptor> $allowedCredentials
     */
    private function isCredentialIdAllowed(string $credentialId, array $allowedCredentials): bool
    {
        foreach ($allowedCredentials as $allowedCredential) {
            if (hash_equals($allowedCredential->getId(), $credentialId)) {
                return true;
            }
        }

        return false;
    }

    private function getFacetId(string $rpId, AuthenticationExtensionsClientInputs $authenticationExtensionsClientInputs, ?AuthenticationExtensionsClientOutputs $authenticationExtensionsClientOutputs): string
    {
        if (null === $authenticationExtensionsClientOutputs || !$authenticationExtensionsClientInputs->has('appid') || !$authenticationExtensionsClientOutputs->has('appid')) {
            return $rpId;
        }
        $appId = $authenticationExtensionsClientInputs->get('appid')->value();
        $wasUsed = $authenticationExtensionsClientOutputs->get('appid')->value();
        if (!is_string($appId) || true !== $wasUsed) {
            return $rpId;
        }

        return $appId;
    }
}
