<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_ticket_setting";
$MENU = "tour";
$TITLE = "관광입장권 전체 금액 설정";
if($mode=="copy") $TITLE .= " (Copy mode : 내용을 수정하여 저장하시면 새로 등록됩니다.)";

#### operation

if ($mode=="save"){

		for($i=0; $i <count($week);$i++){
		  $weeks .="," . $week[$i];
		}
		$week = substr($weeks,1);

		$sqlInsert="
		   insert into $table (
			  code,
			  week,
			  period_s,
			  period_e,
			  price_origin_adult,
			  price_origin_youth,
			  price_origin_child,
			  price_adult,
			  price_youth,
			  price_child
		  ) values (
			  '$code',
			  '$week',
			  '$period_s',
			  '$period_e',
			  '$price_origin_adult',
			  '$price_origin_youth',
			  '$price_origin_child',
			  '$price_adult',
			  '$price_youth',
			  '$price_child'
		)";


		$sqlModify="
		   update $table set
			  code = '$code',
			  week = '$week',
			  period_s = '$period_s',
			  period_e = '$period_e',
			  price_origin_adult = '$price_origin_adult',
			  price_origin_youth = '$price_origin_youth',
			  price_origin_child = '$price_origin_child',
			  price_adult = '$price_adult',
			  price_youth = '$price_youth',
			  price_child = '$price_child'
		   where id_no='$id_no'
		";

		if($id_no){
			$sql = $sqlModify;
			$url = "?tid=$tid&code=$code";
		}else{
			$sql = $sqlInsert;
			$url = "?tid=$tid&code=$code";
		}

		if($dbo->query($sql)){
			redirect2($url);
		}else{
			checkVar(mysql_error(),$sql);
		}
		exit;

}elseif ($mode=="drop"){

	$sql = "delete from $table where id_no = $id_no";
	$dbo->query($sql);

	redirect2("?tid=$tid&code=$code");exit;


}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();

}

if($code){
	$sql2 = "select * from ez_ticket where code=$code";
	$dbo2->query($sql2);
	$rs2= $dbo2->next_record();
}
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
	if(check_blank(fm.price_adult,'성인 가격을',0)=='wrong'){return }
	if(check_blank(fm.price_youth,'청소년 가격을',0)=='wrong'){return }
	if(check_blank(fm.price_child,'소인 가격을',0)=='wrong'){return }
	fm.submit();
}

function cancel(){
	location.href="?code=<?=$code?>";
}

function drop(id_no){
	if(confirm('삭제하시겠습니까?')){
		location.href="?mode=drop&code=<?=$code?>&id_no="+id_no;
	}
}
//-->
</script>


	<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
	  <tr>
		<td class="title_con" style="padding-left:20px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?> - <?=($rs2[subject])? "'".$rs2[subject]."'에 적용" : ""?></td>
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
		<input type="hidden" name="code" value="<?=$rs2[code]?>">

		<tr><td colspan="10"  bgcolor='#5E90AE' height=2></td></tr>
		<tr>
			<td colspan="10">
			<div><span class="subject w10">요일</span> : <?=checkbox($WEEKS,$WEEKS_VAL,$rs[week],'week')?> </div>
			<div><span class="subject w10">기간</span> : <?=html_input("period_s",10,10,'box dateinput readonly')?> ~ <?=html_input("period_e",10,10,'box dateinput readonly')?></div>
			<div><span class="subject w10">성인</span> : 원가격 : <?=html_input('price_origin_adult',10,10,'box numberic')?> - 실제가격 <?=html_input('price_adult',10,10,'box numberic')?></div>
			<div><span class="subject w10">청소년</span> : 원가격 : <?=html_input('price_origin_youth',10,10,'box numberic')?> - 실제가격 <?=html_input('price_youth',10,10,'box numberic')?></div>
			<div><span class="subject w10">소인</span> : 원가격 : <?=html_input('price_origin_child',10,10,'box numberic')?> - 실제가격 <?=html_input('price_child',10,10,'box numberic')?></div>
			</td>
		</tr>
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
$filter = ($code)?"code=$code ":"code=''";
$sql = "select * from $table where $filter order by period_s asc, period_e asc, week asc";
list($rows) = $dbo->query($sql);

if($rows){
?>

    <table border="0" cellspacing="0" cellpadding="3" width="95%" align="center" id="tbl_list">

        <tr><td colspan="9"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height="30" bgcolor="#F7F7F6">
			<td class="subject">기간</td>
			<td class="subject">요일</td>
			<td class="subject">성인</td>
			<td class="subject">청소년</td>
			<td class="subject">소인</td>
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
		  <?
		  $arr=explode(",",$rs[week]);
		  for($i=0; $i < count($arr);$i++){
			$comma = ($i<count($arr)-1)?",":"";
			echo str_replace("요일","",getWeekName($arr[$i])) . $comma;
		  }
		  ?>
		  </td>
          <td align="center"><?=number_format($rs[price_adult])?></td>
          <td align="center"><?=number_format($rs[price_youth])?></td>
          <td align="center"><?=number_format($rs[price_child])?></td>
          <td align="center">

			<span class="btn_pack small bold"><a href="?id_no=<?=$rs[id_no]?>&code=<?=$code?>"> 수정 </a></span>&nbsp;
			<span class="btn_pack small bold"><a href="?mode=copy&id_no=<?=$rs[id_no]?>&code=<?=$code?>"> 복사 </a></span>&nbsp;
			<span class="btn_pack small bold"><a href="javascript:drop('<?=$rs[id_no]?>')"> 삭제 </a></span>

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