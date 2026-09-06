<?php
// Latvie�u valodas fails WAMP dom�nam
// Apak�izv�lnes Projekti un VirtualHosts
// Iestat�jumi un r�ki >> peles lab�s pogas klik��is uz WAMP ikonas
// 3.1.4 $w_settings 'NotVerifyTLD' 'T�r��ana' 'AutoCleanLogs' 'AutoCleanLogsMax' 'AutoCleanLogsMax' 'AutoCleanTmp' 'AutoCleanTmpMax' 'iniCommented'
// 3.2.2 $w_MysqlMariaUser un $w_EnterSize modific�ts - - $w_MySQLsqlmodeInfo $w_mysql_mode $w_phpMyAdminHelp $ w_PhpMyAdMinHelpTxt

$w_translated_by = "Latvie�u valod� tulkojis MariOzo" 
// Projektu apak�izv�lne
$w_projectsSubMenu = 'Projekti';
// VirtualHosts apak�izv�lne
$w_virtualHostsSubMenu = 'J�su VirtualHosts';
$w_add_VirtualHost = 'VirtualHost p�rvald�ba';
$w_aliasSubMenu = 'Aizst�jv�rdi';
$w_portUsed = 'Apache izmantotais ports:';
$w_portUsedMysql = 'MySQL izmantotais ports:';
$w_portUsedMaria = 'MariaDB izmantotais ports:';
$w_testPortUsed = 'Izmantotais testa ports:';
$w_portForApache = 'Apache ports';
$w_listenForApache = 'Klaus��an�s ports, ko pievienot Apache';
$w_portForMysql = 'MySQL ports';
$w_testPortMysql = 'P�rbaudes ports 3306';
$w_testPortMysqlUsed = 'P�rbaudiet izmantoto MySQL portu:';
$w_testPortMariaUsed = 'P�rbaudiet izmantoto MariaDB portu:';


// Lab�s peles pogas izv�lnes
$w_wampSettings = 'Wamp iestat�jumi';
$w_settings = array(
	'urlAddLocalhost' => 'Pievienot viet�jo hostu vietr�d� URL',
	'VirtualHostSubMenu' => 'VirtualHosts apak�izv�lne',
	'AliasSubmenu' => 'Apak�izv�lne Alias',
	'ProjectSubMenu' => 'Projektu apak�izv�lne',
	'HomepageAtStartup' => 'Wampserver m�jas lapa start�jot',
	'MenuItemOnline' => 'Izv�lnes vienums: tie�saist� / bezsaist�',
	'ItemServicesNames' => 'Izv�lnes R�ki: pakalpojumu nosaukumu mai�a',
	'NotCheckVirtualHost' => 'Nep�rbaud�t VirtualHost defin�cijas',
	'NotCheckDuplicate' => 'Nep�rbaud�t servera nosaukuma dublik�tu',
	'VhostAllLocalIp' => 'At�aut VirtualHost lok�lo IP cit�du k� 127.*',
	'SupportMySQL' => 'At�aut MySQL',
	'SupportMariaDB' => 'At�aut MariaDB',
	'DaredevilOptions' => 'Uzman�bu: riskanti! Tikai ekspertiem. ',
	'ShowphmyadMenu' => 'R�d�t izv�ln� PhpMyAdmin',
	'ShowadminerMenu' => 'R�d�t Adminer izv�ln�',
	'mariadbUseConsolePrompt' => 'Main�t noklus�juma Mariadb konsoles uzvedni',
	'mysqlUseConsolePrompt' => 'Main�t noklus�juma Mysql konsoles uzvedni',
	'NotVerifyPATH' => 'Nep�rbaud�t PATH',
	'NotVerifyTLD' => 'Nep�rbaud�t TLD',
	'NotVerifyHosts' => 'Nep�rbaud�t resursdatora failu',
	'Cleaning' => 'Autom�tiska t�r��ana',
	'AutoCleanLogs' => 'Autom�tiski not�r�t �urn�la failus',
	'AutoCleanLogsMax' => 'L�niju skaits pirms t�r��anas',
	'AutoCleanLogsMin' => 'L�niju skaits p�c t�r��anas',
	'AutoCleanTmp' => 'Autom�tiski izt�r�t tmp direktoriju',
	'AutoCleanTmpMax' => 'Failu skaits pirms t�r��anas',
	'ForTestOnly' => 'Tikai testa vajadz�b�m',
	'iniCommented' => 'Koment�t�s php.ini direkt�vas (; rindas s�kum�)',
	'BackupHosts' => 'Dubl�t resursdatora failu',
	'ShowWWWdirMenu' => 'Izv�ln� r�d�t mapi www',
);

// Ar peles labo pogu klik��iniet uz R�ki
$w_wampTools = 'R�ki';
$w_restartDNS = 'Restart�t DNS';
$w_testConf = 'P�rbaudiet httpd.conf sintaksi';
$w_testServices = 'P�rbaud�t pakalpojumu st�vokli';
$w_changeServices = 'Main�t pakalpojumu nosaukumus';
$w_enterServiceNameApache = "Ievadiet Apache pakalpojuma indeksa numuru. Tas tiks pievienots 'wampapache'";
$w_enterServiceNameMysql = "Ievadiet Mysql pakalpojuma indeksa numuru. Tas tiks pievienots 'wampmysqld'";
$w_enterServiceNameAll = "Ievadiet pakalpojumu nosaukumu sufiksa numuru (tuk�s, lai atgrieztu ori�in�los pakalpojumus)";
$w_compilerVersions = 'P�rbaud�t kompilatora VC, savietojam�bas un ini failus';
$w_UseAlternatePort = 'Izmantojiet citu portu, nevis %s';
$w_AddListenPort = 'Pievienojiet Apache klaus��an�s portu';
$w_vhostConfig = 'R�d�t Apache p�rbaud�to VirtualHost';
$w_apacheLoadedModules = 'R�d�t Apache iel�d�tos modu�us';
$w_apacheLoadedIncludes = 'R�d�t iel�d�to Apache iek�au�anu';
$w_testAliasDir = 'P�rbaud�t rel�cijas Alias  <-> Directory';
$w_verifyxDebugdll = 'P�rbaud�t neizmantotos xDebug dlls';
$w_empty = 'Tuk�s';
$w_misc = 'Da��di';
$w_emptyAll = 'Iztuk�ot VISU';

$w_emptyLogs = 'Iztuk�ot �urn�lus';
$w_emptyPHPlog = 'Iztuk�ot PHP k��du �urn�lu';
$w_emptyApaErrLog = 'Iztuk�ot Apache k��du �urn�lu';
$w_emptyApaAccLog = 'Iztuk�ot Apache piek�uves �urn�lu';
$w_emptyMySQLog = 'Iztuk�ot MySQL �urn�lu';
$w_emptyMariaLog = 'Iztuk�ot MariaDB �urn�lu';
$w_emptyAllLog ='Iztuk�ot visus �urn�lu failus';

$w_dnsorder = 'P�rbaud�t DNS mekl��anas sec�bu';
$w_deleteVer = 'Dz�st neizmantot�s versijas';
$w_addingVer = 'Pievienojiet Apache, PHP, MySQL, MariaDB utt. versijas.';
$w_deleteListenPort = 'Dz�st klaus��an�s portu Apache';
$w_delete = 'Dz�st';
$w_defaultDBMS = 'Noklus�juma DBVS:';
$w_invertDefault = 'Apgriezt noklus�juma DBMS ';
$w_changeCLI = 'Main�t PHP CLI versiju';
$w_reinstallServices = 'P�rinstal�t visus pakalpojumus';
$w_wampReport = 'Wampserver konfigur�cijas p�rskats';
$w_dowampReport = 'Izveidot'. $w_wampReport;
$w_verifySymlink = 'P�rbaud�t simbolisk�s saites';
$w_goto = 'Iet uz:';
$w_FileRepository = 'Saites uz Wampserver kr�tuvju failiem un papildin�jumiem';

//da��di
$w_ext_spec = '�pa�i papla�in�jumi';
$w_ext_zend = 'Zend papla�in�jumi';
$w_phpparam_info = 'Vien�gi inform�cijai';
$w_ext_nodll = 'Nav dll faila';
$w_ext_noline = "Nav 'papla�in�juma ='";
$w_mod_fixed = "Neatgriezenisks modulis";
$w_no_module = 'Nav modu�a faila';
$w_no_moduleload = "Nav 'LoadModule'";
$w_mysql_none = "nav";
$w_mysql_user = "lietot�ja re��ms";
$w_mysql_default = "p�c noklus�juma";
$w_mysql_mode = "sql-mode paskaidrojumi";
$w_Size = "Izm�rs";
$w_Time = "Laiks";
$w_Integer = "Integer v�rt�ba";
$w_phpMyAdminHelp = "PhpMyAdmin pal�dz�ba ";

// PromptText for Aestan Tray Menu type: uzvednes main�gie
// Var b�t \r\n daudzrindu
$w_EnterInteger = "Ievadiet veselu skaitli";
$w_enterPort = "Ievadiet vajadz�go porta numuru";
$w_EnterSize = "Ievadiet izm�ru: xxxx, kam seko M - Mega vai G - Giga \ r \ nSkaitlim j�pievieno simbols M vai G. \ r \ nPiem�ram: 64M; 256M; 1G";
$w_EnterTime = "Ievadiet laiku sekund�s";
$w_MysqlMariaUser = "Ievadiet der�gu lietot�jv�rdu. Ja nezin�t, saglab�jiet 'root' noklus�jum�. \ R \ nJa esat iestat�jis paroli vai nu root, vai izv�l�tajam lietot�jam, jums b�s j�ievada �� parole, kad konsol� tiek pras�ts ievad�t paroli: Bez paroles ievadiet tausti�u Enter";
// P�di�as " tekst� ir j�atce�: \" - \r\n var b�t daudzrindu 
$w_addingVerTxt = "Visi \"papildin�jumi\", t.i., visi Apache, PHP, MySQL vai MariaDB versiju instal�t�ji, k� ar� atjaunin�jumu (Wampserver, Aestan Tray Menu, xDebug u.c.) un t�mek�a lietojumprogrammu (PhpMyAdmin, Adminer) instal�t�ji ir vietn� \r\n\r\n'https://sourceforge.net/projects/wampserver/ '\r\n\r\n Vienk�r�i lejupiel�d�jiet vajadz�gos instal��anas failus un palaidiet tos, ar peles labo pogu klik��inot uz lejupiel�d�t� faila nosaukuma. failu \"Palaist k� administratoram \", lai Wampserver versijai pievienotu pievienojumprogrammu vai lietojumprogrammu. \r\n\r\nTad Apache, PHP, MySQL vai MariaDB versijas mai�a ir tr�s klik��u jaut�jums: \r\nKreisais klik��is -> PHP | Apache | MySQL | MariaDB -> Versija -> Izv�lieties versiju \r\n\r\nVersijas mai�a nemain�s j�su veikt�s parametru izmai�as, k� ar� nenodod datub�zes no vec�s versijas uz jaun�ko. \r\n\r\n Past�v daudz lab�k organiz�ta un vienm�r atjaunin�ta kr�tuve nek� Sourceforge: \r\n\r\n 'https: //wampserver.aviatechno.net '. \r\n\r\n Saites uz kr�tuv�m atrodamas ar peles labo pogu klik��inot -> Pal�dz�ba\r\n";
$w_MySQLsqlmodeInfo = "MySQL/MariaDB sql-mode\r\nSQL serveris var darboties da��dos SQL re��mos atkar�b� no sql-mode direkt�vas v�rt�bas.\r\nViena vai vair�ku re��mu iestat��ana ierobe�o noteiktas iesp�jas un prasa liel�ku stingr�bu SQL sintaks� un datu valid�cij�.\r\nsql-mode re��ma darb�ba my.ini fail� ir ��da.\r\n\r\n- sql-mode: p�c noklus�juma\r\nnepast�v vai ir koment�ts (;sql-mode=\"... \")\r\nPiem�ro MySQL / MariaDB versijas noklus�juma re��mus\r\n\r\n- sql-mode: lietot�ja re��ms\r\nKvalifik�cijas re��ma direkt�va ir aizpild�ta ar lietot�ja defin�tiem re��miem, piem�ram :\r\nsql-mode=\"NO_ZERO_DATE,NO_ZERO_IN_DATE,NO_AUTO_CREATE_USER\"\r\n\r\n- sql-mode: none\r\nKvalifik�cijas re��ma sql re��ms ir tuk�s, ta�u tam j�b�t:\r\nsql-mode=\"\"\r\nneviens SQL re��ms netiek lietots. ";
$w_PhpMyAdMinHelpTxt = "-- PhpMyAdmin\r\nIes�kot phpMyAdmin, jums tiks l�gts ievad�t lietot�ja v�rdu un paroli.\r\nP�c Wampserver 3 instal��anas noklus�juma lietot�jv�rds ir \"root\"(bez p�di��m) un nav parole, kas noz�m�, ka veidlapas Parole lodzi�� j�atst�j tuk�s.\r\n\r\nPhpMyAdmin ir konfigur�ts t�, lai �autu piek��t MySQL vai MariaDB atkar�b� no t�, kuri ir akt�vi.\r\nJa abas DBVS ir aktiviz�tas, j�s pieteik�an�s ekr�n� b�s redzama nolai�am� izv�lne ar nosaukumu \"Servera izv�le\", nolai�amaj� sarakst� vispirms tiks par�d�ts noklus�juma serveris. Atlasiet DBMS, kuru v�laties izmantot �eit k� da�u no pieteik�an�s procesa.\r\nATCERIETIES, ja jums ir da��di lietot�ju konti, jums j�izmanto pareizais atlas�tajam DBMS.\r\nAR�: Ja jums ir viens un tas pats konts, t.i., 'root' ab�s DBMS, ja esat iestat�jis da��das paroles, jums ir j�izmanto pareiz� konta un DBMS parole.\r\n";

?>