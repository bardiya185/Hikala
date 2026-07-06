<?php
// Default English language file for
// Projects and VirtualHosts sub-menus
// Settings and Tools right-click sub-menus
// 3.0.7 add $w_listenForApache - $w_AddListenPort - $w_deleteListenPort - $w_settings['SupportMariaDB']
// $w_settings['DaredevilOptions']
// $w_Size - $w_EnterSize - $w_Time - $w_EnterTime - $w_Integer - $w_EnterInteger - $w_add_VirtualHost
// 3.0.8 $w_settings['SupportMySQL'] - $w_portUsedMaria - $w_testPortMariaUsed
// 3.0.9 $w_ext_zend
// 3.1.1 $w_defaultDBMS - $w_invertDefault - $w_changeCLI - $w_misc
// $w_settings['ShowphmyadMenu'] - $w_settings['ShowadminerMenu']
// 3.1.2 $w_reinstallServices - $w_settings['mariadbUseConsolePrompt'] - $w_settings['mysqlUseConsolePrompt']
// $w_enterServiceNameAll - $w_settings['NotVerifyPATH'] - $w_MysqlMariaUser
// 3.1.4 $w_settings 'NotVerifyTLD' 'Cleaning' 'AutoCleanLogs' 'AutoCleanLogsMax' 'AutoCleanLogsMax' 'AutoCleanTmp' 'AutoCleanTmpMax' 'iniCommented'
// $w_wampReport - $w_dowampReport
// 3.1.9 $w_settings 'BackupHosts'
// 3.2.0 $w_verifySymlink  - $w_settings['NotVerifyHosts']
// 3.2.1 $w_addingVer - $w_addingVerTxt - $w_goto - $w_FileRepository
// 3.2.2 $w_MysqlMariaUser and $w_EnterSize modified -  - $w_MySQLsqlmodeInfo $w_mysql_mode $w_phpMyAdminHelp $w_PhpMyAdMinHelpTxt
// 3.2.3 https for wampserver.aviatechno
// 3.2.5 $w_emptyLogs - $w_emptyPHPlog - $w_emptyApaErrLog - $w_emptyApaAccLog - $w_emptyMySQLog - $w_emptyMariaLog - $w_emptyAllLog
//       $w_testAliasDir - $w_verifyxDebugdll - $w_apacheLoadedIncludes - $w_settings 'ShowWWWdirMenu'
// 3.2.6 $w_compareApache - $w_versus - $w_restorefile - $w_restore - $w_apache_restore - $w_ApacheRestoreInfo - $w_apache_restore
//       $w_ApacheCompareInfo - $w_apacheDefineVariables - $w_Refresh_Restart - $w_Refresh_Restart_Info
//       $w_checkUpdates - $w_PhpMyAdminBigFileTxt - $w_apacheTools - $w_PHPloadedExt
//       $w_settings 	apacheCompareVersion - apacheRestoreFiles - apacheGracefulRestart - LinksOnProjectsHomePage
//                    ApacheWampParams - apachePhpCurlDll
//       Suppress : $w_enterServiceNameApache - $w_enterServiceNameMysql - $w_enterServiceNameAll
// 3.2.7 $w_showExcludedPorts
// 3.2.8 $w_phpNotExists - LinksOnProjectsHomeByIp - CheckVirtualHost - $w_PHPversionsUse - $w_All_Versions
//       $w_settings 	ScrollListsHomePage
// 3.2.9 $w_phpparam_obs - $w_ApacheCompiledIn - $w_ApacheDoesNotIf - $w_mod_not_disable
//       $w_NoDefaultDBMS
// 3.3.0 $w_settings WampserverBrowser BrowserChange
//       Suppress apachePhpCurlDll
// 3.3.2 $w_PhpMyAdminGoHidedb - $w_PhpMyAdminGoNoPassword - $w_ConvertHttps - $w_wampHttpsHelp - $w_wampHttpsHelpTxt
//       $w_MariaDBMySQLHelp - $w_MariaDBMySQLHelpTxt - $w_settings httpsReady
//       suppress $w_settings['ShowphmyadMenu']
// 3.3.3 suppress $w_MariaDBMySQLHelpTxt transformed into file 'files\mariadb_mysql_english.rtf'
//       suppress $w_wampHttpsHelpTxt    ........... .... .... 'files\wampmodehttps_english.rtf'
// 3.3.4 $w_AdminerHelpTxt - $w_NeedVirtualhost
// 3.3.5 $w_settings LocalhostHttps
// 3.3.6 $w_wampHttpsHelpAuto - $w_wampHttpsHelpManual
// 3.3.7 $w_ExtNotLoaded
//      suppress $w_PhpMyAdMinHelpTxt and $w_PhpMyAdminBigFileTxt transformed into file 'files\phpmyadmin_english.rtf'
// 3.3.8 $w_ApacheDirectives - $w_DataBasesManagement - CleanLogs_SwitchVersion
// 3.4.0 LinksChooseIp - $w_reinstallWait

// Projects sub-menu
$w_projectsSubMenu = 'Your projects';
// VirtualHosts sub-menu
$w_virtualHostsSubMenu = 'Your VirtualHosts';
$w_add_VirtualHost = 'VirtualHost Management';
$w_aliasSubMenu = 'Your Aliases';
$w_portUsed = 'Port used by Apache: ';
$w_portUsedMysql = 'Port used by MySQL: ';
$w_portUsedMaria = 'Port used by MariaDB : ';
$w_testPortUsed = 'Test port used: ';
$w_portForApache = 'Port for Apache';
$w_listenForApache = 'Listen Port to add to Apache';
$w_portForMysql = 'Port for MySQL';
$w_testPortMysql = 'Test port 3306';
$w_testPortMysqlUsed = 'Test MySQL port used: ';
$w_testPortMariaUsed = 'Test MariaDB port used: ';

// Right-click Settings
$w_wampSettings = 'Wamp Settings';
$w_settings = array(
	'urlAddLocalhost' => 'Add localhost in url',
	'VirtualHostSubMenu' => 'VirtualHosts sub-menu',
	'AliasSubmenu' => 'Alias sub-menu',
	'ProjectSubMenu' => 'Projects sub-menu',
	'HomepageAtStartup' => 'Wampserver Homepage at startup',
	'MenuItemOnline' => 'Menu item: Online / Offline',
	'ItemServicesNames' => 'Tools menu item: Change services names',
	'CheckVirtualHost' => 'Check VirtualHost definitions',
	'NotCheckVirtualHost' => 'Don\'t check VirtualHost definitions',
	'NotCheckDuplicate' => 'Don\'t check duplicate ServerName',
	'VhostAllLocalIp' => 'Allow VirtualHost local IP\'s others than 127.*',
	'SupportMySQL' => 'Allow MySQL',
	'SupportMariaDB' => 'Allow MariaDB',
	'DaredevilOptions' => 'Caution: Risky! Only for experts.',
	'ShowadminerMenu' => 'Show Adminer in Menu',
	'mariadbUseConsolePrompt' => 'Modify default Mariadb console prompt',
	'mysqlUseConsolePrompt' => 'Modify default Mysql console prompt',
	'NotVerifyPATH' => 'Do not verify PATH',
	'NotVerifyTLD' => 'Do not verify TLD',
	'NotVerifyHosts' => 'Do not verify hosts file',
	'Cleaning' => 'Automatic Cleaning',
	'AutoCleanLogs' => 'Clean log files automatically',
	'AutoCleanLogsMax' => 'Number of lines before cleaning',
	'AutoCleanLogsMin' => 'Number of lines after cleaning',
	'AutoCleanTmp' => 'Clean tmp directory automatically',
	'AutoCleanTmpMax' => 'Number of files before cleaning',
	'CleanLogs_SwitchVersion' => 'Empty log files at version changes',
	'ForTestOnly' => 'Only for test purpose',
	'iniCommented' => 'Commented php.ini directives (; at the beginning of the line)',
	'BackupHosts' => 'Backup hosts file',
	'ShowWWWdirMenu' => 'Show www folder in Menu',
	'ApacheWampParams' => 'Wampserver settings for Apache',
	'apacheCompareVersion' => 'Allow comparison of Apache settings.',
	'apacheRestoreFiles' => 'Allow Apache files restoration',
	'apacheGracefulRestart' => 'Allow Apache Graceful Restart',
	'LinksOnProjectsHomePage' => 'Allow links on projects homepage',
	'LinksOnProjectsHomeByIp' => 'Link on projects by \'local link IP\'',
	'LinksChooseIp' => 'Choose local IP',
	'ScrollListsHomePage' => 'Allow scrolling of lists on home page',
	'WampserverBrowser' => 'Wampserver browser',
	'BrowserChange' => 'Set Wampserver browser',
	'httpsReady' => 'Wampserver ready to support https',
	'AllowLocalhostHttps' => 'Enable HTTPS for localhost',
);

// Right-click Tools
$w_wampTools = 'Tools';
$w_restartDNS = 'Restart DNS';
$w_testConf = 'Check httpd.conf syntax';
$w_testServices = 'Check state of services';
$w_changeServices = 'Change the names of services';
$w_compilerVersions = 'Check Compiler VC, compatibility and ini files';
$w_UseAlternatePort = 'Use a port other than %s';
$w_AddListenPort = 'Add a Listen port for Apache';
$w_vhostConfig = 'Show VirtualHost examined by Apache';
$w_apacheLoadedModules = 'Show Apache loaded Modules';
$w_apacheLoadedIncludes = 'Show Apache loaded Includes';
$w_apacheDefineVariables = 'Show Apache variables (Define)';
$w_showExcludedPorts = 'Show the ports excluded by the system';
$w_testAliasDir = 'Check relationships Alias  <-> Directory';
$w_verifyxDebugdll = 'Check for unused xDebug dlls';
$w_empty = 'Empty';
$w_misc = 'Miscellaneous';
$w_emptyAll = 'Empty ALL';

$w_emptyLogs = 'Empty logs';
$w_emptyPHPlog = 'Empty PHP error log';
$w_emptyApaErrLog = 'Empty Apache error log';
$w_emptyApaAccLog = 'Empty Apache access log';
$w_emptyMySQLog = 'Empty MySQL log';
$w_emptyMariaLog = 'Empty MariaDB log';
$w_emptyAllLog ='Empty all log files';

$w_dnsorder = 'Check DNS search order';
$w_deleteVer = 'Delete unused versions';
$w_addingVer = 'Add Apache, PHP, MySQL, MariaDB, etc. versions.';
$w_deleteListenPort = 'Delete a Listen port Apache';
$w_delete = 'Delete';
$w_defaultDBMS = 'Default DBMS:';
$w_NoDefaultDBMS = 'Default DBMS : none';
$w_invertDefault = 'Invert default DBMS ';
$w_changeCLI = 'Change PHP CLI version';
$w_reinstallServices = 'Reinstall all services';
$w_reinstallWait = 'May last more than 30 seconds - Wait';
$w_wampReport = 'Wampserver Configuration Report';
$w_dowampReport = 'Create '.$w_wampReport;
$w_verifySymlink = 'Verify symbolic links';
$w_goto = 'Go to:';
$w_FileRepository = 'Links to Wampserver repositories files & addons';
$w_compareApache = 'Apache settings comparison';
$w_versus = 'versus';
$w_restorefile = 'Restore files saved at the installation of Apache';
$w_restore = 'Restore';
$w_checkUpdates = 'Check for updates';
$w_apacheTools = 'Apache Tools';
$w_PHPloadedExt = 'Show PHP loaded Extensions';
$w_PHPversionsUse = 'Show the use of PHP versions';

//miscellaneous
$w_ext_spec = 'Special extensions';
$w_ext_zend = 'Zend extensions';
$w_phpparam_info = 'For information only';
$w_ext_nodll = 'No dll file';
$w_ext_noline = "No 'extension='";
$w_mod_fixed = "Irreversible module";
$w_mod_not_disable = "These modules should not be disabled";
$w_no_module = 'No module file';
$w_no_moduleload = "No 'LoadModule'";
$w_mysql_none = "none";
$w_mysql_user = "user mode";
$w_mysql_default = "by default";
$w_mysql_mode = "Explanations of sql-mode";
$w_apache_restore = "Warning Apache restoration;";
$w_apache_compare = "Warning Apache settings comparison";
$w_Refresh_Restart = "Help ".$w_refresh.' - '.$w_restartWamp;
$w_Size = "Size";
$w_Time = "Time";
$w_Integer = "Integer Value";
$w_phpMyAdminHelp = "Help PhpMyAdmin";
$w_wampHttpsHelp = "Wampserver HTTPS mode help";
$w_wampHttpsHelpAuto = "Wampserver 'automatic' HTTPS mode help";
$w_wampHttpsHelpManual = "Wampserver 'manual' HTTPS mode help";
$w_phpNotExists = 'PHP version doesn\'t exist';
$w_All_Versions = 'All versions';
$w_phpparam_obs = 'Settings Depreciated | Deleted | New';
$w_ApacheCompiledIn = 'Built-in modules';
$w_ApacheDoesNotIf = 'Do not require <IfModule ModName>.';
$w_PhpMyAdminGoHidedb = 'Hide native databases';
$w_PhpMyAdminGoNoPassword = 'Allow connection without password';
$w_ConvertHttps = 'HTTPS mode for VirtualHost';
$w_MariaDBMySQLHelp = "Help MariaDB - MySQL";
$w_NeedVirtualhost = "Help The need for VirtualHost";
$w_ExtNotLoaded = "PHP extension not loaded :";
$w_ApacheDirectives = "Configuration Directives";
$w_DataBasesManagement = "Database management";

// PromptText for Aestan Tray Menu type: prompt variables
// May have \r\n for multilines
$w_EnterInteger = "Enter an integer";
$w_enterPort = "Enter the desired port number";
$w_EnterSize = "Enter Size: xxxx followed by M for Mega or G for Giga\r\nThe symbol M or G must be attached to the number.\r\nFor example : 64M ; 256M ; 1G";
$w_EnterTime = "Enter time in seconds";
$w_MysqlMariaUser = "Enter a valid username. If you don't know, keep 'root' by default.\r\nIf you have set a password for either root or the chosen user, you will need to type that password when prompted for 'Enter password:' from the console. Without password, Enter key";

// Long texts
// Quotation marks " in texts must be escaped: \" - May have \r\n for multilines
$w_addingVerTxt ="All \"addons\", i.e. all installers of Apache, PHP, MySQL or MariaDB versions as well as installers of updates (Wampserver, Aestan Tray Menu, xDebug, etc.) and web applications (PhpMyAdmin, Adminer) are on\r\n\r\n'https://wampserver.aviatechno.net'\r\n\r\nJust download the installer files you want and launch them by right-clicking on the name of the downloaded file then \"Run as administrator\" to have the addon or application added to your version of Wampserver.\r\n\r\nThen, changing Apache, PHP, MySQL or MariaDB version is a matter of three clicks:\r\nLeft-Click -> PHP|Apache|MySQL|MariaDB -> Version -> Choose version\r\n\r\nThe version change does not include any parameter changes you might have made, nor does it transfer databases from the old version to the new one.\r\n\r\nAnother repository exists:\r\n\r\n'https://sourceforge.net/projects/wampserver'.\r\n\r\nThe links to the repositories are in Right-Click -> Help\r\n";
$w_MySQLsqlmodeInfo = "MySQL/MariaDB sql-mode\r\nThe SQL server may run in different SQL modes depending on the value of the sql-mode directive.\r\nSetting one or more modes restricts certain possibilities and requires greater rigor in SQL syntax and data validation.\r\nThe operation of the sql-mode directive in the my.ini file is as follows.\r\n\r\n- sql-mode: by default\r\nThe sql-mode directive does not exist or is commented out (;sql-mode=\"...\")\r\nThe default modes of the MySQL/MariaDB version are applied\r\n\r\n- sql-mode: user mode\r\nThe sql-mode directive is populated with user-defined modes, for example :\r\nsql-mode=\"NO_ZERO_DATE,NO_ZERO_IN_DATE,NO_AUTO_CREATE_USER\"\r\n\r\n- sql-mode: none\r\nThe sql-mode directive is empty but must exist:\r\nsql-mode=\"\"\r\nno SQL mode is applied.";
$w_ApacheRestoreInfo = "--- Restoring Apache Files\r\nSince Apache 2.4.41, at the end of a release installation, the operational files httpd.conf and httpd-vhosts.conf are copied to a backup folder.\r\nIn case of problems or unwanted changes to Apache you can restore these two files to the original Apache configuration.\r\nOf course, IN THIS CASE YOU WILL LOSE ANY CONFIGURATION CHANGES YOU MAY HAVE MADE AFTER INSTALLATION, such as module or include loads.";
$w_ApacheCompareInfo = "--- Comparing Apache versions\r\nIf you have at least two versions of Apache, you have the possibility to compare the current version with a previous version.\r\nThe following are compared:\r\n- LoadModule\r\n- Include\r\n- httpd-vhosts.conf files\r\n- httpd-ssl.conf files\r\n- openssl.cnf files\r\n- Presence and content of the Certs folder\r\nYou have the possibility to copy the configuration of an old version on the current version.\r\n*** WARNING *** No backups will be made, it is your responsibility to make backups BEFORE copying the configurations.";
$w_Refresh_Restart_Info = "--- Differences between '".$w_refresh."' and '".$w_restartWamp."'\r\n-- ".$w_refresh.":\r\n- Performs various checks,\r\n- Rereads the configuration files of Wampserver, Apache, PHP, MySQL and MariaDB,\r\n- Modifies the Wampmanager configuration file accordingly and updates the menus,\r\n- Performs a 'Graceful Restart Apache',\r\n- Reloads the Aestan Tray menu.\r\nThere is no interruption of the Apache, PHP, MySQL and MariaDB connections.\r\n\r\n-- ".$w_restartWamp.":\r\n- Stop the services :".$c_apacheService.", ".$c_mysqlService." and ".$c_mariadbService.",\r\n- Empty all the log files,\r\n- Empty the tmp folder,\r\n- Exit Wampserver,\r\n- Starts Wampserver 'normally'.\r\nThere is thus a total cut of the connections Apache, PHP, MySQL and MariaDB and put back in place these under other identifications";
$w_AdminerHelpTxt ="\r\n--- Adminer ---\r\nAdminer does not allow you to connect to databases without a password.\r\nIt is therefore necessary to create a password for 'root' before using Adminer.\r\nThis can be done via PhpMyAdmin or via the MySQL and/or MariaDB console.\r\nHowever, it is possible to authorize Adminer connections without a password.\r\nTo do this, see the contents of the file c:\\wamp64\\apps\\adminer4.x.y\\index.php\r\n";

?>