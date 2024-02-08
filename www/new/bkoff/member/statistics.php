<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table =  "ez_member";
$MENU = "member";
$TITLE = "회원통계";

$year = ($year)? $year : date("Y");
$month = ($month)? $month : date("m");
$day = ($day)? $day : date("d");

//-------------------------------------------------------------------------------
?>
<script language="JavaScript" src="../include/function.js"></script>
<script type="text/javascript">
<!--
function gogo(url){
	document.getElementById('bbs').src=url;
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

      <table border="0" cellspacing="1" cellpadding="3" width="100%">

		<form name="fmData" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="save">
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="id_chk" value='0'>


		<tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>
<?
$sql = "select count(*) as total from $table where cp_id='$CP_ID'";
$dbo->query($sql);
$rs =$dbo->next_record();
$TOTAL = $rs[total];
?>
        <tr>
          <td class="subject" width="30%">전체 회원수</td>
          <td height="50">
            총 <?=number_format($rs[total])?> 명
          </td>
        </tr>
        <tr><td colspan="2" bgcolor='#CCCCCC' height="1"></td></tr>
        <!-- <tr>
          <td class="subject" width="150">조건선택</td>
          <td>
			<span class="btn_pack medium bold"><a href="#" onClick="gogo('include_statistics01.php?year=<?=$year?>')"> 월별가입 </a></span>&nbsp;&nbsp;
			<span class="btn_pack medium bold"><a href="#" onClick="gogo('include_statistics02.php?year=<?=$year?>')"> 구매횟수 </a></span>&nbsp;&nbsp;
			<span class="btn_pack medium bold"><a href="#" onClick="gogo('include_statistics03.php?year=<?=$year?>')"> 구매액순위 </a></span>
          </td>
        </tr>
        -->

        <tr>
          <td colspan="2">

			<!--통계시작-->
			<script type="text/javascript" src="../../include/bbs_frame.js"></script>
			<iframe width="100%" height=1000 name="bbs" id="bbs"
				onLoad="calcHeight(this.id);"
				src="include_statistics01.php?year=<?=$year?>"
				scrolling="NO"
				frameborder="0"
				>
			</iframe>
			<!--통계종료-->

          </td>
        </tr>

      </table>

	</form>

	<!--내용이 들어가는 곳 끝-->


<!-- Copyright -->
<?include_once("../bottom.html");?>

