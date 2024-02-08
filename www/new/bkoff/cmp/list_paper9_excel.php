<?
include_once("../include/common_file.php");

if($partner==1){
	chk_power($_SESSION["sessLogin"]["proof"],"파트너실적");
}
else{
	chk_power($_SESSION["sessLogin"]["proof"],"통계");
}
chk_power($_SESSION["sessLogin"]["proof"],"엑셀다운로드");


if($_SESSION["sessLogin"]["staff_type"] == "ceo" && !$partner){
	$sql = "select * from cmp_staff where staff_type in ('ceo','staff','partner_a','partner_i','partner_g')";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
		$partners .="or main_staff like '%($rs[id])%' ";
	}
	$partners = substr($partners,3);
	$partner_filter = " and ($partners)";
}elseif($_SESSION["sessLogin"]["staff_type"] == "ceo" && $partner==1){
	$sql = "select * from cmp_staff where staff_type in ( 'partner_a','partner_i','partner_g')";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
		$partners .="or main_staff like '%($rs[id])%' ";
	}
	$partners = substr($partners,3);
	$partner_filter = " and ($partners)";
}elseif(($_SESSION["sessLogin"]["staff_type"] == "staff" || $_SESSION["sessLogin"]["staff_type"] == "ceo") && $partner==2){
	$sql = "select * from cmp_staff where staff_type in ('staff','leader')";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
		$partners .="or main_staff like '%($rs[id])%' ";
	}
	$partners = substr($partners,3);
	$partner_filter = " and ($partners)";

}elseif(strstr("partner_a,partner_g",$_SESSION["sessLogin"]["staff_type"])){
    $partner_ab = ($_SESSION["sessLogin"]["staff_type"]=="partner_a")? "'partner_a','partner_g'":"'partner_g'";
    $sql = "select * from cmp_staff where cp_id='$CP_ID' and staff_type in ($partner_ab)";
    $dbo->query($sql);
    while($rs=$dbo->next_record()){
        $partners .="or main_staff like '%($rs[id])%' ";
    }
    $partners = substr($partners,3);
    $partner_filter = " and ($partners)";
        
}else{
	$partner_filter = " and main_staff like '%(".$_SESSION["sessLogin"]["id"].")'";
}
/*독립형 파트너*/
if($_SESSION["sessLogin"]["staff_type"]=="partner_a"){
    $partners="";
    $sql = "select * from cmp_staff where cp_id='$CP_ID'";
    $dbo->query($sql);
    while($rs=$dbo->next_record()){
        $partners .="or main_staff like '%($rs[id])%' ";
    }
    $partners = substr($partners,3);
    $partner_filter = " and ($partners)";
    $MENU = "cmp_partner";
}


$dtype=($dtype)? $dtype : "d_date";



$date_s = ($date_s)? $date_s : $PAPER_DEFAULT_DAY1;
$date_e = ($date_e)? $date_e : $PAPER_DEFAULT_DAY2;

$year_this = substr($date_s,0,4);
$year_prev = substr($date_s,0,4)-1;
$date_s2 = $year_prev . substr($date_s,4);
$date_e2 = $year_prev . substr($date_e,4);


if(substr($date_s,0,4)!=substr($date_e,0,4)){
	error("검색하시는 시작일의 년도와 종료일의 연도가 같아야 합니다.");
	exit;
}



if(!strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
$xls_name = "report1_" . date("Ymd") . ".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/vnd.ms-excel; charset=euc-kr");
header("Content-Disposition: attachment;filename=$xls_name;");
header( "Content-Description: PHP4 Generated Data" );
}

$TEN=1;
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
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="padding:10px">




	<?
	$sql = "
		select
			main_staff as did,
			right(left($dtype,7),2) as did2,
			sum(people) as sum_people,
			sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
			from cmp_reservation as a
		where
			($dtype >= '$date_s' and $dtype <='$date_e')
			$filter
			$partner_filter
            $FILTER_PARTNER_QUERY_CPID            
		group by main_staff,right(left($dtype,7),2)
	";
	$dbo->query($sql);
	//if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){
		$did = $rs[did];
		$did2 = $rs[did2];

		$DATA[$did][$did2]["people"] = $rs[sum_people];
		$DATA[$did][$did2]["fee"] = $rs[sum_fee];
		$arr[] = $rs[did];
	}

	$arr = @array_unique($arr);

	?>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align=center height=25 bgcolor="#F7F7F6">
			<th class="subject" >구분</th>
			<th class="subject">1월</th>
			<th class="subject">2월</th>
			<th class="subject">3월</th>
			<th class="subject">4월</th>
			<th class="subject">5월</th>
			<th class="subject">6월</th>
			<th class="subject">7월</th>
			<th class="subject">8월</th>
			<th class="subject">9월</th>
			<th class="subject">10월</th>
			<th class="subject">11월</th>
			<th class="subject">12월</th>
			<th class="subject" >합계</th>
			<th class="subject" >객단가</th>
		</tr>
		<?
        $sql = "
                select 
                    did,
                    sum(people) as total_people,
                    sum(fee) as total_fee
                from cmp_tmp_paper9
                where year='$year'
                group by did,year
                order by total_people desc
            ";
        $dbo->query($sql);
        //checkVar(mysql_error(),$sql);
        while($rs=$dbo->next_record()){            

            $did = $rs[did];
            $did2 = $rs[did2];

			$sum_line=0;
			$sum_line2=0;

			$sum_line=$DATA[$did]["01"]["fee"];
			$sum_line+=$DATA[$did]["02"]["fee"];
			$sum_line+=$DATA[$did]["03"]["fee"];
			$sum_line+=$DATA[$did]["04"]["fee"];
			$sum_line+=$DATA[$did]["05"]["fee"];
			$sum_line+=$DATA[$did]["06"]["fee"];
			$sum_line+=$DATA[$did]["07"]["fee"];
			$sum_line+=$DATA[$did]["08"]["fee"];
			$sum_line+=$DATA[$did]["09"]["fee"];
			$sum_line+=$DATA[$did]["10"]["fee"];
			$sum_line+=$DATA[$did]["11"]["fee"];
			$sum_line+=$DATA[$did]["12"]["fee"];

			$sum_line2=$DATA[$did]["01"]["people"];
			$sum_line2+=$DATA[$did]["02"]["people"];
			$sum_line2+=$DATA[$did]["03"]["people"];
			$sum_line2+=$DATA[$did]["04"]["people"];
			$sum_line2+=$DATA[$did]["05"]["people"];
			$sum_line2+=$DATA[$did]["06"]["people"];
			$sum_line2+=$DATA[$did]["07"]["people"];
			$sum_line2+=$DATA[$did]["08"]["people"];
			$sum_line2+=$DATA[$did]["09"]["people"];
			$sum_line2+=$DATA[$did]["10"]["people"];
			$sum_line2+=$DATA[$did]["11"]["people"];
			$sum_line2+=$DATA[$did]["12"]["people"];

			$sum1 += $DATA[$did]["01"]["people"];
			$sum2 += $DATA[$did]["02"]["people"];
			$sum3 += $DATA[$did]["03"]["people"];
			$sum4 += $DATA[$did]["04"]["people"];
			$sum5 += $DATA[$did]["05"]["people"];
			$sum6 += $DATA[$did]["06"]["people"];
			$sum7 += $DATA[$did]["07"]["people"];
			$sum8 += $DATA[$did]["08"]["people"];
			$sum9 += $DATA[$did]["09"]["people"];
			$sum10 += $DATA[$did]["10"]["people"];
			$sum11 += $DATA[$did]["11"]["people"];
			$sum12 += $DATA[$did]["12"]["people"];
			$sum13 += $sum_line2;
			$sum14 += @($sum_line/$sum_line2);

		?>
		<tr>
			<td style="background-color:#f0f0f0"><?=trim($rs[did])?></td>
			<td><?=nf($DATA[$did]["01"]["people"])?></td>
			<td><?=nf($DATA[$did]["02"]["people"])?></td>
			<td><?=nf($DATA[$did]["03"]["people"])?></td>
			<td><?=nf($DATA[$did]["04"]["people"])?></td>
			<td><?=nf($DATA[$did]["05"]["people"])?></td>
			<td><?=nf($DATA[$did]["06"]["people"])?></td>
			<td><?=nf($DATA[$did]["07"]["people"])?></td>
			<td><?=nf($DATA[$did]["08"]["people"])?></td>
			<td><?=nf($DATA[$did]["09"]["people"])?></td>
			<td><?=nf($DATA[$did]["10"]["people"])?></td>
			<td><?=nf($DATA[$did]["11"]["people"])?></td>
			<td><?=nf($DATA[$did]["12"]["people"])?></td>
			<td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line2)?></td>
			<td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line/$sum_line2)?></td>
		</tr>
	   <?}?>



		<tr style="background-color:#ffe6cc">
			<td>합계</td>
			<td><?=nf($sum1)?></td>
			<td><?=nf($sum2)?></td>
			<td><?=nf($sum3)?></td>
			<td><?=nf($sum4)?></td>
			<td><?=nf($sum5)?></td>
			<td><?=nf($sum6)?></td>
			<td><?=nf($sum7)?></td>
			<td><?=nf($sum8)?></td>
			<td><?=nf($sum9)?></td>
			<td><?=nf($sum10)?></td>
			<td><?=nf($sum11)?></td>
			<td><?=nf($sum12)?></td>
			<td class="r"><?=nf($sum13)?></td>
			<td class="r"><?=nf($sum14)?></td>
		</tr>


	</table>

	<br/>



	<?

	$arr = @array_unique($arr);

	?>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
			<th class="subject" >구분</th>
			<th class="subject">1월</th>
			<th class="subject">2월</th>
			<th class="subject">3월</th>
			<th class="subject">4월</th>
			<th class="subject">5월</th>
			<th class="subject">6월</th>
			<th class="subject">7월</th>
			<th class="subject">8월</th>
			<th class="subject">9월</th>
			<th class="subject">10월</th>
			<th class="subject">11월</th>
			<th class="subject">12월</th>
			<th class="subject" >합계</th>
			<th class="subject" >객단가</th>
		</tr>
		<?
		$sum1 = 0;
		$sum2 = 0;
		$sum3 = 0;
		$sum4 = 0;
		$sum5 = 0;
		$sum6 = 0;
		$sum7 = 0;
		$sum8 = 0;
		$sum9 = 0;
		$sum10 = 0;
		$sum11 = 0;
		$sum12 = 0;
		$sum13 = 0;
		$sum14 = 0;

        $sql = "
                select 
                    did,
                    sum(people) as total_people,
                    sum(fee) as total_fee
                from cmp_tmp_paper9
                where year='$year'
                group by did,year
                order by total_fee desc
            ";
        $dbo->query($sql);
        //checkVar(mysql_error(),$sql);
        while($rs=$dbo->next_record()){            

            $did = $rs[did];
            $did2 = $rs[did2];

			$sum_line=0;
			$sum_line2=0;

			$sum_line=$DATA[$did]["01"]["fee"];
			$sum_line+=$DATA[$did]["02"]["fee"];
			$sum_line+=$DATA[$did]["03"]["fee"];
			$sum_line+=$DATA[$did]["04"]["fee"];
			$sum_line+=$DATA[$did]["05"]["fee"];
			$sum_line+=$DATA[$did]["06"]["fee"];
			$sum_line+=$DATA[$did]["07"]["fee"];
			$sum_line+=$DATA[$did]["08"]["fee"];
			$sum_line+=$DATA[$did]["09"]["fee"];
			$sum_line+=$DATA[$did]["10"]["fee"];
			$sum_line+=$DATA[$did]["11"]["fee"];
			$sum_line+=$DATA[$did]["12"]["fee"];

			$sum_line2=$DATA[$did]["01"]["people"];
			$sum_line2+=$DATA[$did]["02"]["people"];
			$sum_line2+=$DATA[$did]["03"]["people"];
			$sum_line2+=$DATA[$did]["04"]["people"];
			$sum_line2+=$DATA[$did]["05"]["people"];
			$sum_line2+=$DATA[$did]["06"]["people"];
			$sum_line2+=$DATA[$did]["07"]["people"];
			$sum_line2+=$DATA[$did]["08"]["people"];
			$sum_line2+=$DATA[$did]["09"]["people"];
			$sum_line2+=$DATA[$did]["10"]["people"];
			$sum_line2+=$DATA[$did]["11"]["people"];
			$sum_line2+=$DATA[$did]["12"]["people"];

			$sum1 += $DATA[$did]["01"]["fee"];
			$sum2 += $DATA[$did]["02"]["fee"];
			$sum3 += $DATA[$did]["03"]["fee"];
			$sum4 += $DATA[$did]["04"]["fee"];
			$sum5 += $DATA[$did]["05"]["fee"];
			$sum6 += $DATA[$did]["06"]["fee"];
			$sum7 += $DATA[$did]["07"]["fee"];
			$sum8 += $DATA[$did]["08"]["fee"];
			$sum9 += $DATA[$did]["09"]["fee"];
			$sum10 += $DATA[$did]["10"]["fee"];
			$sum11 += $DATA[$did]["11"]["fee"];
			$sum12 += $DATA[$did]["12"]["fee"];
			$sum13 += $sum_line;
			$sum14 += @($sum_line/$sum_line2);

		?>
		<tr>
			<td style="background-color:#f0f0f0"><?=trim($rs[did])?></td>
			<td><?=nf($DATA[$did]["01"]["fee"])?></td>
			<td><?=nf($DATA[$did]["02"]["fee"])?></td>
			<td><?=nf($DATA[$did]["03"]["fee"])?></td>
			<td><?=nf($DATA[$did]["04"]["fee"])?></td>
			<td><?=nf($DATA[$did]["05"]["fee"])?></td>
			<td><?=nf($DATA[$did]["06"]["fee"])?></td>
			<td><?=nf($DATA[$did]["07"]["fee"])?></td>
			<td><?=nf($DATA[$did]["08"]["fee"])?></td>
			<td><?=nf($DATA[$did]["09"]["fee"])?></td>
			<td><?=nf($DATA[$did]["10"]["fee"])?></td>
			<td><?=nf($DATA[$did]["11"]["fee"])?></td>
			<td><?=nf($DATA[$did]["12"]["fee"])?></td>
			<td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line)?></td>
			<td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line/$sum_line2)?></td>
		</tr>
	   <?}?>



		<tr style="background-color:#ffe6cc">
			<td>합계</td>
			<td><?=nf($sum1)?></td>
			<td><?=nf($sum2)?></td>
			<td><?=nf($sum3)?></td>
			<td><?=nf($sum4)?></td>
			<td><?=nf($sum5)?></td>
			<td><?=nf($sum6)?></td>
			<td><?=nf($sum7)?></td>
			<td><?=nf($sum8)?></td>
			<td><?=nf($sum9)?></td>
			<td><?=nf($sum10)?></td>
			<td><?=nf($sum11)?></td>
			<td><?=nf($sum12)?></td>
			<td class="r"><?=nf($sum13)?></td>
			<td class="r"><?=nf($sum14)?></td>
		</tr>


	</table>



</div>

</body>
</html>
