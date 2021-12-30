
// var test = 'test';
// console.log(test);

//обработка нажати¤ кнопки "обзор"
$(document).ready(function() {
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

});


$(document).on('click', '#sbm_btn', function (e) {
	e.preventDefault();
	$('.message').append('ѕроисходит загрузка файла на сервер, ждите!');
	
	$.ajax( {
		url: '/local/ajax/form.php',
		type: 'POST',
		data: new FormData( $("#form")[0] ),
		processData: false,
		contentType: false,
		success: function(data) {
			console.log(data);
			$('.message').empty();
			var result;	

			if (data=='OK'){
				// здесь ставим своЄ уведомление о том, что сообщение отправлено
				//alert('¬аш документ загружен на сервер!');
				result = 'ваш документ загружен на сервер!';
			}
			if (data=='INVALID FILE SIZE'){
				// здесь ставим своЄ уведомление о том, что превышен размер файла
				//alert('¬аш документ слишком большой дл¤ загрузки!');
				result = 'ваш документ слишком большой для загрузки!';
			}
			if (data=='INVALID FILE TYPE'){
				// здесь ставим своЄ уведомление о том, что не тот тип файла (не картинка)
				//alert('¬аш документ не подходит дл¤ загрузки!')
				result = 'ваш документ не подходит для загрузки!';
			}

			$('.message').append(result);
		}
	}); 
});