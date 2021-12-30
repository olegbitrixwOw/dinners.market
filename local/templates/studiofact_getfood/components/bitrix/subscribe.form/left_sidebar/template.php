<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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
?>
<div class="subscribe-form" id="subscribe-form">
	<?=GetMessage('subscr_form_text')?>
<?
$frame = $this->createFrame("subscribe-form", false)->begin();
?>
	<form action="<?=$arResult["FORM_ACTION"]?>">
		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<input
				type="hidden"
				name="sf_RUB_ID[]"
				id="sf_RUB_ID_<?=$itemValue["ID"]?>"
				value="<?=$itemValue["ID"]?>"
			/>
		<?endforeach;?>
		<input
			type="text"
			name="sf_EMAIL"
			size="20"
			value="<?=$arResult["EMAIL"]?>"
			title="<?=GetMessage("subscr_form_email_title")?>"
			placeholder="<?=GetMessage('subscr_form_email_text')?>"
		/>

		<?if ($arParams['USER_CONSENT'] == 'Y'):?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:main.userconsent.request",
				".default",
				array(
					"ID" => $arParams['USER_CONSENT_ID'],
					"IS_CHECKED" => $arParams['USER_CONSENT_IS_CHECKED'],
					"AUTO_SAVE" => "Y",
					"IS_LOADED" => $arParams['USER_CONSENT_IS_LOADED'],
					"REPLACE" => array(
						"button_caption" => GetMessage("subscr_form_go"),
						"fields" => array(
							0 => "Email",
						),
					),
					"SHORT_LABEL" => "Y"
				),
				false
			);
			?>
		<?endif;?>

		<input type="submit" name="OK" value="<?=GetMessage("subscr_form_button")?>" />
	</form>
<?
$frame->beginStub();
?>
	<form action="<?=$arResult["FORM_ACTION"]?>">
		<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
			<input
				type="hidden"
				name="sf_RUB_ID[]"
				id="sf_RUB_ID_<?=$itemValue["ID"]?>"
				value="<?=$itemValue["ID"]?>"
			/>
		<?endforeach;?>
		<input
			type="text"
			name="sf_EMAIL"
			size="20"
			value=""
			title="<?=GetMessage("subscr_form_email_title")?>"
			placeholder="<?=GetMessage('subscr_form_email_text')?>"
		/>
		<input type="submit" name="OK" value="<?=GetMessage("subscr_form_button")?>" />
	</form>
<?
$frame->end();
?>
</div>
