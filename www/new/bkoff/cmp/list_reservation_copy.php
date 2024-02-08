<?
include_once("../include/common_file.php");


$sql = "select * from cmp_reservation where id_no=$id_no";
$dbo->query($sql);
$rs=$dbo->next_record();

if($rs[id_no]){

	$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');
	$code = getUniqNo();

	$sql2="
	   insert into cmp_reservation (
		  code,
          cp_id,
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
		  memo,
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
		  bit_copy
	  ) values (
		  '$code',
		  '$rs[cp_id]',
          '$rs[golf_name]',
		  '$rs[golf_id_no]',
		  '$rs[name] 복사본',
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
		  '$rs[memo]',
		  '$rs[reg_date]',
		  '$rs[reg_date2]',
		  '$rs[people]',
		  '$rs[tour_date]',
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
		  '1'
	)";


	list($rows) = $dbo->query($sql2);

	if($rows){

		$sql3 = "select * from cmp_people where code=$rs[code] order by id_no asc";
		$dbo2->query($sql3);
		while($rs3=$dbo2->next_record()){

				$sql4="
				   insert into cmp_people (
					  code,
					  id,
					  name,
					  sex,
					  name_eng,
					  rn,
					  passport_no,
					  passport_limit,
					  phone,
					  price,
					  price_air,
					  price_land,
					  price_refund,
					  memo,
					  seq,
					  bit,
					  staff,
					  reg_date,
					  reg_date2
				  ) values (
					  '$code',
					  '$rs3[id]',
					  '$rs3[name]',
					  '$rs3[sex]',
					  '$rs3[name_eng]',
					  '$rs3[rn]',
					  '$rs3[passport_no]',
					  '$rs3[passport_limit]',
					  '$rs3[phone]',
					  '$rs3[price]',
					  '$rs3[price_air]',
					  '$rs3[price_land]',
					  '$rs3[price_refund]',
					  '$rs3[memo]',
					  '$rs3[seq]',
					  '$rs3[bit]',
					  '$rs3[staff]',
					  '$reg_date',
					  '$reg_date2'
				)";
				$dbo3->query($sql4);
				//checkVar(mysql_error(),$sql4);

		}



		echo "<script>alert('복사되었습니다. \\n\\n내용을 수정해 주세요.');parent.location.reload()</script>";
	}else{
		checkVar(mysql_error(),$sql2);exit;
		echo "<script>alert('복사하지 못했습니다.')</script>";

	}

	exit;

}


?>