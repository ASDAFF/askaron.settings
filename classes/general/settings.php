<?
class CAskaronSettings
{
	static private $arFields = false;
	public $LAST_ERROR = "";
	
	public static function GetFields()
	{
		$arResult = array();
		
		if ( is_array( self::$arFields ) )
		{
			$arResult = self::$arFields;
		}
		else
		{
			$arResult = array();
			
			//$cache_id = md5($class_name);
			//if( $obCache->InitCache( $cache_ttl, $cache_id, $cache_dir ) )

			$obCache = new CPHPCache;
			if( $obCache->InitCache( 14400, 1, "askaron.settings" ) )
			{
				$arResult = $obCache->GetVars();
			}
			elseif( $obCache->StartDataCache() )
			{
//				if ( defined('BX_COMP_MANAGED_CACHE') && is_object($GLOBALS['CACHE_MANAGER']) )
//				{				
//					global $CACHE_MANAGER;
//					$CACHE_MANAGER->StartTagCache( "askaron.settings" );
//				}
				
				$arResult = self::__GetFields();
					
					
//				if ( defined('BX_COMP_MANAGED_CACHE') && is_object($GLOBALS['CACHE_MANAGER']) )
//				{
//					$CACHE_MANAGER->EndTagCache( "askaron.settings" );
//				}

				$obCache->EndDataCache($arResult);
			}	
		
			self::$arFields = $arResult;			
		}
		
		return $arResult;
	}
	
	private static function __GetFields()
	{
		global $USER_FIELD_MANAGER;
		
		$arResult = array();
		
		$ID = 1;
		$entity_id = "ASKARON_SETTINGS";

		$arUserFields = $USER_FIELD_MANAGER->GetUserFields( $entity_id, $ID, LANGUAGE_ID );

		foreach($arUserFields as $FIELD_NAME => $arUserField)
		{
			$arResult[$FIELD_NAME] = $arUserField['VALUE'];
		}
		
		return $arResult;
	}	
	
	public static function ClearCache()
	{
		$obCache = new CPHPCache();
		$obCache->CleanDir("askaron.settings");
		
		//BXClearCache(true, "/askaron.settings/");
	}
	
	public function Update( $arFields ) 
	{
		$result = true;
		global $APPLICATION;
		
		$this->LAST_ERROR = "";
		
		$ID = 1;
		$entity_id = "ASKARON_SETTINGS";

		$APPLICATION->ResetException();
		$events = GetModuleEvents( "askaron.settings", "OnBeforeSettingsUpdate" );
		while ($arEvent = $events->Fetch())
		{
			$bEventRes = ExecuteModuleEventEx($arEvent, array( &$arFields ) );
			if ( $bEventRes === false )
			{
				if($err = $APPLICATION->GetException())
				{
                    $this->LAST_ERROR .= $err->GetString();
				}
                else
                {
                    $APPLICATION->ThrowException("Unknown error");
                    $this->LAST_ERROR .= "Unknown error";
                }				
				
				$result = false;
				break;
			}
		}		
		
		if ( $result )
		{
			global $USER_FIELD_MANAGER;
			
			// TODO: check required fields
			
			$USER_FIELD_MANAGER->Update( $entity_id, $ID, $arFields );
			self::ClearCache();

			$events = GetModuleEvents( "askaron.settings", "OnAfterSettingsUpdate" );
			while ($arEvent = $events->Fetch())
			{
				ExecuteModuleEventEx($arEvent, array( &$arFields ) );
			}
		}
		
		return $result;
	}
}
?>
