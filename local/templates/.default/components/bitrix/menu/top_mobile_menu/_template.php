<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? if (count($arResult) < 1) { return; } 
    global $USER;
    $arUser = getUserData($USER->GetID());
?>
<ul class="uldepth_level_0"><?
    $previousLevel = 0;
    foreach($arResult as $arItem):
        // var_dump($arItem["LINK"]);
        if($arUser['TYPE'] != 'manager' && $arUser['TYPE'] != 'employee'){  
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
                ?><li class="depth_level_<?=$arItem["DEPTH_LEVEL"];?><? if ($arItem["SELECTED"]) { echo ' selected active'; } ?>">
                    <div class="divdeph_level_<?=$arItem["DEPTH_LEVEL"];?> no_child">
                        <a href="<?=$arItem["LINK"];?>" class="<?if(strpos($_SERVER["REQUEST_URI"], $arItem["LINK"]) !== false):?>active_item <?endif;?>depth_level_<?=$arItem["DEPTH_LEVEL"];?><? if ($arItem["SELECTED"]) { echo ' selected'; } ?>" style="">
                            <?=$arItem["TEXT"];?>
                        </a>
                        <div class="week-days">
         <!--                    <span><a href="<?=$arItem["LINK"];?>?day_week=Mo">Пн</a></span> 
                            <span><a href="<?=$arItem["LINK"];?>?day_week=Tu">Вт</a></span> 
                            <span><a href="<?=$arItem["LINK"];?>?day_week=We">Ср</a></span> 
                            <span><a href="<?=$arItem["LINK"];?>?day_week=Th">Чт</a></span> 
                            <span><a href="<?=$arItem["LINK"];?>?day_week=Fr">Пт</a></span> -->
                            <span><a href="<?=$arItem["LINK"];?>?day_week=Mo">Понедельник</a></span> 
                            <span><a href="<?=$arItem["LINK"];?>?day_week=Tu">Вторник</a></span> 
                            <span><a href="<?=$arItem["LINK"];?>?day_week=We">Среда</a></span> 
                            <span><a href="<?=$arItem["LINK"];?>?day_week=Th">Четверг</a></span> 
                            <span><a href="<?=$arItem["LINK"];?>?day_week=Fr">Пятница</a></span>
                        </div>
                    </div> 
                </li><?
            endif;
            $previousLevel = $arItem["DEPTH_LEVEL"];
            endforeach;?>
            <? if ($previousLevel > 1) {
                echo str_repeat("</ul></li>", ($previousLevel-1) );
            } ?>
        </ul>
