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

               <?include($_SERVER['DOCUMENT_ROOT']."/renew/inc_plan_add_top.php")?>

               <tr>
                 <th scope="row"><?=$airport_place?></th>
                 <td class="traffic"><?=$d_air_no?></td>
                 <td class="time"><?=$d_time_s?></td>
                 <td><div class="time2 hide"><?=$d_time_s?></div><?=$airport_in?> 출발</td>
               </tr>

               <tr>
                 <th scope="row"></th>
                 <td class="traffic"></td>
                 <td class="time"></td>
                 <td>기내 1박</td>
               </tr>


               <tr>
                 <th scope="">&nbsp;</th>
                 <td class="traffic"></td>
                 <td class="time"></td>
                 <td>
					<?
					$plan_meal[1] = "석:기내식\n";
					?>
					<?=ico_meal($plan_meal[1])?>
				 </td>
               </tr>





              <!-- 2일차 -->
			  <tbody>
                <tr>
                  <th colspan="4" scope="col" class="dat_t">2days</th>
                </tr>


               <tr>
                 <th scope="row"><?=$airport_city?></th>
                 <td class="traffic"></td>
                 <td class="time"><?=$d_time_e?></td>
                 <td><div class="time2 hide"><?=$d_time_e?></div><?=$airport_out?> 도착<!-- <br/>가이드 미팅 후 전용차량 이용하여 (호텔)로 이동 --></td>
               </tr>
               <tr>
                 <th scope="row">&nbsp;</th>
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

                        <div class="time2 hide"><?=nl2br($plan_time[1])?></div>

	             		<?
	             		//관광지 이름 확인
						$tour_no=2;//일차
						$tour_today_name="";
						if(strstr($tour_days1,",${tour_no},") && $tour_id_no1){$tour_today_name.=$TOUR_NAME[$tour_id_no1];}
						if(strstr($tour_days2,",${tour_no},") && $tour_id_no2){$tour_today_name.=$TOUR_NAME[$tour_id_no2];}
						if(strstr($tour_days3,",${tour_no},") && $tour_id_no3){$tour_today_name.=$TOUR_NAME[$tour_id_no3];}
						if(strstr($tour_days4,",${tour_no},") && $tour_id_no4){$tour_today_name.=$TOUR_NAME[$tour_id_no4];}
						if(strstr($tour_days5,",${tour_no},") && $tour_id_no5){$tour_today_name.=$TOUR_NAME[$tour_id_no5];}
						if(strstr($tour_days6,",${tour_no},") && $tour_id_no6){$tour_today_name.=$TOUR_NAME[$tour_id_no6];}
						?>


						<?
						//국제선 타고가서 국내선을 탈 경우
						$contents[1] ="";
						include("../new/bkoff/cmp/inc_plan_air_n.php");//국내선 이동
						if($contents[1]) echo nl2br($contents[1]);
						?>


                 		<?if(rnf($golf_over) && strstr($golf_over,",${i},")){//전일정골프?>
                 			관광<br/>
		                 	<span class='tourname'><?=$tour_today_name?></span>

		                 	<p>&nbsp;</p>

                 		<?}elseif($gname[0] && $tour_today_name){//골프 후 관광?>

							<span class='tourname'><?=$tour_today_name?></span>
							<?
							$tour_no=2;//일차
							if(strstr($tour_days1,",${tour_no},") && $tour_id_no1){$code = $tour_id_no1;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days2,",${tour_no},") && $tour_id_no2){$code = $tour_id_no2;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days3,",${tour_no},") && $tour_id_no3){$code = $tour_id_no3;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days4,",${tour_no},") && $tour_id_no4){$code = $tour_id_no4;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days5,",${tour_no},") && $tour_id_no5){$code = $tour_id_no5;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days6,",${tour_no},") && $tour_id_no6){$code = $tour_id_no6;$inc_table = "cmp_tour";include("inc_golf.php");}
							?>
							<p>&nbsp;</p>

							<span class="bx_inter"><?=${car}?>을 이용하여 <?=$hotel_name?> 이동 <?=${ah1}?> 소요</span>
                 		<?}else{//관광 없는 경우(기본)?>

							<span class="bx_inter"><?=${car}?>을 이용하여 <?=$hotel_name?> 이동 <?=${ah1}?> 소요</span>
						<?}?>

						<br>
						호텔 체크인 및 휴식 <br/>

				 </td>
               </tr>

               <!--2일차 호텔 시작-->
               <tr>
                 <td class="traffic"></td>
                 <td class="traffic"></td>
                 <td class="time"></td>
                 <td>

					<?
					//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){echo $i;}
					$i=2;
					$x="";
					if($HOTEL_ABS_MODE){
						if(strstr($hotel_days1,",${i},")){
							if(!$HOTEL_CNT1){
								$hotel2_code = $HOTELINFO_00[1];
								$hotel2_name = $HOTELINFO_01[1];
								$hotel2_url = $HOTELINFO_02[1];
								$hotel2_phone = $HOTELINFO_03[1];
								$HOTEL_CNT1++;
							}else{
								$hotel2_name="";
								$hotel_code = $HOTELINFO_00[1];
								$hotel_name = $HOTELINFO_01[1];
							}
							$x=1;
						}
						elseif(strstr($hotel_days2,",${i},")){
							if(!$HOTEL_CNT2){
								$hotel2_code = $HOTELINFO_00[2];
								$hotel2_name = $HOTELINFO_01[2];
								$hotel2_url = $HOTELINFO_02[2];
								$hotel2_phone = $HOTELINFO_03[2];
								$HOTEL_CNT2++;
							}else{
								$hotel2_name="";
								$hotel_code = $HOTELINFO_00[2];
								$hotel_name = $HOTELINFO_01[2];
							}
							$x=2;
						}
					}

					?>

					<?
					//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27") checkVar("hotel2_name",$hotel2_name);
					if($hotel2_name){
						$x=1;
						$code=$hotel2_code;
					?>
						호텔 : <?=$hotel2_name?>  <?if($HOTEL_ABS_MODE || $i==2){?><br/><?=($hotel2_phone)?"전화번호 : $hotel2_phone":""?><div><?=$hotel2_url?><?}?></div>
					<?
					}else{
						$x=1;
						$code=$hotel_code;
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
               <!--2일차 호텔 종료-->

               <tr>
                 <th scope="">&nbsp;</th>
                 <td class="traffic"></td>
                 <td class="time"></td>
                 <td>
					<?
					/*
					$plan_meal[1] = "조:기내식\n";
					$plan_meal[1] .= "중:불포함\n";
					$plan_meal[1] .= "석:불포함\n";
					*/
					$plan_meal[1] = str_replace("<br />","",$meal);
					?>
					<?=ico_meal($plan_meal[1])?>
				 </td>
               </tr>




		  <!-- 3~n일차 -->
		  <?
		  $night_all=$night;
		  $long_days_txt = "";
		  if($bit_long_days){
			$night_all=3;
			$pre_day_i = $night - 1;
			$long_days_txt = "<br>~<br>제${pre_day_i}일" . (date("Y/m/d",strtotime($r_date." -1 day")));
		  }

		  $j=0;
		  for($i=3; $i<$night_all;$i++){
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
						$city=($TOUR_CITY[$golfs[$x]])? $TOUR_CITY[$golfs[$x]] : $city; //190408
						$city=($GOLF_CITY[$golfs[$x]])? $GOLF_CITY[$golfs[$x]] : $city; //190408
						$plan_place[$i] = $city;
					}
					?>
					<?=nl2br($plan_place[$i])?>

				 </th>
                 <td class="traffic"><?=nl2br($plan_bus[$i])?></td>
                 <td class="time"><?=nl2br($plan_time[$i])?></td>
                 <td>

                        <div class="time2 hide"><?=nl2br($plan_time[$i])?></div>
                 		<?
                 		//관광지 이름 확인
						$tour_no=$i;//일차
						$tour_today_name="";
						if(strstr($tour_days1,",${tour_no},") && $tour_id_no1){$tour_today_name.=$TOUR_NAME[$tour_id_no1];}
						if(strstr($tour_days2,",${tour_no},") && $tour_id_no2){$tour_today_name.=$TOUR_NAME[$tour_id_no2];}
						if(strstr($tour_days3,",${tour_no},") && $tour_id_no3){$tour_today_name.=$TOUR_NAME[$tour_id_no3];}
						if(strstr($tour_days4,",${tour_no},") && $tour_id_no4){$tour_today_name.=$TOUR_NAME[$tour_id_no4];}
						if(strstr($tour_days5,",${tour_no},") && $tour_id_no5){$tour_today_name.=$TOUR_NAME[$tour_id_no5];}
						if(strstr($tour_days6,",${tour_no},") && $tour_id_no6){$tour_today_name.=$TOUR_NAME[$tour_id_no6];}
						?>

                 		<?if(rnf($golf_over) && strstr($golf_over,",${i},")){//전일정골프?>
                 			조식 후<br>
		                 	<span class='tourname'><?=$tour_today_name?></span>

		                 	<?
							//관광지
							$tour_no=$i;//일차
							if(strstr($tour_days1,",${tour_no},") && $tour_id_no1){$code = $tour_id_no1;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days2,",${tour_no},") && $tour_id_no2){$code = $tour_id_no2;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days3,",${tour_no},") && $tour_id_no3){$code = $tour_id_no3;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days4,",${tour_no},") && $tour_id_no4){$code = $tour_id_no4;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days5,",${tour_no},") && $tour_id_no5){$code = $tour_id_no5;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days6,",${tour_no},") && $tour_id_no6){$code = $tour_id_no6;$inc_table = "cmp_tour";include("inc_golf.php");}
							?>

                 		<?}elseif($golf_name_next && $tour_today_name){//골프 후 관광?>
							조식 후 골프장 이동 <?=$gh_next?> 소요 <br>
							<span class='golfname'>[<?=$golf_name_next?>  라운드]</span>

							<?
							$code = $golfs[$x];
							$inc_table = "cmp_golf2";
							include("inc_golf.php");
							?>
							<p>&nbsp;</p>

							<span class='tourname'><?=$tour_today_name?></span>
		                	<?
							//관광지
							$tour_no=$i;//일차
							if(strstr($tour_days1,",${tour_no},") && $tour_id_no1){$code = $tour_id_no1;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days2,",${tour_no},") && $tour_id_no2){$code = $tour_id_no2;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days3,",${tour_no},") && $tour_id_no3){$code = $tour_id_no3;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days4,",${tour_no},") && $tour_id_no4){$code = $tour_id_no4;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days5,",${tour_no},") && $tour_id_no5){$code = $tour_id_no5;$inc_table = "cmp_tour";include("inc_golf.php");}
							if(strstr($tour_days6,",${tour_no},") && $tour_id_no6){$code = $tour_id_no6;$inc_table = "cmp_tour";include("inc_golf.php");}
							?>

                 		<?}else{//관광 없는 경우?>
							조식 후 골프장 이동 <?=$gh_next?> 소요 <br>
							<span class='golfname'>[<?=$golf_name_next?>  라운드]</span>

							<?
							$code = $golfs[$x];
							$inc_table = "cmp_golf2";
							include("inc_golf.php");
							?>

						<?}?>

						<p>&nbsp;</p>
						석식 후 휴식<br>
				 </td>
               </tr>
               <tr>
                 <td class="traffic"></td>
                 <td class="time"></td>
                 <td>

					<?
					//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){echo $i;}
					$x="";
					if($HOTEL_ABS_MODE){
						if(strstr($hotel_days1,",${i},")){
							if(!$HOTEL_CNT1){
								$hotel2_code = $HOTELINFO_00[1];
								$hotel2_name = $HOTELINFO_01[1];
								$hotel2_url = $HOTELINFO_02[1];
								$hotel2_phone = $HOTELINFO_03[1];
								$HOTEL_CNT1++;
							}else{
								$hotel2_name="";
								$hotel_code = $HOTELINFO_00[1];
								$hotel_name = $HOTELINFO_01[1];
							}
							$x=1;
						}
						elseif(strstr($hotel_days2,",${i},")){
							if(!$HOTEL_CNT2){
								$hotel2_code = $HOTELINFO_00[2];
								$hotel2_name = $HOTELINFO_01[2];
								$hotel2_url = $HOTELINFO_02[2];
								$hotel2_phone = $HOTELINFO_03[2];
								$HOTEL_CNT2++;
							}else{
								$hotel2_name="";
								$hotel_code = $HOTELINFO_00[2];
								$hotel_name = $HOTELINFO_01[2];
							}
							$x=2;
						}
					}

					?>

					<?
					//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27") checkVar("hotel2_name",$hotel2_name);
					if($hotel2_name){
						$x=1;
						$code=$hotel2_code;
					?>
						호텔 : <?=$hotel2_name?>  <?if($HOTEL_ABS_MODE || $i==2){?><br/><?=($hotel2_phone)?"전화번호 : $hotel2_phone":""?><div><?=$hotel2_url?><?}?></div>
					<?
					}else{
						$x=1;
						$code=$hotel_code;
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
                 <th scope="row"><?//=$city?></th>
                 <td class="traffic"><?=$car2?></td>
                 <td class="time">&nbsp;</td>
                 <td>
				 	조식 후 체크아웃

                 		<?
                 		//마지막날 관광
                 		//관광지 이름 확인
						$tour_no=$i;//일차
						$tour_today_name="";
						if(strstr($tour_days1,",${tour_no},") && $tour_id_no1){$tour_today_name.=$TOUR_NAME[$tour_id_no1];}
						if(strstr($tour_days2,",${tour_no},") && $tour_id_no2){$tour_today_name.=$TOUR_NAME[$tour_id_no2];}
						if(strstr($tour_days3,",${tour_no},") && $tour_id_no3){$tour_today_name.=$TOUR_NAME[$tour_id_no3];}
						if(strstr($tour_days4,",${tour_no},") && $tour_id_no4){$tour_today_name.=$TOUR_NAME[$tour_id_no4];}
						if(strstr($tour_days5,",${tour_no},") && $tour_id_no5){$tour_today_name.=$TOUR_NAME[$tour_id_no5];}
						if(strstr($tour_days6,",${tour_no},") && $tour_id_no6){$tour_today_name.=$TOUR_NAME[$tour_id_no6];}
						?>
						<span class='tourname'><?=$tour_today_name?></span>
	                	<?
						//관광지
						$tour_no=$i;//일차
						if(strstr($tour_days1,",${tour_no},") && $tour_id_no1){$code = $tour_id_no1;$inc_table = "cmp_tour";include("inc_golf.php");}
						if(strstr($tour_days2,",${tour_no},") && $tour_id_no2){$code = $tour_id_no2;$inc_table = "cmp_tour";include("inc_golf.php");}
						if(strstr($tour_days3,",${tour_no},") && $tour_id_no3){$code = $tour_id_no3;$inc_table = "cmp_tour";include("inc_golf.php");}
						if(strstr($tour_days4,",${tour_no},") && $tour_id_no4){$code = $tour_id_no4;$inc_table = "cmp_tour";include("inc_golf.php");}
						if(strstr($tour_days5,",${tour_no},") && $tour_id_no5){$code = $tour_id_no5;$inc_table = "cmp_tour";include("inc_golf.php");}
						if(strstr($tour_days6,",${tour_no},") && $tour_id_no6){$code = $tour_id_no6;$inc_table = "cmp_tour";include("inc_golf.php");}
						?>

					<?if($r_add1 || $r_add2){?>
					<div>
						<p><?=$r_add1?></p>
						<p><?=$r_add2?></p>
						</div>
					<?}?>
				 </td>
               </tr>
               <tr>
                 <th scope="row" rowspan="2"><?=$r_airport_city?></th>
                 <td class="traffic"><?=$car3?> <?=$r_air_no?></td>
                 <td class="time"><?=$r_time_s?></td>
                 <td><div class="time2 hide"><?=$r_time_s?></div><?=$r_airport_out?>으로 이동. <?=$ah2?> 소요<br/><?=$r_airport_out?> 출발</td>
               </tr>
               <tr>
                 <td class="traffic"></td>
                 <td class="time"></td>
                 <td>
					<?
					if(!$plan_meal[$i]){
						$plan_meal[$i] = "조:기내식\n";
					}
					?>
						<span class="meal_text"><?=(ico_meal($plan_meal[$i]))?></span>

				 </td>
               </tr>

               <tr>
                 <th scope="row"><?=$r_airport_place?></th>
                 <td class="traffic"></td>
                 <td class="time"><?=$r_time_e?></td>
                 <td><div class="time2 hide"><?=$r_time_e?></div><?=$r_airport_in?>도착</td>
               </tr>

               <?include($_SERVER['DOCUMENT_ROOT']."/renew/inc_plan_add_bottom.php")?>

              </tbody>
            </table>






