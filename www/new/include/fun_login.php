<?
header("Content-Type: text/html; charset=UTF-8");

/******************************************************
	Login 확인 - 단순히 확인만을 위한 용도
	사용법 : getLoginStatus();
	return value : 1 - login, 0 - logout
*******************************************************/
function getLoginStatus(){

	global $sessMember;
	global $REMOTE_ADDR;

	$result = ($sessMember[ip] == $REMOTE_ADDR)? 1 : 0;

	return $result;

}


/******************************************************
	Login 확인 - 로그인이 필요한 페이지에 삽입하기 위한 용도
	사용법 : loginCheck(돌려보낼 URL);
	조건 : session_start() 가 있어야 함.
*******************************************************/
function loginCheck($url='login_page.php'){

	global $sessMember;
	global $REMOTE_ADDR;
	//global $REQUEST_URI;  로그인 확인시 돌려보낼 default 페이지가 없을 때는 주석을 풀어 사용할 것

	if($sessMember[ip] != $REMOTE_ADDR){

		//$url .= "?go_url=" . urlencode("../.." . $REQUEST_URI);

		redirect2($url);
		exit;
	}
}


/******************************************************
	Login 처리
	사용법 : login(아이디,비밀번호,성공시 보낼 url);
	조건 : session_start();session_register("sessMember"); 가 있어야 함.
*******************************************************/
function login($id,$pwd,$ok_url=''){

	global $info;
	global $REMOTE_ADDR;
	global $_SESSION;
	global $assort;

	//세션변수 등록
	if(!session_is_registered("sessMember")){
		session_register("sessMember");
	}

	$filter = ($assort=="개인")?" and assort='개인' ":" and assort<>'개인' ";

	$dbo = new MiniDB($info);

	$sql = "select * from ez_member where pwd = password('$pwd') and id='$id' ";

	list($rows)=$dbo->query($sql);
	$rs = $dbo -> next_record();
	//checkVar($rows,$sql);exit;

	if($rows){

		$_SESSION["sessMember"] = array(
			"id"=> $rs[id],
			"grade"=>1,
			"assort"=>$rs[assort],
			"name"=> $rs[name],
			"email"=> $rs[email],
		);

		//$now = time();
		//$sql = "update member set visit = visit+1, last_login='$now' where id='$id'";
		//$dbo -> query($sql);

		$ok_url = ($ok_url)? urldecode($ok_url) : '../index.html';

		redirect($ok_url);

	}else{
		msggo("해당하는 회원정보를 찾지 못했습니다.\\n\\n다시한번 확인해 주시기 바랍니다.","../login.html");
		exit;
	}

}


/******************************************************
	Logout 처리
	사용법 : logout($name);
*******************************************************/
function logout(){

	session_unset();
	session_destroy();

	msggo("로그아웃 되었습니다.","../index.html");

}


/******************************************************
	성인 인증
	사용법 : adult_login(주민등록 번호 앞 자리,보낼 url);
	조건 : session_start() 가 있어야 함.
*******************************************************/

function adult_login($rn1,$url=''){

	global $sessAdult;


	//19세 미만 걸러내기

	$standard = date("Y") - 19;

	$standard = substr($standard,2,2);

	$limit = substr($rn1,0,2);

	$sessAdult = ($limit > $standard || $limit < 20)? 0 : 1;

	redirect2($url);

}


/******************************************************
	성인 인증을 거쳤는지 확인
	사용법 : getLoginStatus();
	return value : 1 - login, 0 - logout
*******************************************************/
function getAdultLoginStatus($class1){

	global $sessAdult;

	if(!$sessAdult && $class1=="성인용품"){

		 msggo('이 서비스는 성인인증을 거쳐야만 합니다.','index.html?tpl=adult');

		 exit;

	}

}
?>