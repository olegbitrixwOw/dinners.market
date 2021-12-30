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

			let fixCartClosure = this.closure('fixCart');
			this.fixCartClosure = fixCartClosure;

			if (this.topPanelElement && this.verticalPosition == 'top'){
				BX.addCustomEvent(window, 'onTopPanelCollapse', fixCartClosure);
			}
			this.fixBuyAll();
			let resizeTimer = null;
			BX.bind(window, 'resize', function() {
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(fixCartClosure, 200);
			});
		}
		this.setCartBodyClosure = this.closure('setCartBody');

		let parent = this.cartElement.parentElement;
			parent.dataset.cartId = this.cartId;
		this.parent = parent;

		if(this.arParams.ACTIVE_DAY_WEEK == this.arParams.DAY_WEEK){
			BX.addCustomEvent(window, 'OnBasketChange', this.closure('refreshCart', {}));
			
			this.setNums();
		}
		// BX.addCustomEvent(window, 'OnBasketChange', this.closure('refreshCart', {}));

		// параметры корзины
		this.basket_box = document.getElementById(this.parent.dataset.cartId);
		this.parent_block = document.getElementById(this.arParams.DAY_WEEK);
	    this.elem = this.parent_block.querySelector('p');
	    this.basketFly = document.querySelector('.basket_fly');
		this.block = document.querySelector('.basket_fly .opener');
		this.itemsBlock = this.block.querySelector('.items');
		this.basket = document.getElementById(this.cartId);
		
		this.limit = 0;
		const limitDOM = document.querySelector('.limit');	
		if(limitDOM){
			this.limit = Number(limitDOM.dataset.daylimit);
		}
		this.basket_block_buttons = this.basket_box.querySelector('.small_basket_hover_block__buttons');
		this.buy_all = document.querySelector('.btn_buy_one_click');
		this.basket_buy = this.basket_box.querySelector('.basket-buy');

	},

	// fixBuyAll: function(){
	// 		const buy_all = document.querySelector('.btn_buy_one_click');
	// 		const limit = Number(document.querySelector('.limit').dataset.daylimit);
	// 		let sum_current_basket = this_ob.setSumCurBasket(this.arParams.DAY_WEEK);
	// 		// this.blockBuyButton(limit, sum_current_basket, basket_box);
	// 		console.log('fixBuyAll');
	// 		console.log(limit);
	// 		console.log(sum_current_basket);
	// },

	setNums: function(){
		const basket =  document.querySelector('#'+this.cartId);
		const nums = basket.querySelectorAll('.good_box').length;
		if(nums){
			const block = document.querySelector('.basket_fly .opener');
			const itemsBlock = block.querySelector('.items');
				  itemsBlock.innerHTML = nums;
		}
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
	
	_blockBuyButton: async function (sum_current_basket, sum_baskets){
		const buy_all = document.querySelector('#buy-all-baskets');
		const basket_block_buttons = this.basket_box.querySelector('.small_basket_hover_block__buttons');
		const delete_all = document.querySelector('#delete-all-baskets');

		const sum_baskets_block = document.querySelector('#sum-all-baskets');
		if(sum_baskets){
			sum_baskets_block.classList.remove('disabled');
			delete_all.classList.remove('disabled');
			buy_all.classList.remove('disabled_button');
			const number = sum_baskets_block.querySelector('strong');
			number.innerHTML = sum_baskets+'<span class="rub black">Р</span>';
		}else{
			sum_baskets_block.classList.add('disabled');
			delete_all.classList.add('disabled');
			buy_all.classList.add('disabled_button'); 
		}

		if(basket_block_buttons){
				const basket_buy = basket_block_buttons.querySelector('.basket-buy');
				const warning = basket_block_buttons.querySelector('.basket_buy_button_warning');	

				if(this.limit){		
					if(this.limit >= sum_current_basket){
						buy_all.classList.remove('disabled_button');
						basket_buy.classList.remove('disabled_button');
						warning.classList.add('disabled_warning');

						// console.log(this.limit);
						// console.log(this.limit > sum_current_basket);
						// console.log(buy_all);
					}else{
						buy_all.classList.add('disabled_button'); 
						basket_buy.classList.add('disabled_button');
						warning.classList.remove('disabled_warning');
					}
				}
		}

		// return true;
		return 'blockBuyButton';
	},

	blockBuyButton: async function (sum_current_basket, sum_baskets){
		console.log('blockBuyButton')
		console.log(sum_baskets)

		const buy_all = document.querySelector('#buy-all-baskets');		
		const delete_all = document.querySelector('#delete-all-baskets');
		const sum_baskets_block = document.querySelector('#sum-all-baskets');		
		// const sum_top = document.querySelector('.sum-top');  


		if(sum_baskets){
			// sum_top.classList.remove('disabled');
			delete_all.parentElement.classList.remove('disabled');
			buy_all.classList.remove('disabled_button');
			if(sum_baskets_block){
				const number = sum_baskets_block.querySelector('strong');
				number.innerHTML = sum_baskets+'<span class="rub black">Р</span>';
			}
		}else{
			// sum_top.classList.add('disabled');
			delete_all.parentElement.classList.add('disabled');
			buy_all.classList.add('disabled_button'); 
		}

		const basket_block_buttons = this.basket_box.querySelector('.small_basket_hover_block__buttons');
		if(basket_block_buttons){
				const basket_buy = basket_block_buttons.querySelector('.basket-buy');
				const warning = basket_block_buttons.querySelector('.basket_buy_button_warning');	

				if(this.limit){		
					if(this.limit >= sum_current_basket){
						buy_all.classList.remove('disabled_button');
						basket_buy.classList.remove('disabled_button');
						warning.classList.add('disabled_warning');

					}else{
						buy_all.classList.add('disabled_button'); 
						basket_buy.classList.add('disabled_button');
						warning.classList.remove('disabled_warning');
					}
				}
		}

		// return true;
		return 'blockBuyButton';
	},

	// !!!
	refreshCart: function (data)
	{
		console.log('refreshCart');

		// console.log(data);

		// if('sbblRemoveItemFromCart' in data){
		// 		console.log(data);
		// 		return;
		// }
		// if(this.itemRemoved)
		// {
			// 	this.itemRemoved = false;
			// 	return;
		// }

		const has_cls_active = $(".small_basket_hover_block").hasClass('active');
		data.sessid = BX.bitrix_sessid();
		data.siteId = this.siteId;
		data.templateName = this.templateName;
		data.arParams = this.arParams;
		data.lid = this.arParams.DAY_WEEK
		let this_ob = this;

		BX.ajax({
				method: 'POST',  
				dataType: 'json',
				url: '/local/components/alex/sale.basket.basket.old/ajax.php',
				data: data,
				start: true,
		    	cache: false,
		    	processData: true,
		     	scriptsRunFirst: true,
		    	emulateOnload: true,
		    	async:true,
				onsuccess: async function(data){
					// console.log(data['BASKET']);		
					this_ob.basket_box.innerHTML = "";
					if(data['BASKET']){
						this_ob.basket_box.innerHTML = data['BASKET'];
					}

			 		BX.onCustomEvent('OnBasketRefresh');
			 		this_ob.setCartBodyClosure;	    

			 		let sum_baskets = data['SUM_BASKETS']; 	
			 		let sum_current_basket = data['SUM_CURRENT_BASKET'];
			 		let nums = data['NUMS']; 
			 		// console.log(nums);
			 		if(nums){ 
			 			this_ob.itemsBlock.innerHTML = nums;			 			
			 		}else{
			 			this_ob.itemsBlock.innerHTML = '0';
			 		}

			 	    let getBasketItems = await this_ob.getBasketItems();
					let blockBuyButton = await this_ob.blockBuyButton(sum_current_basket, sum_baskets);
			 	    let checkElement = await this_ob.checkElement(sum_current_basket, nums);
				}
			});
	},

	setBasketNums:async function(nums){
		// console.log('setBasketNums');
		if(nums){
			this.itemsBlock.innerHTML = nums;
		}else{
			this.itemsBlock.innerHTML = '0';
		}
		return true;
	},

	getBasketItems: async function(nums){ // получаем товары в корзине
		// console.log('getBasketItems');
		if(this.arParams.ACTIVE_DAY_WEEK == this.arParams.DAY_WEEK && this.basketFly.style.right !== '0px'){
			const basket =  document.getElementById(this.cartId);
			const panelBlock = document.getElementById(this.arParams.DAY_WEEK);
			const block = document.querySelector('.basket_fly .opener');

	    	panelBlock.classList.add('shown');
			basket.parentNode.click();

			block.classList.add('active');
			setTimeout(function(){
		      	block.classList.remove('active');
		    }, 1000);
		}
		// return true;
		return 'getBasketItems';
		
	},

	checkElement: async function(sum_current_basket, nums){
		if(!this.parent_block.contains(this.elem) && this.arParams.ACTIVE_DAY_WEEK != this.arParams.DAY_WEEK){ 
			await this.createElement();
		}		
		const make_order = this.parent_block.querySelector('.make_order'); 

		if(sum_current_basket>0){
			if(nums > 0){
				const small_basket_block = this.parent_block.querySelector('.small_basket_block');
		        const small_basket_hover_block = this.parent_block.querySelector('.small_basket_hover_block');
				            
				if(this.parent_block.contains(small_basket_block)){
					small_basket_hover_block.classList.add('active');
					if(this.parent_block.contains(make_order)){
					    make_order.remove();
				    }
				} 
			}
		}
		else{  
			if(this.parent_block.contains(make_order)){
			    // console.log('ничего не далем');
			}
			else{
			    // console.log('создаем запись');
			    // sum_all_baskets.addClass('disabled');
			    if(!this.parent_block.contains(this.elem)){ 
					await this.createElement();
				}	
			}	
		}

		// return true;
		return 'checkElement';
	},

	createElement: async function(){
		// console.log('createElement');
		const element = document.createElement("p")
			  element.classList.add('make_order');
			  element.innerHTML = "сделайте заказ";
		const basket_box = this.parent_block.querySelector('.basket_box');
			  this.parent_block.insertBefore(element, basket_box);
		return true;
	},

	setCartBody: function (result)
	{
		if (this.cartElement)
			this.cartElement.innerHTML = result.replace(/#CURRENT_URL#/g, this.currentUrl);
		if (this.fixedPosition)
			setTimeout(this.fixAfterRenderClosure, 100);
	},

	// !!!
	removeItemFromCart: function (id)
	{
		console.log('removeItemFromCart'); 
        this.refreshCart({sbblRemoveItemFromCart: id});
        this.itemRemoved = true;
        BX.onCustomEvent('OnBasketChange');
	}
};



$(document).ready(function(){
    // $(document).on("click", "a#small_basket", function () {
    //     var $button = $(this);
    //     if (!$(".small_basket_hover_block").hasClass("active")) {
    //         $(".small_basket_hover_block").slideDown("fast", function () {
    //             $(this).addClass("active");
    //         });
    //         if (Number($('#small_basket .quant').html())) {
    //             $button.addClass('small_basket--active');
    //         }
    //     } else {
    //         $(".small_basket_hover_block").slideUp("fast", function () {
    //             $(this).removeClass("active");
    //         });
    //         $button.removeClass('small_basket--active');
    //     }

    //     return false;
    // });


    $(document).on("click", "a.small_basket_block", function () {
    	
    	// refreshCart.log('a.small_basket_block');

        var $button = $(this);
        if (!$(".small_basket_hover_block").hasClass("active")) {
            $(".small_basket_hover_block").slideDown("fast", function () {
                $(this).addClass("active");
            });
            // if (Number($('#small_basket .quant').html())) {
            //     $button.addClass('small_basket--active');
            // }
        } else {
            $(".small_basket_hover_block").slideUp("fast", function () {
                $(this).removeClass("active");
            });
            $button.removeClass('small_basket--active');
        }

        return false;
    });

    // перезагрузка после удаления товара в корзине 
    $(document).on("click", "a.small_basket_hover_delete_action", function () {
  		// console.log('delete');

  		const basketParam = $(this).parents('.small_basket_hover_delete').siblings(".small_basket_hover_name").children('.item_quantity');
	    const basket = initCart(basketParam, {cartId:basketParam.attr('data-cart-id')});
	    	  basket[basket['cartId']].removeItemFromCart($(this).attr("data-id"));
    });

    function initCart(basketParam, basket){
		for (let i in basket) {
			if(i == 'cartId'){
				basket[basket[i]] = new BitrixSmallCart;
				basket[basket[i]].siteId = basketParam.attr('data-siteid');
				basket[basket[i]].ajaxPath = basketParam.attr('data-ajax-path');
				basket[basket[i]].templateName = basketParam.attr('data-template-name');
				basket[basket[i]].arParams = JSON.parse(localStorage.getItem(basket.cartId)); // TODO \Bitrix\Main\Web\Json::encode
				basket[basket[i]].cartId = basket.cartId;
				basket[basket[i]].activate();
			}
		}
		return basket;
	}
 

    function chageInputQuantity(input, basket){
    	console.log('chageInputQuantity');
		var link = "/?action=ADD2BASKET&id=" + $(input).attr("id") + "&quantity=" + $(input).val();			
        $.ajax({
            type: 'POST',
            data: {id: $(input).attr("data-id"), quantity: $(input).val()},
            url: $(input).attr("data-path"),
            async: true,
            cache: false,
            dataType: "html",
            success: function(data) {
				if(data == 1){					
                    // basketId.refreshCart({});
                    basket[basket['cartId']].refreshCart({});
                }
            }
        });
	}

    $(document).on("change", ".small_basket_hover_quantity input", function () {	
		/**refactor */
		// const basketParam = $(this).parent('.item_quantity')
		// const basket = initCart(basketParam, {cartId:basketParam.attr('data-cart-id')});
		
		// var input = $(this);		
		// // var input = $(this).siblings(".small_basket_hover_quantity input");		
		// var val = parseInt($(this).val() || 0);
				
		// input.val(val);
		// /**end refactor */
		// chageInputQuantity(input, basket);        
	});	

    $(document).on("click", ".small_basket_hover_quantity a", function(){ 

    	const basketParam = $(this).parent('.item_quantity')
    	const basket = initCart(basketParam, {cartId:basketParam.attr('data-cart-id')});

		var input = $(this).siblings(".small_basket_hover_quantity input");
		var val = parseFloat(input.val());		
		var quantAfterPoint = input.val().substr(input.val().indexOf(".", input.val().length)).length;
		var ratio = parseFloat(input.attr('data-ratio'));			


        if($(this).hasClass("plus")){
			// val = (val+ratio).toFixed(quantAfterPoint); 
			if(val <= 98) {
				val = val + ratio;/**refactor */
    			input.val(val);
            	// chageInputQuantity(input);
            	chageInputQuantity(input, basket);
			}			
        } 
        else {
        	if(val > ratio){
				// val =(val-ratio).toFixed(quantAfterPoint); 
				if(val <= 99) {
					val = val - ratio;/**refactor */
					input.val(val);
					// chageInputQuantity(input);
					chageInputQuantity(input, basket);
				}
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