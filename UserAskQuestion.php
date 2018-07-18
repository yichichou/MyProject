<?php

if (isset($_GET['FB_ID'])) {

	date_default_timezone_set("Asia/Taipei");  //調整時區
	$Time=date("Y-m-d H:i:s");
	$FB_ID = $_GET['FB_ID'];   //messenger user id
	$FB_Name = $_GET['FB_Name'];
	$Gender=$_GET['Gender'];  //gender
	$Grade=$_GET['Grade'];
	$Subject=$_GET['Subject'];
	$Question=$_GET['Question'];
	
	
	
	
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
	
    //將user的問題存進db
	$sql = "INSERT INTO `user_ask_question`(`Time`,`HashCode`,`FB_ID`,`FB_Name`,`Gender`,`Grade`,`Subject`,`Question`) VALUES ('$Time','$HashCode','$FB_ID','$FB_Name','$Gender','$Grade','$Subject','$Question')";
	$result = $conn->query($sql);

	$conn->close();
	
	//呼叫python找關鍵字(tfidf找10個kw->kw對應db的章節)

	//Call EXE
	$path='C:\xampp\htdocs\Qbot\api\getKeyword\dist';
	chdir($path);
	//$command_api='activate api';
	//shell_exec($command_api);
	$command = 'START getKeyword.exe '.$FB_ID;
	$output = shell_exec($command);
	


}
?>