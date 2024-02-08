<?
session_start();

#### include
include_once ("include/fun_basic.php");



#### DB Connent
include_once ("include/info_dbconn.php");
include_once ("lib/class.$database.php");


#### mode

if ($dong){

	$dbo = new MiniDB($info);

	$sql = "select * from zipcode where dong like '%$dong%' order by sido,gugun,dong,sasuham,dosuh,jibun1";

	if (list($rows)=$dbo->query($sql)) {
			while ($rs=$dbo->next_record()) {
				$jibun = ($rs[jibun1] && $rs[jibun2])? "~" : "";
				$vSasuham = ($rs[sasuham])? ' ' . $rs[sasuham]:"";
				$vDosuh = ($rs[dosuh])? ' ' . $rs[dosuh]:"";
				$vJibun = ($rs[jibun1] && !$rs[jibun2])? ' ' . $rs[jibun1]:"";
				$value .= "," . $rs[zipcode] . "#" .$rs[sido] . " " . $rs[gugun] . " " . $rs[dong] . $vSasuham . $vDosuh . $vJibun;
				$txt .= "," . $rs[sido] . " " . $rs[gugun] . " " . $rs[dong] . " " . $rs[sasuham] . " " . $rs[dosuh] . " " . $rs[jibun1] . $jibun . $rs[jibun2];
			}


		$txt = "주소를 선택해 주세요." . $txt;
	}

	if(!$rows) $txt = "동 이름을 다시 입력해 주세요.";

	$ADDR2 = option_str($txt,$value);
	$DONG = $dong;

}else{

	$FOCUS = "onLoad='document.fmAddrAction1.dong.focus()'";

}

$FORM = $fm;
$ZIPCODE = $zipcode;
$ADDRESS = $address;
$NEXTCTL = $nextctl;

?>

<html style="width: 400px; height: 430px">

<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>우편번호 찾기</title>
<link rel="stylesheet" href="include/basic2.css" type="text/css">
<script language="JavaScript" src="include/form_check.js"></script>

<script language="JavaScript">
<!--

function checkDong(fm){
	if(check_blank(fm.dong,'동읍면 이름을',0)=='wrong'){return false}
}

function checkInfo(fm){
	if(fm.zipcode.value == ''){alert('기본 주소를 선택하여 주세요. \n\n\n만약 선택할 기본 주소가 없다면 먼저 동읍면의 이름을 입력하십시오.');return false}
	if(check_blank(fm.addr2,'나머지 주소를',0)=='wrong'){return false}
}


function winClose(fm){

	var zipcode = fm.zipcode.value;

	opener.<?=$FORM?>.zipcode1.value = zipcode.substr(0,3);
	opener.<?=$FORM?>.zipcode2.value = zipcode.substr(4,3);
	opener.<?=$FORM?>.address.value = fm.addr1.value +  " " + fm.addr2.value;

	self.close();

}
//-->
</script>

</head>

<body bgcolor="#d4d0c8" text="#000000"  style="background: threedface; color: windowtext; margin: 10px; BORDER-STYLE: none" scroll=no <?=$FOCUS?> onLoad="<?=$ERROR?>">

<table align="center" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td height="3"></td>
  </tr>
  <tr>
    <td align="center" valign="middle">

<fieldset style="width: 100%; text-align: center">
<legend>동 / 읍 / 면 검색</legend>

	<table border="0" cellpadding="0" cellspacing="0" width="350">
        <tr>
          			<td  colspan="2"> <p style="color:#000000">
							<strong>동/읍/면 중에서 하나를 입력하시고 "주소찾기"를
							클릭하세요 (예:서초동,은행동...)</strong></p></td>
        </tr>
        <!-- form fmAddrAction1 Begin -->
        <form name=fmAddrAction1 method=post onSubmit="return checkDong(document.fmAddrAction1)">
          <tr>
            <td width="70"> <p style="color:#000000">동/
                읍/ 면 &nbsp; </font></p></td>
            <td height="40"><font color="#424142">
              <input  type="text" name="dong" size="20" align="absmiddle" value="<?=$DONG?>">
              &nbsp;&nbsp;
              				<input type=submit value='주소찾기' style='color:000000;text-align:center'>
              </font></td>
          </tr>
          <!-- form fmAddrAction1 End -->
        </form>
        <tr>
          <td height="3"  colspan="2"></td>
        </tr>
	</table>
</fieldset>

<fieldset style="width: 100%; text-align: center">
<legend>기본주소 선택</legend>

	<table border="0" cellpadding="0" cellspacing="0" width="350">
        <tr>
          			<td height="30" colspan="2"> <p>
							<strong style="color:#000000">기본주소를 선택하시고 나머지 주소를 입력하세요.</strong></p></td>
        </tr>
        <tr>
          <td height="25" width="80"> <p style="color:#000000">기본주소&nbsp;&nbsp;&nbsp;&nbsp;
              </font></p></td>
          <td height="25"><font color="#424142">
            <select  name="address2" size="1" align="absmiddle" style="width:250px" onChange="
				var addr = this.options[selectedIndex].value;
				var addrArray = addr.split('#');

				fm=document.fmAddrInfo;
				fm.zipcode.value= addrArray[0];
				fm.addr1.value = addrArray[1];
				fm.addr2.focus();
			">
				<?=$ADDR2?>
            </select>
            </font></td>
        </tr>
		<tr>
          <td height="3"  colspan="2"></td>
        </tr>
        <tr>
          <td height=10>&nbsp;</td>
        </tr>
	</table>
</fieldset>

<fieldset style="width: 100%; text-align: center">
<legend>번지 호수 입력</legend>
	<table border="0" cellpadding="0" cellspacing="0" width="350">

		<tr>
          			<td height="30" colspan="2"> <p>
							<strong style="color:#000000">번지/호 또는 아파트 동/호수를 입력하세요.</strong></p></td>
        </tr>
        <!-- form fmAddrInfo Begin -->
        <form name=fmAddrInfo onSubmit="return checkInfo(document.fmAddrInfo)" action="javascript:winClose(document.fmAddrInfo)">
          <tr>
            <td height="25"> <p style="color:#000000">우편번호
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font></p></td>
            <td height="25"><font color="#424142">
              <input  type="text" name="zipcode" size="7" readonly>
              </font></td>
          </tr>
          <tr>
            <td height="25"> <p style="color:#000000">기본주소
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </font></p></td>
            <td height="25"><font color="#424142">
              <input  type="text" name="addr1" size="38" readonly>
              </font></td>
          </tr>
          <tr>
            <td height="25"> <p style="color:#000000">나머지주소
                &nbsp; </font></p></td>
            <td height="25"><font color="#424142">
              <input  type="text" name="addr2" size="38">
              </font></td>
          </tr>
          <tr>
            <td height="30" align="center" colspan="2"> <p>
                				<input type=submit value="  확  인  " style="color:000000;text-align:center">
              </p></td>
          </tr>
          <!-- form fmAddrInfo End -->
        </form>
      </table>
</fieldset>

	  </td>
  </tr>

  <tr>
    <td height="30" align="right"  style="padding-right:10px;">
      <p><input type='button' value='창닫기' onClick='self.close()' style='color:000000;text-align:center'></p></td>
  </tr>
</table>



</body>

</html>