<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"엑셀다운로드");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_qna";
$MENU = "cmp_basic";
$LEFT_HIDDEN = "1";
$TITLE = "문의고객 관리 대장";




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


$xls_name = "counsel_" . date("Ymd") . ".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/vnd.ms-excel; charset=euc-kr");
header("Content-Disposition: attachment;filename=$xls_name;");
header( "Content-Description: PHP4 Generated Data" );
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="padding:10px">


    <table border="0" cellspacing="0" cellpadding="3" width="3500" id="tbl_cmp_list" style="border-collapse:collapse;border:1px solid #ccc ">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">일자</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">고객명</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">연락처</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">팩스</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">이메일</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">국가</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">인원</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">수준</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">기간</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">상담내역</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">담당자</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">등록일</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	$bit_login = ($rs[bit_login])?"허용":"차단";
?>
	    <tr align='center'>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[qdate]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[name]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[phone]?></td>
 	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[fax]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[email]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[nation]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[people]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[qlevel]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[period]?></td>
	      <td style="width:300px;padding-left:5px;font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" align="left"><?=$rs[content]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[main_staff]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[reg_date]?></td>
	    </tr>
<?
	$num--;
}
?>
	</table>


</div>

</body>
</html>
