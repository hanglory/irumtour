<?
include_once("../include/common_file.php");


#### Menu
$filecode = substr(SELF,5,-4);
$TITLE = "메인배너";
$MENU = "basic";
$table = "ez_nbanner1";


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
			$filename=$naming . time()."_1";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile1 = $upfile;
			$upfile1_real = $_FILES["file1"]["name"];
			$upfileQuery1 = ($upfile)? "filename = '$upfile1', ":"" ;
		}
		
		if($_FILES["file2"]["size"]){
			#------------------------------------------
			$path="../../public/banner";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file2"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file2"]["name"];	//파일의 이름
			$fname_size=$_FILES["file2"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file2"]["type"];		//파일의 type
			$filename=$naming . time()."_2";		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile2 = $upfile;
			$upfile2_real = $_FILES["file2"]["name"];
			$upfileQuery2 = ($upfile)? "filename2 = '$upfile2', ":"" ;
		}		

        if(strstr($url,"me2.do")){
            echo "
                <script>
                    alert('배너에는 단축 URL을 사용할 수 없습니다.');
                    parent.document.fmData.url.focus();
                </script>
            ";
            exit;
        }
		$url = set_tour_link($url);

		$sqlInsert="
		   insert into $table (
              cp_id,  
			  text1,
			  text2,
			  text3,
			  target,
			  url,
			  filename,
			  filename2,
			  bit_hide,
			  seq
		  ) values (
			  '$CP_ID',
              '$text1',
			  '$text2',
			  '$text3',
			  '$target',
			  '$url',
			  '$upfile1',
			  '$upfile2',
			  '$bit_hide',
			  '0'
		)";

		$sqlModify="
		   update $table set
			  $upfileQuery1
			  $upfileQuery2
			  bit_hide = '$bit_hide',
			  text1 = '$text1',
			  text2 = '$text2',
			  text3 = '$text3',
			  url = '$url',
			  target = '$target'
		   where id_no='$id_no'
              $FILTER_PARTNER_QUERY
		";

		if($id_no){
			$sql = $sqlModify;
			$url = "view_${filecode}.php?id_no=$id_no&assort=$assort";
		}else{
			$sql = $sqlInsert;
			$url = "list_${filecode}.php?assort=$assort";
		}

        if($bit_hide==2) $url = "list_nbanner.php";

		if($dbo->query($sql)){

			if(!$id_no){
				$sql = "update $table set seq=seq+1";
				$dbo->query($sql);
			}

            echo "
                <script>
                    alert('저장하였습니다.');
                    parent.location.href='$url';
                </script>
            ";
		}else{
			checkVar(mysql_error(),$sql);
		}
		exit;

}elseif ($mode=="file_drop"){
		$sql = "update $table set $mode2 ='' where id_no=$id_no $FILTER_PARTNER_QUERY";
		$dbo->query($sql);
		@unlink("../../public/banner/$filename");
		redirect2("?id_no=$id_no&$sessLink");exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){
		$sql = "select *  from $table where id_no=$check[$i] $FILTER_PARTNER_QUERY";
		$dbo->query($sql);
		$rs=$dbo->next_record();
		if($rs[filename]) @unlink("../../public/banner/$rs[filename]");

		$sql = "update $table set seq=seq-1 where seq > '$rs[seq]' $FILTER_PARTNER_QUERY";
		$dbo->query($sql);

		$sql = "delete from $table where id_no = $check[$i] $FILTER_PARTNER_QUERY";
		$dbo->query($sql);

        $sql = "delete from $table where id_no_origin = $check[$i] $FILTER_PARTNER_QUERY";
        $dbo->query($sql);

	}
	redirect2("list_${filecode}.php?assort=$assort");exit;

}elseif ($mode=="hide"){

    for($i = 0; $i < count($check);$i++){
        $sql = "update $table set bit_hide=1  where id_no = $check[$i] $FILTER_PARTNER_QUERY";
        $dbo->query($sql);
    }
    redirect2("list_${filecode}.php?assort=$assort");exit;

}elseif ($mode=="file_drop"){
		$sql = "update $table set filename ='' where id_no=$id_no $FILTER_PARTNER_QUERY";
		$dbo->query($sql);
		@unlink("../../public/banner/$filename");
		redirect2("?id_no=$id_no&$_SESSION[sessLink]");exit;
}else{
	$sql = "select * from $table where id_no=$id_no $FILTER_PARTNER_QUERY";
	$dbo->query($sql);
	$rs= $dbo->next_record();

    if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
        checkVar(mysql_error(),$sql);
        checkVar("cp_id",$rs[cp_id]);
        checkVar("id_no_origin",$rs[id_no_origin]);
        checkVar("seq",$rs[seq]);
    }

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
		<form name="fmData"  method="post" enctype="multipart/form-data" target="actarea">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="assor" value='<?=$assort?>'>

        <tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>
        <tr>
          <td class="subject" width="20%">text1</td>
          <td>
            <input class="box" type="text" name="text1" value="<?=$rs[text1]?>" size="80" maxlength="22">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">text2</td>
          <td>
            <!-- <input class="box" type="text" name="text2" value="<?=$rs[text2]?>" size="80" maxlength="150"> -->
            <textarea name="text2" class="box" rows="3" style="width:300px"><?=$rs[text2]?></textarea>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">text3</td>
          <td>
            <input class="box" type="text" name="text3" value="<?=$rs[text3]?>" size="80" maxlength="24">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


		<tr>
          <td class="subject">URL</td>
          <td>
            <input class="box" type="text" name="url" value="<?=$rs[url]?>" size="80" maxlength="190">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

		<tr>
          <td class="subject">열기/보이기</td>
          <td>
            <select name="target"><?=option_str("현재창,새창","_self,_blank",$rs[target])?></select>
            <?if($CP_ID){?>
            <select name="bit_hide"><?=option_str("보이기,감추기,지우기","0,1,2",$rs[bit_hide])?></select>
            <?}else{?>
            <select name="bit_hide"><?=option_str("보이기,감추기","0,1",$rs[bit_hide])?></select>
            <?}?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

	<?if($rs[filename]):
		@$fileSize = filesize("../../public/banner/${rs[filename]}");
		?>
        <tr>
          <td class="subject">이미지<br />2000 * 600</td>
          <td height="20">
            <?if(!$rs[id_no_origin]){?>
			<input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename&filename=<?=$rs[filename]?>'}">
            <?}?>
            <a class=soft href="../../include/download.php?file=<?=$rs[filename]?>&size=<?=$fileSize?>" onFocus="blur(this)"><?=$rs[filename]?> (<?=ceil($fileSize/1024)?>KB)</a>
			<div style="padding:15px 0 15px 0"><img src="../../public/banner/<?=$rs[filename]?>" width="500" onerror="this.src='/renew/images/review/thumb_review.gif'"></div>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
	<?else:?>
		<tr>
          <td  class="subject">이미지</td>
          <td height="20">
            <input class="box" type="file" name="file1" size=40> 2000 * 600
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
	<?endif;?>
	
	<?if($rs[filename2]):
		@$fileSize = filesize("../../public/banner/${rs[filename2]}");
		?>
        <tr>
          <td class="subject">이미지<br />375X250<!-- 1000x470 --> </td>
          <td height="20">
            <?if(!$rs[id_no_origin]){?>
			<input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename2&filename=<?=$rs[filename2]?>'}">
            <?}?>
            <a class=soft href="../../include/download.php?file=<?=$rs[filename2]?>&size=<?=$fileSize?>" onFocus="blur(this)"><?=$rs[filename2]?> (<?=ceil($fileSize/1024)?>KB)</a>
			<div style="padding:15px 0 15px 0"><img src="../../public/banner/<?=$rs[filename2]?>" width="500" onerror="this.src='/renew/images/review/thumb_review.gif'"></div>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
	<?else:?>
		<tr>
          <td  class="subject">이미지</td>
          <td height="20">
            <input class="box" type="file" name="file2" size=40> 375X250 
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