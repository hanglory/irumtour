<?
session_start();
include "./dbcon.php";
$que = "select * from rankuplog_admin where uid='$uid' and upasswd='$upasswd'";
$select = mysql_query($que);
$object = mysql_fetch_object($select);
if($object){
	$adminid = $object->uid;
	$adminpw = $object->upasswd;
	session_register(adminid);
	session_register(adminpw);
	echo "<meta http-equiv='Refresh' content='0; URL=./rankuplog_main.html'>";
}else{
	echo "<script>alert('아이디와패스워드확인요망');history.go(-1);</script>";
}
?>