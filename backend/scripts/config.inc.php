<?php

if(!defined('WAMPTRACE_PROCESS')) require 'config.trace.php';
if(WAMPTRACE_PROCESS) {
	$errorTxt = "script ".__FILE__;
	$iw = 1; while(!empty($_SERVER['argv'][$iw])) {$errorTxt .= " ".$_SERVER['argv'][$iw];$iw++;}
	require_once 'start_time.php';
	$errorTxt .= ' - Elapsed time='.((microtime(true)-$start_time));
	error_log($errorTxt."\n",3,WAMPTRACE_FILE);
}
$wamp_versions_here = $Alias_Contents = array();
$configurationFile = '../wampmanager.conf';
// Loading Wampserver configuration
$wampConf = @parse_ini_file($configurationFile,false,INI_SCANNER_RAW);
$c_installDir = $wampConf['installDir'];
$configurationFile = $c_installDir.'/wampmanager.conf';
$templateFile = $c_installDir.'/wampmanager.tpl';
$wampserverIniFile = $c_installDir.'/wampmanager.ini';
$wwwDir = $c_installDir.'/www';
$langDir = $c_installDir.'/lang/';
$aliasDir = $c_installDir.'/alias/';
$modulesDir = 'modules/';
$logDir = 'logs/';
$wampBinConfFiles = 'wampserver.conf';
$phpConfFileForApache = 'phpForApache.ini';

// List of log files
$logFilesList = array();
$logFilesList = glob($c_installDir.'/'.$logDir.'*.log');

//We enter the variables of the template with the local conf
$c_wampVersion = $wampConf['wampserverVersion'];
$wamp_versions_here += array('wamp_update' => $c_wampVersion);
$c_wampMode = $wampConf['wampserverMode'];
$c_wampserverID = '{wampserver64}';
$c_wampserverBase = 'TWFkZSBpbiBGcmFuY2UgYnkgRG9taW5pcXVlIE90dGVsbG8=';
$c_navigator = $wampConf['navigator'];
$c_wampVersionInstall = 'unknown';
$c_wampVersionUpdate = '';
if(!empty($wampConf['installVersion'])) {
	$c_wampVersionInstall = $wampConf['installVersion'];
	if(!empty($wampConf['installDate'])) $c_wampVersionInstall .= ' installed on '.$wampConf['installDate'];
	if($c_wampVersion <> $wampConf['installVersion']) {
		if(!empty($wampConf['update'.$c_wampVersion]))
			$c_wampVersionUpdate .= 'Updated to '.$c_wampVersion.' on '.$wampConf['update'.$c_wampVersion];
	}
}

//Retrieve Windows charset
/*
--- Normal (French Windows)
Text_Encoding=Array
(
    [IsSingleByte] => True
    [BodyName] => iso-8859-1
    [EncodingName] => Europe de l'Ouest (Windows)
    [HeaderName] => Windows-1252
    [WebName] => Windows-1252
    [WindowsCodePage] => 1252
    [CodePage] => 1252
    [LocaleCtype] => 1252
)
--- With beta utf-8 checked in Configuration Panel
--- Region -> Administration -> Change regional settings
--- Beta: Use Unicode UTF-8 format for global language support
Text_Encoding=
Array
(
    [IsSingleByte] => False
    [BodyName] => utf-8
    [EncodingName] => Unicode (UTF-8)
    [HeaderName] => utf-8
    [WebName] => utf-8
    [WindowsCodePage] => 1200
    [CodePage] => 65001
    [LocaleCtype] => 1252
*/
$Windows_Charset = '';
$Text_Encoding = array('IsSingleByte' => '','BodyName' => '','EncodingName' => '','HeaderName' => '','WebName' => '','WindowsCodePage' => '', 'CodePage' => '');
$command = "CMD /D /C powershell [System.Text.Encoding]::Default";
$output = shell_exec($command);
if(is_null($output)) {
	error_log("Result of command '".$command."' is null");
}
else {
	foreach($Text_Encoding as $key => $value) {
		if(preg_match('~^'.$key.'\s+:\s+(.+)~mi',$output,$matches) === 1) {
			$Text_Encoding[$key] = $matches[1];
		}
	}
}


$Text_Encoding['LocaleCtype']= trim(strstr(setlocale(LC_CTYPE,''),'.'),'.');
$Windows_Charset = 'Windows-'.$Text_Encoding['LocaleCtype'];

//To be able to launch projects by IP instead of localhost
//http://169.254.x.y/myproject/ instead of http://localhost/myproject/
$c_local_host = gethostname();
$c_local_ip_list = array();
$c_local_ip_list = gethostbynamel($c_local_host);
if($c_local_host === false || $c_local_ip_list === false || count($c_local_ip_list) == 0) {
	$c_local_ip = 'localhost';
	$c_local_ip_list = array('localhost');
}
else {
	$c_local_ip = $c_local_ip_list[0];
}

// See Message For information items in configuration submenus
$seeInfoMessage = true;

$c_editor = $wampConf['editor'];
$c_logviewer = $wampConf['logviewer'];

// Get current language
$lang = $wampConf['language'];

//Adding Variables for Ports
$c_DefaultPort = "80";
$c_UsedPort = $wampConf['apachePortUsed'];
$c_DefaultMysqlPort = $wampConf['mysqlDefaultPort'];
$c_UsedMysqlPort = $wampConf['mysqlPortUsed'];
$c_UsedMariaPort = $wampConf['mariaPortUsed'];

//Variables for Apache
$c_apacheService = $wampConf['ServiceApache'];
$c_apacheVersion = $wampConf['apacheVersion'];
$c_apacheServiceInstallParams = $wampConf['apacheServiceInstallParams'];
$c_apacheServiceRemoveParams = $wampConf['apacheServiceRemoveParams'];
$c_apacheVersionDir = $c_installDir.'/bin/apache';
$c_apacheBinDir = $c_apacheVersionDir.'/apache'.$wampConf['apacheVersion'].'/'.$wampConf['apacheExeDir'];
$c_apacheModulesDir = $c_apacheVersionDir.'/apache'.$wampConf['apacheVersion'].'/modules';
$c_apacheConfDir = $c_apacheVersionDir.'/apache'.$wampConf['apacheVersion'].'/'.$wampConf['apacheConfDir'];
$c_apacheConfFile = $c_apacheConfDir.'/'.$wampConf['apacheConfFile'];
$c_apacheVhostConfFile = $c_apacheVersionDir.'/apache'.$wampConf['apacheVersion'].'/'.$wampConf['apacheConfDir'].'/extra/httpd-vhosts.conf';
$c_apacheAutoIndexConfFile = $c_apacheVersionDir.'/apache'.$wampConf['apacheVersion'].'/'.$wampConf['apacheConfDir'].'/extra/httpd-autoindex.conf';
$c_apacheDefineConf = $c_apacheVersionDir.'/apache'.$c_apacheVersion.'/wampdefineapache.conf';
$c_apacheExe = $c_apacheBinDir.'/'.$wampConf['apacheExeFile'];
$c_apacheVarNotChange = array('APACHE24', 'VERSION_APACHE', 'INSTALL_DIR', 'APACHE_DIR', 'SRVROOT');

//Variables for PHP
$c_phpVersion = $wampConf['phpVersion'];
$c_phpCliVersion = $wampConf['phpWampVersion'];
$c_phpVersionDir = $c_installDir.'/bin/php';
$c_phpConfFile = $c_apacheVersionDir.'/apache'.$wampConf['apacheVersion'].'/'.$wampConf['apacheExeDir'].'/'.$wampConf['phpConfFile'];
$c_phpConfFileIni = $c_phpVersionDir.'/php'.$c_phpVersion.'/'.$wampConf['phpConfFile'];
$c_phpCliConfFile = $c_phpVersionDir.'/php'.$c_phpCliVersion.'/'.$wampConf['phpConfFile'];
$c_phpExe = $c_phpVersionDir.'/php'.$c_phpCliVersion.'/'.$wampConf['phpExeFile'];
$c_phpWebExe = $c_phpVersionDir.'/php'.$c_phpVersion.'/'.$wampConf['phpExeFile'];
$c_phpCli = $c_phpVersionDir.'/php'.$c_phpCliVersion.'/'.$wampConf['phpCliFile'];
$c_phpBinDir = $c_phpVersionDir.'/php'.$wampConf['phpVersion'].'/';
$c_phpExtDir = $c_phpVersionDir.'/php'.$wampConf['phpVersion'].'/ext/';
$phpCliMinVersion = "5.6.40";

//Variables for MySQL
$c_mysqlService = $wampConf['ServiceMysql'];
$c_mysqlPortUsed = $wampConf['mysqlPortUsed'];
$c_mysqlVersion = $wampConf['mysqlVersion'];
$c_mysqlServiceInstallParams = $wampConf['mysqlServiceInstallParams'];
$c_mysqlServiceRemoveParams = $wampConf['mysqlServiceRemoveParams'];
$c_mysqlVersionDir = $c_installDir.'/bin/mysql';
$c_mysqlBinDir = $c_mysqlVersionDir.'/mysql'.$wampConf['mysqlVersion'].'/'.$wampConf['mysqlExeDir'];
$c_mysqlExe = $c_mysqlBinDir.'/'.$wampConf['mysqlExeFile'];
$c_mysqlConfFile = $c_mysqlVersionDir.'/mysql'.$wampConf['mysqlVersion'].'/'.$wampConf['mysqlConfDir'].'/'.$wampConf['mysqlConfFile'];
$c_mysqlConsole = $c_mysqlVersionDir.'/mysql'.$c_mysqlVersion.'/'.$wampConf['mysqlExeDir'].'/mysql.exe';
$c_mysqlExeAnti = str_replace('/','\\',$c_mysqlExe);
$c_mysqlConfFileAnti = str_replace('/','\\',$c_mysqlConfFile);

// Variables for MariaDB
$c_mariadbService = $wampConf['ServiceMariadb'];
$c_mariadbPortUsed = $wampConf['mariaPortUsed'];
$c_mariadbVersion = $wampConf['mariadbVersion'];
$c_mariadbServiceInstallParams = $wampConf['mariadbServiceInstallParams'];
$c_mariadbServiceRemoveParams = $wampConf['mariadbServiceRemoveParams'];
$c_mariadbVersionDir = $c_installDir.'/bin/mariadb';
$c_mariadbBinDir = $c_mariadbVersionDir.'/mariadb'.$wampConf['mariadbVersion'].'/'.$wampConf['mariadbExeDir'];
$c_mariadbExe = $c_mariadbBinDir.'/'.$wampConf['mariadbExeFile'];
$c_mariadbConfFile = $c_mariadbVersionDir.'/mariadb'.$wampConf['mariadbVersion'].'/'.$wampConf['mariadbConfDir'].'/'.$wampConf['mariadbConfFile'];
$c_mariadbConsole = $c_mariadbVersionDir.'/mariadb'.$c_mariadbVersion.'/'.$wampConf['mariadbExeDir'].'/mysql.exe';
$c_mariadbExeAnti = str_replace('/','\\',$c_mariadbExe);
$c_mariadbConfFileAnti = str_replace('/','\\',$c_mariadbConfFile);

//Check hosts file writable
$c_hostsFile = str_replace("\\","/",getenv('WINDIR').'/system32/drivers/etc/hosts');
$c_hostsFile_writable = true;
$WarningMsg = '';
if(file_exists($c_hostsFile)) {
	if(!is_file($c_hostsFile)) {
		$WarningMsg .= $c_hostsFile." is not a file\r\n";
	}
	elseif(!is_writable($c_hostsFile)) {
		if(@chmod($c_hostsFile, 0644) === false) {
			$WarningMsg .= "Impossible to modify the file ".$c_hostsFile." to be writable\r\n";
		}
		if(!is_writable($c_hostsFile)) {
			$WarningMsg .= "The file ".$c_hostsFile." is not writable";
		}
	}
}
else {
	$WarningMsg .= "The file ".$c_hostsFile." does not exists\r\n";
}
if(!empty($WarningMsg)) {
	$c_hostsFile_writable = false;
	error_log($WarningMsg);
	if(WAMPTRACE_PROCESS) error_log("script ".__FILE__."\n*** ".$WarningMsg."\n",3,WAMPTRACE_FILE);
}
//Check last number of wampsave hosts
$next_hosts_save = 0;
if($wampConf['BackupHosts'] == 'on') {
	$hosts_wampsave = @glob($c_hostsFile.'_wampsave.*');
	if(count($hosts_wampsave) > 0) {
		$next_hosts_save = pathinfo(end($hosts_wampsave),PATHINFO_EXTENSION) + 1;
	}
}
//End check hosts writable

//Symbolic links to be created in apache/bin folder
//on certain dlls of the PHP version used as an Apache module
$php_icu_dll = str_replace($c_phpBinDir,'',glob($c_phpBinDir."icu??*[5-9][0-9].dll"));
$phpDllToCopy = array_merge($php_icu_dll,
	array (
		'libeay32.dll',
		'ssleay32.dll',
		'libsasl.dll',
		'libpq.dll',
		'libssh2.dll', //For php 5.5.17
		'libsodium.dll', //For php 7.2.0
		'libsqlite3.dll', //For php 7.4.0
		'php5isapi.dll',
		'php5nsapi.dll',
		'php5ts.dll',
		'php7ts.dll', //For PHP 7
		'php8ts.dll', //For PHP 8
	)
);

//Symbolic links created if does not exist as a file in Apache bin folder
//SSL 3 for PHP >= 8.2.0 extensions curl, ldap, openssl, snmp
$php820_DllToCopy = array(
	'libcrypto-3-x64.dll',
	'libssl-3-x64.dll',
);

//SSL 1 for PHP < 8.2.0
$phpN820_DllToCopy = array(
	'libcrypto-1_1-x64.dll',
	'libssl-1_1-x64.dll',
);

//Values must be the same as in php.ini - xdebug parameters must be the latest

//For PHP version only used for Wampserver scripts
$phpParamsCLI = array(
	'date.timezone',
	'max_execution_time',
	'memory_limit',
	);
//For all others PHP versions
$phpParams = array (
	'allow_url_fopen',
	'allow_url_include',
	'auto_append_file',
	'auto_detect_line_endings',
	'auto_globals_jit',
	'auto_prepend_file',
	'date.timezone',
	'default_charset',
	'default_mimetype',
	'disable_classes',
	'disable_functions',
	'display_errors',
	'display_startup_errors',
	'enable_dl',
	'expose_php',
	'file_uploads',
	'filter.default',
	'ignore_repeated_errors',
	'ignore_repeated_source',
	'implicit_flush',
	'include_path',
	'intl.default_locale',
	'log_errors',
	'max_execution_time',
	'max_input_time',
	'max_input_vars',
	'memory_limit',
	'mysqli.allow_local_infile',
	'opcache.enable',
	'opcache.jit',
	'output_buffering',
	'post_max_size',
	'realpath_cache_size',
	'realpath_cache_ttl',
	'register_argc_argv',
	'report_memleaks',
	'request_order',
	'session.save_path',
	'short_open_tag',
	'track_errors',
	'upload_max_filesize',
	'upload_tmp_dir',
	'variables_order',
	'zend.enable_gc',
	'zlib.output_compression',
	'zlib.output_compression_level',
	'error_reporting',
	'xdebug.mode',
	'xdebug.remote_enable',
	'xdebug.profiler_enable',
	'xdebug.profiler_enable_trigger',
	'xdebug.show_local_vars',
	'xdebug.log_level',
	);

//PHP parameters with values not On or Off cannot be switched on or off
//Can be changed if 'change' = true and 'title' & 'values' not empty
//Parameter name must be also into $phpParams array
//To manualy enter value, 'Choose' must be the last 'values' and 'title' must be 'Size' or 'Seconds' or 'Integer'
//Warning : specific treatment for date.timezone - Don't modify.
$phpParamsNotOnOff = array(
	'date.timezone' => array(
		'change' => true,
		'title' => 'Timezone',
		'quoted' => true,
		'values' => array('Africa', 'America', 'Antarctica', 'Arctic', 'Asia', 'Atlantic', 'Australia', 'Europe', 'Indian', 'Pacific'),
		),
	'memory_limit' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('16M', '32M', '64M', '128M', '256M', '512M', '1G', 'Choose'),
		),
	'max_execution_time' => array(
		'change' => true,
		'title' => 'Seconds',
		'quoted' => false,
		'values' => array('20', '30', '60', '120', '180', '240', '300', 'Choose'),
		),
	'max_input_time' => array(
		'change' => true,
		'title' => 'Seconds',
		'quoted' => false,
		'values' => array('20', '30', '60', '120', '180', '240', '300', 'Choose'),
		),
	'max_input_vars' => array(
		'change' => true,
		'title' => 'Integer',
		'quoted' => false,
		'values' => array('1000', '2000', '2500', '5000', '10000', 'Choose'),
		'min' => '1000',
		'max' => '20000',
		'default' => '2500',
		),
	'post_max_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('2M', '4M', '8M', '16M','32M', '64M', '128M', '256M', 'Choose'),
		),
	'realpath_cache_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('4M', '8M', '16M', '32M', '64M'),
		),
	'upload_max_filesize' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('2M', '4M', '8M', '16M','32M', '64M', '128M', '256M', 'Choose'),
		),
	'default_charset' => array('change' => false),
	'disable_classes' => array('change' => false) ,
	'disable_functions' => array('change' => false),
	'output_buffering' => array('change' => false),
	'session.save_path' => array('change' => false),
	'variables_order' => array('change' => false),
	'request_order' => array('change' => false),
	'default_mimetype' => array('change' => false),
	'auto_prepend_file' => array('change' => false),
	'auto_append_file' => array('change' => false),
	'upload_tmp_dir' => array('change' => false),
	'error_reporting' => array('change' => false),
	'opcache.jit' => array('change' => false),
	'zend.enable_gc' => array('change' => false),
	'zlib.output_compression_level' => array('change' => false),
	'xdebug.mode' => array(
		'change' => true,
		'title' => 'xDebug Mode',
		'quoted' => false,
		'values' => array('off', 'develop', 'coverage', 'debug', 'gcstats', 'profile', 'trace'),
		),
	'xdebug.overload_var_dump' => array('change' => false),
	'xdebug.log_level' => array(
		'change' => true,
		'title' => 'xDebug log level',
		'quoted' => false,
		'values' => array('0','1','3','5','7','10'),
		'infos' => array('Criticals','Connection','Warnings','Communication','Information','Debug Breakpoint'),
	),
);
//PHP parameters that doesn't support Apache Graceful Restart but only Apache Service Restart
$phpParamsApacheRestart = array(
	'xdebug.mode',
	'xdebug.remote_enable',
	'xdebug.profiler_enable',
	'xdebug.profiler_enable_trigger',
	'xdebug.show_local_vars',
	'xdebug.log_level',
);

// Extensions can not be loaded by extension =
// for example zend_extension
$phpNotLoadExt = array(
	'php_opcache',
	'php_xdebug',
	);

$zend_extensions = array(
	'php_opcache' => array('loaded' => '0','content' => '', 'version' => ''),
	'php_xdebug' => array('loaded' => '0','content' =>'', 'version' => ''),
	);

//MySQL parameters
// All parameters must be defined with underscores (_) and not dashes (-)
$mysqlParams = array (
	'basedir',
	'datadir',
	'key_buffer_size',
	'lc_messages',
	'log_error_verbosity',
	'max_allowed_packet',
	'default_storage_engine',
	'innodb_lock_wait_timeout',
	'innodb_buffer_pool_size',
	'innodb_log_file_size',
	'innodb_default_row_format',
	'innodb_strict_mode',
	'myisam_sort_buffer_size',
	'query_cache_size',
	'sql_mode',
	'sort_buffer_size',
	'prompt',
	'skip_grant_tables',
	'table_definition_cache',
	'default_authentication_plugin',
	'local_infile',
	'secure_file_priv',
);
//MySQL parameters with values not On or Off cannot be switched on or off
//Can be changed if 'change' = true && 'title' && 'values'
//Parameter name must be also into $mysqlParams array
//To manualy enter value, 'Choose' must be the last 'values' and 'title' must be 'Size' or 'Seconds' or 'Number'
$mysqlParamsNotOnOff = array(
	'basedir' => array(
		'change' => false,
		'msg' => "\nThis setting should not be changed, otherwise you risk losing your existing databases.\n",
		),
	'datadir' => array(
		'change' => false,
		'msg' => "\nThis setting should not be changed, otherwise you risk losing your existing databases.\n",
		),
	'key_buffer_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('16M', '32M', '64M', 'Choose'),
		),
	'lc_messages' => array(
		'change' => false,
		'msg' => "\nTo set the Error Message Language see:\n\nhttps://dev.mysql.com/doc/refman/5.7/en/error-message-language.html\n",
		),
	'log_error_verbosity' => array(
		'change' => true,
		'title' => 'Number',
		'quoted' => false,
		'values' => array('1', '2', '3'),
		'text' => array('1' => 'Errors only', '2' => 'Errors and warnings', '3' => 'Errors, warnings, and notes'),
		),
	'max_allowed_packet' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('16M', '32M', '64M', '128M', '256M', '512M', '1G', 'Choose'),
		),
	'default_storage_engine' => array(
		'change' => true,
		'title' => 'Text',
		'quoted' => false,
		'values' => array('MYISAM','InnoDB'),
		),
	'innodb_lock_wait_timeout' => array(
		'change' => true,
		'title' => 'Seconds',
		'quoted' => false,
		'values' => array('20', '30', '50', '120', 'Choose'),
		),
	'innodb_buffer_pool_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('16M', '32M', '64M', '128M', '256M', '512M', '1G', 'Choose'),
		),
	'innodb_log_file_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('4M', '8M', '16M', '32M', '64M', 'Choose'),
		),
	'innodb_default_row_format' => array(
		'change' => true,
		'title' => 'Text',
		'quoted' => false,
		'values' => array('dynamic','compact','redundant'),
		),
	'myisam_sort_buffer_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('16M', '32M', '64M', 'Choose'),
		),
	'query_cache_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('4M', '8M', '16M', 'Choose'),
		),
	'sql_mode' => array(
		'change' => true,
		'title' => 'Special',
		'quoted' => true,
		),
	'sort_buffer_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('2M', '4M', '16M', 'Choose'),
		),
	'prompt' => array(
		'change' => false,
		'msg' => "\nTo set the console prompt see:\n\nhttps://dev.mysql.com/doc/refman/5.7/en/mysql-commands.html\n",
		),
	'table_definition_cache' => array(
		'change' => false,
		'msg' => "\nTo set the table_definition_cache see:\n\nhttps://dev.mysql.com/doc/refman/8.0/en/server-system-variables.html#sysvar_table_definition_cache\n",
		),
	'skip_grant_tables' => array(
		'change' => false,
		'msg' => "\n\nWARNING!! WARNING!!\nMySQL my.ini file directive 'skip_grant tables' is uncommented\nThis option causes the server to start without using the privilege system at all,\nWHICH GIVES ANYONE WITH ACCESS TO THE SERVER UNRESTRICTED ACCESS TO ALL DATABASES.\nThis option also causes the server to suppress during its startup sequence the loading of:\nuser-defined functions (UDFs), scheduled events, and plugins that were installed.\n\nYou should leave this option 'uncommented' ONLY for the time required\nto perform certain operations such as the replacement of a lost password for 'root'.\n",
		),
	'default_authentication_plugin' => array('change' => false,),
	'local_infile' => array('change' => false,
	'msg' => "\nlocal_infile: If set to 1, LOCAL is supported for LOAD DATA INFILE statements.\nIf set to 0, usually for security reasons, attempts to perform a LOAD DATA LOCAL will fail with an error message."),
	'secure_file_priv' => array('change' => false,
	'msg' => "\nsecure_file_priv: LOAD DATA, SELECT ... INTO and LOAD FILE() will only work with files in the specified path.\nIf not set, the default, or set to empty string, the statements will work with any files that can be accessed."),
);

//MariaDB parameters
// All parameters must be defined with underscores (_) and not dashes (-)
$mariadbParams = array (
	'basedir',
	'datadir',
	'key_buffer_size',
	'lc_messages',
	'log_warnings',
	'max_allowed_packet',
	'default_storage_engine',
	'innodb_lock_wait_timeout',
	'innodb_buffer_pool_size',
	'innodb_default_row_format',
	'innodb_log_file_size',
	'innodb_strict_mode',
	'myisam_sort_buffer_size',
	'query_cache_size',
	'sql_mode',
	'sort_buffer_size',
	'prompt',
	'skip_grant_tables',
	'secure_file_priv',
);
//MariaDB parameters with values not On or Off cannot be switched on or off
//Can be changed if 'change' = true && 'title' && 'values'
//Parameter name must be also into $mariadbParams array
//To manualy enter value, 'Choose' must be the last 'values' and 'title' must be 'Size' or 'Seconds' or 'Number'
$mariadbParamsNotOnOff = array(
	'basedir' => array(
		'change' => false,
		'msg' => "\nThis setting should not be changed, otherwise you risk losing your existing databases.\n",
		),
	'datadir' => array(
		'change' => false,
		'msg' => "\nThis setting should not be changed, otherwise you risk losing your existing databases.\n",
		),
	'key_buffer_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('16M', '32M', '64M', '128M', '256M', 'Choose'),
		),
	'lc_messages' => array(
		'change' => false,
		'msg' => "\nTo set the Error Message Language see:\n\nhttps://mariadb.com/kb/en/mariadb/server-system-variables/#lc_messages\n",
		),
	'log_warnings' => array(
		'change' => false,
		'msg' => "\nTo set the log_warning directive see:\nhttps://mariadb.com/kb/en/server-system-variables/#log_warnings\n",
		),
	'prompt' => array(
		'change' => false,
		'msg' => "\nTo set the console prompt see:\n\nhttps://dev.mysql.com/doc/refman/5.7/en/mysql-commands.html\n",
		),
	'max_allowed_packet' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('16M', '32M', '64M', '128M', '256M', '512M', '1G', 'Choose'),
		),
	'default_storage_engine' => array(
		'change' => true,
		'title' => 'Text',
		'quoted' => false,
		'values' => array('MYISAM','InnoDB'),
		),
	'innodb_lock_wait_timeout' => array(
		'change' => true,
		'title' => 'Seconds',
		'quoted' => false,
		'values' => array('20', '30', '50', '120', 'Choose'),
		),
	'innodb_buffer_pool_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('16M', '32M', '64M', '128M', '256M', '512M', '1G', 'Choose'),
		),
	'innodb_log_file_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('4M', '8M', '16M', '32M', '64M', 'Choose'),
		),
	'innodb_default_row_format' => array(
		'change' => true,
		'title' => 'Text',
		'quoted' => false,
		'values' => array('dynamic','compact'),
		),
	'key_buffer_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('16M', '32M', '64M', '128M', '256M', 'Choose'),
		),
	'myisam_sort_buffer_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('16M', '32M', '64M', 'Choose'),
		),
	'query_cache_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('4M', '8M', '16M', 'Choose'),
		),
	'sql_mode' => array(
		'change' => true,
		'title' => 'Special',
		'quoted' => 'true',
		),
	'sort_buffer_size' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('2M', '4M', '16M', 'Choose'),
		),
	'skip_grant_tables' => array(
		'change' => false,
		'msg' => "\n\nWARNING!! WARNING!!\nmariaDB my.ini file directive 'skip_grant_tables' is uncommented\nThis option causes the server to start without using the privilege system at all,\nWHICH GIVES ANYONE WITH ACCESS TO THE SERVER UNRESTRICTED ACCESS TO ALL DATABASES.\nThis option also causes the server to suppress during its startup sequence the loading of:\nuser-defined functions (UDFs), scheduled events, and plugins that were installed.\n\nYou should leave this option 'uncommented' ONLY for the time required\nto perform certain operations such as the replacement of a lost password for 'root'.\n"),
	'secure_file_priv' => array(
		'change' => false,
		'msg' => "\nsecure_file_priv: LOAD DATA, SELECT ... INTO and LOAD FILE() will only work with files in the specified path.\nIf not set, the default, or set to empty string, the statements will work with any files that can be accessed."),
);

// Adding parameters to WampServer modifiable
// by "Settings" sub-menu on right-click Wampmanager icon
// Needs $w_settings['parameter'] in wamp\lang\modules\settings_english.php
// #  	At the beginning = Separator only
// ##   	               = Separator + SubMenu
// #-                    = Separator no Caption + Submenu
// ###  	               = Last item in SubMenu
$wamp_Param = array(
	'AliasSubmenu',
	'ShowWWWdirMenu',
	'SupportMySQL',
	'SupportMariaDB',
	'HomepageAtStartup',
	'BackupHosts',
	'ScrollListsHomePage',
	'httpsReady',
	'AllowLocalhostHttps',
	'##WampserverBrowser',
	'###BrowserChange',
	'##CheckVirtualHost',
	'NotCheckVirtualHost',
	'###NotCheckDuplicate',
	'##ApacheWampParams', //do not modify - submenu ApacheWampParams is used in Apache configuration
	'apacheRestoreFiles',
	'apacheCompareVersion',
	'###apacheGracefulRestart',
	'##Cleaning',
	'AutoCleanLogs',
	'CleanLogs_SwitchVersion',
	'AutoCleanLogsMax',
	'AutoCleanLogsMin',
	'AutoCleanTmp',
	'###AutoCleanTmpMax',
	'#-DaredevilOptions',
	'NotVerifyTLD',
	'NotVerifyHosts',
	'LinksOnProjectsHomePage',
	'LinksOnProjectsHomeByIp',
	'###LinksChooseIp',
);
//Wampserver parameters with values not On or Off cannot be switched on or off
//or can be switched on or of but with dependance from another parameter
//Can be changed if 'change' = true && 'title' && 'values'
//Parameter name must be also into $wamp_Param array
//dependance is the name of Wampserver parameter that must be 'on' to see the parameter
//To manualy enter value, 'Choose' must be the last 'values' and 'title' must be 'Size' or 'Seconds' or 'Integer'
$wampParamsNotOnOff = array(
	'AutoCleanLogsMax' => array(
		'change' => true,
		'dependance' => 'AutoCleanLogs',
		'title' => 'Integer',
		'quoted' => true,
		'values' => array('1000', '2000', '5000', '10000'),
		'min' => '1000',
		'max' => '10000',
		'default' => '1000',
		),
	'AutoCleanLogsMin' => array(
		'change' => true,
		'dependance' => 'AutoCleanLogs',
		'title' => 'Integer',
		'quoted' => true,
		'values' => array('1', '10', '20', '50', '100'),
		'min' => '1',
		'max' => '100',
		'default' => '50',
		),
	'AutoCleanTmpMax' => array(
		'change' => true,
		'dependance' => 'AutoCleanTmp',
		'title' => 'Integer',
		'quoted' => true,
		'values' => array('1000', '2000', '5000', '10000'),
		'min' => '1000',
		'max' => '10000',
		'default' => '1000',
		),
	'LinksOnProjectsHomeByIp' => array(
		'change' => true,
		'dependance' => 'LinksOnProjectsHomePage',
		'title' => 'OnOff',
		),
	'LinksChooseIp' => array(
		'change' => true,
		'dependance' => 'LinksOnProjectsHomeByIp',
		'title' => 'Special',
		'quoted' => true,
		'values' => $c_local_ip_list,
		),
	'WampserverBrowser' => array(
		'change' => false,
		'seeifconf' => 'BrowserChoice',
		),
	'BrowserChange' => array(
		'change' => true,
		'title' => 'Special',
		'quoted' => true,
		'seeifconf' => 'BrowserChoice',
	),
	'httpsReady' => array(
		'dependance' => 'UseWampHttps',
		'change' => true,
		'title' => 'OnOff',
		'quoted' => true,
		'showcmd' => 'normal',
	),
	'AllowLocalhostHttps' => array(
		'dependance' => 'httpsReady',
		'change' => true,
		'title' => 'OnOff',
		'quoted' => true,
	),
);

//Parameter servitude must be off if first parameter is off
$WampParamServitude = array(
	'LinksOnProjectsHomePage' => array(
		'servitude' => 'LinksOnProjectsHomeByIp',
	),
	'LinksOnProjectsHomeByIp' => array(
		'servitude' => 'LinksChooseIp',
	),
);

//PhpMyAdmin-specific parameters in its alias
// like php_admin_value upload_max_filesize 128M
//   or php_admin_flag ignore_repeated_errors Off
$PMA_Params = array(
	'upload_max_filesize',
  'post_max_size',
  'max_execution_time',
  'max_input_time',
);

//PhpMyAdmin parameters with values not On or Off cannot be switched on or off
//Can be changed if 'change' = true and 'title' & 'values' not empty
//Parameter name must be also into $PMA_Params array
//To manualy enter value, 'Choose' must be the last 'values' and 'title' must be 'Size' or 'Seconds' or 'Integer'
$PMA_ParamsNotOnOff = array(
	'upload_max_filesize' => array(
		'change' => true,
		'title' => 'Size',
		'quoted' => false,
		'values' => array('128M', '256M', '512M', '1G', 'Choose'),
		'same' => 'post_max_size',
		),
	'post_max_size' => array(
		'change' => false,
		'msg' => 'Must have the same value as upload_max_filesize',
		),
	'max_execution_time' => array(
		'change' => true,
		'title' => 'Seconds',
		'quoted' => false,
		'values' => array('360', '720', '1440', '3600', 'Choose'),
		'same' => 'max_input_time',
		),
	'max_input_time' => array(
		'change' => false,
		'msg' => 'Must have the same value as max_execution_time',
		),
);

// Apache modules which should not be disabled
$apacheModNotDisable = array(
	'alias_module',
	'authz_core_module',
	'authz_host_module',
	'dir_module',
	'log_config_module',
	'mime_module',
	'php5_module',
	'php7_module',
	'php_module',
	);

// Apache modules not to be unloaded if $virtualHost['index'] is true
$apacheModuleNotUnload = array(
	'fcgid_module' => array(
		'index' => 'ServerNameUseFcgid',
	),
	'socache_shmcb_module' => array(
		'index' => 'ServerNameUseHttps',
	),
	'ssl_module' => array(
		'index' => 'ServerNameUseHttps',
	),
);

// Apache settings
$apache_Params = array(
	'AcceptFilter http' => array(
		'mask' => 'none|connect',
		'default' => 'connect',
	),
	'AcceptFilter https' => array(
		'mask' => 'none|connect',
		'default' => 'connect',
	),
	'EnableMMAP' => array(
		'mask' => 'On|Off',
		'default' => 'On',
	),
	'EnableSendfile' => array(
		'mask' => 'On|Off',
		'default' => 'On',
	),
	'HostnameLookups' => array(
		'mask' => 'On|Off|Double',
		'default' => 'Off',
	),
	'LogLevel' => array(
		'mask' => 'Debug|Info|Notice|Warn|Error|Crit|Alert|Emerg|trace[1-8]?',
		'default' => 'Warn',
	),
	'ServerSignature' => array(
		'mask' => 'On|Off|Email',
		'default' => 'Off',
	),
	'ServerTokens' => array(
		'mask' => 'Major|Minor|Minimal|Min|ProductOnly|Prod|OS|Full',
		'default' => 'Full',
	),
	'ThreadStackSize' => array(
		'mask' => '[0-9]+',
		'default' => '65536',
		'value' => array('8388608','16777216','33554432','67108864'),
	),
	'ThreadLimit' => array(
		'mask' => '[0-9]+',
		'default' => '1920',
		'value' => array('2048','4096','8192','16384'),
	),
	'ThreadsPerChild' => array(
		'mask' => '[0-9]+',
		'default' => '64',
		'max' => 'ThreadLimit',
		'value' => array('1024','2048','4096','8192','16384'),
	),
);

/* BigMenu -> Aestan Tray Menu column menus since 3.2.2.6
   Meaning of the items in the order:
   1 = Name of menu (Caption), 2 = number of items by column, 3 =separator 0 or 1
   Items 1 & 2 may be a variable name into single quote (not array) */
$AesBigMenu = array(
	array('$w_apacheModules','$NBmodApacheLines',1),
	array('Africa',18,1),
	array('America',37,1),
	array('Asia',21,1),
	array('Europe',21,1),
	array('Pacific',21,1),
	array('$w_phpSettings',22,1),
	array('$w_phpExtensions','$NBextPHPlines',1),
);

/* TextMenus -> Aestan Tray Menu text menu items since 3.2.2.9
   Font size, color since 3.2.3.0 - Types Flash, RTF, PDF since 3.2.5.4
   Meaning of the items in the order:
   Indice 0 : Submenu Name -+- Indice 1 Caption submenu
   Indice 2 : Type 0=info 1=help 2=Warning 3=Confirm 4=Error
   Indice 3 : Font size -+- Indice 4 : Font color RGB delphi mode (Example $D77800)
   Indice 5 : Background color
   Indice 6 Width added to popup window : W+0
   Indice 7 Filetype : Flash, RTF, PDF
   Indice 8 : Title
   If Indice 7 = Flash
     Indice 9 : Text = only one line (Write \r\n for line feed)
       End-of-line and comma conversions (#13 for line feed ; &#44; for comma)
       are done by the php script refresh.php
     Indice 10 : number of characters per line max (wordwrap) 0 = no limit
     Indice 11 : indice of Glyph - -1 for none
   If Indice 7 = RTF or PDF
     Indice 9 = Relative path of the RTF or PDF file from $c_installDir (like c:/wamp64)
                must use slash / not antislash \
   	Different file names depending on the language used must be of the form filename_language.rtf
   	e.g. instructions_english.pdf or maria_mysql_french.rtf and it must be the English file name
   	below, e.g. maria_mysql_english.rtf
   	 Indice 10 : indice of Glyph - -1 for none
   ----------
   All indices except Type, Font size, Font color, Background color and WordWrap
       may be variable names into single quotes (not array) like '$w_mysql_mode'
   Indice 9 — only for Flash — may be the concatenation of the contents of several variables
            in which case it must be an array of variable names into single quotes */
$AesTextMenus = array(
	array('instructions_for_use','$w_helpUse',1,10,'$000000','$EEEEEE','W+0','PDF','$w_helpUse','files/instructions_english.pdf',22),
	array('need_virtualhost','$w_NeedVirtualhost',1,10,'$000000','$EEEEEE','W+0','RTF','$w_NeedVirtualhost','files/need_virtualhost_english.rtf',22),
	array('AddingVersions','$w_addingVer',1,10,'$000000','$EEEEEE','W+0','Flash','$w_addingVer','$w_addingVerTxt',96,22),
	array('mysql_mode','$w_mysql_mode',1,10,'$000000','$EEEEEE','W+0','Flash','$w_mysql_mode','$w_MySQLsqlmodeInfo',96,22),
	array('phpmyadmin-help','$w_phpMyAdminHelp',0,10,'$000000','$EEEEEE','W+0','RTF','$w_phpMyAdminHelp','files/phpmyadmin_english.rtf',22),
	array('apacherestore-help','$w_apache_restore',2,10,'$000000','$EEEEEE','W+0','Flash','$w_apache_restore','$w_ApacheRestoreInfo',96,23),
	array('apachecompare-help','$w_apache_compare',2,10,'$000000','$EEEEEE','W+0','Flash','$w_apache_compare','$w_ApacheCompareInfo',96,23),
	array('wampHttps-help','$w_wampHttpsHelpAuto',0,10,'$000000','$EEEEEE','W+0','RTF','$w_wampHttpsHelpAuto','files/wampmodehttps_english.rtf',22),
	array('wampHttps-help-manual','$w_wampHttpsHelpManual',0,10,'$000000','$EEEEEE','W+0','RTF','$w_wampHttpsHelpManual','files/wampmodehttpsmanuel_english.rtf',22),
	array('mariadb-mysql-help','$w_MariaDBMySQLHelp',0,10,'$000000','$EEEEEE','W+30','RTF','$w_MariaDBMySQLHelp','files/mariadb_mysql_english.rtf',22),
	array('refresh-restart-help','$w_Refresh_Restart',0,10,'$000000','$EEEEEE','W+0','Flash','$w_Refresh_Restart','$w_Refresh_Restart_Info',120,22),
);

/* TextMenuColor -> Aestan Tray Menu since 3.2.4.6
   Meaning of the items in the order:
   Indice 0 : Caption text to find, case sensitive.
   Indice 1 : Background color gradient start
   Indice 2 : Background color gradient end
   Indice 3 : Text color
   Indice 4 : Text style. 0 none ; 1 bold ; 2 italic ; 3 bold italic
   Example : TextKeyColor0=[FCGI,$EEEEEE,$EEEEEF,$FF0000,2
   Indice 0 may be variable name (not array) into single quotes like '$w_restartDNS'
            may be array index without simple quotes in index like
            '$w_settings[DaredevilOptions]' or '$myarray[56]' */

$AesTextMenuColor = array(
	array('[FCGI-','$F8F8F8','$F8F8F7','$FF0000',2),
	array('[IDNA-','$F8F8F8','$F8F8F7','$FF0000',2),
	array('[HTTPS]','$F8F8F8','$F8F8F7','$606000',1),
	array('[FCGI ->','$F8F8F8','$F8F8F7','$FF0000',0),
	array('[FCGI - CLI]','$F8F8F8','$F8F8F7','$FF0000',0),
	array('$w_settings[DaredevilOptions]','$FFFFFF','$FFFFFE','$0000FF',0),
	array('$w_apache_restore','$FFFFFF','$EEEEEE','$FF0000',2),
	array('$w_apache_compare','$FFFFFF','$EEEEEE','$FF0000',2),
	array('$w_startedOn','$F2A626','$C57F0B','$FFFFFF',0),
	array('$w_ApacheCompiledIn','$EEEEEE','$EEEEEF','$FF0000',0),
	array('$w_mod_not_disable','$F8F8F8','$F8F8F7','$FF0000',0),
	array('$w_ApacheDoesNotIf','$F8F8F8','$F8F8F7','$FF0000',0),
);

/* SeparatorMenuColor -> Aestan Tray Menu since 3.2.4.7
   Different for Left and Right menu
   Meaning of the itmes in the order:
   Indice 0 : Caption text to find, case sensitive.
   Indice 1 : Background color gradient start
   Indice 2 : Background color gradient end
   Indice 3 : Text color
   Indice 4 : Text style. 0 none ; 1 bold ; 2 italic ; 3 bold italic
   Indice 5 : Font name
   Indice 6 : Font size
   Example : LeftSeparatorKeyColor0=Text to show,$EEEEEE,$EEEEED,$FF0000,2,Tahoma,10
             RightSeparatorKeyColor0=Text to show,$EEEEEE,$EEEEED,$FF0000,2,Tahoma,10
   Indice 0 may be variable name (not array) into single quotes like '$w_restartDNS'
            may be array index without simple quotes in index like
            '$w_settings[DaredevilOptions]' or '$myarray[56]' */
$AesSeparatorLeftMenuColor = array(
	array('Wampserver -','$F2A626','$C57F0B','$FFFFFF',0,'Arial',11),
	array('$w_noDBMS','$F1F1F1','$F1F1F0','$000000',2,'Arial',10),
	array('$w_NoDefaultDBMS','$BBBBFF','$BBBBFE','$000000',0,'Arial',10),
	array('$w_warning','$BBBBFF','$BBBBFE','$000000',0,'Arial',10),
);
$AesSeparatorRightMenuColor = array(
	array('Wampserver -','$F2A626','$C57F0B','$FFFFFF',0,'Arial',11),
);

/* PromptCustom -> Aestan Tray Menu Variable type prompt since 3.2.3.0
   Section [PromptCustom] Name PromptKeyx=
   Indices = 0 : Prompt Name, 1 : Font Size, 2 : Background Color
             3 : Font Color,  4 : Value BackGround Color, 5 : Value Text Color
   Example : PromptKey1=Prompt1,12,$D77800,$FCFDFE,$FFFFFF,$0000FF
   PromptKey0 are default values for all Prompt
   PromptKey0=Default,12,$EEEEEE,$000000
   Indice 0 may be variable name (not array) into single quotes like '$w_restartDNS' */
$AesPromptCustom = array(
	array('Default',10,'$EEEEEE','$000000','$FFFFFF','$0000FF'),
	array('MariaUser',10,'$FFFFF0','$890000','$FFFFFF','$0000FF'),
	array('MysqlUser',10,'$FFFFF0','$890000','$FFFFFF','$0000FF'),
);

?>
