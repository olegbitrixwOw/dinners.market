
function checkbox(e) {
	let arr = $('input:checkbox:checked').map(function () {
		  return this.value;
	}).get();

	console.log(arr);
	return arr;
}

$(":checkbox").change(function(event){
    checkbox(this);
});



$(document).on('click', '#sbm_btn', function (e) {
	e.preventDefault();
	// $('.message').append('ѕроисходит выгрузка файла на сервер, ждите!');
	let sections = JSON.stringify(checkbox(e));
	console.log(sections);
	
	$.ajax( {
		url: '/local/ajax/export_form.php',
		type: 'POST',
		data: sections,
		processData: false,
		type: 'post',
        dataType: 'json',
        contentType: 'application/json',
		success: function(data) {
			console.log(data);
			// $('.message').empty();
			// var result;	

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
		}
	})

});