<?php
use Bitrix\Main;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Event;
use Bitrix\Sale\Order;
use Bitrix\Highloadblock as HL; 
use Bitrix\Main\Entity;
use Bitrix\Main\Mail\Event as BitrixMail;
use Bitrix\Main\UserGroupTable as UserGroupTable;
global $USER;


$eventManager = EventManager::getInstance();
$eventManager->addEventHandler(
    "iblock", 
    "OnBeforeIBlockElementAdd", 
    "onBeforeIBlockElementAdd"
);

function onBeforeIBlockElementAdd(&$arFields){
    // pr($arFields);
    if($arFields['IBLOCK_ID'] == IBLOCK_DOCUMENTS){ 
		$arFields = documentAdd($arFields);
		// setLog($arFields);
	}
}

// после добавления записи по балансу обновляем баланс организации
$eventManager->addEventHandler(
    "iblock", 
    "OnAfterIBlockElementAdd", 
    "onAfterIBlockElementAdd"
); 


function onAfterIBlockElementAdd(&$arFields){

	if($arFields['IBLOCK_ID'] == IBLOCK_BALANCE){
		$sum = updateFirmAccount($arFields['PROPERTY_VALUES'][79], $arFields['PROPERTY_VALUES'][80]);
		$PROPERTY_CODE = 'BALANCE';
		CIBlockElement::SetPropertyValues($arFields['PROPERTY_VALUES'][80], IBLOCK_GILDS, $sum, $PROPERTY_CODE);
		$PROPERTY_CODE = 'OLD_SUM';
		CIBlockElement::SetPropertyValues($arFields['ID'], IBLOCK_BALANCE, $sum, $PROPERTY_CODE);
		$PROPERTY_CODE = 'OLD_BALANCE';
		CIBlockElement::SetPropertyValues($arFields['ID'], IBLOCK_BALANCE, $arFields['PROPERTY_VALUES'][79], $PROPERTY_CODE);
	}

	if($arFields['IBLOCK_ID'] == IBLOCK_GILDS){
		$code = Cutil::translit($arFields['NAME'],"ru", array("replace_space"=>"_", "replace_other"=>"_", 'change_case' => 'L', 'max_len' => 100));
		$arLoadData = array('CODE'=>$code);
		$el = new CIBlockElement; 
		$res = $el->Update($arFields['ID'], $arLoadData);
		addFirmAccount($arFields['ID'], $arFields['NAME'], $code);
		orgSectionFind($arFields['ID']);
		
		// pr($arFields);
		if($arFields['PROPERTY_VALUES'][57]){
			$user_id = intval($arFields['PROPERTY_VALUES'][57]);
			if(!empty($user_id)){
				$gild_id = intval($arFields['ID']);
				$account = getFirmAccount($gild_id);
				makeUserManager($user_id, $gild_id, $account['ID']);
				// $arFields['PROPERTY_VALUES'][57] = '';
			}
		}


	}
}

// после изменения записи по балансу в HL блоке обновляем баланс организации
$eventManager->addEventHandler('', 'AccountOnAfterUpdate', 'OnAfterUpdate');
function OnAfterUpdate(\Bitrix\Main\Entity\Event $event){
	$id = $event->getParameter("id");
	$hlblock = HL\HighloadBlockTable::getById(HL_ACCOUNT)->fetch(); 
    $entity = HL\HighloadBlockTable::compileEntity($hlblock); 
    $entity_data_class = $entity->getDataClass(); 
    $rsData = $entity_data_class::getList(array(
       "select" => array("UF_SUM_RUB", "UF_GILD_ID"),
       "filter" => array("ID"=>$id)  // Задаем параметры фильтра выборки
    ));


    $PROPERTY_CODE = 'BALANCE';
    while($arData = $rsData->Fetch()){
    	if($arData['UF_SUM_RUB']){

    	    CIBlockElement::SetPropertyValues(
    	    	$arData['UF_GILD_ID'], 
    	    	IBLOCK_GILDS, 
    	    	$arData['UF_SUM_RUB'], 
    	    	$PROPERTY_CODE
    	    );
    	}
    }
}



$eventManager->addEventHandler(
    "iblock", 
    "OnBeforeIBlockElementUpdate", 
    "onBeforeIBlockElementUpdate"
);

function onBeforeIBlockElementUpdate(&$arFields){
	if($arFields['IBLOCK_ID'] == IBLOCK_BALANCE){
		$account = getFirmAccount($arFields['PROPERTY_VALUES'][80]);
		if($account['UF_SUM_RUB']){
			$sum = intval($account['UF_SUM_RUB']) - intval($arFields['PROPERTY_VALUES'][85]);
			$sum = $sum + intval($arFields['PROPERTY_VALUES'][79]);
			updateFirmAccount($sum, $arFields['PROPERTY_VALUES'][80], $replace = true);
			$arFields['PROPERTY_VALUES'][84] = $sum;// обновляем старую сумму
			$arFields['PROPERTY_VALUES'][85] = intval($arFields['PROPERTY_VALUES'][79]); // обновляем старый баланс
		}
	}

	if($arFields['IBLOCK_ID'] == IBLOCK_DOCUMENTS){
		$arFields = documentUpdate($arFields);
	}

	if($arFields['IBLOCK_ID'] == IBLOCK_GILDS){
		if($arFields['PROPERTY_VALUES'][57]){
			$user_id = intval($arFields['PROPERTY_VALUES'][57]);
			if(!empty($user_id)){
				$gild_id = intval($arFields['ID']);
				$account = getFirmAccount($gild_id);
				makeUserManager($user_id, $gild_id, $account['ID']);
			}
		}
	}
}

$eventManager->addEventHandler(
    "iblock", 
    "OnAfterIBlockElementUpdate", 
    "onAfterIBlockElementUpdate"
);
function onAfterIBlockElementUpdate(&$arFields){

	if($arFields['IBLOCK_ID'] == IBLOCK_DOCUMENTS){
		$res = CIBlockElement::GetByID($arFields['ID']);
		if($ar_res = $res->GetNext()){
			LocalRedirect($ar_res['DETAIL_PAGE_URL']);
		}
	}
}

// добавляем параметры новому пользоателю
$eventManager->addEventHandler(
    "main", 
    "OnAfterUserAdd", 
    "onAfterUserAdd"
);

function onAfterUserAdd(&$arFields){
	$gild_id = intval($arFields['UF_GILD']);
	if($gild_id && $arFields['ID']){		
		$account = getFirmAccount($gild_id);
		$fields = Array(
		  "UF_BALANCE_MO" => $arFields['UF_LIMIT_MO'],
		  "UF_BALANCE_TU" => $arFields['UF_LIMIT_TU'],
		  "UF_BALANCE_WE" => $arFields['UF_LIMIT_WE'],
		  "UF_BALANCE_TH" => $arFields['UF_LIMIT_TH'],
		  "UF_BALANCE_FR" => $arFields['UF_LIMIT_FR'],
		  "UF_USER_TYPE" => $arFields['UF_USER_TYPE'],
		  "UF_GILD" => $gild_id,
		  "UF_BILLING_ACCOUNT"=>$account['ID']
		);
		
		$GLOBALS['USER']->Update($arFields['ID'], $fields);
		addToGroup($arFields); 
	}
	return;
}

// помещаем пользователя в группу Юр или Физ лица после регистрации
function addToGroup($arFields){ 
	if($arFields['UF_USER_TYPE'] && $arFields['UF_USER_TYPE'] !== USER_TYPE_ADMIN){		
			if($arFields['UF_USER_TYPE'] == USER_TYPE_USER){
				$groupId = USER_GROUP_INDIVIDUAL;
			}
			elseif($arFields['UF_USER_TYPE'] == USER_TYPE_EMPLOYEE){
				$groupId = USER_GROUP_CORPORATE;
			}
			elseif ($arFields['UF_USER_TYPE'] == USER_TYPE_MANAGER) {
				$groupId = USER_GROUP_CORPORATE;
			}		
			// Добавить пользователя в группу
			UserGroupTable::add(array(
				"USER_ID" => $arFields['ID'],
				"GROUP_ID" => $groupId,
			));
	}
}

// добавляем параметры новому пользоателю
$eventManager->addEventHandler(
    "main", 
    "OnBeforeUserAdd", 
    "onBeforeUserAdd"
);

function onBeforeUserAdd(&$arFields){
	
	// setLog($arFields);

	if($arFields){

		$userType = intval($arFields['UF_USER_TYPE']); // тип пользователя
		$arFields['LOGIN'] = htmlspecialcharsEx($arFields['LOGIN']);
		$arFields['EMAIL'] = htmlspecialcharsEx($arFields['EMAIL']);
		$arFields['PASSWORD'] = htmlspecialcharsEx($arFields ['PASSWORD']);
		$arFields['CONFIRM_PASSWORD'] = htmlspecialcharsEx($arFields['CONFIRM_PASSWORD']);

		$arFields['WORK_NOTES'] = htmlspecialcharsEx($arFields['WORK_NOTES']); // ИНН
		$arFields['WORK_COMPANY'] = htmlspecialcharsEx($arFields['WORK_COMPANY']); // название компании
		$arFields['NAME'] = htmlspecialcharsEx($arFields['NAME']);// ФИО
		$arFields['PERSONAL_PHONE'] = htmlspecialcharsEx($arFields['PERSONAL_PHONE']); 

		$arFields['UF_GILD'] = intval($arFields['UF_GILD']);
		if($arFields['UF_GILD']){
			$arFields['ACTIVE'] = 'Y';
		}
		else{
			$arFields['ACTIVE'] = 'N';
		}

		if($userType == USER_TYPE_MANAGER){
			$arFields['UF_USER_TYPE'] = USER_TYPE_MANAGER;
		}
		elseif($userType == USER_TYPE_EMPLOYEE){
			$arFields['UF_USER_TYPE'] = USER_TYPE_EMPLOYEE;
		}
		else{
			$arFields['UF_USER_TYPE'] = USER_TYPE_USER;
		}
	}
	// pr($arFields);
	// die();
}

// ПОЧТОВЫЕ СООБЩЕНИЯ ПРИ РЕГИСТРАЦИИ
// заявки на регистрацию от организации
$eventManager->addEventHandler(
    "main", 
    "OnBeforeEventAdd", 
    "onBeforeEventAdd"
);

function onBeforeEventAdd(&$event, &$lid, &$arFields){
	// pr($event);
	// pr($lid);
	// pr($arFields);
	// die();
	// setLog($arFields);

	if($arFields['UF_USER_TYPE'] == USER_TYPE_MANAGER){
		
		// отправляем сообщение администратору о заявки на регистрацию от организации
		BitrixMail::send(array(
		    "EVENT_NAME" => "CORPORATE_USER_REGISTRATION",
		    "LID" => "s1",
		    "C_FIELDS" => array(
		        'EMAIL' => $arFields['EMAIL'],
		        'PHONE' => $arFields['PERSONAL_PHONE'],
		        'NAME'  => $arFields['NAME'],
		        'LOGIN' => $arFields['LOGIN'],
		        'COMPANY' => $arFields['WORK_COMPANY'],
		        'INN' => $arFields['WORK_NOTES']
		    )
		)); 		

		return false;
	}	


}

// ЗАКАЗ
// при создании заказа
$eventManager->addEventHandler(
    "sale", 
    "OnSaleOrderSaved", 
    "onSaleOrderSaved"
);

// function onSaleOrderSaved(Bitrix\Main\Event $event){
// 	$order = $event->getParameter("ENTITY");
// 	// свойства заказа
// 	$propertyCollection = $order->getPropertyCollection();
// 	$deliveryPriceProperty =  $propertyCollection->getItemByOrderPropertyId(DELIVERY_PRICE);
// 	$deliveryPrice = $deliveryPriceProperty->getValue();
// 	// if(!empty($deliveryPrice)){	
// 	// 	$price = $order->getPrice();
// 	// 	$priceUp = $price + $deliveryPrice;
// 	// 	$order->setField('PRICE', $priceUp);
// 	// 	$order->save();
// 	// }

// 	// setLog($priceUp);

// }


// КОРЗИНА
// при удалении позиции
$eventManager->addEventHandler(
    'sale',
    'OnSaleBasketSaved',
    'onSaleBasketSaved'
);

function onSaleBasketSaved(Main\Event $event) {
    /** @var Basket $basket */
    $basket = $event->getParameter("ENTITY");
    $day = $basket->getSiteId();
    if(!basketNumProducts($day)){
    	deleteCuisine($day);
    }
}