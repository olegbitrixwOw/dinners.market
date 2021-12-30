<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$bWasSelected = false;
$selectedItem = 0;
$depth = 1;

foreach ($arResult as $i => $arMenu) {
	if ($arMenu['SELECTED'] == true) {
		$bWasSelected = true;
		$selectedItem = $i;
		$depth = $arMenu["DEPTH_LEVEL"];
		break;
	}


	if($arUser['TYPE'] != 'manager' && $arUser['TYPE'] != 'employee'){	
		if($arMenu["LINK"] == '/personal/your-firm-orders/'){
				unset($arResult[$i]);
		}
	}

	if($arUser['TYPE'] != 'manager'){

		switch ($arMenu["LINK"]) {
			case '/personal/documents/':
				unset($arResult[$i]);
			break;

			case '/personal/documents/make/':
				unset($arResult[$i]);
			break;
		
			default:
			   # здесь ничего не делаем
			break;
		}
	}
}
if ($bWasSelected) {
	for($i=$selectedItem; $i >= 0; $i--){
		if(isset($arResult[$i]) && $arResult[$i]["DEPTH_LEVEL"] < $depth){
			$depth--;
			$arResult[$i]["SELECTED"] = true;
		}
	}
}