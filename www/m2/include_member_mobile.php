<?
switch($mode){
	case "chkid":
		$id= trim($id);
		if($id){
			$sql = "select * from ez_member where id='$id' ";
			list($rows) = $dbo->query($sql);
			if($rows){
				echo "<script>alert('이미 같은 아이디가 존재합니다.');parent.document.fmData.id.value='';parent.document.fmData.id.focus();parent.document.fmData.id_chk.value=0;</script>";
			}else{
				echo "<script>alert('사용가능합니다.    ');parent.document.fmData.pwd.focus();parent.document.fmData.id_chk.value=1;</script>";
			}
		}else{
			echo "<script>parent.document.fmData.id.value='';parent.document.fmData.id_chk.value=0;</script>";
		}
		exit;
		break;

	case "chkcell":
		$cell= trim($cell);
		if($cell){
			$sql = "select * from ez_member where cell='$cell' and cp_id='$CID' ";
			list($rows) = $dbo->query($sql);
			if($rows){
				echo "<script>alert('이미 같은 핸드폰번호가 존재합니다.');parent.document.fmData.cell1.value='';parent.document.fmData.cell2.value='';parent.document.fmData.cell3.value='';parent.document.fmData.cell1.focus();parent.document.fmData.cell_chk.value=0;</script>";
			}else{
				echo "<script>alert('사용가능합니다.    ');parent.document.fmData.email.focus();parent.document.fmData.cell_chk.value=1;</script>";
			}
		}else{
			echo "<script>parent.document.fmData.cell1.value='';parent.document.fmData.cell2.value='';parent.document.fmData.cell3.value='';parent.document.fmData.cell_chk.value=0;</script>";
		}
		exit;
		break;

	default:
		$sql = "select * from ez_member where id='$sessMember[id]' ";
		$dbo->query($sql);
		$rs = $dbo->next_record();
}
?>
<script language="JavaScript">
<!--
function frm_check(){

	var fm=document.fmData;

<?if(!$sessMember[id]){?>
	if(check_blank(fm.id,'아이디를',3)=='wrong'){return}
	if(check_strnum(fm.id,'아이디')=='wrong'){return}
	if(fm.id_chk.value=="0"){alert('중복확인을 하지 않으셨습니다.');return}

	if(check_blank(fm.pwd,'비밀번호를',3)=='wrong'){return}
	if(check_blank(fm.pwd_check,'비밀번호 확인을',3)=='wrong'){return}
<?}?>
	if(check_strnum(fm.pwd,'비밀번호')=='wrong'){return}
	if(fm.pwd.value != fm.pwd_check.value){alert('비밀번호와 비밀번호 확인이 서로 다릅니다. 다시 한번 입력해 주세요.');fm.pwd_check.focus();fm.pwd_check.select();return}

	if(check_blank(fm.name,'이름을',0)=='wrong'){return}

	/*
	if(check_blank(fm.phone1,'전화번호를',2)=='wrong'){return}
	if(check_blank(fm.phone2,'전화번호를',3)=='wrong'){return}
	if(check_blank(fm.phone3,'전화번호를',4)=='wrong'){return}
	*/

	if(check_blank(fm.cell1,'휴대전화 번호를',3)=='wrong'){return}
	if(check_blank(fm.cell2,'휴대전화 번호를',3)=='wrong'){return}
	if(check_blank(fm.cell3,'휴대전화 번호를',4)=='wrong'){return}

	<?if(!$sessMember[id]){?>
	if(fm.cell_chk.value=="0"){alert('핸드폰 중복확인을 하지 않으셨습니다.');return}
	<?}?>


	//if(check_blank(fm.email,'E-mail',0)=='wrong'){return}
	//if(check_email(fm.email)=='wrong'){return}

	//if(check_blank(fm.zipcode,'우편번호를',7)=='wrong'){return}
	//if(check_blank(fm.address,'주소를',0)=='wrong'){return}

	//if(fm.email_bit_tmp.value==""){alert('SMS 수신 여부를 선택해 주세요');return}

	if(check_blank(fm.birth,'생년월일을',10)=='wrong'){return}

	if(fm.sex_tmp.value==""){alert("성별을 선택해 주세요.");return}

	if(fm.email_bit.value==""){alert('SMS 수신 여부를 선택해 주세요');return}
	if(fm.bit_dm.value==""){alert('DM 수신 여부를 선택해 주세요');return}


	fm.submit();
}

function addr_pop(fm,zipcode,address,nextctl){
	newWin("../html/script/addr.html?mobile=1&nzip="+zipcode+"&naddr="+address+"&next="+nextctl, 550,613);
}

function chk_id(){
	var fm = document.fmData;
	if(check_blank(fm.id,'아이디를',3)=='wrong'){return}
	if(check_strnum(fm.id,'아이디')=='wrong'){return}

	var id = document.fmData.id.value;

	actarea.location.href="?mode=chkid&id="+id;
}

function chk_cell(){
	var fm = document.fmData;
	if(check_blank(fm.cell1,'휴대폰번호를',3)=='wrong'){return}
	if(check_strnum(fm.cell1,'휴대폰번호')=='wrong'){return}

	if(check_blank(fm.cell2,'휴대폰번호를',3)=='wrong'){return}
	if(check_strnum(fm.cell2,'휴대폰번호')=='wrong'){return}

	if(check_blank(fm.cell3,'휴대폰번호를',4)=='wrong'){return}
	if(check_strnum(fm.cell3,'휴대폰번호')=='wrong'){return}

	var cell = document.fmData.cell1.value + "-"+ document.fmData.cell2.value + "-" +document.fmData.cell3.value;

	actarea.location.href="?mode=chkcell&cell="+cell;
}

$(function(){

	$('#phone1').keyup(function(e){if(this.value.length==3) $("#phone2").focus();});
	$('#phone2').keyup(function(e){if(this.value.length==4) $("#phone3").focus();});

	$('#cell1').keyup(function(e){if(this.value.length==3) $("#cell2").focus();});
	$('#cell2').keyup(function(e){if(this.value.length==4) $("#cell3").focus();});
});
//-->
</script>

		<form name="fmData" method="post" action="/html/script/member_join.php">
		<input type="hidden" name="mode" value="<?=($rs[id])?"modify":"save"?>">
		<input type="hidden" name="id_chk" value="0">
		<input type="hidden" name="cell_chk" value="0">
		<input type="hidden" name="mobile" value="1">
		<input type="hidden" name="address_old" id="address_old" value="<?=$rs[address_old]?>">

		  <!--3.회원정보-->
          <table class="tbl_list pdt10" cellpadding="0" cellspacing="0" summary="회원정보">
		    <caption>회원정보</caption>
		      <colgroup>
				 <col width="30%" />
				 <col width="*" />
			  </colgroup>
			  <tbody>

			<?if($sessMember[id]){?>
                <tr>
                  <th scope="row"><span class="red">*</span>아이디</th>
                  <td colspan="2" height="25"><?=$rs[id]?></td>
                </tr>
			<?}else{?>
                <tr>
                  <th scope="row"><span class="red">*</span>아이디</th>
                  <td colspan="2">
						<input type="text" name="id" id="id"  class="input" value="<?=$rs[id]?>" onchange="document.fmData.id_chk.value=0" size="15" maxlength="30" >  <a href="javascript:chk_id()"><img src="/html/images/member/btn_idcheck.gif" alt="중복확인" /></a>
				  </td>
                </tr>
			<?}?>

                <!--
				<tr>
                  <th scope="row"><span class="point">&nbsp;</span>평생회원</th>
                  <td colspan="2"><label><input type="checkbox" name="bit_membership" id="bit_membership" value="1" <?=($rs[bit_membership])?"checked='checked'":""?>> 평생회원 신청합니다.</label></td>
                </tr>
				-->

                <tr>
                  <th scope="row"><span class="red">*</span>비밀번호</th>
                  <td colspan="2"><?=html_input("pwd",20,20)?></td>
                </tr>
                <tr>
                  <th scope="row"><span class="red">*</span>비밀번호확인</th>
                  <td colspan="2"><?=html_input("pwd_check",20,20)?></td>
                </tr>
                <tr>
                  <th scope="row"><span class="red">*</span>성 명</th>
                  <td colspan="2"><?=html_input("name",20,25)?></td>
                </tr>
                <tr>
                  <th scope="row"><span class="red"></span>전화번호</th>
                  <td colspan="2"><?=html_input("phone1",4,4,"box numberic eng")?>
				<span>-</span>
				<?=html_input("phone2",4,4,"box numberic eng")?>
				<span>-</span>
				<?=html_input("phone3",4,4,"box numberic eng")?></td>
                </tr>
                <tr>
                  <th scope="row"><span class="red">*</span>핸드폰번호</th>
                  <td colspan="2"><?=html_input("cell1",4,4,"box numberic eng")?>
				<span>-</span>
				<?=html_input("cell2",4,4,"box numberic eng")?>
				<span>-</span>
				<?=html_input("cell3",4,4,"box numberic eng")?>

				<?if(!$sessMember[id]){?>
				<a href="javascript:chk_cell()"><img src="/html/images/member/btn_idcheck.gif" alt="중복확인" /></a>
				<?}?>
				</td>
                </tr>
                <tr>
                  <th scope="row"><span class="red"></span>이메일주소</th>
                  <td colspan="2"><?=html_input("email",20,50)?></td>
                </tr>
                <tr>
                  <th scope="row">&nbsp;<!-- <span class="red">*</span> -->주 소</th>
                  <td colspan="2">
				  <?=html_input("zipcode",7,7)?> <a href="javascript:set_zip()"><img src="images/common/btn_zipcode.gif" alt="우편번호찾기"/></a>
                  <p style="margin-top:10px"><?=html_input("address",30,240)?></p>
                  <p style="margin-top:10px"><?=html_input("address2",30,240)?></p>



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

                <tr>
					<th scope="row"><span class="point">*</span>생년월일</th>
					<td colspan="2"><?=html_input("birth",13,10,'box c')?> (예시 : 1975/02/15)   &nbsp;&nbsp;&nbsp;(성별: <?=radio("남,여","남,여",$rs[sex],'sex')?>)</td>
				</tr>


                <tr>
                  <th scope="row"><span class="red">*</span>이메일 수신여부</th>
                  <td colspan="2"><select name="email_bit"><?=option_str("수신에 동의합니다,수신에 동의하지않습니다.","1,0",$rs[email_bit])?></select></td>
                </tr>

                <tr>
                  <th scope="row"><span class="red">*</span>DM 수신여부</th>
                  <td colspan="2"><select name="bit_dm"><?=option_str("수신에 동의합니다,수신에 동의하지않습니다.","1,0",$rs[bit_dm])?></select></td>
                </tr>
			  </tbody>
          </table>


		  <div class="btn_group mgt15"><a href="javascript:frm_check(this)"><?if($sessMember){?><img src="/html/images/common/btn_ok.gif" alt="확인" height="40" /><?}else{?><img src="/html/images/member/btn_join.gif" alt="다음단계" height="40" /><?}?></a>&nbsp;&nbsp;<a href="javascript:cancel()"><img src="/html/images/member/btn_cancel.gif" alt="취소" height="40" /></a></div>

          <!--회원정보수정-->