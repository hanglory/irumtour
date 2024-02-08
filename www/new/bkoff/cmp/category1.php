<?
include_once("../include/common_file.php");

#### Menu
$assort_subject=($assort)?$assort:"전체";
$filecode = "category1";
$TITLE = "비용분류관리(대분류)";
$MENU = "cmp_paper";
$table = "ez_category1";


switch($mode){

	case "up":
		$top2 = $top-1;
		$sql1 = "update $table set seq=seq+1 where seq=$top2  and cp_id='$CP_ID' " ;
		$sql2 = "update $table set seq=seq-1 where id_no = '$id_no' and cp_id='$CP_ID'" ;

		$dbo->query($sql1);
		$dbo->query($sql2);

		echo "<script>history.back(-1)</script>";
		exit;
		break;

	case "down":
		$top2 = $top+1;
		$sql1 = "update $table set seq=seq-1 where seq=$top2 and cp_id='$CP_ID'" ;
		$sql2 = "update $table set seq=seq+1 where id_no = '$id_no' and cp_id='$CP_ID'" ;

		$dbo->query($sql1);
		$dbo->query($sql2);
		//checkVar(mysql_error(),$sql1);
		//checkVar(mysql_error(),$sql2);exit;

		echo "<script>history.back(-1)</script>";
		exit;
		break;
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


#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword){
	$filter.=" and $target like '%$keyword%' ";
	$findMode=1;
}
$filter .= " and cp_id='$CP_ID'"; 
if($filter) $filter = " where " . substr($filter,5);

#query
$sql1 = "select $column from $table $filter";			//자료수
$sql2 = $sql1 . " order by seq asc "; //limit  $start, $view_row
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
$selectTxt = "국문분류명,영문분류명";
$selectValue ="category1,eng_category1";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&assort=$assort&assor2=$assort2";
$sessLink = "page=$page&" . $link;


//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function selectAll(){
	fm = document.fmBbs;
	for(var i = 1; i < fm.elements.length; i++){
		fm.elements[i].checked = (fm.checkAll.checked == 1)? 1 : 0;
	}
}


function del(){
	var j = 0;
	fm = document.fmBbs;

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
		fm.action="view_category1.php";
		fm.submit();
	}
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
	<form name="fmSearch">
	<input type="hidden" name='flag' value="<?=$flag?>">
	<input type="hidden" name='assort' value="<?=$assort?>">
	<input type="hidden" name='assort2' value="<?=$assort2?>">

	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 { <?=number_format($total_page)?> page /  <?=number_format($page)?> page }</font></td>
	<td valign='bottom' align=right>
	<?if($findMode):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>

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

	<!--내용이 들어가는 곳 시작-->


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
       <form name="fmBbs" method="get" action="<?=SELF?>">
       <input type="hidden" name="mode" value='drop'>
       <input type="hidden" name="assort" value="<?=$assort?>">
       <input type="hidden" name="assort2" value="<?=$assort2?>">

        <tr><td colspan="9"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align="center" height=25 bgcolor="#F7F7F6">
		<td class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
		<td class="subject" width="50"><b>번호</b></td>
		<td class="subject"><b>구분</b></td>
		<td class="subject"><b>대문류명</b></td>
		<!-- <td class="subject"><b>대문류명(영문)</b></td> -->
		<td class="subject"><b></b></td>
		</tr>
		<tr><td colspan="9"  bgcolor='#E1E1E1' height="1"></td></tr>

<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql2);
if($debug) checkVar(mysql_error(),$sql2);
$j=1;
while($rs=$dbo->next_record()){
	$dbo3->query("update $table set seq=$j where id_no=$rs[id_no]");
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="35"><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
	      <td><?=$num?></td>
	      <td><?=($rs[bit_out])?"입금":"출금"?></td>
	      <td><a class=soft href="view_<?=$filecode?>.php?id_no=<?=$rs[id_no];?>&assort=<?=$assort?>&assort2=<?=$assort2?>" onFocus='blur(this)'><?=$rs[category1]?></a></td>
		  <!-- <td><?=$rs[eng_category1]?></td> -->
		  <td><?if(!$findMode){?>
			<?if($num!=1){?><a href='?mode=down&id_no=<?=$rs[id_no]?>&top=<?=$rs[seq]?>&assort=<?=$assort?>&assort2=<?=$assort2?>' onfocus="blur(this)" class=soft>▼</a><?}?>
			<?if($num!=$row_search){?><a href='?mode=up&id_no=<?=$rs[id_no]?>&top=<?=$rs[seq]?>&assort=<?=$assort?>&assort2=<?=$assort2?>' onfocus="blur(this)" class=soft>▲</a><?}?>
			<?}?>
		  </td>
	    </tr>
        <tr><td colspan="9"  bgcolor='#E1E1E1' height="1"></td></tr>
<?
	$j++;
	$num--;
}
?>
		<tr><td colspan=9 height=1><?=$error[noData]?></td></tr>
        <tr><td colspan=9  bgcolor='#E1E1E1' height=3></td></tr>
        <tr>
	  <td colspan=9>

	  <br>
	  <!-- Button Begin---------------------------------------------->
			  <table width="130" border="0" cellspacing="0" cellpadding="0" align="right">
				 <tr align="right">
				  <td><span class="btn_pack medium bold"><a href="#" onClick="location.href='view_<?=$filecode?>.php?assort=<?=$assort?>&assort2=<?=$assort2?>'"> 등록 </a></span></td>
				  <td><span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span></td>
				</tr>
			  </table>
	  <!-- Button End------------------------------------------------>

	  </td>
        </tr>

	</form>
	</table>





	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>