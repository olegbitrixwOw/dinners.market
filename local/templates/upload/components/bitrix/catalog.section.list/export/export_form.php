<?
define("NO_AGENT_CHECK", true);
define("NO_AGENT_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
$APPLICATION->RestartBuffer();

use Bitrix\Main\IO;
use Bitrix\Main\Application;

$result = [
	"error"=>
	"На сервер пришел не валидный запрос!"
];

if(!empty(trim($_GET['sections']))){

    $sections = json_decode(trim($_GET['sections']), true);
	// $sections = json_decode('["115","118"]', true);
	$products = getProducts($sections);

	if(!empty($products)){
		$file_name = 'csv_export';
		// $hash = '_'; 
		$hash = md5(microtime().rand(0, 9999)); 
		$fileWithPropsValue = '/upload/csv/'.$file_name.'_'.$hash.'.csv';

		if(!IO\Directory::isDirectoryExists(Application::getDocumentRoot() . '/upload/csv/')){
			IO\Directory::createDirectory(Application::getDocumentRoot() . '/upload/csv/');
		}
		$file = new IO\File(Application::getDocumentRoot().$fileWithPropsValue);
		$file->putContents(''); // очищаем файл
		putDataToCSV(Application::getDocumentRoot().$fileWithPropsValue, $products);

		
		if($file->isExists()){
		    	$result = [
		              'path'=>$fileWithPropsValue, 
		              'name'=>$file_name.'_'.$hash.'.csv',
		              'error'=>false
		    ];
		}
	}

	// 
	//$result = json_decode($_REQUEST['param'], true);
	// $result = ['tttt'=>111];
	// $result = $products;
}

header('Content-Type: application/json');
echo json_encode($result);
die;