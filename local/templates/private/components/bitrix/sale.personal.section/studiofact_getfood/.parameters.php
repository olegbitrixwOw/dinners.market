<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Catalog;
use Bitrix\Iblock;

use Bitrix\Main\ModuleManager; 
use Bitrix\Main\Web\Json;
use Bitrix\Main\Context;

$arTemplateParameters['DAY_WEEK'] = array(
	"TYPE" => "STRING",
	"PARENT" => "ADDITIONAL_SETTINGS",
	"NAME" => GetMessage("DAY_WEEK"),
	"TYPE" => "STRING",
	"MULTIPLE" => "N",
	"DEFAULT" =>"s1",
	"PARENT" => "ADDITIONAL_SETTINGS",
);

$arTemplateParameters["SHOW_OTHER_FIRM_EMPLOYEER_ORDERS"] = array(
		"PARENT" => "ADDITIONAL_SETTINGS",
		"NAME" => GetMessage('SHOW_OTHER_FIRM_EMPLOYEER_ORDERS'),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y"
	);

