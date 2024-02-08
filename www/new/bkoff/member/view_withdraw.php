<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_withdraw";
$MENU = "member";
$TITLE = "회원탈퇴현황";



#### mode
if($mode=="save"){

		$reg_date = date("Y/m/d");

		$sqlInsert="
		   insert into $table (
			  id,
			  content,
			  reg_date
		  ) values (
			  '$id',
			  '$content',
			  '$reg_date'
		)";


		$sqlModify="
		   update $table set
			  content = '$content'
		   where id_no='$id_no'
		";

		if($id_no){
			$sql = $sqlModify;
			$url = "view_${filecode}.php?id_no=$id_no";
		}else{
			$sql = $sqlInsert;
			$url = "list_${filecode}.php";
		}

		//checkVar("",$sql);exit;

		if($dbo->query($sql)){
			msggo("저장하였습니다.",$url);
		}else{
			checkVar(mysql_error(),$sql);
		}
		exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){

		$sql = "delete from $table where id_no = $check[$i]";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php");exit;

}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();

}
//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(){

	var fm  =document.fmData;


	fm.submit();
}


//-->
</script>

<?include("../top.html");?>



		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=($mode=="sub")?"${rs[parent_company]}의 소속":""?><?=$TITLE?></td>
			</tr>
			<tr>
				<td colspan="3"> </td>
			</tr>
			<tr>
				<td background="../images/common/bg_title.gif" height="5"></td>
			</tr>
		</table>


		<br>


      <table border="0" cellspacing="1" cellpadding="3" width="750">

		<form name="fmData" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="save">
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>


		<tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan="2" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">아이디</td>
          <td>
			<b><?=$rs[id]?></b>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>


        <tr>
          <td class="subject">사유</td>
          <td>
            <textarea class="box" name="content" rows="5" style="width:100%"><?=$rs[content]?></textarea>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>



		<?if($rs[id_no]){?>
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td class="subject">탈퇴일</td>
          <td>
            <?=$rs[reg_date]?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
		<?}?>

        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan=2 bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
		<td colspan=2>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
				<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
				<td><span class="btn_pack medium bold"><a href="#" onClick="location.href='list_<?=$filecode?>.php?<?=$sessLink?>'"> 리스트 </a></span></td>
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

