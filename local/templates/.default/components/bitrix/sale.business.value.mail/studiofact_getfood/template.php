<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
//$this->setFrameMode(true);
?>


		<?foreach($arResult["ITEMS"] as $item):
			if($arParams["DISPLAY_EMPTY"] != "Y" && !$item['VALUE']) continue;
		?>
			<table cellpadding="0" cellspacing="0">
                    <tbody>
                        <tr>
                            <td style="font-size:14px;padding-bottom:1px;padding-left:5px;padding-top:4px">
                            	<?if($arParams["DISPLAY_NAME"]!="N"):?><?=htmlspecialcharsbx($item['NAME'])?>:<?endif;?>
                            </td>
                            <td style="font-size:14px;padding-bottom:1px;padding-left:20px;padding-top:4px">
                            	<?=htmlspecialcharsbx($item['VALUE'])?>
                            </td>
                        </tr>
                    </tbody>
            </table>
		<?endforeach;?>
