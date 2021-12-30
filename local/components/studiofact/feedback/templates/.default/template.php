<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->createFrame()->begin("");  ?>
<? if ($arResult["SUCCESS"]) { ?>
	<div class="sf_feedback_form_success"><?=GetMessage("FEEDBACK_SUCCESS");?></div>
    <button data-fancybox-close="" class="fancybox-close-small">&#215;</button>
<? } else { ?>
	<? if (strlen($arParams["HEAD"]) > 1) {
		?><div class="popup_head"><?=$arParams["HEAD"];?></div><?
	} ?>
<!--    <span data-fancybox-close class="popup-window-close-icon popup-window-titlebar-close-icon"></span>-->
	<form name="sf_feedback_form" action="" method="POST" enctype="multipart/form-data">
		<input type="hidden" name="<?=$arParams["PARENT_ID"];?>_submit" value="Y" />
		<? foreach ($arResult["PROPS"] as $code => $arProp) {
			?><div class="feedback_form_prop_line"<? if ($arProp["VISIBLE"] == "N") { ?> style="display: none;"<? } ?>>
				<label for="feedback_form_prop_<?=$arParams["PARENT_ID"].$code;?>"><?=$arProp["NAME"];?><? if ($arProp["REQUIRED"] == "Y") { ?><span class="req">*</span><? } ?>:</label>
				<? if ($arProp["PROPERTY_TYPE"] == "S" && ($arProp["USER_TYPE"] == "" || $arProp["USER_TYPE"] == "UserID")) {
					?><input type="<?=($arProp["CODE"] == "PERSONAL_PHONE" ? "tel" : "text");?>" class="<? if ($arResult["ERROR"]["FIELD"][$code]) { echo ' input_error'; } ?>" name="<?=$arProp["CODE"];?>" id="feedback_form_prop_<?=$arParams["PARENT_ID"].$code;?>" value="<?=$arProp["VALUE"];?>" placeholder="<?=$arProp["DEFAULT_VALUE"];?>" <? if ($arProp["REQUIRED"] == "Y") { ?>data-required<? } elseif ($arProp["CODE"] == "PERSONAL_PHONE") ?>="phone"/><?
				} else if ($arProp["PROPERTY_TYPE"] == "S" && $arProp["USER_TYPE"] == "HTML") {
					?><textarea class="<? if ($arResult["ERROR"]["FIELD"][$code]) { echo ' input_error'; } ?>" name="<?=$arProp["CODE"];?>" id="feedback_form_prop_<?=$arParams["PARENT_ID"].$code;?>" placeholder="<?=$arProp["DEFAULT_VALUE"];?>"><?=$arProp["VALUE"];?></textarea><?
				} ?>
			</div><?
		} ?>

		<?if ($arParams['USER_CONSENT'] == 'Y'):?>
			<div class="feedback_form_prop_line">
				<?$APPLICATION->IncludeComponent(
					"bitrix:main.userconsent.request",
					".default",
					array(
						"ID" => $arParams['USER_CONSENT_ID'],
						"IS_CHECKED" => $arParams['USER_CONSENT_IS_CHECKED'],
						"AUTO_SAVE" => "Y",
						"IS_LOADED" => $arParams['USER_CONSENT_IS_LOADED'],
						"REPLACE" => array(
							"button_caption" => GetMessage('SF_SMALL_RECALL'),
							"fields" => array(GetMessage('SF_SMALL_NAME'), GetMessage('SF_SMALL_PHONE'), GetMessage('SF_SMALL_COMMENT')),
						),
						"SHORT_LABEL" => "Y"
					),
					false
				);?>
			</div>
		<?endif;?>

		<? if (count($arResult["ERROR"]) > 0) { ?>
			<div class="sf_feedback_form_error">
				<? foreach ($arResult["ERROR"]["FIELD"] as $code => $value) {
					echo "<br />".GetMessage("FEEDBACK_ERROR_".$code);
				}
				foreach ($arResult["ERROR"]["BITRIX"] as $code => $value) {
					echo $value;
				} ?>
			</div>
		<? } ?>
		<div class="feedback_form_prop_line">
			<input type="submit" value="<?=GetMessage("FEEDBACK_SUBMIT");?>" />
		</div>
	</form>
<? } ?>