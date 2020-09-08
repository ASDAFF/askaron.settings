<?
###################################################
# askaron.settings module
# Copyright (c) 2011-2019 Askaron Systems ltd.
# http://askaron.ru
# mailto:mail@askaron.ru
###################################################


IncludeModuleLangFile(__FILE__);
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/options.php");
require_once( "prolog.php" );

$module_id = "askaron.settings";
$install_status = CModule::IncludeModuleEx($module_id);

if( $install_status==0 )
{
	// module not found (0)
}
elseif( $install_status==3 )
{
	//demo expired (3)
	CAdminMessage::ShowMessage(
		Array(
			"TYPE"=>"ERROR",
			"MESSAGE" => GetMessage("askaron_settings_prolog_status_demo_expired"),
			"DETAILS"=> GetMessage("askaron_settings_prolog_buy_html"),
			"HTML"=>true
		)
	);
}
else
{
	$RIGHT = $APPLICATION->GetGroupRight($module_id);
	$RIGHT_W = ($RIGHT>="W");
	$RIGHT_R = ($RIGHT>="R");
	//$bShowSettings = CAskaronSettings::ShowSettingsForUser();

	if ($RIGHT_R)
	{
		$arGroups = array(
			"group1" => array(
				"NAME" => GetMessage("ASKARON_URLPAY_LINK"),
			),
		);

		$arOptions = array(
//			array(
//				"CODE" => "SITE",
//				"SITE_ID" => "",
//				"NAME" => GetMessage("ASKARON_URLPAY_SITE"),
//				"TYPE" => "TEXT",
//				"HELP" => GetMessage("ASKARON_URLPAY_SITE_HELP"),
//				"GROUP" => "group1",
//			),
//			array(
//				"CODE" => "TTL",
//				"SITE_ID" => "",
//				"NAME" => GetMessage("ASKARON_URLPAY_TTL"),
//				"TYPE" => "INTEGER",
//				"HELP" => GetMessage("ASKARON_URLPAY_TTL_HELP"),
//				"GROUP" => "group1",
//			),
//			array(
//				"CODE" => "URL_COMPONENT",
//				"SITE_ID" => "",
//				"NAME" => GetMessage("ASKARON_URLPAY_URL_COMPONENT"),
//				"TYPE" => "TEXT",
//				"HELP" => GetMessage("ASKARON_URLPAY_URL_COMPONENT_HELP"),
//				"GROUP" => "group1",
//			),
		);

		$arErrors = array();
		$arSettings = array();

		if (
			$REQUEST_METHOD == "POST"
			&& strlen($Update) > 0
			&& $RIGHT_W
			&& check_bitrix_sessid()
		)
		{
			//if ($bShowSettings)
			//{
				// Update all options
				foreach ($arOptions as $key => $arOption)
				{
					if ($arOption["TYPE"] == "CHECKBOX")
					{
						if (isset($_REQUEST["arrOptions"][$key]) && $_REQUEST["arrOptions"][$key] == "Y")
						{
							COption::SetOptionString($module_id, $arOption["CODE"], "Y", false, $arOption["SITE_ID"]);
						} else
						{
							COption::SetOptionString($module_id, $arOption["CODE"], "N", false, $arOption["SITE_ID"]);
						}
					}

					if ($arOption["TYPE"] == "TEXT")
					{
						if (isset($_REQUEST["arrOptions"][$key]))
						{
							COption::SetOptionString($module_id, $arOption["CODE"], $_REQUEST["arrOptions"][$key], false, $arOption["SITE_ID"]);
						}
					}

					if ($arOption["TYPE"] == "INTEGER")
					{
						if (isset($_REQUEST["arrOptions"][$key]))
						{
							if (strlen($_REQUEST["arrOptions"][$key]) > 0)
							{
								$val = intval($_REQUEST["arrOptions"][$key]);
								$min = $arOption["MIN"];

								if (strlen($min) > 0 && $val < $min)
								{
									$val = $min;
								}

								COption::SetOptionString($module_id, $arOption["CODE"], $val, false, $arOption["SITE_ID"]);
							}
						}
					}
				}

			//}
		}


		if (
			$REQUEST_METHOD == "POST"
			&& $RIGHT_W
			&& strlen($RestoreDefaults) > 0
			&& check_bitrix_sessid()
		)
		{
			foreach ($arOptions as $key => $arOption)
			{
				\Bitrix\Main\Config\Option::delete(
					$module_id,
					array(
						"name" => $arOption["CODE"],
					)
				);
			}



//			$random_value_tmp = COption::GetOptionString($module_id, "random_value");
//
//			COption::RemoveOption($module_id);
//
//			COption::SetOptionString($module_id, "random_value", $random_value_tmp);


			$z = CGroup::GetList($v1 = "id", $v2 = "asc", array("ACTIVE" => "Y", "ADMIN" => "N"), $get_users_amount = "N");
			while ($zr = $z->Fetch())
			{
				$APPLICATION->DelGroupRight($module_id, array($zr["ID"]));
			}
		}


		// init all options:
		$arDisplayOptions = array();

		foreach ($arOptions as $key => $arOption)
		{
			$arOptionAdd = $arOption;

			$option_value = COption::GetOptionString($module_id, $arOption["CODE"], "", $arOption["SITE_ID"]);

			$arOptionAdd["INPUT_ID"] = "option_" . $key;
			$arOptionAdd["INPUT_NAME"] = "arrOptions[" . $key . "]";
			$arOptionAdd["~INPUT_VALUE"] = $option_value;
			$arOptionAdd["INPUT_VALUE"] = htmlspecialcharsbx($option_value);

			$arDisplayOptions[$key] = $arOptionAdd;
		}

		foreach ($arGroups as $group_key => $arGroup)
		{
			$arGroups[$group_key]["~NAME"] = $arGroup["NAME"];
			$arGroups[$group_key]["NAME"] = htmlspecialcharsbx($arGroup["NAME"]);
		}


		?>

		<?= BeginNote(); ?>
		<?= GetMessage("ASKARON_SETTINGS_MODULE_NOTES"); ?>
		<?= EndNote(); ?>

		<?= BeginNote(); ?>
		<?= GetMessage("ASKARON_SETTINGS_MODULE_NOTES2"); ?>
		<?= EndNote(); ?>

		<?

		$aTabs = array(
			//array("DIV" => "edit1", "TAB" => GetMessage("MAIN_TAB_SET"), "ICON" => "", "TITLE" => GetMessage("MAIN_TAB_TITLE_SET")),
			array("DIV" => "edit3", "TAB" => GetMessage("MAIN_TAB_RIGHTS"), "ICON" => "", "TITLE" => GetMessage("MAIN_TAB_TITLE_RIGHTS")),
		);

		//d($arDisplayOptions);
		$tabControl = new CAdminTabControl("tabControl", $aTabs);
		$tabControl->Begin();
		?>




		<form method="post"
			  action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=htmlspecialchars($mid)?>&lang=<?=LANGUAGE_ID?>&mid_menu=<?=urlencode($_REQUEST["mid_menu"])?>"
			<?= bitrix_sessid_post() ?>

<?/*
			<? $tabControl->BeginNextTab(); ?>

			<tr>
				<td width="100%" style="" colspan="2">
				<?
				//demo (2)
				if ( $install_status == 2 )
				{
					CAdminMessage::ShowMessage(
						Array(
							"TYPE"=>"OK",
							"MESSAGE" => GetMessage("askaron_settings_prolog_status_demo"),
							"DETAILS"=> GetMessage("askaron_settings_prolog_buy_html"),
							"HTML"=>true
						)
					);
				}
				?>

				</td>
			</tr>

			<?// if ($bShowSettings):?>
				<? foreach ($arGroups as $group_key => $arGroup):?>

					<tr class="heading">
						<td valign="top" colspan="2" align="center"><?= $arGroup["NAME"] ?></td>
					</tr>

					<? foreach ($arDisplayOptions as $key => $arInput):?>

						<? if ($group_key == $arInput["GROUP"]):?>
							<tr>
								<td valign="top" width="50%" class="field-name"><label
										for="<?= $arInput["INPUT_ID"] ?>"><?= $arInput["NAME"] ?></label></td>
								<td valign="top" width="50%">
									<? if ($arInput["TYPE"] == "CHECKBOX"):?>
										<input
											type="checkbox"
											value="Y"
											id="<?= $arInput["INPUT_ID"] ?>"
											<? if ($arInput["INPUT_VALUE"] == "Y"):?>
												checked="checked"
											<?endif ?>
											name="<?= $arInput["INPUT_NAME"] ?>"
											/>
									<?endif ?>

									<? if (($arInput["TYPE"] == "TEXT" && $arInput["ROWS"] <= 1) || $arInput["TYPE"] == "INTEGER"):?>
										<input
											type="text"
											value="<?= $arInput["INPUT_VALUE"] ?>"
											id="<?= $arInput["INPUT_ID"] ?>"
											name="<?= $arInput["INPUT_NAME"] ?>"
											size="40"
											/>
									<?endif ?>

									<? if ($arInput["TYPE"] == "TEXT" && $arInput["ROWS"] > 1):?>

										<textarea id="<?= $arInput["INPUT_ID"] ?>" name="<?= $arInput["INPUT_NAME"] ?>"
												  rows="<?= $arInput["ROWS"] ?>"
												  cols="<?= $arInput["COLS"] ?>"><?= $arInput["INPUT_VALUE"] ?></textarea>

									<?endif ?>

									<? if (strlen($arInput["HELP"]) > 0):?>
										<?= BeginNote(); ?>
										<?= $arInput["HELP"]; ?>
										<?= EndNote(); ?>
									<?endif ?>
								</td>
							</tr>
						<?endif ?>

					<?endforeach ?>

				<?endforeach ?>
			<?//endif ?>
*/?>
			<? $tabControl->BeginNextTab(); ?>
			<? require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php"); ?>
			<? $tabControl->Buttons(); ?>
			<input <? if (!$RIGHT_W) echo "disabled" ?> type="submit" name="Update"
														value="<?= GetMessage("MAIN_SAVE") ?>"
														title="<?= GetMessage("MAIN_OPT_SAVE_TITLE") ?>">
			<input <? if (!$RIGHT_W) echo "disabled" ?> type="submit" name="RestoreDefaults"
														title="<? echo GetMessage("MAIN_HINT_RESTORE_DEFAULTS") ?>"
														OnClick="return confirm('<? echo AddSlashes(GetMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING")) ?>')"
														value="<? echo GetMessage("MAIN_RESTORE_DEFAULTS") ?>">
			<? $tabControl->End(); ?>
		</form>
		<?
	}
}
?>