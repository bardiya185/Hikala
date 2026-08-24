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

$newApacheVersion = $_SERVER['argv'][1];
$apacheNew = $newApacheVersion;
$apacheOld = $c_apacheVersion;
$compareOnly = false;
if(!empty($_SERVER['argv'][2]) && !empty($_SERVER['argv'][3]) && trim($_SERVER['argv'][3]) == 'compare') {
	$apacheOld = $_SERVER['argv'][2];
	$compareOnly = true;
}

if(!$compareOnly) {
	require $c_phpVersionDir.'/php'.$wampConf['phpVersion'].'/'.$wampBinConfFiles;
	$newApacheVersionTemp = $newApacheVersion;
	while (!isset($phpConf['apache'][$newApacheVersionTemp]) && $newApacheVersionTemp != '')
	{
	    $pos = strrpos($newApacheVersionTemp,'.');
	    $newApacheVersionTemp = substr($newApacheVersionTemp,0,$pos);
	}
	if($newApacheVersionTemp == '')
	{
	    exit();
	}
	$wampIniNewContents = array();
	if($wampConf['apacheCompareVersion'] == 'on') {
		$wampIniNewContents['apacheCompareVersion'] = 'off';
		$wampConf['apacheCompareVersion'] = 'off';
	}
	if($wampConf['apacheRestoreFiles'] == 'on') {
		$wampIniNewContents['apacheRestoreFiles'] = 'off';
		$wampConf['apacheRestoreFiles'] = 'off';
	}
	if(count($wampIniNewContents) > 0) {
		wampIniSet($configurationFile, $wampIniNewContents);
	}
	require $c_apacheVersionDir.'/apache'.$newApacheVersion.'/'.$wampBinConfFiles;
}
if($apacheNew != $apacheOld) {
	$majTodo = $majModules = $majIncludes = $majVhost = $majHttpdssl = $majOpenssl = $majListen = $majDefaultListen = false;
	$majModulesGo = $majIncludesGo = $majVhostGo = $majHttpdsslGo = $majOpensslGo = $majListenGo = $majDefaultListenGo = false;
	$fp = fopen($c_installDir.'/bin/apache/save_apache.php', 'wb');
	fwrite($fp, "<?php\n\n");
	$apacheConfFile = $c_apacheVersionDir.'/apache'.$apacheOld.'/'.$wampConf['apacheConfDir'].'/'.$wampConf['apacheConfFile'];
	$httpdFileContents = @file_get_contents($apacheConfFile);
	preg_match_all('~^LoadModule\s+([0-9a-z_]+\s+modules/.+)\r?$~im',$httpdFileContents,$matchesON);
	preg_match_all('~^\#LoadModule\s+([0-9a-z_]+\s+modules/.+)\r?$~im',$httpdFileContents,$matchesOFF);
	$mod = array_fill_keys($matchesON[1], '1') + array_fill_keys($matchesOFF[1], '0');
	ksort($mod);
	fwrite($fp, "\$modules_apache_old = ".var_export($mod, true).";\n\n");
	preg_match_all('~^Include\s+(conf/.+)\r?$~im',$httpdFileContents,$matchesON);
	preg_match_all('~^\#Include\s+(conf/.+)\r?$~im',$httpdFileContents,$matchesOFF);
	$includes = array_fill_keys($matchesON[1], '1') + array_fill_keys($matchesOFF[1], '0');
	ksort($includes);
	fwrite($fp, "\$includes_apache_old = ".var_export($includes, true).";\n\n");
	preg_match('~^ServerName\s+localhost:([0-9]{2,5})~im',$httpdFileContents,$matches);
	$oldDefaultListenPort = $matches[1];
	unset($httpdFileContents);
	$newListenPort = $oldListenPort = array();
	$c_apacheDefineConf = $c_apacheVersionDir.'/apache'.$apacheOld.'/wampdefineapache.conf';
	$c_ApacheDefine = retrieve_apache_define($c_apacheDefineConf);
	$oldListenPort = listen_ports($apacheConfFile);
	foreach($oldListenPort as $key => $value) {
		if(strpos($value,'MYPORT') !== false) {
			$value = str_replace(array('${MYPORT','}'),'',$value);
			$oldListenPort[$key] = $value;
		}
	}
	$apacheConfFile = $c_apacheVersionDir.'/apache'.$apacheNew.'/'.$wampConf['apacheConfDir'].'/'.$wampConf['apacheConfFile'];
	$httpdFileContents = @file_get_contents($apacheConfFile);
	preg_match_all('~^LoadModule ([0-9a-z_]+ modules/.+)\r?$~im',$httpdFileContents,$matchesON);
	preg_match_all('~^\#LoadModule ([0-9a-z_]+ modules/.+)\r?$~im',$httpdFileContents,$matchesOFF);
	$mod = array_fill_keys($matchesON[1], '1') + array_fill_keys($matchesOFF[1], '0');
	ksort($mod);
	fwrite($fp, "\$modules_apache_new = ".var_export($mod, true).";\n\n");
	preg_match_all('~^Include (conf/.+)\r?$~im',$httpdFileContents,$matchesON);
	preg_match_all('~^\#Include (conf/.+)\r?$~im',$httpdFileContents,$matchesOFF);
	$includes = array_fill_keys($matchesON[1], '1') + array_fill_keys($matchesOFF[1], '0');
	ksort($includes);
	fwrite($fp, "\$includes_apache_new = ".var_export($includes, true).";\n\n");
	fwrite($fp, "?>\n");
	fclose($fp);
	preg_match('~^ServerName\s+localhost:([0-9]{2,5})~im',$httpdFileContents,$matches);
	$newDefaultListenPort = $matches[1];
	unset($httpdFileContents);
	$c_apacheDefineConf = $c_apacheVersionDir.'/apache'.$apacheNew.'/wampdefineapache.conf';
	$c_ApacheDefine = retrieve_apache_define($c_apacheDefineConf);
	$newListenPort = listen_ports($apacheConfFile);
	foreach($newListenPort as $key => $value) {
		if(strpos($value,'MYPORT') !== false) {
			$value = str_replace(array('${MYPORT','}'),'',$value);
			$newistenPort[$key] = $value;
		}
	}
	if($apacheNew <> $c_apacheVersion) {
		$c_apacheDefineConf = $c_apacheVersionDir.'/apache'.$c_apacheVersion.'/wampdefineapache.conf';
		$c_ApacheDefine = retrieve_apache_define($c_apacheDefineConf);
	}
	if($newDefaultListenPort <> $oldDefaultListenPort) {
		$majTodo = $majDefaultListen = true;
	}
	$moduleDiff = $includeDiff = array();
	$apacheNewConfFile = $c_apacheVersionDir.'/apache'.$apacheNew.'/'.$wampConf['apacheConfDir'].'/'.$wampConf['apacheConfFile'];
	$httpdNewFileContents = @file_get_contents($apacheNewConfFile);
	include $c_installDir.'/bin/apache/save_apache.php';
	$count = 0;
	$FindModuleTxt = $ReplaceModuleTxt = array();
	foreach($modules_apache_old as $key => $value) {
		if(array_key_exists($key, $modules_apache_new)) {
			if($modules_apache_new[$key] <> $value) {
				$majTodo = $majModules = true;
				if($value == 1) {//Load module
					$FindModuleTxt[]  = '#LoadModule '.$key;
					$ReplaceModuleTxt[]  = 'LoadModule '.$key;
					$moduleDiff[$key] = false;
				}
				else {//Don't load module
					$FindModuleTxt[]  = 'LoadModule '.$key;
					$ReplaceModuleTxt[]  = '#LoadModule '.$key;
					$moduleDiff[$key] = true;
				}
			}
		}
	}
	$FindIncludeTxt = $ReplaceIncludeTxt = array();
	foreach($includes_apache_old as $key => $value) {
		if(array_key_exists($key,$includes_apache_new)) {
			if($includes_apache_new[$key] <> $value) {
				$majTodo = $majIncludes = true;
				if($value == 1) {//Include
					$FindIncludeTxt[]  = '#Include '.$key;
					$ReplaceIncludeTxt[]  = 'Include '.$key;
					$includeDiff[$key] = false;
				}
				else {//Don't Include
					$FindIncludeTxt[]  = 'Include '.$key;
					$ReplaceIncludeTxt[]  = '#Include '.$key;
					$includeDiff[$key] = true;
				}
			}
		}
	}
	unlink($c_installDir.'/bin/apache/save_apache.php');
	$oldVhost = $c_apacheVersionDir.'/apache'.$apacheOld.'/'.$wampConf['apacheConfDir'].'/extra/httpd-vhosts.conf';
	$newVhost = $c_apacheVersionDir.'/apache'.$apacheNew.'/'.$wampConf['apacheConfDir'].'/extra/httpd-vhosts.conf';
	$content1 = file($oldVhost, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$content2 = file($newVhost, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$nbVhostOld = count($content1);
	$nbVhostNew = count($content2);
	$lineDiffVhost = false;
	if(abs($nbVhostOld - $nbVhostNew) > 3) {
		$majTodo = $majVhost = true;
	}
	else {
		$oldLineDiffVhost = $newLineDiffVhost = array();
		$count = 0;
		reset($content2);
		foreach($content1 as $key => $value) {
			$value2 = current($content2);
			if($value <> $value2) {
				$oldLineDiffVhost[$key] = $value;
				$newLineDiffVhost[$key] = $value2;
				$majTodo = $majVhost = $lineDiffVhost = true;
				if($count++ > 3) break;
			}
			next($content2);
		}
	}
	unset($content1,$content2);
	$oldSslConf = $c_apacheVersionDir.'/apache'.$apacheOld.'/'.$wampConf['apacheConfDir'].'/extra/httpd-ssl.conf';
	$newSslConf = $c_apacheVersionDir.'/apache'.$apacheNew.'/'.$wampConf['apacheConfDir'].'/extra/httpd-ssl.conf';
	$content1 = file($oldSslConf, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$content2 = file($newSslConf, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$nbSslOld = count($content1);
	$nbSslNew = count($content2);
	$lineDiffSsl = false;
	if(abs($nbSslOld - $nbSslNew) > 3) {
		$majTodo = $majHttpdssl = true;
	}
	else {
		$oldLineDiffSsl = $newLineDiffSsl = array();
		$count = 0;
		reset($content2);
		foreach($content1 as $key => $value) {
			$value2 = current($content2);
			if($value <> $value2) {
				$oldLineDiffSsl[$key] = $value;
				$newLineDiffSsl[$key] = $value2;
				$majTodo = $majHttpdssl = $lineDiffSsl = true;
				if($count++ > 3) break;
			}
			next($content2);
		}
	}
	unset($content1,$content2);
	$oldOpenssl = $c_apacheVersionDir.'/apache'.$apacheOld.'/'.$wampConf['apacheConfDir'].'/openssl.cnf';
	$newOpenssl = $c_apacheVersionDir.'/apache'.$apacheNew.'/'.$wampConf['apacheConfDir'].'/openssl.cnf';
	$content1 = file($oldOpenssl, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$content2 = file($newOpenssl, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$nbOpenOld = count($content1);
	$nbOpenNew = count($content2);
	$lineDiffOpen = false;
	if(abs($nbOpenOld - $nbOpenNew) > 3) {
		$majTodo = $majOpenssl = true;
	}
	else {
		$oldLineDiffOpen = $newLineDiffOpen = array();
		$count = 0;
		reset($content2);
		foreach($content1 as $key => $value) {
			$value2 = current($content2);
			if($value <> $value2) {
				$oldLineDiffOpen[$key] = $value;
				$newLineDiffOpen[$key] = $value2;
				$majTodo = $majOpenssl = $lineDiffOpen = true;
				if($count++ > 3) break;
			}
			next($content2);
		}
	}
	unset($content1,$content2);

	/*Compare Certificats if exist
	$oldDirCerts = $c_apacheVersionDir.'/apache'.$apacheOld.'/'.$wampConf['apacheConfDir'].'/Certs';
	$newDirCerts = $c_apacheVersionDir.'/apache'.$apacheNew.'/'.$wampConf['apacheConfDir'].'/Certs';
	$oldDirCertsAnti = str_replace('/','\\',$oldDirCerts);
	$newDirCertsAnti = str_replace('/','\\',$newDirCerts);
	function short_path(&$item,$key){
		global $oldDirCerts,$newDirCerts;
		$item = str_ireplace($oldDirCerts.'/','',$item);
		$item = str_ireplace($newDirCerts.'/','',$item);
	}

	$files1 = $files2 = array();
	$CertsOld = $CertsNew = true;
	if(is_dir($oldDirCerts)) {
		$files1 = read_dir($oldDirCerts);
		array_walk($files1,'short_path');
	}
	else {
		$CertsOld = false;
	}
	if(is_dir($newDirCerts)) {
		$files2 = read_dir($newDirCerts);
		array_walk($files2,'short_path');
	}
	else {
		$CertsNew = false;
	}
	if($CertsNew !== $CertsOld) $majTodo = $majCerts = true;
	$notCertsNew = $notCertsOld = array();
	if($CertsNew && $CertsOld){
		$notCertsNew = array_diff($files1, $files2);
		$notCertsOld = array_diff($files2, $files1);
		if(count($notCertsNew) > 0) {
			$majTodo = $majCerts = true;
		}
		if(count($notCertsOld) > 0) {
			$majTodo = $majCerts = true;
		}
	}
	unset($files1,$files2);
	*/
	$nbListenOld = count($oldListenPort);
	$nbListenNew = count($newListenPort);
	$notListenNew = $notListenOld = array();
	if($nbListenOld <> $nbListenNew) {
		$majTodo = $majListen = true;
		foreach($oldListenPort as $value) {
			if(!in_array($value,$newListenPort)) {
				$notListenNew[] = $value;
			}
		}
		foreach($newListenPort as $value) {
			if(!in_array($value,$oldListenPort)) {
				$notListenOld[] = $value;
			}
		}
	}
	if($majTodo) {
		$YESred = color('red','YES');
		$NOgreen = color('green','NO');
		generatemessage:
		$message = str_repeat('-',86)."\n";
		$message = color('blue');
		if($compareOnly) {
			$message .= str_repeat(' ',9)."COMPARISON OF SETTINGS BETWEEN CURRENT APACHE ".$apacheNew." AND APACHE ".$apacheOld."\n";
		}
		else {
			$message .= str_repeat(' ',9)."SWITCH APACHE VERSIONS - FROM CURRENT APACHE ".$apacheOld." TO APACHE ".$apacheNew."\n";
		}
		$message .= str_repeat(' ',12).color('red')."There are differences between Apache ".$apacheNew." and Apache ".$apacheOld.color('black')."\n";
		$message .= str_repeat('-',86)."\n";
		if($majModules) {
			$message .= str_pad("  *** -> LoadModule",48).str_pad("Key 'M' for ".($majModulesGo ? 'NO' : 'YES'),16)."- Requested update ".($majModulesGo ? $YESred : $NOgreen)."\n";
			$message .= str_pad("   Module",30).str_pad($apacheNew,12).$apacheOld."\n";
			foreach($moduleDiff as $key => $value) {
				$key0 = explode(' ',$key);
				$temp = str_pad(trim($key0[0]),30).($value ? str_pad("loaded",12) : str_pad("not loaded",12));
				$temp .= ($value ? "not loaded " : "loaded");
				$message .= $temp."\n";
			}
			$message .= str_repeat('-',86)."\n";
		}
		if($majIncludes) {
			$message .= str_pad("  *** -> Include",48).str_pad("Key 'I' for ".($majIncludesGo ? 'NO' : 'YES'),16)."- Requested update ".($majIncludesGo ? $YESred : $NOgreen)."\n";
			$message .= str_pad("   Include",30).str_pad($apacheNew,12).$apacheOld."\n";
			foreach($includeDiff as $key => $value) {
				$key = str_replace('conf/extra/','',trim($key));
				$temp = str_pad($key,30).($value ? str_pad("loaded",12) : str_pad("not loaded",12));
				$temp .= ($value ? "not loaded " : "loaded");
				$message .= $temp."\n";
			}
			$message .= str_repeat('-',86)."\n";
		}
		if($majVhost) {
			$message .= str_pad("  *** -> httpd-vhosts.conf",48).str_pad("Key 'V' for ".($majVhostGo ? 'NO' : 'YES'),16)."- Requested update ".($majVhostGo ? $YESred : $NOgreen)."\n";
			$message .= str_pad(" ",30).str_pad($apacheNew,12).$apacheOld."\n";
			$message .= str_pad("Number of lines",30).str_pad($nbVhostNew,12).$nbVhostOld."\n";
			if(($nbVhostNew == $nbVhostOld) || $lineDiffVhost) {
				$message .= str_repeat(' ',22)."At least one line is different\n";
				if($lineDiffVhost) {
					reset($oldLineDiffVhost);
					foreach($newLineDiffVhost as $key => $value) {
						$value2 = current($oldLineDiffVhost);
						$message .= str_pad("Line ".$key,10).str_pad($apacheNew,8)." : ".$value."\n".str_pad(' ',10).str_pad($apacheOld,8)." : ".$value2."\n";
						next($oldLineDiffVhost);
					}
				}
			}
		$message .= str_repeat('-',86)."\n";
		}
		if($majDefaultListen){
			$message .= str_pad("  *** -> Default Port used by Apache",48).str_pad("Key 'P' for ".($majDefaultListenGo ? 'NO' : 'YES'),16)."- Requested update ".($majDefaultListenGo ? $YESred : $NOgreen)."\n";
			$message .= str_pad(" ",30).str_pad($apacheNew,12).$apacheOld."\n";
			$message .= str_pad("Port",30).str_pad($newDefaultListenPort,12).$oldDefaultListenPort."\n";
			if(!$compareOnly){
				if($majVhost) $message .= color('blue')."  If the only difference between the httpd-vhosts.conf files\n   is the port number ".$c_DefaultPort." versus ".$c_UsedPort.color('black')."\n";
				$message .= color('blue')."   the default Apache listening port will be automatically updated\n   by this Apache version change procedure.".color('black')."\n";
			}
			$message .= str_repeat('-',86)."\n";
		}
		if($majHttpdssl) {
			$message .= str_pad("  *** -> httpd-ssl.conf",48).str_pad("Key 'H' for ".($majHttpdsslGo ? 'NO' : 'YES'),16)."- Requested update ".($majHttpdsslGo ? $YESred : $NOgreen)."\n";
			$message .= str_pad(" ",30).str_pad($apacheNew,12).$apacheOld."\n";
			$message .= str_pad("Number of lines",30).str_pad($nbSslNew,12).$nbSslOld."\n";
			if(($nbSslNew == $nbSslOld) || $lineDiffSsl) {
				$message .= str_repeat(' ',22)."At least one line is different\n";
				if($lineDiffSsl) {
					reset($oldLineDiffSsl);
					foreach($newLineDiffSsl as $key => $value) {
						$value2 = current($oldLineDiffSsl);
						$message .= str_pad("Line ".$key,10).str_pad($apacheNew,8)." : ".$value."\n".str_pad(' ',10).str_pad($apacheOld,8)." : ".$value2."\n";
						next($oldLineDiffSsl);
					}
				}
			}
			$message .= str_repeat('-',86)."\n";
		}
		if($majOpenssl) {
			$message .= str_pad("  *** -> openssl.cnf",48).str_pad("Key 'O' for ".($majOpensslGo ? 'NO' : 'YES'),16)."- Requested update ".($majOpensslGo ? $YESred : $NOgreen)."\n";
			$message .= str_pad(" ",30).str_pad($apacheNew,12).$apacheOld."\n";
			$message .= str_pad("Number of lines",30).str_pad($nbOpenNew,12).$nbOpenOld."\n";
			if(($nbOpenNew == $nbOpenOld) || $lineDiffOpen) {
				$message .= str_repeat(' ',22)."At least one line is different\n";
				if($lineDiffOpen) {
					reset($oldLineDiffOpen);
					foreach($newLineDiffOpen as $key => $value) {
						$value2 = current($oldLineDiffOpen);
						$message .= str_pad("Line ".$key,10).str_pad($apacheNew,8)." : ".$value."\n".str_pad(' ',10).str_pad($apacheOld,8)." : ".$value2."\n";
						next($oldLineDiffOpen);
					}
				}
			}
			$message .= str_repeat('-',86)."\n";
		}
		$listenToAdd = $listenToDel = false;
		if($majListen) {
			$message .= str_pad("  *** -> Listen Ports",48).str_pad("Key 'L' for ".($majListenGo ? 'NO' : 'YES'),16)."- Requested update ".($majListenGo ? $YESred : $NOgreen)."\n";
			$message .= str_pad(" ",10).$apacheNew." : ".implode(" - ",$newListenPort)."\n";
			if(!empty($notListenNew)) {
				$listenToAdd = true;
				$message .= str_pad(" ",19)."LISTEN PORT TO ADD : ".implode(" - ",$notListenNew)."\n";
			}
			if(!empty($notListenOld)) {
				$listenToDel = true;
				$message .= str_pad(" ",19)."LISTEN PORT TO DELETE : ".implode(" - ",$notListenOld)."\n";
			}
			$message .= str_pad(" ",10).$apacheOld." : ".implode(" - ",$oldListenPort)."\n";
			$message .= str_repeat('-',86)."\n";
		}
		$message .= "    Do you want to copy or update configured files\n";
		$message .= "    ".color('red','FROM Apache '.$apacheOld)." -> to Apache ".$apacheNew."\n\n";
		$message .= "For       ".color('blue','ALL')."      updates press ".color('blue',"'A'")." key then Enter key\n";
		$message .= "For       ".color('blue','RESET')."    choice press  ".color('blue',"'R'")." key then Enter key\n";
		$message .= "To        ".color('blue',"CANCEL")."   updates, press only ".color('blue',"Enter")." key\n";
		$message .= "When your ".color('blue',"CHOICE is READY")." press  ".color('blue',"'G'")." key then Enter key\n";
		$message .= "To choose one or more updates, press the associated key then Enter key: ";
		Command_Windows($message,-1,-1,0,'Compare Apache version');
		$touche = mb_strtoupper(trim(fgets(STDIN)));
		if($touche == 'A') {
			$majModulesGo = $majIncludesGo = $majVhostGo = $majHttpdsslGo = $majOpensslGo = $majListenGo = $majDefaultListenGo = true;
			goto generatemessage;
		}
		elseif($touche == 'R') {
			$majModulesGo = $majIncludesGo = $majVhostGo = $majHttpdsslGo = $majOpensslGo = $majListenGo = $majDefaultListen = false;
			goto generatemessage;
		}
		elseif($touche == 'M') {
			$majModulesGo = ($majModulesGo ? false : true);
			goto generatemessage;
		}
		elseif($touche == 'I') {
			$majIncludesGo = ($majIncludesGo ? false :true);
			goto generatemessage;
		}
		elseif($touche == 'V') {
			$majVhostGo = ($majVhostGo ? false : true);
			goto generatemessage;
		}
		elseif($touche == 'P') {
			$majDefaultListenGo = ($majDefaultListenGo ? false : true);
			goto generatemessage;
		}
		elseif($touche == 'H') {
			$majHttpdsslGo = ($majHttpdsslGo ? false : true);
			goto generatemessage;
		}
		elseif($touche == 'O') {
			$majOpensslGo = ($majOpensslGo ? false : true);
			goto generatemessage;
		}
		elseif($touche == 'L') {
			$majListenGo = ($majListenGo ? false : true);
			goto generatemessage;
		}
		elseif($touche == 'G') {}
		else{
		$majTodo = $majModules = $majIncludes = $majVhost = $majHttpdssl = $majOpenssl = $majListen = $majDefaultListen = false;
		$majModulesGo = $majIncludesGo = $majVhostGo = $majHttpdsslGo = $majOpensslGo = $majListenGo = $majDefaultListenGo = false;
		}
	}
	elseif($compareOnly){
		$message = str_repeat('-',46)."\n";
		$message .= "  *** There are no configuration differences\n";
		$message .= "  *** between Apache ".$apacheOld." and Apache ".$apacheNew."\n";
		$message .= "  *** There is no need to update anything\n";
		$message .= "Press Enter key to continue ";
		Command_Windows($message,-1,-1,0,'Compare Apache version');
		$touche = mb_strtoupper(trim(fgets(STDIN)));
	}
	$copyConf = $FileToWrite = false;
	if($majTodo) {
		if($majModules &&$majModulesGo) {
			$httpdNewFileContents = str_replace($FindModuleTxt,$ReplaceModuleTxt,$httpdNewFileContents,$count);
			if($count > 0) $FileToWrite = true;
		}
		if($majIncludes && $majIncludesGo) {
			$httpdNewFileContents = str_replace($FindIncludeTxt,$ReplaceIncludeTxt,$httpdNewFileContents,$count);
			if($count > 0) $FileToWrite = true;
		}
		if($majListen && $majListenGo) {
			if($listenToAdd) {
				foreach($notListenNew as $value) {
					if($value <= 80 || $value == 8080 || ($value > 81 && $value < 1025) || $value > 65535) continue;
					$count = 0;
					$search = array(
						"~^([ \t]*Define[ \t]+APACHE_DIR[ \t]+.*)\s?$~m",
						"~^([ \t]*Listen[ \t]+\[::0\]:".$c_UsedPort.")\s?$~m",
					);
					$replace = array (
						'${1}'."\r\n".'Define MYPORT'.$value.' '.$value,
						'${1}'."\r\n".'Listen 0.0.0.0:${MYPORT'.$value.'}'."\r\n".'Listen [::0]:${MYPORT'.$value.'}',
					);
					$httpdNewFileContents = preg_replace($search,$replace,$httpdNewFileContents, -1, $count);
					if($count == 2) $FileToWrite = true;
				}
			}
			if($listenToDel) {
				foreach($notListenOld as $value) {
					if($value <= 80 || $value == 8080 || ($value > 81 && $value < 1025) || $value > 65535) continue;
					$count = 0;
					$search = array(
						"~^Define[ \t]+MYPORT".$value."[ \t]+.*\s?$~m",
						"~^Listen[ \t]+.*MYPORT".$value.".*\s?$~m",
					);
					$replace = array (
						'',
						'',
					);
					$httpdNewFileContents = preg_replace($search,$replace,$httpdNewFileContents, -1, $count);
					if($count == 3) $FileToWrite = true;
				}
			}

		}//end of MajListen
		if($majDefaultListenGo){
			$findTxtRegex = array(
			'/^(Listen 0.0.0.0:)[0-9]{2,5}/m',
			'/^(Listen \[::0\]:)[0-9]{2,5}/m',
			'/^(ServerName localhost:)[0-9]{2,5}/m',
			);
			$search = $replace = array();
			foreach($findTxtRegex as $value) {
				if(preg_match_all($value,$httpdNewFileContents,$matches,PREG_SET_ORDER) > 0) {
					foreach($matches as $key => $value) {
						if($value[0] <> $value[1].$oldDefaultListenPort) {
							$search[] = $value[0];
							$replace[] = $value[1].$oldDefaultListenPort;
						}
					}
				}
			}
			if(count($search) > 0) {
				$httpdNewFileContents = str_replace($search,$replace,$httpdNewFileContents,$count);
				if($count > 0) $FileToWrite = true;
			}
			$virtualHost = check_virtualhost(true);
			if($virtualHost['include_vhosts'] && $virtualHost['vhosts_exist']) {
				$c_vhostConfFile = $virtualHost['vhosts_file'];
				$myVhostsContents = file_get_contents($c_vhostConfFile) or die ("httpd-vhosts.conf file not found");
				$findTxtRegex = '/^([ \t]*<VirtualHost[ \t]+.+:)[0-9]{2,5}>/m';
				$replaceTxtRegex = '${1}'.$oldDefaultListenPort.'>';

				$myVhostsContents = preg_replace($findTxtRegex,$replaceTxtRegex, $myVhostsContents, -1, $count);
				if($count > 0) write_file($c_vhostConfFile,$myVhostsContents);
			}
			$apacheConf['apachePortUsed'] = $oldDefaultListenPort;
			if($oldDefaultListenPort == $c_DefaultPort) {
				$apacheConf['apacheUseOtherPort'] = "off";
			}
			else {
				$apacheConf['apacheUseOtherPort'] = "on";
			}
			wampIniSet($configurationFile, $apacheConf);
		}
		if($FileToWrite) {
			write_file($apacheNewConfFile,$httpdNewFileContents);
			$copyConf = true;
		}
		unset($httpdNewFileContents);
		if($majVhost && $majVhostGo) {
			if(copy($oldVhost,$newVhost) === false) {
				error_log("**** Copy error ****\n".$oldVhost."\nto\n".$newVhost."\n");
			}
			else $copyConf = true;
		}
		if($majHttpdssl && $majHttpdsslGo) {
			if(copy($oldSslConf,$newSslConf) === false) {
				error_log("**** Copy error ****\n".$oldSslConf."\nto\n".$newSslConf."\n");
			}
			else $copyConf = true;
		}
		if($majOpenssl && $majOpensslGo) {
			if(copy($oldOpenssl,$newOpenssl) === false) {
				error_log("**** Copy error ****\n".$oldOpenssl."\nto\n".$newOpenssl."\n");
			}
			else $copyConf = true;
		}
	}
}

if(!$compareOnly) {
	$apacheConf['apacheVersion'] = $newApacheVersion;
	wampIniSet($configurationFile, $apacheConf);
}

?>