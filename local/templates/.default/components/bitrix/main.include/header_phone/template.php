<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
$this->setFrameMode(true);

if($arResult["FILE"] <> ''){
    ob_start();
    include($arResult["FILE"]);
    $output = ob_get_contents();
    ob_end_clean();
    $output=strip_tags($output);
    $output_number = str_replace(array(" ",chr(13),chr(10),chr(32),"&nbsp;","(",")","-",":","/","\\",),"",$output);
    $output_a = '<a itemprop="telephone" class="phone__number" href="tel:'.$output_number.'">';
    $output = $output_a.$output.'</a>';
    echo $output;
}