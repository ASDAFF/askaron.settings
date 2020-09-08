<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once( dirname(__FILE__)."/../prolog.php" );
use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
//CJSCore::Init('clipboard');

$errorText = "";

$module_id = "askaron.settings";
$install_status=CModule::IncludeModuleEx($module_id);

// demo expired (3)
if( $install_status==3 )
{
	$APPLICATION->SetTitle( GetMessage("ASKARON_SETTINGS_TITLE") );
	require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
	CAdminMessage::ShowMessage(
		Array(
			"TYPE"=>"ERROR",
			"MESSAGE"=>GetMessage("askaron_settings_prolog_status_demo_expired"),
			"DETAILS"=>GetMessage("askaron_settings_prolog_buy_html"),
			"HTML"=>true
		)
	);
}
else
{
	$RIGHT = $APPLICATION->GetGroupRight($module_id);
	$RIGHT_W = ($RIGHT>="W");
	$RIGHT_R = ($RIGHT>="R");

	if( !$RIGHT_R )
	{
		$APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
	}

	global $USER_FIELD_MANAGER, $APPLICATION;

	$ID = 1;

	if (
		$REQUEST_METHOD == "POST"
		&& strlen($Update) > 0
		&& $RIGHT_W
		&& check_bitrix_sessid()
	)
	{
		$arUpdateFields = array();
		$USER_FIELD_MANAGER->EditFormAddFields("ASKARON_SETTINGS", $arUpdateFields); // fill $arUpdateFields from $_POST and $_FILES

		$obSettings = new CAskaronSettings;
		$res = $obSettings->Update($arUpdateFields);
		if ($res)
		{
			LocalRedirect($APPLICATION->GetCurPageParam("ok=Y", array("ok")));
		}
		else
		{
			$errorText = $obSettings->LAST_ERROR;
		}
	}

	// Title
	$APPLICATION->SetTitle(GetMessage("ASKARON_SETTINGS_TITLE"));
	require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

	if ($install_status == 2)
	{
		CAdminMessage::ShowMessage(
			Array(
				"TYPE" => "OK",
				"MESSAGE" => Loc::getMessage("askaron_settings_prolog_status_demo"),
				"DETAILS" => Loc::getMessage("askaron_settings_prolog_buy_html"),
				"HTML" => true
			)
		);
	}

	if (!$RIGHT_W)
	{
		CAdminMessage::ShowMessage(
			Array(
				"TYPE" => "OK",
				"MESSAGE" => Loc::getMessage("ASKARON_SETTINGS_READ_ONLY"),
				"DETAILS" => "",
				"HTML" => true
			)
		);
	}


	$aTabs = array(
		array("DIV" => "edit1", "TAB" => GetMessage("ASKARON_SETTINGS_TAB1_TITLE"), "ICON" => "", "TITLE" => GetMessage("ASKARON_SETTINGS_TAB1_TITLE")),
	);
	?>
	<? if (isset($_REQUEST["ok"]) && $_REQUEST["ok"] == "Y"):?>
	<?
	CAdminMessage::ShowMessage(
		Array(
			"TYPE" => "OK",
			"MESSAGE" => GetMessage("ASKARON_SETTINGS_SUCCESS"),
			"DETAILS" => "",
			"HTML" => true
		)
	);
	?>

<?endif ?>

	<? if (strlen($errorText) > 0):?>
	<?
	CAdminMessage::ShowMessage(
		Array(
			"TYPE" => "ERROR",
			"MESSAGE" => $errorText,
			"DETAILS" => "",
			"HTML" => true
		)
	);
	?>

<?endif ?>

	<?if ( $USER->IsAdmin() ):?>
		<?= BeginNote(); ?>
		<?= GetMessage("ASKARON_SETTINGS_EDIT_RIGHTS"); ?>
		<?= EndNote(); ?>
	<?endif?>


	<?
	$tabControl = new CAdminTabControl("tabControl", $aTabs);
	$tabControl->Begin();
	?>
<form method="post" action="<? echo $APPLICATION->GetCurPage() ?>?&lang=<?= LANGUAGE_ID ?>"
	  enctype="multipart/form-data">
	<?= bitrix_sessid_post() ?>

	<? $tabControl->BeginNextTab();

	//if ($USER_FIELD_MANAGER->GetRights("ASKARON_SETTINGS") >= 'W')
//	if ($USER_FIELD_MANAGER->GetRights("ASKARON_SETTINGS") >= 'W')
//	{

		if (method_exists($USER_FIELD_MANAGER, 'showscript'))
		{
			echo $USER_FIELD_MANAGER->ShowScript();
		}
		?>

		<?if ( $USER->IsAdmin() ):?>
			<tr>
				<td colspan="2" align="left">

					<a href="/bitrix/admin/userfield_edit.php?lang=<?= LANGUAGE_ID ?><?
					?>&amp;ENTITY_ID=ASKARON_SETTINGS&amp;back_url=<?= urlencode($APPLICATION->GetCurPageParam() . '&tabControl_active_tab=user_fields_tab') ?><?
					?>"><?= GetMessage("ASKARON_SETTINGS_ADD_UF") ?></a>
					<br><br>
				</td>
			</tr>
		<?endif?>
		<?


		$bVarsFromForm = false;
		$arUserFields = $USER_FIELD_MANAGER->GetUserFields("ASKARON_SETTINGS", $ID, LANGUAGE_ID);
		foreach ($arUserFields as $FIELD_NAME => $arUserField)
		{
			$arUserField['VALUE_ID'] = $ID;
			?>
			<tr>
				<td colspan="2" style="color: #CCC;"><?= $arUserField["SORT"] ?> <?= $FIELD_NAME ?></td>
			</tr>
			<?
			echo $USER_FIELD_MANAGER->GetEditFormHTML($bVarsFromForm, $GLOBALS[$FIELD_NAME], $arUserField);
		}

		?>


		<tr>
			<td colspan="2">
				<?= BeginNote(); ?>
				<? echo GetMessage("ASKARON_SETTINGS_EXAMPLE_TO_USE"); ?>
				<br>
				<br><strong>&lt;?echo \COption::GetOptionString( &quot;askaron.settings&quot;, &quot;UF_PHONE&quot;);?&gt;</strong>
				<br><strong>&lt;?$email = \COption::GetOptionString( &quot;askaron.settings&quot;, &quot;UF_EMAIL&quot;);?&gt;</strong>
				<br>
				<br>D7:
				<br><strong>&lt;?echo \Bitrix\Main\Config\Option::get( &quot;askaron.settings&quot;, &quot;UF_PHONE&quot;);?&gt;</strong>
				<br><strong>&lt;?$email = \Bitrix\Main\Config\Option::get( &quot;askaron.settings&quot;, &quot;UF_EMAIL&quot;);?&gt;</strong>
				<br>
				<br><?= GetMessage("ASKARON_SETTINGS_MODULE_NOTES") ?>
				<?= EndNote(); ?>
			</td>
		</tr>


		<? $tabControl->Buttons(); ?>

		<?if ( $RIGHT_W ):?>
			<input type="submit" name="Update" value="<?= GetMessage("MAIN_SAVE") ?>"
				   title="<?= GetMessage("MAIN_OPT_SAVE_TITLE") ?>">
		<?endif?>
		<?/*
		<input type="submit" name="RestoreDefaults" title="<?echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="confirm('<?echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo GetMessage("MAIN_RESTORE_DEFAULTS")?>">
		 */ ?>
		<? $tabControl->End(); ?>
		</form>

		<?
//	}
}
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>