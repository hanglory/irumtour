<?
include_once("/home/hosting_users/irumtour/info/info_dbconn.php");

$connect_host="localhost";
$connect_id=$info[user];
$connect_pass=$info[pass];
$connect_db=$info[base];
$connect=mysql_connect($connect_host,$connect_id,$connect_pass);
$mysql=mysql_select_db($connect_db,$connect);
?>
