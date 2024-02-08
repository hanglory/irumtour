<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_etc_contents";
$MENU = "tour";
$TITLE = "기타정보";

#### operation

if ($mode=="save"){

		$reg_date = date('Y/m/d');
		$reg_date2 = date('H:i:s');

		If($assort=="취소환불규정") $assort2 = "80_취소환불규정";
		elseIf($assort=="미팅장소") $assort2 = "90_미팅장소";
		Else $assort2=$assort;

		$sql = "delete from $table where tid=$tid and assort='$assort2' ";
		$dbo->query($sql);

		$sql="
		   insert into $table (
			  tid,
			  code,
			  assort,
			  subject,
			  content,
			  reg_date,
			  reg_date2
		  ) values (
			  '$tid',
			  '$code',
			  '$assort2',
			  '$subject',
			  '$content',
			  '$reg_date',
			  '$reg_date2'
		)";

		$url = "pop_etc.php?tid=$tid&assort=$assort&div=$div";

		$content_length = strlen(trim(strip_tags(str_replace("&nbsp;","",$content))));


		if($content_length){
			if($dbo->query($sql)){
				redirect2($url);
			}else{
				checkVar(mysql_error(),$sql);
			}
		}else{
			redirect2($url);
		}
		exit;

}elseif ($mode=="drop"){

	$sql = "delete from $table where code = $code";
	$dbo->query($sql);

	redirect2("?tid=$tid&assort=$assort&div=$div");exit;

}else{

	$filter = ($mode=="copy")?" and code='$code' ":" and tid=$tid ";


	If($assort=="취소환불규정") $assort2 = "80_취소환불규정";
	elseIf($assort=="미팅장소") $assort2 = "90_미팅장소";
	Else $assort2= $assort;

	$sql = "select * from $table where assort='$assort2' $filter order by id_no desc limit 1";
	$dbo->query($sql);
	//checkVar("",$sql);
	$rs= $dbo->next_record();

}

if($mode=="copy") $code=getUniqNo();
else  $code=($rs[code])? $rs[code] : getUniqNo();

//-------------------------------------------------------------------------------
?>
<?include("../top_min.html");?>
<script type="text/javascript" src="/cheditor/cheditor.js"></script>
<script language="JavaScript">
<!--
function chk_form(){
	var fm = document.fmData;
	if('<?=$assort?>'=="추가입력" && check_blank(fm.subject,'제목을',0)=='wrong'){return}

	var content = myeditor.outputBodyHTML();
	fm.content.value = content;
	/*
	if(document.getElementById("contents").value==""){
		alert('내용을 입력하세요');
		return;
	}
	*/
	fm.submit();
}

function drop(code){
	if(confirm('삭제하시겠습니까?')){
		location.href="?mode=drop&tid=<?=$tid?>&assort=<?=$assort?>&div=<?=$div?>&code="+code;
	}
}

function set_assort(assort){
	var assort_ = "<?=$assort?>";

	if(assort_!=""){
		if(confirm('페이지를 다시 로드합니다.\n\n저장하지 않은 데이터가 있으면 저장하세요. \n\n진행하시겠습니까?')){
			location.href="?tid=<?=$tid?>&div=<?=$div?>&assort="+assort;
		}
	}else{
		location.href="?tid=<?=$tid?>&div=<?=$div?>&assort="+assort;
	}
}

function choice(code){
	location.href="?mode=copy&tid=<?=$tid?>&div=<?=$div?>&assort=<?=$assort?>&code="+code;
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
		<input type="hidden" name="id_no" value='<?=($mode!="copy")?$rs[id_no]:""?>'>
		<input type="hidden" name="tid" value='<?=$tid?>'>
		<input type="hidden" name="code" value='<?=$code?>'>
		<input type="hidden" name="div" value='<?=$div?>'>

		<tr><td colspan="2" bgcolor='#5E90AE' height=2></td></tr>
        <tr>
          <td class="subject" width="15%">구분</td>
          <td>
		    <?
			If($div==1){
				$TOUR_ETC = $TOUR_ETC1;
			}elseIf($div==2){
				$TOUR_ETC = $TOUR_ETC2.",추가입력";
			}
			elseIf($div==3){
				$TOUR_ETC = $TOUR_ETC3.",추가입력";
			}
			?>
			<select name="assort" onchange="set_assort(this.value)">
            <option value="">선택</option>
			<?=option_str($TOUR_ETC,$TOUR_ETC,$assort)?>
			</select>

			<?if($assort=="추가입력"){?>
			<?=html_input("subject",30,45)?>
			<?}?>

          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
		<?if($assort){?>
		<tr>
          <td colspan="2">
				<!-- Html Editor Begin -->
				<textarea id="contents" name="content"><?=$rs[content]?></textarea>
				<script type="text/javascript">
				var myeditor = new cheditor();              // 에디터 개체를 생성합니다.
				myeditor.config.editorHeight = '100px';     // 에디터 세로폭입니다.
				myeditor.config.editorWidth = '100%';        // 에디터 가로폭입니다.
				myeditor.inputForm = 'contents';             // textarea의 ID 이름입니다.
				myeditor.run();                             // 에디터를 실행합니다.
				</script>
				<!-- Html Editor End -->
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
		<tr>
		  <td colspan="2">
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td><span class="btn_pack medium bold"><a href="javascript:chk_form()"> 저장 </a></span>&nbsp;&nbsp;</td>
					<?If($rs[tid]==$tid){?><td><span class="btn_pack medium bold"><a href="javascript:drop('<?=$rs[code]?>')"> 삭제 </a></span>&nbsp;&nbsp;</td><?}?>
					<td><span class="btn_pack medium bold"><a href="javascript:self.close()"> 창닫기 </a></span></td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>
		  </td>
        </tr>
		<?}?>
        <tr>
		  <td colspan=10 height=20>
		  </td>
        </tr>
	</form>
	</table>

<?
$sql = "delete from $table where assort=''";
$dbo->query($sql);

$sql = "select * from $table where assort='$assort2' group by content order by content asc";
list($row_search)=$dbo->query($sql);

if($row_search){
?>

	<div style="padding-left:20px;color:#ff3300">* 다른 상품에 설정된 "<?=$assort?>"입니다. "선택"을 클릭하시면 복사해 올 수 있습니다.</div>
	<table border="0" cellspacing="0" cellpadding="3" width="95%" id="tbl_list" align="center">
        <tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<td class="subject" align="left" width="50"><b>번호</b></td>
		<td class="subject l" align="left"><b>내용</b></td>
		<td class="subject" width="100"><b>선택</b></td>
		</tr>
		<tr><td colspan="8" class='bar'></td></tr>
<?
$i=1;
while($rs=$dbo->next_record()){
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="50"><?=$i?></td>
	      <td align="left"><?=titleCut(strip_tags($rs[content]),200)?></td>
	      <td>
			<span class="btn_pack large bold"><a href="javascript:choice('<?=$rs[code]?>')"> 선택 </a></span>
		  </td>
        <tr><td colspan="8" class='bar'></td></tr>
<?
	$num--;
	$i++;
}
?>
		<tr><td colspan=9 height=1><?=$error[noData]?></td></tr>
        <tr><td colspan=9  bgcolor='#E1E1E1' height=3></td></tr>

	</table>
<?}?>

	<!--내용이 들어가는 곳 끝-->

	<p style="height:50px"></p>

</body>
</html>