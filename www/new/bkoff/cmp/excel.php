<?
include_once("../include/common_file.php");
@set_time_limit(0);

exit;

####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "basic";
$TITLE = "엑셀 등록";
$table = "cmp_golfclub";


$maxFileSize = intval(substr(ini_get(upload_max_filesize),0,-1));
$today = date("Y/m/d");
#### mode


if($mode=="save"){

	$file2=$_FILES["file2"]["tmp_name"];
	$file2_name=$_FILES["file2"]["name"];
	$file2_size=$_FILES["file2"]["size"];
	$file2_type=$_FILES["file2"]["type"];

	$ftype = explode(".",$file2_name);
	If($ftype[1]!="xls"){
		Error("XLS 파일만 업로드 하실 수 있습니다.");
		exit;
	}

	if($_FILES["file2"]["size"]){
		#------------------------------------------
		$path="../../public/xls";	//업로드할 파일의 경로
		$maxsize=$maxFileSize  * (1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
		$fname=$file2;						//파일이름을 담고 있는 변수 이름
		$fname_name=$file2_name;	//파일의 이름
		$fname_size=$file2_size;		//파일의 사이즈
		$fname_type=$file2_type;		//파일의 type
		$filename="excel_" . date("YmdHis");		//파일이름 작명
		$type = "normal"; // 일반파일 normal, 이미지만 image
		#------------------------------------------
		include("../../include/file_upload.php");
	}

	$xlsfile = $path . "/" . $upfile;

	$reg_date = date("Y-m-d");
	$deliver_input_date = time();
	require_once '../../phpExcelReader/Excel/reader.php';
	// ExcelFile($filename, $encoding);
	$data = new Spreadsheet_Excel_Reader();

	// Set output Encoding.
	$data->setOutputEncoding('euc-kr');
	$data->read($xlsfile);

	$time_unix = time();
	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	error_reporting(E_ALL ^ E_NOTICE);
	$error=0;
	for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {

		$query = "";

		$club_id =addslashes(trim($data->sheets[0]['cells'][$i][1]));
		$club_name =addslashes(trim($data->sheets[0]['cells'][$i][2]));
		$club_membership =addslashes(trim($data->sheets[0]['cells'][$i][3]));
		$number_of_holes =addslashes(trim($data->sheets[0]['cells'][$i][4]));
		$address =addslashes(trim($data->sheets[0]['cells'][$i][5]));
		$city =addslashes(trim($data->sheets[0]['cells'][$i][6]));
		$state =addslashes(trim($data->sheets[0]['cells'][$i][7]));
		$country =addslashes(trim($data->sheets[0]['cells'][$i][8]));
		$postal_code =addslashes(trim($data->sheets[0]['cells'][$i][9]));
		$phone =addslashes(trim($data->sheets[0]['cells'][$i][10]));
		$fax =addslashes(trim($data->sheets[0]['cells'][$i][11]));
		$website =addslashes(trim($data->sheets[0]['cells'][$i][12]));
		$contact_name =addslashes(trim($data->sheets[0]['cells'][$i][13]));
		$contact_title =addslashes(trim($data->sheets[0]['cells'][$i][14]));
		$email_address =addslashes(trim($data->sheets[0]['cells'][$i][15]));
		$driving_range =addslashes(trim($data->sheets[0]['cells'][$i][16]));
		$putting_green =addslashes(trim($data->sheets[0]['cells'][$i][17]));
		$chipping_green =addslashes(trim($data->sheets[0]['cells'][$i][18]));
		$practice_bunker =addslashes(trim($data->sheets[0]['cells'][$i][19]));
		$motor_cart =addslashes(trim($data->sheets[0]['cells'][$i][20]));
		$pull_cart =addslashes(trim($data->sheets[0]['cells'][$i][21]));
		$golf_clubs_rental =addslashes(trim($data->sheets[0]['cells'][$i][22]));
		$club_fitting =addslashes(trim($data->sheets[0]['cells'][$i][23]));
		$pro_shop =addslashes(trim($data->sheets[0]['cells'][$i][24]));
		$golf_lessons =addslashes(trim($data->sheets[0]['cells'][$i][25]));
		$caddie_hire =addslashes(trim($data->sheets[0]['cells'][$i][26]));
		$restaurant =addslashes(trim($data->sheets[0]['cells'][$i][27]));
		$reception_hall =addslashes(trim($data->sheets[0]['cells'][$i][28]));
		$changing_room =addslashes(trim($data->sheets[0]['cells'][$i][29]));
		$lockers =addslashes(trim($data->sheets[0]['cells'][$i][30]));
		$lodging_on_site =addslashes(trim($data->sheets[0]['cells'][$i][31]));


		$query="
			insert into cmp_golfclub (
				club_id,
				club_name,
				club_membership,
				number_of_holes,
				address,
				city,
				state,
				country,
				postal_code,
				phone,
				fax,
				website,
				contact_name,
				contact_title,
				email_address,
				driving_range,
				putting_green,
				chipping_green,
				practice_bunker,
				motor_cart,
				pull_cart,
				golf_clubs_rental,
				club_fitting,
				pro_shop,
				golf_lessons,
				caddie_hire,
				restaurant,
				reception_hall,
				changing_room,
				lockers,
				lodging_on_site
		   ) values (
				'$club_id',
				'$club_name',
				'$club_membership',
				'$number_of_holes',
				'$address',
				'$city',
				'$state',
				'$country',
				'$postal_code',
				'$phone',
				'$fax',
				'$website',
				'$contact_name',
				'$contact_title',
				'$email_address',
				'$driving_range',
				'$putting_green',
				'$chipping_green',
				'$practice_bunker',
				'$motor_cart',
				'$pull_cart',
				'$golf_clubs_rental',
				'$club_fitting',
				'$pro_shop',
				'$golf_lessons',
				'$caddie_hire',
				'$restaurant',
				'$reception_hall',
				'$changing_room',
				'$lockers',
				'$lodging_on_site'
		 )";

		$dbo->query($query);
		if(mysql_error()){
			checkVar(mysql_error(),$query);
		}
	}

	error("등록하였습니다.");
	exit;

}
//-------------------------------------------------------------------------------
?>
<?include("../top.html");?>
<script language="JavaScript">
<!--
function chkForm(){
	fm = document.fmData;
	if(check_blank(fm.file2,'xls 파일을',0)=='wrong'){return}
	fm.submit();
}

//-->
</script>


<body text="#000000" bgcolor="FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
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

		<form name=fmData method=post onSubmit="return chkForm()" method=post enctype="multipart/form-data">
		<input type=hidden name=mode value='save'>
		<input type=hidden name=id_no value='<?=$rs[id_no]?>'>


		<tr><td colspan="4"  bgcolor='#5E90AE' height=2></td></tr>

       <tr>
          <td class="subject">XLS 파일</td>
          <td height="20">
            <input class=box type="file" name="file2" size=70 >
         </td>
        </tr>

        <tr><td colspan="4" bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan="4" bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
		<td colspan="4">
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="150" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
				<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 업로드 </a></span></td>
		    </tr>
		  </table>
		  <!-- Button End------------------------------------------------>
		</td>
		</tr>
      </table>

	</form>

	<!--내용이 들어가는 곳 끝-->

	<!-- <div class="msg_box" style="width:750px">
		XLS(Excel 97-2003통합 문서 *.xls) 파일의 순서와 형식을 반드시 지켜 주세요.  <span class="btn_pack small bold"><a href="sample.zip"> 샘플파일 다운로드</a></span><br/>
		XLS 파일의 확장자는 "<b>.xls</b>" 로 저장된 것이어야 합니다.

		<br/>
		<br/>

		<ol style="margin-left:25px">
			<li>거래처코드</li>
			<li>거래처명</li>
			<li>합계</li>
		</ol>
	</div> -->


<!-- Copyright -->
<?include_once("../bottom.html");?>

