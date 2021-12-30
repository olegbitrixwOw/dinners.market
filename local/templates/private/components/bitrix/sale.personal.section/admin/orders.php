<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;

if ($arParams['SHOW_ORDER_PAGE'] !== 'Y')
{
	LocalRedirect($arParams['SEF_FOLDER']);
}	

$firm_id = false;
if($_REQUEST['FIRM_ID']){    
  $firm_id = (int)$_REQUEST['FIRM_ID'];}
?>
<?if($firm_id):?>
<p><a href="/firm/orders/">Вернуться в список</a></p>
<?
$APPLICATION->IncludeComponent(
    "alex:sale.personal.order.list",
    "orders_by_date",
    array(
      "ACTIVE_DATE_FORMAT" => "d.m.Y",
      "AJAX"=>"N",
      "CACHE_GROUPS" => "Y",
      "CACHE_TIME" => "3600",
      "CACHE_TYPE" => "A",
      "DEFAULT_SORT" => "STATUS",
      "DISALLOW_CANCEL" => "N",
      "HISTORIC_STATUSES" => array("F"),
      "ID" => "",
      "NAV_TEMPLATE" => "",
      "ORDERS_PER_PAGE" => "",
      "PATH_TO_BASKET" => "",
      "PATH_TO_CANCEL" => "",
      "PATH_TO_CATALOG" => "/catalog/",
      "PATH_TO_COPY" => "",
      "PATH_TO_DETAIL" => $arResult["PATH_TO_ORDER_DETAIL"],
      "PATH_TO_PAYMENT" => "payment.php",
      "REFRESH_PRICES" => "N",
      "RESTRICT_CHANGE_PAYSYSTEM" => array("0"),
      "SAVE_IN_SESSION" => "Y",
      "SET_TITLE" => "Y",
      "STATUS_COLOR_F" => "gray",
      "STATUS_COLOR_N" => "green",
      "STATUS_COLOR_P" => "yellow",
      "STATUS_COLOR_PSEUDO_CANCELLED" => "red",
      "ORGANIZATION"=>$firm_id,
      "DATE"=>date('d.m.Y'),
      "PAGE"=>1
    ),
    $component
);
?>
<?else:?>
<?$APPLICATION->IncludeComponent(
  "alex:iblock.element.add", 
  "studiofact_getfood_orgs_orders", 
  array(
    "AJAX_MODE" => "N",
    "AJAX_OPTION_ADDITIONAL" => "",
    "AJAX_OPTION_HISTORY" => "N",
    "AJAX_OPTION_JUMP" => "N",
    "AJAX_OPTION_STYLE" => "Y",
    "ALLOW_DELETE" => "Y",
    "ALLOW_EDIT" => "Y",
    "CUSTOM_TITLE_DATE_ACTIVE_FROM" => "",
    "CUSTOM_TITLE_DATE_ACTIVE_TO" => "",
    "CUSTOM_TITLE_DETAIL_PICTURE" => "",
    "CUSTOM_TITLE_DETAIL_TEXT" => "",
    "CUSTOM_TITLE_IBLOCK_SECTION" => "",
    "CUSTOM_TITLE_NAME" => "",
    "CUSTOM_TITLE_PREVIEW_PICTURE" => "",
    "CUSTOM_TITLE_PREVIEW_TEXT" => "",
    "CUSTOM_TITLE_TAGS" => "",
    "DEFAULT_INPUT_SIZE" => "30",
    "DETAIL_TEXT_USE_HTML_EDITOR" => "N",
    "ELEMENT_ASSOC" => "CREATED_BY",
    "GROUPS" => array(
    ),
    "IBLOCK_ID" => "7",
    "IBLOCK_TYPE" => "guild",
    "LEVEL_LAST" => "Y",
    "MAX_FILE_SIZE" => "0",
    "MAX_LEVELS" => "100000",
    "MAX_USER_ENTRIES" => "100000",
    "NAV_ON_PAGE" => "20",
    "PREVIEW_TEXT_USE_HTML_EDITOR" => "N",
    "PROPERTY_CODES" => array(
      0 => "57",
      1 => "65",
      2 => "67",
      3 => "70",
      4 => "71",
      5 => "72",
      6 => "75",
      7 => "76",
      8 => "NAME",
      9 => "DETAIL_TEXT",
    ),
    "PROPERTY_CODES_REQUIRED" => array(
      0 => "65",
    ),
    "RESIZE_IMAGES" => "N",
    "SEF_MODE" => "N",
    "STATUS" => "ANY",
    "STATUS_NEW" => "N",
    "USER_MESSAGE_ADD" => "",
    "USER_MESSAGE_EDIT" => "",
    "USE_CAPTCHA" => "N",
    "COMPONENT_TEMPLATE" => "studiofact_getfood_orgs"
  ),
  false
);?>
<?endif?>

