<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"고객별예약정보관리대장");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_people";
$MENU = "cmp_basic";
$LEFT_HIDDEN="1";
$TITLE = "기간별입출금현황";


if($mode=="save" && $id_no){
	$sql ="update cmp_people set memo='$memo' where id_no=$id_no";
	$dbo->query($sql);
	exit;
}


####각종 기초 정보 결정
$view_row=20;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$column = "*";
$basicLink = "";



####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
if(!$target2) $target2="tour_date";
if(!$period_s) $period_s=date("Y/m/d");
if(!$period_e) $period_e=date("Y/m/d");//date("Y/m/d",mktime(0,0,0,date("m")+1,1,date("Y"))-1);

#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;

if($target2=="total"){
	$filter .="
		and (
			(a.date_in >='$period_s' and a.date_in <='$period_e')
			or (a.date_in2 >='$period_s' and a.date_in2 <='$period_e')
			or (a.date_out >='$period_s' and a.date_out <='$period_e')
			or (a.date_out2 >='$period_s' and a.date_out2 <='$period_e')
		)
	";
	$rs[period_s]=$period_s;
	$rs[period_e]=$period_e;
	$find_bit=1;
}else{
	if($period_s){ $filter.= " and $target2 >='$period_s' ";$rs[period_s]=$period_s;$find_bit=1;}
	if($period_e){ $filter.= " and $target2 <='$period_e' ";$rs[period_e]=$period_e;$find_bit=1;}
}

if($keyword){
	$filter .=" and $target like '%$keyword%' ";
	$best="";	 //배너 select 초기화
	$findMode=1;
}



#query
$sql_1 = "
	select 
		a.*,
		b.name as leader,
		(select count(name) from cmp_people where code=a.code and name<>'' and bit=1) as cnt
	from cmp_people as a left join cmp_reservation as b
	on a.code=b.code
	where a.code<>'' 
	and a.name<>''
	and a.bit=1
	$filter
";
$sql_2 = $sql_1 . " order by code desc,id_no asc";
//checkVar("",$sql_2);

####자료갯수
list($rows)=$dbo->query($sql_1);//검색된 자료의 갯수
$row_search = $rows;



####페이지 처리

$var=ceil($row_search/$view_row);
if ($var > 1){
	$total_page=$var;
}
else{
	$total_page=1;
}



####자료가 하나도 없을 경우의 처리
if(!$row_search){
   $error[noData] = accentError("해당하는 자료가 없습니다.");
}



####검색 항목
$selectTxt = "대표자,고객명,담당자";
$selectValue ="b.name,a.name,b.main_staff";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1";
$sessLink = "page=$page&" . $link;

?>
<?include("../top.html");?>
<script language="JavaScript">
<!--
function save_memo(id_no,memo){
	var fm = document.fmData;
	fm.id_no.value = id_no;
	fm.memo.value = memo;
	fm.submit();
}

//-->
</script>
<style type="text/css">
.blue{color:blue !important;}	
.red{color:red !important;}	
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


	<!--내용이 들어가는 곳 시작-->

	<!-- Search Begin------------------------------------------------>
	<div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
	<form name=fmSearch method="get">
	<input type=hidden name='position' value="">
	<input type=hidden name='ctg1' value="<?=$ctg1?>">


	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=number_format($total_page)?> page /  <?=number_format($page)?> page }<?}?></font></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>


	<select name="target2" class='select'>
	<?=option_str("입출금,예약일,출발일,출금일1,출금일2,입금일1,입금일2","total,b.tour_date,b.d_date,a.date_out,a.date_out2,a.date_in,a.date_in2",$target2)?>
	</select>

	<?=html_input("period_s",13,10,'box dateinput')?> ~
	<?=html_input("period_e",13,10,'box dateinput')?>


	<select name="target" class='select'>
	<?=option_str($selectTxt,$selectValue,$target)?>
	</select>

	<input class=box type="text" name="keyword" size="15" maxlength="40" value='<?=$keyword?>'>
	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >대표자명</th>
		<th class="subject" >고객명</th>
		<th class="subject" >판매가</th>
		<th class="subject blue" >출금액1<br/>(항공요금)</th>
		<th class="subject blue" >출금일1</th>
		<th class="subject blue" >출금액2<br/>(지상비)</th>
		<th class="subject blue" >출금일2</th>
		<th class="subject red" >입금액1<br>(계약금)</th>
		<th class="subject red" >입금일1</th>
		<th class="subject red" >입금액2<br>(잔금)</th>
		<th class="subject red" >입금일2</th>
		<th class="subject" >메모</th>
		</tr>
<?
$sum1=0;
$sum2=0;
$sum3=0;
$sum4=0;
$sum5=0;
$sum6=0;

if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//if($REMOTE_ADDR=="106.246.54.27") checkVar($target.mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	$row = ($prev_leader!=$rs[leader])?1:0;	


	if($rs[date_out] >=$period_s && $rs[date_out] <=$period_e){}else{$rs[price_air]=0;$rs[date_out]="";}
	if($rs[date_out2] >=$period_s && $rs[date_out2] <=$period_e){}else{$rs[price_land]=0;$rs[date_out2]="";}
	if($rs[date_in] >=$period_s && $rs[date_in] <=$period_e){}else{$rs[price_prev]=0;$rs[date_in]="";}
	if($rs[date_in2] >=$period_s && $rs[date_in2] <=$period_e){}else{$rs[price_prev2]=0;$rs[date_in2]="";}


	$sum1 += $rs[price];
	$sum2 += $rs[price_air];
	$sum3 += $rs[price_land];
	$sum4 += $rs[price_prev];
	$sum5 += $rs[price_last];
	$sum6 += $rs[price_prev2];
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      
	      <?if($target!="a.name" && $target2!="a.date_in" && $target2!="a.date_out" && $target2!="a.date_in2" && $target2!="a.date_out2" && $target2!="total"){?>
	      <?if($row){?>	
	      <td rowspan="<?=$rs[cnt]?>"><a href="javascript:newWin('view_reservation.php?code=<?=$rs[code]?>',1200,650,1,1,'','estimate')"><?=$rs[leader]?></a></td>
	      <?}?>
	      <?}else{?>
	      <td><a href="javascript:newWin('view_reservation.php?code=<?=$rs[code]?>',1200,650,1,1,'','estimate')"><?=$rs[leader]?></a></td>
	      <?}?>

	      <td height="30"><?=$rs[name]?></td>
	      <td class="r pr10"><?=nf($rs[price])?></td>
	      <td class="r pr10 blue"><?=nf($rs[price_air])?></td>
	      <td class="blue"><?=($rs[date_out])?></td>
	      <td class="r pr10 blue"><?=nf($rs[price_land])?></td>
	      <td class="blue"><?=($rs[date_out2])?></td>
	      <td class="r pr10 red"><?=nf($rs[price_prev])?></td>
	      <td class="red"><?=($rs[date_in])?></td>
	      <td class="r pr10 red"><?=nf($rs[price_prev2])?></td>
	      <td class="red"><?=($rs[date_in2])?></td>
	      <td><input type="text" id="memo_<?=$rs[id_no]?>" value="<?=$rs[memo]?>" maxlength="30" class="box" style="width:98%" onchange="save_memo(<?=$rs[id_no]?>,this.value)"/></td>
	    </tr>
<?
	$prev_leader = $rs[leader];

	$num--;
}
?>
	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" colspan="2">합계</th>
		<th class="r pr10" style="text-align: right !important"><?=nf($sum1)?></th>
		<th class="r pr10 blue" style="text-align: right !important"><?=nf($sum2)?></th>
		<th class="subject" ></th>
		<th class="r pr10 blue" style="text-align: right !important"><?=nf($sum3)?></th>
		<th class="subject" ></th>
		<th class="r pr10 red" style="text-align: right !important"><?=nf($sum4)?></th>
		<th class="subject" ></th>
		<th class="r pr10 red" style="text-align: right !important"><?=nf($sum6)?></th>
		<th class="subject" ></th>
		<th class="subject" ></th>		
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
			  <?if(strstr($_SESSION["sessLogin"]["proof"],"엑셀다운로드")){?>
				<span class="btn_pack medium bold"><a href="list_payment_excel.php?position=<?=$position?>&ctg1=<?=$ctg1?>&target2=<?=$target2?>&period_s=<?=$period_s?>&period_e=<?=$period_e?>&target=<?=$target?>&keyword=<?=$keyword?>"> 엑셀 다운로드 </a></span>&nbsp;
			  <?}?>
			  </td>
			</tr>
		  </table>
		  <!--//Button End------------------------------------------------>

		  </td>
        </tr>


		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
			<!-- navigation Begin---------------------------------------------->
			<?include_once('../../include/navigation.php')?>
			<?//=$navi?>
			<!-- navigation End------------------------------------------------>
		  </td>
        </tr>
	</table>

	<form name="fmData" method="post" action="<?=SELF?>" target="actarea">
		<input type="hidden" name="mode" value="save">
		<input type="hidden" name="id_no" value="">
		<input type="hidden" name="memo" value="">
	</form>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
