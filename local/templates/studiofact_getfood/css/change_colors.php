<div id="dynamic_change_colors_block">
	<? Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("dynamic_change_colors_block"); ?>
	<? if (strlen($_SESSION["CHANGE_COLOR"]) < 1) {
		$_SESSION["CHANGE_COLOR"] = "orange";
	}
	if (strlen($_REQUEST["change_color"]) > 0) {
		$_SESSION["CHANGE_COLOR"] = $_REQUEST["change_color"];
	} ?>
	<div class="change_colors_block radius5">
		<? if ($_SESSION["CHANGE_COLOR"] != "orange") { ?><a href="/?change_color=orange" class="item radius5 orange" id="change_color_orange"></a><? } ?>
		<? if ($_SESSION["CHANGE_COLOR"] != "blue") { ?><a href="/?change_color=blue" class="item radius5 blue" id="change_color_blue"></a><? } ?>
		<? if ($_SESSION["CHANGE_COLOR"] != "red") { ?><a href="/?change_color=red" class="item radius5 red" id="change_color_red"></a><? } ?>
		<? if ($_SESSION["CHANGE_COLOR"] != "purple") { ?><a href="/?change_color=purple" class="item radius5 purple" id="change_color_purple"></a><? } ?>
		<? if ($_SESSION["CHANGE_COLOR"] != "green") { ?><a href="/?change_color=green" class="item radius5 green" id="change_color_green"></a><? } ?>
	</div>
	<link href="<?=SITE_TEMPLATE_PATH;?>/css/colors_<?=$_SESSION["CHANGE_COLOR"];?>.css" type="text/css"  rel="stylesheet" />
	<style>
	.change_colors_block {
		position: fixed;
		top: 50%;
		left: -5px;
		background: #FFFFFF;
		color: #000000;
		width: auto;
		height: auto;
		margin-top: -140px;
		-webkit-box-shadow: 2px 2px 3px #CACACA;
		-moz-box-shadow: 2px 2px 3px #CACACA;
		box-shadow: 2px 2px 3px #CACACA;
		z-index: 50;
	}
	.change_colors_block .item {
		display: block;
		width: 40px;
		height: 40px;
		margin: 15px;
		cursor: pointer;
	}
	.change_colors_block .item.orange {
		background: #FC7A38;
	}
	.change_colors_block .item.blue {
		background: #3498DB;	
	}
	.change_colors_block .item.red {
		background: #E74C3C;	
	}
	.change_colors_block .item.purple {
		background: #9B59B6;	
	}
	.change_colors_block .item.green {
		background: #FFD700;	
	}
	</style>
	<script type="text/javascript">
		function open_colors () {
			if (parseFloat(getClientWidth()) > parseFloat($(".change_colors_block").width()) + parseFloat($(".main_container").width()) + 80) {
				 $(".change_colors_block").fadeIn("fast");
			} else {
				$(".change_colors_block").hide();
			}
		}
		$(window).resize(function () { open_colors (); });
		$(document).ready(function () { open_colors (); });
		$( window ).load(function() { open_colors (); });
	</script>
	<? Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("dynamic_change_colors_block", ""); ?>
</div>