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
	<form  method="post" action="#" id="upload-form" style="display:flex;flex-flow: row nowrap; justify-content: center;">

		    	<div class="file-block" style="margin: 2em 0;">
					<div class="file-div" id="browse">Обзор</div>
					<input type="file" id="browse-real" name="file" >
				</div>
				<button id="sbm_btn" class="flat2" type="submit" style="margin: 10px 0;">Загрузить CSV файл</button>
	</form>
    <div class="message"></div>
</div>
<?=($strTitle?'<br/><h2>'.$strTitle.'</h2>':'')?>

<script>
 
//обработка нажати¤ кнопки "обзор"
$(function(){
	var browse = document.getElementById("browse");
	var browse_real = document.getElementById("browse-real");
	// console.log(browse);
	// console.log(browse_real);	

	browse.addEventListener("click", function(e) {
		browse_real.click();
		e.preventDefault();
		console.log('click');

	}, false);


	browse_real.addEventListener('change', function() {
		var val = browse_real.value;
		//в opera и chrome путь полный с косыми чертами - разделим на массив с разделител¤ми "\" и отобразим последний элемент:
		var mas = val.split('\\')
		$('.file-name').html(mas[mas.length - 1]);
		console.log('change');

	});

	var files;
 	// Вешаем функцию на событие
	// Получим данные файлов и добавим их в переменную
	$('input[type=file]').change(function(){
		files = this.files;
	});

		


	$('#sbm_btn').on('click', function (e) {
		e.preventDefault();
		console.log('AJAX');
		// console.log($('input[type=file]')[0].files[0]);
		// console.log(new FormData($("#upload-form")[0]));
		// console.log(files);

		var data = new FormData();
	    // $.each( files, function( key, value ){
	    //     data.append( key, value );
	    //     // console.log(value);
	    // });
	    data.append('csv', $('input[type=file]')[0].files[0]);

	    // console.log(data);

	     console.log(data.getAll('csv'));
 

		// $('.message').append('ѕроисходит загрузка файла на сервер, ждите!');

		// console.log('AJAX');
	
		$.ajax( {
			url: '<?=$templateFolder?>/form.php',
			type: 'POST',
			data: data,
			processData: false,
			contentType: false,
			success: function(data) {
				console.log(data);
				// $('.message').empty();
				// var result;	

				// if (data=='OK'){
				// 	// здесь ставим своЄ уведомление о том, что сообщение отправлено
				// 	//alert('¬аш документ загружен на сервер!');
				// 	result = 'ваш документ загружен на сервер!';
				// }
				// if (data=='INVALID FILE SIZE'){
				// 	// здесь ставим своЄ уведомление о том, что превышен размер файла
				// 	//alert('¬аш документ слишком большой дл¤ загрузки!');
				// 	result = 'ваш документ слишком большой для загрузки!';
				// }
				// if (data=='INVALID FILE TYPE'){
				// 	// здесь ставим своЄ уведомление о том, что не тот тип файла (не картинка)
				// 	//alert('¬аш документ не подходит дл¤ загрузки!')
				// 	result = 'ваш документ не подходит для загрузки!';
				// }

				// $('.message').append(result);
			},
				error: function (error) {
				    // **alert('error; ' + eval(error));**
				    console.log(error);
				}
		}); 

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