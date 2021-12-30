<?
/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

if($arResult["SHOW_SMS_FIELD"] == true)
{
	CJSCore::Init('phone_auth');
}
?>

<div class="bx-auth-profile">

<?ShowError($arResult["strProfileError"]);?>
<?
if ($arResult['DATA_SAVED'] == 'Y')
	ShowNote(GetMessage('PROFILE_DATA_SAVED'));
?>

<?if($arResult["SHOW_SMS_FIELD"] == true):?>

<form method="post" action="<?=$arResult["FORM_TARGET"]?>">
<?=$arResult["BX_SESSION_CHECK"]?>
<input type="hidden" name="lang" value="<?=LANG?>" />
<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />
<input type="hidden" name="SIGNED_DATA" value="<?=htmlspecialcharsbx($arResult["SIGNED_DATA"])?>" />
<table class="profile-table data-table">
	<tbody>
		<tr>
			<td><?echo GetMessage("main_profile_code")?><span class="starrequired">*</span></td>
			<td><input size="30" type="text" name="SMS_CODE" value="<?=htmlspecialcharsbx($arResult["SMS_CODE"])?>" autocomplete="off" /></td>
		</tr>
	</tbody>
</table>

<p><input type="submit" name="code_submit_button" value="<?echo GetMessage("main_profile_send")?>" /></p>

</form>

<script>
	new BX.PhoneAuth({
		containerId: 'bx_profile_resend',
		errorContainerId: 'bx_profile_error',
		interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
		data:
			<?=CUtil::PhpToJSObject([
				'signedData' => $arResult["SIGNED_DATA"],
			])?>,
		onError:
			function(response)
			{
				var errorDiv = BX('bx_profile_error');
				var errorNode = BX.findChildByClassName(errorDiv, 'errortext');
				errorNode.innerHTML = '';
				for(var i = 0; i < response.errors.length; i++)
				{
					errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br>';
				}
				errorDiv.style.display = '';
			}
	});
</script>

<div id="bx_profile_error" style="display:none"><?ShowError("error")?></div>

<div id="bx_profile_resend"></div>

<?else:?>

<script type="text/javascript">
<!--
var opened_sections = [<?
$arResult["opened"] = $_COOKIE[$arResult["COOKIE_PREFIX"]."_user_profile_open"];
$arResult["opened"] = preg_replace("/[^a-z0-9_,]/i", "", $arResult["opened"]);
if (strlen($arResult["opened"]) > 0)
{
	echo "'".implode("', '", explode(",", $arResult["opened"]))."'";
}
else
{
	$arResult["opened"] = "reg";
	echo "'reg'";
}
?>];
//-->

var cookie_prefix = '<?=$arResult["COOKIE_PREFIX"]?>';
</script>
<form method="post" name="form1" action="<?=$arResult["FORM_TARGET"].'?USER_ID='.$arParams["USER_ID"]?>" enctype="multipart/form-data">
<?=$arResult["BX_SESSION_CHECK"]?>
<input type="hidden" name="lang" value="<?=LANG?>" />
<input type="hidden" name="ID" value=<?=$arResult["ID"]?> />

<div class="profile-link profile-user-div-link"><a title="<?=GetMessage("REG_SHOW_HIDE")?>" href="javascript:void(0)" onclick="SectionClick('reg')"><?=GetMessage("REG_SHOW_HIDE")?></a></div>
<div class="profile-block-<?=strpos($arResult["opened"], "reg") === false ? "hidden" : "shown"?>" id="user_div_reg">
<table class="profile-table data-table">
	<thead>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
	</thead>
	<tbody>
	<?
	if($arResult["ID"]>0)
	{
	?>
		<?
		if (strlen($arResult["arUser"]["TIMESTAMP_X"])>0)
		{
		?>
		<tr>
			<td><?=GetMessage('LAST_UPDATE')?></td>
			<td><p><?=$arResult["arUser"]["TIMESTAMP_X"]?></p></td>
		</tr>
		<?
		}
		?>
		<?
		if (strlen($arResult["arUser"]["LAST_LOGIN"])>0)
		{
		?>
		<tr>
			<td><?=GetMessage('LAST_LOGIN')?></td>
			<td><p><?=$arResult["arUser"]["LAST_LOGIN"]?></p></td>
		</tr>
		<?
		}
		?>
	<?
	}
	?>
	<tr>
		<td>ИНН организации</td>
		<td><input type="text" name="WORK_NOTES" maxlength="255" value="<?=$arResult["arUser"]["WORK_NOTES"]?>" /></td>
	</tr>

	<tr>
		<td>Название организации</td>
		<td><input type="text" name="WORK_COMPANY" maxlength="255" value="<?=$arResult["arUser"]["WORK_COMPANY"]?>" /></td>
	</tr>

	<tr>
		<td><?=GetMessage('NAME')?></td>
		<td><input type="text" name="NAME" maxlength="50" value="<?=$arResult["arUser"]["NAME"]?>" /></td>
	</tr>

	<tr>
		<td><?=GetMessage('LOGIN')?><span class="starrequired">*</span></td>
		<td><input type="text" name="LOGIN" maxlength="50" value="<? echo $arResult["arUser"]["LOGIN"]?>" /></td>
	</tr>
	<tr>
		<td><?=GetMessage('EMAIL')?><?if($arResult["EMAIL_REQUIRED"]):?><span class="starrequired">*</span><?endif?></td>
		<td><input type="text" name="EMAIL" maxlength="50" value="<? echo $arResult["arUser"]["EMAIL"]?>" /></td>
	</tr>
		
	<tr>
		<td>Телефон</td>
		<td><input type="text" name="PERSONAL_PHONE" maxlength="255" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>" /></td>
	</tr>

<?if($arResult["TIME_ZONE_ENABLED"] == true):?>
	<tr>
		<td colspan="2" class="profile-header"><?echo GetMessage("main_profile_time_zones")?></td>
	</tr>
	<tr>
		<td><?echo GetMessage("main_profile_time_zones_auto")?></td>
		<td>
			<select name="AUTO_TIME_ZONE" onchange="this.form.TIME_ZONE.disabled=(this.value != 'N')">
				<option value=""><?echo GetMessage("main_profile_time_zones_auto_def")?></option>
				<option value="Y"<?=($arResult["arUser"]["AUTO_TIME_ZONE"] == "Y"? ' SELECTED="SELECTED"' : '')?>><?echo GetMessage("main_profile_time_zones_auto_yes")?></option>
				<option value="N"<?=($arResult["arUser"]["AUTO_TIME_ZONE"] == "N"? ' SELECTED="SELECTED"' : '')?>><?echo GetMessage("main_profile_time_zones_auto_no")?></option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?echo GetMessage("main_profile_time_zones_zones")?></td>
		<td>
			<select name="TIME_ZONE"<?if($arResult["arUser"]["AUTO_TIME_ZONE"] <> "N") echo ' disabled="disabled"'?>>
<?foreach($arResult["TIME_ZONE_LIST"] as $tz=>$tz_name):?>
				<option value="<?=htmlspecialcharsbx($tz)?>"<?=($arResult["arUser"]["TIME_ZONE"] == $tz? ' SELECTED="SELECTED"' : '')?>><?=htmlspecialcharsbx($tz_name)?></option>
<?endforeach?>
			</select>
		</td>
	</tr>
<?endif?>

<tr>
		<td><?=GetMessage('ACTIVE')?><span class="starrequired">*</span></td>
		<td>
		<select name="ACTIVE" onchange="this.form.TIME_ZONE.disabled=(this.value != 'N')">
				<option value="Y"<?=($arResult["arUser"]["ACTIVE"] == "Y"? ' SELECTED="SELECTED"' : '')?>><?echo GetMessage("MAKE_USER_ACTIVE")?></option>
				<option value="N"<?=($arResult["arUser"]["ACTIVE"] == "N"? ' SELECTED="SELECTED"' : '')?>><?echo GetMessage("MAKE_USER_INACTIVE")?></option>
			</select>
		</td>
	</tr>

		<?if($arResult["UF_BLOCKED"]):?>
						<tr>
							<td class="field-name">Заблокирован <span>(выбирая "Да" вы удалеяете пользователя из вашей компании)</span></td>
							<td class="field-value">
									<?$APPLICATION->IncludeComponent(
									"bitrix:system.field.edit",
									$arUserField["USER_TYPE"]["USER_TYPE_ID"],
									array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arResult["UF_BLOCKED"]), null, array("HIDE_ICONS"=>"Y"));?>
							</td>
						</tr>
		<?endif?>
			
	</tbody>
</table>
</div>



	<?// ********************* User properties ***************************************************?>
	<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
		<div class="profile-link profile-user-div-link">
			<a title="<?=GetMessage("USER_SHOW_HIDE")?>" href="javascript:void(0)" onclick="SectionClick('user_properties')"><?=strlen(trim($arParams["USER_PROPERTY_NAME"])) > 0 ? $arParams["USER_PROPERTY_NAME"] : GetMessage("USER_TYPE_EDIT_TAB")?></a>
		</div>
		<div id="user_div_user_properties" class="profile-block-<?=strpos($arResult["opened"], "user_properties") === false ? "hidden" : "shown"?>">
			<table class="data-table profile-table">
				<thead> 
					<tr>
						<td colspan="2">&nbsp;</td>
					</tr>
				</thead>
				<tbody>
				<?$first = true;?>
				<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
					<?if($FIELD_NAME !== 'UF_LIMIT_MO' && $FIELD_NAME !== 'UF_LIMIT_TU' && $FIELD_NAME !== 'UF_LIMIT_WE' && $FIELD_NAME !== 'UF_LIMIT_TH' && $FIELD_NAME !== 'UF_LIMIT_FR' && $FIELD_NAME !== 'UF_LIMIT_SU' && $FIELD_NAME !== 'UF_LIMIT_SA' &&  $FIELD_NAME !==  'UF_BLOCKED' && $FIELD_NAME !== 'UF_DELIVERY'):?>
					<tr><td class="field-name">
						<?if ($arUserField["MANDATORY"]=="Y"):?>
							<span class="starrequired">*</span>
						<?endif;?>
						<?=$arUserField["EDIT_FORM_LABEL"]?>:</td>
						<td class="field-value">
<?
// echo $arUserField["USER_TYPE"]["USER_TYPE_ID"];
?>

							<?
							$APPLICATION->IncludeComponent(
								"alex:system.field.edit",
								$arUserField["USER_TYPE"]["USER_TYPE_ID"],
								array(
									"bVarsFromForm" => $arResult["bVarsFromForm"], 
									"arUserField" => $arUserField), 
									null, 
									array("HIDE_ICONS"=>"Y")
								);
							?>

						</td></tr>
					<?endif;?>
				<?endforeach;?>
				</tbody>
			</table>
		</div>
	<?endif;?>
	<?// ******************** /User properties ***************************************************?>
	<p><?echo $arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
	<p><input type="submit" name="save" value="<?=(($arResult["ID"]>0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD"))?>">&nbsp;&nbsp;<input type="reset" value="<?=GetMessage('MAIN_RESET');?>"></p>
</form>
<?
// if($arResult["SOCSERV_ENABLED"])
// {
// 	$APPLICATION->IncludeComponent("bitrix:socserv.auth.split", "studiofact_getfood", Array(
// 	"SHOW_PROFILES" => "Y",	// Показывать объединенные профили
// 		"ALLOW_DELETE" => "Y",	// Разрешить удалять объединенные профили
// 	),
// 	false
// );
// }
?>



<?endif?>



</div>