<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->createFrame()->begin("");

$arUrls = Array(
	"delete" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delete&id=#ID#",
	"delay" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=delay&id=#ID#",
	"add" => $APPLICATION->GetCurPage()."?".$arParams["ACTION_VARIABLE"]."=add&id=#ID#",
);
$arBasketJSParams = array(
	'SALE_DELETE' => GetMessage("SALE_DELETE"),
	'SALE_DELAY' => GetMessage("SALE_DELAY"),
	'SALE_TYPE' => GetMessage("SALE_TYPE"),
	'TEMPLATE_FOLDER' => $templateFolder,
	'DELETE_URL' => $arUrls["delete"],
	'DELAY_URL' => $arUrls["delay"],
	'DAY_WEEK' =>$arParams["DAY_WEEK"],
	'ADD_URL' => $arUrls["add"],
);

// echo $arParams["DAY_WEEK"];
?>
<script type="text/javascript">
	var basketJSParams = <?=CUtil::PhpToJSObject($arBasketJSParams);?>
	// var basketJSParams_<?=$arParams["DAY_WEEK"]?> = <?=CUtil::PhpToJSObject($arBasketJSParams);?>
	
	BX.message({
		'SALE_EMPTY_BASKET': '<p><font class="errortext"><?=GetMessage('SALE_EMPTY_BASKET')?></font></p>'
	});
</script>

<script type="text/javascript">
	var BASE_URL = '<?=$_SERVER['HTTP_HOST'];?>';

</script>
<?
if ($_REQUEST["UPDATE_BIG_BASKET_AJAX"] == "Y") {
	$APPLICATION->RestartBuffer();
} else {
	$APPLICATION->AddHeadScript($templateFolder."/script.js");
	echo '<div id="update_big_basket_ajax_'.$arParams["DAY_WEEK"].'">';
}
?>
<div class="box margin padding">
	
	<script type="text/javascript">
		function showBasketItemsList (id) {
			if (typeof(id) == "undefined") {
				var id = "basket_items_list";
			}

			BX.removeClass(BX("basket_toolbar_button"), "current");
			BX.removeClass(BX("basket_toolbar_button_delayed"), "current");
			BX.removeClass(BX("basket_toolbar_button_subscribed"), "current");
			BX.removeClass(BX("basket_toolbar_button_not_available"), "current");

			BX("normal_count").style.display = 'inline-block';
			BX("delay_count").style.display = 'inline-block';
			BX("subscribe_count").style.display = 'inline-block';
			BX("not_available_count").style.display = 'inline-block';

			BX.removeClass(BX("basket_items_list"), "current");
			BX.removeClass(BX("basket_items_delayed"), "current");
			BX.removeClass(BX("basket_items_subscribed"), "current");
			BX.removeClass(BX("basket_toolbar_button_subscribed"), "current");

			if (id == "basket_items_list") {
				BX.addClass(BX("basket_toolbar_button"), "current");
				BX("normal_count").style.display = 'none';
				BX.addClass(BX("basket_items_list"), "current");
			} else if (id == "basket_items_delayed") {
				BX.addClass(BX("basket_toolbar_button_delayed"), "current");
				BX("delay_count").style.display = 'none';
				BX.addClass(BX("basket_items_delayed"), "current");
			} else if (id == "basket_items_subscribed") {
				BX.addClass(BX("basket_toolbar_button_subscribed"), "current");
				BX("subscribe_count").style.display = 'none';
				BX.addClass(BX("basket_items_subscribed"), "current");
			} else if (id == "basket_items_not_available") {
				BX.addClass(BX("basket_toolbar_button_not_available"), "current");
				BX("not_available_count").style.display = 'none';
				BX.addClass(BX("basket_items_not_available"), "current");
			}
		}
	</script>
	<h2><?=$arResult["DAY"]?></h2>
	<? if (strlen($arResult["ERROR_MESSAGE"]) <= 0) { ?>
		<div id="warning_message"><?
			if (is_array($arResult["WARNING_MESSAGE"]) && !empty($arResult["WARNING_MESSAGE"])) {
				foreach ($arResult["WARNING_MESSAGE"] as $v)
					echo ShowError($v);
			} ?>
		</div>
		<?

		$normalCount = count($arResult["ITEMS"]["AnDelCanBuy"]);
		$normalHidden = ($normalCount == 0) ? "style=\"display:none\"" : "";

		$delayCount = count($arResult["ITEMS"]["DelDelCanBuy"]);
		$delayHidden = ($delayCount == 0) ? "style=\"display:none\"" : "";

		$subscribeCount = count($arResult["ITEMS"]["ProdSubscribe"]);
		$subscribeHidden = ($subscribeCount == 0) ? "style=\"display:none\"" : "";

		$naCount = count($arResult["ITEMS"]["nAnCanBuy"]);
		$naHidden = ($naCount == 0) ? "style=\"display:none\"" : "";

		?>

		<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="basket_form" id="basket_form">
			<!-- <div id="basket_form_container"> -->
			<div id="basket_form_container">
				<div class="bx_ordercart">
					<div class="bx_sort_container">
						<span><?=GetMessage("SALE_ITEMS")?></span>
						<a href="javascript: void(0);" id="basket_toolbar_button" class="current" onclick="showBasketItemsList()"><?=GetMessage("SALE_BASKET_ITEMS")?><div id="normal_count" class="flat" style="display:none">&nbsp;(<?=$normalCount?>)</div></a>
						<a href="javascript: void(0);" id="basket_toolbar_button_delayed" onclick="showBasketItemsList('basket_items_delayed')" <?=$delayHidden?>><?=GetMessage("SALE_BASKET_ITEMS_DELAYED")?><div id="delay_count" class="flat">&nbsp;(<?=$delayCount?>)</div></a>
						<a href="javascript: void(0);" id="basket_toolbar_button_subscribed" onclick="showBasketItemsList('basket_items_subscribed')" <?=$subscribeHidden?>><?=GetMessage("SALE_BASKET_ITEMS_SUBSCRIBED")?><div id="subscribe_count" class="flat">&nbsp;(<?=$subscribeCount?>)</div></a>
						<a href="javascript: void(0);" id="basket_toolbar_button_not_available" onclick="showBasketItemsList('basket_items_not_available')" <?=$naHidden?>><?=GetMessage("SALE_BASKET_ITEMS_NOT_AVAILABLE")?><div id="not_available_count" class="flat">&nbsp;(<?=$naCount?>)</div></a>
					</div>
					<?
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_delayed.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_subscribed.php");
					include($_SERVER["DOCUMENT_ROOT"].$templateFolder."/basket_items_not_available.php");

					?>
				</div>
			</div>
			<input type="hidden" name="BasketOrder" value="BasketOrder" />
			<!-- <input type="hidden" name="ajax_post" id="ajax_post" value="Y"> -->
			<input type="hidden" name="day_week" value="<?=$arParams["DAY_WEEK"]?>" />
		</form>
	<? } else {
		ShowError($arResult["ERROR_MESSAGE"]);
	} ?>

</div>

<?
if ($_REQUEST["UPDATE_BIG_BASKET_AJAX"] == "Y") {
	die ();
} else {
	echo '</div>';
}


if($arParams['USE_GIFTS'] === 'Y' && $arParams['GIFTS_PLACE'] === 'BOTTOM')
{
	$APPLICATION->IncludeComponent(
		"bitrix:sale.gift.basket",
		"",
		array(
			"SHOW_PRICE_COUNT" => 1,
			"PRODUCT_SUBSCRIPTION" => 'N',
			'PRODUCT_ID_VARIABLE' => 'id',
			"PARTIAL_PRODUCT_PROPERTIES" => 'N',
			"USE_PRODUCT_QUANTITY" => 'N',
			"ACTION_VARIABLE" => "actionGift",
			"ADD_PROPERTIES_TO_BASKET" => "Y",

			"BASKET_URL" => $APPLICATION->GetCurPage(),
			"APPLIED_DISCOUNT_LIST" => $arResult["APPLIED_DISCOUNT_LIST"],
			"FULL_DISCOUNT_LIST" => $arResult["FULL_DISCOUNT_LIST"],

			"TEMPLATE_THEME" => $arParams["TEMPLATE_THEME"],
			"PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_SHOW_VALUE"],
			"CACHE_GROUPS" => $arParams["CACHE_GROUPS"],

			'BLOCK_TITLE' => $arParams['GIFTS_BLOCK_TITLE'],
			'HIDE_BLOCK_TITLE' => $arParams['GIFTS_HIDE_BLOCK_TITLE'],
			'TEXT_LABEL_GIFT' => $arParams['GIFTS_TEXT_LABEL_GIFT'],
			'PRODUCT_QUANTITY_VARIABLE' => $arParams['GIFTS_PRODUCT_QUANTITY_VARIABLE'],
			'PRODUCT_PROPS_VARIABLE' => $arParams['GIFTS_PRODUCT_PROPS_VARIABLE'],
			'SHOW_OLD_PRICE' => $arParams['GIFTS_SHOW_OLD_PRICE'],
			'SHOW_DISCOUNT_PERCENT' => $arParams['GIFTS_SHOW_DISCOUNT_PERCENT'],
			'SHOW_NAME' => $arParams['GIFTS_SHOW_NAME'],
			'SHOW_IMAGE' => $arParams['GIFTS_SHOW_IMAGE'],
			'MESS_BTN_BUY' => $arParams['GIFTS_MESS_BTN_BUY'],
			'MESS_BTN_DETAIL' => $arParams['GIFTS_MESS_BTN_DETAIL'],
			'PAGE_ELEMENT_COUNT' => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
			'CONVERT_CURRENCY' => $arParams['GIFTS_CONVERT_CURRENCY'],
			'HIDE_NOT_AVAILABLE' => $arParams['GIFTS_HIDE_NOT_AVAILABLE'],

			"LINE_ELEMENT_COUNT" => $arParams['GIFTS_PAGE_ELEMENT_COUNT'],
		),
		false
	);
}