<?
include_once("../include/common_file.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../include/default.css">
<link rel="stylesheet" href="../include/basic.css" type="text/css">
<script language="JavaScript" src="../include/form_check.js"></script>
<script language="JavaScript" src="../include/function.js"></script>
</head>

<body>

<?
$sql = "select count(id_no) as total from yam_order where MEDIA_NO='$sessLogin[media_code]' and status_pay=1";
$dbo->query($sql);
$rs =$dbo->next_record();
$TOTAL = $rs[total];

//checkVar("TOTAL",$TOTAL);
//checkVar("sql",$sql);
?>

			<div style="text-align:right;padding:10px"><select name="year" onchange="location.href='?year='+this.value"><?=option_int2(date("Y"),2010,1,$year)?></select> 년도</div>

			<table width="100%" height="300" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
				<tr>
<?
$sql = "select substring(status_20,6,2) as assort, count(status_20) as total from yam_order where MEDIA_NO='$sessLogin[media_code]' and id<>'' and left(status_20,4)='$year' group by left(reg_date,7)";
$dbo->query($sql);
//checkVar(mysql_error(),$sql);
while($rs=$dbo->next_record()){
	$id = ceil($rs[assort]);
	$data[$id] = $rs[total];
}

for($i=1; $i < 13;$i++){
	@$percent = ($data[$i]/$TOTAL)*100;
?>
					<td align="center" valign="bottom" width="<?=100/12?>%"><?=number_format($data[$i])?>건<br>(<?=round($percent,2)?>%)<br><img src="../images/common/bg_dot.gif" width="15" height="<?=(round($percent,0))*3?>"></td>
<?
}
?>

				</tr>
				<tr>
<?
for($i=1; $i < 13;$i++){
?>
					<td align="center" height="30"><?=$i?>월</td>
<?
}
?>
				</tr>
			</table>

			<div style="padding:10px;color:gray">* 주문일기준으로 결제완료된 주문건</div>



			<div style="padding:10px;font-weight: bold">* 회원 순위 30위</div>

			<table width="100%" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
				<tr height="30" align="center">
					<td>순위</td>
					<td>아이디</td>
					<td>이름</td>
					<td>금액</td>
				</tr>
<?
$sql = "select count(orAmount) as total, id from yam_order where MEDIA_NO='$sessLogin[media_code]' and id<>'' and left(status_20,4)='$year' group by left(reg_date,7), id order by total desc";
$dbo->query($sql);
//checkVar(mysql_error(),$sql);
$i=1;
while($rs=$dbo->next_record()){
	$sql2="select * from shop_pcmember where media_code='$sessLogin[media_code]' and id='$rs[id]'";
	$dbo2->query($sql2);
	$rs2=$dbo2->next_record();
?>
				<tr>
					<td align="center" height="30"><?=$i?></td>
					<td align="center" height="30"><a href="view_pcmember.php?id_no=<?=$rs2[id_no]?>" target="_parent"><?=$rs[id]?></a></td>
					<td align="center" height="30"><?=$rs2[name]?></td>
					<td align="center" height="30"><?=number_format($rs[total])?>회</td>
				</tr>
<?
	$i++;
}
?>


			</table>

</body>
</html>