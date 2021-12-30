<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("SOF_DEFAULT_TEMPLATE_NAME1"),
	"DESCRIPTION" => GetMessage("SOF_DEFAULT_TEMPLATE_DESCRIPTION"),
	"ICON" => "/images/sale_order_full.gif",
	"PATH" => array(
		"ID" => "alex",
		"CHILD" => array(
			"ID" => "sale_order",
			"NAME" => GetMessage("SOF_NAME")
		)
	),
);
?>