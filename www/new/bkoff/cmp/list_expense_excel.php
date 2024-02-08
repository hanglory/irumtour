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
$TITLE = "지출내역";

$avg_month = ($year == date("Y"))? date("m") : 12;

if(!strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
$xls_name = "expense_" . date("Ymd") . ".xls";
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
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="padding:10px">

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
//checkVar(mysql_error(),$sql);
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
		  <td height="35" colspan="2" class="c" style="background-color:#f0f0f0"> 입금내역 소계  </td>
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


</div>
</body>
</html>