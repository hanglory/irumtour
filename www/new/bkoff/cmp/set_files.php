<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$assort_txt = ($assort==1)? "항공":"지상비";
$assort_txt = ($assort==3)? "고객환불":$assort_txt;
$assort_txt = ($assort==4)? "이티켓":$assort_txt;
$name = ($name)? $name : "고객명없음";
$TITLE = "고객예약정보 > 고객정보 입력 > 파일등록 (${name} : $assort_txt)";
$maxFileSize = intval(substr(ini_get(upload_max_filesize),0,-1));
$id_code = $code . "_" . $id_no . "_" . $assort;
//checkVar("id_code",$id_code);

include_once "../../SMS/xmlrpc.inc.php";
include_once "../../SMS/class.EmmaSMS.php";



#### operation
switch($mode){
	case "save":

		if($_FILES["file1"]["size"]){
			#------------------------------------------
			$path="../../public/cmp_files";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file1"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file1"]["name"];	//파일의 이름
			$fname_size=$_FILES["file1"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file1"]["type"];		//파일의 type
			$filename=$id_code;		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile1 = $upfile;
			$upfile1_real = $_FILES["file1"]["name"];
			$upfileQuery1 = ($upfile)? "filename = '$upfile1',filename_real='$upfile1_real', ":"" ;
		}

		$sql = "delete from cmp_files where id_code='$id_code'";
		$dbo->query($sql);

		$sql="
		   insert into cmp_files ( 
		      id_code, 
		      assort, 
		      filename, 
		      filename_name, 
		      reg_date, 
		      reg_date2 
		  ) values ( 
		      '$id_code', 
		      '$assort', 
		      '$upfile1', 
		      '$upfile1_real', 
		      '$reg_date', 
		      '$reg_date2' 
		 )";
		 if($upfile1){
		 	if($dbo->query($sql)){

		 		//sms
				$sms_from = $OFFICE_TEL;
				$sms_type = "L";
				$sms = new EmmaSMS();
				$sms->login($sms_id, $sms_passwd);		 		
		 		$cell = rnf($cell);
		 		$sms_type = "L";
		 		$message = "${name} 고객님  이티켓이 등록되었으니 아래 이룸투어 홈페이지에서 확인하시기 바랍니다.
		 		https://irumtour.net/m2/login.html";
		 		if($name && $cell){
					//$sms->send($cell, $sms_from, $message, $sms_date, $sms_type);		 				
		 		}

		 	}
		 }

		 echo "
			<script>
					opener.document.getElementById('pfile${assort}_${j}').src='/renew/images/ez_board/ico_file03.gif';
			</script>
		 ";

		back();exit;


	case "drop":

		$sql = "select * from cmp_files where id_code = '$id_code'";
		$dbo->query($sql);
		$rs=$dbo->next_record();
		if($rs[filename]) @unlink("../../public/cmp_files/$rs[filename]");

		$sql = "delete from cmp_files where id_code = '$id_code'";
		$dbo->query($sql);

		 echo "
			<script>
					opener.document.getElementById('pfile${assort}_${j}').src='/renew/images/ez_board/ico_file02.gif';
			</script>
		 ";

		back();exit;

	default:
		$sql = "select * from cmp_files where id_code = '$id_code' limit 1";
		$dbo->query($sql);
		$rs = $dbo->next_record();
        //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}
}



?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;
	fm.mode.value= "save";

	fm.submit();

}

function drop()
{
	var url="<?=SELF?>?mode=drop&code=<?=$code?>&id_code=<?=$id_code?>&j=<?=$j?>&id_no=<?=$id_no?>&assort=<?=$assort?>";
	if(confirm('삭제하시겠습니까?')){
		location.href=url;
	}

}

function close_win(){
	self.close();
}
</script>
<style type="text/css">
#file_wrap{padding:10px;}
#file_wrap img{width:100%}
</style>


<div style="padding:0">

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con" style="padding-left:10px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
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
	if($rs[filename]){
	?>

		<table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">

			<tr><td colspan="15"  bgcolor='#5E90AE' height=2></td></tr>
			<tr align="center" height="1" bgcolor="#F7F7F6"></tr>
			<tr>
				<th colspan="8" height="40" width="20%">등록된 파일</th>
				<th><?=$rs[filename_name]?></th>
			<tr><td colspan="15" class="tblLine"></td></tr>
			</tr>
		</table>

		<br/>

		<!-- Button Begin---------------------------------------------->
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
		 <tr>
		  <td width="60%" align="left">

		  </td>
		  <td align="right" style="padding-right:23px">
			<span class="btn_pack medium bold"><a href="javascript:drop()"> 삭제 </a></span>&nbsp;&nbsp;&nbsp;
			<span class="btn_pack medium bold"><a href="../../include/download.php?file=<?=$rs[filename]?>&orgin_file_name=<?=$rs[filename_real]?>&dir=public/cmp_files"> 다운로드 </a></span>&nbsp;&nbsp;&nbsp;
			<span class="btn_pack medium bold"><a href="#" onClick="close_win()"> 창닫기 </a></span>
		  </td>
		</tr>
		</table>
		<!-- Button End------------------------------------------------>


	<?}else{?>

		<table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">

			<form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
			<input type="hidden" name="mode" value='save'>
			<input type="hidden" id="id_code" name="id_code" value='<?=$id_code?>'>
			<input type="hidden" id="id_no" name="id_no" value='<?=$id_no?>'>
			<input type="hidden" id="code" name="code" value='<?=$code?>'>
			<input type="hidden" id="name" name="name" value='<?=$name?>'>
			<input type="hidden" id="cell" name="cell" value='<?=$cell?>'>
			<input type="hidden" id="assort" name="assort" value='<?=$assort?>'>
			<input type="hidden" id="j" name="j" value='<?=$j?>'>

			<tr><td colspan="15"  bgcolor='#5E90AE' height=2></td></tr>
			<tr align="center" height="1" bgcolor="#F7F7F6"></tr>
			<tr>
				<th colspan="8" height="40" width="20%">파일등록</th>
				<th><input type="file" name="file1" id="file1" value="" size="60" style="padding:3px"></th>
			<tr><td colspan="15" class="tblLine"></td></tr>
			</tr>

			</form>
		</table>

		<br>
		<!-- Button Begin---------------------------------------------->
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
		 <tr>
		  <td width="60%" align="left">

		  </td>
		  <td align="right" style="padding-right:23px">
			<span class="btn_pack medium bold"><a href="javascript:chkForm()"> 저장 </a></span>&nbsp;&nbsp;&nbsp;
			<span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
		  </td>
		</tr>
		</table>
		<!-- Button End------------------------------------------------>

	<?}?>

</div>


	<!--내용이 들어가는 곳 끝-->


<!-- Copyright -->
<?include_once("../bottom_min.html");?>