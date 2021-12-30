<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

$arComponentDescription = array(
	'NAME' => Loc::getMessage('CATALOG_SECTION_PANEL_NAME'),
	'DESCRIPTION' => getMessage('CATALOG_SECTION_PANEL_DESCRIPTIONS'),
	'PATH' => array(
		'ID' => 'citfact',
		'NAME' => Loc::getMessage('CITFACT_NAME')
	),
	'CACHE_PATH' => 'Y',
	'SORT' => 100
);