<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");



####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "판촉물 재고";


$code=$date_s."_".$date_e."_". $dtype."_".$staff;
$code = str_replace("/","",$code);
$code = str_replace("(","",$code);
$code = str_replace(")","",$code);
?>
<?include("../top.html");?>
<style type="text/css">
#tbl_cmp_list td,#tbl_cmp_list th{padding:5px 0 5px 0;text-align:center;padding-right:2px;}
.r{padding-right:5px !important}
</style>

<script type="text/javascript">
<!--
$(function(){
	$(".box").on("change",function(){
		var url = "<?=SELF?>?mode=save&code=<?=$code?>";
		url +="&ckey="+this.id;
		url +="&cval="+this.value;
		actarea.location.href=url;
	});
});
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
	<input type=hidden name='ctg1' value="<?=$ctg1?>">


	<tr height=22>
	<td valign='bottom' align=right>



	<input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?=$date_s?>" class="box c dateinput">
	~
	<input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?=$date_e?>" class="box c dateinput">


	<select name="dtype">
		<?=option_str("예약일기준,출국일기준","tour_date,d_date",$dtype)?>
	</select>

	<?
	$sql = "select * from cmp_staff order by name asc";
	list($rows)=$dbo->query($sql);
	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar($rows.mysql_error(),$sql);}
	while($rs=$dbo->next_record()){
		$STAFF1.=",".$rs[name];
		$STAFF2.=",(".$rs[id].")";
	}
	?>
	<select name="staff">
		<?=option_str("직원명".$STAFF1,$STAFF2,$staff)?>
	</select>

	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>

	<br/>

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
	$danga_b = rnf($DANGA_BOOK);
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
			sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
		from cmp_reservation as a
		where $dtype >='$date_s' and $dtype <='$date_e'
		and main_staff like '%${staff}'
	";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27")checkVar($rs[id_no].mysql_error(),$sql);

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

	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <th><?=nf($rs[sum_fee])?><!-- 매출 --></th>
	      <td><?=nf($rs[sum_fee]*($target_rate/100))?><!-- 요율 A --></td>
	      <td><!-- 빈칸 --></td>
	      <td><!-- 빈칸 --></td>
	      <td><input type="text" id="etc1" value="<?=$rs2[etc1]?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 비고 --></td>
	    </tr>

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" >비용</th>
		<th class="subject" >단가</th>
		<th class="subject" >개수</th>
		<th class="subject" >금액</th>
		<th class="subject" >비고</th>
		</tr>

	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td>골프공<!-- 비용 --></td>
	      <td><?=nf($danga_g)?><!-- 단가 --></td>
	      <td><?=nf($rs[ball])?><!-- 개수 --></td>
	      <td><?=nf($danga_g*$rs[ball])?><!-- 금액 --></td>
	      <td><input type="text" id="etc2" value="<?=$rs2[etc2]?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 비고 --></td>
	    </tr>
	    <?
	    $sum1+=$danga_g;
	    $sum2+=$rs[ball];
	    $sum3+=$danga_g*$rs[ball];
	    ?>

	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td>골프공(고급)<!-- 비용 --></td>
	      <td><?=nf($danga_g2)?><!-- 단가 --></td>
	      <td><?=nf($rs[ball2])?><!-- 개수 --></td>
	      <td><?=nf($danga_g2*$rs[ball2])?><!-- 금액 --></td>
	      <td><input type="text" id="etc6" value="<?=$rs2[etc6]?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 비고 --></td>
	    </tr>
	    <?
	    $sum1+=$danga_g2;
	    $sum2+=$rs[ball2];
	    $sum3+=$danga_g2*$rs[ball2];
	    ?>

	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td>항공커버<!-- 비용 --></td>
	      <td><?=nf($danga_c)?><!-- 단가 --></td>
	      <td><?=nf($rs[cover])?><!-- 개수 --></td>
	      <td><?=nf($danga_c*$rs[cover])?><!-- 금액 --></td>
	      <td><input type="text" id="etc3" value="<?=$rs2[etc3]?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 비고 --></td>
	    </tr>
	    <?
	    $sum1+=$danga_c;
	    $sum2+=$rs[cover];
	    $sum3+=$danga_c*$rs[cover];
	    ?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td>달러북<!-- 비용 --></td>
	      <td><?=nf($danga_b)?><!-- 단가 --></td>
	      <td><?=nf($rs[book])?><!-- 개수 --></td>
	      <td><?=nf($danga_b*$rs[book])?><!-- 금액 --></td>
	      <td><input type="text" id="etc7" value="<?=$rs2[etc7]?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 비고 --></td>
	    </tr>
	    <?
	    $sum1+=$danga_c;
	    $sum2+=$rs[cover];
	    $sum3+=$danga_c*$rs[cover];
	    ?>

	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td>여행자보험<!-- 비용 --></td>
	      <td><?=nf($danga_i)?><!-- 단가 --></td>
	      <td><?=nf($rs[ins])?><!-- 개수 --></td>
	      <td><?=nf($danga_i*$rs[ins])?><!-- 금액 --></td>
	      <td><input type="text" id="etc4" value="<?=$rs2[etc4]?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 비고 --></td>
	    </tr>
	    <?
	    $sum1+=$danga_i;
	    $sum2+=$rs[ins];
	    $sum3+=$danga_i*$rs[ins];
	    ?>

	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td>기타<!-- 비용 --></td>
	      <td><input type="text" id="add1" value="<?=nf($rs2[add1])?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 단가 --></td>
	      <td><input type="text" id="add2" value="<?=nf($rs2[add2])?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 개수 --></td>
	      <td><?=nf($rs2[add1]*$rs2[add2])?><!-- 금액 --></td>
	      <td><input type="text" id="etc5" value="<?=$rs2[etc5]?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 비고 --></td>
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
		<th class="subject" ><?=nf($sum3)?><!-- 합계 B--></th>
		<th class="subject" ><!-- 빈칸 --></th>
		</tr>

	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <th>정산액</th>
	      <th><?=nf(($rs[sum_fee]/2)-$sum3)?><!-- 정산액 A-B --></th>
	      <td><!-- 빈칸 --></td>
	      <td><!-- 빈칸 --></td>
	      <td><!-- 빈 --></td>
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
				<span class="btn_pack medium bold"><a href="list_<?=$filecode?>_excel.php?staff=<?=$staff?>&date_s=<?=$date_s?>&date_e=<?=$date_e?>&dtype=<?=$dtype?>"> 엑셀 </a></span>
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
