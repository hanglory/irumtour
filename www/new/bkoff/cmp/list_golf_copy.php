<?
include_once("../include/common_file.php");


$sql = "select * from cmp_golf where id_no=$id_no";
$dbo->query($sql);
$rs=$dbo->next_record();

if($rs[id_no]){

	$rs[staff]=$sessLogin[name] . " (". $sessLogin[id] . ")";
	$rs[bit_copy]=1;
	$rs[name] = $rs[name] . " 복사본";

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$rs[nation]=addslashes($rs[nation]);
	$rs[city]=addslashes($rs[city]);
	$rs[name]=addslashes($rs[name]);
	$rs[main_staff]=addslashes($rs[main_staff]);
	$rs[phone]=addslashes($rs[phone]);
	$rs[meeting_place]=addslashes($rs[meeting_place]);
	$rs[meeting_board]=addslashes($rs[meeting_board]);
	$rs[local_staff]=addslashes($rs[local_staff]);
	$rs[phone2]=addslashes($rs[phone2]);
	$rs[point_include]=addslashes($rs[point_include]);
	$rs[point_not_include]=addslashes($rs[point_not_include]);
	$rs[meal]=addslashes($rs[meal]);
	$rs[etc]=addslashes($rs[etc]);
	$rs[bit]=addslashes($rs[bit]);
	$rs[partner]=addslashes($rs[partner]);
	$rs[staff]=addslashes($rs[staff]);
	$rs[bit_copy]=addslashes($rs[bit_copy]);
	$rs[golf_name]=addslashes($rs[golf_name]);
	$rs[hotel_name]=addslashes($rs[hotel_name]);
	$rs[golf_name2]=addslashes($rs[golf_name2]);
	$rs[golf_name3]=addslashes($rs[golf_name3]);
	$rs[golf_name4]=addslashes($rs[golf_name4]);
	$rs[car]=addslashes($rs[car]);
	$rs[golf2_1_name]=addslashes($rs[golf2_1_name]);
	$rs[golf2_2_name]=addslashes($rs[golf2_2_name]);
	$rs[golf2_3_name]=addslashes($rs[golf2_3_name]);
	$rs[golf2_4_name]=addslashes($rs[golf2_4_name]);
	$rs[golf2_1]=addslashes($rs[golf2_1]);
	$rs[golf2_2]=addslashes($rs[golf2_2]);
	$rs[golf2_3]=addslashes($rs[golf2_3]);
	$rs[golf2_4]=addslashes($rs[golf2_4]);
	$rs[air_city]=addslashes($rs[air_city]);
	$rs[golf2_5]=addslashes($rs[golf2_5]);
	$rs[golf2_5_name]=addslashes($rs[golf2_5_name]);
	$rs[golf2_5_id_no]=addslashes($rs[golf2_5_id_no]);
	$rs[car2]=addslashes($rs[car2]);
	$rs[etc2]=addslashes($rs[etc2]);
	$rs[cancel_text]=addslashes($rs[cancel_text]);
	$rs[hotel2_name]=addslashes($rs[hotel2_name]);
	$rs[ag1]=addslashes($rs[ag1]);
	$rs[ag2]=addslashes($rs[ag2]);
	$rs[ag3]=addslashes($rs[ag3]);
	$rs[ag4]=addslashes($rs[ag4]);
	$rs[ag5]=addslashes($rs[ag5]);
	$rs[ag6]=addslashes($rs[ag6]);
	$rs[ag7]=addslashes($rs[ag7]);
	$rs[ag8]=addslashes($rs[ag8]);
	$rs[gh1]=addslashes($rs[gh1]);
	$rs[gh2]=addslashes($rs[gh2]);
	$rs[gh3]=addslashes($rs[gh3]);
	$rs[gh4]=addslashes($rs[gh4]);
	$rs[gh5]=addslashes($rs[gh5]);
	$rs[gh6]=addslashes($rs[gh6]);
	$rs[gh7]=addslashes($rs[gh7]);
	$rs[gh8]=addslashes($rs[gh8]);
	$rs[ah1]=addslashes($rs[ah1]);
	$rs[ah2]=addslashes($rs[ah2]);
	$rs[ah3]=addslashes($rs[ah3]);
	$rs[ah4]=addslashes($rs[ah4]);
	$rs[ah5]=addslashes($rs[ah5]);
	$rs[ah6]=addslashes($rs[ah6]);
	$rs[ah7]=addslashes($rs[ah7]);
	$rs[ah8]=addslashes($rs[ah8]);	

	$sql2="
		insert into cmp_golf (
           cp_id,
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
		   bit_copy,
		   golf_name,
		   hotel_name,
		   golf_name2,
		   golf_name3,
		   golf_name4,
		   car,
		   golf2_1_id_no,
		   golf2_2_id_no,
		   golf2_3_id_no,
		   golf2_4_id_no,
		   hotel_id_no,
		   golf2_1_name,
		   golf2_2_name,
		   golf2_3_name,
		   golf2_4_name,
		   golf2_1,
		   golf2_2,
		   golf2_3,
		   golf2_4,
		   air_city,
		   golf2_5,
		   golf2_5_name,
		   golf2_5_id_no,
		   car2,
		   etc2,
		   cancel_text,
		   hotel2_name,
		   hotel2_id_no,
		   ag1,
		   ag2,
		   ag3,
		   ag4,
		   ag5,
		   ag6,
		   ag7,
		   ag8,
		   gh1,
		   gh2,
		   gh3,
		   gh4,
		   gh5,
		   gh6,
		   gh7,
		   gh8,
		   ah1,
		   ah2,
		   ah3,
		   ah4,
		   ah5,
		   ah6,
		   ah7,
		   ah8
	   ) values (
		   '$CP_ID',
           '$rs[nation]',
		   '$rs[city]',
		   '$rs[name]',
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
		   '$rs[staff]',
		   '$rs[bit_copy]',
		   '$rs[golf_name]',
		   '$rs[hotel_name]',
		   '$rs[golf_name2]',
		   '$rs[golf_name3]',
		   '$rs[golf_name4]',
		   '$rs[car]',
		   '$rs[golf2_1_id_no]',
		   '$rs[golf2_2_id_no]',
		   '$rs[golf2_3_id_no]',
		   '$rs[golf2_4_id_no]',
		   '$rs[hotel_id_no]',
		   '$rs[golf2_1_name]',
		   '$rs[golf2_2_name]',
		   '$rs[golf2_3_name]',
		   '$rs[golf2_4_name]',
		   '$rs[golf2_1]',
		   '$rs[golf2_2]',
		   '$rs[golf2_3]',
		   '$rs[golf2_4]',
		   '$rs[air_city]',
		   '$rs[golf2_5]',
		   '$rs[golf2_5_name]',
		   '$rs[golf2_5_id_no]',
		   '$rs[car2]',
		   '$rs[etc2]',
		   '$rs[cancel_text]',
		   '$rs[hotel2_name]',
		   '$rs[hotel2_id_no]',
		   '$rs[ag1]',
		   '$rs[ag2]',
		   '$rs[ag3]',
		   '$rs[ag4]',
		   '$rs[ag5]',
		   '$rs[ag6]',
		   '$rs[ag7]',
		   '$rs[ag8]',
		   '$rs[gh1]',
		   '$rs[gh2]',
		   '$rs[gh3]',
		   '$rs[gh4]',
		   '$rs[gh5]',
		   '$rs[gh6]',
		   '$rs[gh7]',
		   '$rs[gh8]',
		   '$rs[ah1]',
		   '$rs[ah2]',
		   '$rs[ah3]',
		   '$rs[ah4]',
		   '$rs[ah5]',
		   '$rs[ah6]',
		   '$rs[ah7]',
		   '$rs[ah8]'
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