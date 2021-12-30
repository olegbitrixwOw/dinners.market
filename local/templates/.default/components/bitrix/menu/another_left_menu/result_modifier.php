<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$bWasSelected = false;
$selectedItem = 0;
$depth = 1;

foreach ($arResult as $i => $arMenu) {
	if ($arMenu['SELECTED'] == true) {
		$bWasSelected = true;
		$selectedItem = $i;
		$depth = $arMenu["DEPTH_LEVEL"];
		break;
	}
}
if ($bWasSelected) {
	for($i=$selectedItem; $i >= 0; $i--){
		if(isset($arResult[$i]) && $arResult[$i]["DEPTH_LEVEL"] < $depth){
			$depth--;
			$arResult[$i]["SELECTED"] = true;
		}
	}
}
$arSectionsInfo = array();
$arFilter = array(
  "TYPE" => "catalog",
  "SITE_ID" => SITE_ID,
  "ACTIVE" => "Y"
);
$dbIBlock = CIBlock::GetList(array('SORT' => 'ASC', 'ID' => 'ASC'), $arFilter);
$dbIBlock = new CIBlockResult($dbIBlock);
if ($arIBlock = $dbIBlock->GetNext()) {
  $dbSections = CIBlockSection::GetList(array(), array("IBLOCK_ID" => $arIBlock["ID"]), false, array("SECTION_PAGE_URL", "UF_MENU_ICON"));
  while($arSections = $dbSections->GetNext())
  {
    $pictureSrcIcon = CFile::GetFileArray($arSections["UF_MENU_ICON"]);
    if ($pictureSrcIcon) {
      $arPictureSrcIcon = CFile::ResizeImageGet(
        $arSections["UF_MENU_ICON"],
        array("width" => 40, 'height' => 40),
        BX_RESIZE_IMAGE_PROPORTIONAL,
        true
      );
      $arSectionsInfo[crc32($arSections["SECTION_PAGE_URL"])]["UF_MENU_ICON"] = $arPictureSrcIcon["src"];
    }
  }
}
foreach($arResult as &$arItem) {
  $arItem["PARAMS"]["item_id"] = crc32($arItem["LINK"]);
  $arItem["PARAMS"]["picture_src"] = $arSectionsInfo[$arItem["PARAMS"]["item_id"]]["UF_MENU_ICON"];
}