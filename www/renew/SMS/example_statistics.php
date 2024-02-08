<?php
	require_once "xmlrpc.inc.php";
	require_once "class.EmmaSMS.php";

	$sms = new EmmaSMS();
	$sms_id = "smstest";
	$sms_passwd = "smstest";

	$sms->login($sms_id,$sms_passwd);	// $sms->login( [고객 ID], [고객 패스워드]);

	$retValue = $sms->statistics (2008,11);	// 2008년 11월
	if ($retValue) {
		echo "<h3>월별 발송 통계</h3>";
		echo "[발송한 날짜] : [발송 건수] / [성공 건수]<br />";
		foreach ($retValue as $day => $point) {
			echo $day.": ".$point."<br />";
		}
		echo "<h4>잔여 건수</h4>";
		echo $sms->Point."<br />";
	}
	else {
		echo "<h3>에러</h3>";
		echo $sms->errMsg;
	}
?>