<?
include_once("../include/common_file.php");



#### Menu
$filecode = "secu";
$TITLE = "로그인 기록";
$MENU = "basic";
$table = "ez_bkoff_login";

switch($mode){

	case "drop":

		$sql = "delete from $table where id_no=$id_no " ;
		$dbo->query($sql);
		//checkVar(mysql_error(),$sql);exit;

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
	$filter.=" and (ip like '%$keyword%' or id='$keyword')";
	$findMode=1;
}

if($filter) $filter = " where " . substr($filter,5);

#query
$sql1 = "select $column from $table $filter";			//자료수
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
function drop(id_no){
	var url = "<?=SELF?>?mode=drop&id_no="+id_no;
	if(confirm('삭제하시겠습니까?')){
		location.href=url;
	}
}
//-->
</script>
<?include("../top.html");?>



	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?> (웹방화벽이 차단한 IP 해제)</td>
	  </tr>
	  <tr>
		<td> </td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	</table>

	<br/>

	<table border=0  width=100% cellspacing="0" cellpadding="0">
	<form name="fmSearch">
	<input type="hidden" name='flag' value="<?=$flag?>">
	<input type="hidden" name='assort' value="<?=$assort?>">
	<input type="hidden" name='assort2' value="<?=$assort2?>">

	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 { <?=number_format($total_page)?> page /  <?=number_format($page)?> page }</font></td>
	<td valign='bottom' align=right>
	<?if($findMode):?>
	<span class="btn_pack medium"><a href="<?=SELF?>"> 전체목록 </a></span>
	<?endif;?>

	<span class="top"><input class="box" type="text" name="keyword" maxlength="40" value='<?=($keyword=="Iw==")? "#":$keyword;?>'" placeholder="IP"></span>
	<span class="btn_pack medium"><a href="#" onClick="document.fmSearch.submit()"> 검색 </a></span>
	</td>
	<tr>
	</form>
	</table>

	<!--내용이 들어가는 곳 시작-->

	<br/>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
       <form name=fmBbs>

        <tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<td class="subject" width="50"><b>번호</b></td>
		<td class="subject"><b>ID</b></td>
		<td class="subject"><b>IP</b></td>
		<td class="subject"><b>성공여부</b></td>
		<td class="subject"><b>날짜</b></td>
		<td class="subject"><b>삭제</b></td>
		</tr>
		<tr><td colspan=8  bgcolor='#E1E1E1'></td></tr>

<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql2);
//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql2);}
while($rs=$dbo->next_record()){
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><?=$num?></td>
	      <td><?=$rs[id]?></td>
	      <td><?=$rs[ip]?></td>
	      <td><?=$rs[reg_date]?> <?=$rs[reg_date2]?></td>
	      <td><?=$rs[bit]?></td>
	      <td><span class="btn_pack medium bold"><a href="javascript:drop('<?=$rs[id_no]?>')"> 삭제 </a></span></td>
	    </tr>
        <tr><td colspan=8  bgcolor='#CCCCFF' height="1"></td></tr>
<?
	$num--;
}
?>
		<tr><td colspan=9 height=1><?=$error[noData]?></td></tr>
        <tr><td colspan=9  bgcolor='#E1E1E1' height=3></td></tr>


        <tr>
		  <td colspan=8  align=center>
		  	<br/>
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