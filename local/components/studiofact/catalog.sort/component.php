<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$default_sort = true;
if (!isset($arParams['SORT_FIELDS']) && empty($arParams['SORT_FIELDS'])) {
    //массив вариантов сортировки и добавление варианта по условию "Показывать вариант" desc - по убыванию
    $arParams['SORT_FIELDS'] = array();
    $name = array(
        'title' => (!empty($arParams['citfact_sort_alternative_name'])) ? $arParams['citfact_sort_alternative_name'] : GetMessage('SORT_TITLE_NAME'),
        'sort' => array(
            array(
                'FIELD' => 'name',
                'ORDER' => 'asc',
            )
        ),
        'sort_el' => $arParams['citfact_sort_sort_name'],
    );
    $id = array(
        'title' => (!empty($arParams['citfact_sort_alternative_id'])) ? $arParams['citfact_sort_alternative_id'] : GetMessage('SORT_TITLE_ID'),
        'sort' => array(
            array(
                'FIELD' => 'id',
                'ORDER' => 'desc',
            )
        ),
        'sort_el' => $arParams['citfact_sort_sort_id'],
    );
    $popular = array(
        'title' => (!empty($arParams['citfact_sort_alternative_popular'])) ? $arParams['citfact_sort_alternative_popular'] : GetMessage('SORT_TITLE_POPULAR'),
        'sort' => array(
            array(
                'FIELD' => 'SHOW_COUNTER',
                'ORDER' => 'desc',
            )
        ),
        'sort_el' => $arParams['citfact_sort_sort_popular'],
    );
    $price = array(
        'title' => (!empty($arParams['citfact_sort_alternative_price'])) ? $arParams['citfact_sort_alternative_price'] : GetMessage('SORT_TITLE_PRICE'),
        'sort' => array(
            array(
                'FIELD' => 'PROPERTY_MINIMUM_PRICE',
                'ORDER' => 'asc',
            )
        ),
        'sort_el' => $arParams['citfact_sort_sort_price'],
    );
    $property = array(
        'title' => (!empty($arParams['citfact_sort_alternative_property'])) ? $arParams['citfact_sort_alternative_property'] : GetMessage('SORT_TITLE_PROPERTY'),
        'sort' => array(
            array(
                'FIELD' => "PROPERTY_".$arParams['PROPERTY_CODE'],
                'ORDER' => 'asc',
            )
        ),
        'sort_el' => $arParams['citfact_sort_sort_price'],
    );
    $sort = array(
        'title' => (!empty($arParams['citfact_sort_alternative_sort'])) ? $arParams['citfact_sort_alternative_sort'] : GetMessage('SORT_TITLE_SORT'),
        'sort' => array(
            array(
                'FIELD' => 'sort',
                'ORDER' => 'asc',
            )
        ),
        'sort_el' => $arParams['citfact_sort_sort_sort'],
    );
    $change_date = array(
        'title' => (!empty($arParams['citfact_sort_alternative_change_date'])) ? $arParams['citfact_sort_alternative_change_date'] : GetMessage('SORT_TITLE_CHANGE_DATE'),
        'sort' => array(
            array(
                'FIELD' => 'timestamp_x',
                'ORDER' => 'desc',
            )
        ),
        'sort_el' => $arParams['citfact_sort_sort_change_date'],
    );
    if ($arParams['citfact_sort_show_name'] == 'Y')
        $arParams['SORT_FIELDS']['name'] = $name;
    if ($arParams['citfact_sort_show_id'] == 'Y')
        $arParams['SORT_FIELDS']['id'] = $id;
    if ($arParams['citfact_sort_show_popular'] == 'Y')
        $arParams['SORT_FIELDS']["popular"]= $popular;
    if ($arParams['citfact_sort_show_price'] == 'Y')
        $arParams['SORT_FIELDS']['price']=$price;
    if ($arParams['citfact_sort_show_sort'] == 'Y')
        $arParams['SORT_FIELDS']['sort']=$sort;
    if ($arParams['citfact_sort_show_change_date'] == 'Y')
        $arParams['SORT_FIELDS']['change_date']=$change_date;
    if ($arParams['citfact_sort_show_property'] == 'Y')
        $arParams['SORT_FIELDS']['property']=$property;
}

//сортировка массива вариантов сортировки по индексу
foreach ($arParams['SORT_FIELDS'] as $arSort) {
    if (!empty($arSort['sort_el'])) {
        $arParams_sort_place = array();
        foreach ($arParams['SORT_FIELDS'] as $index => $arPlace) {
            $arResult_sort_place[$index] = $arPlace['sort_el'];
        }
        array_multisort($arResult_sort_place, SORT_ASC, $arParams['SORT_FIELDS']);
        break;
    }
}
$arResult['VARIANTS'] = $arParams['SORT_FIELDS'];
if (isset($_GET['sort']) && !empty($_GET['sort']) && isset($arParams['SORT_FIELDS'][$_GET['sort']])) {
    $arResult['SORT']['SORTED'] = 'Y';
    $arResult['VARIANTS'][$_GET['sort']]['SELECTED'] = 'Y';
    $default_sort = false;
    if (!empty($arParams['SORT_FIELDS'][$_GET['sort']]['sort'][0])) {
        if (isset($_GET['order']) && !empty($_GET['order'])) {
            switch ($_GET['order']) {
                case "asc":
                    $curOrder = "asc";
                    $arResult['VARIANTS'][$_GET['sort']]['sort'][0]["ORDER"] = "desc";
                    break;
                default:
                    $curOrder = "desc";
                    $arResult['VARIANTS'][$_GET['sort']]['sort'][0]["ORDER"] = "asc";
                    break;
            }
        }

        $arResult['SORT'][0] = $arParams['SORT_FIELDS'][$_GET['sort']]['sort'][0];
        $arResult['SORT'][0]["ORDER"] = $curOrder;
    }
}

if (!$arResult['SORT']['SORTED'] && isset($_GET['sort']) && !empty($_GET['sort'])) {
    $arResult['SORT']['SORTED'] = 'Y';
    $arResult['VARIANTS'][$_GET['sort']]['sort'][0]["ORDER"] = "asc";
    $arResult['VARIANTS'][$_GET['sort']]["SELECTED"] = "Y";
} else {
    $arResult['SORT']['SORTED'] = 'Y';
    foreach ($arResult['VARIANTS'] as $v=>$variant){
        if($default_sort){
            $arResult['SORT'][0] = $variant['sort'][0];
            $arResult['VARIANTS'][$v]["SELECTED"] = "Y";
            break;
        } else {
            if($variant["SELECTED"] == "Y"){
                $arResult['SORT'][0] = $variant['sort'][0];
                break;
            }
        }
    }
}

$this->IncludeComponentTemplate();

return $arResult['SORT'];
?>
