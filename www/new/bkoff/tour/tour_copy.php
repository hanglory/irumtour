<?
include_once("../include/common_file.php");

$sql = "select * from ez_tour where tid=$tid";
$dbo->query($sql);
$rs=$dbo->next_record();
$columns = "";
$values = "";
$reg_date = Date("Y/m/d");
$reg_date2 = Date("H:i:s");

If($mode=="copy" && $rs[tid]){
	$result = mysql_query("SHOW COLUMNS FROM ez_tour");
	if (mysql_num_rows($result) > 0) {
		while ($row = mysql_fetch_assoc($result)) {
			$col = $row["Field"];
			If($col!="id_no"){

				If($col=="tid"){
					$rs[$col]=getUniqNo();
					$TID = $rs[$col];
				}
				elseIf($col=="cp_id") $rs[$col] = $CP_ID;
                elseIf($col=="reg_date") $rs[$col] = $reg_date;
				elseIf($col=="reg_date2") $rs[$col] = $reg_date2;
				elseIf($col=="filename1"){
					$prev_file  =$rs[$col];
					$rs[$col] = str_replace("/","/CP",$rs[$col]);
					@copy("../../public/goods/$prev_file","../../public/goods/$rs[$col]");
					//checkVar("../../public/goods/$prev_file","../../public/goods/$rs[$col]");
				}
				elseIf($col=="filename2"){
					$prev_file  =$rs[$col];
					$rs[$col] = str_replace("/","/CP",$rs[$col]);
					@copy("../../public/goods/$prev_file","../../public/goods/$rs[$col]");
					//checkVar("../../public/goods/$prev_file","../../public/goods/$rs[$col]");
				}
				elseIf($col=="filename3"){
					$prev_file  =$rs[$col];
					$rs[$col] = str_replace("/","/CP",$rs[$col]);
					@copy("../../public/goods/$prev_file","../../public/goods/$rs[$col]");
					//checkVar("../../public/goods/$prev_file","../../public/goods/$rs[$col]");
				}
				elseIf($col=="filename4"){
					$prev_file  =$rs[$col];
					$rs[$col] = str_replace("/","/CP",$rs[$col]);
					@copy("../../public/goods/$prev_file","../../public/goods/$rs[$col]");
					//checkVar("../../public/goods/$prev_file","../../public/goods/$rs[$col]");
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
			insert into ez_tour
				($columns)
				values
				($values)
		";

	if($dbo->query($sql2)){

		//일정표 복사	(remark);
		$table = "ez_days_contents";
		$sql = "select * from $table where tid=$tid";
		$dbo->query($sql);
		While($rs=$dbo->next_record()){
			$columns = "";
			$values = "";
			$reg_date = Date("Y/m/d");
			$reg_date2 = Date("H:i:s");

			$result = mysql_query("SHOW COLUMNS FROM $table");
			if (mysql_num_rows($result) > 0) {
				while ($row = mysql_fetch_assoc($result)) {
					$col = $row["Field"];
					If($col!="id_no"){
						If($col=="tid"){
							$rs[$col]=$TID;
						}
						elseIf($col=="reg_date") $rs[$col] = $reg_date;
						elseIf($col=="reg_date2") $rs[$col] = $reg_date2;

						$columns .= "," . $col;
						$values .= ",'" . addslashes($rs[$col]) . "'";

						//checkVar($col,$rs[$col]);
					}
				}
			}
			$columns=substr($columns,1);
			$values=substr($values,1);

			$sql2 = "
					insert into $table
						($columns)
						values
						($values)
				";
			$dbo2->query($sql2);
			//checkVar("",$sql2);
		}

		//일정표 복사	(days);
		$table = "ez_tour_days";
		$sql = "select * from $table where tid=$tid";
		$dbo->query($sql);
		While($rs=$dbo->next_record()){
			$columns = "";
			$values = "";

			$result = mysql_query("SHOW COLUMNS FROM $table");
			if(mysql_num_rows($result) > 0) {
				while ($row = mysql_fetch_assoc($result)) {
					$col = $row["Field"];
					If($col!="id_no"){
						If($col=="tid"){
							$rs[$col]=$TID;
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
					insert into $table
						($columns)
						values
						($values)
				";
			$dbo2->query($sql2);
		}

		//ez_tab_contents 복사;
		$table = "ez_tab_contents";
		$sql = "select * from $table where tid=$tid";
		$dbo->query($sql);
		While($rs=$dbo->next_record()){
			$columns = "";
			$values = "";
			$reg_date = Date("Y/m/d");
			$reg_date2 = Date("H:i:s");

			$result = mysql_query("SHOW COLUMNS FROM $table");
			if (mysql_num_rows($result) > 0) {
				while ($row = mysql_fetch_assoc($result)) {
					$col = $row["Field"];
					If($col!="id_no"){
						If($col=="tid"){
							$rs[$col]=$TID;
						}
						elseIf($col=="reg_date") $rs[$col] = $reg_date;
						elseIf($col=="reg_date2") $rs[$col] = $reg_date2;

						$columns .= "," . $col;
						$values .= ",'" . addslashes($rs[$col]) . "'";

						//checkVar($col,$rs[$col]);
					}
				}
			}
			$columns=substr($columns,1);
			$values=substr($values,1);

			$sql2 = "
					insert into $table
						($columns)
						values
						($values)
				";
			$dbo2->query($sql2);
			//checkVar("",$sql2);
		}

	}
	back();
	exit;

}
?>