<?
include_once("../include/common_file.php");


$sql = "select * from cmp_estimate where id_no=$id_no";
$dbo->query($sql);
$rs=$dbo->next_record();

if($rs[id_no]){

	$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');
	$code = getUniqNo();

	$sql2="
		insert into cmp_estimate (
		   code,
		   golf_name,
		   golf_id_no,
		   air_id_no,
		   d_air_no,
		   r_air_no,
		   name,
		   phone,
		   view_path,
		   main_staff,
		   d_date,
		   r_date,
		   send_date,
		   golf,
		   hotel,
		   people,
		   price,
		   account,
		   memo,
		   reg_date,
		   reg_date2,
		   staff,
		   golf2_1_id_no,
		   golf2_2_id_no,
		   golf2_3_id_no,
		   golf2_4_id_no,
		   golf2_1_name,
		   golf2_2_name,
		   golf2_3_name,
		   golf2_4_name,
		   golf2_1,
		   golf2_2,
		   golf2_3,
		   golf2_4,
		   hotel_id_no,
		   hotel_name,
		   room_type,
		   moving_time,
		   hole1,
		   hole2,
		   hole3,
		   hole4,
		   hole5,
		   moving_time2,
		   moving_time3,
		   moving_time4,
		   email,
		   fax,
		   bit_copy
	   ) values (
		   '$code',
		   '$rs[golf_name]',
		   '$rs[golf_id_no]',
		   '$rs[air_id_no]',
		   '$rs[d_air_no]',
		   '$rs[r_air_no]',
		   '$rs[name]',
		   '$rs[phone]',
		   '$rs[view_path]',
		   '$rs[main_staff]',
		   '$rs[d_date]',
		   '$rs[r_date]',
		   '$rs[send_date]',
		   '$rs[golf]',
		   '$rs[hotel]',
		   '$rs[people]',
		   '$rs[price]',
		   '$rs[account]',
		   '$rs[memo]',
		   '$rs[reg_date]',
		   '$rs[reg_date2]',
		   '$rs[staff]',
		   '$rs[golf2_1_id_no]',
		   '$rs[golf2_2_id_no]',
		   '$rs[golf2_3_id_no]',
		   '$rs[golf2_4_id_no]',
		   '$rs[golf2_1_name]',
		   '$rs[golf2_2_name]',
		   '$rs[golf2_3_name]',
		   '$rs[golf2_4_name]',
		   '$rs[golf2_1]',
		   '$rs[golf2_2]',
		   '$rs[golf2_3]',
		   '$rs[golf2_4]',
		   '$rs[hotel_id_no]',
		   '$rs[hotel_name]',
		   '$rs[room_type]',
		   '$rs[moving_time]',
		   '$rs[hole1]',
		   '$rs[hole2]',
		   '$rs[hole3]',
		   '$rs[hole4]',
		   '$rs[hole5]',
		   '$rs[moving_time2]',
		   '$rs[moving_time3]',
		   '$rs[moving_time4]',
		   '$rs[email]',
		   '$rs[fax]',
		   '1'
	 )";


	list($rows) = $dbo->query($sql2);

	if($rows){
		echo "<script>alert('복사되었습니다. \\n\\n내용을 수정해 주세요.');parent.location.reload()</script>";
	}else{
		checkVar(mysql_error(),$sql2);exit;
		echo "<script>alert('복사하지 못했습니다.')</script>";

	}

	exit;

}


?>