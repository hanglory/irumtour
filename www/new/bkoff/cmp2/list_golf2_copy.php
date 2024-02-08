<?
include_once("../include/common_file.php");


$sql = "select * from cmp_golf2 where id_no=$id_no";
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

	$rs[name] = $rs[name] . " 복사본";

	$sql2="
		insert into cmp_golf2 (
		   name,
		   nation,
		   city,
		   content,
		   filename1,
		   filename1_real,
		   reg_date,
		   reg_date2
	   ) values (
		   '$rs[name]',
		   '$rs[nation]',
		   '$rs[city]',
		   '$rs[content]',
		   '$filename1',
		   '$rs[filename1_real]',
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