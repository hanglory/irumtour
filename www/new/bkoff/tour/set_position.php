<?
include_once("../include/common_file.php");

If($best_copy) $best = $best_copy;

for($i = 0; $i < count($check);$i++){
	$sql = "update ez_tour set top_${position_assort}= $position_bit where id_no = $check[$i] ";
	$dbo->query($sql);
}

If($mode2){
	redirect2($mode2 . "?position=top_$position_assort");exit;
}else{
	redirect2("list_tour.php?position=top_$position_assort&category_step1=$category_step1&category_step2=$category_step2&category_step3=$category_step3&best=$best");exit;
}
?>