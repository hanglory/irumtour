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
switch($mode){
	case "save":

		if($_FILES["file1"]["size"]){
			#------------------------------------------
			$path="../../public/bbs_files";	//업로드할 파일의 경로
			$maxsize=30 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file1"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file1"]["name"];	//파일의 이름
			$fname_size=$_FILES["file1"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file1"]["type"];		//파일의 type
			$filename=$naming . time();		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile1 = $upfile;
			$upfile1_real = $_FILES["file1"]["name"];
			$upfileQuery1 = ($upfile)? "filename = '$upfile1',filename_real='$upfile1_real', ":"" ;
		}

		if($_FILES["file2"]["size"]){
			#------------------------------------------
			$path="../../public/bbs_files";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file2"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file2"]["name"];	//파일의 이름
			$fname_size=$_FILES["file2"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file2"]["type"];		//파일의 type
			$filename=$naming . time() . "_2";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile2 = $upfile;
			$upfile2_real = $_FILES["file2"]["name"];
			$upfileQuery2 = ($upfile)? "filename2 = '$upfile2',filename2_real='$upfile2_real', ":"" ;
		}

		if($_FILES["file3"]["size"]){
			#------------------------------------------
			$path="../../public/bbs_files";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file3"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file3"]["name"];	//파일의 이름
			$fname_size=$_FILES["file3"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file3"]["type"];		//파일의 type
			$filename=$naming . time() . "_3";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile3 = $upfile;
			$upfile3_real = $_FILES["file3"]["name"];
			$upfileQuery3 = ($upfile)? "filename3 = '$upfile3',filename3_real='$upfile3_real', ":"" ;
		}

		$subject = enVars($subject);

		if($reg_date){
			$arr = explode(".",$reg_date);
			$reg_date = mktime(Date("H"),Date("s"),Date("i"),$arr[1],$arr[2],$arr[0]);
		}
		else $reg_date = time();
		$edit_date = time();
		$id = $_SESSION["sessLogin"]["id"];

		$sqlInsert="
		   insert into ez_bbs (
              cp_id,
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
              bit_cp,
			  reg_date,
			  edit_date
		  ) values (
			  '$CP_ID',
              '$bid',
			  '$subject',
			  '$content',
			  '$name',
			  '$id',
			  '$pwd',
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
			  '$bit_cp',
              '$reg_date',
			  '$edit_date'
		)";


		$sqlModify="
		   update ez_bbs set
			  $upfileQuery1
			  $upfileQuery2
			  $upfileQuery3
			  bit_cp = '$bit_cp',
              subject = '$subject',
			  content = '$content',
			  name = '$name',
			  pwd = '$pwd',
			  email = '$email',
			  secret = '$secret',
			  notice = '$notice',
			  edit_date = '$edit_date'
		   where id_no='$id_no'
           limit 1
		";

		$sql = ($id_no)?$sqlModify :  $sqlInsert;

		$goUrl =(!$id_no)? "read_bbs.php?sp=new&bid=$bid": "read_bbs.php?id_no=$id_no&bid=$bid";
		$dbo->query($sql);

		redirect2($goUrl . "&" . $_SESSION["link"]);exit;


	case "drop":
		for($i = 0; $i < count($check);$i++){
			$sql = "select *  from $table where id_no=$check[$i]";
			$dbo->query($sql);
			$rs=$dbo->next_record();
			if($rs[filename]) @unlink("../../public/bbs_files/$rs[filename]");
			if($rs[filename2]) @unlink("../../public/bbs_files/$rs[filename2]");
			if($rs[filename3]) @unlink("../../public/bbs_files/$rs[filename3]");

			$sql = "delete from $table where id_no = $check[$i]";
			$dbo->query($sql);

			$sql = "delete from $table2 where doc_no = $check[$i]";
			$dbo->query($sql);
		}

		redirect2("list_bbs.php?bid=$bid&$_SESSION[link]");exit;


    case "bit_cp":
        for($i = 0; $i < count($check);$i++){
            $sql = "update $table set bit_cp=$bit_cp where id_no = $check[$i]";
            $dbo->query($sql);
        }
        back();exit;

	case "file_drop":
		$sql = "update $table set $mode2 ='' where id_no=$id_no";
		$dbo->query($sql);
		@unlink("../../public/bbs_files/$filename");
		redirect2("?id_no=$id_no&$_SESSION[link]");exit;

	default:
		$sql = "select * from $table where id_no = $id_no";
		$dbo->query($sql);
		$rs = $dbo->next_record();
}

//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(){
	var fm = document.fmData;
	//<?if($BBS_ASSORT!=3):?>if(fm.tmp_category.value==""){alert('최소한 1개 이상의 분류를 선택하셔야 합니다'); return }<?endif;?>

	if(check_blank(fm.name,'이름을',0)=='wrong'){return }
	if(check_blank(fm.subject,'제목',0)=='wrong'){return }

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
//-->
</script>
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


		<br/>


		<!--내용이 들어가는 곳 시작-->


      <table border="0" cellspacing="1" cellpadding="3" width="750">

		<form name="fmData" onSubmit="return chkForm(document.fmData)" method=post enctype="multipart/form-data">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="bid" value='<?=$bid?>'>
		<tr><td colspan="2" bgcolor='#5E90AE' height="2"></td></tr>


<?if($CATEGORY){?>
        <tr>
          <td  class="subject">소분류</td>
          <td>
            <select name=category class=select>
				<option value=""></option>
				<?=option_str($CATEGORY,$CATEGORY,$rs[category])?>
			</select>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
<?}?>

		<tr>
          <td class="subject" width="15%">*이름</td>
          <td>
            <input class=box type="text" name="name" value="<?=($rs[name])?$rs[name]:$_SESSION[sessLogin][name]?>" size=40 maxlength=20 >

            <label><input type=checkBox name="notice" value=1 <?=($rs[notice])?"checked":""?>> 공지기능</label>
            <label><input type=checkBox name="secret" value=1 <?=($rs[secret])?"checked":""?>> 비밀글</label>

            <label><input type=checkBox name="bit_cp" value=1 <?=($rs[bit_cp])?"checked":""?>> 파트너 감추기</label>

          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
        <tr>
          <td  class="subject">Email</td>
          <td>
            <input class=box type="text" name="email" value="<?=($rs[email])? $rs[email] : $_SESSION[sessLogin][email]?>" size="40" maxlength="50">
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>


        <tr>
          <td  class="subject">*제목</td>
          <td>
            <input class=box type="text" name="subject" size="70" value="<?=stripslashes($rs[subject])?>" maxlength="200">
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td colspan=2 >

			<!-- Html Editor Begin -->
            <?
            $board_height = 500;
            $editors_id="";
            include("../jodit.php");
            ?>
         

			<!-- <script type="text/javascript" src="../../se2/js/HuskyEZCreator.js" charset="utf-8"></script>
			<script type="text/javascript" src="../../include/smart_editor.js"></script> -->
			<!-- Html Editor End -->

          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>


	<?if($rs[filename]):
		@$fileSize = filesize("../../public/bbs_files/${rs[filename]}");
		?>
        <tr>
          <td  class="subject">파일<r/td>
          <td>
			<input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename&filename=<?=$rs[filename]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename]?>&orgin_file_name=<?=$rs[filename_real]?>&dir=public/bbs_files" onFocus="blur(this)"><?=$rs[filename_real]?> (<?=ceil($fileSize/1024)?>KB)</a>

          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?else:?>
		<tr>
          <td  class="subject">파일 업로드</td>
          <td>
            <input class=box type="file" name="file1" size=40>
			<font color="orange">Alert</font></b> <font color="#666666">: <?=$maxFileSize?>MB 이상은 업로드 되지 않습니다.</font>
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
			<input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename2&filename=<?=$rs[filename2]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename2]?>&orgin_file_name=<?=$rs[filename2_real]?>&dir=public/bbs_files" onFocus="blur(this)"><?=$rs[filename2_real]?> (<?=ceil($fileSize/1024)?>KB)</a>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?else:?>
		<tr>
          <td  class="subject">파일 업로드</td>
          <td>
            <input class=box type="file" name="file2" size=40>
			<font color="orange">Alert</font></b> <font color="#666666">: <?=$maxFileSize?>MB 이상은 업로드 되지 않습니다.</font>
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
			<input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename3&filename=<?=$rs[filename3]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename3]?>&orgin_file_name=<?=$rs[filename3_real]?>&dir=public/bbs_files" onFocus="blur(this)"><?=$rs[filename3_real]?> (<?=ceil($fileSize/1024)?>KB)</a>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?else:?>
		<tr>
          <td  class="subject">파일 업로드</td>
          <td>
            <input class=box type="file" name="file3" size=40>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?endif;?>

	<?if(!$rs[id] && $rs[id_no]):?>
        <tr>
          <td  class="subject">파일</td>
          <td>
			<input type="text" name="pwd" value="<?=$rs[pwd]?>" size="10" maxlength="30" class="box">
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?endif;?>

        <!-- <?if($rs[id_no]){?>
		<tr>
          <td  class="subject">날짜</td>
          <td>
            <input class="box dateinput" type="text" name="reg_date" size="10" value="<?=date("Y/m/d",$rs[reg_date])?>" maxlength="10">
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
		<?}?> -->

        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>

        <tr>
		<td colspan=2>

		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" rcellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
			   <td>
				<span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="#" onClick="location.href='list_bbs.php?<?=$_SESSION["link"]?>'"> 리스트 </a></span>
			   </td>
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
