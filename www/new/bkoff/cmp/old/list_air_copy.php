<?
include_once("../include/common_file.php");


$sql = "select * from cmp_air where id_no=$id_no";
$dbo->query($sql);
$rs=$dbo->next_record();

if($rs[id_no]){

	$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$sql="
	   insert into cmp_air (
	      nation,
	      city,
	      d_air,
	      d_air_no,
	      d_time_s,
	      d_time_e,
	      d_wday,
	      r_air_no,
	      r_time_s,
	      r_time_e,
	      r_wday,
	      airport_in,
	      airport_out,
	      airport_counter,
	      staff,
	      bit_copy
	  ) values (
	      '$rs[nation]',
	      '$rs[city]',
	      '$rs[d_air]',
	      '$rs[d_air_no]',
	      '$rs[d_time_s]',
	      '$rs[d_time_e]',
	      '$rs[d_wday]',
	      '$rs[r_air_no]',
	      '$rs[r_time_s]',
	      '$rs[r_time_e]',
	      '$rs[r_wday]',
	      '$rs[airport_in]',
	      '$rs[airport_out]',
	      '$rs[airport_counter]',
	      '$staff',
	      '1'
	)";

	list($rows) = $dbo->query($sql);

	if($rows){
		echo "<script>alert('복사되었습니다. \\n\\n내용을 수정해 주세요.');history.back(-1);</script>";
	}else{
		//checkVar(mysql_error(),$sql);exit;
		echo "<script>alert('복사하지 못했습니다.')</script>";

	}

	exit;

}
?>