$.extend($.fancybox.defaults, {
    infobar : true,
    buttons : false,
});
$.extend($.fancybox.defaults.iframe, {
    preload : true
});

function is_mobile () {
    var mobile = (/ipod|android|blackberry|mini|windows\sce|palm/i.test(navigator.userAgent.toLowerCase()));
    return mobile;
}
function getClientWidth () {
    return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientWidth:document.body.clientWidth;
}
function getClientHeight () {
    return document.compatMode=='CSS1Compat' && !window.opera?document.documentElement.clientHeight:document.body.clientHeight;
}

/**
 * Идентификация целого числа
 * @param string n Число
 * @returns {boolean}
 */
function isInt(n) {
	return Number(n) === n && n % 1 === 0;
}

/**
 * Возвращает отформатированную цену
 * @param string price Цена
 */
function getFormattedPrice(price, template, precision = 2) {
	// преобразование в число
	price = parseFloat(price);

	if (isInt(price)) {
		return template.replace(/#/, number_format(price, 0, ".", " "));
	}

    return template.replace(/#/, number_format(price.toFixed(precision), precision, ".", " "));
}

function leftmenu () {
	/*	if ((getClientHeight() > parseInt($("#header").height()) + parseInt($("#left_side").height())) && getClientWidth() > parseInt($(".container").width())) {
	 if (is_mobile()) {
	 $("#left_side").css({ position: "absolute", top: $(document).scrollTop() + "px", left: "10px" });
	 $("#left_sideApp").css({ position: "absolute", top: parseInt($('#left_side').height())+20+$(document).scrollTop() + "px", left: "10px" });
	 } else {
	 if (window.location.pathname=='/' && $(document).scrollTop() < 340) {
	 $("#left_side").css({ position: "absolute", top: 20 + "px", left: "10px"});
	 $("#left_sideApp").css({ position: "absolute", top: parseInt($('#left_side').height())+40 + "px", left: "10px"});
	 } else {
	 $("#left_side").css({ position: "fixed", top: parseInt($("#header").height()) + 30 + "px", left: $(".main_container").offset().left + 10 + "px" });
	 $("#left_sideApp").css({ position: "fixed", top: parseInt($('#left_side').height())+30+parseInt($("#header").height()) + 20 + "px", left: $(".main_container").offset().left + 10 + "px" });
	 }
	 }
	 } else {
	 $("#left_side").css({ position: "absolute", top: "0px", left: "10px" });
	 $("#left_sideApp").css({ position: "absolute", top: parseInt($('#left_side').height())+20+"px", left: "10px" });
	 }*/
}
function topmenu () {
	/*var top = $(document).scrollTop();
	 if (top > 40) {
	 $("#header .header_menu").stop(true, true).slideUp("fast", function () { leftmenu (); });
	 if ($("#header").hasClass("headerAbsolute")) {
	 $("#header").animate({top: top + "px"}, 0, "swing");
	 } else {
	 $("#header").animate({top: 0 + "px"}, 0, "swing");
	 }
	 }
	 if (getClientWidth() > 768 && top <= 40) {
	 $("#header .header_menu").stop(true, true).slideDown("fast", function () { leftmenu (); });
	 }
	 if (is_mobile() || getClientWidth() < parseInt($(".container").width())) {
	 $("#header").addClass("headerAbsolute");
	 }

	 leftmenu ();*/
}

$(window).on("scroll", function () {
    var top = 0;
    if ($("#header.headerAbsolute").length > 0) {
        top = $(document).scrollTop();
    }
    if ($(document).scrollTop() > parseInt($("#header").height())) {
        // $("#header .header_menu").stop(true, true).slideUp("fast", function () { leftmenu (); });
        $("#header").stop(true, true).animate({top: top + "px"}, 0, "swing");
    } else {
        $("#header").stop(true, true).animate({top: "0px"}, 0, "swing");
    }
    // if (getClientWidth() > 768 && $(document).scrollTop() <= 40) {
    //     $("#header .header_menu").stop(true, true).slideDown("fast", function () { leftmenu (); });
    // }

    leftmenu ();
});

$(window).load(function() {
    topmenu ();
    resize_open_box ();
    main_block_page ();
});
$(window).resize(function () {
    var $body = $('body');
    topmenu ();
    resize_open_box ();
    main_block_page ();

    if ($body.hasClass('is-catalog-page')) {
        if ($(window).width() <= 768 - scrollbarWidth()) {
            moveFilter('sidebar');
            $('.left_side_container').insertAfter('#main_block_page > h1');
        } else {
            $('.left_side_container').insertAfter('.content');
            if ($('.smart_filter').data('filter-place') == 'content') {
                moveFilter('content');
            } else {
                moveFilter('sidebar');
            }
        }
    }

    
});

$(document).on("click", "#left_side .mobile_menu_button, #mobile_menu_list .mobile_menu_button", function (e) {
    var level = e.target.className.slice(-1),
        parent = $(this).closest(".depth_level_" + level);
    if (event.target.tagName.toLowerCase() === 'a') {}
    else {
        if (parent.hasClass("active")) {
            parent.find("ul.uldepth_level_" + level).stop(true, true).slideUp("fast", "swing");
            parent.removeClass("active");
        } else {
            parent.find("ul.uldepth_level_" + level).stop(true, true).slideDown("fast", "swing");
            parent.addClass("active");
        }
        return false;
    }
});

function main_block_page () {
    if($("#main_block_page").length){
        var height = getClientHeight();
        if ($(".wrapper").length > 0) {
            var height_page = $(".wrapper").height();
            if (height >= height_page) {
                height = height - parseFloat($("#main_block_page").offset().top) - parseFloat($("footer").height()) - parseFloat($("footer").css("margin-top")) - parseFloat($("footer").css("margin-bottom"));
                $("#main_block_page").css("min-height", height + "px");
            }
        }

        return false;
    }
}

if ($(window).width() > 820) {
    $.fancybox.defaults.iframe.css = {'width': '810px'}
}
$(window).load(function () {
    if ($('body').hasClass('is-product-page-popup')) {
        setTimeout(function(){parent.jQuery.fancybox.getInstance().update()},500);
    }
    $(this).on('click', function (e) {
        if ($('body').hasClass('is-product-page-popup')) {
            if ($(e.target).hasClass('fancybox-close-small')) {
	            parent.$.fancybox.close();
            } else {
	            parent.jQuery.fancybox.getInstance().update();
            }
        }
    });
});

$(document).on('onComplete.fb', function() {
    // $('[data-fancybox-close]').remove();
    $("#tutorial-sliders").owlCarousel({
        items: 1,
        dots: true,
        mouseDrag: true,
        autoPlay: false,
        autoplayHoverPause: true,
        nav: true,
        navSpeed: 500,
        loop: true,
        autoWidth: false,
        autoHeight: true,
    });
    // $("#tutorial-sliders").append('<button data-fancybox-close="" class="fancybox-close-small">&#215;</button>');
    //$(".fancybox-content").append('<button data-fancybox-close="" class="fancybox-close-small">&#215;</button>');
    $("#tutorial-sliders").parent().parent().addClass('not_vertical_move');
});

$(document).on('click', '.mobile_menu, .mobile_menu_bg', function (e) {
    var $menu = $('.mobile_menu_list_wrapper');
    e.preventDefault();
    
    let width = window.innerWidth;
    let mobile_menu_width = '100%';
    if(width <= 1024 && width >= 768){
        mobile_menu_width = '50%';
    }

    if (!$menu.hasClass('mobile_menu_list_wrapper--opened')) {
        $('.mobile_menu_bg').show();
        $menu.animate({
            width: mobile_menu_width
        }, function () {
            $menu.addClass('mobile_menu_list_wrapper--opened');
        })
    } else {
        $('.mobile_menu_bg').removeAttr('style');
        $menu.animate({
            width: 0
        }, function () {
            $menu.removeClass('mobile_menu_list_wrapper--opened');
        })
    }
});

$(window).bind("load", function() {
    $(".lzy").lazyload({event : "sporty"});
    var timeout = setTimeout(function() {
        $(".lzy").trigger("sporty")
    }, 1000);
});
$(document).on("mouseenter", ".section--grid-3 .img_box", function () {
    if (window.outerWidth > 400 && $(this).find(".hover_over").length > 0) {
        $(this).find(".hover_over").stop(true, true).fadeIn("fast");
    }
});
$(document).on("mouseleave", ".img_box", function () {
    if (window.outerWidth > 400 && $(this).find(".hover_over").length > 0) {
        $(this).find(".hover_over").stop(true, true).fadeOut("fast");
    }
});

// !!!
$(document).on("mouseenter", ".section--grid-3 .img_box", function () {
    if (window.outerWidth > 400 && $(this).find(".product-blocked").length > 0) {
        $(this).find(".open_fancybox").stop(true, true).fadeIn("fast");
    }
});
$(document).on("mouseleave", ".img_box", function () {
    if (window.outerWidth > 400 && $(this).find(".product-blocked").length > 0) {
        $(this).find(".open_fancybox").stop(true, true).fadeOut("fast");
    }
});


$(document).on('mouseenter', '.product-categories .item_element', function () {
    $(this).find('.hover-overlay').stop(true, true).fadeIn('fast');
});
$(document).on('mouseleave', '.product-categories .item_element', function () {
    $(this).find('.hover-overlay').stop(true, true).fadeOut('fast');
});

function adaptateScroll(){
    $(".adaptive_scroll_slider").each(function(){
        adaptItemScroll($(this));
    });
}

function adaptItemScroll (obj) {
    if (obj.length > 0) {
        if (obj.find(".item_element").length > 0) {
            var w = (parseInt(obj.closest(".scroll-wrapper").width()) - 43)/3;
            if (w < 250) {
                w = (parseInt(obj.closest(".scroll-wrapper").width()) - 23)/2;
                if (w < 250) {
                    w = parseInt(obj.closest(".scroll-wrapper").width() - 3);
                }
            }
            w = parseInt(w);
            obj.find(".item_element").css("width", w + "px");
            obj.css("width", ((w + 20) * obj.find(".item_element").length) - 17 + "px");

            setTimeout(function () {
                if (obj.closest(".section_box").find(".scroll-x").css("display") == "block") {
                    obj.closest(".section_box").find(".slide_scroll_left, .slide_scroll_right").show();
                } else {
                    obj.closest(".section_box").find(".slide_scroll_left, .slide_scroll_right").hide();
                }
            }, 500);
        }
    }
}
$(document).on("click", ".slide_scroll_left, .slide_scroll_right", function () {
    var obj = $("#"+$(this).parent().find(".section").attr("id"));
    if ($(this).hasClass("slide_scroll_left")) {
        var shift = -1;
    } else {
        var shift = 1;
    }
    var item_w = parseInt(obj.find(".item_element").outerWidth()) + 20;
    var step = Math.round(parseInt(obj.parent().scrollLeft()) / item_w) + shift;
    var left = step * item_w;
    if (left < 0) { left = 0; } else if (left > parseInt(obj.width())) { left = parseInt(obj.width()); }
    obj.parent().stop(true, true).animate({
        scrollLeft: left+"px"
    }, 350);
});

function adaptItemSection (obj) {
    if (obj.length > 0) {
        $(".section_box").css("width", $(".content").width() + 25 + "px");
        if (obj.find(".item_element").length > 0) {
            var w = (parseInt(obj.closest(".section_box").width()) - 75)/3;
            if (w < 250) {
                w = (parseInt(obj.closest(".section_box").width()) - 50)/2;
                if (w < 250) {
                    w = parseInt(obj.closest(".section_box").width()) - 25;
                }
            }
            obj.find(".item_element").css("width", w + "px");
        }
    }
}
/* Catalog */
$(document).on("change", "[name^='quantity'], .small-basket-quantity", function () {
    // console.log('change');

    var ratio = parseFloat($(this).parents(".good_box").find(".buy_button_a:visible").data('ratio'));
    if(isNaN(ratio)){
        ratio = 1;
    }
    if($(this).val() % ratio !== 0){
        $(this).val(Math.ceil($(this).val() / ratio) * ratio);
    }

});


$(document).on("click", ".product_quantity a", function () {
    var info_button = $(this).parents(".good_box").find(".buy_button_a:visible");
    var ratio = parseFloat(info_button.data('ratio'));
    if(isNaN(ratio)){
        ratio = 1;
    }
    var obj = $(this).closest(".product_quantity").find("input");
    var val = parseFloat(obj.val());
    if(isNaN(val) || !val){
        val = 0;
    }
    var t = false;
    if ($(this).hasClass("minus")) {
        if (val >  ratio) {
            obj.val(roundToNum(val -  ratio));
            t = true;
        }
    } else {
        obj.val(roundToNum(val +  ratio));
        t = true;
    }
    if($(this).parents(".bx_item_detail").length || $(this).parents(".section").length){
	    var priceFormat = info_button.data('price-format');

        if (typeof(info_button.data('price')) == "object") {
            var obPrice = info_button.data('price'),
                obOldPrice = info_button.data('old-price');
        } else {
            var price = info_button.data('price'),
	            oldPrice = info_button.data('old-price');
        }

        if ($(this).parents(".bx_item_detail").length) {
            var price_block = $(".main_detail_price_" + info_button.data('id') + " span.price"),
                old_price_block = $(".main_detail_price_" + info_button.data('id') + " span.old_price"),
	            economy_price_block = $(".main_detail_price_" + info_button.data('id') + " span.economy_price b");

	        price_block.html(getFormattedPrice(price * obj.val(), priceFormat, 2));
	        old_price_block.html(getFormattedPrice(oldPrice * obj.val(), priceFormat, 2));
	        economy_price_block.html(getFormattedPrice((oldPrice - price) * obj.val(), priceFormat, 2));
        } else if($(this).parents(".section").length) {
            //price_block = $(".main_preview_price_" + info_button.data('id') + " .price_box__actual-price");
            currency =  $($(this).parent().parent().parent().find(".price_box__actual-price .rub"));
            if(currency.length > 1){
                currency = currency[0];
            }
            actualPrice = $($(this).parent().parent().parent().find(".price_box__actual-price"));

            actualPrice.html(number_format((price * obj.val()/ratio).toFixed(2), 1, ".", " ") + "&nbsp").append(currency);

            actualPrice.html(number_format((price * obj.val()).toFixed(2), 0, ".", " ") + "&nbsp").append(currency);
        }
    }
    if (obj.closest(".basket_items_table").length > 0) {
        $(".basket_items_blocks_item").find('input[name='+obj.attr("name")+']').val(obj.val());
    }
    if (obj.closest(".basket_items_blocks_item").length > 0) {
        $(".basket_items_table").find('input[name='+obj.attr("name")+']').val(obj.val());
    }
    if ((obj.closest(".basket_items_blocks_item").length > 0 || obj.closest(".basket_items_table").length > 0) && t) {
        recalcBasketAjax();
    }

    if (obj.closest(".small_basket_hover_quantity").length > 0 && t) {
        $.ajax({
            data: "update_small_basket=Y&SMALL_BASKET_QUANTITY="+obj.val()+"&SMALL_BASKET_ID="+obj.attr("id").replace("QUANTITY_", "")+"&SMALL_BASKET_OPEN=Y",
            url: $("#small_basket_box").attr("data-path"),
            async: true,
            cache: false,
            success: function (html) {
                $("#small_basket_box").html(html);
                $("#small_basket").addClass("update");
                setTimeout(function() { $("#small_basket").removeClass("update") }, 1000);
                getmasktoinput ();
                $('.small_basket_overflow').scrollbar();
            }
        });
    }
    return false;
});

$(document).on("change", ".basket_items_table .item_quantity input", function () {
    
    // console.log('item_quantity');

    var obj = $(this);
    if (obj.closest(".basket_items_table").length > 0) {
        $(".basket_items_blocks_item").find('input[name='+obj.attr("name")+']').val(obj.val());
    }
    if (obj.closest(".basket_items_blocks_item").length > 0) {
        $(".basket_items_table").find('input[name='+obj.attr("name")+']').val(obj.val());
    }
    if ((obj.closest(".basket_items_blocks_item").length > 0 || obj.closest(".basket_items_table").length > 0)) {
        recalcBasketAjax();
    }
    if (obj.closest(".small_basket_hover_quantity").length > 0 && t) {
        $.ajax({
            data: "update_small_basket=Y&SMALL_BASKET_QUANTITY="+obj.val()+"&SMALL_BASKET_ID="+obj.attr("id").replace("QUANTITY_", "")+"&SMALL_BASKET_OPEN=Y",
            url: $("#small_basket_box").attr("data-path"),
            async: true,
            cache: false,
            success: function (html) {
                $("#small_basket_box").html(html);
                $("#small_basket").addClass("update");
                setTimeout(function() { $("#small_basket").removeClass("update") }, 1000);
                getmasktoinput ();
                $('.small_basket_overflow').scrollbar();
            }
        });
    }

    return false;
});


$(document).on("click", ".tabs_header .tabs_head a", function (e) {
	/*if ($(e.target).hasClass('tab-toggle-button')) {
	 $(this).closest('.tabs_header').toggleClass('tabs_header--opened');
	 return false;
	 }*/

    var hash_id = $(this).attr("id");
    $(this).attr("id", " ");
    window.location.hash = hash_id;
    history.pushState(null, null, location.href);
    $(this).attr("id", hash_id);

    var $list = $(this).closest('.tabs_header'),
        $element = $(e.currentTarget);
    if ($(window).width() <= 979) {
        if ($element.is($('.tabs_head:first-child a')) && !$list.hasClass('tabs_header--opened')) {
            e.preventDefault();
            $list.addClass('tabs_header--opened');
        } else {
            e.preventDefault();
            $list.removeClass('tabs_header--opened');
            $(".tabs_body").removeClass("active");
            $($(this).attr("data-href")).addClass("active");

            swapTabElements($element.closest('.tabs_head'), $list.find('.tabs_head:first-child'));
        }
    } else {
        $(this).closest(".tabs_header").find(".tabs_head").removeClass("active");
        $(this).closest(".tabs_head").addClass("active");
        $(".tabs_body").removeClass("active");
        $($(this).attr("data-href")).addClass("active");
        if ($(this).closest(".bx_item_detail").hasClass("bx_item_detail_popup")) {
            parent.jQuery.fancybox.getInstance().update();
        }

        return false;
    }

});

function swapTabElements($elm1, $elm2) {
    var $parent,
        $prev;

    $parent = $elm1.parent();
    $prev = $elm1.prev();

    if (!$prev.is($elm2)) {
        $elm1.prependTo($parent);
        $elm2.insertAfter($prev);
    } else {
        $elm1.prependTo($parent);
    }
}

$(document).on("click", ".product-tabs .nav-tabs a", function (e) {
    var $tabLink = $(e.currentTarget),
        $tabList = $tabLink.closest('.nav-tabs');
    $(this).parents(".product-tabs").find(".tab-content").show();
    if ($(window).width() <= 850) {
        if ($tabLink.is($tabList.find('li:first-child a')) && !$tabList.hasClass('nav-tabs--opened')) {
            e.preventDefault();
            $tabList.addClass('nav-tabs--opened');
            $tabList.find("li").removeClass("active");
        } else {
            e.preventDefault();
            $tabList.find("li").removeClass("active");
            $tabLink.closest("li").addClass("active");
            $tabList.removeClass('nav-tabs--opened');
            $tabLink.closest('.product-tabs').find(".tab-pane").removeClass("active").hide();
            $($tabLink.attr("href")).addClass("active").show();

            swapTabElements($tabLink.closest('li'), $tabList.find('li:first-child'));

            adaptItemScroll ($($(this).attr("href")).find('.section'));
        }
        return false;
    } else {
        $tabList.find("li").removeClass("active");
        $tabLink.closest("li").addClass("active");
        $tabLink.closest('.product-tabs').find(".tab-pane").removeClass("active").hide();
        $($tabLink.attr("href")).addClass("active").show();
        adaptItemScroll ($($(this).attr("href")).find('.section'));
        return false;
    }

});

function resize_open_box () {
    var width = getClientWidth ();
    if (width > 1280) {
        // $(".search_box").removeAttr('style');
    } else if (width > 960 && width <= 1280) {
		/*$(".search_box").css("width", parseInt($(".header .container").width()) - parseInt($(".header .logo").width()) - parseInt($(".header .logo").css("margin-left")) - parseInt($(".header .logo").css("margin-right")) - parseInt($(".header .phone").width()) - parseInt($(".header .phone").css("margin-left")) - parseInt($(".header .phone").css("margin-right")) - parseInt($("#small_basket").width()) - parseInt($("#small_basket").css("margin-left")) - parseInt($("#small_basket").css("margin-right")) - parseInt($(".header .search_box").css("margin-left")) - parseInt($(".header .search_box").css("margin-right")) - 75 + "px");*/
    } else if (width > 480) {
        $(".zoom").removeClass("no_zoom");
    } else if (width <= 480) {
        $(".zoom").addClass("no_zoom");
    }
}

/* OFFERS */ 
if (window.frameCacheVars !== undefined) {
    BX.addCustomEvent("onFrameDataReceived", function(){
        setTimeout(function(){all_func();adaptateScroll();
            setTimeout(function () {$('.product-tabs').find('li:visible:eq(0) a').click().click();},1000);
        }, 200);
    });
} else {
    // !!!
    $(document).ready(function(){
        all_func();
        adaptateScroll();
        setTimeout(function () {$('.product-tabs').find('li:visible:eq(0) a').click().click();},1000);
    });
}
function all_func() {
    var $body = $('body');
    topmenu ();
    resize_open_box ();
    main_block_page ();

    if ($body.hasClass('is-catalog-page')) {
        if ($(window).width() <= 768 - scrollbarWidth()) {
            moveFilter('sidebar');
            $('.smart_filter').show();
            $('.left_side_container').insertAfter('#main_block_page > h1');
        } else {
            $('.left_side_container').insertAfter('.content');
            if ($('.smart_filter').data('filter-place') == 'content') {
                moveFilter('content');
                $('.smart_filter').show();
            } else {
                moveFilter('sidebar');
                $('.smart_filter').show();
            }
        }
    }


    $(".fancybox").fancybox({
        autoSize : true,
        autoResize : true,
        autoCenter : true,
        openEffect : "fade",
        closeEffect : "fade",
        helpers: {
            overlay: {
                locked: true
            }
        }
    });
    // !!!
    // $(".scroll-standard").scrollbar({"showArrows": false});
    $(".scroll-standard").scrollbar(
        {"showArrows": true}
    );

    setTimeout(function () {
        $('.main_banner_big').removeClass('hidden');
        $('.main_banner_big').css("margin-bottom", "-50px");
    }, 250);

    $(function(){
        $(document).click(function(event) {
            if ($(".small_basket_hover_block").hasClass("active"))
            {
                if ($(event.target).closest(".small_basket_hover_block.active").length) return;
                $("#small_basket").trigger("click");
            }
        });
    });
    good_box();
    getmasktoinput();
    // $('.small_basket_overflow').scrollbar(); // !!!
    $('.product-tabs .nav-tabs a').on('shown.bs.tab', function (e) {
        adaptItemScroll($($(e.target).attr('href') + ' .section'));
    });
    if ($('body').hasClass('is-catalog-page') && !$('.smart_filter').length) {
        $('.bx_catalog_text').addClass('bx_catalog_text--beautiful-bottom').css('min-height', 'initial');
        $('.control_button_show').hide();
    }

    if ($('.bx_item_detail_rating').length) {
        $('.bx_item_detail_rating').css('top', $('.bx_breadcrumbs').outerHeight() + 20);
    }

    $('.section--list .catalog_section .item_element:first-child .item_props').removeAttr('style');

    $('.bx_item_detail_rating_wrapper .detail_buy_button').on('click', function (e) {
        e.preventDefault();
        var options = $('.product-options-wrapper').offset().top;
        $('body, html').animate({scrollTop: options - 96 - 40}, 0);
    });

    $('.bx_item_set_hor_container_big').on('click', function (e) {
        var $products,
            productsNumber,
            width,
            i;
        if ($(e.target).hasClass('bx_item_set_del')) {
            if ($(window).width() >= 980) {
                $products = $('.bx_item_set_hor_item');
                productsNumber = $products.length - 1;
                width = (100 / productsNumber) + '%';
                for (i = 0; i < productsNumber; i++) {
                    $($products[i]).css('width', width);
                }
            }
        }
    });

    $(document).on('click', '.list-props-button', function (e) {
		e.preventDefault();
		var $parent = $(this).closest('.char-pop-up');
		$parent.find('.item_props').stop(true, true).slideToggle('fast');
	});

	$(document).on('click', '.list-offer-button', function (e) {
        e.preventDefault();
        var $parent = $(this).closest('.char-pop-up');
        $parent.find('.offers_item').stop(true, true).slideToggle('fast');
    });

    $('.catalog-sorting__button').on('click', function (e) {
        var $button = $(this),
            $catalog = $('.wrapper'),
            gridMode = $catalog.data('grid-size');

        e.preventDefault();
        if (!$button.hasClass('catalog-sorting__button--active')) {
            $button
                .addClass('catalog-sorting__button--active')
                .siblings('button')
                .removeClass('catalog-sorting__button--active');
        }
        if ($button.hasClass('catalog-sorting__grid')) {
            $catalog
                .removeClass('section--list section--list-info section--list-small')
                .addClass('section--grid-' + gridMode);
            $.cookie('BITRIX_SM_catalog_view_mode', 'section--grid-' + gridMode, { expires: 7, path: '/' });
        } else if ($button.hasClass('catalog-sorting__list')) {
            $catalog
                .removeClass('section--grid-' + gridMode)
                .removeClass('section--list-small')
                .addClass('section--list section--list-info');
            $.cookie('BITRIX_SM_catalog_view_mode', 'section--list section--list-info', { expires: 7, path: '/' });
        } else {
            $catalog
                .removeClass('section--grid-' + gridMode)
                .removeClass('section--list-info')
                .addClass('section--list section--list-small');
            $('.section--list .catalog_section .item_element:first-child .item_props').removeAttr('style');
            $.cookie('BITRIX_SM_catalog_view_mode', 'section--list section--list-small', { expires: 7, path: '/' });
        }
    });

    $('.hover_box.box').hover(function () {
        parent = $(this).parents('.product-tabs');
        parent.css('z-index', 44);
    }, function () {
        parent = $(this).parents('.product-tabs');
        parent.css('z-index', 2);
    });


    $('.nav-tabs a').hover(function () {
       //$(this).closest('.product-tabs').find('.tab-content').css('min-height', '820px');
    },function () {
        $(this).closest('.product-tabs').find('.tab-content').css('min-height', '400px');
    });
}


function good_box () {
    if ($(".good_box").length > 0) {
        $(".good_box").each(function () {
            if ($(this).find(".offers_item").length > 0) {
                $(this).find(".offers_item .offer_sku").first().click();
            }
        });
    }

    return true;
}

function change_offer_item (offer_id, big_obj) {
    if (big_obj.hasClass("bx_item_detail")) {
        var type = "detail";
        var massive = ["main_detail_preview_text", "main_detail_price", "main_detail_text", "main_detail_props", "main_detail_quant"];
    } else {
        var type = "preview";
        var massive = ["main_preview_image", "main_preview_price", "main_preview_props"];
    }
    big_obj.find(".offers_hide").hide();
    if (type == "detail") {
        big_obj.find(".main_detail_slider_box").css({"z-index": "15", "opacity": "0"}).removeClass("active_box");
        if (big_obj.find(".main_detail_slider_" + offer_id).length > 0) {
            big_obj.find(".main_detail_slider_" + offer_id).css({"z-index": "20", "opacity": "1"}).addClass("active_box");
        } else {
            big_obj.find(".main_detail_slider").css({"z-index": "20", "opacity": "1"}).addClass("active_box");
        }
        img_box_height ();
    }
    for (var i = 0; i < massive.length; i++) {
        if (big_obj.find("." + massive[i] + "_" + offer_id).length > 0) {
            if(!big_obj.find("." + massive[i]).hasClass("item_props"))
                big_obj.find("." + massive[i] + "_" + offer_id).show();
        } else {
            if(!big_obj.find("." + massive[i]).hasClass("item_props"))
                big_obj.find("." + massive[i]).show();
        }
    }

    return false;
}
/* OFFERS */

/* BUY BUTTON */
function update_small_basket (basket_path) {
    if(basket_path === undefined){
        basket_path = $(window.top.document.getElementById('small_basket_box')).data("path") + "?update_small_basket=Y";
    }

    return false;
}
function showButtonClose () {
    $('.fancybox-content .fancybox-close-small').show();
}
function hideButtonClose () {
    $('.fancybox-content .fancybox-close-small').hide();
}
function showButtonClose () {
  $('.fancybox-content .fancybox-close-small').show();
}
function hideButtonClose () {
  $('.fancybox-content .fancybox-close-small').hide();
}

$(document).on("click", ".add_to_basket_box .button_white", function () {
    $.fancybox.close();

    return false;
});
$(document).on('click', '.popup-window-titlebar-close-icon', function () {
    $.fancybox.close();
});

$(document).on("click", ".show_offers_basket_popup", function () {
    var link = $(this).attr("href");
    var quant = "";
    if ($(this).closest(".good_box").find(".item_quantity input[type='text']").length > 0) { quant = "&" + $(this).closest(".good_box").find(".item_quantity input[type='text']").attr("name") + "=" + $(this).closest(".good_box").find(".item_quantity input[type='text']").val(); }
    var html = '<div class="add_to_basket_box good_box"><div class="head">'+$("#sfp_show_offers_head").html()+'</div><div class="img"><img class="radius5" src="'+$(this).attr("data-img")+'" title="'+$(this).attr("data-name")+'"></div><div class="name">'+$(this).attr("data-name")+'</div>'+$("#skuId"+$(this).attr("data-id")).parent().html()+'</div>';
    $.fancybox(html, {
        autoSize : true,
        autoResize : true,
        autoCenter : true,
        openEffect : "fade",
        closeEffect : "fade",
        helpers: {
            overlay: {
                locked: true
            }
        }
    });

    return false;
});
/* BUY BUTTON */

$(document).on("click", "#header .logo", function () {
    if ($(this).find("div").length > 0) { return false; }
});

function getmasktoinput () {
    var mask_t = "+7 (999) 999 9999";
    var placeholder_t = "+7 (___) ___ ____";
    $("#SMALL_BASKET_ORDER_PHONE").mask(mask_t, { placeholder: placeholder_t });
    $("#ORDER_PROP_3").mask(mask_t, { placeholder: placeholder_t });
}

function moveFilter(place) {
    var /*$showButton = $('.control_button_show'),*/
        $filter = $('.smart_filter'),
        $catalogText = $('.bx_catalog_text'),
        $resetButton = $('.bx_filter_control_section__reset');

    if ($filter.length) {
        if (place == 'sidebar') {
            $('.sidebar-filter').append($filter).show();
            $resetButton.insertAfter('.control_button');
            $catalogText.addClass('bx_catalog_text--beautiful-bottom');
            $('.bx_filter_block').removeAttr('style');
            $('.smart_filter_props').removeAttr('style');
        } else if (place == 'content') {
            $('.sidebar-filter').hide();
            //$showButton.insertAfter($catalogText);
            $filter.insertAfter($('.bx_catalog_text'));
            if (!$filter.hasClass('smart_filter--closed')) {
                $catalogText.removeClass('bx_catalog_text--beautiful-bottom');
            }
            $resetButton.insertBefore('.control_button');
            resizeFilterPropsBlock();
        }
    }
}

$(window).load(function(){
    $('.product-tabs').each(function () {
		var $productTabs = $(this);
		$productTabs.find('.nav.nav-tabs a').each(function () {
			var id = $(this).attr('href').slice(1);
			sliderContainer = '#section_' + id;
			if ($(sliderContainer + ' .item_element').length) {
				$(this).parent().show();
			}
		});

		$(this).find('.nav.nav-tabs').find('li:visible:eq(0) a').click().click();
	});
});


$(window).resize(function() { adaptateScroll(); });

function rtrim ( str, charlist ) {
    charlist = !charlist ? ' \s\xA0' : charlist.replace(/([\[\]\(\)\.\?\/\*\{\}\+\$\^\:])/g, '\$1');
    var re = new RegExp('[' + charlist + ']+$', 'g');
    return str.replace(re, '');
}

function roundToNum(n){
    return rtrim(rtrim(parseFloat(n).toFixed(2),"0"),".");
}


function scrollbarWidth() {
    var documentWidth = parseInt(document.documentElement.clientWidth);
    var windowsWidth = parseInt(window.innerWidth);
    var scrollbarWidth = windowsWidth - documentWidth;
    return scrollbarWidth;
}

function number_format( number, decimals, dec_point, thousands_sep ) {
    // Format a number with grouped thousands
    //
    // +   original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
    // +   improved by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +	 bugfix by: Michael White (http://crestidg.com)

    var i, j, kw, kd, km;

    // input sanitation & defaults
    if( isNaN(decimals = Math.abs(decimals)) ){
        decimals = 2;
    }
    if( dec_point == undefined ){
        dec_point = ",";
    }
    if( thousands_sep == undefined ){
        thousands_sep = ".";
    }

    i = parseInt(number = (+number || 0).toFixed(decimals)) + "";

    if( (j = i.length) > 3 ){
        j = j % 3;
    } else{
        j = 0;
    }

    km = (j ? i.substr(0, j) + thousands_sep : "");
    kw = i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands_sep);
    //kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).slice(2) : "");
    kd = (decimals ? dec_point + Math.abs(number - i).toFixed(decimals).replace(/-/, 0).slice(2) : "");


    return km + kw + kd;
}


BX.ready(function () {
    if(BX.admin !== undefined){
        var MyPanel = BX("header"),
            BxPanel = BX.admin.panel,
            FxPanel = function () {
                if (window.pageYOffset >= BxPanel.DIV.clientHeight && BxPanel.isFixed() === false) {
                    MyPanel.style.top = 0;
                } else if (BxPanel.isFixed() === true) {
                    MyPanel.style.top = BxPanel.DIV.clientHeight + "px";
                } else {
                    MyPanel.style.top = BxPanel.DIV.clientHeight - window.pageYOffset + "px";
                }
            };
        if (!!MyPanel) {
            FxPanel();
            window.onscroll = FxPanel;
            BX.addCustomEvent('onTopPanelCollapse', BX.delegate(FxPanel, this));
            BX.addCustomEvent('onTopPanelFix', BX.delegate(FxPanel, this));
        }
    }
});

$(document).ready(function () {
    $('#modal-but').click(function(){
        $('#modal-panel').toggleClass('modal-active-panel');
    });

    $("#tutor").owlCarousel({
        loop: false,
        nav: true,
        margin: 40,
        responsiveClass:true,
        responsive:{
            0:{items: 1, center: true, nav: false},
            600:{items:2, center: false, nav: false},
            920:{items: 3, nav: true},
            1200:{items: 3, center: false, nav: true},
            1250: {items: 5, nav: true}},
        autoWidth: false,
        navSpeed: 500,
        autoplayHoverPause: true,
    });
});


$(window).load(function () {
    img_box_height ();
    if(location.hash == "#characters" || location.hash == "#restoran" || location.hash == "#description"){
        $(".tabs_header").find(location.hash).click();
        $(".tabs_header").find(location.hash).parent().addClass('active');
        $(".tabs_header").removeClass("tabs_header--opened");
        $($(".tabs_header").find(location.hash).attr("data-href")).addClass("active");
        if ($(".tabs_header").find(".tabs_head").length == 1) {
            $(".tabs_header").find(location.hash    ).css("width", "100%");
        }
    }else if(location.hash != "#comment"){
        if ($(".tabs_header").find(".tabs_head").length > 0) {
            $(".tabs_header").find(".tabs_head a").eq(0).click();
            $(".tabs_header").find(".tabs_head").eq(0).addClass('active');
            $(".tabs_header").removeClass("tabs_header--opened");
            $(".tabs_body:first-child").addClass("active");
            if ($(".tabs_header").find(".tabs_head").length == 1) {
                $(".tabs_header").find(".tabs_head a").css("width", "100%");
            }
        }
    }
    var timerId = setInterval(function() {
        if(typeof window['showComment'] === 'function'){
            if(location.hash == "#comment"){
                $(".tabs_header").find(location.hash).click();
                $(".tabs_header").find(location.hash).parent().addClass('active');
                $(".tabs_header").removeClass("tabs_header--opened");
                $($(".tabs_header").find(location.hash).attr("data-href")).addClass("active");
                if ($(".tabs_header").find(".tabs_head").length == 1) {
                    $(".tabs_header").find(location.hash    ).css("width", "100%");
                }
            }
            clearInterval(timerId);
        }
    }, 200);
    $('[data-fancybox]').fancybox({
        iframe : {
            closeBtn : true,
        },
    });
});
$(window).load(function() { img_box_height (); });
$(window).resize(function () { img_box_height (); });
function img_box_height () {
    var height = 0;
    $(".img_box").find(".main_detail_slider_box.active_box").each(function () {
        if (parseFloat($(this).height()) > height) { height = parseFloat($(this).height()); }
    });
    $(".bx_item_detail .img_box").css("min-height", height + "px");
}


/**
 * Очистка корзины
 */
function EmptyBasket() {
    var $basketContainer = $('#update_big_basket_ajax .box');

    if ($basketContainer.length) {
	    $basketContainer.empty();
	    $basketContainer.append(BX.message('SALE_EMPTY_BASKET'));
    }
}


/**
 * Если товара нет в наличии
 */

// для раздела
$(document).on("click", ".section a.product-item-amount-field-btn-disabled", function () {
    if ($(this).hasClass('minus')) {
        return false;
    }

	var $quantityContainer = $(this).parent(),
		content = "<div class='product-item-amount'>Для оформления заказа недостаточно товара.<br/><b>Наличие:</b> " + $quantityContainer.find('input').val() + ' ' + $quantityContainer.find('div span:first-child').text() + "</div>";

	$.fancybox.open(content, {
		autoSize : false,
		autoResize : true,
		autoCenter : true,
		openEffect : "fade",
		closeEffect : "fade",
		height: "auto",
		width: "auto",
		helpers: {
			overlay: {
				locked: true
			}
		}
	});

	return false;
});

// для деталки
$(document).on("click", ".bx_item_detail .item_quantity a", function () {
	var $quantityContainer = $(this).parents('.product-quantity-wrapper'),
		$quantityMessageContainer = $(this).parents('.product-wrapper').find('.mess-show-max-quantity'),
        quantity = 0;

	if (!$(this).parents('.product-price-block').find('.bx-catalog-subscribe-button:visible').length) {
		quantity = $quantityContainer.find('input').val();
    }

	$quantityMessageContainer.empty();
	if ($(this).hasClass('plus') && $(this).hasClass('product-item-amount-field-btn-disabled')) {
		var content = "Для оформления заказа недостаточно товара.<br/><b>Наличие:</b> " + quantity + ' ' + $quantityContainer.find('div span:first-child').text();
		$quantityMessageContainer.append(content);
	}
});
$(document).on("click", ".product-mobile-block__add", function () {
    $(this).hide();
    $(this).next().show();
});
$(document).on("click", ".minus-mobile", function () {
    $(this).closest('.product-mobile-block__quantity').hide();
    $('.product-mobile-block__add').show();
});
$(window).on("scroll",function () {
    if ($('.product-images-wrapper').length) {
        var sHeight = $('.product-images-wrapper').height();
        var sOffset = $('.product-images-wrapper').offset();
        if ($(document).scrollTop() > sOffset.top + sHeight - 80) {
            $('.product-mobile-block').addClass('fixed');
        }
        else {
            $('.product-mobile-block').removeClass('fixed');
        }
    }
});
$(document).on("mouseover", ".bx_input_submit", function () {
    $('.bx_input_submit').parent().addClass("red");
});
$(document).on("mouseout", ".bx_input_submit", function () {
    $('.bx_input_submit').parent().removeClass("red");
});
function searchMobile() {
    if (window.innerWidth < 769) {
        $(document).on("click", ".bx_input_submit", function (e) {
            if ($(this).hasClass("open")) {}
            else {
                e.preventDefault();
                $(this).addClass('open');
                $('#title-search-input').slideDown();
            }
        });
        $(document).on('click touchstart', function (event) {
            if (!$(event.target).is('.bx_input_submit')&&!$(event.target).is('#title-search-input')) {
                $('#title-search-input').slideUp();
                $('.bx_input_submit.open').removeClass("open");
            }
        })
    }
}
$(window).load(function() { searchMobile(); });
$(window).resize(function () { searchMobile(); }); 

$(document).on( /*for update captcha*/
    'click',
    'img#code + a',
    function (event){
        $.get("/include/captcha.php", function(data){
            $("#sid").val(data);
            captchacode = $("#sid").val();
            $('#code').attr("src", "/bitrix/tools/captcha.php?captcha_sid="+data);
        });
        return false;
    }
);
$(document).ready(function(){
    if ($('*').is('.errortext')) $('html, body').animate({ scrollTop: $('.errortext').offset().top - $('#header').height() - 10}, 1500);
});
$(document).ready(function() {
    setInterval(function() {
        let $form = $('[data-valid-form]');
        if($form.length) {
            for(let i = 0; i < $form.length; i++) {
                applicationsForm($($form[i]))
            }
        }
    }, 100)
});
function applicationsForm(form) {
    let isLoader = true;
    let changeForm = false;
    form.submit(function() {
        if (!valid(form)) {
            return false;
        } 
    });
    (function successForm(){
        isLoader = false;
        bindKeuUp();
    })();
    function bindKeuUp(){
        form.on('keyup', 'input,textarea', function() {
            if (!changeForm) {
                changeForm = true;
            }
        });
        form.on('focusout', '[data-required]', function() {
            $(this).data('required-change', true);
            if (validElement($(this))) {
                $(this).removeClass('validate_error');
                $(this).addClass('validate_success');
            } else {
                $(this).addClass('validate_error');
                $(this).removeClass('validate_success');
            }
        });
        form.on('keyup', '[data-required]', function() {
            if ($(this).data('required-change')) {
                if (validElement($(this))) {
                    $(this).removeClass('validate_error');
                    $(this).addClass('validate_success');
                } else {
                    $(this).addClass('validate_error');
                    $(this).removeClass('validate_success');
                }
            }
        });
    }
    function valid(form) {
        let flag = true;
        form.find('[data-required]').removeClass('validate_error');
        form.find('[data-required]').removeClass('validate_success');
        form.find('[data-required]').each(function(){
            $(this).data('required-change', true);
            if (!validElement($(this))) {
                $(this).addClass('validate_error');
                $(this).removeClass('validate_success');
                flag = false;
            }
        });
        return flag;
    }
    function validElement(input) {
        let phoneMask = /^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{9}$|^$/;
        let mailPattern = /[0-9a-z._]+@[0-9a-z._]+\.[a-z]{2,5}/i;
        let val = input.val();
        let typeRequired = input.data('required');
        let typeInput = input.data('input');
        let valid = true;
        switch (typeRequired) {
            case 'phone':
                if (!val || !phoneMask.test(val)) {
                    valid = false;
                }
                break;
            case 'email':
                if (!val || !mailPattern.test(val)) {
                    valid = false;
                }
                break;
            case 'login':
                if (val.length < 3) {
                    valid = false;
                }
                break;
            case 'password':
                if (val.length < 6) {
                    valid = false;
                }
                break;
            case 'confirm':
                let passwordInput = form.find('[data-required="password"]');
                if (val.length < 6 || passwordInput.val() != val) {
                    valid = false;
                }
                break;
            default:
                if (!val) {
                    valid = false;
                }
        }
        return valid;
    }
}
$(document).ready(function() {
    let myInput = document.querySelectorAll('[data-required], [data-input]');
    $('[data-required="phone"], [data-input="phone"]').mask("+7 (999) 999-9999").attr('placeholder', '+7 (999) 999-9999');
    $('[data-required="email"], [data-input="email"]').attr('placeholder', 'example@mail.ru');
    for (let i = 0; i < myInput.length; i++) {
        myInput[i].onfocus = function () {
            myInput[i].classList.add('show_placeholder');
        };
        myInput[i].onblur = function () {
            myInput[i].classList.remove('show_placeholder');
        };
    }
});
$(document).ready(function(){
    //$(".scroll-delivery .scroll-standard, .product-tabs .scroll-standard").mouseenter(
      //  function() {
        //    $(".scroll-delivery .scroll-standard, .product-tabs .scroll-standard").css('padding-bottom','400px'); 
          //  $(".scroll-delivery .scroll-standard, .product-tabs .scroll-standard").css('margin-bottom','-400px');
        //}
    //);
    // $(".item_element.good_box.bx_catalog_item").hover(
    //     function () {
    //         $(".scroll-delivery .scroll-standard, .product-tabs .scroll-standard").css('padding-bottom','400px');
    //         $(".scroll-delivery .scroll-standard, .product-tabs .scroll-standard").css('margin-bottom','-400px');
    //     },
    //     function() {
    //         $(".scroll-delivery .scroll-standard, .product-tabs .scroll-standard").css('padding-bottom','0');
    //         $(".scroll-delivery .scroll-standard, .product-tabs .scroll-standard").css('margin-bottom','0');
    //     }
    // );
    /*slider_block_onmouseenter = function(event) {
        console.log(1);
        var target = document.querySelectorAll('.scroll-delivery .scroll-standard, .product-tabs .scroll-standard');
        for (var i = 0; i < slider_block.length; i++) {
            target[i].style.paddingBottom = '400px';
            target[i].style.marginBottom = '-400px';
        }
    };
    slider_block_onmouseleave = function(event) {
        console.log(2);
        var target = event.target;
        target.style.paddingBottom = '0';
        target.style.marginBottom = '0';
    };

    var slider_block = document.querySelectorAll('.scroll-delivery .scroll-standard, .product-tabs .scroll-standard');
    for (var i = 0; i < slider_block.length; i++) {
        console.log(slider_block[i]);
        slider_block[i].addEventListener("mouseenter", slider_block_onmouseenter, true);
        slider_block[i].addEventListener("mouseleave", slider_block_onmouseleave, true);
    }*/

    /*$(".scroll-delivery .scroll-standard, .product-tabs .scroll-standard").mouseenter(
        function() {
            $(".scroll-delivery .scroll-standard, .product-tabs .scroll-standard").css('padding-bottom','400px');
            $(".scroll-delivery .scroll-standard, .product-tabs .scroll-standard").css('margin-bottom','-400px');
        }
    );*/
    // var sel = $(".scroll-delivery .scroll-standard, .product-tabs .scroll-standard");
    // sel.hover(
    //      function () {
    //          sel.css('padding-bottom','400px');
    //          sel.css('margin-bottom','-400px');
    //      },
    //      function() {
    //          sel.css('padding-bottom','0');
    //          sel.css('margin-bottom','0');
    //      }
    // );

    if (!$(".order").find('.order__id').length ) return false;

    $(".order").find('.order__id').on('click', function(){
                    var $section = $(this).parents('.order');

                    if ( $section.hasClass('collapsed') ) {
                        $section.removeClass('collapsed').addClass('expanded');
                    } else {
                        if(!$section.find('.selected').length)
                            $section.removeClass('expanded').addClass('collapsed'); 
                    }

                    return false;
    });

});

// проверка времени покупки
$(document).on("click", ".btn_buy_one_click", function (event) {
    let date = new Date();
    // if(date.getHours() >= 23){
        // $('#init_banned').click();
    // }else{
        // $('#init_btn_buy_one_click').click();
    // }

    // $('#allBuyModal').modal('show');
    $('#init_btn_buy_one_click').click();
    $('.mobile_menu').click();
});


// покупка корзины
$(document).on("click", ".basket-buy", function (event) {
    event.preventDefault();
    let date = new Date();
    // if(date.getHours() >= 10){
    // if(date.getHours() >= 23){
    //     $('#init_banned').click();
    // }
    // else{
    //     // здесь редирект на страницу оформления заказа
    //     document.location.href = $(this).attr('href');
    // }
    document.location.href = $(this).attr('href');

});

// Покупка всех корзин в 1 клик
$(document).on("click", "#buy_all_baskets", function () {
        const obj = $(this);
        const data = {
            'buy_all_baskets':1
        }

        $.ajax({
                method: 'POST',
                data: data,
                url:'/local/ajax/buy_basket_all.php',
                async: true,
                cache: false,
                success: function (response) {
                    let data = JSON.parse(response);
                    if(data.REDIRECT == true && data.STATUS == 'OK'){
                        setTimeout(() => { 
                            // перезагрузка страницы
                            window.location.reload();
                        }, 500);
                    }else{
                          $("#allBuyModal .order_by_click").append("<p><strong>" + data.MESSAGE + "</strong></p>");
                    }
                    console.log(data);
                },
                error: function (ajaxContext) {
                    // console.log(ajaxContext.responseText);
                    setTimeout(() => { 
                        window.location.reload();
                    }, 500);
                }
        });
});  

// удаление всех корзин недели
$(document).on("click", "#delete-all-baskets", function (event) {
    $('#init_delete_all_one_click').click();
});
$(document).on("click", "#delete-all-mobile-baskets", function (event) {
    $('#init_delete_mobile_all_one_click').click();
    $('.mobile_menu').click();
});
$(document).on("click", "#delete_all_baskets", function () {
        const obj = $(this);
        const data = {
            'delete_all_baskets':1
        }

        // console.log('delete_all_baskets');

        $.ajax({
                method: 'POST',
                data: data,
                url:'/local/ajax/delete_basket_all.php',
                async: true,
                cache: false,
                success: function (data) {
                    console.log(data);
                    setTimeout(() => { 
                        // перезагрузка страницы
                        window.location.reload();
                    }, 200);
                },
                error: function (ajaxContext) {
                    // console.log(ajaxContext.responseText);
                    setTimeout(() => { 
                        window.location.reload();
                    }, 200);
                }
        });
         
});

$.event.special.touchstart = {
  setup: function( _, ns, handle ){
    if ( ns.includes("noPreventDefault") ) {
      this.addEventListener("touchstart", handle, { passive: false });
    } else {
      this.addEventListener("touchstart", handle, { passive: true });
    }
  }
};


$(document).on("click", "#section_new-products .item_quantity a.plus, #section_new-products .item_quantity a.minus", function (event) {
        console.log('item_quantity');
        const item = $('#'+event.target.id).parents('.item-info');
        const quantity = item.find('input').val();
        const price_block = item.find('.price_box__actual-price')[0];
        const price = quantity * price_block.dataset.price;
                element = document.querySelector('#'+price_block.id);
                element.innerHTML = '';
                element.innerHTML =  price+"&nbsp;<span class='rub black'>P</span>";
});


// $(document).on("click", "#show", function (event) {
//     console.log('show');
//     const delete_all = document.querySelector('#delete-all-baskets');  
//           delete_all.parentElement.classList.remove('disabled');
// });

// $(document).on("click", "#hide", function (event) {
//     console.log('hide');
//     const delete_all = document.querySelector('#delete-all-baskets');  
//           delete_all.parentElement.classList.add('disabled');
// });

