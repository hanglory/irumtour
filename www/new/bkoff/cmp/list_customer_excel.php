<?
include_once("../include/common_file.php");


chk_power($_SESSION["sessLogin"]["proof"],"기본설정");
chk_power($_SESSION["sessLogin"]["proof"],"엑셀다운로드");

if(!strstr("@14.37.242.84@221.154.216.133@","@".$_SERVER["REMOTE_ADDR"]."@")){
$xls_name = "customer_" . date("Ymd") . ".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/vnd.ms-excel; charset=euc-kr");
header("Content-Disposition: attachment;filename=$xls_name;");
header( "Content-Description: PHP4 Generated Data" );
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="padding:10px">

    <table border="0" cellspacing="0" cellpadding="3" width="3500" id="tbl_cmp_list" style="border-collapse:collapse;border:1px solid #ccc ">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">대표자</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">고객명</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">성별</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">영문명</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">주민번호</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">여권번호</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">유효기간</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">연락처</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">스카이패스</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">팩스</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">주소</th>
		</tr>
<?
$sql_2 = $_SESSION['SQL_EXCEL'];
$dbo->query($sql_2);
//checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

			if($rs[rn]){
			$aes = new AES($rs[rn], $inputKey, $blockSize);
			$dec=$aes->decrypt();
			$rs[rn] = $dec;
			}
			if($rs[passport_no]){
			$aes = new AES($rs[passport_no], $inputKey, $blockSize);
			$dec=$aes->decrypt();
			$rs[passport_no] = $dec;
			}

?>
	    <tr align='center'>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[leader]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[name]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[sex]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[name_eng]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[rn]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[passport_no]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[passport_limit]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[phone]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[skypass]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[fax]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[address]?></td>
	    </tr>
<?
}
?>
	</table>

</div>

</body>
</html>
