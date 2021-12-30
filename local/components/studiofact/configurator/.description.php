<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$arComponentDescription = array(
    "NAME" => GetMessage("CFC_NAME"),
    "DESCRIPTION" => GetMessage("CFC_DESCRIPTION"),
    "ICON" => "/images/icon.gif",
    "PATH" => array(
        "ID" => "cfconfigurator",
        "CHILD" => array(
            "ID" => "sale_personal",
            "NAME" => GetMessage("CFC_MAIN")
        )
    ),
);
?>