<?
include_once("../include/common_file.php");


$maxFileSize = intval(substr(ini_get(upload_max_filesize),0,-1));


#### Menu
$filecode = substr(SELF,5,-4);
$TITLE = "팝업관리";
$MENU = "basic";


#### operation
$table = "popup";

if ($mode=="save"){

		$reg_date = date("Y/m/d");

		$naming = "pop_";
		if($_FILES["file1"]["size"]){
			#------------------------------------------
			$path="../../public/popup";	//업로드할 파일의 경로
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


		if($_FILES["file2"]["size"]){
			#------------------------------------------
			$path="../../public/popup";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file2"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file2"]["name"];	//파일의 이름
			$fname_size=$_FILES["file2"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file2"]["type"];		//파일의 type
			$filename=$naming . time();		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile2 = $upfile;
			$upfile2_real = $_FILES["file2"]["name"];
			$upfileQuery2 = ($upfile)? "filename2 = '$upfile2', ":"" ;
		}



		$period_start = $period_start . " " . $period_start_h . ":" . $period_start_i ;
		$period_end = $period_end . " " . $period_end_h . ":" . $period_end_i ;

		$period_end = (substr(trim($period_end),0,1)=="0")?  "":$period_end;

		$sqlInsert="
		   insert into $table (
              cp_id,
			  open,
			  title,
			  width,
			  height,
			  scroll,
			  bgcolor,
			  content,
			  chkopen,
			  position_left,
			  position_top,
			  reg_date,
			  opener_link,
			  filename,
			  filename2,
			  period_start,
			  period_end
		  ) values (
			  '$CP_ID',
              '$open',
			  '$title',
			  '$width',
			  '$height',
			  '$scroll',
			  '$bgcolor',
			  '$content',
			  '$chkopen',
			  '$position_left',
			  '$position_top',
			  '$reg_date',
			  '$opener_link',
			  '$upfile1',
			  '$upfile2',
			  '$period_start',
			  '$period_end'
		)";


		$sqlModify="
		   update $table set
			  $upfileQuery1
			  $upfileQuery2
			  open = '$open',
			  title = '$title',
			  width = '$width',
			  height = '$height',
			  scroll = '$scroll',
			  bgcolor = '$bgcolor',
			  content = '$content',
			  chkopen = '$chkopen',
			  opener_link = '$opener_link',
			  position_left = '$position_left',
			  position_top = '$position_top',
			  period_start = '$period_start',
			  period_end = '$period_end'
		   where id_no='$id_no'
              $FILTER_PARTNER_QUERY
		";

		if($id_no){
			$sql = $sqlModify;
			$url = "view_${filecode}.php?id_no=$id_no";
		}else{
			$sql = $sqlInsert;
			$url = "list_${filecode}.php";
		}

		if($dbo->query($sql)){
			msggo("저장하였습니다.",$url);
		}else{
			checkVar(mysql_error(),$sql);
		}
		exit;

}elseif ($mode=="file_drop"){
		$sql = "update $table set $mode2 ='' where id_no=$id_no $FILTER_PARTNER_QUERY";
		$dbo->query($sql);
		@unlink("../../public/popup/$filename");
		redirect2("?id_no=$id_no&$sessLink");exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){
		$sql = "select *  from $table where id_no=$check[$i] $FILTER_PARTNER_QUERY";
		$dbo->query($sql);
		$rs=$dbo->next_record();
		if($rs[filename]) @unlink("../../public/popup/$rs[filename]");
		if($rs[filename2]) @unlink("../../public/popup/$rs[filename2]");

		$sql = "delete from $table where id_no = $check[$i]";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php");exit;

}else{
	$sql = "select * from $table where id_no=$id_no ";
	$dbo->query($sql);
	$rs= $dbo->next_record();

}
//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(){
	var fm = document.fmData;
	if(check_blank(fm.title,'팝업창 제목을',0)=='wrong'){return }
	if(check_blank(fm.width,'창 사이즈를',2)=='wrong'){return }
	if(check_blank(fm.height,'창 사이즈를',2)=='wrong'){return }
	if(check_num(fm.width,'창 사이즈')=='wrong'){return }
	if(check_num(fm.height,'창 사이즈')=='wrong'){return }
	fm.submit();
}

function dropImg(div){
	if(confirm('사진을 삭제하시겠습니까?')){
		if(div == 'background'){
			document.fmSite.background_prev.value = '';
		}else{
			document.fmSite.photo_prev.value = '';
		}
		document.fmSite.submit();
	}
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


	<!-- Include Core Datepicker Stylesheet -->
	<link rel="stylesheet" href="../../include/ui.datepicker.css" type="text/css" media="screen" title="core css file" charset="utf-8" />
	<!-- Include jQuery -->
	<script src="../../include/jquery.js" type="text/javascript" charset="utf-8"></script>
	<!-- Include Core Datepicker JavaScript -->
	<script src="../../include/ui.datepicker.js" type="text/javascript" charset="utf-8"></script>
	<!-- Attach the datepicker to dateinput after document is ready -->
	<script type="text/javascript" charset="utf-8">
		jQuery(function($){$(".dateinput").datepicker();});
	</script>


	<!--내용이 들어가는 곳 시작-->


    <table border="0" cellspacing="1" cellpadding="3" class="viewWidth">
		<form name=fmData  method=post enctype="multipart/form-data">
		<input type=hidden name=mode value='save'>
		<input type=hidden name=id_no value='<?=$rs[id_no]?>'>
		<input type=hidden name=photo_prev value='<?=$PHOTO?>'>
		<input type=hidden name=background_prev value='<?=$BACKGROUND?>'>

        <tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>
	    <tr>
          <td  class="subject">팝업창 활성화</td>
          <td>
             <?=radio("활성화,팝업창 띄우지 않음","1,0",number_format($rs[open]),'open')?>
          </td>
        </tr>
		<tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">팝업창 제목</td>
          <td>
            <input class="box" type="text" name="title" value="<?=$rs[title]?>" size=40>
			<font color='orange'>Sample</font> <font color="#666666">: [이벤트] 당첨자 발표...</font>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td  class="subject">창 사이즈</td>
          <td>
             가로 : <input class="box" type="text" name="width" value="<?=$rs[width]?>" size=3 maxlength=3>
			 세로 : <input class="box" type="text" name="height" value="<?=$rs[height]?>" size=3 maxlength=3>
			  <font color='orange'>Help</font> <font color="#666666">: 단위 : 픽셀(pixel)</font>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td  class="subject">팝업 위치</td>
          <td>
             TOP : <input class="box" type="text" name="position_top" value="<?=$rs[position_top]?>" size=3 maxlength=3>
			 LEFT : <input class="box" type="text" name="position_left" value="<?=$rs[position_left]?>" size=3 maxlength=3>
			  <font color='orange'>Help</font> <font color="#666666">: 단위 : 픽셀(pixel)</font>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">스크롤바</td>
          <td>
             <?=radio("있음,없음","1,0",number_format($rs[scroll]),'scroll')?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

		<tr>
          <td  class="subject">배경색</td>
          <td>
             <input class="box" type="text" name="bgcolor" value="<?=$rs[bgcolor]?>" size=7 maxlength=7> (RGB칼라값)
			 <font color='orange'>Sample</font> <font color="#666666">: #000000</font>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

	<?if($rs[filename]):
		@$fileSize = filesize("../../public/popup/${rs[filename]}");
		?>
        <tr>
          <td height="20" align="right" bgcolor='#F7F7F6' style="font-weight: bold">배경이미지</td>
          <td height="20">
			<input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename&filename=<?=$rs[filename]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename]?>&size=<?=$fileSize?>&dir=public/popup" onFocus="blur(this)"><?=$rs[filename]?> (<?=ceil($fileSize/1024)?>KB)</a>

          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
	<?else:?>
		<tr>
          <td  class="subject">배경이미지</td>
          <td height="20">
            <input class="box" type="file" name="file1" size=40>
			<font color="orange">Alert</font></b> <font color="#666666">: <?=$maxFileSize?>MB 이상은 업로드 되지 않습니다.</font>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
	<?endif;?>

		<tr>
          <td  class="subject">내용</td>
          <td>
             <textarea class="box" cols=80 rows=5 name=content><?=stripslashes(stripslashes($rs[content]))?></textarea><br>
			 <font color='orange'>Help</font> <font color="#666666">: 내용은 HTML 형식에 맞아야 합니다.<br> HTML 태그에 자신이 없으신 경우 창 크기와 동일한 이미지를 제작하여 사진올리기로 업로드하시면 됩니다.</font>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

		<tr>
          <td  class="subject">링크</td>
          <td>
             <input class="box" type="text" name="opener_link" value="<?=$rs[opener_link]?>" size=80 maxlength=200><br>
			 <font color='orange'>Help</font> <font color="#666666">: 팝업을 클릭하면 창이 닫히며 입력하신 링크로 이동합니다.</font>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

	<?if($rs[filename2]):
		@$fileSize = filesize("../../public/popup/${rs[filename2]}");
		?>
        <tr>
          <td  class="subject">이미지</td>
          <td height="20">
			<input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename2&filename=<?=$rs[filename2]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename2]?>&size=<?=$fileSize?>&dir=public/popup" onFocus="blur(this)"><?=$rs[filename2]?> (<?=ceil($fileSize/1024)?>KB)</a>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
	<?else:?>
		<tr>
          <td  class="subject">이미지</td>
          <td height="20">
            <input class="box" type="file" name="file2" size=40>
			<font color="orange">Alert</font></b> <font color="#666666">: <?=$maxFileSize?>MB 이상은 업로드 되지 않습니다.</font>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
	<?endif;?>

        <tr>
          <td  class="subject">다시열지 않음 버튼 생성</td>
          <td>
             <?=radio("사용,사용하지 않음","1,0",number_format($rs[chkopen]),'chkopen')?>
          </td>
        </tr>
		<tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">시작일</td>
          <td>
            <input class="box dateinput" type="text" name="period_start" value="<?=($rs[period_start])?substr($rs[period_start],0,10):date("Y/m/d")?>" size="10" maxlength="10">

			<select name="period_start_h">
				<?=option_int(0,23,1,substr($rs[period_start],11,2))?>
			</select>시

			<select name="period_start_i">
				<?=option_int(0,59,1,substr($rs[period_start],14,2))?>
			</select>분
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td class="subject">종료일</td>
          <td>
            <input class="box dateinput" type="text" name="period_end" value="<?=substr($rs[period_end],0,10)?>" size="10" maxlength="10">

			<select name="period_end_h">
				<?=option_int(0,23,1,substr($rs[period_end],11,2))?>
			</select>시

			<select name="period_end_i">
				<?=option_int(0,59,1,substr($rs[period_end],14,2))?>
			</select>분
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>

        <tr>
	  <td colspan=10>
		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
				<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
				<td><span class="btn_pack medium bold"><a href="#" onClick="location.href='list_<?=$filecode?>.php?<?=$sessLink?>'"> 리스트 </a></span></td>
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