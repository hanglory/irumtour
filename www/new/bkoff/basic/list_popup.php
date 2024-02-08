<?
include_once("../include/common_file.php");





#### Menu
$filecode = substr(SELF,5,-4);
$TITLE = "팝업관리";
$MENU = "basic";



####각종 기초 정보 결정
$view_row=20;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보

$table = "popup";
$column = "*";
$basicLink = "";


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)


#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
$filter .=($CP_ID)? " and cp_id in ('','$CP_ID')" : " and cp_id=''";
if($keyword){
	$filter .=" and $target like '%$keyword%' ";
}

#query
$sql1 = "select * from $table where id_no>0 $filter";			//자료수
$sql2 = $sql1 . " order by id_no desc limit  $start, $view_row";
//checkVar($filter,$sql1);



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
$selectTxt = "제목,내용";
$selectValue ="title,content";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target";
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
		fm.action="view_popup.php";
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


	<!--내용이 들어가는 곳 시작-->

	<!-- Search Begin------------------------------------------------>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
	<form name=fmSearch method=post>
	<input type=hidden name='flag' value="<?=$flag?>">
	<input type=hidden name='bbs_id' value="<?=$bbs_id?>">
    <input type=hidden name=title_bar value="<?=$title_bar?>">
	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 { <?=number_format($total_page)?> page /  <?=number_format($page)?> page }</font></td>
	<td valign='bottom' align=right>
	<?if($keyword):?>
	<input class=button type="button" value="전체목록" onclick="location.href='?bbs_id=<?=$bbs_id?>'">
	<?endif;?>
	<select name="target" class='select'>
	<?=option_str($selectTxt,$selectValue,$target)?>
	</select>

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

        <tr><td colspan="8"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height=30 bgcolor="#F7F7F6">
		<td class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
		<td class="subject"><b>번호</b></td>
		<td class="subject" align=left><b>제목</b></td>
		<td class="subject"><b>활성화</b></td>
		<td class="subject"><b>등록일</b></td>
		<td class="subject"><b>확인</b></td>
		</tr>
		<tr><td colspan="8"  bgcolor='#E1E1E1'></td></tr>

<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql2);
if($debug) checkVar(mysql_error(),$sql2);
while($rs=$dbo->next_record()){
	$secretImg = ($rs[secret])? "<img src='../../images/board/secret.jpg' border=0> " : "";
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="30"><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
	      <td><?=$num?></td>
	      <td align="left"><a class=soft href="view_popup.php?id_no=<?=$rs[id_no];?>" onFocus='blur(this)'><?=$rs[title]?></a></td>
	      <td><?=($rs[open])?"활성화":"<font color=gray>비활성화</font>"?></td>
	      <td><?=$rs[reg_date]?></td>
	      <td><a href="#" onclick="window.open ('../../../renew/popup.php?id_no=<?=$rs[id_no]?>','','scrollbars=<?=$rs[scroll]?>,resizable=0,width=<?=$rs[width]?>,height=<?=$rs[height]?>,top=<?=number_format($rs[position_top])?>,left=<?=number_format($rs[position_left])?>')" class="soft">확인</a></td>
	    </tr>
        <tr><td colspan="8" class="tblLine"></td></tr>
<?
	$num--;
}
?>
		<tr><td colspan="8" height=1><?=$error[noData]?></td></tr>
        <tr><td colspan="8" bgcolor='#E1E1E1' height=3></td></tr>
        <tr>
    	  <td colspan="8">

    	  <br>
    	  <!-- Button Begin---------------------------------------------->
    			  <table width="130" border="0" cellspacing="0" cellpadding="0" align="right">
    				 <tr align="right">
    				  <td><span class="btn_pack medium bold"><a href="#" onClick="location.href='view_<?=$filecode?>.php'"> 등록 </a></span></td>
    				  <td><span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span></td>
    				</tr>
    			  </table>
    	  <!-- Button End------------------------------------------------>

    	  </td>
        </tr>

        <tr>
    	  <td colspan="8"  align=center>
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