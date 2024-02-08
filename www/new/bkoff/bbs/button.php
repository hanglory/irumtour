<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "bbs";
$TITLE = "버튼 등록";
$inc_file= "../../public/inc/ez_board_button.inc";

#### mode
switch($mode){
	case "save":

		if($_FILES["file1"]["size"]){
			#------------------------------------------
			$path="../../public/ez_board/button";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file1"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file1"]["name"];	//파일의 이름
			$fname_size=$_FILES["file1"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file1"]["type"];		//파일의 type
			$filename="btn_write";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile1 = $upfile;
		}
		$upfile1 = ($upfile1)? $upfile1 : $btn_write;

		if($_FILES["file2"]["size"]){
			#------------------------------------------
			$path="../../public/ez_board/button";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file2"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file2"]["name"];	//파일의 이름
			$fname_size=$_FILES["file2"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file2"]["type"];		//파일의 type
			$filename="btn_edit";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile2 = $upfile;
		}
		$upfile2 = ($upfile2)? $upfile2 : $btn_edit;

		if($_FILES["file3"]["size"]){
			#------------------------------------------
			$path="../../public/ez_board/button";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file3"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file3"]["name"];	//파일의 이름
			$fname_size=$_FILES["file3"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file3"]["type"];		//파일의 type
			$filename="btn_delete";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile3 = $upfile;
		}
		$upfile3 = ($upfile3)? $upfile3 : $btn_delete;

		if($_FILES["file4"]["size"]){
			#------------------------------------------
			$path="../../public/ez_board/button";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file4"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file4"]["name"];	//파일의 이름
			$fname_size=$_FILES["file4"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file4"]["type"];		//파일의 type
			$filename="btn_list";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile4 = $upfile;
		}
		$upfile4 = ($upfile4)? $upfile4 : $btn_list;

		if($_FILES["file5"]["size"]){
			#------------------------------------------
			$path="../../public/ez_board/button";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file5"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file5"]["name"];	//파일의 이름
			$fname_size=$_FILES["file5"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file5"]["type"];		//파일의 type
			$filename="btn_submit";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile5 = $upfile;
		}
		$upfile5 = ($upfile5)? $upfile5 : $btn_submit;

		if($_FILES["file6"]["size"]){
			#------------------------------------------
			$path="../../public/ez_board/button";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file6"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file6"]["name"];	//파일의 이름
			$fname_size=$_FILES["file6"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file6"]["type"];		//파일의 type
			$filename="btn_cancel";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile6 = $upfile;
		}
		$upfile6 = ($upfile6)? $upfile6 : $btn_cancel;

		if($_FILES["file7"]["size"]){
			#------------------------------------------
			$path="../../public/ez_board/button";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file7"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file7"]["name"];	//파일의 이름
			$fname_size=$_FILES["file7"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file7"]["type"];		//파일의 type
			$filename="btn_find";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile7 = $upfile;
		}
		$upfile7 = ($upfile7)? $upfile7 : $btn_find;

		if($_FILES["file8"]["size"]){
			#------------------------------------------
			$path="../../public/ez_board/button";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file8"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file8"]["name"];	//파일의 이름
			$fname_size=$_FILES["file8"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file8"]["type"];		//파일의 type
			$filename="btn_reply";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile8 = $upfile;
		}
		$upfile8 = ($upfile8)? $upfile8 : $btn_reply;

		$fp=fopen($inc_file, "w");	//파일 쓰기모드로 열기, 파일이 있다면 overwirte

		$config ="<?\n";
		$config .="##-------------------------------------------\n";
		$config .="##주의! (이 파일을 수동으로 조작하지 마세요.)\n";
		$config .="##-------------------------------------------\n";

		$config .="\$BTN_WRITE='$upfile1';\n";
		$config .="\$BTN_EDIT='$upfile2';\n";
		$config .="\$BTN_DELETE='$upfile3';\n";
		$config .="\$BTN_LIST='$upfile4';\n";
		$config .="\$BTN_SUBMIT='$upfile5';\n";
		$config .="\$BTN_CANCEL='$upfile6';\n";
		$config .="\$BTN_FIND='$upfile7';\n";
		$config .="\$BTN_REPLY='$upfile8';\n";

		$config .="?";
		$config .=">";


		if(!fwrite($fp,$config)){
			error("환경설정파일을 생성하던 중 예기치 못한 에러가 발생하였습니다. 관리자에게 문의하여 주시기 바랍니다.");exit;
		}
		fclose($fp);

		redirect2("?");
		exit;
		break;


	case "file_drop":
		@unlink("../../public/ez_board/button/$filename");
		back();exit;


	default:
		require($inc_file);
}
//-------------------------------------------------------------------------------
?>

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

		<form name=fmData method=post enctype="multipart/form-data">
		<input type=hidden name=mode value='save'>
		<input type=hidden name=btn_write value='<?=$BTN_WRITE?>'>
		<input type=hidden name=btn_edit value='<?=$BTN_EDIT?>'>
		<input type=hidden name=btn_delete value='<?=$BTN_DELETE?>'>
		<input type=hidden name=btn_list value='<?=$BTN_LIST?>'>
		<input type=hidden name=btn_submit value='<?=$BTN_SUBMIT?>'>
		<input type=hidden name=btn_cancel value='<?=$BTN_CANCEL?>'>
		<input type=hidden name=btn_reply value='<?=$BTN_REPLY?>'>


		<tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td  class="subject" width="15%">글쓰기<r/td>
          <td>
			<?
			$filePath = "../../public/ez_board/button/$BTN_WRITE";
			@$fileSize = filesize($filePath);
			if($filePath!="../../public/ez_board/button/" && $fileSize):
			?>
				<span class="btn_pack small bold"><a href="#" onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?mode=file_drop&mode2=filename&filename=<?=$BTN_WRITE?>'}""> 파일삭제 </a></span>&nbsp;&nbsp;&nbsp;

				<img src="<?=$filePath?>" align="absmiddle">
				<a class=soft href="../../include/download.php?file=<?=$BTN_WRITE?>&orgin_file_name=<?=$BTN_WRITE?>&dir=public/ez_board/button" onFocus="blur(this)"><?=$BTN_WRITE?> (<?=ceil($fileSize/1024)?>KB)</a>
			<?else:?>
				<input class=box type="file" name="file1" size=40>

			<?endif;?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td  class="subject">수정<r/td>
          <td>
			<?
			$filePath = "../../public/ez_board/button/$BTN_EDIT";
			@$fileSize = filesize($filePath);
			if($filePath!="../../public/ez_board/button/" && $fileSize):
			?>
				<span class="btn_pack small bold"><a href="#" onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?mode=file_drop&mode2=filename&filename=<?=$BTN_EDIT?>'}""> 파일삭제 </a></span>&nbsp;&nbsp;&nbsp;

				<img src="<?=$filePath?>" align="absmiddle">
				<a class=soft href="../../include/download.php?file=<?=$BTN_EDIT?>&orgin_file_name=<?=$BTN_EDIT?>&dir=public/ez_board/button" onFocus="blur(this)"><?=$BTN_EDIT?> (<?=ceil($fileSize/1024)?>KB)</a>
			<?else:?>
				<input class=box type="file" name="file2" size=40>

			<?endif;?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td  class="subject">삭제<r/td>
          <td>
			<?
			$filePath = "../../public/ez_board/button/$BTN_DELETE";
			@$fileSize = filesize($filePath);
			if($filePath!="../../public/ez_board/button/" && $fileSize):
			?>
				<span class="btn_pack small bold"><a href="#" onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?mode=file_drop&mode2=filename&filename=<?=$BTN_DELETE?>'}""> 파일삭제 </a></span>&nbsp;&nbsp;&nbsp;

				<img src="<?=$filePath?>" align="absmiddle">
				<a class=soft href="../../include/download.php?file=<?=$BTN_DELETE?>&orgin_file_name=<?=$BTN_DELETE?>&dir=public/ez_board/button" onFocus="blur(this)"><?=$BTN_DELETE?> (<?=ceil($fileSize/1024)?>KB)</a>
			<?else:?>
				<input class=box type="file" name="file3" size=40>

			<?endif;?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td  class="subject">글목록<r/td>
          <td>
			<?
			$filePath = "../../public/ez_board/button/$BTN_LIST";
			@$fileSize = filesize($filePath);
			if($filePath!="../../public/ez_board/button/" && $fileSize):
			?>
				<span class="btn_pack small bold"><a href="#" onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?mode=file_drop&mode2=filename&filename=<?=$BTN_LIST?>'}""> 파일삭제 </a></span>&nbsp;&nbsp;&nbsp;

				<img src="<?=$filePath?>" align="absmiddle">
				<a class=soft href="../../include/download.php?file=<?=$BTN_LIST?>&orgin_file_name=<?=$BTN_LIST?>&dir=public/ez_board/button" onFocus="blur(this)"><?=$BTN_LIST?> (<?=ceil($fileSize/1024)?>KB)</a>
			<?else:?>
				<input class=box type="file" name="file4" size=40>

			<?endif;?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>


        <tr>
          <td  class="subject">확인<r/td>
          <td>
			<?
			$filePath = "../../public/ez_board/button/$BTN_SUBMIT";
			@$fileSize = filesize($filePath);
			if($filePath!="../../public/ez_board/button/" && $fileSize):
			?>
				<span class="btn_pack small bold"><a href="#" onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?mode=file_drop&mode2=filename&filename=<?=$BTN_SUBMIT?>'}""> 파일삭제 </a></span>&nbsp;&nbsp;&nbsp;
				<img src="<?=$filePath?>" align="absmiddle">
				<a class=soft href="../../include/download.php?file=<?=$BTN_SUBMIT?>&orgin_file_name=<?=$BTN_SUBMIT?>&dir=public/ez_board/button" onFocus="blur(this)"><?=$BTN_SUBMIT?> (<?=ceil($fileSize/1024)?>KB)</a>
			<?else:?>
				<input class=box type="file" name="file5" size=40>

			<?endif;?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td  class="subject">취소<r/td>
          <td>
			<?
			$filePath = "../../public/ez_board/button/$BTN_CANCEL";
			@$fileSize = filesize($filePath);
			if($filePath!="../../public/ez_board/button/" && $fileSize):
			?>
				<span class="btn_pack small bold"><a href="#" onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?mode=file_drop&mode2=filename&filename=<?=$BTN_CANCEL?>'}""> 파일삭제 </a></span>&nbsp;&nbsp;&nbsp;

				<img src="<?=$filePath?>" align="absmiddle">
				<a class=soft href="../../include/download.php?file=<?=$BTN_CANCEL?>&orgin_file_name=<?=$BTN_CANCEL?>&dir=public/ez_board/button" onFocus="blur(this)"><?=$BTN_CANCEL?> (<?=ceil($fileSize/1024)?>KB)</a>
			<?else:?>
				<input class=box type="file" name="file6" size=40>

			<?endif;?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td  class="subject">검색<r/td>
          <td>
			<?
			$filePath = "../../public/ez_board/button/$BTN_FIND";
			@$fileSize = filesize($filePath);
			if($filePath!="../../public/ez_board/button/" && $fileSize):
			?>
				<span class="btn_pack small bold"><a href="#" onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?mode=file_drop&mode2=filename&filename=<?=$BTN_FIND?>'}""> 파일삭제 </a></span>&nbsp;&nbsp;&nbsp;

				<img src="<?=$filePath?>" align="absmiddle">
				<a class=soft href="../../include/download.php?file=<?=$BTN_FIND?>&orgin_file_name=<?=$BTN_FIND?>&dir=public/ez_board/button" onFocus="blur(this)"><?=$BTN_FIND?> (<?=ceil($fileSize/1024)?>KB)</a>
			<?else:?>
				<input class=box type="file" name="file7" size=40>

			<?endif;?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td class="subject">댓글저장<r/td>
          <td>
			<?
			$filePath = "../../public/ez_board/button/$BTN_REPLY";
			@$fileSize = filesize($filePath);
			if($filePath!="../../public/ez_board/button/" && $fileSize):
			?>
				<span class="btn_pack small bold"><a href="#" onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?mode=file_drop&mode2=filename&filename=<?=$BTN_REPLY?>'}""> 파일삭제 </a></span>&nbsp;&nbsp;&nbsp;

				<img src="<?=$filePath?>" align="absmiddle">
				<a class=soft href="../../include/download.php?file=<?=$BTN_REPLY?>&orgin_file_name=<?=$BTN_REPLY?>&dir=public/ez_board/button" onFocus="blur(this)"><?=$BTN_REPLY?> (<?=ceil($fileSize/1024)?>KB)</a>
			<?else:?>
				<input class=box type="file" name="file8" size=40>

			<?endif;?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>


        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>

        <tr>
		<td colspan=2>

		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" rcellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
			   <td>
				<span class="btn_pack medium bold"><a href="#" onClick="document.fmData.submit()"> 저장 </a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="#" onClick="location.href='list_bbs.php?<?=$sessLink?>'"> 리스트 </a></span>
			   </td>
		    </tr>
		  </table>
		  <!-- Button End------------------------------------------------>


		</td>
	</tr>

    <tr><td height=10></td></tr>

      </table>

</form>


	<!--내용이 들어가는 곳 끝-->



<!-- Copyright -->
<?include_once("../bottom.html");?>
