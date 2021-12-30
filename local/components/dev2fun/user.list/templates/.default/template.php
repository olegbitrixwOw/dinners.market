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
//print_pre($arResult);
?>

<div class="news-list">
<? if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<? endif;?>
<?foreach($arResult["ITEMS"] as $arResult):?>
    <div class="news-item" id="<?=$this->GetEditAreaId($arResult['ID']);?>">
        <? if($arResult['LOGIN']){ ?>
                <h1><?=$arResult['LOGIN']?></h1>
        <? } ?>
        <? if($arResult['PERSONAL_PHOTO']){ ?>
            <?
            if($arParams['RESIZE_PERSONAL_PHOTO']){
                $imgSrc = $arResult["PERSONAL_PHOTO"]["RESIZE"]['src'];
                $imgWidth = $arResult["PERSONAL_PHOTO"]["RESIZE"]['width'];
                $imgHeight = $arResult["PERSONAL_PHOTO"]["RESIZE"]['height'];
            } else {
                $imgSrc = $arResult["PERSONAL_PHOTO"]['SRC'];
                $imgWidth = $arResult["PERSONAL_PHOTO"]["WIDTH"];
                $imgHeight = $arResult["PERSONAL_PHOTO"]["HEIGHT"];
            }
            ?>
            <a href="<?=$arResult["DETAIL_URL"]?>">
                <img
                    class="preview_picture"
                    src="<?=$imgSrc?>"
                    width="<?=$imgWidth?>"
                    height="<?=$imgHeight?>"
                    alt="<?=$arResult["PERSONAL_PHOTO"]["ALT"]?>"
                    title="<?=$arResult["PERSONAL_PHOTO"]["TITLE"]?>"
                    style="float:left"
                    />
            </a>
        <? } ?>
    </div>
<?endforeach;?>
<? if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<? endif;?>
</div>
