<?php
use Bitrix\Main, Bitrix\Sale;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;

function _sectionCreate($parentID, $section){
    $bs = new CIBlockSection();
    $sectionID = $bs->Add([
            'NAME' => $section['NAME'],
            'CODE' => $section['CODE'],
            'IBLOCK_ID' => IBLOCK_DOCUMENTS,
            'IBLOCK_SECTION_ID' => $parentID
    ]);
    return $sectionID;
}


function _orgSectionFind($firmID){
    $arFilter = Array(
        'IBLOCK_ID'=>IBLOCK_DOCUMENTS, 
        // 'GLOBAL_ACTIVE'=>'Y', 
        'UF_FIRM'=>$firmID);
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

function _typeSectionName($element, $orgCode){
    // $arr = explode('.', $arFields['DATE_ACTIVE_FROM']);
    // $dateCode = $arr[0].'_'.$arr[1]; 
    $type = $element['DOCUMENT_TYPE']; // свойство тип документа
    // setLog($type);
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
    // setLog($name);
    $code = Cutil::translit($name, "ru", array("replace_space"=>"_", "replace_other"=>"_", 'change_case' => 'L', 'max_len' => 100));
    $code = $orgCode.'_'.$code;
    return $typeSection = array('CODE' => $code, 'NAME'=>$name);
}

function _yearSectionName($arFields, $orgCode, $typeCode){
    $arr = explode('.', $arFields['DATE_ACTIVE_FROM']);
    $code = $typeCode.'_'.$arr[2];
    $name = $arr[2];
    return $yearSection = array('CODE'=>$code, 'NAME'=>$name);
}

function _monthSectionName($element, $orgCode, $typeCode){
    $arr = explode('.', $element['DATE_ACTIVE_FROM']);
    $code = $typeCode.'_'.$arr[2].'_'.$arr[1];
    $name = $arr[1];
    return $dateSection = array('CODE'=>$code, 'NAME'=>$name);
}

function _sectionFind($sectionCode, $parentID){
    $res = CIBlockSection::GetList(array(), array('IBLOCK_ID' => IBLOCK_DOCUMENTS, 'CODE' => $sectionCode, 'SECTION_ID'=>$parentID));
    return $section = $res->Fetch();
}

function _dateSectionName($arFields, $orgCode, $typeCode){
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


function _parentSection($arr){
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

function documentAdd($arFields){

    if($arFields['PROPERTY_VALUES']['62']){ // DOCUMENT_TYPE
        
        // $arFields['DOCUMENT_TYPE'] = $arFields['PROPERTY_VALUES']['62']['VALUE'];
        $arFields['DOCUMENT_TYPE'] = $arFields['PROPERTY_VALUES']['62'];
        $org = _orgSectionFind($arFields['PROPERTY_VALUES']['64']);
        $type = _typeSectionName($arFields, $org['CODE']);
        $year = _yearSectionName($arFields, $org['CODE'], $type['CODE']);
        $month  = _monthSectionName($arFields, $org['CODE'], $type['CODE']);
        $typeSection = _sectionFind($type['CODE'], $org['ID']);
        
        $yearSection = _sectionFind($year['CODE'], $typeSection['ID']);
        $monthSection = _sectionFind($month['CODE'], $yearSection['ID']);
        $date = _dateSectionName($arFields, $org['CODE'], $type['CODE']);
        $dateSection = _sectionFind($date['CODE'], $monthSection['ID']);

        $arr = array(
            ['TYPE'=>'orgSection','CODE'=>$org['CODE'],'NAME'=>$org['NAME'],'VALUE'=>$org],
            ['TYPE'=>'typeSection','CODE'=>$type['CODE'], 'NAME'=>$type['NAME'],'VALUE'=>$typeSection],
            ['TYPE'=>'yearSection','CODE'=>$year['CODE'], 'NAME'=>$year['NAME'],'VALUE'=>$yearSection],
            ['TYPE'=>'monthSection','CODE'=>$month['CODE'], 'NAME'=>$month['NAME'],'VALUE'=>$monthSection],
            ['TYPE'=>'dateSection','CODE'=>$date['CODE'],'NAME'=>$date['NAME'],'VALUE'=>$dateSection]
        );

        $parentID = _parentSection($arr);

        $elementCode = Cutil::translit($arFields['NAME'], "ru", array("replace_space"=>"_", "replace_other"=>"_", 'change_case' => 'L', 'max_len' => 100));
        $arFields['CODE'] = $date['CODE'].'_'.$elementCode;
        $arFields['IBLOCK_SECTION'] = $parentID;
        $arFields['ACTIVE'] = 'Y';

        return $arFields;
    }
}