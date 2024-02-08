<?
include_once("../include/common_file.php");
exit;


chk_power($_SESSION["sessLogin"]["proof"],"여행자보험");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_reservation";
$MENU = "cmp_basic";
$LEFT_HIDDEN="1";
$TITLE = "여행자보험";

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
	$best="";	 //배너 select 초기화
	$findMode=1;
}


if($_SESSION["sessLogin"]["admin"]!="super" && $_SESSION["sessLogin"]["staff_type"]!="ceo"){
    $filter.=" and main_staff like '%(".$_SESSION["sessLogin"]["id"].")'";      
}


#query
$sql_1 = "select $column from $table where id_no>0 $filter";			//자료수
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
$selectTxt = "대표자,경로,담당자,예약일,출국일,귀국일,골프장명";
$selectValue ="name,view_path,main_staff,tour_date,d_date,r_date,golf_name";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1";
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


	<div style="padding:10px 0 10px 0;">
		<span class="btn_pack medium bold"><a href="javascript:newWin('form05.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation')">여행자보험 리스트</a></span>
	</div>

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

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >대표자명</th>
		<th class="subject" >예약일</th>
		<th class="subject" >출국일</th>
		<th class="subject" >귀국일</th>
		<th class="subject" >총인원</th>
		<th class="subject" >골프장명</th>
		<th class="subject"></th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	$sql2 = $dbo2->query("select sum(price) as total ,count(bit) as cnt from cmp_people where bit=1 and code=$rs[code]");
	$rs2=$dbo2->next_record();
	$price = $rs2[total];
	$cnt = $rs2[cnt];

	$arr = explode("(",$rs[main_staff]);
	$arr2 = explode(">",$rs[golf_name]);

	$make_code = encrypt($rs[id_no],$SALT);
	$make_code = str_replace("+","{p}",$make_code);
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="35"><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',1000,650,1,1,'','reservation')"><?=$rs[name]?></a></td>
	      <td><?=$rs[tour_date]?></td>
	      <td><?=$rs[d_date]?></td>
	      <td><?=$rs[r_date]?></td>
	      <td><?=nf($rs[people])?></td>
	      <td class="left"><?=$arr2[2]?></td>
	      <td>
				<span class="btn_pack small bold"><a href="javascript:newWin('form01.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation')">예약요청서</a></span> <span class="btn_pack small bold"><a href="./form01.html?code=<?=$make_code?>" target="_blank">▶</a></span>&nbsp;
				<span class="btn_pack small bold"><a href="javascript:newWin('form02.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation')">출발 확정서</a></span> <span class="btn_pack small bold"><a href="./form02.html?code=<?=$make_code?>" target="_blank">▶</a></span>&nbsp;
				<span class="btn_pack small bold"><a href="javascript:newWin('form03.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation')">출발 안내문</a></span> <span class="btn_pack small bold"><a href="./form03.html?code=<?=$make_code?>" target="_blank">▶</a></span>&nbsp;&nbsp;
				<span class="btn_pack small bold"><a href="javascript:newWin('form04.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation')">샌딩 의뢰서</a></span>


				<span class="btn_pack small bold"><a href="javascript:newWin('form07.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation')">INVOICE</a></span> <span class="btn_pack small bold"><a href="./form07.html?code=<?=$make_code?>" target="_blank">▶</a></span>&nbsp;&nbsp;
		  </td>
	    </tr>
<?
	$num--;
}
?>
	</table>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">

		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
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
