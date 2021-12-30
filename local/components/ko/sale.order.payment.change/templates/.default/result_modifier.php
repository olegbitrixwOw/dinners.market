<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
foreach ($arResult['PAYSYSTEMS_LIST'] as $key => $paySystem){
	// pr($paySystem);
	if($paySystem['PAY_SYSTEM_ID']==PAY_SYSTEM_COMPANY){
		unset($arResult['PAYSYSTEMS_LIST'][$key]);
	}
}
