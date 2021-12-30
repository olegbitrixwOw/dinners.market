<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Заказы коллег",
	"DESCRIPTION" => GetMessage("SOF_DEFAULT_TEMPLATE_DESCRIPTION"),
	"ICON" => "/images/sale_order_full.gif",
	"PATH" => array(
		"ID" => "alex",
		"CHILD" => array(
			"ID" => "sale_personal_order_list",
			"NAME" => "Заказы"
		)
	),
);
?>