<script type="text/javascript">
<!--

function setOption(obj,n,dft)
{
	var div = obj.value;

	var fm = document.getElementById('category'+n+'_step2');
	var fm2 = document.getElementById('category'+n+'_step3');


	clearOption(fm);
	clearOption(fm2);

	if(div==''){
	}
	<?
	$sql2= "select * from ez_tour_category1";
	$dbo2->query($sql2);
	while($rs2 = $dbo2->next_record()){
		$sql3 = "select * from ez_tour_category2 where code1='$rs2[id_no]' order by  seq asc";
		$dbo3->query($sql3);
		$i=1;
	?>
	else if(div=='<?=$rs2[id_no]?>'){
		new_option = new Option('선택','');
		fm.options.add(new_option,0);
		<?while($rs3=$dbo3->next_record()){?>
		var dft2="<?=$rs3[id_no]?>";
		var true_bit = (dft==dft2)? true:'';
		new_option = new Option('<?=$rs3[subject]?>','<?=$rs3[id_no]?>',true_bit,true_bit);
		fm.options.add(new_option,<?=$i?>);
		<?$i++;}?>
	}
	<?
	}
	?>
}

function setOption2(obj,n,dft)
{
	var div = obj.value;
	var fm = document.getElementById('category'+n+'_step3');

	clearOption(fm);
	if(div==''){
	}
	<?
	$sql2= "select * from ez_tour_category2 order by seq asc";
	$dbo2->query($sql2);
	while($rs2 = $dbo2->next_record()){
		$sql3 = "select * from ez_tour_category3 where code2='$rs2[id_no]' order by seq asc";
		$dbo3->query($sql3);
		$i=1;
	?>
	else if(div=='<?=$rs2[id_no]?>'){
		new_option = new Option('선택','');
		fm.options.add(new_option,0);
		<?while($rs3=$dbo3->next_record()){?>
		var dft2="<?=$rs3[id_no]?>";
		var true_bit = (dft==dft2)? true:'';
		new_option = new Option('<?=$rs3[subject]?>','<?=$rs3[id_no]?>',true_bit,true_bit);
		fm.options.add(new_option,<?=$i?>);
		<?$i++;}?>
	}
	<?
	}
	?>
}

function clearOption(fm){
	for( var i = fm.options.length ; i >= 0; i-- )
	{
	fm.options[i] = null;
	}
}


function setOption2th(div)
{
	var fm = document.fmData.category2_step2;
	var fm2 = document.fmData.category2_step3;
	clearOption(fm);
	clearOption(fm2);

	if(div==''){
	}
	<?
	$sql2= "select * from ez_tour_category1";
	$dbo2->query($sql2);
	while($rs2 = $dbo2->next_record()){
		$sql3 = "select * from ez_tour_category2 where subject='$rs2[subject]' order by seq asc";
		$dbo3->query($sql3);
		$i=1;
	?>
	else if(div=='<?=$rs2[subject]?>'){
		new_option = new Option('선택','');
		fm.options.add(new_option,0);
		<?while($rs3=$dbo3->next_record()){?>
		new_option = new Option('<?=$rs3[subject]?>','<?=$rs3[subject]?>'<?=($rs[category2_step2]==$rs3[subject])?",'',true":""?>);
		fm.options.add(new_option,<?=$i?>);
		<?$i++;}?>
	}
	<?
	}
	?>
}

function setOption2th2(div)
{
	var fm = document.fmData.category2_step3;
	clearOption(fm);
	if(div==''){
	}
	<?
	$sql2= "select * from ez_tour_category2 order by seq asc";
	$dbo2->query($sql2);
	while($rs2 = $dbo2->next_record()){
		$details = explode(",",trim($rs2[category3]));
	?>
	else if(div=='<?=$rs2[subject]?>'){
		new_option = new Option('선택','');
		fm.options.add(new_option,0);
		<?for($j=0; $j < count($details);$j++){?>
		new_option = new Option('<?=$details[$j]?>','<?=$details[$j]?>' <?=($rs[category2_step3]==$details[$j])?",'',true":""?>);
		fm.options.add(new_option,<?=$j+1?>);
		<?}?>
	}
	<?
	}
	?>
}

function clearOption2th(fm){
	for( var i = fm.options.length ; i >= 0; i-- )
	{
	fm.options[i] = null;
	}
}

//-->
</script>