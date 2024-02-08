<?
for($i=1;$i<=$night;$i++){
	if($plan_text[$i]) $contents[$i] = nl2br($plan_text[$i]);
}
?>

		<table width="<?=$page_width?>" border="0" cellspacing="1" cellpadding="0" style="border-collapse:collapse;border:1px solid #ccc;width:<?=$page_width?>px">
          <tr>
            <td height="25" align="center" bgcolor="<?=$bg_color?>" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';font-weight:bold;color:#FFF">일자</span></td>
            <td width="10%" align="center" bgcolor="<?=$bg_color?>" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';font-weight:bold;color:#FFF">지역</span></td>
            <td width="10%" align="center" bgcolor="<?=$bg_color?>" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';font-weight:bold;color:#FFF">교통편</span></td>
            <td width="6%" align="center" bgcolor="<?=$bg_color?>" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';font-weight:bold;color:#FFF">시간</span></td>
            <td width="60%" align="center" bgcolor="<?=$bg_color?>" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';font-weight:bold;color:#FFF">여행일정</span></td>
            <td width="15%" align="center" bgcolor="<?=$bg_color?>" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';font-weight:bold;color:#FFF">비고</span></td>
          </tr>

		  <tr>
            <td height="25" align="center" bgcolor="<?=$bg_color?>" rowspan="4" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px;color:#FFF;font-weight:bold">제1일<br/><?=full_date($d_date)?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">
            	<?if($plan_add1_d) echo "$plan_add1_a<br>";?>
            	<?if($plan_add2_d) echo "$plan_add2_a<br>";?>
            	<?=$airport_place?>
            </td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">
            	<?if($plan_add1_d) echo "$plan_add1_b<br>";?>
            	<?if($plan_add2_d) echo "$plan_add2_b<br>";?>
            	<?=$d_air_no?>
            </td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">
            	<?if($plan_add1_d) echo "$plan_add1_c<br>";?>
            	<?if($plan_add2_d) echo "$plan_add2_c<br>";?>
            	<?=$d_time_s?>
            </td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';">
            	<?if($plan_add1_d) echo "$plan_add1_d<br>";?>
            	<?if($plan_add2_d) echo "$plan_add2_d<br>";?>
            	<?=$airport_in?>공항 출발
            </td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="4">


			<?
			if(!$plan_meal[1]){
				$plan_meal[1] = "석:기내식";
			}

			?>
			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_meal1" class="editor_meal"><?=$plan_meal[1]?></textarea>
			<?}else{?>
				<span class="meal_text"><?=nl2br(ico_meal($plan_meal[1]))?></span>
			<?}?>


			</td>
          </tr>
		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?//=str_replace("공항","",$airport_out)?><?=$airport_city?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$d_time_e?></td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';">
			<?=$airport_out?> 도착
			</td>
          </tr>
		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">
			<?
			if(!$plan_place[1]){
				$plan_place[1] = "";
			}
			?>
			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_place1" class="editor_place"><?=$plan_place[1]?></textarea>
			<?}else{?>
				<?=nl2br($plan_place[1])?>
			<?}?>
            </td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">

			<?
			if(!$plan_bus[1]){
				$plan_bus[1] = $car;
			}
			?>
			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_bus1" class="editor_bus"><?=$plan_bus[1]?></textarea>
			<?}else{?>
				<?=nl2br($plan_bus[1])?>
			<?}?>


            </td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;vertical-align:top">

			<?
			if(!$plan_time[1]){
				$plan_time[1] = "";
			}
			?>
			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_time1" class="editor_time"><?=$plan_time[1]?></textarea>
			<?}else{?>
				<?=nl2br($plan_time[1])?>
			<?}?>

			</td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';">

			<?
			if(!$plan_text[1]){
				$contents[1] ="";
				include("inc_plan_air_m.php");//국내선 이동
				$contents[1] .= "<$rs2[meeting_board]> 미팅보드를 들고 있는 안내원과 미팅 후 \n";
				$contents[1] .= "${car}을 이용하여 ${hotel_name}로 이동 $ah1 소요 \n";
				$contents[1] .= "호텔 체크인 및 휴식 \n";

				$txt1 = $contents[1];
				$txt2 = nl2br($contents[1]);
			}else{
				$txt1 = $plan_text[1];
				$txt2 = nl2br($plan_text[1]);
			}

			include("inc_plan_color.php");
			$txt2 = str_replace("[","<b>",$txt2);
			$txt2 = str_replace("]","</b>",$txt2);
			?>
			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_text1" class="editor"><?=$txt1?></textarea>
			<?}else{?>
				<?=$txt2?>
			<?}?>

			</td>
          </tr>
		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';">

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

			<?
			//호텔 수정 가능하도록-1일차
			if(!$plan_hotel[1]){
				$plan_hotel[1] = "호텔 : $hotel_name 또는 동급\r";
				if($hotel_phone) $plan_hotel[1] .="전화번호 : $hotel_phone \r";
				$plan_hotel[1] .=$hotel_url;
			}
			?>
			<?if($edit_mode){?>
			<textarea name="plan_hotel1" class="editor_hotel"><?=$plan_hotel[1]?></textarea>
			<?}else{?>
				<?
				$txt2=$plan_hotel[1];
				include("inc_plan_color.php");
				$plan_hotel[1]=$txt2;
				?>
				<span class="hotel_text"><?=nl2br($plan_hotel[1])?></span>
			<?}?>

			</td>
          </tr>

		  <?
		  $night_all=$night;
		  $long_days_txt = "";
		  if($bit_long_days){
			$night_all=3;
			$pre_day_i = $night - 2;
			$long_days_txt = "<br>~<br>제${pre_day_i}일" . full_date(date("Y/m/d",strtotime($r_date." -2 day")));
		  }

		  $j=0;
		  for($i=2; $i<$night_all;$i++){


			if($gname[$j]){ $golf_name_next = $gname[$j];$gh_next=$gh[$j];$j++;}
			else{ $golf_name_next=$gname[0]; $gh_next=$gh[0]; $j=1;}
			if($gap) $j2 = $i;
			else $j2 = $i-1;

		  ?>
		  <tr>
            <td height="25" align="center" bgcolor="<?=$bg_color?>" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;color:#FFF;font-weight:bold" rowspan="2">제<?=$i+$gap?>일<br><?=full_date(date("Y/m/d",strtotime($d_date." +$j2 day")))?> <?=$long_days_txt?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">

			<?
			//$i=$i+1; //19.2.25 날짜가 빠지는 문제 때문에 제외 함.
			if(!$plan_place[$i]){
				$city=($TOUR_CITY[$golfs[$x]])? $TOUR_CITY[$golfs[$x]] : $city; //190408
				$city=($GOLF_CITY[$golfs[$x]])? $GOLF_CITY[$golfs[$x]] : $city; //190408
				$plan_place[$i] = $city;
			}
			?>


			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_place<?=$i?>" class="editor_place2"><?=$plan_place[$i]?></textarea>
			<?}else{?>
				<?=nl2br($plan_place[$i])?>
			<?}?>

            </td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">

			<?
			if(!$plan_bus[$i]){
				$plan_bus[$i] = $car2;
			}
			?>
			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_bus<?=$i?>" class="editor_bus2"><?=$plan_bus[$i]?></textarea>
			<?}else{?>
				<?=nl2br($plan_bus[$i])?>
			<?}?>

            </td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;vertical-align:top">

			<?
			if(!$plan_time[$i]){
				$plan_time[$i] = "";
			}
			?>
			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_time<?=$i?>" class="editor_time2"><?=$plan_time[$i]?></textarea>
			<?}else{?>
				<?=nl2br($plan_time[$i])?>
			<?}?>

			</td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';">

			<?
			//골프장 이미지
			$golf_pic_width= 370;
			$golf_cnt = count(@array_filter($GOLF_PIC[$j]));

			$golf_img = "";
			if($golf_cnt){
				$golf_pic_width = floor($golf_pic_width/2);
				$golf_img="<div class='golf_img'>";
				for($gn=0; $gn < $golf_cnt;$gn++){
					$bit = (($gn+1)%2)? "golfp'" : "";
					if(!$BIT_GOLF_PHOTO_ALL && strstr($chk_imgs,$GOLF_PIC[$j][$gn])) $GOLF_PIC[$j][$gn]="";//한번 표시한 골프장은 초기화
					if($GOLF_PIC[$j][$gn]) $golf_img.= "<img src='http://irumtour.net/new/public/cmp/" . $GOLF_PIC[$j][$gn] . "' width='185' height='139' class='$bit r_${gn}'>";
					$chk_imgs.=$GOLF_PIC[$j][$gn];
				}
				$golf_img.="</div>";
			}
			//골프장 이미지 종료

			if(!$plan_text[$i]){
				$contents[$i] = "조식 후 골프장 이동 $gh_next 소요 \n";
				$contents[$i] .= "[$golf_name_next 라운드] {골프장이미지} \n";
				$contents[$i] .= "석식 후 휴식 \n";

				$txt1 = $contents[$i];
				$txt2 = nl2br($contents[$i]);
			}else{
				$txt1 = $plan_text[$i];
				$txt2 = nl2br($plan_text[$i]);
			}

			$txt2 = preg_replace('/\r\n|\r|\n/','',$txt2);
			$txt2 = str_replace("{<br />골프장이미지}","{골프장이미지}",$txt2);
			$txt2 = str_replace("{골<br />프장이미지}","{골프장이미지}",$txt2);
			$txt2 = str_replace("{골프<br />장이미지}","{골프장이미지}",$txt2);
			$txt2 = str_replace("{골프장<br />이미지}","{골프장이미지}",$txt2);
			$txt2 = str_replace("{골프장이<br />미지}","{골프장이미지}",$txt2);
			$txt2 = str_replace("{골프장이미<br />지}","{골프장이미지}",$txt2);
			$txt2 = str_replace("{골프장이미지<br />}","{골프장이미지}",$txt2);

			include("inc_plan_color.php");

			$txt2 = str_replace("{골프장이미지} <br /><br />","{골프장이미지} <br />",$txt2);
			$txt2 = str_replace("{골프장이미지}<br /><br />","{골프장이미지} <br />",$txt2);
			$txt2 = str_replace("{골프장이미지}",$golf_img,$txt2);

			//관광지 시작
			$tour_no=$i;
			include("inc_plan_tour.php");
			?>
			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_text<?=$i?>" class="editor2"><?=$txt1?></textarea>
			<?}else{?>
				<?=$txt2?>
			<?}?>

			</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">

			<?
			if(!$plan_meal[$i]){
				$plan_meal[$i] = str_replace("<br />","",$meal);
			}

			?>
			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_meal<?=$i?>" class="editor_meal"><?=$plan_meal[$i]?></textarea>
			<?}else{?>
				<span class="meal_text"><?=nl2br(ico_meal($plan_meal[$i]))?></span>
			<?}?>

			</td>
          </tr>
		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';">

			<?
			//2018-01-08
			if($HOTEL_ABS_MODE){
				if(strstr($hotel_days1,",${i},")){
					if(!$HOTEL_CNT1){
						$hotel2_name = $HOTELINFO_01[1];
						$hotel2_url = $HOTELINFO_02[1];
						$hotel2_phone = $HOTELINFO_03[1];
						$hotel_name = $HOTELINFO_01[1];
						$HOTEL_CNT1++;
					}else{
						$hotel2_name="";
						$hotel_name = $HOTELINFO_01[1];
					}
				}
				elseif(strstr($hotel_days2,",${i},")){
					if(!$HOTEL_CNT2){
						$hotel2_name = $HOTELINFO_01[2];
						$hotel2_url = $HOTELINFO_02[2];
						$hotel2_phone = $HOTELINFO_03[2];
						$hotel_name = $HOTELINFO_01[1];
						$HOTEL_CNT2++;
					}else{
						$hotel2_name="";
						$hotel_name = $HOTELINFO_01[2];
					}
				}
			}
			?>

			<?
			//호텔 수정 가능하도록
			if(!$plan_hotel[$i]){

				if($hotel2_name){
					$plan_hotel[$i] = "호텔 : $hotel2_name";
					if($HOTEL_ABS_MODE || $i==2){
						if($hotel2_phone) $plan_hotel[$i] .= "\r" . "전화번호 : $hotel2_phone \r" . "$hotel2_url";
					}
					$ah_next=$ah2;
				}else{
					$plan_hotel[$i] = "호텔 : $hotel_name";
					$ah_next=$ah1;
				}
			}
			?>
			<?if($edit_mode){?>
			<textarea name="plan_hotel<?=$i?>" class="editor_hotel"><?=$plan_hotel[$i]?></textarea>
			<?}else{?>
				<?
				$txt2=$plan_hotel[$i];
				include("inc_plan_color.php");
				$plan_hotel[$i]=$txt2;
				?>
				<span class="hotel_text"><?=nl2br($plan_hotel[$i])?></span>
			<?}?>

			</td>

          </tr>
		  <?}?>



		  <tr>
            <td height="25" align="center" bgcolor="<?=$bg_color?>" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;color:#FFF;font-weight:bold" rowspan="2">제<?=$night-1?>일<br><?=full_date(date("Y/m/d",strtotime($r_date." -1 day")))?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">

			<?
			if(!$plan_place[$i]){
				$city=($TOUR_CITY[$golfs[$x]])? $TOUR_CITY[$golfs[$x]] : $city; //190408
				$city=($GOLF_CITY[$golfs[$x]])? $GOLF_CITY[$golfs[$x]] : $city; //190408
				$plan_place[$i] = $city;
			}
			?>

			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_place<?=$i?>" class="editor_place2"><?=$plan_place[$i]?></textarea>
			<?}else{?>
				<?=nl2br($plan_place[$i])?>
			<?}?>

            </td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">

			<?
			if(!$plan_bus[$i]){
				$plan_bus[$i] = $car2;
			}
			?>
			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_bus<?=$i?>" class="editor_bus2"><?=$plan_bus[$i]?></textarea>
			<?}else{?>
				<?=nl2br($plan_bus[$i])?>
			<?}?>


            </td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;vertical-align:top">

			<?
			if(!$plan_time[$i]){
				$plan_time[$i] = "";
			}
			?>
			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_time<?=$i?>" class="editor_time2"><?=$plan_time[$i]?></textarea>
			<?}else{?>
				<?=nl2br($plan_time[$i])?>
			<?}?>

			</td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';">

			<?

			if($gname[$j]){ $golf_name_next = $gname[$j];$gh_next=$gh[$j];$ag_next=$ag[$j];$j2=($j+1);}
			else{ $golf_name_next=$gname[0]; $gh_next=$gh[0];$ag_next=$ag[0];$j2=1;}
			?>

			<?
			//골프장 이미지
			$golf_pic_width= 370;
			$golf_cnt = count(@array_filter($GOLF_PIC[$j2]));
			$golf_img = "";
			if($golf_cnt){
				$golf_pic_width = floor($golf_pic_width/2);
				$golf_img="<div class='golf_img'>";
				for($gn=0; $gn < $golf_cnt;$gn++){
					$bit = (($gn+1)%2)? "golfp'" : "";
					if(!$BIT_GOLF_PHOTO_ALL && strstr($chk_imgs,$GOLF_PIC[$j2][$gn])) $GOLF_PIC[$j2][$gn]="";//한번 표시한 골프장은 초기화
					if($GOLF_PIC[$j2][$gn]) $golf_img.= "<img src='http://irumtour.net/new/public/cmp/" . $GOLF_PIC[$j2][$gn] . "' width='185' height='139' class='$bit r_${gn}'>";
					$chk_imgs.=$GOLF_PIC[$j2][$gn];
				}
				$golf_img.="</div>";
			}
			//골프장 이미지 종료

			if(!$plan_text[$i]){
				$contents[$i] = "조식 후 골프장 이동 $gh_next 소요 \n";
				$contents[$i] .= "[$golf_name_next 라운드] {골프장이미지} \n";
				//$contents[$i] .= "석식 후 휴식 \n";

				$txt1 = $contents[$i];
				$txt2 = nl2br($contents[$i]);
			}else{
				$txt1 = $plan_text[$i];
				$txt2 = nl2br($plan_text[$i]);
			}

			$txt2 = preg_replace('/\r\n|\r|\n/','',$txt2);
			$txt2 = str_replace("{<br />골프장이미지}","{골프장이미지}",$txt2);
			$txt2 = str_replace("{골<br />프장이미지}","{골프장이미지}",$txt2);
			$txt2 = str_replace("{골프<br />장이미지}","{골프장이미지}",$txt2);
			$txt2 = str_replace("{골프장<br />이미지}","{골프장이미지}",$txt2);
			$txt2 = str_replace("{골프장이<br />미지}","{골프장이미지}",$txt2);
			$txt2 = str_replace("{골프장이미<br />지}","{골프장이미지}",$txt2);
			$txt2 = str_replace("{골프장이미지<br />}","{골프장이미지}",$txt2);

			include("inc_plan_color.php");

			$txt2 = str_replace("{골프장이미지} <br /><br />","{골프장이미지} <br />",$txt2);
			$txt2 = str_replace("{골프장이미지}<br /><br />","{골프장이미지} <br />",$txt2);
			$txt2 = str_replace("{골프장이미지}",$golf_img,$txt2);


			//관광지 시작
			$tour_no=$i;
			include("inc_plan_tour.php");
			?>
			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_text<?=$i?>" class="editor2"><?=$txt1?></textarea>
			<?}else{?>
				<?=$txt2?>
			<?}?>

			</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">

			<?
			if(!$plan_meal[$i]){
				$plan_meal[$i] = str_replace("<br />","",$meal);
			}

			?>
			<?if($edit_mode){?>
			<textarea wrap="hard" name="plan_meal<?=$i?>" class="editor_meal"><?=$plan_meal[$i]?></textarea>
			<?}else{?>
				<span class="meal_text"><?=nl2br(ico_meal($plan_meal[$i]))?></span>
			<?}?>

			</td>
          </tr>
		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$r_airport_city?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$car3?><div><?=$r_air_no?></div></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$r_time_s?></td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';"><?=$r_airport_out?>으로 이동. <?=$ag_next?>소요<br/><?=$r_airport_out?> 출발</td>
          </tr>



		  <tr>
            <td height="25" align="center" bgcolor="<?=$bg_color?>" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;color:#FFF;font-weight:bold" rowspan="3">제<?=$night?>일<br><?=full_date($r_date)?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">
            	<?=$r_airport_place?>
            	<?if($plan_add8_d) echo "<br/>$plan_add8_a";?>
				<?if($plan_add9_d) echo "<br/>$plan_add9_a";?>
            	</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">

				<?if($plan_add8_d) echo "<br/>$plan_add8_b";?>
				<?if($plan_add9_d) echo "<br/>$plan_add9_b";?>
            </td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">
            	<?=$r_time_e?>
				<?if($plan_add8_d) echo "<br/>$plan_add8_c";?>
				<?if($plan_add9_d) echo "<br/>$plan_add9_c";?>
            	</td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';">
				<?=$r_airport_in?>도착
				<?if($plan_add8_d) echo "<br/>$plan_add8_d";?>
				<?if($plan_add9_d) echo "<br/>$plan_add9_d";?>
			</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'나눔고딕','맑은고딕','돋움';line-height:130%;font-family:'나눔고딕','맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="3">

			</td>
          </tr>


        </table>