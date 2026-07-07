<?php
// French language file for
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
// $w_enterServiceNameAll $w_settings['NotVerifyPATH' -> $w_MysqlMariaUse,
// 3.1.4 $w_settings 'NotVerifyTLD' 'Cleaning' 'AutoCleanLogs' 'AutoCleanLogsMax' 'AutoCleanLogsMax' 'AutoCleanTmp' 'AutoCleanTmpMax' 'iniCommented'
// $w_wampReport - $w_dowampReport
// 3.1.9 $w_settings 'BackupHosts'
// 3.2.0 $w_verifySymlink  - $w_settings['NotVerifyHosts']
// 3.2.1 $w_addingVer - $w_addingVerTxt - $w_goto - $w_FileRepository
// 3.2.2 $w_MysqlMariaUser et $w_EnterSize modifiés - $w_MySQLsqlmodeInfo $w_mysql_mode $w_phpMyAdminHelp $w_PhpMyAdMinHelpTxt
// 3.2.3 https for wampserver.aviatechno
// 3.2.5 $w_emptyLogs - $w_emptyPHPlog - $w_emptyApaErrLog - $w_emptyApaAccLog - $w_emptyMySQLog - $w_emptyMariaLog - $w_emptyAllLog
//       $w_testAliasDir - $w_verifyxDebugdll - $w_apacheLoadedIncludes - $w_settings 'ShowWWWdirMenu'
// 3.2.6 $w_compareApache - $w_versus - $w_restorefile - $w_restore - $w_apache_restore - $w_ApacheRestoreInfo - $w_apache_restore
//       $w_ApacheCompareInfo - $w_apacheDefineVariables - $w_Refresh_Restart - $w_Refresh_Restart_Info
//       $w_checkUpdates - $w_PhpMyAdminBigFileTxt - $w_apacheTools - $w_PHPloadedExt
//       $w_settings  apacheCompareVersion - apacheRestoreFiles - apacheGracefulRestart - LinksOnProjectsHomePage
//                    ApacheWampParams - apachePhpCurlDll
//       Suppression de : $w_enterServiceNameApache - $w_enterServiceNameMysql - $w_enterServiceNameAll
// 3.2.7 $w_showExcludedPorts
// 3.2.8 $w_phpNotExists - LinksOnProjectsHomeByIp - CheckVirtualHost - $w_PHPversionsUse - $w_All_Versions
//       $w_settings 	ScrollListsHomePage
// 3.2.9 $w_phpparam_obs - $w_ApacheCompiledIn - $w_ApacheDoesNotIf - $w_mod_not_disable
//       $w_NoDefaultDBMS
// 3.3.0 $w_settings WamperverBrowser BrowserChange
//       suppress apachePhpCurlDll
// 3.3.2 $w_PhpMyAdminGoHidedb - $w_PhpMyAdminGoNoPassword - $w_ConvertHttps - $w_wampHttpsHelp - $w_wampHttpsHelpTxt
//       $w_MariaDBMySQLHelp - $w_MariaDBMySQLHelpTxt - $w_settings httpsReady
//       suppress $w_settings['ShowphmyadMenu']
// 3.3.3 suppress $w_MariaDBMySQLHelpTxt transformé en fichier 'files\mariadb_mysql_french.rtf'
//       suppress $w_wampHttpsHelpTxt    .......... .. ....... 'files\wampmodehttps_french.rtf'
// 3.3.4 $w_AdminerHelpTxt - $w_NeedVirtualhost
// 3.3.5 $w_settings 	AllowLocalhostHttps
// 3.3.6 $w_wampHttpsHelpAuto - $w_wampHttpsHelpManual
// 3.3.7 $w_ExtNotLoaded
//       suppress $w_PhpMyAdMinHelpTxt et $w_PhpMyAdminBigFileTxt transformé en fichier 'files_phpmyadmin_french.rtf'

// Projects sub-menu
$w_projectsSubMenu = 'Sus proyectos';
// VirtualHosts sub-menu
$w_virtualHostsSubMenu = 'Sus servidores virtuales';
$w_add_VirtualHost = 'Administrar servidores virtuales';
$w_aliasSubMenu = 'Sus alias';
$w_portUsed = 'Puerto utilizado por Apache ';
$w_portUsedMysql = 'Puerto utilizado por MySQL ';
$w_portUsedMaria = 'Puerto utilizado por MariaDB ';
$w_testPortUsed = 'Probar puerto utilizado ';
$w_portForApache = 'Puerto para Apache';
$w_listenForApache = 'Puerto de escucha para añadir a Apache';
$w_portForMyql = 'Puerto para MySQL';
$w_testPortMysql = ' Probar puerto 3306';
$w_testPortMysqlUsed = 'Probar puerto MySQL utilizado ';
$w_testPortMariaUsed = 'Probar puerto de MariaDB utilizado ';

// Right-click Settings
$w_wampSettings = 'Configuración de wampserver';
$w_settings = array(
	'urlAddLocalhost' => 'Agregar localhost en la url',
	'VirtualHostSubMenu' => 'Submenú de servidores virtuales',
	'AliasSubmenu' => 'Submenú de alias',
	'ProjectSubMenu' => 'Submenú de proyectos',
	'HomepageAtStartup' => 'Página de inicio de Wampserver al iniciar',
	'MenuItemOnline' => 'Elemento de menú: Conectado o desconectado',
	'ItemServicesNames' => 'Elemento del menú de herramientas: Cambiar nombres de servicios',
	'CheckVirtualHost' => 'Verificar definiciones de VirtualHost',
	'NotCheckVirtualHost' => 'No comprobar las definiciones de VirtualHost',
	'NotCheckDuplicate' => 'No comprobar nombre de servidor duplicado',
	'VhostAllLocalIp' => 'Permitir el IP local de VirtualHost que no sea 127. *',
	'SupportMySQL' => 'Permitir MySQL',
	'SupportMariaDB' => 'Permitir MariaDB',
	'DaredevilOptions' => 'Precaución. Esto es sólo para expertos',
	'ShowphmyadMenu' => 'Mostrar PhpMyAdmin en Menú',
	'ShowadminerMenu' => 'Mostrar Adminer en menú',
	'mariadbUseConsolePrompt' => 'Modificar prompt de la consola de Mariadb por defecto',
	'mysqlUseConsolePrompt' => 'Modificar prompt de la consola de Mysql por defecto',
	'NotVerifyPATH' => 'No verificar PATH',
	'NotVerifyTLD' => 'No verificar TLD',
	'NotVerifyHosts' => 'No verificar archivo hosts',
	'Cleaning' => 'Limpieza automatica',
	'AutoCleanLogs' => 'Limpiar los archivos de registro automáticamente',
	'AutoCleanLogsMax' => 'Número de líneas antes de la limpieza',
	'AutoCleanLogsMin' => 'Número de líneas después de la limpieza',
	'AutoCleanTmp' => 'Limpiar el directorio tmp automáticamente',
	'AutoCleanTmpMax' => 'Número de archivos antes de limpiar',
	'ForTestOnly' => 'Sólo para fines de prueba',
	'iniCommented' => 'Directivas comentadas php.ini (; al principio de la línea)',
	'BackupHosts' => 'Copia de seguridad del archivo hosts',
	'ShowWWWdirMenu' => 'Mostrar carpeta www en menú',
	'ApacheWampParams' => 'Configuración de apache',
	'apacheCompareVersion' => 'Permitir la comparación de la configuración de Apache',
	'apacheRestoreFiles' => 'Permitir la restauración de archivos de Apache',
	'apacheGracefulRestart' => 'Permitir el reinicio correcto de Apache',
	'LinksOnProjectsHomePage' => 'Permitir enlaces en la página de inicio de proyectos',
	'LinksOnProjectsHomeByIp' => 'Enlace en proyectos por \'enlace local de IP\'',
	'ScrollListsHomePage' => 'Permitir desplazamiento de listas en página de inicio',
	'WampserverBrowser' => 'Navegador de Wampserver',
	'BrowserChange' => 'Establecer el navegador de Wampserver',
	'httpsReady' => 'Wampserver listo para soportar https',
	'AllowLocalhostHttps' => 'Permitir HTTPS para localhost',
);

// Right-click Tools
$w_wampTools = 'Herramientas';
$w_restartDNS = 'Reiniciar DNS';
$w_testConf = 'Comprobar sintaxis de httpd.conf';
$w_testServices = 'Comprobar estado de los servicios';
$w_changeServices = 'Cambiar nombres de los servicios';
$w_enterServiceNameApache = "Ingrese un número de índice para el servicio de Apache. Se agregará a 'wampapache'.";
$w_enterServiceNameMysql = "Ingrese un número de índice para el servicio Mysql. Se agregará a 'wampmysqld'.";
$w_enterServiceNameAll = "Ingrese un número para el sufijo de nombres de servicio (vacío para devolver los servicios originales)";
$w_compilerVersions = 'Comprobar compilador VC, compatibilidad y archivos ini';
$w_UseAlternatePort = 'Utilizar un puerto distinto de %s';
$w_AddListenPort = 'Agregar un puerto de escucha para Apache';
$w_vhostConfig = 'Mostrar VirtualHost examinado por Apache';
$w_apacheLoadedModules = 'Mostrar módulos cargados de apache';
$w_apacheLoadedIncludes = 'Mostrar cargador que incluye apache';
$w_apacheDefineVariables = 'Mostrar variables de apache (Definir)';
$w_showExcludedPorts = 'Mostrar puertos excluidos por el sistema';
$w_testAliasDir = 'Comprobar relaciones de alias  <-> Directorio';
$w_verifyxDebugdll = 'Compruebe si hay dlls de xDebug no utilizados';
$w_misc = 'Varios';
$w_empty = 'Vaciar';
$w_emptyAll = 'Vaciar todo';

$w_emptyLogs = 'Vaciar registros';
$w_emptyPHPlog = 'Vaciar registro de errores de PHP';
$w_emptyApaErrLog = 'Vaciar registro de errores de Apache';
$w_emptyApaAccLog = 'Vaciar registro de acceso de Apache';
$w_emptyMySQLog = 'Vaciar registro de MySQL';
$w_emptyMariaLog = 'Vaciar registro de MariaDB';
$w_emptyAllLog ='Vaciar todo los archivos de registro';

$w_dnsorder = 'Comprobar el orden de búsqueda de DNS';
$w_deleteVer = 'Eliminar versiones no utilizadas';
$w_addingVer = 'Agregar versiones de Apache, PHP, MySQL, MariaDB, etc.';
$w_deleteListenPort = 'Delete a Listen port Apache';
$w_delete = 'Eliminar';
$w_defaultDBMS = 'SGBD predeterminado';
$w_NoDefaultDBMS = 'SGBD predeterminado ninguno';
$w_invertDefault = 'Invertir DBMS predeterminado ';
$w_changeCLI = 'Cambiar la versión de CLI de PHP';
$w_reinstallServices = 'Reinstalar todos los servicios';
$w_wampReport = 'Configuración de reporte';
$w_dowampReport = 'Crear '.$w_wampReport;
$w_verifySymlink = 'Verificar enlaces simbólicos';
$w_goto = 'Ir a';
$w_FileRepository = 'Enlaces a archivos y complementos de repositorios de Wampserver';
$w_compareApache = 'Comparación de configuraciones de Apache';
$w_versus = 'versus';
$w_restorefile = 'Restaurar archivos guardados en la instalación de Apache';
$w_restore = 'Restaurar';
$w_checkUpdates = 'Buscar actualizaciones';
$w_apacheTools = 'Herramientas Apache';
$w_PHPloadedExt = 'Mostrar extensiones cargadas de PHP';
$w_PHPversionsUse = 'Mostrar el uso de versiones de PHP';

//miscellaneous
$w_ext_spec = 'Extensiones especiales';
$w_ext_zend = 'Extensiones Zend';
$w_phpparam_info = 'Solo para información';
$w_ext_nodll = 'No hay archivo DLL';
$w_ext_noline = "Sin 'extension ='";
$w_mod_fixed = "Módulo irreversible";
$w_mod_not_disable = "Estos módulos no deben deshabilitarse";
$w_no_module = 'No hay archivo de módulo';
$w_no_moduleload = "No 'LoadModule'";
$w_mysql_none = "ninguno";
$w_mysql_user = "modo de usuario";
$w_mysql_default = "por defecto";
$w_mysql_mode = "Explicaciones del modo sql";
$w_apache_restore = "Advertencia de restauración de Apache;";
$w_apache_compare = "Advertencia Comparación de configuración de Apache";
$w_Refresh_Restart = "Ayuda ".$w_refresh.' - '.$w_restartWamp;
$w_Size = "Tamaño";
$w_Time = "Tiempo";
$w_Integer = "Valor entero";
$w_phpMyAdminHelp = "Ayuda con PhpMyAdmin";
$w_wampHttpsHelp = "Modo de ayuda de HTTPS de Wampserver";
$w_wampHttpsHelpAuto = "Modo de ayuda de HTTPS automático de Wampserver";
$w_wampHttpsHelpManual = "Manual de ayuda del modo HTTPS de Wampserver";
$w_phpNotExists = 'La versión de PHP no existe';
$w_All_Versions = 'Todas las versiones';
$w_phpparam_obs = 'Configuración depreciados | Eliminado | Nuevo';
$w_ApacheCompiledIn = 'Módulos incorporados';
$w_ApacheDoesNotIf = 'No requiere <IfModule ModName>.';
$w_PhpMyAdminGoHidedb = 'Ocultar bases de datos nativas';
$w_PhpMyAdminGoNoPassword = 'Permitir conexión sin contraseña';
$w_ConvertHttps = 'Modo HTTPS para servidor local';
$w_MariaDBMySQLHelp = "Ayuda para MariaDB o MySQL";
$w_NeedVirtualhost = "Necesitas ayuda para crear un servidor virtual";
$w_ExtNotLoaded = "Extensión PHP no cargada: ";

// PromptText for Aestan Tray Menu type: prompt variables
// May have \r\n for multilines
$w_EnterInteger = "Ingrese un número entero";
$w_enterPort = "Ingrese el número de puerto deseado";
$w_EnterSize = "Ingrese el tamaño: xxxx seguido de M para Mega o G para Giga\r\nEl símbolo M o G debe adjuntarse al número\r\nPor ejemplo 64M ; 256M ; 1G";
$w_EnterTime = "Ingrese el tiempo en segundos";
$w_MysqlMariaUser = "Ingrese un nombre de usuario válido. Si no lo sabe, mantenga 'root' por defecto\r\nSi ha establecido una contraseña para root o para el usuario elegido, deberá escribir esa contraseña cuando se le solicite 'Ingresar contraseña:' desde la consola. Sin contraseña, tecla Enter";

// Long texts
// Quotation marks " in texts must be escaped: \" - May have \r\n for multilines
$w_addingVerTxt = "Todos los \"addons\", por ejemplo todos los instaladores de las versiones de Apache, PHP, MySQL o MariaDB, así como los instaladores de actualizaciones (Wampserver, Aestan Tray Menu, xDebug, etc) y aplicaciones web (PhpMyAdmin, Adminer) están activados\r\n\r\n'https://sourceforge.net/projects/wampserver/'\r\n\r\nJust descargue los archivos de instalación que desee y ejecútelos haciendo clic derecho en el nombre del archivo descargado y luego \"Ejecutar como administrador\" para agregar el complemento o la aplicación a su versión de Wampserver\r\n\r\nLuego, cambiar la versión de Apache, PHP, MySQL o MariaDB es cuestión de tres clics\r\nClick izquierdo -> PHP|Apache|MySQL|MariaDB -> Versión -> Elegir versión\r\n\r\nEl cambio de versión no incluye ningún cambio de parámetro que haya realizado, ni transfiere bases de datos de la versión anterior a la nueva\r\n\r\nUn repositorio mucho mejor organizado y siempre actualizado existente de Sourceforge\r\n\r\n'https://wampserver.aviatechno.net'.\r\n\r\nLos enlaces a los repositorios están en clic derecho -> Ayuda\r\n";
$w_MySQLsqlmodeInfo = "MySQL/MariaDB sql-mode\r\nEl servidor SQL puede ejecutarse en diferentes modos SQL según el valor de la directiva sql-mode.\r\nEstablecer uno o más modos restringe ciertas posibilidades y requiere mayor rigor en la sintaxis SQL y la validación de datos\r\nEl funcionamiento de la directiva sql-mode en el archivo my.ini es el siguiente.\r\n\r\n- sql-mode: por defecto\r\nLa directiva sql-mode no existe o está comentada (;sql-mode=\"...\")\r\nSe aplican los modos por defecto de la versión MySQL/MariaDB\r\n\r\n- sql-mode: modo de usuario\r\nLa directiva sql-mode se completa con modos definidos por el usuario, por ejemplo \r\nsql-mode=\"NO_ZERO_DATE,NO_ZERO_IN_DATE,NO_AUTO_CREATE_USER\"\r\n\r\n- sql-mode: ninguno\r\nLa directiva sql-mode está vacía pero debe existir\r\nsql-mode=\"\"\r\nno se aplica ningún modo SQL.";
$w_ApacheRestoreInfo = "--- Restauración de archivos de Apache\r\nDesde Apache 2.4.41, al final de la instalación de una versión, los archivos operativos httpd.conf y httpd-vhosts.conf se copian en una carpeta de respaldo\r\nEn caso de problemas o cambios no deseados en Apache, puede restaurar estos dos archivos a la configuración original de Apache\r\nPor supuesto, EN ESTE CASO PERDERÁ CUALQUIER CAMBIO DE CONFIGURACIÓN QUE HAYA HECHO DESPUÉS DE LA INSTALACIÓN, como el módulo o cargas incluidas";
$w_ApacheCompareInfo = "--- Comparando versiones de Apache\r\nSi tiene al menos dos versiones de Apache, tiene la posibilidad de comparar la versión actual con una versión anterior\r\nSera comparado los siguientes\r\n- LoadModule\r\n- Include\r\n- archivos httpd-vhosts.conf\r\n- archviso httpd-ssl.conf\r\n- archviso openssl.cnf\r\n- Presencia y contenido de la carpeta de certificados\r\nTiene la posibilidad de copiar la configuración de una versión antigua en la versión actual\r\n*** ADVERTENCIA *** No se realizarán copias de seguridad, es su responsabilidad realizar copias de seguridad ANTES de copiar las configuraciones";
$w_Refresh_Restart_Info = "--- Diferencias entre '".$w_refresh."' y '".$w_restartWamp."'\r\n-- ".$w_refresh.":\r\n- Realiza varios controles,\r\n- Vuelve a leer los archivos de configuración de Wampserver, Apache, PHP, MySQL y MariaDB,\r\n- Modifica el archivo de configuración de Wampmanager en consecuencia y actualiza los menús,\r\n- Realiza un 'reinicio correcto de Apache',\r\n- Recarga el menú de la bandeja Aestan\r\nNo hay interrupción de las conexiones Apache, PHP, MySQL y MariaDB.\r\n\r\n-- ".$w_restartWamp.":\r\n- Detener los servicios ".$c_apacheService.", ".$c_mysqlService." y ".$c_mariadbService.",\r\n- Vacíar todos los archivos de registro\r\n- Vaciar la carpeta tmp\r\n- Salir de Wampserver,\r\n- Iniciar Wampserver 'normalmente'\r\nPor lo tanto, hay un corte total de las conexiones Apache, PHP, MySQL y MariaDB y se vuelven a colocar bajo otras identificaciones.";
$w_AdminerHelpTxt = "\r\n--- Adminer ---\r\nEl administrador no le permite conectarse a bases de datos sin contraseña.\r\nPor lo tanto, es necesario crear una contraseña para 'root' antes de utilizar Adminer.\r\nEsto se puede hacer a través de PhpMyAdmin o mediante la consola MySQL y/o MariaDB.\r\nPero aún así, es posible permitir conexiones de administrador sin contraseña.\r\nPara hacer esto, vea el contenido del archivo c:\\wamp64\\apps\\adminer4.x.y\\index.php\r\n";

?>