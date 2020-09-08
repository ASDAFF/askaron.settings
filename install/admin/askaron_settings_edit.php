<?
if ( file_exists( $_SERVER["DOCUMENT_ROOT"]."/local/modules/askaron.settings/admin/askaron_settings_edit.php" ) )
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/local/modules/askaron.settings/admin/askaron_settings_edit.php");
}
else
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/askaron.settings/admin/askaron_settings_edit.php");	
}
?>