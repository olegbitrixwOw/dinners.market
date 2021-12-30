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

<table id="user-list">
<tr>
    <th>ФИО</th>
    <th>Тел</th>
    <th>Логин</th>
    <th>Email</th>
</tr>
<?foreach($arResult["ITEMS"] as $arResult):?>

    <tr id="<?=$this->GetEditAreaId($arResult['ID']);?>">
        <td>
            <p class="user-name">
                <? if($arResult['NAME']){ ?>
                    <?=$arResult['NAME']?>&nbsp;<?=$arResult['LAST_NAME']?>&nbsp;<?=$arResult['SECOND_NAME']?>
                <? } ?>
                <span><a href="<?=$arResult["DETAIL_URL"]?>">подробнее</a></span>
            </p>
            <p><a href="worker-orders/?USER_ID=<?=$arResult["ID"]?>">заказы сотрудника</a></p>
        </td>
        <td><?if($arResult['PERSONAL_PHONE']){?><?=$arResult['PERSONAL_PHONE']?><?}?></td>
        <td><? if($arResult['LOGIN']){?><?=$arResult['LOGIN']?><? } ?></td>
        <td><? if($arResult['NAME']){ ?><?=$arResult['EMAIL']?><? } ?></td>
    </tr>
<?endforeach;?>
</table>
<? if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<br /><?=$arResult["NAV_STRING"]?>
<? endif;?>
</div>
