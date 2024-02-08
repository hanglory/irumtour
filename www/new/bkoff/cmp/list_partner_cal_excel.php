<?
include_once("../include/common_file.php");

//chk_power($_SESSION["sessLogin"]["proof"],"경영관리");
$staff="(". $_SESSION["sessLogin"]["id"] .")";

$code=$date_s."_".$date_e."_". $dtype."_".$staff;
$code = str_replace("/","",$code);
$code = str_replace("(","",$code);
$code = str_replace(")","",$code);

if(!strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
$xls_name = "report_" . date("Ymd") . ".xls";
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
#tbl_cmp_list{border-collapse:collapse;border:1px solid #000 }
#tbl_cmp_list th,#tbl_cmp_list td{padding:5px 0 5px 0;text-align:right;padding-right:2px;}
#tbl_cmp_list th,#tbl_cmp_list td{font-size:9pt;border-left:1px solid #000;border-bottom:1px solid #000;}
#tbl_cmp_list th{font-size:9pt;border-left:1px solid #000;border-bottom:1px solid #000;text-align: center}
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="padding:10px">

	<?
	$staff_id= substr($staff,1,-1);
	$sql = "select * from cmp_staff where id='$staff_id'";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	$target_rate = ($rs[target_rate])? $rs[target_rate] : 50;
	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar($rs[target_rate].mysql_error(),$sql);}


	$danga_g = rnf($DANGA_GOLF);
	$danga_g2 = rnf($DANGA_GOLF2);
	$danga_c = rnf($DANGA_AIR);
	$danga_i = rnf($DANGA_INC);

	$sum1=0;
	$sum2=0;
	$sum3=0;

	$sql = "
		select
			sum(golf_ball) as ball,
			sum(golf_ball2) as ball2,
			sum(air_cover) as cover,
			sum(dollarbook) as book,
			sum((select count(insure) from cmp_people where code=a.code and bit=1)) as ins,
			sum((select sum(price_prev) from cmp_people where code=a.code and bit=1 and date_in<>'' and price_prev>0)) as total_in1,
			sum((select sum(price_prev2) from cmp_people where code=a.code and bit=1 and date_in2<>'' and price_prev2>0)) as total_in2,
			sum((select sum(price_prev3) from cmp_people where code=a.code and bit=1 and date_in3<>'' and price_prev3>0)) as total_in3,
			sum((select sum(price_air) from cmp_people where code=a.code and bit=1 and date_out<>'' and price_air>0)) as total_out1,
			sum((select sum(price_land) from cmp_people where code=a.code and bit=1 and date_out2<>'' and price_land>0)) as total_out2,
			sum((select sum(price_refund) from cmp_people where code=a.code and bit=1 and date_out3<>'' and price_refund>0)) as total_out3,
			sum(
				(
					(select sum(price_prev) from cmp_people where code=a.code and bit=1)
					+(select sum(price_prev2) from cmp_people where code=a.code and bit=1)
					+(select sum(price_prev3) from cmp_people where code=a.code and bit=1)
				)
				-
				(
					(select sum(price_air) from cmp_people where code=a.code and bit=1)
					+(select sum(price_land) from cmp_people where code=a.code and bit=1)
					+(select sum(price_refund) from cmp_people where code=a.code and bit=1)
				)
			) as sum_fee_chk			
		from cmp_reservation as a
		where $dtype >='$date_s' and $dtype <='$date_e'
		and main_staff like '%${staff}'
	";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27")checkVar($rs[id_no].mysql_error(),$sql);


	$rs[sum_fee] = ($rs[total_in1]+$rs[total_in2]+$rs[total_in3]);
	$rs[sum_fee] -= ($rs[total_out1]+$rs[total_out2]+$rs[total_out3]);

	$sql2 = "	
		select 
			count(a.insure) as ins
		from cmp_people as a inner join cmp_reservation as b 
		on a.code=b.code
		inner join cmp_golf as c
		on b.golf_id_no=c.id_no
		where a.bit=1
		and b.${dtype} >='$date_s' and b.${dtype} <='$date_e'
		and b.main_staff like '%${staff}'
		and c.nation<>'한국'
		";
	list($rows) = $dbo2->query($sql2);
	$rs2=$dbo2->next_record();
	$rs[ins] = $rs2[ins];	

	$sql2 = "select * from cmp_cal_etc where code='$code'";
	$dbo2->query($sql2);
	$rs2=$dbo2->next_record();
	//checkVar($rs2[add1].mysql_error(),$sql2);
	?>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" width="20%">매출</th>
		<th class="subject" width="20%">요율(<?=$target_rate?>%)</th>
		<th class="subject" width="20%"></th>
		<th class="subject" width="20%"></th>
		<th class="subject" width="20%">비고</th>
		</tr>

	    <tr align='center'>
	      <th><?=nf($rs[sum_fee])?><!-- 매출 --></th>
	      <td><?=nf($rs[sum_fee]*($target_rate/100))?><!-- 요율 A --></td>
	      <td><!-- 빈칸 --></td>
	      <td><!-- 빈칸 --></td>
	      <td><?=$rs2[etc1]?><!-- 비고 --></td>
	    </tr>

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" >비용</th>
		<th class="subject" >단가</th>
		<th class="subject" >개수</th>
		<th class="subject" >금액</th>
		<th class="subject" >비고</th>
		</tr>

	    <tr align='center'>
	      <td>골프공<!-- 비용 --></td>
	      <td><?=nf($danga_g)?><!-- 단가 --></td>
	      <td><?=nf($rs[ball])?><!-- 개수 --></td>
	      <td><?=nf($danga_g*$rs[ball])?><!-- 금액 --></td>
	      <td><?=$rs2[etc2]?><!-- 비고 --></td>
	    </tr>
	    <?
	    $sum1+=$danga_g;
	    $sum2+=$rs[ball];
	    $sum3+=$danga_g*$rs[ball];
	    ?>

	    <tr align='center'>
	      <td>골프공(고급)<!-- 비용 --></td>
	      <td><?=nf($danga_g2)?><!-- 단가 --></td>
	      <td><?=nf($rs[ball2])?><!-- 개수 --></td>
	      <td><?=nf($danga_g2*$rs[ball2])?><!-- 금액 --></td>
	      <td><?=$rs2[etc6]?><!-- 비고 --></td>
	    </tr>
	    <?
	    $sum1+=$danga_g2;
	    $sum2+=$rs[ball2];
	    $sum3+=$danga_g2*$rs[ball2];
	    ?>

	    <tr align='center'>
	      <td>항공커버<!-- 비용 --></td>
	      <td><?=nf($danga_c)?><!-- 단가 --></td>
	      <td><?=nf($rs[cover])?><!-- 개수 --></td>
	      <td><?=nf($danga_c*$rs[cover])?><!-- 금액 --></td>
	      <td><?=$rs2[etc3]?><!-- 비고 --></td>
	    </tr>
	    <?
	    $sum1+=$danga_c;
	    $sum2+=$rs[cover];
	    $sum3+=$danga_c*$rs[cover];
	    ?>

	    <tr align='center'>
	      <td>여행자보험<!-- 비용 --></td>
	      <td><?=nf($danga_i)?><!-- 단가 --></td>
	      <td><?=nf($rs[ins])?><!-- 개수 --></td>
	      <td><?=nf($danga_i*$rs[ins])?><!-- 금액 --></td>
	      <td><?=$rs2[etc4]?><!-- 비고 --></td>
	    </tr>
	    <?
	    $sum1+=$danga_i;
	    $sum2+=$rs[ins];
	    $sum3+=$danga_i*$rs[ins];
	    ?>

	    <tr align='center'>
	      <td>기타<!-- 비용 --></td>
	      <td><?=nf($rs2[add1])?><!-- 단가 --></td>
	      <td><?=nf($rs2[add2])?><!-- 개수 --></td>
	      <td><?=nf($rs2[add1]*$rs2[add2])?><!-- 금액 --></td>
	      <td><?=$rs2[etc5]?><!-- 비고 --></td>
	    </tr>
	    <?
	    $sum1+=$rs2[add1];
	    $sum2+=$rs2[add2];
	    $sum3+=$rs2[add1]*$rs2[add2];
	    ?>

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" >비용소계</th>
		<th class="subject" ><?//=nf($sum1)?><!-- 비용소개값 --></th>
		<th class="subject" ><?//=nf($sum2)?><!-- 빈칸 --></th>
		<th class="subject"  style="text-align:right"><?=nf($sum3)?><!-- 합계 B--></th>
		<th class="subject" ><!-- 빈칸 --></th>
		</tr>

	    <tr align='center'>
	      <th>정산액</th>
	      <th style="text-align:right"><?=nf(($rs[sum_fee]/2)-$sum3)?><!-- 정산액 A-B --></th>
	      <td><!-- 빈칸 --></td>
	      <td><!-- 빈칸 --></td>
	      <td><!-- 빈 --></td>
	    </tr>
	</table>



</div>

</body>
</html>
