<?
IncludeModuleLangFile(__FILE__);

if (class_exists('askaron_settings')) return;

class askaron_settings extends CModule
{
	var $MODULE_ID = "askaron.settings";
	public $MODULE_VERSION;
	public $MODULE_VERSION_DATE;
	public $MODULE_NAME;
	public $MODULE_DESCRIPTION;
	public $PARTNER_NAME;
	public $PARTNER_URI;
	public $MODULE_GROUP_RIGHTS = 'Y';
	
	public $MY_DIR = "bitrix";	
	// public $MODULE_GROUP_RIGHTS = 'Y';	
	// system for partners modules is avaible since '8.0.7', 2009-06-29
	// public $NEED_MAIN_VERSION = '8.0.7';
	
	// OnAdminListDisplay and other events
	// public $NEED_MAIN_VERSION = '9.5.10';
	

	public $NEED_MODULES = array();

	public function askaron_settings()
	{
		$arModuleVersion = array();

		$path = str_replace('\\', '/', __FILE__);
		$path = substr($path, 0, strlen($path) - strlen('/index.php'));
		include($path.'/version.php');

		$check_last = "/local/modules/".$this->MODULE_ID."/install/index.php";
		$check_last_len = strlen($check_last);

		if ( substr($path, -$check_last_len) == $check_last )
		{
			$this->MY_DIR = "local";
		}		

		if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion))
		{
			$this->MODULE_VERSION = $arModuleVersion['VERSION'];
			$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
		}

		$this->PARTNER_NAME = GetMessage("ASKARON_SETTINGS_PARTNER_NAME");
		$this->PARTNER_URI = 'http://askaron.ru/';

		$this->MODULE_NAME = GetMessage('ASKARON_SETTINGS_MODULE_NAME');
		$this->MODULE_DESCRIPTION = GetMessage('ASKARON_SETTINGS_MODULE_DESCRIPTION');
	}

	public function DoInstall()
	{
		global $APPLICATION, $DB;

		global $askaron_settings_global_errors;
		$askaron_settings_global_errors = array();

		if (is_array($this->NEED_MODULES) && !empty($this->NEED_MODULES))
			foreach ($this->NEED_MODULES as $module)
				if (!IsModuleInstalled($module))
					$askaron_settings_global_errors[] = GetMessage('ASKARON_SETTINGS_NEED_MODULES', array('#MODULE#' => $module));

		if ( strlen($this->NEED_MAIN_VERSION) > 0  && version_compare(SM_VERSION, $this->NEED_MAIN_VERSION) < 0)
		{
			$askaron_settings_global_errors[] = GetMessage( 'ASKARON_SETTINGS_NEED_RIGHT_VER', array('#NEED#' => $this->NEED_MAIN_VERSION) );
		}

//		if ( strtolower($DB->type) != 'mysql' )
//		{
//			$askaron_settings_global_errors[] = GetMessage("ASKARON_SETTINGS_ONLY_MYSQL_ERROR");
//		}		

		if ( count( $askaron_settings_global_errors ) == 0 )
		{
			if ($this->InstallDB())
			{
				$this->InstallFiles();
				RegisterModule("askaron.settings");
			}
			else
			{
				$askaron_settings_global_errors[] = GetMessage("ASKARON_SETTINGS_INSTALL_TABLE_ERROR");
			}
		}
		
		$APPLICATION->IncludeAdminFile( GetMessage("ASKARON_SETTINGS_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->MODULE_ID."/install/step.php");
		return true;
		
	}

	public function DoUninstall()
	{
		global $APPLICATION;
		
		$this->UnInstallFiles();
		$this->UnInstallDB();

		UnRegisterModule('askaron.settings');

		$APPLICATION->IncludeAdminFile( GetMessage("ASKARON_SETTINGS_UNINSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/".$this->MY_DIR."/modules/".$this->MODULE_ID."/install/unstep.php");
		return true;
	}

	function InstallFiles($arParams = array())
	{
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/".$this->MY_DIR."/modules/".$this->MODULE_ID."/install/admin/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin/");
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/".$this->MY_DIR."/modules/".$this->MODULE_ID."/install/themes/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/", true, true);

		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/".$this->MY_DIR."/modules/".$this->MODULE_ID."/install/activities", $_SERVER["DOCUMENT_ROOT"]."/bitrix/activities", true, true);
	}

	function UnInstallFiles()
	{
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/".$this->MY_DIR."/modules/".$this->MODULE_ID."/install/admin/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin");
		DeleteDirFiles($_SERVER["DOCUMENT_ROOT"]."/".$this->MY_DIR."/modules/".$this->MODULE_ID."/install/themes/.default/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/themes/.default");//css
		DeleteDirFilesEx("/bitrix/themes/.default/icons/".$this->MODULE_ID."/");//icons

		DeleteDirFilesEx("/bitrix/activities/custom/askaron_settings_setvariableactivity/");
	}

	function InstallDB()
	{
		return true;		
	}

	function UnInstallDB($arParams = Array())
	{
		return true;
	}
}
?>