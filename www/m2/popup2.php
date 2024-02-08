<?
include_once("./script/include_common_mobile.php");



$sql = "
    select * from popup where id_no=$id_no and cp_id='$CID'
    limit 1
";
list($rows) = $dbo->query($sql);

if($rows){
    $rs = $dbo->next_record();
}else{
    echo "<script>self.close()</script>"; exit;
}

$cookie_name = "sessPopup_" . $id_no;

if($mode=='reload'){

    if($nopen){
        $_SESSION["chk_popup"] = str_replace("@${id_no}@", "", $_SESSION["chk_popup"]);
        $_SESSION["chk_popup"] .= "@${id_no}@";
    }
    echo "<script>location.href='index.html'</script>";
}
?>
<html>
<head>
<title><?=$TITLE?></title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<style type="text/css">
body,td,select,textarea { font-family:verdana,굴림; font-size:11px;letter-spacing:-1px; line-height:180%; text-decoration:none; }
input,textarea,select { font-family:verdana,굴림; font-size:11px;letter-spacing:-1px;  text-decoration:none; color: #808080;text-align: left }
.pic {
    width: 100% !important;
}
.bar_close{
    padding:10px;
    background: #e6e6e6;
    text-align: right;
    outline:1px solid #666;
}
.bx_pop_content{
    outline:1px solid #666;
}
</style>
<script type="text/javascript">
function pop_close(){
    parent.document.getElementById('popup_<?=$rs[id_no]?>').style.display='none';
}    
</script>
</head>

<body bgcolor="<?=$rs[bgcolor]?>" text="#000000" leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" <?if($rs[filename]):?>background="/html2/public/popup/<?=$rs[filename]?>"<?endif;?>>
<form name=fmData method="get" action="popup2.php">
<input type=hidden name=mode value="reload">
<input type=hidden name=id_no value="<?=$rs[id_no]?>">
<table width="100%" height="<?=$rs[height]?>" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td valign="top">
      
      <div class="bx_pop_content" <?if($rs[opener_link]){?>style="cursor:pointer" onclick="location.href='<?=$rs[opener_link]?>';self.close()"<?}else{?>onclick="$('#popup_<?=$id_no?>').hide()"<?}?>>  
          <?if($rs[filename2]):?>
          <img src="/html2/public/popup/<?=$rs[filename2]?>" class="pic" style="width:100%;height:100%;">
          <?endif;?>
          <?=stripslashes(nl2br($rs[content]))?>
      </div>

      <?if($rs[chkopen]):?>
      <div class="bar_close">
          <label>
          <input type="checkbox" name="nopen" value="1" onclick="document.fmData.submit()">
          <font color="#000000">다음부터 이 창 열지 않음</font> 
          </label>
          &nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;
          <a href="javascript:pop_close()"><img src="/renew/images/ez_board/btn_close.gif" border="0"  align="absmiddle" style="width:20px;height:20px"></a>
          &nbsp;&nbsp;&nbsp;
      </div>
      <?endif;?>        

    </td>
  </tr>

</table>
</form>
</body>
</html>
