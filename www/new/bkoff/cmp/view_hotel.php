<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_hotel";
$MENU = "cmp_basic";
$TITLE = "호텔 정보";
$file_rows=5;


#### staff
$sql = "select * from cmp_staff where bit_hide<>1 order by name asc";
$dbo->query($sql);
while($rs=$dbo->next_record()){
	$STAFF.=",$rs[name] ($rs[id])";
}


if($mode=="save"){


	$path="../../public/cmp";	//업로드할 파일의 경로
	$maxsize=$maxFileSize *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한

	for($i=1; $i <= $file_rows; $i++){

		$fn = "file" . $i;

		if($_FILES[$fn]["size"]){
			#------------------------------------------
			$fname=$_FILES[$fn]["tmp_name"]; //파일이름을 담고 있는 변수 이름
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
}

#### operation
if ($mode=="save"){

	$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$sqlInsert="
		insert into cmp_hotel (
           cp_id,
		   name,
		   nation,
		   city,
		   phone,
		   content,
		   content2,
		   filename1,
		   filename2,
		   filename3,
		   filename4,
		   filename5,
		   filename1_real,
		   filename2_real,
		   filename3_real,
		   filename4_real,
		   filename5_real,
		   url,
		   ah,
		   address,
		   reg_date,
		   reg_date2
	   ) values (
           '$CP_ID',
		   '$name',
		   '$nation',
		   '$city',
		   '$phone',
		   '$content',
		   '$content2',
		   '$upfiles[1]',
		   '$upfiles[2]',
		   '$upfiles[3]',
		   '$upfiles[4]',
		   '$upfiles[5]',
		   '$upfile_real[1]',
		   '$upfile_real[2]',
		   '$upfile_real[3]',
		   '$upfile_real[4]',
		   '$upfile_real[5]',
		   '$url',
		   '$ah',
		   '$address',
		   '$reg_date',
		   '$reg_date2'
	 )";


	 $sqlModify="
		update cmp_hotel set
		   $upfileQuery[1]
		   $upfileQuery[2]
		   $upfileQuery[3]
		   $upfileQuery[4]
		   $upfileQuery[5]
		   name = '$name',
		   nation = '$nation',
		   city = '$city',
		   phone = '$phone',
		   url = '$url',
		   ah = '$ah',
		   address = '$address',
		   content = '$content',
		   content2 = '$content2'
		where id_no='$id_no'
	 ";

	if($id_no){
		$sql =$sqlModify;
		$url = "view_${filecode}.php?id_no=$id_no&ctg1=$ctg1";
	}else{
		$sql =$sqlInsert;
		$url = "list_${filecode}.php?ctg1=$ctg1&page=1";
	}

	//checkVar(mysql_error(),$sql);exit;

	if($dbo->query($sql)){
		//If($id_no) msggo("저장하였습니다.",$url);
		//Else echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();self.close()</script>";
        echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();self.close()</script>";

	}else{
		checkVar(mysql_error(),$sql);
	}
	exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){

		$sql = "select *  from $table where id_no=$check[$i]";
		$dbo->query($sql);
		$rs=$dbo->next_record();
		if($rs[filename1]) @unlink("../../public/cmp/$rs[filename1]");
		if($rs[filename2]) @unlink("../../public/cmp/$rs[filename2]");
		if($rs[filename3]) @unlink("../../public/cmp/$rs[filename3]");
		if($rs[filename4]) @unlink("../../public/cmp/$rs[filename4]");

		$sql = "delete from $table where id_no = $check[$i]";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php?ctg1=$ctg1");exit;

}elseif ($mode=="file_drop"){

	$sql = "update $table set $mode2 ='' where id_no=$id_no";
	$dbo->query($sql);
	@unlink("../../public/cmp/$filename");
	redirect2("?id_no=$id_no&$_SESSION[link]");
	exit;

}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
}

$rs[main_staff] =($rs[main_staff])?$rs[main_staff]:"$sessLogin[name] ($sessLogin[id])";
$rs[nation] = ($rs[nation])? $rs[nation] : "한국";


$bit_edit_power = ($CP_ID && $rs[cp_id]!=$CP_ID && $rs[id_no])? 0 : 1; //수정권한
?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;

	if(check_blank(fm.name,'호텔명을',0)=='wrong'){return }
	fm.submit();

}

//파일수정
function fedit(id,bit){
	if(bit==1){
		$(".fsave"+id).hide();
		$(".fdrop"+id).show();
	}else{
		$(".fsave"+id).show();
		$(".fdrop"+id).hide();
	}
}

/*이미지 미리보기*/
function show_file(sfile){

	$("#preview_photo").show();
	$('#preview_photo').load('photo.php', {
	  'sfile': sfile
	});
	//location.href="photo.php?sfile="+sfile;

}
</script>

<div style="padding:0 10px 0 10px">

	<table width="97%" border="0" cellspacing="0" cellpadding="0">
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

    <table border="0" cellspacing="1" cellpadding="3" width="97%">
		<form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td class="subject">호텔명</td>
          <td colspan="3">
	           <?=html_input('name',80,100)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">설명</td>
          <td colspan="3">
	           <?=html_textarea("content",0,10)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">기타정보</td>
          <td colspan="3">
	           <?=html_textarea("content2",0,5)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject" width="15%">국가</td>
          <td>
	           <select name="nation">
	           <?=option_str($NATIONS,$NATIONS,$rs[nation])?>
	           </select>
          </td>

          <td class="subject">지역</td>
          <td>
	           <?=html_input('city',30,28)?>
			,
			<b>호텔-공항 소요시간:</b>
			   <?=html_input('ah',10,28)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">홈페이지</td>
          <td colspan="3">
	           <?=html_input('url',80,100)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">전화번호</td>
          <td colspan="3">
	           <?=html_input('phone',30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">주소</td>
          <td colspan="3">
	           <?=html_input('address',80,150)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <?if($bit_edit_power){?>
		<?
		for($j=1; $j <= $file_rows;$j++){
		$FILENAME= "filename" . $j;
		$FILENAME_REAL= "filename".$j."_real";
		$FILE_NO=$j;
		?>
		<tr>
          <td  class="subject"><?=($j==5)?"로고":"이미지"?><r/td>
          <td colspan="3">
			<?
			if($rs[$FILENAME]):
			@$fileSize = filesize("../../public/cmp/${rs[$FILENAME]}");
			?>

			<span class="hide fsave<?=$FILE_NO?>"><input class=box type="file" name="file<?=$FILE_NO?>" size="40"></span>
			<span class="btn_pack small bold fdrop<?=$FILE_NO?>"><a href="javascript:if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=<?=$FILENAME?>&filename=<?=$rs[$FILENAME]?>'}"> 파일삭제 </a></span>

			&nbsp;&nbsp;
			<span class="btn_pack small bold fdrop<?=$FILE_NO?>"><a href="javascript:fedit('<?=$FILE_NO?>',0)"> 파일수정 </a></span>	&nbsp;
			<span class="btn_pack small bold fsave<?=$FILE_NO?> hide"><a href="javascript:fedit('<?=$FILE_NO?>',1)"> 수정취소 </a></span>

			&nbsp;&nbsp;

			<span class="fdrop<?=$FILE_NO?>">
			<a href="../../include/download.php?file=<?=$rs[$FILENAME]?>&orgin_file_name=<?=$rs[$FILENAME_REAL]?>&dir=public/cmp&ctg1=<?=$ctg1?>"><?=$rs[$FILENAME_REAL]?> (<?=ceil($fileSize/1024)?>KB)</a>
			</span>

			<?else:?>
			<input class=box type="file" name="file<?=$FILE_NO?>" size="40">
			<font color="orange">Alert</font></b> <font color="#666666">: Max <?=$maxFileSize?>MB</font>
			<?endif;?>

			<?If($j==1){?>
			<div style="position:absolute;left:500px;border:3px solid #ccc;display:none" id="preview_photo"></div>
			<?}?>

          </td>
        </tr>
		<tr><td colspan="4" class="tblLine"></td></tr>
		<?}?>
        <?}?>


		<tr>
			<td colspan="4" align="center" style="padding:5px">
				<?
				for($j=1; $j <= $file_rows;$j++){
					$FILENAME= "filename" . $j;
				?>
					<img src="../../public/cmp/<?=$rs[$FILENAME]?>" onerror="this.src='/renew/images/main/thumb_recoom02.jpg'" width='150' height='100' style="border:1px solid #ccc">
				<?
				}
				?>
			</td>
		</tr>


        <tr><td colspan="12" bgcolor='#E1E1E1' height=1></td></tr>

        <tr>
		  <td colspan=10>
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
                    <?if($bit_edit_power){?>
                    <td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
                    <?}?>
					<td><span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span></td>
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

</div>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>