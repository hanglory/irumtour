<?
include_once("../include/common_file_report.php");


chk_power($_SESSION["sessLogin"]["proof"],"기본설정");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_customer";
$MENU = "cmp_basic";
$TITLE = "고객 명단 관리";


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
$sql_2 = $sql_1 . " order by id_no desc ";
//checkVar("",$sql_2);



$xls_name = "customer_" . date("Ymd") . ".xls";
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
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">대표자</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">고객명</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">성별</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">영문명</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">주민번호</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">여권번호</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">유효기간</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">연락처</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

			if($rs[rn]){
			$aes = new AES($rs[rn], $inputKey, $blockSize);
			$dec=$aes->decrypt();
			$rs[rn] = $dec;
			}
			if($rs[passport_no]){
			$aes = new AES($rs[passport_no], $inputKey, $blockSize);
			$dec=$aes->decrypt();
			$rs[passport_no] = $dec;
			}

?>
	    <tr align='center'>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[leader]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[name]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[sex]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[name_eng]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[rn]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[passport_no]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[passport_limit]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[phone]?></td>
	    </tr>
<?
	$num--;
}
?>
	</table>

</div>

</body>
</html>
