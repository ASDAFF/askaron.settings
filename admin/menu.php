<?
IncludeModuleLangFile(__FILE__);

//if($APPLICATION->GetGroupRight("askaron.settings")!="D")

global $USER;

$RIGHT = $APPLICATION->GetGroupRight("askaron.settings");
$RIGHT_W = ($RIGHT>="W");
$RIGHT_R = ($RIGHT>="R");

if( $RIGHT_R )
{
    CModule::IncludeModule('askaron.settings');
	$aMenu = array(
		"parent_menu" => "global_menu_settings",
		"section" => "askaron.settings",
		"sort" => 10000,
        "module_id" => "askaron.settings",
		"text" => GetMessage("ASKARON_SETTINGS_MENU_MAIN"),
		"title" => GetMessage("ASKARON_SETTINGS_MENU_MAIN_TITLE"),
		"url" => "askaron_settings_edit.php?lang=".LANGUAGE_ID,
		"icon" => "askaron_settings_menu_icon",	
		"items_id" => "menu_askaron_settings",
//		"items" => array(
//			array(
//				"text" => GetMessage("ASKARON_SETTINGS_MENU_EDIT"),
//				"url" => "askaron_settings_edit.php?lang=".LANGUAGE_ID,
//				"more_url" => Array(
//					//"askaron_settings_event_admin.php",
//					//"askaron_settings_event_edit.php",
//
//				),
//				"title" => GetMessage("ASKARON_SETTINGS_MENU_EDIT_TITLE"),
//			),
//		),
	);
	return $aMenu;
}
return false;
?>
