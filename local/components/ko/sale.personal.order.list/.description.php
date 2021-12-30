<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("SPOL_DEFAULT_TEMPLATE_NAME"),
	"DESCRIPTION" => GetMessage("SPOL_DEFAULT_TEMPLATE_DESCRIPTION"),
	"ICON" => "/images/sale_order_tbl.gif",
	"PATH" => array(
		"ID" => "alex",
		"CHILD" => array(
			"ID" => "sale_personal_order_list",
			"NAME" => "Заказы"
		)
	),
);
?>