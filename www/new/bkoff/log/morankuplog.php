<?
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

?>