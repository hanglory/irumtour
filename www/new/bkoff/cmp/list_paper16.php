<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");



if($mode=="save"){

	$price=rnf($price);
	$sql = "update cmp_finance set $id = $price";
	$dbo->query($sql);
	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql);}	
	echo "
		<script>
			parent.location.reload();
		</script>
	";
	exit;	
}


$cdate =($cdate)? $cdate : date("Y/m/d");


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "재무상태표";



$sql = "select * from cmp_finance order by id_no limit 1";
$dbo->query($sql);
$rs=$dbo->next_record();


if(!$rs[id_no]){
	$sql = "
		insert into cmp_finance (
			cdate,
			r_print1,
			r_print2,
			r_print3,
			r_print4,
			l_print1,
			l_print2,
			l_print3,
			l_print4,
			l_print5,
			l_print6,
			l_print7,
			l_print8,
			l_print9
		) values (
			'$cdate',
			0,
			0,
			0,
			0,
			0,
			0,
			0,
			0,
			0,
			0,
			0,
			0,
			0
		)
	";
	$dbo->query($sql);	
}

$sql = "select * from cmp_finance order by id_no desc limit 1";
$dbo->query($sql);
//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql);}
$rs=$dbo->next_record();

$rs[r_print1]=nf($rs[r_print1]);
$rs[r_print2]=nf($rs[r_print2]);
$rs[r_print3]=nf($rs[r_print3]);
$rs[r_print4]=nf($rs[r_print4]);
$rs[l_print1]=nf($rs[l_print1]);
$rs[l_print2]=nf($rs[l_print2]);
$rs[l_print3]=nf($rs[l_print3]);
$rs[l_print4]=nf($rs[l_print4]);
$rs[l_print5]=nf($rs[l_print5]);
$rs[l_print6]=nf($rs[l_print6]);
$rs[l_print7]=nf($rs[l_print7]);
$rs[l_print8]=nf($rs[l_print8]);
$rs[l_print9]=nf($rs[l_print9]);
?>
<?include("../top.html");?>
<script type="text/javascript">

$(function(){
	$(".numberic").on("change",function(){
		var id = this.id;
		var val = this.value;
		var cdate = "<?=$cdate?>";
		var url = "<?=SELF?>?mode=save";
		url +="&id="+id;
		url +="&price="+val;
		url +="&cdate="+cdate;
		url +="&customer_in="+$("#customer_in").text();
		actarea.location.href=url;
	});

	$("input").on("click",function(){this.select()});
});
</script>
<style type="text/css">
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:right;padding-right:2px;}
.r{text-align:right !important;padding-right:20px !important;}
.sc{background-color:#f0f0f0;font-weight:bold;}
</style>

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
	<form name="fmSearch" method="get" action="<?=SELF?>">
	<input type="hidden" name='position' value="">


	<tr height=22>
	<td valign='bottom' align=right>

	<!--
	<input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?=$date_s?>" class="box c dateinput">
	~
	<input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?=$date_e?>" class="box c dateinput">
	-->

	<input type="text" name="cdate" id="cdate" size="13" maxlength="10" value="<?=$cdate?>" class="box c dateinput">

	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>


	<?
	//특정일 이후 출금액/입금액 합계

	$filter = " code in (select code from cmp_reservation where tour_date >'$cdate')";
	$sql2 = "
		select	
			(select sum(price_air+price_land+price_refund) from cmp_people where $filter) as l_load1,
			(select sum(price_prev+price_prev2+price_prev3) from cmp_people where $filter) as r_load1
			
		";

	$dbo2->query($sql2);
	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27") checkVar(mysql_error(),$sql2);
	$rs2=$dbo2->next_record();
	$l_load1 =rnf($rs2[l_load1]);
	$r_load1 =rnf($rs2[r_load1]);


	$debt =rnf($rs[r_print1])+rnf($rs[r_print2])+rnf($r_load1); 
	?>

	<table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

		<tr>
			<th class="subject" width="50%" colspan="2">자산</th>
			<th class="subject" width="50%" colspan="2">부채 및 자본</th>
		</tr>
		
		<tr>
			<td class="c" width="50%" colspan="2">유동자산</td>
			<td class="c" width="25%">차입금</td>
			<td class="c" width="25%"><?=html_input("r_print1",10,10,'box r numberic')?></td>
		</tr>		
		<tr>
			<td class="c" width="25%">국민은행펀드</td>
			<td class="c" width="25%"><?=html_input("l_print1",10,10,'box r numberic')?></td>
			<td class="c" width="25%">선수금(고객입금액)</td>
			<td class="c" width="25%"><span id="customer_in"><?=nf($r_load1)?></span></td>
		</tr>
		
		<tr>
			<td class="c" width="25%">KDB</td>
			<td class="c" width="25%"><?=html_input("l_print2",10,10,'box r numberic')?></td>
			<td class="c" width="25%">미지급금</td>
			<td class="c" width="25%"><?=html_input("r_print2",10,10,'box r numberic')?></td>
		</tr>		
		<tr>
			<td class="c" width="25%">임대보증금</td>
			<td class="c" width="25%"><?=html_input("l_print3",10,10,'box r numberic')?></td>
			<td class="c" width="25%"></td>
			<td class="c" width="25%"></td>
		</tr>		
		<tr>
			<td class="c" width="25%">매출채권</td>
			<td class="c" width="25%"><?=html_input("l_print4",10,10,'box r numberic')?></td>
			<td class="c" width="25%">부채 계</td>
			<td class="c" width="25%"><span id="debt"><?=nf($debt)?></span></td>
		</tr>		
		<tr>
			<td class="c" width="25%">예금(고객용)</td>
			<td class="c" width="25%"><?=html_input("l_print5",10,10,'box r numberic')?></td>
			<td class="c sc" width="50%" colspan="2">자본</td>
		</tr>		
		<tr>
			<td class="c" width="25%">예금(고객용)</td>
			<td class="c" width="25%"><?=html_input("l_print6",10,10,'box r numberic')?></td>
			<td class="c" width="25%">납입자본</td>
			<td class="c" width="25%"><?=html_input("r_print3",10,10,'box r numberic')?></td>
		</tr>		
		<tr>
			<td class="c" width="25%">예금(외화)</td>
			<td class="c" width="25%"><?=html_input("l_print7",10,10,'box r numberic')?></td>
			<td class="c" width="25%">이익잉여금</td>
			<td class="c" width="25%"><?=html_input("r_print4",10,10,'box r numberic')?></td>
		</tr>
		<tr>
			<td class="c" width="25%">예금(대출용)</td>
			<td class="c" width="25%"><?=html_input("l_print9",10,10,'box r numberic')?></td>
			<td class="c" width="25%"></td>
			<td class="c" width="25%"></td>
		</tr>		
		<tr>
			<td class="c" width="25%">외환통장</td>
			<td class="c" width="25%"><?=html_input("l_print8",10,10,'box r numberic')?></td>
			<td class="c" width="25%"></td>
			<td class="c" width="25%"></td>
		</tr>		
		<tr>
			<td class="c" width="25%">예금계</td>
			<td class="c" width="25%"><?=nf(rnf($rs[l_print5])+rnf($rs[l_print6])+rnf($rs[l_print7])+rnf($rs[l_print8])+rnf($rs[l_print9]))?></td>
			<td class="c" width="25%"></td>
			<td class="c" width="25%"></td>
		</tr>		
		<tr>
			<td class="c" width="25%">선급금(거래처송금액)</td>
			<td class="c" width="25%"><?=nf($l_load1)?></td>
			<td class="c" width="25%">자본 계</td>
			<td class="c" width="25%"><?=nf(rnf($rs[r_print3])+rnf($rs[r_print4]))?><!--자본계--></td>
		</tr>
		

		<?
		$rs[r_print1]=rnf($rs[r_print1]);
		$rs[r_print2]=rnf($rs[r_print2]);
		$rs[r_print3]=rnf($rs[r_print3]);
		$rs[r_print4]=rnf($rs[r_print4]);
		$rs[l_print1]=rnf($rs[l_print1]);
		$rs[l_print2]=rnf($rs[l_print2]);
		$rs[l_print3]=rnf($rs[l_print3]);
		$rs[l_print4]=rnf($rs[l_print4]);
		$rs[l_print5]=rnf($rs[l_print5]);
		$rs[l_print6]=rnf($rs[l_print6]);
		$rs[l_print7]=rnf($rs[l_print7]);
		$rs[l_print8]=rnf($rs[l_print8]);
		$rs[l_print9]=rnf($rs[l_print9]);

		$l_total = $rs[l_print1]+$rs[l_print2]+$rs[l_print3]+$rs[l_print4]+$rs[l_print5]+$rs[l_print6]+$rs[l_print7]+$rs[l_print8]+$rs[l_print9]+$l_load1;
		$r_total = $rs[r_print1]+$rs[r_print2]+$rs[r_print3]+$rs[r_print4]+$r_load1;
		?>
		<tr>
			<td class="c sc" width="50%" colspan="2"><?=nf($l_total)?></td>
			<td class="c sc" width="50%" colspan="2"><?=nf($r_total)?></td>
		</tr>		
		
	</table>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
        <tr>
		  <td colspan="12">

		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
			 <tr>
			  <td width="60%" align="left">

			  </td>
			  <td align="right">
				<span class="btn_pack medium bold"><a href="list_<?=$filecode?>_excel.php?<?=$_SERVER[QUERY_STRING]?>"> 엑셀 </a></span>
			  </td>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>

		  </td>
        </tr>


	</table>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>

