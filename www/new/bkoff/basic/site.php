<?
include_once("../include/common_file.php");



#### Menu
$filecode = substr(SELF,5,-4);
$TITLE = "사이트정보";
$MENU = "basic";


$https = ($_SERVER['HTTPS']=='on')? "https":"http";
$DOMAIN = $https . "://" . $_SERVER['HTTP_HOST'];
$CPAGE = $DOMAIN  . $_SERVER[REQUEST_URI];


if($CP_ID){
    Header( "HTTP/1.1 301 Moved Permanently" );
    Header( "Location: list_nbanner.php" );
    exit;
}


#### operation
$filename= "../../public/inc/site.inc";

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

		$fp=fopen($filename, "w");	//파일 쓰기모드로 열기, 파일이 있다면 overwirte

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
    $("#btn_xml").hide();
    $("#ing_xml").show();

    setTimeout(function() {
        $("#btn_xml").show();
        $("#ing_xml").hide();
    }, 3000);    
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
			<input type="text" name="site_name" value="<?=$SITE_NAME?>" size="60" maxlength="100" class="box"> 
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td class="subject">사이트 설명</td>
          <td>
            <input type="text" name="description" value="<?=$DESCRIPTION?>" size="60" maxlength="100" class="box"> 
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td class="subject">웹마스터 이메일</td>
          <td>
			<input type="text" name="webmaster" value="<?=$WEBMASTER?>" size="60" maxlength="100" class="box"> 
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">카카오페이지</td>
          <td>
			<input type="text" name="kakao_link" value="<?=$KAKAO_LINK?>" size="60" maxlength="100" class="box"> 
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">국가명</td>
          <td>
			<?
			$row = 5;
			$arr = explode(",",$NATIONS);
			$cnt = count($arr);
			$add_row = 5-($cnt%5);
			$row=$cnt+$add_row;

			for($i=0;$i<$row;$i++){
				if(!($i%5)) echo "<div></div>";
			?>
			<input class="box" type="text" name="nations[]" id="nations<?=$i?>" value="<?=$arr[$i]?>" size="20" maxlength="30">
			<?
			}?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <!-- <tr>
          <td class="subject">SMS 계정</td>
          <td>
            ID : <input class=box type="text" name="smsid" size=20 value="<?=$SMSID?>">
            PWD : <input class=box type="text" name="smspwd" size=20 value="<?=$SMSPWD?>">
            회신 전화번호 : <input class=box type="text" name="smscell" size=20 value="<?=$SMSCELL?>">
			&nbsp;&nbsp;&nbsp;&nbsp;
			[ <a href="http://hosting.whois.co.kr/main/smsh.php?ch=smsh" target="_target">SMS 신청 및 충전</a> ]
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr> -->

  
        <tr>
          <td class="subject" width="15%">네이버블로그 링크</td>
          <td colspan="3">
            <?=html_input("blog",80,150)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>      
      
        <tr>
          <td class="subject" width="15%">페이스북 링크</td>
          <td colspan="3">
            <?=html_input("facebook",80,150)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>      
      
        <tr>
          <td class="subject" width="15%">인스타그램 링크</td>
          <td colspan="3">
            <?=html_input("instagram",80,150)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>      
      
        <tr>
          <td class="subject" width="15%">유튜브 링크</td>
          <td colspan="3">
            <?=html_input("youtube",80,150)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>      
      
<!--         <tr>
          <td class="subject" width="15%">카톡링크</td>
          <td colspan="3">
            <?=html_input("kakao",80,150)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>   -->


        <tr>
          <td class="subject">EP파일 경로</td>
          <td>
                <div style="padding:5px;line-height: 180%;">
                    https://irumtour.net/ep/nhn/all.txt<br/>
                    https://irumtour.net/ep/nhn/brief.txt
                </div>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">sitemap.xml 생성/다운로드</td>
          <td>
                <div style="padding:10px 5px 10px 5px;">
                    <div id="sitemap_path" style="margin-bottom:10px"><?=$DOMAIN?>/renew/public/sitemap/sitemap.xml</div>  
                    <span class="btn_pack medium bold" id="btn_xml"><a href="javascript:sitemap()"> XML 갱신 및 다운로드 </a></span>    
                    <span id="ing_xml" class="hide">생성중....</span>
                </div>
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