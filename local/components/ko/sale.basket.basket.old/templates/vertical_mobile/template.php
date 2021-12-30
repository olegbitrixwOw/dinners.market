<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/**
 * @global string $componentPath
 * @global string $templateName
 * @var CBitrixComponentTemplate $this
 */
$cartStyle = 'bx-basket';
$cartId = "bx_basket".$this->randString();
$arParams['cartId'] = $cartId;

if ($arParams['POSITION_FIXED'] == 'Y')
{
	$cartStyle .= "-fixed {$arParams['POSITION_HORIZONTAL']} {$arParams['POSITION_VERTICAL']}";
	if ($arParams['SHOW_PRODUCTS'] == 'Y')
		$cartStyle .= ' bx-closed';
}
else
{
	$cartStyle .= ' bx-opener';
}
?><script>
	localStorage.setItem('<?=$cartId.'_mobile'?>', JSON.stringify(<?=CUtil::PhpToJSObject ($arParams)?>));
	// var <?=$cartId?> = new BitrixSmallCart;
	var <?=$cartId.'_mobile'?> = new BitrixMobileCart;
	$(document).ready(function () {
	    basketId = <?=$cartId.'_mobile'?>;
	    $(".btn-sm").click(function () {
	        basketId.refreshCart({});
	    });
	});
</script>
<p class="make_order make_order_hide">сделайте заказ</p>
<div class="basket_box" data-cart-id="<?=$cartId?>" data-json="">
<div id="<?=$cartId?>" class="<?=$cartStyle?>"><?
	/** @var \Bitrix\Main\Page\FrameBuffered $frame */
	$frame = $this->createFrame($cartId, false)->begin();
		require(realpath(dirname(__FILE__)) . '/ajax_template.php');
	$frame->beginStub();
		$arResult['COMPOSITE_STUB'] = 'Y';
		require(realpath(dirname(__FILE__)) . '/top_template.php');
		unset($arResult['COMPOSITE_STUB']);
	$frame->end();
?></div>
<script type="text/javascript">
	<?=$cartId.'_mobile'?>.siteId       = '<?=SITE_ID?>';
	<?=$cartId.'_mobile'?>.cartId       = '<?=$cartId?>';
	<?=$cartId.'_mobile'?>.ajaxPath     = '<?=$componentPath?>/ajax.php';
	<?=$cartId.'_mobile'?>.templateName = '<?=$templateName?>';
	<?=$cartId.'_mobile'?>.arParams     =  <?=CUtil::PhpToJSObject ($arParams)?>; // TODO \Bitrix\Main\Web\Json::encode
	<?=$cartId.'_mobile'?>.closeMessage = '<?=GetMessage('TSB1_COLLAPSE')?>';
	<?=$cartId.'_mobile'?>.openMessage  = '<?=GetMessage('TSB1_EXPAND')?>';
	<?=$cartId.'_mobile'?>.activate();
</script>
</div>