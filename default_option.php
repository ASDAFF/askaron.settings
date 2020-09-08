<?
$askaron_settings_default_option = array();

if( CModule::IncludeModule("askaron.settings") )
{
	$askaron_settings_default_option = CAskaronSettings::GetFields();
}
?>