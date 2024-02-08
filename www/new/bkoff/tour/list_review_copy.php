<?
include_once("../include/common_file.php");


$sql = "select * from ez_review where id_no=$id_no";
$dbo->query($sql);
$rs=$dbo->next_record();

if($rs[id_no]){

	$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

	$rs[reg_date] = date('Y/m/d');
	$rs[reg_date2] = date('H:i:s');

	$filename1 = str_replace("/","/copy_",$rs[filename1]);
	$file = "../../public/review/$rs[filename1]";
	$file2 = "../../public/review/$filename1";
	copy($file,$file2);
	$rs[filename1] = $filename1;

	$rs[name] = $rs[name] . " 복사본";

	$sql2="
		insert into ez_review (
       cp_id, 
		   name,
		   nation,
		   content,
		   filename1,
		   filename1_real,
		   reg_date,
		   reg_date2,
		   city,
		   url,
		   ag,
		   gh
	   ) values (
		   '$CP_ID',
       '$rs[name]',
		   '$rs[nation]',
		   '$rs[content]',
		   '$rs[filename1]',
		   '$rs[filename1_real]',
		   '$rs[reg_date]',
		   '$rs[reg_date2]',
		   '$rs[city]',
		   '$rs[url]',
		   '$rs[ag]',
		   '$rs[gh]'
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