<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_member";
$MENU = $filecode;
$TITLE = "회원관리";


if($mode){
	$phone = "${phone1}-${phone2}-${phone3}";
	$cell = "${cell1}-${cell2}-${cell3}";
	if($phone=="--") $phone = "";
	if($cell=="--") $cell = "";
    $pwd_db = create_hash($pwd);
}

#### mode
if($mode=="save"){

		$pwdQuery = ($pwd)? " pwd = '$pwd_db', ":"";
		$reg_date = date('Y/m/d');
		$reg_date2 = date('H:i:s');


        if(!$address) $zipcode="";

		$sqlInsert="
		   insert into ez_member (
              cp_id,
              id,
              pwd,   
              name,
              phone1,
              phone2,
              phone3,
              phone,
              cell1,
              cell2,
              cell3,
              cell,
              email,
              email_bit,
              name_eng,
              name_eng2,
              zipcode,
              address,
              address2,
              memo,
			  reg_date,
			  reg_date2
		  ) values (
			  '$CP_ID',
              '$id',
			  password('$pwd'),
              '$name',
              '$phone1',
              '$phone2',
              '$phone3',
              '$phone',
              '$cell1',
              '$cell2',
              '$cell3',
              '$cell',
              '$email',
              '$email_bit',
              '$name_eng',
              '$name_eng2',
              '$zipcode',
              '$address',
              '$address2',
              '$memo',
              '$reg_date',
              '$reg_date2'
		)";


		$sqlModify="
		   update $table set
			  $pwdQuery
			  name = '$name',
			  phone1 = '$phone1',
			  phone2 = '$phone2',
			  phone3 = '$phone3',
			  phone = '$phone',
			  cell1 = '$cell1',
			  cell2 = '$cell2',
			  cell3 = '$cell3',
			  cell = '$cell',
			  email = '$email',
			  email_bit = '$email_bit',
			  name_eng = '$name_eng',
			  name_eng2 = '$name_eng2',
			  zipcode = '$zipcode',
			  address = '$address',
              address2 = '$address2',
			  memo = '$memo'
		   where id_no=$id_no
		";

		$sql = ($rs[id])? $sqlModify :$sqlInsert;

		if($id_no){
			$sql = $sqlModify;
			$url = "view_${filecode}.php?id_no=$id_no";
		}else{
			$sql = $sqlInsert;
			$url = "list_${filecode}.php";
		}

		//checkVar("",$sql);exit;

		if($dbo->query($sql)){
			msggo("저장하였습니다.",$url);
		}else{
			checkVar(mysql_error(),$sql);
		}
		exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){

		$sql = "delete from $table where id_no = $check[$i]";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php");exit;

}elseif ($mode=="chkid"){
		$id= trim($id);
		if($id){
			$sql = "select id from ez_member where id='$id' union all select id from ez_withdraw_id where id='$id'";
			list($rows) = $dbo->query($sql);
			if($rows){
				echo "<script type='text/javascript'>alert('이미 같은 아이디가 존재합니다.');parent.document.fmData.id.value='';parent.document.fmData.id.focus();parent.document.fmData.id_chk.value=0;</script>";
			}else{
				echo "<script type='text/javascript'>alert('사용가능합니다.    ');parent.document.fmData.pwd.focus();parent.document.fmData.id_chk.value=1;</script>";
			}

		}else{
			echo "<script type='text/javascript'>parent.document.fmData.id.value='';parent.document.fmData.id_chk.value=0;</script>";
		}
		exit;


}else{
	$sql = "select * from ez_member where id_no='$id_no'";
	$dbo->query($sql);
	$rs= $dbo->next_record();

	$phone = explode("-",$rs[phone]);
	$rs[phone1] = $phone[0];
	$rs[phone2] = $phone[1];
	$rs[phone3] = $phone[2];

	$cell = explode("-",$rs[cell]);
	$rs[cell1] = $cell[0];
	$rs[cell2] = $cell[1];
	$rs[cell3] = $cell[2];

}
//-------------------------------------------------------------------------------
?>
<link rel="stylesheet" type="text/css" href="/css/style.css" /></link>
<?include("../top.html");?>



		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=($mode=="sub")?"${rs[parent_company]}의 소속":""?><?=$TITLE?></td>
			</tr>
			<tr>
				<td colspan="3"> </td>
			</tr>
			<tr>
				<td background="../images/common/bg_title.gif" height="5"></td>
			</tr>
		</table>


		<br>


	<!-- container -->
<script language="JavaScript">
<!--

function chkForm(){

		var fm = document.fmData;

		if(check_strnum(fm.pwd,'비밀번호')=='wrong'){return}

		if(check_blank(fm.name,'이름을',0)=='wrong'){return}

		if(check_blank(fm.cell1,'핸드폰번호를',3)=='wrong'){return}
		if(check_blank(fm.cell2,'핸드폰번호를',3)=='wrong'){return}
		if(check_blank(fm.cell3,'핸드폰번호를',4)=='wrong'){return}

		if(check_num(fm.cell1,'핸드폰번호')=='wrong'){return}
		if(check_num(fm.cell2,'핸드폰번호')=='wrong'){return}
		if(check_num(fm.cell3,'핸드폰번호')=='wrong'){return}

		//if(check_blank(fm.email,'E-mail',0)=='wrong'){return}
		//if(check_email(fm.email)=='wrong'){return}

		//if(fm.email_bit_tmp.value==""){alert('이메일 수신 여부를 선택해 주세요');return}
		fm.submit();

}

function Addr_pop(fm,zipcode,address,nextctl){
	newWin("../../autoaddr.php?fm="+fm+"&zipcode="+zipcode+"&address="+address+"&nextctl="+nextctl, 400,400);
}

function chk_id(){
	var fm = document.fmData;
	if(check_blank(fm.id,'아이디를',4)=='wrong'){return}
	if(check_strnum(fm.id,'아이디')=='wrong'){return}

	var id = document.fmData.id.value;

	actarea.location.href="?mode=chkid&id="+id;
}
//-->
</script>



      <table border="0" cellspacing="1" cellpadding="3" width="750">

		<form name="fmData" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="save">
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="code" value='<?=$code?>'>
        <input type="hidden" name="name_eng" value='<?=$rs[name_eng]?>'>
        <input type="hidden" name="name_eng2" value='<?=$rs[name_eng2]?>'>


		<tr><td colspan="4"  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">아이디</td>
          <td colspan="3">
			<b><?=user_id($rs[id])?></b>
          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">한글이름</td>
          <td>
			<?=html_input("name",30,30)?>
          </td>

          <td class="subject" width="20%">비밀번호</td>
          <td>
			<?=html_input("pwd",20,10)?>
          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">전화번호</td>
          <td>
			<?=html_input("phone1",6,4)?>-<?=html_input("phone2",6,4)?>-<?=html_input("phone3",6,4)?>
          </td>

          <td class="subject" width="20%">휴대폰번호</td>
          <td>
			<?=html_input("cell1",6,4)?>-<?=html_input("cell2",6,4)?>-<?=html_input("cell3",6,4)?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">이메일주소</td>
          <td colspan="3">
			<?=html_input("email",60,80)?>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>

          <td class="subject" width="20%">이메일 수집여부</td>
          <td colspan="3">
			<?=radio("예.이메일을 받겠습니다.,아니오. 이메일을 받지않겠습니다.","1,0",$rs[email_bit],'email_bit')?>
          </td>
        </tr>
<!-- 
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">영문이름</td>
          <td colspan="3">
			성 <?=html_input("name_eng",20,40)?> 이름 <?=html_input("name_eng2",20,40)?>
          </td>
        </tr> -->


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">주소</td>
          <td colspan="3" height="70">
            <input type="text" name="zipcode" value="<?=$rs[zipcode]?>"  id="zipcode" size="10" maxlength="7" class="box" readonly>
            <span class="btn_pack medium bold"><a href="#" onClick="set_zip()"> 우편번호 </a></span>
            <br/>
            <?=html_input("address",80,200)?><br/>
            <?=html_input("address2",80,200)?>
            <input type="text" name="address_old" id="address_old" value="<?=$rs[address_old]?>" style="border:0;width:100%" class="box" readonly="readonly">

                <!--다음 주소-->
                <span id="guide" style="color:#999;display:none"></span>
                <script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>
                <script>
                function set_zip() {
                    new daum.Postcode({
                        oncomplete: function(data) {
                            // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                            // 도로명 주소의 노출 규칙에 따라 주소를 조합한다.
                            // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                            var fullRoadAddr = data.roadAddress; // 도로명 주소 변수
                            var extraRoadAddr = ''; // 도로명 조합형 주소 변수

                            // 법정동명이 있을 경우 추가한다.
                            if(data.bname !== ''){
                                extraRoadAddr += data.bname;
                            }
                            // 건물명이 있을 경우 추가한다.
                            if(data.buildingName !== ''){
                                extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                            }
                            // 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                            if(extraRoadAddr !== ''){
                                extraRoadAddr = ' (' + extraRoadAddr + ')';
                            }
                            // 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 추가한다.
                            if(fullRoadAddr !== ''){
                                fullRoadAddr += extraRoadAddr;
                            }

                            // 우편번호와 주소 정보를 해당 필드에 넣는다.
                            //document.getElementById("zipcode").value = data.postcode1 + "-" + data.postcode2;
                            document.getElementById("zipcode").value = data.zonecode;
                            document.getElementById("address").value = fullRoadAddr;
                            document.getElementById("address_old").value = data.jibunAddress;

                            // 사용자가 '선택 안함'을 클릭한 경우, 예상 주소라는 표시를 해준다.
                            if(data.autoRoadAddress) {
                                //예상되는 도로명 주소에 조합형 주소를 추가한다.
                                var expRoadAddr = data.autoRoadAddress + extraRoadAddr;
                                document.getElementById("guide").innerHTML = '(예상 도로명 주소 : ' + expRoadAddr + ')';

                            } else if(data.autoJibunAddress) {
                                var expJibunAddr = data.autoJibunAddress;
                                document.getElementById("guide").innerHTML = '(예상 지번 주소 : ' + expJibunAddr + ')';

                            } else {
                                document.getElementById("guide").innerHTML = '';
                            }
                        }
                    }).open();
                }
                </script>
                <!--다음 주소-->

          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">관리자메모</td>
          <td colspan="3">
			<?=html_textarea('memo',80,5)?>
          </td>
        </tr>


        <tr><td colspan="4" bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan="4" bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
		<td colspan="4">
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
				<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
				<td><span class="btn_pack medium bold"><a href="#" onClick="location.href='list_<?=$filecode?>.php?<?=$sessLink?>'"> 리스트 </a></span></td>
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

