<?php
include_once("../include/common_file.php");


chk_power($_SESSION["sessLogin"]["proof"],"일일현황");

$rs[date_s] = ($date_s)? $date_s : date("Y/m/d");
$rs[date_e] = ($date_e)? $date_e : date("Y/m/d");
$date_s = $rs[date_s];
$date_e = $rs[date_e];


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_basic";
$LEFT_HIDDEN="1";
$TITLE = "일일현황";

$bit_partner = (strstr($_SESSION["sessLogin"]["staff_type"],"partner"))?1:0;
if($bit_partner){
	$query_partner = " and main_staff like '%($user_id)'";
}
$query_partner .= " and cp_id ='$CP_ID'";
$query_partner2 .= " and c.cp_id ='$CP_ID'";
?>
<?php include("../top.html");?>
<script>
$(function(){
	$("#date_s").on("change",function(){
		$("#date_e").val($("#date_s").val());
	})	;
});
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


	</td>
	<td align=right valign=top>
	<?=html_input("date_s",13,10,'box dateinput')?> ~
	<?=html_input("date_e",13,10,'box dateinput')?>
	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>


	<h3>1. TL</h3>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" >일자</th>
		<th class="subject" >고객명</th>
		<th class="subject" >BSP</th>
		</tr>
<?php
$name = trim($keyword);
$sql = "select * from cmp_reservation where tl>='$rs[date_s]' and tl<='$rs[date_e]' and bsp<>'' $query_partner order by id_no desc ";
$dbo->query($sql);
//if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql);
while($rs=$dbo->next_record()){

?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="25"><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',1100,650,1,1,'','reservation')"><?=$rs[tl]?></a></td>
	      <td><?=$rs[name]?></td>
	      <td><?=$rs[bsp]?></td>
	    </tr>
<?php
	$num--;
}
?>
	</table>

	<br/>
	<br/>

	<h3>2. 출발고객</h3>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" >일자</th>
		<th class="subject" >고객명</th>
		<th class="subject" >인원</th>
		<th class="subject" >상품명</th>
		<th class="subject" >항공</th>
		<th class="subject" >출발시간</th>
		<th class="subject" >샌딩여부</th>
		<th class="subject" >담당자</th>
		</tr>
<?php
$name = trim($keyword);
$sql = "select * from cmp_reservation where d_date>='$date_s'  and d_date<='$date_e' $query_partner order by d_date desc ";
$dbo->query($sql);
//if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql);
while($rs=$dbo->next_record()){

	if($rs[air_id_no]){
		$sql2="select * from cmp_air where id_no=$rs[air_id_no]";
		$dbo2->query($sql2);
		$rs2=$dbo2->next_record();
		//checkVar("",$sql2);
	}
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="25"><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',1100,650,1,1,'','reservation')"><?=$rs[d_date]?></a></td>
	      <td><?=$rs[name]?></td>
	      <td><?=$rs[people]?></td>
	      <td><?=$rs[golf_name]?></td>
	      <td><?=$rs[d_air_no]?></td>
	      <td><?=$rs2[d_time_s]?></td>
	      <td><?=$rs[bit_sending]?></td>
	      <td><?=$rs[main_staff]?></td>
	    </tr>
<?php
	$num--;
}
?>
	</table>


	<br/>
	<br/>

	<h3>3. 귀국고객</h3>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >일자</th>
		<th class="subject" >고객명</th>
		<th class="subject" >인원</th>
		<th class="subject" >상품명</th>
		<th class="subject" >항공</th>
		<th class="subject" >출발시간</th>
		<th class="subject" >샌딩여부</th>
		<th class="subject" >담당자</th>
		</tr>
<?php
$name = trim($keyword);
$sql = "select * from cmp_reservation where r_date>='$date_s' and r_date<='$date_e' $query_partner order by d_date desc ";
$dbo->query($sql);
//if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql);
while($rs=$dbo->next_record()){

	if($rs[air_id_no]){
		$sql2="select * from cmp_air where id_no=$rs[air_id_no]";
		$dbo2->query($sql2);
		$rs2=$dbo2->next_record();
		//checkVar("",$sql2);
	}
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="25"><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',1100,650,1,1,'','reservation')"><?=$rs[r_date]?></a></td>
	      <td><?=$rs[name]?></td>
	      <td><?=$rs[people]?></td>
	      <td><?=$rs[golf_name]?></td>
	      <td><?=$rs[d_air_no]?></td>
	      <td><?=$rs2[d_time_s]?></td>
	      <td><?=$rs[bit_sending]?></td>
	      <td><?=$rs[main_staff]?></td>
	    </tr>
<?php
	$num--;
}
?>
	</table>

	<br/>
	<br/>

	<h3>4. 예약고객</h3>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" >일자</th>
		<th class="subject" >고객명</th>
		<th class="subject" >인원</th>
		<th class="subject" >상품명</th>
		<th class="subject" >판매가</th>
		<th class="subject" >담당자</th>
		</tr>
<?php
$arr = explode("/",$date_s); $date_s2 = date("Y/m/d",mktime(0,0,0,$arr[1],$arr[2],$arr[0]));
$arr = explode("/",$date_e); $date_e2 = date("Y/m/d",mktime(0,0,0,$arr[1],$arr[2],$arr[0]));
//checkVar($date_s,$date_s2);
$sql = "
SELECT c.*, b.nation AS nation
from cmp_reservation AS c 
			LEFT JOIN cmp_golf b on b.id_no = c.golf_id_no
			where c.tour_date>='$date_s2' 
			  and c.tour_date<='$date_e2' 
			  $query_partner2		
			order BY 
				CASE WHEN b.nation = '한국' THEN 1 ELSE 0 END, 
    			c.d_date DESC";
$dbo->query($sql);
//if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql);
$total1=0;
$total2=0;
while($rs=$dbo->next_record()){
    if($rs[nation] == '한국'){$i+=1;}
    if($i == 1){
?>
        <tr align='center' bgcolor="#F7F7F6">
            <td height="25">해외소계</td>
            <td></td>
            <td><?=nf($total1)?></td>
            <td></td>
            <td><?=nf($total2)?></td>
            <td></td>
        </tr>
<?php
        $i+=1;
    }
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="25"><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',1100,650,1,1,'','reservation')"><?=$rs[d_date]?></a></td>
	      <td><?=$rs[name]?></td>
	      <td><?=$rs[people]?></td>
	      <td><?=$rs[golf_name]?></td>
	      <td><?=nf($rs[price])?></td>
	      <td><?=$rs[main_staff]?></td>
	     </tr>
<?php
	$total1+=$rs[people];
	$total2 +=$rs[price];

	$num--;
}
?>
	    <tr align='center' bgcolor="#F7F7F6">
	      <td height="25">합계</td>
	      <td></td>
	      <td><?=nf($total1)?></td>
	      <td></td>
	      <td><?=nf($total2)?></td>
	      <td></td>
	     </tr>
	</table>


	<br/>
	<br/>

	<h3>5. 샌딩고객</h3>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >일자</th>
		<th class="subject" >고객명</th>
		<th class="subject" >인원</th>
		<th class="subject" >항공</th>
		</tr>
<?php
$arr = explode("/",$date_e);
$w = date("w",mktime(0,0,0,$arr[1],$arr[2],$arr[0]));
$days = ($w==5)? 3:1;

$arr = explode("/",$date_s); $date_s2 = date("Y/m/d",mktime(0,0,0,$arr[1],$arr[2]+1,$arr[0]));
$arr = explode("/",$date_e); $date_e2 = date("Y/m/d",mktime(0,0,0,$arr[1],$arr[2]+$days,$arr[0]));
//checkVar($date_s,$date_s2);
$sql = "select * from cmp_reservation where d_date>='$date_s2'  and d_date<='$date_e2' and bit_sending='Y' $query_partner order by d_date desc ";
$dbo->query($sql);
//checkVar(mysql_error(),$sql);
while($rs=$dbo->next_record()){

?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="25"><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',1100,650,1,1,'','reservation')"><?=$rs[d_date]?></a></td>
	      <td><?=$rs[name]?></td>
	      <td><?=$rs[people]?></td>
	      <td><?if($rs[d_air_no] || $rs[r_date_no]){?>출국편명:<?=$rs[d_air_no]?>,귀국편명:<?=$rs[r_air_no]?><?}?></span></td>
	     </tr>
<?php
	$num--;
}
?>
	</table>


	<br/>
	<br/>

	<h3>6. 잔금입금고객</h3>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >대표자명</th>
		<th class="subject" >연락처</th>
		<th class="subject" >예약일</th>
		<th class="subject" >출국일</th>
		<th class="subject" >잔금입금일</th>
		<th class="subject" >총인원</th>
		<th class="subject" >골프장명</th>
		<th class="subject" >판매가</th>
		<th class="subject" >잔금</th>
		<th class="subject" >담당자</th>
		</tr>
<?php
$today  =date("Y/m/d");
$sort = "d_date desc";
$day = date("Y/m/d",strtotime(date("Y/m/d")." +1 day"));
$filter = "  pay_date = '$day' ";
$sql_2 = "select * from cmp_reservation where $filter $query_partner order by $sort";

$dbo->query($sql_2);
//if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	$sql2 = $dbo2->query("select sum(price) as total ,count(bit) as cnt from cmp_people where bit=1 and code=$rs[code]");
	$rs2=$dbo2->next_record();
	$price = $rs2[total];
	$cnt = $rs2[cnt];

	$golf_name = explode(">",$rs[golf_name]);

	$arr = explode("(",$rs[main_staff]);
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',1100,650,1,1,'','reservation')"><?=$rs[name]?></a></td>
	      <td><?=$rs[phone]?></td>
	      <td><?=$rs[tour_date]?></td>
	      <td><?=$rs[d_date]?></td>
	      <td><?=$rs[pay_date]?></td>
	      <td><?=nf($rs[people])?></td>
	      <td align="left" style="padding-left:10px"><?=$golf_name[2]?></td>
	      <td class="numberic"><?=nf($price)?></td>
	      <td style="color:#ff3300" class="numberic"><?=nf($rs[price_last])?></td>
	      <td><?=$arr[0]?></td>
	    </tr>
<?php
	$num--;
}
?>
	</table>

	<br/>
	<br/>
	<h3>7. 실적현황 (<?=date("Y/m/d")?>까지의 누계)</h3>
<? include("home.php");?>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<? include_once("../bottom.html");?>
