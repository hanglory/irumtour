<?
include_once("../include/common_file.php");


$sql = "select * from cmp_golf where id_no=$id_no";
$dbo->query($sql);
$rs=$dbo->next_record();

if($rs[id_no]){

	$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');


	$sql2="
	   insert into cmp_golf (
		  nation,
		  city,
		  name,
		  main_staff,
		  phone,
		  meeting_place,
		  meeting_board,
		  local_staff,
		  phone2,
		  point_include,
		  point_not_include,
		  meal,
		  etc,
		  bit,
		  partner,
		  staff,
		  bit_copy
	  ) values (
		  '$rs[nation]',
		  '$rs[city]',
		  '$rs[name] 복사본',
		  '$rs[main_staff]',
		  '$rs[phone]',
		  '$rs[meeting_place]',
		  '$rs[meeting_board]',
		  '$rs[local_staff]',
		  '$rs[phone2]',
		  '$rs[point_include]',
		  '$rs[point_not_include]',
		  '$rs[meal]',
		  '$rs[etc]',
		  '$rs[bit]',
		  '$rs[partner]',
		  '$staff',
		  '1'
	)";



	list($rows) = $dbo->query($sql2);

	if($rows){
		echo "<script>alert('복사되었습니다. \\n\\n내용을 수정해 주세요.');parent.location.reload();</script>";
	}else{
		//checkVar(mysql_error(),$sql2);exit;
		echo "<script>alert('복사하지 못했습니다.')</script>";

	}

	exit;

}


?>