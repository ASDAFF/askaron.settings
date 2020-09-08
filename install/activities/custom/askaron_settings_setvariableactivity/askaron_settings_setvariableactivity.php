<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CBPAskaron_Settings_SetVariableActivity
	extends CBPActivity
{
	public function __construct($name)
	{
		parent::__construct($name);
		$this->arProperties = array(
			"Title" => "",
			//"ExecuteCode" => "",
			//"VariableCode" => "",
			//"SettingsFromCode" => "",
			"VariableValueFrom" => array(),
		);
	}

	public function Execute()
	{
		$arVar = $this->getRawProperty('VariableValueFrom');
		if ( is_array($arVar) )
		{
			foreach ($arVar as $variable => $from )
			{
				$value = \Bitrix\Main\Config\Option::get("askaron.settings", $from );
				$this->SetVariable( $variable, $value );
			}
		}

		return CBPActivityExecutionStatus::Closed;
	}

	public static function ValidateProperties($arTestProperties = array(), CBPWorkflowTemplateUser $user = null)
	{
		$arErrors = array();

		if (!$arTestProperties["VariableValueFrom"])
		{
			$arErrors[] = array(
				"code" => "EMPTY_VARIABLE_CODE",
				"message" => GetMessage("ASKARON_SETTINGS_EMPTY_VARIABLE_CODE"),
			);
		}

		return array_merge($arErrors, parent::ValidateProperties($arTestProperties, $user));
	}

	public static function GetPropertiesDialog($documentType, $activityName, $arWorkflowTemplate, $arWorkflowParameters, $arWorkflowVariables, $arCurrentValues = null, $formName = "")
	{
		$runtime = CBPRuntime::GetRuntime();

		if (!is_array($arWorkflowParameters))
			$arWorkflowParameters = array();
		if (!is_array($arWorkflowVariables))
			$arWorkflowVariables = array();

		if (!is_array($arCurrentValues))
		{
			$arCurrentValues = array(
				//"execute_code" => "",
				//"variable_code" => "",
				//"settings_from_code" => "",

				"variable_value_from" => array(),
			);

			$arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName($arWorkflowTemplate, $activityName);
			if (is_array($arCurrentActivity["Properties"]))
			{

				//$arCurrentValues["execute_code"] = $arCurrentActivity["Properties"]["ExecuteCode"];
				//$arCurrentValues["variable_code"] = $arCurrentActivity["Properties"]["VariableCode"];
				//$arCurrentValues["settings_from_code"] = $arCurrentActivity["Properties"]["SettingsFromCode"];
				$arCurrentValues["variable_value_from"] = $arCurrentActivity["Properties"]["VariableValueFrom"];

			}
		}

		return $runtime->ExecuteResourceFile(
			__FILE__,
			"properties_dialog.php",
			array(
				"arCurrentValues" => $arCurrentValues,
				"formName" => $formName,
				"arWorkflowVariables" => $arWorkflowVariables,
			)
		);
	}

	public static function GetPropertiesDialogValues($documentType, $activityName, &$arWorkflowTemplate, &$arWorkflowParameters, &$arWorkflowVariables, $arCurrentValues, &$arErrors)
	{
		$arErrors = array();

		$runtime = CBPRuntime::GetRuntime();

		$arProperties = array(
			//"ExecuteCode" => $arCurrentValues["execute_code"],
			//"VariableCode" => $arCurrentValues["variable_code"],
			//"SettingsFromCode" => $arCurrentValues["settings_from_code"],
			"VariableValueFrom" => $arCurrentValues["variable_value_from"],
		);

		if (!is_array($arProperties["VariableValueFrom"]))
		{
			$arProperties["VariableValueFrom"] = array();
		}

		foreach ($arProperties["VariableValueFrom"] as $key => $value)
		{
			if (strlen($value) <= 0)
			{
				unset($arProperties["VariableValueFrom"][$key]);
			}
		}


		$arErrors = self::ValidateProperties($arProperties, new CBPWorkflowTemplateUser(CBPWorkflowTemplateUser::CurrentUser));
		if (count($arErrors) > 0)
			return false;

		$arCurrentActivity = &CBPWorkflowTemplateLoader::FindActivityByName($arWorkflowTemplate, $activityName);
		$arCurrentActivity["Properties"] = $arProperties;

		return true;
	}
}
?>