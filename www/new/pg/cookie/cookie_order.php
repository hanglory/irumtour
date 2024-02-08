<?php

$COOKIEPAYMENTS_ID = "cookiepayments에서 발급받은 ID";
$COOKIEPAYMENTS_KEY = "cookiepayments에서 발급받은 연동키";

function uf_get_datetime_with_microseconds() {
    list($usec, $sec) = explode(" ", microtime());
    $usec = str_replace('0.', '', $usec); // 0.22532500 -> 22532500
    return date("YmdHis").$usec;
}
$order_no = uf_get_datetime_with_microseconds();

// //헤더정보
$headers = array(); 
array_push($headers, "content-type: application/json; charset=utf-8");
array_push($headers, "ApiKey:".$COOKIEPAYMENTS_KEY."");

$cookiepayments_url = "https://www.cookiepayments.com/pay/ready";

$request_data_array = array(
	'API_ID' => $COOKIEPAYMENTS_ID,
	'ORDERNO'=> $order_no,
	'PRODUCTNAME'=> '테스트상품',
	'AMOUNT'=> '100',
	'BUYERNAME'=> '홍길동',
	'BUYEREMAIL'=> '이메일',
	'RETURNURL'=>  'https://yourDomain/result.html',
    'PAYMETHOD'=> 'CARD',
	'PRODUCTCODE'=> '',	
	'BUYERID'=> '',
	'BUYERADDRESS'=> '',
	'BUYERPHONE'=> '',
	'ETC1'=> '', //추가 필드1
    'ETC2'=> '', //추가 필드2
    'ETC3'=> '', //추가 필드3
    'ETC4'=> '', //추가 필드4
    'ETC5'=> ''
);

$cookiepayments_json = json_encode($request_data_array, TRUE);

$ch = curl_init(); // curl 초기화
curl_setopt($ch,CURLOPT_URL, $cookiepayments_url);
curl_setopt($ch,CURLOPT_POST, false);
curl_setopt($ch,CURLOPT_POSTFIELDS, $cookiepayments_json);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
curl_setopt($ch,CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);
curl_close($ch);

var_dump($response);

?>