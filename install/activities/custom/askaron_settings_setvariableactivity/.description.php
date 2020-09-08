<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arActivityDescription = array(
	"NAME" => GetMessage("ASKARON_SETTINGS_SETVARIABLE_DESCR_NAME"),
	"DESCRIPTION" => GetMessage("ASKARON_SETTINGS_SETVARIABLE_DESCR_DESCR"),
	"TYPE" => "activity",
	"CLASS" => "Askaron_Settings_SetVariableActivity",
	"JSCLASS" => "BizProcActivity",
	"CATEGORY" => array(
		"ID" => "other",
	),
);
?>
