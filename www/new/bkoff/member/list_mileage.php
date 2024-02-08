<?
include_once("../include/common_file.php");



####각종 기초 정보 결정
$view_row=($view_row)?$view_row:10;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$filecode = substr(SELF,5,-4);
$table = "ez_special_point";
$MENU = "member";
$TITLE = "회원 마일리지 리스트";
$filter = "";
$column = "*";
$basicLink = "";


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
#검색조건
if($keyword)	 {
	$keyword =trim($keyword);
	$filter.=" and $target like '%$keyword%' ";$findMode=1;
}

if($start_date)	 {
	$filter.=" and reg_date >= '$start_date' ";
}
if($end_date)	 {
	$filter.=" and reg_date <= '$end_date ' ";
}

//$filter = ($filter)? " where " . substr($filter,5) : "";
$sort = ($sort)? $sort : "reg_date desc";


#query
$sql1 = "select id_no,point,use_point,subject,id,name,reg_date,'O' as assort from ez_order where (point>0 or use_point>0) and id<>'' $filter";			//자료수
$sql1 .= "union all select id_no,point,use_point,subject,id,name,reg_date,'A' as assort from ez_special_point where (point>0 or use_point>0) and id<>'' $filter";			//자료수
$sql2 = $sql1 . " order by $sort  limit  $start, $view_row";
$dbo->query($sql1);
//checkVar(mysql_error(),$sql1);


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
$selectTxt = "아이디,회원명,내용";
$selectValue ="id,nmame,subject";



#### Link
$link = "keyword=$keyword&target=$target&sort=$sort&view_row=$view_row";
$sessLink = "page=$page&" . $link;

//-------------------------------------------------------------------------------
?>
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

//-->
</script>
<script language="JavaScript" src="../include/function.js"></script>
<script language="JavaScript">
<!--
function save4excel(){
	newWin('save4excel.php?filter=<?=base64_encode($filter)?>',150,10,0,0); //150,10
}

function sort(str){
	var fm = document.fmSearch;
	fm.sort.value=str;
	fm.submit();
}
//-->
</script>
<?include("../top.html");?>



	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
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
	<form name=fmSearch>
	<input type=hidden name='flag' value="<?=$flag?>">
	<input type=hidden name='assort' value="<?=$assort?>">
	<input type=hidden name='assort2' value="<?=$assort2?>">

	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 { <?=number_format($total_page)?> page /  <?=number_format($page)?> page }</font></td>
	<td valign='bottom' align=right>
	<?if($findMode):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>

	<input class="box dateinput" type="text" name="start_date" size="10" maxlength="10" value='<?=$start_date?>'> ~
	<input class="box dateinput" type="text" name="end_date" size="10" maxlength="10" value='<?=$end_date?>'>
	<?include_once("../include/include_period.php")?>
	&nbsp;&nbsp;
	<select name="view_row" onchange="location.href='?<?=$QUERY_STRING?>&view_row='+this.value">
		<?=option_int(10,100,10,$view_row)?>
	</select>
	행씩보기

	&nbsp;&nbsp;
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

        <tr><td colspan=13  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height=25 bgcolor="#F7F7F6"'>
			<td class="subject">번호</td>
			<td class="subject">아이디</td>
			<td class="subject">회원명</td>
			<td class="subject l">내용</td>
			<td class="subject r">적립 마일리지</td>
			<td class="subject r">사용 마일리지</td>
			<td class="subject">등록일</td>
          </tr>
		<tr><td colspan=13  bgcolor='#E1E1E1'></td></tr>
		<tr><td colspan=13  bgcolor='#E1E1E1'></td></tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql2);
while($rs=$dbo->next_record()){
?>
	    <tr align='center' height="30" onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td align="center"><?=$num?></td>
          <td align="center"><?=$rs[id]?></td>
          <td align="center"><?=$rs[name]?></td>
          <td align="left"><?if($rs[assort]=='A'){?><a href="view_mileage.php?id_no=<?=$rs[id_no]?>"><?}?><?=$rs[subject]?> <font color="gray"><?=($rs[assort]=='A')?"[이벤트적립]":""?></font></td>
          <td align="right"><?=number_format($rs[point])?>&nbsp;&nbsp;</td>
          <td align="right"><?=number_format($rs[use_point])?>&nbsp;&nbsp;</td>
          <td align="center"><?=substr($rs[reg_date],0,10)?></td>
	    </tr>
		<tr><td colspan=13 bgcolor='#cccccc'></td></tr>
		<?=$error[noData]?>
<?
	$num--;
}
?>
		<tr><td colspan=13 height=1><?=$error[noData]?></td></tr>
        <tr><td colspan=13  bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan=13  bgcolor='#FFFFFF' height=10></td></tr>


        <tr>
	  <td colspan=13  align=center>
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
