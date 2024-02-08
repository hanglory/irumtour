<?
switch($mode){
	case "chkid":
		$id= trim($id);
		if($id){
			$sql = "select * from member where id='$id' ";
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

	default:
		$sql = "select * from member where id='$sessMember[id]' ";
		$dbo->query($sql);
		$rs = $dbo->next_record();

}

?>
<script language="JavaScript" src="include/form_check.js"></script>
<script language='JavaScript' src='include/function.js'></script>
<script language="JavaScript">
<!--
function formCheck(fm){

<?if(!$sessMember[id]){?>
	if(check_blank(fm.id,'아이디를',4)=='wrong'){return false}
	if(check_strnum(fm.id,'아이디')=='wrong'){return false}
	if(fm.id_chk.value=="0"){alert('가입여부확인을 하지 않으셨습니다.');return false}

	if(check_blank(fm.pwd,'비밀번호를',4)=='wrong'){return false}
	if(check_blank(fm.pwd_check,'비밀번호 확인을',6)=='wrong'){return false}
<?}?>
	if(check_strnum(fm.pwd,'비밀번호')=='wrong'){return false}
	if(fm.pwd.value != fm.pwd_check.value){alert('비밀번호와 비밀번호 확인이 서로 다릅니다. 다시 한번 입력해 주세요.');fm.pwd_check.focus();fm.pwd_check.select();return false}

	if(check_blank(fm.name,'이름을',0)=='wrong'){return false}

<?if(!$sessMember[id]){?>
	if(check_num(fm.rn1,'주민등록번호')=='wrong'){return false}
	if(check_num(fm.rn2,'주민등록번호')=='wrong'){return false}
	if(check_rn(fm.rn1,fm.rn2)=='wrong'){return false}
<?}?>

	if(check_blank(fm.phone1,'전화번호를',2)=='wrong'){return false}
	if(check_blank(fm.phone2,'전화번호를',3)=='wrong'){return false}
	if(check_blank(fm.phone3,'전화번호를',4)=='wrong'){return false}

	if(check_num(fm.phone1,'전화번호')=='wrong'){return false}
	if(check_num(fm.phone2,'전화번호')=='wrong'){return false}
	if(check_num(fm.phone3,'전화번호')=='wrong'){return false}

	if(check_blank(fm.cell1,'핸드폰번호를',3)=='wrong'){return false}
	if(check_blank(fm.cell2,'핸드폰번호를',3)=='wrong'){return false}
	if(check_blank(fm.cell3,'핸드폰번호를',4)=='wrong'){return false}

	if(check_num(fm.cell1,'핸드폰번호')=='wrong'){return false}
	if(check_num(fm.cell2,'핸드폰번호')=='wrong'){return false}
	if(check_num(fm.cell3,'핸드폰번호')=='wrong'){return false}


	if(check_blank(fm.zipcode,'우편번호를',7)=='wrong'){return false}
	if(check_blank(fm.address,'주소를',0)=='wrong'){return false}

	if(check_blank(fm.email,'E-mail',0)=='wrong'){return false}
	if(check_email(fm.email)=='wrong'){return false}


	if(fm.email_bit_tmp.value==""){alert('이메일 수신 여부를 선택해 주세요');return false}
}

function Addr_pop(fm,zipcode,address,nextctl){
	newWin("./autoaddr.php?fm="+fm+"&zipcode="+zipcode+"&address="+address+"&nextctl="+nextctl, 400,400);
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

		<table width="960" border="0" cellspacing="0" cellpadding="0">

		<form name="fmData" method=post onsubmit="return formCheck(this)" action="script/member_join.php">
		<input type="hidden" name="mode" value="<?=($rs[id])?"modify":"save"?>">
		<input type="hidden" name="id_chk" value="0">

          <tr>
            <td align="center">
			<table width="935" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" bgcolor="#495164" height="2"></td>
              </tr>

			  <?if($sessMember[id]){?>
			  <tr>
                <td width="190" class="sj_tit"><img src="images/members/ico_must.gif"><img src="images/members/ict01.gif"></td>
                <td class="sj_sub"><b><?=$rs[id]?></b></td>
              </tr>
			  <?}else{?>
			  <tr>
                <td width="190" class="sj_tit"><img src="images/members/ico_must.gif"><img src="images/members/ict01.gif"></td>
                <td class="sj_sub"><input type="id" name="id"  id="textfield" size="20" value="<?=$rs[id]?>" onchange="document.fmData.id_chk.value=0" maxlength="30" class="input">
				<img src="images/members/btn_idcheck.gif" align="absmiddle" style="cursor:pointer" onclick="chk_id()">
				</td>
              </tr>
			  <?}?>

              <tr>
                <td width="190" class="sj_tit"><img src="images/members/ico_must.gif"><img src="images/members/ict02.gif"></td>
                <td class="sj_sub"><?=html_input("pwd",20,10)?></td>
              </tr>
              <tr>
                <td width="190" class="sj_tit"><img src="images/members/ico_must.gif"><img src="images/members/ict03.gif"></td>
                <td class="sj_sub"><?=html_input("pwd_check",20,10)?></td>
              </tr>
              <tr>
                <td width="190" class="sj_tit"><img src="images/members/ico_must.gif"><img src="images/members/ict04.gif"></td>
                <td class="sj_sub"><?=html_input("name",30,30)?></td>
              </tr>
              <tr>
                <td width="190" class="sj_tit02"><img src="images/members/ict06.gif"></td>
                <td class="sj_sub"><?=html_input("phone1",6,4)?>-<?=html_input("phone2",6,4)?>-<?=html_input("phone3",6,4)?></td>
              </tr>
              <tr>
                <td width="190" class="sj_tit"><img src="images/members/ico_must.gif"><img src="images/members/ict07.gif"></td>
                <td class="sj_sub"><?=html_input("cell1",6,4)?>-<?=html_input("cell2",6,4)?>-<?=html_input("cell3",6,4)?></td>
              </tr>
               <tr>
                <td width="190" class="sj_tit"><img src="images/members/ico_must.gif"><img src="images/members/ict09.gif"></td>
                <td class="sj_sub"><?=html_input("email",60,80)?></td>
              </tr>
               <tr>
                <td width="190" class="sj_tit"><img src="images/members/ico_must.gif"><img src="images/members/ict10.gif"></td>
                <td class="sj_sub"><?=radio("예.이메일을 받겠습니다.,아니오. 이메일을 받지않겠습니다.","1,0",$rs[email_bit],'email_bit')?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
             <td style="padding:20px 0px 20px 10px;" class="left"><img src="images/members/ict_join04.gif"></td>
          </tr>
          <tr>
             <td align="center">

			 <table width="935" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" bgcolor="#495164" height="2"></td>
                </tr>
              <tr>
                <td width="190" class="sj_tit02"><img src="images/members/ict05.gif"></td>
                <td class="sj_sub"><?=html_input("name_eng",40,60)?></td>
              </tr>
              <tr>
                <td width="190" class="sj_tit02"><img src="images/members/ict08.gif"></td>
                <td class="sj_sub"><table width="80%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td style="padding-bottom:8px;">
						<input type="text" name="zipcode" value="<?=$rs[zipcode]?>"  id="textfield" size="7" maxlength="7" class="input" readonly>
						<img src="images/members/btn_zipcode.gif" onclick="Addr_pop('document.fmData','zipcode','address','email')" class="hand" />
					</td>
                  </tr>
                  <tr>
                    <td><?=html_input("address",80,200)?></td>
                  </tr>
                </table>
				</td>
              </tr>
            </table>
			</td>
          </tr>
          <tr>
            <td align="center" style="padding-top:20px;"><img src="images/members/btn_joinend.gif" id="submit" alt="가입완료"/>&nbsp;<img src="images/members/btn_cancel.gif" class="hand" onclick="cancel()" alt="취소" align="absmiddle"/></td>
          </tr>
		</form>
        </table>