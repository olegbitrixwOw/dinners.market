<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule("sale") || !CModule::IncludeModule("iblock")) die();

global $USER;
if(!$USER->IsAuthorized()){
	ShowError('Вы не авторизованы!');
	return false;
}

include_once 'functions.php';
   
// получаем группу организации пользователя
$groupRes = \Bitrix\Main\UserGroupTable::getList(array(
	'filter' => array('USER_ID'=>$USER->GetID(),'GROUP.ACTIVE'=>'Y'),
	'select' => array('GROUP_ID','GROUP_CODE'=>'GROUP.STRING_ID'), // выбираем идентификатор группы и символьный код группы
	'order' => array('GROUP.C_SORT'=>'ASC'), // сортируем в соответствии с сортировкой групп
));

$group = '';
while ($arGroup = $groupRes->fetch()) {
	if(stripos($arGroup['GROUP_CODE'], 'firm') !== false){
	$group = $arGroup; 
	}
}		    

if($group['GROUP_ID']){
// получаем всех пользователей
$usersRes = \Bitrix\Main\UserGroupTable::getList(array(
	'filter' => array('GROUP_ID'=>$group['GROUP_ID'],'USER.ACTIVE'=>'Y'),
	'select' => array('USER_ID','NAME'=>'USER.NAME','LAST_NAME'=>'USER.LAST_NAME'), // выбираем идентификатор п-ля, имя и фамилию
	'order' => array('USER.ID'=>'DESC'), // сортируем по идентификатору пользователя
));

$users = [];
while ($user = $usersRes->fetch()){ 
	if($user['USER_ID'] !== $USER->GetID()){
		$user['FULL_NAME'] = $user['NAME'].' '.$user['LAST_NAME'];
		$user['SALES'] = orderByUser($user['USER_ID']);
		$users[] = $user;

		// pr($user['SALES']);
	}
}

$arResult['USERS'] = $users;
}		
$this->IncludeComponentTemplate();

