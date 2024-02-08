<?
include_once("../include/common_file.php");

//chk_power($_SESSION["sessLogin"]["proof"],"고객별예약정보관리대장");

####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_basic";
$LEFT_HIDDEN="1";
$TITLE = "고객별 히스토리";

?>
	<?include("../top.html");?>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?>

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
	<br/>

	<!-- Search Begin------------------------------------------------>
	<div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
	<form name=fmSearch method="get">
	<input type=hidden name='position' value="">
	<input type=hidden name='ctg1' value="<?=$ctg1?>">


	<tr height=22>
	<td></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>

	고객명(핸드폰/전화번호/상품명):
	</td>
	<td align=right width=180 valign=top>
	<input class=box type="text" name="keyword" size="20" maxlength="40" value='<?=$keyword?>'>
	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>


	<h3>1. 문의현황</h3>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >문의일자</th>
		<th class="subject" >이름</th>
		<th class="subject" >연락처</th>
		<th class="subject" >상담내역</th>
		<th class="subject" >담당자</th>
		</tr>
<?
$name = trim($keyword);
$name2 = rnf($name);
if($name2) $filter  = "or replace(phone,'-','')='$name2' or replace(cell,'-','')='$name2' ";
$sql = "select * from cmp_qna where name='$name' $filter order by id_no desc";
$dbo->query($sql);
//checkVar(mysql_error(),$sql);
while($rs=$dbo->next_record()){

?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><?=$rs[reg_date]?></td>
		  <td height="25"><a href="javascript:newWin('view_qna.php?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation')"><?=$rs[name]?></a></td>
	      <td><?=$rs[phone]?></td>
	      <td><?=$rs[content]?></td>
	      <td><?=$rs[main_staff]?></td>
	    </tr>
<?
	$num--;
}
?>
	</table>

	<br/>
	<br/>

	<h3>2. 견적현황</h3>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >발송일</th>
		<th class="subject" >상품명</th>
		<th class="subject" >인원</th>
		<th class="subject" >담당자</th>
		<th class="subject" >판매가</th>
		<th class="subject" >관리자메모</th>
		</tr>
<?
$name = trim($keyword);
if($name) $filter ="name='$name' or golf_name like '%$name%' ";
if($name2) $filter  .= " or replace(phone,'-','')='$name2' or golf_name like '%$name2%'";
$sql = "select * from cmp_estimate where $filter order by id_no desc";
$dbo->query($sql);
//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql);
while($rs=$dbo->next_record()){

?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="25"><a href="javascript:newWin('view_estimate.php?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation')"><?=$rs[send_date]?></a></td>
	      <td><?=$rs[golf_name]?></td>
	      <td><?=$rs[people]?></td>
	      <td><?=$rs[staff]?></td>
	      <td><?=nf($rs[price])?></td>
	      <td><?=$rs[memo]?></td>
	    </tr>
<?
	$num--;
}
?>
	</table>

	<br/>
	<br/>

	<h3>3. 예약현황</h3>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >대표자</th>
		<th class="subject" >예약일</th>
		<th class="subject" >출국일</th>
		<th class="subject" >상품명</th>
		<th class="subject" >인원</th>
		<th class="subject" width="10%">일행</th>
		<th class="subject" >항공정보</th>
		<th class="subject" >판매가</th>
		<th class="subject" width="20%">관리자메모</th>
		</tr>
<?
$name = trim($keyword);
$filter = "";
if($name){
	$filter = "
		a.name='$name'
		or b.name='$name'
		or a.golf_name like '%$name%'
	";
}
if($name2) $filter  .= " or replace(a.phone,'-','')='$name2' or replace(b.phone,'-','')='$name2' ";
$sql = "
	select
		a.* from
		cmp_reservation as a left join cmp_people as b
		on a.code=b.code
		where
		$filter
		group by a.code
		order by a.id_no desc
";
$dbo->query($sql);
//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql);
while($rs=$dbo->next_record()){
	$sql2 = "select * from cmp_people where code='$rs[code]' order by id_no asc ";
	$dbo2->query($sql2);
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="25"><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation')"><?=$rs[name]?></a></td>
	      <td><?=$rs[tour_date]?></td>
	      <td><?=$rs[d_date]?></td>
	      <td><?=$rs[golf]?></td>
	      <td><?=$rs[people]?></td>
	      <td>
	      	<?while($rs2=$dbo2->next_record()){?>
	      		<p><a href="<?=SELF?>?keyword=<?=$rs2[name]?>"><?=$rs2[name]?></a></p>
	      	<?}?>
	      </td>
	      <td><span id="air_info"><?if($rs[d_air_no] || $rs[r_date_no]){?>출국편명:<?=$rs[d_air_no]?>,귀국편명:<?=$rs[r_air_no]?><?}?></span> </td>
	      <td><?=nf($rs[price])?></td>
	      <td><?=$rs[memo]?></td>
	    </tr>
<?
	$num--;
}
?>
	</table>


	<br/>
	<br/>

	<h3>4. 고객정보</h3>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >대표자</th>
		<th class="subject" >고객명</th>
		<th class="subject" >성별</th>
		<th class="subject" >영문명</th>
		<th class="subject" >주민번호</th>
		<th class="subject" >여권번호</th>
		<th class="subject" >유효기간</th>
		<th class="subject" >연락처</th>
		</tr>
<?
$name = trim($keyword);
$filter = "";

if($name) $filter  = " name='$name' ";
if($name2) $filter  .= "or replace(phone,'-','')='$name2' ";
$sql = "select * from cmp_customer where $filter order by id_no desc";
$dbo->query($sql);
//checkVar(mysql_error(),$sql);
while($rs=$dbo->next_record()){
	if($rs[rn]){
	$aes = new AES($rs[rn], $inputKey, $blockSize);
	$dec=$aes->decrypt();
	$rs[rn] = $dec;
	}
	if($rs[passport_no]){
	$aes = new AES($rs[passport_no], $inputKey, $blockSize);
	$dec=$aes->decrypt();
	$rs[passport_no] = $dec;
	}
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><?=($rs[leader])?$rs[leader]:"<font color='gray'>미등록</font>"?></td>
		  <td><a href="javascript:newWin('view_customer.php?id_no=<?=$rs[id_no]?>&ctg1=<?=$ctg1?>',870,400,1,1,'golf')"><?=$rs[name]?></a></td>
	      <td><?=$rs[sex]?></td>
	      <td><?=$rs[name_eng]?></td>
	      <td><?=$rs[rn]?></td>
	      <td><?=$rs[passport_no]?></td>
	      <td><?=$rs[passport_limit]?></td>
	      <td><?=$rs[phone]?></td>
	    </tr>
<?
	$num--;
}
?>
	</table>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
