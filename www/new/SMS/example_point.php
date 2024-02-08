<?php
	require_once "xmlrpc.inc.php";
	require_once "class.EmmaSMS.php";

	$sms = new EmmaSMS();
	$sms_id = "smstest";
	$sms_passwd = "smstest";
	$sms->login($sms_id, $sms_passwd);	// $sms->login( [고객 ID], [고객 패스워드]);
	$point = $sms->point();

	if($point != false)
		echo "남은 건수는 : ".$point."건 입니다.";
	else
		echo "[에러] ".$sms->errMsg;
?>