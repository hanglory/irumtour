<?

$endnumque = "select no from rankuplog_month order by no desc limit 1";
$endnumsel = mysql_query($endnumque);
$endfetch = mysql_fetch_array($endnumsel);
$query = "update rankuplog_month set month$tomonth = month$tomonth+1 where no='$endfetch[no]'";
$select = mysql_query($query);
?>