			<?
			$day2_start_type="K,";
			if(strstr($day2_start_type,$plan_type)){
				$hotel_plus=1;
				$hotel_plus2=-1;
			}


			//호텔 n일차
			$hotel_i=$i;

			if($HOTEL_ABS_MODE){

				$hotel_data = get_abs_hotel($hotel_i);
				$hotel_name = $hotel_data['hotel_name'];
				$hotel_url = $hotel_data['hotel_url'];
				$hotel_phone = $hotel_data['hotel_phone'];

			}else{
				if($hotel_plus2) $hotel_i+=$hotel_plus2;
			}

			//호텔 수정 가능하도록
			$hotel_next_no=$hotel_i-2;
			if(!$plan_hotel[$hotel_i]){

				 if(!$HOTEL_ABS_MODE){

					$hotel_next_no++;
					if($hotel_cnt<=$hotel_next_no) $hotel_next_no=($hotel_cnt-1);
					$arr = explode("{@}",$HOTELINFOS[$hotel_next_no]);
					$hotel_name = $arr[0];
					$hotel_url = $arr[1];
					$hotel_phone = $arr[2];
					if($hotel_name) $plan_hotel[$hotel_i] = "호텔 : $hotel_name";
				}

				if(!strstr($HOTEL_NAMES,"{@}".$hotel_name)){
				 	//if(strstr("@14.37.242.84@221.154.216.133@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar($hotel_i,$hotel_name);}
					if($hotel_name) $plan_hotel[$hotel_i] = "호텔 : $hotel_name";
					if($hotel_phone) $plan_hotel[$hotel_i] .= "\r" . "전화번호 : ".$hotel_phone." \r" . $hotel_phone;
					//$ah_next=$ah2;
				}else{
					if($hotel_name) $plan_hotel[$hotel_i] = "호텔 : $hotel_name";
					//$ah_next=$ah1;
				}

				$HOTEL_NAMES.="{@}".$hotel_name;

			}
			?>

			<?if($edit_mode){?>
			<textarea name="plan_hotel<?=$i?>" class="editor_hotel"><?=$plan_hotel[$hotel_i]?></textarea>
			<?}else{?>
				<?
				$txt2=$plan_hotel[$hotel_i];
				include("inc_plan_color.php");
				$plan_hotel[$hotel_i]=$txt2;
				?>
				<span class="hotel_text"><?=nl2br($plan_hotel[$hotel_i])?></span>
			<?}?>			