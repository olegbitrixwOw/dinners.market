<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// pr($arResult);
$propertyID = 73;
// pr($arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"]);
// pr($arResult["PROPERTY_LIST_FULL"][$propertyID]);
// pr($arResult["PROPERTY_LIST_FULL"][$propertyID]);

// if ($arResult["PROPERTY_LIST_FULL"][$propertyID]["LIST_TYPE"] == "C"){
// 	echo $type = $arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y" ? "checkbox" : "radio";
// }
// else{
// 	echo $type = $arResult["PROPERTY_LIST_FULL"][$propertyID]["MULTIPLE"] == "Y" ? "multiselect" : "dropdown";
// }

// foreach ($arResult["PROPERTY_LIST"] as $key => $propertyID){
// 		if($propertyID == 73){
// 			// $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "L";
// 			pr($arResult["PROPERTY_LIST_FULL"][$propertyID]);
// 		}
// }

// foreach ($arResult["PROPERTY_LIST"] as $key => $propertyID){
// 		if($propertyID == 73){
// 			// $arResult["PROPERTY_LIST_FULL"][$propertyID]["PROPERTY_TYPE"] == "L";
// 			pr($arResult["PROPERTY_LIST_FULL"][$propertyID]);

// 			$arSelect = array("*", "PROPERTY_*");
// 			$arFilter = array("IBLOCK_ID"=>$iblock_id, 	"ACTIVE"=>"Y", "=DATE_ACTIVE_FROM" => $date, "=PROPERTY_CITY"=>$city_id);
// 			$arItems = [];
// 			$res = CIBlockElement::GetList(array(), $arFilter, false, array("nPageSize"=>500), $arSelect);
// 			while($arItem = $res->fetch()){
// 			    $arItems[] = getProp($arItem); 
// 			}
// 			$arItems;
// 		}
// }