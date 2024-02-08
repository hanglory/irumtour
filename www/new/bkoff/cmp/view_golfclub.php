<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_golfclub";
$TITLE = "GOLF CLUB (API)";


$COUNTRY="";
$sql = "
		select distinct country from $table order by country asc
	";
$dbo->query($sql);
while($rs=$dbo->next_record()){
	$COUNTRY .=",".$rs[country];
}
$COUNTRY = substr($COUNTRY,1);


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
$filter = " and club_name<>''";

$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword){
	$filter .=" and $target like '%$keyword%' ";
	$best="";	 //배너 select 초기화
	$findMode=1;
}
if($country){ $filter .= " and country='$country' "; $find_bit=1;}
if($club_id=="undefined") $club_id="";
if($club_id){$filter .= " and club_id='$club_id' ";$find_bit=1;}

if($filter) $filter = " where " . substr($filter,5);

#query
$sql_1 = "select $column from $table $filter";			//자료수
$sql_2 = $sql_1 . " order by club_name asc limit  $start, $view_row";
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
$selectTxt = "Club Name,State,City,Club_id";
$selectValue ="club_name,state,city,club_id";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&golf_id_no=$golf_id_no";
$sessLink = "page=$page&" . $link;

?>
<?include("../top_min.html");?>
<script type="text/javascript">
function set_club(club_id,club_name){
	opener.document.getElementById('club_id').value=club_id;
	opener.document.getElementById('club_name').innerHTML=club_name;
	self.close();
}	
</script>

<div style="padding:0 10px 0 10px">

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
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
	<div style="padding:0 0 5px 0;">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
	<form name="fmSearch" method="get" action="<?=SELF?>">
	<input type="hidden" name='position' value="">
	<input type="hidden" name='ctg1' value="<?=$ctg1?>">


	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=number_format($total_page)?> page /  <?=number_format($page)?> page }<?}?></font></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>

    <select name="country">
    <?=option_str("Country".$COUNTRY,$COUNTRY,$country)?>
    </select>

	<select name="target" class='select'>
	<?=option_str($selectTxt,$selectValue,$target)?>
	</select>  

	<input class=box type="text" name="keyword" size="15" maxlength="40" value='<?=$keyword?>'>
	<input class="button" type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject">Country</th>
		<th class="subject">State</th>
		<th class="subject">City</th>
		<th class="subject">Club Name</th>
		<th class="subject">Holes</th>
		<th class="subject">Address</th>
		<!-- <th class="subject">Phone</th>
		<th class="subject">Website</th>
		<th class="subject">Email</th>
		<th class="subject">Driving Range</th>
		<th class="subject">Motor Cart</th>
		<th class="subject">Caddie Hire</th>
		<th class="subject"></th> -->
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td style="height:35px"><?=$rs[country]?></td>
	      <td><?=$rs[state]?></td>
		  <td><?=$rs[city]?></td>
	      <td class="l" style="padding-left:10px"><a href="view_golfclub.php?club_id=<?=$rs[club_id]?>"><?=$rs[club_name]?></a></td>
	      <td><?=$rs[number_of_holes]?></td>
	      <td class="l" style="padding-left:10px"><?=$rs[address]?></td>
	      <!-- <td><?=$rs[phone]?></td>
	      <td><?=$rs[website]?></td>
	      <td><?=$rs[email_address]?></td>
	      <td><?=$rs[driving_range]?></td>
	      <td><?=$rs[motor_cart]?></td>
	      <td><?=$rs[caddie_hire]?></td> -->
	      <td><span class="btn_pack medium bold"><a href="#" onClick="set_club('<?=$rs[club_id]?>','<?=$rs[club_name]?>')"> 선택 </a></span></td>
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