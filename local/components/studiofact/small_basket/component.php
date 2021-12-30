<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
if (!\Bitrix\Main\Loader::includeModule("sale") || !\Bitrix\Main\Loader::includeModule("iblock") || !\Bitrix\Main\Loader::includeModule("currency") || !\Bitrix\Main\Loader::includeModule("catalog"))
	return;

$arParams["PATH_TO_BASKET"] = trim($arParams["PATH_TO_BASKET"]);
$fUserId = CSaleBasket::GetBasketUserID();
if (floatval($_REQUEST["SMALL_BASKET_DELETE"]) > 0) {
	CSaleBasket::Delete($_REQUEST["SMALL_BASKET_DELETE"]);
}
if (floatval($_REQUEST["SMALL_BASKET_QUANTITY"]) > 0) {
	CSaleBasket::Update($_REQUEST["SMALL_BASKET_ID"], Array("QUANTITY" => $_REQUEST["SMALL_BASKET_QUANTITY"]));
}
if ($_REQUEST["action"] == "ADD2BASKET" && intVal($_REQUEST["id"]) > 0 && $_SERVER["PHP_SELF"] == "/personal/cart/index.php") {
	Add2BasketByProductID(intVal($_REQUEST["id"]), 1, array(), array());
	die();
}

if ($_REQUEST["SMALL_BASKET_FAST_ORDER"] == "Y" && strlen($_REQUEST["SMALL_BASKET_ORDER_PHONE"]) > 0) {
	$_REQUEST["SMALL_BASKET_ORDER_PHONE"] = preg_replace('/[^0-9]/', '', trim($_REQUEST["SMALL_BASKET_ORDER_PHONE"]));
	$price = 0;
	$currency = "";
	$strOrderList = "";
	$db_get = CSaleBasket::GetList(Array("NAME" => "ASC", "ID" => "ASC"), Array("FUSER_ID" => $fUserId, "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, Array("ID", "PRODUCT_ID", "PRODUCT_PRICE_ID", "PRICE", "CURRENCY", "WEIGHT", "QUANTITY", "CAN_BUY", "DELAY", "NAME", "NOTES", "CALLBACK_FUNC", "PRODUCT_PROVIDER_CLASS", "DETAIL_PAGE_URL", "MODULE"));
	while ($ar_get = $db_get->Fetch()) {
		$currency = $ar_get["CURRENCY"];
		$price += $ar_get["PRICE"]*$ar_get["QUANTITY"];
		$strOrderList .= $ar_get["NAME"]." - ".$ar_get["QUANTITY"]." ".$measureText.": ".SaleFormatCurrency($ar_get["PRICE"], $ar_get["CURRENCY"]);
		$strOrderList .= "\n";
	}
	global $USER;
	if ($USER->IsAuthorized()) {
		$user_id = intVal($USER->GetID());
	} else {
		$db_get = CUser::GetByLogin($_REQUEST["SMALL_BASKET_ORDER_PHONE"]);
		if ($ar_get = $db_get->Fetch()) {
			$user_id = intVal($ar_get["ID"]);
		} else {
			$user = new CUser;
			$pass = randString(10);
			$user_id = $user->Add(Array(
				"LOGIN" => $_REQUEST["SMALL_BASKET_ORDER_PHONE"],
				"NAME" => $_REQUEST["SMALL_BASKET_ORDER_PHONE"],
				"EMAIL" => $_REQUEST["SMALL_BASKET_ORDER_PHONE"]."@".$_SERVER["SERVER_NAME"],
				"PASSWORD" => $pass,
				"CONFIRM_PASSWORD" => $pass,
				"ACTIVE" => "Y"
			));
		}
	}
	$ar_get = CSalePersonType::GetList(Array("SORT" => "ASC"), Array("LID" => SITE_ID, "ACTIVE" => "Y"), false, false, Array("ID"))->Fetch();
	$person_type_id = $ar_get["ID"];

    $db_dtype = CSaleDelivery::GetList(array(),array("LID" => SITE_ID,"+<=ORDER_PRICE_FROM" => $price,"+>=ORDER_PRICE_TO" => $price,"ACTIVE" => "Y",),false,false,array())->Fetch();
    $delivery_id = $ar_get["ID"];

	$arFields = array(
		"LID" => SITE_ID,
		"PERSON_TYPE_ID" => $person_type_id,
		"PAYED" => "N",
		"CANCELED" => "N",
		"STATUS_ID" => "N",
		"PRICE" => $price,
		"CURRENCY" => $currency,
		"USER_ID" => $user_id,
		"DELIVERY_ID" => 1,
		"USER_DESCRIPTION" => $_REQUEST["SMALL_BASKET_ORDER_PHONE"]
	);
	$order_id = CSaleOrder::Add($arFields);
	if (intVal($order_id) > 0) {
		CSaleBasket::OrderBasket($order_id, $_SESSION["SALE_USER_ID"]);
		CSaleBasket::DeleteAll($fUserId);
		$arOrder = CSaleOrder::GetByID($order_id);

		$event = new CEvent;
		$event->Send("SALE_NEW_ORDER", SITE_ID, Array(
			"ORDER_ID" => $arOrder["ACCOUNT_NUMBER"],
			"ORDER_DATE" => Date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT", SITE_ID))),
			"ORDER_USER" => $_REQUEST["SMALL_BASKET_ORDER_PHONE"],
			"PRICE" => SaleFormatCurrency($price, $currency),
			"BCC" => COption::GetOptionString("sale", "order_email", "order@".$_SERVER["SERVER_NAME"]),
			//"EMAIL" => (strlen($arUserResult["USER_EMAIL"])>0 ? $arUserResult["USER_EMAIL"] : $USER->GetEmail()),
			"ORDER_LIST" => $strOrderList,
			"SALE_EMAIL" => COption::GetOptionString("sale", "order_email", "order@".$_SERVER["SERVER_NAME"]),
			"DELIVERY_PRICE" => 0,
		), "N");
	}
}

$arResult["BASKET_ITEMS"] = Array();
$arResult["NUM_PRODUCTS"] = 0;
$arResult["PRODUCTS_IMAGES"] = Array();
$prodId = Array();
CSaleBasket::UpdateBasketPrices($fUserId, SITE_ID);
$db_get = CSaleBasket::GetList(Array("NAME" => "ASC", "ID" => "ASC"), Array("FUSER_ID" => $fUserId, "LID" => SITE_ID, "ORDER_ID" => "NULL"), false, false, Array("ID", "PRODUCT_ID", "PRODUCT_PRICE_ID", "PRICE", "CURRENCY", "WEIGHT", "QUANTITY", "CAN_BUY", "DELAY", "NAME", "NOTES", "CALLBACK_FUNC", "PRODUCT_PROVIDER_CLASS", "DETAIL_PAGE_URL", "MODULE", "*"));
while ($ar_get = $db_get->Fetch()) {
    $arPrice = CCatalogProduct::GetOptimalPrice($ar_get['PRODUCT_ID'], $ar_get['QUANTITY'], $USER->GetUserGroupArray());
    $ar_get['PRICE'] = $arPrice['RESULT_PRICE']['DISCOUNT_PRICE'];
    $ar_get["PRICE_FORMATED"] = CurrencyFormat($ar_get["PRICE"], $ar_get["CURRENCY"]);
	$ar_get["SUM_FORMATED"] = CurrencyFormat($ar_get["PRICE"]*$ar_get["QUANTITY"], $ar_get["CURRENCY"]);
	$ar_get["PROPS"] = Array();
	$db_get2 = CSaleBasket::GetPropsList(Array("SORT" => "ASC", "NAME" => "ASC"), Array("BASKET_ID" => $ar_get["ID"]), false, false, Array());
	while ($ar_get2 = $db_get2->Fetch()) {
		$ar_get["PROPS"][] = $ar_get2;
	}
	$arResult["BASKET_ITEMS"][] = $ar_get;
	$arResult["NUM_PRODUCTS"] += $ar_get["QUANTITY"];
	if (intVal($ar_get["PRODUCT_ID"]) > 0) {
		$prodId[] = $ar_get["PRODUCT_ID"];
	}
}

if (count($prodId) > 0) {
	$db_get = CIBlockElement::GetList(Array(), Array("ID" => $prodId), false, false, Array("ID", "PREVIEW_PICTURE", "DETAIL_PICTURE", "PROPERTY_CML2_LINK"));
	while ($ar_get = $db_get->Fetch()) {
		if (intVal($ar_get["PREVIEW_PICTURE"]) > 0) {
			$file = CFile::ResizeImageGet($ar_get["PREVIEW_PICTURE"], Array("width" => 54, "height" => 41), BX_RESIZE_IMAGE_EXACT);
			$arResult["PRODUCTS_IMAGES"][$ar_get["ID"]] = $file["src"];
		} else if (intVal($ar_get["DETAIL_PICTURE"]) > 0) {
			$file = CFile::ResizeImageGet($ar_get["DETAIL_PICTURE"], Array("width" => 54, "height" => 41), BX_RESIZE_IMAGE_EXACT);
			$arResult["PRODUCTS_IMAGES"][$ar_get["ID"]] = $file["src"];
		} else if (intVal($ar_get["PROPERTY_CML2_LINK_VALUE"]) > 0) {
			$ar_get2 = CIBlockElement::GetList(Array(), Array("ID" => $ar_get["PROPERTY_CML2_LINK_VALUE"]), false, false, Array("ID", "PREVIEW_PICTURE", "DETAIL_PICTURE"))->Fetch();
			if (intVal($ar_get2["PREVIEW_PICTURE"]) > 0) {
				$file = CFile::ResizeImageGet($ar_get2["PREVIEW_PICTURE"], Array("width" => 54, "height" => 41), BX_RESIZE_IMAGE_EXACT);
				$arResult["PRODUCTS_IMAGES"][$ar_get["ID"]] = $file["src"];
			} else if (intVal($ar_get2["DETAIL_PICTURE"]) > 0) {
				$file = CFile::ResizeImageGet($ar_get2["DETAIL_PICTURE"], Array("width" => 54, "height" => 41), BX_RESIZE_IMAGE_EXACT);
				$arResult["PRODUCTS_IMAGES"][$ar_get["ID"]] = $file["src"];
			}
		}
	}
}
if(count($prodId)){
	$res = CCatalogMeasureRatio::getList(array(), array("PRODUCT_ID" => $prodId), false, false, array('PRODUCT_ID', 'RATIO'));
	while ($ar_get = $res->Fetch()) {
		$arResult["CATALOG_RATIO"][$ar_get["PRODUCT_ID"]] = $ar_get["RATIO"];
	}
}

$this->IncludeComponentTemplate();
if (strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) != "xmlhttprequest") {
	include_once ($_SERVER["DOCUMENT_ROOT"].$this->__template->__folder."/script.php");
} ?>