<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "bbs";
$TITLE = "게시판 등록 관리";
$path_c= explode("/",$_SERVER["SCRIPT_FILENAME"]);
$www = ($path_c[count($path_c)-4]!="www")? $path_c[count($path_c)-4]."/": "";


####각종 기초 정보 결정
$view_row=20;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$table = "ez_bbs_info";
$filter = "";
$column = "*";
$basicLink = "";



####데이터를 불러오기 위한 쿼리(search의 경우를 위해)


#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword)	 $filter.=" and $target like '%$keyword%' ";
if($flag)		 $filter.=" and flag = '$flag' ";


$filter = ($filter)? " where " . substr($filter,5) : "";

#query
$sql1 = "select $column from $table  $filter";			//자료수
$sql2 = "select $column from $table  $filter  order by id_no desc  limit  $start, $view_row";




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
$selectTxt = "게시판이름,게시판아이디";
$selectValue ="subject,bid";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&flag=$flag";
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
		fm.action="reg_bbs.php";
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
	<table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
	<form name=fmSearch method=post>
	<input type=hidden name='flag' value="<?=$flag?>">

	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 { <?=number_format($total_page)?> page /  <?=number_format($page)?> page }</font></td>
	<td valign='bottom' align=right>
	<?if($keyword):?>
	<input class=button type="button" value="전체목록" onclick="document.fmSearch.keyword.value='';fmSearch.submit();">
	<?endif;?>
	<select name="target" class='select'>
	<?=option_str($selectTxt,$selectValue,$target)?>
	</select>
	</td>
	<td align=right width=195 valign=top>
	<input class=box type="text" name="keyword" size="20" maxlength="40" value='<?=$keyword?>'>
	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	<!-- Search End------------------------------------------------>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
       <form name=fmBbs>
       <input type=hidden name=mode value='drop'>
       <input type=hidden name=bid value="<?=$bid?>">

        <tr><td colspan="20"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height="30" bgcolor="#F7F7F6">
		<td><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
			<td><b>번호</b></td>
			<td align=left><b>제목</b></td>
			<td align=left><b>게시판링크</b></td>
			<td><b>게시판종류</b></td>
			<!-- <td><b>게시물관리</b></td> -->
			<td><b>Read</b></td>
			<td><b>Write</b></td>
			<td><b>Reply</b></td>
			<td><b>등록일</b></td>
			<td><b>관리하기</b></td>
		</tr>
		<tr><td colspan="20"  bgcolor='#E1E1E1'></td></tr>
		<tr><td colspan="20"  bgcolor='#E1E1E1'></td></tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql2);
while($rs=$dbo->next_record()){
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" class="lh">
	      <td><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
	      <td><?=$num?></td>
	      <td align=left><?=$rs[subject]?></td>
	      <td align=left><a href="../../board.php?bid=<?=$rs[bid]?>" target="_blank" class=soft>http://<?=$_SERVER["HTTP_HOST"] ?>/<?=$www?>board.php?bid=<?=$rs[bid]?></a></td>
	      <td><?=board_assort($rs[assort])?></td>
		  <!-- <td><a href="list_bbs.php?bbs_id=<?=$rs[bid]?>&title_bar=<?=$rs[subject]?>" class=soft>게시물 관리하기</a></td> -->
	      <td><?=getMemberName($rs[power_read])?></td>
	      <td><?=getMemberName($rs[power_write])?></td>
		  <td><?=getMemberName($rs[power_comment])?></td>
		  </td>
	      <td><?=$rs[reg_date]?></td>
	      <td><span class="btn_pack small bold"><a href="reg_bbs.php?id_no=<?=$rs[id_no]?>"> 수정 </a></span></td>
	    </tr>
		<tr><td colspan="20" class="bar"></td></tr>
<?
	$num--;
}
?>
		<tr><td colspan="20" height=1><?=$error[noData]?></td></tr>
        <tr><td colspan="20"  bgcolor='#E1E1E1' height=3></td></tr>
        <tr>
	  <td colspan="20">

	  <br>
	  <!-- Button Begin---------------------------------------------->
			  <table width="130" border="0" cellspacing="0" cellpadding="0" align="right">
				 <tr align="right">
				  <td><span class="btn_pack medium bold"><a href="#" onClick="location.href='reg_bbs.php'"> 등록 </a></span></td>
				  <td><span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span></td>
				</tr>
			  </table>
	  <!-- Button End------------------------------------------------>


	  </td>
        </tr>

        <tr>
	  <td colspan="20"  align=center>
		<!-- navigation Begin---------------------------------------------->
		<?include_once('../../include/navigation.php')?>
		<?=$navi?>
		<!-- navigation End------------------------------------------------>
	  </td>
        </tr>
	</form>
	</table>

	  <!-- 도움말 -->
		<table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
		<tr>
		<td style="border:1 #E1E1E1 solid;" BGCOLOR=#FFFFFF>
			<table width=100%>
				<td width=70 align=right valign=top><img src="../images/icon/help.jpg" width="64" height="82" border="0"></td>
				<td style="color:gray" bgcolor='#F7F7F6'>
					<!-- 도움말내용 -->
					<ul>
						<li>게시판을 등록 / 수정 / 삭제 하실 수 있습니다
					</ul>
				</td>
			<table>
		</td>
		</tr>
		</table>

		</td>
		</tr>
	</table>
	<!--내용이 들어가는 곳 끝-->



<!-- Copyright -->
<?include_once("../bottom.html");?>
