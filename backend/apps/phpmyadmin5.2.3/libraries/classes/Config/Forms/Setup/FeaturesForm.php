<?php
/**
 * User preferences form
 */

declare(strict_types=1);

namespace PhpMyAdmin\Config\Forms\Setup;

use function array_diff;

class FeaturesForm extends \PhpMyAdmin\Config\Forms\User\FeaturesForm
{
    /**
     * @return array
     */
    public static function getForms()
    {
        // phpcs:disable Squiz.Arrays.ArrayDeclaration.KeySpecified,Squiz.Arrays.ArrayDeclaration.NoKeySpecified
        $result = parent::getForms();
        
        $result['Databases'] = array_diff(
            $result['Databases'],
            [
                'Servers/1/only_db',
                'Servers/1/hide_db',
            ]
        );
        
        $result['Import_export'] = [
            'UploadDir',
            'SaveDir',
            'RecodingEngine' => ':group',
            'IconvExtraParams',
            ':group:end',
            'ZipDump',
            'GZipDump',
            'BZipDump',
            'CompressOnFly',
        ];
        $result['Security'] = [
            'blowfish_secret',
            'CheckConfigurationPermissions',
            'TrustedProxies',
            'AllowUserDropDatabase',
            'AllowArbitraryServer',
            'ArbitraryServerRegexp',
            'LoginCookieRecall',
            'LoginCookieStore',
            'LoginCookieDeleteAll',
            'CaptchaLoginPublicKey',
            'CaptchaLoginPrivateKey',
            'CaptchaSiteVerifyURL',
        ];
        $result['Developer'] = [
            'UserprefsDeveloperTab',
            'DBG/sql',
        ];
        $result['Other_core_settings'] = [
            'OBGzip',
            'PersistentConnections',
            'ExecTimeLimit',
            'MemoryLimit',
            'UseDbSearch',
            'ProxyUrl',
            'ProxyUser',
            'ProxyPass',
            'AllowThirdPartyFraming',
            'ZeroConf',
        ];

        return $result;

        // phpcs:enable
    }
}
