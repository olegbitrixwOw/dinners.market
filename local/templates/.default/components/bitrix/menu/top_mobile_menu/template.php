<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? if (count($arResult) < 1) { return; } 
global $USER;
$arUser = getUserData($USER->GetID());
// текущий день недели
if(isset($_REQUEST["day_week"])){
    $lid =  htmlentities($_REQUEST["day_week"]);
}else{
    $lid  = substr(date("l"), 0, 2);
}
?>
<div class="top-mobile-menu">
    <?foreach($arResult as $arItem):?>
            <span class="<?if ($arItem["SELECTED"]) { echo 'selected'; } ?>"><a href="<? echo $arItem['LINK']?>?day_week=<?=$lid?>"><?=$arItem["TEXT"];?></a></span>
    <?endforeach;?>
</div>
