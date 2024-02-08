<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$TITLE = "고객예약정보 > 고객정보 입력 > 여권사진 (${name})";
$maxFileSize = intval(substr(ini_get(upload_max_filesize),0,-1));
$id_code = $code . "_" . $j;
$passport_no = trim(str_replace(" ","",$passport_no));
$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";


#### operation
switch($mode){
	case "save":

		if($_FILES["file1"]["size"]){
			#------------------------------------------
			$path="../../public/cmp_pass";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file1"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file1"]["name"];	//파일의 이름
			$fname_size=$_FILES["file1"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file1"]["type"];		//파일의 type
			$filename=$id_code;		//파일이름 작명
			$type = "image"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile1 = $upfile;
			$upfile1_real = $_FILES["file1"]["name"];
			$upfileQuery1 = ($upfile)? "filename = '$upfile1',filename_real='$upfile1_real', ":"" ;
		}


		if($upfile1){

			if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){

				//$filename = "test2.jpg";
				$ocr = ocr($upfile1);

				$ip = $ocr[ip];
				$ocr_type = $ocr[idType];
				$name = $ocr[userName];
				$name_eng = $ocr[userNameEng];
				$passport_limit = $ocr[expiryDate];
				$issueDate = $ocr[issueDate];
				$passport_no = $ocr[passport_no];
				$rn = $ocr[rn];
				$sex = $ocr[sex];

				// if($rn){
				// $aes = new AES($rn, $inputKey, $blockSize);
				// $dec=$aes->decrypt();
				// $rn = $dec;
				// }

				// if($passport_no){
				// $aes = new AES($passport_no, $inputKey, $blockSize);
				// $dec=$aes->decrypt();
				// $passport_no = $dec;
				// }
				checkVar("upfile1",$upfile1);
				checkVar("ip",$ip);
				checkVar("name",$name);
				checkVar("name_eng",$name_eng);
				checkVar("passport_limit",$passport_limit);
				checkVar("idType",$idType);
				checkVar("issueDate",$issueDate);
				checkVar("passport_no",$passport_no);
				checkVar("rn",$rn);	
				checkVar("sex",$sex);	
				//exit;
			}	


			$sql = "delete from cmp_passport where id_code='$id_code'";
			$dbo->query($sql);

			$sql="
				insert into cmp_passport (
				   ocr_type,
				   code,
				   id_code,
				   passport_no,
				   name,
				   sex,
				   filename1,
				   filename1_real
			   ) values (
				   '$ocr_type',
				   '$code',
				   '$id_code',
				   '$passport_no',
				   '$name',
				   '$sex',
				   '$upfile1',
				   '$upfile1_real'
			 )";
			 			 	checkVar(mysql_error(),$sql);
			 	checkVar("customer_id_no",$customer_id_no);exit;
			 if($dbo->query($sql)){
			 	checkVar(mysql_error(),$sql);
			 	checkVar("customer_id_no",$customer_id_no);exit;

			 	if($customer_id_no){

					$sql="
					   update cmp_customer set
						  sex = '$sex',
						  name_eng = '$name_eng',
						  rn = '$rn',
						  passport_no = '$passport_no',
						  staff = '$staff',
						  passport_limit = '$passport_limit'
					   where id_no='$customer_id_no'
					";
			 		$dbo->query($sql);
			 		checkVar(mysql_error(),$sql);exit;
			 		echo "
			 			<script>
			 			opener.location.href.reload();
			 			self.close();
			 			</script>
			 		";
			 		exit;

			 	}else{

				 	$sql = "select * from cmp_customer where rn='$rn'";
				 	$dbo->query($sql);
				 	$rs=$dbo->next_record();
				 	if(!$rs[id_no]){
						$sql="
						   insert into cmp_customer (
							  name,
							  sex,
							  name_eng,
							  rn,
							  passport_no,
							  staff,
							  passport_limit
						  ) values (
							  '$name',
							  '$sex',
							  '$name_eng',
							  '$rn',
							  '$passport_no',
							  '$phone',
							  '$staff',
							  '$passport_limit'
						)";
				 	}else{
						$sql="
						   update cmp_customer set
							  sex = '$sex',
							  name_eng = '$name_eng',
							  rn = '$rn',
							  passport_no = '$passport_no',
							  staff = '$staff',
							  passport_limit = '$passport_limit'
						   where rn='$rn'
						";
				 	}
				 	$dbo->query($sql);	
				}	
			 }

		 }
		 
		 echo "
			<script>
					opener.document.getElementById('plink_${j}').src='/renew/images/ez_board/ico_file03.gif';
			</script>
		 ";

		back();exit;


	case "drop":

		$sql = "select * from cmp_passport where passport_no = '$passport_no'";
		$dbo->query($sql);
		$rs=$dbo->next_record();
		if($rs[filename1]) @unlink("../../public/cmp_pass/$rs[filename1]");

		$sql = "delete from cmp_passport where passport_no = '$passport_no'";
		$dbo->query($sql);

		 echo "
			<script>
					opener.document.getElementById('plink_${j}').src='/renew/images/ez_board/ico_file02.gif';
			</script>
		 ";


		back();exit;

	default:
		$sql = "select * from cmp_passport where passport_no = '$passport_no'";
		$dbo->query($sql);
		$rs = $dbo->next_record();
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
	var url="<?=SELF?>?mode=drop&code=<?=$code?>&passport_no=<?=$passport_no?>&j=<?=$j?>";
	location.href=url;

}

function close_win(){

	var color = "<?=($rs[filename1])?'#ff0066':'gray'?>";
	opener.document.getElementById('plink_<?=$j?>').style.color=color;

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
	if($rs[filename1]){
	?>

		<div id="file_wrap"><img src="../../public/cmp_pass/<?=$rs[filename1]?>"></div>

		<!-- Button Begin---------------------------------------------->
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
		 <tr>
		  <td width="60%" align="left">

		  </td>
		  <td align="right" style="padding-right:23px">
			<span class="btn_pack medium bold"><a href="javascript:drop()"> 삭제 </a></span>&nbsp;&nbsp;&nbsp;
			<span class="btn_pack medium bold"><a href="../../include/download.php?file=<?=$rs[filename1]?>&orgin_file_name=<?=$rs[filename1_real]?>&dir=public/cmp_pass"> 다운로드 </a></span>&nbsp;&nbsp;&nbsp;
			<span class="btn_pack medium bold"><a href="#" onClick="close_win()"> 창닫기 </a></span>
		  </td>
		</tr>
		</table>
		<!-- Button End------------------------------------------------>

	<?}else{?>

		<table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">

			<form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
			<input type="hidden" name="mode" value='save'>
			<input type="hidden" id="code" name="code" value='<?=$code?>'>
			<input type="1hidden" id="customer_id_no" name="customer_id_no" value='<?=$customer_id_no?>'>
			<input type="hidden" id="name" name="name" value='<?=$name?>'>
			<input type="hidden" id="j" name="j" value='<?=$j?>'>

			<tr><td colspan="15"  bgcolor='#5E90AE' height=2></td></tr>
			<tr align="center" height="1" bgcolor="#F7F7F6"></tr>
			<tr>
				<th colspan="8" height="40" width="20%">사진등록</th>
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