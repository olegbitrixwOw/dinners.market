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
$this->setFrameMode(true);

$strTitle = "";

// foreach($arResult["SECTIONS"] as $arSection)
// 	{


// 		pr($arSection['ID']);
// 	}

// 	die();
?>

<div class="catalog-section-list">
	<form method="post" action="#" id="test-form">
	<?
	$TOP_DEPTH = $arResult["SECTION"]["DEPTH_LEVEL"];
	$CURRENT_DEPTH = $TOP_DEPTH;

	foreach($arResult["SECTIONS"] as $arSection)
	{
		$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
		$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BCSL_ELEMENT_DELETE_CONFIRM')));
		if($CURRENT_DEPTH < $arSection["DEPTH_LEVEL"])
		{
			echo "\n",str_repeat("\t", $arSection["DEPTH_LEVEL"]-$TOP_DEPTH),"<ul>";
		}
		elseif($CURRENT_DEPTH == $arSection["DEPTH_LEVEL"])
		{
			echo "</li>";
		}
		else
		{
			while($CURRENT_DEPTH > $arSection["DEPTH_LEVEL"])
			{
				echo "</li>";
				echo "\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH),"</ul>","\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH-1);
				$CURRENT_DEPTH--;
			}
			echo "\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH),"</li>";
		}

		$count = $arParams["COUNT_ELEMENTS"] && $arSection["ELEMENT_CNT"] ? "&nbsp;(".$arSection["ELEMENT_CNT"].")" : "";

		if ($_REQUEST['SECTION_ID']==$arSection['ID'])
		{
			$link = '<b>'.$arSection["NAME"].$count.'</b>';
			$strTitle = $arSection["NAME"];
		}
		else
		{
			// $link = '<a href="'.$arSection["SECTION_PAGE_URL"].'">'.$arSection["NAME"].$count.'</a>';
			// $link = '<a href="'.$arSection["SECTION_PAGE_URL"].'">'.$arSection["NAME"].$count.'</a>';
			$link = '<input type="checkbox" class="checkbox" name="'.$arSection['CODE'].'" value="'.$arSection['ID'].'"><span>'.$arSection["NAME"].'</span>';
		}

		echo "\n",str_repeat("\t", $arSection["DEPTH_LEVEL"]-$TOP_DEPTH);
		?><li id="<?=$this->GetEditAreaId($arSection['ID']);?>"><?=$link?><?

		$CURRENT_DEPTH = $arSection["DEPTH_LEVEL"];
	}

	while($CURRENT_DEPTH > $TOP_DEPTH)
	{
		echo "</li>";
		echo "\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH),"</ul>","\n",str_repeat("\t", $CURRENT_DEPTH-$TOP_DEPTH-1);
		$CURRENT_DEPTH--;
	}
	?>
	<button id="sbm_btn" class="flat2" type="submit">Получить CSV файл</button>
	<div id="result" class="csv"></div>
	</form>
 
</div>
<?=($strTitle?'<br/><h2>'.$strTitle.'</h2>':'')?>
<!-- <input type="checkbox" name="option2" class="checkbox" value="a2" >Windows 2000<Br> -->
<script>
  $(function(){
		$(":checkbox").change(function(event){
		    checkbox(this);
		});

		$('#sbm_btn').on('click', function (e) {
			e.preventDefault();
			// $('.message').append('ѕроисходит выгрузка файла на сервер, ждите!');
	
			let sections = checkbox(e);

			if(sections.length>0){
				
				sections = JSON.stringify(sections);
				// console.log(sections);

				$.ajax( {
				url: '<?=$templateFolder?>/export_form.php',
				type: 'GET',
				// data: {sections:'["115","118"]'},
				data: {sections:sections},
		        dataType: 'json',
		        contentType: 'application/json',
				success: function(data) {
					console.log(data);
					$('#result').empty();
					// var result;	
					let file = 'Ссылка на выгруженный файл: <a href="'+data.path+'">'+data.name+'</a>';
					$('#result').append(file);
					$('#result').css({'display':'inline-block'});

					// if (data=='OK'){
					// 	// здесь ставим своЄ уведомление о том, что сообщение отправлено
					// 	result = 'ваш документ загружен на сервер!';
					// }
					// if (data=='INVALID FILE SIZE'){
					// 	// здесь ставим своЄ уведомление о том, что превышен размер файла
					// 	result = 'ваш документ слишком большой для загрузки!';
					// }
					// if (data=='INVALID FILE TYPE'){
					// 	// здесь ставим своЄ уведомление о том, что не тот тип файла (не картинка)
					// 	result = 'ваш документ не подходит для загрузки!';
					// }

					// $('.message').append(result);
				},
				error: function (error) {
				    // **alert('error; ' + eval(error));**
				    console.log(error);
				}
			});

			}
			
			

		});

  });


function checkbox(e) {
	let arr = $('input:checkbox:checked').map(function () {
		  return this.value;
	}).get();

	// console.log(arr);
	return arr;
}

</script>