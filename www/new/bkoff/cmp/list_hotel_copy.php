<?
include_once("../include/common_file.php");


$sql = "select * from cmp_hotel where id_no=$id_no";
$dbo->query($sql);
$rs=$dbo->next_record();

if($rs[id_no]){

	$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$filename1 = str_replace("/","/copy_",$rs[filename1]);
	$file = "../../public/cmp/$rs[filename1]";
	$file2 = "../../public/cmp/$filename1";
	copy($file,$file2);

	$filename2 = str_replace("/","/copy_",$rs[filename2]);
	$file = "../../public/cmp/$rs[filename2]";
	$file2 = "../../public/cmp/$filename2";
	copy($file,$file2);

	$rs[name] = $rs[name] . " 복사본";

	$sql2="
		insert into cmp_hotel (
           cp_id,
		   name,
		   nation,
		   city,
		   content,
		   filename1,
		   filename2,
		   filename1_real,
		   filename2_real,
		   ah,
		   reg_date,
		   reg_date2
	   ) values (
           '$CP_ID',
		   '$rs[name]',
		   '$rs[nation]',
		   '$rs[city]',
		   '$rs[content]',
		   '$filename1',
		   '$filename2',
		   '$rs[filename1_real]',
		   '$rs[filename2_real]',
		   '$rs[ah]',
		   '$reg_date',
		   '$reg_date2'
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