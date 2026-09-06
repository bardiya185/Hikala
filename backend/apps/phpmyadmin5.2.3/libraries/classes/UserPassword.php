<?php

declare(strict_types=1);

namespace PhpMyAdmin;

use PhpMyAdmin\Html\Generator;
use PhpMyAdmin\Query\Compatibility;
use PhpMyAdmin\Server\Privileges;

use function __;
use function strlen;

/**
 * Functions for user password
 */
class UserPassword
{
    
    private $serverPrivileges;

    /**
     * @param Privileges $serverPrivileges Privileges object
     */
    public function __construct(Privileges $serverPrivileges)
    {
        $this->serverPrivileges = $serverPrivileges;
    }

    /**
     * Generate the message
     *
     * @return array   error value and message
     */
    public function setChangePasswordMsg()
    {
        $error = false;
        $message = Message::success(__('The profile has been updated.'));

        if ($_POST['nopass'] != '1') {
            if (strlen($_POST['pma_pw']) === 0 || strlen($_POST['pma_pw2']) === 0) {
                $message = Message::error(__('The password is empty!'));
                $error = true;
            } elseif ($_POST['pma_pw'] !== $_POST['pma_pw2']) {
                $message = Message::error(
                    __('The passwords aren\'t the same!')
                );
                $error = true;
            } elseif (strlen($_POST['pma_pw']) > 256) {
                $message = Message::error(__('Password is too long!'));
                $error = true;
            }
        }

        return [
            'error' => $error,
            'msg' => $message,
        ];
    }

    /**
     * Change the password
     *
     * @param string $password New password
     */
    public function changePassword($password): string
    {
        global $auth_plugin, $dbi;

        $hashing_function = $this->changePassHashingFunction();

        [$username, $hostname] = $dbi->getCurrentUserAndHost();

        $serverVersion = $dbi->getVersion();

        $orig_auth_plugin = $this->serverPrivileges->getCurrentAuthenticationPlugin('change', $username, $hostname);
        $authPluginChanged = false;

        if (isset($_POST['authentication_plugin']) && ! empty($_POST['authentication_plugin'])) {
            if ($orig_auth_plugin !== $_POST['authentication_plugin']) {
                $authPluginChanged = true;
            }

            $orig_auth_plugin = $_POST['authentication_plugin'];
        }

        $sql_query = 'SET password = '
            . ($password == '' ? '\'\'' : $hashing_function . '(\'***\')');

        $isPerconaOrMySql = Compatibility::isMySqlOrPerconaDb();
        if ($isPerconaOrMySql && $serverVersion >= 50706) {
            $sql_query = $this->getChangePasswordQueryAlterUserMySQL(
                $serverVersion,
                $username,
                $hostname,
                $orig_auth_plugin,
                $password === '' ? '' : '***', // Mask it, preview mode
                $authPluginChanged
            );
        } elseif (
            ($isPerconaOrMySql && $serverVersion >= 50507)
            || (Compatibility::isMariaDb() && $serverVersion >= 50200)
        ) {
            if ($orig_auth_plugin === 'sha256_password') {
                $value = 2;
            } else {
                $value = 0;
            }

            $dbi->tryQuery('SET `old_passwords` = ' . $value . ';');
        }

        $this->changePassUrlParamsAndSubmitQuery(
            $username,
            $hostname,
            $password,
            $sql_query,
            $hashing_function,
            $orig_auth_plugin,
            $authPluginChanged
        );

        $auth_plugin->handlePasswordChange($password);

        return $sql_query;
    }

    private function getChangePasswordQueryAlterUserMySQL(
        int $serverVersion,
        string $username,
        string $hostname,
        string $authPlugin,
        string $password,
        bool $authPluginChanged
    ): string {
        global $dbi;

        if ($serverVersion >= 50706 && $serverVersion < 50737) {
            return 'ALTER USER \'' . $dbi->escapeString($username)
                . '\'@\'' . $dbi->escapeString($hostname)
                . '\' IDENTIFIED WITH ' . $authPlugin . ' BY '
                . ($password === '' ? '\'\'' : '\'' . $dbi->escapeString($password) . '\'');
        }

        $sql_query = 'ALTER USER \'' . $dbi->escapeString($username)
            . '\'@\'' . $dbi->escapeString($hostname) . '\' IDENTIFIED';

        if ($authPluginChanged) {
            $sql_query .= ' WITH ' . $authPlugin;
        }

        return $sql_query . ' BY ' . ($password === '' ? '\'\'' : '\'' . $dbi->escapeString($password) . '\'');
    }

    /**
     * Generate the hashing function
     */
    private function changePassHashingFunction(): string
    {
        if (isset($_POST['authentication_plugin']) && $_POST['authentication_plugin'] === 'mysql_old_password') {
            $hashing_function = 'OLD_PASSWORD';
        } else {
            $hashing_function = 'PASSWORD';
        }

        return $hashing_function;
    }

    /**
     * Changes password for a user
     */
    private function changePassUrlParamsAndSubmitQuery(
        string $username,
        string $hostname,
        string $password,
        string $sql_query,
        string $hashing_function,
        string $orig_auth_plugin,
        bool $authPluginChanged
    ): void {
        global $dbi;

        $err_url = Url::getFromRoute('/user-password');

        $serverVersion = $dbi->getVersion();
        $isPerconaOrMySql = Compatibility::isMySqlOrPerconaDb();

        if ($isPerconaOrMySql && $serverVersion >= 50706) {
            $local_query = $this->getChangePasswordQueryAlterUserMySQL(
                $serverVersion,
                $username,
                $hostname,
                $orig_auth_plugin,
                $password,
                $authPluginChanged
            );
        } elseif (
            Compatibility::isMariaDb()
            && $serverVersion >= 50200
            && $serverVersion < 100100
            && $orig_auth_plugin !== ''
        ) {
            if ($orig_auth_plugin === 'mysql_native_password') {
                $dbi->tryQuery('SET old_passwords = 0;');
            } elseif ($orig_auth_plugin === 'sha256_password') {
                $dbi->tryQuery('SET `old_passwords` = 2;');
            }

            $hashedPassword = $this->serverPrivileges->getHashedPassword($_POST['pma_pw']);

            $local_query = 'UPDATE `mysql`.`user` SET'
                . " `authentication_string` = '" . $hashedPassword
                . "', `Password` = '', "
                . " `plugin` = '" . $orig_auth_plugin . "'"
                . " WHERE `User` = '" . $dbi->escapeString($username)
                . "' AND Host = '" . $dbi->escapeString($hostname) . "';";
        } else {
            $local_query = 'SET password = ' . ($password == ''
                ? '\'\''
                : $hashing_function . '(\''
                    . $dbi->escapeString($password) . '\')');
        }

        if (! @$dbi->tryQuery($local_query)) {
            Generator::mysqlDie(
                $dbi->getError(),
                $sql_query,
                false,
                $err_url
            );
        }
        $dbi->tryQuery('FLUSH PRIVILEGES;');
    }

    public function getFormForChangePassword(?string $username, ?string $hostname): string
    {
        return $this->serverPrivileges->getFormForChangePassword($username ?? '', $hostname ?? '', false);
    }
}
