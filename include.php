<?
###################################################
# Askaron.Settings module                         #
# Copyright (c) 2011-2014 Askaron Systems ltd.    #
# http://askaron.ru                               #
# mailto:mail@askaron.ru                          #
###################################################

if (!function_exists('htmlspecialcharsbx'))
{
	function htmlspecialcharsbx($string, $flags=ENT_COMPAT)
	{
		//shitty function for php 5.4 where default encoding is UTF-8
		return htmlspecialchars($string, $flags, (defined("BX_UTF")? "UTF-8" : "ISO-8859-1"));
	}
}

CModule::AddAutoloadClasses("askaron.settings", array(	
	"CAskaronSettings"		=> "classes/general/settings.php",
));
?>