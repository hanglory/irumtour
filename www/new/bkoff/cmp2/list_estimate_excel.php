<?
include_once("../include/common_file_report.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_estimate";
$MENU = "cmp_basic";
$TITLE = "견적서 관리 대장";
if($id_no) $filter=" and id_no= $id_no";


#### 기본 정보
$column = "*";
$basicLink = "";


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)

#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword){
	$filter .=" and $target like '%$keyword%' ";
	$best="";	 //배너 select 초기화
	$findMode=1;
}



#query
$sql_1 = "select $column from $table where id_no>0 $filter";			//자료수
$sql_2 = $sql_1 . " order by id_no desc";
//checkVar("",$sql_2);

####자료갯수
list($rows)=$dbo->query($sql_1);//검색된 자료의 갯수
$row_search = $rows;

if($REMOTE_ADDR!="106.246.54.18"){
$xls_name = "estimate_" . date("Ymd") . ".xls";
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
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="padding:10px">


    <table border="0" cellspacing="0" cellpadding="3" width="3500" id="tbl_cmp_list" style="border-collapse:collapse;border:1px solid #ccc ">

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >고객명</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >연락처</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >경로</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >담당자</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >발송일</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >출국일</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >발송일</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >상품명</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >거래처</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >골프장명1</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >골프장명2</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >골프장명3</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >골프장명4</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >호텔명</th>

		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >룸타입</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >인원</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >판매가</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >항공정보</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >메모</th>

		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){


	$sql3="select * from cmp_golf where id_no=$rs[golf_id_no]";
	$dbo3->query($sql3);
	$rs3=$dbo3->next_record();	
?>
	    <tr align='center'>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[name]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[phone]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[view_path]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[main_staff]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[send_date]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[d_date]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[r_date]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[golf_name]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs3[partner]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[golf2_1_name]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[golf2_2_name]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[golf2_3_name]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[golf2_4_name]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[hotel_name]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[room_type]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[people]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[price]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?if($rs[d_air_no] || $rs[r_date_no]){?>출국편명:<?=$rs[d_air_no]?>,귀국편명:<?=$rs[r_air_no]?><?}?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs[memo]?></td>
	    </tr>
<?
	$num--;
}
?>

	</table>

	<!--내용이 들어가는 곳 끝-->

</body>
</html>