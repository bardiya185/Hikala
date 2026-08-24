<?php
// �������������ļ�
// ��Ŀ�� VirtualHosts �Ӳ˵�
// ���ú͹����Ҽ������Ӳ˵�
// 3.0.7 ���� $w_listenForApache - $w_AddListenPort - $w_deleteListenPort - $w_settings['SupportMariaDB']
// 3.2.2 ���޸� $w_MysqlMariaUser �� $w_EnterSize -  - $w_MySQLsqlmodeInfo $w_mysql_mode $w_phpMyAdminHelp $w_PhpMyAdMinHelpTxt
// 3.2.3 wampserver.aviatechno ����Ϊ https

// ��Ŀ�Ӳ˵�
$w_projectsSubMenu = '��Ŀ�б�';
// ���������Ӳ˵�
$w_virtualHostsSubMenu = '���������б�';
$w_add_VirtualHost = '������������';
$w_aliasSubMenu = '������Alias���б�';
$w_portUsed = 'Apache ʹ�õĶ˿ڣ� ';
$w_portUsedMysql = 'MySQL ʹ�õĶ˿ڣ� ';
$w_portUsedMaria = 'MariaDB ʹ�õĶ˿ڣ� ';
$w_testPortUsed = '���Զ˿��Ƿ�ʹ�ã� ';
$w_portForApache = 'Apache ʹ�õĶ˿�';
$w_listenForApache = '���� Apache �˿ڼ���';
$w_portForMysql = 'MySQL �˿�';
$w_testPortMysql = '���� 3306 �˿�';
$w_testPortMysqlUsed = '���� MySQL ʹ�õĶ˿ڣ� ';
$w_testPortMariaUsed = '���� MariaDB ʹ�õĶ˿ڣ� ';

// �Ҽ��Ӳ˵�-����
$w_wampSettings = 'Wamp ����';
$w_settings = array(
    'urlAddLocalhost' => 'Add localhost in url',
    'VirtualHostSubMenu' => '��ʾ���������˵�',
    'AliasSubmenu' => '��ʾ������Alias���˵�',
    'ProjectSubMenu' => '��ʾ��Ŀ�˵�',
    'HomepageAtStartup' => '���� Wampserver ʱ�Զ��� localhost ',
    'MenuItemOnline' => '��ʾ�л�����/���߲˵�',
    'ItemServicesNames' => '���߲˵���: ���ķ�������',
    'NotCheckVirtualHost' => '��������������Ƿ��Ѷ���',
    'NotCheckDuplicate' => '����� ServerName �Ƿ��ظ�',
    'VhostAllLocalIp' => '����������������ʹ�ñ���IP���� 127.* ��',
    'SupportMySQL' => '���� MySQL',
    'SupportMariaDB' => '���� MariaDB',
    'DaredevilOptions' => '��������ֵ�з��գ�����Ϥ����ģ�',
    'ShowphmyadMenu' => '��ʾ PHPMyAdmin �˵�',
    'ShowadminerMenu' => '��ʾ Adminer �˵�',
    'mariadbUseConsolePrompt' => '�޸�Ĭ�ϵ� MariaDB ����̨��ʾ',
    'mysqlUseConsolePrompt' => '�޸�Ĭ�ϵ� MySQL ����̨��ʾ',
    'NotVerifyPATH' => '������ PATH',
    'NotVerifyTLD' => '������ TLD',
    'NotVerifyHosts' => '������ hosts �ļ�',
    'Cleaning' => '�Զ�����',
    'AutoCleanLogs' => '�Զ�������־�ļ�',
    'AutoCleanLogsMax' => '����ǰ��־����',// ��־����>=�趨ֵʱ����
    'AutoCleanLogsMin' => '��������־����',// ����������־����
    'AutoCleanTmp' => '�Զ�������ʱ��tmp��Ŀ¼',
    'AutoCleanTmpMax' => '����ǰ�ļ���',// �ļ���>=�趨ֵʱ����
    'ForTestOnly' => '�������ڲ��Ի������������ԣ�',
    'iniCommented' => '��ע�� php.ini ����',
    'BackupHosts' => '���� hosts �ļ�',
    'ShowWWWdirMenu' => '��ʾ www �ļ��в˵�',
);

// �Ҽ��Ӳ˵�-����
$w_wampTools = '����';
$w_restartDNS = '���� DNS';
$w_testConf = '��� httpd.conf �﷨';
$w_testServices = '������״̬';
$w_changeServices = '���ķ�������';
$w_enterServiceNameApache = "Enter an index number for the Apache service. It will be added to 'wampapache'";
$w_enterServiceNameMysql = "Enter an index number for the Mysql service. It will be added to 'wampmysqld'";
$w_enterServiceNameAll = "Enter a number for the suffix of service names (empty to return original services)";
$w_compilerVersions = '��� VC ���п������������Ժ� ini �ļ�';
$w_UseAlternatePort = 'ʹ�� %s ����Ķ˿�';
$w_AddListenPort = '���� Apache �����˿�';
$w_vhostConfig = '��ʾ Apache ����Ч����������';
$w_apacheLoadedModules = '��ʾ Apache �Ѽ��ص�ģ��';
$w_apacheLoadedIncludes = '��ʾ Apache �Ѽ��صĶ��������ļ�';
$w_testAliasDir = '��������Alias����Ŀ¼�Ĺ���';
$w_verifyxDebugdll = '���δʹ�õ� xDebug ��չ dll';
$w_empty = '���';
$w_misc = '����';
$w_emptyAll = '�������';

$w_emptyLogs = '�����־';
$w_emptyPHPlog = '��� PHP ������־';
$w_emptyApaErrLog = '��� Apache ������־';
$w_emptyApaAccLog = '��� Apache ������־';
$w_emptyMySQLog = '��� MySQL ��־';
$w_emptyMariaLog = '��� MariaDB ��־';
$w_emptyAllLog ='���������־�ļ�';

$w_dnsorder = '��� DNS ����˳��';
$w_deleteVer = 'ɾ��δʹ�ð汾';
$w_addingVer = '���� Apache, PHP, MySQL, MariaDB �Ȱ汾.';
$w_deleteListenPort = 'ɾ�� Apache �����˿�';
$w_delete = 'ɾ��';
$w_defaultDBMS = 'Ĭ�� DBMS:';
$w_invertDefault = '����Ĭ�� DBMS ';
$w_changeCLI = '���� PHP CLI �汾';
$w_reinstallServices = '���°�װ���з���';
$w_wampReport = 'Wampserver ���ñ���';
$w_dowampReport = '���� '.$w_wampReport;
$w_verifySymlink = '��֤�����ӣ�symbolic links��';
$w_goto = 'ת����';
$w_FileRepository = 'Wampserver �ļ��������վ';

// ����
$w_ext_spec = 'ר����չ';
$w_ext_zend = 'Zend ��չ';
$w_phpparam_info = '�����ο�';
$w_ext_nodll = '�� dll �ļ�';
$w_ext_noline = "�� 'extension='";
$w_mod_fixed = "�̶�ģ��";
$w_no_module = '��ģ���ļ�';
$w_no_moduleload = "�� 'LoadModule'";
$w_mysql_none = "none";
$w_mysql_user = "user mode";
$w_mysql_default = "by default";
$w_mysql_mode = "sql-mode ˵��";
$w_Size = "��С";
$w_Time = "ʱ��";
$w_Integer = "������ֵ";
$w_phpMyAdminHelp = "PHPMyAdmin ����";

// Aestan Tray �˵� PromptText �� ������ʾ
// ���з���ʹ�� \r\n
$w_EnterInteger = "����������ֵ";
$w_enterPort = '����Ҫʹ�õĶ˿ں�';
$w_EnterSize = "�����С�� **M �� **G ��**������������\r\n���磺64M ; 256M ; 1G";
$w_EnterTime = "��������";
$w_MysqlMariaUser = "������һ����Ч���û���. ����㲻֪����;, �뱣��ΪĬ�ϵ� ��root����";

// ���ı�
// ����ת��˫����(\")
$w_addingVerTxt ="���С�������������� Apache, PHP, MySQL �� MariaDB ���а汾�İ�װ�����Լ����³��� (Wampserver, Aestan Tray Menu, xDebug ��) �� WebӦ�ó��� (PhpMyAdmin, Adminer) ������ Sourceforge ����.\r\n\r\n".
	"'https://sourceforge.net/projects/wampserver/'\r\n\r\n".
	"ֻ��Ҫ��������İ�װ�����ļ����Ҽ��������صİ�װ�����ļ���Ȼ��ѡ���Թ���Ա�������С�����װ��֮�󣬲����Ӧ�þͻ����ӵ�����Wampserver��.\r\n\r\n".
	"Ȼ��ֻ��Ҫ�����������£����ɸ��� Apache, PHP, MySQL �� MariaDB �İ汾:\r\n".
	"����˵� -> PHP|Apache|MySQL|MariaDB -> �汾 -> ѡ��汾\r\n\r\n".
	"�汾���ĺ󣬾ɰ汾�Ĳ�������/��չ���ú����ݶ������Զ�ת�Ƶ��°汾����Ҫ����Ǩ��.\r\n\r\n".
	"���� Sourceforge �����ǻ��и��ø�����Ĵ������վ:\r\n\r\n".
	"1. https://wampserver.aviatechno.net\r\n\r\n".
	"2. https://wampserver.site\r\n\r\n".
	"�������վ�����ӻ������� �Ҽ��˵� -> ���� ���\r\n";
$w_MySQLsqlmodeInfo = "MySQL/MariaDB sql-mode\r\n".
	"SQL ����������� sql-mode �������Բ�ͬ��ģʽ����.\r\n".
	"����һ������ģʽ������һЩ�÷���������Ҫ���SQL�﷨�����ݽ����ϸ����֤�ͼ�飬������ܵ���SQL����޷�ִ��.\r\n".
	"��ͬģʽ�£���Ӧ my.ini �ļ�����������.\r\n\r\n".
	"- sql-mode: Ĭ��ģʽ\r\n".
	"sql-mode ��������ڻ��ѱ�ע�� (;sql-mode=\"...\")\r\n".
	"��ʹ�� MySQL/MariaDB ��Ĭ������\r\n\r\n".
	"- sql-mode: �Զ���ģʽ\r\n".
	"����ѡ���ģʽ������ sql-mode ���ã����磺\r\n".
	"sql-mode=\"NO_ZERO_DATE,NO_ZERO_IN_DATE,NO_AUTO_CREATE_USER\"\r\n\r\n".
	"- sql-mode: ��ģʽ\r\n".
	"sql-mode ����Ϊ�գ����������:\r\n".
	"sql-mode=\"\"\r\n".
	"�� SQL ģʽ.";
$w_PhpMyAdMinHelpTxt = "-- PhpMyAdmin\r\n".
	"����PhpMyAdminʱ����Ҫ���������û���������.\r\n".
	"��װ Wampserver 3 ��, ���ݿ����ϵͳĬ���û����� root ������Ϊ�գ�����������ղ�����д.\r\n\r\n".
	"������� PhpMyAdmin ������� MySQL �� MariaDB ��ֻ��Ҫ�ڵ�¼����ѡ�񼴿�.\r\n".
	"���ֻ������һ�����ݿ����ϵͳ����û��ѡ�������ѡ�����һ��ΪĬ�����ݿ����ϵͳ.\r\n".
	"��ס��������в�ͬ���û��ʻ��������Ϊ��ѡ�����ݿ����ϵͳʹ����ȷ���û��ʻ�.\r\n".
	"����: �������ݿ����ϵͳ֮����û������ݲ���ͨ.\r\n";

?>