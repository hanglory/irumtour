<?php
// 주의
// 수기결제는 PG 상점아이디 발급 시 수기결제 가능한 상점아이디를 발급받아야 수기결제 이용하실 수 있습니다.

function uf_get_datetime_with_microseconds() {
    list($usec, $sec) = explode(" ", microtime());
    $usec = str_replace('0.', '', $usec); // 0.22532500 -> 22532500
    return date("YmdHis").$usec;
}
$order_no = uf_get_datetime_with_microseconds();

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

if($RES_STR['RTN_CD'] == '0000'){

    $headers = array(); 
    array_push($headers, "content-type: application/json; charset=utf-8");
    array_push($headers, "TOKEN:".$RES_STR['TOKEN']."");
    array_push($headers, "ApiKey:".$COOKIEPAYMENTS_KEY."");

    $cookiepayments_url = "https://www.cookiepayments.com/keyin/payment";

    $request_data_array = array(
        'API_ID' => $COOKIEPAYMENTS_ID,
        'ORDERNO'=> $order_no,
        'PRODUCTNAME'=> '테스트상품',
        'AMOUNT'=> '100',
        'BUYERNAME'=> '홍길동',
        'BUYEREMAIL'=> '이메일',
        'CARDNO'=>'카드번호',
        'EXPIREDT'=>'유효기간', //ex) YYMM
        'PRODUCTCODE'=> '', //선택
        'BUYERID'=> '', //선택
        'BUYERADDRESS'=> '', //선택
		'BUYERPHONE'=> '', // 선택
		'QUOTA'=> '', // 할부 기능 00:0개월,01:1개월,12:12개월까지 default : 00 
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

    $returndata = json_decode($response,TRUE);
    print_r($returndata);
    exit;
}
?>