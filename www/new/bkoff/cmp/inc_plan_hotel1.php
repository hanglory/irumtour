			<?
			$day2_start_type="K,";
			if(strstr($day2_start_type,$plan_type)){
				$hotel_plus=1;
				$hotel_plus2=-1;
			}


			$hotel1day_i=1;

			if($HOTEL_ABS_MODE){
				if($hotel_plus) $hotel1day_i+=$hotel_plus;
				
				$hotel_data = get_abs_hotel($hotel1day_i);
				$hotel_name = $hotel_data['hotel_name'];
				$hotel_url = $hotel_data['hotel_url'];
				$hotel_phone = $hotel_data['hotel_phone'];
			}
			?>


			<?
			//호텔 수정 가능하도록-1일차
			if(!$plan_hotel[$hotel1day_i]){
				$plan_hotel[$hotel1day_i] = "호텔 : $hotel_name \r";
				if($hotel_phone) $plan_hotel[$hotel1day_i] .="전화번호 : $hotel_phone \r";
				$plan_hotel[$hotel1day_i] .=$hotel_url;
			}

			$HOTEL_NAMES="{@}".$hotel_name;
			?>
			<?if($edit_mode){?>
			<textarea name="plan_hotel1" class="editor_hotel"><?=$plan_hotel[$hotel1day_i]?></textarea>
			<?}else{?>
				<?
				$txt2=$plan_hotel[$hotel1day_i];
				include("inc_plan_color.php");
				$plan_hotel[$hotel1day_i]=$txt2;
				?>
				<span class="hotel_text"><?=nl2br($plan_hotel[$hotel1day_i])?></span>
			<?}?>