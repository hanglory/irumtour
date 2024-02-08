<?
#### Include
include_once  ("../include/fun_basic.php");
include_once ("../lib/class.phpmailer.php");
include_once ("../public/inc/site.inc");
include_once ("../include/vars.php");


#### 메일 내용
//$name = enVars($name);

$mailAddress = ($toMail)? $toMail : $WEBMASTER;

switch ($mode){

	case "mail":	//form mail

		#### DB Connent
		include_once ("../include/info_dbconn.php");
		include_once ("../lib/class.$database.php");
		$dbo = new MiniDB($info);

		$sql = "select * from ez_mail where reg_date='$rd' order by id_no desc limit 1";
		$dbo->query($sql);
		$rs=$dbo->next_record();

		$url = "script";
		$script = "alert('접수되었습니다.');self.close()";
		$subject = "[KITECH] 신청접수";

		$mailAddress = $rs[cn_email];
		$toName = $rs[cn];

		$mailContent = "
			<html>
			<body bgcolor=#ffffff>
			<div><service_center.php?tpl=_helper&nbsp;</div>
			<div><font face=arial size=2>
			□ 기업명 : $rs[org] &nbsp; <br>
			□ 부서 : $rs[org_part] &nbsp; <br>
			□ 성명 : $rs[name] &nbsp; <br>
			□ 직함 : $rs[org_position] &nbsp; <br>
			□ 전화 : ${rs[phone1]}-${rs[phone2]}-${rs[phone3]} &nbsp; <br>
			□ 팩스 : ${rs[fax1]}-${rs[fax2]}-${rs[fax3]} &nbsp; <br>
			□ 휴대폰 : ${rs[cell1]}-${rs[cell2]}-${rs[cell3]}  &nbsp; <br>
			□ e-mail : <a href='mailto:$rs[email]'>$rs[email]</a> &nbsp; <br>
			□ 주소 : ($rs[zipcode]) $rs[address] &nbsp; <br>
			□ 내용
			</font></div>
			<div><font face=arial size=2>_______________________________________________</font><br><br></div>
			<div><font face=arial size=2>$rs[content]</font><font face=arial size=2></div></font></body></html>
		";

		break;

}


#### 메일 발송
$mail = new phpmailer();

$mail->From     = $email;
$mail->FromName = ($name)? $name : "KITECH";
$mail->Host     = "localhost";
$mail->Mailer   = "smtp";
$mail->Subject = $subject;
$mail->ContentType = "text/html";
$mail->CharSet = "ks_c_5601-1987";

if($filename_name) $mail->AddAttachment($filename, $filename_name);

$mail->Body    = $mailContent;
$mail->AddAddress($mailAddress, $toName);
$mail->Send();


#### 이동

if($url=='close'){
	echo "<script>alert('${close_msg}');self.close();</script>";
}
elseif($url =='script'){
	echo "<script>${script}</script>";
}else{
	redirect2($url);
}
?>