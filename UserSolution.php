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
	
	//呼叫python 將 $HashCode 傳給 python 去撈user的 keySolution
	$python = "C:\\ProgramData\\Anaconda3\\python.exe";
	$pyscript = "C:\\xampp\\htdocs\\Qbot\\api\\videoSolution\\videoSolution.py";
	$result = shell_exec("$python $pyscript $HashCode $FB_ID");
	
	
	//將 user_ask_question 資料表中的 KeyVideoLink 欄位取出，顯示給user
	
	$Selectsql="SELECT KeyVideoLink FROM `user_ask_question` WHERE `HashCode` = '$HashCode' ORDER BY id DESC limit 1";
	
	$Selectresult = $conn->query($Selectsql);
	
	
	if ($Selectresult->num_rows > 0) {
    	// output data of each row
		$row = $Selectresult->fetch_assoc();
	    $KeyVideoLink=$row["KeyVideoLink"]; //字串
    } else {
        echo "0 results";
    }  
	
	
	//$KeyVideoLink 為json格式，取出 key和link 的值
	$myJSON = json_decode($KeyVideoLink);  //字串變json格式
	//echo sizeof($myJSON->{"value"}); //取json中value標籤的長度 "VALUE為SolutionKeyword"
	#$array=$myJSON->{"value"};//取json中value標籤的值(value多個值組合成"陣列"array")
	//echo $KeyVideoLink;
	$Videokey_array=$myJSON->{"key"}; //取json中value標籤的值(value多個值組合成"陣列"array")
	$Videolink_array=$myJSON->{"link"};
	
	//echo $key_array[0];
	//echo $link_array[0];
	$len=sizeof($Videolink_array);
	//echo $len;
	
	
	//echo $key_array;
	
	//只回報錯誤 移除警告資訊
	error_reporting(E_ERROR | E_PARSE);
	if($len==0){
		
		$blank_json='{
		 "messages": [
		   {"text": "我聽不懂你說甚麼"},
		   {"text": "請再說一次"}
		 ]
		}';
		//編碼
		echo $blank_json;
	} 
	
	if($len!=0){
		
		//包內層
		foreach($Videokey_array as $index => $key)
		{
			$objs[] -> text = $key.":";
			$objs[] -> text = $Videolink_array[$index];
			//$objs[] ->$key=$link_array[$index];
			
		}
		
		//包外層
		$newjson -> messages = $objs;
		//編碼
		$json_message=json_encode($newjson,JSON_UNESCAPED_UNICODE);
		echo $json_message;
	}

	
	/* //影片的圖片
	error_reporting(E_ERROR | E_PARSE);
	
	function video_id($url){
		$parse_url = parse_url($url);
		$query = [];
		parse_str($parse_url['query'], $query);
		if (! empty($query['v'])) {
			return $query['v'];
		}

		$t = explode('/', trim($parse_url['path'], '/'));
		
		foreach ($t as $k => $v) {
			if ($v == 'v') {
				if (! empty($t[$k + 1])) {
					return $t[$k + 1];
				}
			}
		}
		
			return $url;
	}

	function video_img($url){
		$iner -> type = "image";
		$iner -> payload -> url = "https://img.youtube.com/vi/".video_id($url)."/default.jpg";
		echo $iner;
		return $iner;
	}


	foreach ($Videolink_array as $value) {
		$objs[] -> attachment = video_img($value);
		$objs[] -> text = $value;
	}
	//包外層
	$json -> messages = $objs; */



	//編碼
	//echo json_encode($json,JSON_UNESCAPED_UNICODE); 


	
		
		#####################################################################
		//將 user_ask_question 資料表中的 KeyWikiLink 欄位取出，顯示給user
		
		/* $Selectsql="SELECT KeyWikiLink FROM `user_ask_question` WHERE `HashCode` = '$HashCode' ORDER BY id DESC limit 1";
		
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
		
		//包內層
		foreach($Wikikey_array as $index => $key)
		{	
			$objs[] -> text = "下方是相關的百科連結喔！";
			$objs[] -> text = $key.":";
			$objs[] -> text = $Wikilink_array[$index];
			//$objs[] ->$key=$link_array[$index];
			
		}
		//包外層
		$Wikijson -> messages = $objs;
		//編碼
		$Wikijson_message=json_encode($Wikijson,JSON_UNESCAPED_UNICODE);
		echo $Wikijson_message; */
	//}
	
	
	/*
	//撈資料庫中KeySolution的欄位，知道user問題的解答
	
	//顯示user最新的hashcode
	$Selectsql="SELECT KeySolution FROM `user_ask_question` WHERE `HashCode` = '$HashCode' ORDER BY id DESC limit 1";
	
	$Selectresult = $conn->query($Selectsql);
	if ($Selectresult->num_rows > 0) {
			// output data of each row
			$row = $Selectresult->fetch_assoc();
			$KeySolution=$row["KeySolution"];  //
		} else {
			echo "0 results";
		}  
		//echo $KeySolution;  //字串
	
	
	

	//$KeySolution為json格式，取出value的值
	#$myJSON = json_decode($KeySolution);  //字串變json格式
	/*
	$newJSON=json_encode($myJSON,JSON_UNESCAPED_UNICODE);
	echo $newJSON;
	//Call EXE
	$path='C:\xampp\htdocs\Qbot\api\videoSolution\dist';
	chdir($path);
	//$command_api='activate api';
	//shell_exec($command_api);
	$command = 'START videoSolution.exe '.$KeySolution.' '.$FB_ID; //用json格式的字串，傳給python，而python再利用json.loads()方法變成dictionary
	$output = shell_exec($command);*/
	
	
	
	//echo sizeof($myJSON->{"value"}); //取json中value標籤的長度 "VALUE為SolutionKeyword"
	#$array=$myJSON->{"value"};//取json中value標籤的值(value多個值組合成"陣列"array")
	#print_r($array); #print_r專用來印出array
	
	/*
	$string = '"'.rtrim(implode('","', $array), '","').'"';  //array 變成string
	$string = '['.$string.']';
	$string ="'$string'" ;
	
	
	$string ="'$KeySolution'" ;
	#$string = '.'['.'.rtrim(implode('","', $array), '","').'.']'.';  //array 變成string
	//$myJSON=var_dump($KeySolution);
	$MyString=json_encode($KeySolution,JSON_UNESCAPED_UNICODE);
	$newJSON = json_decode($MyString);  //字串變json格式
	$myJSON="'".$newJSON."'";
	//print(gettype($myJSON));
	//print($myJSON);
	
	$myJSON1 = base64_encode($KeySolution);

	
	//$result = shell_exec('python /videoSolution/videoSolution.py '. $KeySolution.' '.$FB_ID);
	#exec("C:\\xampp\\htdocs\\Qbot\\api\\videoSolution\\videoSolution.py  '$myJSON' '$FB_ID'");
	
	//$result = shell_exec('python C:/xampp/htdocs/Qbot/api/videoSolution/videoSolution.py '.$myJSON.' '.$FB_ID);	
	//法1
	
	#用array傳值給python
	//呼叫PYTHON取找適合的影片 (solution)
	//Call EXE
	//$path='C:\xampp\htdocs\Qbot\api\videoSolution\dist';
	//chdir($path);
	//$command_api='activate api';
	//shell_exec($command_api);
	
	#$command = 'START videoSolution.exe '.$myJSON1.' '.$FB_ID; //用json格式的字串，傳給python，而python再利用json.loads()方法變成dictionary
	#$output = shell_exec($command);
	
	/*
	//法2
	foreach ($array as $valueObj) { //foreach是專用來將陣列裡的值取出
		echo "$valueObj ";
		#用一個物件參數傳值給python
		//呼叫PYTHON取找適合的影片 (solution)
		//Call EXE
		/*
		$path='C:\xampp\htdocs\Qbot\api\videoSolution\dist';
		chdir($path);
		//$command_api='activate api';
		//shell_exec($command_api);
		$command = 'START videoSolution.exe '.$valueObj.' '.$FB_ID;
		$output = shell_exec($command);
		
	}
	
	
	
	
	$str=$array[0]; //取json中value標籤特定位置的值
	echo $str;
*/
	  
/*

		
	//將KeySolution對應至 ask_question_solution 資料表中的UserKeyword欄位，才能找出

	$Selectsql="SELECT SolutionKeyword FROM `ask_question_solution` WHERE `HashCode` = '$HashCode' AND UserKeyword='$valueObj' ORDER BY id DESC limit 1";
	
	$Selectresult = $conn->query($Selectsql);
	if ($Selectresult->num_rows > 0) {
			// output data of each row
			$row = $Selectresult->fetch_assoc();
			$KeySolution=$row["KeySolution"];
		} else {
			echo "0 results";
		}  
		echo $KeySolution;
		*/
		
}
?>