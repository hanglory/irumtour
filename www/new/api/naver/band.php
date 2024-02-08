<?
include '/home/hosting_users/irumtour/www/new/api/api_common.php';
header("Content-Type: text/html; charset=UTF-8");

exit;
/*
$NAVER_Client_ID="339165200";
$NAVER_Client_Secret="NMMLoJyB1i5XYh6aLzvQ7mBxHbFSJ3vZ";
$NAVER_Access_Token ="ZQAAAU7pgRVmZ_8b04LwSvqzqTUzHf9BXsOsQQKGT-xq1gu1cUonTs0ejr0-rsAxktl3H7Y7BkuIZN9qRuJKeVYczB6UC5lKFF2rQGdVxcEjoPO1";
$NAVER_redirect_uri="https://irumtour.net/new/api/naver/band.php";
*/


if($mode=="cafe"){
	/*카페목록*/
	$url = "https://openapi.band.us/v2.1/bands";
	$url .="?access_token=".$NAVER_Access_Token;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, false);
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$response = curl_exec($ch);
	$error_msg = curl_error($ch);
	curl_close($ch);

	$json = json_decode($response,true);

	$BAND_NAMES="";
	$BAND_KEYS="";
	while(list($key,$val)=each($json[result_data][bands])){
		while(list($key2,$val2)=each($val)){
			if($key2=="name") $BAND_NAMES .=",".$val2;
			if($key2=="band_key") $BAND_KEYS .=",".$val2;
		}
	}

	checkVar("BAND_NAMES",$BAND_NAMES);
	checkVar("BAND_KEYS",$BAND_KEYS);

}elseif($mode=="list"){

	/*카페목록*/
	$band_key= "AADWFryD4Ks2C9n6x0iEJymH";
	$url = "https://openapi.band.us/v2/band/posts";
	$url .="?access_token=".$NAVER_Access_Token;
	$url .="&band_key=".$band_key;
	if($after) $url .="&after=".$after;
	$url .="&limit=20";
	$url .="&locale=ko_KR"; //ko_KR

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, false);
	//curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$response = curl_exec($ch);
	$error_msg = curl_error($ch);
	curl_close($ch);

	$json = json_decode($response,true);

	$after =$json[result_data][paging][next_params][after];
	checkVar("after",$after);
	while(list($key,$val)=each($json[result_data][items])){
		//checkVar($key,$val);
		while(list($key2,$val2)=each($val)){	
			checkVar("author",$val2["name"]);
			checkVar($key2,$val2);
		}
	}

}
exit;


// $query = urlencode($query);
// $url = "https://dapi.kakao.com/v2/search/cafe";
// $url .="?query=".$query;
// $url .="&sort=recency";
// $url .="&page=44";
// $url .="&size=20";

// 	$post = array(
// 		'query' => $query,
// 		'sort' => "recency",
// 		'size' => 50
// 		); 


// 	$headers = array(
// 	    "Authorization: KakaoAK ".$KAKAO_RESET_KEY
// 	);	
// 	$ch = curl_init();
// 	curl_setopt($ch, CURLOPT_HEADER, false);
// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// 	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// 	curl_setopt($ch, CURLOPT_URL, $url);
// 	curl_setopt($ch, CURLOPT_POST, false);
// 	//curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
// 	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
// 	$response = curl_exec($ch);
// 	$error_msg = curl_error($ch);
// 	curl_close($ch);

// 	$json = json_decode($response,true);

// 	var_dump($json);


// 	checkVar("error_msg",$error_msg);
// 	checkVar("검색어",$query);
// 	checkVar("검색된 문서 수",$json[meta][total_count]);
// 	checkVar("노출 가능 문서 수",$json[meta][pageable_count]);
// 	checkVar("현재 페이지가 마지막 페이지인지 여부",$json[meta][is_end]);

// 	while(list($key,$val)=each($json[documents])){
// 		while(list($key2,$val2)=each($val)){
// 			//checkVar($key2,$val2);
// 			if($key2=="title") checkVar($key2,$val2);
// 		}
// 	}
?>