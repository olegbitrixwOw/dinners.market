<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
 


$arViewModeList = $arResult['VIEW_MODE_LIST'];

$arViewStyles = array(
  'LIST' => array(
    'CONT' => 'bx_sitemap',
    'TITLE' => 'bx_sitemap_title',
    'LIST' => 'bx_sitemap_ul',
  ),
  'LINE' => array(
    'CONT' => 'bx_catalog_line',
    'TITLE' => 'bx_catalog_line_category_title',
    'LIST' => 'bx_catalog_line_ul',
    'EMPTY_IMG' => $this->GetFolder() . '/images/line-empty.png'
  ),
  'TEXT' => array(
    'CONT' => 'bx_catalog_text',
    'TITLE' => 'bx_catalog_text_category_title',
    'LIST' => 'bx_catalog_text_ul'
  ),
  'TILE' => array(
    'CONT' => 'bx_catalog_tile',
    'TITLE' => 'bx_catalog_tile_category_title',
    'LIST' => 'bx_catalog_tile_ul',
    'EMPTY_IMG' => $this->GetFolder() . '/images/tile-empty.png'
  )
);
$arCurView = $arViewStyles[$arParams['VIEW_MODE']];

$strSectionEdit = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_EDIT");
$strSectionDelete = CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "SECTION_DELETE");
$arSectionDeleteParams = array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM'));
// pr($arResult['SECTION']);
// pr($arResult['SECTION']['ID']);
$firm_id = false;
$arFilter = Array('IBLOCK_ID'=>IBLOCK_DOCUMENTS,'ID'=>$arResult['SECTION']['ID'], 'GLOBAL_ACTIVE'=>'Y');
$db_list = CIBlockSection::GetList(array(), $arFilter, false, array("UF_FIRM"));
if($uf_value = $db_list->GetNext()){
    $firm_id = $uf_value["UF_FIRM"];
}
?>

<div class="<? echo $arCurView['CONT']; ?>"><?
  if ('Y' == $arParams['SHOW_PARENT_NAME'] && 0 < $arResult['SECTION']['ID'])
  {
    $this->AddEditAction($arResult['SECTION']['ID'], $arResult['SECTION']['EDIT_LINK'], $strSectionEdit);
    $this->AddDeleteAction($arResult['SECTION']['ID'], $arResult['SECTION']['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

    ?>
        <h1
            class="<? echo $arCurView['TITLE']; ?>"
            id="<? echo $this->GetEditAreaId($arResult['SECTION']['ID']); ?>"
        >

        
        <a href="<? echo $arResult['SECTION']['SECTION_PAGE_URL']; ?>"><?
      $x1=isset($arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]) &&
      $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"] != ""
        ? $arResult['SECTION']["IPROPERTY_VALUES"]["SECTION_PAGE_TITLE"]
        : $arResult['SECTION']['NAME'];

      echo($x1);
      ?></a>

    </h1>
    <?if($arParams['USER'] == 'admin'):?>
    <h2><a href="/firm/documents/make/?FIRM_ID=<? echo $firm_id; ?>" class="add-user">Добавить новый документ</a></h2>
    <?endif;?>

      <?
  }
  if (0 < $arResult["SECTIONS_COUNT"])
  {
    ?>
      <? // начало вывода дерева ?>
        <div id="jstree_div_demo">
        <ul class="root">
       <?
        switch ($arParams['VIEW_MODE'])
        {

        case 'LIST':
        $intCurrentDepth = 1;
        $boolFirst = true;
        foreach ($arResult['SECTIONS'] as &$arSection)
        {

          $this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], $strSectionEdit);
          $this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], $strSectionDelete, $arSectionDeleteParams);

          if ($intCurrentDepth < $arSection['RELATIVE_DEPTH_LEVEL'])
          {
            if (0 < $intCurrentDepth)
              echo "\n", str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']), '<ul>';
          }
          elseif ($intCurrentDepth == $arSection['RELATIVE_DEPTH_LEVEL'])
          {
            if (!$boolFirst)
              echo '</li>';
          }
          else
          {
            while ($intCurrentDepth > $arSection['RELATIVE_DEPTH_LEVEL'])
            {
              echo '</li>', "\n", str_repeat("\t", $intCurrentDepth), '</ul>', "\n", str_repeat("\t", $intCurrentDepth - 1);
              $intCurrentDepth--;
            }
            echo str_repeat("\t", $intCurrentDepth - 1), '</li>';
          }

        echo(!$boolFirst ? "\n" : ''), str_repeat("\t", $arSection['RELATIVE_DEPTH_LEVEL']);
        ?>
              <li id="<?= $this->GetEditAreaId($arSection['ID']); ?>">
                <a target="_blank" href="<? echo $arSection["SECTION_PAGE_URL"]; ?>">
                  <? echo $arSection["NAME"]; ?><?
              if ($arParams["COUNT_ELEMENTS"])
              {
                ?> <span>(<? echo $arSection["ELEMENT_CNT"]; ?>)</span><?
              }
              ?></a>
              <? if(!empty($arSection['ELEMENTS'])):?>
                    <ul>
                     <? foreach ($arSection['ELEMENTS'] as $key => $element):?>
                        <li data-jstree='{"icon":"/vendor/vakata/jstree/images/file.png"}'>
                            <a target="_blank" data-href="<?='/firm/documents/'.$arSection['CODE'].'/'.$element['ID'].'/'?>" class="document">
                              <?=$element['NAME'];?>
                            </a>                     
                        </li>
                        <? // pr($element);?>
                     <? endforeach;?>
                    </ul>
              <? endif;?>
              <?
            $intCurrentDepth = $arSection['RELATIVE_DEPTH_LEVEL'];
            $boolFirst = false;
            }
            unset($arSection);
            while ($intCurrentDepth > 1)
            {
              echo '</li>', "\n", str_repeat("\t", $intCurrentDepth), '</ul>', "\n", str_repeat("\t", $intCurrentDepth - 1);
              $intCurrentDepth--;
            }
            if ($intCurrentDepth > 0)
            {
              echo '</li>', "\n";
            }
            break;
            }
          ?>
          </ul></div>
      <?
      echo('LINE' != $arParams['VIEW_MODE'] ? '<div style="clear: both;"></div>' : '');
    }
  ?></div>
   
<?php
//
// include(__DIR__.'/tree.php'); 

// Yp::bx_head([
//   '/vendor/vakata/jstree/dist/jstree.js',
//   '/vendor/vakata/jstree/dist/themes/default/style.css',
// ]);

