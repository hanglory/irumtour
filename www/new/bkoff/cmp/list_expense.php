<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");

$dtype=($dtype)? $dtype : "d_date";


$date_s = ($date_s)? $date_s : $PAPER_DEFAULT_DAY1;
$date_e = ($date_e)? $date_e : $PAPER_DEFAULT_DAY2;
$year = ($year)? $year : date("Y");


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "월별비용";

$avg_month = ($year == date("Y"))? date("m") : 12;

?>
<?include("../top.html");?>
<style type="text/css">
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:right;padding-right:2px;}
</style>
<script type="text/javascript">
<!--
function month_save(code){
	newWin('view_expense.php?code='+code,950,650,1,1,'','expense')
}
//-->
</script>

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

	기준년도 : <input type="text" name="year" id="year" size="13" maxlength="10" value="<?=$year?>" class="box c">

	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>


	<?if($avg_month!=12) echo "(평균값 : " . nf($avg_month) . "월 현재 기준 - 합계 / " . nf($avg_month) . "개월)"?>
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
		<th class="subject" >월평균</th>
		<th class="subject" >비율</th>
		</tr>

		<!-- 인원 -->
<?
$total_type1=0;
$total_type2=0;
unset($total_sum_type1);
unset($total_sum_type2);
$bit_out=0;//입금항목

//전체
$sql = "
	select
		sum(price) as price
		from
		cmp_expense as a left join ez_category1 as b
		on a.category1=b.id_no
		where 
            a.year = $year
            and a.cp_id='$CP_ID'
		    and b.bit_out=$bit_out
";
$dbo->query($sql);
$rs=$dbo->next_record();
$total_price=  $rs[price];
if($debug) checkVar($total_price,$sql);

$sql = "
	select
		a.month,
		a.category1,
		a.category2,
		sum(a.price) as price
	from
	cmp_expense as a left join ez_category1 as b
	on a.category1=b.id_no
	where 
        year = $year
        and a.cp_id='$CP_ID'
		and b.bit_out=$bit_out
	group by month,category1,category2
";
$dbo->query($sql);
if($debug)  checkVar(mysql_error(),$sql);
while($rs=$dbo->next_record()){
	$did = "$rs[month]-$rs[category1]-$rs[category2]";
	$DATA[$did] = $rs[price];
}


$sql = "select * from ez_category1 where bit_out=$bit_out and cp_id='$CP_ID' order by seq";
$dbo->query($sql);
if($debug)  checkVar(mysql_error(),$sql);
while($rs=$dbo->next_record()){

	$sql2 = "select * from ez_category2 where category1='$rs[category1]' and cp_id='$CP_ID' order by seq";
	list($rows) = $dbo2->query($sql2);

	while($rs2=$dbo2->next_record()){
?>

		<tr align='center'>
	      <?if($prev!=$rs[id_no]){?>
		  <td height="35" rowspan="<?=$rows+1?>" class="c" style="background-color:#f0f0f0"><?=$rs[category1]?></td>
		  <?}?>
		  <td class="c"><?=$rs2[category2]?></td>
		<?
		for($i=1;$i<=12;$i++){

			$did = "${i}-$rs[id_no]-$rs2[id_no]";
			$code = "${year}-${i}-$rs[id_no]-$rs2[id_no]-$rs[category1]-$rs2[category2]";

			$sum[$i]+=$DATA[$did];
			$sum2[$rs2[id_no]]+=$DATA[$did];
		?>
		  <td class="hand" onclick="month_save('<?=$code?>')"><?=nf($DATA[$did])?></td>
		<?}?>
	      <td><?=nf($sum2[$rs2[id_no]])?></td>
	      <td><?=nf($sum2[$rs2[id_no]]/$avg_month)?></td>
	      <td><?=@round(($sum2[$rs2[id_no]]/$total_price)*100,1)?>%</td>
	    </tr>
<?
		$prev = $rs[id_no];
	}
?>
		<tr align='center' style="background-color:#f0f0f0">
	      <?if($prev!=$rs[id_no]){?>
		  <td height="35" rowspan="<?=$rows+1?>" class="c" style="background-color:#f0f0f0"><?=$rs[category1]?> </td>
		  <?}?>
		  <td class="c">합계</td>
		<?
		$total_sum_type1[$i]=0;
		for($i=1;$i<=12;$i++){
			$total1 += $sum[$i];
			$total_sum[$i] += $sum[$i];
			$total_sum_type1[$i] += $sum[$i];
		?>
		  <td><?=nf($sum[$i])?></td>
		<?}?>
	      <td><?=nf($total1)?></td>
	      <td><?=nf($total1/$avg_month)?></td>
	      <td><?=@round(($total1/$total_price)*100,1)?>%</td>
	    </tr>

<?
	unset($total1);
	unset($sum);
	unset($sum2);

}
?>

		<tr align='center' style="background-color:#f0f0f0">
		  <td height="35" colspan="2" class="c" style="background-color:#f0f0f0"> 출금내역 소계  </td>
		<?
		for($i=1;$i<=12;$i++){
			$total_sum2 +=$total_sum_type1[$i];
		?>
		  <td><?=nf($total_sum_type1[$i])?></td>
		<?}?>
	      <td><?=nf($total_sum2)?></td>
	      <td><?=nf($total_sum2/$avg_month)?></td>
	      <td><?=100?>%</td>
	    </tr>












<?if($user_id!="tester"){?>
<?
$bit_out=1;//출금항목

//전체
$sql = "
	select
		sum(price) as price
	from
	cmp_expense as a left join ez_category1 as b
	on a.category1=b.id_no
	where 
        a.year = $year
        and a.cp_id='$CP_ID'
		and b.bit_out=$bit_out
";
$dbo->query($sql);
$rs=$dbo->next_record();
$total_price=  $rs[price];
if($debug)  checkVar($total_price.mysql_error(),$sql);

$sql = "
	select
		a.month,
		a.category1,
		a.category2,
		sum(a.price) as price
	from
	cmp_expense as a left join ez_category1 as b
	on a.category1=b.id_no
	where 
        year = $year
        and a.cp_id='$CP_ID'
		and b.bit_out=$bit_out
	group by month,category1,category2
";
$dbo->query($sql);
if($debug)  checkVar(mysql_error(),$sql);
while($rs=$dbo->next_record()){
	$did = "$rs[month]-$rs[category1]-$rs[category2]";
	$DATA[$did] = (0-$rs[price]);
}


$sql = "select * from ez_category1 where bit_out=$bit_out and cp_id='$CP_ID' order by seq";
$dbo->query($sql);
if($debug)  checkVar(mysql_error(),$sql);
while($rs=$dbo->next_record()){

	$sql2 = "select * from ez_category2 where category1='$rs[category1]' and cp_id='$CP_ID' order by seq";
	list($rows) = $dbo2->query($sql2);

	while($rs2=$dbo2->next_record()){
?>

		<tr align='center'>
	      <?if($prev!=$rs[id_no]){?>
		  <td height="35" rowspan="<?=$rows+1?>" class="c" style="background-color:#F8E0F1"><?=$rs[category1]?></td>
		  <?}?>
		  <td class="c"><?=$rs2[category2]?></td>
		<?
		for($i=1;$i<=12;$i++){

			$did = "${i}-$rs[id_no]-$rs2[id_no]";
			$code = "${year}-${i}-$rs[id_no]-$rs2[id_no]-$rs[category1]-$rs2[category2]";

			$sum[$i]+=(0-$DATA[$did]);
			$sum2[$rs2[id_no]]+=(0-$DATA[$did]);
		?>
		  <td class="hand" onclick="month_save('<?=$code?>')"><?=nf($DATA[$did])?></td>
		<?}?>
	      <td><?=nf(0-$sum2[$rs2[id_no]])?></td>
	      <td><?=nf(0-($sum2[$rs2[id_no]]/$avg_month))?></td>
	      <td><?=@round(($sum2[$rs2[id_no]]/$total_price)*100,1)?>%</td>
	    </tr>
<?
		$prev = $rs[id_no];
	}
?>
		<tr align='center' style="background-color:#f0f0f0">
	      <?if($prev!=$rs[id_no]){?>
		  <td height="35" rowspan="<?=$rows+1?>" class="c" style="background-color:#f0f0f0"><?=$rs[category1]?> </td>
		  <?}?>
		  <td class="c">합계</td>
		<?
		$total_sum_type2[$i]=0;
		for($i=1;$i<=12;$i++){
			$total1 += (0-$sum[$i]);
			$total_sum[$i] += (0-$sum[$i]);
			$total_sum_type2[$i] += $sum[$i];
		?>
		  <td><?=nf(0-$sum[$i])?></td>
		<?}?>
	      <td><?=nf($total1)?></td>
	      <td><?=nf($total1/$avg_month)?></td>
	      <td><?=@round(($total1/$total_price)*100,1)?>%</td>
	    </tr>
<?
	unset($total1);
	unset($sum);
	unset($sum2);
}
?>

		<tr align='center' style="background-color:#f0f0f0">
		  <td height="35" colspan="2" class="c" style="background-color:#f0f0f0"> 입금내역 소계  </td>
		<?
		$total_sum2=0;
		for($i=1;$i<=12;$i++){
			$total_sum2 +=$total_sum_type2[$i];
		?>
		  <td>-<?=nf($total_sum_type2[$i])?></td>
		<?}?>
	      <td>-<?=nf($total_sum2)?></td>
	      <td>-<?=nf($total_sum2/$avg_month)?></td>
	      <td><?=100?>%</td>
	    </tr>
<?}//if($login_id!="tester"){?>













		<tr align='center' style="background-color:#f0f0f0">
		  <td height="35" colspan="2" class="c" style="background-color:#f0f0f0"> 총계 </td>
		<?
		$total_sum2=0;
		for($i=1;$i<=12;$i++){
			$total_sum2 +=$total_sum[$i];
		?>
		  <td><?=nf($total_sum[$i])?></td>
		<?}?>
	      <td><?=nf($total_sum2)?></td>
	      <td><?=nf($total_sum2/$avg_month)?></td>
	      <td><?=100?>%</td>
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
