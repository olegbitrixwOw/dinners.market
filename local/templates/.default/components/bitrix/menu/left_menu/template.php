<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? if (count($arResult) < 1) { return; } 
    global $USER;
    $arUser = getUserData($USER->GetID());
?>
<?
// текущая страница: /ru/support/index.php?id=3&s=5
global $APPLICATION;
$dir = $APPLICATION->GetCurDir();
// в $dir будет значение "/ru/support/"

if ($dir == "/personal/order/make/"){
?>
<style>
#left_side{
	display: none !important;
}
</style>
<?}?>
<ul class="uldepth_level_0"><? 
    $previousLevel = 0;
    foreach($arResult as $arItem):

        if($arUser['TYPE'] != 'manager' && $arUser['TYPE'] != 'employee' && $arUser['TYPE'] != 'admin'){  
            if($arItem["LINK"] == '/personal/your-firm-orders/'){
                    continue;
            }
        }
     
        
        if($arUser['TYPE'] != 'manager'){
            if($arItem["LINK"] == '/personal/documents/make/'){
                continue;
            }

            if($arItem["LINK"] == '/personal/documents/'){
                continue;
            }

            if($arItem["LINK"] == '/firm/organization/'){
                continue;
            }

             if($arItem["LINK"] == '/firm/organization/organization_balance.php'){
                continue;
            }

            if($arItem["LINK"] == '/firm/requisites/'){
                continue;
            }
        }

        if($arUser['TYPE'] != 'admin'){
            if($arItem["LINK"] == '/firm/organizations/'){
                continue;
            }

            if($arItem["LINK"] == '/firm/applications/'){
                continue;
            } 
        }

        if($arUser['TYPE'] != 'manager' && $arUser['TYPE'] != 'admin'){
            if($arItem["LINK"] == '/firm/orders/'){
                continue;
            }
        }        
        

        if($arParams['CUISINE']){
            if (strpos($arItem['LINK'], 'news')){           
                $arItem['LINK'] = $arItem['LINK'].$arParams['CUISINE'].'/';     
            }

            if (strpos($arItem['LINK'], 'contacts')){           
                $arItem['LINK'] = $arItem['LINK'].'?cuisine='.$arParams['CUISINE'];     
            }
        }   

    if ($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):
        echo str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));
    endif;
    if ($arItem["IS_PARENT"]):
    ?><li class="depth_level_<?=$arItem["DEPTH_LEVEL"];?><? if ($arItem["SELECTED"]) { echo ' selected active'; } ?>"><span class="mobile_menu_button"><i></i>
            <div class="divdeph_level_<?=$arItem["DEPTH_LEVEL"];?>"><a href="<?=$arItem["LINK"];?>" class="<?if(strpos($_SERVER["REQUEST_URI"], $arItem["LINK"]) !== false):?>active_item <?endif;?>depth_level_<?=$arItem["DEPTH_LEVEL"];?><? if ($arItem["SELECTED"]) { echo ' selected'; } ?>"><?=$arItem["TEXT"];?></a></div>
            <span class="icon span_depth_level_<?=$arItem["DEPTH_LEVEL"];?>"></span>
            </span>
        <ul class="uldepth_level_<?=$arItem["DEPTH_LEVEL"];?>"><?
            else:
                ?><li class="depth_level_<?=$arItem["DEPTH_LEVEL"];?><? if ($arItem["SELECTED"]) { echo ' selected active'; } ?>"><div class="divdeph_level_<?=$arItem["DEPTH_LEVEL"];?> no_child"><a href="<?=$arItem["LINK"];?>" class="<?if(strpos($_SERVER["REQUEST_URI"], $arItem["LINK"]) !== false):?>active_item <?endif;?>depth_level_<?=$arItem["DEPTH_LEVEL"];?><? if ($arItem["SELECTED"]) { echo ' selected'; } ?>"><?=$arItem["TEXT"];?></a></div></li><?
            endif;
            $previousLevel = $arItem["DEPTH_LEVEL"];
            endforeach;?>
            <? if ($previousLevel > 1) {
                echo str_repeat("</ul></li>", ($previousLevel-1) );
            } ?>
        </ul>
