<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

if(!CModule::IncludeModule("main"))
	return;
if(!CModule::IncludeModule("iblock"))
	return;

$arSorts = array("ASC"=>GetMessage("T_IBLOCK_DESC_ASC"), "DESC"=>GetMessage("T_IBLOCK_DESC_DESC"));
$arSortFields = array(
        "ID"=>GetMessage("T_IBLOCK_DESC_FID"),
        "NAME"=>GetMessage("T_IBLOCK_DESC_FNAME"),
        "ACTIVE_FROM"=>GetMessage("T_IBLOCK_DESC_FACT"),
        "SORT"=>GetMessage("T_IBLOCK_DESC_FSORT"),
        "TIMESTAMP_X"=>GetMessage("T_IBLOCK_DESC_FTSAMP")
);

$arProperty_LNS = array();

$arComponentParameters = array(
	"GROUPS" => array(
	),
	"PARAMETERS" => array(
		"AJAX_MODE" => array(),
		"COUNT" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("T_IBLOCK_DESC_LIST_CONT"),
			"TYPE" => "STRING",
			"DEFAULT" => "20",
		),
		"SORT_BY1" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBORD1"),
			"TYPE" => "LIST",
			"DEFAULT" => "shows",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SORT_ORDER1" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBBY1"),
			"TYPE" => "LIST",
			"DEFAULT" => "ASC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SORT_BY2" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBORD2"),
			"TYPE" => "LIST",
			"DEFAULT" => "SORT",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SORT_ORDER2" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBBY2"),
			"TYPE" => "LIST",
			"DEFAULT" => "ASC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SORT_BY3" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBORD2"),
			"TYPE" => "LIST",
			"DEFAULT" => "id",
			"VALUES" => $arSortFields,
			"ADDITIONAL_VALUES" => "Y",
		),
		"SORT_ORDER3" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_DESC_IBBY2"),
			"TYPE" => "LIST",
			"DEFAULT" => "DESC",
			"VALUES" => $arSorts,
			"ADDITIONAL_VALUES" => "Y",
		),
		"FILTER_NAME" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_FILTER"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
                "FIELD_CODE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("IBLOCK_FIELD"),
			"TYPE" => "LIST",
//			"DEFAULT" => "DESC",
			"VALUES" => array(
                            'ID' => 'ID',
                            'XML_ID' => 'XML_ID',
                            'ACTIVE' => 'ACTIVE',
                            'LOGIN' => 'LOGIN',
                            'NAME' => 'NAME',
                            'LAST_NAME' => 'LAST_NAME',
                            'SECOND_NAME' => 'SECOND_NAME',
                            'EMAIL' => 'EMAIL',
                            'PERSONAL_PHOTO' => 'PERSONAL_PHOTO',
                            'TIMESTAMP_X' => 'TIMESTAMP_X',
                            'PERSONAL_BIRTHDAY' => 'PERSONAL_BIRTHDAY',
                            'DATE_REGISTER' => 'DATE_REGISTER',
                            'LAST_LOGIN' => 'LAST_LOGIN',
                        ),
                        "MULTIPLE" => "Y",
			"ADDITIONAL_VALUES" => "Y",
		),
//		"FIELD_CODE" => CIBlockParameters::GetFieldCode(GetMessage("IBLOCK_FIELD"), "DATA_SOURCE"),
		"PROPERTY_CODE" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_PROPERTY"),
			"TYPE" => "STRING",
			"MULTIPLE" => "Y",
			"VALUES" => "",
			"ADDITIONAL_VALUES" => "Y",
		),
        "DETAIL_URL" => array(
			"PARENT" => "DATA_SOURCE",
			"NAME" => GetMessage("T_IBLOCK_DESC_DETAIL_PAGE_URL"),
			"TYPE" => "STRING",
			"DEFAULT" => "/?USER_ID=#ID#",
		),
		"PREVIEW_TRUNCATE_LEN" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("T_IBLOCK_DESC_PREVIEW_TRUNCATE_LEN"),
			"TYPE" => "STRING",
			"DEFAULT" => "",
		),
		"RESIZE_PERSONAL_PHOTO" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("T_DESC_RESIZE_PERSONAL_PHOTO"),
			"TYPE" => "STRING",
			"DEFAULT" => "500*600",
		),
		"RESIZE_WORK_LOGO" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("T_DESC_RESIZE_WORK_LOGO"),
			"TYPE" => "STRING",
			"DEFAULT" => "500*600",
		),
		"RESIZE_TYPE" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("T_DESC_RESIZE_TYPE"),
			"TYPE" => "LIST",
			"DEFAULT" => "BX_RESIZE_IMAGE_PROPORTIONAL",
			"VALUES" => array(
                            'BX_RESIZE_IMAGE_EXACT' => GetMessage("T_DESC_BX_RESIZE_IMAGE_EXACT"),
                            'BX_RESIZE_IMAGE_PROPORTIONAL' => GetMessage("T_DESC_BX_RESIZE_IMAGE_PROPORTIONAL"),
                            'BX_RESIZE_IMAGE_PROPORTIONAL_ALT' => GetMessage("T_DESC_BX_RESIZE_IMAGE_PROPORTIONAL_ALT"),
                        ),
		),
		"ACTIVE_DATE_FORMAT" => CIBlockParameters::GetDateFormat(GetMessage("T_IBLOCK_DESC_ACTIVE_DATE_FORMAT"), "ADDITIONAL_SETTINGS"),
//		"SET_TITLE" => array(),
//		"SET_BROWSER_TITLE" => array(
//			"PARENT" => "ADDITIONAL_SETTINGS",
//			"NAME" => GetMessage("CP_BNL_SET_BROWSER_TITLE"),
//			"TYPE" => "CHECKBOX",
//			"DEFAULT" => "Y",
//		),
//		"SET_META_KEYWORDS" => array(
//			"PARENT" => "ADDITIONAL_SETTINGS",
//			"NAME" => GetMessage("CP_BNL_SET_META_KEYWORDS"),
//			"TYPE" => "CHECKBOX",
//			"DEFAULT" => "Y",
//		),
//		"SET_META_DESCRIPTION" => array(
//			"PARENT" => "ADDITIONAL_SETTINGS",
//			"NAME" => GetMessage("CP_BNL_SET_META_DESCRIPTION"),
//			"TYPE" => "CHECKBOX",
//			"DEFAULT" => "Y",
//		),
		"SET_STATUS_404" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("CP_BNL_SET_STATUS_404"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"CACHE_TIME"  =>  array("DEFAULT"=>36000000),
		"CACHE_FILTER" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("IBLOCK_CACHE_FILTER"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "N",
		),
		"CACHE_GROUPS" => array(
			"PARENT" => "CACHE_SETTINGS",
			"NAME" => GetMessage("CP_BNL_CACHE_GROUPS"),
			"TYPE" => "CHECKBOX",
			"DEFAULT" => "Y",
		),
		"FIRM" => array(
			"PARENT" => "ADDITIONAL_SETTINGS",
			"NAME" => GetMessage("FIRM"),
			"TYPE" => "STRING",
			"DEFAULT" => "N",
		)
	),
);
CIBlockParameters::AddPagerSettings($arComponentParameters, GetMessage("T_IBLOCK_DESC_PAGER_NEWS"), true, true);
?>