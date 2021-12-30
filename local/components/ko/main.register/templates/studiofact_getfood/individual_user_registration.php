<?
// pr($arResult);

// меняем порядок следования полей
$arResult['SHOW_FIELDS'] = array(
	'WORK_COMPANY',
	'WORK_NOTES',
	'LOGIN',
	'NAME',
	'LAST_NAME',
	'SECOND_NAME',
	'EMAIL',
	'PERSONAL_PHONE',
	'PASSWORD',
	'CONFIRM_PASSWORD'
);

if(array_key_exists('PERSONAL_PHONE', $arResult["REQUIRED_FIELDS_FLAGS"])){
	unset($arResult["REQUIRED_FIELDS_FLAGS"]["PERSONAL_PHONE"]);
}
// pr($arResult["REQUIRED_FIELDS_FLAGS"]);
?>
<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
<?
if($arResult["BACKURL"] <> ''):
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
endif;
?>

<table>
	<thead>
		<tr>
			<td colspan="2"><b><?=GetMessage("AUTH_REGISTER")?></b></td>
		</tr>
	</thead>
	<tbody>
<?foreach ($arResult["SHOW_FIELDS"] as $FIELD):?>
	<?if($FIELD == "AUTO_TIME_ZONE" && $arResult["TIME_ZONE_ENABLED"] == true):?>
		<tr>
			<td><?echo GetMessage("main_profile_time_zones_auto")?><?if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?><span class="starrequired">*</span><?endif?></td>
			<td>
				<select name="REGISTER[AUTO_TIME_ZONE]" onchange="this.form.elements['REGISTER[TIME_ZONE]'].disabled=(this.value != 'N')">
					<option value=""><?echo GetMessage("main_profile_time_zones_auto_def")?></option>
					<option value="Y"<?=$arResult["VALUES"][$FIELD] == "Y" ? " selected=\"selected\"" : ""?>><?echo GetMessage("main_profile_time_zones_auto_yes")?></option>
					<option value="N"<?=$arResult["VALUES"][$FIELD] == "N" ? " selected=\"selected\"" : ""?>><?echo GetMessage("main_profile_time_zones_auto_no")?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td><?echo GetMessage("main_profile_time_zones_zones")?></td>
			<td>
				<select name="REGISTER[TIME_ZONE]"<?if(!isset($_REQUEST["REGISTER"]["TIME_ZONE"])) echo 'disabled="disabled"'?>>
		<?foreach($arResult["TIME_ZONE_LIST"] as $tz=>$tz_name):?>
					<option value="<?=htmlspecialcharsbx($tz)?>"<?=$arResult["VALUES"]["TIME_ZONE"] == $tz ? " selected=\"selected\"" : ""?>><?=htmlspecialcharsbx($tz_name)?></option>
		<?endforeach?>
				</select>
			</td>
		</tr>
	<?else:?>
		<tr>
			<?if ($FIELD !== "WORK_COMPANY" && $FIELD !== "WORK_NOTES"):?>
				<td><p><?=GetMessage("REGISTER_FIELD_".$FIELD)?>:
					<?if ($arResult["REQUIRED_FIELDS_FLAGS"][$FIELD] == "Y"):?>
					<span class="starrequired">*</span>
					<?endif?></p>
				</td>
			<?endif?>
				<td><?
	switch ($FIELD)
	{
			case "PASSWORD":
				?><input size="30" type="password" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" class="bx-auth-input" />
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
				<?endif?><?
				break;
			case "CONFIRM_PASSWORD":
				?><input size="30" type="password" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" autocomplete="off" /><?
				break;

			case "PERSONAL_GENDER":
				?><select name="REGISTER[<?=$FIELD?>]">
					<option value=""><?=GetMessage("USER_DONT_KNOW")?></option>
					<option value="M"<?=$arResult["VALUES"][$FIELD] == "M" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_MALE")?></option>
					<option value="F"<?=$arResult["VALUES"][$FIELD] == "F" ? " selected=\"selected\"" : ""?>><?=GetMessage("USER_FEMALE")?></option>
				</select><?
				break;

			case "PERSONAL_COUNTRY":
			case "WORK_COUNTRY":
				?><select name="REGISTER[<?=$FIELD?>]"><?
				foreach ($arResult["COUNTRIES"]["reference_id"] as $key => $value)
				{
					?><option value="<?=$value?>"<?if ($value == $arResult["VALUES"][$FIELD]):?> selected="selected"<?endif?>><?=$arResult["COUNTRIES"]["reference"][$key]?></option>
				<?
				}
				?></select><?
				break;

			case "PERSONAL_PHOTO":
			case "WORK_LOGO":
				?><input size="30" type="file" name="REGISTER_FILES_<?=$FIELD?>" /><?
				break;

			case "PERSONAL_NOTES":
			case "WORK_NOTES":
				?><input size="30" type="hidden" name="REGISTER[<?=$FIELD?>]" value="N" /><?
				break;
			case "PERSONAL_PHONE":?>
				<input size="30" type="text" name="REGISTER[<?=$FIELD?>]" data-input="phone" value="<?=$arResult["VALUES"][$FIELD]?>" /><?
				break;
			case "WORK_COMPANY":?>
				<input size="30" type="hidden" name="REGISTER[<?=$FIELD?>]" value="N" /><?
				break;
			default:
				// pr($FIELD);
				if ($FIELD == "PERSONAL_BIRTHDAY"):?>
					<small><?=$arResult["DATE_FORMAT"]?></small><br />
				<?endif;?>
				<input size="30" type="text" name="REGISTER[<?=$FIELD?>]" value="<?=$arResult["VALUES"][$FIELD]?>" /><?
					if ($FIELD == "PERSONAL_BIRTHDAY")
						$APPLICATION->IncludeComponent(
							'bitrix:main.calendar',
							'',
							array(
								'SHOW_INPUT' => 'N',
								'FORM_NAME' => 'regform',
								'INPUT_NAME' => 'REGISTER[PERSONAL_BIRTHDAY]',
								'SHOW_TIME' => 'N'
							),
							null,
							array("HIDE_ICONS"=>"Y")
						);
					?>
	<?}?>
		</td>
		</tr>
	<?endif?>
<?endforeach?>
	
<?// ********************* User properties ***************************************************?>
<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
	<tr><td colspan="2"><?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></td></tr>
	<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
	<tr><td><?=$arUserField["EDIT_FORM_LABEL"]?>:<?if ($arUserField["MANDATORY"]=="Y"):?><span class="starrequired">*</span><?endif;?></td><td>
			<?
			$APPLICATION->IncludeComponent(
				"bitrix:system.field.edit",
				$arUserField["USER_TYPE"]["USER_TYPE_ID"],
				array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "regform"), null, array("HIDE_ICONS"=>"Y"));
				?></td></tr>
	<?endforeach;?>
<?endif;?>
<?// ******************** /User properties ***************************************************?>
<?
/* CAPTCHA */
if ($arResult["USE_CAPTCHA"] == "Y")
{
	?>
		<tr>
			<td colspan="2"><b><?=GetMessage("REGISTER_CAPTCHA_TITLE")?></b></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
			</td>
		</tr>
		<tr>
			<td><?=GetMessage("REGISTER_CAPTCHA_PROMT")?>:<span class="starrequired">*</span></td>
			<td><input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off" /></td>
		</tr>
	<?
}
/* !CAPTCHA */
?>

<tr>
			<td></td>
			<td>

			<label data-bx-user-consent="{&quot;id&quot;:1,&quot;sec&quot;:&quot;5yt132&quot;,&quot;autoSave&quot;:true,&quot;actionUrl&quot;:&quot;\/bitrix\/components\/bitrix\/main.userconsent.request\/ajax.php&quot;,&quot;replace&quot;:{&quot;button_caption&quot;:null,&quot;fields&quot;:[&quot;IP-\u0430\u0434\u0440\u0435\u0441&quot;]}}" class="main-user-consent-request user-agree-checkbox">
			<input type="checkbox" value="Y" checked="" name="">
			<span>Я принимаю условия пользовательского соглашения</span></label> 
			
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
			<input type="hidden" name="USER_TAB" value="individual">
			<input type="hidden" name="UF_USER_TYPE" value="<?=USER_TYPE_USER?>">
			</td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<td></td>
			<td><input type="submit" name="register_submit_button" value="<?=GetMessage("AUTH_REGISTER")?>" /></td>
		</tr>
	</tfoot>

</table>
</form>