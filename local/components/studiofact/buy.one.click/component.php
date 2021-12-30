<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Citfact\Getfood\BuyOneClick;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if (
	!Loader::includeModule("sale")
	|| !Loader::includeModule("iblock")
	|| !Loader::includeModule("currency")
	|| !Loader::includeModule("catalog")
	|| !Loader::includeModule("citfact.getfood"))
{
	return;
}

$arParams["PATH_TO_BASKET"] = trim($arParams["PATH_TO_BASKET"]);
$fUserId = CSaleBasket::GetBasketUserID();

if (isset($_REQUEST["product_id"]))
{
	$productId = intval($_REQUEST["product_id"]);

	$quantity = 1;
	if (isset($_REQUEST["quantity"]) && floatval($_REQUEST["quantity"]) > 0)
	{
		$quantity = floatval($_REQUEST["quantity"]);
	}

	$oBuyOneClick = new BuyOneClick(array(
		'arProductInfo' => array($productId => $quantity)
	));

	$arProducts = $oBuyOneClick->getProducts();
}
else
{
	$oBuyOneClick = new BuyOneClick();
}

if ($_REQUEST["SMALL_BASKET_FAST_ORDER"] == "Y" && strlen($_REQUEST["SMALL_BASKET_ORDER_PHONE"]) > 0)
{
	$_REQUEST["SMALL_BASKET_ORDER_PHONE"] = preg_replace('/[^0-9]/', '', trim($_REQUEST["SMALL_BASKET_ORDER_PHONE"]));
	$price = 0;
	$currency = "";
	$strOrderList = "";
	$orderComment = GetMessage("ONE_CLICK_TITLE") . $_REQUEST["SMALL_BASKET_ORDER_PHONE"];

	if (isset($arProducts) && !empty($arProducts))
	{
		foreach ($arProducts as $ar_get)
		{
			$currency = $ar_get["CURRENCY"];
			$price += $ar_get["PRICE"];
			$strOrderList .= $ar_get["NAME"] . " - " . $ar_get["QUANTITY"] . " " . $measureText . ": " . SaleFormatCurrency($ar_get["PRICE"], $ar_get["CURRENCY"]);
			$strOrderList .= "\n";
		}
	}
	else
	{
		$db_get = CSaleBasket::GetList(Array("NAME" => "ASC", "ID" => "ASC"), Array("FUSER_ID" => $fUserId, "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, Array("ID", "PRODUCT_ID", "PRODUCT_PRICE_ID", "PRICE", "CURRENCY", "WEIGHT", "QUANTITY", "CAN_BUY", "DELAY", "NAME", "NOTES", "CALLBACK_FUNC", "PRODUCT_PROVIDER_CLASS", "DETAIL_PAGE_URL", "MODULE"));
		while ($ar_get = $db_get->Fetch()) {
			$currency = $ar_get["CURRENCY"];
			$price += $ar_get["PRICE"]*$ar_get["QUANTITY"];
			$strOrderList .= $ar_get["NAME"]." - ".$ar_get["QUANTITY"]." ".$measureText.": ".SaleFormatCurrency($ar_get["PRICE"], $ar_get["CURRENCY"]);
			$strOrderList .= "\n";
		}
	}

	$oBuyOneClick->setComment($orderComment);


	$order_id = $oBuyOneClick->order();

	if (intVal($order_id) > 0)
	{
		$arOrder = CSaleOrder::GetByID($order_id);

		$event = new CEvent;
		$event->Send("SALE_NEW_ORDER", SITE_ID, Array(
			"ORDER_ID" => $arOrder["ACCOUNT_NUMBER"],
			"ORDER_DATE" => Date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT", SITE_ID))),
			"ORDER_USER" => $_REQUEST["SMALL_BASKET_ORDER_PHONE"],
			"PRICE" => SaleFormatCurrency($price, $currency),
			"BCC" => COption::GetOptionString("sale", "order_email", "order@".$_SERVER["SERVER_NAME"]),
			"ORDER_LIST" => $strOrderList,
			"SALE_EMAIL" => COption::GetOptionString("sale", "order_email", "order@".$_SERVER["SERVER_NAME"]),
			"DELIVERY_PRICE" => 0,
		), "N");

		echo GetMessage("ONE_CLICK_SUCCESS");
	}
	else
	{
		echo GetMessage("ONE_CLICK_ERROR");
	}
}
else
{
	echo GetMessage("ONE_CLICK_ERROR_PARAMETER");
}