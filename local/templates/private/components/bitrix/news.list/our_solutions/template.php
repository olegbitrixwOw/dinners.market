<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
?>
<div id="modal-panel">
    <div id="modal-but"><span><?=GetMessage('OUR_SITES')?></span></div>
    <div id="tutor" class="owl-carousel" style="">
        <?foreach($arResult["ITEMS"] as $arItem):?>
        <div class="slide">
            <a href="<?=$arItem["PROPERTIES"]["SOL_LINK"]["VALUE"]?>" class="panel-link">
                <img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="">
            </a>
            <div class="shop-name"><?=$arItem["NAME"]?></div>
        </div>
        <?endforeach;?>
    </div>
</div>