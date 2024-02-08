<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour";
$MENU = "tour";
$TITLE = "렌트카";
$filter = " where bit=1 and category1='rentcar' ";


#### category
If($category_step1) $ctg1 = $category_step1;
Else $category_step1 = $ctg1;

If($ctg1){
	$sql = "select * from ez_tour_category1 where id_no=$ctg1";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	$TITLE	.= " > " . $rs[subject];
}


####등록이 완료되지 않고 남아 있는 상품 지우기
$time_drop = date("Y/m/d",time()-86400);
$sql = "delete from ez_tour where reg_date<'$time_drop' and (bit is null or bit='') ";
list($rows) = $dbo->query($sql);
//checkVar(mysql_error(),$sql);
//checkVar("rows",$rows);



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

#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword){
	$filter .=" and $target like '%$keyword%' ";
}

if($sale_group){
  $filter .= " and sale_group='$sale_group'";
  $find_bit=1;
}

if($position){
  $filter .= " and $position='1'";
  $find_bit=1;
}

if($tour_group){
  $filter .= " and tour_group='$tour_group'";
  $find_bit=1;
}

if($bit_jg){$filter2 .= " or bit_jg='$bit_jg'"; $find_bit=1;}
if($bit_jg){$filter2 .= " or bit_jg='$bit_cp'"; $find_bit=1;}
if($bit_a){$filter2 .= " or bit_a='$bit_a'"; $find_bit=1;}
if($bit_b){$filter2 .= " or bit_b='$bit_b'"; $find_bit=1;}
if($bit_c){$filter2 .= " or bit_c='$bit_c'"; $find_bit=1;}
$filter2 = substr($filter2,3);
if($filter2) $filter.=" and ($filter2)";

#query
$sql_1 = "select $column from $table $filter";			//자료수
$sql_2 = $sql_1 . " order by id_no desc limit  $start, $view_row";
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
$selectTxt = "상품명,상품코드";
$selectValue ="subject,tid";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1";
$sessLink = "page=$page&" . $link;

//-------------------------------------------------------------------------------
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
		alert("삭제할 자료를 선택하지 않으셨습니다.");
		return;
	}
	if(confirm("선택한 자료를 삭제하시겠습니까?")){
		fm.action="view_<?=$filecode?>.php";
		fm.submit();
	}
}

function mng_days(tid){
	newWin('pop_days.php?tid='+tid,850,800,1,1);
}

function mng_price(tid,days){
	newWin('pop_price.php?tid='+tid+'&days='+days,850,800,1,1);
}

function mng_calendar(tid){
	newWin('pop_calendar.php?tid='+tid,950,800,1,1);
}

function set_position(str,bit){
	var j = 0;
	var fm = document.fmData;

	for(var i = 1; i < fm.elements.length; i++){
		if(fm.elements[i].checked == 1){
			j++;
		}
	}

	if(j == 0){
		alert("상품을 선택하지 않으셨습니다.");
		return;
	}
	if(confirm("설정하시겠습니까?")){
		fm.mode.value="position";
		fm.mode2.value="list_rentcar.php";
		fm.action="set_position.php";
		fm.position_assort.value=str;
		fm.position_bit.value=bit;
		fm.submit();
	}
}

function get_position(str){
	var fm = document.fmSearch;
	fm.position.value="top_"+str;
	fm.submit();
}

function copy(tid){

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
	<form name=fmSearch method=post>
	<input type=hidden name='position' value="">
	<input type=hidden name='ctg1' value="<?=$ctg1?>">
	<tr>
		<td colspan="3" align="right" style="padding: 0 5px 5px 0">

		<span style="padding-right:5px">
		<a href="#" onClick="get_position('middle')" <?if($position!="top_middle"){?>style="font-weight:normal"<?}?>> 메인 middle 5 설정</a>&nbsp;|
		<a href="#" onClick="get_position('best')" <?if($position!="top_best"){?>style="font-weight:normal"<?}?>> 상품별 Top3  설정</a>&nbsp;|
		<a href="#" onClick="get_position('recom')" <?if($position!="top_recom"){?>style="font-weight:normal"<?}?>> 추천상품 top3  설정</a>&nbsp;|
		<a href="#" onClick="get_position('hit')" <?if($position!="top_hit"){?>style="font-weight:normal"<?}?>> 인기상품 top3  설정</a>
		</span>

		</td>
	</tr>


	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 { <?=number_format($total_page)?> page /  <?=number_format($page)?> page }</font></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="전체목록" onclick="location.href='?'">
	<?endif;?>


	 <label for="bit_jg">
		<input type="checkbox" name="bit_jg" id="bit_jg" value="1" <?=($bit_jg)?'checked=checked':''?> /> 지구투어
	 </label>
	 <label for="bit_cp">
		<input type="checkbox" name="bit_cp" id="bit_cp" value="1" <?=($bit_cp)?'checked=checked':''?> /> CP
	 </label>
	 <label for="bit_a">
		<input type="checkbox" name="bit_a" id="bit_a" value="1" <?=($bit_a)?'checked=checked':''?> /> A
	 </label>
	 <label for="bit_b">
		<input type="checkbox" name="bit_b" id="bit_b" value="1" <?=($bit_b)?'checked=checked':''?> /> B
	 </label>
	 <label for="bit_c">
		<input type="checkbox" name="bit_c" id="bit_c" value="1" <?=($bit_c)?'checked=checked':''?> /> C
	 </label>


	 <select name="sale_group">
	 <option value="">전체보기</optioni>
	 <?=option_str($SALE_GROUP,$SALE_GROUP_VAL,$sale_group)?>
	 </select>

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

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
       <form name="fmData" method="post">
       <input type=hidden name="mode" value='drop'>
       <input type=hidden name="mode2" value=''>
       <input type=hidden name="position_bit" value="">
       <input type=hidden name="position_assort" value="">
       <input type=hidden name="ctg1" value="<?=$ctg1?>">

        <tr><td colspan="12"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<td class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
		<!-- <td class="subject"><b>번호</b></td> -->
		<td class="subject l" width="27%"><b>상품명</b></td>
		<td class="subject"><b>지구</b></td>
		<td class="subject"><b>CP</b></td>
		<td class="subject"><b>치종</b></td>
		<td class="subject"><b>인도지역</b></td>
		<td class="subject"><b>표시가격</b></td>
		<td class="subject"><b>가격설정</b></td>
		</tr>
		<tr><td colspan="12" class='bar'></td></tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
while($rs=$dbo->next_record()){
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="35"><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
	      <!-- <td><?=$num?></td> -->
	      <td class="l pl10"><a class=soft href="view_<?=$filecode?>.php?id_no=<?=$rs[id_no];?>&ctg1=<?=$ctg1?>" onFocus='blur(this)'><?=$rs[subject]?></a></td>
	      <td><?=($rs[bit_jg])?"T":"<font color=gray>F</font>"?></td>
	      <td><?=($rs[bit_cp])?"T":"<font color=gray>F</font>"?></td>
	      <td><?=$rs[rent_type]?></td>
	      <td><?=$rs[rent_area]?></td>
		  <td><?=number_format($rs[price_adult])?></td>
	      <td>
			  <span class="btn_pack small bold"><a href="javascript:newWin('pop_rentcar_price.php?tid=<?=$rs[tid]?>',850,800,1,1)"> 가격 </a></span>
		  </td>
	    </tr>
        <tr><td colspan="12" class='bar'></td></tr>
<?
	$num--;
}
?>
		<tr><td colspan="12" height=1><?=$error[noData]?></td></tr>
        <tr><td colspan="12"  bgcolor='#E1E1E1' height=3></td></tr>
        <tr>
		  <td colspan="12">

		  <br>
		  <!-- Button Begin---------------------------------------------->
				  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
					 <tr>
					  <td width="60%" align="left">

						<?if($position=="top_middle"){?>
						<span class="btn_pack medium bold"><a href="#" onClick="set_position('middle',0)"> 메인 middle 5 해제</a></span>&nbsp;
						<?}elseif($position=="top_best"){?>
						<span class="btn_pack medium bold"><a href="#" onClick="set_position('best',0)"> 상품별 Top3  해제</a></span>&nbsp
						<?}elseif($position=="top_recom"){?>
						<span class="btn_pack medium bold"><a href="#" onClick="set_position('recom',0)"> 추천상품 top3  해제</a></span>&nbsp;
						<?}elseif($position=="top_hit"){?>
						<span class="btn_pack medium bold"><a href="#" onClick="set_position('hit',0)"> 인기상품 top3  해제</a></span>&nbsp;
						<?}else{?>
						<span class="btn_pack medium bold"><a href="#" onClick="set_position('middle',1)"> 메인 middle 5 설정</a></span>&nbsp;
						<span class="btn_pack medium bold"><a href="#" onClick="set_position('best',1)"> 상품별 Top3  설정</a></span>&nbsp;
						<span class="btn_pack medium bold"><a href="#" onClick="set_position('recom',1)"> 추천상품 top3  설정</a></span>&nbsp;
						<span class="btn_pack medium bold"><a href="#" onClick="set_position('hit',1)"> 인기상품 top3  설정</a></span>&nbsp;
						<?}?>

					  </td>
					  <td align="right">
						<span class="btn_pack medium bold"><a href="#" onClick="location.href='view_<?=$filecode?>.php?ctg1=<?=$ctg1?>'"> 등록 </a></span>&nbsp;
						<span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span>
					  </td>
					</tr>
				  </table>
		  <!-- Button End------------------------------------------------>

		  </td>
        </tr>

        <tr>
	  <td colspan="12"  align=center>
		<!-- navigation Begin---------------------------------------------->
		<?include_once('../../include/navigation.php')?>
		<?=$navi?>
		<!-- navigation End------------------------------------------------>
	  </td>
        </tr>
	</form>
	</table>





	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>