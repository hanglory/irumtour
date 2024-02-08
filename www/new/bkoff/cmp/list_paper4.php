<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");

$dtype=($dtype)? $dtype : "tour_date";	   //기본 예약일 (d_date:출국일)


$date_s = ($date_s)? $date_s : $PAPER_DEFAULT_DAY1;
$date_e = ($date_e)? $date_e : $PAPER_DEFAULT_DAY2;
$year = ($year)? $year : date("Y");


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "거래처 현황";
$TITLE .=($ptype=="1")? "(인원)" : "(매출)";

?>
<?include("../top.html");?>
<style type="text/css">
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:right;padding-right:2px;}
</style>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?>

		</td>
	  </tr>
	  <tr>
		<td> </td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	</table>

	<br/>


	<!--내용이 들어가는 곳 시작-->

	<!-- Search Begin------------------------------------------------>
	<div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
	<form name=fmSearch method="get">
	<input type=hidden name='position' value="">
	<input type=hidden name='d_type' value="<?=$d_type?>"
	<input type=hidden name='p_type' value="<?=$p_type?>"


	<tr height=22>
	<td valign='bottom' align=right>

	<!--
	<input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?=$date_s?>" class="box c dateinput">
	~
	<input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?=$date_e?>" class="box c dateinput">
	-->

	<select name="dtype">
		<?=option_str("예약일기준,출국일기준","tour_date,d_date",$dtype)?>
	</select>

	기준년도 : <input type="text" name="year" id="year" size="13" maxlength="10" value="<?=$year?>" class="box c">

    거래처 : <input type="text" name="keyword" value="<?=$keyword?>" class="box" maxlength="20">
	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>


	<?
	if($year){
		$YEAR_PREV = date("Y/m/d",mktime(0,0,0,1,1,$year-1));
		$YEAR_THIS = date("Y/m/d",mktime(0,0,0,1,1,$year+1)-1);
	}

	if($keyword){$keyword=trim($keyword); $filter = " and b.partner like '%$keyword%'";}

	$arr="";
	$sql = "
		select
			b.partner as did,
			left(a.$dtype,7) as did2,
			sum(a.people) as sum_people,
			sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
			from cmp_reservation as a left join cmp_golf as b
			on a.golf_id_no=b.id_no
			where
				a.$dtype >= '$YEAR_PREV'
				and a.$dtype <='$YEAR_THIS'
				$filter
			group by b.partner,left(a.$dtype,7)

	";


	$dbo->query($sql);
	//if($easeplus) checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){
		$did = $rs[did];
		$did2 = $rs[did2];
		$DATA[$did][$did2]["people"] = $rs[sum_people];
		$DATA[$did][$did2]["fee"] = $rs[sum_fee];
		$arr[] = $rs[did];
	}
	$arr = array_unique($arr);
	sort($arr);
	?>

    (단위 : 천원)
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" width="10%">거래처</th>
		<th class="subject" colspan="2">구분</th>
		<?
		for($i=1;$i<=12;$i++){
		?>
		<th class="subject" ><?=$i?>월</th>
		<?}?>
		<th class="subject" >합계</th>
		<th class="subject" >객단가</th>
		</tr>

		<!-- 인원 -->
		<?
		while(list($key,$val)=each($arr)){
		if($val){
		?>
			<tr align='center'>
			  <td height="35" rowspan="6" class="c" style="background-color:#f0f0f0"><?=$val?></td>
			  <td height="35" rowspan="3" class="c" style="background-color:#f0f0f0">인원</td>
			  <td class="c" style="background-color:#f0f0f0">금년</td>
			<?
			$sum_this1=0;
			$sum_this2=0;
			for($i=1;$i<=12;$i++){
				$did = $val;
				$did2 = substr($YEAR_THIS,0,4) . "/" . num2($i);
				$sum_this1+=$DATA[$did][$did2]["people"];
				$sum_this2+=$DATA[$did][$did2]["fee"];
			?>
			  <td><?=nf($DATA[$did][$did2]["people"])?></td>
			<?}?>
			  <td><?=nf($sum_this1)?></td>
			  <td><?=@nf($sum_this2/$sum_this1)?>원</td>
			</tr>

			<tr align='center'>
			  <td class="c" style="background-color:#f0f0f0">전년</td>
			<?
			$sum_prev1=0;
			$sum_prev2=0;
			for($i=1;$i<=12;$i++){
				$did = $val;
				$did2 = substr($YEAR_PREV,0,4) . "/" . num2($i);
				$sum_prev1+=$DATA[$did][$did2]["people"];
				$sum_prev2+=$DATA[$did][$did2]["fee"];
			?>
			  <td><?=nf($DATA[$did][$did2]["people"])?></td>
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

				$x = @(($DATA[$did][$did_this]["people"]-$DATA[$did][$did_prev]["people"])/$DATA[$did][$did_prev]["people"])*100;
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
			  <td class="c" style="background-color:#f0f0f0">금년</td>
			<?
			$sum_this1=0;
			$sum_this2=0;
			for($i=1;$i<=12;$i++){
				$did = $val;
				$did2 = substr($YEAR_THIS,0,4) . "/" . num2($i);
				$sum_this1+=$DATA[$did][$did2]["people"];
				$sum_this2+=$DATA[$did][$did2]["fee"];
			?>
			  <td class="r"><?=nf($DATA[$did][$did2]["fee"]/$TEN)?></td>
			<?}?>
			  <td><?=@nf($sum_this2/$TEN)?></td>
			  <td><?=@nf($sum_this2/$sum_this1)?>원</td>
			</tr>

			<tr align='center'>
			  <td class="c" style="background-color:#f0f0f0">전년</td>
			<?
			$sum_prev1=0;
			$sum_prev2=0;
			for($i=1;$i<=12;$i++){
				$did = $val;
				$did2 = substr($YEAR_PREV,0,4) . "/" . num2($i);
				$sum_prev1+=$DATA[$did][$did2]["people"];
				$sum_prev2+=$DATA[$did][$did2]["fee"];
			?>
			  <td class="r"><?=nf($DATA[$did][$did2]["fee"]/$TEN)?></td>
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

				$x = @(($DATA[$did][$did_this]["fee"]-$DATA[$did][$did_prev]["fee"])/$DATA[$did][$did_prev]["fee"])*100;
				$x = @round($x,2);
			?>
			  <td><?=get_m_color($x)?>%</td>
			<?}?>
			  <td><?=@get_m_color(round((($sum_this2-$sum_prev2)/$sum_prev2)*100,2))?>%</td>
			  <td><?=@get_m_color(round(((($sum_this2/$sum_this1)-($sum_prev2/$sum_prev1))/($sum_prev2/$sum_prev1))*100,2))?>%</td>
			</tr>
	<?}}?>

	</table>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
        <tr>
		  <td colspan="12">

		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
			 <tr>
			  <td width="60%" align="left">

			  </td>
			  <td align="right">
				<span class="btn_pack medium bold"><a href="list_<?=$filecode?>_excel.php?year=<?=$year?>&date_s=<?=$date_s?>&date_e=<?=$date_e?>&dtype=<?=$dtype?>&ptype=<?=$ptype?>&keyword=<?=$keyword?>"> 엑셀 </a></span>
			  </td>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>

		  </td>
        </tr>


	</table>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
