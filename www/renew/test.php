<?
include_once("script/include_common_file.php");

function full_date(){}

$tid = 16289;

$tid = secu($tid);

$sql = "select * from ez_tour where tid='$tid' and bit=1";
$dbo->query($sql);
$rs=$dbo->next_record();
$night = $rs[days2];
$plan_type = $rs[plan_type];


$category1 = $rs[category1];
$arr = explode("-",$category1);

if(!$rs[tid]){
	echo "<script>alert('판매할 수 없는 상품입니다.');top.history.back(-1)</script>";
	exit;
}


$_SESSION[TODAY][$rs[tid]] = $rs[filename1];

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
<?include("header.php");?>

<script type="text/javascript">

function tab(id){
	$(".tab").hide();
	$("#tab0"+id).show();
}


$(function(){

	$('.pimage').bxSlider({
		minSlides: 1,
		maxSlides: 1,
		moveSlides:1,
		slideWidth: 660,
		pager:false,
		controls:true,
		auto: false,
		autoControls: false,
		nextSelector: '.pimage_prev',
		prevSelector: '.pimage_next',
		prevText: '<img src=\"images/detail/btn_prev.png\" />',
		nextText: '<img src=\"images/detail/btn_next.png\" />'
	});


});
</script>

</head>
<body>
<!--Wrap-->
<div id="wrap">


  <!--Container-->
  <div id="container">

    <!--Contents-->
    <div id="contents">




				<!--일정표 시작-->
				<?
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
				$hotel_days1 = ",".$rs[hotel_days].",";
				$hotel_days2 = ",".$rs[hotel_days2].",";
				$HOTEL_ABS_MODE = ($rs[hotel_days] || $rs[hotel_days2])?1:0;


				$arr = explode(">",$rs[golf_name]);
				$default_golf_name= $arr[count($arr)-1];

				if($rs[subject]) $default_golf_name = $rs[subject];

				$d_air_no = $rs[d_air_no];
				$r_air_no = $rs[r_air_no];
				$d_air_time1 = $rs[d_air_time1];
				$d_air_time2 = $rs[d_air_time2];
				$r_air_time1 = $rs[r_air_time1];
				$r_air_time2 = $rs[r_air_time2];


				/*2016-04-06*/
				$plan_type=$rs[plan_type];

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

				$arr = explode(">",$rs[golf2_1_name]);$rs[golf2_1_name] = $arr[count($arr)-1];
				$arr = explode(">",$rs[golf2_2_name]);$rs[golf2_2_name] = $arr[count($arr)-1];
				$arr = explode(">",$rs[golf2_3_name]);$rs[golf2_3_name] = $arr[count($arr)-1];
				$arr = explode(">",$rs[golf2_4_name]);$rs[golf2_4_name] = $arr[count($arr)-1];
				$arr = explode(">",$rs[golf2_5_name]);$rs[golf2_5_name] = $arr[count($arr)-1];
				$arr = explode(">",$rs[golf2_6_name]);$rs[golf2_6_name] = $arr[count($arr)-1];

				$golf_name1 = $rs[golf2_1_name];
				$golf_name2 = $rs[golf2_2_name];
				$golf_name3 = $rs[golf2_3_name];
				$golf_name4 = $rs[golf2_4_name];
				$golf_name5 = $rs[golf2_5_name];
				$golf_name6 = $rs[golf2_6_name];
				$golf_name4_ = $rs[golf2_4_name];

				/*2016-04-06*/
				if($golf_name1) $gname[] = trim($golf_name1);
				if($golf_name2) $gname[] = trim($golf_name2);
				if($golf_name3) $gname[] = trim($golf_name3);
				if($golf_name4) $gname[] = trim($golf_name4);
				if($golf_name5) $gname[] = trim($golf_name5);
				if($golf_name6) $gname[] = trim($golf_name6);



				if($rs[golf2_1_id_no]) $golfs[] = $rs[golf2_1_id_no];
				if($rs[golf2_2_id_no]) $golfs[] = $rs[golf2_2_id_no];
				if($rs[golf2_3_id_no]) $golfs[] = $rs[golf2_3_id_no];
				if($rs[golf2_4_id_no]) $golfs[] = $rs[golf2_4_id_no];
				if($rs[golf2_5_id_no]) $golfs[] = $rs[golf2_5_id_no];
				if($rs[golf2_6_id_no]) $golfs[] = $rs[golf2_6_id_no];


				if($golf_name3 && !$golf_name4) $golf_name4_ = $golf_name3;
				if($golf_name2 && !$golf_name3 && !$golf_name4) $golf_name4_ = $golf_name2;
				if($golf_name1 && !$golf_name2 && !$golf_name3 && !$golf_name4){ $golf_name2 = $golf_name1; $golf_name4_ = $golf_name1;}


				//골프장 이미지
				while(list($key,$val)=@each($golfs)){
					$sql2 = "select * from cmp_golf2 where id_no=$val";
					$dbo2->query($sql2);
					$rs2=$dbo2->next_record();
					$key2 = $key+1;

					$GOLF_PIC[$key2][0] = $rs2[filename1];
					$GOLF_PIC[$key2][1] = $rs2[filename2];
					//$GOLF_PIC[$key2][2] = $rs2[filename3];
					//$GOLF_PIC[$key2][3] = $rs2[filename4];
				}
				//골프장 이미지 종료



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
				$d_time_e = $rs2[d_time_e];
				$airport_in = str_replace("공항","",$rs2[airport_in]);
				$airport_out = $rs2[airport_out];
				$airport_city = $rs2[city];
				$airport_place = $rs2[airport_place];

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
				$r_airport_in = str_replace("공항","",$rs2[airport_in]);
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


				$sql2 = "select * from cmp_golf where id_no=$rs[golf_id_no]";
				$dbo2->query($sql2);
				$rs2= $dbo2->next_record();

				$nation = $rs2[nation];
				$car = $rs2[car];
				$car2 = $rs2[car2];
				$car3 =($rs2[car3])? $rs2[car3] : $rs2[car];
				$city = $rs2[city];

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

				include("inc_days_${plan_type_lower}.php");
				?>




    </div>
    <!--//Contents-->
  </div>
  <!--//Container-->


</div>
<!--//Wrap-->
</body>
</html>
