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
?>

<div class="catalog-section-list">
	<form action="#" method="POST" id="upload-form">
		<div class="file-block" style="margin: 2em 0;">
        <input type="file" name="file">
        <input type="submit" value="Upload" class="flat2" >
        </div>
    </form>
    <div class="message"></div>
    <div class="img"></div>
</div>
<?=($strTitle?'<br/><h2>'.$strTitle.'</h2>':'')?>

<script>
 
//обработка нажати¤ кнопки "обзор"
$(function(){
	$("#upload-form").submit(function(e){
		e.preventDefault();
        console.log('AJAX');
		$.ajax({
            url: '<?=$templateFolder?>/upload.php',
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            // this part is progress bar
            xhr: function () {
                var xhr =  new XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        percentComplete = parseInt(percentComplete * 100);
                        //console.log(percentComplete);
                        $('progress').val(percentComplete);  
                    }
                }, false);
                return xhr;
            },
            success: function (data) {
                $('.img').html(data);
            }
        });
	});
});



</script>