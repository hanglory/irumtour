<?
$sql = "alter table ez_member add address_old varchar(250)";
$dbo->query($sql);

switch($mode){
	case "chkid":
		$id= trim($id);
		if($id){
			$sql = "select * from ez_member where id='$id' ";
			list($rows) = $dbo->query($sql);
			if($rows){
				echo "<script>alert('�̹� ���� ���̵� �����մϴ�.');parent.document.fmData.id.value='';parent.document.fmData.id.focus();parent.document.fmData.id_chk.value=0;</script>";
			}else{
				echo "<script>alert('��밡���մϴ�.    ');parent.document.fmData.pwd.focus();parent.document.fmData.id_chk.value=1;</script>";
			}
		}else{
			echo "<script>parent.document.fmData.id.value='';parent.document.fmData.id_chk.value=0;</script>";
		}
		exit;
		break;

	case "chkcell":
		$cell= trim($cell);
		if($cell){
			$sql = "select * from ez_member where cell='$cell' ";
			list($rows) = $dbo->query($sql);
			if($rows){
				echo "<script>alert('�̹� ���� �ڵ�����ȣ�� �����մϴ�.');parent.document.fmData.cell1.value='';parent.document.fmData.cell2.value='';parent.document.fmData.cell3.value='';parent.document.fmData.cell1.focus();parent.document.fmData.cell_chk.value=0;</script>";
			}else{
				echo "<script>alert('��밡���մϴ�.    ');parent.document.fmData.email.focus();parent.document.fmData.cell_chk.value=1;</script>";
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
	if(check_blank(fm.id,'���̵�',3)=='wrong'){return}
	if(check_strnum(fm.id,'���̵�')=='wrong'){return}
	if(fm.id_chk.value=="0"){alert('�ߺ�Ȯ���� ���� �����̽��ϴ�.');return}

	if(check_blank(fm.pwd,'��й�ȣ��',3)=='wrong'){return}
	if(check_blank(fm.pwd_check,'��й�ȣ Ȯ����',3)=='wrong'){return}
<?}?>
	if(check_strnum(fm.pwd,'��й�ȣ')=='wrong'){return}
	if(fm.pwd.value != fm.pwd_check.value){alert('��й�ȣ�� ��й�ȣ Ȯ���� ���� �ٸ��ϴ�. �ٽ� �ѹ� �Է��� �ּ���.');fm.pwd_check.focus();fm.pwd_check.select();return}

	if(check_blank(fm.name,'�̸���',0)=='wrong'){return}

	/*
	if(check_blank(fm.phone1,'��ȭ��ȣ��',2)=='wrong'){return}
	if(check_blank(fm.phone2,'��ȭ��ȣ��',3)=='wrong'){return}
	if(check_blank(fm.phone3,'��ȭ��ȣ��',4)=='wrong'){return}
	*/

	if(check_blank(fm.cell1,'�޴���ȭ ��ȣ��',3)=='wrong'){return}
	if(check_blank(fm.cell2,'�޴���ȭ ��ȣ��',3)=='wrong'){return}
	if(check_blank(fm.cell3,'�޴���ȭ ��ȣ��',4)=='wrong'){return}

	<?if(!$sessMember[id]){?>
	if(fm.cell_chk.value=="0"){alert('�ڵ��� �ߺ�Ȯ���� ���� �����̽��ϴ�.');return}
	<?}?>


	//if(check_blank(fm.email,'E-mail',0)=='wrong'){return}
	//if(check_email(fm.email)=='wrong'){return}

	//if(check_blank(fm.zipcode,'�����ȣ��',7)=='wrong'){return}
	//if(check_blank(fm.address,'�ּҸ�',0)=='wrong'){return}

	//if(fm.email_bit_tmp.value==""){alert('SMS ���� ���θ� ������ �ּ���');return}

	if(check_blank(fm.birth,'���������',10)=='wrong'){return}

	if(fm.sex_tmp.value==""){alert("������ ������ �ּ���.");return}

	if(fm.email_bit.value==""){alert('SMS ���� ���θ� ������ �ּ���');return}
	if(fm.bit_dm.value==""){alert('DM ���� ���θ� ������ �ּ���');return}


	fm.submit();
}

function addr_pop(fm,zipcode,address,nextctl){
	newWin("../html/script/addr.html?mobile=1&nzip="+zipcode+"&naddr="+address+"&next="+nextctl, 550,613);
}

function chk_id(){
	var fm = document.fmData;
	if(check_blank(fm.id,'���̵�',3)=='wrong'){return}
	if(check_strnum(fm.id,'���̵�')=='wrong'){return}

	var id = document.fmData.id.value;

	actarea.location.href="?mode=chkid&id="+id;
}

function chk_cell(){
	var fm = document.fmData;
	if(check_blank(fm.cell1,'�޴�����ȣ��',3)=='wrong'){return}
	if(check_strnum(fm.cell1,'�޴�����ȣ')=='wrong'){return}

	if(check_blank(fm.cell2,'�޴�����ȣ��',3)=='wrong'){return}
	if(check_strnum(fm.cell2,'�޴�����ȣ')=='wrong'){return}

	if(check_blank(fm.cell3,'�޴�����ȣ��',4)=='wrong'){return}
	if(check_strnum(fm.cell3,'�޴�����ȣ')=='wrong'){return}

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

		  <!--3.ȸ������-->
          <table class="tbl_list pdt10" cellpadding="0" cellspacing="0" summary="ȸ������">
		    <caption>ȸ������</caption>
		      <colgroup>
				 <col width="30%" />
				 <col width="*" />
			  </colgroup>
			  <tbody>

			<?if($sessMember[id]){?>
                <tr>
                  <th scope="row"><span class="red">*</span>���̵�</th>
                  <td colspan="2" height="25"><?=$rs[id]?></td>
                </tr>
			<?}else{?>
                <tr>
                  <th scope="row"><span class="red">*</span>���̵�</th>
                  <td colspan="2">
						<input type="text" name="id" id="id"  class="input" value="<?=$rs[id]?>" onchange="document.fmData.id_chk.value=0" size="15" maxlength="30" >  <a href="javascript:chk_id()"><img src="/html/images/member/btn_idcheck.gif" alt="�ߺ�Ȯ��" /></a>
				  </td>
                </tr>
			<?}?>

                <!--
				<tr>
                  <th scope="row"><span class="point">&nbsp;</span>���ȸ��</th>
                  <td colspan="2"><label><input type="checkbox" name="bit_membership" id="bit_membership" value="1" <?=($rs[bit_membership])?"checked='checked'":""?>> ���ȸ�� ��û�մϴ�.</label></td>
                </tr>
				-->

                <tr>
                  <th scope="row"><span class="red">*</span>��й�ȣ</th>
                  <td colspan="2"><?=html_input("pwd",20,20)?></td>
                </tr>
                <tr>
                  <th scope="row"><span class="red">*</span>��й�ȣȮ��</th>
                  <td colspan="2"><?=html_input("pwd_check",20,20)?></td>
                </tr>
                <tr>
                  <th scope="row"><span class="red">*</span>�� ��</th>
                  <td colspan="2"><?=html_input("name",20,25)?></td>
                </tr>
                <tr>
                  <th scope="row"><span class="red"></span>��ȭ��ȣ</th>
                  <td colspan="2"><?=html_input("phone1",4,4,"box numberic eng")?>
				<span>-</span>
				<?=html_input("phone2",4,4,"box numberic eng")?>
				<span>-</span>
				<?=html_input("phone3",4,4,"box numberic eng")?></td>
                </tr>
                <tr>
                  <th scope="row"><span class="red">*</span>�ڵ�����ȣ</th>
                  <td colspan="2"><?=html_input("cell1",4,4,"box numberic eng")?>
				<span>-</span>
				<?=html_input("cell2",4,4,"box numberic eng")?>
				<span>-</span>
				<?=html_input("cell3",4,4,"box numberic eng")?>

				<?if(!$sessMember[id]){?>
				<a href="javascript:chk_cell()"><img src="/html/images/member/btn_idcheck.gif" alt="�ߺ�Ȯ��" /></a>
				<?}?>
				</td>
                </tr>
                <tr>
                  <th scope="row"><span class="red"></span>�̸����ּ�</th>
                  <td colspan="2"><?=html_input("email",20,50)?></td>
                </tr>
                <tr>
                  <th scope="row">&nbsp;<!-- <span class="red">*</span> -->�� ��</th>
                  <td colspan="2">
				  <?=html_input("zipcode",7,7)?> <a href="javascript:set_zip()"><img src="images/common/btn_zipcode.gif" alt="�����ȣã��"/></a>
                  <p style="margin-top:10px"><?=html_input("address",30,240)?></p>
                  <p style="margin-top:10px"><?=html_input("address2",30,240)?></p>



				<!--���� �ּ�-->
				<span id="guide" style="color:#999;display:none"></span>
				<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>
				<script>
				function set_zip() {
					new daum.Postcode({
						oncomplete: function(data) {
							// �˾����� �˻���� �׸��� Ŭ�������� ������ �ڵ带 �ۼ��ϴ� �κ�.

							// ���θ� �ּ��� ���� ��Ģ�� ���� �ּҸ� �����Ѵ�.
							// �������� ������ ���� ���� ��쿣 ����('')���� �����Ƿ�, �̸� �����Ͽ� �б� �Ѵ�.
							var fullRoadAddr = data.roadAddress; // ���θ� �ּ� ����
							var extraRoadAddr = ''; // ���θ� ������ �ּ� ����

							// ���������� ���� ��� �߰��Ѵ�.
							if(data.bname !== ''){
								extraRoadAddr += data.bname;
							}
							// �ǹ����� ���� ��� �߰��Ѵ�.
							if(data.buildingName !== ''){
								extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
							}
							// ���θ�, ���� ������ �ּҰ� ���� ���, ��ȣ���� �߰��� ���� ���ڿ��� �����.
							if(extraRoadAddr !== ''){
								extraRoadAddr = ' (' + extraRoadAddr + ')';
							}
							// ���θ�, ���� �ּ��� ������ ���� �ش� ������ �ּҸ� �߰��Ѵ�.
							if(fullRoadAddr !== ''){
								fullRoadAddr += extraRoadAddr;
							}

							// �����ȣ�� �ּ� ������ �ش� �ʵ忡 �ִ´�.
							//document.getElementById("zipcode").value = data.postcode1 + "-" + data.postcode2;
							document.getElementById("zipcode").value = data.zonecode;
							document.getElementById("address").value = fullRoadAddr;
							document.getElementById("address_old").value = data.jibunAddress;

							// ����ڰ� '���� ����'�� Ŭ���� ���, ���� �ּҶ�� ǥ�ø� ���ش�.
							if(data.autoRoadAddress) {
								//����Ǵ� ���θ� �ּҿ� ������ �ּҸ� �߰��Ѵ�.
								var expRoadAddr = data.autoRoadAddress + extraRoadAddr;
								document.getElementById("guide").innerHTML = '(���� ���θ� �ּ� : ' + expRoadAddr + ')';

							} else if(data.autoJibunAddress) {
								var expJibunAddr = data.autoJibunAddress;
								document.getElementById("guide").innerHTML = '(���� ���� �ּ� : ' + expJibunAddr + ')';

							} else {
								document.getElementById("guide").innerHTML = '';
							}
						}
					}).open();
				}
				</script>
				<!--���� �ּ�-->


                  </td>
                </tr>

                <tr>
					<th scope="row"><span class="point">*</span>�������</th>
					<td colspan="2"><?=html_input("birth",13,10,'box c')?> (���� : 1975/02/15)   &nbsp;&nbsp;&nbsp;(����: <?=radio("��,��","��,��",$rs[sex],'sex')?>)</td>
				</tr>


                <tr>
                  <th scope="row"><span class="red">*</span>�̸��� ���ſ���</th>
                  <td colspan="2"><select name="email_bit"><?=option_str("���ſ� �����մϴ�,���ſ� ���������ʽ��ϴ�.","1,0",$rs[email_bit])?></select></td>
                </tr>

                <tr>
                  <th scope="row"><span class="red">*</span>DM ���ſ���</th>
                  <td colspan="2"><select name="bit_dm"><?=option_str("���ſ� �����մϴ�,���ſ� ���������ʽ��ϴ�.","1,0",$rs[bit_dm])?></select></td>
                </tr>
			  </tbody>
          </table>


		  <div class="btn_group mgt15"><a href="javascript:frm_check(this)"><?if($sessMember){?><img src="/html/images/common/btn_ok.gif" alt="Ȯ��" height="40" /><?}else{?><img src="/html/images/member/btn_join.gif" alt="�����ܰ�" height="40" /><?}?></a>&nbsp;&nbsp;<a href="javascript:cancel()"><img src="/html/images/member/btn_cancel.gif" alt="���" height="40" /></a></div>

          <!--ȸ����������-->