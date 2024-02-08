<?
include_once("../include/common_file.php");

if($partner){
	chk_power($_SESSION["sessLogin"]["proof"],"파트너현황");
}
else{
	chk_power($_SESSION["sessLogin"]["proof"],"경영관리");
}
chk_power($_SESSION["sessLogin"]["proof"],"엑셀다운로드");
//if($staff_partner){error("권한이 없습니다.");exit;}


if($partner){
	$sql = "select * from cmp_staff where partner=1 and bit_hide<>1";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
		$partners .="or main_staff like '%($rs[id])%' ";
	}
	$partners = substr($partners,3);
	$partner_filter = " and ($partners)";
}else{
	$sql = "select * from cmp_staff where partner=1 and bit_hide<>1";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
		$partners .="and main_staff not like '%($rs[id])%' ";
	}
	$partners = substr($partners,3);
	$partner_filter = " and ($partners)";
}


$dtype=($dtype)? $dtype : "d_date";

$date_s = ($date_s)? $date_s : $PAPER_DEFAULT_DAY1;
$date_e = ($date_e)? $date_e : $PAPER_DEFAULT_DAY2;

$year_this = substr($date_s,0,4);
$year_prev = substr($date_s,0,4)-1;
$date_s2 = $year_prev . substr($date_s,4);
$date_e2 = $year_prev . substr($date_e,4);


if(substr($date_s,0,4)!=substr($date_e,0,4)){
	error("검색하시는 시작일의 년도와 종료일의 연도가 같아야 합니다.");
	exit;
}



if($REMOTE_ADDR!="106.246.54.27"){
$xls_name = "report12_" . date("Ymd") . ".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/vnd.ms-excel; charset=euc-kr");
header("Content-Disposition: attachment;filename=$xls_name;");
header( "Content-Description: PHP4 Generated Data" );
}

$TEN=1;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
#tbl_cmp_list{border-collapse:collapse;border:1px solid #000 }
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:center;padding-right:2px;}
#tbl_cmp_list td{font-size:9pt;border-left:1px solid #000;border-bottom:1px solid #000;}
#tbl_cmp_list tH{font-size:9pt;border-left:1px solid #000;border-bottom:1px solid #000}
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="padding:10px">



	<?
	if($year){
		$YEAR_PREV = date("Y/m/d",mktime(0,0,0,1,1,$year-1));
		$YEAR_THIS = date("Y/m/d",mktime(0,0,0,1,1,$year+1)-1);
	}

	?>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
			<th class="subject" >국가</th>
			<th class="subject" >도시</th>
			<th class="subject" >항공사</th>
			<th class="subject" >인원</th>
			<th class="subject" >이름</th>
			<th class="subject" >생년월일</th>
		</tr>
		<?
		if($city) $filter = " and b.city like '%$city%'";
		if($d_air) $filter = " and b.d_air like '%$d_air%'";

		$sql = "
			select
				a.${dtype} as date,
				a.code,
				b.nation,
				b.city,
				b.d_air,
				sum(a.people) as total
				from cmp_reservation as a left join cmp_air as b
				on a.d_air_id_no=b.id_no
			where
				((a.$dtype >= '$date_s' and a.$dtype <='$date_e')
				or
				(a.$dtype >= '$date_s2' and a.$dtype <='$date_e2'))
				and b.city<>''
				$filter
			group by a.code,b.nation,b.city,b.d_air
			order by b.nation asc,b.city asc, b.d_air asc,$dtype asc
		";

		$dbo->query($sql);
		if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql);}
		$total=0;
		while($rs=$dbo->next_record()){
			$total+=$rs[total];

			$sql2="select * from cmp_people where code=$rs[code] and bit=1 order by id_no asc";
			$dbo2->query($sql2);
			checkVar(mysql_error(),$sql2);

			for($i=1; $i<=$rs[total];$i++){
				$rs2=$dbo2->next_record();
				if($rs2[rn]){
				$aes = new AES($rs2[rn], $inputKey, $blockSize);
				$dec=$aes->decrypt();
				$rs2[rn] = substr($dec,0,8) . "*******";
				}
		?>

			<?if($i==1){?>
			<tr style="background-color:#f0f0f0">
				<td rowspan="<?=$rs[total]?>"><?=$rs[nation]?></td>
				<td rowspan="<?=$rs[total]?>"><?=$rs[city]?></td>
				<td rowspan="<?=$rs[total]?>"><?=$rs[d_air]?></td>
				<td rowspan="<?=$rs[total]?>"><?=nf($rs[total])?>명</td>
				<td height="30"><?=$rs2[name]?></td>
				<td><?=$rs2[rn]?></td>
			</tr>
			<?}else{?>
			<tr style="background-color:#f0f0f0">
				<td height="30"><?=$rs2[name]?></td>
				<td><?=$rs2[rn]?></td>
			</tr>
			<?}?>
			<?}?>
		<?}?>


		<tr style="background-color:#ffe6cc">
			<td colspan="3">합계</td>
			<td><?=nf($total)?>명</td>
			<td></td>
			<td></td>
		</tr>

	</table>



</div>

</body>
</html>
