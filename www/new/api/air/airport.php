<?php
include '/home/hosting_users/irumtour/www/new/api/api_common.php';
header("Content-Type: text/html; charset=UTF-8");


$key = "0g7LgnItlv2wg%2Bx%2BKcBPQKYOkBRi1%2Bt94uaZFjbLDFaX6zKtUBy5CqB12z6yLidx2kNdDCiany4gEc2ZC644WQ%3D%3D";


if($mode=="airport"){

	$ch = curl_init();
	$url = 'http://openapi.airport.co.kr/service/rest/AirportCodeList/getAirportCodeList'; /*URL*/
	$queryParams = '?' . urlencode('ServiceKey') . '=' . $key; /*Service Key*/

	curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$response = curl_exec($ch);
	$error_msg = curl_error($ch);
	curl_close($ch);

	$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA); 
	$json = json_encode($xml); 
	$arr = json_decode($json,TRUE);

	while(list($key,$val)=each($arr[body][items][item])){
		//checkVar($key,$val);

		$cityKor="";
		$cityEng="";
		$cityCode="";

		while(list($key2,$val2)=each($val)){
			//checkVar($key2,$val2);

			if($key2=="cityKor") $cityKor = $val2;
			elseif($key2=="cityEng") $cityEng = $val2;
			elseif($key2=="cityCode") $cityCode = $val2;
		}

		$sql = "
			insert into cmp_air_airport (
				cityKor,
				cityEng,
				cityCode
			) values (
				'$cityKor',
				'$cityEng',
				'$cityCode'			
			)
		";
		$dbo->query($sql);
		//checkVar(mysql_error(),$sql);	
	}

}

echo "
	<script>
		alert('공항코드가 갱신되었습니다.');
		parent.location.reload();
	</script>
";

?>