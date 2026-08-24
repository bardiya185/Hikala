<?php


$i = 0;
/* The 'cookie' auth_type uses AES algorithm to encrypt the password. If
 * at least one server configuration uses 'cookie' auth_type, enter here a
 * pass phrase that will be used by AES. The minimum length is 32 characters
 * The maximum length seems to be 46 characters. */
$cfg['blowfish_secret'] = 'h]C+{nqW$omNoTIkCwC$%z-LTcy%p6_j';
$wampConf = @parse_ini_file('../../wampmanager.conf');
$mariaFirst = ($wampConf['SupportMySQL'] == 'on' && $wampConf['SupportMariaDB'] == 'on' && $wampConf['mariaPortUsed'] == $wampConf['mysqlDefaultPort']) ? true : false;
if($wampConf['SupportMySQL'] == 'on') {

	$i++;
	if($mariaFirst) $i++;
	$cfg['Servers'][$i]['verbose'] = 'MySQL';
	$cfg['Servers'][$i]['host'] = '127.0.0.1';
	$cfg['Servers'][$i]['port'] = $wampConf['mysqlPortUsed'];
	$cfg['Servers'][$i]['extension'] = 'mysqli';
	$cfg['Servers'][$i]['auth_type'] = 'cookie';
	$cfg['Servers'][$i]['user'] = '';
	$cfg['Servers'][$i]['password'] = '';
	$cfg['Servers'][$i]['hide_db'] = '(information_schema|mysql|performance_schema|sys)';
	$cfg['Servers'][$i]['AllowNoPassword'] = true;
}

if($wampConf['SupportMariaDB'] =='on') {
	$i++;
	if($mariaFirst) $i -= 2;
	$cfg['Servers'][$i]['verbose'] = 'MariaDB';
	$cfg['Servers'][$i]['host'] = '127.0.0.1';
	$cfg['Servers'][$i]['port'] = $wampConf['mariaPortUsed'];
	$cfg['Servers'][$i]['extension'] = 'mysqli';
	$cfg['Servers'][$i]['auth_type'] = 'cookie';
	$cfg['Servers'][$i]['user'] = '';
	$cfg['Servers'][$i]['password'] = '';
	$cfg['Servers'][$i]['hide_db'] = '(information_schema|mysql|performance_schema|sys)';
	$cfg['Servers'][$i]['AllowNoPassword'] = true;
}
$cfg['PmaNoRelation_DisableWarning'] = true;
$cfg['Export']['sql_drop_table'] = true;
$cfg['Export']['sql_if_not_exists'] = true;

$cfg['MySQLManualBase'] = 'http://dev.mysql.com/doc/refman/5.7/en/';


?>
