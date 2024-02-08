<?
include_once("../include/common_file_report.php");



####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_reservation";
$MENU = "cmp_basic";
$LEFT_HIDDEN = "1";
$TITLE = "기간별매출현황";



#### 기본 정보
$column = "*";
$basicLink = "";


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)

#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : trim($keyword);
if($keyword){
	$filter .=" and $target like '%$keyword%' ";
	$best="";	 //배너 select 초기화
	$findMode=1;
}

if($period_s){ $filter.= " and $target2 >='$period_s' ";$rs[period_s]=$period_s;$find_bit=1;}
if($period_e){ $filter.= " and $target2 <='$period_e' ";$rs[period_e]=$period_e;$find_bit=1;}


#query
$sql_1 = "select $column from $table where id_no>0 $filter";			//자료수
$sql_2 = $sql_1 . " order by id_no desc";
//checkVar("",$sql_2);

####자료갯수
list($rows)=$dbo->query($sql_1);//검색된 자료의 갯수
$row_search = $rows;


$xls_name = "report1_" . date("Ymd") . ".xls";
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
	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">대표자명</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">국가</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">지역</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">골프장명</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">경로</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">인원</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">예약일</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">출발일</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">박수</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" >매출</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" colspan="3">입금가</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">수수료<br/>(매출-입금가)</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">1인수익</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">고객입금액</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">가지급금</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">담당자</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">결제수단</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">골프공</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" rowspan="2">항공카버</th>
		</tr>


	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" >인원수<br/>*1인판매가</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" >항공</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" >지상</th>
		<th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="subject" >소계</th>
		</tr>

<?
$price1=0;
$price2=0;
$price3=0;
$price4=0;
$price5=0;
$price6=0;
$price7=0;
$price8=0;
$price9=0;
$price10=0;
$price11=0;

if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){
	$golf_name = explode(">",$rs[golf_name]);

	$night = (strtotime($rs[r_date]) -  strtotime($rs[d_date]))/86400;//박수

	$sql3 = "select
			count(*) as cnt,
			sum(price) as price,
			sum(price_air) as price_air,
			sum(price_land) as price_land
		from cmp_people where code=$rs[code] and bit=1";
	$dbo3->query($sql3);
	$rs3= $dbo3->next_record();
	$gain_one = ($rs3[price] - ($rs3[price_air]+$rs3[price_land]))/$rs[people];

	$staff = explode("(",$rs[main_staff]);

?>
	    <tr align='center'>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" height="35"><?=$rs[name]?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=trim($golf_name[0])?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=trim($golf_name[1])?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=trim($golf_name[2])?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[view_path]?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=nf($rs[people])?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[tour_date]?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[d_date]?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$night?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=nf($rs3[price])?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=nf($rs3[price_air])?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=nf($rs3[price_land])?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=nf($rs3[price_air]+$rs3[price_land])?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=nf($rs3[price]-($rs3[price_air]+$rs3[price_land]))?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=nf($gain_one)?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=nf($rs[price_customer_input])?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=nf($rs[price_tmp_output])?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$staff[0]?>담당자</td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[pay_method]?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=$rs[bit_cash]?></td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=$rs[bit_tax]?></td>
	    </tr>
<?
	$price1+=$rs[people];
	$price2+=$rs3[price];
	$price3+=$rs3[price_air];
	$price4+=$rs3[price_land];
	$price5+=($rs3[price_air]+$rs3[price_land]);
	$price6+=($rs3[price]-($rs3[price_air]+$rs3[price_land]));
	$price7+=$gain_one;
	$price8+=($rs[price_customer_input]);
	$price9+=($rs[price_tmp_output]);

	$price10+=$rs[golf_ball];
	$price11+=$rs[air_cover];

	$num--;
}
?>

	    <tr align='center'>
		<td height="35" colspan="5" style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red">합계</td>
		<td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price1)?></td>
		<td></td>
		<td></td>
		<td></td>
		<td class="numberic" style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price2)?></td>
		<td class="numberic" style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price3)?></td>
		<td class="numberic" style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price4)?></td>
		<td class="numberic" style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price5)?></td>
		<td class="numberic" style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price6)?></td>
		<td class="numberic" style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price7)?></td>
		<td class="numberic" style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price8)?></td>
		<td class="numberic" style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price9)?></td>
		<td></td>
		<td></td>
		<td class="numberic" style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price10)?></td>
		<td class="numberic" style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price11)?></td>
	    </tr>
	</table>

</body>
</html>