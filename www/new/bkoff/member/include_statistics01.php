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
$sql = "select count(*) as total from ez_member";
$dbo->query($sql);
$rs =$dbo->next_record();
$TOTAL = $rs[total];
?>

			<div style="text-align:right;padding:10px"><select name="year" onchange="location.href='?year='+this.value"><?=option_int2(date("Y"),2010,1,$year)?></select> 년도</div>

			<table width="100%" height="300" border="1" style="border-collapse:collapse" bordercolor="#CCCCCC">
				<tr>
<?
$sql = "select substring(reg_date,6,2) as assort, count(reg_date) as total from ez_member where left(reg_date,4)='$year' group by left(reg_date,7)";
$dbo->query($sql);
while($rs=$dbo->next_record()){
	$id = ceil($rs[assort]);
	$data[$id] = $rs[total];
}

for($i=1; $i < 13;$i++){
	@$percent = ($data[$i]/$TOTAL)*100;
?>
					<td align="center" valign="bottom" width="<?=100/12?>%"><?=number_format($data[$i])?>명<br>(<?=round($percent,2)?>%)<br><img src="../images/common/bg_dot.gif" width="15" height="<?=(round($percent,0))*3?>"></td>
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


</body>
</html>`