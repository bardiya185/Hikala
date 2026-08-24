<?php

namespace CodeLts\U2F\U2FServer;

class SignRequest implements \JsonSerializable
{
    
    protected $version = U2FServer::VERSION;
    
    protected $challenge;
    
    protected $keyHandle;
    
    protected $appId;

    public function __construct(array $parameters)
    {
        $this->challenge = $parameters['challenge'];
        $this->keyHandle = $parameters['keyHandle'];
        $this->appId = $parameters['appId'];
    }

    /**
     * @return string
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function challenge()
    {
        return $this->challenge;
    }

    /**
     * @return string
     */
    public function keyHandle()
    {
        return $this->keyHandle;
    }

    /**
     * @return string
     */
    public function appId()
    {
        return $this->appId;
    }

    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return [
            'version' => $this->version,
            'challenge' => $this->challenge,
            'keyHandle' => $this->keyHandle,
            'appId' => $this->appId,
        ];
    }

}
