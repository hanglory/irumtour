<?
include_once("../include/common_file.php");
include_once ("../../lib/class.phpmailer.php");

#### 메일 내용
$date = date("Y.m.d");

if(!$id_no){
	error("발송할 메일이 없습니다.");
	exit;
}else{

		$sql = "select *  from mailling where id_no='$id_no' ";
		$dbo->query($sql);
		//checkVar(mysql_error(),$sql);
		$rs =$dbo->next_record();

		$EMAIL = $rs[email];
		$SUBJECT = $rs[subject];
		$MAILLING_ID_NO = $rs[reg_date];
		$CONTENT = $rs[content];
		$BIT_PROOF = $rs[bit_proof];


		$sql2 = "select * from mailling_tmp where mailling_id_no='$MAILLING_ID_NO'  limit 500";
		$dbo2->query($sql2);
		//checkVar(mysql_error(),$sql2);
		while($rs2 =$dbo2->next_record()){

			$CODE = base64_encode("id=$rs2[id]");

			if(!$BIT_PROOF) $proof_msg = "<div style='width:90%;padding-top:30px;font-size:8pt'>본 메일은 $rs2[proof_date] 기준, 회원님의 수신동의 여부를 확인한 결과 회원님께서 수신동의를 하셨기에 발송되었습니다. <br>이메일의 수신을 더 이상 원하지 않으시면 <a href='http://${SERVER_NAME}/script/block_mail.php?$CODE' target=_blank><b>[수신거부]</b></a>를 클릭해 주시기 바랍니다.<br>If you don’t want to receive this mail, click here.</div>";

			$SUBJECT = str_replace("{회원명}",$rs2[name],$SUBJECT);
			$content = str_replace("{회원명}",$rs2[name],$CONTENT);
			$content = stripslashes($content) . $proof_msg;
			$mailForm = "../../script/mail_form/basic.html";
			require $mailForm;

			//checkVar("EMAIL",$EMAIL);
			//checkVar("SUBJECT",$SUBJECT);
			//checkVar("email",$rs2[email]);
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
			$mail->AddAddress($rs2[email], $rs2[name]);
			if($mail->Send()){
				checkVar("","$rs2[name] $rs[email] 발송");
				$sql3 = "delete from mailling_tmp where id_no=$rs2[id_no] ";
				$dbo3->query($sql3);
				$sql3 = "update mailling set send_count=1+send_count where reg_date=$MAILLING_ID_NO";
				$dbo3->query($sql3);
			}else{
				checkVar("","미발송");
			}

		}

	//echo $mailContent;
	redirect2("list_email.php");
}

?>