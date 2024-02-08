<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_staff";
$MENU = "cmp_basic";
$TITLE = "담당자 관리";




#### mode
if($mode=="save"){

		$reg_date = date('Y/m/d');
		$reg_date2 = date('H:i:s');


		$pwdQuery = ($pwd)? " pwd= password('$pwd'), ":"";

		$reg_date = date('Y/m/d');
		$reg_date2 = date('H:i:s');

		for($i=0; $i<count($power);$i++){
			$powers .=  ",".$power[$i];
		}
		$power = substr($powers,1);


		$sqlInsert="
		   insert into $table (
			  id,
			  pwd,
			  name,
			  mposition,
			  phone1,
			  phone2,
			  phone3,
			  cell1,
			  cell2,
			  cell3,
			  email,
			  bit_login,
			  power,
			  reg_date,
			  reg_date2
		  ) values (
			  '$id',
			  password('$pwd'),
			  '$name',
			  '$mposition',
			  '$phone1',
			  '$phone2',
			  '$phone3',
			  '$cell1',
			  '$cell2',
			  '$cell3',
			  '$email',
			  '$bit_login',
			  '$power',
			  '$reg_date',
			  '$reg_date2'
		)";


		$sqlModify="
		   update $table set
			  $pwdQuery
			  name = '$name',
			  mposition = '$mposition',
			  phone1 = '$phone1',
			  phone2 = '$phone2',
			  phone3 = '$phone3',
			  cell1 = '$cell1',
			  cell2 = '$cell2',
			  cell3 = '$cell3',
			  bit_login = '$bit_login',
			  power = '$power',
			  email = '$email'
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
			$sql = "select * from $table where id='$id' ";
			list($rows) = $dbo->query($sql);
			if($rows){
				echo "<script>alert('이미 같은 아이디가 존재합니다.');parent.document.fmData.id.value='';parent.document.fmData.id.focus();parent.document.fmData.id_chk.value=0;</script>";
			}else{
				echo "<script>parent.document.fmData.id_chk.value=1;</script>";
			}

		}else{
			echo "<script>parent.document.fmData.id.value='';parent.document.fmData.id_chk.value=0;</script>";
		}
		exit;


}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();

}
//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(){

	var fm  =document.fmData;

<?if(!$rs[id]){?>
	if(check_blank(fm.id,'아이디를',4)=='wrong'){return }
	if(check_strnum(fm.id,'아이디')=='wrong'){return }
	if(fm.id_chk.value==0){fm.id.focus(); return }
	if(check_blank(fm.pwd,'비밀번호를',4)=='wrong'){return }
	if(check_blank(fm.pwd_check,'확인을 위한 비밀번호를',4)=='wrong'){return }
<?}?>
	if(fm.pwd.value != fm.pwd_check.value){alert('비밀번호가 서로 다릅니다.   ');fm.pwd_check.focus();return }

	if(check_blank(fm.name,'이름을',0)=='wrong'){return }

	fm.submit();
}


function chkid(){
	fm = document.fmData;
	var id;
	id = fm.id.value;

	actarea.location.href="?mode=chkid&id="+id;

}

function Addr_pop(fm,zipcode,address,nextctl){
		newWin("../../autoaddr.php?fm="+fm+"&zipcode="+zipcode+"&address="+address+"&nextctl="+nextctl, 400,400,0,0);
}


//-->
</script>

<?include("../top.html");?>



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


      <table border="0" cellspacing="1" cellpadding="3" width="750">

		<form name="fmData" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="save">
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="id_chk" value='0'>


		<tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>
        <tr>
          <td class="subject" width="140">아이디</td>
          <td>
		    <?if(!$rs[id_no]){?>
            <input class="box" type="text" name="id" value="<?=$rs[id]?>" size=20 maxlength="20" onchange="document.fmData.id_chk.value=0"  onfocus="chkid()" onblur="chkid()" onchange="chkid()">
			<font color="#FF6633">아이디를 입력하시면 자동으로 중복체크를 하게 됩니다.</font>
			<?}else{?>
			<b><?=$rs[id]?></b>
			<?}?>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject">비밀번호</td>
          <td>
            <input class="box" type="password" name="pwd"  size=20 maxlength="20">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>
        <tr>
          <td class="subject">비밀번호 확인</td>
          <td>
            <input class="box" type="password" name="pwd_check"  size=20 maxlength="20">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>
        <tr>
          <td class="subject">직원명</td>
          <td>
            <input class="box" type="text" name="name" value="<?=$rs[name]?>" size=30 maxlength="30">
			, 직함 <input class="box" type="text" name="mposition" value="<?=$rs[mposition]?>" size=10 maxlength="20">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject">연락처</td>
          <td>
            <input class="box" type="text" name="phone1" value="<?=$rs[phone1]?>" size="4" maxlength="4"> -
            <input class="box" type="text" name="phone2" value="<?=$rs[phone2]?>" size="4" maxlength="4"> -
            <input class="box" type="text" name="phone3" value="<?=$rs[phone3]?>" size="4" maxlength="4">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject">핸드폰번호</td>
          <td>
            <input class="box" type="text" name="cell1" value="<?=$rs[cell1]?>" size="4" maxlength="4"> -
            <input class="box" type="text" name="cell2" value="<?=$rs[cell2]?>" size="4" maxlength="4"> -
            <input class="box" type="text" name="cell3" value="<?=$rs[cell3]?>" size="4" maxlength="4">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject">이메일</td>
          <td>
            <input class="box" type="text" name="email" value="<?=$rs[email]?>" size=30 maxlength="30">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>
        <tr>
          <td class="subject">접속 허용</td>
          <td>
			<?
			if(!$rs[id_no]) $rs[bit_login]=1;
			?>
            <?=radio("허용,차단","1,0",$rs[bit_login],'bit_login')?>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>


        <tr>
          <td class="subject">권한</td>
          <td>
            <div style="width:580px">
			<?=checkbox($STAFF_POWER,$STAFF_POWER,$rs[power],'power')?>
			</div>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>



		<?if($rs[id_no]){?>
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject">등록일</td>
          <td>
            <?=$rs[reg_date]?>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>
		<?}?>

        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan=2 bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
		<td colspan=2>
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

