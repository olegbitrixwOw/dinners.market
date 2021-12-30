$(document).on("click", ".opener", function (e) {

	e.preventDefault()

	console.log('open');

	function onOpenFlyBasket(_this){
		$(".basket_fly .tabs li").removeClass("cur");
		$(".basket_fly .tabs_content li").removeClass("cur");
		$(".basket_fly .remove_all_basket").removeClass("cur");
		if(!$(_this).is(".wish_count.empty"))
		{
			$(".basket_fly .tabs_content li[item-section="+$(_this).data("type")+"]").addClass("cur");
			$(".basket_fly .tabs li:eq("+$(_this).index()+")").addClass("cur");
			$(".basket_fly .remove_all_basket."+$(_this).data("type")).addClass("cur");
		}
		else
		{
			$(".basket_fly .tabs li").first().addClass("cur").siblings().removeClass("cur");
			$(".basket_fly .tabs_content li").first().addClass("cur").siblings().removeClass("cur");
			$(".basket_fly .remove_all_basket").first().addClass("cur");
		}
		$(".basket_fly .opener > div.clicked").removeClass('small'); 
	}

	if(window.matchMedia('(min-width: 769px)').matches)
	{
		var _this = this;

	if(parseInt($(".basket_fly").css("right")) < 0)
		{
			$(".basket_fly").stop().animate({"right": "0"}, 333, function(){
				if($(_this).closest('.basket_fly.loaded').length)
				{
					onOpenFlyBasket(_this);
				}
				else
				{
					// $.ajax({
					// 	url: arNextOptions['SITE_DIR'] + 'ajax/basket_fly.php',
					// 	type: 'post',
					// 	success: function(html){
					// 		$('.basket_fly').addClass('loaded').html(html);
					// 		onOpenFlyBasket(_this);
					// 	}
					// });
				}
			});
		}
	else if($(this).is(".wish_count:not(.empty)") && !$(".basket_fly .basket_sort ul.tabs li.cur").is("[item-section=DelDelCanBuy]"))
		{
			$(".basket_fly .tabs li").removeClass("cur");
			$(".basket_fly .tabs_content li").removeClass("cur");
			$(".basket_fly .remove_all_basket").removeClass("cur");
			$(".basket_fly .tabs_content li[item-section="+$(this).data("type")+"]").addClass("cur");
			$(".basket_fly .tabs li:eq("+$(this).index()+")").first().addClass("cur");
			$(".basket_fly .remove_all_basket."+$(this).data("type")).first().addClass("cur");
		}
		else if($(this).is(".basket_count") && $(".basket_fly .basket_sort ul.tabs li.cur").length && !$(".basket_fly .basket_sort ul.tabs li.cur").is("[item-section=AnDelCanBuy]"))
		{
			$(".basket_fly .tabs li").removeClass("cur");
			$(".basket_fly .tabs_content li").removeClass("cur");
			$(".basket_fly .remove_all_basket").removeClass("cur");
			$(".basket_fly .tabs_content li:eq("+$(this).index()+")").addClass("cur");
			$(".basket_fly .tabs li:eq("+$(this).index()+")").first().addClass("cur");
			$(".basket_fly .remove_all_basket."+$(this).data("type")).first().addClass("cur");
		}
		else
		{
			$(".basket_fly").stop().animate({"right": -$(".basket_fly").outerWidth()}, 150);
			$(".basket_fly .opener > div.clicked").addClass('small');
		}

	}
});