<?
session_start();
$totaltoday = date("Y-m-d");
$tomon = date("Y-m");
$tomonth = date("m");
$totaltime = date("G");
$beforeip = getenv("REMOTE_ADDR");
$totalweek = date("w");
$totaldate = date("j");

$mon_que = "select * from rankuplog_totaltoday where todaydate like '%$tomon%'";
$mon_sel = mysql_query($mon_que);
$mon_num = mysql_num_rows($mon_sel);
if(!$mon_num){



$date1 = (01==$tomonth)?"1":"0";
$date2 = (02==$tomonth)?"1":"0";
$date3 = (03==$tomonth)?"1":"0";
$date4 = (04==$tomonth)?"1":"0";
$date5 = (05==$tomonth)?"1":"0";
$date6 = (06==$tomonth)?"1":"0";
$date7 = (07==$tomonth)?"1":"0";
$date8 = (08==$tomonth)?"1":"0";
$date9 = (09==$tomonth)?"1":"0";
$date10 = (10==$tomonth)?"1":"0";
$date11 = (11==$tomonth)?"1":"0";
$query = "insert into rankuplog_month set ";
$query .= "no = '',";
$query .= "wdate = now(),";
$query .= "month01 = '$date1',";
$query .= "month02 = '$date2',";
$query .= "month03 = '$date3',";
$query .= "month04 = '$date4',";
$query .= "month05 = '$date5',";
$query .= "month06 = '$date6',";
$query .= "month07 = '$date7',";
$query .= "month08 = '$date8',";
$query .= "month09 = '$date9',";
$query .= "month10 = '$date10',";
$query .= "month11 = '$date11',";
$query .= "month12 = '$date12'";
$select = mysql_query($query);

}else{


$endnumque = "select no from rankuplog_month order by no desc limit 1";
$endnumsel = mysql_query($endnumque);
$endfetch = mysql_fetch_array($endnumsel);
$query = "update rankuplog_month set month$tomonth = month$tomonth+1 where no='$endfetch[no]'";
$select = mysql_query($query);

}


$total_query = "select todaydate from rankuplog_totaltoday where todaydate=now()";
$total_select = mysql_query($total_query);
$total_num = mysql_num_rows($total_select);
if($total_num){



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




}else{






//오늘날짜업데이트
$query = "delete from rankuplog_totaltoday";
$select = mysql_query($query);
$query = "insert into rankuplog_totaltoday values('',now())";
$select = mysql_query($query);

//시간대별초기화
$time1 = ($totaltime==1)?"1":"0";
$time2 = ($totaltime==2)?"1":"0";
$time3 = ($totaltime==3)?"1":"0";
$time4 = ($totaltime==4)?"1":"0";
$time5 = ($totaltime==5)?"1":"0";
$time6 = ($totaltime==6)?"1":"0";
$time7 = ($totaltime==7)?"1":"0";
$time8 = ($totaltime==8)?"1":"0";
$time9 = ($totaltime==9)?"1":"0";
$time10 = ($totaltime==10)?"1":"0";
$time11 = ($totaltime==11)?"1":"0";
$time12 = ($totaltime==12)?"1":"0";
$time13 = ($totaltime==13)?"1":"0";
$time14 = ($totaltime==14)?"1":"0";
$time15 = ($totaltime==15)?"1":"0";
$time16 = ($totaltime==16)?"1":"0";
$time17 = ($totaltime==17)?"1":"0";
$time18 = ($totaltime==18)?"1":"0";
$time19 = ($totaltime==19)?"1":"0";
$time20 = ($totaltime==20)?"1":"0";
$time21 = ($totaltime==21)?"1":"0";
$time22 = ($totaltime==22)?"1":"0";
$time23 = ($totaltime==23)?"1":"0";
$time0 = ($totaltime==0)?"1":"0";

$query = "insert into rankuplog_time set ";
$query .= "no = '',";
$query .= "wdate = now(),";
$query .= "time1 = '$time1',";
$query .= "time2 = '$time2',";
$query .= "time3 = '$time3',";
$query .= "time4 = '$time4',";
$query .= "time5 = '$time5',";
$query .= "time6 = '$time6',";
$query .= "time7 = '$time7',";
$query .= "time8 = '$time8',";
$query .= "time9 = '$time9',";
$query .= "time10 = '$time10',";
$query .= "time11 = '$time11',";
$query .= "time12 = '$time12',";
$query .= "time13 = '$time13',";
$query .= "time14 = '$time14',";
$query .= "time15 = '$time15',";
$query .= "time16 = '$time16',";
$query .= "time17 = '$time17',";
$query .= "time18 = '$time18',";
$query .= "time19 = '$time19',";
$query .= "time20 = '$time20',";
$query .= "time21 = '$time21',";
$query .= "time22 = '$time22',";
$query .= "time23 = '$time23',";
$query .= "time0 = '$time0'";
$select = mysql_query($query);

//아이피초기화
$query = "insert into rankuplog_ip set ";
$query .= "no='',";
$query .= "wdate=now(),";
$query .= "content='$beforeip',";
$query .= "num=1";
$select = mysql_query($query);

//요일별
$sun = (0==$totalweek)?"1":"0";
$mon = (1==$totalweek)?"1":"0";
$tue = (2==$totalweek)?"1":"0";
$wed = (3==$totalweek)?"1":"0";
$thu = (4==$totalweek)?"1":"0";
$fri = (5==$totalweek)?"1":"0";
$sat = (6==$totalweek)?"1":"0";
$query = "insert into rankuplog_week set ";
$query .= "no = '',";
$query .= "wdate = now(),";
$query .= "date0 = '$sun',";
$query .= "date1 = '$mon',";
$query .= "date2 = '$tue',";
$query .= "date3 = '$wed',";
$query .= "date4 = '$thu',";
$query .= "date5 = '$fri',";
$query .= "date6 = '$sat'";
$select = mysql_query($query);

//일별
$date1 = (1==$totaldate)?"1":"0";
$date2 = (2==$totaldate)?"1":"0";
$date3 = (3==$totaldate)?"1":"0";
$date4 = (4==$totaldate)?"1":"0";
$date5 = (5==$totaldate)?"1":"0";
$date6 = (6==$totaldate)?"1":"0";
$date7 = (7==$totaldate)?"1":"0";
$date8 = (8==$totaldate)?"1":"0";
$date9 = (9==$totaldate)?"1":"0";
$date10 = (10==$totaldate)?"1":"0";
$date11 = (11==$totaldate)?"1":"0";
$date12 = (12==$totaldate)?"1":"0";
$date13 = (13==$totaldate)?"1":"0";
$date14 = (14==$totaldate)?"1":"0";
$date15 = (15==$totaldate)?"1":"0";
$date16 = (16==$totaldate)?"1":"0";
$date17 = (17==$totaldate)?"1":"0";
$date18 = (18==$totaldate)?"1":"0";
$date19 = (19==$totaldate)?"1":"0";
$date20 = (20==$totaldate)?"1":"0";
$date21 = (21==$totaldate)?"1":"0";
$date22 = (22==$totaldate)?"1":"0";
$date23 = (23==$totaldate)?"1":"0";
$date24 = (24==$totaldate)?"1":"0";
$date25 = (25==$totaldate)?"1":"0";
$date26 = (26==$totaldate)?"1":"0";
$date27 = (27==$totaldate)?"1":"0";
$date28 = (28==$totaldate)?"1":"0";
$date29 = (29==$totaldate)?"1":"0";
$date30 = (30==$totaldate)?"1":"0";
$date31 = (31==$totaldate)?"1":"0";
$query = "insert into rankuplog_date set ";
$query .= "no = '',";
$query .= "wdate = now(),";
$query .= "date1 = '$date1',";
$query .= "date2 = '$date2',";
$query .= "date3 = '$date3',";
$query .= "date4 = '$date4',";
$query .= "date5 = '$date5',";
$query .= "date6 = '$date6',";
$query .= "date7 = '$date7',";
$query .= "date8 = '$date8',";
$query .= "date9 = '$date9',";
$query .= "date10 = '$date10',";
$query .= "date11 = '$date11',";
$query .= "date12 = '$date12',";
$query .= "date13 = '$date13',";
$query .= "date14 = '$date14',";
$query .= "date15 = '$date15',";
$query .= "date16 = '$date16',";
$query .= "date17 = '$date17',";
$query .= "date18 = '$date18',";
$query .= "date19 = '$date19',";
$query .= "date20 = '$date20',";
$query .= "date21 = '$date21',";
$query .= "date22 = '$date22',";
$query .= "date23 = '$date23',";
$query .= "date24 = '$date24',";
$query .= "date25 = '$date25',";
$query .= "date26 = '$date26',";
$query .= "date27 = '$date27',";
$query .= "date28 = '$date28',";
$query .= "date29 = '$date29',";
$query .= "date30 = '$date30',";
$query .= "date31 = '$date31'";
$select = mysql_query($query);


//접속전도메인
$beforedomain = ($HTTP_REFERER)?"$HTTP_REFERER":"직접입력또는즐겨찾기";
$query = "insert into rankuplog_domain set ";
$query .= "no = '',";
$query .= "wdate = now(),";
$query .= "content = '$beforedomain',";
$query .= "num = '1'";
$select = mysql_query($query);



if($HTTP_REFERER){
$shortdomain = str_replace("www.","",$HTTP_REFERER);
$shortdomain = explode("/",$shortdomain);
$shortdomain = $shortdomain[0]."//".$shortdomain[1].$shortdomain[2];
}else{
$shortdomain = "직접입력또는즐겨찾기";
}

$query = "insert into rankuplog_shortdomain set ";
$query .= "no='',";
$query .= "wdate=now(),";
$query .= "content='$shortdomain',";
$query .= "num=1";
$select = mysql_query($query);


//총접속자
$query = "insert into rankuplog_total set ";
$query .= "no='',";
$query .= "wdate=now(),";
$query .= "num='1'";
$select = mysql_query($query);




}

?>