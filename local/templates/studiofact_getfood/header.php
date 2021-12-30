<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);
CUtil::InitJSCore();
CJSCore::Init(array("fx", "currency"));
$curPage = $APPLICATION->GetCurPage(true);
use Bitrix\Main\Page\Asset;
global $ccModule;
$ccModule = (\Bitrix\Main\Loader::includeModule("citfact.getfood"));
$config = new \Citfact\Getfood\Configurator();
if (!isset($_SESSION['basket_hide'])) { 
	$_SESSION['basket_hide'] = 0; 
}
if (!isset($_SESSION['mobile_basket_hide'])) { 
	$_SESSION['mobile_basket_hide'] = 1; 
}

global $USER;
if ($USER->IsAuthorized()) { 
	$arUser = getUserData($USER->GetID());
}


?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID;?>" lang="<?=LANGUAGE_ID;?>">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET;?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
		<!-- <meta name="viewport" content="width = device-width, initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, target-densitydpi = device-dpi"> -->
		<meta name="viewport" content="width = device-width, initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no">
		<meta name="format-detection" content="telephone=no">
		<meta http-equiv="cleartype" content="on">
		<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_DIR;?>favicon.ico">
		<!-- saved from url=(0014)about:internet -->
		<title><?$APPLICATION->ShowTitle();?></title>
        <?
        $APPLICATION->ShowHead();  
        ?>        
        <? 
  		// $APPLICATION->ShowMeta("keywords")   // Вывод мета тега keywords 
		// $APPLICATION->ShowMeta("description") // Вывод мета тега description  
		// $APPLICATION->ShowCSS(); // Подключение основных файлов стилей template_styles.css и styles.css 
		// $APPLICATION->ShowHeadStrings() // Отображает специальные стили, JavaScript 
		// $APPLICATION->ShowHeadScripts() // Вывода служебных скриптов 
		?>        
        <?Citfact\Getfood\Htmlhelper\CCitfactCss::showCss()?>
        <?
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery-1.8.2.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/modernizr.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.easing.1.3.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/bootstrap.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/owl.carousel2.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/fancybox/jquery.fancybox3.min.js"); 
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.scrollbar/jquery.scrollbar.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.jscrollpane.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.mousewheel.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.flexslider-min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.mask.min.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.cookie.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.lazyload.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/script.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/jquery.tabslideout.v1.2.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/tabs.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/spectrum.js");
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/main_gravicom.js");

 Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/scripts_by_ko.js");
        ?>
		<?$color = COption::GetOptionString("main", "sf_template_color", "orange");?>
		<!--[if lt IE 9]>
			<script type='text/javascript' src="<?=SITE_TEMPLATE_PATH;?>/js/html5.js"></script>
			<script type='text/javascript' src="<?=SITE_TEMPLATE_PATH;?>/js/css3-mediaqueries.js"></script>
		<![endif]-->
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/fonts/awesome.css");?>
		<?$sf_solution = COption::GetOptionString("main", "sf_solution", "");
		if (strlen($sf_solution) > 0) {
			$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/".$sf_solution.".css");
		}  ?>		
		<!-- !!! -->
		<?$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/base.css");?>
        <?Citfact\Getfood\Htmlhelper\CCitfactCss::setThemeColor()?>
        <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH;?>/form.css">
        <link rel="stylesheet" href="/local/templates/.default/css/custom.css">
    </head>
	<?
    if(!function_exists('bclass')){
        function bclass() {
	    	global $APPLICATION;
			$page = $APPLICATION->GetCurPage();
			switch ($page) {
				case SITE_DIR . 'index.php' :
				case SITE_DIR :
					return ' home';
				case SITE_DIR . 'personal/order/make/' :
				case SITE_DIR . 'personal/order/make/index.php' :
                    return ' cart wide-page';
				case SITE_DIR . 'personal/cart/' :
				case SITE_DIR . 'personal/cart/index.php' :
					return ' cart';
				default :
					return ' not-home';
			}
	    }
    }
	?>
	<body  class="<?echo bclass();?> Font<?=($ccModule ? CGetfood::getOption("FONT_FAMILY") : '')?>" itemscope itemtype="http://schema.org/LocalBusiness">
		<span itemprop="name" style="display: none"><?=COption::GetOptionString('citfact.getfood', 'SITE_NAME')?></span>
        <? if(file_exists(__DIR__ . "/options.php")) require_once __DIR__ . "/options.php"; ?>
		<? if ($_REQUEST["open_popup"] != "Y") { ?>
		<div id="panel"><?$APPLICATION->ShowPanel();?></div>
        <?//$APPLICATION->IncludeComponent("studiofact:configurator", "", array());?>
        <?$APPLICATION->IncludeFile(SITE_DIR."include/svg.php")?>
		<?$catalog_view_mode = $APPLICATION->get_cookie("catalog_view_mode");?>
		<div class="wrapper section--grid-3" data-grid-size="3" id="main_wrapper">
			<header id="header">
				<div class="header_menu header--overflow">
					<div class="container">
						<?if(($USER->IsAuthorized() && $arUser['TYPE'] == 'user') || ($USER->IsAuthorized() && $arUser['TYPE'] == 'employee') || !$USER->IsAuthorized()):?>
							<div class="fl">
								<p class="logo inline" title="<?=GetMessage("STUDIOFACT_MAIN");?>"><a href="/">
									<?$APPLICATION->IncludeComponent(
											"bitrix:main.include", 
											".default", 
											array(
												"AREA_FILE_SHOW" => "file",
												"EDIT_TEMPLATE" => "",
												"PATH" => SITE_DIR."include/header_logo.php",
												"MODE" => "text",
												"COMPONENT_TEMPLATE" => ".default"
											),
											false
									);?>		
								</a></p> 								
							</div>
						<?endif?>
						<div class="fl header-menu">
							<?
							$APPLICATION->IncludeComponent(
								"bitrix:menu",
								"top_menu",
								array(
									"ROOT_MENU_TYPE" => "top", 
									"MENU_CACHE_TYPE" => "Y",
									"MENU_CACHE_TIME" => "36000000",
									"MENU_CACHE_USE_GROUPS" => "Y",
									"MENU_CACHE_GET_VARS" => array(),
									"MAX_LEVEL" => "2",
									"CHILD_MENU_TYPE" => "top_submenu",
									"USE_EXT" => "N",
									"ALLOW_MULTI_SELECT" => "N"
								)
							);
							?>
						</div>
						<div class="fl">
							<div class="socials" >
								<?=GetMessage("STUDIOFACT_SOCIALS");?>	
								<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/header/socials_dinamic.php"), false);?>		
							</div>
						</div>

						<div class="fr user_auth">
							<?//if(($USER->IsAuthorized() && $arUser['TYPE'] == 'user') || ($USER->IsAuthorized() && $arUser['TYPE'] == 'manager') || !$USER->IsAuthorized()):?>
							<?//if($USER->IsAuthorized()):?>
								<?$APPLICATION->IncludeComponent("studiofact:auth", "", array('USER'=>$arUser));?>
								<?//$APPLICATION->IncludeComponent("studiofact:auth", "old", array());?>
							<?//endif?>
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="header">
					<div class="small_auth_box">						
						<?//if($USER->IsAuthorized()):?>
						<?//pr($arUser)?>
						<?$APPLICATION->IncludeComponent("studiofact:auth", "", array('USER'=>$arUser));?>						
					</div>
                    <? $demo = COption::GetOptionString("citfact","demo"); if($demo){ ?><a class="tutorial" href="javascript:;" data-fancybox-callback data-fancybox data-src="#tutorial-sliders">?</a><? } ?>
					<div class="container">
						<div class="fl">
							<span class="inline mobile mobile_menu">
								<span class="mobile_menu__stick"></span>
								<span class="mobile_menu__stick"></span>
								<span class="mobile_menu__stick"></span>
							</span>							

							<div class="mobile_basket_panel">
								<?$begin_date = beginDate();
								$week_date = weekDate($begin_date);
								$lid = getDayWeek();
								$url = getUrl();?>
								<span class="day">
									<a href1="<?=$url?>?day_week=<?=$lid?>"><?=$day = getDayName($lid);?></a>
									<span><?=$week_date[$lid]?></span>
								</span>
								<span class="quant"><?=$nums = basketNumProducts($lid)?></span>
								<span class="cart_heading_wrapper">
								    <span class="summ inline">
								    	<span><?=$sum = sumCurBasket($lid)?></span>
								    	<span class="rub black">Р</span>
								    </span>
								  	<span class="clear"></span>
								</span>
							</div>
							
						</div>						
						
						<div class="fr">
							<div class="search_box fl">
								<?
								$APPLICATION->IncludeComponent(
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
			                	);
			                	?>                	
                			</div>
						</div>
						<div class="clear"></div>
					</div>
					
				</div>
			</header>
				<? if ($APPLICATION->GetCurPage(true) == SITE_DIR."index.php" && ERROR_404 != "Y") { ?>
					<?//$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/main_banner_big.php"), false);?>
				<? } ?>
			<div class="main">
				<?if(CGetfood::getOption("ADVANTAGES") == 'true' ){
					$APPLICATION->IncludeComponent("bitrix:main.include", "", array(
						"AREA_FILE_SHOW" => "file",
							"PATH" => SITE_DIR."include/services.php"
						),
						false,
						array(
						"ACTIVE_COMPONENT" => "N"
						)
					);
				}?>
				<div class="container main_container">
					<div class="mobile_menu_bg"></div>
					<div class="mobile mobile_menu_list_wrapper">
						<div id="mobile_menu_list" class="radius5">
							
							<div class="mobile_menu_list_header">
								<span class="mobile mobile_menu mobile_menu--close" id="mobile_menu_btn">
									<span class="mobile_menu__stick"></span>
									<span class="mobile_menu__stick"></span>
									<span class="mobile_menu__stick"></span>
								</span>
								<span class="mobile_menu_list_header__text">КОРЗИНА</span>
							</div>


							<?$APPLICATION->IncludeFile('/include/basket_fly/basket_fly_accordion_order_mobile.php',array(),array('MODE'=>'html'));?>

							<div class="header menu_search_container">
								<div class="search_box"></div>
							</div>
							<div class="mobile_dop_menu_list">
								<hr />
								<?
								$APPLICATION->IncludeComponent('bitrix:menu', "left_menu", array(
										"ROOT_MENU_TYPE" => "top",
										"MENU_CACHE_TYPE" => "Y",
										"MENU_CACHE_TIME" => "36000000",
										"MENU_CACHE_USE_GROUPS" => "Y",
										"MENU_CACHE_GET_VARS" => array(),
										"MAX_LEVEL" => "1",
										"USE_EXT" => "N",
										"ALLOW_MULTI_SELECT" => "N"
									)
								);
								?>
								<div class="user_auth">
									<?//if ($USER->IsAuthorized()):?>
									<?//$APPLICATION->IncludeComponent("studiofact:auth", "", Array());?>
									<?//endif?>										
								</div>
								<?//$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/dop_left_menu.php"), false);?>
							</div>
						</div>
					</div>
					
					<div class="top_menu_wrapper" style="/*margin: 2em 0;*/">
						<?//if($curPage != "/" && $curPage != "/index.php"):?>						 
							<?include($_SERVER['DOCUMENT_ROOT'].'/include/menu_product_type.php'); ?> 
						<?//endif?>						
					</div>			
					
					<div class="content">
						<div id="main_block_page"> 
							<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", "", array(
									"START_FROM" => "0",
									"PATH" => "",
									"SITE_ID" => "-"
								),
								false,
								Array('HIDE_ICONS' => 'Y')
							);?>
				<? if ($APPLICATION->GetCurPage(true) != SITE_DIR."index.php" && ERROR_404 != "Y") { ?>
							<h1><?=$APPLICATION->ShowTitle(false);?></h1>
					<? } 
				?>
<? } ?>