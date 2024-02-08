<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");

$dtype=($dtype)? $dtype : "d_date";



$date_s = ($date_s)? $date_s : $PAPER_DEFAULT_DAY1;
$date_e = ($date_e)? $date_e : $PAPER_DEFAULT_DAY2;

$year_this = substr($date_s,0,4);
$year_prev = substr($date_s,0,4)-1;
$date_s2 = $year_prev . substr($date_s,4);
$date_e2 = $year_prev . substr($date_e,4);


/*
if(substr($date_s,0,4)!=substr($date_e,0,4)){
	error("검색하시는 시작일의 년도와 종료일의 연도가 같아야 합니다.");
	exit;
}
*/


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "항공사 이용 현황";
$TITLE .=($ctype)? "> $ctype " : "";
$TITLE .=($dtype=="d_date")? "(출국일자 기준)" : "(예약일자 기준)";
?>
<?include("../top.html");?>
<style type="text/css">
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:center;padding-right:2px;}
.r{padding-right:5px !important}
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
	<input type=hidden name='ctg1' value="<?=$ctg1?>">


	<tr height=22>
	<td valign='bottom' align=right>


	<input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?=$date_s?>" class="box c dateinput">
	~
	<input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?=$date_e?>" class="box c dateinput">

	<select name="dtype">
		<?=option_str("예약일기준,출국일기준","tour_date,d_date",$dtype)?>
	</select>

	<input type="text" name="city" id="city" size="13" maxlength="10" value="<?=$city?>" placeholder="도시" class="box"> 	
	<input type="text" name="d_air" id="d_air" size="13" maxlength="10" value="<?=$d_air?>" placeholder="항공사" class="box"> 	
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

	?>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
			<th class="subject" >국가</th>
			<th class="subject" >도시</th>
			<th class="subject" >항공사</th>
			<th class="subject" >인원</th>
			<th class="subject" >이름</th>
			<th class="subject" >생년월일</th>
		</tr>
		<?
		if($city) $filter = " and b.city like '%$city%'";
		if($d_air) $filter = " and b.d_air like '%$d_air%'";

		$sql = "
			select
				a.${dtype} as date,
				a.code,
				b.nation,
				b.city,
				b.d_air,
				sum(a.people) as total
				from cmp_reservation as a left join cmp_air as b
				on a.d_air_id_no=b.id_no
			where
				((a.$dtype >= '$date_s' and a.$dtype <='$date_e')
				or
				(a.$dtype >= '$date_s2' and a.$dtype <='$date_e2'))
				and b.city<>''
				$filter
			group by a.code,b.nation,b.city,b.d_air
			order by b.nation asc,b.city asc, b.d_air asc,$dtype asc
		";
		
		$dbo->query($sql);
		if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql);}
		$total=0;
		while($rs=$dbo->next_record()){
			$total+=$rs[total];

			$sql2="select * from cmp_people where code=$rs[code] and bit=1 order by id_no asc";
			$dbo2->query($sql2);
			//checkVar(mysql_error(),$sql2);

			for($i=1; $i<=$rs[total];$i++){
				$rs2=$dbo2->next_record();
				if($rs2[rn]){
				$aes = new AES($rs2[rn], $inputKey, $blockSize);
				$dec=$aes->decrypt();
				$rs2[rn] = substr($dec,0,8) . "*******";
				}				
		?>
			
			<?if($i==1){?>
			<tr style="background-color:#f0f0f0">	
				<td rowspan="<?=$rs[total]?>"><?=$rs[nation]?></td>
				<td rowspan="<?=$rs[total]?>"><?=$rs[city]?></td>
				<td rowspan="<?=$rs[total]?>"><?=$rs[d_air]?></td>
				<td rowspan="<?=$rs[total]?>"><?=nf($rs[total])?>명</td>
				<td height="30"><a href="javascript:newWin('view_reservation.php?code=<?=$rs[code]?>',1000,650,1,1,'','reservation')"><?=$rs2[name]?></a></td>
				<td><?=$rs2[rn]?></td>				
			</tr>	
			<?}else{?>
			<tr style="background-color:#f0f0f0">				
				<td height="30"><?=$rs2[name]?></td>
				<td><?=$rs2[rn]?></td>									
			</tr>
			<?}?>
			<?}?>
		<?}?>


		<tr style="background-color:#ffe6cc">
			<td colspan="3">합계</td>
			<td><?=nf($total)?>명</td>
			<td></td>
			<td></td>
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
				<span class="btn_pack medium bold"><a href="list_<?=$filecode?>_excel.php?year=<?=$year?>&date_s=<?=$date_s?>&date_e=<?=$date_e?>&dtype=<?=$dtype?>&city=<?=$city?>&d_air=<?=$d_air?>"> 엑셀 </a></span>
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
