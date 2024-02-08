<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"보고서");


$dtype=($dtype)? $dtype : "d_date";


$date_s = ($date_s)? $date_s : $PAPER_DEFAULT_DAY1;
$date_e = ($date_e)? $date_e : $PAPER_DEFAULT_DAY2;
$year = ($year)? $year : date("Y");



if($REMOTE_ADDR!="106.246.54.27"){
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
	if($year){
		$YEAR_PREV = date("Y/m/d",mktime(0,0,0,1,1,$year-1));
		$YEAR_THIS = date("Y/m/d",mktime(0,0,0,1,1,$year+1)-1);
	}


	$sql = "
		select
			left($dtype,7) as did,
			sum(people) as sum_people,
			sum(fee) as sum_fee
			from cmp_reservation
			where
				$dtype >= '$YEAR_PREV'
				and $dtype <='$YEAR_THIS'
			group by left($dtype,7)

	";
	$dbo->query($sql);
	//checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){
		$did = $rs[did];
		$DATA[$did]["people"] = $rs[sum_people];
		$DATA[$did]["fee"] = $rs[sum_fee];
	}

	?>

    <table border="0" cellspacing="0" cellpadding="3" width="3500" id="tbl_cmp_list" style="border-collapse:collapse;border:1px solid #000 ">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >구분</th>
		<th class="subject" >구분</th>
		<?
		for($i=1;$i<=12;$i++){
		?>
		<th class="subject" ><?=$i?>월</th>
		<?}?>
		<th class="subject" >합계</th>
		<th class="subject" >객단가</th>
		</tr>

		<!-- 인원 -->
		<tr align='center'>
	      <td height="35" rowspan="3" class="c" style="background-color:#f0f0f0">인원</td>
		  <td class="c">금년</td>
		<?
		$sum_this1=0;
		$sum_this2=0;
		for($i=1;$i<=12;$i++){
			$did = substr($YEAR_THIS,0,4) . "/" . num2($i);
			$sum_this1+=$DATA[$did]["people"];
			$sum_this2+=$DATA[$did]["fee"];
		?>
		  <td><?=nf($DATA[$did]["people"])?></td>
		<?}?>
	      <td><?=nf($sum_this1)?></td>
	      <td><?=nf($sum_this2/$sum_this1)?>원</td>
	    </tr>

		<tr align='center'>
	      <td class="c">전년</td>
		<?
		$sum_prev1=0;
		$sum_prev2=0;
		for($i=1;$i<=12;$i++){
			$did = substr($YEAR_PREV,0,4) . "/" . num2($i);
			$sum_prev1+=$DATA[$did]["people"];
			$sum_prev2+=$DATA[$did]["fee"];
		?>
		  <td><?=nf($DATA[$did]["people"])?></td>
		<?}?>
	      <td><?=@nf($sum_prev1)?></td>
	      <td><?=@nf($sum_prev2/$sum_prev1)?>원</td>
	    </tr>

		<tr align='center' style="background-color:#f0f0f0">
	      <td class="c">증가율</td>
		<?
		$sum=0;
		for($i=1;$i<=12;$i++){
			$did_prev = substr($YEAR_PREV,0,4) . "/" . num2($i);
			$did_this = substr($YEAR_THIS,0,4) . "/" . num2($i);

			$x = @(($DATA[$did_this]["people"]-$DATA[$did_prev]["people"])/$DATA[$did_prev]["people"])*100;
			$x = @round($x,2);
		?>
		  <td><?=get_m_color($x)?>%</td>
		<?}?>
	      <td><?=@get_m_color(round((($sum_this1-$sum_prev1)/$sum_prev1)*100,2))?>%</td>
	      <td><?=@get_m_color(round(((($sum_this2/$sum_this1)-($sum_prev2/$sum_prev1))/($sum_prev2/$sum_prev1))*100,2))?>%</td>
	    </tr>


		<!-- 매출 -->
		<tr align='center'>
	      <td height="35" rowspan="3" class="c" style="background-color:#f0f0f0">매출</td>
		  <td class="c">금년</td>
		<?
		$sum_this1=0;
		$sum_this2=0;
		for($i=1;$i<=12;$i++){
			$did = substr($YEAR_THIS,0,4) . "/" . num2($i);
			$sum_this1+=$DATA[$did]["people"];
			$sum_this2+=$DATA[$did]["fee"];
		?>
		  <td class="r"><?=nf($DATA[$did]["fee"]/$TEN)?></td>
		<?}?>
	      <td><?=@nf($sum_this2/$TEN)?></td>
	      <td><?=@nf($sum_this2/$sum_this1)?>원</td>
	    </tr>

		<tr align='center'>
	      <td class="c">전년</td>
		<?
		$sum_prev1=0;
		$sum_prev2=0;
		for($i=1;$i<=12;$i++){
			$did = substr($YEAR_PREV,0,4) . "/" . num2($i);
			$sum_prev1+=$DATA[$did]["people"];
			$sum_prev2+=$DATA[$did]["fee"];
		?>
		  <td class="r"><?=nf($DATA[$did]["fee"]/$TEN)?></td>
		<?}?>
	      <td><?=@nf($sum_prev2/$TEN)?></td>
	      <td><?=@nf($sum_prev2/$sum_prev1)?>원</td>
	    </tr>

		<tr align='center' style="background-color:#f0f0f0">
	      <td class="c">증가율</td>
		<?
		$sum=0;
		for($i=1;$i<=12;$i++){
			$did_prev = substr($YEAR_PREV,0,4) . "/" . num2($i);
			$did_this = substr($YEAR_THIS,0,4) . "/" . num2($i);

			$x = @(($DATA[$did_this]["fee"]-$DATA[$did_prev]["fee"])/$DATA[$did_prev]["fee"])*100;
			$x = @round($x,2);
		?>
		  <td><?=get_m_color($x)?>%</td>
		<?}?>
	      <td><?=@get_m_color(round((($sum_this2-$sum_prev2)/$sum_prev2)*100,2))?>%</td>
	      <td><?=@get_m_color(round(((($sum_this2/$sum_this1)-($sum_prev2/$sum_prev1))/($sum_prev2/$sum_prev1))*100,2))?>%</td>
	    </tr>


	</table>



</div>

</body>
</html>
