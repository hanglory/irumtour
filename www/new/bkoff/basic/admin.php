<?
include_once("../include/common_file.php");

Header( "HTTP/1.1 301 Moved Permanently" );
Header( "Location: ./site.php" );
exit;


#### Menu
$filecode = substr(SELF,5,-4);
$TITLE = "관리자 계정 변경";
$MENU = "basic";


$id = trim($id);
$pwd = trim($pwd);

if($mode=="save"){
	$sqlInsert="
	   insert into ez_admin (
		  id,
		  pwd
	  ) values (
		  '$id',
		  password('$pwd')
	)";


	$sqlModify="
	   update ez_admin set
		  id = '$id',
		  pwd = password('$pwd')
	   where id_no='$id_no'
	";

	$sql = ($id_no)? $sqlModify : $sqlInsert;
	$dbo->query($sql);
	//checkVar(mysql_error(),$sql);
	error("변경되었습니다.");
	exit;

}else{
	$sql = "select * from ez_admin order by id_no desc limit 1";
	$dbo->query($sql);
	$rs = $dbo->next_record();
}

//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(){
	var fm = document.fmData;
	if(check_blank(fm.id,'아이디를',4)=='wrong'){return }
	if(check_blank(fm.pwd,'비밀번호를',4)=='wrong'){return }
	if(fm.pwd.value!=fm.pwd_check.value){alert('비밀번호와 비밀번호 확인이 일치하지 않습니다.');fm.pwd_check.focus();return }

	fm.submit();
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

	  <table border="0" cellspacing="0" cellpadding="3" width="100%" id="viewWidth">

		<form name="fmData" target="actarea">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>

		<tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td  class="subject">*새 ID</td>
          <td>
            <input class=box type="text" name="id" size=40 value="<?=$rs[id]?>">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td  class="subject">*새 비밀번호</td>
          <td>
            <input class=box type="password" name="pwd" size=40>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td  class="subject">*새 비밀번호 확인</td>
          <td>
            <input class=box type="password" name="pwd_check" size=40>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>

        <tr>
		<td colspan=2>
		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
				<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
				<td><span class="btn_pack medium bold"><a href="#" onClick="document.fmData.reset()"> 취소 </a></span></td>
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