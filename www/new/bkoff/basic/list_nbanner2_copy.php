<?
include_once("../include/common_file.php");

$sql = "select * from ez_nbanner2 where id_no=$id_no";
$dbo->query($sql);
$rs=$dbo->next_record();
$columns = "";
$values = "";
$reg_date = Date("Y/m/d");
$reg_date2 = Date("H:i:s");

If($mode=="copy" && $rs[id_no]){
	$result = mysql_query("SHOW COLUMNS FROM ez_nbanner2");
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			$col = $row["Field"];
			If($col!="id_no"){

				If($col=="reg_date") $rs[$col] = $reg_date;
				elseIf($col=="reg_date2") $rs[$col] = $reg_date2;
				elseIf($col=="bit_hide") $rs[$col] = 1;
				elseIf($col=="category1") $rs[$col] = "";
				elseIf($col=="category2") $rs[$col] = "";
				elseIf($col=="category3") $rs[$col] = "";
				elseIf($col=="category4") $rs[$col] = "";
				elseIf($col=="category5") $rs[$col] = "";
				elseIf($col=="category6") $rs[$col] = "";
				elseIf($col=="filename"){
					$prev_file  =$rs[$col];
					$rs[$col] = str_replace("/","/CP",$rs[$col]);
					@copy("../../public/banner/$prev_file","../../public/banner/$rs[$col]");
					//checkVar("../../public/banner/$prev_file","../../public/banner/$rs[$col]");
				}


				$columns .= "," . $col;
				$values .= ",'" . addslashes($rs[$col]) . "'";

				//checkVar($col,$rs[$col]);
			}
		}
	}
	$columns=substr($columns,1);
	$values=substr($values,1);

	$sql2 = "
			insert into ez_nbanner2
				($columns)
				values
				($values)
		";

	if($dbo->query($sql2)){

	}


	back();
	exit;

}
?>