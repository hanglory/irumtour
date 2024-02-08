<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");
chk_power($_SESSION["sessLogin"]["proof"],"엑셀다운로드");

$dtype=($dtype)? $dtype : "d_date";


$date_s = ($date_s)? $date_s : $PAPER_DEFAULT_DAY1;
$date_e = ($date_e)? $date_e : $PAPER_DEFAULT_DAY2;
$year = ($year)? $year : date("Y");


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "연도별 매출&비용";
$TITLE .=($dtype=="d_date")? "(출국일자 기준)" : "(예약일자 기준)";


if(!strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
$xls_name = "expense2_" . date("Ymd") . ".xls";
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

<?
for($y=0; $y<3;$y++){

	$sum1=0;
	$sum2=0;
	$sum3=0;
?>
	<?
	if($year){
		$YEAR_PREV = date("Y/m/d",mktime(0,0,0,1,1,$year));
		$YEAR_THIS = date("Y/m/d",mktime(0,0,0,1,1,$year+1)-1);
	}

	$sql = "
		select
			left($dtype,7) as did,
			sum(people) as sum_people,
			sum(price_prev - (select sum(price_air + price_land+price_refund) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
			from cmp_reservation as a
			where
				$dtype >= '$YEAR_PREV'
				and $dtype <='$YEAR_THIS'
			group by left($dtype,7)

	";

	$dbo->query($sql);
	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27")checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){

		$sql2 = "
			select 
				sum((c.price_prev+c.price_prev2+c.price_prev3)-(c.price_air+c.price_land+c.price_refund)) as sum_fee
			from cmp_people as c left join cmp_reservation as d
			on c.code=d.code
			where c.bit=1
				and left(d.$dtype,7) = '$rs[did]'
		";
		$dbo2->query($sql2);
		$rs2=$dbo2->next_record();
		$rs[sum_fee] = $rs2[sum_fee];

		$did = $rs[did];
		$DATA[$did]["people"] = $rs[sum_people];
		$DATA[$did]["fee"] = $rs[sum_fee];
	}

	?>

		<tr>
			<th class="subject" colspan="5"><?=$year?>년</th>
		</tr>
		<tr>
			<th class="subject" >구분</th>
			<th class="subject" >인원</th>
			<th class="subject" >매출</th>
			<th class="subject" >비용</th>
			<th class="subject" >순이익</th>
		</tr>
		<?
		$ctg="";
		$sql = "select * from ez_category1 where bit_out<>1 order by seq";
		$dbo->query($sql);
		while($rs=$dbo->next_record()){

			$sql2 = "select * from ez_category2 where category1='$rs[category1]' order by seq";
			list($rows) = $dbo2->query($sql2);

			while($rs2=$dbo2->next_record()){
				$ctg.=",'$rs[id_no]_$rs2[id_no]'";
			}

		}
		$ctg = substr($ctg,1);


		$ctg2="";
		$sql = "select * from ez_category1 where bit_out=1 order by seq";
		$dbo->query($sql);
		while($rs=$dbo->next_record()){

			$sql2 = "select * from ez_category2 where category1='$rs[category1]' order by seq";
			list($rows) = $dbo2->query($sql2);

			while($rs2=$dbo2->next_record()){
				$ctg2.=",'$rs[id_no]_$rs2[id_no]'";
			}

		}
		$ctg2 = substr($ctg2,1);



		for($i=1;$i<=12;$i++){
			$did = $year . "/" . num2($i);
			$month = rnf($i);
			$sql = "
				select 
					(
						select
							sum(price)
						from cmp_expense
						where year=$year and month=$month
						and concat(category1,'_',category2) in ($ctg)
					) as price1,
					(
						select
							sum(price)
						from cmp_expense
						where year=$year and month=$month
						and concat(category1,'_',category2) in ($ctg2)
					) as price2
			";
			$dbo->query($sql);
			$rs=$dbo->next_record();		
			$rs[price] = $rs[price1]-$rs[price2];
			$sum1 += $DATA[$did]["people"];
			$sum2 += $DATA[$did]["fee"];
			$sum3 += $rs["price"];

		?>
		<tr>
			<td class="c" style="background-color:#f0f0f0"><?=num2($i)?>월</td>
			<td class="r"><?=nf($DATA[$did]["people"])?></td>
			<td class="r"><?=nf($DATA[$did]["fee"])?></td>
			<td class="r"><?=nf($rs["price"])?></td>
			<td class="r"><?=nf($DATA[$did]["fee"]-$rs["price"])?></td>
		</tr>
		<?}?>
		<tr>
			<td class="subject c">누계</td>
			<td class="subject r" ><?=nf($sum1)?></td>
			<td class="subject r" ><?=nf($sum2)?></td>
			<td class="subject r" ><?=nf($sum3)?></td>
			<td class="subject r" ><?=nf($sum2-$sum3)?></td>
		</tr>

		<?if($y<2){?>
		<tr>
			<td colspan="5">&nbsp;</td>
		</tr>
		<?}?>
<?
	$year -=1;
}
?>
	</table>




</div>
</body>
</html>