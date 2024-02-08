<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "shop_email";
$MENU = "member";
$TITLE = "마일리지 지급";


#### mode
if($mode=="save"){

	$filter =($target)? " and ($target >= '$start_date' and $target <= '$end_date 23:59:59') " : "";
	if($TARGETS){
		$TARGETS = "'".str_replace(",","','",trim($TARGETS)) . "'";
		$filter = " and id in ($TARGETS)";
	}

	$sql = "select * from ez_member where id<>'' $filter";
	$dbo->query($sql);

	$i=1;
	$reg_date=date("Y/m/d");
	$reg_date2=date("H:i:s");
	$point= str_replace(",","",trim($point));
	while($rs=$dbo->next_record()){

		$sql2="
		   insert into ez_special_point (
			  id,
			  name,
			  subject,
			  point,
			  use_point,
			  reg_date,
			  reg_date2
		  ) values (
			  '$rs[id]',
			  '$rs[name]',
			  '$subject',
			  '$point',
			  '$use_point',
			  '$reg_date',
			  '$reg_date2'
		)";

		$dbo2->query($sql2);
		$i++;
		//checkVar($cell,$content);
	}

	msggo("일괄 적립하였습니다..","list_mileage.php");
	exit;
}
if($mode=="modify"){
		$sql2 = "
			update ez_special_point set
				subject='$subject',
				point='$point',
				use_point='$use_point'
			where id_no='$id_no'
		";
		$dbo2->query($sql2);
	msggo("수정하였습니다.","list_mileage.php");
	exit;
}
if($mode=="drop_one"){
		$sql2 = "delete from ez_special_point where id_no=$id_no ";
		$dbo2->query($sql2);
	msggo("삭제하였습니다.","list_mileage.php");
	exit;
}

$sql = "select * from ez_special_point where id_no='$id_no' ";
$dbo->query($sql);
$rs =$dbo->next_record();

for($i=0; $i<count($check);$i++){
	$id = $check[$i];
	$TARGETS .= "," . $check2[$id];
}
$TARGETS = substr($TARGETS,1);

if(!$TARGETS && $rs[id]) $TARGETS = $rs[id];
//-------------------------------------------------------------------------------
?>
<script language="JavaScript" src="../include/function.js"></script>
<script language="JavaScript">
<!--
function chkForm(){

	var fm  =document.fmData;

	if(check_blank(fm.subject,'내용을',0)=='wrong'){return }
	if(check_blank(fm.point,'포인트를',0)=='wrong'){return }
	if(confirm('적립하시겠습니까?')){
		fm.submit();
	}
}



//-->
</script>


<?include("../top.html");?>



<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
	</tr>
	<tr>
		<td> </td>
	</tr>
	<tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	</tr>
</table>


<br>

      <table border="0" cellspacing="1" cellpadding="3" width="750">

		<form name="fmData" method=post enctype="multipart/form-data">
		<input type="hidden" name="mode" value="<?=($rs[id_no])?"modify":"save"?>">
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="id_chk" value='0'>


		<tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>



        <tr>
          <td class="subject">적립대상</td>
          <td>
            <textarea cols=80 rows=3 class="box" readonly name="TARGETS"><?=$TARGETS?></textarea>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject" width="15%">내용</td>
          <td>
			<input class="box" type="text" name="subject" value="<?=$rs[subject]?>" size=50 maxlength="190">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject">마일리지 적립(+)</td>
          <td>
			<?=html_input('point',10,10,'box numberic')?> Point
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject">마일리지 사용(-)</td>
          <td>
			<?=html_input('use_point',10,10,'box numberic')?> Point
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>



        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan=2 bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
		<td colspan=2>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="130" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
				<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 적용 </a></span></td>
				<?if($rs[id_no]){?><td><span class="btn_pack medium bold"><a href="#" onClick="if(confirm('삭제하시겠습니까?'))location.href='?mode=drop_one&id_no=<?=$rs[id_no]?>'"> 삭제 </a></span></td><?}?>
		    </tr>
		  </table>
		  <!-- Button End------------------------------------------------>
		</td>
		</tr>
      </table>

	</form>

	<!--내용이 들어가는 곳 끝-->


<!-- Copyright -->
<?include_once("../bottom.html");?>
<script language="Javascript" src="http://xp3.nayana.kr/~yamshop/zoomtv/geditor/geditor.js"></script>
<iframe name="actarea2" style="display:none;"></iframe>