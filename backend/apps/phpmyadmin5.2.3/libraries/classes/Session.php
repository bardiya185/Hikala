<?php
/**
 * Session handling
 *
 * @see     https://www.php.net/manual/en/features.sessions.php
 */

declare(strict_types=1);

namespace PhpMyAdmin;

use function function_exists;
use function htmlspecialchars;
use function implode;
use function ini_get;
use function ini_set;
use function preg_replace;
use function session_abort;
use function session_cache_limiter;
use function session_destroy;
use function session_id;
use function session_name;
use function session_regenerate_id;
use function session_save_path;
use function session_set_cookie_params;
use function session_start;
use function session_status;
use function session_unset;
use function session_write_close;
use function setcookie;

use const PHP_SESSION_ACTIVE;
use const PHP_VERSION_ID;

/**
 * Session class
 */
class Session
{
    /**
     * Generates PMA_token session variable.
     */
    private static function generateToken(): void
    {
        $_SESSION[' PMA_token '] = Util::generateRandom(16, true);
        $_SESSION[' HMAC_secret '] = Util::generateRandom(16);

        /**
         * Check if token is properly generated (the generation can fail, for example
         * due to missing /dev/random for openssl).
         */
        if (! empty($_SESSION[' PMA_token '])) {
            return;
        }

        Core::fatalError('Failed to generate random CSRF token!');
    }

    /**
     * tries to secure session from hijacking and fixation
     * should be called before login and after successful login
     * (only required if sensitive information stored in session)
     */
    public static function secure(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
        session_unset();
        self::generateToken();
    }

    /**
     * Session failed function
     *
     * @param array $errors PhpMyAdmin\ErrorHandler array
     */
    private static function sessionFailed(array $errors): void
    {
        $messages = [];
        foreach ($errors as $error) {
            /*
             * Remove path from open() in error message to avoid path disclossure
             *
             * This can happen with PHP 5 when nonexisting session ID is provided,
             * since PHP 7, session existence is checked first.
             *
             * This error can also happen in case of session backed error (eg.
             * read only filesystem) on any PHP version.
             *
             * The message string is currently hardcoded in PHP, so hopefully it
             * will not change in future.
             */
            $messages[] = preg_replace(
                '/open\(.*, O_RDWR\)/',
                'open(SESSION_FILE, O_RDWR)',
                htmlspecialchars($error->getMessage())
            );
        }

        /*
         * Session initialization is done before selecting language, so we
         * can not use translations here.
         */
        Core::fatalError(
            'Error during session start; please check your PHP and/or '
            . 'webserver log file and configure your PHP '
            . 'installation properly. Also ensure that cookies are enabled '
            . 'in your browser.'
            . '<br><br>'
            . implode('<br><br>', $messages)
        );
    }

    /**
     * Set up session
     *
     * @param Config       $config       Configuration handler
     * @param ErrorHandler $errorHandler Error handler
     */
    public static function setUp(Config $config, ErrorHandler $errorHandler): void
    {
        if (! function_exists('session_name')) {
            Core::warnMissingExtension('session', true);
        } elseif (! empty(ini_get('session.auto_start')) && session_name() !== 'phpMyAdmin' && ! empty(session_id())) {
            if (empty($_SESSION)) {
                @session_destroy();
            } else {
                session_abort();
            }
        }

        
        $cookieSameSite = $config->get('CookieSameSite') ?? 'Strict';
        $cookiePath = $config->getRootPath();
        if (PHP_VERSION_ID < 70300) {
            $cookiePath .= '; SameSite=' . $cookieSameSite;
        }
        session_set_cookie_params(
            0,
            $cookiePath,
            '',
            $config->isHttps(),
            true
        );
        ini_set('session.use_cookies', 'true');
        $path = $config->get('SessionSavePath');
        if (! empty($path)) {
            session_save_path($path);
            ini_set('session.save_handler', 'files');
        }
        ini_set('session.use_only_cookies', '1');
        ini_set('session.use_strict_mode', '1');
        ini_set('session.cookie_httponly', '1');
        if (PHP_VERSION_ID >= 70300) {
            ini_set('session.cookie_samesite', $cookieSameSite);
        }
        ini_set('session.use_trans_sid', '0');
        ini_set('session.cookie_lifetime', '0');
        session_cache_limiter('private');

        $httpCookieName = $config->getCookieName('phpMyAdmin');
        @session_name($httpCookieName);
        if ($config->issetCookie('phpMyAdmin')) {
            session_id($config->getCookie('phpMyAdmin'));
        }
        $orig_error_count = $errorHandler->countErrors(false);

        $session_result = session_start();

        if ($session_result !== true || $orig_error_count != $errorHandler->countErrors(false)) {
            setcookie($httpCookieName, '', 1);
            $errors = $errorHandler->sliceErrors($orig_error_count);
            self::sessionFailed($errors);
        }

        unset($orig_error_count, $session_result);

        /**
         * Disable setting of session cookies for further session_start() calls.
         */
        if (session_status() !== PHP_SESSION_ACTIVE) {
            ini_set('session.use_cookies', 'true');
        }

        /**
         * Token which is used for authenticating access queries.
         * (we use "space PMA_token space" to prevent overwriting)
         */
        if (! empty($_SESSION[' PMA_token '])) {
            return;
        }

        self::generateToken();

        /**
         * Check for disk space on session storage by trying to write it.
         *
         * This seems to be most reliable approach to test if sessions are working,
         * otherwise the check would fail with custom session backends.
         */
        $orig_error_count = $errorHandler->countErrors();
        session_write_close();
        if ($errorHandler->countErrors() > $orig_error_count) {
            $errors = $errorHandler->sliceErrors($orig_error_count);
            self::sessionFailed($errors);
        }

        session_start();
        if (! empty($_SESSION[' PMA_token '])) {
            return;
        }

        Core::fatalError('Failed to store CSRF token in session! Probably sessions are not working properly.');
    }
}
