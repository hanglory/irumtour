<?
include_once("../include/common_file.php");


if($partner==1){
	chk_power($_SESSION["sessLogin"]["proof"],"파트너현황");

	$user_id = $_SESSION["sessLogin"]["id"];
	$partner_filter = " and main_staff like '%(${user_id})'";
	$MENU = "cmp_partner";
}
elseif($partner==2){
	chk_power($_SESSION["sessLogin"]["proof"],"통계");
	$sql = "select * from cmp_staff where staff_type in ('staff','leader') ";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
		$partners .="or main_staff like '%($rs[id])%' ";
	}
	$partners = substr($partners,3);
	$partner_filter = " and ($partners)";
	$MENU = "cmp_paper2";
}
else{
	chk_power($_SESSION["sessLogin"]["proof"],"경영관리");
	$MENU = "cmp_paper";
}
//if($staff_partner){error("권한이 없습니다.");exit;}

if($_SESSION["sessLogin"]["staff_type"] == "ceo" && $partner){
	$sql = "select * from cmp_staff where staff_type in ('partner_a','partner_i','partner_g')";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
		$partners .="or main_staff like '%($rs[id])%' ";
	}
	$partners = substr($partners,3);
	$partner_filter = " and ($partners)";
}


$dtype=($dtype)? $dtype : "tour_date";
$year =($year)? $year : date("Y");

/*
if($REMOTE_ADDR=="106.246.54.27"){
	$sql = "update cmp_reservation set view_path='기타' where view_path not in ('신규','재방문','추천','기타')";
	//$sql = "select * from  cmp_reservation where view_path not in ('신규','재방문','추천','기타')";
	list($rows) = $dbo->query($sql);
	checkVar($rows.mysql_error(),$sql);
}
*/


$date_s = ($date_s)? $date_s : "${year}/01/01";
$date_e = ($date_e)? $date_e : "${year}/12/31";

$year_this = substr($date_s,0,4);
$year_prev = substr($date_s,0,4)-1;
$date_s2 = $year_prev . substr($date_s,4);
$date_e2 = $year_prev . substr($date_e,4);


if(substr($date_s,0,4)!=substr($date_e,0,4)){
	error("검색하시는 시작일의 년도와 종료일의 연도가 같아야 합니다.");
	exit;
}


$sql = "select * from cmp_staff where bit_hide<>1";
list($rows)=$dbo->query($sql);
//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar($rows.mysql_error(),$sql);}
while($rs=$dbo->next_record()){
	$col = "$rs[name] ($rs[id])";

	$goal = ($dtype=="d_date")? $rs[goal2] : $rs[goal];
	$arr = explode(",",$goal);
	while(list($key,$val)=each($arr)){
		$key2=$key+1;
		$STAFF[$col][$key2]=$val;
	}
}


####기초 정보
$filecode = substr(SELF,5,-4);
$TITLE = "목표 및 달성율";
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
	<input type=hidden name='partner' value="<?=$partner?>">


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
			main_staff as did,
			left($dtype,7) as did2,
			sum(people) as sum_people,
			sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
			from cmp_reservation as a
			where
				$dtype >= '$YEAR_PREV'
				and $dtype <='$YEAR_THIS'
				and main_staff<>''
				$partner_filter
			group by main_staff,left($dtype,7)
	";
	$dbo->query($sql);

	unset($arr);
	//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){
		$did = $rs[did];
		$did2 = $rs[did2];
		$DATA[$did][$did2]["people"] = $rs[sum_people];
		$DATA[$did][$did2]["fee"] = $rs[sum_fee];
		$arr[] = $rs[did];

	}
	$arr = @array_unique($arr);
	?>

    (단위 : 천원)
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" >구분</th>
		<th class="subject" >구분</th>
		<?
		for($i=1;$i<=12;$i++){
		?>
		<th class="subject" ><?=$i?>월</th>
		<?}?>
		<th class="subject" >합계</th>
		</tr>




		<?
		@sort($arr);
		while(list($key,$val)=@each($arr)){
			$arr2 = explode("(",$val);
			$did = $val;
			$did2 = $year_this;
		?>



		    <!-- 목표 -->
			<tr align='center'>
		      <td height="35" rowspan="10" class="c" style="background-color:#f0f0f0"><?=(trim($arr2[0]))?trim($arr2[0]):"기타"?></td>
		      <td class="c" style="background-color:#f0f0f0">목표</td>
			<?
			$sum_prev1=0;
			$sum_prev2=0;
			for($i=1;$i<=12;$i++){
				$sum_prev1+=$STAFF[$did][$i];
			?>
			  <td class="r" style="background-color:#f0f0f0"><?=nf($STAFF[$did][$i]/$TEN)?></td>
			<?}?>
		      <td style="background-color:#f0f0f0"><?=@nf($sum_prev1/$TEN)?></td>
		    </tr>


		    <!-- 목표누계 -->
			<tr align='center'>
		      <td class="c" style="background-color:#f0f0f0">목표누계</td>
			<?
			$ssum_prev=0;
			unset($ssum_1);
			for($i=1;$i<=12;$i++){
				$ssum_prev+=$STAFF[$did][$i];
				$ssum_1[$i]=$ssum_prev;
			?>
			  <td class="r" style="background-color:#f0f0f0"><?=nf($ssum_prev/$TEN)?></td>
			<?}?>
		      <td style="background-color:#f0f0f0"><?=nf($ssum_prev/$TEN)?></td>
		    </tr>


			<!-- 금년실적 -->
			<tr align='center'>
			  <td class="c">금년실적</td>
			<?
			$sum_this1=0;
			$sum_this2=0;
			for($i=1;$i<=12;$i++){
				$did2 = substr($YEAR_THIS,0,4) . "/" . num2($i);
				$sum_this1+=$DATA[$did][$did2]["people"];
				$sum_this2+=$DATA[$did][$did2]["fee"];
			?>
			  <td class="r"><?=nf($DATA[$did][$did2]["fee"]/$TEN)?></td>
			<?}?>
		      <td><?=@nf($sum_this2/$TEN)?></td>
		    </tr>

			<!-- 금년실적누계 -->
			<tr align='center'>
			  <td class="c" style="background-color:#f0f0f0">금년실적누계</td>
			<?
			$sum_line1=0;
			$sum_line2=0;
			$sum_line1_=0;
			$sum_line2_=0;
			unset($ssum_2);
			for($i=1;$i<=12;$i++){
				$did2 = substr($YEAR_THIS,0,4) . "/" . num2($i);
				$sum_line1+=$DATA[$did][$did2]["people"];
				if($DATA[$did][$did2]["fee"]>0){
					$sum_line2+=rnf(nf($DATA[$did][$did2]["fee"]/$TEN));
					$sum_line2_+=rnf(nf($DATA[$did][$did2]["fee"]));
				}
				else{
					$sum_line2-=rnf(nf($DATA[$did][$did2]["fee"]/$TEN));
					$sum_line2_-=rnf(nf($DATA[$did][$did2]["fee"]));
				}
				$total_month[$i]=$sum_line2;

				$ssum_2[$i]=$sum_line2_;
			?>
			  <td class="r" style="background-color:#f0f0f0"><?=nf($sum_line2_/$TEN)?></td>
			<?}?>
		      <td style="background-color:#f0f0f0"><?=@nf($sum_line2_/$TEN)?></td>
		    </tr>


		    <!-- 달성율 -->
			<tr align='center'>
		      <td class="c" style="background-color:#f0f0f0">달성율</td>
			<?
			$sum_prev1=0;
			$sum_prev2=0;
			for($i=1;$i<=12;$i++){
				$did2 = substr($YEAR_THIS,0,4) . "/" . num2($i);
				$result1=($STAFF[$did][$i])? $STAFF[$did][$i] : 0;
				$result2=($DATA[$did][$did2]["fee"])?$DATA[$did][$did2]["fee"]:0;
				$result = @round(($result2/$result1)*100,2);

				$sum_prev1+=$result1;
				$sum_prev2+=$result2;

			?>
			  <td class="r" style="background-color:#f0f0f0"><?=($result2)? $result:0?>%</td>
			<?}?>

			  <?
			  $result_all = @round(($sum_prev2/$sum_prev1)*100,2);
			  ?>
		      <td style="background-color:#f0f0f0"><?=$result_all?>%</td>
		    </tr>



		    <!-- 누계 달성율 -->
			<tr align='center'>
		      <td class="c" style="background-color:#f0f0f0">누계 달성율</td>
			<?
			$sum_c6_c4=0;
			for($i=1;$i<=12;$i++){
				$did2 = substr($YEAR_THIS,0,4) . "/" . num2($i);
				$sum_c6_c4 = @round((($ssum_2[$i]/$ssum_1[$i])*100),2);

			?>
			  <td class="r" style="background-color:#f0f0f0"><?=$sum_c6_c4?>%</td>
			<?}?>
		      <td style="background-color:#f0f0f0"><?=$sum_c6_c4?>%</td>
		    </tr>



		    <!-- 전년실적 -->
			<tr align='center'>
		      <td class="c">전년실적</td>
			<?
			$sum_prev1=0;
			$sum_prev2=0;
			for($i=1;$i<=12;$i++){
				$did2 = substr($YEAR_PREV,0,4) . "/" . num2($i);
				$sum_prev1+=$DATA[$did][$did2]["people"];
				$sum_prev2+=$DATA[$did][$did2]["fee"];
			?>
			  <td class="r"><?=nf($DATA[$did][$did2]["fee"]/$TEN)?></td>
			<?}?>
		      <td><?=@nf($sum_prev2/$TEN)?></td>
		    </tr>

		    <!-- 증가율 -->
			<tr align='center'>
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
		    </tr>


			<!-- 전년실적누계 -->
			<tr align='center'>
			  <td class="c" style="background-color:#f0f0f0">전년실적누계</td>
			<?
			$sum_2line1=0;
			$sum_2line2=0;
			for($i=1;$i<=12;$i++){
				$did2 = substr($YEAR_PREV,0,4) . "/" . num2($i);
				$sum_2line1+=$DATA[$did][$did2]["people"];
				if($DATA[$did][$did2]["fee"]>0) $sum_2line2+=rnf(nf($DATA[$did][$did2]["fee"]/$TEN));
				else $sum_2line2-=rnf(nf($DATA[$did][$did2]["fee"]/$TEN));
				$total_2month[$i]=$sum_2line2;
			?>
			  <td class="r" style="background-color:#f0f0f0"><?=nf($sum_2line2)?></td>
			<?}?>
		      <td style="background-color:#f0f0f0"><?=@nf($sum_2line2)?></td>
		    </tr>


		    <!-- 누계 증가율 -->
			<tr align='center'>
		      <td class="c" style="background-color:#f0f0f0">누계증가율</td>
			<?
			$sum=0;
			for($i=1;$i<=12;$i++){
				$x = @(($total_month[$i]-$total_2month[$i])/$total_2month[$i])*100;
				$x = @round($x,2);
			?>
			  <td style="background-color:#f0f0f0"><?=get_m_color($x)?>%<br></td>
			<?}?>
		      <td style="background-color:#f0f0f0"><?=get_m_color($x)?>%</td>
		    </tr>

		<?}?>


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
				<span class="btn_pack medium bold"><a href="list_<?=$filecode?>_excel.php?year=<?=$year?>&date_s=<?=$date_s?>&date_e=<?=$date_e?>&dtype=<?=$dtype?>&partner=<?=$partner?>"> 엑셀 </a></span>
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
