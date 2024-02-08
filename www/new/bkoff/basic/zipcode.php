<?
session_start();
session_register("sessLink");


#### Include
include_once('../../include/fun_basic.php');
include_once('../../include/fun_bbs.php');
include_once('../include/proof.php');


#### DB Connent
include_once ("../../include/info_dbconn.php");
include_once ("../../lib/class.$database.php");

$dbo = new MiniDB($info);
$dbo2 = new MiniDB($info);



#### Menu
$filecode = "zipcode";
$TITLE = "우편번호";
$MENU = "basic";
$table = "zipcode";



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
}

if($filter) $filter = " where " . substr($filter,5);

#query
$sql1 = "select $column from $table $filter";			//자료수
$sql2 = $sql1 . " order by id_no asc limit  $start, $view_row";
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
		fm.action="view_banner.php";
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


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
       <form name="fmBbs">
       <input type=hidden name=mode value="drop">

        <tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>
	    <tr bgcolor="#F7F7F6"'>
		<td class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
		<td class="subject"><b>우편번호</b></td>
		<td class="subject"><b>시</b></td>
		<td class="subject"><b>도</b></td>
		</tr>
		<tr><td colspan=8  bgcolor='#E1E1E1'></td></tr>

<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql2);
while($rs=$dbo->next_record()){
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" class="lh">
	      <td><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
	      <td><?=$num?></td>
	      <td align="left"><a class=soft href="view_banner.php?id_no=<?=$rs[id_no];?>" onFocus='blur(this)'><?=$rs[zipcode]?></a></td>
	      <td><?=$rs[si]?></td>
	      <td><?=$rs[si]?></td>
	    </tr>
		<tr><td colspan="8" class='bar'></td></tr>
<?
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
				  <td><span class="btn_pack medium bold"><a href="#" onClick="location.href='view_<?=$filecode?>.php?assort=<?=$assort?>'"> 등록 </a></span></td>
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





	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>