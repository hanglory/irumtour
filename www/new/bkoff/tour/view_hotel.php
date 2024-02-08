<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_hotel";
$MENU = "tour";
$TITLE = "호텔(제주) 관리";



#### mode
if($mode=="save"){

		//sort($room);
		for($i=0; $i < count($room);$i++){
			$rooms .=($room[$i])? "," . chk_opt_text($room[$i]):"";
		}
		$rooms = substr($rooms,1);

		if($_FILES["file1"]["size"]){
			#------------------------------------------
			$path="../../public/hotel";	//업로드할 파일의 경로
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

		$reg_date = date('Y/m/d');
		$reg_date2 = date('H:i:s');

		$sqlInsert="
		   insert into ez_hotel (
			  code,
			  region,
			  subject,
			  content,
			  filename,
			  reg_date,
			  reg_date2,
			  rooms,
			  assort
		  ) values (
			  '$code',
			  '$region',
			  '$subject',
			  '$content',
			  '$upfile1',
			  '$reg_date',
			  '$reg_date2',
			  '$rooms',
			  '$assort'
		)";


		$sqlModify="
		   update ez_hotel set
			  $upfileQuery1
			  code = '$code',
			  region = '$region',
			  subject = '$subject',
			  content = '$content',
			  rooms = '$rooms',
			  assort = '$assort'
		   where id_no='$id_no'
		";

		if($id_no){
			$sql = $sqlModify;
			$url = "view_${filecode}.php?id_no=$id_no";
		}else{
			$sql = $sqlInsert;
			$url = "view_${filecode}.php";
		}

		//checkVar("",$sql);exit;

		if($dbo->query($sql)){
			msggo("저장하였습니다.",$url);
		}else{
			checkVar(mysql_error(),$sql);
		}
		exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){

		$sql = "delete from $table where code = $check[$i]";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php");exit;

}elseif ($mode=="file_drop"){
		$sql = "update $table set $mode2 ='' where id_no=$id_no";
		$dbo->query($sql);
		@unlink("../../public/hotel/$filename");
		redirect2("?id_no=$id_no&$_SESSION[link]");exit;

}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
}

$code = ($rs[code])?$rs[code] : getUniqNo();
//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(){

	var fm  =document.fmData;

	if(fm.assort_tmp.value==""){alert("숙박 구분을 선택해주세요.");return }
	if(check_blank(fm.subject,'명칭을',0)=='wrong'){return }
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
		<input type="hidden" name="code" value='<?=$code?>'>

		<tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan="2" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">구분</td>
          <td>
			<?=radio($HOTEL,$HOTEL,$rs[assort],'assort')?>
          </td>
        </tr>

        <tr><td colspan="2" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">지역</td>
          <td>
			<?=radio($REGION_JEJU,$REGION_JEJU,$rs[region],'region')?>
          </td>
        </tr>

        <tr><td colspan="2" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">명칭</td>
          <td><?=html_input('subject',50,50)?></td>
        </tr>

		<tr><td colspan="2" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">간단한 소개</td>
          <td>
			<?=html_textarea('content',80,3)?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td class="subject" width="20%">객실 이름</td>
          <td>
			<?
			$arr = explode(",",$rs[rooms]);
			$p = (4-(count($arr)%4))+4;
			$r =($rs[rooms])?count($arr)+$p:12;
			for($i=0; $i < $r ; $i++){
			?>
				<input type="text" name="room[]" value="<?=$arr[$i]?>" class="box">
			<?
			}
			?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

	<?if($rs[filename]):
		@$fileSize = filesize("../../public/hotel/${rs[filename]}");
		?>
        <tr>
          <td  class="subject">파일<r/td>
          <td>
			<input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename&filename=<?=$rs[filename]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename]?>&orgin_file_name=<?=$rs[filename_real]?>&dir=public/hotel" onFocus="blur(this)"><?=$rs[filename_real]?> (<?=ceil($fileSize/1024)?>KB)</a>

			<div style="padding-top:20px"><img src="../../public/hotel/<?=$rs[filename]?>" width="139" height="108"></div>

          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?else:?>
		<tr>
          <td  class="subject">파일 업로드</td>
          <td>
            <input class=box type="file" name="file1" size=40>
			<font color="orange">Size</font></b> <font color="#666666">: 139*108</font>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?endif;?>

        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan=2 bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
		<td colspan=2>
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
      </table>

	</form>

	<!--내용이 들어가는 곳 끝-->


<!-- Copyright -->
<?include_once("../bottom.html");?>

