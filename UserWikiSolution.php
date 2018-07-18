<?php

if (isset($_GET['FB_ID'])) {

	date_default_timezone_set("Asia/Taipei");  //調整時區
	
	$FB_ID = $_GET['FB_ID'];   //messenger user id
	$Subject=$_GET['Subject'];
	
	
	//connection to data
	$servername = "localhost";
	$username = "Qbot";
	$password = "12345678";
	$dbname = "qbot";

	$conn = new mysqli($servername, $username, $password, $dbname);

	//顯示user最新的hashcode
	$Selectsql="SELECT HashCode FROM `question_chatbot_result` WHERE `FB_ID` = '$FB_ID' ORDER BY id DESC limit 1";
	
	$Selectresult = $conn->query($Selectsql);
	
	
	if ($Selectresult->num_rows > 0) {
    	// output data of each row
		$row = $Selectresult->fetch_assoc();
	    $HashCode=$row["HashCode"];
    } else {
        echo "0 results";
    }  
	
	
	
	
	#####################################################################
		//將 user_ask_question 資料表中的 KeyWikiLink 欄位取出，顯示給user
	$Selectsql="SELECT KeyWikiLink FROM `user_ask_question` WHERE `HashCode` = '$HashCode' ORDER BY id DESC limit 1";
		
		$Selectresult = $conn->query($Selectsql);
		
		
		if ($Selectresult->num_rows > 0) {
			// output data of each row
			$row = $Selectresult->fetch_assoc();
			$KeyWikiLink=$row["KeyWikiLink"]; //字串
		} else {
			echo "0 results";
		}
		$myJSON = json_decode($KeyWikiLink);  //字串變json格式
		$Wikikey_array=$myJSON->{"key"}; //取json中value標籤的值(value多個值組合成"陣列"array")
		$Wikilink_array=$myJSON->{"link"};
		
		//只回報錯誤 移除警告資訊
		error_reporting(E_ERROR | E_PARSE);
		$objs[] -> text = "下方是相關的百科連結喔！";
		//包內層
		 foreach($Wikikey_array as $index => $key)
		{	
			
			$objs[] -> text = $key.":";
			$objs[] -> text = $Wikilink_array[$index];
			//$objs[] ->$key=$link_array[$index];
			
		} 
		//包外層
		$Wikijson -> messages = $objs;
		//編碼
		$Wikijson_message=json_encode($Wikijson,JSON_UNESCAPED_UNICODE);
		echo $Wikijson_message;

	
		
}
?>