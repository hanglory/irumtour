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
$table = "ez_member";
$MENU = $filecode;
$TITLE = "회원관리";
$filter = "";
$column = "*";
$basicLink = "";


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
#검색조건
if($keyword){
	$keyword =trim($keyword);
	$filter.=" and $target like '%$keyword%' ";$findMode=1;
}

$sort = ($sort)? $sort : "id_no desc";

#query
$sql1 = "select $column from $table where id<>'' and cp_id='$CP_ID' $filter ";			//자료수
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
$selectTxt = "아이디,회원명,이메일,핸드폰,전화번호";
$selectValue ="id,name,email,cell,phone";



#### Link
$link = "keyword=$keyword&target=$target&sort=$sort";
$sessLink = "page=$page&" . $link;

//-------------------------------------------------------------------------------
?>
<?include("../top.html");?>
<script language="JavaScript">
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
</script>





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



    <br/>

	<!-- Search Begin------------------------------------------------>
	<table border=0  width=100% cellspacing="0" cellpadding="0">
	<form name="fmSearch" method="get" action="<?=SELF?>">
    	<tr height=22>
        	<td>
                <font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 { <?=number_format($total_page)?> page /  <?=number_format($page)?> page }</font>
            </td>
        	<td valign='bottom' align=right>
            	<?if($findMode):?>
            	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
            	<?endif;?>

            	<select name="target" class='select'>
            	<?=option_str($selectTxt,$selectValue,$target)?>
            	</select>

            	<span class="top"><input class="box" type="text" name="keyword" maxlength="40" value='<?=($keyword=="Iw==")? "#":$keyword;?>'></span>
            	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
        	</td>
    	<tr>
	</form>
	</table>
	<!-- Search End------------------------------------------------>



    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
       <form name="fmData" method="post">
       <input type="hidden" name="mode" value="drop">

        <tr><td colspan="9" bgcolor='#5E90AE' height="2"></td></tr>
	    <tr align=center height="30" bgcolor="#F7F7F6">
            <td><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
			<td class="subject">번호</td>
			<td class="subject">아이디</td>
			<td class="subject">회원명</td>
			<td class="subject">전화번호</td>
			<td class="subject">핸드폰</td>
			<td class="subject">가입일</td>
          </tr>
		<tr><td colspan="9" bgcolor='#E1E1E1'></td></tr>
		<tr><td colspan="9" bgcolor='#E1E1E1'></td></tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql2);
if($debug) checkVar(mysql_error(),$sql2);
while($rs=$dbo->next_record()){
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" class="lh">
	      <td><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"><input type="hidden" name="check2[<?=$rs[id_no]?>]" value="<?=$rs[id];?>"></td>
          <td align="center"><?=$num?></td>
          <td align="center"><?=user_id($rs[id])?></td>
          <td align="center"><a class=soft href="view_<?=$filecode?>.php?id_no=<?=$rs[id_no]?>&<?=$basicLink?>" onFocus="blur(this)" title="상세정보보기"><?=$rs[name]?></a></td>
          <td align="center"><?=$rs[phone]?></td>
          <td align="center"><?=$rs[cell]?></td>
          <td align="center"><?=substr($rs[reg_date],0,10)?></td>
	    </tr>
		<tr><td colspan="9" class='bar'></td></tr>
<?
	$num--;
}
?>
		<tr><td colspan="9" height=1><?=$error[noData]?></td></tr>
        <tr><td colspan="9"  bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan="9"  bgcolor='#FFFFFF' height=10></td></tr>
        <tr>
		  <td colspan="9">

			  <!-- Button Begin---------------------------------------------->
			  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
				 <tr>
				  <td>
				  <!-- <span class="btn_pack medium bold"><a href="#" onClick="email()"> 이메일발송 </a></span>&nbsp; -->
				  </td>
				  <td></td>
				  <td align="right"><!-- <span class="btn_pack medium bold"><a href="#" onClick="location.href='view_<?=$filecode?>.php'"> 등록 </a></span>&nbsp; -->
				  <span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span></td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>

		  </td>
        </tr>

        <tr>
	  <td colspan="9"  align=center>
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
