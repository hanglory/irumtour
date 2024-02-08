<?
$form_mode=2;

for($i=1;$i<=$night;$i++){
	if($plan_text[$i]) $contents[$i] = nl2br($plan_text[$i]);
}
?>



            <table summary="일정표" class="tbl_schedule" cellpadding="0" cellspacing="0" >
              <caption>일정표</caption>
              <?if(!$MOBILE){?>
			  <colgroup>
                <col width="15%" />
                <col width="10%" />
                <col width="10%" />
                <col width="*" />
              </colgroup>
			  <?}?>

              <!-- 1일차 -->
			  <tbody>
                <tr>
                  <th colspan="4" scope="col" class="dat_t">1days</th>
                </tr>
               <tr>
                 <th scope="row"><?=$airport_place?></th>
                 <td class="traffic"><?=$d_air_no?></td>
                 <td class="time"><?=$d_time_s?></td>
                 <td><?=$airport_in?> 출발</td>
               </tr>

               <tr>
                 <th scope="row" rowspan="4"><?=$airport_city?></th>
                 <td class="traffic"></td>
                 <td class="time"><?=$d_time_e?></td>
                 <td><?=$airport_out?> 도착</td>
               </tr>
               <tr>
                 <td class="traffic">
					<?
					if(!$plan_bus[1]){
						$plan_bus[1] = $car;
					}
					?>
					<?=nl2br($plan_bus[1])?>
				 </td>
                 <td class="time">
					<?
					if(!$plan_time[1]){
						$plan_time[1] = "";
					}
					?>
					<?=nl2br($plan_time[1])?>
				 </td>
                 <td>

					<<?=$rs2[meeting_board]?>> 미팅보드를 들고 있는 안내원과 미팅 후 <br/>
					<?=${car}?>을 이용하여 골프장으로 이동 <?=${ag1}?> 소요 <br/>
					[<?=$gname[0]?>  라운드] <br/>

					라운드 후 호텔로 이동 - <?=${gh1}?> 소요 <br/>
					호텔 체크인 후 석식 및 휴식 <br/>

					<?
					$code = $golfs[0];
					$inc_table = "cmp_golf2";
					include("inc_golf.php");
					?>

				 </td>
               </tr>

               <tr>
                 <td class="traffic"></td>
                 <td class="time"></td>
                 <td>
					<?
					//2018-01-08
					if($HOTEL_ABS_MODE){
						if(strstr($hotel_days1,",1,")){
							$hotel_name = $HOTELINFO_01[1];
							$hotel_url = $HOTELINFO_02[1];
							$hotel_phone = $HOTELINFO_03[1];
							$HOTEL_CNT1++;
						}
						elseif(strstr($hotel_days2,",1,")){
							$hotel_name = $HOTELINFO_01[2];
							$hotel_url = $HOTELINFO_02[2];
							$hotel_phone = $HOTELINFO_03[2];
							$HOTEL_CNT2++;
						}
					}
					?>

					호텔 : <?=$hotel_name?>  <br/><?=($hotel_phone)?"전화번호 : $hotel_phone":""?><div><?=$hotel_url?></div>


					<?
					$code = $HOTELINFO_00[1];
					if(!strstr($cmp_hotel,"@$code@")){
						$cmp_hotel .=	"@$code@";
						$inc_table = "cmp_hotel";
						include("inc_golf.php");
					}
					?>

				 </td>
               </tr>

               <tr>
                 <td class="traffic"></td>
                 <td class="time"></td>
                 <td>
					<?
					if($meal_type==2){
						$plan_meal[1] = "석:불포함\n";
					}else{
						$plan_meal[1] = "석:호텔식\n";
					}
					?>
					<?=ico_meal($plan_meal[1])?>
				 </td>
               </tr>

		  <!-- 2~n일차 -->
		  <?
		  $night_all=$night;
		  $long_days_txt = "";
		  if($bit_long_days){
			$night_all=3;
			$pre_day_i = $night - 1;
			$long_days_txt = "<br>~<br>제${pre_day_i}일" . (date("Y/m/d",strtotime($r_date." -1 day")));
		  }

		  $j=1;
		  for($i=2; $i<$night_all;$i++){
			$x = "";
			if($gname[$j]){$x = $j;$golf_name_next = $gname[$j]; $gh_next = $gh[$j]; $j++;}
			else{$x = 0;$golf_name_next=$gname[0]; $gh_next = $gh[0]; $j=1;}
			$j2 = $i-1;
		  ?>
                <tr>
                  <th colspan="4" scope="col" class="dat_t"><?=$i?>days</th>
                </tr>
               <tr>
                 <th scope="row" rowspan="3">
					<?
					if(!$plan_place[$i]){
						$plan_place[$i] = $city;
					}
					?>
					<?=nl2br($plan_place[$i])?>

				 </th>
                 <td class="traffic"><?=nl2br($plan_bus[$i])?></td>
                 <td class="time"><?=nl2br($plan_time[$i])?></td>
                 <td>
						조식 후 골프장 이동 <?=$gh_next?> 소요 <br>
						[<?=$golf_name_next?>  라운드]<br>
						석식 후 휴식<br>

						<?
						$code = $golfs[$x];
						$inc_table = "cmp_golf2";
						include("inc_golf.php");
						?>
				 </td>
               </tr>
               <tr>
                 <td class="traffic"></td>
                 <td class="time"></td>
                 <td>

					<?
					$x="";
					if($HOTEL_ABS_MODE){
						if(strstr($hotel_days1,",${i},")){
							if(!$HOTEL_CNT1){
								$hotel2_name = $HOTELINFO_01[1];
								$hotel2_url = $HOTELINFO_02[1];
								$hotel2_phone = $HOTELINFO_03[1];
								$HOTEL_CNT1++;
							}else{
								$hotel2_name="";
								$hotel_name = $HOTELINFO_01[1];
							}
							$x=1;
						}
						elseif(strstr($hotel_days2,",${i},")){
							if(!$HOTEL_CNT2){
								$hotel2_name = $HOTELINFO_01[2];
								$hotel2_url = $HOTELINFO_02[2];
								$hotel2_phone = $HOTELINFO_03[2];
								$HOTEL_CNT2++;
							}else{
								$hotel2_name="";
								$hotel_name = $HOTELINFO_01[2];
							}
							$x=2;
						}
					}
					?>

					<?
					if($hotel2_name){
						$x=2;
					?>
						호텔 : <?=$hotel2_name?>  <?if($HOTEL_ABS_MODE || $i==2){?><br/><?=($hotel2_phone)?"전화번호 : $hotel2_phone":""?><div><?=$hotel2_url?><?}?></div>
					<?
					}else{
						$x=1;
					?>
						호텔 : <?=$hotel_name?>
					<?}?>

					<br>
					<?
					$code = $HOTELINFO_00[$x];
					if(!strstr($cmp_hotel,"@$code@")){
						$cmp_hotel .=	"@$code@";
						$inc_table = "cmp_hotel";
						include("inc_golf.php");
					}
					?>


				 </td>
               </tr>
               <tr>
                 <td class="traffic"></td>
                 <td class="time"></td>
                 <td>
					<?
					if(!$plan_meal[$i]){
						$plan_meal[$i] = str_replace("<br />","",$meal);
					}
					?>
					<span class="meal_text"><?=(ico_meal($plan_meal[$i]))?></span>
				 </td>
               </tr>
		  <?}?>



			   <!-- 마지막날 -->
               <tr>
                  <th colspan="4" scope="col" class="dat_t"><?=$night?>days</th>
                </tr>
               <tr>
                 <th scope="row">
					<?
					if(!$plan_place[$i]){
						$plan_place[$i] = $city;
					}
					?>
					<?=nl2br($plan_place[$i])?>

				 </th>
                 <td class="traffic"><?=nl2br($plan_bus[$i])?></td>
                 <td class="time"><?=nl2br($plan_time[$i])?></td>
                 <td>
					<?
					$x="";
					if($gname[$j]){$x = $j;$golf_name_next = $gname[$j]; $gh_next = $gh[$j]; $ag_next = $ag[$j];$j2=($j+1);}
					else{$x = 0;$golf_name_next=$gname[0]; $gh_next=$gh[0]; $ag_next= $ag[0];$j2=1;}
					?>

					조식 후 체크아웃 or 골프장으로 이동. <?=$gh_next?> 소요 <br/>
					[<?=$golf_name_next?>  라운드] <br/>

					<?
					$code = $golfs[0];
					$inc_table = "cmp_golf2";
					include("inc_golf.php");
					?>

				 </td>
               </tr>
               <tr>
                 <th scope="row" rowspan="2"><?=$r_airport_city?></th>
                 <td class="traffic"><?=$car3?> <?=$r_air_no?></td>
                 <td class="time"><?=$r_time_s?></td>
                 <td><?=$airport_out?>으로 이동. <?=$ah2?> 소요<br/><?=$airport_out?> 출발</td>
               </tr>
               <tr>
                 <td class="traffic"></td>
                 <td class="time"></td>
                 <td>
					<?
					if(!$plan_meal[$i]){
						$plan_meal[$i] = "조:호텔식\n";
						$plan_meal[$i] .= "중:불포함";
					}
					?>
						<span class="meal_text"><?=(ico_meal($plan_meal[$i]))?></span>

				 </td>
               </tr>

               <tr>
                 <th scope="row"><?=$r_airport_place?></th>
                 <td class="traffic"></td>
                 <td class="time"><?=$r_time_e?></td>
                 <td><?=$airport_in?>도착</td>
               </tr>

              </tbody>
            </table>
