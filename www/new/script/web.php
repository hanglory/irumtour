<?php
header("Content-Type: text/html; charset=UTF-8");

/* FIXME. change strBusNumber !! */
$url = "https://band.us/discover/page-search/%ED%83%9C%EA%B5%AD%20%EA%B3%A8%ED%94%84%20%ED%88%AC%EC%96%B4";
$ref_url = "";
$data = array();

function curl($url, $ref_url, $data)
{
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.94 Safari/537.36");
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	curl_setopt($ch, CURLOPT_URL, $url);
	//curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_REFERER, $ref_url);

	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	ob_start();

	$res = curl_exec ($ch);
	$result = explode("\n", $res); // 7
	
	var_dump($res);

	system("date");

	return 1;
	ob_end_clean();
	curl_close($ch);
	unset($ch);
}

$result = curl($url, $ref_url, $data);
?>