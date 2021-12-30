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

<h2><a href="<?=$arParams["EDIT_URL"]?>?edit=Y" class="register"><?=GetMessage("IBLOCK_ADD_LINK_TITLE")?></a></h2>

<table id="user-list">
	<?if($arResult["NO_USER"] == "N"):?>
	<tr>
	    <th>Название организации</th>
	    <th>Активность</th>
	    <th>Редактировать</th>
	    <th>Удалить</th>
	</tr>
		<?if (count($arResult["ELEMENTS"]) > 0):?>
			<?foreach ($arResult["ELEMENTS"] as $arElement):?>
			<tr>
				<td><!--a href="detail.php?CODE=<?=$arElement["ID"]?>"--><?=$arElement["NAME"]?><!--/a--></td>
				<td>
					<small>
						<?=is_array($arResult["WF_STATUS"]) ? $arResult["WF_STATUS"][$arElement["WF_STATUS_ID"]] : $arResult["ACTIVE_STATUS"][$arElement["ACTIVE"]]?>					
					</small>
				</td>
				<?if ($arResult["CAN_EDIT"] == "Y"):?>
				<td>
					<?if ($arElement["CAN_EDIT"] == "Y"):?><a href="<?=$arParams["EDIT_URL"]?>?edit=Y&amp;CODE=<?=$arElement["ID"]?>"><?=GetMessage("IBLOCK_ADD_LIST_EDIT")?><?else:?>&nbsp;<?endif?></a>
				</td>
				<?endif?>
				<?if ($arResult["CAN_DELETE"] == "Y"):?>
				<td>
					<?if ($arElement["CAN_DELETE"] == "Y"):?><a href="?delete=Y&amp;CODE=<?=$arElement["ID"]?>&amp;<?=bitrix_sessid_get()?>" onClick="return confirm('<?echo CUtil::JSEscape(str_replace("#ELEMENT_NAME#", $arElement["NAME"], GetMessage("IBLOCK_ADD_LIST_DELETE_CONFIRM")))?>')"><?=GetMessage("IBLOCK_ADD_LIST_DELETE")?></a><?else:?>&nbsp;<?endif?>
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
