<?php

if(!defined('WAMPTRACE_PROCESS')) require 'config.trace.php';
if(WAMPTRACE_PROCESS) {
	$errorTxt = "script ".__FILE__;
	$iw = 1; while(!empty($_SERVER['argv'][$iw])) {$errorTxt .= " ".$_SERVER['argv'][$iw];$iw++;}
	require_once 'start_time.php';
	$errorTxt .= ' - Elapsed time='.(microtime(true)-$start_time);
	error_log($errorTxt."\n",3,WAMPTRACE_FILE);
}

require 'config.inc.php';
require 'wampserver.lib.php';
$WampStartOnOri = IntlDateFormatter::formatObject(new DateTime('now',new DateTimeZone(date_default_timezone_get())),'Y-MM-d HH:mm:ss');
$wampIniNewContents['wampStartDate'] = $WampStartOnOri;
wampIniSet($configurationFile, $wampIniNewContents);

?>