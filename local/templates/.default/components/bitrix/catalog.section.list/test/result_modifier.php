<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// $arViewModeList = array('LIST', 'LINE', 'TEXT', 'TILE');

// $arDefaultParams = array(
// 	'VIEW_MODE' => 'LIST',
// 	'SHOW_PARENT_NAME' => 'Y',
// 	'HIDE_SECTION_NAME' => 'N'
// );

// $arParams = array_merge($arDefaultParams, $arParams);

// if (!in_array($arParams['VIEW_MODE'], $arViewModeList))
// 	$arParams['VIEW_MODE'] = 'LIST';
// if ('N' != $arParams['SHOW_PARENT_NAME'])
// 	$arParams['SHOW_PARENT_NAME'] = 'Y';
// if ('Y' != $arParams['HIDE_SECTION_NAME'])
// 	$arParams['HIDE_SECTION_NAME'] = 'N';

// $arResult['VIEW_MODE_LIST'] = $arViewModeList;

// if (0 < $arResult['SECTIONS_COUNT'])
// {
// 	if ('LIST' != $arParams['VIEW_MODE'])
// 	{
// 		$boolClear = false;
// 		$arNewSections = array();
// 		foreach ($arResult['SECTIONS'] as &$arOneSection)
// 		{
// 			if (1 < $arOneSection['RELATIVE_DEPTH_LEVEL'])
// 			{
// 				$boolClear = true;
// 				continue;
// 			}
// 			$arNewSections[] = $arOneSection;
// 		}
// 		unset($arOneSection);
// 		if ($boolClear)
// 		{
// 			$arResult['SECTIONS'] = $arNewSections;
// 			$arResult['SECTIONS_COUNT'] = count($arNewSections);
// 		}
// 		unset($arNewSections);
// 	}
// }

// if (0 < $arResult['SECTIONS_COUNT'])
// {
// 	$boolPicture = false;
// 	$boolDescr = false;
// 	$arSelect = array('ID');
// 	$arMap = array();
// 	if ('LINE' == $arParams['VIEW_MODE'] || 'TILE' == $arParams['VIEW_MODE'])
// 	{
// 		reset($arResult['SECTIONS']);
// 		$arCurrent = current($arResult['SECTIONS']);
// 		if (!isset($arCurrent['PICTURE']))
// 		{
// 			$boolPicture = true;
// 			$arSelect[] = 'PICTURE';
// 		}
// 		if ('LINE' == $arParams['VIEW_MODE'] && !array_key_exists('DESCRIPTION', $arCurrent))
// 		{
// 			$boolDescr = true;
// 			$arSelect[] = 'DESCRIPTION';
// 			$arSelect[] = 'DESCRIPTION_TYPE';
// 		}
// 	}
// 	if ($boolPicture || $boolDescr)
// 	{
// 		foreach ($arResult['SECTIONS'] as $key => $arSection)
// 		{
// 			$arMap[$arSection['ID']] = $key;
// 		}
// 		$rsSections = CIBlockSection::GetList(array(), array('ID' => array_keys($arMap)), false, $arSelect);
// 		while ($arSection = $rsSections->GetNext())
// 		{
// 			if (!isset($arMap[$arSection['ID']]))
// 				continue;
// 			$key = $arMap[$arSection['ID']];
// 			if ($boolPicture)
// 			{
// 				$arSection['PICTURE'] = intval($arSection['PICTURE']);
// 				$arSection['PICTURE'] = (0 < $arSection['PICTURE'] ? CFile::GetFileArray($arSection['PICTURE']) : false);
// 				$arResult['SECTIONS'][$key]['PICTURE'] = $arSection['PICTURE'];
// 				$arResult['SECTIONS'][$key]['~PICTURE'] = $arSection['~PICTURE'];
// 			}
// 			if ($boolDescr)
// 			{
// 				$arResult['SECTIONS'][$key]['DESCRIPTION'] = $arSection['DESCRIPTION'];
// 				$arResult['SECTIONS'][$key]['~DESCRIPTION'] = $arSection['~DESCRIPTION'];
// 				$arResult['SECTIONS'][$key]['DESCRIPTION_TYPE'] = $arSection['DESCRIPTION_TYPE'];
// 				$arResult['SECTIONS'][$key]['~DESCRIPTION_TYPE'] = $arSection['~DESCRIPTION_TYPE'];
// 			}

// 		}
// 	}
// }


$monthsList = array(
	"01" => "январь", 
	"02" => "февраль", 
	"03" => "март", 
	"04" => "апрель", 
	"05" => "май", 
	"06" => "июнь", 
	"07" => "июль", 
	"08" => "август", 
	"09" => "сентябрь",
	"10" => "октябрь", 
	"11" => "ноябрь", 
	"12" => "декабрь"
);

foreach ($arResult['SECTIONS'] as $key => $arSection) {

	

	$elementFilter = array(
	    'IBLOCK_ID' =>$arSection['IBLOCK_ID'],
	    'CHECK_PERMISSIONS' => 'Y',
	    'MIN_PERMISSION' => 'R',
	    'INCLUDE_SUBSECTIONS' => 'Y',
	    'ACTIVE' => 'Y',
	    'ACTIVE_DATE' => '',
	    'SECTION_ID'=> $arSection['ID']
	);

	$arResult['SECTIONS'][$key]["ELEMENT_CNT"] = CIBlockElement::GetList(array(), $elementFilter, array());

	if(empty($arResult['SECTIONS'][$key]["ELEMENT_CNT"])){
		unset($arResult['SECTIONS'][$key]);
	}else{

		if($arSection['DEPTH_LEVEL'] == 4){
			// 	if($arSection["ELEMENT_CNT"]>0){
			// 		$arResult['SECTIONS'][$key]['NAME'] = $monthsList[$arSection['NAME']];
			// 		$arResult['SECTIONS'][$key]['ELEMENTS'] = documentList($arSection["IBLOCK_ID"], $arSection['ID']);
			// 	}else{
			// 		unset($arResult['SECTIONS'][$key]);
			// 	}
			$arResult['SECTIONS'][$key]['NAME'] = $monthsList[$arSection['NAME']];
		}
		if($arSection['DEPTH_LEVEL'] == 5){
			// if($arSection["ELEMENT_CNT"]>0){
			// 	$arResult['SECTIONS'][$key]['ELEMENTS'] = documentList($arSection["IBLOCK_ID"], $arSection['ID']);
			// }else{
			// 	unset($arResult['SECTIONS'][$key]);
			// }
			$arResult['SECTIONS'][$key]['ELEMENTS'] = documentList($arSection["IBLOCK_ID"], $arSection['ID']);
		}
	}
}




foreach ($arResult['SECTIONS'] as $key => $arSection) {
	// pr($arSection["ELEMENT_CNT"]);
	// if($arSection['DEPTH_LEVEL'] == 2){
	// 	pr($arSection);
	// }

	// if($arSection['ID'] == 340){
	// if($arSection['ID'] == 327){
		// pr($arSection);
		// pr($arResult['SECTIONS'][$key]["ELEMENT_CNT"]);
		// pr($arSection["IBLOCK_SECTION_ID"]);
	// }
}

?>