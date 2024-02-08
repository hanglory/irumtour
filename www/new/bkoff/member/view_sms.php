<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "email";
$MENU = "member";
$TITLE = "회원 SMS 발송";


#### mode
if($mode=="save"){

	$filter =($target)? " and ($target >= '$start_date' and $target <= '$end_date 23:59:59') " : "";
	if($TARGETS){
		$TARGETS = "'".str_replace(",","','",trim($TARGETS)) . "'";
		$filter = " and id in ($TARGETS)";
	}

	$sql = "select name,cell from shop_pcmember where bit_sms = '1' and cell<>'' $filter";
	$dbo->query($sql);

	$i=1;
	while($rs=$dbo->next_record()){

		$content = str_replace("{회원명}",$rs[name],$content);
		$cell  =trim(str_replace("-","",$rs[cell]));
		if(!$from) $from="02-561-0356";

		F_SMS($cell, $from, $content);

		$i++;
		//checkVar($cell,$content);
	}

	echo "<script>alert('발송하였습니다.');history.back(-1);</script>";

}


for($i=0; $i<count($check);$i++){
	$id = $check[$i];
	$TARGETS .= "," . $check2[$id];
}
$TARGETS = substr($TARGETS,1);
//-------------------------------------------------------------------------------
?>
<script language="JavaScript" src="../include/function.js"></script>
<script language="JavaScript">
<!--
function chkForm(){

	var fm  =document.fmData;

	if(check_blank(fm.from,'보내는 사람 전화번호',0)=='wrong'){return }
	if(check_blank(fm.content,'내용을',0)=='wrong'){return }

	if(confirm('문자를 발송하시겠습니까?')){
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
		<input type="hidden" name="mode" value="save">
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="id_chk" value='0'>


		<tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>


        <tr>
          <td class="subject">보내는 사람 전화번호</td>
          <td>
			<input class="box" type="text" name="from" value="<?=$rs[from]?>" size=30 maxlength="50">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>


		<?if($TARGETS){?>
        <tr>
          <td class="subject">발송대상</td>
          <td>
            <textarea cols=80 rows=3 class="box" readonly name="TARGETS"><?=$TARGETS?></textarea>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>
		<?}else{?>
        <tr>
          <td class="subject">조건</td>
          <td>
            <!--<?=radio("전체회원,회원가입일,방문일,최종주문일",",reg_date,last_login,last_buy",$rs[target],"target")?>&nbsp;&nbsp;&nbsp;-->
            <?=radio("전체회원,회원가입일,방문일",",reg_date,last_login",$rs[target],"target")?>&nbsp;&nbsp;&nbsp;
			<input class="box dateinput" type="text" name="start_date" size="10" maxlength="10" value='<?=$rs[start_date]?>'> ~
			<input class="box dateinput" type="text" name="end_date" size="10" maxlength="10" value='<?=$rs[end_date]?>'>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>
		<?}?>


        <tr>
          <td class="subject">내용</td>
          <td colspan="3">
		    {회원명} : 회원 이름으로 대치됩니다.
            <textarea name="content" style="width:100%; height:70px" class="box"><?=stripslashes($rs[content])?></textarea>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>



        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan=2 bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
		<td colspan=2>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="230" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
				<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> SMS 발송 </a></span></td>
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