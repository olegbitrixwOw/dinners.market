<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
?>

<div class="news-detail"><br />
	<?if($arParams["DISPLAY_NAME"]!="N" && $arResult["NAME"]):?>
		<h3>Сотрудники организации <?=$arResult["NAME"]?></h3>
	<?endif;?>

	<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):?>
		<p><?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p>
	<?endif;?>

	<?if($arResult["NAV_RESULT"]):?>
		<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
		<?echo $arResult["NAV_TEXT"];?>
		<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
	<?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
		<?echo $arResult["DETAIL_TEXT"];?>
	<?else:?>
		<?echo $arResult["PREVIEW_TEXT"];?>
	<?endif?>
	<div style="clear:both"></div>
	<br />
	<h2><a href="/firm/workers/register/?firm_id=<?=$arResult['ID']?>" class="register">Зарегистрировать нового сотрудника</a></h2>
	<?$users = getUsersByFirm($arResult["ID"]);?>
	<?if(count($users)>0):?>
		<?$APPLICATION->IncludeComponent(
			"dev2fun:user.list", 
			"studiofact_getfood", 
			array(
				"COMPONENT_TEMPLATE" => "studiofact_getfood",
				"COUNT" => count($users),
				"SORT_BY1" => "NAME",
				"SORT_ORDER1" => "ASC",
				"SORT_BY2" => "ACTIVE_FROM",
				"SORT_ORDER2" => "ASC",
				"SORT_BY3" => "id",
				"SORT_ORDER3" => "DESC",
				"FILTER_NAME" => "",
				"FIELD_CODE" => array(
					0 => "ID",
					1 => "LOGIN",
					2 => "NAME",
					3 => "EMAIL",
					4 => "",
				),
				"PROPERTY_CODE" => array(
				),
				"DETAIL_URL" => "/firm/workers/worker-profile/",
				"AJAX_MODE" => "N",
				"AJAX_OPTION_JUMP" => "N",
				"AJAX_OPTION_STYLE" => "Y",
				"AJAX_OPTION_HISTORY" => "N",
				"AJAX_OPTION_ADDITIONAL" => "",
				"CACHE_TYPE" => "N",
				"CACHE_TIME" => "36000000",
				"CACHE_FILTER" => "N",
				"CACHE_GROUPS" => "Y",
				"PREVIEW_TRUNCATE_LEN" => "",
				"RESIZE_PERSONAL_PHOTO" => "500*600",
				"RESIZE_WORK_LOGO" => "500*600",
				"RESIZE_TYPE" => "BX_RESIZE_IMAGE_PROPORTIONAL",
				"ACTIVE_DATE_FORMAT" => "d.m.Y",
				"SET_STATUS_404" => "N",
				"PAGER_TEMPLATE" => ".default",
				"DISPLAY_TOP_PAGER" => "N",
				"DISPLAY_BOTTOM_PAGER" => "Y",
				"PAGER_TITLE" => "Пользователи",
				"PAGER_SHOW_ALWAYS" => "N",
				"PAGER_DESC_NUMBERING" => "N",
				"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
				"PAGER_SHOW_ALL" => "N",
				"DISPLAY_DATE" => "Y",
				"DISPLAY_NAME" => "Y",
				"DISPLAY_PICTURE" => "Y",
				"DISPLAY_PREVIEW_TEXT" => "Y",
				"FIRM" => $arResult['ID']
			),
			false
		);?>
	<?else:?>
		<p>нет зарегистрированных сотрудников</p>
	<?endif;?>
	
	<?if(array_key_exists("USE_SHARE", $arParams) && $arParams["USE_SHARE"] == "Y")
	{
		?>
		<div class="news-detail-share">
			<noindex>
			<?
			$APPLICATION->IncludeComponent("bitrix:main.share", "", array(
					"HANDLERS" => $arParams["SHARE_HANDLERS"],
					"PAGE_URL" => $arResult["~DETAIL_PAGE_URL"],
					"PAGE_TITLE" => $arResult["~NAME"],
					"SHORTEN_URL_LOGIN" => $arParams["SHARE_SHORTEN_URL_LOGIN"],
					"SHORTEN_URL_KEY" => $arParams["SHARE_SHORTEN_URL_KEY"],
					"HIDE" => $arParams["SHARE_HIDE"],
				),
				$component,
				array("HIDE_ICONS" => "Y")
			);
			?>
			</noindex>
		</div>
		<?
	}
	?>
</div>