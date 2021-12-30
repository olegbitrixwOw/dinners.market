<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$this->setFrameMode(true);
?>

<div class="configurator" id="configurator" data-switcher>
    <div class="switch" data-switch></div>
    <form method="POST" name="style-switcher" class="form">
        <div class="configurator_header"></div>
        <? if(count($arResult["PROPS"])) { ?>
            <div class="left-block" id="left_block">
                <?
                foreach($arResult["PROPS"] as $k => $prop) {?>
                    <div class="section-block" id="section_<?=$k?>" data-tab-btn="<?=$k?>"><?=$prop["NAME"]?></div><?
                } ?>
            </div>
            <div class="right-block" >
                <div class="content-body" id="right_block_content">
                    <?
                    foreach($arResult["PROPS"] as $k => $props) { ?>
                        <div class="block-item" id="block_item_<?=$k?>" data-tab-body="<?=$k?>">
                            <?foreach($props["PROPS"] as $k1 => $prop) { ?>
                                <div class="item item_<?=$prop["TYPE"]?>">
                                    <div class="title"><?=$prop["NAME"]?></div>
                                    <div class="values <?=$prop["CODE"]?>"
                                         data-property="<?=$prop["PROPERTY_NAME"]?>"
                                         data-page-url="<?=$prop["PAGE_URL"]?>"
                                         data-property-type="<?=$prop["TYPE"]?>"
                                    >
                                        <?foreach($prop["VALUES"] as $k2 => $val) { ?>
                                            <?
                                            $active = "";
                                            if(
                                                    isset($arResult["siteOption"][$prop["PROPERTY_NAME"]]) &&
                                                    ($val["VALUE"] == $arResult["siteOption"][$prop["PROPERTY_NAME"]])
                                            ){
                                                $active = "active";
                                            }
                                            ?>
                                            <a href="#" data-value="<?=$val["VALUE"]?>" class="<?=$active?>">
                                                <? if($prop["TYPE"] == "color_radio") { ?>
                                                    <span style="background-color: <?=$val["VALUE"]?>"></span>
                                                <? } else { ?>
                                                    <div class="group__title" <?if(isset($val["IMG"])){?> style="background-image: url('<?=$val["IMG"]?>')"<?}?>><?=$val["NAME"]?></div>
                                                <? } ?>
                                            </a>
                                        <? } ?>
                                        <? if($prop["TYPE"] == "color_radio") { ?>
                                            <a href="#" class="picker" id="picker"><span></span></a>
                                        <? } ?>
                                    </div>
                                </div>
                            <? } ?>
                        </div>
                        <?
                    } ?>

                </div>
                <div class="values" data-property="default" data-property-type="true" data-page-url="/">
                    <div class="header-inner"  data-value="default_value" >
                        <?=GetMessage("CONFIGURATOR_DEFAULT_SETTINGS")?>
                    </div>
                </div>
            </div>

        <? } ?>
    </form>
</div>
<script>

    if(localStorage.getItem('tab')) {
        var configurator = document.getElementById("configurator");
        configurator.classList.add("active");
    }

    if(localStorage.getItem('tab')) {
        var tab = document.getElementById("section_"+localStorage.getItem('tab'));
        if (tab.length!==0){
            tab.classList.add('active');
        }
    } else {
        var container_left = document.getElementById("left_block");
        try {
            container_left.childNodes.forEach(
                function(a){
                    if (!a.data){
                        a.classList.add("active");
                        non_existent_stop_function();
                    }
                } )
        }catch (err) {}
    }
    if(localStorage.getItem('tab')) {
        var tab = document.getElementById("block_item_"+localStorage.getItem('tab'));
        if (tab.length!==0){
            tab.classList.add('active');
        }
    } else {
        var container_right = document.getElementById("right_block_content");
        try {
            container_right.childNodes.forEach(
                function(a){
                    if (!a.data){
                        a.classList.add("active");
                        non_existent_stop_function();
                    }
                } )
        }catch (err) {}
    }
    localStorage.removeItem('tab');
</script>
<script>
    // !!!
    //$(function(){
       // $('#right_block_content').jScrollPane();
    //  });

    // $("#picker").spectrum({
    //     preferredFormat: "hex",
    //     showInput: true,
    //     chooseText: "<?=GetMessage('CONFIGURATOR_SELECT')?>",
    //     cancelText: "<?=GetMessage('CONFIGURATOR_CANCEL')?>",
    //     move: function(color) {
    //         $('.colors a.active').removeClass('active');
    //         $("#picker").addClass('active');
    //         change_color(color.toHexString())
    //     },
    //     change: function(color) {
    //         $('.colors a.active').removeClass('active');
    //         $("#picker").addClass('active');
    //         change_color(color.toHexString())
    //     }
    // });
    function change_color(color){
        $.ajax({
            type: "POST",
            method: "POST",
            dataType: 'json',
            url: "<?=$this->__component->__template->__folder?>/ajax.php",
            data: {
                action: "setColor",
                option: "MAIN_COLOR",
                value: color
            }
        })
        .done(function( msg ) {
            if(!msg.error){
                $('head style.custom').remove();
                $('head').append('<style class="custom">'+msg.result+'</style>');
            }
        });
        return false;
    }

    $(".configurator [data-value]").on('click',function () {
        var tab_code=$(this).parents('.block-item').data("tab-body");
        if(tab_code != undefined){
            localStorage.setItem('tab', tab_code);
        }
        if($(this).hasClass('active')){
            var old_value='active'
        } else {
            var old_value=''
        }
        $(this).parents('.values').find(".active").removeClass("active");
        $(this).addClass("active");

        var data_value = $(this).data('value');
        var data_page_url = $(this).parents('.values').data('page-url');
        switch($(this).parents('.values').data('property-type')) {
            case 'color_radio':
                change_color($(this).data('value'));
                return false;

            case 'checkbox':
                if(old_value=='active'){
                    data_value = false
                }

                break;

            default: break;
        }
        $.ajax({
            type: "POST",
            method: "POST",
            dataType: 'json',
            url: "<?=$this->__component->__template->__folder?>/ajax.php",
            data: {
                action: "saveOption",
                option: $(this).parents('.values').data('property'),
                value: data_value
            }
        })
        .done(function( msg ) {
                location.href=data_page_url;
        });
        return false;
    });
</script>