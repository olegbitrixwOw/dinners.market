<?php
use Bitrix\Main, Bitrix\Sale;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;

// получаем раздел документов фирмы для которой добавляем новый документ
function orgSectionFind($firmID){
    $arFilter = Array('IBLOCK_ID'=>IBLOCK_DOCUMENTS, 'GLOBAL_ACTIVE'=>'Y', 'UF_FIRM'=>$firmID);
    $db_list = CIBlockSection::GetList(array(), $arFilter, false, array('NAME','UF_FIRM', 'CODE', 'IBLOCK_ID', 'ID'));
   
    if($uf_value = $db_list->GetNext()){
          return $uf_value;
    }
    else{ // если раздела нет то создаем его 
      
        $sectionID = false;
        $res = CIBlockElement::GetByID($firmID);
        if($ar_res = $res->GetNext()){
            $arFirm = $ar_res;
            $code = Cutil::translit($arFirm['NAME'], "ru", array("replace_space"=>"_", "replace_other"=>"_", 'change_case' => 'L', 'max_len' => 100));
            $bs = new CIBlockSection();
            $sectionID = $bs->Add([
                    'NAME' => $arFirm['NAME'],
                    'CODE' => $code.'_'.$arFirm['ID'],
                    'IBLOCK_ID' => IBLOCK_DOCUMENTS,
                    'UF_FIRM' => $arFirm['ID']
            ]);
        }
        if($sectionID){
            $res = CIBlockSection::GetByID($sectionID);
            if($ar_res = $res->GetNext()){
              return $ar_res;
            }
        }    
    }
}

function yearSectionName($arFields, $orgCode, $typeCode){
    $arr = explode('.', $arFields['DATE_ACTIVE_FROM']);
    $code = $typeCode.'_'.$arr[2];
    $name = $arr[2];
    return $yearSection = array('CODE'=>$code, 'NAME'=>$name);
}

function monthSectionName($element, $typeCode){
    $arr = explode('.', $element['DATE_ACTIVE_FROM']);
    $code = $typeCode.'_'.$arr[2].'_'.$arr[1];
    $name = $arr[1];
    return $dateSection = array('CODE'=>$code, 'NAME'=>$name);
}

function sectionFind($sectionCode, $parentID){
    $res = CIBlockSection::GetList(array(), array('IBLOCK_ID' => IBLOCK_DOCUMENTS, 'CODE' => $sectionCode, 'SECTION_ID'=>$parentID));
    return $section = $res->Fetch();
}

function dateSectionName($arFields, $orgCode, $typeCode){
    $arr = explode('.', $arFields['DATE_ACTIVE_FROM']);
    $code = $typeCode.'_'.$arr[2].'_'.$arr[1].'_'.$arr[0];

    $year = $arr[2];
    $month = $arr[1];
    $date = $arr[0];
    $name = $arr[0];

    return $dateSection = array(
      'CODE'=>$code, 
      'NAME'=>$name,
      'YEAR'=>$year,
      'MONTH'=>$month,
      'DATE'=>$date
    );
}

function parentSection($arr){
    $parentID = false;
    foreach ($arr as $key => $section) {
      
      if($section['VALUE']){
        $sectionID = $section['VALUE']['ID'];
      }
      else{
          
          if($section['TYPE'] == 'orgSection'){
                // $sectionID = sectionCreate(false, $section);
          }else{
                $sectionID = _sectionCreate($parentID, $section);
          }
      }
      
      $parentID = $sectionID;
    }
    return $parentID;
}

function countItems($section_id, $element_section_id){
    $sect = CIBlockSection::GetList(
        array("sort"=>"asc", 'name'=>'asc'), 
        array(
            'IBLOCK_ID'=>IBLOCK_DOCUMENTS, 
            'ID'=>$section_id,
            'GLOBAL_ACTIVE'=>"Y",
            'CNT_ACTIVE'=>true,
            'INCLUDE_SUBSECTIONS'=>true
        ), 
        true, 
        array('NAME')
    );

    while($el = $sect->Fetch()){
        $count += $el["ELEMENT_CNT"];
    }

    if($section_id != $element_section_id){
        $minnums = 0;
    }else{
        $minnums = 1;
    }

    if($count == $minnums){
        $bs = new CIBlockSection;
        $arFields = Array(
            "ACTIVE" => 'N',
        );
        $res = $bs->Update($section_id, $arFields);
    }
}

function checkSection($arr, $elementSectionID){
    foreach ($arr as $key => $section) {
        if($section['VALUE']){
            $sectionID = $section['VALUE']['ID'];
            countItems($sectionID, $elementSectionID);
        }
    }
}

function typeSectionName($element, $orgCode){
    $type = $element['DOCUMENT_TYPE']; // свойство тип документа
    switch ($type) {
       case '41':
            $name = 'Счет';
        break;
        
        case '40':
            $name = 'Акт выполненных услуг';
        break;

        case '39':
            $name = 'Договор';
        break;

        case '38':
            $name = 'Товарная накладная';
        break;

        default:
              # code...
        break;
    }
    $code = Cutil::translit($name, "ru", array("replace_space"=>"_", "replace_other"=>"_", 'change_case' => 'L', 'max_len' => 100));
    $code = $orgCode.'_'.$code;
    return $typeSection = array('CODE' => $code, 'NAME'=>$name);
}

function documentSections($element, $arFields = false){
    if($element['DOCUMENT_TYPE']){
        $org = orgSectionFind($element['ORGANIZATION']);

        if($arFields){

            if($arFields['PROPERTY_VALUES'][62]){ // DOCUMENT_TYPE 
                $element['DOCUMENT_TYPE'] = $arFields['PROPERTY_VALUES'][62];
            }

            if($arFields['DATE_ACTIVE_FROM']){
              $element['DATE_ACTIVE_FROM'] = $arFields['DATE_ACTIVE_FROM'];
            }
        }

        $type = typeSectionName($element, $org['CODE']);
        $year = yearSectionName($element, $org['CODE'], $type['CODE']);
        $month = monthSectionName($element, $type['CODE']);
        $typeSection = sectionFind($type['CODE'], $org['ID']);
        $yearSection = sectionFind($year['CODE'], $typeSection['ID']);
        $monthSection = sectionFind($month['CODE'], $yearSection['ID']);

        $date = dateSectionName($element, $org['CODE'], $type['CODE']);
        $dateSection = sectionFind($date['CODE'], $monthSection['ID']);

        $arr = array(
          ['TYPE'=>'orgSection','CODE'=>$org['CODE'],'NAME'=>$org['NAME'],'VALUE'=>$org],
          ['TYPE'=>'typeSection','CODE'=>$type['CODE'], 'NAME'=>$type['NAME'],'VALUE'=>$typeSection],
          ['TYPE'=>'yearSection','CODE'=>$year['CODE'], 'NAME'=>$year['NAME'],'VALUE'=>$yearSection],
          ['TYPE'=>'monthSection','CODE'=>$month['CODE'], 'NAME'=>$month['NAME'],'VALUE'=>$monthSection],
          ['TYPE'=>'dateSection','CODE'=>$date['CODE'],'NAME'=>$date['NAME'],'VALUE'=>$dateSection]
        );

        $parentID = parentSection($arr);
        $elementCode = Cutil::translit($element['NAME'], "ru", array("replace_space"=>"_", "replace_other"=>"_", 'change_case' => 'L', 'max_len' => 100));
        $element['CODE'] = $date['CODE'].'_'.$elementCode;
        $element['IBLOCK_SECTION'] = $parentID;

        return $element;
    }
} 

function documentUpdate($arFields){
    $res = CIBlockElement::GetByID($arFields['ID']);
    if($arItem = $res->GetNext()){
            $dbProperty = CIBlockElement::getProperty(IBLOCK_DOCUMENTS, $arItem['ID'], array("sort", "asc"),array());
            while ($arProperty = $dbProperty->GetNext()) {
                if($arProperty['CODE'] == 'DOCUMENT_TYPE'){
                    $arItem['DOCUMENT_TYPE'] =  $arProperty['VALUE']; 
                }
                if($arProperty['CODE'] == 'ORGANIZATION'){
                    $arItem['ORGANIZATION'] =  $arProperty['VALUE']; 
                }   
            }
            // разделы документа после обновления
            $elementAfterUp = documentSections($arItem, $arFields);
            $arFields['IBLOCK_SECTION'] = $elementAfterUp['IBLOCK_SECTION'];
    }
    return $arFields;
}