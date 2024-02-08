<?
include_once("../include/common_file.php");


####기초 정보
$filetid = substr(SELF,5,-4);
$table = "ez_rentcar_setting";
$MENU = "tour";
$TITLE = "렌트카 전체 금액 설정";
if($mode=="copy") $TITLE .= " (Copy mode : 내용을 수정하여 저장하시면 새로 등록됩니다.)";

#### operation

if ($mode=="save"){

		$reg_date = Date("Y/m/d");
		$reg_date2 = Date("H:i:s");

		$price= str_replace(",","",Trim($price));

		If(!$id_no){
			$sql  ="select * from $table where tid='$tid' and period_s='$period_s' and period_e ='$period_e'  ";
			list($rows)= $dbo->query($sql);
			If($rows){
				Error("이미 같은 기간에 대해 설정된 내용이 있습니다.");exit;
			}
		}

		$sqlInsert="
		   insert into $table (
			  tid,
			  period_s,
			  period_e,
			  price_origin,
			  price
		  ) values (
			  '$tid',
			  '$period_s',
			  '$period_e',
			  '$price_origin',
			  '$price'
		)";

		$sqlModify="
		   update $table set
			  period_s = '$period_s',
			  period_e = '$period_e',
			  price = '$price',
			  price_origin = '$price_origin'
		   where id_no='$id_no'
		";

		if($id_no){
			$sql = $sqlModify;
			$url = "?tid=$tid&tid=$tid";
		}else{
			$sql = $sqlInsert;
			$url = "?tid=$tid&tid=$tid";
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

	redirect2("?tid=$tid&tid=$tid");exit;


}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();

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
	if(fm.period_e.value!="" && check_blank(fm.period_s,'기간을',0)=='wrong'){return}
	if(fm.period_s.value!="" && check_blank(fm.period_e,'기간을',0)=='wrong'){return}
	if(check_blank(fm.price,'시간당 가격을',0)=='wrong'){return}
	fm.submit();
}

function cancel(){
	location.href="?tid=<?=$tid?>";
}

function drop(id_no){
	if(confirm('삭제하시겠습니까?')){
		location.href="?mode=drop&tid=<?=$tid?>&id_no="+id_no;
	}
}
//-->
</script>


	<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
	  <tr>
		<td class="title_con" style="padding-left:20px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?> </td>
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
		<input type="hidden" name="tid" value="<?=$tid?>">

		<tr><td colspan="10"  bgcolor='#5E90AE' height=2></td></tr>
		<tr>
			<td colspan="10">
			<div><span class="subject w20">기간</span> : <?=html_input("period_s",10,10,'box dateinput readonly')?> ~ <?=html_input("period_e",10,10,'box dateinput readonly')?></div>
			<div><span class="subject w20">시간당 금액(할인 전)</span> : <?=html_input("price_origin",10,10,'box numberic')?>원 </div>
			<div><span class="subject w20">시간당 금액</span> : <?=html_input("price",10,10,'box numberic')?>원 </div>
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
$sql = "select * from $table where tid='$tid' order by period_s asc, period_e asc";
list($rows) = $dbo->query($sql);
if($rows){
?>

    <table border="0" cellspacing="0" cellpadding="3" width="95%" align="center" id="tbl_list">

        <tr><td colspan="9"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height="30" bgcolor="#F7F7F6">
			<td class="subject">기간</td>
			<td class="subject">시간 당 가격</td>
			<td class="subject" width="200">수정/삭제</td>
          </tr>
		<tr><td colspan="9"  bgcolor='#E1E1E1'></td></tr>
		<tr><td colspan="9"  bgcolor='#E1E1E1'></td></tr>
<?
while($rs=$dbo->next_record()){
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" class="lh">
          <td align="center"><?=$rs[period_s]?> <?=($rs[period_e])?"~":""?> <?=$rs[period_e]?></td>
          <td align="center"><?=number_format($rs[price])?></td>
          <td align="center">

			<span class="btn_pack small bold"><a href="?id_no=<?=$rs[id_no]?>&tid=<?=$tid?>"> 수정 </a></span>&nbsp;
			<span class="btn_pack small bold"><a href="?mode=copy&id_no=<?=$rs[id_no]?>&tid=<?=$tid?>"> 복사 </a></span>&nbsp;
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