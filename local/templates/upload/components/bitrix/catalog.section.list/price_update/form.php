<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
//проверяем размер файла
if ($_FILES['file']['error']=='1'){
    echo 'INVALID FILE SIZE';
    die();
}
	// echo 'OK';
	echo json_encode($_FILES['file']);
	die;
// если есть вложение
if (!empty($_FILES['file']['tmp_name'])) {  

 // 	$result = ['response'=>'OK'];

	// header('Content-Type: application/json');
	// echo json_encode($result);
	echo 'OK';
	die;
    
    // удаляем старые файлы
    // delete();

    // $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');
    
    // //здесь проверяем расширение (csv):
    // if(in_array($_FILES['file']['type'], $mimes)){
    //     // Закачиваем файл в /tmp_img
    //     $name = $_FILES['file']['name'];
    //     $uploads_dir = $_SERVER['DOCUMENT_ROOT'].'/upload/csv/import';
    //     // echo  $uploads_dir;
    //     $is_moved = move_uploaded_file($_FILES['file']['tmp_name'], "$uploads_dir/$name");
    //     if ($is_moved){
            
    //         // IBlockfeedCSV_2($name); 
    //         echo 'OK';

    //     }else{
    //         echo 'ERROR FILE MOVED';
    //     }
    // }else{
    //     echo 'INVALID FILE TYPE';
    // }
}