<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => GetMessage("SPO_NAME"),
	"DESCRIPTION" => GetMessage("SPO_DESCRIPTION"),
	"ICON" => "/images/icon.gif",
	"PATH" => array(
		"ID" => "alex",
		"CHILD" => array(
			"ID" => "sale_personal_order_list",
			"NAME" => GetMessage("SPO_MAIN")
		)
	),
);
?>