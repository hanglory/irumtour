<?
include_once("../include/common_file.php");
include_once "../../SMS/xmlrpc.inc.php";
include_once "../../SMS/class.EmmaSMS.php";
include_once ("../../include/fun_alim.php");


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
	if(!$pay_date_s && !$pay_date_e) $filter = " and pay_date >= '$today' ";
	$target_date = "pay_date";
	$SMS_TEXT = $SMS_TEXT1;
	$find_bit=1;
}
elseif($ctg1=="2"){
	$TITLE.=" > 출발 안내 문자";

	$sort = "d_date desc";
	if(!$pay_date_s && !$pay_date_e) $filter = " and d_date >= '$today' ";
	$target_date = "d_date";
	//$target_date = "d_date, id_no desc";
	$SMS_TEXT = $SMS_TEXT2;
	$find_bit=1;
}elseif($ctg1=="3"){
	$TITLE.=" > 계약금안내";

	$m3 = date("Y/m/d",strtotime(date("Y/m/d")." - 1 month"));
	$sort = "r_date desc";
	$target_date = "r_date";
	//$filter = " and r_date <= '$today' and r_date>='$m3'";
		if(!$pay_date_s && !$pay_date_e) $filter = " and r_date>='$m3'";
	//$target_date = "r_date, id_no desc";
	$SMS_TEXT = $SMS_TEXT3;
	$find_bit=1;
}elseif($ctg1=="4"){
	$TITLE.=" > 해피콜";

	$target_date = "r_date";
	$m = date("Y/m/d");
	$m3 = date("Y/m/d",strtotime(date("Y/m/d")." - 1 month"));
	$sort = "r_date desc";
	if(!$pay_date_s && !$pay_date_e) $filter = " and r_date>='$m3' and r_date<='$m'";
	//$target_date = "r_date, id_no desc";
	$SMS_TEXT = $SMS_TEXT4;
	$find_bit=1;
}else{
	$filter = " and d_date >= '$today' and d_date <= '$next_date' ";
}

if($pay_date_s){
	$filter .= " and $target_date >= '$pay_date_s' ";
	$findMode=1;
}
if($pay_date_e){
	$filter .= " and $target_date <= '$pay_date_e' ";
	$findMode=1;
}



if($mode=="sms"){

	$sms_from = $OFFICE_TEL;
	$sms_type = "L";
	$sms = new EmmaSMS();
	$sms->login($sms_id, $sms_passwd);


	$help = "문의($OFFICE_TEL)";
	while(list($key,$val)=each($check)){

		$arr=explode("@",$val);
		$reservation_id_no = $arr[0];
		$customer_name = $arr[1];
		$customer_cell = $arr[2];
		$customer_leader = $arr[3];

		//$sql = "select * from cmp_reservation where id_no=$reservation_id_no";
		$sql = "select a.*,b.nation,b.city,b.name as tour_name from cmp_reservation as a left join cmp_golf as b on a.golf_id_no=b.id_no where a.id_no=$reservation_id_no";
		$dbo->query($sql);
		$rs=$dbo->next_record();

		if(!strstr($rs[golf_name],">")) $rs[golf_name] ="$rs[nation]>$rs[city]>$rs[tour_name]";
		$golf_name =explode(">",$rs[golf_name]);
		$goods_name=$golf_name[2];
		$staff = explode("(",$rs[main_staff]);
		$staff_name=$staff[0];
		$d_date = $rs[d_date];
		$message = $sms_text;
		$message = str_replace("{고객명}",$customer_name,$message);
		$message = str_replace("{담당자명}",$staff[0],$message);
		$customer = $rs[name];
		$code=$rs[code];
		$phone=$rs[phone];
		$price_prev = $rs[price_prev];//계약금

		$staff_id = substr($staff[1],0,-1);

		$sql2 = "select * from cmp_staff where id='$staff_id'";
		$dbo2->query($sql2);
		$rs2=$dbo2->next_record();
		$staff_cell = "${rs2[cell1]}-${rs2[cell2]}-${rs2[cell3]}";

		$arr = explode(">",$rs[golf_name]);
		$nation =($arr[1])? trim($arr[0]) : $rs[nation];
		$city =($arr[1])? trim($arr[1]) : $rs[city];
	    $sms_to = $customer_cell;
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

			$sql2 = "select * from cmp_air where id_no=$rs[d_air_id_no]";
			$dbo2->query($sql2);
			//checkVar("",$sql2);
			$rs2=$dbo2->next_record();
			$airport_in=$rs2[airport_in];
			//$airport_counter= $rs2[airport_counter];
			$d_air_no= $rs2[d_air_no];
			$d_time_s=$rs2[d_time_s];
			$d_time_e=$rs2[d_time_e];

			$sql2 = "select * from cmp_air where id_no=$rs[r_air_id_no]";
			$dbo2->query($sql2);
			//checkVar("",$sql2);
			$rs2=$dbo2->next_record();			
			$r_air_no=$rs2[r_air_no];
			$r_time_s=$rs2[r_time_s];
			$r_time_e=$rs2[r_time_e];

			$sql2 = "select * from cmp_cargo where air='$rs2[d_air]' order by id_no desc limit 1";
			$dbo2->query($sql2);
			//checkVar("",$sql2);
			$rs2= $dbo2->next_record();
			$airport_counter = $rs2[cargo_counter];
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


			$str = "출발 $rs[d_date] $d_air_no $d_time_s ~ $d_time_e";
			$str .= ",도착 $rs[r_date] $r_air_no $r_time_s ~ $r_time_e";
			//$str .= ",도착 $rs[r_date]";

			$str2 = "$airport_in $airport_counter, $rs2[airport_out] $rs3[meeting_place]";
			$str2 = (trim($str2)==",")? $help :$str2;

			$prepare = ($prepare)?$prepare : $help;
			$temperature = ($temperature)? $temperature : $help;
			$cargo2 = ($cargo2)? $cargo2 : $help;

			$message = str_replace("{일정}",$str,$message);

			$message = str_replace("{국내공항}",$airport_in,$message);
			$message = str_replace("{체크인 카운터}",$airport_counter,$message);
			$message = str_replace("{미팅위치}",$meeting_place,$message);
			$message = str_replace("{미팅보드}",$meeting_board,$message);
			$message = str_replace("{현지담당}",$local_staff,$message);
			$message = str_replace("{비상연락처}",$phone2,$message);

			$message = str_replace("{수화물안내}",$cargo1,$message);
			$message = str_replace("{준비물}",$prepare,$message);
			$message = str_replace("{날씨}",$temperature,$message);
			$message = str_replace("{출국일}",$d_date,$message);

		}elseif($ctg1==3){

			$message = str_replace("{고객명}",$customer,$message);
			$message = str_replace("{담당자}",$staff[0],$message);
			$message = str_replace("{상품명}",$golf_name[2],$message);
			$message = str_replace("{출국일}",$d_date,$message);

		}elseif($ctg1==4){

			$message = str_replace("{고객명}",$customer,$message);
			$message = str_replace("{담당자}",$staff[0],$message);
			$message = str_replace("{출국일}",$d_date,$message);			

		}elseif($ctg1==1){

			$price_total = nf($rs[price_last]);
			$price_last = nf($rs[price_last] / $rs[people]);
			$pay_date = $rs[pay_date];

			/*
			$price_last = 0;
			$name = trim($customer_name);
			$phone = rnf($phone);

			$dbo2->query("select * from cmp_people where code=$code and bit=1 and trim(name)='$name'");
			$rs2 = $dbo2->next_record();
			$price_last = nf($rs2[price_last]);
			*/

			$message = str_replace("{상품명}",$golf_name[2],$message);
			$message = str_replace("{골프장명}",$golf_name[2],$message);
			$message = str_replace("{잔금액(총액)}",$price_total,$message);
			$message = str_replace("{잔금액(1인)}",$price_last,$message);
			$message = str_replace("{잔금일}",$pay_date,$message);
			$message = str_replace("{출국일}",$d_date,$message);			
			$message = str_replace("{담당자연락처}",$staff_cell,$message);			
		}

		//checkVar($sms_to,$message);
		if($send_type=="alim"){
			alim($sms_to,$talk_mode);
		}else{
			$ret = $sms->send($sms_to, $sms_from, $message, $sms_date, $sms_type);
			if(($reservation_id_no_prev!=$reservation_id_no) && $staff_cell) $sms->send($staff_cell, $sms_from, $message, $sms_date, $sms_type);
		}

		/*
		$sql2 = "select * from cmp_people where code=$rs[code] and phone<>''";
		$dbo2->query($sql2);
		//checkVar("sql2",$sql2);

		while($rs2=$dbo2->next_record()){
			$sms_to = rnf($rs2[phone]);
			//checkVar($sms_to,$message);
			$ret = $sms->send($sms_to, $sms_from, $message, $sms_date, $sms_type);
		}
		*/

		$reservation_id_no_prev=$reservation_id_no;

	}
	exit;
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

if(strstr($_SESSION["sessLogin"]["staff_type"],"partner")) $filter.=" and main_staff like '%(".$_SESSION["sessLogin"]["id"].")'";      


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
$selectTxt = "대표자,경로,담당자,예약일,출국일,귀국일,상품명";
$selectValue ="name,view_path,main_staff,tour_date,d_date,r_date,golf_name";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1&send_type=$send_type&talk_mode=$talk_mode";
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
	if(confirm("문자를 발송하시겠습니까?")){
		fm.submit();
	}
}
//-->
</script>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?> <?=($send_type=="alim")?"(알림톡)":""?>

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
	<input type=hidden name='send_type' value="<?=$send_type?>">
	<input type=hidden name='talk_mode' value="<?=$talk_mode?>">


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
				<span class="btn_pack medium bold"><a href="<?=SELF?>?ctg1=3"> 계약금안내 </a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="<?=SELF?>?ctg1=4"> 해피콜 </a></span>&nbsp;

				&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;

				<span class="btn_pack medium bold"><a href="<?=SELF?>?ctg1=1&send_type=alim&talk_mode=payment2"> 잔금안내문자(알림톡) </a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="<?=SELF?>?ctg1=3&send_type=alim&talk_mode=payment"> 계약금안내(알림톡) </a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="<?=SELF?>?ctg1=4&send_type=alim&talk_mode=happycall"> 해피콜(알림톡) </a></span>&nbsp;				
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
       <form name="fmData" method="post" action="<?=SELF?>">
       <input type=hidden name=mode value='sms'>
		<input type=hidden name='ctg1' value="<?=$ctg1?>">
		<input type=hidden name='send_type' value="<?=$send_type?>">
		<input type=hidden name='talk_mode' value="<?=$talk_mode?>">       

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></th>
		<th class="subject" >대표자명</th>
		<th class="subject" >연락처</th>
		<th class="subject" >예약일</th>
		<th class="subject" >출국일</th>
		<th class="subject" >잔금입금일</th>
		<th class="subject" >총인원</th>
		<th class="subject" >상품명</th>
		<th class="subject" >판매가</th>
		<th class="subject" >잔금</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	/*
	$sql2 = $dbo2->query("select sum(price) as total ,count(bit) as cnt from cmp_people where bit=1 and code=$rs[code]");
	$rs2=$dbo2->next_record();
	$price = $rs2[total];
	//$cnt = $rs2[cnt];
	*/

	$name = trim($rs[name]);
	$phone = rnf($rs[phone]);

	$dbo2->query("select * from cmp_people where code=$rs[code] and bit=1 and name='$name' and replace(phone,' ','')='$phone' ");
	$rs2 = $dbo2->next_record();

	if(!strstr($rs[golf_name],">")) $rs[golf_name] ="$rs[nation]>$rs[city]>$rs[golf_name]";
	$golf_name = explode(">",$rs[golf_name]);

	$arr = explode("(",$rs[main_staff]);

	$hidden_chkecbox = ($ctg==1 && !$rs2[price_last])?1:0;
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="25">
	      	
	      	<input type="checkbox" name="check[]" value="<?=$rs[id_no];?>@<?=$rs[name]?>@<?=rnf($rs[phone])?>@leader">
	      	
	      </td>
	      <td><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',1100,650,1,1,'','reservation')"><?=$rs[name]?></a></td>
	      <td><?=$rs[phone]?></td>
	      <td><?=substr($rs[tour_date],2)?></td>
	      <td><?=substr($rs[d_date],2)?></td>
	      <td><?=substr($rs[pay_date],2)?></td>
	      <td><?=nf($rs[people])?></td>
	      <td align="left" style="padding-left:10px"><?=$golf_name[2]?></td>
	      <td class="numberic"><?=nf($rs[price])?></td>
	      <td style="color:#ff3300" class="numberic"><?=nf($rs[price_last])?></td>
	    </tr>
<?
	$num--;

	$sql3="select * from cmp_people where code='$rs[code]' and bit=1 and name<>'' and phone<>'' order by seq asc";
	$dbo3->query($sql3);
	//checkVar($rows.mysql_error(),$sql3);
	while($rs3=$dbo3->next_record()){
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="25"><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>@<?=$rs3[name]?>@<?=rnf($rs3[phone])?>@"></td>
	      <td>&nbsp;&nbsp; ┗ <?=$rs3[name]?></td>
	      <td><?=$rs3[phone]?></td>
	      <td></td>
	      <td></td>
	      <td></td>
	      <td></td>
	      <td></td>
	      <td class="numberic"><?=nf($rs3[price])?></td>
	      <td style="color:#ff3300" class="numberic"><?=nf($rs3[price_last])?></td>
	    </tr>
<?
	}


}
?>
	</table>

	</td>
	<td width="20%" valign="top" style="padding:4px">
		<textarea name="sms_text" class="box" cols="50" rows="18"><?=stripslashes($SMS_TEXT)?></textarea>
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
				<span class="btn_pack medium bold"><a href="<?=SELF?>?ctg1=3"> 계약금안내 </a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="<?=SELF?>?ctg1=4"> 해피콜 </a></span>&nbsp;


				&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;

				<span class="btn_pack medium bold"><a href="<?=SELF?>?ctg1=1&send_type=alim&talk_mode=payment2"> 잔금안내문자(알림톡) </a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="<?=SELF?>?ctg1=3&send_type=alim&talk_mode=payment"> 계약금안내(알림톡) </a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="<?=SELF?>?ctg1=4&send_type=alim&talk_mode=happycall"> 해피콜(알림톡) </a></span>&nbsp;		

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
