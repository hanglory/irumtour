<?
include_once("../include/common_file.php");
include_once ("../../lib/class.phpmailer.php");

#### 메일 내용
$date = date("Y.m.d");

$EMAIL = $email;
$SUBJECT = $subject;
$CONTENT = $content;
$BIT_PROOF = $bit_proof;

$CODE = base64_encode("id=xxxx");

if(!$BIT_PROOF) $proof_msg = "<div style='width:90%;padding-top:30px;font-size:8pt'>본 메일은 0000/00/00 기준, 회원님의 수신동의 여부를 확인한 결과 회원님께서 수신동의를 하셨기에 발송되었습니다. <br>이메일의 수신을 더 이상 원하지 않으시면 <a href='http://scm.yam-korea.com/block_mail.php?$CODE' target=_blank><b>[수신거부]</b></a>를 클릭해 주시기 바랍니다.<br>If you don’t want to receive this mail, click here.</div>";

$SUBJECT = str_replace("{회원명}","홍길동",$SUBJECT);
$content = str_replace("{회원명}","홍길동",$CONTENT);
$content = stripslashes($content) . $proof_msg;
$mailForm = "../../script/mail_form/basic.html";
require $mailForm;

//checkVar("EMAIL",$EMAIL);
//checkVar("SUBJECT",$SUBJECT);
//checkVar("email",$test_email);
//checkVar("proof_msg",$proof_msg);

$mail = new phpmailer();

$mail->From  =$EMAIL;
$mail->FromName =$SERVER_NAME ;
$mail->Host     = "localhost";
$mail->Mailer   = "smtp";
$mail->Subject = $SUBJECT;
$mail->ContentType = "text/html";
$mail->CharSet = "ks_c_5601-1987";

$mail->Body    = $mailContent;
$mail->AddAddress($test_email, "테스터");
if($mail->Send()){
	echo "<script>alert('테스트 메일을 발송하였습니다. ')</script>";
}else{
	echo "<script>alert('테스트 메일을 발송하지 못했습니다. ')</script>";
}



?>