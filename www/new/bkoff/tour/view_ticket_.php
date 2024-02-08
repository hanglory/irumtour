<?
include_once("../include/common_file.php");

####기초 정보
$table = "ez_tour";

#### mode
if($mode=="position"){

	for($i=0; $i<count($check);$i++){
		$id = $check[$i];

		$sql = "update $table set top_${position_assort} =$position_bit where id_no=$id ";
		$dbo->query($sql);

	}

	redirect2("list_tour.php?position=top_${position_assort}");
	exit;

}

?>