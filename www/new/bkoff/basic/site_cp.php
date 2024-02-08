<?
include_once("../include/common_file.php");





#### Men
$filecode = substr(SELF,5,-4);
$TITLE = "사이트정보";
$MENU = "basic";



#### operation
$filename= "../../public/cp/site_".$CP_ID.".inc";


if ($mode=="save"){

        for($i=0; $i <count($nations);$i++){
            if($nations[$i]) $nations_ .="," . str_replace(",","",addslashes($nations[$i]));
        }
        $nations = substr($nations_,1);

        //사이트 정보
        $domain = str_replace("http://","",$domain);

        for($i=0; $i < count($account); $i++){
            $account_all .=($account[$i])? "," . $account[$i] : "";
        }
        $account_all = substr($account_all,1);

        $fp=fopen($filename, "w");  //파일 쓰기모드로 열기, 파일이 있다면 overwirte

        $config ="<?\n";
        $config .="##-------------------------------------------\n";
        $config .="##주의! (이 파일을 수동으로 조작하지 마세요.)\n";
        $config .="##-------------------------------------------\n";

        $config .="\$NATIONS='$nations';\n";
        $config .="\$SITE_NAME='$site_name';\n";
        $config .="\$DESCRIPTION='$description';\n";
        $config .="\$WEBMASTER='$webmaster';\n";
        $config .="\$SMSID='$smsid';\n";
        $config .="\$SMSPWD='$smspwd';\n";
        $config .="\$SMSCELL='$smscell';\n";
        $config .="\$KAKAO_LINK='$kakao_link';\n";

        $config .="\$SNS_BLOG='$blog';\n";
        $config .="\$SNS_FACEBOOK='$facebook';\n";
        $config .="\$SNS_INSTAGRAM='$instagram';\n";
        $config .="\$SNS_YOUTUBE='$youtube';\n";
        $config .="\$SNS_KAKAO='$kakao';\n";

        $config .="?";
        $config .=">";


        if(!fwrite($fp,$config)){
            error("환경설정파일을 생성하던 중 예기치 못한 에러가 발생하였습니다. 관리자에게 문의하여 주시기 바랍니다.");exit;
        }
        fclose($fp);

        msggo("저장하였습니다.","?");
}else{
    @include($filename);
    $account = explode(",",$ACCOUNT);
}

$SITE = ($SITE)? $SITE : str_replace(":80","",$HTTP_HOST);

$NATIONS =  stripslashes($NATIONS);

$rs[blog]=$SNS_BLOG;
$rs[facebook]=$SNS_FACEBOOK;
$rs[instagram]=$SNS_INSTAGRAM;
$rs[youtube]=$SNS_YOUTUBE;
$rs[kakao]=$SNS_KAKAO;

//-------------------------------------------------------------------------------
?>
<?include("../top.html");?>
<script language="JavaScript">
<!--
function chkForm(fm){
    var fm = document.fmData;

    /*
    if(check_blank(fm.smsid,'ID를',0)=='wrong'){return }
    if(check_blank(fm.smspwd,'비밀번호를',0)=='wrong'){return }
    if(check_blank(fm.smscell,'휴대폰번호를',0)=='wrong'){return }
    */

    fm.submit();
}

function sitemap(){
    let url = "../../../renew/xml.php?mode=download";
    actarea.location.href=url;
}
//-->
</script>




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

    <br>

    <!--내용이 들어가는 곳 시작-->

      <table border="0" cellspacing="0" cellpadding="3" width="100%" id="viewWidth">

        <form name="fmData">
        <input type="hidden" name="mode" value="save">

        <tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>


        <tr>
          <td class="subject">사이트 이름</td>
          <td>
            <input type="text" name="site_name" value="<?=$SITE_NAME?>" size="30" maxlength="100" class="box"> 
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td class="subject">사이트 설명</td>
          <td>
            <input type="text" name="description" value="<?=$DESCRIPTION?>" size="80" maxlength="100" class="box"> 
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>


        <tr>
        <td colspan=2>
        <br>
          <!-- Button Begin---------------------------------------------->
          <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
            <tr align="right">
                <td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
                <td><span class="btn_pack medium bold"><a href="#" onClick="document.fmData.reset()"> 취소 </a></span></td>
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