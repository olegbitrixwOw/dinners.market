<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;


if (strlen($arParams["MAIN_CHAIN_NAME"]) > 0)
{
	$APPLICATION->AddChainItem(htmlspecialcharsbx($arParams["MAIN_CHAIN_NAME"]), $arResult['SEF_FOLDER']);
}

$theme = Bitrix\Main\Config\Option::get("main", "wizard_eshop_bootstrap_theme_id", "blue", SITE_ID);

$availablePages = array();

// if ($arParams['SHOW_ORDER_PAGE'] === 'Y')
// {
// 	$availablePages[] = array(
// 		"path" => $arResult['PATH_TO_ORDERS'],
// 		"name" => Loc::getMessage("SPS_ORDER_PAGE_NAME"),
// 		"icon" => '<svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-icon-order"></use></svg>'
// 	);
// }

if ($arParams['SHOW_ACCOUNT_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_ACCOUNT'],
		"name" => Loc::getMessage("SPS_ACCOUNT_PAGE_NAME"),
		"icon" => '<svg width="459.67px" height="459.67px" enable-background="new 0 0 459.669 459.669" version="1.1" viewBox="0 0 459.669 459.669" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
		<path d="m404.72 76.087h-349.78c-30.299 0-54.948 24.648-54.948 54.948v197.6c0 30.298 24.649 54.948 54.948 54.948h349.77c30.298 0 54.947-24.65 54.947-54.948v-197.6c1e-3 -30.3-24.648-54.948-54.946-54.948zm24.544 252.55c0 13.534-11.011 24.544-24.544 24.544h-349.78c-13.534 0-24.545-11.01-24.545-24.544v-132.42h398.86l1e-3 132.42zm0-175.79l-398.86 0.029v-21.834c0-13.534 11.011-24.545 24.545-24.545h349.77c13.533 0 24.544 11.011 24.544 24.545v21.805z"/>
		<path d="m68.136 324.98h83.23c2.98 0 5.398-2.416 5.398-5.396v-16.421c0-2.981-2.418-5.397-5.398-5.397h-83.23c-2.981 0-5.398 2.416-5.398 5.397v16.421c-1e-3 2.98 2.416 5.396 5.398 5.396z"/>
		<path d="m337.96 324.98h24.756c14.288 0 25.87-11.582 25.87-25.869v-24.756c0-14.287-11.582-25.869-25.87-25.869h-24.756c-14.287 0-25.869 11.582-25.869 25.869v24.756c0 14.287 11.582 25.869 25.869 25.869z"/>
</svg>'
	);
}

if ($arParams['SHOW_PRIVATE_PAGE'] === 'Y')
{
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_PRIVATE'],
		"name" => Loc::getMessage("SPS_PERSONAL_PAGE_NAME"),
		"icon" => '<svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-icon-profile"></use></svg>'
	);
}

if ($arParams['SHOW_ORDER_PAGE'] === 'Y')
{

	$delimeter = ($arParams['SEF_MODE'] === 'Y') ? "?" : "&";
	$availablePages[] = array(
		"path" => $arResult['PATH_TO_ORDERS'].$delimeter."filter_history=Y",
		"name" => Loc::getMessage("SPS_ORDER_PAGE_HISTORY"),
		"icon" => '<svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-icon-choise"></use></svg>'
	);
}

// if ($arParams['SHOW_PROFILE_PAGE'] === 'Y')
// {
// 	$availablePages[] = array(
// 		"path" => $arResult['PATH_TO_PROFILE'],
// 		"name" => Loc::getMessage("SPS_PROFILE_PAGE_NAME"),
// 		"icon" => '<svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-icon-list"></use></svg>'
// 	);
// }

// if ($arParams['SHOW_BASKET_PAGE'] === 'Y')
// {
// 	$availablePages[] = array(
// 		"path" => $arParams['PATH_TO_BASKET'],
// 		"name" => Loc::getMessage("SPS_BASKET_PAGE_NAME"),
// 		"icon" => '<svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-icon-cart"></use></svg>'
// 	);
// }

// if ($arParams['SHOW_SUBSCRIBE_PAGE'] === 'Y')
// {
// 	$availablePages[] = array(
// 		"path" => $arResult['PATH_TO_SUBSCRIBE'],
// 		"name" => Loc::getMessage("SPS_SUBSCRIBE_PAGE_NAME"),
// 		"icon" => '<svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-icon-message"></use></svg>'
// 	);
// }

// if ($arParams['SHOW_CONTACT_PAGE'] === 'Y')
// {
// 	$availablePages[] = array(
// 		"path" => $arParams['PATH_TO_CONTACT'],
// 		"name" => Loc::getMessage("SPS_CONTACT_PAGE_NAME"),
// 		"icon" => '<svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-icon-notebook"></use></svg>'
// 	);
// }

// if($arParams['SHOW_OTHER_FIRM_EMPLOYEER_ORDERS'] === 'Y'){

// 	$availablePages[] = array(
// 		"path" => '/personal/your-firm-orders/',
// 		"name" => 'Заказы коллег',
// 		"icon" => '<svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-icon-order"></use></svg>'
// 	);
// }

if($arParams['SHOW_OTHER_FIRM_EMPLOYEER_ORDERS'] === 'Y'){

	$availablePages[] = array(
		"path" => '/firm/documents/',
		"name" => 'Документооборот',
		"icon" => '<svg class="icon"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#svg-icon-order"></use></svg>'
	);
}

$customPagesList = CUtil::JsObjectToPhp($arParams['~CUSTOM_PAGES']);
if ($customPagesList)
{
	foreach ($customPagesList as $page)
	{
		$availablePages[] = array(
			"path" => $page[0],
			"name" => $page[1],
			"icon" => (strlen($page[2])) ? '<i class="fa '.htmlspecialcharsbx($page[2]).'"></i>' : ""
		);
	}
}

if (empty($availablePages))
{
	ShowError(Loc::getMessage("SPS_ERROR_NOT_CHOSEN_ELEMENT"));
}
else
{
	?>
    <div class="row">
        <div class="col-md-12">
            <div class="lk-grid sale-personal-section-index">
                <div class="row">
                    <?
                    foreach ($availablePages as $blockElement)
                    {
                        ?>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <a class="lk-grid__item" href="<?=htmlspecialcharsbx($blockElement['path'])?>">
                                <div>
                                    <div class="lk-grid__icon">
                                        <?=$blockElement['icon']?>
                                    </div>
                                    <?=htmlspecialcharsbx($blockElement['name'])?>
                                </div>
                            </a>
                        </div>
                        <?
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
	<?
}
?>
