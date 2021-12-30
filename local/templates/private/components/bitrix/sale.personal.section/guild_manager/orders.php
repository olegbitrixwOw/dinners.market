<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;

if ($arParams['SHOW_ORDER_PAGE'] !== 'Y')
{
	LocalRedirect($arParams['SEF_FOLDER']);
}	

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
      "ORGANIZATION"=>$arParams["USER"]["FIRM"]["ID"],
      "DATE"=>date('d.m.Y'),
      // "DATE"=>date('04.10.2020'),
    ),
    $component
);
?>

