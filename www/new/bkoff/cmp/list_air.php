<?
include_once("../include/common_file.php");


chk_power($_SESSION["sessLogin"]["proof"],"기본설정");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_air";
$MENU = "cmp_basic";
$TITLE = "항공사별 타임 스케쥴";



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
	$findMode=1;
}

if($nation){
	$filter .=" and nation ='$nation' ";
	$findMode=1;
}
if($d_air) $filter .= " and d_air = '$d_air'";
if($airport_in_place) $filter .= " and airport_place = '$airport_place'";

$sort=($keyword)? "d_air asc":"id_no desc";


if(strstr("partner_a,partner_g",$_SESSION["sessLogin"]["staff_type"])){
    if($FILTER_PARTNER_QUERY) $FILTER_PARTNER_QUERY = str_replace(" and cp_id ="," and cp_id in('',",$FILTER_PARTNER_QUERY.")");
}else{
    $FILTER_PARTNER_QUERY="";
}



#query
$sql_1 = "
    select 
        a.*
    from $table as a 
    where 
        id_no>0 
        $filter
        $FILTER_PARTNER_QUERY
    ";
$sql_2 = $sql_1 . " order by $sort limit  $start, $view_row";
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
$selectTxt = "도시,국내공항,현지공항,항공사명,출발항공편,도착항공편";
$selectValue ="city,airport_in,airport_out,d_air,d_air_no,r_air_no";



#### Link
$keyword2 = base64_encode($keyword);



$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1&position=$position&d_air=$d_air&nation=$nation";
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

function copy(id_no){
	location.href="list_<?=$filecode?>_copy.php?id_no="+id_no;
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


   <select name="d_air">
   <option value="">항공사</option>
   <?=option_str($AIRLINES,$AIRLINES,$d_air)?>
   </select>

	 <select name="nation" onchange="document.fmSearch.submit()">
	 <option value="">전체보기</optioni>
	 <?=option_str($NATIONS,$NATIONS,$nation)?>
	 </select>

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
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" rowspan="2"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></th>
		<th class="subject" rowspan="2">국가</th>
		<th class="subject" rowspan="2">도시</th>
		<th class="subject" rowspan="2">공항(출발)</th>
		<th class="subject" rowspan="2">항공사명</th>
		<th class="subject" rowspan="2">공항(도착)</th>
		<th class="subject" colspan="4">출발</th>
		<th class="subject" colspan="4">귀국</th>
		<th class="subject" rowspan="2"></th>
		</tr>
	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" >항공편</th>
		<th class="subject" >출발시간</th>
		<th class="subject" >도착시간</th>
		<th class="subject" >출발요일</th>
		<th class="subject" >항공편</th>
		<th class="subject" >출발시간</th>
		<th class="subject" >도착시간</th>
		<th class="subject" >출발요일</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
if($debug){checkVar(mysql_error(),$sql_2);}
while($rs=$dbo->next_record()){

    $sql2 = "
        select id_no from cmp_reservation where r_air_id_no=$rs[id_no] or d_air_id_no=$rs[id_no] and cp_id='$CP_ID' limit 1
        union
        select id_no from cmp_estimate where r_air_id_no=$rs[id_no] or d_air_id_no=$rs[id_no] and cp_id='$CP_ID' limit 1
    ";
    $dbo2->query($sql2);
    $rs2=$dbo2->next_record();

    $bit_edit_power = ($CP_ID && $rs[cp_id]!=$CP_ID)? 1 : 1; 
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="35"><?if(!$rs2[id_no] && $bit_edit_power){?><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"><?}?></td>
	      <td><?=$rs[nation]?></td>
	      <td><?=$rs[city]?></td>
	      <td><?=$rs[airport_in]?></td>
	      <td><a href="javascript:newWin('view_<?=$filecode?>.php?id_no=<?=$rs[id_no]?>',950,580,1,1,'','air')"><?=$rs[d_air]?></a></td>
	      <td><?=$rs[airport_out]?></td>
	      <td><?=$rs[d_air_no]?></td>
	      <td><?=$rs[d_time_s]?></td>
	      <td><?=$rs[d_time_e]?></td>
	      <td><?=$rs[d_wday]?></td>
	      <td><?=$rs[r_air_no]?></td>
	      <td><?=$rs[r_time_s]?></td>
	      <td><?=$rs[r_time_e]?></td>
	      <td><?=$rs[r_wday]?></td>
	      <td><span class="btn_pack medium bold"><a href="#" onClick="copy('<?=$rs[id_no]?>')"> 복사 </a></span></td>
	    </tr>
<?
	$num--;
}
?>
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
				<span class="btn_pack medium bold"><a href="javascript:newWin('view_<?=$filecode?>.php?ctg1=<?=$ctg1?>',870,580,1,1,'air')"> 등록 </a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span>
			  </td>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>

		  </td>
        </tr>


       	<?if(!$seq_mode){?>
		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
			<!-- navigation Begin---------------------------------------------->
			<?include_once('../../include/navigation.php')?>
			<?=$navi?>
			<!-- navigation End------------------------------------------------>
		  </td>
        </tr>
		<?}?>
	</form>
	</table>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
