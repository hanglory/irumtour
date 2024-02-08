<?
include_once("../include/common_file.php");

//chk_power($_SESSION["sessLogin"]["proof"],"고객별예약정보관리대장");

####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_basic";
$LEFT_HIDDEN="0";
$TITLE = "홈택스 전자세금계산서";
$view_row=20;	//한 페이지에 보여줄 행 수를 결정

//$SDate = ($SDate)? $SDate : date("Y/m/d",strtotime(date("Y/m/d")." -2 day"));
$SDate = ($SDate)? $SDate : date("Y/m/01");
$EDate = ($EDate)? $EDate : date("Y/m/d");
$TIKeyType = ($TIKeyType)? $TIKeyType : "BUY";
$DType = ($DType)? $DType : "S";
$TITLE .=($TIKeyType=="BUY")? " 매입 내역" : " 매출 내역";


$real_path = "../..";
include $real_path.'/api/api_common.php';
include $real_path.'/api/Popbill/PopbillHTTaxinvoice.php';
include $real_path.'/api/HTTaxinvoice/common.php';

if($mode=="find"){
	//RequestJob
	if($TIKeyType=="BUY") $TIKeyType_ = KeyType::BUY;
	else $TIKeyType_ = KeyType::SELL;

	try {
	    $jobID = $HTTaxinvoiceService->RequestJob($bizno, $TIKeyType_, $DType, rnf($SDate), rnf($EDate));
		$para = "?jobID=$jobID";
		$para .= "&TIKeyType=$TIKeyType";
		$para .="&DType=$DType";
		$para .="&SDate=$SDate";
		$para .="&EDate=$EDate";
		$para .="&QString=$QString";
	    redirect2(SELF.$para);exit;
	}
	catch(PopbillException $pe) {
	    $code = $pe->getCode();
	    $message = $pe->getMessage();
	    msggo($message,"list_tax.php?mode=stop");exit;
	}
}

if($mode!="find" && $jobID){
	$bit = ($bit)? $bit+1:1;
	$para = "?jobID=$jobID";
	$para .= "&TIKeyType=$TIKeyType";
	$para .="&DType=$DType";
	$para .="&SDate=$SDate";
	$para .="&EDate=$EDate";
	$para .="&QString=$QString";
	$para .="&bit=$bit";

	//Summary
	// 문서형태 배열, N-일반세금계산서, M-수정세금계산서
	$Type = array ('N','M');
	// 과세형태 배열, T-과세, N-면세, Z-영세
	$TaxType = array ('T','N','Z');
	// 영수/청구 배열, R-영수, C-청구, N-없음
	$PurposeType = array ('R','C','N');
	// 종사업장 유무, 공백-전체조회, 0-종사업장 없는 건만 조회, 1-종사업장번호 조건에 따라 조회
	$TaxRegIDYN = "";
	// 종사업장번호 유형, 공백-전체, S-공급자, B-공급받는자, T-수탁자
	$TaxRegIDType = "";
	// 종사업장번호, 콤마(",")로 구분하여 구성 ex) "1234,0001";
	$TaxRegID = "";
	$PerPage= $view_row;

	try {
        $response = $HTTaxinvoiceService->Search ( $bizno, $jobID, $Type, $TaxType, $PurposeType,
            $TaxRegIDYN, $TaxRegIDType, $TaxRegID, $page, $PerPage, $Order, $LinkID, $QString );   
	}
	catch(PopbillException $pe) {
	    $code = $pe->getCode();
	    $message = $pe->getMessage();
	    if(strstr($message,"수집작업이 진행중입니다")){
	    	echo "<meta http-equiv='refresh' content='1;url=".SELF."$para'>";
	    	exit;
	    }

	}

	// checkVar("jobID",$jobID);
	// checkVar("code (응답코드)",$response->code);
	// checkVar("message (응답메시지)",$response->message);
	// checkVar("total (총 검색결고 건수)",$response->total);
	// checkVar("perPage (페이지당 검색개수)",$response->perPage);
	// checkVar("pageNum (페이지 번호)",$response->pageNum);
	// checkVar("pageCount (페이지 개수)",$response->pageCount);

}









$page =($page)?$page:1;
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;

####자료갯수
$row_search = $response->total;

####페이지 처리
$var=ceil($row_search/$view_row);
$total_page = ($var > 1)? $var : 1;


#### Link
$link = substr($para,1);

$rs[SDate] = $SDate;
$rs[EDate] = $EDate;
?>
<?include("../top.html");?>
<script language="JavaScript">
function cashbill_send(code,id_no,assort,name,price,phone){
	//let url = "../../api/Cashbill/RegistIssue.php";
	let para="";
	para+="?id_no="+id_no;
	para+="&code="+code;
	para+="&assort="+assort;
	para+="&name="+name;
	para+="&price="+price;
	para+="&phone="+phone;
	javascript:newWin('view_<?=$filecode?>.php'+para,870,350,1,1,'','regcashbill')
}

function tax_detail(NTSConfirmNum){
	let url = "../../api/HTTaxinvoice/GetPopUpURL.php";
	url+="?NTSConfirmNum="+NTSConfirmNum;
	javascript:newWin(url,870,600,0,0,'','detailtax');
}

$(function(){
	<?if(!$mode && !$jobID){?>
		$("#btn_find").last().trigger('click');
	<?}?>
});
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


	<!--내용이 들어가는 곳 시작-->

	<!-- Search Begin------------------------------------------------>
	<div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
	
	<form name="fmSearch" method="get" action="<?=SELF?>">
	<input type="hidden" name='mode' value="find">


	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=nf($total_page)?> page /  <?=nf($page)?> page }<?}?></font></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>


	<select name="TIKeyType" class='select'>
	<?=option_str("매입,매출","BUY,SELL",$TIKeyType)?>
	</select>

	<select name="DType" class='select'>
	<?=option_str("작성일자,발행일자,전송일자","W,I,S",$DType)?>
	</select>
	<?=html_input("SDate",13,10,'box dateinput c')?> ~
	<?=html_input("EDate",13,10,'box dateinput c')?>

	<input class=box type="text" name="QString" size="20" maxlength="40" value='<?=$QString?>' placeholder="사업자번호 또는 거래처명">
	<input class=button type="submit" name="Submit" id="btn_find" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align="center" height="25" bgcolor="#F7F7F6">
			<th class="subject" >국세청승인번호</th>
			<th class="subject" >작성일자</th>
			<th class="subject" >발행일자</th>
			<th class="subject" >전송일자</th>
			<th class="subject" >공급가액 합계</th>
			<th class="subject" >세액 합계</th>
			<th class="subject" >합계금액</th>
			<th class="subject" >사업자번호</th>
			<th class="subject" >상호</th>
		</tr>


	    <!--     <li>ntsconfirmNum (국세청승인번호) : <?php echo $response->list[$i]->ntsconfirmNum ; ?></li>
	        <li>writeDate (작성일자) : <?php echo $response->list[$i]->writeDate ; ?></li>
	        <li>issueDate (발행일자) : <?php echo $response->list[$i]->issueDate ; ?></li>
	        <li>sendDate (전송일자) : <?php echo $response->list[$i]->sendDate ; ?></li>
	        <li>invoiceType (구분) : <?php echo $response->list[$i]->invoiceType ; ?></li>
	        <li>taxType (과세형태) : <?php echo $response->list[$i]->taxType ; ?></li>
	        <li>purposeType (영수/청구) : <?php echo $response->list[$i]->purposeType ; ?></li>
	        <li>supplyCostTotal (공급가액 합계) : <?php echo $response->list[$i]->supplyCostTotal ; ?></li>
	        <li>taxTotal (세액 합계) : <?php echo $response->list[$i]->taxTotal ; ?></li>
	        <li>totalAmount (합계금액) : <?php echo $response->list[$i]->totalAmount ; ?></li>
	        <li>remark1 (비고) : <?php echo $response->list[$i]->remark1 ; ?></li>

	        <li>invoicerCorpNum (공급자 사업자번호) : <?php echo $response->list[$i]->invoicerCorpNum ; ?></li>
	        <li>invoicerTaxRegID (공급자 종사업장번호) : <?php echo $response->list[$i]->invoicerTaxRegID ; ?></li>
	        <li>invoicerCorpName (공급자 상호) : <?php echo $response->list[$i]->invoicerCorpName ; ?></li>
	        <li>invoicerCEOName (공급자 대표자성명) : <?php echo $response->list[$i]->invoicerCEOName ; ?></li>
	        <li>invoicerEmail (공급자 담당자 이메일) : <?php echo $response->list[$i]->invoicerEmail ; ?></li>

	        <li>invoiceeCorpNum (공급받는자 사업자번호) : <?php echo $response->list[$i]->invoiceeCorpNum ; ?></li>
	        <li>invoiceeType (공급받는자 구분) : <?php echo $response->list[$i]->invoiceeType ; ?></li>
	        <li>invoiceeTaxRegID (공급받는자 종사업장번호) : <?php echo $response->list[$i]->invoiceeTaxRegID ; ?></li>
	        <li>invoiceeCorpName (공급받는자 상호) : <?php echo $response->list[$i]->invoiceeCorpName ; ?></li>
	        <li>invoiceeCEOName (공급받는자 대표자 성명) : <?php echo $response->list[$i]->invoiceeCEOName ; ?></li>
	        <li>invoiceeEmail1 (공급받는자 담당자 이메일) : <?php echo $response->list[$i]->invoiceeEmail1 ; ?></li>
	        <li>invoiceeEmail2 (공급받는자 ASP 연계사업자 이메일) : <?php echo $response->list[$i]->invoiceeEmail2 ; ?></li>

	        <li>trusteeCorpNum (수탁자 사업자번호) : <?php echo $response->list[$i]->trusteeCorpNum ; ?></li>
	        <li>tursteeTaxRegID (수탁자 종사업장번호) : <?php echo $response->list[$i]->trusteeTaxRegID ; ?></li>
	        <li>tursteeCorpName (수탁자 상호) : <?php echo $response->list[$i]->trusteeCorpName ; ?></li>
	        <li>trusteeCEOName (수탁자 대표자 성명) : <?php echo $response->list[$i]->trusteeCEOName ; ?></li>
	        <li>trusteeEmail (수탁자 담당자 이메일) : <?php echo $response->list[$i]->trusteeEmail ; ?></li>

	        <li>purchaseDate (거래일자) : <?php echo $response->list[$i]->purchaseDate ?></li>
	        <li>itemName (품명) : <?php echo $response->list[$i]->itemName ?></li>
	        <li>spec (규격) : <?php echo $response->list[$i]->spec ?></li>
	        <li>qty (수량) : <?php echo $response->list[$i]->qty ?></li>
	        <li>unitCost (단가) : <?php echo $response->list[$i]->unitCost ; ?></li>
	        <li>supplyCost (공급가액) : <?php echo $response->list[$i]->supplyCost ; ?></li>
	        <li>tax (세액) : <?php echo $response->list[$i]->tax ; ?></li>
	        <li>remark (비고) : <?php echo $response->list[$i]->remark ; ?></li>

	        <li>modifyYN (수정 전자세금계산서 여부) : <?php echo $response->list[$i]->modifyYN  ? 'true' : 'false' ?></li>
	        <li>orgNTSConfirmNum (원본 전자세금계산서 국세청승인번호) : <?php echo $response->list[$i]->orgNTSConfirmNum ; ?></li> -->

<?
for ( $i = 0; $i < Count ( $response->list ); $i++ ) {		
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="30"><a href="javascript:tax_detail('<?=$response->list[$i]->ntsconfirmNum ?>')"><?=$response->list[$i]->ntsconfirmNum?></a></td>
	      <td height="30"><?=$response->list[$i]->writeDate?></td>
	      <td height="30"><?=$response->list[$i]->issueDate?></td>
	      <td height="30"><?=$response->list[$i]->sendDate?></td>
	      <td height="30"><?=nf($response->list[$i]->supplyCostTotal)?></td>
	      <td height="30"><?=nf($response->list[$i]->taxTotal)?></td>
	      <td height="30"><?=nf($response->list[$i]->totalAmount)?></td>

	      <?if($TIKeyType=="SELL"){?>
	      <td height="30"><?=$response->list[$i]->invoicerCorpNum?></td>
	      <td height="30"><?=$response->list[$i]->invoiceeCorpName?></td>
	      <?}else{?>
	      <td height="30"><?=$response->list[$i]->invoicerCorpNum?></td>
	      <td height="30"><?=$response->list[$i]->invoicerCorpName?></td>	      	
	      <?}?>
	    </tr>
<?
}
?>
	</table>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
			<!-- navigation Begin---------------------------------------------->
			<?include_once('../../include/navigation.php')?>
			<?=$navi?>
			<!-- navigation End------------------------------------------------>
		  </td>
        </tr>
	</table>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
