<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("sale") || !CModule::IncludeModule("iblock")) die();

global $USER;
if(!$USER->IsAuthorized()){
	ShowError('Вы не авторизованы!');
	return false;
}

include_once 'functions.php';

// получаем биллинг аккаунт и тип пользователя
$result = \Bitrix\Main\UserTable::getList(array(
    'select' => array('ID',
    	'LOGIN', 
    	'EMAIL', 
    	'UF_BILLING_ACCOUNT', 
    	'UF_USER_TYPE',
    ), 
    "filter"=>array("ID"=>$USER->GetID())
));
$arUser = $result->fetch();

// получаем список пользователей по биллинг-аккаунту
$usersRes = \Bitrix\Main\UserTable::getList(array(
    'select' => array('ID',
    	'LOGIN', 
    	'EMAIL', 
    	'NAME',
    	'LAST_NAME',
    	'UF_BILLING_ACCOUNT', 
    	'UF_USER_TYPE',
    ), 
    "filter"=>array("UF_BILLING_ACCOUNT"=>$arUser['UF_BILLING_ACCOUNT'])
));

$firmUsers = [];
while ($firmUser = $usersRes->fetch()){
	if($USER->GetID() !== $firmUser['ID']){
	    $firmUser['FULL_NAME'] = $firmUser['NAME'].' '.$firmUser['LAST_NAME'];
	    $firmUser['SALES'] = orderByUser($firmUser['ID']); // получаем заказы каждого пользователя 
	    $firmUsers[] = $firmUser;
	}
}
$arResult['USERS'] = $firmUsers;

$this->IncludeComponentTemplate();

