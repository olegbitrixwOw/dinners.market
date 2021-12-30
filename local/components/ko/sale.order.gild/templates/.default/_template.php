<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!isset($_POST['AJAX'])):?>
<div class="layout-L2__col layout-L2__col--col2">
	<div class="favorites-filter-wrapper">
		<div class="favorites-filter">
			<span><?= $arResult['USER']['FULL_NAME'] ?></span>
		</div>
		<div class="favorites-options">
			<div class="prodoptions">
				<a href="#" class="prodoptions__option prodoptions__option--print"></a>
			</div>
		</div>
	</div>

	<div class="products-filter products-filter--history" data-onloading="orders">
		<div class="products-filter__wrapper">
			<div class="products-filter__sort">
				<span class="products-filter__label">Сортировка:</span>
				<a id="sort-date_insert" href="#" class="products-filter__sort-item products-filter__sort-item--desc active">Дата<em class="asc"></em></a>
				<a id="sort-price" href="#" class="products-filter__sort-item products-filter__sort-item--desc">Сумма<em class="asc"></em></a>
			</div>
		</div>
	</div>
	<div class="orders" data-onloading="orders">
<?endif;?>
	<?if(isset($arResult['SALES']) && !empty($arResult['SALES'])):?>
		<?foreach($arResult['SALES'] as $sale):?>
		<div class="order collapsed">
			<div class="order__header">
				<a class="order__id" href="#"><?= $sale['NAME'] ?> <i class="order__more"></i></a>
				<div class="order__info"><?= $sale['DESCRIPTION'] ?></div>
				<div class="order__status">
					<p><?= $sale['STATUS'] ?></p>
					<p><?= $sale['PAYED'] ?></p>
				</div>
				<div class="order__price"><?= $sale['PRICE'] ?> ₽</div>
			</div>
			<div class="order__body">
				<div class="order__options">
					<p><i class="print"></i> <a href="#">Печать</a></p>
					<p>
						<i class="repeat"></i>
						<a class="order-repeat" data-id="<?= $sale['ID'] ?>" href="#">Повторить заказ</a></p>
				</div>
				<div class="order__goods">
					<?foreach($sale['PRODUCTS']['NAME'] as $id => $name):?>
						<a href="<?= $sale['PRODUCTS']['DETAIL_URLs'][$id] ?>"><?= $name ?></a>
					<?endforeach;?>
				</div>
				<div class="order__price-single">
					<?foreach($sale['PRODUCTS']['PRICE'] as $price):?>
						<div><?= $price ?> ₽</div>
					<?endforeach;?>
				</div>
				<div class="order__amount">
					<?foreach($sale['PRODUCTS']['QUANTITY'] as $quantity):?>
						<div><?= $quantity ?> шт.</div>
					<?endforeach;?>
				</div>
				<div class="order__price-total">
					<?foreach($sale['PRODUCTS']['FULLPRICE'] as $price):?>
						<div><?= $price ?> ₽</div>
					<?endforeach;?>
				</div>
			</div>
		</div>
		<?endforeach;?>
	<?endif;?>
	<?if(!isset($_POST['AJAX'])):?></div><?endif;?>
	<?$all = $arResult['ALL_ELEMENTS_COUNT'];
	$show = $arResult['SHOW_ELEMENTS_COUNT'];
	if($show < $all):?>
	<div class="categories__more-wrap">
		<a href="#" id="order_history_more"
		   data-onloading="orders"
		   class="categories__more"
		   data-page="<?= $arParams['PAGE'] ?>"><?= $show ?> из <?= $all ?> <br>Показать еще...</a>
	</div>
	<?endif;?>
</div>