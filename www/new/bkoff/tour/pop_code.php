<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_golf";
$TITLE = "고객관리 상품 목록";

####각종 기초 정보 결정
$view_row=15;	//한 페이지에 보여줄 행 수를 결정

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
$keyword = trim($keyword);
if($keyword){
	$filter .=" and $target like '%$keyword%' ";
	$best="";	 //배너 select 초기화
	$findMode=1;
}

if($nation) $filter.=" and nation='$nation'";



#query
$sql_1 = "select $column from $table where id_no>0 $filter";			//자료수
$sql_2 = $sql_1 . " order by name asc,nation asc, city asc, id_no desc  limit  $start, $view_row";
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
$selectTxt = "상품명,국가,공항지역,지역,거래처,담당자";
$selectValue ="name,nation,air_city,city,partner,main_staff";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1";
$sessLink = "page=$page&" . $link;

?>
<?include("../top_min.html");?>
<script type="text/javascript">
<!--
function get_code(code,subject){
	opener.document.fmData.goods_code.value=code;
	opener.document.fmData.goods_name.value=subject;
	opener.document.getElementById('goods_name_tmp').innerHTML=subject;
	self.close();
}
//-->
</script>


	<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
	  <tr>
		<td class="title_con" style="padding-left:20px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?>

		<div style="float:right">
		<span class="btn_pack medium bold"><a href="javascript:self.close()"> 창닫기 </a></span>
		</div>
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

	<br>

	<!-- Search Begin------------------------------------------------>
	<div style="padding:0 0 5px 0">
    <table border="0" cellspacing="1" cellpadding="3" width="95%" align="center">
	<form name=fmSearch method="get">
	<input type=hidden name='position' value="">
	<input type=hidden name='ctg1' value="<?=$ctg1?>">


	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=number_format($total_page)?> page /  <?=number_format($page)?> page }<?}?></font></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>


	 <select name="nation">
	 <option value="">전체보기</optioni>
	 <?=option_str($NATIONS,$NATIONS,$nation)?>
	 </select>


	<select name="target" class='select'>
	<?=option_str($selectTxt,$selectValue,$target)?>
	</select>

	<input class=box type="text" name="keyword" size="30" maxlength="40" value='<?=$keyword?>'>
	<span class="btn_pack medium bold"><a href="javascript:document.fmSearch.submit()"> 검색 </a></span>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>


    <table border="0" cellspacing="1" cellpadding="3" width="95%" align="center">

		<tr><td colspan="10" class="tblLine"></td></tr>


	    <tr>
		<td class="subject c" >국가</td>
		<td class="subject c" >지역</td>
		<td class="subject c" >상품명</td>
		<td class="subject c" >거래처</td>
		<td class="subject c" ></td>
		</tr>
		<tr><td colspan="10" class="tblLine"></td></tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	$rs[name] = str_replace("'","",$rs[name]);
	$rs[name] = str_replace("\"","",$rs[name]);
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><?=$rs[nation]?></td>
	      <td><?=$rs[city]?></td>
	      <td align="left"><?=$rs[name]?></td>
	      <td><?=$rs[partner]?></td>
	      <td><span class="btn_pack medium bold"><a href="javascript:get_code(<?=$rs[id_no]?>,'<?=$rs[name]?>')"> 선택 </a></span></td>
	    </tr>
		<tr><td colspan="10" class="tblLine"></td></tr>
<?
	$num--;
}
?>
		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
			<!-- navigation Begin---------------------------------------------->
			<?include_once('../../include/navigation.php')?>
			<?=$navi?>
			<!-- navigation End------------------------------------------------>
		  </td>
        </tr>
	</table>





	<!--내용이 들어가는 곳 끝-->

</body>
</html>