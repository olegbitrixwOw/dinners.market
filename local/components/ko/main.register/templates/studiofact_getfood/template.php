<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */
use Bitrix\Main;
use Bitrix\Main\IO;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

if($arResult["SHOW_SMS_FIELD"] == true)
{
	CJSCore::Init('phone_auth');
}

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$user_tab = $request->getPost("USER_TAB");
// pr($user_tab);
// if(!empty($user_tab)){
// 	echo $user_tab;
// }

// echo $templateFolder;
// echo $templateName;
?>

<?
if (count($arResult["ERRORS"]) > 0):
	foreach ($arResult["ERRORS"] as $key => $error)
		if (intval($key) == 0 && $key !== 0) 
			$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

	ShowError(implode("<br />", $arResult["ERRORS"]));

elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):
?>
<p><?echo GetMessage("REGISTER_EMAIL_WILL_BE_SENT")?></p>
<?endif?>


<div class="tab">
  <button class="tablinks" onclick="openCity(event, 'corporate_user')" id="corporate">Корпоративные пользователи</button>
  <button class="tablinks" onclick="openCity(event, 'individual_user')" id="individual">Частные клиенты</button>
</div>

<div id="corporate_user" class="tabcontent">
  		<p>Вы регистрируетесь как представитель организации.</p>
    	<?include(Main\Application::getDocumentRoot().$templateFolder.'/corporate_user_registration.php');?>
</div>

<div id="individual_user" class="tabcontent">
	  	<p>Вы регистрируетесь как частное лицо.</p> 
	  	<?include(Main\Application::getDocumentRoot().$templateFolder.'/individual_user_registration.php');?>
</div>



<script>
	function openCity(evt, cityName) {
		  var i, tabcontent, tablinks;
		  tabcontent = document.getElementsByClassName("tabcontent");
		  for (i = 0; i < tabcontent.length; i++) {
		    tabcontent[i].style.display = "none";
		  }
		  tablinks = document.getElementsByClassName("tablinks");
		  for (i = 0; i < tablinks.length; i++) {
		    tablinks[i].className = tablinks[i].className.replace(" active", "");
		  }
		  document.getElementById(cityName).style.display = "block";
		  evt.currentTarget.className += " active";
	}
	<?if(!empty($user_tab)):?>
		const tabcontent = '<?=$user_tab?>';
	<?else:?>
		const tabcontent = 'corporate';
	<?endif;?>
	  const first = document.getElementById(tabcontent);
	  first.click();
</script>
<script>
	// устанавливаем маску на поле телефон
	// (function set_tel_mask(){
	// 		console.log('set_tel_mask');
	// 		const tel = document.querySelector('input[autocomplete="tel"]'); 
	// 					tel.setAttribute('data-input', 'phone');
	// 		// console.log(tel);
	// })();
</script>