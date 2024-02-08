<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour_price_list";
$MENU = "tour";
$TITLE = "가격설정 (현지여행사)";
if($mode=="copy") $TITLE .= " (Copy mode : 내용을 수정하여 저장하시면 새로 등록됩니다.)";

#### operation

if ($mode=="save"){

		$j=3;
		for($i=0; $i<count($name);$i++){

			$id_no = $id_nos[$i];

			$room1[$i] = ceil(str_replace(",","",$room1[$i]));
			$room2[$i] = ceil(str_replace(",","",$room2[$i]));
			$room3[$i] = ceil(str_replace(",","",$room3[$i]));
			$room4[$i] = ceil(str_replace(",","",$room4[$i]));
			$room5[$i] = ceil(str_replace(",","",$room5[$i]));
			$room6[$i] = ceil(str_replace(",","",$room6[$i]));
			$room7[$i] = ceil(str_replace(",","",$room7[$i]));
			$room8[$i] = ceil(str_replace(",","",$room8[$i]));
			$room9[$i] = ceil(str_replace(",","",$room9[$i]));
			$room10[$i] = ceil(str_replace(",","",$room10[$i]));

			$sql="
			   update ez_tour_price set
				  partner_room1 = '$room1[$i]',
				  partner_room2 = '$room2[$i]',
				  partner_room3 = '$room3[$i]',
				  partner_room4 = '$room4[$i]',
				  partner_room5 = '$room5[$i]',
				  partner_room6 = '$room6[$i]',
				  partner_room7 = '$room7[$i]',
				  partner_room8 = '$room8[$i]',
				  partner_room9 = '$room9[$i]',
				  partner_room10 = '$room10[$i]'
			   where id_no=$id_no
			";

			if($id_no) $dbo->query($sql);
			//if($id_no) checkVar("",$sql);
		}

	    $url = "pop_price_partner.php?tid=$tid&days=$days&bstations=$bstations";
		redirect2($url);
		exit;

}elseif ($mode=="drop"){

	$sql = "delete from $table where code = $code";
	$dbo->query($sql);

	redirect2("?tid=$tid&days=$days&bstations=$bstations");exit;


}else{
	$sql = "select * from $table where code=$code";
	$dbo->query($sql);
	$rs= $dbo->next_record();

}
if(!$rs[id_no]){
	$rs[room1_bit]=1;
	$rs[room2_bit]=1;
	$rs[room3_bit]=1;
	$rs[room4_bit]=1;
}
//-------------------------------------------------------------------------------
?>
<link rel="stylesheet" type="text/css" href="../../css/style.css" /></link>
<?include("../top_min.html");?>
<script type="text/javascript" src="/cheditor/cheditor.js"></script>
<style type="text/css">
.w10{width:10%}
.tbl_title{font-weight:bold;padding:20px 0 10px 30px;}
.tour_table{padding-left:30px;}
</style>
<script language="JavaScript">
<!--
function chk_form(){
	var fm = document.fmData;
	fm.submit();
}

function cancel(){
	if(confirm('취소하시겠습니까?')){
		location.href="?tid=<?=$tid?>&days=<?=$days?>&bstations=<?=$bstations?>";
	}
}

function drop(code){
	if(confirm('삭제하시겠습니까?')){
		location.href="?mode=drop&tid=<?=$tid?>&days=<?=$days?>&bstations=<?=$bstations?>&code="+code;
	}
}

function drop_top(code){
	if(confirm('삭제하시겠습니까?')){
		location.href="?mode=drop_top&tid=<?=$tid?>&days=<?=$days?>&bstations=<?=$bstations?>&code="+code;
	}
}

$(function(){
	$('#price_table').load("../../include_price_table.php", {
		'edit_bit': 1,
		'partner_mode': 1,
		'tid': '<?=$tid?>',
		'days': '<?=$days?>',
		'bstations': '<?=$bstations?>'
	});

	$("#period_color").change(function(){
		$(this).css("color",$(this).val());
	});
});
//-->
</script>


	<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
	  <tr>
		<td class="title_con" style="padding-left:20px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
	  </tr>
	  <tr>
		<td> </td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	</table>

	<!--내용이 들어가는 곳 시작-->

	<br>

	<?If($rs[id_no]){?>
		<div style="padding:3px;margin-left:23px;font-weight:bold">
			<?=($rs[station])? $rs[station]. " | ":""?>  <?=($rs[station])?"출발":"기본 가격"?>  <?=($rs[period_s])?" | ".$rs[period_s]:""?><?=($rs[period_e])?"~":""?><?=$rs[period_e]?>  <?=($rs[week])?"/":""?>
			<?
			$arr =explode(",",$rs[week]);
			for($l=0; $l <count($arr);$l++){
			$comma = ($l!=count($arr)-1)?",":"";
			echo "<span>" . str_replace("요일","",getWeekName($arr[$l])) . $comma . "</span>";}
			?>
		</div>

		<table border="0" cellspacing="1" cellpadding="3" width="95%" align="center">
			<form name="fmData"  method="post" enctype="multipart/form-data">
			<input type="hidden" name="mode" value='save'>
			<input type="hidden" name="id_no" value="<?if($mode!='copy') echo $rs[id_no]?>">
			<input type="hidden" name="tid" value='<?=$tid?>'>
			<input type="hidden" name="days" value='<?=$days?>'>
			<input type="hidden" name="bstations" value='<?=$bstations?>'>


			<tr><td colspan="11" class="tblLine"></td></tr>
			<!-- <tr>
				<td colspan="11">
				<?If($bstations){?>
				<div><span class="subject w10">탑승지</span> <span style="width:680px"><?=checkbox($bstations,$bstations,$rs[station],'station')?></span></div>
				<?}?>
				<div><span class="subject w10">요일</span> <?=checkbox($WEEKS,$WEEKS_VAL,$rs[week],'week')?> </div>
				<div><span class="subject w10">기간</span> <?=html_input("period_s",10,10,'box dateinput readonly')?> ~ <?=html_input("period_e",10,10,'box dateinput readonly')?>

				<strong>비고</strong> : <?=html_input("period_msg",30,30)?>
				<strong>색상</strong> : <?=html_input("period_color",7,7,'box')?>

				<?=colorpicker('period_color')?>

				</div>
				</td>
			</tr>
			<tr><td colspan="11"  bgcolor='#5E90AE' height=2></td></tr> -->
			<tr>
			  <td class="subject c w10">구분</td>
			  <?if($days!="당일" && $days!="무박"){?>
			  <?If($rs[room2_bit]){?><td class="subject c w10">2인1실</td><?}?>
			  <?If($rs[room3_bit]){?><td class="subject c w10">3인1실</td><?}?>
			  <?If($rs[room4_bit]){?><td class="subject c w10">4인1실</td><?}?>
			  <?If($rs[room5_bit]){?><td class="subject c w10">5인1실</td><?}?>
			  <?If($rs[room1_bit]){?><td class="subject c w10">1인1실</td><?}?>
			  <?If($rs[room6_bit]){?><td class="subject c w10"><?=$rs[room_name1]?></td><?}?>
			  <?If($rs[room7_bit]){?><td class="subject c w10"><?=$rs[room_name2]?></td><?}?>
			  <?If($rs[room8_bit]){?><td class="subject c w10"><?=$rs[room_name3]?></td><?}?>
			  <?If($rs[room9_bit]){?><td class="subject c w10"><?=$rs[room_name4]?></td><?}?>
			  <?If($rs[room10_bit]){?><td class="subject c w10"><?=$rs[room_name5]?></td><?}?>
			  <?}else{?>
			  <td class="subject c w10"><label><input type="hidden" name="room1_bit" value="1">가격</label></td>
			  <?}?>
			</tr>
			<tr><td colspan="11" class="tblLine"></td></tr>

			<?
			$sql2 = "select * from ez_tour_price where code=$code order by seq asc";
			list($rows2) = $dbo2->query($sql2);

			for($i=0;$i<5;$i++){
				$rs2=$dbo2->next_record();
				if(!$i) $rs2[name] = "대인";
				if($i==1) $rs2[name] = "소인";
				$readonly = ($rs2[name]=="대인" || $rs2[name]=="소인")?"readonly='readonly'":"";
			?>
			<tr align="center">
			  <td>
				<input type="hidden" name="id_nos[]" value="<?if($mode!='copy') echo $rs2[id_no]?>" />
				<input type="text" name="name[]" value="<?=$rs2[name]?>" size="10" maxlength="10" class="box" <?=$readonly?>/>
			  </td>
			  <?if($days!="당일" && $days!="무박"){?>
			  <?If($rs[room2_bit]){?><td><input type="text" name="room2[]" value="<?=$rs2[partner_room2]?>" size="8" maxlength="10" class="box numberic"/></td><?}?>
			  <?If($rs[room3_bit]){?><td><input type="text" name="room3[]" value="<?=$rs2[partner_room3]?>" size="8" maxlength="10" class="box numberic"/></td><?}?>
			  <?If($rs[room4_bit]){?><td><input type="text" name="room4[]" value="<?=$rs2[partner_room4]?>" size="8" maxlength="10" class="box numberic"/></td><?}?>
			  <?If($rs[room5_bit]){?><td><input type="text" name="room5[]" value="<?=$rs2[partner_room5]?>" size="8" maxlength="10" class="box numberic"/></td><?}?>
			  <?}?>
			  <?If($rs[room1_bit]){?><td><input type="text" name="room1[]" value="<?=$rs2[partner_room1]?>" size="8" maxlength="10" class="box numberic"/></td><?}?>
			  <?if($days!="당일" && $days!="무박"){?>
			  <?If($rs[room6_bit]){?><td><input type="text" name="room6[]" value="<?=$rs2[partner_room6]?>" size="8" maxlength="10" class="box numberic"/></td><?}?>
			  <?If($rs[room7_bit]){?><td><input type="text" name="room7[]" value="<?=$rs2[partner_room7]?>" size="8" maxlength="10" class="box numberic"/></td><?}?>
			  <?If($rs[room8_bit]){?><td><input type="text" name="room8[]" value="<?=$rs2[partner_room8]?>" size="8" maxlength="10" class="box numberic"/></td><?}?>
			  <?If($rs[room9_bit]){?><td><input type="text" name="room9[]" value="<?=$rs2[partner_room9]?>" size="8" maxlength="10" class="box numberic"/></td><?}?>
			  <?If($rs[room10_bit]){?><td><input type="text" name="room10[]" value="<?=$rs2[partner_room10]?>" size="8" maxlength="10" class="box numberic"/></td><?}?>
			  <?}?>
			</tr>
			<tr><td colspan="11" class="tblLine"></td></tr>
			<?}?>

			<tr>
			  <td colspan="11">
				  <br>
				  <!-- Button Begin---------------------------------------------->
				  <table border="0" width="210" cellspacing="0" cellpadding="0" align="right">
					<tr align="right">
						<td><span class="btn_pack medium bold"><a href="javascript:chk_form()"> 저장 </a></span></td>
						<td><span class="btn_pack medium bold"><a href="javascript:cancel()"> 취소 </a></span></td>
						<td><span class="btn_pack medium bold"><a href="javascript:self.close()"> 창닫기 </a></span></td>
					</tr>
				  </table>
				  <!-- Button End------------------------------------------------>
			  </td>
			</tr>

			<tr>
		  <td colspan="11" height=20>
		  </td>
			</tr>
		</form>
		</table>
	<?}else{?>

		<div class="msg" style="margin:25px">"<a href="pop_price.php?tid=<?=$tid?>&days=<?=$days?>&bstations=<?=$bstations?>">가격 설정</a>"에서 설정된 가격표에 대해서만 현지여행사 가격을 입력하실 수 있습니다.</div>

	<?}//If($rs[id_no])?>

	<div style="padding:10px 0 50px 0" id="price_table"></div>



	<!--내용이 들어가는 곳 끝-->

</body>
</html>