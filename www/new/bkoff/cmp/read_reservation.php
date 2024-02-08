<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_reservation";
$MENU = "cmp_basic";
$TITLE = "고객예약정보";
if($id_no) $filter=" and id_no= $id_no";

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
	$filter .=" and $target like '%$keyword%' ";
	$best="";	 //배너 select 초기화
	$findMode=1;
}



#query
$sql_1 = "select $column from $table where id_no>0 $filter";			//자료수
$sql_2 = $sql_1 . " order by id_no desc limit  $start, $view_row";
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
$selectTxt = "대표자,경로,담당자,예약일,출국일,귀국일,골프장명";
$selectValue ="name,view_path,main_staff,tour_date,d_date,r_date,golf_name";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1";
$sessLink = "page=$page&" . $link;

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>:::EasePlus:::</title>
<link rel="stylesheet" href="../include/default.css">
<link rel="stylesheet" href="../include/basic.css" type="text/css">
<script language="JavaScript" src="../../include/form_check.js"></script>
<script language="JavaScript" src="../../include/function.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript">
<!--
$(function(){
	var width = screen.width-150;
	$("#tbl_wrap").css("width",width + "px");
});
//-->
</script>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="padding:10px">

	<table width="97%" border="0" cellspacing="0" cellpadding="0">
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

	<div style="height:500px; overflow:auto" id="tbl_wrap">
    <table border="0" cellspacing="0" cellpadding="3" width="3500" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >대표자명</th>
		<th class="subject" >경로</th>
		<th class="subject" >담당자</th>
		<th class="subject" >예약일</th>
		<th class="subject" >출국일</th>
		<th class="subject" >귀국일</th>
		<th class="subject" >총인원</th>
		<th class="subject" >골프장명</th>
		<th class="subject" >판매가</th>

		<th class="subject" >고객명</th>
		<th class="subject" >성별</th>
		<th class="subject" >영문명</th>
		<th class="subject" >주민번호</th>
		<th class="subject" >여권번호</th>
		<th class="subject" >연락처</th>
		<th class="subject" >메모</th>

		<th class="subject" >계약금</th>
		<th class="subject" >잔금</th>
		<th class="subject" >잔금입금일</th>
		<th class="subject" >입금확인</th>
		<th class="subject" >항공요금</th>
		<th class="subject" >지상비</th>
		<th class="subject" >고객환불</th>
		<th class="subject" >수익</th>
		<th class="subject" >결제정보</th>
		<th class="subject" >세금계산서</th>

		<th class="subject" >출국편명</th>
		<th class="subject" >귀국편명</th>
		<th class="subject" >비고</th>

		<th class="subject" >거래처</th>
		<th class="subject" >담당자</th>
		<th class="subject" >연락처</th>
		<th class="subject" >현지담당</th>
		<th class="subject" >비상연락처</th>
		<th class="subject" >공항미팅위치</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
checkVar(mysql_error(),$sql_2);

while($rs=$dbo->next_record()){

	$sql2 = $dbo2->query("select sum(price) as total ,count(bit) as cnt from cmp_people where bit=1 and code=$rs[code]");
	$rs2=$dbo2->next_record();
	$price = $rs2[total];
	$cnt = $rs2[cnt];
	$arr = explode("(",$rs[main_staff]);

	$dbo3->query("select * from cmp_golf where id_no=$rs[golf_id_no]");
	$rs3 = $dbo3->next_record();

	$k=0;
	$sql2 = $dbo2->query("select * from cmp_people where bit=1 and code=$rs[code]");
	while($rs2=$dbo2->next_record()){

		if($rs2[rn]){
		$aes = new AES($rs2[rn], $inputKey, $blockSize);
		$dec=$aes->decrypt();
		$rs2[rn] = $dec;
		}

		if($rs2[passport_no]){
		$aes = new AES($rs2[passport_no], $inputKey, $blockSize);
		$dec=$aes->decrypt();
		$rs2[passport_no] = $dec;
		}

?>
	    <tr align='center'>
			<?if(!$k){?>
			<td height="35" rowspan="<?=$cnt?>"><?=$rs[name]?></td>
			<td rowspan="<?=$cnt?>"><?=$rs[view_path]?></td>
			<td rowspan="<?=$cnt?>"><?=$arr[0]?></td>
			<td rowspan="<?=$cnt?>"><?=$rs[tour_date]?></td>
			<td rowspan="<?=$cnt?>"><?=$rs[d_date]?></td>
			<td rowspan="<?=$cnt?>"><?=$rs[r_date]?></td>
			<td rowspan="<?=$cnt?>"><?=nf($rs[people])?></td>
			<td rowspan="<?=$cnt?>"><?=$rs[golf_name]?></td>
			<?}?>
			<td><?=nf($rs2[price])?></td>
			<td><?=$rs2[name]?></td>
			<td><?=$rs2[sex]?></td>
			<td><?=$rs2[name_eng]?></td>
			<td><?=$rs2[rn]?></td>
			<td><?=$rs2[passport_no]?></td>
			<td><?=$rs2[phone]?></td>
			<td><?=$rs2[memo]?></td>

			<?if(!$k){?>
			<td rowspan="<?=$cnt?>"><?=nf($rs[price_prev])?></td>
			<td rowspan="<?=$cnt?>"><?=nf($rs[price_last])?></td>
			<td rowspan="<?=$cnt?>"><?=$rs[pay_date]?></td>
			<td rowspan="<?=$cnt?>"><?=$rs[pay_check]?></td>
			<?}?>
			<td><?=nf($rs2[price_air])?></td>
			<td><?=nf($rs2[price_land])?></td>
			<td><?=nf($rs2[price_refund])?></td>
			<td><?=nf(($rs2[price_prev]+$rs2[price_prev2]+$rs2[price_prev3])-($rs2[price_air]+$rs2[price_land]+$rs2[price_refund]))?></td>
			<?if(!$k){?>
			<td rowspan="<?=$cnt?>"><?=$rs[pay_method]?></td>
			<td rowspan="<?=$cnt?>"><?=$rs[bit_tax]?></td>
			<td rowspan="<?=$cnt?>"><?=$rs[d_air_no]?></td>
			<td rowspan="<?=$cnt?>"><?=$rs[r_air_no]?></td>
			<td rowspan="<?=$cnt?>"><?=$rs[bit_sending]?></td>

			<td rowspan="<?=$cnt?>"><?=$rs3[partner]?></td>
			<td rowspan="<?=$cnt?>"><?=$rs3[main_staff]?></td>
			<td rowspan="<?=$cnt?>"><?=$rs3[phone]?></td>
			<td rowspan="<?=$cnt?>"><?=$rs3[local_staff]?></td>
			<td rowspan="<?=$cnt?>"><?=$rs3[phone2]?></td>
			<td rowspan="<?=$cnt?>"><?=$rs3[meeting_place]?></td>
			<?}?>
	    </tr>
<?
		$k++;
	}
	$num--;
}
?>
	</table>
	</div>


    <table border="0" cellspacing="0" cellpadding="3" width="97%" id="tbl_list">
        <tr>
		  <td colspan="12">

		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
			 <tr>
			  <td width="60%" align="left">

			  </td>
			  <td align="right">
			  <?if(strstr($_SESSION["sessLogin"]["proof"],"엑셀다운로드")){?>
				<span class="btn_pack medium bold"><a href="list_reservation_excel.php?target=<?=$target?>&keyword=<?=$keyword?>"> 엑셀 다운로드 </a></span>&nbsp;
				<?}?>
				<span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
			  </td>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>

		  </td>
        </tr>


       	<?if(!$seq_mode){?>
		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
			<!-- navigation Begin---------------------------------------------->
			<?include_once('../../include/navigation.php')?>
			<?=$navi?>
			<!-- navigation End------------------------------------------------>
		  </td>
        </tr>
		<?}?>
	</form>
	</table>


</div>

	<!--내용이 들어가는 곳 끝-->

</body>
</html>