<?
session_start();
header("Content-Type: text/html; charset=UTF-8");

#### Include
include_once  ("../include/fun_basic.php");
include_once ("../lib/class.phpmailer.php");
include_once ("../public/inc/site.inc");
include_once ("../include/vars.php");



#### Include
include_once  ("../include/fun_basic.php");
include_once  ("../include/fun_login.php");


#### DB Connent
include_once ("../include/info_dbconn.php");
include_once ("../lib/class.$database.php");
$dbo = new MiniDB($info);

#### 메일 내용
$date = date("Y.m.d");


switch ($mode){

	case "join":	//회원 가입시

		$assort = "$name님의 회원가입을 축하드립니다. ";

		$toMail = $email;// 받는 사람 이메일
		$toName = $name ;// 받는 사람 이름
		$fromMail = $Mail;// 보내는 사람 이메일
		$content = "
			${name}님께서는 저희 $SITE_NAME 회원으로 등록되었습니다.<br>
			회원으로 가입해 주셔서 감사합니다.<br><br><br>
			앞으로 ${name}님께 양질의 서비스를 제공해 드리기 위해 최선을 다하겠습니다.<br>
			또한 회원님의 개인정보를 소중히 관리할 것을 약속드립니다.<br><br><br>
			※ 로그인 하실 때 필요한 회원정보를 확인해 드리겠습니다.<br>
			<font color='#9999FF'>■</font> 회원님 ID : ${id}<br>
			<font color='#9999FF'>■</font> 회원님 비밀번호 : ${pwd}<br>
			<br><br>
			감사합니다. <br><br><br>
			";// 메일 내용

		require "mail_form/basic.html";

		$end = "../join03.html";//메일발송 후 가야 할 곳
		break;

	case "normal":		//일반메일

		//subject,email,name,fromMail,fromName,content

		$assort = $subject;

		$now = date("Y년 m월 d일 h시 m분");

		$toMail = $WEBMASTER;// 받는 사람 이메일
		//$toMail = "support@easeplus.com";// 받는 사람 이메일
		$toName = $SITE_NAME;// 받는 사람 이름
		$fromMail = $fromEmail;// 보내는 사람 이메일
		$fromName = $fromName;// 보내는 사람 이름
		$content = nl2br($content);// 메일 내용

		require "mail_form/basic.html";

		$script="alert('메일을 발송하였습니다.');self.close();";

		break;

	case "lost":		//아이디 패스워드 찾기

		$pwd = base64_decode($pwd);

		if($bit){//아이디 확인

			$assort = "요청하신 아이디입니다.";
			$content = "${name}님 안녕하십니까?<br>
				${name}님께서 저희 홈페이지 상에서 접수해 주신 아이디 분실 신고에 따라 회원님의 아이디를 알려드립니다.<br>
				※ 회원님의 아이디는 다음과 같습니다.<br><br><br>
				<font color='#9999FF'>■</font> 회원님 ID : ${id}<br>
				  앞으로도 많은 이용을 부탁드립니다.<br>
				  감사합니다.
				";// 메일 내용

		}else{

			$assort = "요청하신 비밀번호입니다.";
			$content = "${name}님 안녕하십니까?<br>
				${name}님께서 저희 홈페이지 상에서 접수해 주신 비밀번호 분실 신고에 따라 회원님의 비밀번호를 알려드립니다.<br>
				※ 회원님의 비밀번호는 다음과 같습니다.<br><br><br>
				<font color='#9999FF'>■</font> 비밀번호 : ${pwd}<br><br><br>
				  앞으로도 많은 이용을 부탁드립니다.<br>
				  감사합니다.
				";// 메일 내용
		}

		$toMail = $email;// 받는 사람 이메일
		$toName = $name;// 받는 사람 이름
		$fromMail = $WEBMASTER;// 보내는 사람 이메일
		$fromName =$SITE_NAME;// 보내는 사람 이름
		//$content = $content);// 메일 내용

		require "mail_form/basic.html";

		$script="alert('메일을 발송하였습니다.');parent.location.href='../login.html'";

		break;

case "rejoin":	//회원 정보 수정시

		$assort = "$name님의 회원정보가 수정되었습니다.";

		$toMail = $email;// 받는 사람
		$toName = $name. "님";
		$date  = date("Y년m월d일 H시 i분");
		$content = "
			${name} 회원님의 정보가 $date 에 변경되어 성공적으로 적용되었습니다.<br>
			<br><br>
			감사합니다. <br><br><br>
			";// 메일 내용

		require "mail_form/basic.html";

		$end = "../index.html";//메일발송 후 가야 할 곳
		break;

case "goods":	//추천메일

		$assort = $subject;

		$toMail = $to_mail;// 받는 사람
		$toName = $to_mail;
		$fromMail = $from_mail;
		$fromName = $from_mail;

		$content = nl2br($content);

		$sql = "select * from ez_tour where tid='$tid'";
		$dbo->query($sql);
		$rs=$dbo->next_record();
		$rs[price_adult] = number_format($rs[price_adult]);

		require "mail_form/recomm_mail.html";

		$script="alert('메일을 발송하였습니다.');parent.pop_close()";
		break;

case "order":	//주문시

		#### DB Connent
		include_once ("../include/info_dbconn.php");
		include_once ("../lib/class.$database.php");
		$dbo = new MiniDB($info);

		$sql = "select * from order_info where id_no='$id_no' and phpid='$sd' order by id_no desc limit 1";
		$dbo->query($sql);
		$rs=$dbo->next_record();


		$assort = "${SITE_NAME}를 이용해 주셔서 감사합니다. - 주문확인 메일";

		$toMail = $rs[order_email];// 받는 사람
		$toName = $rs[order_name];
		$fromMail = $WEBMASTER;
		$fromName = $SITE_NAME;

		$content = "${toName}님 안녕하십니까?<br>
			저희 ${SITE_NAME}를 이용해 주셔서 감사합니다.<br>
			${toName}님께서 신청하신 여행 정보에 대해 알려드립니다.<br><br>

			<b>여행상품</b> : $rs[tour_subject] <br>
			<b>여행일</b> : $rs[tour_date] <br>
			<b>금액</b> : $rs[tour_price]($) <br>
			<b>신청 비밀번호</b> : $rs[pwd]  (회원이신 경우 로그인 하시면 확인이 가능합니다)<br>
			<b>신청일</b> : $rs[reg_date]	<br><br>

			자세한 내역은 <a href='http://www.irumtour.net/new/login.html' target='_blank'><b>홈페이지</b> </a>에서 확인하실 수 있습니다.<br><br>
			감사합니다.
			";// 메일 내용

		require "mail_form/basic.html";

		$end = "../booking02.html?id_no=$id_no&sd=$sd";//메일발송 후 가야 할 곳
		break;

}



#### 메일 발송
/*
checkVar("bit",$bit);
checkVar("받는 사람 이메일",$toMail);
checkVar("받는 사람 이름 ",$toName);
checkVar("보내는 사람 이메일",$fromMail);
checkVar("보내는 사람 이름",$fromName);
checkVar("메일 내용",$content);
checkVar("\$homepage",$homepage);
exit;
*/


$mail = new phpmailer();

$mail->From  =($fromMail)? $fromMail: $WEBMASTER;
$mail->FromName =($fromName)? $fromName: $SITE_NAME;
$mail->Host     = "localhost";
$mail->Mailer   = "smtp";
$mail->Subject = $assort;
$mail->ContentType = "text/html";
$mail->CharSet = "utf-8";

$mail->Body = $mailContent;
$mail->AddAddress($toMail, $toName);
$mail->Send();

if($script){
	echo "<script>${script}</script>";
}else{
	redirect2($end);
}
?>