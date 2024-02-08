<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"기본설정");

####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_basic";
$TITLE = "기본설정";


#### operation
$filename= "../../public/inc/cmp_config.inc";

if($mode=="save"){

		for($i=0; $i <count($nations);$i++){
			if($nations[$i]) $nations_ .="," . str_replace(",","",addslashes($nations[$i]));
		}
		$nations = substr($nations_,1);

		for($i=0; $i <count($airlines);$i++){
			if($airlines[$i]) $airlines_ .="," . str_replace(",","",addslashes($airlines[$i]));
		}
		$airlines = substr($airlines_,1);

		$sms_text1 = str_format($sms_text1);
		$sms_text2 = str_format($sms_text2);

		$fp=fopen($filename, "w");	//파일 쓰기모드로 열기, 파일이 있다면 overwirte

		$config ="<?\n";
		$config .="##-------------------------------------------\n";
		$config .="##주의! (이 파일을 수동으로 조작하지 마세요.)\n";
		$config .="##-------------------------------------------\n";

		$config .="\$NATIONS='$nations';\n";
		$config .="\$AIRLINES='$airlines';\n";
		$config .="\$SMS_TEXT1='$sms_text1';\n";
		$config .="\$SMS_TEXT2='$sms_text2';\n";
		$config .="\$OFFICE_TEL='$office_tel';\n";
		$config .="\$ACCOUNT='$account';\n";

		$config .="?";
		$config .=">";


		if(!fwrite($fp,$config)){
			error("환경설정파일을 생성하던 중 예기치 못한 에러가 발생하였습니다. 관리자에게 문의하여 주시기 바랍니다.");exit;
		}
		fclose($fp);


		msggo("저장하였습니다.","?");
}else{
	@include($filename);
}
?>
<script language="JavaScript">
<!--
function chkForm(){
	var fm = document.fmData1;

	/*
	if(check_blank(fm.smsid,'ID를',0)=='wrong'){return }
	if(check_blank(fm.smspwd,'비밀번호를',0)=='wrong'){return }
	if(check_blank(fm.smscell,'휴대폰번호를',0)=='wrong'){return }
	*/

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

	<!--내용이 들어가는 곳 시작-->

      <table border="0" cellspacing="0" cellpadding="3" width="100%" id="viewWidth">

		<form name="fmData1" method="post">
		<input type="hidden" name="mode" value="save">

		<tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>


        <tr>
          <td class="subject" width="15%">국가명</td>
          <td>
			<?
			$row = 5;
			$arr = explode(",",$NATIONS);
			$cnt = count($arr);
			$add_row = 5-($cnt%5);
			$row=$cnt+$add_row;

			for($i=0;$i<$row;$i++){
				if(!($i%5)) echo "<div></div>";
			?>
			<input class="box" type="text" name="nations[]" id="nations<?=$i?>" value="<?=$arr[$i]?>" size="20" maxlength="30">
			<?
			}?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td class="subject" width="15%">항공사명</td>
          <td>
			<?
			$row = 5;
			$arr = explode(",",$AIRLINES);
			$cnt = count($arr);
			$add_row = 5-($cnt%5);
			$row=$cnt+$add_row;

			for($i=0;$i<$row;$i++){
				if(!($i%5)) echo "<div></div>";
			?>
			<input class="box" type="text" name="airlines[]" id="airlines<?=$i?>" value="<?=$arr[$i]?>" size="20" maxlength="30">
			<?
			}?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td class="subject" width="15%">대표번호(문자발송시)</td>
          <td>

			<input class="box" type="text" name="office_tel" id="nations<?=$i?>" value="<?=$OFFICE_TEL?>" size="20" maxlength="30">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">잔금안내문자</td>
          <td>
			<span style="float:left"><textarea name="sms_text1" class="box" cols="80" rows="15"><?=$SMS_TEXT1?></textarea></span>
			<span style="float:left">
				{고객명}<br>
				{담당자명}<br>
				{골프장명}<br>
				{잔금액}<br>
				{잔금일}<br>
				* 실제 데이터로 대체 됩니다. ex) {고객명}님 -> 홍길동님
			</span>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">출발안내문자</td>
          <td>
			<span style="float:left"><textarea name="sms_text2" class="box" cols="80" rows="15"><?=$SMS_TEXT2?></textarea></span>
			<span style="float:left">
				{고객명}<br>
				{일정}<br>
				{국내공항} {체크인 카운터}<br/>
				{미팅위치} {미팅보드} {현지담당} {비상연락처}<br>
				{수화물안내}<br>
				{준비물}<br>
				{날씨}
			</span>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">계좌번호</td>
          <td>
			<textarea name="account" class="box" cols="80" rows="3"><?=$ACCOUNT?></textarea></span>
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
					<td><span class="btn_pack medium bold"><a href="#" onClick="document.fmData1.reset()"> 취소 </a></span></td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>
			</td>
		</tr>
		</form>
      </table>




	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>