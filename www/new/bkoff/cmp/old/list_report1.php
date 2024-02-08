<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"기간별매출현황");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_reservation";
$MENU = "cmp_basic";
$LEFT_HIDDEN = "1";
$TITLE = "기간별매출현황";

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
if(!$period_s) $period_s=date("Y/m/d",mktime(0,0,0,date("m"),1,date("Y")));
if(!$period_e) $period_e=date("Y/m/d",mktime(0,0,0,date("m")+1,1,date("Y"))-1);


#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : trim($keyword);
if($keyword){
	$filter .=" and $target like '%$keyword%' ";
	$best="";	 //배너 select 초기화
	$findMode=1;
}

if($period_s){ $filter.= " and $target2 >='$period_s' ";$rs[period_s]=$period_s;$find_bit=1;}
if($period_e){ $filter.= " and $target2 <='$period_e' ";$rs[period_e]=$period_e;$find_bit=1;}


#query
/*
$sql_1 = "
	select
		a.*
		from $table as a
		where
		a.id_no>0
		$filter
	";			//자료수
$sql_2 = $sql_1 . " order by a.id_no desc"; // limit  $start, $view_row
*/

#query
$sql_1 = "
	select
		a.*,
		b.partner
		from $table as a left join cmp_golf as b
		on a.golf_id_no=b.id_no
		where
		a.id_no>0
		$filter
	";			//자료수
$sql_2 = $sql_1 . " order by a.id_no desc"; // limit  $start, $view_row
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
$selectTxt = "대표자명,국가/지역/골프장명,경로,출발일,결제수단,담당자,거래처";
$selectValue ="a.name,a.golf_name,a.view_path,a.d_date,a.pay_method,a.main_staff,b.partner";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&period_s=$period_s&period_e=$period_e&target2=$target2";
$sessLink = "page=$page&" . $link;

?>
<?include("../top.html");?>
<script language="JavaScript">
<!--
function selectAll(){
	fm = document.fmData;
	for(var i = 1; i < fm.elements.length; i++){
		fm.elements[i].checked = (fm.checkAll.checked == 1)? 1 : 0;
	}
}

function del(){
	var j = 0;
	fm = document.fmData;

	for(var i = 1; i < fm.elements.length; i++){
		if(fm.elements[i].checked == 1){
			j++;
		}
	}
	if(j == 0){
		alert("삭제할 상품을 선택하지 않으셨습니다.");
		return;
	}
	if(confirm("선택한 상품을 삭제하시겠습니까?")){
		fm.action="view_<?=$filecode?>.php";
		fm.mode.value="drop";
		fm.submit();
	}
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
	<?=option_str("예약일,출발일","tour_date,d_date",$target2)?>
	</select>

	<?=html_input("period_s",13,10,'box dateinput')?> ~
	<?=html_input("period_e",13,10,'box dateinput')?>

	<select name="target" class='select'>
	<?=option_str($selectTxt,$selectValue,$target)?>
	</select>
	</td>
	<td align=right width=150 valign=top>
	<input class=box type="text" name="keyword" size="15" maxlength="40" value='<?=$keyword?>'>
	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" rowspan="2">대표자명</th>
		<th class="subject" rowspan="2">국가</th>
		<th class="subject" rowspan="2">지역</th>
		<th class="subject" rowspan="2">상품명</th>
		<th class="subject" rowspan="2">경로</th>
		<th class="subject" rowspan="2">인원</th>
		<th class="subject" rowspan="2">예약일</th>
		<th class="subject" rowspan="2">출발일</th>
		<th class="subject" rowspan="2">박수</th>
		<th class="subject" >매출</th>
		<th class="subject" colspan="3">입금가</th>
		<th class="subject" rowspan="2">수수료<br/>(매출-입금가)</th>
		<th class="subject" rowspan="2">1인수익</th>
		<th class="subject" rowspan="2">고객입금액</th>
		<th class="subject" rowspan="2">가지급금</th>
		<th class="subject" rowspan="2">담당자</th>
		<th class="subject" rowspan="2">결제수단</th>
		<th class="subject" rowspan="2">골프공</th>
		<th class="subject" rowspan="2">항공카버</th>
		</tr>


	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >인원수<br/>*1인판매가</th>
		<th class="subject" >항공</th>
		<th class="subject" >지상</th>
		<th class="subject" >소계</th>
		</tr>

<?
$price1=0;
$price2=0;
$price3=0;
$price4=0;
$price5=0;
$price6=0;
$price7=0;
$price8=0;
$price9=0;
$price10=0;
$price11=0;

if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){
	$golf_name = explode(">",$rs[golf_name]);

	$night = (strtotime($rs[r_date]) -  strtotime($rs[d_date]))/86400;//박수

	$sql3 = "select
			count(*) as cnt,
			sum(price) as price,
			sum(price_air) as price_air,
			sum(price_land) as price_land
		from cmp_people where code=$rs[code] and bit=1";
	$dbo3->query($sql3);
	$rs3= $dbo3->next_record();
	@$gain_one = ($rs3[price] - ($rs3[price_air]+$rs3[price_land]))/$rs[people];

	$staff = explode("(",$rs[main_staff]);

?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
		<td height="35"><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',950,650,1,1,'','reservation')"><?=$rs[name]?></a></td>
		<td><?=trim($golf_name[0])?></td>
		<td><?=trim($golf_name[1])?></td>
		<td><?=trim($golf_name[2])?></td>
		<td><?=$rs[view_path]?></td>
		<td><?=nf($rs[people])?></td>
		<td><?=$rs[tour_date]?></td>
		<td><?=$rs[d_date]?></td>
		<td><?=$night?></td>
		<td class="numberic"><?=nf($rs3[price])?></td>
		<td class="numberic"><?=nf($rs3[price_air])?></td>
		<td class="numberic"><?=nf($rs3[price_land])?></td>
		<td class="numberic"><?=nf($rs3[price_air]+$rs3[price_land])?></td>
		<td class="numberic"><?=nf($rs3[price]-($rs3[price_air]+$rs3[price_land]))?></td>
		<td class="numberic"><?=nf($gain_one)?></td>
		<td class="numberic"><?=nf($rs[price_customer_input])?></td>
		<td class="numberic"><?=nf($rs[price_tmp_output])?></td>
		<td><?=$staff[0]?></td>
		<td><?=$rs[pay_method]?></td>
		<td class="numberic"><?=$rs[golf_ball]?></td>
		<td class="numberic"><?=$rs[air_cover]?></td>
	    </tr>
<?

	$price1+=$rs[people];
	$price2+=$rs3[price];
	$price3+=$rs3[price_air];
	$price4+=$rs3[price_land];
	$price5+=($rs3[price_air]+$rs3[price_land]);
	$price6+=($rs3[price]-($rs3[price_air]+$rs3[price_land]));
	$price7+=$gain_one;
	$price8+=($rs[price_customer_input]);
	$price9+=($rs[price_tmp_output]);

	$price10+=$rs[golf_ball];
	$price11+=$rs[air_cover];

	$num--;
}
?>

	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
		<td height="35" colspan="5" style="font-weight:bold;color:red">합계</td>
		<td style="font-weight:bold;color:red"><?=nf($price1)?></td>
		<td></td>
		<td></td>
		<td></td>
		<td class="numberic" style="font-weight:bold;color:red"><?=nf($price2)?></td>
		<td class="numberic" style="font-weight:bold;color:red"><?=nf($price3)?></td>
		<td class="numberic" style="font-weight:bold;color:red"><?=nf($price4)?></td>
		<td class="numberic" style="font-weight:bold;color:red"><?=nf($price5)?></td>
		<td class="numberic" style="font-weight:bold;color:red"><?=nf($price6)?></td>
		<td class="numberic" style="font-weight:bold;color:red"><?=nf($price7)?></td>
		<td class="numberic" style="font-weight:bold;color:red"><?=nf($price8)?></td>
		<td class="numberic" style="font-weight:bold;color:red"><?=nf($price9)?></td>
		<td></td>
		<td></td>
		<td class="numberic" style="font-weight:bold;color:red"><?=nf($price10)?></td>
		<td class="numberic" style="font-weight:bold;color:red"><?=nf($price11)?></td>
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

			  <?if(strstr($_SESSION["sessLogin"]["proof"],"엑셀다운로드")){?>
			  <td align="right">
				<span class="btn_pack medium bold"><a href="list_report1_print.php?period_s=<?=$period_s?>&period_e=<?=$period_e?>&target=<?=$target?>&target2=<?=$target2?>&keyword=<?=$keyword?>"> 엑셀 </a></span>
			  </td>
			  <?}?>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>

		  </td>
        </tr>

<!--
       	<?if(!$seq_mode){?>
		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
			<?include_once('../../include/navigation.php')?>
			<?=$navi?>
		  </td>
        </tr>
		<?}?> -->
	</form>
	</table>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
