<?
session_start();
header("Content-Type: text/html; charset=UTF-8");

$arr = explode("/www",$_SERVER['DOCUMENT_ROOT']);
$path_root =$arr[0];

#### include
include_once($path_root."/www/new/include/config.php");
include_once($path_root."/www/new/include/cmp_config.php");
include_once($path_root."/www/new/include/fun_basic.php");
include_once($path_root."/www/new/include/vars.php");
include_once($path_root."/www/new/public/inc/site.inc");

#### DB Connent
include_once($path_root . "/info/info_dbconn.php");
include_once($path_root."/www/new/lib/class.$database.php");

$dbo = new MiniDB($info);


reset($_POST);



$url ="https://www.cookiepayments.com";//라이브
//$url ="https://sandbox.cookiepayments.com";//테스트


/* 토큰 발행 API */
$tokenheaders = array(); 
array_push($tokenheaders, "content-type: application/json; charset=utf-8");

$token_url = "${url}/payAuth/token";

$token_request_data = array(
    'pay2_id' => $PG_API_ID,
    'pay2_key'=> $PG_API_KEY,
);

foreach ($token_request_data as $key => $value) {
    checkVar($key,$value);
}


$req_json = json_encode($token_request_data);

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

foreach ($RES_STR as $key => $value) {
    checkVar($key,$value);
}
exit;


if($RES_STR['RTN_CD'] == '0000'){

    $headers = array(); 
    array_push($headers, "content-type: application/json; charset=utf-8");
    array_push($headers, "TOKEN: ".$RES_STR['TOKEN']);

    $cookiepayments_url = "${url}/api/settlement";

    $request_data_array = array(
        'API_ID' => $PG_API_ID,
        'TID' => '조회를 원하는 PG사 거래번호',
        'DATE' => '조회를 원하는 지급일 ex)20240101',        
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
}
?>