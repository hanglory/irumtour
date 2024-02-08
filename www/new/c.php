<?
header("Content-Type: text/html; charset=UTF-8");
$server_url = "http://toursketch.co.kr";
$uri="/v1/partners/products/valid/tour/";
$url = $server_url.$uri;
$headers = array("application/x-www-form-urlencoded;charset=UTF-8");
//http://toursketch.co.kr/v1/partners/products/valid/tour/?prdId=218647,289436&clientCode=W&startDay=20200201
$data = array( 
	'prdId' => '218647,289436',
	'clientCode' => 'W',
	'startDay' => '20200201'
	);

//$headers = array("Content-Type:multipart/form-data");

$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_URL, $server_url.$uri);
$ret = curl_exec($ch);


$error_msg = curl_error($ch);
curl_close($ch);

// 리턴 JSON 문자열 확인
print_r($ret . PHP_EOL);

// JSON 문자열 배열 변환
$retArr = json_decode($ret);

// 결과값 출력
//print_r($retArr);
?>