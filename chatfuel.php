<?php
//只回報錯誤移除警告資訊
error_reporting(E_ERROR | E_PARSE);

//取得影片陣列
$url_array = array("url_1","url_2","url_3","url_4");

//包內層
foreach ($url_array as $value) { 
	$objs[] -> text = $value;
}
//包外層
$json -> messages = $objs;

//編碼
echo json_encode($json);
?>