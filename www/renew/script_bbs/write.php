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
		$naming = "bpcu";

		if($_FILES["file1"]["size"]){
			#------------------------------------------
			$path="public/bbs_files";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file1"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file1"]["name"];	//파일의 이름
			$fname_size=$_FILES["file1"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file1"]["type"];		//파일의 type
			$filename=$naming . time();		//파일이름 작명
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

		$reg_date = time();
		$subject = enVars($subject);
		$content = addslashes($content);

	    $subject=antiInjection($subject);
	    $content=antiInjection($content);
	    $name=antiInjection($name);
	    $pwd=antiInjection($pwd);
	    $email=antiInjection($email);


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
			  edit_date = '$reg_date'
		   where id_no='$id_no' and  (pwd=password('$pwd') or (id<>'' and id='$id'))
		   limit 1
		";

		$sql = ($id_no)?$sqlModify :  $sqlInsert;
		$goUrl =(!$id_no)? "?bmode=list&amp;bid=$bid": "?bmode=read&amp;bid=$bid&amp;id_no=$id_no";
		$dbo->query($sql);

		redirect2($goUrl . "&amp;" . $_SESSION["link"]);exit;


	case "file_drop":
		$sql = "update $table set $mode2 ='' where id_no=$id_no  limit 1";
		$dbo->query($sql);
		@unlink("public/bbs_files/$filename");
		back();exit;

	default:
		$sql = "select * from $table where id_no = $id_no";
		$dbo->query($sql);
		$rs = $dbo->next_record();
}

?>
	<script type="text/javascript" src="../include/function.js"></script>
	<script type="text/javascript" src="../include/form_check.js"></script>

	<script language="JavaScript">
	<!--
	function chkForm(){
		var fm = document.fmData;

		if(check_blank(fm.name,'이름을',0)=='wrong'){return  }
		if(check_blank(fm.subject,'제목',0)=='wrong'){return }

		oEditors.getById["content"].exec("update_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.

		try {
			elClickedObj.form.submit();
		} catch(e) {}

		if(document.getElementById("content").value==""){
			//alert('본문을 입력하세요');
			//return;
		}


		<?if(!$_SESSION[sessMember][id]){?>
		if(check_blank(fm.pwd,'비밀번호를',0)=='wrong'){return }
		<?}?>

		fm.submit();

	}

	function cancel(){
		if(confirm("입력하시던 내용을 저장하지 않고 글 목록으로 돌아가시겠습니까?")){
			location.href="?bmode=list&amp;<?=$_SESSION[link]?>";
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

	<?@include("public/inc/bbs_".$bid . "_top.inc");?>


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
				<th>작성자</th>
				<td class="topline"><input type="text" size="30" name="name" value="<?=($rs[name])?$rs[name]:$_SESSION[sessMember][name]?>" size="40" maxlength="20" class="box" /></td>
			</tr>

			<?if($SET_ASSORT!="3"){?>
			<tr>
				<th>E-mail</th>
				<td><input type="text" size="30"name="email" value="<?=($rs[email])? $rs[email] : $_SESSION[sessMember][email]?>" size="40" maxlength="50"  class="box" /></td>
			</tr>
			<?}?>
			<tr>
				<th >제목</th>
				<td>
					<input type="text"name="subject" value="<?=stripslashes($rs[subject])?>" maxlength="150" style="width:80%" class="box"/>
					<?if($SET_SECRET=="T"){?>
					<input type="checkBox" name="secret" id="secret" value="1" class="border0" <?=($rs[secret])?"checked":""?>> <label for="secret">비밀글</label>
					<?}?>
				</td>
			</tr>
			<tr>
				<td colspan="2">

				<!-- Html Editor Begin -->
				<textarea name="content" id="content" rows="10" cols="100" style="width:100%; height:300px; display:none;"><?=stripslashes($rs[content])?></textarea>
				<script type="text/javascript" src="./se2/js/HuskyEZCreator.js" charset="utf-8"></script>
				<script type="text/javascript" src="./include/smart_editor.js"></script><!--sSkinURI 경로 수정 필요, SE2B_Configuration.js-->
				<!-- Html Editor End -->

				</td>
			</tr>

			<?if($SET_FILE=="T"){?>
			<tr>
				<th>파일</th>
				<td>
					<?if($rs[filename]){
					@$fileSize = filesize("./public/bbs_files/${rs[filename]}");
					?>
					<?=$rs[filename_real]?> (<?=ceil($fileSize/1024)?>KB) <input type="button" class="button" value='파일삭제' onclick="if(confirm('파일을 삭제하시겠습니까?')){location.href='<?=SELF?>?bmode=write&amp;id_no=<?=$rs[id_no]?>&amp;mode=file_drop&amp;mode2=filename&amp;filename=<?=$rs[filename]?>'}">
					<?}else{?>
					<input type="file" class="box" name="file1" size="60" />
					<?}?>
				</td>
			</tr>
			<tr>
				<th>파일</th>
				<td>
					<?if($rs[filename2]){
					@$fileSize = filesize("./public/bbs_files/${rs[filename2]}");
					?>
					<?=$rs[filename2_real]?> (<?=ceil($fileSize/1024)?>KB) <input type="button" class="button" value='파일삭제' onclick="if(confirm('파일을 삭제하시겠습니까?')){location.href='<?=SELF?>?bmode=write&amp;id_no=<?=$rs[id_no]?>&amp;mode=file_drop&amp;mode2=filename2&amp;filename=<?=$rs[filename2]?>'}">
					<?}else{?>
					<input type="file" class="box" name="file2" size="60" />
					<?}?>
				</td>
			</tr>
			<tr>
				<th>파일</th>
				<td>
					<?if($rs[filename3]){
					@$fileSize = filesize("./public/bbs_files/${rs[filename3]}");
					?>
					<?=$rs[filename3_real]?> (<?=ceil($fileSize/1024)?>KB) <input type="button" class="button" value=' 파일삭제 ' onclick="if(confirm('파일을 삭제하시겠습니까?')){location.href='<?=SELF?>?bmode=write&amp;id_no=<?=$rs[id_no]?>&amp;mode=file_drop&amp;mode2=filename3&amp;filename=<?=$rs[filename3]?>'}">
					<?}else{?>
					<input type="file" class="box" name="file3" size="60" />
					<?}?>
				</td>
			</tr>
			<?}?>

			<?if(!$_SESSION[sessMember][id]){?>
			<tr>
				<th>비밀번호</th>
				<td><input type="password" class="box" size="20" name="pwd" maxlength="20" /></td>
			</tr>
			<?}?>
		</tbody>
	</table>

	<!-- //bbswrite -->

	<!-- bbswrite-button -->
	<div class="right">
		<a href="javascript:chkForm()"><img src="public/ez_board/button/<?=$BTN_SUBMIT?>" class="border0 middle"/></a>
		<a href="#" onclick="cancel()"><img src="public/ez_board/button/<?=$BTN_CANCEL?>" /></a>
		<a href="?bmode=list&amp;bid=<?=$bid?>"><img src="public/ez_board/button/<?=$BTN_LIST?>" /></a>
	</div>
	<!-- //bbswrite-button -->
	</form>

	<?@include("public/inc/bbs_".$bid . "_bottom.inc");?>

</div><!-- <div id="ez_conents_wrap"> -->