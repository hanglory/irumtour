<?
include_once("common.php");



#### 기본 정보
$table = "ez_bbs";
$table2 = "ez_bbs_comment";
$column = "*";
$maxFileSize = intval(substr(ini_get(upload_max_filesize),0,-1));
$id = $_SESSION["sessMember"]["id"];

#### mode
switch($mode){
	case "save":

		if($_FILES["file1"]["size"]){
			#------------------------------------------
			$path="public/bbs_files";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file1"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file1"]["name"];	//파일의 이름
			$fname_size=$_FILES["file1"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file1"]["type"];		//파일의 type
			$naming . time();		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("include/file_upload.php");
			$upfile1 = $upfile;
			$upfile1_real = $_FILES["file1"]["name"];
			$upfileQuery1 = ($upfile)? "filename = '$upfile1',filename_real='$upfile1_real', ":"" ;
		}

		if($_FILES["file2"]["size"]){
			#------------------------------------------
			$path="public/bbs_files";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file2"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file2"]["name"];	//파일의 이름
			$fname_size=$_FILES["file2"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file2"]["type"];		//파일의 type
			$filename=$naming . time() . "_2";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("include/file_upload.php");
			$upfile2 = $upfile;
			$upfile2_real = $_FILES["file2"]["name"];
			$upfileQuery2 = ($upfile)? "filename2 = '$upfile2',filename2_real='$upfile2_real', ":"" ;
		}

		if($_FILES["file3"]["size"]){
			#------------------------------------------
			$path="public/bbs_files";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file3"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file3"]["name"];	//파일의 이름
			$fname_size=$_FILES["file3"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file3"]["type"];		//파일의 type
			$filename=$naming . time() . "_3";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("include/file_upload.php");
			$upfile3 = $upfile;
			$upfile3_real = $_FILES["file3"]["name"];
			$upfileQuery3 = ($upfile)? "filename3 = '$upfile3',filename3_real='$upfile3_real', ":"" ;
		}

		$subject = enVars($subject);

		$reg_date = time();

		$sqlInsert="
		   insert into ez_bbs (
			  bid,
			  subject,
			  content,
			  name,
			  id,
			  pwd,
			  email,
			  ip,
			  cnt,
			  cnt_comment,
			  secret,
			  notice,
			  video_tag,
			  filename,
			  filename2,
			  filename3,
			  filename_real,
			  filename2_real,
			  filename3_real,
			  reg_date,
			  edit_date
		  ) values (
			  '$bid',
			  '$subject',
			  '$content',
			  '$name',
			  '$id',
			  password('$pwd'),
			  '$email',
			  '$ip',
			  '$cnt',
			  '$cnt_comment',
			  '$secret',
			  '$notice',
			  '$video_tag',
			  '$upfile1',
			  '$upfile2',
			  '$upfile3',
			  '$upfile1_real',
			  '$upfile2_real',
			  '$upfile3_real',
			  '$reg_date',
			  '$edit_date'
		)";


		$sqlModify="
		   update ez_bbs set
			  $upfileQuery1
			  $upfileQuery2
			  $upfileQuery3
			  subject = '$subject',
			  content = '$content',
			  name = '$name',
			  email = '$email',
			  secret = '$secret',
			  notice = '$notice',
			  video_tag = '$video_tag',
			  edit_date = '$reg_date'
		   where id_no='$id_no' and  (pwd=password('$pwd') or (id<>'' and id='$id'))  limit 1
		";

		$sql = ($id_no)?$sqlModify :  $sqlInsert;
		$goUrl =(!$id_no)? "?bmode=list&bid=$bid": "?bmode=read&bid=$bid&id_no=$id_no";
		$dbo->query($sql);

		redirect2($goUrl . "&" . $_SESSION["link"]);exit;


	case "file_drop":
		$sql = "update $table set $mode2 ='' where id_no=$id_no";
		$dbo->query($sql);
		@unlink("public/bbs_files/$filename");
		redirect2("?id_no=$id_no&$_SESSION[link]");exit;

	default:
		$sql = "select * from $table where id_no = $id_no";
		$dbo->query($sql);
		$rs = $dbo->next_record();
}

?>
	<script type="text/javascript" src="/include/function.js"></script>
	<script type="text/javascript" src="/include/form_check.js"></script>

	<script language="JavaScript">
	<!--
	function chkForm(){
		var fm = document.fmData;

		if(check_blank(fm.name,'Name',0)=='wrong'){return false }
		if(check_blank(fm.subject,'Subject',0)=='wrong'){return false}

		oEditors.getById["content"].exec("update_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.

		try {
			elClickedObj.form.submit();
		} catch(e) {}

		if(document.getElementById("content").value==""){
			alert('Please enter');
			return false;
		}

		<?if(!$_SESSION[sessMember][id]){?>
		if(check_blank(fm.pwd,'Passowrd',0)=='wrong'){return false}
		<?}?>

	}

	function cancel(){
		if(confirm("Would you like to go back without saving?")){
			location.href="?bmode=list&<?=$_SESSION[link]?>";
		}
	}
	//-->
	</script>


	<link rel="stylesheet" type="text/css" href="./script_bbs/css/ez_board.css" />
	<link rel="stylesheet" type="text/css" href="./script_bbs/css/ez_default.css" />
	<!--[if IE]>
	<link rel="stylesheet" type="text/css" href="./script_bbs/css/ez_board_ie.css" />
	<![endif]-->


<div id="ez_conents_wrap">

	<?include("public/inc/bbs_".$bid . "_top.inc");?>


	<!-- bbswrite -->

	<form name="fmData" onSubmit="return chkForm(document.fmData)" method="post" enctype="multipart/form-data">
	<input type="hidden" name="mode" value='save'>
	<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
	<input type="hidden" name="bid" value='<?=$bid?>'>

	<table id="bbswrite" cellspacing="0" summary="">
		<colgroup>
			<col width="120" /><col width="" />
		</colgroup>
		<tbody>
			<tr>
				<th>Name</th>
				<td class="topline"><input type="text" size="30" name="name" value="<?=($rs[name])?$rs[name]:$_SESSION[sessMember][id]?>" size="40" maxlength="20" /></td>
			</tr>

			<?if($SET_ASSORT!="3"){?>
			<tr>
				<th>E-mail</th>
				<td><input type="text" size="30"name="email" value="<?=($rs[email])? $rs[email] : $_SESSION[sessMember][email]?>" size="40" maxlength="50"/></td>
			</tr>
			<?}?>
			<tr>
				<th >Subject</th>
				<td>
					<input type="text"name="subject" value="<?=stripslashes($rs[subject])?>" maxlength="150" style="width:80%"/>
					<?if($SET_SECRET=="T"){?>
					<input type="checkBox" name="secret" id="secret" value="1" class="border0" <?=($rs[secret])?"checked":""?>> <label for="secret">Secret</label>
					<?}?>
				</td>
			</tr>
			<tr>
				<td colspan="2">

				<!-- Html Editor Begin -->
				<label><textarea id="contents" name="contents"><?=stripslashes($rs[content])?></textarea></label>
				<script type="text/javascript">
				var myeditor = new cheditor();              // 에디터 개체를 생성합니다.
				myeditor.config.editorHeight = '300px';     // 에디터 세로폭입니다.
				myeditor.config.editorWidth = '100%';        // 에디터 가로폭입니다.
				myeditor.inputForm = 'contents';             // textarea의 ID 이름입니다.
				myeditor.run();                             // 에디터를 실행합니다.
				</script>
				<!-- Html Editor End -->

			   <label><textarea id="content" name="content" style="display:none"><?=stripslashes($rs[content])?></textarea></label>

				</td>
			</tr>
			<tr>
				<th>Video tag</th>
				<td>
				<textarea name="video_tag" cols="80" rows="5" class="box" style="width:100%"><?=$rs[video_tag]?></textarea>
				</td>
			</tr>

			<?if($SET_FILE=="T"){?>
			<tr>
				<th>File</th>
				<td>
					<?if($rs[filename]){
					@$fileSize = filesize("../../public/bbs_files/${rs[filename]}");
					?>
					<?=$rs[filename_real]?> (<?=ceil($fileSize/1024)?>KB) <input type="button" class="button" value='file deleted' onClick="if(confirm('Would you like to delete the file?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename&filename=<?=$rs[filename]?>'}">
					<?}else{?>
					<input type="file" class="input-file" name="file1" size="60" />
					<?}?>
				</td>
			</tr>
			<tr>
				<th>File</th>
				<td>
					<?if($rs[filename2]){
					@$fileSize = filesize("../../public/bbs_files/${rs[filename2]}");
					?>
					<?=$rs[filename2_real]?> (<?=ceil($fileSize/1024)?>KB) <input type="button" class="button" value='file deleted' onClick="if(confirm('Would you like to delete the file?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename2&filename=<?=$rs[filename2]?>'}">
					<?}else{?>
					<input type="file" class="input-file" name="file2" size="60" />
					<?}?>
				</td>
			</tr>
			<tr>
				<th>File</th>
				<td>
					<?if($rs[filename3]){
					@$fileSize = filesize("../../public/bbs_files/${rs[filename3]}");
					?>
					<?=$rs[filename3_real]?> (<?=ceil($fileSize/1024)?>KB) <input type="button" class="button" value='file deleted' onClick="if(confirm('Would you like to delete the file?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename3&filename=<?=$rs[filename3]?>'}">
					<?}else{?>
					<input type="file" class="input-file" name="file3" size="60" />
					<?}?>
				</td>
			</tr>
			<?}?>

			<?if(!$_SESSION[sessMember][id]){?>
			<tr>
				<th>Password</th>
				<td><input type="password" class="input-file" size="20" name="pwd" maxlength="20" /></td>
			</tr>
			<?}?>
		</tbody>
	</table>

	<!-- //bbswrite -->

	<!-- bbswrite-button -->
	<div class="right">
		<input type="image" src="public/ez_board/button/<?=$BTN_SUBMIT?>" class="border0"/>
		<a href="#" onclick="cancel()"><img src="public/ez_board/button/<?=$BTN_CANCEL?>" /></a>
		<a href="?bmode=list&bid=<?=$bid?>"><img src="public/ez_board/button/<?=$BTN_LIST?>" /></a>
	</div>
	<!-- //bbswrite-button -->
	</form>

	<?include("public/inc/bbs_".$bid . "_bottom.inc");?>

</div><!-- <div id="ez_conents_wrap"> -->