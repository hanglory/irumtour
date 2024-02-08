<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");

$filename= "../../public/inc/cmp_paper6.inc";


if($mode=="sum"){
	$price1 = str_replace(",","",trim($price1));
	$price2 = str_replace(",","",trim($price2));
	$price3 = str_replace(",","",trim($price3));
	$price4 = str_replace(",","",trim($price4));

	$price1 = 0-$price1;

	$total = $price1+$price2+$price3+$price4;
	$total_=  nf($total);
	$price2_=  nf($price2);
	$price3_=  nf($price3);
	$price4_=  nf($price4);

	echo "
		<script>
			parent.document.getElementById('price2').value = '$price2_';
			parent.document.getElementById('price3').value = '$price3_';
			parent.document.getElementById('price4').value = '$price4_';
			parent.document.getElementById('s_price5').innerHTML = '$total_';
		</script>
	";


	$fp=fopen($filename, "w");	//파일 쓰기모드로 열기, 파일이 있다면 overwirte

	$config ="<?\n";
	$config .="##-------------------------------------------\n";
	$config .="##주의! (이 파일을 수동으로 조작하지 마세요.)\n";
	$config .="##-------------------------------------------\n";

	$config .="\$PRICE2='$price2';\n";
	$config .="\$PRICE3='$price3';\n";
	$config .="\$PRICE4='$price4';\n";

	$config .="?";
	$config .=">";

	fwrite($fp,$config);
	fclose($fp);
	exit;
}
include($filename);

$dtype=($dtype)? $dtype : "d_date";

$PAPER_DEFAULT_DAY1 = date("Y/m/01");
$PAPER_DEFAULT_DAY2 = date("Y/m/d",strtotime(date("Y/m/01")." +1 month")-1);

$date_s = ($date_s)? $date_s : $PAPER_DEFAULT_DAY1;
$date_e = ($date_e)? $date_e : $PAPER_DEFAULT_DAY2;

$year_this = substr($date_s,0,4);
$year_prev = substr($date_s,0,4)-1;
$date_s2 = $year_prev . substr($date_s,4);
$date_e2 = $year_prev . substr($date_e,4);


if(substr($date_s,0,4) > substr($date_e,0,4)){
	error("날짜가 잘못되었습니다. 다시 확인해 주세요.");
	exit;
}


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "통장잔액 확인";
?>
<?include("../top.html");?>
<style type="text/css">
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:center;padding-right:2px;}
.r{padding-right:5px !important}
</style>

<script type="text/javascript">
<!--
function sum(){
	var url ="<?=SELF?>?mode=sum";
	url +="&price1=" + $("#s_price1").text();
	url +="&price2=" + $("#price2").val();
	url +="&price3=" + $("#price3").val();
	url +="&price4=" + $("#price4").val();

	actarea.location.href=url;
}


$(function(){
	$("#price2,#price3,#price4").on("change",function(){
		 sum();
	});

	$("#price2,#price3,#price4").on("focus",function(){
		this.select();
	});

	sum();
});
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

	<br/>


	<!--내용이 들어가는 곳 시작-->

	<!-- Search Begin------------------------------------------------>
	<div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
	<form name=fmSearch method="get">
	<input type=hidden name='position' value="">
	<input type=hidden name='ctg1' value="<?=$ctg1?>">


	<tr height=22>
	<td valign='bottom' align=right>



	<input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?=$date_s?>" class="box c dateinput">
	~
	<input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?=$date_e?>" class="box c dateinput">


	<select name="dtype">
		<?=option_str("예약일기준,출국일기준","tour_date,d_date",$dtype)?>
	</select>

	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>

	<?
	$sql = "
		select
			sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
		from cmp_reservation as a
		where
			($dtype >= '$date_s' and $dtype <='$date_e')
		";
	$dbo->query($sql);
	$rs= $dbo->next_record();
	$amount = $rs[sum_fee];

	//if($easeplus) checkVar(mysql_error(),$sql);

	$rs[price2] = nf($PRICE2);
	$rs[price3] = nf($PRICE3);
	$rs[price4] = nf($PRICE4);
	?>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
			<th class="subject" width="30%">구분</th>
			<th class="subject" >항목</th>
		</tr>

		<tr style="background-color:#fff">
			<td>고객통장</td>
			<td class="r"><span id="s_price1"><?=nf($amount)?></span></td> <!-- -(고객입금액-가지급금) -->
		</tr>

		<tr style="background-color:#fff">
			<td>법인통장</td>
			<td class="r"><span id="s_price4"><?=html_input("price4",30,30,'box numberic')?></span></td>
		</tr>

		<tr style="background-color:#fff">
			<td>회사(비용)통장</td>
			<td class="r"><span id="s_price2"><?=html_input("price2",30,30,'box numberic')?></span></td>
		</tr>

		<tr style="background-color:#fff">
			<td>외환통장</td>
			<td class="r"><span id="s_price3"><?=html_input("price3",30,30,'box numberic')?></span></td>
		</tr>

		<tr style="background-color:#ffe6cc">
			<td>실시간 통장 잔액</td>
			<td class="r"><span id="s_price5">0</span></td>
		</tr>

	</table>



	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
