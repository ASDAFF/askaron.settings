<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?
$arUserFields = array();

$res = CUserTypeEntity::GetList(
	array("SORT"=>"ASC"),
	array(
		"ENTITY_ID" => "ASKARON_SETTINGS",
		"LANG" => LANGUAGE_ID,
	)
);

while ( $arField = $res->GetNext() )
{
	$arUserFields[] = $arField;
}
?>

<tr>
	<td colspan="2">
		<?if ($arWorkflowVariables):?>
			<div class="adm-detail-content-item-block">
				<table width="100%" border="0" class="internal">
					<tr class="heading">
						<td><?=GetMessage("ASKARON_SETTINGS_SETVARIABLE_NAME")?></td>
						<td><?=GetMessage("ASKARON_SETTINGS_SETVARIABLE_ID")?></td>
						<?//<td>Type</td>?>
						<td><?=GetMessage("ASKARON_SETTINGS_SETVARIABLE_VALUE_FROM")?></td>
					</tr>
					<?foreach ( $arWorkflowVariables as $key => $arItem ):?>
						<tr>
							<td><?=htmlspecialcharsbx( $arItem["Name"] );?></td>
							<td><?=htmlspecialcharsbx( $key );?></td>
							<?/*  <td><?=htmlspecialcharsbx( $arItem["Type"] );?></td> */?>
							<td>
								<select name="variable_value_from[<?=$key?>]">
									<option value=""><?=GetMessage("ASKARON_SETTINGS_SETVARIABLE_NOT_SELECTED")?></option>
									<?foreach ($arUserFields as $arField):?>
										<option value="<?=$arField["FIELD_NAME"]?>"
											<?if ($arCurrentValues["variable_value_from"][ $key ] == $arField["FIELD_NAME"] ):?>
												selected="selected"
											<?endif?>
										><?=$arField["EDIT_FORM_LABEL"]?> (<?=$arField["FIELD_NAME"]?>)</option>
									<?endforeach?>
								</select>
							</td>
						</tr>
					<?endforeach?>
				</table>
			</div>
		<?endif?>
	</td>
</tr>
