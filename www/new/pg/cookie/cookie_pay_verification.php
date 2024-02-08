<?php

$COOKIEPAYMENTS_ID = "cookiepayments에서 발급받은 ID";
$COOKIEPAYMENTS_KEY = "cookiepayments에서 발급받은 연동키";

/* 토큰 발행 API */
$tokenheaders = array(); 
array_push($tokenheaders, "content-type: application/json; charset=utf-8");
$token_url = "https://www.cookiepayments.com/payAuth/token";

$token_request_data = array(
	'pay2_id' => $COOKIEPAYMENTS_ID,
	'pay2_key'=> $COOKIEPAYMENTS_KEY,
);
$req_json = json_encode($token_request_data, TRUE);

$ch = curl_init(); // curl 초기화
curl_setopt($ch,CURLOPT_URL, $token_url);
curl_setopt($ch,CURLOPT_POST, false);
curl_setopt($ch,CURLOPT_POSTFIELDS, $req_json);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT ,3);
curl_setopt($ch,CURLOPT_TIMEOUT, 20);
curl_setopt($ch, CURLOPT_HTTPHEADER, $tokenheaders);
$RES_STR = curl_exec($ch);
curl_close($ch);
$RES_STR = json_decode($RES_STR,TRUE);
/* 여기 까지 */

/* 결제 검증 API */
if($RES_STR['RTN_CD'] == '0000'){

    $headers = array(); 
    array_push($headers, "content-type: application/json; charset=utf-8");
    array_push($headers, "TOKEN:".$RES_STR['TOKEN']."");
    array_push($headers, "ApiKey:".$COOKIEPAYMENTS_KEY."");

    $cookiepayments_url = "https://www.cookiepayments.com/api/paycert";
    $request_data_array = array(
		'tid' => 'PG사 거래고유번호',
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

    $returndata = json_decode($response,TRUE);
    print_r($returndata);
    exit;
}
