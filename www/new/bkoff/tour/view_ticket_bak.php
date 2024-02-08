<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_ticket";
$MENU = "tour";
$TITLE = "렌트카 관리";



#### mode
if($mode=="save"){

		if($_FILES["file1"]["size"]){
			#------------------------------------------
			$path="../../public/ticket";	//업로드할 파일의 경로
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

		$price_origin_adult = price_format($price_origin_adult);
		$price_origin_youth = price_format($price_origin_youth);
		$price_origin_child = price_format($price_origin_child);
		$price_adult = price_format($price_adult);
		$price_youth = price_format($price_youth);
		$price_child = price_format($price_child);

		$sqlInsert="
		   insert into ez_ticket (
			  code,
			  subject,
			  filename,
			  price_origin_adult,
			  price_origin_youth,
			  price_origin_child,
			  price_adult,
			  price_youth,
			  price_child
		  ) values (
			  '$code',
			  '$subject',
			  '$upfile1',
			  '$price_origin_adult',
			  '$price_origin_youth',
			  '$price_origin_child',
			  '$price_adult',
			  '$price_youth',
			  '$price_child'
		)";

		$sqlModify="
		   update ez_ticket set
			  $upfileQuery1
			  code = '$code',
			  subject = '$subject',
			  price_origin_adult = '$price_origin_adult',
			  price_origin_youth = '$price_origin_youth',
			  price_origin_child = '$price_origin_child',
			  price_adult = '$price_adult',
			  price_youth = '$price_youth',
			  price_child = '$price_child'
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
		if($dbo->query($sql)){
			$sql = "delete from ez_ticket_setting where code = $check[$i]";
			$dbo->query($sql);
		}
	}
	redirect2("list_${filecode}.php");exit;

}elseif ($mode=="file_drop"){
		$sql = "update $table set $mode2 ='' where id_no=$id_no";
		$dbo->query($sql);
		@unlink("../../public/ticket/$filename");
		redirect2("?id_no=$id_no&$_SESSION[link]");exit;

}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
	$rs[price] = number_format($rs[price]);
}

$code = ($rs[code])?$rs[code] : getUniqNo();
//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(){

	var fm  =document.fmData;

	if(check_blank(fm.subject,'상품명을',0)=='wrong'){return false}
	if(check_blank(fm.price_adult,'성인 가격을',0)=='wrong'){return false}
	if(check_blank(fm.price_youth,'청소년 가격을',0)=='wrong'){return false}
	if(check_blank(fm.price_child,'소인 가격을',0)=='wrong'){return false}

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
          <td class="subject" width="20%">상품명</td>
          <td>
			<?=html_input('subject',50,50)?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td class="subject">성인</td>
          <td>
			 원가격 : <?=html_input('price_origin_adult',10,10,'box numberic')?> - 실제가격 <?=html_input('price_adult',10,10,'box numberic')?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td class="subject">청소년</td>
          <td>
			 원가격 : <?=html_input('price_origin_youth',10,10,'box numberic')?> - 실제가격 <?=html_input('price_youth',10,10,'box numberic')?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td class="subject">어린이</td>
          <td>
			 원가격 : <?=html_input('price_origin_child',10,10,'box numberic')?> - 실제가격 <?=html_input('price_child',10,10,'box numberic')?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

	<?if($rs[filename]):
		@$fileSize = filesize("../../public/ticket/${rs[filename]}");
		?>
        <tr>
          <td  class="subject">파일<r/td>
          <td>
			<input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename&filename=<?=$rs[filename]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename]?>&orgin_file_name=<?=$rs[filename_real]?>&dir=public/ticket" onFocus="blur(this)"><?=$rs[filename_real]?> (<?=ceil($fileSize/1024)?>KB)</a>

			<div style="padding-top:20px"><img src="../../public/ticket/<?=$rs[filename]?>" width="139" height="108"></div>

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

