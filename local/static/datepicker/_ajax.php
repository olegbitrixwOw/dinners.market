<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");?>
<?php
use Bitrix\Main\Loader;
use Bitrix\Main\Mail\Event;
use Bitrix\Main\Context;
global $APPLICATION; 
$request = Context::getCurrent()->getRequest();
$iblockId = intval($request->getPost('iblock_id'));
$iblockType = htmlspecialcharsEx($request->getPost('iblock_type'));
$position = intval($request->getPost('position'));
$device = htmlspecialcharsEx($request->getPost('device')); 

$propertyCode = htmlspecialcharsEx($request->getPost('property_code'));
$sort = intval($request->getPost('sort')); 

$newsCount = intval($request->getPost('news_count')); // количество записей в выборке 
$arrFilter = array("IBLOCK_ID"=>$iblockId, "ACTIVE"=>"Y", ">SORT"=>intval($sort));

// параметры ответа 
$data = [];
$data['NUMS'] = 0; // число элементов
$data['NEXT_ELEMENT'] = 0; // ID следующего элемента для активации кнопки

// выборка записей
$arItems = array();
$res = CIBlockElement::GetList(
    array("SORT"=>"ASC"),      // сортировка
    $arrFilter,   // фильтрация
    false,      // параметры группировки полей
    array("nPageSize"=> $newsCount + 1),      // параметры навигации
    array("ID", "NAME", "SORT") // поля для выборки
);
while($arItem = $res->fetch()){
     $arItems[] = $arItem; 
}
$data['arItems'] = $arItems;

if(count($arItems) > 0){
    $data['NUMS'] = count($arItems); // число элементов
    $preserved = array_slice($arItems,-2, 1);
    $END = end($arItems);
    if($data['NUMS'] > $newsCount){
        $data['NEXT_ELEMENT'] = 1;
        $res = CIBlockElement::GetByID($preserved[0]["ID"]);
    }else{
        $res = CIBlockElement::GetByID($END["ID"]);
    }
    $data['LAST_ELEMENT'] = $res->GetNext();
    $data['SORT'] = $data['LAST_ELEMENT']['SORT'];
}
ob_start();
?>
<?$APPLICATION->IncludeComponent(
  "bitrix:news.list",
  "projects_show_more",
  array(
    "ACTIVE_DATE_FORMAT" => "d.m.Y",
    "ADD_SECTIONS_CHAIN" => "Y",
    "AJAX_MODE" => "N",
    "AJAX_OPTION_ADDITIONAL" => "", 
    "AJAX_OPTION_HISTORY" => "N",
    "AJAX_OPTION_JUMP" => "N",
    "AJAX_OPTION_STYLE" => "Y",
    "CACHE_FILTER" => "N",
    "CACHE_GROUPS" => "Y",
    "CACHE_TIME" => "36000000",
    "CACHE_TYPE" => "A",
    "CHECK_DATES" => "Y",
    "DETAIL_URL" => "",
    "DISPLAY_BOTTOM_PAGER" => "Y",
    "DISPLAY_DATE" => "Y",
    "DISPLAY_NAME" => "Y",
    "DISPLAY_PICTURE" => "Y",
    "DISPLAY_PREVIEW_TEXT" => "Y",
    "DISPLAY_TOP_PAGER" => "N",
    "FIELD_CODE" => array("",""),
    "FILTER_NAME" => "arrFilter",
    "USE_FILTER" => "Y",
    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
    "IBLOCK_ID" => $iblockId ,
    "IBLOCK_TYPE" => $iblockType,
    "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
    "INCLUDE_SUBSECTIONS" => "Y",
    "MESSAGE_404" => "",
    "NEWS_COUNT" => $newsCount,
    "PAGER_BASE_LINK_ENABLE" => "N",
    "PAGER_DESC_NUMBERING" => "N",
    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
    "PAGER_SHOW_ALL" => "N",
    "PAGER_SHOW_ALWAYS" => "N",
    "PAGER_TEMPLATE" => ".default",
    "PAGER_TITLE" => "Новости",
    "PARENT_SECTION" => "",
    "PARENT_SECTION_CODE" => "",
    "PREVIEW_TRUNCATE_LEN" => "",
    "PROPERTY_CODE"=>$propertyCode,
    "SET_BROWSER_TITLE" => "Y",
    "SET_LAST_MODIFIED" => "N",
    "SET_META_DESCRIPTION" => "Y",
    "SET_META_KEYWORDS" => "Y",
    "SET_STATUS_404" => "N",
    "SET_TITLE" => "Y",
    "SHOW_404" => "N",
    "SORT_BY1" => "ACTIVE_FROM",
    "SORT_BY2" => "SORT",
    "SORT_ORDER1" => "DESC",
    "SORT_ORDER2" => "ASC",
    "STRICT_SECTION_CHECK" => "N",
    "POSITION" => $position,
    "DEVICE" =>$device
  )
);?>
<?
$data['ITEMS'] = ob_get_clean();
echo json_encode($data);
?>

<?die();?>
