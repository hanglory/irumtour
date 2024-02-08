<?
/*FILTER_PARTNER_QUERY_STAFFTYPE 등은 common_file.php에 있음.*/
if(strstr("list_staff.php",SELF)){

    if(strstr("partner_a,partner_g",$_SESSION['sessLogin']['staff_type'])){
        $filter.= $FILTER_PARTNER_QUERY_STAFFTYPE . $FILTER_PARTNER_QUERY_CPID;
    }

}

?>