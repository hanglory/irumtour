<?
include_once("../include/common_file.php");


//21.07.07 최경아대표님 요청에 따라 모든 사람에게 공개
//chk_power($_SESSION["sessLogin"]["proof"],"기본설정");



####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_account";
$MENU = "cmp_default";
$TITLE = "송금계좌 정보";


$sql = "alter table cmp_account add partner varchar(50)";
$dbo->query($sql);
$rs=$dbo->next_record();

#### mode
if($mode=="save"){

	$company = trim($company);

	if(!$id_no || ($id_no && $company!=$company_old)){
		$sql = "select * from cmp_account where company='$company' ";
		list($rows) = $dbo->query($sql);
		if($rows){
			error("이미 등록된 거래처 입니다. 거래처 명을 확인해 주세요.");
			exit;
		}
	}



	$reg_date = date('Y/m/d H:i:s');


	$sqlInsert="
	   insert into cmp_account (
          cp_id,
	      partner,
	      bank,
	      account,
	      owner,
	      reg_date,
	      reg_date2
	  ) values (
	      '$CP_ID',
          '$partner',
	      '$bank',
	      '$account',
	      '$owner',
	      '$reg_date',
	      '$reg_date2'
	)";


	$sqlModify="
	   update cmp_account set
	      partner = '$partner',
	      bank = '$bank',
	      account = '$account',
	      owner = '$owner'
	   where id_no='$id_no'
	";


	if($id_no){
		$sql = $sqlModify;
		$url = "view_${filecode}.php?id_no=$id_no";
	}else{
		$sql = $sqlInsert;
		$url = "list_${filecode}.php";
	}

	//checkVar("",$sql);exit;

	if($dbo->query($sql)){
		echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();self.close()</script>";
	}else{
        if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
    		checkVar(mysql_error(),$sql);
        }else{
            error("저장하지 못했습니다.");
        }
	}
	exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){

		$sql = "delete from $table where id_no = $check[$i]";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php");exit;


}else{
	$partners="";
	$sql = "select * from cmp_partner order where cp_id='$CP_ID' by company asc";
	$dbo->query($sql);
	while($rs= $dbo->next_record()){
		$partners.=",".$rs[company];
		$partners2.=",".$rs[id_no];
	}


	$sql = "select * from $table where id_no=$id_no and cp_id='$CP_ID'";
	$dbo->query($sql);
	$rs= $dbo->next_record();

	$rs[partner] = trim($rs[partner]);
	$rs[owner] = trim($rs[owner]);

}
//-------------------------------------------------------------------------------
?>
<?include("../top_min.html");?>
<script language="JavaScript">
<!--
function chkForm(){

	var fm  =document.fmData;


	if(check_blank(fm.partner,'거래처를',0)=='wrong'){return }
	if(check_blank(fm.bank,'은행명을',0)=='wrong'){return }

	fm.submit();
}


//-->
</script>
<style type="text/css">
body{padding:0 10px;}
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


      <table border="0" cellspacing="1" cellpadding="3" width="100%">

		<form name="fmData" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="save">
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>


		<tr><td colspan="2"  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">거래처</td>
          <td colspan="3">
	           <?=html_input("partner",30,50)?>
          </td>

        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="25%">은행명</td>
          <td>
            <?=html_input("bank",30,50)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td class="subject">계좌번호</td>
          <td>
            <?=html_input("account",50,50)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td class="subject">예금주</td>
          <td>
            <?=html_input("owner",30,50)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


		<?if($rs[id_no]){?>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">등록일</td>
          <td>
            <?=$rs[reg_date]?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
		<?}?>

        <tr><td colspan="2" bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan="2" bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
		<td colspan="2">
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">

					<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>

					<td><span class="btn_pack medium bold"><a href="#" onClick="opener.location.reload();self.close()"> 창닫기 </a></span></td>
		    </tr>
		  </table>
		  <!-- Button End------------------------------------------------>
		</td>
		</tr>
      </table>

	</form>

	<!--내용이 들어가는 곳 끝-->


<!-- Copyright -->
<?include_once("../bottom_min.html");?>

