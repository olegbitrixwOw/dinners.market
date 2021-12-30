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
if($arParams['IBLOCK_ID'] == IBLOCK_BALANCE){
	array_push($arParams['PROPERTY_CODES'], 80);
}
$APPLICATION->IncludeComponent("alex:iblock.element.add.form", "balance", $arParams, $component);
?>