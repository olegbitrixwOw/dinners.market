'use strict';

function BitrixMobileCart(){}
BitrixMobileCart.prototype = { 

// function BitrixSmallCart(){}
// BitrixSmallCart.prototype = { 
	activate: function ()  
	{
		this.cartElement = BX(this.cartId);
		this.fixedPosition = this.arParams.POSITION_FIXED == 'Y'; 
		// this.fixedPosition = 'Y'; 
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
			// this.fixBuyAll();
			let resizeTimer = null;
			BX.bind(window, 'resize', function() {
				clearTimeout(resizeTimer);
				resizeTimer = setTimeout(fixCartClosure, 200);
			});
		}
		this.setCartBodyClosure = this.closure('setCartBody');

		// console.log(this.cartElement);

		if(this.cartElement){
			let parent = this.cartElement.parentElement;
				parent.dataset.cartId = this.cartId;
			this.parent = parent;
			// параметры корзины
			this.basket_box = document.querySelector('.basket_box[data-cart-id='+this.parent.dataset.cartId+']');
			this.parent_block = document.querySelector('.mobile_panel[data-id='+this.arParams.DAY_WEEK+']');
			this.basket_block_buttons = this.basket_box.querySelector('.small_basket_hover_block__buttons');
			this.basket_buy = this.basket_box.querySelector('.basket-buy');
		}

		if(this.arParams.ACTIVE_DAY_WEEK == this.arParams.DAY_WEEK){
			BX.addCustomEvent(window, 'OnMobileBasketChange', this.closure('refreshCart', {})); 
		}
		
		this.mobileBasketPanel = document.querySelector('.mobile_basket_panel');
		this.limit = 0;
		const limitDOM = document.querySelector('.limit');	
		if(limitDOM){
			this.limit = Number(limitDOM.dataset.daylimit);
		}
		this.buy_all = document.querySelector('.btn_buy_one_click');
	},

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

			// console.log('blockBuyButton');
			// console.log(this.limit);

			const buy_all = document.querySelector('#buy-all-mobile-baskets');
			const delete_mobile_all = document.querySelector('#delete-all-mobile-baskets');
			const sum_baskets_block = document.querySelector('#sum-all-mobile-baskets');

			if(sum_baskets){
				sum_baskets_block.classList.remove('disabled');
				delete_mobile_all.classList.remove('disabled');
				buy_all.classList.remove('disabled_button');
				const number = sum_baskets_block.querySelector('strong');
					number.innerHTML = sum_baskets+'<span class="rub black">Р</span>';
			}else{
				sum_baskets_block.classList.add('disabled');
				delete_mobile_all.classList.add('disabled');
				buy_all.classList.add('disabled_button'); 
			}
			const basket_box = document.querySelector('.basket_box[data-cart-id='+this.cartId+']');
			const basket_block_buttons = basket_box.querySelector('.small_basket_hover_block__buttons');
			if(basket_block_buttons){
				const basket_buy = basket_block_buttons.querySelector('.basket-buy');
				const warning = basket_block_buttons.querySelector('.basket_buy_button_warning');		
				if(this.limit){	
					if(this.limit > sum_current_basket){
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

		return 'blockBuyButton';
	},

	blockBuyButton: async function (sum_current_basket, sum_baskets){

				const buy_all = document.querySelector('#buy-all-mobile-baskets');
				const delete_mobile_all = document.querySelector('#delete-all-mobile-baskets');
				const sum_baskets_block = document.querySelector('#sum-all-mobile-baskets');
				const sum_top = document.querySelector('.sum-top'); 

				if(sum_baskets){
					sum_top.classList.remove('disabled');
					buy_all.classList.remove('disabled_button');
					
					if(sum_baskets_block){
						const number = sum_baskets_block.querySelector('strong');
						number.innerHTML = sum_baskets+'<span class="rub black">Р</span>';
					}
								
				}else{
					sum_top.classList.add('disabled');
					buy_all.classList.add('disabled_button');
				}

				const basket_box = document.querySelector('.basket_box[data-cart-id='+this.cartId+']');
				const basket_block_buttons = basket_box.querySelector('.small_basket_hover_block__buttons');
				if(basket_block_buttons){
					const basket_buy = basket_block_buttons.querySelector('.basket-buy');
					const warning = basket_block_buttons.querySelector('.basket_buy_button_warning');	
			
					if(this.limit){	

						if(this.limit > sum_current_basket){
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
		const has_cls_active = $(".mobile_basket_hover_block").hasClass('active');
		data.sessid = BX.bitrix_sessid();
		data.siteId = this.siteId;
		data.templateName = this.templateName;
		data.arParams = this.arParams;
		data.arParams.LIMIT = this.limit;
		data.lid = this.arParams.DAY_WEEK
		let this_ob = this;

		// console.log(data);
		// console.log(data.lid);
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
					// if(this_ob.cartElement){
					// 	this_ob.basket_box.innerHTML = "";
					// 	if(data['BASKET']){
					// 		this_ob.basket_box.innerHTML = data['BASKET'];
					// 	}
					// }
					// console.log(data['BASKET']);
					const basket_box = document.querySelector('.basket_box[data-cart-id='+this_ob.cartId+']');
					basket_box.innerHTML = "";
					if(data['BASKET']){
						basket_box.innerHTML = data['BASKET'];
					}

			 		BX.onCustomEvent('OnBasketRefresh'); 
			 		this_ob.setCartBodyClosure;	    
			 		let sum_baskets = data['SUM_BASKETS']; 	
			 		let sum_current_basket = data['SUM_CURRENT_BASKET'];
			 		let nums = data['NUMS']; 
				 	let setBasketNums = await this_ob.setBasketNums(nums, sum_current_basket);
					let blockBuyButton = await this_ob.blockBuyButton(sum_current_basket, sum_baskets);
				 	let checkElement = await this_ob.checkElement(sum_current_basket, nums);
				 	await this_ob.showMobilePanel(this_ob.arParams.DAY_WEEK);
				}
			});
	},

	checkElement: async function(sum_current_basket, nums){

		console.log('checkElement');
		// console.log(sum_current_basket);
		// console.log(nums);
		
		const parent_block = document.querySelector('.mobile_panel[data-id='+this.arParams.DAY_WEEK+']');
		const make_order = parent_block.querySelector('.make_order');
		if(sum_current_basket>0){
			if(nums > 0){
				const small_basket_block = parent_block.querySelector('.small_basket_block');
				const small_basket_hover_block = parent_block.querySelector('.small_basket_hover_block');

					if(parent_block.contains(small_basket_block)){
						small_basket_hover_block.classList.add('active');
					    make_order.classList.add("make_order_hide");
					}
			}
		}
		else{  
			// await this.createElement(parent_block);	
			make_order.classList.remove("make_order_hide");
		}
		return 'checkElement';
	},

	showMobilePanel:async function(panel_id){
		$('[data-id='+panel_id+']').addClass('shown');
			if (!$("[data-id="+panel_id+"] .small_basket_hover_block").hasClass("active")) {
				$("[data-id="+panel_id+"] .small_basket_hover_block").slideDown("fast", function () {
				$("[data-id="+panel_id).addClass("active");
			});
		} 
	},

	setBasketNums:async function(nums, sum_current_basket){
		// console.log('setBasketNums');
		// console.log(nums, sum_current_basket);
		// console.log(this.mobileBasketPanel);

		const quant = this.mobileBasketPanel.querySelector('.quant');
		const summ = this.mobileBasketPanel.querySelector('.summ span');
		if(nums){
			quant.innerText  = nums;
			summ.innerText  = sum_current_basket;
		}else{
			quant.innerText = '0';
			summ.innerText = '0';
		}
		return true;
	},


/*	checkElement: async function(sum_current_basket, nums){

		const parent_block = document.querySelector('.mobile_panel[data-id='+this.arParams.DAY_WEEK+']');
		// console.log(parent_block);

		if(sum_current_basket>0){
			if(nums > 0){
				if(parent_block){
						const small_basket_block = parent_block.querySelector('.small_basket_block');
				 		const small_basket_hover_block = parent_block.querySelector('.small_basket_hover_block');

						if(parent_block.contains(small_basket_block)){
							small_basket_hover_block.classList.add('active');
							const make_order = parent_block.querySelector('.make_order');
							if(make_order){
								if(parent_block.contains(make_order)){
								    make_order.remove();
							    }
							}
						} 
				}
			
			}
		}
		else{  
			await this.createElement(parent_block);	
		}

		return 'checkElement';
	},*/

	createElement: async function(parent_block){
		// const element = document.createElement("p")
		// 	  element.classList.add('make_order');
		// 	  element.innerHTML = "сделайте заказ";

		// if(this.parent_block){
		// 	const basket_box = parent_block.querySelector('.basket_box');
		// 	parent_block.insertBefore(element, basket_box);
		// }
		if(this.parent_block){
			const basket_box = parent_block.querySelector('.basket_box');
				  basket_box.insertAdjacentHTML('beforebegin', '<p class="make_order">сделайте заказ</p>');	

		}
		return true;
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
		// console.log('removeItemFromCart'); 
		
        this.refreshCart({sbblRemoveItemFromCart: id});
        this.itemRemoved = true;
        // BX.onCustomEvent('OnBasketChange');
        BX.onCustomEvent('OnMobileBasketChange');
	}
};



$(document).ready(function(){

    $(document).on("click", "a.small_basket_block", function () {

        var $button = $(this);
        if (!$(".small_basket_hover_block").hasClass("active")) {
            $(".small_basket_hover_block").slideDown("fast", function () {
                $(this).addClass("active");
            });
        } else {
            $(".small_basket_hover_block").slideUp("fast", function () {
                $(this).removeClass("active");
            });
            $button.removeClass('small_basket--active');
        }

        return false;
    });

    // перезагрузка после удаления товара в корзине 
    $(document).on("click", "a.mobile_basket_hover_delete_action", function () {
  		console.log('delete');
  		// console.log($(this).attr("data-id"));

  		const basketParam = $(this).parents('.small_basket_hover_delete').siblings(".small_basket_hover_name").children('.item_quantity');
	    const basket = initMobileCart(basketParam, {cartId:basketParam.attr('data-cart-id')});
	    	  basket[basket['cartId']].removeItemFromCart($(this).attr("data-id"));

	   	// console.log(basketParam.attr('data-cart-id'));
    });

    function initMobileCart(basketParam, basket){
		for (let i in basket) {
			if(i == 'cartId'){
				basket[basket[i]] = new BitrixMobileCart;
				basket[basket[i]].siteId = basketParam.attr('data-siteid');
				basket[basket[i]].ajaxPath = basketParam.attr('data-ajax-path');
				basket[basket[i]].templateName = basketParam.attr('data-template-name');
				basket[basket[i]].arParams = JSON.parse(localStorage.getItem(basket.cartId+'_mobile')); // TODO \Bitrix\Main\Web\Json::encode
				basket[basket[i]].cartId = basket.cartId;
				basket[basket[i]].activate();
			}
		}
		return basket;
	}
 

    function chageInputQuantityMobile(input, basket){
    	console.log('chageInputQuantityMobile');
    	// console.log(input);
    	// console.log(basket);

        var link = "/?action=ADD2BASKET&id=" + $(input).attr("id") + "&quantity=" + $(input).val();

        console.log({id: $(input).attr("data-id"), quantity: $(input).val()});
        console.log($(input).attr("data-path"));
        $.ajax({
            type: 'POST',
            data: {id: $(input).attr("data-id"), quantity: $(input).val()},
            url: $(input).attr("data-path"),
            async: true,
            cache: false,
            dataType: "html",
            success: function(data) {
                if(data == 1){
                    basket[basket['cartId']].refreshCart({});
                }
            }
        });
	}

    // $(document).on("change", ".mobile_basket_hover_quantity input", function () {
    //    // chageInputQuantityMobile(this);
    // });


    $(document).on("click", ".mobile_basket_hover_quantity a", function(){ 

    	// console.log('mobile_basket_hover_quantity');

    	const basketParam = $(this).parent('.item_quantity');
    	const basket = initMobileCart(basketParam, {cartId:basketParam.attr('data-cart-id')});

    	// console.log(basketParam.attr('data-cart-id'));
    	// console.log(basket);

		var input = $(this).siblings(".mobile_basket_hover_quantity input");
		var val = parseFloat(input.val());
		var quantAfterPoint = input.val().substr(input.val().indexOf(".", input.val().length)).length;
		var ratio = parseFloat(input.attr('data-ratio'));
        if($(this).hasClass("plus")){
            val = (val+ratio).toFixed(quantAfterPoint);
    		input.val(val);
            chageInputQuantityMobile(input, basket);
        } 
        else {
        	if(val > ratio){
                val =(val-ratio).toFixed(quantAfterPoint);
                input.val(val);
                chageInputQuantityMobile(input, basket);
        	}
        }
	});

});