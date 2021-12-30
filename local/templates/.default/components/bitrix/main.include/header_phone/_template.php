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
    $output = str_replace(array(" ",chr(13),chr(10),chr(32),"&nbsp;","(",")","-",":","/","\\",),"",$output);
    $output_a='<a itemprop="telephone" class="phone__number" href="tel:'.$output.'">';
    switch (strlen($output)){
        case 4:
            $output = $output_a.substr($output,0,2).'-'.substr($output,2,2).'</a>';
            break;
        case 5:
            $output = $output_a.substr($output,0,1).'-'.substr($output,1,2).'-'.substr($output,3,2).'</a>';
            break;
        case 6:
            $output = $output_a.substr($output,0,2).'-'.substr($output,2,2).'-'.substr($output,4,2).'</a>';
            break;
        case 7:
            $output = $output_a.substr($output,0,3).'-'.substr($output,3,2).'-'.substr($output,5,2).'</a>';
            break;
        case 11:
            $output = $output_a.substr($output,0,1).' ('.substr($output,1,3).') '.substr($output,4,3).' '.substr($output,7,2).' '.substr($output,9,2).'</a>';
            break;
        case 12:
            $output = $output_a.substr($output,0,2).' ('.substr($output,2,3).') '.substr($output,5,3).' '.substr($output,8,2).' '.substr($output,10,2).'</a>';
            break;
        default:
            $output = $output_a.$output.'</a>';
    }
    echo $output;
}