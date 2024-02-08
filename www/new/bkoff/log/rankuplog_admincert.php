<?
$certquery = "select * from rankuplog_admin where uid='$adminid' and upasswd='$adminpw'";
$certselect = mysql_query($certquery);
$certNum = mysql_fetch_object($certselect);
if(!$certNum){
	echo "<script>
			alert('정상적인방법이아닙니다!!!');
			parent.location.replace('./index.html');
			</script>";
	exit;
}
?>