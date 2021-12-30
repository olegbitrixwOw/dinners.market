<?

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Page\Asset;

// Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/script.js");
// Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/style.css");

Asset::getInstance()->addJs("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/script.js");
Asset::getInstance()->addJs("$templateFolder/js/moment-with-locales.min.js");
Asset::getInstance()->addJs("$templateFolder/js/bootstrap-datetimepicker.min.js");

Asset::getInstance()->addCss("/bitrix/components/bitrix/sale.order.payment.change/templates/.default/style.css");
Asset::getInstance()->addCss("$templateFolder/css/style.css");
Asset::getInstance()->addCss("$templateFolder/css/bootstrap-datetimepicker.min.css");

CJSCore::Init(array('clipboard'));
Loc::loadMessages(__FILE__);

$APPLICATION->SetTitle("Заказы сотрудников организации");

if (!empty($arResult['ERRORS']['FATAL']))
{
	foreach($arResult['ERRORS']['FATAL'] as $error)
	{
		ShowError($error);
	}
	$component = $this->__component;
	if ($arParams['AUTH_FORM_IN_TEMPLATE'] && isset($arResult['ERRORS']['FATAL'][$component::E_NOT_AUTHORIZED]))
	{
		$APPLICATION->AuthForm('', false, false, 'N', false);
	}

}
else
{?>	
<?
// pr($arParams["NAVYPARAMS"]);
// pr($arParams["PAGE"]);
// pr($arParams["DATE"]);
?>
	<?if($arParams["AJAX"] !== 'Y'):?>	

		<div class="form-group">
	        <div class="input-group date" id="datetimepicker">
	          <input type="text" class="form-control" />
	          <span class="input-group-addon">
	          <span class="glyphicon-calendar glyphicon"></span>
	          </span>
	        </div>
	    </div> 
    	<div class="ajax_orders">

    <?endif?>	

    <?
    // pr($arResult['ORDERS']);
    
	if (!empty($arResult['ERRORS']['NONFATAL']))
	{
		foreach($arResult['ERRORS']['NONFATAL'] as $error)
		{
			ShowError($error);
		}
	}
	if (!count($arResult['ORDERS']))
	{
		if ($_REQUEST["filter_history"] == 'Y')
		{
			if ($_REQUEST["show_canceled"] == 'Y')
			{
				?>
				<h3><?= Loc::getMessage('SPOL_TPL_EMPTY_CANCELED_ORDER')?></h3>
				<?
			}
			else
			{
				?>
				<h3><?= Loc::getMessage('SPOL_TPL_EMPTY_HISTORY_ORDER_LIST')?></h3>
				<?
			}
		}
		else
		{
			?>
			<h3><?= Loc::getMessage('SPOL_TPL_EMPTY_ORDER_LIST')?></h3>
			<?
		}
	}
	?>

		<div class="row col-md-12 col-sm-12">
			<?
			$nothing = !isset($_REQUEST["filter_history"]) && !isset($_REQUEST["show_all"]);
			$clearFromLink = array("filter_history","filter_status","show_all", "show_canceled");

			if ($nothing || $_REQUEST["filter_history"] == 'N')
			{
				?>
				<!-- <a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y", $clearFromLink, false)?>">
					<?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_HISTORY")?>
				</a> -->
				<?
			}
			if ($_REQUEST["filter_history"] == 'Y')
			{
				?>
				<a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("", $clearFromLink, false)?>">
					<?echo Loc::getMessage("SPOL_TPL_CUR_ORDERS")?>
				</a>
				<?
				if ($_REQUEST["show_canceled"] == 'Y')
				{
					?>
					<a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y", $clearFromLink, false)?>">
						<?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_HISTORY")?>
					</a>
					<?
				}
				else
				{
					?>
					<a class="sale-order-history-link" href="<?=$APPLICATION->GetCurPageParam("filter_history=Y&show_canceled=Y", $clearFromLink, false)?>">
						<?echo Loc::getMessage("SPOL_TPL_VIEW_ORDERS_CANCELED")?>
					</a>
					<?
				}
			}
			?>
		</div>

	<?
	if (!count($arResult['ORDERS']))
	{
		?>
		<!-- <div class="row col-md-12 col-sm-12">
			<a href="<?=htmlspecialcharsbx($arParams['PATH_TO_CATALOG'])?>" class="sale-order-history-link">
				<?=Loc::getMessage('SPOL_TPL_LINK_TO_CATALOG')?>
			</a>
		</div> -->
		<?
	}

	if ($_REQUEST["filter_history"] !== 'Y')
	{
		$paymentChangeData = array();
		$orderHeaderStatus = null;
		?>
		
		

		<?
		foreach ($arResult['ORDERS'] as $key => $order)
		{


			if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'])
			{
				$orderHeaderStatus = $order['ORDER']['STATUS_ID'];

				?>
				<h1 class="sale-order-title"><?= Loc::getMessage('SPOL_TPL_ORDER_IN_STATUSES') ?> &laquo;<?=htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME'])?>&raquo;</h1>
				<table id="user-list">
					<tbody>
					<tr>
					    <th>номер заказа</th>
					    <th>покупатель</th>
					    <th>состав заказа</th>
					    <th>адрес доставки</th>
					    <th>подробнее о заказе</th>
					</tr>   
				<?
			}
			?>		
			<tr>
			    <td>
			    	<?=Loc::getMessage('SPOL_TPL_ORDER')?>
			    	<?=Loc::getMessage('SPOL_TPL_NUMBER_SIGN').$order['ORDER']['ACCOUNT_NUMBER']?>
			    	<?=Loc::getMessage('SPOL_TPL_FROM_DATE')?>
			    </td>
			    <td><?=$order["USER"]["NAME"]?> <?=$order["USER"]["LAST_NAME"]?> <?=$order["USER"]["SECOND_NAME"]?></th>
			    <td>			    	
			    	<?=count($order['BASKET_ITEMS']);?>							
					<?
							$count = count($order['BASKET_ITEMS']) % 10;
							if ($count == '1')
							{
								echo Loc::getMessage('SPOL_TPL_GOOD');
							}
							elseif ($count >= '2' && $count <= '4')
							{
								echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
							}
							else
							{
								echo Loc::getMessage('SPOL_TPL_GOODS');
							}
					?>
					<?=Loc::getMessage('SPOL_TPL_SUMOF')?>
					<?=$order['ORDER']['FORMATED_PRICE']?>
			    </td>
			    <td><?=$order["DELIVERY_ADDRESS"]?></th>
			    <td align="right"><a class="sale-order-list-about-link" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"])?>"><?=Loc::getMessage('SPOL_TPL_MORE_ON_ORDER')?></a></td>
			</tr>
		<?}?>
		</tbody></table>
		<?
	}
	else
	{
		$orderHeaderStatus = null;

		if ($_REQUEST["show_canceled"] === 'Y' && count($arResult['ORDERS']))
		{
			?>
			<h1 class="sale-order-title">
				<?= Loc::getMessage('SPOL_TPL_ORDERS_CANCELED_HEADER') ?>
				
			</h1>
			<?
		}

		foreach ($arResult['ORDERS'] as $key => $order)
		{
			if ($orderHeaderStatus !== $order['ORDER']['STATUS_ID'] && $_REQUEST["show_canceled"] !== 'Y')
			{
				$orderHeaderStatus = $order['ORDER']['STATUS_ID'];
				?>
				<h1 class="sale-order-title">
					<?= Loc::getMessage('SPOL_TPL_ORDER_IN_STATUSES') ?> &laquo;<?=htmlspecialcharsbx($arResult['INFO']['STATUS'][$orderHeaderStatus]['NAME'])?>&raquo;
				</h1>
				<?
			}
			?>
			<div class="col-md-12 col-sm-12 sale-order-list-container">
				<div class="row">
					<div class="col-md-12 col-sm-12 sale-order-list-accomplished-title-container">
						<div class="row">
							<div class="col-md-8 col-sm-12 sale-order-list-accomplished-title-container">
								<h2 class="sale-order-list-accomplished-title">
									<?= Loc::getMessage('SPOL_TPL_ORDER') ?>
									<?= Loc::getMessage('SPOL_TPL_NUMBER_SIGN') ?>
									<?= htmlspecialcharsbx($order['ORDER']['ACCOUNT_NUMBER'])?>
									<?= Loc::getMessage('SPOL_TPL_FROM_DATE') ?>
									<?= $order['ORDER']['DATE_INSERT'] ?>,
									<?= count($order['BASKET_ITEMS']); ?>
									<?
									$count = substr(count($order['BASKET_ITEMS']), -1);
									if ($count == '1')
									{
										echo Loc::getMessage('SPOL_TPL_GOOD');
									}
									elseif ($count >= '2' || $count <= '4')
									{
										echo Loc::getMessage('SPOL_TPL_TWO_GOODS');
									}
									else
									{
										echo Loc::getMessage('SPOL_TPL_GOODS');
									}
									?>
									<?= Loc::getMessage('SPOL_TPL_SUMOF') ?>
									<?= $order['ORDER']['FORMATED_PRICE'] ?>
								</h2>
							</div>
							<div class="col-md-4 col-sm-12 sale-order-list-accomplished-date-container">
								<?
								if ($_REQUEST["show_canceled"] !== 'Y')
								{
									?>
									<span class="sale-order-list-accomplished-date">
										<?= Loc::getMessage('SPOL_TPL_ORDER_FINISHED')?>
									</span>
									<?
								}
								else
								{
									?>
									<span class="sale-order-list-accomplished-date canceled-order">
										<?= Loc::getMessage('SPOL_TPL_ORDER_CANCELED')?>
									</span>
									<?
								}
								?>
								<span class="sale-order-list-accomplished-date-number"><?= $order['ORDER']['DATE_STATUS_FORMATED'] ?></span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 sale-order-list-inner-accomplished">
						<div class="row sale-order-list-inner-row">
							<div class="col-md-3 col-sm-12 sale-order-list-about-accomplished">
								<a class="sale-order-list-about-link" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_DETAIL"])?>">
									<?=Loc::getMessage('SPOL_TPL_MORE_ON_ORDER')?>
								</a>
							</div>
							<div class="col-md-3 col-md-offset-6 col-sm-12 sale-order-list-repeat-accomplished">
								<a class="sale-order-list-repeat-link sale-order-link-accomplished" href="<?=htmlspecialcharsbx($order["ORDER"]["URL_TO_COPY"])?>"><?=Loc::getMessage('SPOL_TPL_REPEAT_ORDER')?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?
		}
	}
	?>
	<div class="clearfix"></div>
	<?if($arParams["AJAX"] !== 'Y'):?>	
	</div>
	<?endif?>
	
	<?if($arParams["AJAX"] !== 'Y'):?>
	<?
	echo $arResult["NAV_STRING"];

	if ($_REQUEST["filter_history"] !== 'Y')
	{
		$javascriptParams = array(
			"url" => CUtil::JSEscape($this->__component->GetPath().'/ajax.php'),
			"templateFolder" => CUtil::JSEscape($templateFolder),
			"paymentList" => $paymentChangeData
		);
		$javascriptParams = CUtil::PhpToJSObject($javascriptParams);
		?>
		<script>
			BX.Sale.PersonalOrderComponent.PersonalOrderList.init(<?=$javascriptParams?>);
		</script>
		<?
	}?>
	<?endif?>
<?	
}
?>
<?if($arParams["AJAX"] !== 'Y'):?>
<?$arParams['AJAX_FILE_PATH'] = $templateFolder.'/ajax.php';?>
<script type="text/javascript">
    const ajax_file_path = '<?=$arParams['AJAX_FILE_PATH']?>';
    // const user_id = '<?=$arParams['USER_ID'];?>';
    const organization = '<?=$arParams['ORGANIZATION'];?>';
    const path_to_detail = '<?=$arParams['PATH_TO_DETAIL'];?>';

    const ajax = function(date, page = 1) {              
            BX.ajax({
                method: 'POST',  
                dataType: 'json',
                url: ajax_file_path,
                data: {
                  'date':date,
                  // 'user_id':user_id,
                  'organization':organization,
                  'page':page,
                  'path_to_detail':path_to_detail
                },
                start: true,
                cache: false,
                processData: true,
                scriptsRunFirst: true,
                emulateOnload: true,
                async:true,
                onsuccess: async function(data){
                    const ordersBlock  = $('.ajax_orders');
                    ordersBlock.empty();
                    ordersBlock.prepend(data["ITEMS"]);
                    // console.log(data);
                },
                onfailure: function(){
                }
            });  
    }

    BX.ready(function(){
        $('#datetimepicker').datetimepicker({
            locale: 'ru',
            stepping:10,
            format: 'DD.MM.YYYY',
            defaultDate: moment(),
        }).on("dp.change", function (e) {
            let date = $(this).find("input").val();
            console.log(date);
            ajax(date);
        });     
    });

</script>
<?endif?>	