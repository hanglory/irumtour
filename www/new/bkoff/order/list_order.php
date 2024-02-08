<?
include_once("../include/common_file.php");


####각종 기초 정보 결정
$view_row=20;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;


#### 기본 정보
$filecode = substr(SELF,5,-4);
$table = "ez_order";
$MENU = "order";
$TITLE = "예약접수관리";
$filter = " and bit=1";
$column = "*";
$basicLink = "";


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
#검색조건
if($keyword)	 {
	$keyword =trim($keyword);
	$filter.=" and $target like '%$keyword%' ";$findMode=1;
}

If($status){

	$filter .=($status=="대기예약")? " and standby='1'" : " and status='$status'";
	$findMode=1;
}

$filter = ($filter)? " where " . substr($filter,5) : "";
$sort = ($sort)? $sort : "id_no desc";


#query
$sql1 = "select $column from $table  $filter";			//자료수
$sql2 = $sql1 . " order by $sort  limit  $start, $view_row";
//checkVar("",$sql2);


####자료갯수
list($rows)=$dbo->query($sql1);//검색된 자료의 갯수
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
$selectTxt = "상품명,성명,아이디,핸드폰,결제금액,출발일,상태";
$selectValue ="subject,name,id,cell,price,tour_date,status";

#### Link
$link = "keyword=$keyword&target=$target&sort=$sort";
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


function get_status(status){
	var fm = document.fmSearch;
	fm.status.value = status;
	fm.submit();
}
//-->
</script>
<script language="JavaScript" src="../../include/function.js"></script>
<script language="JavaScript">
<!--
function mng_price(code){
	newWin('pop_air_price.php?code='+code,850,800,1,1);
}

$(function(){
	$('a[title]').qtip({ style: { name: 'cream', tip: true } })
});

//-->
</script>


	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?> <?=($status)?"-".$status:""?></td>
	  </tr>
	  <tr>
		<td> </td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	</table>

    <br>

	<!-- Search Begin------------------------------------------------>
	<table border=0  width=100% cellspacing="0" cellpadding="0">
	<form name="fmSearch">
	<input type="hidden" name='flag' value="<?=$flag?>">
	<input type="hidden" name='assort' value="<?=$assort?>">
	<input type="hidden" name='assort2' value="<?=$assort2?>">
	<input type="hidden" name='status' value="<?=$satus?>">

	<tr height=22>
	<td><font color="#666666">자료수: <?=number_format($row_search)?>개 { <?=number_format($total_page)?> page /  <?=number_format($page)?> page }</font></td>
	<td valign='bottom' align=right>
	<?if($findMode):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>

	<?
	$order_status=explode(",",$ORDER_STATUS);
	For($i=0; $i < count($order_status);$i++){
	?>

		<span class="btn_pack small bold"><a href="javascript:get_status('<?=$order_status[$i]?>')"> <?=$order_status[$i]?> </a></span>&nbsp;

	<?}?>

	<select name="target" class='select'>
	<?=option_str($selectTxt,$selectValue,$target)?>
	</select>

	<span class="top"><input class="box" type="text" name="keyword" maxlength="40" value='<?=($keyword=="Iw==")? "#":$keyword;?>'></span>
	<span class="btn_pack small"><a href="#" onClick="document.fmSearch.submit()"> 검색 </a></span>
	</td>
	<tr>
	</form>
	</table>
	<!-- Search End------------------------------------------------>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
       <form name=fmData>
       <input type=hidden name=mode value='drop'>

        <tr><td colspan="14"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height="30" bgcolor="#F7F7F6">
            <td><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
			<td class="subject">번호</td>
			<td class="subject">성명</td>
			<td class="subject">상품명</td>
			<td class="subject">출발일</td>
			<td class="subject">인원</td>
			<td class="subject">금액</td>
			<td class="subject">전화번호</td>
			<td class="subject">예약일</td>
			<td class="subject">상태</td>
          </tr>
		<tr><td colspan="14"  bgcolor='#E1E1E1'></td></tr>
		<tr><td colspan="14"  bgcolor='#E1E1E1'></td></tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql2);
while($rs=$dbo->next_record()){
	$black = check_black($rs[cell],1);
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" class="lh">
	      <td><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
          <td align="center"><?=$num?></td>
          <td align="center"><?=$rs[name]?><?=($rs[id])?"($rs[id])":""?>  <a href="#" class="black" title="<?=$black?>"><?=($black)?"[BLACK]":""?></a></td>
          <td align="left"><span style="color:red;font-weight:bold"><?=($rs[standby]?"[대기]":"")?></span> <a class=soft href="javascript:newWin('view_<?=$filecode?>.php?id_no=<?=$rs[id_no]?>&<?=$basicLink?>',870,700,1,1)" onFocus="blur(this)"  style="letter-spacing:-1px;font-weight:normal"><?=($rs[subject])?$rs[subject]:"여행상품"?></a></td>
          <td align="center"><?=substr($rs[tour_date],2)?></td>
          <td align="center"><?=number_format($rs[adult])?></td>
          <td align="center"><?=number_format($rs[price])?></td>
		  <td align="center"><?=$rs[cell]?></td>
          <td align="center"><?=substr($rs[reg_date],2)?></td>
          <td align="center"><?=get_status_color($rs[status])?></td>

	    </tr>
		<tr><td colspan="14" class='bar'></td></tr>
<?
	$num--;
}
?>
		<tr><td colspan="14" height=1><?=$error[noData]?></td></tr>
        <tr><td colspan="14"  bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan="14"  bgcolor='#FFFFFF' height=10></td></tr>
        <tr>
		  <td colspan="14">

			  <!-- Button Begin---------------------------------------------->
			  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
				 <tr>
				  <td></td>
				  <td align="right"><!-- <span class="btn_pack medium bold"><a href="#" onClick="location.href='view_<?=$filecode?>.php'"> 등록 </a></span>&nbsp; -->
				  <span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span></td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>

		  </td>
        </tr>

        <tr>
	  <td colspan="14"  align=center>
		<!-- navigation Begin---------------------------------------------->
		<?include_once('../../include/navigation.php')?>
		<?=$navi?>
		<!-- navigation End------------------------------------------------>
	  </td>
        </tr>
	</form>
	</table>



<!-- Copyright -->
<?include_once("../bottom.html");?>
