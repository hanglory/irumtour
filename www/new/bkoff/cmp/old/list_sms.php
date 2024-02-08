<?
include_once("../include/common_file.php");
include_once "../../SMS/xmlrpc.inc.php";
include_once "../../SMS/class.EmmaSMS.php";


 chk_power($_SESSION["sessLogin"]["proof"],"문자발송");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_reservation";
$MENU = "cmp_basic";
$LEFT_HIDDEN="1";
$TITLE = "문자발송";
$today  =date("Y/m/d");
$next_date = date("Y/m/d",strtotime(date("Y/m/d")." +1 month"));
if($ctg1=="1"){
	$TITLE.=" > 잔금 안내 문자";
	$sort = "pay_date desc, id_no desc";
	$filter = " and pay_date >= '$today' ";
	//$target_date = "pay_date";
	$SMS_TEXT = $SMS_TEXT1;
	$find_bit=1;
}
if($ctg1=="2"){
	$TITLE.=" > 출발 안내 문자";
	$sort = "d_date desc";
	$filter = " and d_date >= '$today' ";
	//$target_date = "d_date, id_no desc";
	$SMS_TEXT = $SMS_TEXT2;
	$find_bit=1;
}else{
	$filter = " and d_date >= '$today' and d_date <= '$next_date' ";
}



if($mode=="sms"){

	$sms_id = "irumplace";
	$sms_passwd = "ccn121422!";
	$sms_from = $OFFICE_TEL;
	$sms_type = "L";
	$sms = new EmmaSMS();
	$sms->login($sms_id, $sms_passwd);


	$help = "문의($OFFICE_TEL)";
	while(list($key,$val)=each($check)){

		$sql = "select * from cmp_reservation where id_no=$val";
		$dbo->query($sql);
		$rs=$dbo->next_record();
		$golf_name = explode(">",$rs[golf_name]);
		$staff = explode("(",$rs[main_staff]);
		$message = $sms_text;
		$message = str_replace("{고객명}",$rs[name],$message);
		$message = str_replace("{담당자명}",$staff[0],$message);
		$customer = $rs[name];

		$arr = explode(">",$rs[golf_name]);
		$nation = trim($arr[0]);
		$city = trim($arr[1]);
	    $sms_to = rnf($rs[phone]);
		$arr_d = explode("/",$rs[d_date]);
		$month = ceil($arr_d[1]);

		if($ctg1==2){

			$sql2 = "select * from cmp_prepare where nation='$nation' order by id_no desc limit 1";
			$dbo2->query($sql2);
			//checkVar("",$sql2);
			$rs2= $dbo2->next_record();
			$prepare= $rs2[content];

			$sql2 = "select month${month} as temp from cmp_temperature where nation='$nation' and city='$city' order by id_no desc limit 1";
			$dbo2->query($sql2);
			//checkVar("",$sql2);
			$rs2= $dbo2->next_record();
			$temperature = $rs2[temp];

			$sql2 = "select * from cmp_air where id_no=$rs[air_id_no]";
			$dbo2->query($sql2);
			//checkVar("",$sql2);
			$rs2=$dbo2->next_record();
			$airport_in=$rs2[airport_in];
			$airport_counter= $rs2[airport_counter];
			$d_air_no= $rs2[d_air_no];
			$d_time_s=$rs2[d_time_s];
			$r_air_no=$rs2[r_air_no];
			$r_time_s=$rs2[r_time_s];

			$sql2 = "select * from cmp_cargo where air='$rs2[d_air]' order by id_no desc limit 1";
			$dbo2->query($sql2);
			//checkVar("",$sql2);
			$rs2= $dbo2->next_record();
			$cargo1 = $rs2[content1];
			$cargo1 .= $rs2[content2];
			$cargo1 = trim($cargo1);

			$sql3 = "select * from cmp_golf where id_no=$rs[golf_id_no]";
			$dbo3->query($sql3);
			//checkVar("",$sql3);
			$rs3=$dbo3->next_record();
			$meeting_place = $rs3[meeting_place];
			$meeting_board = $rs3[meeting_board];
			$local_staff = $rs3[local_staff];
			$phone2 = $rs3[phone2];


			$str = "출발 $rs[d_date] $d_air_no $d_time_s";
			//$str .= ",도착 $rs[r_date] $r_air_no $r_time_s";
			$str .= ",도착 $rs[r_date]";

			$str2 = "$airport_in $airport_counter, $rs2[airport_out] $rs3[meeting_place]";
			$str2 = (trim($str2)==",")? $help :$str2;

			$prepare = ($prepare)?$prepare : $help;
			$temperature = ($temperature)? $temperature : $help;
			$cargo2 = ($cargo2)? $cargo2 : $help;

			$message = str_replace("{일정}",$str,$message);

			$message = str_replace("{국내공항}",$airport_in,$message);
			$message = str_replace("{체크인 카운터}",$airport_counter,$message);
			$message = str_replace("{미팅위치}",$meeting_place,$message);
			$message = str_replace("{미팅보드}","\"이룸투어+${customer}\"",$message);
			$message = str_replace("{현지담당}",$local_staff,$message);
			$message = str_replace("{비상연락처}",$phone2,$message);

			$message = str_replace("{수화물안내}",$cargo1,$message);
			$message = str_replace("{준비물}",$prepare,$message);
			$message = str_replace("{날씨}",$temperature,$message);

		}else{
			$price_last = nf($rs[price_last] / $rs[people]);
			$message = str_replace("{골프장명}",$golf_name[2],$message);
			$message = str_replace("{잔금액}",$price_last,$message);
			$message = str_replace("{잔금일}",$rs[pay_date],$message);
		}

		//checkVar($sms_to,$message);
		$ret = $sms->send($sms_to, $sms_from, $message, $sms_date, $sms_type);

		$sql2 = "select * from cmp_people where code=$rs[code] and phone<>''";
		$dbo2->query($sql2);
		//checkVar("sql2",$sql2);

		while($rs2=$dbo2->next_record()){
			$sms_to = rnf($rs2[phone]);
			//checkVar($sms_to,$message);
			$ret = $sms->send($sms_to, $sms_from, $message, $sms_date, $sms_type);
		}


	}
	error("발송하였습니다.");
	exit;
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
	$filter .=" and $target like '%$keyword%' ";
	$best="";	 //배너 select 초기화
	$findMode=1;
}

if($pay_date_s){
	$filter .= " and $target_date >= '$pay_date_s' ";
	$findMode=1;
}
if($pay_date_e){
	$filter .= " and $target_date <= '$pay_date_e' ";
	$findMode=1;
}


#query
$sort = ($sort)?$sort : "id_no desc";
$sql_1 = "select $column from $table where id_no>0 $filter";			//자료수
$sql_2 = $sql_1 . " order by $sort";
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

$rs[target_date] =($rs[target_date])? $rs[target_date] : $target_date;
$rs[pay_date_s] = $pay_date_s;
$rs[pay_date_e] = $pay_date_e;


?>
<?include("../top.html");?>
<script language="JavaScript">
<!--
function selectAll(){
	fm = document.fmData;
	for(var i = 1; i < fm.elements.length; i++){
		fm.elements[i].checked = (fm.checkAll.checked == 1)? 1 : 0;
	}
}

function sms(){
	var j = 0;
	fm = document.fmData;

	for(var i = 1; i < fm.elements.length; i++){
		if(fm.elements[i].checked == 1){
			j++;
		}
	}
	if(j == 0){
		alert("발송 대상을 선택하지 않으셨습니다.");
		return;
	}
	if(confirm("문자를 발송하시겠습니까??")){
		fm.submit();
	}
}
//-->
</script>


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

	<!-- Search Begin------------------------------------------------>
	<div style="padding:0 0 5px 0">
	<form name=fmSearch method="get">
	<input type=hidden name='position' value="">
	<input type=hidden name='ctg1' value="<?=$ctg1?>">


	<table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=number_format($total_page)?> page /  <?=number_format($page)?> page }<?}?></font></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>


	<select name="target_date" class='select'>
	<?=option_str("잔금입금일,출국일","pay_date,d_date",$target_date)?>
	</select>
	<?=html_input("pay_date_s",13,10,'box dateinput')?> ~
	<?=html_input("pay_date_e",13,10,'box dateinput')?>


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


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
        <tr>
		  <td colspan="12">

		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
			 <tr>
			  <td width="60%" align="left">
				<span class="btn_pack medium bold"><a href="<?=SELF?>?ctg1=1"> 잔금안내문자 </a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="<?=SELF?>?ctg1=2"> 출발안내문자 </a></span>&nbsp;
			  </td>
			  <td align="right">

				<span class="btn_pack medium bold"><a href="javascript:sms()"> 문자 발송 </a></span>&nbsp;
			  </td>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>

		  </td>
        </tr>

	</table>

	<br/>


	<table width="100%" style="border-collapse:collapse;border:1px solid #ccc">
	<tr>
	<td valign="top" style="padding:5px">

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='sms'>

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></th>
		<th class="subject" >대표자명</th>
		<th class="subject" >연락처</th>
		<th class="subject" >예약일</th>
		<th class="subject" >출국일</th>
		<th class="subject" >잔금입금일</th>
		<th class="subject" >총인원</th>
		<th class="subject" >골프장명</th>
		<th class="subject" >판매가</th>
		<th class="subject" >잔금</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	$sql2 = $dbo2->query("select sum(price) as total ,count(bit) as cnt from cmp_people where bit=1 and code=$rs[code]");
	$rs2=$dbo2->next_record();
	$price = $rs2[total];
	$cnt = $rs2[cnt];

	$golf_name = explode(">",$rs[golf_name]);

	$arr = explode("(",$rs[main_staff]);
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="25"><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
	      <td><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',950,650,1,1,'','reservation')"><?=$rs[name]?></a></td>
	      <td><?=$rs[phone]?></td>
	      <td><?=$rs[tour_date]?></td>
	      <td><?=$rs[d_date]?></td>
	      <td><?=$rs[pay_date]?></td>
	      <td><?=nf($rs[people])?></td>
	      <td align="left" style="padding-left:10px"><?=$golf_name[2]?></td>
	      <td class="numberic"><?=nf($price)?></td>
	      <td style="color:#ff3300" class="numberic"><?=nf($rs[price_last])?></td>
	    </tr>
<?
	$num--;
}
?>
	</table>

	</td>
	<td width="20%" valign="top" style="padding:4px">
		<textarea name="sms_text" class="box" cols="50" rows="18"><?=$SMS_TEXT?></textarea>
	</td>
	</table>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
        <tr>
		  <td colspan="12">

		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
			 <tr>
			  <td width="60%" align="left">
				<span class="btn_pack medium bold"><a href="<?=SELF?>?ctg1=1"> 잔금안내문자 </a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="<?=SELF?>?ctg1=2"> 출발안내문자 </a></span>&nbsp;
			  </td>
			  <td align="right">

				<span class="btn_pack medium bold"><a href="javascript:sms()"> 문자 발송 </a></span>&nbsp;
			  </td>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>

		  </td>
        </tr>

	</table>

	</form>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
