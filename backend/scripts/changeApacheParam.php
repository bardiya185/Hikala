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

$ApacheHttpdContents = @file_get_contents($c_apacheConfFile) or die ("httpd.conf file not found");

$changeError = '';
$parameter = $new_parameter = $_SERVER['argv'][1];
$value = $_SERVER['argv'][2];
$newvalue = $_SERVER['argv'][3];
$action = $_SERVER['argv'][4];

$count = 0;
if($action == 'uncomment') {
	$ApacheHttpdContents = preg_replace('~^[ \t]*#[ \t]*'.$parameter.'[ \t]+'.$value.'~m',$new_parameter.' '.$value,$ApacheHttpdContents, -1, $count);
}
elseif($action == 'change') {
	$ApacheHttpdContents = preg_replace('~'.$parameter.'[ \t]+'.$value.'~m',$new_parameter.' '.$newvalue,$ApacheHttpdContents, -1, $count);
}

//error_log("parameter=".$parameter."| - new parameter=".$new_parameter."| value=".$value."| newvalue=".$newvalue."| action=".$action."| count=".$count);

if($count > 0) {
	write_file($c_apacheConfFile,$ApacheHttpdContents);
}

if(!empty($changeError)) {
	$message = color('red',"********************* WARNING ********************\n\n");
	$message .= $changeError;
	$message .= "\nPress ENTER to continue...";
	Command_Windows($message,-1,-1,0,'Change PHP parameter');
  trim(fgets(STDIN));
}

?>