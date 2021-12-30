<?if(!CModule::IncludeModule("iblock"))
    return;
$arIBlocks=array();
$db_iblock = CIBlock::GetList(array("SORT"=>"ASC"), array("SITE_ID"=>$_REQUEST["site"], "TYPE" => ($arCurrentValues["IBLOCK_TYPE"]!="-"?$arCurrentValues["IBLOCK_TYPE"]:"")));
while($arRes = $db_iblock->Fetch())
    $arIBlocks[$arRes["ID"]] = "[".$arRes["ID"]."] ".$arRes["NAME"];

$arSorts = array("ASC"=>GetMessage("T_IBLOCK_DESC_ASC"), "DESC"=>GetMessage("T_IBLOCK_DESC_DESC"));
$arSortFields = array(
    "ID"=>GetMessage("T_IBLOCK_DESC_FID"),
    "NAME"=>GetMessage("T_IBLOCK_DESC_FNAME"),
    "ACTIVE_FROM"=>GetMessage("T_IBLOCK_DESC_FACT"),
    "SORT"=>GetMessage("T_IBLOCK_DESC_FSORT"),
    "TIMESTAMP_X"=>GetMessage("T_IBLOCK_DESC_FTSAMP")
);

$arProperty_LNS = array();
$rsProp = CIBlockProperty::GetList(array("sort"=>"asc", "name"=>"asc"), array("ACTIVE"=>"Y","MULTIPLE"=>"N" ,"IBLOCK_ID"=>(isset($arCurrentValues["IBLOCK_ID"])?$arCurrentValues["IBLOCK_ID"]:$arCurrentValues["ID"])));
while ($arr=$rsProp->Fetch())
{
    $arProperty[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
    if (in_array($arr["PROPERTY_TYPE"], array("N", "S")))
    {
        $arProperty_LNS[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
    }
}

$arComponentParameters = Array(
    "GROUPS" => array(
        "IBLOCK_ID" => array(
            "NAME" => GetMessage("IBLOCK_ID")
        ),
        "NAME" => array(
            "NAME" => GetMessage("NAME")
        ),
        "ID" => array(
            "NAME" => GetMessage("ID")
        ),
        "POPULAR" => array(
            "NAME" => GetMessage("POPULAR")
        ),
        "PRICE" => array(
            "NAME" => GetMessage("PRICE")
        ),
        "SORT_INDEX" => array(
            "NAME" => GetMessage("SORT_INDEX")
        ),
        "CHANGE_DATE" => array(
            "NAME" => GetMessage("CHANGE_DATE")
        ),


    ),
    "PARAMETERS" => Array(
        "IBLOCK_ID" => array(
            "PARENT" => "BASE",
            "NAME" => GetMessage("IBLOCK"),
            "TYPE" => "LIST",
            "VALUES" => $arIBlocks,
            "DEFAULT" => '',
            "ADDITIONAL_VALUES" => "Y",
            "REFRESH" => "Y",
        ),
        "citfact_sort_show_name" => Array(
        "PARENT" => "NAME",
        "NAME" => GetMessage("SHOW"),
        "TYPE" => "CHECKBOX",
        "REFRESH" => "Y",
        ),
        "citfact_sort_show_id" => Array(
            "PARENT" => "ID",
            "NAME" => GetMessage("SHOW"),
            "TYPE" => "CHECKBOX",
            "REFRESH" => "Y",
        ),

        "citfact_sort_show_popular" => Array(
            "PARENT" => "POPULAR",
            "NAME" => GetMessage("SHOW"),
            "TYPE" => "CHECKBOX",
            "REFRESH" => "Y",
        ),
        "citfact_sort_show_price" => Array(
            "PARENT" => "PRICE",
            "NAME" => GetMessage("SHOW"),
            "TYPE" => "CHECKBOX",
            "REFRESH" => "Y",
        ),
        "citfact_sort_show_sort" => Array(
            "PARENT" => "SORT_INDEX",
            "NAME" => GetMessage("SHOW"),
            "TYPE" => "CHECKBOX",
            "REFRESH" => "Y",
        ),
        "citfact_sort_show_change_date" => Array(
            "PARENT" => "CHANGE_DATE",
            "NAME" => GetMessage("SHOW"),
            "TYPE" => "CHECKBOX",
            "REFRESH" => "Y",
        ),
    ),
);
$arTemplateParameters = array(
    "SECTIONS_VIEW_MODE" => array(
        "PARENT" => "SECTIONS_SETTINGS",
        "NAME" => GetMessage('CPT_BC_SECTIONS_VIEW_MODE'),
        "TYPE" => "LIST",
        "VALUES" => $arViewModeList,
        "MULTIPLE" => "N",
        "DEFAULT" => "LIST",
        "REFRESH" => "Y"
    ),
    "SECTIONS_SHOW_PARENT_NAME" => array(
        "PARENT" => "SECTIONS_SETTINGS",
        "NAME" => GetMessage('CPT_BC_SECTIONS_SHOW_PARENT_NAME'),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "Y"
    )
);
if (isset($arCurrentValues['citfact_sort_show_name']) && $arCurrentValues['citfact_sort_show_name'] =='Y')
{
    $arComponentParameters['PARAMETERS']['citfact_sort_alternative_name'] = array(
        "PARENT" => "NAME",
        "NAME" => GetMessage("ALTERNATIVE"),
        "TYPE" => "STRING",
        "REFRESH" => "",
    );
    $arComponentParameters['PARAMETERS']['citfact_sort_sort_name'] = Array(
        "PARENT" => "NAME",
        "NAME" => GetMessage("SORT"),
        "TYPE" => "STRING",
        "REFRESH" => "",
    );
}
if (isset($arCurrentValues['citfact_sort_show_id']) && $arCurrentValues['citfact_sort_show_id'] =='Y')
{
    $arComponentParameters['PARAMETERS']['citfact_sort_alternative_id']= array(
        "PARENT" => "ID",
        "NAME" => GetMessage("ALTERNATIVE"),
        "TYPE" => "STRING",
        "REFRESH" => "",
    );
    $arComponentParameters['PARAMETERS']['citfact_sort_sort_id'] = Array(
        "PARENT" => "ID",
        "NAME" => GetMessage("SORT"),
        "TYPE" => "STRING",
        "REFRESH" => "",
    );
}
if (isset($arCurrentValues['citfact_sort_show_popular']) && $arCurrentValues['citfact_sort_show_popular'] =='Y')
{
    $arComponentParameters['PARAMETERS']['citfact_sort_alternative_popular']= array(
        "PARENT" => "POPULAR",
        "NAME" => GetMessage("ALTERNATIVE"),
        "TYPE" => "STRING",
        "REFRESH" => "",
    );
    $arComponentParameters['PARAMETERS']['citfact_sort_sort_popular'] = Array(
        "PARENT" => "POPULAR",
        "NAME" => GetMessage("SORT"),
        "TYPE" => "STRING",
        "REFRESH" => "",
    );
}
if (isset($arCurrentValues['citfact_sort_show_price']) && $arCurrentValues['citfact_sort_show_price'] =='Y')
{
    $arComponentParameters['PARAMETERS']['citfact_sort_alternative_price']= array(
        "PARENT" => "PRICE",
        "NAME" => GetMessage("ALTERNATIVE"),
        "TYPE" => "STRING",
        "REFRESH" => "",
    );
    $arComponentParameters['PARAMETERS']['citfact_sort_sort_price'] = Array(
        "PARENT" => "PRICE",
        "NAME" => GetMessage("SORT"),
        "TYPE" => "STRING",
        "REFRESH" => "",
    );
}
if (isset($arCurrentValues['citfact_sort_show_id']) && $arCurrentValues['citfact_sort_show_id'] =='Y')
{

    $arComponentParameters['PARAMETERS']['citfact_sort_alternative_price']= array(
        "PARENT" => "PRICE",
        "NAME" => GetMessage("ALTERNATIVE"),
        "TYPE" => "STRING",
        "REFRESH" => "",
    );
    $arComponentParameters['PARAMETERS']['citfact_sort_sort_price'] = Array(
        "PARENT" => "PRICE",
        "NAME" => GetMessage("SORT"),
        "TYPE" => "STRING",
        "REFRESH" => "",
    );
}
if (isset($arCurrentValues['citfact_sort_show_sort']) && $arCurrentValues['citfact_sort_show_sort'] =='Y')
{

    $arComponentParameters['PARAMETERS']['citfact_sort_alternative_sort']= array(
        "PARENT" => "SORT_INDEX",
        "NAME" => GetMessage("ALTERNATIVE"),
        "TYPE" => "STRING",
        "REFRESH" => "",
    );
    $arComponentParameters['PARAMETERS']['citfact_sort_sort_sort'] = Array(
        "PARENT" => "SORT_INDEX",
        "NAME" => GetMessage("SORT"),
        "TYPE" => "STRING",
        "REFRESH" => "",
    );
}
if (isset($arCurrentValues['citfact_sort_show_change_date']) && $arCurrentValues['citfact_sort_show_change_date'] =='Y')
{
    $arComponentParameters['PARAMETERS']['citfact_sort_alternative_change_date']= array(
        "PARENT" => "CHANGE_DATE",
        "NAME" => GetMessage("ALTERNATIVE"),
        "TYPE" => "STRING",
        "REFRESH" => "",
    );
    $arComponentParameters['PARAMETERS']['citfact_sort_sort_change_date'] = Array(
        "PARENT" => "CHANGE_DATE",
        "NAME" => GetMessage("SORT"),
        "TYPE" => "STRING",
        "REFRESH" => "",
    );
}
if (!empty($arCurrentValues['IBLOCK_ID']))
{
    $arComponentParameters['GROUPS']['PROPERTY']= array(
        "NAME" => GetMessage("PROPERTY"),
    );
    $arComponentParameters['PARAMETERS']['citfact_sort_show_property'] = Array(
        "PARENT" => "PROPERTY",
        "NAME" => GetMessage("SHOW"),
        "TYPE" => "CHECKBOX",
        "REFRESH" => "Y"
    );
    if (isset($arCurrentValues['citfact_sort_show_property']) && $arCurrentValues['citfact_sort_show_property'] =='Y')
    {
        $arComponentParameters['PARAMETERS']['PROPERTY_CODE']= array(
            "PARENT" => "PROPERTY",
            "NAME" => GetMessage("IBLOCK_PROPERTIES"),
            "TYPE" => "LIST",
            "MULTIPLE" => "N",
            "VALUES" => $arProperty_LNS,
            "ADDITIONAL_VALUES" => "Y",
        );
        $arComponentParameters['PARAMETERS']['citfact_sort_alternative_property']= array(
            "PARENT" => "PROPERTY",
            "NAME" => GetMessage("ALTERNATIVE"),
            "TYPE" => "STRING",
            "REFRESH" => "",
        );
        $arComponentParameters['PARAMETERS']['citfact_sort_sort_property'] = Array(
            "PARENT" => "PROPERTY",
            "NAME" => GetMessage("SORT"),
            "TYPE" => "STRING",
            "REFRESH" => "",
        );
    }
}
?>