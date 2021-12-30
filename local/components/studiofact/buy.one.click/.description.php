<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$arComponentDescription = array(
	"NAME" => GetMessage("BUY_ONE_CLICK_NAME"),
	"DESCRIPTION" => GetMessage("BUY_ONE_CLICK_DESCRIPTION"),
	"CACHE_PATH" => "Y",
	"SORT" => 10,
	"PATH" => array(
		"ID" => "service",
		"CHILD" => array(
			"ID" => "small_basket",
			"NAME" => GetMessage("BUY_ONE_CLICK_NAME"),
		),
	),
); ?>