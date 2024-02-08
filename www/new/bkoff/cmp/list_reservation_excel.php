<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"엑셀다운로드");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_reservation";
$MENU = "cmp_basic";
$TITLE = "고객예약정보";
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


if(strstr($_SESSION["sessLogin"]["staff_type"],"partner")){
	$filter.=" and main_staff like '%(".$_SESSION["sessLogin"]["id"].")'";
}



#query
$sql_1 = "select $column from $table where cp_id='$CP_ID' $filter";			//자료수
$sql_2 = $sql_1 . " order by id_no desc";
//checkVar("",$sql_2);

####자료갯수
list($rows)=$dbo->query($sql_1);//검색된 자료의 갯수
$row_search = $rows;

if($_SERVER["REMOTE_ADDR"]!="106.246.54.27"){
$xls_name = "reservation_" . date("Ymd") . ".xls";
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
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >대표자명</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >핸드폰</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >경로</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >담당자</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >예약일</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >출국일</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >귀국일</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >총인원</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >골프장명</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >판매가</th>

		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >고객명</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >성별</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >영문명</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >주민번호</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >여권번호</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >연락처</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >메모</th>

		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >계약금</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >잔금</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >잔금입금일</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >입금확인</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >항공요금</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >지상비</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >고객환불</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >수익</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >결제정보</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >세금계산서</th>

		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >출국편명</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >귀국편명</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >비고</th>

		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >거래처</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >담당자</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >연락처</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >현지담당</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >비상연락처</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >공항미팅위치</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//checkVar(mysql_error(),$sql_2);

while($rs=$dbo->next_record()){

	$sql2 = $dbo2->query("select sum(price) as total ,count(bit) as cnt from cmp_people where bit=1 and code=$rs[code]");
	$rs2=$dbo2->next_record();
	$price = $rs2[total];
	$cnt = $rs2[cnt];
	$arr = explode("(",$rs[main_staff]);

	$dbo3->query("select * from cmp_golf where id_no=$rs[golf_id_no]");
	$rs3 = $dbo3->next_record();

	$k=0;
	$sql2 = $dbo2->query("select * from cmp_people where bit=1 and code=$rs[code]");
	while($rs2=$dbo2->next_record()){

		if($rs2[rn]){
		$aes = new AES($rs2[rn], $inputKey, $blockSize);
		$dec=$aes->decrypt();
		$rs2[rn] = $dec;
		}

		if($rs2[passport_no]){
		$aes = new AES($rs2[passport_no], $inputKey, $blockSize);
		$dec=$aes->decrypt();
		$rs2[passport_no] = $dec;
		}

?>
	    <tr align='center'>
			<?if(!$k){?>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  height="35" rowspan="<?=$cnt?>"><?=$rs[name]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs[phone]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs[view_path]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$arr[0]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs[tour_date]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs[d_date]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs[r_date]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=nf($rs[people])?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs[golf_name]?></td>
			<?}?>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=nf($rs2[price])?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs2[name]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs2[sex]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs2[name_eng]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs2[rn]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs2[passport_no]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs2[phone]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=$rs2[memo]?></td>

			<?if(!$k){?>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=nf($rs[price_prev])?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=nf($rs[price_last])?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs[pay_date]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs[pay_check]?></td>
			<?}?>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=nf($rs2[price_air])?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=nf($rs2[price_land])?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=nf($rs2[price_refund])?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" ><?=nf(($rs2[price_prev]+$rs2[price_prev2]+$rs2[price_prev3])-($rs2[price_air]+$rs2[price_land]+$rs[price_refund]))?></td>
			<?if(!$k){?>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs[pay_method]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs[bit_tax]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs[d_air_no]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs[r_air_no]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs[bit_sending]?></td>

			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs3[partner]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs3[main_staff]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs3[phone]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs3[local_staff]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs3[phone2]?></td>
			<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"  rowspan="<?=$cnt?>"><?=$rs3[meeting_place]?></td>
			<?}?>
	    </tr>
<?
		$k++;
	}
	$num--;
}
?>
	</table>

	<!--내용이 들어가는 곳 끝-->

</body>
</html>