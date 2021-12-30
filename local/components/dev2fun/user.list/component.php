<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var CBitrixComponent $this */
/** @var array $arParams */
/** @var array $arResult */
/** @var string $componentPath */
/** @var string $componentName */
/** @var string $componentTemplate */
/** @global CDatabase $DB */
/** @global CUser $USER */
/** @global CMain $APPLICATION */

/** @global CIntranetToolbar $INTRANET_TOOLBAR */
global $INTRANET_TOOLBAR;

CPageOption::SetOptionString("main", "nav_page_in_session", "N");

if(!isset($arParams["CACHE_TIME"]))
	$arParams["CACHE_TIME"] = 36000000;

$arParams["SORT_BY1"] = trim($arParams["SORT_BY1"]);
if(strlen($arParams["SORT_BY1"])<=0)
	$arParams["SORT_BY1"] = "ACTIVE_FROM";
if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER1"]))
	$arParams["SORT_ORDER1"]="DESC";

if(strlen($arParams["SORT_BY2"])<=0)
	$arParams["SORT_BY2"] = "SORT";
if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER2"]))
	$arParams["SORT_ORDER2"]="ASC";

if(strlen($arParams["SORT_BY3"])<=0)
	$arParams["SORT_BY3"] = "SORT";
if(!preg_match('/^(asc|desc|nulls)(,asc|,desc|,nulls){0,1}$/i', $arParams["SORT_ORDER3"]))
	$arParams["SORT_ORDER3"]="ASC";

if(strlen($arParams["FILTER_NAME"])<=0 || !preg_match("/^[A-Za-z_][A-Za-z01-9_]*$/", $arParams["FILTER_NAME"]))
{
	$arrFilter = array();
}
else
{
	$arrFilter = $GLOBALS[$arParams["FILTER_NAME"]];
	if(!is_array($arrFilter))
		$arrFilter = array();
}

if(!is_array($arParams["FIELD_CODE"]))
	$arParams["FIELD_CODE"] = array();
foreach($arParams["FIELD_CODE"] as $key=>$val)
	if(!$val)
            unset($arParams["FIELD_CODE"][$key]);

if(!is_array($arParams["PROPERTY_CODE"]))
	$arParams["PROPERTY_CODE"] = array();
foreach($arParams["PROPERTY_CODE"] as $key=>$val)
	if($val==="")
            unset($arParams["PROPERTY_CODE"][$key]);
if(!$arParams["PROPERTY_CODE"]){
    $arParams["PROPERTY_CODE"] = array();
}

$arParams["DETAIL_URL"]=trim($arParams["DETAIL_URL"]);

$arParams["COUNT"] = intval($arParams["COUNT"]);
if($arParams["COUNT"]<=0)
	$arParams["COUNT"] = 20;

$arParams["CACHE_FILTER"] = $arParams["CACHE_FILTER"]=="Y";
if(!$arParams["CACHE_FILTER"] && count($arrFilter)>0)
	$arParams["CACHE_TIME"] = 0;

$arParams["ACTIVE_DATE_FORMAT"] = trim($arParams["ACTIVE_DATE_FORMAT"]);
if(strlen($arParams["ACTIVE_DATE_FORMAT"])<=0)
	$arParams["ACTIVE_DATE_FORMAT"] = $DB->DateFormatToPHP(CSite::GetDateFormat("SHORT"));
$arParams["PREVIEW_TRUNCATE_LEN"] = intval($arParams["PREVIEW_TRUNCATE_LEN"]);

$arParams["DISPLAY_TOP_PAGER"] = $arParams["DISPLAY_TOP_PAGER"]=="Y";
$arParams["DISPLAY_BOTTOM_PAGER"] = $arParams["DISPLAY_BOTTOM_PAGER"]!="N";

$arParams["PAGER_DESC_NUMBERING"] = $arParams["PAGER_DESC_NUMBERING"]=="Y";
$arParams["PAGER_DESC_NUMBERING_CACHE_TIME"] = intval($arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]);
$arParams["PAGER_SHOW_ALL"] = $arParams["PAGER_SHOW_ALL"]!=="N";
if($arParams["RESIZE_TYPE"]){
    $resizeType = $this->getConstantValue($arParams["RESIZE_TYPE"]);
} else {
    $resizeType = BX_RESIZE_IMAGE_PROPORTIONAL;
}

if($arParams["DISPLAY_TOP_PAGER"] || $arParams["DISPLAY_BOTTOM_PAGER"])
{
	$arNavParams = array(
		"nPageSize" => $arParams["COUNT"],
		"bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
		"bShowAll" => $arParams["PAGER_SHOW_ALL"],
	);
	$arNavigation = CDBResult::GetNavParams($arNavParams);
	if($arNavigation["PAGEN"]==0 && $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"]>0)
		$arParams["CACHE_TIME"] = $arParams["PAGER_DESC_NUMBERING_CACHE_TIME"];
}
else
{
	$arNavParams = array(
		"nTopCount" => $arParams["COUNT"],
		"bDescPageNumbering" => $arParams["PAGER_DESC_NUMBERING"],
	);
	$arNavigation = false;
}

$arParams["USE_PERMISSIONS"] = $arParams["USE_PERMISSIONS"]=="Y";
if(!is_array($arParams["GROUP_PERMISSIONS"]))
	$arParams["GROUP_PERMISSIONS"] = array(1);

$bUSER_HAVE_ACCESS = !$arParams["USE_PERMISSIONS"];
if($arParams["USE_PERMISSIONS"] && isset($GLOBALS["USER"]) && is_object($GLOBALS["USER"]))
{
	$arUserGroupArray = $USER->GetUserGroupArray();
	foreach($arParams["GROUP_PERMISSIONS"] as $PERM)
	{
		if(in_array($PERM, $arUserGroupArray))
		{
			$bUSER_HAVE_ACCESS = true;
			break;
		}
	}
}

$firm = intval($arParams["FIRM"]);
if($this->StartResultCache(false, array(($arParams["CACHE_GROUPS"]==="N"? false: $USER->GetGroups()), $bUSER_HAVE_ACCESS, $arNavigation, $arrFilter)))
{
	if(!CModule::IncludeModule("main")||!CModule::IncludeModule("iblock"))
	{
		$this->AbortResultCache();
		ShowError(GetMessage("IBLOCK_MODULE_NOT_INSTALLED"));
		return;
	}
        //SELECT
        $arSelect = array_merge($arParams["FIELD_CODE"], array(
                "ID",
                "ACTIVE",
                "LAST_LOGIN",
                "LOGIN",
                "EMAIL",
                "NAME",
                "LAST_NAME",
                "SECOND_NAME",
                "TIMESTAMP_X",
                "DATE_REGISTER",
                "PERSONAL_BIRTHDAY",
                "PERSONAL_PHOTO",
        ));
        //WHERE // !!!

        $arFilter = array (
            "ACTIVE" => "Y",
        );

        if($firm){
            $arFilter["UF_GILD"] = $firm;
        }

        // var_dump($arFilter);

        //ORDER BY
        $arSort = array(
                $arParams["SORT_BY1"]=>$arParams["SORT_ORDER1"],
                $arParams["SORT_BY2"]=>$arParams["SORT_ORDER2"],
                $arParams["SORT_BY3"]=>$arParams["SORT_ORDER3"],
        );
        if(!array_key_exists("ID", $arSort))
                $arSort["ID"] = "DESC";
        $arParameters = array(
            'SELECT' => $arParams["PROPERTY_CODE"],
            'NAV_PARAMS' => $arNavParams,
            'FIELDS' => $arSelect,
        );
        $arResult = array();
        // $rsUser = CUser::GetList($arSort, array_merge($arFilter, $arrFilter), $arParameters);
        //        $rsUser->SetUrlTemplates($arParams["DETAIL_URL"], "", "");

        // pr($arParameters);

        $rsUser = CUser::GetList(($by = "NAME"), ($order = "desc"),array_merge($arFilter, $arrFilter));
        if($rsUser->SelectedRowsCount())
        {
            while ($arUser = $rsUser->GetNext()){

                if($arParams['DETAIL_URL']){
                    $arUser['DETAIL_URL'] = $this->getUrlTemplates($arParams['DETAIL_URL'], $arUser);
                }
                if(strlen($arUser["LAST_LOGIN"])>0){
                    $arUser["DISPLAY_LAST_LOGIN"] = CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($arUser["LAST_LOGIN"], CSite::GetDateFormat()));
                } else {
                    $arUser["DISPLAY_LAST_LOGIN"] = "";
                }
                if(strlen($arUser["DATE_REGISTER"])>0){
                    $arUser["DISPLAY_DATE_REGISTER"] = CIBlockFormatProperties::DateFormat($arParams["ACTIVE_DATE_FORMAT"], MakeTimeStamp($arUser["DATE_REGISTER"], CSite::GetDateFormat()));
                } else {
                    $arUser["DISPLAY_DATE_REGISTER"] = "";
                }
                if(isset($arUser["PERSONAL_PHOTO"]))
                {
                    $arUser["PERSONAL_PHOTO"] = (0 < $arUser["PERSONAL_PHOTO"] ? CFile::GetFileArray($arUser["PERSONAL_PHOTO"]) : false);
                    if($arUser["PERSONAL_PHOTO"] && $arParams['RESIZE_PERSONAL_PHOTO']){
                        $arSize = explode('*', $arParams['RESIZE_PERSONAL_PHOTO']);
                        $arResizeFile = CFile::ResizeImageGet($arUser['PERSONAL_PHOTO'], array('width'=>$arSize[0], 'height'=>$arSize[1]), $resizeType, true);
                        if($arResizeFile){
                            $arUser["PERSONAL_PHOTO"]['RESIZE'] = $arResizeFile;
                        }
                        unset($arSize);
                    }
                }
                if(isset($arUser["WORK_LOGO"]))
                {
                    $arUser["WORK_LOGO"] = (0 < $arUser["WORK_LOGO"] ? CFile::GetFileArray($arUser["WORK_LOGO"]) : false);
                    if($arUser["WORK_LOGO"] && $arParams['RESIZE_WORK_LOGO']){
                        $arSize = explode('*', $arParams['RESIZE_WORK_LOGO']);
                        $arResizeFile = CFile::ResizeImageGet($arUser['WORK_LOGO'], array('width'=>$arSize[0], 'height'=>$arSize[1]), $resizeType, true);
                        if($arResizeFile){
                            $arUser["WORK_LOGO"]['RESIZE'] = $arResizeFile;
                        }
                        unset($arSize);
                    }
                }
                $arResult['ITEMS'][] = $arUser;
            }

            // pr($arParams["PAGER_TITLE"]);
            // pr($arParams["PAGER_TEMPLATE"]);
            // pr($arParams["PAGER_SHOW_ALWAYS"]);
            // pr($arParams["PAGER_TITLE"]);
            
            $navComponentParameters = array();
            $arResult["NAV_STRING"] = $rsUser->GetPageNavStringEx(
                    $navComponentObject,
                    $arParams["PAGER_TITLE"],
                    $arParams["PAGER_TEMPLATE"],
                    $arParams["PAGER_SHOW_ALWAYS"],
                    $this,
                    $navComponentParameters
            );
            $arResult["NAV_CACHED_DATA"] = null;
            $arResult["NAV_RESULT"] = $rsElement;
            $this->SetResultCacheKeys(array(
                    "ITEMS",
                    "NAV_STRING",
                    $arParams["RESULT_KEY"]
            ));
            $this->IncludeComponentTemplate();
	}
	else
	{
		$this->AbortResultCache();
		ShowError(GetMessage("T_NEWS_NEWS_NA"));
		if($arParams["SET_STATUS_404"]==="Y") {
			CHTTP::SetStatus("404 Not Found");
			@define("ERROR_404", "Y");
		}
	}
}
?>