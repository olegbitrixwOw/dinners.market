<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Highloadblock as HL;
/**
 * @var array $arParams
 * @var array $arResult
 * @var SaleOrderAjax $component
 */

$component = $this->__component;
$component::scaleImages($arResult['JS_DATA'], $arParams['SERVICES_IMAGES_SCALING']);

$products = $props = $prop_codes = array();
$hl_values = $hl_tables = $hl_files = array();
foreach($arResult["JS_DATA"]["GRID"]["ROWS"] as $key => $row){
    $products[$key] = $row["data"]["PRODUCT_ID"];
}
foreach($arResult["JS_DATA"]["GRID"]["ROWS"] as $key => $row){
    if(isset($row['data']) && isset($row['data']['PROPS']) && !empty($row['data']['PROPS'])){
        foreach ($row['data']['PROPS'] as $k => $prop){
            $prop_codes[$prop["CODE"]] = $prop["CODE"];
        }
    }
}
if(!empty($products) && !empty($prop_codes)){
    $arSelect = Array("ID", "IBLOCK_ID", "NAME", "DATE_ACTIVE_FROM","PROPERTY_*");//IBLOCK_ID è ID îáÿçàòåëüíî äîëæíû áûòü óêàçàíû, ñì. îïèñàíèå arSelectFields âûøå
    $arFilter = Array("ACTIVE_DATE"=>"Y", "ACTIVE"=>"Y", "ID" => $products);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while($ob = $res->GetNextElement()){
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        foreach($arProps as $key => $p){
            if(array_key_exists($key, $prop_codes) && $p["USER_TYPE"] == "directory"){
                $hl_tables[] = $p["USER_TYPE_SETTINGS"]["TABLE_NAME"];
                $hl_values[] = $p["VALUE"];
                $props[$arFields["ID"]][$p["CODE"]][$p["VALUE"]] = "";
            }
        }
    }
    $hl_tables = array_unique($hl_tables);
    if(!empty($hl_tables)){
        foreach($hl_tables as $HLiB_name){
            $hlblock = HL\HighloadBlockTable::getList(array('filter'=>array('TABLE_NAME'=>$HLiB_name)))->fetch();
            $arR = array();
            if (!empty($hlblock)){
                $entity = HL\HighloadBlockTable::compileEntity($hlblock);
                $entity_data_class = $entity->getDataClass();
                $rsData = $entity_data_class::getList(array(
                    "select" => array("*"),
                    "order" => array("ID" => "ASC"),
                    "filter" => array("UF_XML_ID" => $hl_values),
                ));
                while($arData = $rsData->Fetch()){
                    if(isset($arData["UF_FILE"])){
                        $hl_files[$arData["UF_XML_ID"]] = CFile::GetPath($arData["UF_FILE"]);
                    }
                }
            }
        }
        if(!empty($hl_files)){
            foreach ($props as $good_id => $prop){
                foreach ($prop as $code => $p) {
                    foreach ($p as $value => $p2) {
                        $props[$good_id][$code][$value] = $hl_files[$value];
                    }
                }
            }
        }
        foreach($arResult["JS_DATA"]["GRID"]["ROWS"] as $key => $row){
            foreach($row["data"]["PROPS"] as $prop_num => $prop){
                if(isset($props[$row['data']['PRODUCT_ID']][$prop["CODE"]]) && !empty($props[$row['data']['PRODUCT_ID']][$prop["CODE"]])){
                    $arResult["JS_DATA"]["GRID"]["ROWS"][$key]["data"]["PROPS"][$prop_num]["FILE"] = array_values($props[$row['data']['PRODUCT_ID']][$prop["CODE"]]);
                }
            }
        }
    }
}

$arUser = getUserData($USER->GetID());
$arResult['JS_DATA']['ORDER_DESCRIPTION'] = 'Адрес доставки: '.$arUser['UF_DELIVERY'];
