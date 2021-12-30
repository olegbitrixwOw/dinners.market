<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
	"NAME" => "Корзина в парвом блоке",
	"DESCRIPTION" => GetMessage("SBBL_DEFAULT_TEMPLATE_DESCRIPTION"),
	"ICON" => "/images/sale_basket.gif",
	"PATH" => array(
		"ID" => "alex",
		"CHILD" => array(
			"ID" => "sale_basket",
			"NAME" => GetMessage("SBBL_NAME")
		)
	),
);
?>