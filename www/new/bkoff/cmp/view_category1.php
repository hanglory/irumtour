<?
include_once("../include/common_file.php");



####각종 기초 정보 결정
$view_row=10;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;

#### 기본 정보
$filecode = "category1";
$table = "ez_category1";
$MENU = "cmp_paper";
$TITLE = "비용 대분류 정보";
$filter = "";
$column = "*";
$basicLink = "";



function chk_val_ctg3($val){
	$val = trim($val);
	$val = str_replace("'","",$val);
	$val = str_replace(" ,",",",$val);
	$val = str_replace(", ",",",$val);
	$val = str_replace("\"","",$val);
	$val = str_replace(",,",",",$val);
	if(substr($val,0,1)==",") $val = substr($val,1);
	if(substr($val,-1)==",") $val = substr($val,0,-1);
	$val = trim($val);
	return $val;
}

if($mode=="save"){
	$path="../../public/category";	//업로드할 파일의 경로
	$maxsize=$maxFileSize *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한

	for($i=1; $i <= 6; $i++){

		$fn = "file" . $i;

		if($_FILES[$fn]["size"]){
			#------------------------------------------
			$fname=$_FILES[$fn]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES[$fn]["name"];	//파일의 이름
			$fname_size=$_FILES[$fn]["size"];		//파일의 사이즈
			$fname_type=$_FILES[$fn]["type"];		//파일의 type
			$filename=time() . "_" . $i;		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfiles[$i] = $upfile;
			$upfile_real[$i] = $_FILES[$fn]["name"];
			$upfileQuery[$i] = ($upfile)? "filename${i} = '". $upfiles[$i] ."',filename${i}_real='".$_FILES[$fn]["name"]."', ":"" ;
		}
	}

	$category2= chk_val_ctg3($category2);
	$eng_category2= chk_val_ctg3($eng_category2);
}

switch($mode){
	case "save":
		if(!$id_no){

			$sql = "select max(seq)+1 as seq from ez_category1 where cp_id='$CP_ID'";
			$dbo->query($sql);
			$rs=$dbo->next_record();
			$seq= $rs[seq];

			$sql="
			   insert into ez_category1 (
                  cp_id,
				  category1,
				  bit_out,
				  seq
			  ) values (
				  '$CP_ID',
                  '$category1',
				  '$bit_out',
				  '$seq'
			)";

			$goUrl = "category1.php";


		}else{	//modify인 경우

			$sql="
			   update ez_category1 set
				  bit_out = '$bit_out',
				  category1 = '$category1'
			   where id_no='$id_no' and cp_id='$CP_ID'
			";

			$basicLink = "table=$table&table2=$table2";
			$goUrl = "?id_no=$id_no&$basicLink";
		}



		if($dbo->query($sql)){
			//if(!$id_no){ $sql = "update $table set seq=seq+1  "; $dbo->query($sql);}

			if($id_no){
				$sql = "update cmp_expense set category1='$category1' where category1='$category1_old' and cp_id='$CP_ID'";
				$dbo->query($sql);
				$sql = "update ez_category2 set category1='$category1' where category1='$category1_old' and cp_id='$CP_ID'";
				$dbo->query($sql);
			}

		}

		//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql);exit;}
		//include_once("make_js.php");
		redirect2($goUrl);
		exit;
		break;


	 case "drop":

		for($i = 0; $i < count($check);$i++){
			$sql = "select *  from $table where id_no=$check[$i]";
			$dbo->query($sql);
			$rs=$dbo->next_record();
			if($rs[filename1]) @unlink("../../public/category/$rs[filename1]");
			if($rs[filename2]) @unlink("../../public/category/$rs[filename2]");
			if($rs[filename3]) @unlink("../../public/category/$rs[filename3]");
			if($rs[filename4]) @unlink("../../public/category/$rs[filename4]");
			if($rs[filename5]) @unlink("../../public/category/$rs[filename5]");
			if($rs[filename6]) @unlink("../../public/category/$rs[filename6]");

			$sql = "update $table set seq=seq-1 where seq > '$rs[seq]' and cp_id='$CP_ID' ";
			$dbo->query($sql);

			$sql = "delete from $table where id_no = $check[$i] and cp_id='$CP_ID'";
			$dbo->query($sql);
		}
		redirect2("category1.php?assort=$assort&assort2=$assort2");exit;
		break;

	case "file_drop":
		$sql = "update $table set $mode2 ='' where id_no=$id_no and cp_id='$CP_ID'";
		$dbo->query($sql);
		@unlink("../../public/category/$filename");
		redirect2("?id_no=$id_no&$_SESSION[link]");exit;

	default:
		$sql = "select * from $table where id_no = $id_no and cp_id='$CP_ID'";
		$dbo->query($sql);
		$rs = $dbo->next_record();
}
//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(fm){
	fm = document.fmData;
	if(check_blank(fm.category1,' 대분류명을',0)=='wrong'){return }
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

      <table border="0" cellspacing="1" cellpadding="3" class="viewWidth" width=100%>

		<form name="fmData" method="post" enctype="multipart/form-data">
		<input type=hidden name=mode value='save'>
		<input type=hidden name=id_no value='<?=$rs[id_no]?>'>
		<input type=hidden name=category1_old value='<?=$rs[category1]?>'>

		<tr><td colspan=2 bgcolor='#408080' height="1"></td></tr>

		<tr>
          <td height="20" class="subject" width="20%">*<b> 대분류명</b></td>
          <td height="20">
            <?=html_input('category1',40,150)?>

			<!-- <label><input type="checkbox" name="order_type" id="order_type" value="1" <?=($rs[order_type])?"checked='checked'":""?>>일반상품</label> -->

			&nbsp;&nbsp;&nbsp;&nbsp;
			<?=radio("출금,입금","0,1",$rs[bit_out],'bit_out')?>

          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


		<!-- <tr>
          <td height="20" class="subject"><b>영문 대분류명</b></td>
          <td height="20">
            <?=html_input('category1_en',80,150)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr> -->




        <tr>
		<td colspan=2>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" cellspacing="5" cellpadding="0" align="right">
		    <tr>
			<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
			<td><span class="btn_pack medium bold"><a href="category1.php"> 목록 </a></span></td>
		      <td width = 20>&nbsp;</td>
		    </tr>
		  </table>
		  <!-- Button End------------------------------------------------>
		</td>
		</tr>
      </table>

    </td>
  </tr>
</table>

</form>

<!-- Copyright -->
<?include_once("../bottom.html");?>
