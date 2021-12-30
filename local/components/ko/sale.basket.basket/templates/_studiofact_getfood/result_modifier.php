<?php

use Citfact\Getfood\Image;

/**
 * Ðåñàéçèíã èçîáðàæåíèé
 */
Image::resizeBasket($arResult, [150, 150], [500, 500]);

// var_dump($arParams["DAY_WEEK"]);
$arResult['DAY'] = Bitrix\Main\Context::getCurrent()->getSite(); // текущий сайт
switch ($arParams["DAY_WEEK"]) {
	case 'Mo':
		$arResult['DAY'] = 'Понедельник';
		break;
	case 'Tu':
		$arResult['DAY'] = 'Вторник';
		break;
	case 'We':
		$arResult['DAY'] = 'Среда';
		break;
	case 'Th':
		$arResult['DAY'] = 'Четверг';
		break;
	case 'Fr':
		$arResult['DAY'] = 'Пятница';
		break;
	case 'Sa':
		$arResult['DAY'] = 'Суббота';
		break;
	case 'Su':
		$arResult['DAY'] = 'Воскресенье';
		break;
	case 'repeat':
		$arResult['DAY'] = 'Повтор заказа другого покупателя вашей фирмы';
		break;
	default:
		break;
}

// var_dump($arResult);