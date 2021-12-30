<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$this->setFrameMode(true);
extract($arResult);
?>
<div class="catalog_sort clearfix">
    <div class="sort_btn_wrap clearfix">
        <?foreach ($arResult['VARIANTS'] as $code => $arSort){
            $order = $arSort["sort"][0]["ORDER"];?>
            <a class="<?if($arSort['SELECTED']) echo "active ". $order; if ($order !== "asc") echo " desc";?> "
               href="<?=$APPLICATION->GetCurPageParam("sort=".$code."&order=".$order, array("sort", "order"), false)?>"
               title="<?=$arSort['title']?>"
            >
                <?=$arSort['title']?>
                <i class="arr icons_fa"></i>
            </a>
        <?}?>
    </div>
    <?$order_by = $order_by . ",nulls"?>
    <div class="catalog-sorting clearfix">
        <?if(CGetfood::getOption("TILE") == 'true'){?>
            <button class="catalog-sorting__button catalog-sorting__grid"></button>
        <?}?>
        <?if(CGetfood::getOption("LIST") == 'true'){?>
            <button class="catalog-sorting__button catalog-sorting__list"></button>
        <?}?>
        <?if(CGetfood::getOption("LIST2") == 'true'){?>
            <button class="catalog-sorting__button catalog-sorting__list-info"></button>
        <?}?>
    </div>
</div>
<script>
    $(".catalog-sorting__button.catalog-sorting__button--active").removeClass("catalog-sorting__button--active");
    if (document.cookie.indexOf("BITRIX_SM_catalog_view_mode=section--list%20section--list-small") >= 0){
        $("#main_wrapper").attr("class", "wrapper section--list section--list-small");
        $(".catalog-sorting__list-info").addClass("catalog-sorting__button--active");
    } else if (document.cookie.indexOf("BITRIX_SM_catalog_view_mode=section--list%20section--list-info") >= 0){
        $("#main_wrapper").attr("class", "wrapper section--list section--list-info");
        $(".catalog-sorting__list").addClass("catalog-sorting__button--active");
    } else {
        $("#main_wrapper").attr("class", "wrapper section--grid-3");
        $(".catalog-sorting__grid").addClass("catalog-sorting__button--active");
    }
</script>