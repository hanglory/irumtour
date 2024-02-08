<?
include_once("../include/common_file.php");

//chk_power($_SESSION["sessLogin"]["proof"],"경영관리");


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "입출금 현황 리포트";



####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
if(!$target2) $target2="tour_date";
if(!$period_s) $period_s=date("Y/m/d",mktime(0,0,0,date("m"),1,date("Y")));
if(!$period_e) $period_e=date("Y/m/d",mktime(0,0,0,date("m")+1,1,date("Y"))-1);

#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;

if($target2=="total"){
	$filter .="
		and (
			(a.date_in >='$period_s' and a.date_in <='$period_e')
			or (a.date_in2 >='$period_s' and a.date_in2 <='$period_e')
			or (a.date_in3 >='$period_s' and a.date_in3 <='$period_e')
			or (a.date_out >='$period_s' and a.date_out <='$period_e')
			or (a.date_out2 >='$period_s' and a.date_out2 <='$period_e')
			or (a.date_out3 >='$period_s' and a.date_out3 <='$period_e')
		)
	";
	$rs[period_s]=$period_s;
	$rs[period_e]=$period_e;
	$find_bit=1;
}else{
	if($period_s){ $filter.= " and $target2 >='$period_s' ";$rs[period_s]=$period_s;$find_bit=1;}
	if($period_e){ $filter.= " and $target2 <='$period_e' ";$rs[period_e]=$period_e;$find_bit=1;}
}

if($keyword){
	$filter .=" and $target like '%$keyword%' ";
	$best="";	 //배너 select 초기화
	$findMode=1;
}



#query
$sql_1 = "
	select
		a.*,
		b.name as leader,
		(select count(name) from cmp_people where code=a.code and name<>'' and bit=1) as cnt
	from cmp_people as a left join cmp_reservation as b
	on a.code=b.code
	where 
        a.code<>''
        and a.name<>''
        and b.name<>''
        and a.bit=1
        and b.cp_id='$CP_ID'
        $filter
";
$sql_2 = $sql_1 . " order by b.name asc";
//checkVar("",$sql_2);

####자료갯수
list($rows)=$dbo->query($sql_1);//검색된 자료의 갯수
$row_search = $rows;

if(!strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
$xls_name = "report_".date("Ymd").".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment;filename=$xls_name;");
header( "Content-Description: PHP4 Generated Data" );
echo "<meta content=\"application/vnd.ms-excel; charset=UTF-8\" name=\"Content-type\"> ";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
#tbl_cmp_list{border-collapse:collapse;border:1px solid #000 }
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:right;padding-right:2px;}
#tbl_cmp_list td{font-size:9pt;border-left:1px solid #000;border-bottom:1px solid #000;}
#tbl_cmp_list tH{font-size:9pt;border-left:1px solid #000;border-bottom:1px solid #000}
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:center;padding-right:2px;}
.r{padding-right:5px !important;text-align: right !important}
.blue{color:blue !important;}
.red{color:red !important;}
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="padding:10px">


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6">
    		<th class="subject" >대표자명</th>
    		<th class="subject" >고객명</th>
    		<th class="subject blue" >출금액1,2,3(항공,지상비,고객환불)</th>
    		<th class="subject red" >입금액1,2,3(계약금,잔금,거래처환불)</th>
		</tr>


<!-- 합계 s -->
<?
$sum1=0;
$sum2=0;
$sum3=0;
$sum4=0;
$sum5=0;
$sum6=0;

$dbo->query($sql_2);
//if($REMOTE_ADDR=="106.246.54.27") checkVar($target.mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

    if($rs[date_out] >=$period_s && $rs[date_out] <=$period_e){}else{$rs[price_air]=0;$rs[date_out]="";}
    if($rs[date_out2] >=$period_s && $rs[date_out2] <=$period_e){}else{$rs[price_land]=0;$rs[date_out2]="";}
    if($rs[date_out3] >=$period_s && $rs[date_out3] <=$period_e){}else{$rs[price_refund]=0;$rs[date_out3]="";}
    if($rs[date_in] >=$period_s && $rs[date_in] <=$period_e){}else{$rs[price_prev]=0;$rs[date_in]="";}
    if($rs[date_in2] >=$period_s && $rs[date_in2] <=$period_e){}else{$rs[price_prev2]=0;$rs[date_in2]="";}
    if($rs[date_in3] >=$period_s && $rs[date_in3] <=$period_e){}else{$rs[price_prev3]=0;$rs[date_in3]="";}


    $sum1 += $rs[price_air]+$rs[price_land]+$rs[price_refund];
    $sum2 += $rs[price_prev]+$rs[price_prev2]+$rs[price_prev3];
}
?>        
        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="subject" colspan="2">합계</th>
            <th class="r pr10 blue" style="text-align: right !important"><?=nf($sum1)?></th>
            <th class="r pr10 red" style="text-align: right !important"><?=nf($sum2)?></th>
        </tr>   
<!-- 합계 f -->




<?
$sum1=0;
$sum2=0;
$sum3=0;
$sum4=0;
$sum5=0;
$sum6=0;

if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//if($REMOTE_ADDR=="106.246.54.27") checkVar($target.mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	$row = ($prev_leader!=$rs[leader])?1:0;


	if($rs[date_out] >=$period_s && $rs[date_out] <=$period_e){}else{$rs[price_air]=0;$rs[date_out]="";}
	if($rs[date_out2] >=$period_s && $rs[date_out2] <=$period_e){}else{$rs[price_land]=0;$rs[date_out2]="";}
	if($rs[date_out3] >=$period_s && $rs[date_out3] <=$period_e){}else{$rs[price_refund]=0;$rs[date_out3]="";}
	if($rs[date_in] >=$period_s && $rs[date_in] <=$period_e){}else{$rs[price_prev]=0;$rs[date_in]="";}
	if($rs[date_in2] >=$period_s && $rs[date_in2] <=$period_e){}else{$rs[price_prev2]=0;$rs[date_in2]="";}
	if($rs[date_in3] >=$period_s && $rs[date_in3] <=$period_e){}else{$rs[price_prev3]=0;$rs[date_in3]="";}


	$sum1 += $rs[price_air]+$rs[price_land]+$rs[price_refund];
	$sum2 += $rs[price_prev]+$rs[price_prev2]+$rs[price_prev3];
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">

	      <td height="30"><?=$rs[leader]?></td>
	      <td height="30"><?=$rs[name]?></td>
	      <td class="r pr10 blue" style="text-align: right !important"><?=nf($rs[price_air]+$rs[price_land]+$rs[price_refund])?></td>
	      <td class="r pr10 red" style="text-align: right !important"><?=nf($rs[price_prev]+$rs[price_prev2]+$rs[price_prev3])?></td>
	    </tr>
<?
	$num--;
}
?>
	    <tr align=center height=25 bgcolor="#F7F7F6">
    		<th class="subject" colspan="2">합계</th>
    		<th class="r pr10 blue" style="text-align: right !important"><?=nf($sum1)?></th>
    		<th class="r pr10 red" style="text-align: right !important"><?=nf($sum2)?></th>
		</tr>
	</table>


</div>

</body>
</html>