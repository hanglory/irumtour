<?
session_start();

#### Include
include_once('../../include/fun_basic.php');
include_once('../../include/fun_bbs.php');
include_once('../include/proof.php');

#### DB Connent
include_once ("../../include/info_dbconn.php");

include_once ("../../lib/class.$database.php");
$dbo = new MiniDB($info);



$sql = "show tables";
$dbo->query($sql);
$db_name = "Tables_in_".$info[base];
while($rs = $dbo->next_record()){
	$tables .= "," . $rs[$db_name];
}
?>

<html>
<head>
<title>관리자 페이지</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../include/basic.css" type="text/css">
<script language="JavaScript" src="../../include/function.js"></script>
<style type="text/css">
*{
	font-size:8pt;
	font-family:verdana
}
</style>
</head>

<body>


<b>Insert, Update  Query 생성</b><br>

<form name="fmData">
<select name="table" onchange="document.fmData.submit()">
	<?=option_str($tables,$tables,$table)?>
</select>
</form>

<?

if($table){

	$link = @mysql_connect($info[host], $info[user], $info[pass]);
	if (!$link) {
		die('Could not connect to MySQL server: ' . mysql_error());
	}
	$dbname = $info[base];
	$db_selected = mysql_select_db($dbname, $link);
	if (!$db_selected) {
		die("Could not set $dbname: " . mysql_error());
	}
	$query = "select * from $table limit 1";
	$res = mysql_query($query, $link);
	$field = mysql_num_fields($res);


	echo "
	\$reg_date = date('Y/m/d');<br>
	\$reg_date2 = date('H:i:s');<br>
	<br>
	<br>
	\$sqlInsert=\" <br>
	 &nbsp;&nbsp; insert into $table ( <br>
	";

	for($i=1;$i<$field;$i++){
		$comma = ($i < ($field-1))?",":"";
		$name = mysql_field_name($res, $i);
		echo " &nbsp;&nbsp; &nbsp;&nbsp; ${name}${comma} <br>";
	}

	echo"
	&nbsp;&nbsp;) values ( <br>
	";

	for($i=1;$i<$field;$i++){
		$comma = ($i < ($field-1))?",":"";
		$name = mysql_field_name($res, $i);
		echo " &nbsp;&nbsp; &nbsp;&nbsp; '\$${name}'${comma} <br>";
	}

	echo "
	)\"; <br>
	<br>
	<br>
	\$sqlModify=\" <br>
	&nbsp;&nbsp; update $table set <br>
	";

	for($i=1;$i<$field;$i++){
		$comma = ($i < ($field-3))?",":"";
		$name = mysql_field_name($res, $i);
		if(!strstr($name,"reg_date")) echo " &nbsp;&nbsp; &nbsp;&nbsp; ${name} = '\$${name}'${comma} <br>";
	}

	echo"
	&nbsp;&nbsp; where id_no='\$id_no' <br>
	\";

	";

}
?>

</body>
</html>