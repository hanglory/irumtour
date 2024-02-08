<?
include_once("../include/common_file.php");
$TRAIN_TYPE .= ",대체열차,일반열차,일반대체열차";

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour_train_list";
$MENU = "tour";
$TITLE = "기차설정";
if($mode=="copy") $TITLE .= " (Copy mode : 내용을 수정하여 저장하시면 새로 등록됩니다.)";

#### operation

if ($mode=="save"){

		if(!$id_no) $code=getUniqNo();
		$train_go = Trim($train_go);
		$train_return = Trim($train_return);

		for($i=0; $i<count($station);$i++){
			$stations .=","	 .$station[$i];
		}
		$station = substr($stations,1);

		$sqlInsert="
		   insert into $table (
			  tid,
			  code,
			  train,
			  train_go,
			  train_return,
			  updown,
			  station
		  ) values (
			  '$tid',
			  '$code',
			  '$train',
			  '$train_go',
			  '$train_return',
			  '$updown',
			  '$station'
		)";

		$sqlModify="
		   update $table set
			  tid = '$tid',
			  code = '$code',
			  train = '$train',
			  train_go = '$train_go',
			  updown = '$updown',
			  station = '$station',
			  train_return = '$train_return'
		   where id_no='$id_no'
		";

		if($id_no){
			$sql = $sqlModify;
			$url = "?tid=$tid&days=$days&bstations=$bstations";
		}else{
			$sql = $sqlInsert;
			$url = "?tid=$tid&days=$days&bstations=$bstations";
		}

		if($dbo->query($sql)){

			for($i=0; $i<count($week_1);$i++){

				$id_no = $id_nos[$i];

				$week_1[$i] = ceil(str_replace(",","",$week_1[$i]));
				$week_2[$i] = ceil(str_replace(",","",$week_2[$i]));
				$week_3[$i] = ceil(str_replace(",","",$week_3[$i]));
				$week_4[$i] = ceil(str_replace(",","",$week_4[$i]));
				$week_5[$i] = ceil(str_replace(",","",$week_5[$i]));
				$week_6[$i] = ceil(str_replace(",","",$week_6[$i]));
				$week_7[$i] = ceil(str_replace(",","",$week_7[$i]));
				$week_8[$i] = ceil(str_replace(",","",$week_8[$i]));

				$week2_1[$i] = ceil(str_replace(",","",$week2_1[$i]));
				$week2_2[$i] = ceil(str_replace(",","",$week2_2[$i]));
				$week2_3[$i] = ceil(str_replace(",","",$week2_3[$i]));
				$week2_4[$i] = ceil(str_replace(",","",$week2_4[$i]));
				$week2_5[$i] = ceil(str_replace(",","",$week2_5[$i]));
				$week2_6[$i] = ceil(str_replace(",","",$week2_6[$i]));
				$week2_7[$i] = ceil(str_replace(",","",$week2_7[$i]));
				$week2_8[$i] = ceil(str_replace(",","",$week2_8[$i]));

				If($period_s[$i] && !$period_e[$i]) $period_e[$i]=$period_s[$i];
				If($period_e[$i] && !$period_s[$i]) $period_s[$i]=$period_e[$i];

				//중복 검사
				$sql2 = "select * from ez_tour_train where tid='$tid' and train='$train' and train_go='$train_go' and train_return ='$train_return' and updown='$updown' and period_s='$period_s[$i]' and period_e='$period_e[$i]' and station='$station' and id_no <>'$id_no'";
				list($rows2) = $dbo2->query($sql2);
			    //checkVar("",$sql2);exit;
				If($rows2){
					msggo("이미 같은 기간의 가격정보가 설정되어 있습니다.","pop_train.php?tid=$tid&days=$days&bstations=$bstations");
					exit;
				}


				$sqlInsert2="
				   insert into ez_tour_train (
					  tid,
					  code,
					  train,
					  train_go,
					  train_return,
					  updown,
					  period_s,
					  period_e,
					  week_1,
					  week_2,
					  week_3,
					  week_4,
					  week_5,
					  week_6,
					  week_7,
					  week_8,
					  week2_1,
					  week2_2,
					  week2_3,
					  week2_4,
					  week2_5,
					  week2_6,
					  week2_7,
					  week2_8,
					  station
				  ) values (
					  '$tid',
					  '$code',
					  '$train',
					  '$train_go',
					  '$train_return',
					  '$updown',
					  '$period_s[$i]',
					  '$period_e[$i]',
					  '$week_1[$i]',
					  '$week_2[$i]',
					  '$week_3[$i]',
					  '$week_4[$i]',
					  '$week_5[$i]',
					  '$week_6[$i]',
					  '$week_7[$i]',
					  '$week_8[$i]',
					  '$week2_1[$i]',
					  '$week2_2[$i]',
					  '$week2_3[$i]',
					  '$week2_4[$i]',
					  '$week2_5[$i]',
					  '$week2_6[$i]',
					  '$week2_7[$i]',
					  '$week2_8[$i]',
					  '$station'
				)";

				$sqlModify2="
				   update ez_tour_train set
					  tid = '$tid',
					  code = '$code',
					  train = '$train',
					  train_go = '$train_go',
					  train_return = '$train_return',
					  updown = '$updown',
					  period_s = '$period_s[$i]',
					  period_e = '$period_e[$i]',
					  week_1 = '$week_1[$i]',
					  week_2 = '$week_2[$i]',
					  week_3 = '$week_3[$i]',
					  week_4 = '$week_4[$i]',
					  week_5 = '$week_5[$i]',
					  week_6 = '$week_6[$i]',
					  week_7 = '$week_7[$i]',
					  week_8 = '$week_8[$i]',
					  week2_1 = '$week2_1[$i]',
					  week2_2 = '$week2_2[$i]',
					  week2_3 = '$week2_3[$i]',
					  week2_4 = '$week2_4[$i]',
					  week2_5 = '$week2_5[$i]',
					  week2_6 = '$week2_6[$i]',
					  week2_7 = '$week2_7[$i]',
					  week2_8 = '$week2_8[$i]',
					  station = '$station'
				   where id_no='$id_no'
				";

				$sql2 = ($id_no)? $sqlModify2: $sqlInsert2;
				if($week_1[$i]) $dbo->query($sql2);
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

}elseif ($mode=="drop2"){

	$sql = "delete from ez_tour_train where id_no = $id_no";
	$dbo->query($sql);

	$sql = "select * from $table where code = $code";
	list($rows) = $dbo->query($sql);
	if($rows){
		$sql = "delete from $table where code = $code";
		$dbo->query($sql);
	}

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

	if(fm.station_tmp.value==0){alert("탑승지를 선택해주세요");return}
	//if(fm.train_tmp.value==""){alert("기차를 선택해주세요");return}

	if(fm.updown_tmp.value==""){alert("상행 또는 하행을 선택해 주세요.");return}

	var tgo = (fm.train_go.value=="" || fm.train_go.value.substr(0,1)==" ")?0:1;
	var treturn = (fm.train_return.value=="" || fm.train_return.value.substr(0,1)==" ")?0:1;

	if(tgo==0 && treturn==0){alert('가는 편 또는 오는 편 기차번호를 입력하셔야 합니다.');fm.train_go.focus();return}

	if(document.getElementById('week1_1').value==""){alert('금액을 입력하세요');document.getElementById('week1_1').focus();return}
	if(document.getElementById('week1_2').value==""){alert('금액을 입력하세요');document.getElementById('week1_2').focus();return}
	if(document.getElementById('week1_3').value==""){alert('금액을 입력하세요');document.getElementById('week1_3').focus();return}
	if(document.getElementById('week1_4').value==""){alert('금액을 입력하세요');document.getElementById('week1_4').focus();return}
	if(document.getElementById('week1_5').value==""){alert('금액을 입력하세요');document.getElementById('week1_5').focus();return}
	if(document.getElementById('week1_6').value==""){alert('금액을 입력하세요');document.getElementById('week1_6').focus();return}
	if(document.getElementById('week1_7').value==""){alert('금액을 입력하세요');document.getElementById('week1_7').focus();return}
	//if(document.getElementById('week1_8').value==""){alert('금액을 입력하세요\n\n공휴일전일 가격이 없는 경우 평일 요금을 적용해 주세요.');document.getElementById('week1_8').focus();return}

	if(document.getElementById('week2_1').value==""){alert('금액을 입력하세요');document.getElementById('week2_1').focus();return}
	if(document.getElementById('week2_2').value==""){alert('금액을 입력하세요');document.getElementById('week2_2').focus();return}
	if(document.getElementById('week2_3').value==""){alert('금액을 입력하세요');document.getElementById('week2_3').focus();return}
	if(document.getElementById('week2_4').value==""){alert('금액을 입력하세요');document.getElementById('week2_4').focus();return}
	if(document.getElementById('week2_5').value==""){alert('금액을 입력하세요');document.getElementById('week2_5').focus();return}
	if(document.getElementById('week2_6').value==""){alert('금액을 입력하세요');document.getElementById('week2_6').focus();return}
	if(document.getElementById('week2_7').value==""){alert('금액을 입력하세요');document.getElementById('week2_7').focus();return}
	//if(document.getElementById('week2_8').value==""){alert('금액을 입력하세요\n\n공휴일전일 가격이 없는 경우 평일 요금을 적용해 주세요.');document.getElementById('week2_8').focus();return}
	fm.submit();
}

function cancel(){
	location.href="?tid=<?=$tid?>&days=<?=$days?>&bstations=<?=$bstations?>";
}

function drop(code){
	if(confirm('삭제하시겠습니까?')){
		location.href="?mode=drop&tid=<?=$tid?>&days=<?=$days?>&bstations=<?=$bstations?>&code="+code;
	}
}

function drop2(id_no,code){
	if(confirm('삭제하시겠습니까?')){
		location.href="?mode=drop2&tid=<?=$tid?>&days=<?=$days?>&bstations=<?=$bstations?>&id_no="+id_no+"&code="+code;
	}
}

function drop_top(code){
	if(confirm('삭제하시겠습니까?')){
		location.href="?mode=drop_top&tid=<?=$tid?>&days=<?=$days?>&bstations=<?=$bstations?>&code="+code;
	}
}
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

		<tr><td colspan="10" class="tblLine"></td></tr>
		<tr>
			<td colspan="10">
			<?If($bstations){?>
			<div><span class="subject w10">탑승지</span> : <span style="width:680px"><?=checkbox($bstations,$bstations,$rs[station],'station')?></span></div>
			<?}else{?>
			<div><span class="subject w10">탑승지</span> : <span style="width:680px">상품관리 상세페이지>탑승지(출발역)에 역이름을 입력하세요.</span></div>
			<?}?>
			<!-- <div><span class="subject w10">기차</span> : <?=radio($TRAIN_TYPE,$TRAIN_TYPE,$rs[train],'train')?></div> -->
			<div><span class="subject w10">상하행</span> : <?=radio("상행,하행","up,down",$rs[updown],'updown')?></div>
			<div><span class="subject w10">기차번호</span> : 가는편 <?=html_input("train_go",30,50)?> / 오는편 <?=html_input("train_return",30,50)?></div>
			</td>
		</tr>
		<tr><td colspan="10"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr>
          <td class="subject c">기간</td>
          <td class="subject c">구분</td>
          <td class="subject c">월요일</td>
          <td class="subject c">화요일</td>
          <td class="subject c">수요일</td>
          <td class="subject c">목요일</td>
          <td class="subject c">금요일</td>
          <td class="subject c">토요일</td>
          <td class="subject c">일요일</td>
          <td class="subject c">공휴일</td>
        </tr>
		<tr><td colspan="10" class="tblLine"></td></tr>

		<?
		$sql2 = "select * from ez_tour_train where code=$code order by period_s asc,period_e asc";
		list($rows2) = $dbo2->query($sql2);

		//If($REMOTE_ADDR=="1.215.151.146") checkVar("",$sql2);

		//$r = ($rows2)?$rows2+5:5;

		//for($i=0;$i<$r;$i++){
			$rs2=$dbo2->next_record();
		?>
	    <tr align="center">
          <td rowspan="2">
			<input type="hidden" name="id_nos[]" value="<?if($mode!='copy') echo $rs2[id_no]?>" />
			<input type="text" name="period_s[]" id="period_s<?=$i?>" value="<?if($mode!='copy') echo $rs2[period_s]?>" size="10" maxlength="10" class="box dateinput readonly"  readonly='readonly'  /> ~
			<input type="text" name="period_e[]" id="period_e<?=$i?>" value="<?if($mode!='copy') echo $rs2[period_e]?>" size="10" maxlength="10" class="box dateinput readonly"  readonly='readonly'  />
		  </td>
          <td>대인</td>
          <td><input type="text" name="week_1[]" id="week1_1" value="<?=$rs2[week_1]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week_2[]" id="week1_2" value="<?=$rs2[week_2]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week_3[]" id="week1_3" value="<?=$rs2[week_3]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week_4[]" id="week1_4" value="<?=$rs2[week_4]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week_5[]" id="week1_5" value="<?=$rs2[week_5]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week_6[]" id="week1_6" value="<?=$rs2[week_6]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week_7[]" id="week1_7" value="<?=$rs2[week_7]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week_8[]" id="week1_8" value="<?=$rs2[week_8]?>" size="8" maxlength="10" class="box numberic"/></td>

        </tr>

	    <tr align="center">
          <td>소인</td>
          <td><input type="text" name="week2_1[]" id="week2_1" value="<?=$rs2[week2_1]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week2_2[]" id="week2_2" value="<?=$rs2[week2_2]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week2_3[]" id="week2_3" value="<?=$rs2[week2_3]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week2_4[]" id="week2_4" value="<?=$rs2[week2_4]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week2_5[]" id="week2_5" value="<?=$rs2[week2_5]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week2_6[]" id="week2_6" value="<?=$rs2[week2_6]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week2_7[]" id="week2_7" value="<?=$rs2[week2_7]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week2_8[]" id="week2_8" value="<?=$rs2[week2_8]?>" size="8" maxlength="10" class="box numberic"/></td>

        </tr>
		<tr><td colspan="10" class="tblLine"></td></tr>
		<?//}?>

		<tr>
		  <td colspan=10>
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td width="74%" align="left" valign="top" style="padding-left:18px">* 기간을 입력하지 않으면 기본 가격이 됩니다.</td>
					<td><span class="btn_pack medium bold"><a href="javascript:chk_form()"> 저장 </a></span></td>
					<td><span class="btn_pack medium bold"><a href="javascript:cancel()"> 취소 </a></span></td>
					<td><span class="btn_pack medium bold"><a href="javascript:self.close()"> 창닫기 </a></span></td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>
		  </td>
        </tr>

        <tr>
	  <td colspan=10 height=20>
	  </td>
        </tr>
	</form>
	</table>


	<!--결과-->

	<?
	$sql = "select * from ez_tour_train_list where tid='$tid' group by tid,train,train_go,train_return,updown,station order by id_no asc";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
	?>

	<div class='tbl_title'>
		<img src="/images/view/ic_stitle.gif" alt=""/><?=$rs[station]?> / <?=($rs[updown]=="up")?"상행":"하행"?> / 가는편: <?=$rs[train_go]?>, 오는편: <?=$rs[train_return]?>
	</div>

	<div class="tour_table">
	<table class="tbl_table">
	<tr>
		<th>기간</th>
		<th>구분</th>
		<th>월요일</th>
		<th>화요일</th>
		<th>수요일</th>
		<th>목요일</th>
		<th>금요일</th>
		<th>토요일</th>
		<th>공휴일</th>
		<!-- <th>공휴일전일</th> -->
		<th width="60">수정</th>
	</tr>

	<?
	$sql2 = "select * from ez_tour_train where tid='$tid' and train='$rs[train]' and train_go='$rs[train_go]' and train_return='$rs[train_return]' and updown='$rs[updown]' and station = '$rs[station]' order by period_s asc, period_e asc";
	list($rows) = $dbo2->query($sql2);

	If(!$rows){
		$sql2 = "delete from ez_tour_train_list where tid='$tid' and train='$rs[train]' and train_go='$rs[train_go]' and train_return='$rs[train_return]' and updown='$rs[updown]' and station = '$rs[station]'";
		$dbo2->query($sql2);
	}

	//If($REMOTE_ADDR=="1.215.151.146") checkVar($rows,$sql2);
	$j=0;
	while($rs2=$dbo2->next_record()){
	?>
		<tr>
			<td class="c" rowspan="2"><?=(!$rs2[period_s])?"전체기간":""?><?=$rs2[period_s]?><br><?=($rs2[period_e])?"~":""?><?=$rs2[period_e]?> </td>
			<td class="c">대인</td>
			<td class="c"><?=number_format($rs2[week_1])?></td>
			<td class="c"><?=number_format($rs2[week_2])?></td>
			<td class="c"><?=number_format($rs2[week_3])?></td>
			<td class="c"><?=number_format($rs2[week_4])?></td>
			<td class="c"><?=number_format($rs2[week_5])?></td>
			<td class="c"><?=number_format($rs2[week_6])?></td>
			<td class="c"><?=number_format($rs2[week_7])?></td>
			<!-- <td class="c"><?=number_format($rs2[week_8])?></td> -->
			<td class="c" rowspan="2">
				<span class="btn_pack small bold"><a href="?tid=<?=$tid?>&code=<?=$rs2[code]?>&days=<?=$days?>&bstations=<?=$bstations?>"> 수정 </a></span><br>
				<span class="btn_pack small bold"><a href="?mode=copy&tid=<?=$tid?>&code=<?=$rs2[code]?>&days=<?=$days?>&bstations=<?=$bstations?>"> 복사 </a></span><br>
				<span class="btn_pack small bold"><a href="javascript:drop2('<?=$rs2[id_no]?>','<?=$rs2[code]?>')"> 삭제 </a></span>
			</td>
		</tr>
		<tr>
			<td class="c">소인</td>
			<td class="c"><?=number_format($rs2[week2_1])?></td>
			<td class="c"><?=number_format($rs2[week2_2])?></td>
			<td class="c"><?=number_format($rs2[week2_3])?></td>
			<td class="c"><?=number_format($rs2[week2_4])?></td>
			<td class="c"><?=number_format($rs2[week2_5])?></td>
			<td class="c"><?=number_format($rs2[week2_6])?></td>
			<td class="c"><?=number_format($rs2[week2_7])?></td>
			<!-- <td class="c"><?=number_format($rs2[week_8])?></td> -->
		</tr>
	<?
		$prev = $rs2[week];
		$j++;
	}
	?>
	</table>
	</div>

	<?
	}
	?>
	<!--//결과-->


	<div style="height:50px"></div>



	<!--내용이 들어가는 곳 끝-->

</body>
</html>