<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_center";
$MENU = "member";
$TITLE = $flag . " 관리";



#### mode
if($mode=="save"){

	if($_FILES["file1"]["size"]){

		#------------------------------------------
		$path="../../public/center";	//업로드할 파일의 경로
		$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
		$fname=$_FILES["file1"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
		$fname_name=$_FILES["file1"]["name"];	//파일의 이름
		$fname_size=$_FILES["file1"]["size"];		//파일의 사이즈
		$fname_type=$_FILES["file1"]["type"];		//파일의 type
		$filename=time() ;		//파일이름 작명
		$type = "normal"; // 일반파일 normal, 이미지만 image
		#------------------------------------------
		include("../../include/file_upload.php");
		$upfile1 = $upfile;
		$upfile1_real = $_FILES["file1"]["name"];
		$upfileQuery1 = ($upfile)? "filename = '$upfile1',filename_real='$upfile1_real', ":"" ;
	}

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$sql = "select * from $table where center_div='$center_div'";
	list($seq) = $dbo->query($sql);
	$seq+=1;


	$sqlInsert="
	   insert into $table (
		  posit,
		  school,
		  center_div,
		  name,
		  content,
		  phone,
		  email,
		  seq,
		  reg_date,
		  reg_date2,
		  filename,
		  filename_real
	  ) values (
		  '$posit',
		  '$school',
		  '$center_div',
		  '$name',
		  '$content',
		  '$phone',
		  '$email',
		  '$seq',
		  '$reg_date',
		  '$reg_date2',
		  '$upfile1',
		  '$upfile1_real'
	)";


	$sqlModify="
	   update $table set
		  $upfileQuery1
		  posit = '$posit',
		  school = '$school',
		  center_div = '$center_div',
		  name = '$name',
		  content = '$content',
		  phone = '$phone',
		  email = '$email'
	   where id_no='$id_no'
	";

	$sql = ($id_no)? $sqlModify : $sqlInsert;

	if($id_no){
		$sql = $sqlModify;
		$url = "view_${filecode}.php?id_no=$id_no&flag=$center_div";
	}else{
		$sql = $sqlInsert;
		$url = "list_${filecode}.php?flag=$center_div";
	}

	//checkVar("",$sql);exit;

	if($dbo->query($sql)){

		if(!$id_no){
			//$sql = "update $table set seq=seq+1";
			//$dbo->query($sql);
		}else{

			if($center_div_old!=$center_div){
				$sql = "update $table set seq=0 where id_no=$id_no";
				$dbo->query($sql);

				$sql = "update $table set seq=seq+1";
				$dbo->query($sql);
			}

		}

		msggo("저장하였습니다.",$url);
	}else{
		checkVar(mysql_error(),$sql);
	}
	exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){
		$sql = "select *  from $table where id_no=$check[$i]";
		$dbo->query($sql);
		$rs=$dbo->next_record();
		if($rs[filename]) @unlink("../../public/center/$rs[filename]");

		$sql = "update $table set seq=seq-1 where seq > '$rs[seq]' and center_div='$check2[$i]'";
		$dbo->query($sql);

		$sql = "delete from $table where id_no = $check[$i]";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php?flag=$check2[$i]");exit;

}elseif ($mode=="file_drop"){

		$sql = "update $table set $mode2 ='' where id_no=$id_no";
		$dbo->query($sql);
		@unlink("../../public/center/$filename");
		redirect2("?id_no=$id_no&flag=$flag&$sessLink");exit;
		exit;

}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();

}

$rs[center_div]=($rs[center_div])?$rs[center_div]:$flag;
//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(){

	var fm  =document.fmData;

	if(check_blank(fm.name,'성명을',0)=='wrong'){return }

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


		<br>


      <table border="0" cellspacing="1" cellpadding="3" width="750">

		<form name="fmData" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="save">
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="flag" value='<?=$flag?>'>
		<input type="hidden" name="center_div_old" value='<?=$rs[center_div]?>'>


		<tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>
        <tr>
          <td class="subject">구분</td>
          <td>
			<?=radio("센터원,Old Member","센터원,Old Member",$rs[center_div],"center_div")?>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

		<tr>
          <td class="subject">직함</td>
          <td>
			<input class="box" type="text" name="posit"  size="30" maxlength="50" value="<?=$rs[posit]?>">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

		<tr>
          <td class="subject">학위</td>
          <td>
			<input class="box" type="text" name="school"  size="30" maxlength="50" value="<?=$rs[school]?>">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>


		<tr>
          <td class="subject">이름</td>
          <td>
			<input class="box" type="text" name="name"  size="30" maxlength="50" value="<?=$rs[name]?>">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject">내용</td>
          <td>
            <textarea name="content" class="box" rows="5" cols="80"><?=$rs[content]?></textarea>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject">Tel</td>
          <td>
            <input class="box" type="text" name="phone"  size="30" maxlength="50" value="<?=$rs[phone]?>">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>
        <tr>
          <td class="subject">Email</td>
          <td>
            <input class="box" type="text" name="email"  size="30" maxlength="100" value="<?=$rs[email]?>">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

	<?if($rs[filename]):
		@$fileSize = filesize("../../public/center/${rs[filename]}");
		?>
        <tr>
          <td  class="subject">파일<r/td>
          <td>
			<input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename&filename=<?=$rs[filename]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename]?>&orgin_file_name=<?=$rs[filename_real]?>&dir=public/center" onFocus="blur(this)"><?=$rs[filename_real]?> (<?=ceil($fileSize/1024)?>KB)</a>

          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?else:?>
		<tr>
          <td  class="subject">파일 업로드</td>
          <td>
            <input class=box type="file" name="file1" size=40>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?endif;?>


		<?if($rs[id_no]){?>
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject">등록일</td>
          <td>
            <?=$rs[reg_date]?>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>
		<?}?>

        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan=2 bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
		<td colspan=2>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
				<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
				<td><span class="btn_pack medium bold"><a href="#" onClick="location.href='list_<?=$filecode?>.php?<?=$sessLink?>&flag=<?=$flag?>'"> 리스트 </a></span></td>
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

