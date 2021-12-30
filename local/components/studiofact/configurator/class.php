<?php

/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage sale
 * @copyright 2001-2016 Bitrix
 */

use Bitrix\Main,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\Loader;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

class CitFactConfigurator extends CBitrixComponent
{

    public function executeComponent()
    {
        global $USER;
        $config = new \Citfact\Getfood\Configurator();
        $this->arResult["PROPS"] = $config->getOptions();
        $this->arResult["siteOption"] = $config->getCurrentOptions();
        if ($config->modeDefinition() !== "demo") {
            if ($USER->IsAdmin()) {
                $this->includeComponentTemplate();
            }
        } else{
            $this->includeComponentTemplate();
        }

    }
}