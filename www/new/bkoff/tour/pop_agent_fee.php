<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour_fee_list";
$MENU = "tour";
$TITLE = "수수료설정";
if($mode=="copy") $TITLE .= " (Copy mode : 내용을 수정하여 저장하시면 새로 등록됩니다.)";

#### operation

if ($mode=="save"){

		$sql = "select * from ez_tour_fee_list where tid=$tid and period_s='$period_s' and period_e='$period_e' and id_no<>'$id_no'";
		list($rows) = $dbo->query($sql);
		if($rows){
			error("이미 설정된 기간입니다.");
			exit;
		}

		if(!$id_no) $code=getUniqNo();

		$sqlInsert="
		   insert into ez_tour_fee_list (
			  tid,
			  code,
			  period_s,
			  period_e
		  ) values (
			  '$tid',
			  '$code',
			  '$period_s',
			  '$period_e'
		)";


		$sqlModify="
		   update ez_tour_fee_list set
			  period_s = '$period_s',
			  period_e = '$period_e'
		   where id_no='$id_no'
		";

		if($id_no){
			$sql = $sqlModify;
			$url = "?tid=$tid&days=$days";
		}else{
			$sql = $sqlInsert;
			$url = "?tid=$tid&days=$days";
		}

		if($dbo->query($sql)){

			for($i=0; $i<count($cp_group);$i++){

				$id_no = $id_nos[$i];

				$sqlInsert2="
				   insert into ez_tour_fee (
					  tid,
					  code,
					  period_s,
					  period_e,
					  cp_group,
					  cash,
					  adult,
					  child,
					  rate
				  ) values (
					  '$tid',
					  '$code',
					  '$period_s',
					  '$period_e',
					  '$cp_group[$i]',
					  '$cash[$i]',
					  '$adult[$i]',
					  '$child[$i]',
					  '$rate[$i]'
				)";


				$sqlModify2="
				   update ez_tour_fee set
					  tid = '$tid',
					  code = '$code',
					  period_s = '$period_s',
					  period_e = '$period_e',
					  cp_group = '$cp_group[$i]',
					  cash = '$cash[$i]',
					  adult = '$adult[$i]',
					  child = '$child[$i]',
					  rate = '$rate[$i]'
				   where id_no='$id_no'
				";

				$sql2 = ($id_no)? $sqlModify2: $sqlInsert2;
				$dbo->query($sql2);
			}
			//redirect2($url);
			echo "<script>parent.location.href='$url'</script>";
		}else{
			checkVar(mysql_error(),$sql);
		}
		exit;

}elseif ($mode=="drop"){

	$sql = "delete from $table where code = $code";
	$dbo->query($sql);

	redirect2("?tid=$tid&days=$days");exit;

}else{
	$sql = "select * from $table where code=$code";
	$dbo->query($sql);
	$rs= $dbo->next_record();

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
	location.href="?tid=<?=$tid?>&days=<?=$days?>";
}

function drop(code){
	if(confirm('삭제하시겠습니까?')){
		location.href="?mode=drop&tid=<?=$tid?>&days=<?=$days?>&code="+code;
	}
}


function drop_top(code){
	if(confirm('삭제하시겠습니까?')){
		location.href="?mode=drop_top&tid=<?=$tid?>&days=<?=$days?>&code="+code;
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
		<form name="fmData"  method="post" enctype="multipart/form-data" target="actarea2">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value="<?if($mode!='copy') echo $rs[id_no]?>">
		<input type="hidden" name="tid" value='<?=$tid?>'>
		<input type="hidden" name="days" value='<?=$days?>'>

		<tr><td colspan="10" class="tblLine"></td></tr>
		<tr>
			<td colspan="10">
			<div><span class="subject w10">기간</span>
			<input type="text" name="period_s" id="period_s" value="<?if($mode!='copy') echo $rs2[period_s]?>" size="10" maxlength="10" class="box dateinput readonly"  readonly='readonly'  /> ~
			<input type="text" name="period_e" id="period_e" value="<?if($mode!='copy') echo $rs2[period_e]?>" size="10" maxlength="10" class="box dateinput readonly"  readonly='readonly'  />
			* 기간을 입력하지 않으면 기본 가격이 됩니다.
			</div>
		</tr>
		<tr><td colspan="10"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr>
          <td class="subject c">그룹</td>
          <td class="subject c">구분</td>
          <td class="subject c">성인</td>
          <td class="subject c">소인</td>
          <td class="subject c">단위</td>
        </tr>
		<tr><td colspan="10" class="tblLine"></td></tr>

		<?
		$CP_GROUP2 .=$CP_GROUP.",".$CP_GROUP;
		$arr=explode(",",$CP_GROUP2);
		$sql2 = "select * from ez_tour_fee where code=$code order by period_s asc,period_e asc";
		list($rows2) = $dbo2->query($sql2);

		$r = ($rows2)?$rows2:count($arr);
		for($i=0;$i<$r;$i++){
			$rs2=$dbo2->next_record();
		?>
		<input type="hidden" name="id_nos[]" value="<?if($mode!='copy') echo $rs2[id_no]?>" />
	    <tr align="center">
		  <td><input type="text" name="cp_group[]" value="<?=$arr[$i]?>" size="1" maxlength="1" class="box c input_readonly" readonly/>그룹</td>
		  <td><input type="text" name="cash[]" value="<?=($i<3)?"현금":"카드"?>" size="4" maxlength="4" class="box c input_readonly" readonly/> (<?=($i<3)?"접수중,입금요청,입금완료":"입금완료"?>)</td>
          <td><input type="text" name="adult[]" id="cash1_1" value="<?=$rs2[adult]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="child[]" id="cash1_2" value="<?=$rs2[child]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><select name="rate[]"><?=option_str("원,%","0,1",$rs[rate])?></select></td>
        </tr>
		<?if($i==2||$i==5){?><tr><td colspan="10" class="tblLine"></td></tr><?}?>
		<tr><td colspan="10" class="tblLine"></td></tr>
		<?}?>

		<tr>
		  <td colspan=10>
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td width="74%" align="left" valign="top" style="padding-left:18px"></td>
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
	$sql = "select * from ez_tour_fee_list where tid='$tid' group by tid,period_s,period_e order by period_s asc, period_e asc";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
	?>

	<div class='tbl_title'>
		<img src="/images/view/ic_stitle.gif" alt=""/><?=$rs[period_s]?><?=($rs[period_e])?"~":"기본"?><?=$rs[period_e]?>
	</div>

	<div class="tour_table">
	<table class="tbl_table">
	<tr>
		<th colspan="2">구분</th>
		<th>성인</th>
		<th>소인</th>
		<th width="80">수정</th>
	</tr>

	<?
	$sql2 = "select * from ez_tour_fee where tid='$tid' and period_s='$rs[period_s]' and period_e='$rs[period_e]' order by id_no asc";
	$dbo2->query($sql2);
	unset($rows);
	while($rs2=$dbo2->next_record()){
		$rows[$rs2[cash]]++;
	}

	$dbo2->query($sql2);
	$j=0;
	while($rs2=$dbo2->next_record()){
	?>
	<tr>
	    <?if($rs2[cash]){?>
			<?if($prev!=$rs2[cash] || !$j){?><td class="c" rowspan="<?=$rows["$rs2[cash]"]?>" width="15%"><?=$rs2[cash]?></td>
			<?}?>
			<td class="c"><?=$rs2[cp_group]?></td>
		<?}else{?>
			<td class="c" colspan="2"><?=$rs2[cp_group]?></td>
		<?}?>

		<td class="c"><?=$rs2[adult]?> <?=(!$rs2[rate])?"원":"%"?></td>
		<td class="c"><?=$rs2[child]?> <?=(!$rs2[rate])?"원":"%"?></td>
		<?if(!$j){?>
			<td class="c" rowspan="6">
			<span class="btn_pack small bold"><a href="?tid=<?=$tid?>&code=<?=$rs2[code]?>&days=<?=$days?>"> 수정 </a></span><br>
			<span class="btn_pack small bold"><a href="?mode=copy&tid=<?=$tid?>&code=<?=$rs2[code]?>&days=<?=$days?>"> 복사 </a></span><br>
			<span class="btn_pack small bold"><a href="javascript:drop('<?=$rs2[code]?>')"> 삭제 </a></span>
			</td>
		<?}?>
		</td>
	</tr>
	<?
		$prev = $rs2[cash];
		$j++;
	}?>
	</table>
	</div>

	<?
	}
	?>
	<!--//결과-->


	<div style="height:50px"></div>
	<iframe name="actarea2" style="display:none;"></iframe>


	<!--내용이 들어가는 곳 끝-->

</body>
</html>