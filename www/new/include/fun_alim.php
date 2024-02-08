<?
function alim($cell,$talk_mode){

	global $_SESSION;
	global $_SERVER;

	global $customer_name;//고객명
	global $staff_name;//담당자
	global $staff_name;//담당자명
	global $staff_cell;//담당자연락처
	global $goods_name;//지역명
	global $d_date;//출국일
	global $price_prev_one;//계약금
	global $prev_date;//입금일
	global $price_total;//총액
	global $price_last;//1인잔금액
	global $pay_date;//잔금일

	$today = date("Y/m/d");

	$BS_ID="irumtour";
	$BS_PWD="ce5a5694327f71cbd7b3123240675a10ec42d0d9";
	$senderKey = "c6c91608c73367f5f8ca904550777e0da80f37d2";

	$url = "https://www.biztalk-api.com";
	$url_token = $url . "/v2/auth/getToken";
	$url_send = $url . "/v2/kko/sendAlimTalk";


	if(!$_SESSION["alim_token"]){

		$headers = array();
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';

		$post_data = array(
			'bsid' => $BS_ID,
			'passwd' => $BS_PWD
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_token);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data)); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		$error = curl_errno($ch);
		curl_close ($ch);

		//var_dump($result);
		//checkVar("error",$error);

		$rtr = json_decode($result);

		//checkVar("responseCode",$rtr->responseCode);
		//checkVar("msg",$rtr->msg);
		//checkVar("token",$rtr->token);

		$_SESSION["alim_token"] = $rtr->token;
	}

	if($talk_mode=="happycall"){//해피콜
		$tmpltCode="happycall";
		$msg = "${customer_name} 고객님 안녕하세요?
이룸투어의 ${staff_name} 입니다.
${goods_name} 골프여행은 잘 다녀오셨는지요?
즐거운 여행이 되실 수 있도록 최선을 다했으나 불편한 점도 있으셨을 것 같습니다.
혹시 그런 부분이 있었다면 언제든지 편하게 말씀해주세요.
더 나은 서비스 제공의 밑거름으로 삼겠습니다.
다음 여행 일정이 잡히시면 꼭 연락주세요.
항상 건강하시고 행복하고 즐거운 일들만 가득하시길 기원합니다.
이룸투어 ${staff_name} 배상";
	}
	elseif($talk_mode=="payment_"){//계약금안내
		$tmpltCode="payment_";
$msg = "${customer_name} 고객님 안녕하세요?
이룸투어 ${staff_name}입니다.
고객님의 \"${goods_name} 투어\" 예약 확정을 위하여 아래와 같이 계약금 안내 드립니다.
◈ 출발일: ${d_date}
◈ 계약금: ${price_prev_one} 만원/1인
◈ 입금일: ${today}
◈ 입금계좌: 국민은행 813037-04-005009
예금주 (주)이룸플레이스
즐거운 여행이 되실 수 있도록 최선을 다하겠습니다.

◈이룸투어 밴드◈
https://band.us/@irumtour";
	}
	elseif($talk_mode=="payment2"){//잔금안내
		$tmpltCode="payment2";
		$msg = "${customer_name} 고객님 안녕하세요
이룸투어 ${staff_name}입니다.
${d_date}에 출발하시는 ${goods_name} 골프 투어 잔금안내드립니다.

◈ 잔금액(총액) : ${price_total}
◈ 잔금액(1인) : ${price_last}
◈ 잔금일 : ${pay_date}
◈ 입금계좌: 국민은행 813037-04-005009
예금주 (주)이룸플레이스

이룸투어 ${staff_name} 배상
담당자 연락처 : ${staff_cell}

◈이룸투어 밴드◈
https://band.us/@irumtour";
	}

	if($talk_mode){
		$token = $_SESSION["alim_token"];

		$headers = array();
		$headers[] = 'Content-Type: application/json';
		$headers[] = 'bt-token: '.$token;

		$send_id = time();
		$post_data = array(
			'msgIdx' => $send_id,
			'countryCode' => "82",
			'recipient' => $cell,
			'senderKey' => $senderKey,
			'message' => $msg,
			'tmpltCode' => $tmpltCode,
			'resMethod' => "PUSH"
		);

		//checkVar("url_send",$url_send);
		//checkVar("token",$token . "<br/>");
		//echo to_han(json_encode($post_data));
		//reset($post_data);
		//exit;


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_send);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, to_han(json_encode($post_data))); 
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$result = curl_exec($ch);
		$error = curl_errno($ch);
		curl_close ($ch);

		//var_dump($result);
		//checkVar("error",$error);

		$rtr = json_decode($result);

		if(strstr("@112.172.15.90@221.154.110.119@",$_SERVER["REMOTE_ADDR"])){
			checkVar("responseCode",$rtr->responseCode);
			checkVar("msg",$rtr->msg);
			checkVar("msg",$msg);
		}
		//exit;
	}

}

//function han($s) { return reset(json_decode('{"s":"'.$s.'"}')); } function to_han ($str) { return preg_replace('/(\\\u[a-f0-9]+)+/e','han("$0")',$str); } 
?>