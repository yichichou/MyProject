<?php
//只回報錯誤移除警告資訊
error_reporting(E_ERROR | E_PARSE);

//取得影片陣列
$url_array = array("https://www.youtube.com/watch?v=3DRDy12RcGw","https://www.youtube.com/watch?v=BKVan9W9v8E",'https://www.youtube.com/watch?v=RCfTVQTRl9E');

function video_img($url){
	$iner -> type = "image";
	$iner -> payload -> url = "https://img.youtube.com/vi/".video_id($url)."/default.jpg";
	return $iner;
}


foreach ($url_array as $value) {
	$objs[] -> attachment = video_img($value);
	$objs[] -> text = $value;
}
//包外層
$json -> messages = $objs;

function video_id($url)
{
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

//編碼
echo json_encode($json);
?>