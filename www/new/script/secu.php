<?
session_start();


#### 페이지의 캐쉬읽기를 금지
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");


#### Include
include_once ("../include/config.php");
include_once ("../include/fun_basic.php");


#### DB Connent
include_once ("../../../info/info_dbconn.php");
include_once ("../lib/class.$database.php");

$dbo = new MiniDB($info);


$ip = $_SERVER["REMOTE_ADDR"];
$sql = "SHOW COLUMNS FROM ez_castle_ip";
list($rows) = $dbo->query($sql);
if(!$rows){

	$sql_tbl_login = "
		CREATE TABLE ez_castle_ip (
		  id_no int(10) unsigned NOT NULL AUTO_INCREMENT,
		  ip varchar(20),
		  cnt int not null default 0,
		  PRIMARY KEY (id_no),
		  KEY index_id (ip)
		)
	";
	$dbo->query($sql_tbl_login);
}
$sql = "select count(ip) as cnt from ez_castle_ip where ip='$ip'";
$dbo->query($sql);
$rs=$dbo->next_record();
$cnt = $rs[cnt];
if($cnt){
	$sql = "update ez_castle_ip set cnt=cnt+1 where ip='$ip'";
}else{
	$sql="
		insert into ez_castle_ip (
		   ip,
		   cnt
	   ) values (
		   '$ip',
		   '1'
	 )";
}
$dbo->query($sql);

Header( "HTTP/1.1 301 Moved Permanently" );
Header( "Location: /new/script/msg.php?secu=1");
?>