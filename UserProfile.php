<?php

if (isset($_GET['FB_ID'])) {

	date_default_timezone_set("Asia/Taipei");  //調整時區
	$Time=date("Y-m-d H:i:s");
	$FB_ID = $_GET['FB_ID'];   //messenger user id
	$FB_Name = $_GET['FB_Name'];
	$Gender=$_GET['Gender'];  //gender
	$Grade=$_GET['Grade'];
	$Subject=$_GET['Subject'];
	
	echo $Time;
	echo $FB_ID;
	echo $FB_Name;
	echo $Gender;
	echo $Grade;
	echo $Subject;
	
	
	
	$HashCode=hash('sha256',$Time.$FB_ID.$FB_Name);
	
	//connection to data
	$servername = "localhost";
	$username = "Qbot";
	$password = "12345678";
	$dbname = "qbot";

	$conn = new mysqli($servername, $username, $password, $dbname);


	
	
	
	
	$sql = "INSERT INTO `question_chatbot_result`(`HashCode`,`Time`,`FB_ID`,`FB_Name`,`Gender`,`Grade`,`Subject`) VALUES ('$HashCode','$Time','$FB_ID','$FB_Name','$Gender','$Grade','$Subject')";
	$result = $conn->query($sql);
	


	$conn->close();


}
?>