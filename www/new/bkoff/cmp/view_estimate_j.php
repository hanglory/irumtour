<?
include_once("../include/common_file.php");


chk_power($_SESSION["sessLogin"]["proof"],"견적서관리대장");
$maxFileSize = intval(substr(ini_get(upload_max_filesize),0,-1));



####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_estimate";
$MENU = "cmp_basic";
$TITLE = "일정표 등록";
$file_rows = 1; //파일 업로드 갯수



$sql2 = "select * from cmp_staff where id='$user_id'";
$dbo2->query($sql2);
$rs2=$dbo2->next_record();
$cp_url = $rs2[cp_url];


#### staff
$sql = "select * from cmp_staff where bit_hide<>1 order by name asc";
$dbo->query($sql);
while($rs=$dbo->next_record()){
    $STAFF.=",$rs[name] ($rs[id])";
}
if(strstr($_SESSION["sessLogin"]["staff_type"],"partner")) $STAFF =$_SESSION[sessLogin][name] ." (". $_SESSION[sessLogin][id] .")";



#### operation
if ($mode=="save"){

    //chkVars();

    if($_FILES["file1"]["size"]){
        #------------------------------------------
        $path="../../public/cmp_files"; //업로드할 파일의 경로
        $maxsize=10 *(1024*1024) ;          //2MB   업로드 가능한 최대 사이즈 제한
        $fname=$_FILES["file1"]["tmp_name"];                        //파일이름을 담고 있는 변수 이름
        $fname_name=$_FILES["file1"]["name"];   //파일의 이름
        $fname_size=$_FILES["file1"]["size"];       //파일의 사이즈
        $fname_type=$_FILES["file1"]["type"];       //파일의 type
        $filename="est_${code}_1";      //파일이름 작명
        $type = "image"; // 일반파일 normal, 이미지만 image
        #------------------------------------------
        include("../../include/file_upload.php");
        $upfile1 = $upfile;
        $upfile1_real = $_FILES["file1"]["name"];
        $upfileQuery1 = ($upfile)? "filename1 = '$upfile1',filename1_real='$upfile1_real', ":"" ;
    }

    if($_FILES["file2"]["size"]){
        #------------------------------------------
        $path="../../public/cmp_files"; //업로드할 파일의 경로
        $maxsize=10 *(1024*1024) ;          //2MB   업로드 가능한 최대 사이즈 제한
        $fname=$_FILES["file2"]["tmp_name"];                        //파일이름을 담고 있는 변수 이름
        $fname_name=$_FILES["file2"]["name"];   //파일의 이름
        $fname_size=$_FILES["file2"]["size"];       //파일의 사이즈
        $fname_type=$_FILES["file2"]["type"];       //파일의 type
        $filename="est_${code}_2";      //파일이름 작명
        $type = "image"; // 일반파일 normal, 이미지만 image
        #------------------------------------------
        include("../../include/file_upload.php");
        $upfile2 = $upfile;
        $upfile2_real = $_FILES["file2"]["name"];
        $upfileQuery2 = ($upfile)? "filename2 = '$upfile2',filename2_real='$upfile2_real', ":"" ;
    }


    if(strstr($tour_name,"선택하지 않기")){
        $tour_name="";
        $tour_id_no="";
        $tour="";
        $tour_days="";
    }

    if(strstr($tour2_name,"선택하지 않기")){
        $tour2_name="";
        $tour2_id_no="";
        $tour2="";
        $tour_days2="";
    }

    if(strstr($tour3_name,"선택하지 않기")){
        $tour3_name="";
        $tour3_id_no="";
        $tour3="";
        $tour_days3="";
    }

    if(strstr($tour4_name,"선택하지 않기")){
        $tour4_name="";
        $tour4_id_no="";
        $tour4="";
        $tour_days4="";
    }

    if(strstr($tour5_name,"선택하지 않기")){
        $tour5_name="";
        $tour5_id_no="";
        $tour5="";
        $tour_days5="";
    }

    if(strstr($tour6_name,"선택하지 않기")){
        $tour6_name="";
        $tour6_id_no="";
        $tour6="";
        $tour_days6="";
    }


    $tour_days=str_replace(" ","",trim($tour_days));
    $tour_days2=str_replace(" ","",trim($tour_days2));
    $tour_days3=str_replace(" ","",trim($tour_days3));
    $tour_days4=str_replace(" ","",trim($tour_days4));
    $tour_days5=str_replace(" ","",trim($tour_days5));
    $tour_days6=str_replace(" ","",trim($tour_days6));
    if(substr($tour_days,0,1)==",") $tour_days = substr($tour_days,1);
    if(substr($tour_days2,0,1)==",") $tour_days2 = substr($tour_days2,1);
    if(substr($tour_days3,0,1)==",") $tour_days3 = substr($tour_days3,1);
    if(substr($tour_days4,0,1)==",") $tour_days4 = substr($tour_days4,1);
    if(substr($tour_days5,0,1)==",") $tour_days5 = substr($tour_days5,1);
    if(substr($tour_days6,0,1)==",") $tour_days6 = substr($tour_days6,1);
    if(substr($tour_days,-1)==",") $tour_days = substr($tour_days,0,-1);
    if(substr($tour_days,-1)==",") $tour_days = substr($tour_days,0,-1);
    if(substr($tour_days2,-1)==",") $tour_days2 = substr($tour_days2,0,-1);
    if(substr($tour_days3,-1)==",") $tour_days3 = substr($tour_days3,0,-1);
    if(substr($tour_days4,-1)==",") $tour_days4 = substr($tour_days4,0,-1);
    if(substr($tour_days5,-1)==",") $tour_days5 = substr($tour_days5,0,-1);
    if(substr($tour_days6,-1)==",") $tour_days6 = substr($tour_days6,0,-1);

    $staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

    $reg_date = date('Y/m/d');
    $reg_date2 = date('H:i:s');

    $price = rnf2($price);

    $add_text7 = (rnf2($add_text7))? rnf2($add_text7) : 0;
    $add_text8 = (rnf2($people))? rnf2($people) : 0;
    $add_text9 = $add_text7 * $add_text8;


    $hotel_days = chk_opt_text($hotel_days);
    $hotel_days2 = chk_opt_text($hotel_days2);
    $hotel_days3 = chk_opt_text($hotel_days3);
    $hotel_days4 = chk_opt_text($hotel_days4);
    $hotel_days5 = chk_opt_text($hotel_days5);

    $cal_price_origin=rnf2($cal_price_origin);
    $cal_price1=rnf2($cal_price1);
    $cal_price_origin2=rnf2($cal_price_origin2);
    $cal_price_origin2_2=rnf2($cal_price_origin2_2);
    $cal_price_origin2_3=rnf2($cal_price_origin2_3);
    $cal_price_origin2_4=rnf2($cal_price_origin2_4);
    $cal_price1_b=rnf2($cal_price1_b);
    $cal_price1_b_2=rnf2($cal_price1_b_2);
    $cal_price1_b_3=rnf2($cal_price1_b_3);
    $cal_price1_b_4=rnf2($cal_price1_b_4);
    $cal_price_origin3=rnf2($cal_price_origin3);
    $cal_price_origin3_2=rnf2($cal_price_origin3_2);
    $cal_price1_b2=rnf2($cal_price1_b2);
    $cal_price1_b2_2=rnf2($cal_price1_b2_2);
    $cal_price_origin4=rnf2($cal_price_origin4);
    $cal_price_origin4_2=rnf2($cal_price_origin4_2);
    $cal_price1_b3=rnf2($cal_price1_b3);
    $cal_price1_b3_2=rnf2($cal_price1_b3_2);
    $cal_price_origin5=rnf2($cal_price_origin5);
    $cal_price_origin5_2=rnf2($cal_price_origin5_2);
    $cal_price_origin5_3=rnf2($cal_price_origin5_3);
    $cal_price1_b4=rnf2($cal_price1_b4);
    $cal_price1_b4_2=rnf2($cal_price1_b4_2);
    $cal_price1_b4_3=rnf2($cal_price1_b4_3);
    $cal_price2=rnf2($cal_price2);
    $cal_price3=rnf2($cal_price3);
    $cal_amount=rnf2($cal_amount);

    $cal2_price_origin=rnf2($cal2_price_origin);
    $cal2_price1=rnf2($cal2_price1);
    $cal2_price_origin2=rnf2($cal2_price_origin2);
    $cal2_price_origin2_2=rnf2($cal2_price_origin2_2);
    $cal2_price_origin2_3=rnf2($cal2_price_origin2_3);
    $cal2_price_origin2_4=rnf2($cal2_price_origin2_4);
    $cal2_price1_b=rnf2($cal2_price1_b);
    $cal2_price1_b_2=rnf2($cal2_price1_b_2);
    $cal2_price1_b_3=rnf2($cal2_price1_b_3);
    $cal2_price1_b_4=rnf2($cal2_price1_b_4);
    $cal2_price_origin3=rnf2($cal2_price_origin3);
    $cal2_price_origin3_2=rnf2($cal2_price_origin3_2);
    $cal2_price1_b2=rnf2($cal2_price1_b2);
    $cal2_price1_b2_2=rnf2($cal2_price1_b2_2);
    $cal2_price_origin4=rnf2($cal2_price_origin4);
    $cal2_price_origin4_2=rnf2($cal2_price_origin4_2);
    $cal2_price1_b3=rnf2($cal2_price1_b3);
    $cal2_price1_b3_2=rnf2($cal2_price1_b3_2);
    $cal2_price_origin5=rnf2($cal2_price_origin5);
    $cal2_price_origin5_2=rnf2($cal2_price_origin5_2);
    $cal2_price_origin5_3=rnf2($cal2_price_origin5_3);
    $cal2_price1_b4=rnf2($cal2_price1_b4);
    $cal2_price1_b4_2=rnf2($cal2_price1_b4_2);
    $cal2_price1_b4_3=rnf2($cal2_price1_b4_3);
    $cal2_price2=rnf2($cal2_price2);
    $cal2_price3=rnf2($cal2_price3);
    $cal2_amount=rnf2($cal2_amount);

    $sqlInsert="
        insert into cmp_estimate (
            bit_worked,
            bit_read_all,
            staff,
            code,
            golf_name,
            golf_id_no,
            air_id_no,
            d_air_no,
            r_air_no,
            name,
            phone,
            view_path,
            main_staff,
            d_date,
            r_date,
            send_date,
            golf,
            golf2_1_id_no,
            golf2_2_id_no,
            golf2_3_id_no,
            golf2_4_id_no,
            golf2_5_id_no,
            golf2_6_id_no,
            golf2_7_id_no,
            golf2_8_id_no,
            golf2_9_id_no,
            golf2_10_id_no,
            golf2_1_name,
            golf2_2_name,
            golf2_3_name,
            golf2_4_name,
            golf2_5_name,
            golf2_6_name,
            golf2_7_name,
            golf2_8_name,
            golf2_9_name,
            golf2_10_name,
            golf2_1,
            golf2_2,
            golf2_3,
            golf2_4,
            golf2_5,
            golf2_6,
            golf2_7,
            golf2_8,
            golf2_9,
            golf2_10,
            tour_name,
            tour_id_no,
            tour2_name,
            tour2_id_no,
            tour,
            tour_days,
            tour2,
            tour_days2,
            tour3_name,
            tour3_id_no,
            tour3,
            tour_days3,
            tour4_name,
            tour4_id_no,
            tour4,
            tour_days4,
            tour5_name,
            tour5_id_no,
            tour5,
            tour_days5,
            tour6_name,
            tour6_id_no,
            tour6,
            tour_days6,
            hotel,
            hotel2,
            hotel3,
            hotel4,
            hotel5,
            hotel_id_no,
            hotel_name,
            hotel2_id_no,
            hotel2_name,
            hotel3_id_no,
            hotel3_name,
            hotel4_id_no,
            hotel4_name,
            hotel5_id_no,
            hotel5_name,
            room_type,
            people,
            price,
            add_text7,
            add_text8,
            add_text9,
            account,
            email,
            point_dep,
            fax,
            memo,
            plan_type,
            d_air_id_no,
            r_air_id_no,
            d_air_time1,
            d_air_time2,
            r_air_time1,
            r_air_time2,
            subject,
            bit_long_days,
            hotel_days,
            hotel_days2,
            hotel_days3,
            hotel_days4,
            hotel_days5,

            hole1,
            hole2,
            hole3,
            hole4,
            hole5,
            hole6,
            hole7,
            hole8,
            hole9,
            hole10,

            meal_type,

            filename1,
            filename1_real,
            filename2,
            filename2_real,

            d_air_no_m,
            d_air_time1_m,
            d_air_time2_m,

            plan_add1_a,
            plan_add1_b,
            plan_add1_c,
            plan_add1_d,
            plan_add2_a,
            plan_add2_b,
            plan_add2_c,
            plan_add2_d,
            plan_add8_a,
            plan_add8_b,
            plan_add8_c,
            plan_add8_d,
            plan_add9_a,
            plan_add9_b,
            plan_add9_c,
            plan_add9_d,

            cal_price_origin,
            cal_price_rate,
            cal_price1,
            cal_price_origin_txt,
            cal_price_origin2,
            cal_price_rate2,
            cal_price_origin_txt_2,
            cal_price_origin2_2,
            cal_price_rate2_2,
            cal_price_origin_txt_3,
            cal_price_origin2_3,
            cal_price_rate2_3,
            cal_price_origin_txt_4,
            cal_price_origin2_4,
            cal_price_rate2_4,
            cal_price1_b,
            cal_price1_b_2,
            cal_price1_b_3,
            cal_price1_b_4,
            cal_price_origin_txt2,
            cal_price_origin3,
            cal_price_rate3,
            cal_price_origin_txt2_2,
            cal_price_origin3_2,
            cal_price_rate3_2,
            cal_price1_b2,
            cal_price1_b2_2,
            cal_price_origin_txt3,
            cal_price_origin4,
            cal_price_rate4,
            cal_price_origin_txt3_2,
            cal_price_origin4_2,
            cal_price_rate4_2,
            cal_price1_b3,
            cal_price1_b3_2,
            cal_price_origin_txt4,
            cal_price_origin5,
            cal_price_rate5,
            cal_price_origin_txt4_2,
            cal_price_origin5_2,
            cal_price_rate5_2,
            cal_price1_b4,
            cal_price1_b4_2,
            cal_price1_b4_3,
            cal_price2,
            cal_price3,
            cal_amount,
            cal2_price_origin,
            cal2_price_rate,
            cal2_price1,
            cal2_price_origin_txt,
            cal2_price_origin2,
            cal2_price_rate2,
            cal2_price_origin_txt_2,
            cal2_price_origin2_2,
            cal2_price_rate2_2,
            cal2_price_origin_txt_3,
            cal2_price_origin2_3,
            cal2_price_rate2_3,
            cal2_price_origin_txt_4,
            cal2_price_origin2_4,
            cal2_price_rate2_4,
            cal2_price1_b,
            cal2_price1_b_2,
            cal2_price1_b_3,
            cal2_price1_b_4,
            cal2_price_origin_txt2,
            cal2_price_origin3,
            cal2_price_rate3,
            cal2_price_origin_txt2_2,
            cal2_price_origin3_2,
            cal2_price_rate3_2,
            cal2_price1_b2,
            cal2_price1_b2_2,
            cal2_price_origin_txt3,
            cal2_price_origin4,
            cal2_price_rate4,
            cal2_price_origin_txt3_2,
            cal2_price_origin4_2,
            cal2_price_rate4_2,
            cal2_price1_b3,
            cal2_price1_b3_2,
            cal2_price_origin_txt4,
            cal2_price_origin5,
            cal2_price_rate5,
            cal2_price_origin_txt4_2,
            cal2_price_origin5_2,
            cal2_price_origin5_3,
            cal2_price_rate5_2,
            cal2_price1_b4,
            cal2_price1_b4_2,
            cal2_price1_b4_3,
            cal2_price2,
            cal2_price3,
            cal2_amount,
            partner,

            cal_price_origin_txt4_3,
            cal_price_origin5_3,
            cal_price_rate5_3,

            reg_date,
            reg_date2
       ) values (
            '$bit_worked',
            '$bit_read_all',
            '$staff',
            '$code',
            '$golf_name',
            '$golf_id_no',
            '$air_id_no',
            '$d_air_no',
            '$r_air_no',
            '$name',
            '$phone',
            '$view_path',
            '$main_staff',
            '$d_date',
            '$r_date',
            '$send_date',
            '$golf',
            '$golf2_1_id_no',
            '$golf2_2_id_no',
            '$golf2_3_id_no',
            '$golf2_4_id_no',
            '$golf2_5_id_no',
            '$golf2_6_id_no',
            '$golf2_7_id_no',
            '$golf2_8_id_no',
            '$golf2_9_id_no',
            '$golf2_10_id_no',
            '$golf2_1_name',
            '$golf2_2_name',
            '$golf2_3_name',
            '$golf2_4_name',
            '$golf2_5_name',
            '$golf2_6_name',
            '$golf2_7_name',
            '$golf2_8_name',
            '$golf2_9_name',
            '$golf2_10_name',
            '$golf2_1',
            '$golf2_2',
            '$golf2_3',
            '$golf2_4',
            '$golf2_5',
            '$golf2_6',
            '$golf2_7',
            '$golf2_8',
            '$golf2_9',
            '$golf2_10',
            '$tour_name',
            '$tour_id_no',
            '$tour2_name',
            '$tour2_id_no',
            '$tour',
            '$tour_days',
            '$tour2',
            '$tour_days2',
            '$tour3_name',
            '$tour3_id_no',
            '$tour3',
            '$tour_days3',
            '$tour4_name',
            '$tour4_id_no',
            '$tour4',
            '$tour_days4',
            '$tour5_name',
            '$tour5_id_no',
            '$tour5',
            '$tour_days5',
            '$tour6_name',
            '$tour6_id_no',
            '$tour6',
            '$tour_days6',
            '$hotel',
            '$hotel2',
            '$hotel3',
            '$hotel4',
            '$hotel5',
            '$hotel_id_no',
            '$hotel_name',
            '$hotel2_id_no',
            '$hotel2_name',
            '$hotel3_id_no',
            '$hotel3_name',
            '$hotel4_id_no',
            '$hotel4_name',
            '$hotel5_id_no',
            '$hotel5_name',
            '$room_type',
            '$people',
            '$price',
            '$add_text7',
            '$add_text8',
            '$add_text9',
            '$account',
            '$email',
            '$point_dep',
            '$fax',
            '$memo',
            '$plan_type',
            '$d_air_id_no',
            '$r_air_id_no',
            '$d_air_time1',
            '$d_air_time2',
            '$r_air_time1',
            '$r_air_time2',
            '$subject',
            '$bit_long_days',
            '$hotel_days',
            '$hotel_days2',
            '$hotel_days3',
            '$hotel_days4',
            '$hotel_days5',
            '$hole1',
            '$hole2',
            '$hole3',
            '$hole4',
            '$hole5',
            '$hole6',
            '$hole7',
            '$hole8',
            '$hole9',
            '$hole10',
            '$meal_type',
            '$filename1',
            '$filename1_real',
            '$filename2',
            '$filename2_real',
            '$d_air_no_m',
            '$d_air_time1_m',
            '$d_air_time2_m',
            '$plan_add1_a',
            '$plan_add1_b',
            '$plan_add1_c',
            '$plan_add1_d',
            '$plan_add2_a',
            '$plan_add2_b',
            '$plan_add2_c',
            '$plan_add2_d',
            '$plan_add8_a',
            '$plan_add8_b',
            '$plan_add8_c',
            '$plan_add8_d',
            '$plan_add9_a',
            '$plan_add9_b',
            '$plan_add9_c',
            '$plan_add9_d',
            '$cal_price_origin',
            '$cal_price_rate',
            '$cal_price1',
            '$cal_price_origin_txt',
            '$cal_price_origin2',
            '$cal_price_rate2',
            '$cal_price_origin_txt_2',
            '$cal_price_origin2_2',
            '$cal_price_rate2_2',
            '$cal_price_origin_txt_3',
            '$cal_price_origin2_3',
            '$cal_price_rate2_3',
            '$cal_price_origin_txt_4',
            '$cal_price_origin2_4',
            '$cal_price_rate2_4',
            '$cal_price1_b',
            '$cal_price1_b_2',
            '$cal_price1_b_3',
            '$cal_price1_b_4',
            '$cal_price_origin_txt2',
            '$cal_price_origin3',
            '$cal_price_rate3',
            '$cal_price_origin_txt2_2',
            '$cal_price_origin3_2',
            '$cal_price_rate3_2',
            '$cal_price1_b2',
            '$cal_price1_b2_2',
            '$cal_price_origin_txt3',
            '$cal_price_origin4',
            '$cal_price_rate4',
            '$cal_price_origin_txt3_2',
            '$cal_price_origin4_2',
            '$cal_price_rate4_2',
            '$cal_price1_b3',
            '$cal_price1_b3_2',
            '$cal_price_origin_txt4',
            '$cal_price_origin5',
            '$cal_price_rate5',
            '$cal_price_origin_txt4_2',
            '$cal_price_origin5_2',
            '$cal_price_rate5_2',
            '$cal_price1_b4',
            '$cal_price1_b4_2',
            '$cal_price1_b4_3',
            '$cal_price2',
            '$cal_price3',
            '$cal_amount',
            '$cal2_price_origin',
            '$cal2_price_rate',
            '$cal2_price1',
            '$cal2_price_origin_txt',
            '$cal2_price_origin2',
            '$cal2_price_rate2',
            '$cal2_price_origin_txt_2',
            '$cal2_price_origin2_2',
            '$cal2_price_rate2_2',
            '$cal2_price_origin_txt_3',
            '$cal2_price_origin2_3',
            '$cal2_price_rate2_3',
            '$cal2_price_origin_txt_4',
            '$cal2_price_origin2_4',
            '$cal2_price_rate2_4',
            '$cal2_price1_b',
            '$cal2_price1_b_2',
            '$cal2_price1_b_3',
            '$cal2_price1_b_4',
            '$cal2_price_origin_txt2',
            '$cal2_price_origin3',
            '$cal2_price_rate3',
            '$cal2_price_origin_txt2_2',
            '$cal2_price_origin3_2',
            '$cal2_price_rate3_2',
            '$cal2_price1_b2',
            '$cal2_price1_b2_2',
            '$cal2_price_origin_txt3',
            '$cal2_price_origin4',
            '$cal2_price_rate4',
            '$cal2_price_origin_txt3_2',
            '$cal2_price_origin4_2',
            '$cal2_price_rate4_2',
            '$cal2_price1_b3',
            '$cal2_price1_b3_2',
            '$cal2_price_origin_txt4',
            '$cal2_price_origin5',
            '$cal2_price_rate5',
            '$cal2_price_origin_txt4_2',
            '$cal2_price_origin5_2',
            '$cal2_price_origin5_3',
            '$cal2_price_rate5_2',
            '$cal2_price1_b4',
            '$cal2_price1_b4_2',
            '$cal2_price1_b4_3',
            '$cal2_price2',
            '$cal2_price3',
            '$cal2_amount',
            '$partner',
            '$cal_price_origin_txt4_3',
            '$cal_price_origin5_3',
            '$cal_price_rate5_3',
            '$reg_date',
            '$reg_date2'
     )";


    $staffQuery = "";
    if($_SESSION["sessLogin"]["staff_type"]=="ceo" || $_SESSION["sessLogin"]["staff_type"]=="staff" || strstr($main_staff,"(${user_id})")){
        $staffQuery = "main_staff = '$main_staff',";
        $staffQuery .= "staff = '$staff',";
        $staffQuery .= "bit_read_all = '$bit_read_all',";
    }

     $sqlModify="
        update cmp_estimate set
            $upfileQuery1
            $upfileQuery2
            $staffQuery
            bit_worked = '$bit_worked',
            staff = '$staff',
            golf_name = '$golf_name',
            golf_id_no = '$golf_id_no',
            air_id_no = '$air_id_no',
            d_air_no = '$d_air_no',
            r_air_no = '$r_air_no',
            name = '$name',
            phone = '$phone',
            view_path = '$view_path',
            d_date = '$d_date',
            r_date = '$r_date',
            send_date = '$send_date',
            golf = '$golf',
            golf2_1_id_no='$golf2_1_id_no',
            golf2_2_id_no='$golf2_2_id_no',
            golf2_3_id_no='$golf2_3_id_no',
            golf2_4_id_no='$golf2_4_id_no',
            golf2_5_id_no='$golf2_5_id_no',
            golf2_6_id_no='$golf2_6_id_no',
            golf2_7_id_no='$golf2_7_id_no',
            golf2_8_id_no='$golf2_8_id_no',
            golf2_9_id_no='$golf2_9_id_no',
            golf2_10_id_no='$golf2_10_id_no',
            golf2_1_name='$golf2_1_name',
            golf2_2_name='$golf2_2_name',
            golf2_3_name='$golf2_3_name',
            golf2_4_name='$golf2_4_name',
            golf2_5_name='$golf2_5_name',
            golf2_6_name='$golf2_6_name',
            golf2_7_name='$golf2_7_name',
            golf2_8_name='$golf2_8_name',
            golf2_9_name='$golf2_9_name',
            golf2_10_name='$golf2_10_name',
            golf2_1='$golf2_1',
            golf2_2='$golf2_2',
            golf2_3='$golf2_3',
            golf2_4='$golf2_4',
            golf2_5='$golf2_5',
            golf2_6='$golf2_6',
            golf2_7='$golf2_7',
            golf2_8='$golf2_8',
            golf2_9='$golf2_9',
            golf2_10='$golf2_10',

            tour_name='$tour_name',
            tour_id_no='$tour_id_no',
            tour2_name='$tour2_name',
            tour2_id_no='$tour2_id_no',
            tour='$tour',
            tour_days='$tour_days',
            tour2='$tour2',
            tour_days2='$tour_days2',

            tour3_name='$tour3_name',
            tour3_id_no='$tour3_id_no',
            tour3='$tour3',
            tour_days3='$tour_days3',
            tour4_name='$tour4_name',
            tour4_id_no='$tour4_id_no',
            tour4='$tour4',
            tour_days4='$tour_days4',
            tour5_name='$tour5_name',
            tour5_id_no='$tour5_id_no',
            tour5='$tour5',
            tour_days5='$tour_days5',
            tour6_name='$tour6_name',
            tour6_id_no='$tour6_id_no',
            tour6='$tour6',
            tour_days6='$tour_days6',

            hotel = '$hotel',
            hotel2 = '$hotel2',
            hotel3 = '$hotel3',
            hotel4 = '$hotel4',
            hotel5 = '$hotel5',
            hotel_id_no='$hotel_id_no',
            hotel_name='$hotel_name',
            hotel2_id_no='$hotel2_id_no',
            hotel2_name='$hotel2_name',
            hotel3_id_no='$hotel3_id_no',
            hotel3_name='$hotel3_name',
            hotel4_id_no='$hotel4_id_no',
            hotel4_name='$hotel4_name',
            hotel5_id_no='$hotel5_id_no',
            hotel5_name='$hotel5_name',
            room_type='$room_type',
            people = '$people',
            price = '$price',
            add_text7 = '$add_text7',
            add_text8 = '$add_text8',
            add_text9 = '$add_text9',
            account = '$account',
            email = '$email',
            point_dep = '$point_dep',
            fax = '$fax',
            plan_type = '$plan_type',
            d_air_id_no='$d_air_id_no',
            r_air_id_no='$r_air_id_no',
            d_air_time1 = '$d_air_time1',
            d_air_time2 = '$d_air_time2',
            r_air_time1 = '$r_air_time1',
            r_air_time2 = '$r_air_time2',
            subject='$subject',

            bit_long_days='$bit_long_days',
            hotel_days='$hotel_days',
            hotel_days2='$hotel_days2',
            hotel_days3='$hotel_days3',
            hotel_days4='$hotel_days4',
            hotel_days5='$hotel_days5',

            hole1='$hole1',
            hole2='$hole2',
            hole3='$hole3',
            hole4='$hole4',
            hole5='$hole5',
            hole6='$hole6',
            hole7='$hole7',
            hole8='$hole8',
            hole9='$hole9',
            hole10='$hole10',

            meal_type='$meal_type',


            plan_add1_a='$plan_add1_a',
            plan_add1_b='$plan_add1_b',
            plan_add1_c='$plan_add1_c',
            plan_add1_d='$plan_add1_d',
            plan_add2_a='$plan_add2_a',
            plan_add2_b='$plan_add2_b',
            plan_add2_c='$plan_add2_c',
            plan_add2_d='$plan_add2_d',
            plan_add8_a='$plan_add8_a',
            plan_add8_b='$plan_add8_b',
            plan_add8_c='$plan_add8_c',
            plan_add8_d='$plan_add8_d',
            plan_add9_a='$plan_add9_a',
            plan_add9_b='$plan_add9_b',
            plan_add9_c='$plan_add9_c',
            plan_add9_d='$plan_add9_d',

            d_air_no_m='$d_air_no_m',
            d_air_time1_m='$d_air_time1_m',
            d_air_time2_m='$d_air_time2_m',

            partner='$partner',

            cal_price_origin = '$cal_price_origin',
            cal_price_rate = '$cal_price_rate',
            cal_price1 = '$cal_price1',
            cal_price_origin_txt = '$cal_price_origin_txt',
            cal_price_origin2 = '$cal_price_origin2',
            cal_price_rate2 = '$cal_price_rate2',
            cal_price_origin_txt_2 = '$cal_price_origin_txt_2',
            cal_price_origin2_2 = '$cal_price_origin2_2',
            cal_price_rate2_2 = '$cal_price_rate2_2',
            cal_price_origin_txt_3 = '$cal_price_origin_txt_3',
            cal_price_origin2_3 = '$cal_price_origin2_3',
            cal_price_rate2_3 = '$cal_price_rate2_3',
            cal_price_origin_txt_4 = '$cal_price_origin_txt_4',
            cal_price_origin2_4 = '$cal_price_origin2_4',
            cal_price_rate2_4 = '$cal_price_rate2_4',
            cal_price1_b = '$cal_price1_b',
            cal_price1_b_2 = '$cal_price1_b_2',
            cal_price1_b_3 = '$cal_price1_b_3',
            cal_price1_b_4 = '$cal_price1_b_4',
            cal_price_origin_txt2 = '$cal_price_origin_txt2',
            cal_price_origin3 = '$cal_price_origin3',
            cal_price_rate3 = '$cal_price_rate3',
            cal_price_origin_txt2_2 = '$cal_price_origin_txt2_2',
            cal_price_origin3_2 = '$cal_price_origin3_2',
            cal_price_rate3_2 = '$cal_price_rate3_2',
            cal_price1_b2 = '$cal_price1_b2',
            cal_price1_b2_2 = '$cal_price1_b2_2',
            cal_price_origin_txt3 = '$cal_price_origin_txt3',
            cal_price_origin4 = '$cal_price_origin4',
            cal_price_rate4 = '$cal_price_rate4',
            cal_price_origin_txt3_2 = '$cal_price_origin_txt3_2',
            cal_price_origin4_2 = '$cal_price_origin4_2',
            cal_price_rate4_2 = '$cal_price_rate4_2',
            cal_price1_b3 = '$cal_price1_b3',
            cal_price1_b3_2 = '$cal_price1_b3_2',
            cal_price_origin_txt4 = '$cal_price_origin_txt4',
            cal_price_origin5 = '$cal_price_origin5',
            cal_price_rate5 = '$cal_price_rate5',
            cal_price_origin_txt4_2 = '$cal_price_origin_txt4_2',
            cal_price_origin5_2 = '$cal_price_origin5_2',
            cal_price_rate5_2 = '$cal_price_rate5_2',
            cal_price1_b4 = '$cal_price1_b4',
            cal_price1_b4_2 = '$cal_price1_b4_2',
            cal_price1_b4_3 = '$cal_price1_b4_3',
            cal_price2 = '$cal_price2',
            cal_price3 = '$cal_price3',
            cal_amount = '$cal_amount',
            cal2_price_origin = '$cal2_price_origin',
            cal2_price_rate = '$cal2_price_rate',
            cal2_price1 = '$cal2_price1',
            cal2_price_origin_txt = '$cal2_price_origin_txt',
            cal2_price_origin2 = '$cal2_price_origin2',
            cal2_price_rate2 = '$cal2_price_rate2',
            cal2_price_origin_txt_2 = '$cal2_price_origin_txt_2',
            cal2_price_origin2_2 = '$cal2_price_origin2_2',
            cal2_price_rate2_2 = '$cal2_price_rate2_2',
            cal2_price_origin_txt_3 = '$cal2_price_origin_txt_3',
            cal2_price_origin2_3 = '$cal2_price_origin2_3',
            cal2_price_rate2_3 = '$cal2_price_rate2_3',
            cal2_price_origin_txt_4 = '$cal2_price_origin_txt_4',
            cal2_price_origin2_4 = '$cal2_price_origin2_4',
            cal2_price_rate2_4 = '$cal2_price_rate2_4',
            cal2_price1_b = '$cal2_price1_b',
            cal2_price1_b_2 = '$cal2_price1_b_2',
            cal2_price1_b_3 = '$cal2_price1_b_3',
            cal2_price1_b_4 = '$cal2_price1_b_4',
            cal2_price_origin_txt2 = '$cal2_price_origin_txt2',
            cal2_price_origin3 = '$cal2_price_origin3',
            cal2_price_rate3 = '$cal2_price_rate3',
            cal2_price_origin_txt2_2 = '$cal2_price_origin_txt2_2',
            cal2_price_origin3_2 = '$cal2_price_origin3_2',
            cal2_price_rate3_2 = '$cal2_price_rate3_2',
            cal2_price1_b2 = '$cal2_price1_b2',
            cal2_price1_b2_2 = '$cal2_price1_b2_2',
            cal2_price_origin_txt3 = '$cal2_price_origin_txt3',
            cal2_price_origin4 = '$cal2_price_origin4',
            cal2_price_rate4 = '$cal2_price_rate4',
            cal2_price_origin_txt3_2 = '$cal2_price_origin_txt3_2',
            cal2_price_origin4_2 = '$cal2_price_origin4_2',
            cal2_price_rate4_2 = '$cal2_price_rate4_2',
            cal2_price1_b3 = '$cal2_price1_b3',
            cal2_price1_b3_2 = '$cal2_price1_b3_2',
            cal2_price_origin_txt4 = '$cal2_price_origin_txt4',
            cal2_price_origin5 = '$cal2_price_origin5',
            cal2_price_rate5 = '$cal2_price_rate5',
            cal2_price_origin_txt4_2 = '$cal2_price_origin_txt4_2',
            cal2_price_origin5_2 = '$cal2_price_origin5_2',
            cal2_price_rate5_2 = '$cal2_price_rate5_2',
            cal2_price1_b4 = '$cal2_price1_b4',
            cal2_price1_b4_2 = '$cal2_price1_b4_2',
            cal2_price1_b4_3 = '$cal2_price1_b4_3',
            cal2_price2 = '$cal2_price2',
            cal2_price3 = '$cal2_price3',
            cal2_amount = '$cal2_amount',

            cal_price_origin_txt4_3 = '$cal_price_origin_txt4_3',
            cal_price_origin5_3 = '$cal_price_origin5_3',
            cal_price_rate5_3 = '$cal_price_rate5_3',

            cal2_price_origin_txt4_3 = '$cal2_price_origin_txt4_3',
            cal2_price_origin5_3 = '$cal2_price_origin5_3',
            cal2_price_rate5_3 = '$cal2_price_rate5_3',
            memo = '$memo'
        where id_no='$id_no'
     ";




    if($id_no){
        $sql =$sqlModify;
        $url = "view_${filecode}.php?id_no=$id_no&ctg1=$ctg1";
    }else{
        $sql =$sqlInsert;
        $url = "list_${filecode}.php?ctg1=$ctg1";
    }

    if($dbo->query($sql)){

        $ext_url1 = short_url($long_url1);
        $ext_url2 = short_url($long_url2);

        $sql="delete from cmp_ex_link where code='$code'";
        $dbo->query($sql);

        $sql="
           insert into cmp_ex_link (
              code,
              url,
              url2
          ) values (
              '$code',
              '$ext_url1',
              '$ext_url2'
        )";
        $dbo->query($sql);

        If($id_no) echo"<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();location.href='$url'</script>";
        Else echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();self.close()</script>";

    }else{
        checkVar(mysql_error(),$sql);
    }
    exit;

}elseif ($mode=="drop"){

    for($i = 0; $i < count($check);$i++){

        $sql = "delete from $table where code = $check[$i]";
        $dbo->query($sql);

    }
    redirect2("list_${filecode}.php?ctg1=$ctg1");exit;

}elseif($mode=="golf"){

    $golf = trim($golf);
    $sql = "select * from cmp_golf where name like '%$golf%' order by nation asc,city asc, name asc";
    list($rows) = $dbo->query($sql);
    while($rs=$dbo->next_record()){
        $KEY .= ",$rs[nation] > $rs[city] > $rs[name]";
        $VAL .= ",".$rs[id_no];
    }
    $str = ($rows)? "선택하세요":"검색된 상품명 없습니다.";
    echo "
        <select name='golf_tmp' id='golf_tmp' onchange=\"set_golf(this.options[this.selectedIndex].text,this.value)\" style='width:190px'>
        ";
            echo option_str($str.$KEY,$VAL);
    echo "
        <option value='$golf'>$golf</option>
        </select>
    ";
    exit;

}elseif($mode=="golf2"){

    $golf = str_replace(" ","",trim($golf));
    $sql = "select * from cmp_golf2 where replace(name,' ','') like '%$golf%' order by nation asc,city asc, name asc";
    list($rows) = $dbo->query($sql);
    while($rs=$dbo->next_record()){
        $KEY .= ",$rs[nation] > $rs[city] > $rs[name]";
        $VAL .= ",".$rs[id_no];
    }
    $str = ($rows)? "선택하세요":"검색된 골프장이 없습니다.";
    echo "
        <select name='${id}_tmp' id='${id}_tmp' onchange=\"set_golf2('$id',this.options[this.selectedIndex].text,this.value)\" style='width:190px'>
        ";
            echo option_str($str.$KEY,$VAL);
    echo "
        <option value='$golf'>$golf</option>
        </select>
    ";
    exit;

}elseif($mode=="hotel"){

    $hotel = str_replace(" ","",trim($hotel));
    $sql = "select * from cmp_hotel where replace(name,' ','') like '%$hotel%' order by nation asc,city asc, name asc";
    list($rows) = $dbo->query($sql);
    while($rs=$dbo->next_record()){
        $KEY .= ",$rs[nation] > $rs[city] > $rs[name]";
        $VAL .= ",".$rs[id_no];
    }
    $str = ($rows)? "선택하세요":"검색된 호텔명이 없습니다.";

    $set_hotel = ($bit)? "set_hotel".$bit :"set_hotel";

    echo "
        <select name='hotel_tmp' id='hotel_tmp' onchange=\"${set_hotel}(this.options[this.selectedIndex].text,this.value)\" style='width:190px'>
        ";
            echo option_str($str.$KEY,$VAL);
    echo "
        <option value='$hotel'>$hotel</option>
        </select>
    ";
    exit;

}elseif($mode=="tour"){

    $tour = str_replace(" ","",trim($tour));
    $sql = "select * from cmp_tour where replace(name,' ','') like '%$tour%' order by nation asc,city asc, name asc";
    list($rows) = $dbo->query($sql);
    while($rs=$dbo->next_record()){
        $KEY .= ",$rs[nation] > $rs[city] > $rs[name]";
        $VAL .= ",".$rs[id_no];
    }
    $str = ($rows)? "선택하세요":"검색된 관광지명이 없습니다.";

    $set_tour = ($bit)? "set_tour2":"set_tour";

    echo "
        <select name='tour_tmp' id='tour_tmp' onchange=\"${set_tour}(this.options[this.selectedIndex].text,this.value,'$bit')\" style='width:190px'>
        ";
            echo option_str($str.$KEY,$VAL);
    /*
    echo "
        <option value='$tour'>$tour</option>
        </select>
    ";
    */
    echo "
        <option value=''>선택하지 않기</option>
        </select>
    ";
    exit;

}elseif($mode=="etc"){

    $sql = "
        select
            a.*,
            (select max(id_no) from cmp_partner where company=a.partner) as partner_id_no
            from cmp_golf as a
            where id_no=$golf_id_no
        ";
    $dbo->query($sql);
    $rs=$dbo->next_record();

    $arr  =explode(">",$rs[golf2_1_name]);  $rs[golf2_1] = trim($arr[count($arr)-1]);
    $arr  =explode(">",$rs[golf2_2_name]);  $rs[golf2_2] = trim($arr[count($arr)-1]);
    $arr  =explode(">",$rs[golf2_3_name]);  $rs[golf2_3] = trim($arr[count($arr)-1]);
    $arr  =explode(">",$rs[golf2_4_name]);  $rs[golf2_4] = trim($arr[count($arr)-1]);
    $arr  =explode(">",$rs[golf2_5_name]);  $rs[golf2_5] = trim($arr[count($arr)-1]);
    $arr  =explode(">",$rs[golf2_6_name]);  $rs[golf2_6] = trim($arr[count($arr)-1]);
    $arr  =explode(">",$rs[golf2_7_name]);  $rs[golf2_7] = trim($arr[count($arr)-1]);
    $arr  =explode(">",$rs[golf2_8_name]);  $rs[golf2_8] = trim($arr[count($arr)-1]);
    $arr  =explode(">",$rs[golf2_9_name]);  $rs[golf2_9] = trim($arr[count($arr)-1]);
    $arr  =explode(">",$rs[golf2_10_name]); $rs[golf2_10] = trim($arr[count($arr)-1]);
    $arr  =explode(">",$rs[hotel_name]);    $rs[hotel] = trim($arr[count($arr)-1]);
    $arr  =explode(">",$rs[hotel2_name]);   $rs[hotel2] = trim($arr[count($arr)-1]);
    $arr  =explode(">",$rs[hotel3_name]);   $rs[hotel3] = trim($arr[count($arr)-1]);
    $arr  =explode(">",$rs[hotel4_name]);   $rs[hotel4] = trim($arr[count($arr)-1]);
    $arr  =explode(">",$rs[hotel5_name]);   $rs[hotel5] = trim($arr[count($arr)-1]);

    echo "<script>
            parent.document.getElementById('golf2_1_name').value='$rs[golf2_1_name]';
            parent.document.getElementById('golf2_1_id_no').value='$rs[golf2_1_id_no]';
            parent.document.getElementById('golf2_2_name').value='$rs[golf2_2_name]';
            parent.document.getElementById('golf2_2_id_no').value='$rs[golf2_2_id_no]';
            parent.document.getElementById('golf2_3_name').value='$rs[golf2_3_name]';
            parent.document.getElementById('golf2_3_id_no').value='$rs[golf2_3_id_no]';
            parent.document.getElementById('golf2_4_name').value='$rs[golf2_4_name]';
            parent.document.getElementById('golf2_4_id_no').value='$rs[golf2_4_id_no]';

            parent.document.getElementById('golf2_5_name').value='$rs[golf2_5_name]';
            parent.document.getElementById('golf2_5_id_no').value='$rs[golf2_5_id_no]';

            parent.document.getElementById('golf2_6_name').value='$rs[golf2_6_name]';
            parent.document.getElementById('golf2_6_id_no').value='$rs[golf2_6_id_no]';

            parent.document.getElementById('golf2_7_name').value='$rs[golf2_7_name]';
            parent.document.getElementById('golf2_7_id_no').value='$rs[golf2_7_id_no]';

            parent.document.getElementById('golf2_8_name').value='$rs[golf2_8_name]';
            parent.document.getElementById('golf2_8_id_no').value='$rs[golf2_8_id_no]';

            parent.document.getElementById('golf2_9_name').value='$rs[golf2_9_name]';
            parent.document.getElementById('golf2_9_id_no').value='$rs[golf2_9_id_no]';

            parent.document.getElementById('golf2_10_name').value='$rs[golf2_10_name]';
            parent.document.getElementById('golf2_10_id_no').value='$rs[golf2_10_id_no]';

            parent.document.getElementById('hotel_name').value='$rs[hotel_name]';
            parent.document.getElementById('hotel_id_no').value='$rs[hotel_id_no]';
            parent.document.getElementById('hotel2_name').value='$rs[hotel2_name]';
            parent.document.getElementById('hotel2_id_no').value='$rs[hotel2_id_no]';
            parent.document.getElementById('hotel3_name').value='$rs[hotel3_name]';
            parent.document.getElementById('hotel3_id_no').value='$rs[hotel3_id_no]';
            parent.document.getElementById('hotel4_name').value='$rs[hotel4_name]';
            parent.document.getElementById('hotel4_id_no').value='$rs[hotel4_id_no]';
            parent.document.getElementById('hotel5_name').value='$rs[hotel5_name]';
            parent.document.getElementById('hotel5_id_no').value='$rs[hotel5_id_no]';

            parent.document.getElementById('golf2_1').value='$rs[golf2_1]';
            parent.document.getElementById('golf2_2').value='$rs[golf2_2]';
            parent.document.getElementById('golf2_3').value='$rs[golf2_3]';
            parent.document.getElementById('golf2_4').value='$rs[golf2_4]';
            parent.document.getElementById('golf2_5').value='$rs[golf2_5]';
            parent.document.getElementById('golf2_6').value='$rs[golf2_6]';
            parent.document.getElementById('golf2_7').value='$rs[golf2_7]';
            parent.document.getElementById('golf2_8').value='$rs[golf2_8]';
            parent.document.getElementById('golf2_9').value='$rs[golf2_9]';
            parent.document.getElementById('golf2_10').value='$rs[golf2_10]';
            parent.document.getElementById('hotel').value='$rs[hotel]';
            parent.document.getElementById('hotel2').value='$rs[hotel2]';
            parent.document.getElementById('hotel3').value='$rs[hotel3]';
            parent.document.getElementById('hotel4').value='$rs[hotel4]';
            parent.document.getElementById('hotel5').value='$rs[hotel5]';

            parent.document.getElementById('partner').value='$rs[partner]';
        </script>";

    exit;

}elseif($mode=="price"){

    $price_customer_input = nf(rnf2($price_customer_input));
    $price_last = nf(rnf2($price) - rnf2($price_customer_input));
    echo "
        <script>
            parent.document.getElementById('price_customer_input').value='$price_customer_input';
            parent.document.getElementById('price_last').value='$price_last';
        </script>
    ";
    exit;

}elseif($mode=="cal_sum"){

    $cal_price_origin=rnf2($cal_price_origin);
    $cal_price_origin2=rnf2($cal_price_origin2);
    $cal_price_origin2_2=rnf2($cal_price_origin2_2);
    $cal_price_origin2_3=rnf2($cal_price_origin2_3);
    $cal_price_origin2_4=rnf2($cal_price_origin2_4);
    $cal_price_origin3=rnf2($cal_price_origin3);
    $cal_price_origin3_2=rnf2($cal_price_origin3_2);
    $cal_price_origin4=rnf2($cal_price_origin4);
    $cal_price_origin4_2=rnf2($cal_price_origin4_2);
    $cal_price_origin5=rnf2($cal_price_origin5);
    $cal_price_origin5_2=rnf2($cal_price_origin5_2);
    $cal_price_origin5_3=rnf2($cal_price_origin5_3);
    $cal_price1=rnf2($cal_price1);
    $cal_price2=rnf2($cal_price2);
    $cal_price3=rnf2($cal_price3);

    $cal_price1 = $cal_price_origin * $cal_price_rate;
    $cal_price1_b = $cal_price_origin2 * $cal_price_rate2;
    $cal_price1_b_2 = $cal_price_origin2_2 * $cal_price_rate2_2;
    $cal_price1_b_3 = $cal_price_origin2_3 * $cal_price_rate2_3;
    $cal_price1_b_4 = $cal_price_origin2_4 * $cal_price_rate2_4;
    $cal_price1_b2 = $cal_price_origin3 / $cal_price_rate3;
    $cal_price1_b2_2 = $cal_price_origin3_2 / $cal_price_rate3_2;
    $cal_price1_b3 = $cal_price_origin4 / $cal_price_rate4;
    $cal_price1_b3_2 = $cal_price_origin4_2 / $cal_price_rate4_2;
    $cal_price1_b4 = $cal_price_origin5 * $cal_price_rate5;
    $cal_price1_b4_2 = $cal_price_origin5_2 * $cal_price_rate5_2;
    $cal_price1_b4_3 = $cal_price_origin5_3 / $cal_price_rate5_3;

    $cal_amount =$cal_price1 + $cal_price2 + $cal_price3;
    $cal_amount +=$cal_price1_b;
    $cal_amount +=$cal_price1_b_2;
    $cal_amount +=$cal_price1_b_3;
    $cal_amount +=$cal_price1_b_4;
    $cal_amount +=$cal_price1_b2;
    $cal_amount +=$cal_price1_b2_2;
    $cal_amount +=$cal_price1_b3;
    $cal_amount +=$cal_price1_b3_2;
    $cal_amount +=$cal_price1_b4;
    $cal_amount +=$cal_price1_b4_2;
    $cal_amount +=$cal_price1_b4_3;

    $cal_price_origin=nf($cal_price_origin);
    $cal_price_origin2=nf($cal_price_origin2);
    $cal_price_origin2_2=nf($cal_price_origin2_2);
    $cal_price_origin2_3=nf($cal_price_origin2_3);
    $cal_price_origin2_4=nf($cal_price_origin2_4);
    $cal_price_origin3=nf($cal_price_origin3);
    $cal_price_origin3_2=nf($cal_price_origin3_2);
    $cal_price_origin4=nf($cal_price_origin4);
    $cal_price_origin4_2=nf($cal_price_origin4_2);
    $cal_price_origin5=nf($cal_price_origin5);
    $cal_price_origin5_2=nf($cal_price_origin5_2);
    $cal_price_origin5_3=nf($cal_price_origin5_3);
    $cal_price1=nf($cal_price1);
    $cal_price2=nf($cal_price2);
    $cal_price3=nf($cal_price3);
    $cal_amount=nf($cal_amount);

    $cal_price1_b=nf($cal_price1_b);
    $cal_price1_b_2=nf($cal_price1_b_2);
    $cal_price1_b_3=nf($cal_price1_b_3);
    $cal_price1_b_4=nf($cal_price1_b_4);
    $cal_price1_b2=nf($cal_price1_b2);
    $cal_price1_b2_2=nf($cal_price1_b2_2);
    $cal_price1_b3=nf($cal_price1_b3);
    $cal_price1_b3_2=nf($cal_price1_b3_2);
    $cal_price1_b4=nf($cal_price1_b4);
    $cal_price1_b4_2=nf($cal_price1_b4_2);
    $cal_price1_b4_3=nf($cal_price1_b4_3);


    $cal2_price_origin=rnf2($cal2_price_origin);
    $cal2_price_origin2=rnf2($cal2_price_origin2);
    $cal2_price_origin2_2=rnf2($cal2_price_origin2_2);
    $cal2_price_origin2_3=rnf2($cal2_price_origin2_3);
    $cal2_price_origin2_4=rnf2($cal2_price_origin2_4);
    $cal2_price_origin3=rnf2($cal2_price_origin3);
    $cal2_price_origin3_2=rnf2($cal2_price_origin3_2);
    $cal2_price_origin4=rnf2($cal2_price_origin4);
    $cal2_price_origin4_2=rnf2($cal2_price_origin4_2);
    $cal2_price_origin5=rnf2($cal2_price_origin5);
    $cal2_price_origin5_2=rnf2($cal2_price_origin5_2);
    $cal2_price_origin5_3=rnf2($cal2_price_origin5_3);
    $cal2_price1=rnf2($cal2_price1);
    $cal2_price2=rnf2($cal2_price2);
    $cal2_price3=rnf2($cal2_price3);

    $cal2_price1 = $cal2_price_origin * $cal2_price_rate;
    $cal2_price1_b = $cal2_price_origin2 * $cal2_price_rate2;
    $cal2_price1_b_2 = $cal2_price_origin2_2 * $cal2_price_rate2_2;
    $cal2_price1_b_3 = $cal2_price_origin2_3 * $cal2_price_rate2_3;
    $cal2_price1_b_4 = $cal2_price_origin2_4 * $cal2_price_rate2_4;
    $cal2_price1_b2 = $cal2_price_origin3 / $cal2_price_rate3;
    $cal2_price1_b2_2 = $cal2_price_origin3_2 / $cal2_price_rate3_2;
    $cal2_price1_b3 = $cal2_price_origin4 / $cal2_price_rate4;
    $cal2_price1_b3_2 = $cal2_price_origin4_2 / $cal2_price_rate4_2;
    $cal2_price1_b4 = $cal2_price_origin5 * $cal2_price_rate5;
    $cal2_price1_b4_2 = $cal2_price_origin5_2 * $cal2_price_rate5_2;
    $cal2_price1_b4_3 = $cal2_price_origin5_3 / $cal2_price_rate5_3;

    $cal2_amount =$cal2_price1 + $cal2_price2 + $cal2_price3;
    $cal2_amount +=$cal2_price1_b;
    $cal2_amount +=$cal2_price1_b_2;
    $cal2_amount +=$cal2_price1_b_3;
    $cal2_amount +=$cal2_price1_b_4;
    $cal2_amount +=$cal2_price1_b2;
    $cal2_amount +=$cal2_price1_b2_2;
    $cal2_amount +=$cal2_price1_b3;
    $cal2_amount +=$cal2_price1_b3_2;
    $cal2_amount +=$cal2_price1_b4;
    $cal2_amount +=$cal2_price1_b4_2;
    $cal2_amount +=$cal2_price1_b4_3;

    $cal2_price_origin=nf($cal2_price_origin);
    $cal2_price_origin2=nf($cal2_price_origin2);
    $cal2_price_origin2_2=nf($cal2_price_origin2_2);
    $cal2_price_origin2_3=nf($cal2_price_origin2_3);
    $cal2_price_origin2_4=nf($cal2_price_origin2_4);
    $cal2_price_origin3=nf($cal2_price_origin3);
    $cal2_price_origin3_2=nf($cal2_price_origin3_2);
    $cal2_price_origin4=nf($cal2_price_origin4);
    $cal2_price_origin4_2=nf($cal2_price_origin4_2);
    $cal2_price_origin5=nf($cal2_price_origin5);
    $cal2_price_origin5_2=nf($cal2_price_origin5_2);
    $cal2_price_origin5_3=nf($cal2_price_origin5_3);
    $cal2_price1=nf($cal2_price1);
    $cal2_price2=nf($cal2_price2);
    $cal2_price3=nf($cal2_price3);
    $cal2_amount=nf($cal2_amount);

    $cal2_price1_b=nf($cal2_price1_b);
    $cal2_price1_b_2=nf($cal2_price1_b_2);
    $cal2_price1_b_3=nf($cal2_price1_b_3);
    $cal2_price1_b_4=nf($cal2_price1_b_4);
    $cal2_price1_b2=nf($cal2_price1_b2);
    $cal2_price1_b2_2=nf($cal2_price1_b2_2);
    $cal2_price1_b3=nf($cal2_price1_b3);
    $cal2_price1_b3_2=nf($cal2_price1_b3_2);
    $cal2_price1_b4=nf($cal2_price1_b4);
    $cal2_price1_b4_2=nf($cal2_price1_b4_2);
    $cal2_price1_b4_3=nf($cal2_price1_b4_3);

    echo "
        <script>
            parent.document.getElementById('cal_price_origin').value='$cal_price_origin';
            parent.document.getElementById('cal_price_origin2').value='$cal_price_origin2';
            parent.document.getElementById('cal_price_origin2_2').value='$cal_price_origin2_2';
            parent.document.getElementById('cal_price_origin2_3').value='$cal_price_origin2_3';
            parent.document.getElementById('cal_price_origin2_4').value='$cal_price_origin2_4';
            parent.document.getElementById('cal_price_origin3').value='$cal_price_origin3';
            parent.document.getElementById('cal_price_origin3_2').value='$cal_price_origin3_2';
            parent.document.getElementById('cal_price_origin4').value='$cal_price_origin4';
            parent.document.getElementById('cal_price_origin4_2').value='$cal_price_origin4_2';
            parent.document.getElementById('cal_price_origin5').value='$cal_price_origin5';
            parent.document.getElementById('cal_price_origin5_2').value='$cal_price_origin5_2';
            parent.document.getElementById('cal_price_origin5_3').value='$cal_price_origin5_3';
            parent.document.getElementById('cal_price1').value='$cal_price1';
            parent.document.getElementById('cal_price2').value='$cal_price2';
            parent.document.getElementById('cal_price3').value='$cal_price3';
            parent.document.getElementById('cal_amount').value='$cal_amount';

            parent.document.getElementById('cal_price1_b').value='$cal_price1_b';
            parent.document.getElementById('cal_price1_b_2').value='$cal_price1_b_2';
            parent.document.getElementById('cal_price1_b_3').value='$cal_price1_b_3';
            parent.document.getElementById('cal_price1_b_4').value='$cal_price1_b_4';
            parent.document.getElementById('cal_price1_b2').value='$cal_price1_b2';
            parent.document.getElementById('cal_price1_b2_2').value='$cal_price1_b2_2';
            parent.document.getElementById('cal_price1_b3').value='$cal_price1_b3';
            parent.document.getElementById('cal_price1_b3_2').value='$cal_price1_b3_2';
            parent.document.getElementById('cal_price1_b4').value='$cal_price1_b4';
            parent.document.getElementById('cal_price1_b4_2').value='$cal_price1_b4_2';
            parent.document.getElementById('cal_price1_b4_3').value='$cal_price1_b4_3';


            parent.document.getElementById('cal2_price_origin').value='$cal2_price_origin';
            parent.document.getElementById('cal2_price_origin2').value='$cal2_price_origin2';
            parent.document.getElementById('cal2_price_origin2_2').value='$cal2_price_origin2_2';
            parent.document.getElementById('cal2_price_origin2_3').value='$cal2_price_origin2_3';
            parent.document.getElementById('cal2_price_origin2_4').value='$cal2_price_origin2_4';
            parent.document.getElementById('cal2_price_origin3').value='$cal2_price_origin3';
            parent.document.getElementById('cal2_price_origin3_2').value='$cal2_price_origin3_2';
            parent.document.getElementById('cal2_price_origin4').value='$cal2_price_origin4';
            parent.document.getElementById('cal2_price_origin4_2').value='$cal2_price_origin4_2';
            parent.document.getElementById('cal2_price_origin5').value='$cal2_price_origin5';
            parent.document.getElementById('cal2_price_origin5_2').value='$cal2_price_origin5_2';
            parent.document.getElementById('cal2_price_origin5_3').value='$cal2_price_origin5_3';
            parent.document.getElementById('cal2_price1').value='$cal2_price1';
            parent.document.getElementById('cal2_price2').value='$cal2_price2';
            parent.document.getElementById('cal2_price3').value='$cal2_price3';
            parent.document.getElementById('cal2_amount').value='$cal2_amount';

            parent.document.getElementById('cal2_price1_b').value='$cal2_price1_b';
            parent.document.getElementById('cal2_price1_b_2').value='$cal2_price1_b_2';
            parent.document.getElementById('cal2_price1_b_3').value='$cal2_price1_b_3';
            parent.document.getElementById('cal2_price1_b_4').value='$cal2_price1_b_4';
            parent.document.getElementById('cal2_price1_b2').value='$cal2_price1_b2';
            parent.document.getElementById('cal2_price1_b2_2').value='$cal2_price1_b2_2';
            parent.document.getElementById('cal2_price1_b3').value='$cal2_price1_b3';
            parent.document.getElementById('cal2_price1_b3_2').value='$cal2_price1_b3_2';
            parent.document.getElementById('cal2_price1_b4').value='$cal2_price1_b4';
            parent.document.getElementById('cal2_price1_b4_2').value='$cal2_price1_b4_2';
            parent.document.getElementById('cal2_price1_b4_3').value='$cal2_price1_b4_3';

        </script>
    ";
    exit;

}elseif($mode=="jisangbi"){//지상비 불러오기

    /*
    $d_date = $rs[d_date];
    $r_date = $rs[r_date];
    $golf_id_no = $rs[golf_id_no];
    $plan_type = $rs[plan_type];
    */

    $d_date_w = date("w",strtotime($d_date));

    if(!$d_date_w) $d_date_w=6;
    elseif($d_date_w==6) $d_date_w=0;
    else $d_date_w=$d_date_w-1;;

    $sql = "select w_${d_date_w} as amount from cmp_set_price where golf_id_no=$golf_id_no and date_s<='$d_date' and date_e >= '$d_date'";
    $dbo->query($sql);
    $rs=$dbo->next_record();
    $basic = $rs[amount];
    checkVar(mysql_error(),$sql);

    checkVar("plan_type",$plan_type);
    checkVar("golf_id_no",$golf_id_no);
    checkVar("d_date",$d_date);
    checkVar("r_date",$r_date);
    checkVar("d_date_w",$d_date_w);
    checkVar("basic",$basic);

    $date = $d_date;


    $sum_hotel=0;
    $sum_golf=0;
    $first_golf=0;
    $last_golf=0;

    $j=0;
    while($date<=$r_date){

        checkVar("date",$date);

        $sql = "select price from cmp_set_price_hotel where golf_id_no=$golf_id_no and date_s<='$date' and date_e >= '$date'";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        $sum_hotel  += $rs[price];

        $sql = "select price as golf_price from cmp_set_price_golf where golf_id_no=$golf_id_no and date_s<='$date' and date_e >= '$date'";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        $sum_golf+= $rs[golf_price];
        if($rs[golf_price]) $j++;
        if($j==1) $first_golf =$rs[golf_price];
        $last_golf =$rs[golf_price];

        $date = date("Y/m/d",strtotime($date ." +1 day"));

    }

    checkVar("sum_hotel",$sum_hotel);
    checkVar("sum_golf",$sum_golf);

    checkVar("first_golf",$first_golf);
    checkVar("last_golf",$last_golf);

    if($plan_type=="A" || $plan_type=="G" || $plan_type=="H"){
    }
    elseif($plan_type=="B"){
        $sum_golf -= $last_golf;
    }
    elseif($plan_type=="C" || $plan_type=="F"){
        $sum_golf += $first_golf;
        $sum_golf -= $last_golf;
    }
    elseif($plan_type=="D" || $plan_type=="E"){
        $sum_golf += $first_golf;
    }


    $total =  $basic + $sum_hotel + $sum_golf;
    $total_ = nf($total);

    checkVar("total",$total);

    /*
    A(조석) 가는날,오는날
    B(조조) 가는날,오는날-1
    C(석조) 가는날+1,오는날-1
    D(석석) 가는날+1,오는날

    E(석석)
    F(석조)
    G(조석)
    H(조석)
    */

    echo "
        <script>
            parent.document.getElementById('cal_price_origin').value='$total_';
            parent.document.getElementById('cal_price_rate').value='1';
            parent.document.getElementById('cal_price1').value='$total_';
        </script>
    ";

}elseif($mode=="file_drop"){

        $sql = "select filename${no} as f from $table where code='$code' and id_no=$id_no";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        //checkVar(mysql_error(),$sql);

        if($rs[f]){
            @unlink("../../public/cmp_files/${rs[file]}");
            //checkVar("","../../public/cmp_files/${rs[file]}");
        }
        $sql = "update $table set filename${no}='' where code='$code' and id_no=$id_no";
        $dbo->query($sql);
        //checkVar(mysql_error(),$sql);exit;
        back();exit;


}else{

    //191120 s
    //거래처 불러오기
    $PARTNERS = "";
    $sql = "select * from cmp_partner order by company asc";
    $dbo->query($sql);
    //if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql);
    while($rs= $dbo->next_record()){
        $PARTNERS .= ",$rs[company]";
        $PARTNERS2 .= ",$rs[id_no]";
    }
    $PARTNERS = substr($PARTNERS,1);
    $PARTNERS2 = substr($PARTNERS2,1);
    //191120 e


    $sql = "select * from $table where id_no=$id_no";
    $dbo->query($sql);
    $rs= $dbo->next_record();
    $code=$rs[code];

    $rs[price] = nf($rs[price]);
    $rs[price_prev] = nf($rs[price_prev]);
    $rs[price_customer_input] = nf($rs[price_customer_input]);
    $rs[price_tmp_output] = nf($rs[price_tmp_output]);
    $rs[price_last] = nf($rs[price_last]);
    $arr  =explode(">",$rs[golf_name]);
    $rs[golf] = trim($arr[count($arr)-1]);

    $rs[cal_price_origin]=nf($rs[cal_price_origin]);
    $rs[cal_price1]=nf($rs[cal_price1]);
    $rs[cal_price_origin2]=nf($rs[cal_price_origin2]);
    $rs[cal_price_origin2_2]=nf($rs[cal_price_origin2_2]);
    $rs[cal_price_origin2_3]=nf($rs[cal_price_origin2_3]);
    $rs[cal_price_origin2_4]=nf($rs[cal_price_origin2_4]);
    $rs[cal_price1_b]=nf($rs[cal_price1_b]);
    $rs[cal_price1_b_2]=nf($rs[cal_price1_b_2]);
    $rs[cal_price1_b_3]=nf($rs[cal_price1_b_3]);
    $rs[cal_price1_b_4]=nf($rs[cal_price1_b_4]);
    $rs[cal_price_origin3]=nf($rs[cal_price_origin3]);
    $rs[cal_price_origin3_2]=nf($rs[cal_price_origin3_2]);
    $rs[cal_price1_b2]=nf($rs[cal_price1_b2]);
    $rs[cal_price1_b2_2]=nf($rs[cal_price1_b2_2]);
    $rs[cal_price_origin4]=nf($rs[cal_price_origin4]);
    $rs[cal_price_origin4_2]=nf($rs[cal_price_origin4_2]);
    $rs[cal_price1_b3]=nf($rs[cal_price1_b3]);
    $rs[cal_price1_b3_2]=nf($rs[cal_price1_b3_2]);
    $rs[cal_price_origin5]=nf($rs[cal_price_origin5]);
    $rs[cal_price_origin5_2]=nf($rs[cal_price_origin5_2]);
    $rs[cal_price_origin5_3]=nf($rs[cal_price_origin5_3]);
    $rs[cal_price1_b4]=nf($rs[cal_price1_b4]);
    $rs[cal_price1_b4_2]=nf($rs[cal_price1_b4_2]);
    $rs[cal_price1_b4_3]=nf($rs[cal_price1_b4_3]);
    $rs[cal_price2]=nf($rs[cal_price2]);
    $rs[cal_price3]=nf($rs[cal_price3]);
    $rs[cal_amount]=nf($rs[cal_amount]);

    $rs[cal2_price_origin]=nf($rs[cal2_price_origin]);
    $rs[cal2_price1]=nf($rs[cal2_price1]);
    $rs[cal2_price_origin2]=nf($rs[cal2_price_origin2]);
    $rs[cal2_price_origin2_2]=nf($rs[cal2_price_origin2_2]);
    $rs[cal2_price_origin2_3]=nf($rs[cal2_price_origin2_3]);
    $rs[cal2_price_origin2_4]=nf($rs[cal2_price_origin2_4]);
    $rs[cal2_price1_b]=nf($rs[cal2_price1_b]);
    $rs[cal2_price1_b_2]=nf($rs[cal2_price1_b_2]);
    $rs[cal2_price1_b_3]=nf($rs[cal2_price1_b_3]);
    $rs[cal2_price1_b_4]=nf($rs[cal2_price1_b_4]);
    $rs[cal2_price_origin3]=nf($rs[cal2_price_origin3]);
    $rs[cal2_price_origin3_2]=nf($rs[cal2_price_origin3_2]);
    $rs[cal2_price1_b2]=nf($rs[cal2_price1_b2]);
    $rs[cal2_price1_b2_2]=nf($rs[cal2_price1_b2_2]);
    $rs[cal2_price_origin4]=nf($rs[cal2_price_origin4]);
    $rs[cal2_price_origin4_2]=nf($rs[cal2_price_origin4_2]);
    $rs[cal2_price1_b3]=nf($rs[cal2_price1_b3]);
    $rs[cal2_price1_b3_2]=nf($rs[cal2_price1_b3_2]);
    $rs[cal2_price_origin5]=nf($rs[cal2_price_origin5]);
    $rs[cal2_price_origin5_2]=nf($rs[cal2_price_origin5_2]);
    $rs[cal2_price_origin5_3]=nf($rs[cal2_price_origin5_3]);
    $rs[cal2_price1_b4]=nf($rs[cal2_price1_b4]);
    $rs[cal2_price1_b4_2]=nf($rs[cal2_price1_b4_2]);
    $rs[cal2_price1_b4_3]=nf($rs[cal2_price1_b4_3]);
    $rs[cal2_price2]=nf($rs[cal2_price2]);
    $rs[cal2_price3]=nf($rs[cal2_price3]);
    $rs[cal2_amount]=nf($rs[cal2_amount]);

    $rs[plan_type]=($rs[plan_type])? $rs[plan_type] : "L";

}

$code = ($code)? $code : getUniqNo();
$rs[main_staff] =($rs[main_staff])?$rs[main_staff]:"$sessLogin[name] ($sessLogin[id])";
if(!$rs[send_date]) $rs[send_date] = date("Y/m/d");

if(!$rs[id_no]) $rs[partner] = "이룸투어 자체수배";


//$url_basic = ($cp_url)? $cp_url : "http://irumtour.net";
$url_basic = "http://irumtour.net";
$long_url1 =  "${url_basic}/new/bkoff/cmp/form06.html?code=". str_replace("+","{p}",encrypt($rs[id_no],$SALT));
$long_url2 =  "${url_basic}/new/bkoff/cmp/form08.html?code=". str_replace("+","{p}",encrypt($rs[id_no],$SALT));

?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
    var fm = document.fmData;

    if(check_blank(fm.name,'고객명을',0)=='wrong'){return }
    if(check_select(fm.view_path,'경로를')=='wrong'){return }
    if(check_select(fm.partner,'거래처를')=='wrong'){return }
    fm.submit();

}

function find_golf(){
newWin('pop_find_goods.php?bit=1',800,400,1,1,'','goods');
}

function find_golf2(id){

    var id_no = $("#"+id+"_id_no").val();
    newWin('pop_find_golf.php?id='+id+'&id_no='+id_no,800,400,1,1,'','customer')

}


function find_tour(bit){
    newWin('pop_find_tour.php?bit='+bit,800,400,1,1,'','ptour');
}

function find_hotel(bit){
    newWin('pop_find_hotel.php?bit='+bit,800,400,1,1,'','ptour');
}

function set_golf(k,v){
    $("#golf_name").val(k);
    $("#golf_id_no").val(v);

    $('#actarea').load('<?=SELF?>', {
        'mode': 'etc',
        'golf_id_no': v
    });

}

function set_golf2(id,k,v){
    $("#"+id+"_name").val(k);
    $("#"+id+"_id_no").val(v);
}

function find_golf_clear(id){
    if(confirm('선택한 골프장을 제외하시겠습니까?')){
        $("#"+id).val('');
        $("#"+id+"_name").val('');
        $("#"+id+"_id_no").val('');
    }
}

function set_hotel(k,v){
    $("#hotel_name").val(k);
    $("#hotel_id_no").val(v);
}

function set_hotel2(k,v){
    $("#hotel2_name").val(k);
    $("#hotel2_id_no").val(v);
}

function set_hotel3(k,v){
    $("#hotel3_name").val(k);
    $("#hotel3_id_no").val(v);
}

function set_hotel4(k,v){
    $("#hotel4_name").val(k);
    $("#hotel4_id_no").val(v);
}

function set_hotel5(k,v){
    $("#hotel5_name").val(k);
    $("#hotel5_id_no").val(v);
}

function find_hotel_clear(n){
    if(n==undefined) n="";
    if(confirm('선택한 호텔을 제외하시겠습니까?')){
        $("#hotel"+n).val('');
        $("#hotel"+n+"_name").val('');
        $("#hotel"+n+"_id_no").val('');
    }
}


function set_tour(k,v,n){
    $("#tour_name").val(k);
    $("#tour_id_no").val(v);
}

function set_tour2(k,v,n){
    $("#tour"+n+"_name").val(k);
    $("#tour"+n+"_id_no").val(v);
}

function find_tour_clear(n){
    if(confirm('선택한 관광지를 제외하시겠습니까?')){
        $("#tour"+n).val('');
        $("#tour"+n+"_name").val('');
        $("#tour"+n+"_id_no").val('');
    }
}


function pop_win(){
    var fm = document.fmData;
    if(check_blank(fm.people,'총인원을',0)=='wrong'){return }
    if(fm.people.value==0){alert('총인원을 입력하세요.');fm.people.value='';fm.people.select();return }
    newWin('pop_estimate.php?code=<?=$code?>&people='+document.getElementById('people').value,1200,500,1,1,'','pop_estimate');
}


function air_info(){
    var fm = document.fmData;
    if(fm.golf_id_no.value==""){alert("상품명을 먼저 선택하세요.");return }
    newWin('pop_air.php?golf_id_no='+fm.golf_id_no.value,1200,670,1,1,'','pop_air');
}

function etc_info(){
    var fm = document.fmData;
    if(fm.golf_id_no.value==""){alert("상품명을 먼저 선택하세요.");return }
    newWin('pop_etc.php?golf_id_no='+fm.golf_id_no.value,1200,670,1,1,'','pop_air');
}

function get_price_last(){
    var price=$("#price").val();
    var price_prev=$("#price_prev").val();
    var price_customer_input=$("#price_customer_input").val();
    var url ="<?=SELF?>?mode=price";
    url += "&price=" + price;
    url += "&price_prev=" + price_prev;
    url += "&price_customer_input=" + price_customer_input;
    actarea.location.href=url;

}

function data_copy(){
    if(confirm("고객별 예약 정보 관리대장으로 자료를 복사하시겠습니까?")){
        location.href="form06.html?id_no=<?=$id_no?>&e2r_copy=1"; //일정표를 저장하고 가기 위해 링크 변경
        //location.href="estimate_to_reservation.php?id_no=<?=$id_no?>";
    }
}

function cal_sum(){
    var url = "<?=SELF?>?mode=cal_sum&id_no=<?=$id_no?>";
    url +="&cal_price_origin="+ $("#cal_price_origin").val();
    url +="&cal_price_origin2="+ $("#cal_price_origin2").val();
    url +="&cal_price_origin2_2="+ $("#cal_price_origin2_2").val();
    url +="&cal_price_origin2_3="+ $("#cal_price_origin2_3").val();
    url +="&cal_price_origin2_4="+ $("#cal_price_origin2_4").val();
    url +="&cal_price_origin3="+ $("#cal_price_origin3").val();
    url +="&cal_price_origin3_2="+ $("#cal_price_origin3_2").val();
    url +="&cal_price_origin4="+ $("#cal_price_origin4").val();
    url +="&cal_price_origin4_2="+ $("#cal_price_origin4_2").val();
    url +="&cal_price_origin5="+ $("#cal_price_origin5").val();
    url +="&cal_price_origin5_2="+ $("#cal_price_origin5_2").val();
    url +="&cal_price_origin5_3="+ $("#cal_price_origin5_3").val();
    url +="&cal_price_rate="+ $("#cal_price_rate").val();
    url +="&cal_price_rate2="+ $("#cal_price_rate2").val();
    url +="&cal_price_rate2_2="+ $("#cal_price_rate2_2").val();
    url +="&cal_price_rate2_3="+ $("#cal_price_rate2_3").val();
    url +="&cal_price_rate2_4="+ $("#cal_price_rate2_4").val();
    url +="&cal_price_rate3="+ $("#cal_price_rate3").val();
    url +="&cal_price_rate3_2="+ $("#cal_price_rate3_2").val();
    url +="&cal_price_rate4="+ $("#cal_price_rate4").val();
    url +="&cal_price_rate4_2="+ $("#cal_price_rate4_2").val();
    url +="&cal_price_rate5="+ $("#cal_price_rate5").val();
    url +="&cal_price_rate5_2="+ $("#cal_price_rate5_2").val();
    url +="&cal_price_rate5_3="+ $("#cal_price_rate5_3").val();
    url +="&cal_price1="+ $("#cal_price1").val();
    url +="&cal_price2="+ $("#cal_price2").val();
    url +="&cal_price3="+ $("#cal_price3").val();

    url +="&cal2_price_origin="+ $("#cal2_price_origin").val();
    url +="&cal2_price_origin2="+ $("#cal2_price_origin2").val();
    url +="&cal2_price_origin2_2="+ $("#cal2_price_origin2_2").val();
    url +="&cal2_price_origin2_3="+ $("#cal2_price_origin2_3").val();
    url +="&cal2_price_origin2_4="+ $("#cal2_price_origin2_4").val();
    url +="&cal2_price_origin3="+ $("#cal2_price_origin3").val();
    url +="&cal2_price_origin3_2="+ $("#cal2_price_origin3_2").val();
    url +="&cal2_price_origin4="+ $("#cal2_price_origin4").val();
    url +="&cal2_price_origin4_2="+ $("#cal2_price_origin4_2").val();
    url +="&cal2_price_origin5="+ $("#cal2_price_origin5").val();
    url +="&cal2_price_origin5_2="+ $("#cal2_price_origin5_2").val();
    url +="&cal2_price_origin5_3="+ $("#cal2_price_origin5_3").val();
    url +="&cal2_price_rate="+ $("#cal2_price_rate").val();
    url +="&cal2_price_rate2="+ $("#cal2_price_rate2").val();
    url +="&cal2_price_rate2_2="+ $("#cal2_price_rate2_2").val();
    url +="&cal2_price_rate2_3="+ $("#cal2_price_rate2_3").val();
    url +="&cal2_price_rate2_4="+ $("#cal2_price_rate2_4").val();
    url +="&cal2_price_rate3="+ $("#cal2_price_rate3").val();
    url +="&cal2_price_rate3_2="+ $("#cal2_price_rate3_2").val();
    url +="&cal2_price_rate4="+ $("#cal2_price_rate4").val();
    url +="&cal2_price_rate4_2="+ $("#cal2_price_rate4_2").val();
    url +="&cal2_price_rate5="+ $("#cal2_price_rate5").val();
    url +="&cal2_price_rate5_2="+ $("#cal2_price_rate5_2").val();
    url +="&cal2_price_rate5_3="+ $("#cal2_price_rate5_3").val();
    url +="&cal2_price1="+ $("#cal2_price1").val();
    url +="&cal2_price2="+ $("#cal2_price2").val();
    url +="&cal2_price3="+ $("#cal2_price3").val();

    actarea.location.href=url;
}

function find(){

    var name = $("#name").val();
    if(name==""){alert("고객명을 입력하세요.");$("#name").focus();return;}

    newWin('pop_qnacustomer2.php?page=origin&name='+name,950,400,1,1,'','customer')

}

function air_info_api(bit){
    var fm = document.fmData;
    var url ="pop_air_cn.php";
    if(bit==2) url ="pop_air_ko.php";
    url +="?golf_id_no="+fm.golf_id_no.value;
    url +="&d_date="+fm.d_date.value;
    url +="&r_date="+fm.r_date.value;
    url +="&api=r";
    if(fm.golf_id_no.value==""){alert("상품명을 먼저 선택하세요.");return }
    newWin(url,1200,670,1,1,'','pop_air');
}

jQuery(function($){

    $(".num").keypress(function(event){
        if(event.which && (event.which < 48 || event.which > 57)){
            event.preventDefault();
        }
    });

    $("#golf2_1").on("change",function(){if(this.value==""){$("#golf2_1_name").val('');$("#golf2_1_id_no").val('')}});
    $("#golf2_2").on("change",function(){if(this.value==""){$("#golf2_2_name").val('');$("#golf2_2_id_no").val('')}});
    $("#golf2_3").on("change",function(){if(this.value==""){$("#golf2_3_name").val('');$("#golf2_3_id_no").val('')}});
    $("#golf2_4").on("change",function(){if(this.value==""){$("#golf2_4_name").val('');$("#golf2_4_id_no").val('')}});
    $("#golf2_5").on("change",function(){if(this.value==""){$("#golf2_5_name").val('');$("#golf2_5_id_no").val('')}});
    $("#golf2_6").on("change",function(){if(this.value==""){$("#golf2_6_name").val('');$("#golf2_6_id_no").val('')}});
    $("#golf2_7").on("change",function(){if(this.value==""){$("#golf2_7_name").val('');$("#golf2_7_id_no").val('')}});
    $("#golf2_8").on("change",function(){if(this.value==""){$("#golf2_8_name").val('');$("#golf2_8_id_no").val('')}});
    $("#golf2_9").on("change",function(){if(this.value==""){$("#golf2_9_name").val('');$("#golf2_9_id_no").val('')}});
    $("#golf2_10").on("change",function(){if(this.value==""){$("#golf2_10_name").val('');$("#golf2_10_id_no").val('')}});
    $("#hotel").on("change",function(){if(this.value==""){$("#hotel_name").val('');$("#hotel_id_no").val('')}});
    $("#hotel2").on("change",function(){if(this.value==""){$("#hotel2_name").val('');$("#hotel2_id_no").val('')}});
    $("#hotel3").on("change",function(){if(this.value==""){$("#hotel3_name").val('');$("#hotel3_id_no").val('')}});
    $("#hotel4").on("change",function(){if(this.value==""){$("#hotel4_name").val('');$("#hotel4_id_no").val('')}});
    $("#hotel5").on("change",function(){if(this.value==""){$("#hotel5_name").val('');$("#hotel5_id_no").val('')}});



    $( "#d_date" ).datepicker({
      dateFormat: "yy/mm/dd",
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 3,
      onClose: function( selectedDate ) {
        $( "#r_date" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#r_date" ).datepicker({
      dateFormat: "yy/mm/dd",
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 2,
      onClose: function( selectedDate ) {
        $( "#d_date" ).datepicker( "option", "maxDate", selectedDate );
      }
    });

    $('#name').keypress(function(e){
        if(e.which == 13) find();
    });

    $('#golf').keypress(function(e){
        if(e.which == 13) find_golf();
    });

    $('#golf2_1').keypress(function(e){
        if(e.which == 13) find_golf2('golf2_1');
    });

    $('#golf2_2').keypress(function(e){
        if(e.which == 13) find_golf2('golf2_2');
    });

    $('#golf2_3').keypress(function(e){
        if(e.which == 13) find_golf2('golf2_3');
    });

    $('#golf2_4').keypress(function(e){
        if(e.which == 13) find_golf2('golf2_4');
    });

    $('#golf2_5').keypress(function(e){
        if(e.which == 13) find_golf2('golf2_5');
    });

    $('#golf2_6').keypress(function(e){
        if(e.which == 13) find_golf2('golf2_6');
    });

    $('#golf2_7').keypress(function(e){
        if(e.which == 13) find_golf2('golf2_7');
    });

    $('#golf2_8').keypress(function(e){
        if(e.which == 13) find_golf2('golf2_8');
    });

    $('#golf2_9').keypress(function(e){
        if(e.which == 13) find_golf2('golf2_9');
    });

    $('#golf2_10').keypress(function(e){
        if(e.which == 13) find_golf2('golf2_10');
    });

    $('#hotel').keypress(function(e){
        if(e.which == 13) find_hotel();
    });


    $("#tbl_normal input").on("change",function(){
        cal_sum();
    });

    $("#tbl_normal2 input").on("change",function(){
        cal_sum();
    });

    $("#tbl_normal input").on("focus",function(){
        this.select();
    });

    $("#golf").css("border",0);

});
</script>


<!-- 지상비 불러오기 -->
<script type="text/javascript">
<!--
$(function(){
    /*
    $("#d_date").on("change",function(){jisangbi();});
    $("#r_date").on("change",function(){jisangbi();});
    $(".plan_type").on("click",function(){jisangbi();});
    */

    $(".numberic").on("focus",function(){
        $(this).select();
    });
});

function jisangbi(){

    var d_date = $("#d_date").val();
    var r_date = $("#r_date").val();
    var plan_type = $(':radio[name="plan_type"]:checked').val();
    var golf_id_no= $("#golf_id_no").val();

    var url = "<?=SELF?>?mode=jisangbi";
    url += "&d_date="+ d_date;
    url += "&r_date="+ r_date;
    url += "&plan_type="+ plan_type;
    url += "&golf_id_no="+ golf_id_no;
    actarea.location.href=url;
}

function file_drop(no)
{
    var url="<?=SELF?>?mode=file_drop&code=<?=$code?>&id_no=<?=$id_no?>&assort=<?=$assort?>&no="+no;
    if(confirm('삭제하시겠습니까?')){
        location.href=url;
    }

}

function read_golf(id){
    let golf_id = id+"_id_no";
    let golf_id_no = $("#"+golf_id).val();
    if(golf_id_no=="0" || golf_id_no==""){
        alert("선택된 골프장이 없습니다.");
        return;
    }else{
        let url = "./read_golf.php";
        url +="?id_no="+golf_id_no;
        newWin(url,870,700,0,0,'golf_detail');
    }
}
//-->
</script>
<!--// 지상비 불러오기 -->

<style type="text/css">
.readonly{border:0}
</style>

<div style="padding:0 10px 0 10px">

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="title_con" style="padding-left:10px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
      </tr>
      <tr>
        <td> </td>
      </tr>
       <tr>
        <td background="../images/common/bg_title.gif" height="5"></td>
      </tr>
    </table>

    <!--내용이 들어가는 곳 시작-->

    <br>

    <table border="0" cellspacing="1" cellpadding="3" width="100%">
        <form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
        <input type="hidden" name="mode" value='save'>
        <input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
        <input type="hidden" name="code" value='<?=$code?>'>
        <input type="hidden" name="golf_name" id="golf_name" value='<?=$rs[golf_name]?>'>
        <input type="hidden" name="golf_id_no" id="golf_id_no" value='<?=$rs[golf_id_no]?>'>


        <input type="hidden" name="air_id_no" id="air_id_no" value='<?=$rs[air_id_no]?>'>
        <input type="hidden" name="d_air_no" id="d_air_no" value='<?=$rs[d_air_no]?>'>
        <input type="hidden" name="d_air_no_m" id="d_air_no_m" value='<?=$rs[d_air_no_m]?>'>
        <input type="hidden" name="r_air_no" id="r_air_no" value='<?=$rs[r_air_no]?>'>
        <input type="hidden" name="d_air_time1" id="d_air_time1" value='<?=$rs[d_air_time1]?>'>
        <input type="hidden" name="d_air_time2" id="d_air_time2" value='<?=$rs[d_air_time2]?>'>
        <input type="hidden" name="d_air_time1_m" id="d_air_time1_m" value='<?=$rs[d_air_time1_m]?>'>
        <input type="hidden" name="d_air_time2_m" id="d_air_time2_m" value='<?=$rs[d_air_time2_m]?>'>
        <input type="hidden" name="r_air_time1" id="r_air_time1" value='<?=$rs[r_air_time1]?>'>
        <input type="hidden" name="r_air_time2" id="r_air_time2" value='<?=$rs[r_air_time2]?>'>

        <input type="hidden" name="golf2_1_name" id="golf2_1_name" value='<?=$rs[golf2_1_name]?>'>
        <input type="hidden" name="golf2_1_id_no" id="golf2_1_id_no" value='<?=$rs[golf2_1_id_no]?>'>
        <input type="hidden" name="golf2_2_name" id="golf2_2_name" value='<?=$rs[golf2_2_name]?>'>
        <input type="hidden" name="golf2_2_id_no" id="golf2_2_id_no" value='<?=$rs[golf2_2_id_no]?>'>
        <input type="hidden" name="golf2_3_name" id="golf2_3_name" value='<?=$rs[golf2_3_name]?>'>
        <input type="hidden" name="golf2_3_id_no" id="golf2_3_id_no" value='<?=$rs[golf2_3_id_no]?>'>
        <input type="hidden" name="golf2_4_name" id="golf2_4_name" value='<?=$rs[golf2_4_name]?>'>
        <input type="hidden" name="golf2_4_id_no" id="golf2_4_id_no" value='<?=$rs[golf2_4_id_no]?>'>

        <input type="hidden" name="golf2_5_name" id="golf2_5_name" value='<?=$rs[golf2_5_name]?>'>
        <input type="hidden" name="golf2_5_id_no" id="golf2_5_id_no" value='<?=$rs[golf2_5_id_no]?>'>
        <input type="hidden" name="golf2_6_name" id="golf2_6_name" value='<?=$rs[golf2_6_name]?>'>
        <input type="hidden" name="golf2_6_id_no" id="golf2_6_id_no" value='<?=$rs[golf2_6_id_no]?>'>

        <input type="hidden" name="golf2_7_name" id="golf2_7_name" value='<?=$rs[golf2_7_name]?>'>
        <input type="hidden" name="golf2_7_id_no" id="golf2_7_id_no" value='<?=$rs[golf2_7_id_no]?>'>

        <input type="hidden" name="golf2_8_name" id="golf2_8_name" value='<?=$rs[golf2_8_name]?>'>
        <input type="hidden" name="golf2_8_id_no" id="golf2_8_id_no" value='<?=$rs[golf2_8_id_no]?>'>

        <input type="hidden" name="golf2_9_name" id="golf2_9_name" value='<?=$rs[golf2_9_name]?>'>
        <input type="hidden" name="golf2_9_id_no" id="golf2_9_id_no" value='<?=$rs[golf2_9_id_no]?>'>

        <input type="hidden" name="golf2_10_name" id="golf2_10_name" value='<?=$rs[golf2_10_name]?>'>
        <input type="hidden" name="golf2_10_id_no" id="golf2_10_id_no" value='<?=$rs[golf2_10_id_no]?>'>


        <input type="hidden" name="d_air_id_no" id="d_air_id_no" value='<?=$rs[d_air_id_no]?>'>
        <input type="hidden" name="r_air_id_no" id="r_air_id_no" value='<?=$rs[r_air_id_no]?>'>

        <input type="hidden" name="hotel_name" id="hotel_name" value='<?=$rs[hotel_name]?>'>
        <input type="hidden" name="hotel_id_no" id="hotel_id_no" value='<?=$rs[hotel_id_no]?>'>

        <input type="hidden" name="hotel2_name" id="hotel2_name" value='<?=$rs[hotel2_name]?>'>
        <input type="hidden" name="hotel2_id_no" id="hotel2_id_no" value='<?=$rs[hotel2_id_no]?>'>

        <input type="hidden" name="hotel3_name" id="hotel3_name" value='<?=$rs[hotel3_name]?>'>
        <input type="hidden" name="hotel3_id_no" id="hotel3_id_no" value='<?=$rs[hotel3_id_no]?>'>

        <input type="hidden" name="hotel4_name" id="hotel4_name" value='<?=$rs[hotel4_name]?>'>
        <input type="hidden" name="hotel4_id_no" id="hotel4_id_no" value='<?=$rs[hotel4_id_no]?>'>

        <input type="hidden" name="hotel5_name" id="hotel5_name" value='<?=$rs[hotel5_name]?>'>
        <input type="hidden" name="hotel5_id_no" id="hotel5_id_no" value='<?=$rs[hotel5_id_no]?>'>


        <input type="hidden" name="tour_name" id="tour_name" value='<?=$rs[tour_name]?>'>
        <input type="hidden" name="tour_id_no" id="tour_id_no" value='<?=$rs[tour_id_no]?>'>

        <input type="hidden" name="tour2_name" id="tour2_name" value='<?=$rs[tour2_name]?>'>
        <input type="hidden" name="tour2_id_no" id="tour2_id_no" value='<?=$rs[tour2_id_no]?>'>
        <input type="hidden" name="tour3_name" id="tour3_name" value='<?=$rs[tour3_name]?>'>
        <input type="hidden" name="tour3_id_no" id="tour3_id_no" value='<?=$rs[tour3_id_no]?>'>
        <input type="hidden" name="tour4_name" id="tour4_name" value='<?=$rs[tour4_name]?>'>
        <input type="hidden" name="tour4_id_no" id="tour4_id_no" value='<?=$rs[tour4_id_no]?>'>
        <input type="hidden" name="tour5_name" id="tour5_name" value='<?=$rs[tour5_name]?>'>
        <input type="hidden" name="tour5_id_no" id="tour5_id_no" value='<?=$rs[tour5_id_no]?>'>
        <input type="hidden" name="tour6_name" id="tour6_name" value='<?=$rs[tour6_name]?>'>
        <input type="hidden" name="tour6_id_no" id="tour6_id_no" value='<?=$rs[tour6_id_no]?>'>
        <input type="hidden" name="long_url1" id="long_url1" value='<?=$long_url1?>'>
        <input type="hidden" name="long_url2" id="long_url2" value='<?=$long_url2?>'>


        <tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td class="subject" width="15%">고객명</td>
          <td>
               <?=html_input('name',20,50)?> <span class="btn_pack medium bold"><a href="javascript:find()"> 검색 </a></span>

               <label><input type="checkbox" name="bit_worked" id="bit_worked" value="1" <?=($rs[bit_worked])?'checked':''?>> 미처리</label>
          </td>

          <td class="subject">연락처</td>
          <td>
               <?=html_input('phone',30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">경로</td>
          <td>
               <select name="view_path">
               <option value="">선택</option>
               <?=option_str($VIEW_PATH,$VIEW_PATH,$rs[view_path])?>
               </select>
          </td>

          <td class="subject">담당자</td>
          <td>
               <?if($_SESSION["sessLogin"]["staff_type"]=="ceo" || $_SESSION["sessLogin"]["staff_type"]=="staff" || strstr($rs[main_staff],"($user_id)")){?>
                   <select name="main_staff">
                    <?=option_str($STAFF,$STAFF,$rs[main_staff])?>
                   </select>

                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                   <span>
                       <label><input type="checkbox" name="bit_read_all" value="1" <?=($rs[bit_read_all])?'checked':''?>>모두 보기</label>
                   </span>
               <?}else{?>
                   <?=$rs[main_staff]?>
               <?}?>

          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">출발지역</td>
          <td>
               <?=html_input('point_dep',30,40,'box ')?>
          </td>

          <td class="subject">팩스</td>
          <td>
                <?=html_input('fax',30,40,'box ')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">출국일</td>
          <td>
               <?=html_input('d_date',13,10,'box ')?>
          </td>

          <td class="subject">귀국일</td>
          <td>
                <?=html_input('r_date',13,10,'box ')?>

                <label><input type="checkbox" name="bit_long_days" id="bit_long_days" value="1" <?=($rs[bit_long_days])?'checked':''?>> 장기간 체류</label>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">발송일</td>
          <td colspan="3">
               <?=html_input('send_date',13,10,'box dateinput')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">제목</td>
          <td colspan="3">
                <?=html_input('subject',60,140,'box ')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">상품명</td>
          <td colspan="3">
               <span id="golf_wrap"></span>
               <?=html_input('golf',30,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_golf()"> 검색 </a></span>

               <?if($rs[golf_id_no]){?>
               &nbsp;&nbsp;&nbsp;
               (상품코드 : <a href="javascript:newWin('view_golf.php?id_no=<?=$rs[golf_id_no]?>',870,700,1,1,'golf')"><?=$rs[golf_id_no]?></a>)
               <?}?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <!-- 191120 s -->
        <tr>
          <td class="subject">거래처</td>
          <td colspan="3">
            <select name="partner" id="partner">
            <option value=""></option>
            <?=option_str($PARTNERS,$PARTNERS,$rs[partner]);?>
            </select>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <!-- 191120 e -->

        <tr>
          <td class="subject">골프장명</td>
          <td colspan="3">
               <?
               $rs[hole1]=($rs[hole1])? $rs[hole1] : "18홀";
               $rs[hole2]=($rs[hole2])? $rs[hole2] : "18홀";
               $rs[hole3]=($rs[hole3])? $rs[hole3] : "18홀";
               $rs[hole4]=($rs[hole4])? $rs[hole4] : "18홀";
               $rs[hole5]=($rs[hole5])? $rs[hole5] : "18홀";
               $rs[hole6]=($rs[hole6])? $rs[hole6] : "18홀";
               $rs[hole7]=($rs[hole7])? $rs[hole7] : "18홀";
               $rs[hole8]=($rs[hole8])? $rs[hole8] : "18홀";
               $rs[hole9]=($rs[hole9])? $rs[hole9] : "18홀";
               $rs[hole10]=($rs[hole10])? $rs[hole10] : "18홀";
               ?>
               <span id="golf2_1_wrap"></span>
               <?=html_input('golf2_1',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_1')"> 검색 </a></span> <span class="btn_pack medium bold"><a href="javascript:find_golf_clear('golf2_1')"> 지우기 </a></span>
               <select name="hole1"><?=option_str($HOLE,$HOLE,$rs[hole1])?></select>
               <span class="btn_pack medium bold"><a href="javascript:read_golf('golf2_1')">상세정보</a></span>

               <br/>
               <span id="golf2_2_wrap"></span>
               <?=html_input('golf2_2',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_2')"> 검색 </a></span> <span class="btn_pack medium bold"><a href="javascript:find_golf_clear('golf2_2')"> 지우기 </a></span>
               <select name="hole2"><?=option_str($HOLE,$HOLE,$rs[hole2])?></select>
               <span class="btn_pack medium bold"><a href="javascript:read_golf('golf2_2')">상세정보</a></span>
               <br/>
               <span id="golf2_3_wrap"></span>
               <?=html_input('golf2_3',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_3')"> 검색 </a></span> <span class="btn_pack medium bold"><a href="javascript:find_golf_clear('golf2_3')"> 지우기 </a></span>
               <select name="hole3"><?=option_str($HOLE,$HOLE,$rs[hole3])?></select>
               <span class="btn_pack medium bold"><a href="javascript:read_golf('golf2_3')">상세정보</a></span>
               <br/>
               <span id="golf2_4_wrap"></span>
               <?=html_input('golf2_4',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_4')"> 검색 </a></span> <span class="btn_pack medium bold"><a href="javascript:find_golf_clear('golf2_4')"> 지우기 </a></span>
               <select name="hole4"><?=option_str($HOLE,$HOLE,$rs[hole4])?></select>
               <span class="btn_pack medium bold"><a href="javascript:read_golf('golf2_4')">상세정보</a></span>
               <br/>

               <span id="golf2_5_wrap"></span>
               <?=html_input('golf2_5',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_5')"> 검색 </a></span> <span class="btn_pack medium bold"><a href="javascript:find_golf_clear('golf2_5')"> 지우기 </a></span>
               <select name="hole5"><?=option_str($HOLE,$HOLE,$rs[hole5])?></select>
               <span class="btn_pack medium bold"><a href="javascript:read_golf('golf2_5')">상세정보</a></span>
               <br/>
               <span id="golf2_6_wrap"></span>
               <?=html_input('golf2_6',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_6')"> 검색 </a></span> <span class="btn_pack medium bold"><a href="javascript:find_golf_clear('golf2_6')"> 지우기 </a></span>
               <select name="hole6"><?=option_str($HOLE,$HOLE,$rs[hole6])?></select>
               <span class="btn_pack medium bold"><a href="javascript:read_golf('golf2_6')">상세정보</a></span>

               <br/>
               <span id="golf2_7_wrap"></span>
               <?=html_input('golf2_7',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_7')"> 검색 </a></span> <span class="btn_pack medium bold"><a href="javascript:find_golf_clear('golf2_7')"> 지우기 </a></span>
               <select name="hole7"><?=option_str($HOLE,$HOLE,$rs[hole7])?></select>
               <span class="btn_pack medium bold"><a href="javascript:read_golf('golf2_7')">상세정보</a></span>

               <br/>
               <span id="golf2_8_wrap"></span>
               <?=html_input('golf2_8',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_8')"> 검색 </a></span> <span class="btn_pack medium bold"><a href="javascript:find_golf_clear('golf2_8')"> 지우기 </a></span>
               <select name="hole8"><?=option_str($HOLE,$HOLE,$rs[hole8])?></select>
               <span class="btn_pack medium bold"><a href="javascript:read_golf('golf2_8')">상세정보</a></span>

               <br/>
               <span id="golf2_9_wrap"></span>
               <?=html_input('golf2_9',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_9')"> 검색 </a></span> <span class="btn_pack medium bold"><a href="javascript:find_golf_clear('golf2_9')"> 지우기 </a></span>
               <select name="hole9"><?=option_str($HOLE,$HOLE,$rs[hole9])?></select>
               <span class="btn_pack medium bold"><a href="javascript:read_golf('golf2_9')">상세정보</a></span>

               <br/>
               <span id="golf2_10_wrap"></span>
               <?=html_input('golf2_10',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_10')"> 검색 </a></span> <span class="btn_pack medium bold"><a href="javascript:find_golf_clear('golf2_10')"> 지우기 </a></span>
               <select name="hole10"><?=option_str($HOLE,$HOLE,$rs[hole10])?></select>
               <span class="btn_pack medium bold"><a href="javascript:read_golf('golf2_10')">상세정보</a></span>


          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">관광지명</td>
          <td colspan="3">
               <span id="tour_wrap"></span>
               <?=html_input('tour',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_tour('')"> 검색 </a></span>  <span class="btn_pack medium bold"><a href="javascript:find_tour_clear('')"> 지우기 </a></span>


               &nbsp;<?=html_input('tour_days',24,50)?>일차 (콤마로 구분)


               <br/>
               <span id="tour_wrap2"></span>
               <?=html_input('tour2',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_tour(2)"> 검색 </a></span>  <span class="btn_pack medium bold"><a href="javascript:find_tour_clear(2)"> 지우기 </a></span>
               &nbsp;<?=html_input('tour_days2',24,50)?>일차

               <br/>
               <span id="tour_wrap3"></span>
               <?=html_input('tour3',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_tour(3)"> 검색 </a></span>  <span class="btn_pack medium bold"><a href="javascript:find_tour_clear(3)"> 지우기 </a></span>
               &nbsp;<?=html_input('tour_days3',24,50)?>일차

               <br/>
               <span id="tour_wrap4"></span>
               <?=html_input('tour4',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_tour(4)"> 검색 </a></span>  <span class="btn_pack medium bold"><a href="javascript:find_tour_clear(4)"> 지우기 </a></span>
               &nbsp;<?=html_input('tour_days4',24,50)?>일차

               <br/>
               <span id="tour_wrap5"></span>
               <?=html_input('tour5',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_tour(5)"> 검색 </a></span>  <span class="btn_pack medium bold"><a href="javascript:find_tour_clear(5)"> 지우기 </a></span>
               &nbsp;<?=html_input('tour_days5',24,50)?>일차

               <br/>
               <span id="tour_wrap6"></span>
               <?=html_input('tour6',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_tour(6)"> 검색 </a></span>  <span class="btn_pack medium bold"><a href="javascript:find_tour_clear(6)"> 지우기 </a></span>
               &nbsp;<?=html_input('tour_days6',24,50)?>일차

          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">호텔명</td>
          <td colspan="3">
               <span id="hotel_wrap"></span>
               <?=html_input('hotel',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_hotel()"> 검색 </a></span>  <span class="btn_pack medium bold"><a href="javascript:find_hotel_clear()"> 지우기 </a></span>
               &nbsp;<?=html_input('hotel_days',24,50)?>일차 (콤마로 구분)


               <br/>
               <span id="hotel_wrap2"></span>
               <?=html_input('hotel2',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_hotel(2)"> 검색 </a></span>  <span class="btn_pack medium bold"><a href="javascript:find_hotel_clear(2)"> 지우기 </a></span>
               &nbsp;<?=html_input('hotel_days2',24,50)?>일차

               <br/>
               <span id="hotel_wrap3"></span>
               <?=html_input('hotel3',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_hotel(3)"> 검색 </a></span>  <span class="btn_pack medium bold"><a href="javascript:find_hotel_clear(3)"> 지우기 </a></span>
               &nbsp;<?=html_input('hotel_days3',24,50)?>일차

               <br/>
               <span id="hotel_wrap4"></span>
               <?=html_input('hotel4',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_hotel(4)"> 검색 </a></span>  <span class="btn_pack medium bold"><a href="javascript:find_hotel_clear(4)"> 지우기 </a></span>
               &nbsp;<?=html_input('hotel_days4',24,50)?>일차

               <br/>
               <span id="hotel_wrap5"></span>
               <?=html_input('hotel5',45,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_hotel(5)"> 검색 </a></span>  <span class="btn_pack medium bold"><a href="javascript:find_hotel_clear(5)"> 지우기 </a></span>
               &nbsp;<?=html_input('hotel_days5',24,50)?>일차

          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">일정표 TYPE</td>
          <td colspan="3">
               <?=radio($PLAN_TYPE1,$PLAN_TYPE2,$rs[plan_type],'plan_type')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">1일차 식사표기</td>
          <td colspan="3">
               <?
               if(!$rs[id_no]) $rs[meal_type]=2;
               $MEAL_TYPE1="1type(호텔식),2type(불포함)";
               $MEAL_TYPE2="0,2";
               ?>
               <?=radio($MEAL_TYPE1,$MEAL_TYPE2,$rs[meal_type],'meal_type')?>
               (일정표 TYPE A,B,C,D,L형에만 해당합니다.)
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">룸타입</td>
          <td colspan="3">
               <?=$rs[room_type]=($rs[room_type])?$rs[room_type]:"2인1실"?>
               <?=radio($ROOM_TYPE,$ROOM_TYPE,$rs[room_type],'room_type')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject">국내선 항공일정</td>
          <td colspan="3">

            <table class="tbl_normal">
                <tr>
                    <th width="150">구분</th>
                    <th>지역</th>
                    <th>교통편</th>
                    <th>시간</th>
                    <th>여행일정</th>
                </tr>
                <tr>
                    <th>지방출발</th>
                    <td><input type="text" name="plan_add1_a" id="plan_add1_a" value="<?=$rs[plan_add1_a]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add1_b" id="plan_add1_b" value="<?=$rs[plan_add1_b]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add1_c" id="plan_add1_c" value="<?=$rs[plan_add1_c]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add1_d" id="plan_add1_d" value="<?=$rs[plan_add1_d]?>" class="box" size="80" maxlength="100"></td>
                </tr>
                <tr>
                    <th>인천도착</th>
                    <td><input type="text" name="plan_add2_a" id="plan_add2_a" value="<?=$rs[plan_add2_a]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add2_b" id="plan_add2_b" value="<?=$rs[plan_add2_b]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add2_c" id="plan_add2_c" value="<?=$rs[plan_add2_c]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add2_d" id="plan_add2_d" value="<?=$rs[plan_add2_d]?>" class="box" size="80" maxlength="100"></td>
                </tr>
            </table>
            <table class="tbl_normal">
                <tr>
                    <th width="150">인천출발</th>
                    <td><input type="text" name="plan_add8_a" id="plan_add8_a" value="<?=$rs[plan_add8_a]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add8_b" id="plan_add8_b" value="<?=$rs[plan_add8_b]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add8_c" id="plan_add8_c" value="<?=$rs[plan_add8_c]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add8_d" id="plan_add8_d" value="<?=$rs[plan_add8_d]?>" class="box" size="80" maxlength="100"></td>
                </tr>
                <tr>
                    <th>지방도착</th>
                    <td><input type="text" name="plan_add9_a" id="plan_add9_a" value="<?=$rs[plan_add9_a]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add9_b" id="plan_add9_b" value="<?=$rs[plan_add9_b]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add9_c" id="plan_add9_c" value="<?=$rs[plan_add9_c]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add9_d" id="plan_add9_d" value="<?=$rs[plan_add9_d]?>" class="box" size="80" maxlength="100"></td>
                </tr>

            </table>

          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>



        <tr>
          <td class="subject">인원</td>
          <td colspan="3">

               <?=html_input('people',3,3,'box num numberic')?>명
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">판매가</td>
          <td>
               <?=html_input('price',13,10,'box num')?>원
          </td>

          <td class="subject">1인예약금</td>
          <td>
               <?=html_input('add_text7',5,4,'box num')?>
                만원
          </td>


        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">
            <span class="btn_pack medium bold"><a href="javascript:air_info()"> 항공 </a></span>
            <span class="btn_pack medium bold"><a href="javascript:air_info_api(1)"> 해외 </a></span>
            <span class="btn_pack medium bold"><a href="javascript:air_info_api(2)"> 국내 </a></span>

          </td>
          <td colspan="3">

               <?
                    if($rs[d_air_id_no]){
                        $sql3 = "select * from cmp_air where id_no=$rs[d_air_id_no]";
                        $dbo3->query($sql3);
                        $rs3=$dbo3->next_record();
                        $rs[d_air_no] = $rs3[d_air_no];
                        $rs[d_air_time1] = $rs3[d_time_s];
                        $rs[r_air_time2] = $rs3[d_time_e];
                    }

                    if($rs[r_air_id_no]){
                        $sql3 = "select * from cmp_air where id_no=$rs[r_air_id_no]";
                        $dbo3->query($sql3);
                        $rs3=$dbo3->next_record();
                        $rs[r_air_no] = $rs3[r_air_no];
                        $rs[r_air_time1] = $rs3[r_time_s];
                        $rs[r_air_time2] = $rs3[r_time_e];
                    }
               ?>


               <div id="air_info">
                <?if($rs[d_air_no]){?>▶출국편명:<?=$rs[d_air_no]?> (출발시간:<?=$rs[d_air_time1]?> / 도착시간:<?=$rs[d_air_time2]?>)<?}?>
                <?if($rs[d_air_no_m]){?> - 국내선으로 이동 <?=$rs[d_air_no_m]?> (출발시간:<?=$rs[d_air_time1_m]?> / 도착시간:<?=$rs[d_air_time2_m]?>)<?}?>
                <?if($REMOTE_ADDR=="106.246.54.27"){?>(ID_NO:<?=$rs[d_air_id_no]?>)<?}?></div>
               <div id="air_info2"><?if($rs[r_air_no]){?>▶귀국편명:<?=$rs[r_air_no]?> (출발시간:<?=$rs[r_air_time1]?> / 도착시간:<?=$rs[r_air_time2]?>)<?}?> <?if($REMOTE_ADDR=="106.246.54.27"){?>(ID_NO:<?=$rs[r_air_id_no]?>)<?}?></div>

          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <!-- <tr>
          <td class="subject">참고사항</td>
          <td colspan="3">
               <?=html_textarea('etc',0,5)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr> -->


        <tr>
          <td class="subject">정산</td>
          <td colspan="3">
                <table id="tbl_normal" style="width:99%">
                    <tr>
                        <th width="10%">구분</th>
                        <th width="70%">외화</th>
                        <th width="20%">원화</th>
                    </tr>
                    <tr>
                        <th>랜드비총액</th>
                        <td>외화 : <?=html_input("cal_price_origin",10,10,'box numberic2')?> X 환율: <?=html_input("cal_price_rate",10,10,'box numberic2')?>
                        <span class="btn_pack medium bold"><a href="javascript:jisangbi()"> 지상비불러오기 </a></span>
                        <span class="btn_pack medium bold"><a href="javascript:newWin('https://search.naver.com/search.naver?where=nexearch&query=%EC%8B%A4%EC%8B%9C%EA%B0%84+%ED%99%98%EC%9C%A8%EC%A1%B0%ED%9A%8C&ie=utf8&sm=tab_she&qdt=0',720,800,1,1)"> 환율조회 </a></span>
                        </td>
                        <td><?=html_input("cal_price1",15,15,'box readonly numberic2')?></td>
                    </tr>
                    <tr>
                        <th>골프요금</th>
                        <td>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal_price_origin_txt",20,40)?>
                                외화 : <?=html_input("cal_price_origin2",10,10,'box numberic2')?> X 환율: <?=html_input("cal_price_rate2",10,10,'box numberic2')?>
                            </div>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal_price_origin_txt_2",20,40)?>
                                외화 : <?=html_input("cal_price_origin2_2",10,10,'box numberic2')?> X 환율: <?=html_input("cal_price_rate2_2",10,10,'box numberic2')?>
                            </div>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal_price_origin_txt_3",20,40)?>
                                외화 : <?=html_input("cal_price_origin2_3",10,10,'box numberic2')?> X 환율: <?=html_input("cal_price_rate2_3",10,10,'box numberic2')?>
                            </div>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal_price_origin_txt_4",20,40)?>
                                외화 : <?=html_input("cal_price_origin2_4",10,10,'box numberic2')?> X 환율: <?=html_input("cal_price_rate2_4",10,10,'box numberic2')?>
                            </div>
                        </td>
                        <td>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal_price1_b",15,15,'box readonly numberic2')?></div>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal_price1_b_2",15,15,'box readonly numberic2')?></div>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal_price1_b_3",15,15,'box readonly numberic2')?></div>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal_price1_b_4",15,15,'box readonly numberic2')?></div>
                        </td>
                    </tr>
                    <tr>
                        <th>호텔요금</th>
                        <td>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal_price_origin_txt2",20,40)?>
                                금액 : <?=html_input("cal_price_origin3",10,10,'box numberic2')?> &nbsp;/ 인원: <?=html_input("cal_price_rate3",10,10,'box numberic2')?>
                            </div>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal_price_origin_txt2_2",20,40)?>
                                금액 : <?=html_input("cal_price_origin3_2",10,10,'box numberic2')?> &nbsp;/ 인원: <?=html_input("cal_price_rate3_2",10,10,'box numberic2')?>
                            </div>
                        </td>
                        <td>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal_price1_b2",15,15,'box readonly numberic2')?></div>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal_price1_b2_2",15,15,'box readonly numberic2')?></div>
                        </td>
                    </tr>
                    <tr>
                        <th>렌트카요금</th>
                        <td>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal_price_origin_txt3",20,40)?>
                                금액 : <?=html_input("cal_price_origin4",10,10,'box numberic2')?> &nbsp;/ 인원: <?=html_input("cal_price_rate4",10,10,'box numberic2')?>
                            </div>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal_price_origin_txt3_2",20,40)?>
                                금액 : <?=html_input("cal_price_origin4_2",10,10,'box numberic2')?> &nbsp;/ 인원: <?=html_input("cal_price_rate4_2",10,10,'box numberic2')?>
                            </div>
                        </td>
                        <td>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal_price1_b3",15,15,'box readonly numberic2')?></div>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal_price1_b3_2",15,15,'box readonly numberic2')?></div>
                        </td>
                    </tr>
                    <tr>
                        <th>기타</th>
                        <td>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal_price_origin_txt4",20,40)?>
                                외화 : <?=html_input("cal_price_origin5",10,10,'box numberic2')?> X 환율: <?=html_input("cal_price_rate5",10,10,'box numberic2')?>
                            </div>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal_price_origin_txt4_2",20,40)?>
                                외화 : <?=html_input("cal_price_origin5_2",10,10,'box numberic2')?> X 환율: <?=html_input("cal_price_rate5_2",10,10,'box numberic2')?>
                            </div>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal_price_origin_txt4_3",20,40)?>
                                금액 : <?=html_input("cal_price_origin5_3",10,10,'box numberic2')?> &nbsp;/ 인원: <?=html_input("cal_price_rate5_3",10,10,'box numberic2')?>
                            </div>
                            </div>
                        </td>
                        <td>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal_price1_b4",15,15,'box readonly numberic2')?></div>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal_price1_b4_2",15,15,'box readonly numberic2')?></div>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal_price1_b4_3",15,15,'box readonly numberic2')?></div>
                        </td>
                    </tr>
                    <tr>
                        <th>항공</th>
                        <td></td>
                        <td><?=html_input("cal_price2",15,15,'box numberic ')?></td>
                    </tr>
                    <tr>
                        <th>수수료</th>
                        <td></td>
                        <td><?=html_input("cal_price3",15,15,'box numberic ')?></td>
                    </tr>
                    <tr>
                        <th>판매가</th>
                        <th></th>
                        <th><?=html_input("cal_amount",15,15,'box readonly numberic ')?></th>
                    </tr>
                    <tr>
                        <th>파일</th>
                        <th colspan="2" style="text-align:left">

                            <?if($rs[filename1]){?>
                            &nbsp;&nbsp;&nbsp;
                            <span class="btn_pack medium bold"><a href="../../include/download.php?file=<?=$rs[filename1]?>&orgin_file_name=<?=$rs[filename1_real]?>&dir=public/cmp_files"> 다운로드 </a></span>
                            &nbsp;&nbsp;&nbsp;
                            <span class="btn_pack medium bold"><a href="javascript:file_drop(1)"> 삭제 </a></span>
                            <?}else{?>
                            <input type="file" name="file1" id="file1" value="" size="60" style="padding:3px">
                            <?}?>
                        </th>
                    </tr>
                    <!-- <tr>
                        <th>메모</th>
                        <th colspan="2"><?=html_input("cal_memo1",60,250,'box w100')?></th>
                    </tr> -->
                    </table>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject">정산2</td>
          <td colspan="3">
                <table id="tbl_normal2" style="width:99%">
                    <tr>
                        <th width="10%">구분</th>
                        <th width="70%">외화</th>
                        <th width="20%">원화</th>
                    </tr>
                    <tr>
                        <th>랜드비총액</th>
                        <td>외화 : <?=html_input("cal2_price_origin",10,10,'box numberic2')?> X 환율: <?=html_input("cal2_price_rate",10,10,'box numberic2')?>
                        <span class="btn_pack medium bold"><a href="javascript:jisangbi()"> 지상비불러오기 </a></span>
                        </td>
                        <td><?=html_input("cal2_price1",15,15,'box readonly numberic2')?></td>
                    </tr>
                    <tr>
                        <th>골프요금</th>
                        <td>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal2_price_origin_txt",20,40)?>
                                외화 : <?=html_input("cal2_price_origin2",10,10,'box numberic2')?> X 환율: <?=html_input("cal2_price_rate2",10,10,'box numberic2')?>
                            </div>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal2_price_origin_txt_2",20,40)?>
                                외화 : <?=html_input("cal2_price_origin2_2",10,10,'box numberic2')?> X 환율: <?=html_input("cal2_price_rate2_2",10,10,'box numberic2')?>
                            </div>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal2_price_origin_txt_3",20,40)?>
                                외화 : <?=html_input("cal2_price_origin2_3",10,10,'box numberic2')?> X 환율: <?=html_input("cal2_price_rate2_3",10,10,'box numberic2')?>
                            </div>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal2_price_origin_txt_4",20,40)?>
                                외화 : <?=html_input("cal2_price_origin2_4",10,10,'box numberic2')?> X 환율: <?=html_input("cal2_price_rate2_4",10,10,'box numberic2')?>
                            </div>
                        </td>
                        <td>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal2_price1_b",15,15,'box readonly numberic2')?></div>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal2_price1_b_2",15,15,'box readonly numberic2')?></div>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal2_price1_b_3",15,15,'box readonly numberic2')?></div>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal2_price1_b_4",15,15,'box readonly numberic2')?></div>
                        </td>
                    </tr>
                    <tr>
                        <th>호텔요금</th>
                        <td>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal2_price_origin_txt2",20,40)?>
                                금액 : <?=html_input("cal2_price_origin3",10,10,'box numberic2')?> &nbsp;/ 인원: <?=html_input("cal2_price_rate3",10,10,'box numberic2')?>
                            </div>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal2_price_origin_txt2_2",20,40)?>
                                금액 : <?=html_input("cal2_price_origin3_2",10,10,'box numberic2')?> &nbsp;/ 인원: <?=html_input("cal2_price_rate3_2",10,10,'box numberic2')?>
                            </div>
                        </td>
                        <td>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal2_price1_b2",15,15,'box readonly numberic2')?></div>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal2_price1_b2_2",15,15,'box readonly numberic2')?></div>
                        </td>
                    </tr>
                    <tr>
                        <th>렌트카요금</th>
                        <td>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal2_price_origin_txt3",20,40)?>
                                금액 : <?=html_input("cal2_price_origin4",10,10,'box numberic2')?> &nbsp;/ 인원: <?=html_input("cal2_price_rate4",10,10,'box numberic2')?>
                            </div>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal2_price_origin_txt3_2",20,40)?>
                                금액 : <?=html_input("cal2_price_origin4_2",10,10,'box numberic2')?> &nbsp;/ 인원: <?=html_input("cal2_price_rate4_2",10,10,'box numberic2')?>
                            </div>
                        </td>
                        <td>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal2_price1_b3",15,15,'box readonly numberic2')?></div>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal2_price1_b3_2",15,15,'box readonly numberic2')?></div>
                        </td>
                    </tr>
                    <tr>
                        <th>기타</th>
                        <td>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal2_price_origin_txt4",20,40)?>
                                외화 : <?=html_input("cal2_price_origin5",10,10,'box numberic2')?> X 환율: <?=html_input("cal2_price_rate5",10,10,'box numberic2')?>
                            </div>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal2_price_origin_txt4_2",20,40)?>
                                외화 : <?=html_input("cal2_price_origin5_2",10,10,'box numberic2')?> X 환율: <?=html_input("cal2_price_rate5_2",10,10,'box numberic2')?>
                            </div>
                            <div style="margin:2px 0 2px 0">
                                <?=html_input("cal2_price_origin_txt4_3",20,40)?>
                                금액 : <?=html_input("cal2_price_origin5_3",10,10,'box numberic2')?> &nbsp;/ 인원: <?=html_input("cal2_price_rate5_3",10,10,'box numberic2')?>
                            </div>
                        </td>
                        <td>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal2_price1_b4",15,15,'box readonly numberic2')?></div>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal2_price1_b4_2",15,15,'box readonly numberic2')?></div>
                            <div style="margin:2px 0 2px 0"><?=html_input("cal2_price1_b4_3",15,15,'box readonly numberic2')?></div>
                        </td>
                    </tr>
                    <tr>
                        <th>항공</th>
                        <td></td>
                        <td><?=html_input("cal2_price2",15,15,'box numberic ')?></td>
                    </tr>
                    <tr>
                        <th>수수료</th>
                        <td></td>
                        <td><?=html_input("cal2_price3",15,15,'box numberic ')?></td>
                    </tr>
                    <tr>
                        <th>판매가</th>
                        <th></th>
                        <th><?=html_input("cal2_amount",15,15,'box readonly numberic ')?></th>
                    </tr>
                    <tr>
                        <th>파일</th>
                        <th colspan="2" style="text-align:left">

                            <?if($rs[filename2]){?>
                            &nbsp;&nbsp;&nbsp;
                            <span class="btn_pack medium bold"><a href="../../include/download.php?file=<?=$rs[filename2]?>&orgin_file_name=<?=$rs[filename2_real]?>&dir=public/cmp_files"> 다운로드 </a></span>
                            &nbsp;&nbsp;&nbsp;
                            <span class="btn_pack medium bold"><a href="javascript:file_drop(2)"> 삭제 </a></span>
                            <?}else{?>
                                <input type="file" name="file2" id="file2" value="" size="60" style="padding:3px">
                            <?}?>
                        </th>
                    </tr>
                    <!-- <tr>
                        <th>메모</th>
                        <th colspan="2"><?=html_input("cal_memo2",60,250,'box w100')?></th>
                    </tr> -->
                    </table>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">관리자 메모</td>
          <td colspan="3">
            <?=html_textarea('memo',0,15)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <?if($rs[id_no]){?>
        <tr>
          <td class="subject">링크</td>
          <td colspan="3">
            <?=short_url($long_url1)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">견적요청링크</td>
          <td colspan="3">
            <?=short_url($long_url2)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <?}?>

        <tr><td colspan="12" bgcolor='#E1E1E1' height=1></td></tr>

        <tr>
          <td colspan=10>
              <br>
              <!-- Button Begin---------------------------------------------->
              <table border="0" width="370" cellspacing="0" cellpadding="0" align="right">
                <tr align="right">
                    <td>

                        <?
                        $sql2 = "
                                select
                                    *
                                from cmp_reservation
                                where origin_id_no=$rs[id_no]
                                limit 1
                            ";
                        $dbo2->query($sql2);
                        $rs2=$dbo2->next_record();
                        if($rs2[origin_id_no]){
                        ?>
                        <span class="btn_pack medium bold"><a href="view_reservation.php?id_no=<?=$rs2[id_no]?>"> 고객예약정보 바로가기 </a></span>&nbsp;&nbsp;
                        <?}?>


                        <span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span>
                        &nbsp;&nbsp;
                        <?if($rs[id_no]){?>
                        <span class="btn_pack medium bold"><a href="javascript:data_copy()"> 예약확정 </a></span>
                        &nbsp;&nbsp;
                        <?}?>
                        <span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
                    </td>
                </tr>
              </table>
              <!-- Button End------------------------------------------------>
          </td>
        </tr>

        <tr>
      <td colspan=10 height=20>
      </td>
        </tr>
    </form>
    </table>

</div>


    <!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>