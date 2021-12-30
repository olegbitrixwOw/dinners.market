/**
 * Купить в 1 клик
 */
BX.ready(function () {
	var submitBtn = BX('buy_one_click_product');
	BX.bind(submitBtn, 'click', function () {
		BX.onCustomEvent('buy-one-click-product', []);
	});

	if (!BX.UserConsent) {
		return;
	}

	var control = BX.UserConsent.load(BX('buyOneProductModal'));
	if (!control) {
		return;
	}

	BX.addCustomEvent(
		control,
		BX.UserConsent.events.save,
		function (data) {
			console.log('js event:', 'save', data);

			var phone = $("#SMALL_BASKET_ORDER_PHONE").val().replace(/[^0-9]/g, ''),
				product_id, quantity, getParams,
				path;

			if (phone.length != 11) {
				$("#SMALL_BASKET_ORDER_PHONE").addClass("red_border");
				setTimeout(function() { $("#SMALL_BASKET_ORDER_PHONE").removeClass("red_border") }, 1000);
			} else {
				// закрытие предыдущей модалки
				$.fancybox.close();

				product_id = $('.bx_item_detail').attr('data-product-id');
				quantity = $('.product-quantity-wrapper .item_quantity input').val();
				getParams = "update_small_basket=Y&SMALL_BASKET_FAST_ORDER=Y&product_id=" + product_id + "&quantity=" + quantity + "&SMALL_BASKET_ORDER_PHONE=" + $("#SMALL_BASKET_ORDER_PHONE").val();

				// если в текущем документе нет малой корзины, значить кликнули внутри модалки быстрый просмотр
				path = $("#small_basket_box").attr("data-path");
				if (!path) {
					path = parent.$("#small_basket_box").attr("data-path");
				}

				$.ajax({
					data: getParams,
					url: path,
					async: true,
					cache: false,
					success: function (response) {
						// вывод сообщения
						var html = '<div class="success_fast_order">' + response + '</div>';
						$.fancybox.open(html, {
							autoSize : false,
							autoResize : true,
							autoCenter : true,
							openEffect : "fade",
							closeEffect : "fade",
							width: "auto",
							height: "auto",
							helpers: {
								overlay: {
									locked: false
								}
							}
						});
					}
				});
			}
		}
	);
});