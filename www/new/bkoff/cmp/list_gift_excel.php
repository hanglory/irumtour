<?
include_once("../include/common_file.php");


chk_power($_SESSION["sessLogin"]["proof"],"기본설정");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_gift";
$MENU = "cmp_basic";
$TITLE = "고객 주소록";


if($REMOTE_ADDR!="1106.246.54.27"){
$xls_name = "customer_" . date("Ymd") . ".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/vnd.ms-excel; charset=euc-kr");
header("Content-Disposition: attachment;filename=$xls_name;");
header( "Content-Description: PHP4 Generated Data" );
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
td,th{font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc}
.r{text-align:right !important;}
.c{text-align:center}
.subject{background-color:#eee}
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="padding:10px">

    <table border="0" cellspacing="0" cellpadding="3" id="tbl_cmp_list" style="border-collapse:collapse;border:1px solid #ccc ">


    <table border="0" cellspacing="0" width="800" cellpadding="3" id="tbl_cmp_list" style="border-collapse:collapse;border:1px solid #ccc ">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >등록일</th>
		<th class="subject" >고객명</th>
		<th class="subject" >핸드폰</th>
		<th class="subject" >주소</th>
		<th class="subject" >발송내역</th>
		<th class="subject" >발송일</th>
		</tr>



	<?

	#검색조건
	$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
	if($keyword){
		$filter .=" and $target like '%$keyword%' ";
		$best="";	 //배너 select 초기화
		$findMode=1;
	}

	$sql_1 = "select * from $table where id_no>0 $filter";			//자료수
	$sql = $sql_1 . " order by id_no desc ";

	$dbo->query($sql);
	//checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){
	?>
		<tr>
	      <td><?=$rs[save_date]?></td>
		  <td><?=$rs[name]?></td>
		  <td><?=$rs[cell]?></td>
	      <td style="text-align:left;padding-left:10px"><?=$rs[address]?></td>
	      <td style="text-align:left;padding-left:10px"><?=$rs[content]?></td>
	      <td><?=$rs[send_date]?></td>
		</tr>
	<?}?>
	</table>




</div>
</body>
</html>