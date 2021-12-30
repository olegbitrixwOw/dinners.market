'use strict';

function BitrixSmallCart(){}

BitrixSmallCart.prototype = {
	
	activate: function ()
	{
		this.cartElement = BX(this.cartId);
		this.fixedPosition = this.arParams.POSITION_FIXED == 'Y';
		if (this.fixedPosition)
		{
			this.cartClosed = true;
			this.maxHeight = false;
			this.itemRemoved = false;
			this.verticalPosition = this.arParams.POSITION_VERTICAL;
			this.horizontalPosition = this.arParams.POSITION_HORIZONTAL;
			this.topPanelElement = BX("bx-panel");

			this.fixAfterRender(); // TODO onready
			this.fixAfterRenderClosure = this.closure('fixAfterRender');

			var fixCartClosure = this.closure('fixCart');
			this.fixCartClosure = fixCartClosure;

			if (this.topPanelElement && this.verticalPosition == 'top')
				BX.addCustomEvent(window, 'onTopPanelCollapse', fixCartClosure);

			var resizeTimer = null;
			BX.bind(window, 'resize', function() {
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(fixCartClosure, 200);
			});
		}
		this.setCartBodyClosure = this.closure('setCartBody');

		/* !!! */
		// console.log('activate');
		// console.log(this.arParams);

		BX.addCustomEvent(window, 'OnBasketChange', this.closure('refreshCart', {day_week:this.arParams.DAY_WEEK})); 
	},

	fixAfterRender: function ()
	{
		this.statusElement = BX(this.cartId + 'status');
		if (this.statusElement)
		{
			if (this.cartClosed)
				this.statusElement.innerHTML = this.openMessage;
			else
				this.statusElement.innerHTML = this.closeMessage;
		}
		this.productsElement = BX(this.cartId + 'products');
		this.fixCart();
	},

	closure: function (fname, data)
	{
		var obj = this;
		return data
			? function(){obj[fname](data)}
			: function(arg1){obj[fname](arg1)};
	},

	toggleOpenCloseCart: function ()
	{
		if (this.cartClosed)
		{
			BX.removeClass(this.cartElement, 'bx-closed');
			BX.addClass(this.cartElement, 'bx-opener');
			this.statusElement.innerHTML = this.closeMessage;
			this.cartClosed = false;
			this.fixCart();
		}
		else // Opened
		{
			BX.addClass(this.cartElement, 'bx-closed');
			BX.removeClass(this.cartElement, 'bx-opener');
			BX.removeClass(this.cartElement, 'bx-max-height');
			this.statusElement.innerHTML = this.openMessage;
			this.cartClosed = true;
			var itemList = this.cartElement.querySelector("[data-role='basket-item-list']");
			if (itemList)
				itemList.style.top = "auto";
		}
		setTimeout(this.fixCartClosure, 100);
	},

	setVerticalCenter: function(windowHeight)
	{
		var top = windowHeight/2 - (this.cartElement.offsetHeight/2);
		if (top < 5)
			top = 5;
		this.cartElement.style.top = top + 'px';
	},

	fixCart: function()
	{
		// set horizontal center
		if (this.horizontalPosition == 'hcenter')
		{
			var windowWidth = 'innerWidth' in window
				? window.innerWidth
				: document.documentElement.offsetWidth;
			var left = windowWidth/2 - (this.cartElement.offsetWidth/2);
			if (left < 5)
				left = 5;
			this.cartElement.style.left = left + 'px';
		}

		var windowHeight = 'innerHeight' in window
			? window.innerHeight
			: document.documentElement.offsetHeight;

		// set vertical position
		switch (this.verticalPosition) {
			case 'top':
				if (this.topPanelElement)
					this.cartElement.style.top = this.topPanelElement.offsetHeight + 5 + 'px';
				break;
			case 'vcenter':
				this.setVerticalCenter(windowHeight);
				break;
		}

		// toggle max height
		if (this.productsElement)
		{
			var itemList = this.cartElement.querySelector("[data-role='basket-item-list']");
			if (this.cartClosed)
			{
				if (this.maxHeight)
				{
					BX.removeClass(this.cartElement, 'bx-max-height');
					if (itemList)
						itemList.style.top = "auto";
					this.maxHeight = false;
				}
			}
			else // Opened
			{
				if (this.maxHeight)
				{
					if (this.productsElement.scrollHeight == this.productsElement.clientHeight)
					{
						BX.removeClass(this.cartElement, 'bx-max-height');
						if (itemList)
							itemList.style.top = "auto";
						this.maxHeight = false;
					}
				}
				else
				{
					if (this.verticalPosition == 'top' || this.verticalPosition == 'vcenter')
					{
						if (this.cartElement.offsetTop + this.cartElement.offsetHeight >= windowHeight)
						{
							BX.addClass(this.cartElement, 'bx-max-height');
							if (itemList)
								itemList.style.top = 82+"px";
							this.maxHeight = true;
						}
					}
					else
					{
						if (this.cartElement.offsetHeight >= windowHeight)
						{
							BX.addClass(this.cartElement, 'bx-max-height');
							if (itemList)
								itemList.style.top = 82+"px";
							this.maxHeight = true;
						}
					}
				}
			}

			if (this.verticalPosition == 'vcenter')
				this.setVerticalCenter(windowHeight);
		}
	},

	/* !!! */ 
	refreshCart: function (data)
	{
		console.log('refreshCart');
		console.log(data);

		if (this.itemRemoved)
		{
			this.itemRemoved = false;
			return;
		}
		var has_cls_active = $(".small_basket_hover_block").hasClass('active');
		data.sessid = BX.bitrix_sessid();
		data.siteId = this.siteId;
		data.templateName = this.templateName;
		data.arParams = this.arParams;

		console.log(data);
		console.log(this.ajaxPath); 

		var this_ob = this;
		BX.ajax({
			url: this.ajaxPath, // /bitrix/components/bitrix/sale.basket.basket.line/ajax.php
			method: 'POST',
			dataType: 'html',
			data: data,
			onsuccess: function(html){
				console.log(html);
				
                $("#small_basket_box").html(html);
                BX.onCustomEvent('OnBasketRefresh');
				if(has_cls_active){
                    $(".small_basket_hover_block").addClass('active');
                }
                $("#small_basket").addClass("update");
                setTimeout(function() {
                	$("#small_basket").removeClass("update");
                }, 1000);
                getmasktoinput ();
                $('.small_basket_overflow').scrollbar();
                this_ob.setCartBodyClosure;
			}
		});
	},

	setCartBody: function (result)
	{
		if (this.cartElement)
			this.cartElement.innerHTML = result.replace(/#CURRENT_URL#/g, this.currentUrl);
		if (this.fixedPosition)
			setTimeout(this.fixAfterRenderClosure, 100);
	},

	removeItemFromCart: function (id)
	{
            this.refreshCart ({sbblRemoveItemFromCart: id});
            this.itemRemoved = true;
            BX.onCustomEvent('OnBasketChange');
	}
};

$(document).ready(function(){ 
    $(document).on("click", "a#small_basket", function () {
        var $button = $(this);
        if (!$(".small_basket_hover_block").hasClass("active")) {
            $(".small_basket_hover_block").slideDown("fast", function () {
                $(this).addClass("active");
            });
            if (Number($('#small_basket .quant').html())) {
                $button.addClass('small_basket--active');
            }
        } else {
            $(".small_basket_hover_block").slideUp("fast", function () {
                $(this).removeClass("active");
            });
            $button.removeClass('small_basket--active');
        }

        return false;
    });


    $(document).on("click", "a.small_basket_hover_delete_action", function () {
		basketId.removeItemFromCart($(this).attr("data-id"));
        return false;
    });

    function chageInputQuantity(input){
        var link = "/?action=ADD2BASKET&id=" + $(input).attr("id") + "&quantity=" + $(input).val();
        $.ajax({
            type: 'POST',
            data: {id: $(input).attr("data-id"), quantity: $(input).val()},
            url: $(input).attr("data-path"),
            async: true,
            cache: false,
            dataType: "html",
            success: function(data) {
                if(data == 1)
                    basketId.refreshCart({});
            }
        });
	}

    $(document).on("change", ".small_basket_hover_quantity input", function () {
        chageInputQuantity(this);
    });


    $(document).on("click", ".small_basket_hover_quantity a", function(){
		var input = $(this).siblings(".small_basket_hover_quantity input");
		var val = parseFloat(input.val());
		var quantAfterPoint = input.val().substr(input.val().indexOf(".", input.val().length)).length;
		var ratio = parseFloat(input.attr('data-ratio'));
        if($(this).hasClass("plus")){
            val = (val+ratio).toFixed(quantAfterPoint);
    		input.val(val);
            chageInputQuantity(input);
        } else {
        	if(val > ratio){
                val =(val-ratio).toFixed(quantAfterPoint);
                input.val(val);
                chageInputQuantity(input);
        	}
        }
	});

	/**
	 * Купить в 1 клик
	 */
	var submitBtn = BX('small_basket_hover_buy_go');
	BX.bind(submitBtn, 'click', function(){
		BX.onCustomEvent('buy-one-click', []);
	});

	if (!BX.UserConsent)
	{
		return;
	}
	var control = BX.UserConsent.load(BX('oneClickModal'));
	if (!control)
	{
		return;
	}

	BX.addCustomEvent(
		control,
		BX.UserConsent.events.save,
		function (data) {
			console.log('js event:', 'save', data);

			var
				phone = $("#SMALL_BASKET_ORDER_PHONE").val().replace(/[^0-9]/g, ''),
				getParams,
				path;

			if (phone.length != 11) {
				$("#SMALL_BASKET_ORDER_PHONE").addClass("red_border");
				setTimeout(function() { $("#SMALL_BASKET_ORDER_PHONE").removeClass("red_border") }, 1000);
			} else {
				// закрытие предыдущей модалки
				$.fancybox.close();

				getParams = "update_small_basket=Y&SMALL_BASKET_FAST_ORDER=Y&SMALL_BASKET_ORDER_PHONE=" + $("#SMALL_BASKET_ORDER_PHONE").val();

				$.ajax({
					data: getParams,
					url: $('#small_basket_box').attr('data-path'),
					async: true,
					cache: false,
					success: function (response) {
						// обновление малой корзины
						BX.onCustomEvent('OnBasketChange');

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

						// очистка корзины
						EmptyBasket();
					}
				});
			}
		}
	);
});
