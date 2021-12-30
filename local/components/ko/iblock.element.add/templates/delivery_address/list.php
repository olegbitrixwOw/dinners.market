<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(false);
// $arParams['FILTER'] = array("=PROPERTY_FIRM" => PROPERTY_FIRM_ID);
if($arParams["FIRM_ID"]){
	$arParams['FILTER'] = array("=PROPERTY_FIRM" => (int)$arParams["FIRM_ID"]);
	$APPLICATION->IncludeComponent("alex:iblock.element.add.list", "$templateName", $arParams, $component);
}?>