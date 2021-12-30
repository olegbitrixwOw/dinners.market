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
$arUser = getUserData($USER->GetID());
if($arUser['TYPE'] !== 'admin'){
	$arParams["FIRM_ID"] = (int)$arParams["FIRM_ID"];
	$arUser['FIRM']['ID'] =  (int) $arUser['FIRM']['ID'];
	if($arParams["FIRM_ID"] !== $arUser['FIRM']['ID']){
		return;
	}
}

$colspan = 2;
if ($arResult["CAN_EDIT"] == "Y") $colspan++;
if ($arResult["CAN_DELETE"] == "Y") $colspan++;
?>
<?if (strlen($arResult["MESSAGE"]) > 0):?>
	<?ShowNote($arResult["MESSAGE"])?> 
<?endif?>


<table id="list">
<?if($arResult["NO_USER"] == "N"):?>
	<thead>
		<tr>
			<td<?=$colspan > 1 ? " colspan=\"".$colspan."\"" : ""?>><?=GetMessage("IBLOCK_DELIVERY_ADDRESS")?></td>
		</tr>
	</thead>
	<tbody>
	<?if (count($arResult["ELEMENTS"]) > 0):?>
		<?foreach ($arResult["ELEMENTS"] as $arElement):?>
		<tr>
			<td><?=$arElement["NAME"]?></td>
			<?if ($arResult["CAN_EDIT"] == "Y"):?>
			<td>
				<?if ($arElement["CAN_EDIT"] == "Y"):?>
					<a href="<?=$arParams["EDIT_URL"]?>?edit=Y&amp;CODE=<?=$arElement["ID"]?>&amp;FIRM_ID=<?=$arParams["FIRM_ID"]?>"><span class="tools"></span></a>
				<?endif?>
			</td>
			<?endif?>
			<td>
				<?if ($arElement["CAN_DELETE"] == "Y"):?>
					<a href="?delete=Y&amp;CODE=<?=$arElement["ID"]?>&amp;<?=bitrix_sessid_get()?>" onClick="return confirm('<?echo CUtil::JSEscape(str_replace("#ELEMENT_NAME#", $arElement["NAME"], GetMessage("IBLOCK_ADD_LIST_DELETE_CONFIRM")))?>')">
						<span class="delete"></span>				
					</a>

				<?endif?>

			</td>
		</tr>
		<?endforeach?>
	<?else:?>
		<tr>
			<td<?=$colspan > 1 ? " colspan=\"".$colspan."\"" : ""?>><?=GetMessage("IBLOCK_ADD_LIST_EMPTY")?></td>
		</tr>
	<?endif?>
	</tbody>
<?endif?>
	<tfoot>
		<tr>
			<td<?=$colspan > 1 ? " colspan=\"".$colspan."\"" : ""?>><?if ($arParams["MAX_USER_ENTRIES"] > 0 && $arResult["ELEMENTS_COUNT"] < $arParams["MAX_USER_ENTRIES"]):?>
				<a href="<?=$arParams["EDIT_URL"]?>?edit=Y&FIRM_ID=<?=$arParams["FIRM_ID"]?>">
					<?=GetMessage("IBLOCK_ADD_LINK_TITLE")?>						
				</a>
				<?else:?><?=GetMessage("IBLOCK_LIST_CANT_ADD_MORE")?><?endif?>
			</td>
		</tr>
	</tfoot>
</table>

<?if (strlen($arResult["NAV_STRING"]) > 0):?><?=$arResult["NAV_STRING"]?><?endif?>