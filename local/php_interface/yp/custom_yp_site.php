<?php

define('YP_PRODUCTION_URL','');

// тут храним коды, магию
class CustomSite
{

	public static function get_day_of_week_code($short_name)
	{
		if($short_name==='Mo') return 28;
		if($short_name==='Tu') return 29;
		if($short_name==='We') return 30;
		if($short_name==='Th') return 31;
		if($short_name==='Fr') return 32;
		if($short_name==='Sa') return 33;
		if($short_name==='Su') return 34;

		return -1;
	} 

	public static function day_code_to_rus_name($code)
	{
		if($code==28) return 'понедельник';
		if($code==29) return 'вторник';
		if($code==30) return 'среду';
		if($code==31) return 'четверг';
		if($code==32) return 'пятницу';
		if($code==33) return 'субботу';
		if($code==34) return 'воскресенье';

		return -1;
	}

	public static function get_next_day_of_week_code($code)
	{
		if($code==34) return 28;
		if($code==28) return 29;
		if($code==29) return 30;
		if($code==30) return 31;
		if($code==31) return 32;
		if($code==32) return 33;
		if($code==33) return 34;

		return -1;
	}
}



/*** Юра ***/

function bx_current_link_with_get_parameter($name, $value)
{
    $query = $_GET;

    if ($name){
          $query[$name] = $value;
    }

    global $APPLICATION;
    $uri=$APPLICATION->GetCurPage();
    return $uri.'?'.http_build_query($query);
}

function get_day_of_week()
{
  $day_of_week=$_GET['day_week'];
  if (!$day_of_week)
  {
    // $day_of_week=Yp::get_current_day_code();
    $day_of_week = get_current_day_code();
  }

  return $day_of_week;
}


function get_current_day_code()
  {
    return substr(date("l"), 0, 2);
  }

function get_day_of_week_code($short_name)
{
    if($short_name==='Mo') return 28;
    if($short_name==='Tu') return 29;
    if($short_name==='We') return 30;
    if($short_name==='Th') return 31;
    if($short_name==='Fr') return 32;
    if($short_name==='Sa') return 33;
    if($short_name==='Su') return 34;

    return -1;
}

function next_day(string $day_name)
{
    if ($day_name==='Su') return 'Mo';
    if ($day_name==='Mo') return 'Tu';
    if ($day_name==='Tu') return 'We';
    if ($day_name==='We') return 'Th';
    if ($day_name==='Th') return 'Fr';
    if ($day_name==='Fr') return 'Sa';
    if ($day_name==='Sa') return 'Su';

    return 'err';
}

function include_catalog_for_day($day_name=null)
{
  if ($day_name==null)
    $day_name=get_day_of_week();

  $day_code=CustomSite::get_day_of_week_code($day_name);
  if (!$day_code)
    return;

  $filter_name='filter_catalog_for_day_'.$day_name;
  global $$filter_name;

  $$filter_name = Array(
    "PROPERTY_DAY_OF_WEEK" => [$day_code],
    "ACTIVE" => "Y",
    "ACTIVE_DATE" => "Y",
    "SECTION_GLOBAL_ACTIVE" => "Y"
  );

  ?>
  <?
  global $APPLICATION;
  $APPLICATION->IncludeComponent(
    "bitrix:catalog.section",
    "scrollbar2",
    array(
      "IBLOCK_TYPE" => "catalog",
      "IBLOCK_ID" => "2",
      "SECTION_ID" => "",
      "SECTION_CODE" => "",
      "SLIDER_ID" => 'new-products',
      "SECTION_NAME" => "Наши новинки",
      "SECTION_USER_FIELDS" => array(
        0 => "",
        1 => "",
      ),
      "ELEMENT_SORT_FIELD" => "sort",
      "ELEMENT_SORT_ORDER" => "asc",
      "ELEMENT_SORT_FIELD2" => "name",
      "ELEMENT_SORT_ORDER2" => "asc",
      "FILTER_NAME" => $filter_name,
      "INCLUDE_SUBSECTIONS" => "Y",
      "SHOW_ALL_WO_SECTION" => "Y",
      "HIDE_NOT_AVAILABLE" => "Y",
      "PAGE_ELEMENT_COUNT" => "20",
      "LINE_ELEMENT_COUNT" => "3",
      "PRODUCT_DISPLAY_MODE" => "Y",
      "ADD_PICT_PROP" => "MORE_PHOTO",
      "PROPERTY_CODE" => array(
        1 => "ARTNUMBER",
        2 => "PROTEINS",
        3 => "FATS",
        4 => "CARBOHYDRATES",
        5 => "CALORIE",
        6 => "CALORIES",
        7 => "TASTE",
        8 => "TYPES",
        9 => "DOUGH",
        10 => "DAY_OF_WEEK"
      ),
      "OFFERS_FIELD_CODE" => array(
        0 => "ID",
        1 => "NAME",
        2 => "PREVIEW_TEXT",
        3 => "PREVIEW_PICTURE",
        4 => "",
      ),
      "OFFERS_PROPERTY_CODE" => array(
        0 => "WEIGHT",
        1 => "DOUGH",
        2 => "QUANT",
        3 => "NACHINKA",
        4 => "",
      ),
      "OFFERS_SORT_FIELD" => "sort",
      "OFFERS_SORT_ORDER" => "asc",
      "OFFERS_SORT_FIELD2" => "name",
      "OFFERS_SORT_ORDER2" => "asc",
      "OFFERS_LIMIT" => "0",
      "SECTION_URL" => "/catalog/#SECTION_CODE#/",
      "DETAIL_URL" => "/catalog/#SECTION_CODE#/#ELEMENT_CODE#/",
      "SECTION_ID_VARIABLE" => "SECTION_ID",
      "AJAX_MODE" => "N",
      "AJAX_OPTION_JUMP" => "N",
      "AJAX_OPTION_STYLE" => "N",
      "AJAX_OPTION_HISTORY" => "N",
      "CACHE_TYPE" => "A",
      "CACHE_TIME" => "36000000",
      "CACHE_GROUPS" => "Y",
      "SET_META_KEYWORDS" => "N",
      "META_KEYWORDS" => "-",
      "SET_META_DESCRIPTION" => "N",
      "META_DESCRIPTION" => "-",
      "BROWSER_TITLE" => "-",
      "ADD_SECTIONS_CHAIN" => "N",
      "DISPLAY_COMPARE" => "Y",
      "SET_TITLE" => "N",
      "SET_STATUS_404" => "N",
      "CACHE_FILTER" => "Y",
      "PRICE_CODE" => array(
        0 => "BASE",
      ),
      "USE_PRICE_COUNT" => "N",
      "SHOW_PRICE_COUNT" => "1",
      "PRICE_VAT_INCLUDE" => "Y",
      "CONVERT_CURRENCY" => "Y",
      "BASKET_URL" => "/personal/cart/",
      "ACTION_VARIABLE" => "action",
      "PRODUCT_ID_VARIABLE" => "id",
      "USE_PRODUCT_QUANTITY" => "Y",
      "PRODUCT_QUANTITY_VARIABLE" => "quantity",
      "ADD_PROPERTIES_TO_BASKET" => "Y",
      "PRODUCT_PROPS_VARIABLE" => "prop",
      "PARTIAL_PRODUCT_PROPERTIES" => "Y",
      "PRODUCT_PROPERTIES" => array(),
      "OFFERS_CART_PROPERTIES" => array(
        0 => "WEIGHT",
        1 => "DOUGH",
        2 => "QUANT",
        3 => "NACHINKA",
      ),
      "PAGER_TEMPLATE" => ".default",
      "DISPLAY_TOP_PAGER" => "N",
      "DISPLAY_BOTTOM_PAGER" => "N",
      "PAGER_TITLE" => "Товары",
      "PAGER_SHOW_ALWAYS" => "N",
      "PAGER_DESC_NUMBERING" => "N",
      "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
      "PAGER_SHOW_ALL" => "N",
      "AJAX_OPTION_ADDITIONAL" => "",
      "MESS_BTN_BUY" => "Купить",
      "MESS_BTN_ADD_TO_BASKET" => "Купить",
      "MESS_BTN_SUBSCRIBE" => "Подписаться",
      "MESS_BTN_DETAIL" => "Подробнее",
      "MESS_NOT_AVAILABLE" => "Нет в наличии",
      "LABEL_PROP" => "NEWPRODUCT",
      "OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
      "OFFER_TREE_PROPS" => array(
        0 => "WEIGHT",
        1 => "DOUGH",
        2 => "NACHINKA",
        3 => "QUANT",
      ),
      "PRODUCT_SUBSCRIPTION" => "Y",
      "SHOW_DISCOUNT_PERCENT" => "Y",
      "DISCOUNT_PERCENT_POSITION" => "top-right",
      "SHOW_OLD_PRICE" => "Y",
      "SET_BROWSER_TITLE" => "N",
      "CURRENCY_ID" => "RUB",
      "TOP" => "Y",
      "COMPARE_PATH" => "/catalog/compare/",
      "NOT_LAZY_COUNTER" => "3",
      "COMPONENT_TEMPLATE" => "scroll",
      "BACKGROUND_IMAGE" => "-",
      "SEF_MODE" => "N",
      "SET_LAST_MODIFIED" => "N",
      "USE_MAIN_ELEMENT_SECTION" => "N",
      "PAGER_BASE_LINK_ENABLE" => "N",
      "SHOW_404" => "N",
      "MESSAGE_404" => "",
      "DISABLE_INIT_JS_IN_COMPONENT" => "N",
      "SHOW_CLOSE_POPUP" => "Y",
    ),
    false
  );
}

function append_day_of_week_to_filter(&$arrFilter)
{
  $day_name=get_day_of_week();
  $day_code=CustomSite::get_day_of_week_code($day_name);
  if (!$day_code)
    return;
  $arrFilter['PROPERTY_DAY_OF_WEEK']=[$day_code];
}
