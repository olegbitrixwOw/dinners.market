<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);

$i = 0;
$expanded = false;
if(isset($_GET["set_filter"])){
	$expanded = true;
}
foreach ($arResult["ITEMS"] as $key => $arItem) {
	if( $arItem["DISPLAY_EXPANDED"] == "Y" ){
		$expanded = true;
	}
	if (!empty($arItem["VALUES"]) && ($arItem["VALUES"]["MIN"]["VALUE"] != $arItem["VALUES"]["MAX"]["VALUE"] || !isset($arItem["VALUES"]["MIN"]["VALUE"]))) {
		$i++;
	}
}
//if ($i == 0) { return; }

CJSCore::Init(array("fx")); ?>
<?
$position = 0;
if(CGetfood::getOption("FILTER")== "vertical"){
	$position = "sidebar";
}elseif(CGetfood::getOption("FILTER")== "horizontal"){
	$position = "content";
}
?>
<div class="bx_filter_horizontal box padding smart_filter <?=($expanded) ? "" : " smart_filter--closed test"?>" data-filter-place="<?=$position?>" style="overflow: unset; display: none">
	<div class="control_button_show box">
		<h3 class="sidebar-filter__title"><?=GetMessage("SF_FILTER");?></h3>
		<a href="javascript: void(0);" title="<?=GetMessage("SF_SHOW_FILTER_PARAMS");?>"></a>
	</div>
	<div class="bx_filter_section m4"<?=($expanded) ? "" : ' style="overflow: hidden; display: none;"'?>>
		<div class="bx_filter_title"><?echo GetMessage("CT_BCSF_FILTER_TITLE")?></div>
		<form name="<?echo $arResult["FILTER_NAME"]."_form"?>" action="<?echo $arResult["FORM_ACTION"]?>" method="get" class="smartfilter">
			<div class="smart_filter_props">
				<div class="smart_filter_props0 clearfix">
					<? foreach($arResult["ITEMS"] as $key => $arItem):
						$key = md5($key);
						if (isset($arItem["PRICE"])):
							if (!$arItem["VALUES"]["MIN"]["VALUE"] || !$arItem["VALUES"]["MAX"]["VALUE"] || $arItem["VALUES"]["MIN"]["VALUE"] == $arItem["VALUES"]["MAX"]["VALUE"])
								continue;
							?>
							<div class="bx_filter_container border_line price_filter">
								<div class="row">
									<div class="bx_filter_container_title col-xs-12"><?=$arItem["NAME"]?></div>
									<div class="col-xs-12 col-sm-8 col-md-9 col-lg-9 sidebar-filter__slider">
										<div class="bx_ui_slider_track" id="drag_track_<?=$key?>">
											<div class="bx_ui_slider_range" style="left: 0; right: 0%;"  id="drag_tracker_<?=$key?>"></div>
											<a class="bx_ui_slider_handle left"  href="javascript:void(0)" style="left:0;" id="left_slider_<?=$key?>"></a>
											<a class="bx_ui_slider_handle right" href="javascript:void(0)" style="right:0%;" id="right_slider_<?=$key?>"></a>
										</div>
										<div class="bx_filter_param_area">
											<div class="bx_filter_param_area_block left_value" id="curMinPrice_<?=$key?>"><?=round($arItem["VALUES"]["MIN"]["VALUE"]);?></div>
											<div class="bx_filter_param_area_block right_value" id="curMaxPrice_<?=$key?>"><?=round($arItem["VALUES"]["MAX"]["VALUE"]);?></div>
											<div style="clear: both;"></div>
										</div>
									</div>
									<div class="bx_filter_param_area bx_filter_param_area__right col-xs-12 col-sm-4 col-md-3 col-lg-3 clearfix">
										<div class="bx_filter_param_area_block bx_filter_param_area_block__min">
											<div class="bx_input_container">
												<input class="min-price" type="text" name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>" id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>" value="<? if (intVal($arItem["VALUES"]["MIN"]["HTML_VALUE"]) > 0) { echo intVal($arItem["VALUES"]["MIN"]["HTML_VALUE"]); } else { echo intVal($arItem["VALUES"]["MIN"]["VALUE"]); } ?>" size="5" onkeyup="smartFilter.keyup(this)" />
											</div>
										</div>
										<div class="bx_filter_param_area_block bx_filter_param_area_block__divider">-</div>
										<div class="bx_filter_param_area_block bx_filter_param_area_block__max">
											<div class="bx_input_container">
												<input class="max-price" type="text" name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>" id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>" value="<? if (intVal($arItem["VALUES"]["MAX"]["HTML_VALUE"]) > 0) { echo intVal($arItem["VALUES"]["MAX"]["HTML_VALUE"]); } else { echo intVal($arItem["VALUES"]["MAX"]["VALUE"]); } ?>" size="5" onkeyup="smartFilter.keyup(this)" />
											</div>
										</div>
									</div>
								</div>
							</div>
							<script type="text/javascript" defer="defer">
								var DoubleTrackBar<?=$key?> = new cDoubleTrackBar('drag_track_<?=$key?>', 'drag_tracker_<?=$key?>', 'left_slider_<?=$key?>', 'right_slider_<?=$key?>', {
									OnUpdate: function(){
										BX("<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").value = this.MinPos;
										BX("<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>").value = this.MaxPos;
									},
									Min: parseFloat(<?echo intVal($arItem["VALUES"]["MIN"]["VALUE"])?>),
									Max: parseFloat(<?echo intVal($arItem["VALUES"]["MAX"]["VALUE"])?>),
									MinInputId : BX('<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>'),
									MaxInputId : BX('<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>'),
									FingerOffset: 8,
									MinSpace: 1,
									RoundTo: 1,
									Precision: 2
								});
							</script>
						<?endif?>
					<?endforeach?>

					<? $i = 0; foreach($arResult["ITEMS"] as $key => $arItem): $i++;?>

						<? if  ($arItem["PROPERTY_TYPE"] == "N"):
							if (!$arItem["VALUES"]["MAX"]["VALUE"] || $arItem["VALUES"]["MIN"]["VALUE"] == $arItem["VALUES"]["MAX"]["VALUE"]){
								continue;
							}

							?>
							<div class="bx_filter_container border_line price_filter<? if ($i == 0) { echo ' active'; } ?>">
								<div class="row">
									<div class="bx_filter_container_title col-xs-12"><?=$arItem["NAME"]?></div>
									<div class="col-xs-12 col-sm-8 col-md-9 col-lg-9 sidebar-filter__slider">
										<div class="bx_ui_slider_track" id="drag_track_<?=$key?>">
											<div class="bx_ui_slider_range" style="left: 0; right: 0%;"  id="drag_tracker_<?=$key?>"></div>
											<a class="bx_ui_slider_handle left"  href="javascript:void(0)" style="left:0;" id="left_slider_<?=$key?>"></a>
											<a class="bx_ui_slider_handle right" href="javascript:void(0)" style="right:0%;" id="right_slider_<?=$key?>"></a>
										</div>
										<div class="bx_filter_param_area">
											<div class="bx_filter_param_area_block left_value" id="curMinPrice_<?=$key?>"><?=$arItem["VALUES"]["MIN"]["VALUE"]?></div>
											<div class="bx_filter_param_area_block right_value" id="curMaxPrice_<?=$key?>"><?=$arItem["VALUES"]["MAX"]["VALUE"]?></div>
											<div style="clear: both;"></div>
										</div>
									</div>
									<div class="bx_filter_param_area bx_filter_param_area__right col-xs-12 col-sm-4 col-md-3 col-lg-3 clearfix">
										<div class="bx_filter_param_area_block bx_filter_param_area_block__min">
											<div class="bx_input_container">
												<input class="min-price" type="text" name="<?echo $arItem["VALUES"]["MIN"]["CONTROL_NAME"]?>" id="<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>" value="<? if (intVal($arItem["VALUES"]["MIN"]["HTML_VALUE"]) > 0) { echo intVal($arItem["VALUES"]["MIN"]["HTML_VALUE"]); } else { echo intVal($arItem["VALUES"]["MIN"]["VALUE"]); } ?>" size="5" onkeyup="smartFilter.keyup(this)" />
											</div>
										</div>
										<div class="bx_filter_param_area_block bx_filter_param_area_block__divider">-</div>
										<div class="bx_filter_param_area_block bx_filter_param_area_block__max">
											<div class="bx_input_container">
												<input class="max-price" type="text" name="<?echo $arItem["VALUES"]["MAX"]["CONTROL_NAME"]?>" id="<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>" value="<? if (intVal($arItem["VALUES"]["MAX"]["HTML_VALUE"]) > 0) { echo intVal($arItem["VALUES"]["MAX"]["HTML_VALUE"]); } else { echo intVal($arItem["VALUES"]["MAX"]["VALUE"]); } ?>" size="5" onkeyup="smartFilter.keyup(this)" />
											</div>
										</div>
										<div style="clear: both;"></div>
									</div>

								</div>
							</div>

							<script type="text/javascript" defer="defer">
								var DoubleTrackBar<?=$key?> = new cDoubleTrackBar('drag_track_<?=$key?>', 'drag_tracker_<?=$key?>', 'left_slider_<?=$key?>', 'right_slider_<?=$key?>', {
									OnUpdate: function(){
										BX("<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>").value = this.MinPos;
										BX("<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>").value = this.MaxPos;
									},
									Min: parseFloat(<?=$arItem["VALUES"]["MIN"]["VALUE"]?>),
									Max: parseFloat(<?=$arItem["VALUES"]["MAX"]["VALUE"]?>),
									MinInputId : BX('<?echo $arItem["VALUES"]["MIN"]["CONTROL_ID"]?>'),
									MaxInputId : BX('<?echo $arItem["VALUES"]["MAX"]["CONTROL_ID"]?>'),
									FingerOffset: 8,
									MinSpace: 1,
									RoundTo: 0.01,
									Precision: 2
								});
							</script>
						<?elseif(!empty($arItem["VALUES"]) && !isset($arItem["PRICE"])):?>
							<div class="bx_filter_container">
								<span class="bx_filter_container_title"><?=$arItem["NAME"]?></span>
								<div class="bx_filter_block" style="display: none;">


									<?foreach($arItem["VALUES"] as $val => $ar):?>
										<span class="<?echo $ar["DISABLED"] ? 'disabled': ''?> NiceCheck">
										<input type="checkbox" value="<?echo $ar["HTML_VALUE"]?>" name="<?echo $ar["CONTROL_NAME"]?>" id="<?echo $ar["CONTROL_ID"]?>" <?echo $ar["CHECKED"]? 'checked="checked"': ''?> onclick="smartFilter.click(this)" />
										<label for="<?echo $ar["CONTROL_ID"]?>"><?echo $ar["VALUE"];?></label>
									</span>
									<?endforeach;?>


								</div>
							</div>
						<?endif;?>
					<?endforeach;?>
				</div>
			</div>

			<? foreach($arResult["HIDDEN"] as $arItem): ?>
				<input type="hidden" name="<?echo $arItem["CONTROL_NAME"]?>" id="<?echo $arItem["CONTROL_ID"]?>" value="<?echo $arItem["HTML_VALUE"]?>" />
			<? endforeach; ?>

			<div style="clear: both;"></div>
			<div class="bx_filter_control_section clearfix">
				<input class="bx_filter_control_section__reset" type="submit" id="del_filter" name="del_filter" value="<?=GetMessage("CT_BCSF_DEL_FILTER")?>" />
				<!--<div class="control_button_hide">
					<a href="javascript: void(0);" class="javascript"><?/*=GetMessage("SF_HIDE_FILTER_PARAMS");*/?></a>
				</div>-->
				<div class="control_button">
					<div class="bx_filter_popup_result" id="modef" <? if(!isset($arResult["ELEMENT_COUNT"])) echo 'style="display:none"'; ?>>
						<?echo GetMessage("CT_BCSF_FILTER_COUNT", array("#ELEMENT_COUNT#" => '<span id="modef_num">'.intval($arResult["ELEMENT_COUNT"]).'</span>'));?>
						<a href="<?echo $arResult["FILTER_URL"]?>" class="bx_filter_popup_result_submit" target=""><?echo GetMessage("CT_BCSF_FILTER_SHOW")?></a>
					</div>
					<button class="bx_filter_control_section__filter" type="submit" id="set_filter" name="set_filter"><?=GetMessage("CT_BCSF_SET_FILTER")?></button>
				</div>
			</div>
		</form>
		<div style="clear: both;"></div>
	</div>
</div>
<script>
	var smartFilter = new JCSmartFilter('<?echo CUtil::JSEscape($arResult["FORM_ACTION"])?>', '<?=CUtil::JSEscape($arParams["FILTER_VIEW_MODE"])?>', <?=CUtil::PhpToJSObject($arResult["JS_FILTER_PARAMS"])?>);
	// скролл до начала раздела
	var bUseFilter = <?=$expanded ? 'true' : 'false'?>;
	$(document).ready(function () {
		if (bUseFilter && $(window).width() < 769) {
			var top = $('#catalog_section_top').offset().top - 160;
			$('html, body').animate({scrollTop: top}, 500);
		}
	});
</script>