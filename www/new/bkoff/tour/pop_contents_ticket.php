<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tab_contents_ticket";
$MENU = "tour";
$TITLE = "여행지관리";


switch($assort){
  case "10_information": $TITLE="상품정보";break;
  case "20_guide": $TITLE="이용안내";break;
  case "30_facility": $TITLE="시설안내";break;
  case "40_plan": $TITLE="운행일정";break;
  case "50_time": $TITLE="운행코스/시간표";break;
  case "60_hotel": $TITLE="호텔정보";break;
  case "70_map": $TITLE="찾아가는 길";break;
}


#### operation

if ($mode=="save"){

		$reg_date = date('Y/m/d');
		$reg_date2 = date('H:i:s');

		$chk = Trim(strip_tags($content));

		If($chk=="&nbsp;") $content="";


		$sqlInsert="
		   insert into $table (
			  tid,
			  assort,
			  content,
			  reg_date,
			  reg_date2
		  ) values (
			  '$tid',
			  '$assort',
			  '$content',
			  '$reg_date',
			  '$reg_date2'
		)";


		$sqlModify="
		   update $table set
			  tid = '$tid',
			  assort = '$assort',
			  content = '$content'
		   where id_no='$id_no'
		";

		if($id_no){
			$sql = $sqlModify;
			$url = "pop_contents.php?tid=$tid&assort=$assort";
		}else{
			$sql = $sqlInsert;
			$url = "pop_contents.php?tid=$tid&assort=$assort";
		}

		if($dbo->query($sql)){
			redirect2($url);
		}else{
			checkVar(mysql_error(),$sql);
		}
		exit;


}elseif ($mode=="drop"){

	$sql = "delete from $table where id_no = $id_no";
	$dbo->query($sql);

	redirect2("?tid=$tid&assort=$assort");exit;

}else{
	$sql = "select * from $table where tid=$tid and assort='$assort' order by id_no desc limit 1";
	$dbo->query($sql);
	$rs= $dbo->next_record();

}

//-------------------------------------------------------------------------------
?>
<?include("../top_min.html");?>
<script type="text/javascript" src="/cheditor/cheditor.js"></script>
<script language="JavaScript">
<!--
function chk_form(){
	var fm = document.fmData;

	var content = myeditor.outputBodyHTML();
	fm.content.value = content;

	if(document.getElementById("contents").value==""){
		alert('내용을 입력하세요');
		return;
	}
	fm.submit();
}

function drop(id_no){
	if(confirm('삭제하시겠습니까?')){
		location.href="?mode=drop&tid=<?=$tid?>&assort=<?=$assort?>&id_no="+id_no;
	}
}
//-->
</script>


	<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
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


    <table border="0" cellspacing="1" cellpadding="3" width="95%" align="center">
		<form name="fmData"  method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="tid" value='<?=$tid?>'>
		<input type="hidden" name="assort" value='<?=$assort?>'>


        <tr>
          <td>
				<!-- Html Editor Begin -->
				<textarea id="contents" name="content"><?=$rs[content]?></textarea>
				<script type="text/javascript">
				var myeditor = new cheditor();              // 에디터 개체를 생성합니다.
				myeditor.config.editorHeight = '500px';     // 에디터 세로폭입니다.
				myeditor.config.editorWidth = '100%';        // 에디터 가로폭입니다.
				myeditor.inputForm = 'contents';             // textarea의 ID 이름입니다.
				myeditor.run();                             // 에디터를 실행합니다.
				</script>
				<!-- Html Editor End -->
          </td>
        </tr>
        <tr class="tblLine"></td></tr>
        <tr>
		  <td>
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="200" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td><span class="btn_pack medium bold"><a href="javascript:chk_form()"> 저장 </a></span></td>
					<td><span class="btn_pack medium bold"><a href="pop_contents.php?tid=<?=$tid?>&assort=<?=$assort?>"> 취소 </a></span></td>
					<td><span class="btn_pack medium bold"><a href="javascript:self.close()"> 창닫기 </a></span></td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>
		  </td>
        </tr>

        <tr>
	  <td  height=20>
	  </td>
        </tr>
	</form>
	</table>


</body>
</html>