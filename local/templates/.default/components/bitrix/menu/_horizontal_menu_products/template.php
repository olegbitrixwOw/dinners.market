<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? if (count($arResult) < 1) { return; } ?>

<p title="<?=GetMessage("STUDIOFACT_MAIN");?>">
    <a href="/">
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include", "", 
        array(
        "AREA_FILE_SHOW" => "file", 
        "PATH" => SITE_DIR."include/header_menu_logo.php"
        ), 
        false
    );?>    
    </a></p> 


<nav class="navbar navbar-default navbar-ovsinka affix-top" role="navigation">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
        <div class="-collapse navbar-collapse" id="navbar">
            <ul class="nav navbar-nav text-uppercase" id="nav_overflow">
                <li class=" ">
                    <a href="/" class=" a_no_underline <?if($arResult["ALL_DISHES"]):?>selected<?endif?>" role="button">
                        <span class="icon icon-all-dishes"></span> 
                        <span class="item"><span class="item-name" style="font-weight: bold; font-size: 10px; text-transform: uppercase;">Все блюда</span></span>
                        <span class="caret-icon"></span>
                    </a>
                </li>
                <?
                if($arResult["ALL_DISHES"]){
                    unset($arResult["ALL_DISHES"]);
                }
                ?>
                <?$previousLevel = 0;?>
                <?foreach($arResult as $arItem):
                    if($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):
                        echo str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));
                    endif;?>
                <?if($arItem["IS_PARENT"]):?>
                    <li class="dropdown ">
                        <a href="<?=$arItem["LINK"];?>" class="dropdown-toggle a_no_underline <?if($arItem["SELECTED"]):?>selected<?endif?> " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="icon <?=$arItem['CLASS_CSS']?>"></span> 
                            <span class="item"><span class="item-name"><?=$arItem["TEXT"];?></span></span>
                            <span class="caret-icon"></span>
                        </a>
                        <ul class="dropdown-menu">
                <?else:?>
                        <li class=" ">
                            <a href="<?=$arItem["LINK"];?>" class="a_no_underline <?if($arItem["SELECTED"]):?>selected<?endif?>" role="button">
                                <span class="icon <?=$arItem['CLASS_CSS']?>"></span>
                                <span class="item"><span class="item-name"><?=$arItem["TEXT"];?></span></span>
                                </span><span class="caret-icon"></span>
                            </a>
                        </li>
                <?endif;?>
                        <?$previousLevel = $arItem["DEPTH_LEVEL"];?>
                <?endforeach;?>
                <? if ($previousLevel > 1) {
                    echo str_repeat("</ul></li>", ($previousLevel-1) );
                } ?>
            </ul>
            <div class="search_box fl">
                <?if(!isset($_REQUEST['q'])):?>
                <?$APPLICATION->IncludeComponent(
                    "studiofact:search.title", 
                    "visual", 
                    array(
                        "NUM_CATEGORIES" => "1",
                        "TOP_COUNT" => "5",
                        "CHECK_DATES" => "N",
                        "SHOW_OTHERS" => "N",
                        "PAGE" => SITE_DIR."catalog/",
                        "CATEGORY_0_TITLE" => GetMessage("SEARCH_GOODS"),
                        "CATEGORY_0" => array(
                            0 => "iblock_catalog",
                            1 => "iblock_offers",
                        ),
                        "CATEGORY_0_iblock_catalog" => array(
                            0 => "all",
                        ),
                        "CATEGORY_OTHERS_TITLE" => GetMessage("SEARCH_OTHER"),
                        "SHOW_INPUT" => "Y",
                        "INPUT_ID" => "title-search-input",
                        "CONTAINER_ID" => "search",
                        "PRICE_CODE" => array(
                            0 => "BASE",
                        ),
                        "SHOW_PREVIEW" => "Y",
                        "PREVIEW_WIDTH" => "75",
                        "PREVIEW_HEIGHT" => "75",
                        "CONVERT_CURRENCY" => "Y",
                        "COMPONENT_TEMPLATE" => "visual",
                        "ORDER" => "date",
                        "USE_LANGUAGE_GUESS" => "Y",
                        "PRICE_VAT_INCLUDE" => "Y",
                        "PREVIEW_TRUNCATE_LEN" => "",
                        "CURRENCY_ID" => "RUB",
                        "CATEGORY_0_iblock_offers" => array(
                            0 => "all",
                        ),
                        "CATEGORY_1_TITLE" => ""
                    ),
                    false
                );?>
                <?endif;?>
            </div>
        </div>        

    </div>
</nav>
