<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour_category1";
$MENU = "tour";
$TITLE = "상품관리 - 기차";

//-------------------------------------------------------------------------------
?>
<?include("../top.html");?>
<script type="text/javascript">
<!--
function box_resize(){

	var height=window.innerWidth;//Firefox
	 if (document.body.clientHeight)
	 {
	  height=document.body.clientHeight;//IE
	 }
	 height = height-160;

	$("#tree").css("height",height + "px");

}

$(function(){
	box_resize();

	window.onresize	= box_resize;
});
//-->
</script>

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


	<!--내용이 들어가는 곳 시작-->

	<table width="100%">
	<tr>
		<td width="30%">
			<!--게시판 시작-->
			<script type="text/javascript" src="../../include/bbs_frame.js"></script>
			<iframe width="98%" name="tree" id="tree"
				src="category_tree.php"
				frameborder="0"
				>
			</iframe>
			<!--게시판 종료-->
		</td>
		<td valign="top">

			<!--관리/등록/삭제-->
			<iframe width="100%" name="reg" id="reg"
				onLoad="calcHeight(this.id);"
				src="category_reg.php"
				scrolling="NO"
				frameborder="0"
				>
			</iframe>
			<!--//관리/등록/삭제-->

		</td>
	</tr>
	</table>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>