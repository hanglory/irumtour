<?
include_once("../include/common_file.php");


$sql = "select * from cmp_estimate where id_no=$id_no";
$dbo->query($sql);
$rs=$dbo->next_record();

if($rs[id_no]){

	$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');
	$code = getUniqNo();
	$tour_date =  $reg_date;

	$rs[point_dep] = addslashes($rs[point_dep]);
    $rs[golf_name] = addslashes($rs[golf_name]);
	$rs[name] = addslashes($rs[name]);
	$rs[view_path] = addslashes($rs[view_path]);
	$rs[main_staff] = addslashes($rs[main_staff]);
    $rs[staff_staff] = addslashes($rs[staff_staff]);
	$rs[golf] = addslashes($rs[golf]);
	$rs[price] = addslashes($rs[price]);
	$rs[price_prev] = addslashes($rs[price_prev]);
	$rs[price_last] = addslashes($rs[price_last]);
	$rs[pay_date] = addslashes($rs[pay_date]);
	$rs[pay_method] = addslashes($rs[pay_method]);
	$rs[bit_tax] = addslashes($rs[bit_tax]);
	$rs[air_info] = addslashes($rs[air_info]);
	$rs[people] = addslashes($rs[people]);
	$rs[pay_check] = addslashes($rs[pay_check]);
	$rs[bit_sending] = addslashes($rs[bit_sending]);
	$rs[memo2] = addslashes($rs[memo2]);
	$rs[request_date] = addslashes($rs[request_date]);
	$rs[sending_yn1] = addslashes($rs[sending_yn1]);
	$rs[sending_yn2] = addslashes($rs[sending_yn2]);
	$rs[sending_yn3] = addslashes($rs[sending_yn3]);
	$rs[sending_yn4] = addslashes($rs[sending_yn4]);
	$rs[sending_yn5] = addslashes($rs[sending_yn5]);
	$rs[sending_memo] = addslashes($rs[sending_memo]);
	$rs[sending_meeting_time] = addslashes($rs[sending_meeting_time]);
	$rs[sending_meeting_place] = addslashes($rs[sending_meeting_place]);
	$rs[memo_confirm] = addslashes($rs[memo_confirm]);
	$rs[room] = addslashes($rs[room]);
	$rs[hole] = addslashes($rs[hole]);
	$rs[price_customer_input] = addslashes($rs[price_customer_input]);
	$rs[price_tmp_output] = addslashes($rs[price_tmp_output]);
	$rs[golf2_1_name] = addslashes($rs[golf2_1_name]);
	$rs[golf2_2_name] = addslashes($rs[golf2_2_name]);
	$rs[golf2_3_name] = addslashes($rs[golf2_3_name]);
	$rs[golf2_4_name] = addslashes($rs[golf2_4_name]);
	$rs[golf2_1] = addslashes($rs[golf2_1]);
	$rs[golf2_2] = addslashes($rs[golf2_2]);
	$rs[golf2_3] = addslashes($rs[golf2_3]);
	$rs[golf2_4] = addslashes($rs[golf2_4]);
	$rs[hotel] = addslashes($rs[hotel]);
	$rs[hotel_name] = addslashes($rs[hotel_name]);
	$rs[hole1] = addslashes($rs[hole1]);
	$rs[hole2] = addslashes($rs[hole2]);
	$rs[hole3] = addslashes($rs[hole3]);
	$rs[hole4] = addslashes($rs[hole4]);
	$rs[moving_time] = addslashes($rs[moving_time]);
	$rs[moving_time2] = addslashes($rs[moving_time2]);
	$rs[moving_time3] = addslashes($rs[moving_time3]);
	$rs[hole5] = addslashes($rs[hole5]);
	$rs[tl] = addslashes($rs[tl]);
	$rs[bsp] = addslashes($rs[bsp]);
	$rs[golf_ball] = addslashes($rs[golf_ball]);
	$rs[air_cover] = addslashes($rs[air_cover]);
	$rs[memo_payment] = addslashes($rs[memo]);
	$rs[customer_type] = addslashes($rs[customer_type]);
	$rs[fee] = addslashes($rs[fee]);
	$rs[plan_type] = addslashes($rs[plan_type]);

	$rs[plan_bus1] = addslashes($rs[plan_bus1]);
	$rs[plan_bus2] = addslashes($rs[plan_bus2]);
	$rs[plan_bus3] = addslashes($rs[plan_bus3]);
	$rs[plan_bus4] = addslashes($rs[plan_bus4]);
	$rs[plan_bus5] = addslashes($rs[plan_bus5]);
	$rs[plan_bus6] = addslashes($rs[plan_bus6]);
	$rs[plan_bus7] = addslashes($rs[plan_bus7]);
	$rs[plan_bus8] = addslashes($rs[plan_bus8]);
	$rs[plan_bus9] = addslashes($rs[plan_bus9]);
	$rs[plan_bus10] = addslashes($rs[plan_bus10]);
	$rs[plan_bus11] = addslashes($rs[plan_bus11]);
	$rs[plan_bus12] = addslashes($rs[plan_bus12]);
	$rs[plan_bus13] = addslashes($rs[plan_bus13]);
	$rs[plan_bus14] = addslashes($rs[plan_bus14]);
	$rs[plan_bus15] = addslashes($rs[plan_bus15]);
	$rs[plan_bus16] = addslashes($rs[plan_bus16]);
	$rs[plan_bus17] = addslashes($rs[plan_bus17]);
	$rs[plan_bus18] = addslashes($rs[plan_bus18]);
	$rs[plan_bus19] = addslashes($rs[plan_bus19]);
	$rs[plan_bus20] = addslashes($rs[plan_bus20]);

	$rs[plan_text1] = addslashes($rs[plan_text1]);
	$rs[plan_text2] = addslashes($rs[plan_text2]);
	$rs[plan_text3] = addslashes($rs[plan_text3]);
	$rs[plan_text4] = addslashes($rs[plan_text4]);
	$rs[plan_text5] = addslashes($rs[plan_text5]);
	$rs[plan_text6] = addslashes($rs[plan_text6]);
	$rs[plan_text7] = addslashes($rs[plan_text7]);
	$rs[plan_text8] = addslashes($rs[plan_text8]);
	$rs[plan_text9] = addslashes($rs[plan_text9]);
	$rs[plan_text10] = addslashes($rs[plan_text10]);
	$rs[plan_text11] = addslashes($rs[plan_text11]);
	$rs[plan_text12] = addslashes($rs[plan_text12]);
	$rs[plan_text13] = addslashes($rs[plan_text13]);
	$rs[plan_text14] = addslashes($rs[plan_text14]);
	$rs[plan_text15] = addslashes($rs[plan_text15]);
	$rs[plan_text16] = addslashes($rs[plan_text16]);
	$rs[plan_text17] = addslashes($rs[plan_text17]);
	$rs[plan_text18] = addslashes($rs[plan_text18]);
	$rs[plan_text19] = addslashes($rs[plan_text19]);
	$rs[plan_text20] = addslashes($rs[plan_text20]);
	$rs[form3_text] = addslashes($rs[form3_text]);
	$rs[form3_text2] = addslashes($rs[form3_text2]);
	$rs[d_air_time1] = addslashes($rs[d_air_time1]);
	$rs[d_air_time2] = addslashes($rs[d_air_time2]);
	$rs[r_air_time1] = addslashes($rs[r_air_time1]);
	$rs[r_air_time2] = addslashes($rs[r_air_time2]);
	$rs[d_air_id_no] = addslashes($rs[d_air_id_no]);
	$rs[r_air_id_no] = addslashes($rs[r_air_id_no]);
	$rs[add_text1] = addslashes($rs[add_text1]);
	$rs[add_text2] = addslashes($rs[add_text2]);
	$rs[plan_time1] = addslashes($rs[plan_time1]);
	$rs[plan_time2] = addslashes($rs[plan_time2]);
	$rs[plan_time3] = addslashes($rs[plan_time3]);
	$rs[plan_time4] = addslashes($rs[plan_time4]);
	$rs[plan_time5] = addslashes($rs[plan_time5]);
	$rs[plan_time6] = addslashes($rs[plan_time6]);
	$rs[plan_time7] = addslashes($rs[plan_time7]);
	$rs[plan_time8] = addslashes($rs[plan_time8]);
	$rs[plan_time9] = addslashes($rs[plan_time9]);
	$rs[plan_time10] = addslashes($rs[plan_time10]);
	$rs[plan_time11] = addslashes($rs[plan_time11]);
	$rs[plan_time12] = addslashes($rs[plan_time12]);
	$rs[plan_time13] = addslashes($rs[plan_time13]);
	$rs[plan_time14] = addslashes($rs[plan_time14]);
	$rs[plan_time15] = addslashes($rs[plan_time15]);
	$rs[plan_time16] = addslashes($rs[plan_time16]);
	$rs[plan_time17] = addslashes($rs[plan_time17]);
	$rs[plan_time18] = addslashes($rs[plan_time18]);
	$rs[plan_time19] = addslashes($rs[plan_time19]);
	$rs[plan_time20] = addslashes($rs[plan_time20]);
	$rs[plan_meal1] = addslashes($rs[plan_meal1]);
	$rs[plan_meal2] = addslashes($rs[plan_meal2]);
	$rs[plan_meal3] = addslashes($rs[plan_meal3]);
	$rs[plan_meal4] = addslashes($rs[plan_meal4]);
	$rs[plan_meal5] = addslashes($rs[plan_meal5]);
	$rs[plan_meal6] = addslashes($rs[plan_meal6]);
	$rs[plan_meal7] = addslashes($rs[plan_meal7]);
	$rs[plan_meal8] = addslashes($rs[plan_meal8]);
	$rs[plan_meal9] = addslashes($rs[plan_meal9]);
	$rs[plan_meal10] = addslashes($rs[plan_meal10]);
	$rs[plan_meal11] = addslashes($rs[plan_meal11]);
	$rs[plan_meal12] = addslashes($rs[plan_meal12]);
	$rs[plan_meal13] = addslashes($rs[plan_meal13]);
	$rs[plan_meal14] = addslashes($rs[plan_meal14]);
	$rs[plan_meal15] = addslashes($rs[plan_meal15]);
	$rs[plan_meal16] = addslashes($rs[plan_meal16]);
	$rs[plan_meal17] = addslashes($rs[plan_meal17]);
	$rs[plan_meal18] = addslashes($rs[plan_meal18]);
	$rs[plan_meal19] = addslashes($rs[plan_meal19]);
	$rs[plan_meal20] = addslashes($rs[plan_meal20]);

	$rs[plan_hotel1] = addslashes($rs[plan_hotel1]);
	$rs[plan_hotel2] = addslashes($rs[plan_hotel2]);
	$rs[plan_hotel3] = addslashes($rs[plan_hotel3]);
	$rs[plan_hotel4] = addslashes($rs[plan_hotel4]);
	$rs[plan_hotel5] = addslashes($rs[plan_hotel5]);
	$rs[plan_hotel6] = addslashes($rs[plan_hotel6]);
	$rs[plan_hotel7] = addslashes($rs[plan_hotel7]);
	$rs[plan_hotel8] = addslashes($rs[plan_hotel8]);
	$rs[plan_hotel9] = addslashes($rs[plan_hotel9]);
	$rs[plan_hotel10] = addslashes($rs[plan_hotel10]);
	$rs[plan_hotel11] = addslashes($rs[plan_hotel11]);
	$rs[plan_hotel12] = addslashes($rs[plan_hotel12]);
	$rs[plan_hotel13] = addslashes($rs[plan_hotel13]);
	$rs[plan_hotel14] = addslashes($rs[plan_hotel14]);
	$rs[plan_hotel15] = addslashes($rs[plan_hotel15]);
	$rs[plan_hotel16] = addslashes($rs[plan_hotel16]);
	$rs[plan_hotel17] = addslashes($rs[plan_hotel17]);
	$rs[plan_hotel18] = addslashes($rs[plan_hotel18]);
	$rs[plan_hotel19] = addslashes($rs[plan_hotel19]);
	$rs[plan_hotel20] = addslashes($rs[plan_hotel20]);
	$rs[partner] = addslashes($rs[partner]);

	$rs[hotel2] = addslashes($rs[hotel2]);
	$rs[hotel2_id_no] = addslashes($rs[hotel2_id_no]);
	$rs[hotel2_name] = addslashes($rs[hotel2_name]);

	$sql2="
	   insert into cmp_reservation (
		   code,
           cp_id,
           point_dep,
           subject,
		   golf_name,
		   golf_id_no,
		   name,
		   phone,
		   view_path,
		   main_staff,
           sub_staff,
		   d_date,
		   r_date,
		   golf,
		   price,
		   price_prev,
		   price_last,
		   pay_date,
		   pay_method,
		   bit_tax,
		   air_info,
		   reg_date,
		   reg_date2,
		   people,
		   tour_date,
		   pay_check,
		   bit_sending,
		   air_id_no,
		   d_air_no,
		   r_air_no,
		   bit_cash,
		   memo2,
		   request_date,
		   sending_yn1,
		   sending_yn2,
		   sending_yn3,
		   sending_yn4,
		   sending_yn5,
		   sending_memo,
		   sending_meeting_time,
		   sending_meeting_place,
		   memo_confirm,
		   room,
		   hole,
		   price_customer_input,
		   price_tmp_output,
		   bit,
		   bit_copy,
		   golf2_1_id_no,
		   golf2_2_id_no,
		   golf2_3_id_no,
		   golf2_4_id_no,
		   hotel_id_no,
		   golf2_1_name,
		   golf2_2_name,
		   golf2_3_name,
		   golf2_4_name,
		   golf2_1,
		   golf2_2,
		   golf2_3,
		   golf2_4,
		   hotel,
		   hotel_name,
		   hole1,
		   hole2,
		   hole3,
		   hole4,
		   moving_time,
		   moving_time2,
		   moving_time3,
		   hole5,
		   tl,
		   bsp,
		   golf_ball,
		   air_cover,
		   memo_payment,
		   customer_type,
		   fee,
		   plan_type,
		   plan_text1,
		   plan_text2,
		   plan_text3,
		   plan_text4,
		   plan_text5,
		   plan_text6,
		   plan_text7,
		   plan_text8,
		   plan_text9,
		   plan_text10,
		   plan_text11,
		   plan_text12,
		   plan_text13,
		   plan_text14,
		   plan_text15,
		   plan_text16,
		   plan_text17,
		   plan_text18,
		   plan_text19,
		   plan_text20,

		   plan_bus1,
		   plan_bus2,
		   plan_bus3,
		   plan_bus4,
		   plan_bus5,
		   plan_bus6,
		   plan_bus7,
		   plan_bus8,
		   plan_bus9,
		   plan_bus10,
		   plan_bus11,
		   plan_bus12,
		   plan_bus13,
		   plan_bus14,
		   plan_bus15,
		   plan_bus16,
		   plan_bus17,
		   plan_bus18,
		   plan_bus19,
		   plan_bus20,

		   form3_text,
		   form3_text2,
		   d_air_time1,
		   d_air_time2,
		   r_air_time1,
		   r_air_time2,
		   d_air_id_no,
		   r_air_id_no,
		   add_text1,
		   add_text2,
		   plan_time1,
		   plan_time2,
		   plan_time3,
		   plan_time4,
		   plan_time5,
		   plan_time6,
		   plan_time7,
		   plan_time8,
		   plan_time9,
		   plan_time10,
		   plan_time11,
		   plan_time12,
		   plan_time13,
		   plan_time14,
		   plan_time15,
		   plan_time16,
		   plan_time17,
		   plan_time18,
		   plan_time19,
		   plan_time20,
		   plan_meal1,
		   plan_meal2,
		   plan_meal3,
		   plan_meal4,
		   plan_meal5,
		   plan_meal6,
		   plan_meal7,
		   plan_meal8,
		   plan_meal9,
		   plan_meal10,
		   plan_meal11,
		   plan_meal12,
		   plan_meal13,
		   plan_meal14,
		   plan_meal15,
		   plan_meal16,
		   plan_meal17,
		   plan_meal18,
		   plan_meal19,
		   plan_meal20,

		   plan_hotel1,
		   plan_hotel2,
		   plan_hotel3,
		   plan_hotel4,
		   plan_hotel5,
		   plan_hotel6,
		   plan_hotel7,
		   plan_hotel8,
		   plan_hotel9,
		   plan_hotel10,
		   plan_hotel11,
		   plan_hotel12,
		   plan_hotel13,
		   plan_hotel14,
		   plan_hotel15,
		   plan_hotel16,
		   plan_hotel17,
		   plan_hotel18,
		   plan_hotel19,
		   plan_hotel20,

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

		   hotel2,
		   hotel2_id_no,
		   hotel2_name,
		   partner,
		   origin_id_no
	   ) values (
		   '$code',
		   '$rs[cp_id]',
           '$rs[point_dep]',
           '$rs[subject]',
           '$rs[golf_name]',
		   '$rs[golf_id_no]',
		   '$rs[name]',
		   '$rs[phone]',
		   '$rs[view_path]',
		   '$rs[main_staff]',
           '$rs[sub_staff]',
		   '$rs[d_date]',
		   '$rs[r_date]',
		   '$rs[golf]',
		   '$rs[price]',
		   '$rs[price_prev]',
		   '$rs[price_last]',
		   '$rs[pay_date]',
		   '$rs[pay_method]',
		   '$rs[bit_tax]',
		   '$rs[air_info]',
		   '$rs[reg_date]',
		   '$rs[reg_date2]',
		   '$rs[people]',
		   '$tour_date',
		   '$rs[pay_check]',
		   '$rs[bit_sending]',
		   '$rs[air_id_no]',
		   '$rs[d_air_no]',
		   '$rs[r_air_no]',
		   '$rs[bit_cash]',
		   '$rs[memo2]',
		   '$rs[request_date]',
		   '$rs[sending_yn1]',
		   '$rs[sending_yn2]',
		   '$rs[sending_yn3]',
		   '$rs[sending_yn4]',
		   '$rs[sending_yn5]',
		   '$rs[sending_memo]',
		   '$rs[sending_meeting_time]',
		   '$rs[sending_meeting_place]',
		   '$rs[memo_confirm]',
		   '$rs[room]',
		   '$rs[hole]',
		   '$rs[price_customer_input]',
		   '$rs[price_tmp_output]',
		   '$rs[bit]',
		   '2',
		   '$rs[golf2_1_id_no]',
		   '$rs[golf2_2_id_no]',
		   '$rs[golf2_3_id_no]',
		   '$rs[golf2_4_id_no]',
		   '$rs[hotel_id_no]',
		   '$rs[golf2_1_name]',
		   '$rs[golf2_2_name]',
		   '$rs[golf2_3_name]',
		   '$rs[golf2_4_name]',
		   '$rs[golf2_1]',
		   '$rs[golf2_2]',
		   '$rs[golf2_3]',
		   '$rs[golf2_4]',
		   '$rs[hotel]',
		   '$rs[hotel_name]',
		   '$rs[hole1]',
		   '$rs[hole2]',
		   '$rs[hole3]',
		   '$rs[hole4]',
		   '$rs[moving_time]',
		   '$rs[moving_time2]',
		   '$rs[moving_time3]',
		   '$rs[hole5]',
		   '$rs[tl]',
		   '$rs[bsp]',
		   '$rs[golf_ball]',
		   '$rs[air_cover]',
		   '$rs[memo_payment]',
		   '$rs[customer_type]',
		   '$rs[fee]',
		   '$rs[plan_type]',
		   '$rs[plan_text1]',
		   '$rs[plan_text2]',
		   '$rs[plan_text3]',
		   '$rs[plan_text4]',
		   '$rs[plan_text5]',
		   '$rs[plan_text6]',
		   '$rs[plan_text7]',
		   '$rs[plan_text8]',
		   '$rs[plan_text9]',
		   '$rs[plan_text10]',
		   '$rs[plan_text11]',
		   '$rs[plan_text12]',
		   '$rs[plan_text13]',
		   '$rs[plan_text14]',
		   '$rs[plan_text15]',
		   '$rs[plan_text16]',
		   '$rs[plan_text17]',
		   '$rs[plan_text18]',
		   '$rs[plan_text19]',
		   '$rs[plan_text20]',

		   '$rs[plan_bus1]',
		   '$rs[plan_bus2]',
		   '$rs[plan_bus3]',
		   '$rs[plan_bus4]',
		   '$rs[plan_bus5]',
		   '$rs[plan_bus6]',
		   '$rs[plan_bus7]',
		   '$rs[plan_bus8]',
		   '$rs[plan_bus9]',
		   '$rs[plan_bus10]',
		   '$rs[plan_bus11]',
		   '$rs[plan_bus12]',
		   '$rs[plan_bus13]',
		   '$rs[plan_bus14]',
		   '$rs[plan_bus15]',
		   '$rs[plan_bus16]',
		   '$rs[plan_bus17]',
		   '$rs[plan_bus18]',
		   '$rs[plan_bus19]',
		   '$rs[plan_bus20]',

		   '$rs[form3_text]',
		   '$rs[form3_text2]',
		   '$rs[d_air_time1]',
		   '$rs[d_air_time2]',
		   '$rs[r_air_time1]',
		   '$rs[r_air_time2]',
		   '$rs[d_air_id_no]',
		   '$rs[r_air_id_no]',
		   '$rs[add_text1]',
		   '$rs[add_text2]',
		   '$rs[plan_time1]',
		   '$rs[plan_time2]',
		   '$rs[plan_time3]',
		   '$rs[plan_time4]',
		   '$rs[plan_time5]',
		   '$rs[plan_time6]',
		   '$rs[plan_time7]',
		   '$rs[plan_time8]',
		   '$rs[plan_time9]',
		   '$rs[plan_time10]',
		   '$rs[plan_time11]',
		   '$rs[plan_time12]',
		   '$rs[plan_time13]',
		   '$rs[plan_time14]',
		   '$rs[plan_time15]',
		   '$rs[plan_time16]',
		   '$rs[plan_time17]',
		   '$rs[plan_time18]',
		   '$rs[plan_time19]',
		   '$rs[plan_time20]',
		   '$rs[plan_meal1]',
		   '$rs[plan_meal2]',
		   '$rs[plan_meal3]',
		   '$rs[plan_meal4]',
		   '$rs[plan_meal5]',
		   '$rs[plan_meal6]',
		   '$rs[plan_meal7]',
		   '$rs[plan_meal8]',
		   '$rs[plan_meal9]',
		   '$rs[plan_meal10]',
		   '$rs[plan_meal11]',
		   '$rs[plan_meal12]',
		   '$rs[plan_meal13]',
		   '$rs[plan_meal14]',
		   '$rs[plan_meal15]',
		   '$rs[plan_meal16]',
		   '$rs[plan_meal17]',
		   '$rs[plan_meal18]',
		   '$rs[plan_meal19]',
		   '$rs[plan_meal20]',

		   '$rs[plan_hotel1]',
		   '$rs[plan_hotel2]',
		   '$rs[plan_hotel3]',
		   '$rs[plan_hotel4]',
		   '$rs[plan_hotel5]',
		   '$rs[plan_hotel6]',
		   '$rs[plan_hotel7]',
		   '$rs[plan_hotel8]',
		   '$rs[plan_hotel9]',
		   '$rs[plan_hotel10]',
		   '$rs[plan_hotel11]',
		   '$rs[plan_hotel12]',
		   '$rs[plan_hotel13]',
		   '$rs[plan_hotel14]',
		   '$rs[plan_hotel15]',
		   '$rs[plan_hotel16]',
		   '$rs[plan_hotel17]',
		   '$rs[plan_hotel18]',
		   '$rs[plan_hotel19]',
		   '$rs[plan_hotel20]',

			'$rs[plan_add1_a]',
			'$rs[plan_add1_b]',
			'$rs[plan_add1_c]',
			'$rs[plan_add1_d]',
			'$rs[plan_add2_a]',
			'$rs[plan_add2_b]',
			'$rs[plan_add2_c]',
			'$rs[plan_add2_d]',
			'$rs[plan_add8_a]',
			'$rs[plan_add8_b]',
			'$rs[plan_add8_c]',
			'$rs[plan_add8_d]',
			'$rs[plan_add9_a]',
			'$rs[plan_add9_b]',
			'$rs[plan_add9_c]',
			'$rs[plan_add9_d]',

		   '$rs[hotel2]',
		   '$rs[hotel2_id_no]',
		   '$rs[hotel2_name]',
		   '$rs[partner]',
		   '$rs[id_no]'
	 )";


	list($rows) = $dbo->query($sql2);

	if($rows){

		if($rs[people]){
			for($i=1;$i<=$rs[people];$i++){
				$rs[price] = rnf($rs[price]);
				$name = ($i==1)? $rs[name] : $i-1;
				$phone = ($i==1)? $rs[phone] : "";
				$sql3 = "
						insert into cmp_people (code,name,price,phone,bit) values ('$code','$name','$rs[price]','$phone',1);
					";
				$dbo3->query($sql3);
			}
		}


		echo "<script>alert('복사되었습니다. \\n\\n내용을 수정해 주세요.');opener.location.href='list_reservation.php';self.close()</script>";
	}else{
		checkVar(mysql_error(),$sql2);exit;
		echo "<script>alert('복사하지 못했습니다.')</script>";

	}

	exit;

}


?>