<?php
include '/home/hosting_users/irumtour/www/new/api/api_common.php';
header("Content-Type: text/html; charset=UTF-8");


$key = "0g7LgnItlv2wg%2Bx%2BKcBPQKYOkBRi1%2Bt94uaZFjbLDFaX6zKtUBy5CqB12z6yLidx2kNdDCiany4gEc2ZC644WQ%3D%3D";

$mode="plan";
if($mode=="plan"){


	$ch = curl_init();
	$url = 'http://openapi.airport.co.kr/service/rest/FlightScheduleList/getIflightScheduleList'; /*URL*/
	$queryParams = '?' . urlencode('ServiceKey') . '='.$key; /*Service Key*/
	$queryParams .= '&' . urlencode('schDate') . '=' . urlencode('20151005'); /**/
	$queryParams .= '&' . urlencode('schDeptCityCode') . '=' . urlencode('GMP'); /**/
	$queryParams .= '&' . urlencode('schArrvCityCode') . '=' . urlencode('HND'); /**/
	$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode('1'); /**/
	$queryParams .= '&' . urlencode('serviceKey') . '=' . urlencode($key); /**/

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

		$airlineKorean="";
		$airport="";
		$city="";
		$internationalEddate="";
		$internationalFri="";
		$internationalIoType="";
		$internationalMon="";
		$internationalNum="";
		$internationalSat="";
		$internationalStdate="";
		$internationalSun="";
		$internationalThu="";
		$internationalTime="";
		$internationalTue="";
		$internationalWed="";

		while(list($key2,$val2)=each($val)){
			//checkVar($key2,$val2);

			if($key2=="airlineKorean") $airlineKorean = $val2;
			elseif($key2=="airport") $airport=$val2;
			elseif($key2=="city") $city=$val2;
			elseif($key2=="internationalEddate") $internationalEddate=$val2;
			elseif($key2=="internationalFri") $internationalFri=$val2;
			elseif($key2=="internationalIoType") $internationalIoType=$val2;
			elseif($key2=="internationalMon") $internationalMon=$val2;
			elseif($key2=="internationalNum") $internationalNum=$val2;
			elseif($key2=="internationalSat") $internationalSat=$val2;
			elseif($key2=="internationalStdate") $internationalStdate=$val2;
			elseif($key2=="internationalSun") $internationalSun=$val2;
			elseif($key2=="internationalThu") $internationalThu=$val2;
			elseif($key2=="internationalTime") $internationalTime=$val2;
			elseif($key2=="internationalTue") $internationalTue=$val2;
			elseif($key2=="internationalWed") $internationalWed=$val2;
		}

		checkVar("i",$i);
		checkVar("airlineKorean",$airlineKorean);
		checkVar("airport",$airport);
		checkVar("city",$city);
		//checkVar("internationalEddate",$internationalEddate);
		//checkVar("internationalFri",$internationalFri);
		checkVar("internationalIoType",$internationalIoType);
		//checkVar("internationalMon",$internationalMon);
		checkVar("internationalNum",$internationalNum);
		//checkVar("internationalSat",$internationalSat);
		//checkVar("internationalStdate",$internationalStdate);
		//checkVar("internationalSun",$internationalSun);
		//checkVar("internationalThu",$internationalThu);
		checkVar("internationalTime",$internationalTime);
		//checkVar("internationalTue",$internationalTue);
		//checkVar("internationalWed",$internationalWed);
		
	}

}

?>