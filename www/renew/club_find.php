<?
include_once("script/include_common_file.php");

	$return_arr = array();

    $sql2 = "
    	select 
    		* 
    		from cmp_golfclub 
    	where 
    		club_name<>'' and club_name like '%$club_name%' 
			or club_id in (select club_id from cmp_golf2 where name like '%$club_name%')
    	order by club_name asc limit 20";
    $dbo2->query($sql2);
    //if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql2);}
    while($rs2=$dbo2->next_record()){
    	$rs2['club_name'] = str_replace("{comma}",",",$rs2['club_name']);
        array_push($return_arr,$rs2['club_name']);
    }
    echo json_encode($return_arr);
?>