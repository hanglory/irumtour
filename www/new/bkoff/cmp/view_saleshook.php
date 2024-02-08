
<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"인사관리");



####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_saleshook";
$MENU = "cmp_basic";
$TITLE = "판촉물 사용승인 현황";
$file_rows = 1; //파일 업로드 갯수
$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";


#### staff
$sql = "select * from cmp_staff where bit_hide<>1 order by name asc";
$dbo->query($sql);
while($rs=$dbo->next_record()){
	$STAFF.=",$rs[name] ($rs[id])";
}


#### operation
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

if ($mode=="save"){

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$sqlInsert="
	   insert into cmp_hrm (
	      assort,
	      name,
	      date_out,
	      date_return,
	      date_req,
	      place,
	      staff,
	      filename1,
	      filename1_real,
	      reg_date,
	      reg_date2
	  ) values (
	      '$assort',
	      '$name',
	      '$date_out',
	      '$date_return',
	      '$date_req',
	      '$place',
	      '$staff',
	      '$upfiles[1]',
	      '$upfile_real[1]',
	      '$reg_date',
	      '$reg_date2'
	)";

	$sqlModify="
	   update cmp_hrm set
	      $upfileQuery[1]
	      assort = '$assort',
	      name = '$name',
	      date_out = '$date_out',
	      date_return = '$date_return',
	      date_req = '$date_req',
	      place = '$place',
	      staff = '$staff'
	   where id_no='$id_no'
	";

	if($id_no){
		$sql =$sqlModify;
		$url = "view_${filecode}.php?id_no=$id_no&ctg1=$ctg1";
	}else{
		$sql =$sqlInsert;
		$url = "list_${filecode}.php?ctg1=$ctg1";
	}

	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql);exit;}

	if($dbo->query($sql)){
		If($id_no) echo"<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();location.href='$url'</script>";
		Else echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();self.close()</script>";
	}else{
		checkVar(mysql_error(),$sql);
	}
	exit;

}elseif ($mode=="drop"){

	$sql = "delete from $table where id_no = $id_no";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	echo "<script>opener.location.reload();self.close()</script>";
	exit;

}elseif ($mode=="file_drop"){

	$sql = "update $table set $mode2 ='' where id_no=$id_no";
	$dbo->query($sql);
	@unlink("../../public/cmp/$filename");
	redirect2("?id_no=$id_no&$_SESSION[link]");
	exit;

}elseif ($mode=="proof"){

	$today=date("Y/m/d");
	if($_SESSION["sessLogin"]["id"]=="sanha" || $_SESSION["sessLogin"]["id"]=="chadori" || $REMOTE_ADDR=="106.246.54.27"){
		$sql = "update $table set proof='$today' where id_no = $id_no";
		$dbo->query($sql);
	}
	back();
	exit;

}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
	$rs[staff_] = $rs[staff];
}

$rs[main_staff] =($rs[main_staff])?$rs[main_staff]:"$sessLogin[name] ($sessLogin[id])";
?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;
	fm.mode.value = "save";

	if(fm.assort_tmp.value==""){alert('구분을 선택해 주세요.');return;}

	if(check_select(fm.name,'고객명')=='wrong'){return }
	if(check_blank(fm.date_out,'출국일을',0)=='wrong'){return }
	if(check_blank(fm.date_return,'귀국일',0)=='wrong'){return }
	if(check_blank(fm.date_req,'신청',0)=='wrong'){return }
	fm.submit();

}

function proof(){
	var fm = document.fmData;
	fm.mode.value = "proof";

	if(confirm('승인하시겠습니까?')){
		fm.submit();
	}
}

function drop(){
	var fm = document.fmData;
	fm.mode.value = "drop";

	if(confirm('삭제하시겠습니까?')){
		fm.submit();
	}
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
	/*
	$("#preview_photo").show();
	$('#preview_photo').load('photo.php', {
	  'sfile': sfile
	});
	//location.href="photo.php?sfile="+sfile;
	*/
}


</script>


<style type="text/css">
.readonly{border:0}
</style>

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
          <td class="subject" width="15%">구분</td>
          <td>
	           <?=radio($ASSORT,$ASSORT,$rs[assort],'assort')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <tr>
          <td class="subject">직원명</td>
          <td>
	           <select name="name">
	           		<?=option_str("직원명".$STAFF,$STAFF,$rs[name])?>
	           </select>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">출국일</td>
          <td>
	           <?=html_input("date_out",13,10,'box dateinput')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">귀국일</td>
          <td>
	           <?=html_input("date_return",13,10,'box dateinput')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">신청일</td>
          <td>
	           <?=html_input("date_req",13,10,'box dateinput')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <?if($rs[proof]){?>
        <tr>
          <td class="subject" width="15%">승인</td>
          <td>
	           <?=$rs[proof]?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <?}?>

        <tr>
          <td class="subject" width="15%">행선지</td>
          <td>
	           <?=html_input("place",30,45)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

		<?
		for($j=1; $j <= $file_rows;$j++){
		$FILENAME= "filename" . $j;
		$FILENAME_REAL= "filename".$j."_real";
		$FILE_NO=$j;
		?>
		<tr>
          <td  class="subject">첨부파일</td>
          <td>
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
			<a href="../../include/download.php?file=<?=$rs[$FILENAME]?>&orgin_file_name=<?=$rs[$FILENAME_REAL]?>&dir=public/cmp&ctg1=<?=$ctg1?>" onmouseover="show_file('../../public/cmp/<?=$rs[$FILENAME]?>')" onmouseout="hide_file()"><?=$rs[$FILENAME_REAL]?> (<?=ceil($fileSize/1024)?>KB)</a>
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
		<tr><td colspan="2" class="tblLine"></td></tr>
		<?}?>

        <tr><td colspan="12" bgcolor='#E1E1E1' height=1></td></tr>

        <tr>
		  <td colspan=10>
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="370" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td>
						<span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span>
						&nbsp;&nbsp;
						<?if($rs[id_no]){?>
							<?if($_SESSION["sessLogin"]["id"]=="sanha" || $_SESSION["sessLogin"]["id"]=="chadori" || $REMOTE_ADDR=="106.246.54.27"){?>
							<span class="btn_pack medium bold"><a href="javascript:proof()"> 승인 </a></span>
							&nbsp;&nbsp;
							<?}?>
							<?if($_SESSION["sessLogin"]["id"]=="sanha" || $_SESSION["sessLogin"]["id"]=="chadori" || $rs[staff]==$staff || $REMOTE_ADDR=="106.246.54.27"){?>
							<span class="btn_pack medium bold"><a href="javascript:drop()"> 삭제 </a></span>
							&nbsp;&nbsp;
							<?}?>
						<?}?>

						<span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
					</td>
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