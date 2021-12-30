<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

extract($arResult);
?>

<div class="catalog_sort clearfix">
	<div class="sort_btn_wrap clearfix">
		<a
				href="<?=$APPLICATION->GetCurPageParam("sort_by=created&order_by=".(($sort_by_created) ? $order_by_new : "desc"), Array("sort_by", "order_by"), false);?>"
				class="<?=($sort_by_created ? "active ". $order_by : "desc")?>"
				title="<?=Loc::getMessage("SF_SORT_BY_NEW");?>"
		>
			<?=Loc::getMessage("SF_SORT_BY_NEW");?>
			<i class="arr icons_fa"></i>
		</a>
		<a
				href="<?=$APPLICATION->GetCurPageParam("sort_by=show_counter&order_by=".(($sort_by_counter) ? $order_by_new : "desc"), Array("sort_by", "order_by"), false);?>"
				class="<?=($sort_by_counter ? "active ". $order_by : "desc")?>"
				title="<?=Loc::getMessage("SF_SORT_BY_POPULAR");?>"
		>
			<?=Loc::getMessage("SF_SORT_BY_POPULAR");?>
			<i class="arr icons_fa"></i>
		</a>
		<a
				href="<?=$APPLICATION->GetCurPageParam("sort_by=CATALOG_PRICE_1&order_by=".(($sort_by_price) ? $order_by_new : "desc"), Array("sort_by", "order_by"), false);?>"
				class="<?=($sort_by_price ? "active ". $order_by : "desc")?>"
				title="<?=Loc::getMessage("SF_SORT_BY_PRICE");?>"
		>
			<?=Loc::getMessage("SF_SORT_BY_PRICE");?>
			<i class="arr icons_fa"></i>
		</a>
	</div>
	<?$order_by = $order_by . ",nulls"?>
	<div class="catalog-sorting clearfix">
		<?if(CGetfood::getOption("TILE") == 'true'){?>
			<button class="catalog-sorting__button catalog-sorting__grid"></button>
		<?}?>
		<?if(CGetfood::getOption("LIST") == 'true'){?>
			<button class="catalog-sorting__button catalog-sorting__list"></button>
		<?}?>
		<?if(CGetfood::getOption("LIST2") == 'true'){?>
			<button class="catalog-sorting__button catalog-sorting__list-info"></button>
		<?}?>
	</div>
</div>
<script>
	$(".catalog-sorting__button.catalog-sorting__button--active").removeClass("catalog-sorting__button--active");
	if (document.cookie.indexOf("BITRIX_SM_catalog_view_mode=section--list%20section--list-small") >= 0){
		$("#main_wrapper").attr("class", "wrapper section--list section--list-small");
		$(".catalog-sorting__list-info").addClass("catalog-sorting__button--active");
	} else if (document.cookie.indexOf("BITRIX_SM_catalog_view_mode=section--list%20section--list-info") >= 0){
		$("#main_wrapper").attr("class", "wrapper section--list section--list-info");
		$(".catalog-sorting__list").addClass("catalog-sorting__button--active");
	} else {
		$("#main_wrapper").attr("class", "wrapper section--grid-3");
		$(".catalog-sorting__grid").addClass("catalog-sorting__button--active");
	}
</script>