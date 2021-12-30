<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");?>
<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Mail\Event;
use Bitrix\Main\Context;
global $APPLICATION; 
$request = Context::getCurrent()->getRequest();
$data = [];
$date = htmlspecialcharsEx($request->getPost('date'));
$user_id = htmlspecialcharsEx($request->getPost('user_id'));
$page = intval($request->getPost('page'));
$data['DATE'] = $date;
ob_start(); // тут будет вызов компонета?>
<?$APPLICATION->IncludeComponent(
	"alex:sale.personal.order.list",
	"studiofact_getfood",
	Array(
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"DEFAULT_SORT" => "STATUS",
		"DISALLOW_CANCEL" => "N",
		"HISTORIC_STATUSES" => array("F"),
		"ID" => "",
		"NAV_TEMPLATE" => "",
		"ORDERS_PER_PAGE" => "1",
		"PATH_TO_BASKET" => "",
		"PATH_TO_CANCEL" => "",
		"PATH_TO_CATALOG" => "/catalog/",
		"PATH_TO_COPY" => "",
		"PATH_TO_DETAIL" => "",
		"PATH_TO_PAYMENT" => "payment.php",
		"REFRESH_PRICES" => "N",
		"RESTRICT_CHANGE_PAYSYSTEM" => array("0"),
		"SAVE_IN_SESSION" => "Y",
		"SET_TITLE" => "Y",
		"STATUS_COLOR_F" => "gray",
		"STATUS_COLOR_N" => "green",
		"STATUS_COLOR_P" => "yellow",
		"STATUS_COLOR_PSEUDO_CANCELLED" => "red",
		"DATE" => $date,
		"USER_ID" => $user_id,
		"PAGE"=>$page		
	)
);?>
<?
$data['ITEMS'] = ob_get_clean();
echo json_encode($data);
?>
<?die();?>
