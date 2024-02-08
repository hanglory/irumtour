<?
for($i=1;$i<=$night;$i++){
	if($plan_text[$i]) $contents[$i] = nl2br($plan_text[$i]);
}
?>
		<table width="<?=$page_width?>" border="0" cellspacing="1" cellpadding="0" style="border-collapse:collapse;border:1px solid #ccc ">
          <tr>
            <td height="25" align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';font-weight:bold;color:#FFF">일자</span></td>
            <td width="10%" align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';font-weight:bold;color:#FFF">지역</span></td>
            <td width="10%" align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';font-weight:bold;color:#FFF">교통편</span></td>
            <td width="5%" align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';font-weight:bold;color:#FFF">시간</span></td>
            <td width="60%" align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';font-weight:bold;color:#FFF">여행일정</span></td>
            <td width="13%" align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';font-weight:bold;color:#FFF">비고</span></td>
          </tr>

		  <tr>
            <td height="25" align="center" bgcolor="#948A54" rowspan="4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px;color:#FFF;font-weight:bold">제1일<br/><?=full_date($d_date)?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$airport_place?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$d_air_no?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$d_time_s?></td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';"><?=$airport_in?>공항 출발</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="4">

			<?
			if(!$plan_meal[1]){
				$plan_meal[1] = "석:기내식";
			}
			?>
			<?if($edit_mode){?>
			<textarea name="plan_meal1" class="editor_meal"><?=$plan_meal[1]?></textarea>
			<?}else{?>
				<span class="meal_text"><?=nl2br($plan_meal[1])?></span>
			<?}?>


			</td>
          </tr>
		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?//=str_replace("공항","",$airport_out)?><?=$airport_city?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$d_time_e?></td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';">
			<?=$airport_out?> 도착
			</td>
          </tr>
		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;">

			<?
			if(!$plan_place[1]){
				$plan_place[1] = "";
			}
			?>
			<?if($edit_mode){?>
			<textarea name="plan_place1" class="editor_place"><?=$plan_place[1]?></textarea>
			<?}else{?>
				<?=nl2br($plan_place[1])?>
			<?}?>


			</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;">

			<?
			if(!$plan_bus[1]){
				$plan_bus[1] = $car;
			}
			?>
			<?if($edit_mode){?>
			<textarea name="plan_bus1" class="editor_bus"><?=$plan_bus[1]?></textarea>
			<?}else{?>
				<?=nl2br($plan_bus[1])?>
			<?}?>


			</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;vertical-align:top">

			<?
			if(!$plan_time[1]){
				$plan_time[1] = "";
			}
			?>
			<?if($edit_mode){?>
			<textarea name="plan_time1" class="editor_time2"><?=$plan_time[1]?></textarea>
			<?}else{?>
				<?=nl2br($plan_time[1])?>
			<?}?>

			</td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';">

			<?
			if(!$plan_text[1]){
				$contents[1] = "[$rs2[meeting_board]] 미팅보드를 들고 있는 안내원과 미팅 후 \n";
				$contents[1] .= "${car}을 이용하여 ${hotel_name}로 이동 $ah1 소요 \n";
				$contents[1] .= "호텔 체크인 및 휴식 \n";

				$txt1 = $contents[1];
				$txt2 = nl2br($contents[1]);
			}else{
				$txt1 = $plan_text[1];
				$txt2 = nl2br($plan_text[1]);
			}
			$txt2 = str_replace("[","<b>",$txt2);
			$txt2 = str_replace("]","</b>",$txt2);
			?>

			<?if($edit_mode){?>
			<textarea name="plan_text1" class="editor" style="height:80px"><?=$txt1?></textarea>
			<?}else{?>
				<?=$txt2?>
			<?}?>

			</td>
          </tr>
		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';">호텔 : <?=$hotel_name?>  <br/><?=($hotel_phone)?"전화번호 : $hotel_phone":""?><div><?=$hotel_url?></div></td>
          </tr>


		  <?
		  $j=0;
		  for($i=2; $i<$night;$i++){

			if($gname[$j]){ $golf_name_next = $gname[$j];$gh_next=$gh[$j];$ag_next=$ag[$j];$j++;}
			else{ $golf_name_next=$gname[0]; $gh_next=$gh[0]; $ag_next=$ag[0]; $j=1;}
			$j2 = $i-1;


			$bit_last_hotel = ($i!=($night-1))? 0: 1 ;

			$rowspan = ($bit_last_hotel)? "": "rowspan='2'";

		  ?>
		  <tr>
            <td height="25" align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;color:#FFF;font-weight:bold" <?=$rowspan?>>제<?=$i?>일<br><?=full_date(date("Y/m/d",strtotime($d_date." +$j2 day")))?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;">

			<?
			if(!$plan_place[$i]){
				$plan_place[$i] = $city;
			}
			?>
			<?if($edit_mode){?>
			<textarea name="plan_place<?=$i?>" class="editor_place2"><?=$plan_place[$i]?></textarea>
			<?}else{?>
				<?=nl2br($plan_place[$i])?>
			<?}?>

			</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;">

			<?
			if(!$plan_bus[$i]){
				$plan_bus[$i] = $car2;
			}
			?>
			<?if($edit_mode){?>
			<textarea name="plan_bus<?=$i?>" class="editor_bus2"><?=$plan_bus[$i]?></textarea>
			<?}else{?>
				<?=nl2br($plan_bus[$i])?>
			<?}?>


			</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;vertical-align:top">

			<?
			if(!$plan_time[$i]){
				$plan_time[$i] = "";
			}
			?>
			<?if($edit_mode){?>
			<textarea name="plan_time<?=$i?>" class="editor_time2"><?=$plan_time[$i]?></textarea>
			<?}else{?>
				<?=nl2br($plan_time[$i])?>
			<?}?>

			</td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';">

			<?
			if(!$plan_text[$i]){
				$contents[$i] = "조식 후 골프장 이동. $gh_next 소요 \n";
				$contents[$i] .= "[$golf_name_next 18홀 라운드] \n";
				$contents[$i] .= "석식 후 휴식 \n";

				$txt1 = $contents[$i];
				$txt2 = nl2br($contents[$i]);
			}else{
				$txt1 = $plan_text[$i];
				$txt2 = nl2br($plan_text[$i]);
			}
			$txt2 = str_replace("[","<b>",$txt2);
			$txt2 = str_replace("]","</b>",$txt2);
			?>
			<?if($edit_mode){?>
			<textarea name="plan_text<?=$i?>" class="editor2"><?=$txt1?></textarea>
			<?}else{?>
				<?=$txt2?>
			<?}?>

			</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc" <?=$rowspan?>>

			<?
			if(!$plan_meal[$i]){
				$plan_meal[$i] = str_replace("<br />","",$meal);
			}
			?>
			<?if($edit_mode){?>
			<textarea name="plan_meal<?=$i?>" class="editor_meal"><?=$plan_meal[$i]?></textarea>
			<?}else{?>
				<span class="meal_text"><?=nl2br($plan_meal[$i])?></span>
			<?}?>

			</td>
          </tr>

		  <?if(!$bit_last_hotel){?>
		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';">

			<?if($hotel2_name){?>
				호텔 : <?=$hotel2_name?>  <?if($i==2){?><br/><?=($hotel2_phone)?"전화번호 : $hotel2_phone":""?><div><?=$hotel2_url?><?}?></div>
			<?}else{?>
				호텔 : <?=$hotel_name?>
			<?}?>

			</div></td>
          </tr>
		  <?}?>
		  <?}?>

		  <tr>
            <td height="25" align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;color:#FFF;font-weight:bold" rowspan="2">제<?=$night?>일<br><?=full_date($r_date)?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$r_airport_city?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$car3?><div><?=$r_air_no?></div></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$r_time_s?></td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';"><?=$airport_out?>으로 이동. <?=$ag_next?><br/><?=$airport_out?> 출발</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">

			</td>
          </tr>



		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$r_airport_place?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$r_time_e?></td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'Malgun Gothic','돋움';line-height:130%;font-family:'Malgun Gothic','돋움';">
				<?=$airport_in?>도착
			</td>

          </tr>


        </table>