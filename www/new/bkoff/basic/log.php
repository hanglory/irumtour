<?
#### Include
include_once('../../include/fun_basic.php');
include_once('../../include/fun_bbs.php');
include_once('../include/proof.php');


#### Menu
$filecode = substr(SELF,5,-4);
$TITLE = "로그분석";
$MENU = "basic";
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


      <table border="0" cellspacing="1" cellpadding="3" width="850">
		<tr>
		<td>

		<script type="text/javascript" src="/include/bbs_frame.js"></script>
		<iframe width="98%" height=1000 name="bbs" id="bbs"
			onLoad="calcHeight(this.id);"
			src="../log/rankuplog_login.php?uid=123123&upasswd=123123"
			scrolling="NO"
			frameborder="0"
			>
		</iframe>

		</td>
		</tr>
      </table>




	<!--내용이 들어가는 곳 끝-->



<!-- Copyright -->
<?include_once("../bottom.html");?>