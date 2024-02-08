<?
include_once("../include/common_file.php");


#### Menu
$filecode = substr(SELF,5,-4);
$TITLE = "카테고리별 배너(스페셜)";
$MENU = "basic";
$table = "ez_ctg_banner4";


#### operation

if ($mode=="save"){

		$reg_date = date("Y/m/d");

		$naming = 'lmir_';
		if($_FILES["file1"]["size"]){
			#------------------------------------------
			$path="../../public/banner";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
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
			$upfileQuery1 = ($upfile)? "filename = '$upfile1', ":"" ;
		}

		//$url = str_replace("http://","",$url);
		$sqlInsert="
		   insert into $table (
			  text1,
			  target,
			  url,
			  filename,
			  seq
		  ) values (
			  '$text1',
			  '$target',
			  '$url',
			  '$upfile1',
			  '0'
		)";

		$sqlModify="
		   update $table set
			  $upfileQuery1
			  text1 = '$text1',
			  url = '$url',
			  target = '$target'
		   where id_no='$id_no'
		";

		if($id_no){
			$sql = $sqlModify;
			$url = "view_${filecode}.php?id_no=$id_no&assort=$assort";
		}else{
			$sql = $sqlInsert;
			$url = "list_${filecode}.php?assort=$assort";
		}

		if($dbo->query($sql)){

			if(!$id_no){
				$sql = "update $table set seq=seq+1";
				$dbo->query($sql);
			}

			msggo("저장하였습니다.",$url);
		}else{
			checkVar(mysql_error(),$sql);
		}
		exit;

}elseif ($mode=="file_drop"){
		$sql = "update $table set $mode2 ='' where id_no=$id_no";
		$dbo->query($sql);
		@unlink("../../public/banner/$filename");
		redirect2("?id_no=$id_no&$sessLink");exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){
		$sql = "select *  from $table where id_no=$check[$i]";
		$dbo->query($sql);
		$rs=$dbo->next_record();
		if($rs[filename]) @unlink("../../public/banner/$rs[filename]");

		$sql = "update $table set seq=seq-1 where seq > '$rs[seq]'";
		$dbo->query($sql);

		$sql = "delete from $table where id_no = $check[$i]";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php?assort=$assort");exit;

}elseif ($mode=="file_drop"){
		$sql = "update $table set filename ='' where id_no=$id_no";
		$dbo->query($sql);
		@unlink("../../public/banner/$filename");
		redirect2("?id_no=$id_no&$_SESSION[sessLink]");exit;
}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();

}
//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(){
	var fm = document.fmData;
	//if(check_blank(fm.file1,'이미지를',0)=='wrong'){return }
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

	<!--내용이 들어가는 곳 시작-->
    <table border="0" cellspacing="1" cellpadding="3" class="viewWidth" width="750">
		<form name="fmData"  method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="assor" value='<?=$assort?>'>

        <tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>
        <tr>
          <td class="subject" width="25%">text</td>
          <td>
            <input class="box" type="text" name="text1" value="<?=$rs[text1]?>" size=30 maxlength="70">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

		<tr>
          <td class="subject" width="25%">URL</td>
          <td>
            <input class="box" type="text" name="url" value="<?=$rs[url]?>" size=100 maxlength="190">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

		<tr>
          <td class="subject" width="25%">열기</td>
          <td>
            <select name="target"><?=option_str("현재창,새창","_self,_blank",$rs[target])?></select>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

	<?if($rs[filename]):
		@$fileSize = filesize("../../public/banner/${rs[filename]}");
		?>
        <tr>
          <td class="subject">이미지<br />725 * 247</td>
          <td height="20">
			<input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename&filename=<?=$rs[filename]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename]?>&size=<?=$fileSize?>" onFocus="blur(this)"><?=$rs[filename]?> (<?=ceil($fileSize/1024)?>KB)</a>
			<div style="padding:15px 0 15px 0"><img src="../../public/banner/<?=$rs[filename]?>" width=240></div>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
	<?else:?>
		<tr>
          <td  class="subject">이미지</td>
          <td height="20">
            <input class="box" type="file" name="file1" size=40> 725 * 247
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
	<?endif;?>

        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>
        <tr>
		  <td colspan=10>
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
					<td><span class="btn_pack medium bold"><a href="#" onClick="location.href='list_<?=$filecode?>.php?<?=$sessLink?>&assort=<?=$assort?>'"> 리스트 </a></span></td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>
		  </td>
        </tr>
        <tr>
	  <td colspan=10 height=20>
	  </td>
        </tr>
	</form>
	</table>



	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>