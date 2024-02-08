<?
include_once("../include/common_file.php");


####기초 정보
$filetid = substr(SELF,5,-4);
$table = "ez_hotel_room";
$MENU = "tour";
$TITLE = "호텔/펜션/콘도 객실/금액 설정";


if($mode=="copy") $TITLE .= " (Copy mode : 내용을 수정하여 저장하시면 새로 등록됩니다.)";

#### operation

if ($mode=="save"){

		if($sub_code){
			$sql  ="delete from ez_hotel_room where tid=$tid and sub_code=$sub_code";
			$dbo->query($sql);
		}

		$sub_code=getUniqNo();

		for($i=0; $i <count($subject);$i++){

			$week_1[$i] = price_format($week_1[$i]);
			$week_2[$i] = price_format($week_2[$i]);
			$week_3[$i] = price_format($week_3[$i]);
			$week_4[$i] = price_format($week_4[$i]);
			$week_5[$i] = price_format($week_5[$i]);
			$week_6[$i] = price_format($week_6[$i]);
			$week_7[$i] = price_format($week_7[$i]);
			$week_8[$i] = price_format($week_8[$i]);

			$sql="
			   insert into ez_hotel_room (
				  tid,
				  sub_code,
				  subject,
				  period_s,
				  period_e,
				  week_1,
				  week_2,
				  week_3,
				  week_4,
				  week_5,
				  week_6,
				  week_7,
				  week_8
			  ) values (
				  '$tid',
				  '$sub_code',
				  '$subject[$i]',
				  '$period_s',
				  '$period_e',
				  '$week_1[$i]',
				  '$week_2[$i]',
				  '$week_3[$i]',
				  '$week_4[$i]',
				  '$week_5[$i]',
				  '$week_6[$i]',
				  '$week_7[$i]',
				  '$week_8[$i]'
			)";

			$dbo->query($sql);

		}

		$url = "?tid=$tid&tid=$tid";

		//checkVar(mysql_error(),$sql);exit;
		msggo("저장하였습니다.",$url);
		exit;

}elseif ($mode=="drop"){

	$sql = "delete from $table where tid=$tid and sub_code = $sub_code";
	$dbo->query($sql);

	redirect2("?tid=$tid&tid=$tid");exit;


}else{
	$sql = "select * from ez_tour where tid=$tid";
	$dbo->query($sql);
	$rs= $dbo->next_record();


}

if(!$rs[period_s] && $period_s) $rs[period_s] = $period_s;
if(!$rs[period_e] && $period_e) $rs[period_e] = $period_e;
//-------------------------------------------------------------------------------
?>
<link rel="stylesheet" type="text/css" href="../../css/style.css" /></link>
<?include("../top_min.html");?>
<script type="text/javascript" src="/cheditor/cheditor.js"></script>
<style type="text/css">
.week{padding-right:5px;}
</style>
<script language="JavaScript">
<!--
function chk_form(){
	var fm = document.fmData;
	if(check_blank(fm.period_s,'기간을',0)=='wrong'){return}
	if(check_blank(fm.period_e,'기간을',0)=='wrong'){return}
	fm.submit();
}

function cancel(){
	location.href="?tid=<?=$tid?>";
}

function drop(sub_code){
	if(confirm('삭제하시겠습니까?')){
		location.href="?mode=drop&tid=<?=$tid?>&sub_code="+sub_code;
	}
}

<?
if(!$rs[hotel_size]){
?>
$(function(){
	alert("상품관리에서 \"객실명(평형및객실)\"을 먼저 입력해 주세요");
	self.close();
});
<?
}
?>

//-->
</script>


	<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
	  <tr>
		<td class="title_con" style="padding-left:20px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?> - <?=$rs[subject]?></td>
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
		<input type="hidden" name="tid" value="<?=$tid?>">
		<input type="hidden" name="copy" value="<?=$mode?>">
		<input type="hidden" name="sub_code" value="<?=($mode!='copy')?$sub_code:''?>">

		<tr><td colspan="10"  bgcolor='#5E90AE' height=2></td></tr>
		<tr>
			<td colspan="10">
			<div><span class="subject w10">기간</span> : <?=html_input("period_s",10,10,'box dateinput readonly')?> ~ <?=html_input("period_e",10,10,'box dateinput readonly')?> * 1박 기준 요금</div>
			</td>
		</tr>
		<tr><td colspan="10" class="tblLine"></td></tr>
	    <tr>
          <td class="subject c w10">객실명</td>
          <td class="subject c w10">월요일</td>
          <td class="subject c w10">화요일</td>
          <td class="subject c w10">수요일</td>
          <td class="subject c w10">목요일</td>
          <td class="subject c w10">금요일</td>
          <td class="subject c w10">토요일</td>
          <td class="subject c w10">공휴일</td>
          <td class="subject c w10">공휴일전일</td>
        </tr>
		<tr><td colspan="10" class="tblLine"></td></tr>
		<?
		$arr = explode(",",$rs[hotel_size]);

		for($i=0;$i<count($arr);$i++){

			$sql2 = "select * from ez_hotel_room where tid=$tid and sub_code=$sub_code and subject='$arr[$i]' ";
			$dbo2->query($sql2);
			$rs2=$dbo2->next_record();
			if($rs2[id_no]){
				$rs2[week_1] = number_format($rs2[week_1]);
				$rs2[week_2] = number_format($rs2[week_2]);
				$rs2[week_3] = number_format($rs2[week_3]);
				$rs2[week_4] = number_format($rs2[week_4]);
				$rs2[week_5] = number_format($rs2[week_5]);
				$rs2[week_6] = number_format($rs2[week_6]);
				$rs2[week_7] = number_format($rs2[week_7]);
				$rs2[week_8] = number_format($rs2[week_8]);
			}
		?>
	    <tr align="center">
          <td><input type="text" name="subject[]" value="<?=$arr[$i]?>" size="20" maxlength="45" class="box c" style="border:0;background-color:#fff;color:black"  /></td>
          <td><input type="text" name="week_1[]" value="<?=$rs2[week_1]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week_2[]" value="<?=$rs2[week_2]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week_3[]" value="<?=$rs2[week_3]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week_4[]" value="<?=$rs2[week_4]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week_5[]" value="<?=$rs2[week_5]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week_6[]" value="<?=$rs2[week_6]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week_7[]" value="<?=$rs2[week_7]?>" size="8" maxlength="10" class="box numberic"/></td>
          <td><input type="text" name="week_8[]" value="<?=$rs2[week_8]?>" size="8" maxlength="10" class="box numberic"/></td>
        </tr>
		<tr><td colspan="10" class="tblLine"></td></tr>
		<?}?>
		<tr><td colspan="10" class="tblLine"></td></tr>
		<tr>
		  <td colspan=10>
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
	  <td colspan=10 height=20>
	  </td>
        </tr>
	</form>
	</table>


	<br>
<?
$sql = "select * from $table where tid='$tid' group by period_s,period_e order by period_s asc, period_e asc";
list($rows) = $dbo->query($sql);

if($rows){
?>

    <table border="0" cellspacing="0" cellpadding="3" width="95%" align="center" id="tbl_list">

        <tr><td colspan="9"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height="30" bgcolor="#F7F7F6">
			<td class="subject">기간</td>
			<td class="subject" width="200">수정/삭제</td>
          </tr>
		<tr><td colspan="9"  bgcolor='#E1E1E1'></td></tr>
		<tr><td colspan="9"  bgcolor='#E1E1E1'></td></tr>
<?
while($rs=$dbo->next_record()){
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" class="lh">
          <td align="center"><?=$rs[period_s]?> <?=($rs[period_e])?"~":""?> <?=$rs[period_e]?></td>
          <td align="center">

			<span class="btn_pack small bold"><a href="?sub_code=<?=$rs[sub_code]?>&tid=<?=$tid?>&period_s=<?=$rs[period_s]?>&period_e=<?=$rs[period_e]?>"> 수정 </a></span>&nbsp;
			<span class="btn_pack small bold"><a href="?mode=copy&sub_code=<?=$rs[sub_code]?>&tid=<?=$tid?>"> 복사 </a></span>&nbsp;
			<span class="btn_pack small bold"><a href="javascript:drop('<?=$rs[sub_code]?>')"> 삭제 </a></span>

		  </td>
	    </tr>
		<tr><td colspan="9" class='bar'></td></tr>
<?
	$num--;
}
?>
		<tr><td colspan="9" height=1><?=$error[noData]?></td></tr>
        <tr><td colspan="9"  bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan="9"  bgcolor='#FFFFFF' height=10></td></tr>

	</table>
<?
}//if($rows)
?>

	<!--내용이 들어가는 곳 끝-->

</body>
</html>