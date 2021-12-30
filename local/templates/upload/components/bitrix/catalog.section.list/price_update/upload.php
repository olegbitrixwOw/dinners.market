<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");

if(isset($_FILES) && !empty($_FILES)) {
    
    if(is_uploaded_file($_FILES['file']['tmp_name'])) {
        $sourcePath = $_FILES['file']['tmp_name'];
        $targetPath = $_SERVER['DOCUMENT_ROOT'].'/upload/csv/import/'.$_FILES['file']['name'];
        
        if(move_uploaded_file($sourcePath,$targetPath)) {
        ?>
            Ссылка на загруженный файл: <a href="<?php echo $targetPath; ?>"><?php echo $_FILES['file']['name']; ?></a>
        <?
            $name = $_FILES['file']['name'];

            $array_line_full = getCSV($csv_file = $name);
            foreach($array_line_full as $itemFile){
                // var_dump($item);
                $product = getProduct($itemFile[0]);
                pricesUpdate($product['ID'], $itemFile[1]);
                // updateProperties($product['ID'], $itemFile[1]);
            }
        }
    }

}
die;
?>