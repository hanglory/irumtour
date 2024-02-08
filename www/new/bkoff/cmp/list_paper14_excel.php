<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");


$cdate =($cdate)? $cdate : date("Y/m/d");


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "재무상태표";



$sql = "select * from cmp_finance order by id_no limit 1";
$dbo->query($sql);
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


if($_SERVER["REMOTE_ADDR"]!="106.246.54.27"){
$xls_name = date("Ymd")."_profit_loss.xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/vnd.ms-excel; charset=euc-kr");
header("Content-Disposition: attachment;filename=;");
header( "Content-Description: PHP4 Generated Data" );
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Irumtour</title>
<style type="text/css" media="screen">
	*{font-size:11pt;text-align: center;}
	.r{text-align:right !important;padding-right:20px !important;}
	.sc{background-color:#f0f0f0;font-weight:bold;}
</style>
</head>
<body>


	<?
	$filter = " code in (select code from cmp_reservation where d_date >'$cdate')";
	$sql2 = "
		select	
			(select sum(price_air+price_land+price_refund) from cmp_people where $filter) as l_load1,
			(select sum(price_prev+price_prev2+price_prev3) from cmp_people where $filter) as r_load1
			
		";

	$dbo2->query($sql2);
	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27") checkVar(mysql_error(),$sql2);
	$rs2=$dbo2->next_record();
	$l_load1 =$rs2[l_load1];
	$r_load1 =$rs2[r_load1];

	$debt =rnf($rs[r_print1])+rnf($rs[r_print2])+rnf($r_load1); 
	?>

	<table border="1" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

		<tr>
			<th class="subject" width="50%" colspan="2">자산</th>
			<th class="subject" width="50%" colspan="2">부채 및 자본</th>
		</tr>
		
		<tr>
			<td class="c" width="50%" colspan="2">유동자산</td>
			
			<td class="c" width="25%">차입금</td>
			<td class="c" width="25%"><?=$rs[r_print1]?></td>
		</tr>		
		<tr>
			<td class="c" width="25%">국민은행펀드</td>
			<td class="c" width="25%"><?=$rs[l_print1]?></td>
			<td class="c" width="25%">선수금(고객입금액) </td>
			<td class="c" width="25%"><?=nf($r_load1)?></td>
		</tr>
		
		<tr>
			<td class="c" width="25%">KDB</td>
			<td class="c" width="25%"><?=$rs[l_print2]?></td>
			<td class="c" width="25%">미지급금</td>
			<td class="c" width="25%"><?=$rs[r_print2]?></td>
		</tr>		
		<tr>
			<td class="c" width="25%">임대보증금</td>
			<td class="c" width="25%"><?=$rs[l_print3]?></td>
			<td class="c" width="25%"></td>
			<td class="c" width="25%"></td>
		</tr>		
		<tr>
			<td class="c" width="25%">매출채권</td>
			<td class="c" width="25%"><?=$rs[l_print4]?></td>
			<td class="c" width="25%">부채 계</td>
			<td class="c" width="25%"><?=nf(rnf($debt))?></td>
		</tr>		
		<tr>
			<td class="c" width="25%">예금(내부용)</td>
			<td class="c" width="25%"><?=$rs[l_print5]?></td>
			<td class="c sc" width="50%" colspan="2">자본</td>
		</tr>		
		<tr>
			<td class="c" width="25%">예금(고객용)</td>
			<td class="c" width="25%"><?=$rs[l_print6]?></td>
			<td class="c" width="25%">납입자본</td>
			<td class="c" width="25%"><?=$rs[r_print3]?></td>
		</tr>		
		<tr>
			<td class="c" width="25%">예금(외화)</td>
			<td class="c" width="25%"><?=$rs[l_print7]?></td>
			<td class="c" width="25%">이익잉여금</td>
			<td class="c" width="25%"><?=$rs[r_print4]?></td>
		</tr>		
		<tr>			
			<td class="c" width="25%">예금(대출용)</td>
			<td class="c" width="25%"><?=$rs[l_print9]?></td>
			<td class="c" width="25%"></td>
			<td class="c" width="25%"></td>
		</tr>		
		<tr>
			<td class="c" width="25%">외환통장</td>
			<td class="c" width="25%"><?=$rs[l_print8]?></td>
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
			<td class="c" width="25%">자본계</td>
			<td class="c" width="25%"><?=nf(rnf($rs[r_print3])+rnf($rs[r_print4]))?></td>
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




</body>
</html>