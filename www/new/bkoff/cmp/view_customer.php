<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_customer";
$MENU = "cmp_basic";
$TITLE = "고객 명단 정보";


#### staff
$sql = "select * from cmp_staff where bit_hide<>1 order by name asc";
$dbo->query($sql);
while($rs=$dbo->next_record()){
	$STAFF.=",$rs[name] ($rs[id])";
}


#### operation
if ($mode=="save"){

	$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');


	$rn = trim($rn);
	$passport_no = trim($passport_no);

	if($rn){
	$aes = new AES($rn, $inputKey, $blockSize);
	$enc = $aes->encrypt();
	$rn = $enc;
	}

	if($passport_no){
	$aes = new AES($passport_no, $inputKey, $blockSize);
	$enc = $aes->encrypt();
	$passport_no = $enc;
	}

	$sqlInsert="
	   insert into cmp_customer (
          cp_id,
		  id,
		  name,
		  leader,
		  sex,
		  name_eng,
		  rn,
		  passport_no,
		  phone,
		  staff,
		  skypass,
		  fax,
		  address,
		  passport_limit
	  ) values (
          '$CP_ID',
		  '$id',
		  '$name',
		  '$leader',
		  '$sex',
		  '$name_eng',
		  '$rn',
		  '$passport_no',
		  '$phone',
		  '$staff',
		  '$skypass ',
		  '$fax',
		  '$address',
		  '$passport_limit'
	)";


	$sqlModify="
	   update cmp_customer set
		  id = '$id',
		  name = '$name',
		  leader = '$leader',
		  sex = '$sex',
		  name_eng = '$name_eng',
		  rn = '$rn',
		  passport_no = '$passport_no',
		  phone = '$phone',
		  staff = '$staff',
		  skypass  = '$skypass ',
		  fax = '$fax',
		  address = '$address',
		  passport_limit = '$passport_limit'
	   where id_no='$id_no'
	";

	if($id_no){
		$sql =$sqlModify;
		$url = "view_${filecode}.php?id_no=$id_no&ctg1=$ctg1";
	}else{
		$sql =$sqlInsert;
		$url = "list_${filecode}.php?ctg1=$ctg1&page=1";
	}

	//checkVar(mysql_error(),$sql);exit;

	if($dbo->query($sql)){
		If($id_no)  echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();history.back(-1)</script>";
		Else echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();self.close()</script>";

	}else{
		checkVar(mysql_error(),$sql);
	}
	exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){

		//$sql = "delete from $table where id_no = $check[$i]";
        $sql = "
            update cmp_people set
                bit_hide_customer=1
            where 
                id_no = $check[$i]
            limit 1
        ";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php?ctg1=$ctg1");exit;


}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
	if($rs[rn]){
	$aes = new AES($rs[rn], $inputKey, $blockSize);
	$dec=$aes->decrypt();
	$rs[rn] = $dec;
	}
  if(strstr($rs[passport_no],"error_")) $rs[passport_no]="";
	if($rs[passport_no]){
	$aes = new AES($rs[passport_no], $inputKey, $blockSize);
	$dec=$aes->decrypt();
	$rs[passport_no] = $dec;
	}

}
?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;

	if(check_blank(fm.name,'고객명을',0)=='wrong'){return }
	fm.submit();

}

jQuery(function($){

	$(".num").keypress(function(event){
		if(event.which && (event.which < 48 || event.which > 57)){
			event.preventDefault();
		}
	});

	$(".rn").mask("999999-9999999");

});
</script>

<div style="padding:0 10px 0 10px">

	<table width="97%" border="0" cellspacing="0" cellpadding="0">
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

	<br>

    <table border="0" cellspacing="1" cellpadding="3" width="97%">
		<form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td class="subject">고객명</td>
          <td>
	           <?=html_input('name',30,28)?>
          </td>

          <td class="subject">성별</td>
          <td>
			   <select name="sex">
	           <?=option_str('M,F','M,F',$rs[sex])?>
			   </select>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">영문명</td>
          <td>
	           <?=html_input('name_eng',30,40)?>
          </td>
          <td class="subject">대표자</td>
          <td>
	           <?=html_input('leader',30,40)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject">주민번호</td>
          <td>
	          <?=html_input('rn',20,14)?>
          </td>

          <td class="subject">여권번호</td>
          <td>
	          <?=html_input('passport_no',30,45)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject">유효기간</td>
          <td>
	           <?=html_input('passport_limit',30,45)?>
          </td>

          <td class="subject">연락처</td>
          <td>
	           <?=html_input('phone',30,45)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">스카이패스</td>
          <td colspan="3">
	           <?=html_input('skypass',30,45)?>
          </td>

        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject">팩스</td>
          <td>
	           <?=html_input('fax',20,25)?>
          </td>

          <td class="subject">주소</td>
          <td>
	           <?=html_input('address',50,200)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr><td colspan="12" bgcolor='#E1E1E1' height=1></td></tr>

        <tr>
		  <td colspan=10>
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
					<td><span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span></td>
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

</div>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>