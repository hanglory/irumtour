<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_air";
$TITLE = "항공정보 선택";


####각종 기초 정보 결정
$view_row=10;	//한 페이지에 보여줄 행 수를 결정

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
	$filter .=" and $target like '%$keyword%' ";
	$best="";	 //배너 select 초기화
	$findMode=1;
}

if($golf_id_no){
	$sql = "select * from cmp_golf where id_no=$golf_id_no";
	$dbo->query($sql);
	$rs=$dbo->next_record();

	$filter .= " and (nation='$rs[nation]' and city like '%$rs[city]%')";
}

if($filter) $filter = " where " . substr($filter,5);

#query
$sql_1 = "select $column from $table $filter";			//자료수
$sql_2 = $sql_1 . " order by nation asc, city asc, d_time_s asc limit  $start, $view_row";
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
$selectTxt = "도시,국내공항,항공사명,출발항공편,도착항공편";
$selectValue ="city,airport_in,d_air,d_air_no,r_air_no";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&golf_id_no=$golf_id_no";
$sessLink = "page=$page&" . $link;

?>
<?include("../top_min.html");?>
<script type="text/javascript">
<!--

//'d','<?=$rs[id_no]?>','<?=$rs[d_air_no]?>','<?=$rs[d_time_s]?>','<?=$rs[d_time_e]?>'
function set_air(bit,id_no,air_no,air_time1,air_time2){
	var str="";

	if(bit=="d"){
		opener.$("#air_id_no").val(id_no);
		opener.$("#d_air_no").val(air_no);
		opener.$("#d_air_time1").val(air_time1);
		opener.$("#d_air_time2").val(air_time2);

		str ="출국편명 : " + d_air_no;
		str +=" (출발시간 : " + air_time1 ;
		str +="  도착시간 : " + air_time2 +")";
		opener.document.getElementById('air_info').innerHTML = str;

	}else{
		opener.$("#air_id_no").val(id_no);
		opener.$("#r_air_no").val(air_no);
		opener.$("#r_air_time1").val(air_time1);
		opener.$("#r_air_time2").val(air_time2);

		str ="귀국편명 : " + d_air_no;
		str +=" (출발시간 : " + air_time1 ;
		str +="  도착시간 : " + air_time2 +")";
		opener.document.getElementById('air_info2').innerHTML = str;
	}


	self.close();
}

//-->
</script>
<script language="JavaScript">

</script>

<div style="padding:0 10px 0 10px">

	<table width="97%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con" style="padding-left:10px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
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
    <table border="0" cellspacing="0" cellpadding="3" width="97%" id="tbl_list">
	<form name=fmSearch method="get">
	<input type=hidden name='position' value="">
	<input type=hidden name='ctg1' value="<?=$ctg1?>">


	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=number_format($total_page)?> page /  <?=number_format($page)?> page }<?}?></font></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>

	<select name="target" class='select'>
	<?=option_str($selectTxt,$selectValue,$target)?>
	</select>
	</td>
	<td align=right width=150 valign=top>
	<input class=box type="text" name="keyword" size="15" maxlength="40" value='<?=$keyword?>'>
	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>

    <table border="0" cellspacing="0" cellpadding="3" width="97%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" rowspan="2">국가</th>
		<th class="subject" rowspan="2">도시</th>
		<th class="subject" rowspan="2">국내공항</th>
		<th class="subject" rowspan="2">항공사명</th>
		<th class="subject" colspan="4">출발</th>
		<th class="subject" rowspan="2">출발선택</th>
		<th class="subject" colspan="4">귀국</th>
		<th class="subject" rowspan="2">귀국선택</th>
		</tr>
	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >항공편</th>
		<th class="subject" >출발시간</th>
		<th class="subject" >도착시간</th>
		<th class="subject" >출발요일</th>
		<th class="subject" >항공편</th>
		<th class="subject" >출발시간</th>
		<th class="subject" >도착시간</th>
		<th class="subject" >출발요일</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td style="height:35px"><?=$rs[nation]?></td>
	      <td><?=$rs[city]?></td>
	      <td><?=$rs[airport_in]?></td>
		  <td><?=$rs[d_air]?></td>
	      <td><?=$rs[d_air_no]?></td>
	      <td><?=$rs[d_time_s]?></td>
	      <td><?=$rs[d_time_e]?></td>
	      <td><?=$rs[d_wday]?></td>
	      <td><span class="btn_pack medium bold"><a href="#" onClick="set_air('d','<?=$rs[id_no]?>','<?=$rs[d_air_no]?>','<?=$rs[d_time_s]?>','<?=$rs[d_time_e]?>')"> 선택 </a></span></td>
	      <td><?=$rs[r_air_no]?></td>
	      <td><?=$rs[r_time_s]?></td>
	      <td><?=$rs[r_time_e]?></td>
	      <td><?=$rs[r_wday]?></td>
	      <td><span class="btn_pack medium bold"><a href="#" onClick="set_air('r','<?=$rs[id_no]?>','<?=$rs[r_air_no]?>','<?=$rs[r_time_s]?>','<?=$rs[r_time_e]?>')"> 선택 </a></span></td>
	    </tr>
<?
	$num--;
}

?>
	</table>


	<table width="97%">
		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
			<!-- navigation Begin---------------------------------------------->
			<?include_once('../../include/navigation.php')?>
			<?=$navi?>
			<!-- navigation End------------------------------------------------>
		  </td>
        </tr>
        </table>


		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table width="97%" border="0" cellspacing="0" cellpadding="0" align="right">
			 <tr>
			  <td width="60%" align="left">

			  </td>
			  <td align="right" style="padding-right:23px">
				<span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
			  </td>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>



</div>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>