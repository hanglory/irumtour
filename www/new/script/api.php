<?
session_start();

#### Include
include_once ("../include/config.php");
include_once ("../include/fun_basic.php");


#### DB Connent
include_once ("../../../info/info_dbconn.php");
include_once ("../lib/class.$database.php");

$dbo = new MiniDB($info);


	$url = "https://api.golf-course-database.com:8000/clubs";

    $post   = array(
    	'' => ''
    	); 


    
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/xml', $additionalHeaders));
	curl_setopt($ch, CURLOPT_HEADER, true);
	curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
	curl_setopt($ch, CURLOPT_POST, false);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL,$url);
	$ret = curl_exec($ch);

	$error_msg = curl_error($ch);
	curl_close($ch);

	$arr = explode("{",$ret);
	$ret="{".$arr[1];

	// 리턴 JSON 문자열 확인
	print_r($ret);

	// JSON 문자열 배열 변환
	$retArr = json_decode($ret);

	// 결과값 출력
	print_r($retArr);

	//$resCode = $retArr->resCode;
	//$message = $retArr->resMsg;
	//$message=iconv("utf-8","euc-kr",$message);
	//checkVar("resCode",$resCode);
	//checkVar("message",$message);



?> 
