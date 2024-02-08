<?
session_start();
include "./dbcon.php";
include "./rankuplog_admincert.php";

if($mode == 'domaindel'){
	$que = "delete from rankuplog_domain";
	$select = mysql_query($que);
	$que = "delete from rankuplog_shortdomain";
	$select = mysql_query($que);
	echo "<script>alert('정상적으로삭제되었습니다');location.replace('./rankuplog_domain.html');</script>";
}


if($mode == 'ipdel'){
	$que = "delete from rankuplog_ip";
	$select = mysql_query($que);
	echo "<script>alert('정상적으로삭제되었습니다');location.replace('./rankuplog_ip.html');</script>";
}

if($mode == 'change'){
	$que = "update rankuplog_admin set uid='$uid',upasswd='$upasswd'";
	$select = mysql_query($que);
	echo "<script>alert('정상적으로변경되었습니다');location.replace('rankuplog_logout.php');</script>";
}



if($mode == 'tabledelete'){
$que = "drop table rankuplog_admin;";
$select = mysql_query($que);
$que = "drop table rankuplog_date;";
$select = mysql_query($que);
$que = "drop table rankuplog_domain;";
$select = mysql_query($que);
$que = "drop table rankuplog_ip;";
$select = mysql_query($que);
$que = "drop table rankuplog_month;";
$select = mysql_query($que);
$que = "drop table rankuplog_shortdomain;";
$select = mysql_query($que);
$que = "drop table rankuplog_time;";
$select = mysql_query($que);
$que = "drop table rankuplog_total;";
$select = mysql_query($que);
$que = "drop table rankuplog_totaltoday;";
$select = mysql_query($que);
$que = "drop table rankuplog_week;";
$select = mysql_query($que);
}



if($mode == 'org'){
$que = "delete from rankuplog_date;";
$select = mysql_query($que);
$que = "delete from rankuplog_domain;";
$select = mysql_query($que);
$que = "delete from rankuplog_ip;";
$select = mysql_query($que);
$que = "delete from rankuplog_month;";
$select = mysql_query($que);
$que = "delete from rankuplog_shortdomain;";
$select = mysql_query($que);
$que = "delete from rankuplog_time;";
$select = mysql_query($que);
$que = "delete from rankuplog_total;";
$select = mysql_query($que);
$que = "delete from rankuplog_totaltoday;";
$select = mysql_query($que);
$que = "delete from rankuplog_week;";
$select = mysql_query($que);
echo "<script>alert('정상적으로초기화되었습니다');location.replace('./rankuplog_main.html');</script>";

}
?>