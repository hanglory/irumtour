<?
include_once("../include/common_file.php");


#### Menu
$filecode = substr(SELF,5,-4);
$TITLE = "게시판 환경설정";
$MENU = "bbs";


#### operation
$inc_file= "../../public/inc/ez_board_config.inc";

if ($mode=="save"){

		if($_FILES["file1"]["size"]){
			#------------------------------------------
			$path="../../public/ez_board/bg";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file1"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file1"]["name"];	//파일의 이름
			$fname_size=$_FILES["file1"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file1"]["type"];		//파일의 type
			$filename="bg_list";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile1 = $upfile;
		}
		$upfile1 = ($upfile1)? $upfile1 : $bg_list;

		if($_FILES["file2"]["size"]){
			#------------------------------------------
			$path="../../public/ez_board/bg";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file2"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file2"]["name"];	//파일의 이름
			$fname_size=$_FILES["file2"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file2"]["type"];		//파일의 type
			$filename="default_img";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile2 = $upfile;
		}
		$upfile2 = ($upfile2)? $upfile2 : $default_img;

		$fp=fopen($inc_file, "w");	//파일 쓰기모드로 열기, 파일이 있다면 overwirte

		$config ="<?\n";
		$config .="##-------------------------------------------\n";
		$config .="##주의! (이 파일을 수동으로 조작하지 마세요.)\n";
		$config .="##-------------------------------------------\n";

		$config .="\$EZ_BOARD_CONFIG_WIDTH='$ez_board_config_width';\n";
		$config .="\$EZ_BOARD_CONFIG_LENGTH='$ez_board_config_length';\n";
		$config .="\$EZ_BOARD_CONFIG_ROWS='$ez_board_config_rows';\n";
		$config .="\$EZ_BOARD_CONFIG_GALLERY_1ROWS='$ez_board_config_gallery_1rows';\n";
		$config .="\$EZ_BOARD_CONFIG_GALLERY_ROWS='$ez_board_config_gallery_rows';\n";
		$config .="\$BG_LIST='$upfile1';\n";
		$config .="\$DEFAULT_IMG='$upfile2';\n";

		$config .="?";
		$config .=">";


		if(!fwrite($fp,$config)){
			error("환경설정파일을 생성하던 중 예기치 못한 에러가 발생하였습니다. 관리자에게 문의하여 주시기 바랍니다.");exit;
		}
		fclose($fp);

		//checkVar($upfile1,$config);exit;

		msggo("저장하였습니다.","?");

}elseif($mode=="file_drop"){

		unlink("../../public/ez_board/bg/$filename");
		back();exit;

}else{
	@require($inc_file);
}
//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(fm){
	var fm = document.fmData;


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

		<form name="fmData" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="save">
		<input type="hidden" name="bg_list" value='<?=$BG_LIST?>'>
		<input type="hidden" name="default_img" value='<?=$DEFAULT_IMG?>'>

		<tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>
        <tr>
          <td class="subject" width="20%">게시판 가로 사이즈</td>
          <td>
			<input type="text" name="ez_board_config_width" value="<?=$EZ_BOARD_CONFIG_WIDTH?>" size="4" maxlength="4" class="box"> px
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">글목록 글자수</td>
          <td>
			<input type="text" name="ez_board_config_length" value="<?=$EZ_BOARD_CONFIG_LENGTH?>" size="4" maxlength="3" class="box"> 자
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">글목록의 게시물 갯수</td>
          <td>
			<input type="text" name="ez_board_config_rows" value="<?=$EZ_BOARD_CONFIG_ROWS?>" size="4" maxlength="3" class="box"> 개
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">글목록의 게시물 갯수(이미지)</td>
          <td>
			1행에 <input type="text" name="ez_board_config_gallery_1rows" value="<?=$EZ_BOARD_CONFIG_GALLERY_1ROWS?>" size="4" maxlength="3" class="box"> 개 씩,
			총 <input type="text" name="ez_board_config_gallery_rows" value="<?=$EZ_BOARD_CONFIG_GALLERY_ROWS?>" size="4" maxlength="3" class="box"> 개
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td class="subject" width="15%">글목록 배경 이미지<r/td>
          <td>
			<?
			$filePath = "../../public/ez_board/bg/$BG_LIST";
			@$fileSize = filesize($filePath);
			if($filePath!="../../public/ez_board/bg/" && $fileSize):
			?>
				<span class="btn_pack small bold"><a href="#" onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?mode=file_drop&mode2=filename&filename=<?=$BG_LIST?>'}""> 파일삭제 </a></span>&nbsp;&nbsp;&nbsp;

				<img src="<?=$filePath?>" align="absmiddle">
				<a class=soft href="../../include/download.php?file=<?=$BG_LIST?>&orgin_file_name=<?=$BG_LIST?>&dir=public/ez_board/bg" onFocus="blur(this)"><?=$BG_LIST?> (<?=ceil($fileSize/1024)?>KB)</a>
			<?else:?>
				<input class=box type="file" name="file1" size="40">
			<?endif;?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td class="subject" width="15%">기본 이미지<r/td>
          <td>
			<?
			$filePath = "../../public/ez_board/bg/$DEFAULT_IMG";
			@$fileSize = filesize($filePath);
			if($filePath!="../../public/ez_board/bg/" && $fileSize):
			?>
				<span class="btn_pack small bold"><a href="#" onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?mode=file_drop&mode2=filename&filename=<?=$DEFAULT_IMG?>'}""> 파일삭제 </a></span>&nbsp;&nbsp;&nbsp;

				<img src="<?=$filePath?>" align="absmiddle">
				<a class=soft href="../../include/download.php?file=<?=$DEFAULT_IMG?>&orgin_file_name=<?=$DEFAULT_IMG?>&dir=public/ez_board/bg" onFocus="blur(this)"><?=$DEFAULT_IMG?> (<?=ceil($fileSize/1024)?>KB)</a>
			<?else:?>
				<input class=box type="file" name="file2" size="40">
			<?endif;?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>

        <tr>
		<td colspan=2>
		<br>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
				<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
				<td><span class="btn_pack medium bold"><a href="#" onClick="document.fmData.reset()"> 취소 </a></span></td>
		    </tr>
		  </table>
		  <!-- Button End------------------------------------------------>
		</td>
	</tr>
      </table>

	</form>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>