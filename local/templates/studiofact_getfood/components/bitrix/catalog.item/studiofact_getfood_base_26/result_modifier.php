<? if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Citfact\Getfood\Image;

/**
 * Ðåñàéçèíã èçîáðàæåíèé
 */

Image::resizeCatalogItem($arResult, 325, 245);

// устанавливаем день недели для текущего элемента (для корзины покупки товара)
$arResult['DAY_WEEK'] = false;
if($arResult['ITEM']['DISPLAY_PROPERTIES']['DAY_OF_WEEK']){

	if(!empty($arResult['ITEM']['DISPLAY_PROPERTIES']['DAY_OF_WEEK']['VALUE_XML_ID'])){
		foreach ($arResult['ITEM']['DISPLAY_PROPERTIES']['DAY_OF_WEEK']['VALUE_XML_ID'] as $key => $value){
			if($value == $arParams['DAY_WEEK']){
					$arResult['DAY_WEEK'] = $arParams['DAY_WEEK'];
				break;
			}
		}
	}
}
// pr($arParams['CUISINE']);
// pr($arResult['DAY_WEEK']);
// pr($arResult['ITEM']['DETAIL_PAGE_URL']);
// $url = substr($arResult['ITEM']['DETAIL_PAGE_URL'],0, -1);
// pr($url);
// pr(substr($url,0 , strrpos($url,'/')+1));
// pr($arResult['ITEM']['DETAIL_PAGE_URL']);