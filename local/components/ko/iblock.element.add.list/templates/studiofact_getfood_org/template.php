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

$colspan = 2;
if ($arResult["CAN_EDIT"] == "Y") $colspan++;
if ($arResult["CAN_DELETE"] == "Y") $colspan++;
?>
<?if (strlen($arResult["MESSAGE"]) > 0):?>
	<?ShowNote($arResult["MESSAGE"])?>
<?endif?>

<table id="user-list">
	<?if($arResult["NO_USER"] == "N"):?>
	<tr>
	    <th>Название организации</th>
	    <th>Баланс</th>
		<th>Адрес доставки</th>
	    <th></th>
    </tr>
		<?if (count($arResult["ELEMENTS"]) > 0):?>
			<?foreach ($arResult["ELEMENTS"] as $arElement):?>
			<tr>
				<td><?=$arElement["NAME"]?></td>
				<td><?=$arElement["BALANCE"]['VALUE']?></td>
				<td><a href="delivery_adress.php?FIRM_ID=<?=$arElement["ID"]?>">Адреса офисов доставки</a></td>
				<?if ($arResult["CAN_EDIT"] == "Y"):?>
				<td>
					<?if ($arElement["CAN_EDIT"] == "Y" && $arParams['USER'] == 'admin'):?>
						<a href="<?=$arParams["EDIT_URL"]?>?edit=Y&amp;CODE=<?=$arElement["ID"]?>"><span class="tools"></span></a>
					<?endif?>
				</td>
				<?endif?>
				
			</tr>
			<?endforeach?>
		<?else:?>
			<tr>
				<td<?=$colspan > 1 ? " colspan=\"".$colspan."\"" : ""?>><?=GetMessage("IBLOCK_ADD_LIST_EMPTY")?></td>
			</tr>
		<?endif?>
	<?endif?>
</table>
<?if (strlen($arResult["NAV_STRING"]) > 0):?><?=$arResult["NAV_STRING"]?><?endif?>
