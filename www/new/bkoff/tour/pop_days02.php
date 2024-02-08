<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour_days";
$TITLE = "설정된 일정 보기";
?>
<?include("../top_min.html");?>
<script type="text/javascript">
<!--
function mng_days(){
	location.href='pop_days.php?tid=<?=$tid?>';
}

function mng_days_detail(){
	location.href='pop_days02.php?tid=<?=$tid?>';
}
//-->
</script>

	<table width="99%" border="0" cellspacing="0" cellpadding="0" align="center">
	  <tr>
		<td class="title_con" style="padding-left:20px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
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
    <table border="0" cellspacing="0" cellpadding="5" width="95%" align="center">

		<tr>
          <td height="30" colspan="3">

			<!--게시판 시작-->
			<script type="text/javascript" src="../../include/bbs_frame.js"></script>
			<iframe width="98%" name="bbs" id="bbs"
				onLoad="calcHeight(this.id);"
				src="../../days.html?tid=<?=$tid?>"
				scrolling="NO"
				frameborder="0"
				>
			</iframe>
			<!--게시판 종료-->

		  </td>
        </tr>

        <tr>
		  <td colspan=10>
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="180" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td><span class="btn_pack medium bold"><a href="#" onClick="mng_days()"> 일정설정 </a></span></td>
					<td><span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span></td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>
		  </td>
        </tr>
	</table>

	 <br>


	<!--내용이 들어가는 곳 끝-->
	<iframe name="actarea" style="display:none;"></iframe>


</body>
</html>