<?
include_once("../include/common_file.php");



####각종 기초 정보 결정
$view_row=10;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$filecode = substr(SELF,5,-4);
$table = "ez_category2";
$MENU = "cmp_paper";
$TITLE = "비용분류관리(소분류)";
$filter = "";
$column = "*";
$basicLink = "";

switch($mode){

	case "up":
		$top2 = $top-1;
		$sql1 = "update $table set seq=seq+1 where seq=$top2 and cp_id='$CP_ID'" ;
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
$view_row=10;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$table = "ez_category2";
$column = "*";
//$basicLink = "basicFileName=$basicFileName";

####데이터를 불러오기 위한 쿼리(search의 경우를 위해)


#검색조건
$filter = " and cp_id='$CP_ID'";
if($keyword)	 $filter.=" and category2 like '%$keyword%' ";
if($category1)	 $filter.=" and category1 = '$category1' ";

$filter = ($filter)? " where " . substr($filter,4) : "";


#query
$sql1 = "select $column from $table  $filter";			//자료수
$sql2 = "select $column from $table  $filter  order by seq asc";//  limit  $start, $view_row

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
$selectTxt = "소분류,소분류";
$selectValue ="category2,category3";



#### Link
$link = $basicLink . "&keyword=$keyword&category1=$category1&orderby=$orderby&assort=$assort&keyword=$keyword";
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
		fm.action="view_category2.php";
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
	<table border=0  class="viewWidth" cellspacing="0" cellpadding="0" width="100%">
	<form name=fmSearch method="get" action="<?=SELF?>">
	<input type=hidden name='viewDiv' value="<?=$viewDiv?>">
	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 { <?=number_format($total_page)?> page /  <?=number_format($page)?> page }</font></td>
	<td valign='bottom' align=right>
	<?if($keyword):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>


<?
$dbo2->query("select * from ez_category1 order by seq asc");
while($rs2=$dbo2->next_record()){
	$categorys .= ",". $rs2[category1];
}
?>
	<select name="category1" class="select" onchange="location.href='<?=SELF?>?category1='+this.value">
		<?=option_str("전체".$categorys,$categorys,$category1)?>
	</select>

	<input class=box type="text" name="keyword" size="20" maxlength="40" value='<?=$keyword?>' onClick='this.select()'>
	<span class="btn_pack medium"><a href="#" onClick="document.fmSearch.submit()"> 검색 </a></span>
	</td>
	<tr>
	</form>
	</table>
	<!-- Search End------------------------------------------------>
	<br/>



    <table border="0" cellspacing="0" cellpadding="3" width="100%" class="viewWidth">
       <form name=fmBbs>
       <input type=hidden name=mode value='drop'>
	   <input type=hidden name=assort value='<?=$assort?>'>

		<tr><td colspan=10  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<td width="10%"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
		<td align=left><b>대분류</b></td>
		<td align=left><b>소분류</b></td>
		<td class="subject"><b></b></td>
		</tr>
		<tr><td colspan="10" class="tblLine"></td></tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql2);
$j=1;
if($debug) {checkVar(mysql_error(),$sql2);}
while($rs=$dbo->next_record()){
	$dbo3->query("update $table set seq=$j where id_no=$rs[id_no]");
?>
	    <tr height="30" onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" >
	      <td align="center"><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
		  <td><?=$rs[category1]?></td>
		  <td><a href="view_category2.php?id_no=<?=$rs[id_no]?>" class=soft><?=$rs[category2]?></a></td>
		  <td align="center"><?if(!$findMode && $category1){?>
			<?if($num!=1){?><a href='?mode=down&id_no=<?=$rs[id_no]?>&top=<?=$rs[seq]?>&assort=<?=$assort?>&assort2=<?=$assort2?>' onfocus="blur(this)" class=soft>▼</a><?}?>
			<?if($num!=$row_search){?><a href='?mode=up&id_no=<?=$rs[id_no]?>&top=<?=$rs[seq]?>&assort=<?=$assort?>&assort2=<?=$assort2?>' onfocus="blur(this)" class=soft>▲</a><?}?>
			<?}?>
		  </td>
	    </tr>
        <tr><td colspan=10  bgcolor='#E1E1E1' height="1"></td></tr>
<?
	$j++;
	$num--;
}
?>
		<tr><td colspan=10 height=1 align=center><font color="red"><b><?=($row_search<1)? "등록된 자료가 없습니다." : "";?></b></font></td></tr>
        <tr><td colspan="10" class="tblLine"></td></tr>

        <tr>
		  <td colspan=13>

		  <!-- Button Begin---------------------------------------------->
			  <table border="0" cellspacing="5" cellpadding="0" align="right">
				 <tr>
					<td><span class="btn_pack medium bold"><a href="view_category2.php?category1=<?=$category1?>"> 등록 </a></span></td>
					<td><span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span></td>
				</tr>
			  </table>
		  <!-- Button End------------------------------------------------>
	  </td>
        </tr>

        <tr>
	  <td colspan=13  align=center>
		<!-- navigation Begin----------------------------------------------
		<?include_once('../../include/navigation.php')?>
		<?=$navi?>
		-- navigation End------------------------------------------------>
	  </td>
        </tr>
	</form>
	</table>





<!-- Copyright -->
<?include_once("../bottom.html");?>
