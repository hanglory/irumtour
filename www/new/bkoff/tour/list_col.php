<?
include_once("../include/common_file.php");

$sql = "select * from ez_tour where category1<>'7--' order by category1 asc";
$dbo->query($sql);
?>

<table border=1>
<?
while($rs=$dbo->next_record()){
?>
<tr>
	<td><?=str_replace("¿©Çà","",get_category_name($rs[category1]))?></td>
	<td><?=$rs[subject]?></td>
	<td><?=nl2br($rs[content1])?></td>
	<td><?=nl2br($rs[content2])?></td>
	<td><?=nl2br($rs[content3])?></td>
</tr>
<?}?>
</table>