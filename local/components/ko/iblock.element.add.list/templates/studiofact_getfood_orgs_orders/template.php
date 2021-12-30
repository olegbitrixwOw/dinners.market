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

$APPLICATION->SetTitle("История заказов организаций");
?>
<?if (strlen($arResult["MESSAGE"]) > 0):?>
	<?ShowNote($arResult["MESSAGE"])?> 
<?endif?>


<table id="list">
	<?if($arResult["NO_USER"] == "N"):?>
	<tr>
	    <th>Название организации</th>
	    <th>ИНН</th>	 
		<th>Юридический адрес</th>
    </tr>
		<?if (count($arResult["ELEMENTS"]) > 0):?>
			<?foreach ($arResult["ELEMENTS"] as $arElement):?>
			<tr>
				<td>
					<a href="/firm/orders/?FIRM_ID=<?=$arElement["ID"]?>"><?=$arElement["NAME"]?></a>						
				</td>
				<td>
					<?if(!empty($arElement["INN"]['VALUE'])):?>
						<?=$arElement["INN"]['VALUE']?>
					<?endif?>
				</td>
				<td>
					<?if(!empty($arElement["UR_ADDRESS"]['VALUE'])):?>
						<?=$arElement["UR_ADDRESS"]['VALUE']?>
					<?endif?>					
				</td>
			</tr>
			<?endforeach?>
		<?else:?>
			<tr>
				<td<?=$colspan > 1 ? " colspan=\"".$colspan."\"" : ""?>><?=GetMessage("IBLOCK_ADD_LIST_EMPTY")?></td>
			</tr>
		<?endif?>
	<?endif?>
	<tfoot>
		<tr>
			<td colspan="5"><a href="<?=$arParams["EDIT_URL"]?>?edit=Y"><?=GetMessage("IBLOCK_ADD_LINK_TITLE")?></a></td>
		</tr>
	</tfoot>
</table>

<?if (strlen($arResult["NAV_STRING"]) > 0):?><?=$arResult["NAV_STRING"]?><?endif?>
