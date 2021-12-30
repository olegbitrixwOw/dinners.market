<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? $this->setFrameMode(true); ?>
<? if (count($arResult) < 1) { return; } 


?>
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
                    <a href="/" class=" a_no_underline" role="button">
                        <span class="icon icon-sushi"></span> <span class="item"><span class="item-name">Все блюда</span></span><span class="caret-icon"></span>
                    </a>
                </li>
                <?$previousLevel = 0;?>
                <?foreach($arResult as $arItem):
                    if($previousLevel && $arItem["DEPTH_LEVEL"] < $previousLevel):
                        echo str_repeat("</ul></li>", ($previousLevel - $arItem["DEPTH_LEVEL"]));
                    endif;?>
                <?if($arItem["IS_PARENT"]):?>
                    <li class="dropdown ">
                        <a href="<?=$arItem["LINK"];?>" class="dropdown-toggle a_no_underline" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="icon <?=$arItem['CLASS_CSS']?>"></span> 
                            <span class="item"><span class="item-name"><?=$arItem["TEXT"];?></span></span>
                            <span class="caret-icon"></span>
                        </a>
                        <ul class="dropdown-menu">
                <?else:?>
                        <li class=" ">
                            <a href="<?=$arItem["LINK"];?>" class=" a_no_underline" role="button">
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
        </div>

    </div>
</nav>