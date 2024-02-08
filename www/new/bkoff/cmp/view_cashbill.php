<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_cashbill";
$MENU = "cmp_basic";
$TITLE = "현금영수증발행";


#### operation
if ($mode=="tax"){

	$totalAmount = rnf($totalAmount);
	if($taxationType=="과세"){
		$supplyCost = floor($totalAmount/1.1);
	}else{
		$supplyCost = $totalAmount;
	}
	$tax = $totalAmount - $supplyCost;
	
	$totalAmount = nf($totalAmount);
	$supplyCost = nf($supplyCost);
	$tax = nf($tax);
	echo "
		<script>
			parent.document.getElementById('totalAmount').value='$totalAmount';
			parent.document.getElementById('supplyCost').value='$supplyCost';
			parent.document.getElementById('tax').value='$tax';
		</script>
	";

	exit;

}



$sql2 = "select * from cmp_reservation where code='$code' and cp_id='$CP_ID'";
$dbo2->query($sql2);
$rs2=$dbo2->next_record();
$rs[itemName] = $rs2[golf_name];


$rs[tradeUsage] = ($rs[tradeUsage])? $rs[tradeUsage] : "소득공제용";
$rs[taxationType] = ($rs[taxationType])? $rs[taxationType] : "비과세";
$rs[identityNum] = ($rs[identityNum])? $rs[identityNum] : $phone;
$rs[totalAmount] = ($rs[totalAmount])? $rs[totalAmount] : $price;
$rs[totalAmount] = nf($rs[totalAmount]);
$rs[customerName] = $name;
?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm(){
	if(confirm("현금영수증을 발행하시겠습니까?")){
		let fm = document.fmData;
		let identityNum_txt = $("#identityNum_txt").text();
		let totalAmount = $("#totalAmount").val();
		if(totalAmount==0) $("#totalAmount").val('');

		if(check_blank(fm.identityNum,identityNum_txt+'를',0)=='wrong'){return }
		if(check_blank(fm.totalAmount,'거래금액을')=='wrong',0){return }
		fm.submit();
	}
}

function set_idno(bit){
	if(bit=="지출증빙용"){
		$("#identityNum_txt").text("사업자등록번호");
		$("#identityNum").mask("999-99-99999");	
	}else{
		let phone = "<?=$phone?>";
		$("#identityNum_txt").text("핸드폰번호");
		$("#identityNum").val(phone);	
		$("#identityNum").mask("010-9999-9999");	

	}
}

function set_tax(){
	let taxationType = $("#taxationType_tmp").val();
	let totalAmount = $("#totalAmount").val();
	let url = "<?=SELF?>?mode=tax";
	url+="&taxationType="+taxationType;
	url+="&totalAmount="+totalAmount;
	actarea.location.href=url;
}

$(function(){
	set_tax();
	set_idno();
	$(".taxationType,#totalAmount").on("change",function(){
		set_tax();
	});	
	$(".tradeUsage").on("click",function(){
		$("#identityNum").val('');
		set_idno(this.value);
	});	
});
</script>

<div style="padding:0 10px 0 10px">

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con" style="padding-left:10px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
	  </tr>
	  <tr>
		<td> </td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	</table>

	<!--내용이 들어가는 곳 시작-->

	<br/>

    <table border="0" cellspacing="1" cellpadding="3" width="100%">

		<form name="fmData" method="post" action="../../api/Cashbill/RegistIssue.php">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="orderNumber" value='<?=$code?>-<?=$id_no?>'>
		<input type="hidden" name="code" value='<?=$code?>'>
		<input type="hidden" name="id_no" value='<?=$id_no?>'>
		<input type="hidden" name="assort" value='<?=$assort?>'>
		<input type="hidden" name="phone" value='<?=$phone?>'>
		<input type="hidden" name="tradeType" value='승인거래'>
		<input type="hidden" name="tradeOpt" value='일반'>
		<tr><td colspan="8"  bgcolor="#5E90AE" height="2"></td></tr>

        <tr>
          <td class="subject" width="15%">거래구분</td>
          <td width="35%">
	           <?=radio("소득공제용,지출증빙용","소득공제용,지출증빙용",$rs[tradeUsage],'tradeUsage')?>
          </td>
          <td class="subject" width="15%">과세형태</td>
          <td>
	           <?=radio("과세,비과세","과세,비과세",$rs[taxationType],'taxationType')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%"><span id="identityNum_txt">핸드폰번호</span></td>
          <td>
	           <?=html_input('identityNum',25,28)?>
          </td>
          <td class="subject" width="15%">주문자명</td>
          <td>
	           <?=html_input('customerName',25,20)?>
          </td>

        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">거래금액</td>
          <td colspan="3">
	           <?=html_input('totalAmount',15,9,'box numberic')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">공급가액</td>
          <td>
	           <?=html_input('supplyCost',15,9,'box numberic')?>
          </td>

          <td class="subject">부가세</td>
          <td>
	           <?=html_input('tax',10,9,'box numberic')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject">주문상품명</td>
          <td colspan="3">
	           <?=html_input('itemName',80,100)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>        

        <tr><td colspan="12" bgcolor='#E1E1E1' height=1></td></tr>
        <tr>
		  <td colspan="10">
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td>
						<span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 현금영수증발행 </a></span>&nbsp;&nbsp;
						<span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
					</td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>
		  </td>
        </tr>

        <tr>
		  <td colspan="10" height="20">
		  </td>
        </tr>
	</form>
	</table>

	</div>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>