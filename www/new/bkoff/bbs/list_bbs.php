<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "bbs";
$TITLE = "게시판관리";


####각종 기초 정보 결정
$view_row=20;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;


$sql = "select * from ez_bbs_info where bid='$bid'";
$dbo->query($sql);
$rs = $dbo->next_record();
$bbs_title = $rs[subject];
$TITLE = $rs[subject];
$DOC_DIV = $rs[category];
$DOC_DIV_BIT=($DOC_DIV)?1:0;
$DOC_DIV=option_str($DOC_DIV,$DOC_DIV,$category);

#### 기본 정보
$table = "ez_bbs";
$table2 = "ez_bbs_comment";
$filter = " where bid = '$bid' and notice <> 1 and cp_id='$CP_ID'";
$filterNotice = " where bid = '$bid' and notice = 1 and cp_id='$CP_ID'";
$column = "*";
$basicLink = "bid=$bid&table=$table&table2=$table2&filter=$filter";


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)


#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword)	 $filter.=" and $target like '%$keyword%' ";
if($category)	 $filter.=" and category like '%$category%' ";
if($flag)		 $filter.=" and flag = '$flag' ";

//$filter = ($filter)? " where " . substr($filter,5) : "";



#query
$sql1 = "select $column from $table  $filter";			//자료수
$sql2 = $sql1 . " order by id_no desc limit  $start, $view_row";
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
$selectTxt = "제목,내용,글쓴이";
$selectValue ="subject,content,name";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&flag=$flag&bid=$bid";
$_SESSION["link"] =  $link;
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
    fm.mode.value="drop";

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
		fm.action="view_bbs.php?bid=<?=$bid?>";
		fm.submit();
	}
}

function bit_cp(){
    var j = 0;
    fm = document.fmBbs;
    fm.mode.value="bit_cp";

    for(var i = 1; i < fm.elements.length; i++){
        if(fm.elements[i].checked == 1){
            j++;
        }
    }
    if(j == 0){
        alert("변경할 자료를 선택하지 않으셨습니다.");
        return;
    }
    if(confirm("선택한 자료를 변경하시겠습니까?")){
        fm.action="view_bbs.php?bid=<?=$bid?>";
        fm.submit();
    }
}
//-->
</script>
<script language="JavaScript" src="../../include/function.js"></script>
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
	<table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
    	<form name="fmSearch" method="post" action="<?=SELF?>">
    	<input type=hidden name='flag' value="<?=$flag?>">
    	<input type=hidden name='bid' value="<?=$bid?>">
        	<tr height="22">
            	<td>
                    <font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 { <?=number_format($total_page)?> page /  <?=number_format($page)?> page }</font>
                	<?if($DOC_DIV_BIT != "0"){?>
                		<select class="select" onchange="location.href='?bid=<?=$bid?>&category='+this.value">
                			<option value="">:::분류:::</option>
                			<?=$DOC_DIV?>
                		</select>
                	<?}?>
            	</td>
            	<td valign='bottom' align="right">
                	<?if($keyword):?>
                	<input class=button type="button" value="전체목록" onclick="location.href='?bid=<?=$bid?>'">
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

        <tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>
	    <tr height="30">
		<td  class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
		<td  class="subject"><b>번호</b></td>
		<td  class="subject" align=left  width="40%"><b>제목</b></td>
		<td  class="subject"><b>의견</b></td>
		<td  class="subject"><b>글쓴이</b></td>
		<td  class="subject"><b>날짜</b></td>
		<td  class="subject"><b>조회</b></td>
		</tr>
		<tr><td colspan=8  bgcolor='#E1E1E1'></td></tr>
		<tr><td colspan=8  bgcolor='#E1E1E1'></td></tr>
<?
$j=1;
$dbo2->query("select * from $table $filterNotice order by reg_date desc");
while($rs = $dbo2->next_record()){
	$ico_secret = ($rs[secret])? "<img src='../../images/ez_board/ico_secret.jpg'  align='middle'> " : "";
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" class="lh">
	      <td><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
	      <td>[공지]</td>
	      <td align=left><?=($rs[category])?"[$rs[category]]":"";?>
			<a class=soft href="read_bbs.php?bid=<?=$bid?>&id_no=<?=$rs[id_no];?>" onFocus='blur(this)'><b><?=stripslashes($rs[subject])?></b> <?=$ico_secret?></a>
	      </td>
	      <td><?=number_format($rs[cnt_comment])?></td>
	      <td><?=$rs[name]?></td>
	      <td><?=date("Y.m.d",$rs[reg_date])?></td>
	      <td><?=number_format($rs[cnt])?></td>
	    </tr>
        <tr><td colspan="8" class='bar'></td></tr>
<?
	$j++;
}
?>


<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql2);
if($debug) checkVar(mysql_error(),$sql2);
while($rs=$dbo->next_record()){
	$ico_secret = ($rs[secret])? "<img src='../../../renew/images/ez_board/ico_secret.jpg'  align='middle'> " : "";
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" class="lh">
	      <td><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
	      <td><?=$num?></td>
	      <td align="left">
			<a class=soft href="read_bbs.php?bid=<?=$bid?>&id_no=<?=$rs[id_no];?>" onFocus='blur(this)'><?=($rs[category])?"[$rs[category]]":"";?> <?=stripslashes($rs[subject])?> <?=$ico_secret?></a>
            <?if(!$CP_ID && $rs[bit_cp]){?><span class="gray">[파트너 감춤]</span><?}?>
	      </td>
	      <td><?=number_format($rs[cnt_comment])?></td>
	      <td><?=$rs[name]?></td>
	      <td><?=date("Y.m.d",$rs[reg_date])?></td>
	      <td><?=number_format($rs[cnt])?></td>
	    </tr>
        <tr><td colspan="8" class='bar'></td></tr>
<?
	$num--;
}
?>
		<tr><td colspan=9 height=1><?=$error[noData]?></td></tr>
        <tr><td colspan=9  bgcolor='#E1E1E1' height=3></td></tr>
        <tr>  
    	  <td colspan=4>
            <?if(!$CP_ID){?>
                <select name="bit_cp" id="bit_cp">
                 <?=option_str("보이기,감추기","0,1")?>
                </select>
                <span class="btn_pack medium bold"><a href="#" onClick="bit_cp()"> 변경 </a></span>
            <?}?>
          </td>
          <td colspan=5>

    		<br>
    		  <!-- Button Begin---------------------------------------------->
                    <table width="130" border="0" cellspacing="0" cellpadding="0" align="right">
                        <tr align="right">
                            <td><span class="btn_pack medium bold"><a href="#" onClick="location.href='view_bbs.php?bid=<?=$bid?>'"> 등록 </a></span></td>
                            <td><span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span></td>
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



<!-- Copyright -->
<?include_once("../bottom.html");?>
