<?php
/*
https://golf-course-database.com/api-v1-0/
*/
include '/home/hosting_users/irumtour/www/new/api/api_common.php';
include '/home/hosting_users/irumtour/www/new/api/bank/common.php';
header("Content-Type: text/html; charset=UTF-8");


$user="irumtourthai";
$password="3Ggnd94HpS";
$url = "https://api-staging.golf-course-database.com:8000/clubs";



$filename= "../../public/inc/golf_api.inc";
@include($filename);
if(!$WORK_DATE) $WORK_DATE=date("Y-m-d");



/*delete club upate s*/
$url = "https://api.golf-course-database.com:8000/deleted-clubs?since=".$WORK_DATE;
echo $url;

//$url = "https://api.golf-course-database.com:8000";

$ch = curl_init();
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "$user:$password");
$result = curl_exec($ch);
curl_close($ch);  

$rarr = json_decode($result);

if(is_array($rarr)){
	for($i=0; $i<count($rarr);$i++){
		$club_id = $rarr[$i]->club_id;

		$sql = "select * from cmp_golfclub where club_id='$club_id'";
		$dbo->query($sql);
		$rs=$dbo->next_record();
		if($rs[club_id]){

			$sql2 = "delete from cmp_golfclub where club_id='$club_id'"; $dbo->query($sql);checkVar(mysql_error(),$sql2);
			$sql2 = "delete from cmp_golfcourses where club_id='$club_id'"; $dbo->query($sql);checkVar(mysql_error(),$sql2);
			$sql2 = "update cmp_golf2 set club_id='' where club_id='$club_id'"; $dbo->query($sql);checkVar(mysql_error(),$sql2);
		}

		//checkVar("club_id",$club_id);
	}
}
/*delete club upate e*/



$WORK_DATE = date("Y-m-d");
$fp=fopen($filename, "w");	//파일 쓰기모드로 열기, 파일이 있다면 overwirte
$config ="<?\n";
$config .="\$WORK_DATE='$WORK_DATE';\n";
$config .="?";
$config .=">";
fwrite($fp,$config);
fclose($fp);


?>