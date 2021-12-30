<?php

// use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;


/*** CSV ***/

// получаем цену у товара по его ID 
function PriceByProductID($productID){
    CModule::IncludeModule("catalog"); 
    $rsPrices = CPrice::GetList(Array(), Array("PRODUCT_ID" => $productID));
    $array = Array();    
    while($arPrice = $rsPrices->Fetch()){
        //print_r($arPrice);
        $array[] = $arPrice;
    }    
    return $array;
}

// получаем список продуктов по категориям
function getProducts($sections){
  
  // echo is_array($sections);
  // var_dump($sections);

  CModule::IncludeModule("iblock");
  // $arFilter = array("IBLOCK_ID"=>IBLOCK_PRODUCTS, "ACTIVE"=>"Y", "SECTION_ID"=>93); 
  $arFilter = array(
    "IBLOCK_ID"=>IBLOCK_PRODUCTS, 
    "ACTIVE"=>"Y", 
    "INCLUDE_SUBSECTIONS" => "Y",
    "SECTION_ID"=>$sections
  );

  $arSelect = array(
    "ID",
    "NAME",
    "DETAIL_PAGE_URL",
    // "DETAIL_TEXT", 
    // "PROPERTY_KATEGORIYA1", 
    // "PROPERTY_CATEGORY1_SLUG", 
    // "PROPERTY_KATEGORIYA2", 
    // "PROPERTY_CATEGORY2_SLUG", 
    // "PROPERTY_KATEGORIYA3", 
    // "PROPERTY_CATEGORY3_SLUG",
    // "PROPERTY_BASE",
    // "PROPERTY_TSENA", // цена в рублях
    // "PROPERTY_TSENA_" // 
  );
  $res = CIBlockElement::GetList(
    array("ID"=>"ASC"), 
    $arFilter, 
    false, 
    false, 
    $arSelect
  );   

  $array_line_full = array();
  while($ar = $res->GetNext()) {
    $product["ID"] = $ar['ID'];
    $product["NAME"] = $ar['NAME'];
    $product['DETAIL_PAGE_URL'] = $ar['DETAIL_PAGE_URL'];
    $product['DETAIL_TEXT'] = $ar['DETAIL_TEXT'];
    // $product['PROPERTY_KATEGORIYA1_VALUE'] = $ar['PROPERTY_KATEGORIYA1_VALUE'];
    // $product['PROPERTY_CATEGORY1_SLUG_VALUE'] = $ar['PROPERTY_CATEGORY1_SLUG_VALUE'];
    // $product['PROPERTY_KATEGORIYA2_VALUE'] = $ar['PROPERTY_KATEGORIYA2_VALUE'];
    // $product['PROPERTY_CATEGORY2_SLUG_VALUE'] = $ar['PROPERTY_CATEGORY2_SLUG_VALUE'];
    // $product['PROPERTY_KATEGORIYA3_VALUE'] = $ar['PROPERTY_KATEGORIYA3_VALUE'];
    // $product['PROPERTY_CATEGORY3_SLUG_VALUE'] = $ar['PROPERTY_CATEGORY3_SLUG_VALUE'];

    // $product['PROPERTY_BASE_VALUE'] = $ar['PROPERTY_BASE_VALUE'];
    // $product['PROPERTY_TSENA_VALUE'] = $ar['PROPERTY_TSENA_VALUE'];
    // $product['PROPERTY_TSENA__VALUE'] = $ar['PROPERTY_TSENA__VALUE'];

    $product['PRICE'] = PriceByProductID($ar['ID'])[0]['PRICE'];
    $array_line_full[]  = $product;
 
  }

  return $array_line_full;
}

// CSV
function putDataToCSV($file,$array){
  $fields_type = 'R'; //дописываем строки в файл
  $delimiter = ";";   //разделитель для csv-файла
  $csvFile = new \CCSVData($fields_type, false);
  $csvFile->SetFieldsType($fields_type);
  $csvFile->SetDelimiter($delimiter);
  $csvFile->SetFirstHeader(true);
  $arrayHeader = array(
    "ID",
    "ИМЯ",
    "URL",
    "ЦЕНА"
  );
  $arrayFields = array(
    "ID",
    "NAME",
    "DETAIL_PAGE_URL",
    // "OPISANIE", 
    // "KATEGORIA", 
    // "CODE KATEGORI", 
    // "KATEGORIA UR 2", 
    // "CODE KATEGORI UR 2", 
    // "KATEGORIA UR 3", 
    // "CODE KATEGORI UR 3",
    "PRICE"
    // "ID",
    // "NAME",
    // "DETAIL_PAGE_URL",
    // "DETAIL_TEXT", 
    // "PROPERTY_KATEGORIYA1_VALUE", 
    // "PROPERTY_CATEGORY1_SLUG_VALUE", 
    // "PROPERTY_KATEGORIYA2_VALUE", 
    // "PROPERTY_CATEGORY2_SLUG_VALUE", 
    // "PROPERTY_KATEGORIYA3_VALUE", 
    // "PROPERTY_CATEGORY3_SLUG_VALUE",
    // "PRICE"
  );

  // запишем заголовки:
  // $csvFile->SaveFile($file,$arrayFields);
  $csvFile->SaveFile($file,$arrayHeader);

  foreach ($array as $arValue){
    $row = array();
    foreach ($arrayFields as $arrayField)
    {
      $row[] = $arValue[$arrayField];
    }
    $csvFile->SaveFile($file,$row);
  }
}

// читаем CSV файл
function getCSV($csv_file){        
    $handle = fopen($_SERVER['DOCUMENT_ROOT'].'/upload/csv/import/'.$csv_file, "r"); // Открываем csv для чтения 
    $array_line_full = array(); 

    // Проходим весь csv-файл, и читаем построчно. 3-ий параметр разделитель поля
    while (($line = fgetcsv($handle, 0, ";")) !== FALSE) { 
        $array_line_full[] = $line;
    }
    fclose($handle); //Закрываем файл
    unset($array_line_full[0]);
    return $array_line_full; 
}


// получаем продукт по его названию
function getProduct($name, $code = null, $section = null){
 CModule::IncludeModule("iblock");
    $arFilter = array(
      "IBLOCK_ID"=>IBLOCK_PRODUCTS, 
      "ACTIVE"=>"Y", 
      "NAME"=>$name
    );

    $arSelect = array(
      "ID",
      "NAME",
      "DETAIL_PAGE_URL",
    );

    $res = CIBlockElement::GetList(
      array("ID"=>"ASC"), 
      $arFilter, 
      false, 
      false, 
      $arSelect
    );   

    $array_line_full = array();
    while($ar = $res->GetNext()) {
      $array_line_full[] = $ar;
    }

    return $array_line_full[0];
}


// обновляем цены
function pricesUpdate($product_id, $price, $currency = "USD", $catalogGroupName = 1){

    CModule::IncludeModule("catalog");  
    $arrPrices = PriceByProductID($product_id);
    
    foreach($arrPrices as $PriceItem){
        
        if($PriceItem['CATALOG_GROUP_ID'] == $catalogGroupName){
            $arFields = Array(
                "PRODUCT_ID" => $product_id, 
                "CATALOG_GROUP_ID" => $catalogGroupName, 
                "PRICE" => $price, 
                "CURRENCY" => $currency
              );
            $obPrice = new CPrice();
            if($obPrice->Update($PriceItem['ID'], $arFields)){
              // $arAddPrices[$price_type_id] = $price;  
              return true;
            }
            else {
                echo 'Ошибка обновления цен <br>'; 
                die();          
            }  
        } 
    }  

}

// обновляем свойства цена
function updateProperties($product_id, $price){
    $arProp = [];
    $arProp['TSENA'] =  CCurrencyRates::ConvertCurrency($price, 'USD', 'RUB'); // цена на русском
    $arProp['TSENA_'] = $price; // цена на английском
    $propRes = CIBlockElement::SetPropertyValuesEx($arParams['fields']['PRODUCT_ID'], IBLOCK_PRODUCTS, $arProp);
}