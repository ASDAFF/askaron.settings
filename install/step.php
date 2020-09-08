<?if(!check_bitrix_sessid()) return;?>
<?
//IncludeModuleLangFile(__FILE__);
global $askaron_settings_global_errors;
$askaron_settings_global_errors = is_array($askaron_settings_global_errors) ? $askaron_settings_global_errors : array();

if(is_array($askaron_settings_global_errors) && count($askaron_settings_global_errors)>0)
{
	foreach($askaron_settings_global_errors as $val)
	{
			$alErrors .= $val."<br>";
	}
	echo CAdminMessage::ShowMessage(Array("TYPE"=>"ERROR", "MESSAGE" =>GetMessage("MOD_INST_ERR"), "DETAILS"=>$alErrors, "HTML"=>true));
}
else
{
	echo CAdminMessage::ShowNote(GetMessage("MOD_INST_OK"));
	
	?>
	<p><a href="askaron_settings_edit.php?lang=<?=LANG?>"><?=GetMessage("ASKARON_SETTINGS_SETTINGS_PAGE" )?></a></p>
	<?
}
?>
<form action="<?echo $APPLICATION->GetCurPage()?>">
	<input type="hidden" name="lang" value="<?echo LANG?>">
	<input type="submit" name="" value="<?echo GetMessage("MOD_BACK")?>">
</form>
