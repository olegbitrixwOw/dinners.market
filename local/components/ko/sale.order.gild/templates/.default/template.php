<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(isset($arResult['USERS']) && !empty($arResult['USERS'])):?>
<?foreach($arResult['USERS'] as $user):?>
<?if(count($user['SALES']) > 0):?>
<div class="layout-L2__col layout-L2__col--col2">
	
	<div class="favorites-filter-wrapper">
		<div class="favorites-filter">
			<span><?= $user['FULL_NAME'] ?></span>
		</div>
		<div class="favorites-options">
			<div class="prodoptions">
				<a href="#" class="prodoptions__option prodoptions__option--print"></a>
			</div>
		</div>
	</div>

	<div class="orders" data-onloading="orders">
	
		<?foreach($user['SALES'] as $sale):?>
		<div class="order collapsed">
			<div class="order__header">
				
				<div class="order__info">
					<a class="order__id" href="#"><?= $sale['NAME'] ?> <i class="order__more">подробнее</i> </a>
					<?= $sale['DESCRIPTION'] ?>
				</div>
				<div class="order__status"><?= $sale['STATUS'] ?><?= $sale['PAYED'] ?></div>
				<div class="order__price"><?= $sale['PRICE'] ?> ₽</div>
			</div>

			

			<div class="order__body">
				

				<?foreach($sale['PRODUCTS'] as $key => $product):?>
						<div class="order__goods">
							<a href="<?= $product['DETAIL_PAGE_URL'] ?>"><?= $product['NAME'] ?></a>
						</div>
						<div class="order__price-single">
								<div><?=$product['PRICE'] ?> ₽</div>
						</div>
						<div class="order__amount">
							<div><?=$product['QUANTITY'] ?> шт.</div>
						</div>
						<div class="order__price-total">
							<div><?= $product['FINALPRICE'] ?> ₽</div>
						</div>
				<?endforeach;?>
			</div>

			<div class="order__body">
				<div class="order__options">
					<p>
						<i class="repeat"></i>
						<a class="order-repeat" data-id="<?= $sale['ID'] ?>" data-dayweek="<?= $arParams['DAY_WEEK'] ?>" href="#">Повторить заказ</a></p>
				</div>
			</div>
		</div>
		<?endforeach;?>
	</div>
</div>
<?endif;?>
<?endforeach;?>
<?endif;?>
<script>
  $(function(){
     $('.order-repeat').click(function(e){
          e.preventDefault();
          let order_id = $(this).data('id');
          let day_week = $(this).data('dayweek');
          basket(order_id, day_week);
     });
  });
 	// функция отправки и отображения формы
 	function basket(order_id, day_week){		
		$.ajax({
		  url: '<?=$componentPath?>/basket_repeat.php',
		  type: 'GET',
		  data: {id:order_id, day_week:day_week},
		  datatype: 'json',
		  success: function(data){
		  	console.log(data)
			window.location.replace('/personal/cart/?day_week=<?=$arParams['DAY_WEEK'] ?>');
		  }
		});
	}
</script>