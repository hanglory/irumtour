<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour_price_list";
$MENU = "tour";
$TITLE = "가격설정";
if($mode=="copy") $TITLE .= " (Copy mode : 내용을 수정하여 저장하시면 새로 등록됩니다.)";

#### operation


if ($mode=="save"){

		for($i=0; $i<count($station);$i++){
			$stations .=","	 .$station[$i];
		}
		$station = substr($stations,1);

		for($i=0; $i<count($week);$i++){
			$weeks .=","	 .$week[$i];
		}
		$week = substr($weeks,1);
		if(!$id_no) $code=getUniqNo();

		If($period_s && !$period_e) $period_e= $period_s;
		If($period_e && !$period_s) $period_s= $period_e;


		If(!$id_no){
			 $sql = "select * from $table where tid=$tid and period_s='$period_s' and period_e='$period_e' and weeks='$weeks' and station='$station'";
			 list($rows)=$dbo->query($sql);
			 If($rows){
				Error("이미 같은 금액정보가 저장되어 있습니다.\\n\\n정차역,요일,기간 등이 같은 데이터가 있습니다.");exit;
			 }
		}

	   $seq_price=0;

		$sqlInsert="
		   insert into $table (
			  tid,
			  code,
			  station,
			  week,
			  period_s,
			  period_e,
			  room_name1,
			  room_name2,
			  room_name3,
			  room_name4,
			  room_name5,
			  room1_bit,
			  room2_bit,
			  room3_bit,
			  room4_bit,
			  room5_bit,
			  room6_bit,
			  room7_bit,
			  room8_bit,
			  room9_bit,
			  room10_bit,
			  period_msg,
			  period_color,
			  seq_price
		  ) values (
			  '$tid',
			  '$code',
			  '$station',
			  '$week',
			  '$period_s',
			  '$period_e',
			  trim('$room_name1'),
			  trim('$room_name2'),
			  trim('$room_name3'),
			  trim('$room_name4'),
			  trim('$room_name5'),
			  '$room1_bit',
			  '$room2_bit',
			  '$room3_bit',
			  '$room4_bit',
			  '$room5_bit',
			  '$room6_bit',
			  '$room7_bit',
			  '$room8_bit',
			  '$room9_bit',
			  '$room10_bit',
			  '$period_msg',
			  '$period_color',
			  '$seq_price'
		)";

		$sqlModify="
		   update $table set
			  tid = '$tid',
			  station = '$station',
			  week = '$week',
			  period_s = '$period_s',
			  period_e = '$period_e',
			  room_name1 = trim('$room_name1'),
			  room_name2 = trim('$room_name2'),
			  room_name3 = trim('$room_name3'),
			  room_name4 = trim('$room_name4'),
			  room_name5 = trim('$room_name5'),
			  room1_bit = '$room1_bit',
			  room2_bit = '$room2_bit',
			  room3_bit = '$room3_bit',
			  room4_bit = '$room4_bit',
			  room5_bit = '$room5_bit',
			  room6_bit = '$room6_bit',
			  room7_bit = '$room7_bit',
			  room8_bit = '$room8_bit',
			  room9_bit = '$room9_bit',
			  room10_bit = '$room10_bit',
			  period_msg = '$period_msg',
			  period_color = '$period_color'
		   where id_no='$id_no'
		";

		if($id_no){
			$sql = $sqlModify;
			$url = "pop_price.php?tid=$tid&days=$days&bstations=$bstations";
		}else{
			$sql = $sqlInsert;
			$url = "pop_price.php?tid=$tid&days=$days&bstations=$bstations";
		}

		if($dbo->query($sql)){

			$j=3;
			for($i=0; $i<count($name);$i++){

				$id_no = $id_nos[$i];

				if($name[$i]=="대인") $seq=1;
				elseif($name[$i]=="소인") $seq=2;
				else{ $seq = $j; $j++;}

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

				$sqlInsert2="
				   insert into ez_tour_price (
					  tid,
					  code,
					  station,
					  week,
					  period_s,
					  period_e,
					  name,
					  room1,
					  room2,
					  room3,
					  room4,
					  room5,
					  room6,
					  room7,
					  room8,
					  room9,
					  room10,
					  seq
				  ) values (
					  '$tid',
					  '$code',
					  '$station',
					  '$week',
					  '$period_s',
					  '$period_e',
					  '$name[$i]',
					  '$room1[$i]',
					  '$room2[$i]',
					  '$room3[$i]',
					  '$room4[$i]',
					  '$room5[$i]',
					  '$room6[$i]',
					  '$room7[$i]',
					  '$room8[$i]',
					  '$room9[$i]',
					  '$room10[$i]',
					  '$seq'
				)";


				$sqlModify2="
				   update ez_tour_price set
					  tid = '$tid',
					  station = '$station',
					  week = '$week',
					  period_s = '$period_s',
					  period_e = '$period_e',
					  name = '$name[$i]',
					  room1 = '$room1[$i]',
					  room2 = '$room2[$i]',
					  room3 = '$room3[$i]',
					  room4 = '$room4[$i]',
					  room5 = '$room5[$i]',
					  room6 = '$room6[$i]',
					  room7 = '$room7[$i]',
					  room8 = '$room8[$i]',
					  room9 = '$room9[$i]',
					  room10 = '$room10[$i]',
					  seq = '$seq'
				   where id_no='$id_no'
				";

				$sql2 = ($id_no)? $sqlModify2: $sqlInsert2;
				if($name[$i]) $dbo->query($sql2);
			}

			redirect2($url);
		}else{
			checkVar(mysql_error(),$sql);
		}
		exit;

}elseif ($mode=="drop"){

	$sql = "delete from $table where code = $code";
	$dbo->query($sql);

	redirect2("?tid=$tid&days=$days&bstations=$bstations");exit;


}elseif ($mode=="seq_price"){//순서변경

	$sql = "update $table set seq_price=$seq where tid=$tid and period_s='$period_s' and period_e='$period_e' and station='$station' ";
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

function reset_seq(station,period_s,period_e,seq){
	var url = "";
	url += "pop_price.php?mode=seq_price";
	url += "&tid=<?=$tid?>";
	url += "&period_s="+period_s;
	url += "&period_e="+period_e;
	url += "&seq="+seq;
	url += "&station="+station;
	url += "&days=<?=$days?>";
	url += "&bstations=<?=$bstations?>";

	location.href=url;
}


$(function(){
	$('#price_table').load("../../include_price_table.php", {
		'edit_bit': 1,
		'admin': 1,
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

    <table border="0" cellspacing="1" cellpadding="3" width="95%" align="center">
		<form name="fmData"  method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value="<?if($mode!='copy') echo $rs[id_no]?>">
		<input type="hidden" name="tid" value='<?=$tid?>'>
		<input type="hidden" name="days" value='<?=$days?>'>
		<input type="hidden" name="bstations" value='<?=$bstations?>'>

		<tr><td colspan="11" class="tblLine"></td></tr>
		<tr>
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
		<tr><td colspan="11"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr>
          <td class="subject c w10">구분</td>
		  <?if($days!="당일" && $days!="무박"){?>
          <td class="subject c w10"><label><input type="checkbox" name="room2_bit" value="1" <?=($rs[room2_bit])?"checked='checked'":""?>>2인1실</label></td>
          <td class="subject c w10"><label><input type="checkbox" name="room3_bit" value="1" <?=($rs[room3_bit])?"checked='checked'":""?>>3인1실</label></td>
          <td class="subject c w10"><label><input type="checkbox" name="room4_bit" value="1" <?=($rs[room4_bit])?"checked='checked'":""?>>4인1실</label></td>
          <td class="subject c w10"><label><input type="checkbox" name="room5_bit" value="1" <?=($rs[room5_bit])?"checked='checked'":""?>>5인1실</label></td>
          <td class="subject c w10"><label><input type="checkbox" name="room1_bit" value="1" <?=($rs[room1_bit])?"checked='checked'":""?>>1인1실</label></td>
          <td class="subject c w10"><label><input type="checkbox" name="room6_bit" value="1" <?=($rs[room6_bit])?"checked='checked'":""?>><?=html_input("room_name1",5,10)?></label></td>
          <td class="subject c w10"><label><input type="checkbox" name="room7_bit" value="1" <?=($rs[room7_bit])?"checked='checked'":""?>><?=html_input("room_name2",5,10)?></label></td>
          <td class="subject c w10"><label><input type="checkbox" name="room8_bit" value="1" <?=($rs[room8_bit])?"checked='checked'":""?>><?=html_input("room_name3",5,10)?></label></td>
          <td class="subject c w10"><label><input type="checkbox" name="room9_bit" value="1" <?=($rs[room9_bit])?"checked='checked'":""?>><?=html_input("room_name4",5,10)?></label></td>
          <td class="subject c w10"><label><input type="checkbox" name="room10_bit" value="1" <?=($rs[room10_bit])?"checked='checked'":""?>><?=html_input("room_name5",5,10)?></label></td>
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
          <td><input type="text" name="room2[]" value="<?=$rs2[room2]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="room3[]" value="<?=$rs2[room3]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="room4[]" value="<?=$rs2[room4]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="room5[]" value="<?=$rs2[room5]?>" size="8" maxlength="10" class="box numberic"/></td>
		  <?}?>
          <td><input type="text" name="room1[]" value="<?=$rs2[room1]?>" size="8" maxlength="10" class="box numberic"/></td>
		  <?if($days!="당일" && $days!="무박"){?>
          <td><input type="text" name="room6[]" value="<?=$rs2[room6]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="room7[]" value="<?=$rs2[room7]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="room8[]" value="<?=$rs2[room8]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="room9[]" value="<?=$rs2[room9]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="room10[]" value="<?=$rs2[room10]?>" size="8" maxlength="10" class="box numberic"/></td>
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

	<div style="padding:10px 0 50px 0" id="price_table"></div>



	<!--내용이 들어가는 곳 끝-->

</body>
</html>