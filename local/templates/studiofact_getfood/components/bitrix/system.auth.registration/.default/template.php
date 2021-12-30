<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="bx-auth box margin padding">
	<?
	if (strripos ($arParams["~AUTH_RESULT"]["MESSAGE"] , "Неверный логин или пароль.")===false)
		ShowMessage($arParams["~AUTH_RESULT"]);
	?>
	<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y" && is_array($arParams["AUTH_RESULT"]) &&  $arParams["AUTH_RESULT"]["TYPE"] === "OK"):?>
		<p><?echo GetMessage("AUTH_EMAIL_SENT")?></p>
	<?else:?>
	<?if($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
		<p><?echo GetMessage("AUTH_EMAIL_WILL_BE_SENT")?></p>
	<?endif?>
<noindex>
<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" data-valid-form>
	<? if (strlen($arResult["BACKURL"]) > 0) { ?>
		<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
	<? } ?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="REGISTRATION" />

<table class="data-table bx-auth-table bx-registration-table">
	<thead>
		<tr>
			<td colspan="2"><b><?=GetMessage("AUTH_REGISTER")?></b><br /><br /></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td class="bx-auth-label"><?=GetMessage("AUTH_NAME")?></td>
			<td><input type="text" name="USER_NAME" maxlength="50" value="<?=$arResult["USER_NAME"]?>" class="bx-auth-input"/></td>
		</tr>
		<tr>
			<td class="bx-auth-label"><?=GetMessage("AUTH_LAST_NAME")?></td>
			<td><input type="text" name="USER_LAST_NAME" maxlength="50" value="<?=$arResult["USER_LAST_NAME"]?>" class="bx-auth-input"/></td>
		</tr>
		<tr>
			<td class="bx-auth-label"><?=GetMessage("AUTH_LOGIN_MIN")?><span class="starrequired">*</span></td>
			<td><input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>" class="bx-auth-input" data-required="login"/></td>
		</tr>
		<tr>
			<td class="bx-auth-label"><?=GetMessage("AUTH_PASSWORD_REQ")?><span class="starrequired">*</span></td>
			<td><input type="password" name="USER_PASSWORD" maxlength="50" value="<?=$arResult["USER_PASSWORD"]?>" class="bx-auth-input" data-required="password"/>
<?if($arResult["SECURE_AUTH"]):?>
				<span class="bx-auth-secure" id="bx_auth_secure" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
					<div class="bx-auth-secure-icon"></div>
				</span>
				<noscript>
				<span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
					<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
				</span>
				</noscript>
<script type="text/javascript">
document.getElementById('bx_auth_secure').style.display = 'inline-block';
</script>
<?endif?>
			</td>
		</tr>
		<tr>
			<td class="bx-auth-label"><?=GetMessage("AUTH_CONFIRM")?><span class="starrequired">*</span></td>
			<td><input type="password" name="USER_CONFIRM_PASSWORD" maxlength="50" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" class="bx-auth-input" data-required="confirm"/></td>
		</tr>
        <tr>
            <td class="bx-auth-label"><?=GetMessage("AUTH_PHONE")?></td>
            <td><input type="text" name="USER_EMAIL" maxlength="255" value="<?=$arResult["PERSONAL_PHONE"]?>" class="bx-auth-input" data-input="phone"/></td>
        </tr>
        <tr>
            <td class="bx-auth-label"><?=GetMessage("AUTH_EMAIL")?><?if($arResult["EMAIL_REQUIRED"]):?><span class="starrequired">*</span><?endif?></td>
            <td><input type="text" name="USER_EMAIL" maxlength="255" value="<?=$arResult["USER_EMAIL"]?>" class="bx-auth-input" data-required="email"/></td>
        </tr>
<?// ********************* User properties ***************************************************?>
<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
	<tr><td colspan="2"><?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></td></tr>
	<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
	<tr><td class="bx-auth-label"><?=$arUserField["EDIT_FORM_LABEL"]?>:<?if ($arUserField["MANDATORY"]=="Y"):?><span class="starrequired">*</span><?endif;?></td><td>
			<?$APPLICATION->IncludeComponent(
				"bitrix:system.field.edit",
				$arUserField["USER_TYPE"]["USER_TYPE_ID"],
				array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "bform"), null, array("HIDE_ICONS"=>"Y"));?></td></tr>
	<?endforeach;?>
<?endif;?>
<?// ******************** /User properties ***************************************************

	/* CAPTCHA */
	if ($arResult["USE_CAPTCHA"] == "Y")
	{
		?>
		<tr>
			<td colspan="2"><b><?=GetMessage("CAPTCHA_REGF_TITLE")?></b><br /><br /></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="hidden" name="captcha_sid" id="sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" id="code" width="180" height="40" alt="CAPTCHA" />
                <a href=""><img class="refresh-image" src="<?=SITE_TEMPLATE_PATH?>/images/refresh.png" alt="update captcha"></a><br /><br />
			</td>
		</tr>
		<tr>
			<td class="bx-auth-label"><?=GetMessage("CAPTCHA_REGF_PROMT")?>:<span class="starrequired">*</span></td>
            <td><input type="text" name="captcha_word" maxlength="50" value="" data-required/></td>
		</tr>
		<?
	}
	/* CAPTCHA */
	?>
		<tr>
			<td class="bx-auth-label">&nbsp;</td>
			<td class="agreement">
                <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/agreement/agreement_registration.php"), false);?>
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="2" align="center"><input type="submit" name="Register" value="<?=GetMessage("AUTH_REGISTER")?>" /></td>
		</tr>
	</tfoot>
</table>
	<br />
	<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
	<p><?=GetMessage("AUTH_REQ")?><span class="starrequired">*</span></p>
	<p><a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow"><b><?=GetMessage("AUTH_AUTH")?></b></a></p>
</form>
</noindex>
        <script type="text/javascript">
            <? if($arParams["~AUTH_RESULT"] !== false) { ?>
                <?if (strripos($arParams["~AUTH_RESULT"]["MESSAGE"], 'Слово для защиты') !== false):?>
                    try{document.bform.captcha_word.focus();}catch(e){}
                <?endif?>
                <?if (strripos($arParams["~AUTH_RESULT"]["MESSAGE"], 'Неверный E-Mail') !== false):?>
                    try{document.bform.USER_EMAIL.focus();}catch(e){}
                <?endif?>
                <?if (strripos($arParams["~AUTH_RESULT"]["MESSAGE"], 'Неверное подтверждение пароля') !== false):?>
                    try{document.bform.USER_CONFIRM_PASSWORD.focus();}catch(e){}
                <?endif?>
                <?if (strripos($arParams["~AUTH_RESULT"]["MESSAGE"], 'Пароль ') !== false):?>
                    try{document.bform.USER_PASSWORD.focus();}catch(e){}
                <?endif?>
                <?if (strripos($arParams["~AUTH_RESULT"]["MESSAGE"], 'Логин ') !== false):?>
                    try{document.bform.USER_LOGIN.focus();}catch(e){}
                <?endif?>
            <? } ?>
        </script>
<?endif?>
</div>

