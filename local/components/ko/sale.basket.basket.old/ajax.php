<?php
define('STOP_STATISTICS', true);
define('NOT_CHECK_PERMISSIONS', true); 
if (!isset($_POST['siteId']) || !is_string($_POST['siteId']))
	die();
if (!isset($_POST['templateName']) || !is_string($_POST['templateName']))
	die();
if ($_SERVER['REQUEST_METHOD'] != 'POST' ||
	preg_match('/^[A-Za-z0-9_]{2}$/', $_POST['siteId']) !== 1 ||
	preg_match('/^[.A-Za-z0-9_-]+$/', $_POST['templateName']) !== 1)
	die;
define('SITE_ID', $_POST['siteId']);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
if (!check_bitrix_sessid())
	die;
$_POST['arParams']['AJAX'] = 'Y';

$lid = htmlspecialchars($_POST['lid']);
$APPLICATION->RestartBuffer();
$data = [];
$data['SUM_BASKETS'] = sumAllBaskets();
$data['SUM_CURRENT_BASKET'] = sumCurBasket($lid);
$data['NUMS'] = basketNumProducts($lid);
if($_POST['templateName']=='vertical_mobile'){
	$_POST['arParams']['SUM_CURRENT_BASKET'] = $data['SUM_CURRENT_BASKET'];
}
ob_start();
$APPLICATION->IncludeComponent('alex:sale.basket.basket.old', $_POST['templateName'], $_POST['arParams']);
$data['BASKET'] =  ob_get_clean();
echo json_encode($data);

