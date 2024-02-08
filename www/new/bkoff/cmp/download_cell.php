<?
include_once("../include/common_file.php");






if($_SESSION["sessLogin"]["staff_type"]=="parner_i"){
    $filter=" and main_staff like '%(".$_SESSION["sessLogin"]["id"].")'";      
    $filter2=" and staff like '%(".$_SESSION["sessLogin"]["id"].")'";      
}
elseif(strstr("partner_a,partner_g",$_SESSION["sessLogin"]["staff_type"])){
    $cp_id = $_SESSION["sessLogin"]["cp_id"];
    $filter=" and cp_id='$cp_id'";      
    $filter2=" and cp_id='$cp_id'";      
}else{
    $filter=" and cp_id=''";      
    $filter2=" and cp_id=''";      
}






$sql = "
	select name,phone as cell,main_staff from cmp_reservation where phone <>'' $filter group by phone
	union all
	select name,phone as cell,main_staff from cmp_estimate where phone <>'' $filter group by phone
	union all
	select name,phone as cell,staff as main_staff from cmp_customer where phone <>'' $filter2 group by phone

	order by name asc
";
if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
    checkVar(mysql_error(),$sql);
}

if(!strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
$xls_name = "cellnumber_" . date("Ymd").".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/vnd.ms-excel; charset=euc-kr");
header("Content-Disposition: attachment;filename=$xls_name;");
header( "Content-Description: PHP4 Generated Data" );
}
?>

<table>
<tr>
	<td>이름</td>
	<td>핸드폰번호</td>
	<td>담당자</td>
</tr>
<?

$dbo->query($sql);

while($rs=$dbo->next_record()){
?>
<tr>
	<td><?=$rs[name]?></td>
	<td style="mso-number-format:'@'"><?=$rs[cell]?></td>
	<td><?=$rs[main_staff]?></td>
</tr>
<?}?>
</table>