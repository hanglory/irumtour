<?
include_once("../include/common_file.php");



#### Menu
$MENU = "bbs";



#### 코멘트 지우기
if($mode=="drop"){
	for($i = 0; $i < count($check);$i++){
		$n =  $check[$i];
		$sql = "delete from bbs_comment where no = $check[$i]";
		$dbo->query($sql);
		$sql = "update bbs set comment=comment-1 where no = $check2[$n]";
		$dbo->query($sql);
	}
	redirect2("list_comment.php"); exit;
}

####각종 기초 정보 결정
$view_row=50;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
/*
$title_bar = "뉴스";
$basicFileName = "boardA1";
$bbs_id = "qna";
*/
$table = "bbs_comment";
$table2 = "bbs_comment";

$filterNotice = " where assort = '$bbs_id' and notice = 1";
$column = "*";
$basicLink = "title_bar=$title_bar&basicFileName=$basicFileName&bbs_id=$bbs_id&table=$table&table2=$table2&filter=$filter";


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)


#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword)	 $filter.=" and $target like '%$keyword%' ";
if($flag)		 $filter.=" and flag = '$flag' ";

//$filter = ($filter)? " where " . substr($filter,5) : "";

#query
$sql1 = "select $column from $table2  $filter";			//자료수
$sql2 = "select $column from $table2  $filter  order by no desc  limit  $start, $view_row";

//checkVar("",$sql1);

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
$selectTxt = "제목,내용,글쓴이,카테고리";
$selectValue ="title,content,name,category";



#### Link
$keyword2 = base64_encode($keyword);
$link = "bbs_id=$bbs_id&keyword=$keyword&target=$target&flag=$flag&title_bar=$title_bar&keyword2=$keyword2";
$sessLink = "page=$page&" . $link;


//-------------------------------------------------------------------------------
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../include/basic.css" type="text/css">
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
		fm.submit();
	}
}
//-->
</script>
</head>

<body text="#000000" bgcolor="FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?include("../top.html");?>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width=178 valign=left>
	<?include("../bbs_left.html");?>
	</td>
    <td valign=top>

	<!--내용이 들어가는 곳 시작-->
<table width="750" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><img src="../images/common/box01.gif" width="750" height="12"></td>
        </tr>
        <tr>
          <td background="../images/common/box03.gif">
            <table width="710" border="0" cellspacing="0" cellpadding="0" align="center">
              <tr>
                <td><img src="../images/menu/box01.gif" width="13" height="13" align="absmiddle">
                  <font color="#FF6600">코멘트관리</font> </td>
              </tr>
              <tr>
                <td height=7><img src="../images/common/dot_line.gif" width="710" height="7"></td>
              </tr>
              <tr>
                <td>
                  <p>게시판 자료를 관리할 수 있습니다.</p>
                  </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td><img src="../images/common/box02.gif" width="750" height="13"></td>
        </tr>
      </table>
      <br>

	<!-- Search Begin------------------------------------------------>
	<table border=0  width=750 cellspacing="0" cellpadding="0">
	<form name=fmSearch method=post>
	<input type=hidden name='flag' value="<?=$flag?>">
	<input type=hidden name='bbs_id' value="<?=$bbs_id?>">
    <input type=hidden name=title_bar value="<?=$title_bar?>">
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



       <table border="0" cellspacing="1" cellpadding="3" width="750">
       <form name=fmBbs>
       <input type=hidden name=mode value='drop'>
       <input type=hidden name=bbs_id value="<?=$bbs_id?>">
       <input type=hidden name=basicFileName value="<?=$basicFileName?>">
       <input type=hidden name=table value="<?=$table?>">
	   <input type=hidden name=title_bar value="<?=$title_bar?>">

        <tr><td colspan=8  bgcolor='#408080'></td></tr>
	  <tr align=center height=25>
            <td><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
			<td><b>번호</b></td>
			<td align=left><b>내용</b></td>
			<td><b>이름</b></td>
			<td><b>ID</b></td>
			<td><b>PWD</b></td>
			<td><b>IP</b></td>
			<td><b>날짜</b></td>
          </tr>
          <tr><td colspan=8  bgcolor='#CCCCFF'></td></tr>
          <tr><td colspan=8  bgcolor='#CCCCFF'></td></tr>


<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql2);
while($rs=$dbo->next_record()){
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td>
			<input type="checkbox" name="check[]" value="<?=$rs[no];?>">
			<input type="hidden" name="check2[<?=$rs[no];?>]" value="<?=$rs[id_no];?>">
		  </td>
	      <td><?=$num?></td>
	      <td align=left><a class=soft href="view_bbs.php?n=<?=$rs[id_no];?>&<?=$basicLink?>" onFocus='blur(this)'><b><?=titleCut(strip_tags($rs[comment]),40)?></b></a>
	      </td>
	      <td><?=$rs[name]?></td>
	      <td><?=$rs[id]?></td>
		  <td><?=$rs[pwd]?></td>
		  <td><?=$rs[ip]?></td>
	      <td><?=date("Y.m.d H:i",$rs[reg_date])?></td>
	    </tr>
        <tr><td colspan=8  bgcolor='#CCCCFF'></td></tr>
<?
	$num--;
}
?>
		<tr><td colspan=9 height=1><?=$error[noData]?></td></tr>
        <tr><td colspan=9  bgcolor='#408080'></td></tr>
        <tr>
	  <td colspan=9>

		  <!-- Button Begin---------------------------------------------->
                  <table width="60" border="0" cellspacing="0" cellpadding="0" align="right">
		    <tr>
                      <td width = 60><img src="../images/button/drop.gif" width="50" height="28" border=0 style='cursor:hand' onClick="del()"></td>

                    </tr>
                  </table>
		  <!-- Button End------------------------------------------------>
	  </td>
        </tr>

        <tr>
	  <td colspan=8  align=center>
		<!-- navigation Begin---------------------------------------------->
		<?include_once('../../include/navigation.php')?>
		<?=$navi?>
		<!-- navigation End------------------------------------------------>
	  </td>
        </tr>
	</form>
	</table>


	</td>
  </tr>
</table>


		<!-- Copyright -->
		<?include_once("../copyright.html");?>

</body>
</html>