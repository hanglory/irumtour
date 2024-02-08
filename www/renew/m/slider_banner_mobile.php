
<ul class="bxslider" style="z-index:1">
<?
$sql = "select * from banner_main order by seq asc";
$dbo->query($sql);
while($rs=$dbo->next_record()){
	$url = ($rs[url])? $rs[url]:'#';
	$url = str_replace("/new/","/m/",$url);
	$url = str_replace("sublist","sub_list",$url);

	if($rs[url]){
	  $target=($rs[target]=="_blank")? "target='_blank'":"target='_top'";
	}else{
	  $target="";
	}
?>
	<li><a href="<?=$url?>" <?=$target?>><img src="/new/public/banner/<?=$rs[filename]?>" width="100%" /></a></li>
<?}?>
</ul>