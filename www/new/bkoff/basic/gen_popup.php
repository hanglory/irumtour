<?
#### 페이지의 캐쉬읽기를 금지
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");


#### Include
include_once('../../include/fun_basic.php');
include_once('../include/proof.php');


#### Menu
$MENU = "basic";


#### operation
$setfilename= "../../public/inc/popup.inc";

if ($mode=="save"){

		#------------------------------------------
		$path="../../public/popup";		//업로드할 파일의 경로
		$maxsize="4194304"	;				//4MB	업로드 가능한 최대 사이즈 제한
		$fname=$background;						//파일이름을 담고 있는 변수 이름
		$fname_name=$background_name;	//파일의 이름
		$fname_size=$background_size;		//파일의 사이즈
		$fname_type=$background_type;		//파일의 type
		$filename=$sessLogin[id] .'_popbg';		//파일이름 작명
		#------------------------------------------
		include("../../include/file_upload.php");
		$background_img =($background && $background != 'none' )? "\$BACKGROUND='$upfile';\n" : "\$BACKGROUND='$background_prev';\n";

		#------------------------------------------
		$path="../../public/popup";		//업로드할 파일의 경로
		$maxsize="4194304"	;				//4MB	업로드 가능한 최대 사이즈 제한
		$fname=$photo;						//파일이름을 담고 있는 변수 이름
		$fname_name=$photo_name;	//파일의 이름
		$fname_size=$photo_size;		//파일의 사이즈
		$fname_type=$photo_type;		//파일의 type
		$filename=$sessLogin[id] .'_pop';		//파일이름 작명
		#------------------------------------------
		include("../../include/file_upload.php");
		$photo_img =($photo && $photo != 'none' )? "\$PHOTO='$upfile';\n" : "\$PHOTO='$photo_prev';\n";

		//사이트 정보
		$domain = str_replace("http://","",$domain);
		$fp=fopen($setfilename, "w");	//파일 쓰기모드로 열기, 파일이 있다면 overwirte

		$config ="<?\n";
		$config .="##-------------------------------------------\n";
		$config .="##주의! (이 파일을 수동으로 조작하지 마세요.)\n";
		$config .="##-------------------------------------------\n";

		$config .="\$OPEN='$open';\n";
		$config .="\$LEFT='$left';\n";
		$config .="\$TITLE='$title';\n";
		$config .="\$WIDTH='$width';\n";
		$config .="\$HEIGHT='$height';\n";
		$config .="\$SCROLL='$scrool';\n";
		$config .="\$BGCOLOR='$bgcolor';\n";
		$config .= $background_img;
		$config .="\$CONTENT='$content';\n";
		$config .= $photo_img;
		$config .="\$CHKOPEN='$chkopen';\n";

		$config .="?";
		$config .=">";


		if(!fwrite($fp,$config)){
			error("환경설정파일을 생성하던 중 예기치 못한 에러가 발생하였습니다. 관리자에게 문의하여 주시기 바랍니다.");exit;
		}
		fclose($fp);

		msggo("저장하였습니다.","?");
}else{
	@include($setfilename);

	$OPEN = ($OPEN)? $OPEN : "0";
	$SCROLL = ($SCROLL)? $SCROLL : "0";
	$CHKOPEN = ($CHKOPEN)? $CHKOPEN : "0";
}
//-------------------------------------------------------------------------------
?>
<html>
<head>
<title>관리자 페이지</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../include/basic.css" type="text/css">
<script language="JavaScript" src="../include/form_check.js"></script>
<script language="JavaScript" src="../include/function.js"></script>
<script language="JavaScript">
<!--
function chkForm(fm){
	if(check_blank(fm.width,'창 사이즈를',2)=='wrong'){return false}
	if(check_blank(fm.height,'창 사이즈를',2)=='wrong'){return false}
	if(check_num(fm.width,'창 사이즈')=='wrong'){return false}
	if(check_num(fm.height,'창 사이즈')=='wrong'){return false}
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
</head>

<body text="#000000" bgcolor="FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<?include("../top.html");?>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td width=178 valign=left>
	<?include("../basic_left.html");?>
	</td>
    <td valign=top>

	<!--내용이 들어가는 곳 시작-->

	   <table border="0" cellspacing="0" cellpadding="9" width="750">
		<tr><td colspan=7  bgcolor='#5E90AE' height=2></td></tr>
		<tr bgcolor="#F7F7F6"'>
			<td><img src="../images/icon/arrow.jpg" align="absmiddle">   <b><font color="#000000">팝업창 생성</font></b></td>
		  </tr>
		  <tr>
			<td background="../images/common/dot.gif" height=1></td>
		  </tr>
		</table>

      <br>

    <table border="0" cellspacing="1" cellpadding="3" width="750">
		<form name=fmSite onSubmit="return chkForm(document.fmSite)" method=post enctype="multipart/form-data">
		<input type=hidden name=mode value='save'>
		<input type=hidden name=photo_prev value='<?=$PHOTO?>'>
		<input type=hidden name=background_prev value='<?=$BACKGROUND?>'>

        <tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>
	    <tr>
          <td height="25" align="right" bgcolor='#F7F7F6' style="font-weight: bold">팝업창 활성화</td>
          <td height="25">
             <?=radio("활성화,팝업창 띄우지 않음","1,0",$OPEN,'open')?>
          </td>
        </tr>
		<tr><td colspan=2 bgcolor='#E1E1E1'></td></tr>

        <tr>
          <td height="25" width=140 align="right" bgcolor='#F7F7F6' style="font-weight: bold">팝업창 제목</td>
          <td height="25">
            <input class=box type="text" name="title" value="<?=$TITLE?>" size=40>
			<font color='orange'>Sample</font> <font color="#666666">: [이벤트] 당첨자 발표...</font>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#E1E1E1'></td></tr>
        <tr>
          <td height="25" align="right" bgcolor='#F7F7F6' style="font-weight: bold">창 사이즈</td>
          <td height="25">
             가로 : <input class=box type="text" name="width" value="<?=$WIDTH?>" size=3 maxlength=3>
			 세로 : <input class=box type="text" name="height" value="<?=$HEIGHT?>" size=3 maxlength=3>
			 왼쪽 위치 : <input class=box type="text" name="left" value="<?=$LEFT?>" size=3 maxlength=3> 없는 경우 중앙 정렬
			  <font color='orange'>Help</font> <font color="#666666">: 단위 : 픽셀(pixel)</font>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#E1E1E1'></td></tr>
        <tr>
          <td height="25" align="right" bgcolor='#F7F7F6' style="font-weight: bold">스크롤바</td>
          <td height="25">
             <?=radio("있음,없음","1,0",$SCROLL,'scrool')?>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#E1E1E1'></td></tr>

		<tr>
          <td height="25" align="right" bgcolor='#F7F7F6' style="font-weight: bold">배경색</td>
          <td height="25">
             <input class=box type="text" name="bgcolor" value="<?=$BGCOLOR?>" size=7 maxlength=7> (RGB칼라값)
			 <font color='orange'>Sample</font> <font color="#666666">: #000000</font>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#E1E1E1'></td></tr>

		<tr>
          <td height="25" align="right" bgcolor='#F7F7F6' style="font-weight: bold">배경이미지</td>
          <td height="25">
             <input class=box type="file" name="background" value="<?=$BACKGROUND?>" size=30>
			 <?if($BACKGROUND):
			 $file = '../../public/popup/'.$BACKGROUND;
			 $pic_info=GetImageSize($file);
			 ?>
			 <input type=button class=button value='이미지보기' onclick="newWin('../../image_viewer.php?file=public/popup/<?=$BACKGROUND?>',<?=$pic_info[0]?>,<?=$pic_info[1]?>)" onFocus="blur(this)">
			 <input type=button class=button value='이미지삭제' onFocus="blur(this)" onClick="dropImg('background')" >
			 <?endif;?>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#E1E1E1'></td></tr>

		<tr>
          <td height="25" align="right" bgcolor='#F7F7F6' style="font-weight: bold">내용</td>
          <td height="25">
             <textarea class=box cols=80 rows=5 name=content><?=stripslashes(stripslashes($CONTENT))?></textarea><br>
			 <font color='orange'>Help</font> <font color="#666666">: 내용은 HTML 형식에 맞아야 합니다.<br> HTML 태그에 자신이 없으신 경우 창 크기와 동일한 이미지를 제작하여 사진올리기로 업로드하시면 됩니다.</font>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#E1E1E1'></td></tr>

		<tr>
          <td height="25" align="right" bgcolor='#F7F7F6' style="font-weight: bold">사진올리기</td>
          <td height="25">
             <input class=box type="file" name="photo" value="<?=$PHOTO?>" size=30>
			 <?if($PHOTO):
			 $file = '../../public/popup/'.$PHOTO;
			 $pic_info=GetImageSize($file);
			 ?>
			 <input type=button class=button value='이미지보기' onclick="newWin('../../image_viewer.php?file=public/popup/<?=$PHOTO?>',<?=$pic_info[0]?>,<?=$pic_info[1]?>)" onFocus="blur(this)">
			 <input type=button class=button value='이미지삭제' onFocus="blur(this)" onClick="dropImg('photo')" >
			 <?endif;?>
			 <br><font color='orange'>Help</font> <font color="#666666">: 사진을 표시한 후 내용을 표시합니다.</font>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#E1E1E1'></td></tr>

        <tr>
          <td height="25" align="right" bgcolor='#F7F7F6' style="font-weight: bold">다시열지 않음 버튼 생성</td>
          <td height="25">
             <?=radio("사용,사용하지 않음","1,0",$CHKOPEN,'chkopen')?>
          </td>
        </tr>
		<tr><td colspan=2 bgcolor='#E1E1E1'></td></tr>

        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>

        <tr>
	  <td colspan=10>

		  <!-- Button Begin---------------------------------------------->
                  <table border="0" cellspacing="0" cellpadding="0" align="right">
				    <tr>
                      <td width = 60><input type=image src="../images/button/save.gif"  border=0></td>
                      <td width = 60><img src="../images/button/cancel.gif"  border=0 style='cursor:hand' onclick="document.fmSite.reset()"></td>
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




	  <!-- 도움말 -->
		<table width=750  cellspacing="0" cellpadding="1" >
		<tr>
		<td style="border:1 #E1E1E1 solid;" BGCOLOR=#FFFFFF>
			<table width=100%>
				<td width=70 align=right valign=top><img src="../images/icon/help.jpg" width="64" height="82" border="0"></td>
				<td style="color:gray" bgcolor='#F7F7F6'>
					<!-- 도움말내용 -->
					<ul>
						<li> 사이트 방문시 팝업되는 창을 생성할 수 있습니다.
					</ul>
				</td>
			<table>
		</td>
		</tr>
		</table>

	<!--내용이 들어가는 곳 끝-->


	</td>
  </tr>
</table>


		<!-- Copyright -->
		<?include_once("../copyright.html");?>

</body>
</html>