<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
use Bitrix\Main\Application; 
$request = Application::getInstance()->getContext()->getRequest(); 
if(empty($arResult))
	return "";
// var_dump($arResult);
foreach ($arResult as $key => $arItem) {
	if($arItem["LINK"] == '/firm/workers/worker-orders/'){
		$arResult[$key]["LINK"] = $arResult[$key]["LINK"].'?USER_ID='.$request->get("USER_ID");
	}
	if($arItem["LINK"] == '/firm/workers/worker-orders/order/'){
		$arResult[$key]["LINK"] = $arResult[$key]["LINK"].'?ID='.$request->get("ID");
	}
}

// die();
$strReturn = '<div class="bx_breadcrumbs"><ul class="clearfix">';
$num_items = count($arResult);
for ($index = 0, $itemSize = $num_items; $index < $itemSize; $index++) {
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);

	if ($index > 0) {
		$strReturn .= '<li class="bx_breadcrumbs__separator"><span> / </span></li>';
	}

	if ($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
		$strReturn .= '<li><a href="'.$arResult[$index]["LINK"].'" title="'.$title.'">'.$title.'</a></li>';
	else
		$strReturn .= '<li><span>'.$title.'</span></li>';
}

$strReturn .= '</ul></div>';

return $strReturn;
?>