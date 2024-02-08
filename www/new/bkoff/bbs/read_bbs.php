<?
include_once("../include/common_file.php");




####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "bbs";
$TITLE = "게시판관리";


#### 기본 정보
$sql = "select * from ez_bbs_info where bid='$bid'";
$dbo->query($sql);
$rs = $dbo->next_record();
$bbs_title = $rs[subject];
$CATEGORY =$rs[category];
$BBS_ASSORT=$rs[assort];
$TITLE = $rs[subject];



$table = "ez_bbs";
$table2 = "ez_bbs_comment";
$column = "*";
$maxFileSize = intval(substr(ini_get(upload_max_filesize),0,-1));


#### mode
if($mode=="save"){
	$reg_date = time();
	$id =$_SESSION["sessLogin"]["id"];
	$name =$_SESSION["sessLogin"]["name"];

	$sql="
	   insert into $table2 (
		  doc_no,
		  name,
		  pwd,
		  comment,
		  reg_date,
		  id
	  ) values (
		  '$doc_no',
		  '$name',
		  '$pwd',
		  '$comment',
		  '$reg_date',
		  '$id'
	)";

	$dbo->query($sql);

	bbs_counter_comment($doc_no);

	back();
	exit;
}
elseif($mode=="drop"){
	$sql = "delete from $table2 where id_no = $id_no";
	$dbo->query($sql);

	$sql = "update $table set cnt_comment=(select count(*) from $table2 where doc_no=$doc_no) where id_no=$doc_no";
	$dbo->query($sql);

	back();
	exit;
}else{
		$sql =($sp=="new")? "select * from $table order by id_no desc limit 1":"select * from $table where id_no = $id_no";
		$dbo->query($sql);
		$rs = $dbo->next_record();
}

//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function cmtDrop(id_no){

	if(confirm('선택하신 의견을 삭제하시겠습니까?')){
		location.href="read_bbs.php?mode=drop&bid=<?=$bid?>&doc_no=<?=$rs[id_no]?>&id_no="+id_no;
	}

}

function cmtSave(){
	fm = document.fmData;
	if(fm.comment.value == ''){
		alert("저장할 내용을 입력하세요");
		fm.comment.focus();
		return;
	}
	fm.mode.value ='save';
	fm.submit();
}
//-->
</script>
<style type="text/css">
#doc_info span{
	color:gray;
	padding-left:20px
}
</style>
<?include("../top.html");?>



		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=($mode=="sub")?"${rs[parent_company]}의 소속":""?><?=$TITLE?></td>
			</tr>
			<tr>
				<td colspan="3"> </td>
			</tr>
			<tr>
				<td background="../images/common/bg_title.gif" height="5"></td>
			</tr>
		</table>


		<br>


		<!--내용이 들어가는 곳 시작-->


      <table border="0" cellspacing="1" cellpadding="3" width="750">
		<tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td height="30" colspan="2" style="padding-left:20px">
			<span class="bold blue"><?=($rs[notice])?"[공지]":""?></span>
			<span class="bold gray"><?=($rs[secret])?"[비밀글]":""?></span>
			<span class="bold f11"><?=stripslashes($rs[subject])?></span>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

		<tr>
          <td height="30" colspan="2">
		  <div id="doc_info">
			  <span>이름 : </span><?=$rs[name];?>
			  <?if($rs[id]) echo "<span> ID : </span>". $rs[id];?>
			  <?if($rs[email]) echo "<span> Email : </span>". $rs[email];?>
			  <?echo "<span> 등록일 : </span>". date("Y.m.d",$rs[reg_date]);?>
			  <?if($rs[edit_date]) echo "<span> 수정일 : </span>" . date("Y.m.d",$rs[edit_date]);?>
			  <?echo "<span> 조회수 : </span>". number_format($rs[cnt]);?>
		  </div>
		  </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td colspan=2 height="200" valign="top">
		  <div style="padding:20px"><?=$rs[content]?></div>
		  </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>


	<?if($rs[filename]):
		@$fileSize = filesize("../../public/bbs_files/${rs[filename]}");
		?>
        <tr>
          <td  class="subject">파일<r/td>
          <td>
			<a class=soft href="../../include/download.php?file=<?=$rs[filename]?>&orgin_file_name=<?=$rs[filename_real]?>&dir=public/bbs_files" onFocus="blur(this)"><?=$rs[filename_real]?> (<?=ceil($fileSize/1024)?>KB)</a>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?endif;?>

	<?if($rs[filename2]):
		@$fileSize = filesize("../../public/bbs_files/${rs[filename2]}");
		?>
        <tr>
          <td  class="subject">파일</td>
          <td>
            <a class=soft href="../../include/download.php?file=<?=$rs[filename2]?>&orgin_file_name=<?=$rs[filename2_real]?>&dir=public/bbs_files" onFocus="blur(this)"><?=$rs[filename2_real]?> (<?=ceil($fileSize/1024)?>KB)</a>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?endif;?>

	<?if($rs[filename3]):
		@$fileSize = filesize("../../public/bbs_files/${rs[filename3]}");
		?>
        <tr>
          <td  class="subject">파일</td>
          <td>
            <a class=soft href="../../include/download.php?file=<?=$rs[filename3]?>&orgin_file_name=<?=$rs[filename3_real]?>&dir=public/bbs_files" onFocus="blur(this)"><?=$rs[filename3_real]?> (<?=ceil($fileSize/1024)?>KB)</a>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?endif;?>


        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>

        <tr>
		<td colspan=2>

		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" rcellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
			   <td>
				<span class="btn_pack medium bold"><a href="view_bbs.php?id_no=<?=$rs[id_no]?>&bid=<?=$bid?>"> 수정 </a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="#" onClick="location.href='view_bbs.php?bid=<?=$bid?>'"> 등록 </a></span> &nbsp;
				<span class="btn_pack medium bold"><a href="#" onClick="location.href='list_bbs.php?<?=$_SESSION["link"]?>'"> 리스트 </a></span>
			   </td>
		    </tr>
		  </table>
		  <!-- Button End------------------------------------------------>


		</td>
	</tr>

    <tr><td height=10></td></tr>
    <tr><td colspan="2" class='bar'></td></tr>
		<form name="fmData" method="post">
		<input type="hidden" name="mode" value= "save">
		<input type="hidden" name="doc_no" value= "<?=$rs[id_no]?>">
		<tr>
          <td  class="subject" width="100">덧글</td>
          <td>
			<div style="padding:1 0 1">
			<table>
			<tr>
			<td>	
			<textarea class=box name=comment rows=4 cols=86 style="width:100%;height:60px"></textarea>
			</td>
			<td>
			<input class=box type=button value="입력" style="color:000000;width:89px;height:60px" onFocus="blur(this)" onClick="cmtSave()">
			</td>	
				</tr>
			</table>
			</div>
          </td>
        </tr>
		</form>
        <tr><td colspan="2" class='bar'></td></tr>

	<?
	$dbo->query("select * from $table2 where doc_no = '$id_no' order by id_no desc");
	$i=1;
	while($rsCmt = $dbo->next_record()){
	?>
        <tr>
          <td align="center"><?=$i?></td>
          <td valign=top style="padding:10px 0 10px">
			<?=nl2br(enVars($rsCmt[comment]))?>
			<hr size=1 color=D7D7D7 width=250 align=left>
			<span class="blue f8"><?=$rsCmt[name]?>님 <?=$rsCmt[email]?></span> <span class="gray en">( <?=date("Y.m.d H:i",$rsCmt[reg_date])?> )</span>

			&nbsp;&nbsp;&nbsp;&nbsp;
			<span class="btn_pack small bold"><a href="javascript:cmtDrop('<?=$rsCmt[id_no]?>')"> 삭제 </a></span>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?
		$i++;
	}
	?>
      </table>




	<!--내용이 들어가는 곳 끝-->



<!-- Copyright -->
<?include_once("../bottom.html");?>
