<?
include_once("../include/common_file.php");


$sql = "select * from cmp_estimate where id_no=$id_no";
$dbo->query($sql);
$rs=$dbo->next_record();

if($rs[id_no]){

	$code = getUniqNo();
	$staff=$_SESSION[sessLogin][name] . " (". $_SESSION[sessLogin][id] . ")";
	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');
	$bit_copy = 1;

    $result = mysql_query("SHOW COLUMNS FROM cmp_estimate");
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_assoc($result)) {
            $col = $row["Field"];
            If($col!="id_no"){

                If($col=="code") $rs[$col]=$code;
                elseIf($col=="name") $rs[$col] = "[복사본]".$rs[$col];
                elseIf($col=="staff") $rs[$col] = $staff;
                elseIf($col=="bit_copy") $rs[$col] = $bit_copy;
                elseIf($col=="reg_date") $rs[$col] = $reg_date;
                elseIf($col=="reg_date2") $rs[$col] = $reg_date2;

                $columns .= "," . $col;
                $values .= ",'" . addslashes($rs[$col]) . "'";
            }
        }
        $columns=substr($columns,1);
        $values=substr($values,1);

        $sql2 = "
                insert into cmp_estimate
                    ($columns)
                    values
                    ($values)
            ";
        list($rows) = $dbo->query($sql2);

        if($rows){
            echo "<script>alert('복사되었습니다. \\n\\n내용을 수정해 주세요.');parent.location.reload()</script>";
        }else{
            checkVar(mysql_error(),$sql2);exit;
            echo "<script>alert('복사하지 못했습니다.')</script>";

        }

    }

}


?>