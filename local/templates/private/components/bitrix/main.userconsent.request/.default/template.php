<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/** @var array $arParams */
/** @var array $arResult */

$config = \Bitrix\Main\Web\Json::encode($arResult['CONFIG']);
?>

<label data-bx-user-consent="<?=htmlspecialcharsbx($config)?>" class="main-user-consent-request user-agree-checkbox">
	<input type="checkbox" value="Y" <?=($arParams['IS_CHECKED'] ? 'checked' : '')?> name="<?=htmlspecialcharsbx($arParams['INPUT_NAME'])?>">
	<span>
		<?if (isset($arParams['SHORT_LABEL']) && $arParams['SHORT_LABEL'] == 'Y'):?>
			<?=GetMessage('MAIN_USER_CONSENT_REQUEST_LABEL')?>
		<?else:?>
			<?=htmlspecialcharsbx($arResult['INPUT_LABEL'])?>
		<?endif;?>
	</span>
</label>

<script type="text/html" data-bx-template="main-user-consent-request-loader">
	<div class="main-user-consent-request-popup">
		<div class="main-user-consent-request-popup-cont">
			<div data-bx-head="" class="main-user-consent-request-popup-header"></div>
			<div class="main-user-consent-request-popup-body">
				<div data-bx-loader="" class="main-user-consent-request-loader">
					<svg class="main-user-consent-request-circular" viewBox="25 25 50 50">
						<circle class="main-user-consent-request-path" cx="50" cy="50" r="20" fill="none" stroke-width="1" stroke-miterlimit="10"></circle>
					</svg>
				</div>
				<div data-bx-content="" class="main-user-consent-request-popup-content">
					<div class="main-user-consent-request-popup-textarea-block">
						<textarea data-bx-textarea="" class="main-user-consent-request-popup-text"></textarea>
					</div>
					<div class="main-user-consent-request-popup-buttons">
						<span data-bx-btn-accept="" class="main-user-consent-request-popup-button main-user-consent-request-popup-button-acc">Y</span>
						<span data-bx-btn-reject="" class="main-user-consent-request-popup-button main-user-consent-request-popup-button-rej">N</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>