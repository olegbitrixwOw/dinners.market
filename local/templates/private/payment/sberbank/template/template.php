<?
	use Bitrix\Main\Localization\Loc;
	use Bitrix\Sale\Payment;
	use Bitrix\Sale\PriceMaths;
	use Bitrix\Sale\Order;

Loc::loadMessages(__FILE__);
	if (array_key_exists('PAYMENT_SHOULD_PAY', $params))
		$params['PAYMENT_SHOULD_PAY'] = PriceMaths::roundPrecision($params['PAYMENT_SHOULD_PAY']);

	// получаем данные кухни по свойству заказа
	$order = \Bitrix\Sale\Order::load($params['PAYMENT_ORDER_ID']);
	$propertyCollection = $order->getPropertyCollection();
	$somePropValue = $propertyCollection->getItemByOrderPropertyId(CUISINE);
	$cuisineEl = getCuisineElement($somePropValue->getValue());

?>
<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<title><?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_SBERBANK_RECEIPT')?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET?>">
<style type="text/css">
H1 {font-size: 12pt;}
p, ul, ol, h1 {margin-top:6px; margin-bottom:6px}
td {font-size: 9pt;}
small {font-size: 7pt;}
body {font-size: 10pt;}
</style>
</head>
<body bgColor="#ffffff">

<table border="0" cellspacing="0" cellpadding="0" style="width:180mm; height:145mm;">
<tr valign="top">

	<td style="border:1pt solid #000000;" align="center">
		
		<table border="0" cellspacing="0" cellpadding="0" style="width:122mm; margin-top:3pt;">
			<tr>
				<td style="width:60mm; border-bottom:1pt solid #000000;">
					<?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_SBERBANK_ORDER_ID', array(
					'#PAYMENT_ID#' => htmlspecialcharsbx($params["PAYMENT_ID"]), 
					'#ORDER_ID#' => htmlspecialcharsbx($params["PAYMENT_ORDER_ID"])
				))?>
				<?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_SBERBANK_ORDER_FROM')?>
				<?=htmlspecialcharsbx($params["PAYMENT_DATE_INSERT"])?>
				</td>

				<td style="width:2mm;">&nbsp;</td>
				<td style="border-bottom:1pt solid #000000;">
					<?=htmlspecialcharsbx($params['BUYER_PERSON_BANK_ACCOUNT']);?>
				</td>
			</tr>

		</table>
		<table border="0" cellspacing="0" cellpadding="0" style="width:122mm; margin-top:10pt;">
			<tr>
				<td width="1%" nowrap   style="border-bottom:1pt solid #000000;">
					<?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_SBERBANK_PAYMENT_RECEIVER_FIO')?>
					<?=$cuisineEl['RECEIVER_FIO']?>
				</td>
			</tr>

		</table>


		<table border="0" cellspacing="0" cellpadding="0" style="width:122mm; margin-top:10pt;">

			<tr>
				<td width="1%" nowrap   style="border-bottom:1pt solid #000000;">
					(номер карты получателя платежа)
					<?=$cuisineEl['SETTLEMENT_CARD']?>
				</td>
			</tr>
		</table>


		<table border="0" cellspacing="0" cellpadding="0" style="width:122mm; margin-top:10pt;">

			<tr>
				<td width="1%" nowrap   style="border-bottom:1pt solid #000000;">
					(сообщение получателю платежа) &nbsp;&nbsp; 
					<?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_PAYMENT_ORDER_ID', array(
					'#ORDER_ID#' => htmlspecialcharsbx($params["PAYMENT_ORDER_ID"])
				))?></td>
			</tr>
		</table>


		<table border="0" cellspacing="0" cellpadding="0" style="width:122mm; margin-top:3pt;">
			<tr>
				<td width="100%" style="border-bottom:1pt solid #000000;"><?
				
					$sAddrFact = array();
					if($params["BUYER_PERSON_ZIP"] != '')
						$sAddrFact[] = htmlspecialcharsbx($params["BUYER_PERSON_ZIP"]);

					if($params["BUYER_PERSON_COUNTRY"] != '')
						$sAddrFact[] = htmlspecialcharsbx($params["BUYER_PERSON_COUNTRY"]);

					if($params["BUYER_PERSON_REGION"] != '')
						$sAddrFact[] = htmlspecialcharsbx($params["BUYER_PERSON_REGION"]);

					if($params["BUYER_PERSON_CITY"] != '')
					{
						$g = substr($params["BUYER_PERSON_CITY"], 0, 2);
						$sAddrFact[] = '<nobr>'.($g<>Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_SBERBANK_SHORT_YEAR') && $g<>ToUpper(Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_SBERBANK_SHORT_YEAR'))? Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_SBERBANK_SHORT_YEAR')." ":"").htmlspecialcharsbx($params["BUYER_PERSON_CITY"]).'</nobr>';
					}

					if($params["BUYER_PERSON_VILLAGE"] != '')
						$sAddrFact[] = htmlspecialcharsbx($params["BUYER_PERSON_VILLAGE"]);

					if($params["BUYER_PERSON_STREET"] != '')
						$sAddrFact[] = htmlspecialcharsbx($params["BUYER_PERSON_STREET"]);

					if($params["BUYER_PERSON_ADDRESS_FACT"] != '')
						$sAddrFact[] = htmlspecialcharsbx($params["BUYER_PERSON_ADDRESS_FACT"]);

					echo implode(', ', $sAddrFact);
				?>&nbsp;</td>
			</tr>
		</table>
		<table border="0" cellspacing="0" cellpadding="0" style="width:122mm; margin-top:3pt;">
			<tr>
				<td><?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_SBERBANK_SHOULD_PAY')?>&nbsp;<?
				if (strpos($params["PAYMENT_SHOULD_PAY"], ".")!==false)
					$a = explode(".", $params["PAYMENT_SHOULD_PAY"]);
				else
					$a = explode(",", $params["PAYMENT_SHOULD_PAY"]);

				if ($a[1] <= 9 && $a[1] > 0)
					$a[1] = $a[1]."0";
				elseif ($a[1] == 0)
					$a[1] = "00";

				echo "<font style=\"text-decoration:underline;\">&nbsp;".htmlspecialcharsbx($a[0])."&nbsp;</font>&nbsp;".Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_SBERBANK_SHORT_RUB')."&nbsp;<font style=\"text-decoration:underline;\">&nbsp;".htmlspecialcharsbx($a[1])."&nbsp;</font>&nbsp;".Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_SBERBANK_SHORT_COP')."";
				?></td>
			
			</tr>
		</table>


	</td>
</tr>


</table>
<br />
<h1><?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_SBERBANK_WARNING')?></h1>

<!-- CONDITIONS -->
<?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_SBERBANK_FORM_CONDITIONS')?>


<p><b><?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_SBERBANK_NOTE')?></b>
<?=htmlspecialcharsbx($params["SELLER_COMPANY_NAME"])?>
	<?=Loc::getMessage('SALE_HANDLERS_PAY_SYSTEM_SBERBANK_NOTE_DESCRIPTION')?></p>
</body>
</html>