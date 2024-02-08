<?
include_once("../include/common_file.php");


if($_POST[keyword]){
	$keyword = trim($keyword);

	if($assort=="40_hotel"){
		$sql = "select * from cmp_hotel where name like '%$keyword%' ";
		list($rows)= $dbo->query($sql);
		echo "<select id='golf2_id_no' style='width:540px;padding:5px;margin-right:5px;font-size:11pt' onchange='load_hotel(this.value)'>";
	}else{
		$sql = "select * from cmp_golf2 where name like '%$keyword%' ";
		list($rows)= $dbo->query($sql);
		echo "<select id='golf2_id_no' style='width:540px;padding:5px;margin-right:5px;font-size:11pt' onchange='load_golf(this.value)'>";
	}

	echo "<option value=''>선택</option>";
	while($rs=$dbo->next_record()){
	?>
		<option value='<?=$rs[id_no]?>'><?=$rs[name]?></option>
	<?
	}
	echo "</script>&nbsp;";

	exit;
}

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tab_contents";
$MENU = "tour";
$TITLE = "여행지관리";
$REMARK=0;

switch($assort){
  case "10_information": $TITLE="상세정보";break;
  case "30_golf": $TITLE="골프장소개";break;
  case "40_hotel": $TITLE="호텔/리조트소개";break;
  case "50_gallery": $TITLE="포토갤러리";break;
  case "60_cancel": $TITLE="예약/환불규정";break;
  case "remark_1_days": $TITLE="REMARK(상단)";$REMARK=1;$table = "ez_days_contents";break;
  case "remark_3_days": $TITLE="REMARK(상단2)";$REMARK=1;$table = "ez_days_contents";break;
  case "remark_2_days": $TITLE="REMARK(하단)";$REMARK=1;$table = "ez_days_contents";break;
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

	// oEditors.getById["content"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.
	// try {
	// 	elClickedObj.form.submit();
	// } catch(e) {}

	if(document.getElementById("content").value==""){
		alert('본문을 입력하세요');
		return;
	}

	fm.submit();
}

function drop(id_no){
	if(confirm('삭제하시겠습니까?')){
		location.href="?mode=drop&tid=<?=$tid?>&assort=<?=$assort?>&id_no="+id_no;
	}
}


function find(){
	$.ajax({
	  url: "pop_contents.php",
	  type: "POST",
	  data: {
		assort: '<?=$assort?>',
		keyword: $("#keyword").val()
	  },
	  success: function(data) {
		$("#golf_select").html(data);
	  }
	});
}

function load_golf(golf_id_no){
	var url = "pop_contents.php?tid=<?=$tid?>&assort=30_golf";
	url += "&golf_id_no=" + golf_id_no;
	url += "&keyword=" + $("#keyword").val();
	location.href=url;
}

function load_hotel(golf_id_no){
	var url = "pop_contents.php?tid=<?=$tid?>&assort=40_hotel";
	url += "&golf_id_no=" + golf_id_no;
	url += "&keyword=" + $("#keyword").val();
	location.href=url;
}
//-->
</script>
<style type="text/css">
body{
    padding:0 10px;
}    
</style>

	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
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

	<?

		if($assort=="30_golf" || $assort=="40_hotel"){
		?>

	   <div style="padding:0 24px 0 20px;text-align:right">
			<?$rs[keyword] = $keyword;?>
			<span id="golf_select"></span>
			<?=html_input('keyword',30,35)?>
			<span class="btn_pack medium bold"><a href="javascript:find()"> 검색 </a></span>

		</div>

		<?
		}

		if($golf_id_no){

			if($assort=="30_golf")	$sub_table="cmp_golf2";
			elseif($assort=="40_hotel")	$sub_table="cmp_hotel";

			$sql2 = "select * from $sub_table where id_no=$golf_id_no";
			$dbo2->query($sql2);
			$rs2=$dbo2->next_record();

			$rs[content]	  = nl2br($rs2[content]);
		}


	?>


    <table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">
		<form name="fmData"  method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="tid" value='<?=$tid?>'>
		<input type="hidden" name="assort" value='<?=$assort?>'>


        <tr>
          <td>
			<!-- Html Editor Begin -->

            <?
            $board_height = 500;
            $editors_id="";
            include("../jodit.php");
            ?>

			<!-- <textarea name="content" id="content" rows="10" cols="100" style="width:100%; height:300px; display:none;"><?=$rs[content]?></textarea>
			<script type="text/javascript" src="../../se2/js/HuskyEZCreator.js" charset="utf-8"></script>
			<script type="text/javascript" src="../../include/smart_editor.js"></script> -->
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