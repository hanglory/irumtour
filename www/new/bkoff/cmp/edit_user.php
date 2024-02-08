<?
include_once("../include/common_file.php");



####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_staff";
$MENU = "cmp_user";
$TITLE = "개인정보수정";
$time = time();


#### mode
if($mode=="save"){
    
    $pwdQuery = ($pwd)? " pwd= password('$pwd'), ":"";

    $sql="
       update $table set
          $pwdQuery
          phone1 = '$phone1',
          phone2 = '$phone2',
          phone3 = '$phone3',
          cell1 = '$cell1',
          cell2 = '$cell2',
          cell3 = '$cell3',
          email = '$email'
       where id_no='$id_no'
    ";

    if($dbo->query($sql)){
        error("저장하였습니다.");
    }else{
        checkVar(mysql_error(),$sql);
    }
    exit;

}else{

    $sql = "
            select 
                * 
            from $table 
            where id='$user_id'
                and id<>''
            limit 1
        ";
    $dbo->query($sql);
    $rs= $dbo->next_record();
}
//-------------------------------------------------------------------------------
?>
<?include("../top.html");?>
<script language="JavaScript">
<!--
function chkForm(){

    var fm  =document.fmData;

    if(fm.pwd.value!=""){
        if(fm.pwd.value != fm.pwd_check.value){alert('비밀번호가 서로 다릅니다.   ');fm.pwd_check.focus();return }
    }

    fm.submit();
}


$(function(){

    $("#cell1").mask("999");
    $("#cell2").mask("9999");
    $("#cell3").mask("9999");

});
//-->
</script>


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


        <br/>


        <table border="0" cellspacing="1" cellpadding="3" width="100%">

        <form name="fmData" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" value="save">
        <input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
        <input type="hidden" name="id_chk" value='0'>


        <tr><td colspan="4"  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <tr>
          <td class="subject" width="140">아이디</td>
          <td>
            <b><?=$rs[id]?></b>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <tr>
          <td class="subject">이름</td>
          <td>
            <?=$rs[name]?> <?=$rs[mposition]?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">비밀번호</td>
          <td>
            <input class="box" type="password" name="pwd"  size=20 maxlength="20"> (변경할 경우에만 입력하세요.)
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <tr>
          <td class="subject">비밀번호 확인</td>
          <td>
            <input class="box" type="password" name="pwd_check"  size=20 maxlength="20"> (변경할 경우에만 입력하세요.)
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">연락처</td>
          <td>
            <input class="box" type="text" name="phone1" value="<?=$rs[phone1]?>" size="5" maxlength="3"> -
            <input class="box" type="text" name="phone2" value="<?=$rs[phone2]?>" size="5" maxlength="4"> -
            <input class="box" type="text" name="phone3" value="<?=$rs[phone3]?>" size="5" maxlength="4">
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">핸드폰번호</td>
          <td>
            <input class="box" type="text" name="cell1" id="cell1" value="<?=$rs[cell1]?>" size="5" maxlength="4"> -
            <input class="box" type="text" name="cell2" id="cell2" value="<?=$rs[cell2]?>" size="5" maxlength="4"> -
            <input class="box" type="text" name="cell3" id="cell3" value="<?=$rs[cell3]?>" size="5" maxlength="4">
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">이메일</td>
          <td>
            <input class="box" type="text" name="email" value="<?=$rs[email]?>" size="30" maxlength="30">
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>




        <?if($rs[id_no]){?>
        <tr><td colspan="4">&nbsp;</td></tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">등록일</td>
          <td>
            <?=$rs[reg_date]?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <?}?>

        <tr><td colspan="4" bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan="4" bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
        <td colspan="4">
          <!-- Button Begin---------------------------------------------->
          <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
            <tr align="right">
                <td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
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

