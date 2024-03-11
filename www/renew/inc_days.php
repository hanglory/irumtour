<?
$ROWS_GOLF = 12;
$ROWS_HOTEL = 5;

function plan_abs_hotel($i){
    global $HOTEL_ABS_MODE;
    global $ROWS_HOTEL;
    global $HOTEL_CNT;
    global $HOTELINFO_00;
    global $HOTELINFO_01;
    global $HOTELINFO_02;
    global $HOTELINFO_03;
    global $HOTELINFO_03;
    global $hotel_dayss;

    $bit_abs=0;
    for ($j=1; $j <= $ROWS_HOTEL; $j++) {
        if(strstr($hotel_dayss[$j],",${i},")){
            $hotel_name = $HOTELINFO_01[$j];
            $hotel_url = $HOTELINFO_02[$j];
            $hotel_phone = $HOTELINFO_03[$j];
            $code = $HOTELINFO_00[$j];
            $bit_abs=1;
            break;
        }
    }
    if(!$bit_abs){
        $hotel_name = $HOTELINFO_01[$i];
        $hotel_url = $HOTELINFO_02[$i];
        $hotel_phone = $HOTELINFO_03[$i];
        $HOTEL_CNT++;
        $code = $HOTELINFO_00[$i];
    }

    $rt[hotel_name] = $hotel_name;
    $rt[hotel_url] = $hotel_url;
    $rt[hotel_phone] = $hotel_phone;
    $rt[HOTEL_CNT]=$HOTEL_CNT;
    $rt[code] = $code;

    return $rt;
}





$sql = "select * from ez_tour where tid=$tid";
$dbo->query($sql);
$rs= $dbo->next_record();
$arr = explode("(",$rs[main_staff]);
$staff=$arr[0];
$staff_id=substr($arr[1],0,-1);
$d_date = $rs[d_date];
$r_date = $rs[r_date];
$bit_long_days = $rs[bit_long_days];

//2018-01-08
$hotel_id_no1 = $rs[hotel_id_no];
$hotel_id_no2 = $rs[hotel2_id_no];
$hotel_id_no3 = $rs[hotel3_id_no];
$hotel_id_no4 = $rs[hotel4_id_no];
$hotel_id_no5 = $rs[hotel5_id_no];
$hotel_days1 = ",".$rs[hotel_days].",";
$hotel_days2 = ",".$rs[hotel_days2].",";
$hotel_days3 = ",".$rs[hotel_days3].",";
$hotel_days4 = ",".$rs[hotel_days4].",";
$hotel_days5 = ",".$rs[hotel_days5].",";
$HOTEL_ABS_MODE = ($rs[hotel_days] || $rs[hotel_days2] || $rs[hotel_days3] || $rs[hotel_days4] || $rs[hotel_days5])?1:0;
/*24.02.23s*/
$hotel_id_nos[1] = $rs[hotel_id_no];
$hotel_id_nos[2] = $rs[hotel2_id_no];
$hotel_id_nos[3] = $rs[hotel3_id_no];
$hotel_id_nos[4] = $rs[hotel4_id_no];
$hotel_id_nos[5] = $rs[hotel5_id_no];
$hotel_dayss[1] = ",".$rs[hotel_days].",";
$hotel_dayss[2] = ",".$rs[hotel_days2].",";
$hotel_dayss[3] = ",".$rs[hotel_days3].",";
$hotel_dayss[4] = ",".$rs[hotel_days4].",";
$hotel_dayss[5] = ",".$rs[hotel_days5].",";
$HOTEL_ABS_MODE = ($rs[hotel_days] || $rs[hotel_days2] || $rs[hotel_days3] || $rs[hotel_days4] || $rs[hotel_days5])?1:0;
/*24.02.23f*/


$golf_over = ",".$rs[golf_over].",";//골프장을 건너뛰어야 하는 일차

$arr = explode(">",$rs[golf_name]);
$default_golf_name= $arr[count($arr)-1];

if($rs[subject]) $default_golf_name = $rs[subject];

$d_air_no = $rs[d_air_no];
//$d_air_no_m = $rs[d_air_no_m];
$r_air_no = $rs[r_air_no];
$d_air_time1 = $rs[d_air_time1];
$d_air_time2 = $rs[d_air_time2];
//$d_air_time1_m = $rs[d_air_time1_m];
//$d_air_time2_m = $rs[d_air_time2_m];
$r_air_time1 = $rs[r_air_time1];
$r_air_time2 = $rs[r_air_time2];

/*2016-04-06*/
$plan_type=$rs[plan_type];

if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){
    //$plan_type = "C";
    checkVar("plan_type",$plan_type);
}

$plan_bus[1] = $rs[plan_bus1];
$plan_bus[2] = $rs[plan_bus2];
$plan_bus[3] = $rs[plan_bus3];
$plan_bus[4] = $rs[plan_bus4];
$plan_bus[5] = $rs[plan_bus5];
$plan_bus[6] = $rs[plan_bus6];
$plan_bus[7] = $rs[plan_bus7];
$plan_bus[8] = $rs[plan_bus8];
$plan_bus[9] = $rs[plan_bus9];
$plan_bus[10] = $rs[plan_bus10];
$plan_bus[11] = $rs[plan_bus11];
$plan_bus[12] = $rs[plan_bus12];
$plan_bus[13] = $rs[plan_bus13];
$plan_bus[14] = $rs[plan_bus14];
$plan_bus[15] = $rs[plan_bus15];
$plan_bus[16] = $rs[plan_bus16];
$plan_bus[17] = $rs[plan_bus17];
$plan_bus[18] = $rs[plan_bus18];
$plan_bus[19] = $rs[plan_bus19];
$plan_bus[20] = $rs[plan_bus20];

$plan_place[1] = $rs[plan_place1];
$plan_place[2] = $rs[plan_place2];
$plan_place[3] = $rs[plan_place3];
$plan_place[4] = $rs[plan_place4];
$plan_place[5] = $rs[plan_place5];
$plan_place[6] = $rs[plan_place6];
$plan_place[7] = $rs[plan_place7];
$plan_place[8] = $rs[plan_place8];
$plan_place[9] = $rs[plan_place9];
$plan_place[10] = $rs[plan_place10];
$plan_place[11] = $rs[plan_place11];
$plan_place[12] = $rs[plan_place12];
$plan_place[13] = $rs[plan_place13];
$plan_place[14] = $rs[plan_place14];
$plan_place[15] = $rs[plan_place15];
$plan_place[16] = $rs[plan_place16];
$plan_place[17] = $rs[plan_place17];
$plan_place[18] = $rs[plan_place18];
$plan_place[19] = $rs[plan_place19];
$plan_place[20] = $rs[plan_place20];

$plan_text[1] = $rs[plan_text1];
$plan_text[2] = $rs[plan_text2];
$plan_text[3] = $rs[plan_text3];
$plan_text[4] = $rs[plan_text4];
$plan_text[5] = $rs[plan_text5];
$plan_text[6] = $rs[plan_text6];
$plan_text[7] = $rs[plan_text7];
$plan_text[8] = $rs[plan_text8];
$plan_text[9] = $rs[plan_text9];
$plan_text[10] = $rs[plan_text10];
$plan_text[11] = $rs[plan_text11];
$plan_text[12] = $rs[plan_text12];
$plan_text[13] = $rs[plan_text13];
$plan_text[14] = $rs[plan_text14];
$plan_text[15] = $rs[plan_text15];
$plan_text[16] = $rs[plan_text16];
$plan_text[17] = $rs[plan_text17];
$plan_text[18] = $rs[plan_text18];
$plan_text[19] = $rs[plan_text19];
$plan_text[20] = $rs[plan_text20];

$plan_time[1] = $rs[plan_time1];
$plan_time[2] = $rs[plan_time2];
$plan_time[3] = $rs[plan_time3];
$plan_time[4] = $rs[plan_time4];
$plan_time[5] = $rs[plan_time5];
$plan_time[6] = $rs[plan_time6];
$plan_time[7] = $rs[plan_time7];
$plan_time[8] = $rs[plan_time8];
$plan_time[9] = $rs[plan_time9];
$plan_time[10] = $rs[plan_time10];
$plan_time[11] = $rs[plan_time11];
$plan_time[12] = $rs[plan_time12];
$plan_time[13] = $rs[plan_time13];
$plan_time[14] = $rs[plan_time14];
$plan_time[15] = $rs[plan_time15];
$plan_time[16] = $rs[plan_time16];
$plan_time[17] = $rs[plan_time17];
$plan_time[18] = $rs[plan_time18];
$plan_time[19] = $rs[plan_time19];
$plan_time[20] = $rs[plan_time20];

$plan_meal[1] = $rs[plan_meal1];
$plan_meal[2] = $rs[plan_meal2];
$plan_meal[3] = $rs[plan_meal3];
$plan_meal[4] = $rs[plan_meal4];
$plan_meal[5] = $rs[plan_meal5];
$plan_meal[6] = $rs[plan_meal6];
$plan_meal[7] = $rs[plan_meal7];
$plan_meal[8] = $rs[plan_meal8];
$plan_meal[9] = $rs[plan_meal9];
$plan_meal[10] = $rs[plan_meal10];
$plan_meal[11] = $rs[plan_meal11];
$plan_meal[12] = $rs[plan_meal12];
$plan_meal[13] = $rs[plan_meal13];
$plan_meal[14] = $rs[plan_meal14];
$plan_meal[15] = $rs[plan_meal15];
$plan_meal[16] = $rs[plan_meal16];
$plan_meal[17] = $rs[plan_meal17];
$plan_meal[18] = $rs[plan_meal18];
$plan_meal[19] = $rs[plan_meal19];
$plan_meal[20] = $rs[plan_meal20];

//추가라인 19-1-31
$plan_add1_a=$rs[plan_add1_a];
$plan_add1_b=$rs[plan_add1_b];
$plan_add1_c=$rs[plan_add1_c];
$plan_add1_d=$rs[plan_add1_d];
$plan_add2_a=$rs[plan_add2_a];
$plan_add2_b=$rs[plan_add2_b];
$plan_add2_c=$rs[plan_add2_c];
$plan_add2_d=$rs[plan_add2_d];
$plan_add8_a=$rs[plan_add8_a];
$plan_add8_b=$rs[plan_add8_b];
$plan_add8_c=$rs[plan_add8_c];
$plan_add8_d=$rs[plan_add8_d];
$plan_add9_a=$rs[plan_add9_a];
$plan_add9_b=$rs[plan_add9_b];
$plan_add9_c=$rs[plan_add9_c];
$plan_add9_d=$rs[plan_add9_d];

$arr = explode(">",$rs[golf2_1_name]);$rs[golf2_1_name] = $arr[count($arr)-1];
$arr = explode(">",$rs[golf2_2_name]);$rs[golf2_2_name] = $arr[count($arr)-1];
$arr = explode(">",$rs[golf2_3_name]);$rs[golf2_3_name] = $arr[count($arr)-1];
$arr = explode(">",$rs[golf2_4_name]);$rs[golf2_4_name] = $arr[count($arr)-1];
$arr = explode(">",$rs[golf2_5_name]);$rs[golf2_5_name] = $arr[count($arr)-1];
$arr = explode(">",$rs[golf2_6_name]);$rs[golf2_6_name] = $arr[count($arr)-1];
$arr = explode(">",$rs[golf2_7_name]);$rs[golf2_7_name] = $arr[count($arr)-1];
$arr = explode(">",$rs[golf2_8_name]);$rs[golf2_8_name] = $arr[count($arr)-1];
$arr = explode(">",$rs[golf2_9_name]);$rs[golf2_9_name] = $arr[count($arr)-1];
$arr = explode(">",$rs[golf2_10_name]);$rs[golf2_10_name] = $arr[count($arr)-1];
$arr = explode(">",$rs[golf2_11_name]);$rs[golf2_611name] = $arr[count($arr)-1];
$arr = explode(">",$rs[golf2_12_name]);$rs[golf2_6_12ame] = $arr[count($arr)-1];

$golf_name1 = $rs[golf2_1_name];
$golf_name2 = $rs[golf2_2_name];
$golf_name3 = $rs[golf2_3_name];
$golf_name4 = $rs[golf2_4_name];
$golf_name5 = $rs[golf2_5_name];
$golf_name6 = $rs[golf2_6_name];
$golf_name7 = $rs[golf2_7_name];
$golf_name8 = $rs[golf2_8_name];
$golf_name9 = $rs[golf2_9_name];
$golf_name10 = $rs[golf2_10_name];
$golf_name11 = $rs[golf2_11_name];
$golf_name12 = $rs[golf2_12_name];
$golf_name4_ = $rs[golf2_4_name];

/*2016-04-06*/
if($golf_name1) $gname[] = trim($golf_name1 . " " . $rs[hole1]);
if($golf_name2) $gname[] = trim($golf_name2 . " " . $rs[hole2]);
if($golf_name3) $gname[] = trim($golf_name3 . " " . $rs[hole3]);
if($golf_name4) $gname[] = trim($golf_name4 . " " . $rs[hole4]);
if($golf_name5) $gname[] = trim($golf_name5 . " " . $rs[hole5]);
if($golf_name6) $gname[] = trim($golf_name6 . " " . $rs[hole6]);
if($golf_name7) $gname[] = trim($golf_name7 . " " . $rs[hole7]);
if($golf_name8) $gname[] = trim($golf_name8 . " " . $rs[hole8]);
if($golf_name9) $gname[] = trim($golf_name9 . " " . $rs[hole9]);
if($golf_name10) $gname[] = trim($golf_name10 . " " . $rs[hole10]);
if($golf_name11) $gname[] = trim($golf_name11 . " " . $rs[hole11]);
if($golf_name12) $gname[] = trim($golf_name12 . " " . $rs[hole12]);

if($rs[golf2_1_id_no]) $golfs[] = $rs[golf2_1_id_no];
if($rs[golf2_2_id_no]) $golfs[] = $rs[golf2_2_id_no];
if($rs[golf2_3_id_no]) $golfs[] = $rs[golf2_3_id_no];
if($rs[golf2_4_id_no]) $golfs[] = $rs[golf2_4_id_no];
if($rs[golf2_5_id_no]) $golfs[] = $rs[golf2_5_id_no];
if($rs[golf2_6_id_no]) $golfs[] = $rs[golf2_6_id_no];
if($rs[golf2_7_id_no]) $golfs[] = $rs[golf2_7_id_no];
if($rs[golf2_8_id_no]) $golfs[] = $rs[golf2_8_id_no];
if($rs[golf2_9_id_no]) $golfs[] = $rs[golf2_9_id_no];
if($rs[golf2_10_id_no]) $golfs[] = $rs[golf2_10_id_no];
if($rs[golf2_11_id_no]) $golfs[] = $rs[golf2_11_id_no];
if($rs[golf2_12_id_no]) $golfs[] = $rs[golf2_12_id_no];


if($golf_name3 && !$golf_name4) $golf_name4_ = $golf_name3;
if($golf_name2 && !$golf_name3 && !$golf_name4) $golf_name4_ = $golf_name2;
if($golf_name1 && !$golf_name2 && !$golf_name3 && !$golf_name4){ $golf_name2 = $golf_name1; $golf_name4_ = $golf_name1;}


//골프장 이미지
while(list($key,$val)=@each($golfs)){
    $sql2 = "select * from cmp_golf2 where id_no=$val";
    $dbo2->query($sql2);
    $rs2=$dbo2->next_record();
    $key2 = $key+1;

    $GOLF_CITY[$val] = $rs2[city]; //190408

    //if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar($val,$rs2[city]);}

    $GOLF_PIC[$key2][0] = $rs2[filename1];
    $GOLF_PIC[$key2][1] = $rs2[filename2];
    //$GOLF_PIC[$key2][2] = $rs2[filename3];
    //$GOLF_PIC[$key2][3] = $rs2[filename4];
}
//골프장 이미지 종료

//관광지 추가 19-1-10
$tour_id_no1 = $rs[tour_id_no];
$tour_id_no2 = $rs[tour2_id_no];
$tour_id_no3 = $rs[tour3_id_no];
$tour_id_no4 = $rs[tour4_id_no];
$tour_id_no5 = $rs[tour5_id_no];
$tour_id_no6 = $rs[tour6_id_no];
$tour_days1 = ",".$rs[tour_days].",";
$tour_days2 = ",".$rs[tour_days2].",";
$tour_days3 = ",".$rs[tour_days3].",";
$tour_days4 = ",".$rs[tour_days4].",";
$tour_days5 = ",".$rs[tour_days5].",";
$tour_days6 = ",".$rs[tour_days6].",";

if($rs[tour_id_no]) $tours[] = $rs[tour_id_no];
if($rs[tour2_id_no]) $tours[] = $rs[tour2_id_no];
if($rs[tour3_id_no]) $tours[] = $rs[tour3_id_no];
if($rs[tour4_id_no]) $tours[] = $rs[tour4_id_no];
if($rs[tour5_id_no]) $tours[] = $rs[tour5_id_no];
if($rs[tour6_id_no]) $tours[] = $rs[tour6_id_no];

//관광지 이미지
while(list($key,$val)=@each($tours)){
    $sql2 = "select * from cmp_tour where id_no=$val";
    $dbo2->query($sql2);
    $rs2=$dbo2->next_record();
    //if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar($rs2[name],$sql2);}
    $key2 = $key+1;

    $TOUR_CITY[$val] = $rs2[city]; //190408

    $TOUR_INFO[$key2][0] = $rs2[name];
    $TOUR_INFO[$key2][1] = $rs2[content];
    $TOUR_INFO[$key2][2] = $rs2[filename1];
    $TOUR_INFO[$key2][3] = $rs2[filename2];

    $TOUR_NAME[$rs2[id_no]]="[".$rs2[name]."]";
}


$sql2 = "select * from cmp_staff where id='$staff_id'";
$dbo2->query($sql2);
$rs2= $dbo2->next_record();
$staff_phone = " ($rs2[cell1]-$rs2[cell2]-$rs2[cell3])";
if($staff_phone==" (--)") $staff_phone="";
$staff .= $rs2[mposition] . $staff_phone;


$sql2 = "select * from cmp_air where id_no=$rs[d_air_id_no]";
$dbo2->query($sql2);
$rs2= $dbo2->next_record();
$d_air = $rs2[d_air];
$d_air_no = $rs2[d_air_no];
$d_time_s = $rs2[d_time_s];
$d_time_s = $d_time_s."</div><div class='time2 hide'> ".$d_air_no;
$d_time_e = $rs2[d_time_e];
$airport_in = str_replace("","",$rs2[airport_in]);
$airport_out = $rs2[airport_out];
$airport_city = $rs2[city];
$airport_place = $rs2[airport_place];

$d_air_no_m = $rs2[d_air_no_m];
$d_air_no_m2 = $rs2[d_air_no_m2];
$d_air_time1_m = $rs2[d_time_s_m];
$d_air_time2_m = $rs2[d_time_e_m];

$r_add1 = $rs2[r_add1];
$r_add2 = $rs2[r_add2];

if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar("$d_air_time1_m",$rs2[d_air_time1_m]);}

if($REMOTE_ADDR=="111106.246.54.27"){
    checkVar(mysql_error(),$sql2);
    checkVar($rs[airport_out],$airport_out);
}


$sql2 = "select * from cmp_air where id_no=$rs[r_air_id_no]";
$dbo2->query($sql2);
$rs2= $dbo2->next_record();
$r_air = $rs2[r_air_no];
$r_air_no = $rs2[r_air_no];
$r_time_s = $rs2[r_time_s];
$r_time_e = $rs2[r_time_e];
$r_airport_in = str_replace("","",$rs2[airport_in]);
$r_airport_out = $rs2[airport_out];
$r_airport_city = $rs2[city];
$r_airport_place = $rs2[airport_place];

$sql2 = "select * from cmp_hotel where id_no=$rs[hotel_id_no]";
$dbo2->query($sql2);
$rs2= $dbo2->next_record();
$hotel_name = $rs2[name];
$hotel_url = $rs2[url];
$hotel_phone = $rs2[phone];
$ah1 = $rs2[ah];

//2018-01-08
$HOTEL_CNT1=0;
$HOTEL_CNT2=0;
$HOTELINFO_00[1] = $rs2[id_no];
$HOTELINFO_01[1] = $rs2[name];
$HOTELINFO_02[1] = $rs2[url];
$HOTELINFO_03[1] = $rs2[phone];
$HOTELINFO_04[1] = $rs2[ah];


$sql2 = "select * from cmp_hotel where id_no=$rs[hotel2_id_no]";
$dbo2->query($sql2);
$rs2= $dbo2->next_record();
$hotel2_name = $rs2[name];
$hotel2_url = $rs2[url];
$hotel2_phone = $rs2[phone];
$ah2 = $rs2[ah];

//2018-01-08
$HOTELINFO_00[2] = $rs2[id_no];
$HOTELINFO_01[2] = $rs2[name];
$HOTELINFO_02[2] = $rs2[url];
$HOTELINFO_03[2] = $rs2[phone];
$HOTELINFO_04[2] = $rs2[ah];

/*24.02.23*/
$HOTEL_CNT=0;
for ($i=1; $i <= $ROWS_HOTEL; $i++) {
    $j=($i==1)?"":$i;
    $sql2 = "select * from cmp_hotel where id_no=".$rs["hotel${j}_id_no"];
    $dbo2->query($sql2);
    $rs2= $dbo2->next_record();
    $hotel_names[$i] = $rs2[name];
    $hotel_urls[$i] = $rs2[url];
    $hotel_phones[$i] = $rs2[phone];
    $ahs[$i] = $rs2[ah];
    $HOTELINFO_00[$i] = $rs2[id_no];
    $HOTELINFO_01[$i] = $rs2[name];
    $HOTELINFO_02[$i] = $rs2[url];
    $HOTELINFO_03[$i] = $rs2[phone];
    $HOTELINFO_04[$i] = $rs2[ah];
}




$sql2 = "select * from cmp_golf where id_no=$rs[golf_id_no]";
$dbo2->query($sql2);
$rs2= $dbo2->next_record();
//if($REMOTE_ADDR=="106.246.54.27") checkVar($rs2[meal],$sql2);

$nation = $rs2[nation];
$car = $rs2[car];
$car2 = $rs2[car2];
$car3 =($rs2[car3])? $rs2[car3] : $rs2[car];
$city = $rs2[city];


if($car2){
    $plan_bus[2] = $car2;
    $plan_bus[3] = $car2;
    $plan_bus[4] = $car2;
    $plan_bus[5] = $car2;
    $plan_bus[6] = $car2;
    $plan_bus[7] = $car2;
    $plan_bus[8] = $car2;
    $plan_bus[9] = $car2;
    $plan_bus[10] = $car2;
    $plan_bus[11] = $car2;
    $plan_bus[12] = $car2;
    $plan_bus[13] = $car2;
    $plan_bus[14] = $car2;
    $plan_bus[15] = $car2;
    $plan_bus[16] = $car2;
    $plan_bus[17] = $car2;
    $plan_bus[18] = $car2;
    $plan_bus[19] = $car2;
    $plan_bus[20] = $car2;
}


$ag1=($rs2[ag1])?$rs2[ag1] : "00분";
$ag2=($rs2[ag2])?$rs2[ag2] : "00분";
$ag3=($rs2[ag3])?$rs2[ag3] : "00분";
$ag4=($rs2[ag4])?$rs2[ag4] : "00분";
$ag5=($rs2[ag5])?$rs2[ag5] : "00분";
$ag6=($rs2[ag6])?$rs2[ag6] : "00분";
$ag7=($rs2[ag7])?$rs2[ag7] : "00분";
$ag8=($rs2[ag8])?$rs2[ag8] : "00분";
$gh1=($rs2[gh1])?$rs2[gh1] : "00분";
$gh2=($rs2[gh2])?$rs2[gh2] : "00분";
$gh3=($rs2[gh3])?$rs2[gh3] : "00분";
$gh4=($rs2[gh4])?$rs2[gh4] : "00분";
$gh5=($rs2[gh5])?$rs2[gh5] : "00분";
$gh6=($rs2[gh6])?$rs2[gh6] : "00분";
$gh7=($rs2[gh7])?$rs2[gh7] : "00분";
$gh8=($rs2[gh8])?$rs2[gh8] : "00분";
$ah1=($rs2[ah1])?$rs2[ah1] : "00분";
$ah2=($rs2[ah2])?$rs2[ah2] : "00분";
$ah3=($rs2[ah3])?$rs2[ah3] : "00분";
$ah4=($rs2[ah4])?$rs2[ah4] : "00분";
$ah5=($rs2[ah5])?$rs2[ah5] : "00분";
$ah6=($rs2[ah6])?$rs2[ah6] : "00분";
$ah7=($rs2[ah7])?$rs2[ah7] : "00분";
$ah8=($rs2[ah8])?$rs2[ah8] : "00분";

$ag[] = $ag1;
$ag[] = $ag2;
$ag[] = $ag3;
$ag[] = $ag4;

$gh[]  =$gh1;
$gh[]  =$gh2;
$gh[]  =$gh3;
$gh[]  =$gh4;

if($REMOTE_ADDR=="1106.246.54.27"){
    checkVar("gh1",$gh1);
    checkVar("gh2",$gh2);
    checkVar("gh3",$gh3);
    checkVar("gh4",$gh4);
}


/*2016-04-06*/
$air_city = $rs2[air_city];
$car2 = $rs2[car2];
$etc2 = $rs2[etc2];
$cancel_text = ($rs2[cancel_text])? $rs2[cancel_text] : $CANCEL_TXT;

/*
$hotel2_name = $rs2[hotel2_name];
$hotel2_id_no = $rs2[hotel2_id_no];
*/

//checkVar("golf_id_no",$rs[golf_id_no]);
//checkVar($car,$car2);


$meal  =nl2br($rs2[meal]);



/*2016-04-06*/
$form_mode = ($nation=="일본" || $nation=="중국" || $nation=="대만")?2:1;
if(!$plan_type){
    $plan_type = ($form_mode==2)?"A":"E";
}


$edit_mode = (!$doc_mode && !$print_mode)?1:0;


/*2016-04-06*/
if($doc_mode && !$code){
    $xls_name = "report1.doc";
    header("Content-type: application/vnd.ms-word");
    header("Content-Type: application/vnd.ms-word; charset=euc-kr");
    header("Content-Disposition: attachment;filename=$xls_name;");
    header( "Content-Description: PHP4 Generated Data" );
}

/*
if($form_mode==2){
   $days1 = $night-1;
   $days2 = $night;
}else{
   $days1 = $night-2;
   $days2 = $night;
}
*/

if($plan_type=="E" || $plan_type=="F"){
    $days1 = $night-2;
    $days2 = $night;
}else{
    $days1 = $night-1;
    $days2 = $night;
}

$plan_type_lower = strtolower($plan_type);

//공항도착시간
if($ah1!="00분" && $ah2=="00분") $ah2 = $ah1;

$hotels="";//1회만 보이기 위한 변수

// if($REMOTE_ADDR=="106.246.54.27" && date("Y/m/d")=="2018/06/01"){
// 	checkVar("tid",$tid);
// 	checkVar("plan",$plan_type_lower);
// 	checkVar("meal_type",$meal_type);
// 	checkVar("hight",$night);
// }

$night = ($night<60)? $night : 60;
?>