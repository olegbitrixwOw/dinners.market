<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$this->setFrameMode(true);

if($arResult["FILE"] <> '') {
    ob_start();
    include($arResult["FILE"]);
    $output = trim(ob_get_contents());
    ob_end_clean();

    if($output){
        echo '<div class="welcome-text">' . $output . '</div>';
    }
}