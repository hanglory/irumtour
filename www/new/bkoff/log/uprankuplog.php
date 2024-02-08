<?
//시간대별처리
$query = "update rankuplog_time set time$totaltime = time$totaltime+1 where wdate=now()";
$select = mysql_query($query);

//아이피
$ip_query = "select * from rankuplog_ip where content='$beforeip' and wdate=now()";
$ip_select = mysql_query($ip_query);
$ip_num = mysql_num_rows($ip_select);
if($ip_num){
	$query = "update rankuplog_ip set num=num+1 where content='$beforeip' and wdate=now()";
	$select = mysql_query($query);
}else{
	$query = "insert into rankuplog_ip set ";
	$query .= "no='',";
	$query .= "wdate=now(),";
	$query .= "content='$beforeip',";
	$query .= "num=1";
	$select = mysql_query($query);
}

//요일별
$query = "update rankuplog_week set date$totalweek=date$totalweek+1 where wdate=now()";
$select = mysql_query($query);

//일별
$query = "update rankuplog_date set date$totaldate = date$totaldate+1 where wdate=now()";
$select = mysql_query($query);

//접속전도메인
$beforedomain = ($HTTP_REFERER)?"$HTTP_REFERER":"직접입력또는즐겨찾기";

$domain_query = "select * from rankuplog_domain where content='$beforedomain' and wdate=now()";
$domain_select = mysql_query($domain_query);
$domain_num = mysql_num_rows($domain_select);
if($domain_num){
	$query = "update rankuplog_domain set num=num+1 where content='$beforedomain' and wdate=now()";
	$select = mysql_query($query);
}else{
	$query = "insert into rankuplog_domain set ";
	$query .= "no='',";
	$query .= "wdate=now(),";
	$query .= "content='$beforedomain',";
	$query .= "num=1";
	$select = mysql_query($query);
}

//짧은도메인
if($HTTP_REFERER){
$shortdomain = str_replace("www.","",$HTTP_REFERER);
$shortdomain = explode("/",$shortdomain);
$shortdomain = $shortdomain[0]."//".$shortdomain[1].$shortdomain[2];
}else{
$shortdomain = "직접입력또는즐겨찾기";
}

$shdomain_query = "select * from rankuplog_shortdomain where content='$shortdomain' and wdate=now()";
$shdomain_select = mysql_query($shdomain_query);
$shdomain_num = mysql_num_rows($shdomain_select);
if($shdomain_num){
	$query = "update rankuplog_shortdomain set num=num+1 where content='$shortdomain' and wdate=now()";
	$select = mysql_query($query);
}else{
	$query = "insert into rankuplog_shortdomain set ";
	$query .= "no='',";
	$query .= "wdate=now(),";
	$query .= "content='$shortdomain',";
	$query .= "num=1";
	$select = mysql_query($query);
}


//총접속자
$query = "update rankuplog_total set num=num+1 where wdate=now()";
$select = mysql_query($query);


?>
