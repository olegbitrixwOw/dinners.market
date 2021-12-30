<?php
/**
*
* @version 0.1.1
* @author darkfriend
*/
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class Dev2funUserList extends CBitrixComponent {

    /**
     * Get replace pattern string
     * @param string $pattern
     * @param array $arUser user fields
     * @return string
     */
    public function getUrlTemplates($pattern,$arUser){
        $arReplace = array();
        foreach ($arUser as $key=>$val){
            $arReplace['#'.$key.'#'] = $val;
        }
        return strtr($pattern, $arReplace);
    }

    /**
     * Get value constant
     * @param string $constant constant name
     * @return string|false value
     */
    public function getConstantValue($constant){
        if(!$constant) return false;
        $cU = get_defined_constants();
        if(!isset($cU[$constant])) return false;
        return $cU[$constant];
    }
}