<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$bWasSelected = false;
$selectedItem = 0;
$depth = 1;

$current_day = false;
if(isset($_REQUEST["day_week"])){
    $current_day = htmlentities($_REQUEST["day_week"]);
}

// foreach ($arResult as $i => $arMenu) {
// 	if ($arMenu['SELECTED'] == true) {
// 		$bWasSelected = true;
// 		$selectedItem = $i;
// 		$depth = $arMenu["DEPTH_LEVEL"];
// 		break;
// 	}
// }

if ($bWasSelected) {
	for($i=$selectedItem; $i >= 0; $i--){
		if(isset($arResult[$i]) && $arResult[$i]["DEPTH_LEVEL"] < $depth){
			$depth--;
			$arResult[$i]["SELECTED"] = true;
		}
	}
}
foreach($arResult as $i => $arMenu){
	$code = substr($arMenu['LINK'], 8);
		switch ($code) {
			case '/rolls/':
				$class_css = 'icon-sushi';
				break;
			case '/garniry/':
				$class_css = 'icon-garniry';
				break;
			case '/deserty/':
				$class_css = 'icon-dessert';
				break;
			case '/drinks/':
				$class_css = 'icon-drink';
				break;
			case '/soups/':
				$class_css = 'icon-soup';
				break;
			
			case '/salads/':
				$class_css = 'icon-salad';
				break;

			case '/pastywok/':
				$class_css = 'icon-wok';
				break;

			case '/pastywok/':
				$class_css = 'icon-wok';
				break;

			case '/sendvichi_i_zakuski/':
				$class_css = 'icon-burger';
				break;

			case '/freshi_smuzi_limonad/':
				$class_css = 'icon-smoothie';
				break;

			case '/hot_dishes/':
				$class_css = 'icon-hot';
				break;

			default:
				$class_css = 'icon-pizza';
				break;
		}

	$arResult[$i]['CLASS_CSS'] = $class_css;

	if($current_day){
		$arResult[$i]['LINK'] = $arMenu['LINK'].'?day_week='.$current_day;
	}
	
}

