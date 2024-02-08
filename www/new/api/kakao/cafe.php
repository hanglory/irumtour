<?php
include '/home/hosting_users/irumtour/www/new/api/api_common.php';
header("Content-Type: text/html; charset=UTF-8");
//header('Content-Type: application/json');

//개발가이드 : https://developers.kakao.com/docs/latest/ko/daum-search/dev-guide#search-cafe
$query = "남해아난티";


$query = urlencode($query);
$url = "https://dapi.kakao.com/v2/search/cafe";
$url .="?query=".$query;
$url .="&sort=recency";
$url .="&page=44";
$url .="&size=20";

	$post = array(
		'query' => $query,
		'sort' => "recency",
		'size' => 50
		); 


	$headers = array(
	    "Authorization: KakaoAK ".$KAKAO_RESET_KEY
	);	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, false);
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$response = curl_exec($ch);
	$error_msg = curl_error($ch);
	curl_close($ch);

	$json = json_decode($response,true);

	var_dump($json);


	checkVar("error_msg",$error_msg);
	checkVar("검색어",$query);
	checkVar("검색된 문서 수",$json[meta][total_count]);
	checkVar("노출 가능 문서 수",$json[meta][pageable_count]);
	checkVar("현재 페이지가 마지막 페이지인지 여부",$json[meta][is_end]);

	while(list($key,$val)=each($json[documents])){
		while(list($key2,$val2)=each($val)){
			//checkVar($key2,$val2);
			if($key2=="title") checkVar($key2,$val2);
		}
	}

?>