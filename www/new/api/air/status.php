<?php
include '/home/hosting_users/irumtour/www/new/api/api_common.php';
header("Content-Type: text/html; charset=UTF-8");


$key = "0g7LgnItlv2wg%2Bx%2BKcBPQKYOkBRi1%2Bt94uaZFjbLDFaX6zKtUBy5CqB12z6yLidx2kNdDCiany4gEc2ZC644WQ%3D%3D";

$mode="status";
if($mode=="status"){

	$ch = curl_init();
	$url = 'http://openapi.airport.co.kr/service/rest/FlightStatusList/getFlightStatusList'; /*URL*/
	$queryParams = '?' . urlencode('ServiceKey') . '='.$key; /*Service Key*/
	$queryParams .= '&' . urlencode('schLineType') . '=' . urlencode('D'); /**/
	$queryParams .= '&' . urlencode('schIOType') . '=' . urlencode('I'); /**/
	$queryParams .= '&' . urlencode('schAirCode') . '=' . urlencode('GMP'); /**/
	$queryParams .= '&' . urlencode('schStTime') . '=' . urlencode('0600'); /**/
	$queryParams .= '&' . urlencode('schEdTime') . '=' . urlencode('1800'); /**/
	$queryParams .= '&' . urlencode('serviceKey') . '=' . urlencode($key); /**/
	$queryParams .= '&' . urlencode('page') . '=' . urlencode('1'); /**/

	curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$response = curl_exec($ch);
	curl_close($ch);


	$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA); 
	$json = json_encode($xml); 
	$arr = json_decode($json,TRUE);

	$page = $arr[body][pageNo];
	checkVar("page",$page);

	$totalCount = $arr[body][totalCount];
	checkVar("totalCount",$totalCount);	

	$numOfRows = $arr[body][numOfRows];
	checkVar("numOfRows",$numOfRows);

	$total_page = ceil($totalCount / $numOfRows);
	checkVar("total_page",$total_page);

	$i=0;
	while(list($key,$val)=each($arr[body][items][item])){
	
		$i++;	
		//checkVar($key,$val);

		$airFln="";
		$airlineEnglish="";
		$airlineKorean="";
		$airport="";
		$arrivedEng="";
		$arrivedKor="";
		$boardingEng="";
		$boardingKor="";
		$city="";
		$etd="";
		$gate="";
		$io="";
		$line="";
		$rmkEng="";
		$rmkKor="";
		$std="";

		while(list($key2,$val2)=each($val)){
			//checkVar($key2,$val2);

			if($key2=="airFln") $airFln = $val2;
			elseif($key2=="airFln") $airFln=$val2;
			elseif($key2=="airlineEnglish") $airlineEnglish=$val2;
			elseif($key2=="airlineKorean") $airlineKorean=$val2;
			elseif($key2=="airport") $airport=$val2;
			elseif($key2=="arrivedEng") $arrivedEng=$val2;
			elseif($key2=="arrivedKor") $arrivedKor=$val2;
			elseif($key2=="boardingEng") $boardingEng=$val2;
			elseif($key2=="boardingKor") $boardingKor=$val2;
			elseif($key2=="city") $city=$val2;
			elseif($key2=="etd") $etd=$val2;
			elseif($key2=="gate") $gate=$val2;
			elseif($key2=="io") $io=$val2;
			elseif($key2=="line") $line=$val2;
			elseif($key2=="rmkEng") $rmkEng=$val2;
			elseif($key2=="rmkKor") $rmkKor=$val2;
			elseif($key2=="std") $std=$val2;
		}

		checkVar("i",$i);
		checkVar("airlineKorean",$airlineKorean);


		checkVar("airFln",$airFln);
		checkVar("airlineEnglish",$airlineEnglish);
		checkVar("airlineKorean",$airlineKorean);
		checkVar("airport",$airport);
		checkVar("arrivedEng",$arrivedEng);
		checkVar("arrivedKor",$arrivedKor);
		checkVar("boardingEng",$boardingEng);
		checkVar("boardingKor",$boardingKor);
		checkVar("city",$city);
		checkVar("etd",$etd);
		checkVar("gate",$gate);
		checkVar("io",$io);
		checkVar("line",$line);
		checkVar("rmkEng",$rmkEng);
		checkVar("rmkKor",$rmkKor);
		checkVar("std",$std);
	}

}

?>