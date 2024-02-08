<?
session_start();
header("Content-Type: text/html; charset=utf-8");

while(list($key,$val)=each($_GET)){$$key=$val;}
while(list($key,$val)=each($_POST)){$$key=$val;}

#### Include
include_once('../../include/fun_basic.php');
include_once('../../include/fun_bbs.php');
include_once('../../include/fun_tour.php');
include_once('../../include/fun_cmp.php');
include_once('../include/proof.php');
include_once("../../include/config.php");
include_once("../../include/cmp_config.php");
include_once("../../password-hashing-master/PasswordHash.php");

include_once('../../include/fun_api.php');

include_once("../../include/vars.php");
include_once("../../lib/thumb.lib.php");
include_once("../../lib/AES.php");


#### inc
include_once('../../public/inc/ez_board_config.inc');
include_once('../../public/inc/site.inc');
include_once('../../public/inc/cmp_config.inc');


#### DB Connent
include_once ("../../include/info_dbconn.php");
include_once ("../../lib/class.$database.php");

$dbo = new MiniDB($info);


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_blank";
$TITLE = "권한 경고";
?>
<?include("../top.html");?>
<script language="JavaScript">
function chkForm(){
	var fm = document.fmData1;

	/*
	if(check_blank(fm.smsid,'ID를',0)=='wrong'){return }
	if(check_blank(fm.smspwd,'비밀번호를',0)=='wrong'){return }
	if(check_blank(fm.smscell,'휴대폰번호를',0)=='wrong'){return }
	*/

	fm.submit();
}
</script>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
			<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
	  </tr>
	  <tr>
			<td></td>
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
          <td align="center" height="200" style="color:red">
						<b>권한이 없습니다</b>
						<br><br>관리자에게 문의하여 주시기 바랍니다.
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

      </table>




	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>