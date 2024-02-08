<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"기본설정");

####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_basic";
$TITLE = "기본설정";


#### operation
$filename_nation= "../../public/inc/cmp_config_nation.inc";
$filename= "../../public/inc/cmp_config.inc";
if($CP_ID) $filename= "../../public/cp/cmp_config_".$CP_ID.".inc";



if($mode=="save"){

    $ACCOUNT = "$ACCOUNT1 $ACCOUNT2 $ACCOUNT3";

	$DANGA_GOLF = rnf($DANGA_GOLF);
	$DANGA_GOLF2 = rnf($DANGA_GOLF2);
	$DANGA_AIR = rnf($DANGA_AIR);
	$DANGA_BOOK = rnf($DANGA_BOOK);
	$DANGA_INC = rnf($DANGA_INC);

	for($i=0; $i <count($nations);$i++){
		if($nations[$i]) $nations_ .="," . str_replace(",","",addslashes($nations[$i]));
	}
	$nations = substr($nations_,1);

	for($i=0; $i <count($airlines);$i++){
		if($airlines[$i]) $airlines_ .="," . str_replace(",","",addslashes($airlines[$i]));
	}
	$airlines = substr($airlines_,1);

	for($i=0; $i <count($partners);$i++){
		if($partners[$i]) $partners_ .="," . str_replace(",","",addslashes($partners[$i]));
	}
	$partners = substr($partners_,1);

	$sms_text1 = str_format($sms_text1);
	$sms_text2 = str_format($sms_text2);

	$danga_golf = rnf($danga_golf);
	$danga_golf2 = rnf($danga_golf2);
	$danga_air = rnf($danga_air);
	$danga_inc = rnf($danga_inc);

	$stock_golf = rnf($stock_golf);
	$stock_golf2 = rnf($stock_golf2);
	$stock_air = rnf($stock_air);
	$stock_inc = rnf($stock_inc);

	$fp=fopen($filename, "w");	//파일 쓰기모드로 열기, 파일이 있다면 overwirte

	$config ="<?\n";
	$config .="##-------------------------------------------\n";
	$config .="##주의! (이 파일을 수동으로 조작하지 마세요.)\n";
	$config .="##-------------------------------------------\n";

	$config .="\$DANGA_GOLF='$danga_golf';\n";
	$config .="\$DANGA_GOLF2='$danga_golf2';\n";
	$config .="\$DANGA_AIR='$danga_air';\n";
	$config .="\$DANGA_INC='$danga_inc';\n";
	$config .="\$DANGA_BOOK='$danga_book';\n";

	$config .="\$STOCK_GOLF='$stock_golf';\n";
	$config .="\$STOCK_GOLF2='$stock_golf2';\n";
	$config .="\$STOCK_AIR='$stock_air';\n";
	$config .="\$STOCK_INC='$stock_inc';\n";
	$config .="\$STOCK_BOOK='$stock_book';\n";

	$config .="\$NATIONS='$nations';\n";
	$config .="\$AIRLINES='$airlines';\n";
	$config .="\$PARTNERS='$partners';\n";
	$config .="\$SMS_TEXT1='$sms_text1';\n";
	$config .="\$SMS_TEXT2='$sms_text2';\n";
	$config .="\$SMS_TEXT3='$sms_text3';\n";
	$config .="\$SMS_TEXT4='$sms_text4';\n";
	$config .="\$OFFICE_TEL='$office_tel';\n";
	$config .="\$OFFICE_FAX='$office_fax';\n";
	$config .="\$OFFICE_TEL_PARTNER='$office_tel_partner';\n";
	$config .="\$ACCOUNT='$ACCOUNT';\n";
    $config .="\$ACCOUNT1='$account1';\n";
    $config .="\$ACCOUNT2='$account2';\n";
    $config .="\$ACCOUNT3='$account3';\n";
	$config .="\$ESTIMATE_PRICE_TXT='$estimate_price_txt';\n";
    $config .="\$ESTIMATE_PRICE_TXT_KO='$estimate_price_txt_ko';\n";
    
    $config .="\$CANCEL_TXT='$cancel_txt';\n";
    $config .="\$CANCEL_TXT_KO='$cancel_txt_ko';\n";


	$config .="\$FRANCHISECORPNAME='$franchisecorpname';\n";
	$config .="\$FRANCHISECEONAME='$franchiseceoname';\n";
	$config .="\$FRANCHISEADDR='$franchiseaddr';\n";
	$config .="\$FRANCHISETEL='$franchisetel';\n";
    
    $config .="\$EST_SNS='$est_sns';\n";

	$config .="?";
	$config .=">";


	if(!fwrite($fp,$config)){
		error("환경설정파일을 생성하던 중 예기치 못한 에러가 발생하였습니다. 관리자에게 문의하여 주시기 바랍니다.");exit;
	}
	fclose($fp);

    if(!$CP_ID){
        $fp=fopen($filename_nation, "w");  //파일 쓰기모드로 열기, 파일이 있다면 overwirte
        $config ="<?\n";
        $config .="##-------------------------------------------\n";
        $config .="##주의! (이 파일을 수동으로 조작하지 마세요.)\n";
        $config .="##-------------------------------------------\n";
        $config .="\$NATIONS='$nations';\n";
        $config .="\$AIRLINES='$airlines';\n";
        $config .="?";
        $config .=">";
        fwrite($fp,$config);
        fclose($fp);
    }

	msggo("저장하였습니다.","?");
}else{
	@include($filename);
}



/*독립형 파트너 신규 등록시 기본값 s*/
if($CP_ID && !is_file($filename)){
    $sql = "
            select
                *
            from cmp_cp
            where id='$CP_ID'
            limit 1
        ";
    $dbo->query($sql);
    $rs=$dbo->next_record();

    $OFFICE_TEL=$rs[phone];
    $OFFICE_FAX=$rs[fax];
    $OFFICE_TEL_PARTNER=$rs[staff_phone];
    $ACCOUNT=$rs[bank] ." " . $rs[bank_account] ." " . $rs[bank_owner];
    $ACCOUNT1=$rs[bank];
    $ACCOUNT2=$rs[bank_account];
    $ACCOUNT3=$rs[bank_owner];
    $FRANCHISECORPNAME=$rs[bank_owner];
    $FRANCHISECEONAME=$rs[ceo];
    $FRANCHISEADDR=$rs[address]." " . $rs[address2];
    $FRANCHISETEL=$rs[phone];

    $rs[company] = ($rs[company2])? $rs[company2] : $rs[company];

    $SMS_TEXT1 = "";
    $SMS_TEXT2 = "";
    $SMS_TEXT3 = "";
    $SMS_TEXT4 = "";
    $ESTIMATE_PRICE_TXT = "";
    $ESTIMATE_PRICE_TXT_KO = "";
    $CANCEL_TXT = "";
    $CANCEL_TXT_KO = "";
    $EST_SNS = "";
}
/*독립형 파트너 신규 등록시 기본값 f*/







$NATIONS =  stripslashes($NATIONS);
$AIRLINES =  stripslashes($AIRLINES);
$SMS_TEXT1 =  stripslashes($SMS_TEXT1);
$SMS_TEXT2 =  stripslashes($SMS_TEXT2);
$SMS_TEXT3 =  stripslashes($SMS_TEXT3);
$OFFICE_TEL =  stripslashes($OFFICE_TEL);
$ACCOUNT =  stripslashes($ACCOUNT);
$ESTIMATE_PRICE_TXT =  stripslashes($ESTIMATE_PRICE_TXT);
$ESTIMATE_PRICE_TXT_KO =  stripslashes($ESTIMATE_PRICE_TXT_KO);
$CANCEL_TXT =  stripslashes($CANCEL_TXT);
$CANCEL_TXT_KO =  stripslashes($CANCEL_TXT_KO);
?>
<?include("../top.html");?>
<script language="JavaScript">
<!--
function chkForm(){
	var fm = document.fmData1;

	/*
	if(check_blank(fm.smsid,'ID를',0)=='wrong'){return }
	if(check_blank(fm.smspwd,'비밀번호를',0)=='wrong'){return }
	if(check_blank(fm.smscell,'휴대폰번호를',0)=='wrong'){return }
	*/

	fm.submit();
}

//-->
</script>
<style type="text/css">
.col_danga{
	display:inline-block;
	width:80px;
}	
</style>



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

	<br>

	<!--내용이 들어가는 곳 시작-->

      <table border="0" cellspacing="0" cellpadding="3" width="100%" id="viewWidth">

		<form name="fmData1" method="post">
		<input type="hidden" name="mode" value="save">

		<tr><td colspan="4"  bgcolor='#5E90AE' height=2></td></tr>


        <tr>
          <td class="subject" width="15%">국가명</td>
          <td colspan="3">
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
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject" width="15%">항공사명</td>
          <td colspan="3">
			<?
			$row = 5;
			$arr = explode(",",$AIRLINES);
			$cnt = count($arr);
			$add_row = 5-($cnt%5);
			$row=$cnt+$add_row;

			for($i=0;$i<$row;$i++){
				if(!($i%5)) echo "<div></div>";
			?>
			<input class="box" type="text" name="airlines[]" id="airlines<?=$i?>" value="<?=$arr[$i]?>" size="20" maxlength="30">
			<?
			}?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <!-- <tr>
          <td class="subject" width="15%">거래처</td>
          <td colspan="3">
			<?
			$row = 5;
			$arr = explode(",",$PARTNERS);
			$cnt = count($arr);
			$add_row = 5-($cnt%5);
			$row=$cnt+$add_row;

			for($i=0;$i<$row;$i++){
				if(!($i%5)) echo "<div></div>";
			?>
			<input class="box" type="text" name="partners[]" id="partners<?=$i?>" value="<?=$arr[$i]?>" size="20" maxlength="30">
			<?
			}?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
 -->


        <tr>
          <td class="subject" width="15%">대표번호(문자발송시)</td>
          <td width="20%">
                <input class="box" type="text" name="office_tel" id="office_tel" value="<?=$OFFICE_TEL?>" size="20" maxlength="30">
          </td>
          <td class="subject" width="15%">팩스발송번호</td>
          <td>
                <input class="box" type="text" name="office_fax" id="office_fax" value="<?=$OFFICE_FAX?>" size="20" maxlength="30">
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">발행자(현금영수증,세금계산서)</td>
          <td>
                <input class="box" type="text" name="franchisecorpname" id="franchisecorpname" value="<?=$FRANCHISECORPNAME?>" size="20" maxlength="30">
          </td>
          <td class="subject" width="15%">대표(현금영수증,세금계산서)</td>
          <td>
                <input class="box" type="text" name="franchiseceoname" id="franchiseceoname" value="<?=$FRANCHISECEONAME?>" size="20" maxlength="30">
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <tr>
          <td class="subject" width="15%">주소(현금영수증,세금계산서)</td>
          <td colspan="3">
                <input class="box" type="text" name="franchiseaddr" id="franchiseaddr" value="<?=$FRANCHISEADDR?>" size="100" maxlength="150">
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <tr>
          <td class="subject" width="15%">전화(현금영수증,세금계산서)</td>
          <td>
                <input class="box" type="text" name="franchisetel" id="franchisetel" value="<?=$FRANCHISETEL?>" size="20" maxlength="30">
          </td>
          <td class="subject" width="15%">파트너 견적 접수시 알림받을 핸드폰</td>
          <td>
                <input class="box" type="text" name="office_tel_partner" id="office_tel_partner" value="<?=$OFFICE_TEL_PARTNER?>" size="20" maxlength="30">
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>



        <tr>
          <td class="subject" width="15%">계좌번호</td>
          <td colspan="3">
                <input class="box" type="text" name="account1" id="account1" value="<?=$ACCOUNT1?>" size="10" maxlength="20" placeholde="은행명">
                <input class="box" type="text" name="account2" id="account2" value="<?=$ACCOUNT2?>" size="30" maxlength="30" placeholde="계좌번호">
                <input class="box" type="text" name="account3" id="account3" value="<?=$ACCOUNT3?>" size="20" maxlength="30" placeholde="예금주">
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">잔금안내문자</td>
          <td colspan="3">
			<span style="float:left"><textarea name="sms_text1" class="box" cols="80" rows="15"><?=$SMS_TEXT1?></textarea></span>
			<span style="float:left">
				{고객명}<br>
				{담당자명}<br>
				{골프장명}<br>
				{잔금액(총액)}<br>
				{잔금액(1인)}<br>
				{잔금일}<br>
				{출국일}<br>
				{귀국일}<br>
				{담당자연락처}<br>
				* 실제 데이터로 대체 됩니다. ex) {고객명}님 -> 홍길동님
			</span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">출발안내문자</td>
          <td colspan="3">
			<span style="float:left"><textarea name="sms_text2" class="box" cols="80" rows="15"><?=$SMS_TEXT2?></textarea></span>
			<span style="float:left">
				{고객명}<br>
				{일정}<br>
				{국내공항} {체크인 카운터}<br/>
				{미팅위치} {미팅보드} {현지담당} {비상연락처}<br>
				{수화물안내}<br>
				{준비물}<br>
				{날씨}<br>
				{출국일}<br/>
				{귀국일}				
			</span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">계약금안내</td>
          <td colspan="3">
			<span style="float:left"><textarea name="sms_text3" class="box" cols="80" rows="15"><?=$SMS_TEXT3?></textarea></span>
			<span style="float:left">
				{고객명}<br>
				{담당자}<br>
				{상품명}<br>
				{출국일}<br/>
				{귀국일}
			</span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject" width="15%">해피콜</td>
          <td colspan="3">
			<span style="float:left"><textarea name="sms_text4" class="box" cols="80" rows="10"><?=$SMS_TEXT4?></textarea></span>
			<span style="float:left">
				{고객명}<br>
				{담당자}<br/>
				{출국일}<br/>
				{귀국일}				
			</span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>




        <tr>
          <td class="subject" width="15%">견적서 예약시 주의사항 문구(해외)</td>
          <td colspan="3">
			<span style="float:left"><textarea name="estimate_price_txt" class="box" cols="103" rows="10"><?=$ESTIMATE_PRICE_TXT?></textarea></span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">견적서 예약시 주의사항 문구(국내)</td>
          <td colspan="3">
            <span style="float:left"><textarea name="estimate_price_txt_ko" class="box" cols="103" rows="10"><?=$ESTIMATE_PRICE_TXT_KO?></textarea></span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject" width="15%">취소 수수료 규정(해외)</td>
          <td colspan="3">
            <span style="float:left"><textarea name="cancel_txt" class="box" cols="103" rows="10"><?=$CANCEL_TXT?></textarea></span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">취소 수수료 규정(국내)</td>
          <td colspan="3">
            <span style="float:left"><textarea name="cancel_txt_ko" class="box" cols="103" rows="10"><?=$CANCEL_TXT_KO?></textarea></span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject" width="15%">여행상품정보</td>
          <td colspan="3">
            <textarea name="est_sns" class="box" cols="103" rows="3" placeholder="일정표 '여행상품정보SNS'에 표시"><?=$EST_SNS?></textarea></span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>




        <tr>
          <td class="subject" width="15%">단가</td>
          <td colspan="3">
			<p><span class="col_danga">골프공</span><input class="box numberic" type="text" name="danga_golf" value="<?=nf($DANGA_GOLF)?>" size="11" maxlength="10">원</p>
			<p><span class="col_danga">골프공(고급)</span><input class="box numberic" type="text" name="danga_golf2" value="<?=nf($DANGA_GOLF2)?>" size="11" maxlength="10">원</p>
			<p><span class="col_danga">항공커버</span><input class="box numberic" type="text" name="danga_air" value="<?=nf($DANGA_AIR)?>" size="11" maxlength="10">원</p>
			<p><span class="col_danga">달러북</span><input class="box numberic" type="text" name="danga_book" value="<?=nf($DANGA_BOOK)?>" size="11" maxlength="10">원</p>
			<p><span class="col_danga">여행자보험</span><input class="box numberic" type="text" name="danga_inc" value="<?=nf($DANGA_INC)?>" size="11" maxlength="10">원</p>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">기준 재고</td>
          <td colspan="3">
			<p><span class="col_danga">골프공</span><input class="box numberic" type="text" name="stock_golf" value="<?=nf($STOCK_GOLF)?>" size="4" maxlength="10">개</p>
			<p><span class="col_danga">골프공(고급)</span><input class="box numberic" type="text" name="stock_golf2" value="<?=nf($STOCK_GOLF2)?>" size="4" maxlength="10">개</p>
			<p><span class="col_danga">항공커버</span><input class="box numberic" type="text" name="stock_air" value="<?=nf($STOCK_AIR)?>" size="4" maxlength="10">개</p>
			<p><span class="col_danga">달러북</span><input class="box numberic" type="text" name="stock_book" value="<?=nf($STOCK_BOOK)?>" size="4" maxlength="10">개</p>
			<!-- <p><span class="col_danga">여행자보험</span><input class="box numberic" type="text" name="stock_inc" value="<?=nf($STOCK_INC)?>" size="4" maxlength="10">개</p> -->

			(현재 재고가 아닙니다. 기준 재고-사용량=현재 재고)
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>






        <tr><td colspan="4" bgcolor='#E1E1E1' height=3></td></tr>

        <tr>
			<td colspan="4">
			<br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td colspan="3"><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
					<td colspan="3"><span class="btn_pack medium bold"><a href="#" onClick="document.fmData1.reset()"> 취소 </a></span></td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>
			</td>
		</tr>
		</form>
      </table>




	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>