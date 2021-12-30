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
// pr($arResult);
// pr($arParams);
?>
<div class="news-list">
<? if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?><br />
<? endif;?>

<table id="user-list">
<tr>
    <th>ФИО</th>
    <th>Тел</th>
    <th>Логин</th>
    <th>Email</th>
    <th></th>
    <th></th>
</tr>
<?foreach($arResult["ITEMS"] as $arResult):?>

    <tr id="<?=$this->GetEditAreaId($arResult['ID']);?>">
        <td>
                <? if($arResult['NAME']){ ?>
                     <p class="user-record">&nbsp;<?=$arResult['LAST_NAME']?>&nbsp;<?=$arResult['NAME']?>&nbsp;<?=$arResult['SECOND_NAME']?></p>
                <? } ?>
        </td>
        <td><?if($arResult['PERSONAL_PHONE']){?><p class="user-record"><?=$arResult['PERSONAL_PHONE']?></p><?}?></td>
        <td><?if($arResult['LOGIN']){?><p class="user-record"><?=$arResult['LOGIN']?></p><? } ?></td>
        <td><?if($arResult['NAME']){ ?><p class="user-record"><?=$arResult['EMAIL']?></p><? } ?></td>
        <td style="padding: 1em 0 0 1em;">
           <a href="worker-orders/?USER_ID=<?=$arResult["ID"]?>"><span class="orders"></span></a>
        </td>
        <td style="padding: 1em 0 0 1em;">
            <a href="<?=$arResult["DETAIL_URL"].'?USER_ID='.$arResult["ID"]?>"><span class="tools"></span></a>
        </td>
    </tr> 
    
<?endforeach;?>
</table>
<? if($arParams["DISPLAY_BOTTOM_PAGER"]):?> 
	<br /><?=$arResult["NAV_STRING"]?>
<? endif;?>
</div> 
