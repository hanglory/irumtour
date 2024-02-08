<?
include_once("../include/common_file.php");

//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar("proof",$_SESSION["sessLogin"]["proof"]);exit;}
chk_power($_SESSION["sessLogin"]["proof"],"경영관리");

$dtype=($dtype)? $dtype : "d_date";



$date_s = ($date_s)? $date_s : $PAPER_DEFAULT_DAY1;
$date_e = ($date_e)? $date_e : $PAPER_DEFAULT_DAY2;
$year = ($year)? $year : date("Y");


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "기간별 매출현황";
$TITLE .=($dtype=="d_date")? "(출국일자 기준)" : "(예약일자 기준)";
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


	$sql = "
		select
			left($dtype,7) as did,
			sum(people) as sum_people,
			sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
			from cmp_reservation as a
			where
				$dtype >= '$YEAR_PREV'
				and $dtype <='$YEAR_THIS'
                $FILTER_PARTNER_QUERY_CPID
			group by left($dtype,7)
	";
	$dbo->query($sql);
	if($debug) checkVar(mysql_error(),$sql);


	while($rs=$dbo->next_record()){
		$did = $rs[did];
		$DATA[$did]["people"] = $rs[sum_people];
		$DATA[$did]["fee"] = $rs[sum_fee];
	}

	?>

    (단위 : 천원)
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

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
		  <td><?=nf($DATA[$did]["people"])?></td>
		<?}?>
	      <td><?=@nf($sum_prev1)?></td>
	      <td><?=@nf($sum_prev2/$sum_prev1)?>원<!-- 객단가 : 수수료/인원 --></td>
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


		<!-- 인원 누계-->
		<tr align='center'>
	      <td height="35" rowspan="3" class="c" style="background-color:#f0f0f0">인원(누계)</td>
		  <td class="c">금년</td>
		<?
		$sum_this1=0;
		$sum_this2=0;
		unset($SUM1);
		unset($SUM2);

		for($i=1;$i<=12;$i++){
			$did = substr($YEAR_THIS,0,4) . "/" . num2($i);
			$sum_this1+=$DATA[$did]["people"];
			$sum_this2+=$DATA[$did]["fee"];

			$SUM1[$i] = $sum_this1;

		?>
		  <td><?=nf($sum_this1)?></td>
		<?}?>
	      <td></td>
	      <td></td>
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

			$SUM2[$i] = $sum_prev1;
		?>
		  <td><?=nf($sum_prev1)?></td>
		<?}?>
	      <td></td>
	      <td></td>
	    </tr>

		<tr align='center' style="background-color:#f0f0f0">
	      <td class="c">증가율</td>
		<?
		$sum=0;
		for($i=1;$i<=12;$i++){
			$x = @($SUM1[$i] / $SUM2[$i] -1)*100;
			$x = @round($x,2);
		?>
		  <td><?=get_m_color($x)?>%</td>
		<?}?>
	      <td></td>
	      <td></td>
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



		<!-- 매출 누계-->
		<tr align='center'>
	      <td height="35" rowspan="3" class="c" style="background-color:#f0f0f0">매출(누계)</td>
		  <td class="c">금년</td>
		<?
		$sum_this1=0;
		$sum_this2=0;
		unset($SUM1);
		unset($SUM2);
		for($i=1;$i<=12;$i++){
			$did = substr($YEAR_THIS,0,4) . "/" . num2($i);
			$sum_this1+=$DATA[$did]["people"];
			$sum_this2+=$DATA[$did]["fee"];
			$SUM1[$i] = $sum_this2;
		?>
		  <td class="r"><?=nf($sum_this2/$TEN)?></td>
		<?}?>
	      <td></td>
	      <td></td>
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
			$SUM2[$i] = $sum_prev2;
		?>
		  <td class="r"><?=nf($sum_prev2/$TEN)?></td>
		<?}?>
	      <td></td>
	      <td></td>
	    </tr>

		<tr align='center' style="background-color:#f0f0f0">
	      <td class="c">증가율</td>
		<?
		$sum=0;
		for($i=1;$i<=12;$i++){
			$x = @($SUM1[$i] / $SUM2[$i] -1)*100;
			$x = @round($x,2);
		?>
		  <td><?=get_m_color($x)?>%</td>
		<?}?>
	      <td></td>
	      <td></td>
	    </tr>




		<!-- 객단가 -->
		<tr align='center'>
	      <td height="35" rowspan="3" class="c" style="background-color:#f0f0f0">객단가</td>
		  <td class="c">금년</td>
		<?
		$sum_this1=0;
		$sum_this2=0;
		for($i=1;$i<=12;$i++){
			$did = substr($YEAR_THIS,0,4) . "/" . num2($i);
			/*
			$sum_this1+=$DATA[$did]["people"];
			$sum_this2+=$DATA[$did]["fee"];
			*/
			$num =@round(($DATA[$did]["fee"]/$DATA[$did]["people"])/$TEN);
			$sum_this2+=$num;
		?>
		  <td class="r"><?=$num?></td>
		<?}?>
	      <td><?=@nf($sum_this2)?></td>
	      <td><?//=@nf($sum_this2/$sum_this1)?></td>
	    </tr>

		<tr align='center'>
	      <td class="c">전년</td>
		<?
		$sum_prev1=0;
		$sum_prev2=0;
		for($i=1;$i<=12;$i++){
			$did = substr($YEAR_PREV,0,4) . "/" . num2($i);

			/*
			$sum_prev1+=$DATA[$did]["people"];
			$sum_prev2+=$DATA[$did]["fee"];
			*/

			$num =@round(($DATA[$did]["fee"]/$DATA[$did]["people"])/$TEN);
			$sum_prev2+=$num;
		?>
		  <td class="r"><?=$num?></td>
		<?}?>
	      <td><?=@nf($sum_prev2)?></td>
	      <td><?//=@nf($sum_prev2/$sum_prev1)?></td>
	    </tr>

		<tr align='center' style="background-color:#f0f0f0">
	      <td class="c">증가율</td>
		<?
		$sum=0;
		for($i=1;$i<=12;$i++){
			$did_prev = substr($YEAR_PREV,0,4) . "/" . num2($i);
			$did_this = substr($YEAR_THIS,0,4) . "/" . num2($i);

			$x = @((($DATA[$did_this]["fee"]/$DATA[$did_this]["people"]) - ($DATA[$did_prev]["fee"]/$DATA[$did_prev]["people"]) ) / ($DATA[$did_prev]["fee"]/$DATA[$did_prev]["people"]) )*100;
			$x = @round($x,2);
		?>
		  <td><?=get_m_color($x)?>%</td>
		<?}?>
	      <td><?=@get_m_color(round((($sum_this2-$sum_prev2)/$sum_prev2)*100,2))?>%</td>
	      <td><?//=@get_m_color(round(((($sum_this2/$sum_this1)-($sum_prev2/$sum_prev1))/($sum_prev2/$sum_prev1))*100,2))?></td>
	    </tr>

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
				<span class="btn_pack medium bold"><a href="list_<?=$filecode?>_excel.php?year=<?=$year?>&date_s=<?=$date_s?>&date_e=<?=$date_e?>&dtype=<?=$dtype?>"> 엑셀 </a></span>
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
