<?
include_once("script/include_common_file.php");


$sql = "select * from popup where id_no=$id_no";
list($rows) = $dbo->query($sql);

if($rows){
	$rs = $dbo->next_record();
}else{
	echo "<script>self.close()</script>"; exit;
}

$cookie_name = "sessPopup_" . $id_no;

if($mode=='reload'){
	if($nopen){
		setcookie ($cookie_name, 1,time()+86400);
	}
	echo "<script>self.close()</script>";
}
?>
<html lang="ko">
<head>
<title><?=$SITE_NAME?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
<!--
body,td,select,textarea { font-family:verdana,굴림; font-size:11px;letter-spacing:-1px; line-height:180%; text-decoration:none; }
input,textarea,select { font-family:verdana,굴림; font-size:11px;letter-spacing:-1px;  text-decoration:none; color: #808080;text-align: left }
-->
</style>
<script language="JavaScript">
<!--
<?if($LEFT):?>
var topPoint = (screen.height/2)-(<?=$HEIGHT?>/2);
window.moveTo(<?=$LEFT?>,topPoint);
<?endif;?>
//-->
</script>
</head>

<body bgcolor="<?=$rs[bgcolor]?>" text="#000000" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" <?if($rs[filename]):?>background="../new/public/popup/<?=$rs[filename]?>"<?endif;?>>
<form name=fmData>
<input type=hidden name=mode value="reload">
<input type=hidden name=id_no value="<?=$rs[id_no]?>">
<table width="100%" height="<?=$rs[height]?>" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign=top <?if($rs[opener_link]){?>style="cursor:pointer" onclick="opener.location.href='<?=$rs[opener_link]?>';self.close()"<?}?>>
      <?if($rs[filename2]):?>
      <img src="../new/public/popup/<?=$rs[filename2]?>">
      <?endif;?>
      <?=stripslashes($rs[content])?>
    </td>
  </tr>
  <tr>
    <td height="25" align="right" bgcolor="#e6e6e6">
      <?if($rs[chkopen]):?>
	  <input type="checkbox" name="nopen" value="1">
      <font color="#000000">다음부터 이 창 열지 않음</font>
	  &nbsp;&nbsp;&nbsp;
	  <?endif;?>
	  <a href="javascript:document.fmData.submit()" onfocus="blur(this)" style="color:FFFFFF;text-decoration: none"><img src="./images/ez_board/btn_close.gif" border="0"  align="absmiddle"></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>
