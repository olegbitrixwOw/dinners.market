<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__); ?>
		<?if ($_REQUEST["open_popup"] != "Y") {?>
						</div>
					</div>


							<?if($showLeft):?>
								<div class="left_side_container<?echo bclass();?>">
								<div class="sidebar-filter"></div>
								<!--sidebar-menu--right-->
								<!--sidebar-menu--down-->
								<div id="left_side" class="sidebar-menu sidebar-menu--down">
									<?
									$APPLICATION->IncludeComponent(
										"bitrix:menu", 
										"left_menu", 
										array(
											"ROOT_MENU_TYPE" => "left",
											"MENU_CACHE_TYPE" => "A",
											"MENU_CACHE_TIME" => "36000000",
											"MENU_CACHE_USE_GROUPS" => "Y",
											"MENU_THEME" => "site",
											"CACHE_SELECTED_ITEMS" => "Y",
											"MENU_CACHE_GET_VARS" => array(
											),
											"MAX_LEVEL" => "4",
											"CHILD_MENU_TYPE" => "left",
											"USE_EXT" => "N",
											"DELAY" => "N",
											"ALLOW_MULTI_SELECT" => "N",
											"COMPONENT_TEMPLATE" => "left_menu"
										),
										false
									);
									?>
								</div>
								</div>
							<?endif;?>
					<div style="clear:both"></div>
					<footer>
						<div class="box padding">
							<div class="row">
								<div class="col-lg-7 col-md-7 col-sm-7 footer-left-col">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/footer_text.php"), false);?>
								</div>
								<div class="col-lg-5 col-md-5 col-sm-5 footer-right-col">
									<?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/footer_text2.php"), false);?>
								</div> 
							</div>							
						</div>					
                    </footer>
					<script type="text/javascript">
						$(function() {
							$(window).scroll(function() {
								if($(this).scrollTop() != 0) {
									$('.scroll-to-top').fadeIn();
								} else {
									$('.scroll-to-top').fadeOut();
								}
							});
							$('#topNubex').click(function() {
								$('body,html').animate({scrollTop:0},700);
							});
						});
					</script>
					<?if (CGetfood::getOption("UP_DISPLAY")== 'true'){ ?>
        				<a class="scroll-to-top" id="<?=($ccModule ? CGetfood::getOption("UP_FORM") : 'scroll-2')?>-<?=($ccModule ? CGetfood::getOption("UP_LOCATION") : 'right')?>" href="#"></a>
        			<?}?>
            </div>
            </div>
		</div>
		<? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/feedback_form.php"), false); ?>

        <? $APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/metrics.php"), false); ?>
		<? } else { ?>
			<script type="text/javascript">$(document).ready(function () { $("#bx-composite-banner").remove(); });
</script>
		<? } ?>
		<div id="sfp_add_to_basket_head" style="display: none;"><?=GetMessage("SFP_ADD_TO_BASKET_HEAD");
?></div>
		<div id="sfp_show_offers_head" style="display: none;"><?=GetMessage("SFP_SHOW_OFFERS_HEAD");
?></div>
        <div class="success_fast_order" style="display: none;"><?=GetMessage("SUCCESS_FAST_ORDER");?></div>
		<div style="display:none" id="oneClickModal">
			<div class="order_by_click">
				<div class="popup_head"><?=GetMessage("SF_SMALL_BUY_ONE_CLICK");?></div>
				<div class="feedback_form_prop_line">
					<label for="SMALL_BASKET_ORDER_PHONE"><?=GetMessage("SF_SMALL_BUY_LABEL");?></label>
					<input type="tel" class="" name="SMALL_BASKET_ORDER_PHONE" id="SMALL_BASKET_ORDER_PHONE" value="" placeholder="">
				</div>
				<div class="user-agree-checkbox">
                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/agreement/agreement_one_click.php"), false);?>
				</div>
			    <a href="javascript: void(0);" class="button small_basket_hover_buy_go inline" id="small_basket_hover_buy_go">
                    <?=GetMessage("SF_SMALL_BUY_GO");?>
                </a>
		    </div>
		</div>
		<div style="display:none" id="buyOneProductModal">
			<div class="order_by_click">
				<div class="popup_head"><?=GetMessage("SF_SMALL_BUY_ONE_CLICK");?></div>
				<div class="feedback_form_prop_line">
					<label for="SMALL_BASKET_ORDER_PHONE"><?=GetMessage("SF_SMALL_BUY_LABEL");?></label>
					<input type="tel" class="" name="SMALL_BASKET_ORDER_PHONE" id="SMALL_BASKET_ORDER_PHONE" value="" placeholder="">
				</div>
				<div class="user-agree-checkbox">
                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/agreement/agreement_one_click_modal.php"), false);?>
				</div>
				<a href="javascript: void(0);" class="button buy_one_click_product inline" id="buy_one_click_product">
					<?=GetMessage("SF_SMALL_BUY_GO");?>
				</a>
			</div>
		</div>
		<div style="display:none" id="allBuyModal">
			<div class="order_by_click">
				<div class="popup_head"><?=GetMessage("SF_SMALL_BUY_ALL");?></div>

				<div class="user-agree-checkbox">
                    <?$APPLICATION->IncludeComponent("bitrix:main.include", "", array("AREA_FILE_SHOW" => "file", "PATH" => SITE_DIR."include/agreement/agreement_one_click.php"), false);?>
				</div>
				
			    <a href="javascript: void(0);" class="button buy_all_baskets inline" id="buy_all_baskets">
                    <?=GetMessage("SF_SMALL_BUY_GO");?>
                </a>
		    </div>
		</div>
		<div style="display:none" id="bannedModal">
			<div class="order_by_click">
				<div class="popup_head">Вы не можите сделать заказ после 10 утра</div>
		    </div>
		</div>
	</body>
</html>
