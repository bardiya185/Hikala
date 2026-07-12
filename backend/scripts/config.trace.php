<?php

define('WAMPTRACE_PROCESS', false);

if(WAMPTRACE_PROCESS) {
	if(!file_exists('start_time.php')) {
		$start_time = microtime(true);
		file_put_contents('start_time.php','<?php'."\n".'$start_time = '.var_export($start_time, true).';'."\n?>\n");
		$time_text = 'Start time='.$start_time;
		memory_reset_peak_usage();
	}
	else {
		require_once 'start_time.php';
		$time_text = 'Elapsed time='.((microtime(true)-$start_time));
	}
	if(!defined('WAMPTRACE_FILE')) {
		$wampConf = @parse_ini_file('../wampmanager.conf');
		define('WAMPTRACE_FILE', $wampConf['installDir']."/logs/wamptrace.log");
		//Create file with datetime in first line
		$fp = fopen(WAMPTRACE_FILE, "ab");
		fwrite($fp,"- Wampserver trace report - ".date(DATE_RSS)."\n");
		fclose($fp);
		unset($wampConf,$fp);
	}
	error_log("script ".__FILE__." WAMPTRACE_FILE=".WAMPTRACE_FILE." ".$time_text."\n",3,WAMPTRACE_FILE);
}
else {
	if(file_exists('start_time.php')) unlink('start_time.php');
}

?>
