<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"고객송출현황");
chk_power($_SESSION["sessLogin"]["proof"],"엑셀다운로드");


if(strstr($_SESSION["sessLogin"]["staff_type"],"partner")) $filter.=" and a.main_staff like '%(".$_SESSION["sessLogin"]["id"].")'";
else $filter = "and main_staff = '$staff_id'"; 

$sql ="
select
a.*,
b.d_time_s
from
cmp_reservation as a left join cmp_air as b
on a.air_id_no = b.id_no
where left(a.d_date,7)='$year/$month'
$filter
order by d_date asc
";
$dbo->query($sql);
//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql);}

if($_SERVER["REMOTE_ADDR"]!="106.246.54.27"){
$xls_name = "calendar_" . date("Ymd").".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/vnd.ms-excel; charset=euc-kr");
header("Content-Disposition: attachment;filename=$xls_name;");
header( "Content-Description: PHP4 Generated Data" );
}

?>
<!DOCTYPE html system "ABOUT:LEGACY-COMPAT">

<head>
<meta charset="UTF-8">
<style type="text/css">
*{font-size:9pt}
table{border-collapse:collapse;border-right:1px solid #666;border-top:1px solid #666}
td{text-align:center;border-left:1px solid #666;border-bottom:1px solid #666;padding:5px}
</style>
</head>

<body>


<table>
<tr>
	<td>날짜</td>
	<td>고객명</td>
	<td>인원</td>
	<td>여행기간</td>
	<td>상품명</td>
	<td>담당자</td>
</tr>
<?
//checkVar(mysql_error(),$sql);
while($rs = $dbo->next_record()){
	$golf_arr = explode(">",$rs[golf_name]);
	$golf = $golf_arr[count($golf_arr)-1];
	$night = ((strtotime($rs[r_date]) -  strtotime($rs[d_date]))/86400)+1;//박수

	$arr = explode("(",$rs[main_staff]);
	$id = substr($arr[1],0,-1);
?>
<tr>
	<td><?=$rs[d_date]?></td>
	<td><?=$rs[name]?></td>
	<td><?=$rs[people]?>명</td>
	<td><?=$night?>일</td>
	<td> <?=$golf?></td>
	<td><?=$arr[0]?></td>
</tr>
<?}?>
</table>


</body>

</html>