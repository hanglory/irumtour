<?
for($i=1;$i<=$night;$i++){
	if($plan_text[$i]) $contents[$i] = nl2br($plan_text[$i]);
}
?>
		<table width="<?=$page_width?>" border="0" cellspacing="1" cellpadding="0" style="border-collapse:collapse;border:1px solid #ccc ">
          <tr>
            <td height="25" align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';font-weight:bold;color:#FFF">일자</span></td>
            <td align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';font-weight:bold;color:#FFF">지역</span></td>
            <td align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';font-weight:bold;color:#FFF">교통편</span></td>
            <td align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';font-weight:bold;color:#FFF">시간</span></td>
            <td width="60%" align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';font-weight:bold;color:#FFF">여행일정</span></td>
            <td align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><span style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';font-weight:bold;color:#FFF">비고</span></td>
          </tr>

		  <tr>
            <td height="25" align="center" bgcolor="#948A54" rowspan="4" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px;color:#FFF;font-weight:bold">제1일</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$airport_place?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$d_air_no?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$d_time_s?></td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';"><?=$airport_in?>공항 출발</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="4">


			석:기내식

			</td>
          </tr>
		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?//=str_replace("공항","",$airport_out)?><?=$airport_city?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$d_time_e?></td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';">
			<?=$airport_out?> 도착
			</td>
          </tr>
		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$car?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"></td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';">

			<?
			if(!$plan_text[1]){
				$contents[1] = "[$rs2[meeting_board]] 미팅보드를 들고 있는 안내원과 미팅 후 \n";
				$contents[1] .= "${car}을 이용하여 ${hotel_name}로 이동 00분 소요 \n";
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
			<textarea name="plan_text1" class="editor"><?=$txt1?></textarea>
			<?}else{?>
				<?=$txt2?>
			<?}?>


			</td>
          </tr>
		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';">호텔 : <?=$hotel_name?> 또는 동급 <br/><?=($hotel_phone)?"전화번호 : $hotel_phone":""?><div><?=$hotel_url?></div></td>
          </tr>


		  <?
		  $j=1;
		  for($i=2; $i<$night-1;$i++){

			if($gname[$j]){ $golf_name_next = $gname[$j];$j++;}
			else{ $golf_name_next=$gname[0]; $j=1;}
		  ?>
		  <tr>
            <td height="25" align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;color:#FFF;font-weight:bold" rowspan="2">제<?=$i?>일</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$city?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$car2?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';">


			<?
			if(!$plan_text[$i]){
				$contents[$i] = "조식 후 골프장 이동 00분 소요 \n";
				$contents[$i] .= "[$golf_name_next 00홀 라운드] \n";
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
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">
			<?=$meal?>

			</td>
          </tr>
		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';">호텔 : <?=$hotel_name?> 또는 동급</div></td>
          </tr>
		  <?}?>



		  <tr>
            <td height="25" align="center" bgcolor="#948A54" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;color:#FFF;font-weight:bold" rowspan="3">제<?=$night?>일</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$city?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$car2?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';">

			<?

			if($gname[$j]){ $golf_name_next = $gname[$j];}
			else{ $golf_name_next=$gname[0]; }
			?>


			<?
			if(!$plan_text[$i]){
				$contents[$i] = "조식 후 체크아웃 or 골프장으로 이동 \n";
				$contents[$i] .= "[$golf_name_next 00홀 라운드] \n";

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
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="3">

			조:호텔식<br/>중:불포함

			</td>
          </tr>
		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$car?><div><?=$r_air_no?></div></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$r_time_s?></td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';"><?=$airport_out?>으로 이동<br/><?=$airport_out?> 출발</td>
          </tr>



		  <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$airport_place?></td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px">&nbsp;</td>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding:5px"><?=$r_time_e?></td>
            <td align="left"  bgcolor="#ffffff" style="font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc;padding-left:5px;font-size:10pt;font-family:'맑은고딕','돋움';line-height:130%;font-family:'맑은고딕','돋움';">
				<?=$airport_in?>도착
			</td>

          </tr>


        </table>