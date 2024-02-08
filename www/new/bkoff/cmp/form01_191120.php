<?
include_once("../include/common_file_report.php");

$page_width="700";

$edit_mode=1;


if($_GET[code]){
  $id_no = decrypt($code,$SALT);

  $edit_mode=0;;
}
if(!$code && !$id_no){exit;}
if($_GET["id_no"]){
  if(!$sessLogin[id]){exit;}
}


if($mode=="save"){

  $sql="
     update cmp_reservation set
        memo2 = '$memo2',
      partner2 = '$partner2'
     where id_no=$id_no
  ";
  $dbo->query($sql);
  error("저장하였습니다.");
  exit;
}


$sql = "select * from cmp_reservation where id_no=$id_no";
$dbo->query($sql);
$rs= $dbo->next_record();
$arr =explode("(",$rs[main_staff]);
$staff=$arr[0];


if(!$rs[d_air_id_no]){

  $sql2 = "select * from cmp_air where id_no=$rs[air_id_no]";
  $dbo2->query($sql2);
  $rs2= $dbo2->next_record();

  $d_air = $rs2[d_air];
  $d_air_no = $rs2[d_air_no];
  $d_time_s = $rs2[d_time_s];
  $d_time_e = $rs2[d_time_e];
  $r_air_no = $rs2[r_air_no];
  $r_time_s = $rs2[r_time_s];
  $r_time_e = $rs2[r_time_e];
  $airport_in = $rs2[airport_in];

}else{

  $sql2 = "select * from cmp_air where id_no=$rs[d_air_id_no]";
  $dbo2->query($sql2);
  $rs2= $dbo2->next_record();
  $d_air = $rs2[d_air];
  $d_air_no = $rs2[d_air_no];
  $d_time_s = $rs2[d_time_s];
  $d_time_e = $rs2[d_time_e];
  $airport_in = str_replace("공항","",$rs2[airport_in]);
  $airport_out = $rs2[airport_out];
  $airport_city = $rs2[city];
  $airport_place = $rs2[airport_place];

  $sql2 = "select * from cmp_air where id_no=$rs[r_air_id_no]";
  $dbo2->query($sql2);
  $rs2= $dbo2->next_record();
  $r_air = $rs2[r_air_no];
  $r_air_no = $rs2[r_air_no];
  $r_time_s = $rs2[r_time_s];
  $r_time_e = $rs2[r_time_e];
  $r_airport_in = str_replace("공항","",$rs2[airport_in]);
  $r_airport_out = $rs2[airport_out];
  $r_airport_city = $rs2[city];
  $r_airport_place = $rs2[airport_place];

}


if($doc_mode){
$xls_name = "reservation_request_".date("Ymd").".doc";
header("Content-type: application/vnd.ms-word");
header("Content-Type: application/vnd.ms-word; charset=euc-kr");
header("Content-Disposition: attachment;filename=$xls_name;");
header( "Content-Description: PHP4 Generated Data" );
}


$sql2 = "select * from cmp_golf where id_no=$rs[golf_id_no]";
$dbo2->query($sql2);
$rs2= $dbo2->next_record();


//거래처 불러오기
$PARTNERS = "";
//if($rs2[nation]) $filter_partner = " where nation='$rs2[nation]'";
$sql3 = "select * from cmp_partner $filter_partner order by company asc";
$dbo3->query($sql3);
//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql3);
while($rs3= $dbo3 ->next_record()){
  $PARTNERS .= ",$rs3[company] $rs3[name]";
}
$PARTNERS = substr($PARTNERS,1);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>예약요청서 - <?=$rs2[name]?></title>


<meta property="og:type" content="website">
<meta property="og:title" content="예약요청서 -<?=$rs2[name]?>">
<meta property="og:url" content="http://<?=$_SERVER["HTTP_HOST"]?>/<?=$_SE2RVER["REQUEST_URI"]?>">
<meta property="og:description" content="<?=$SITE_NAME?>">
<meta property="og:image" content="https://www.daumgolf.net/html/images/common/top_logo.gif">

<?if($edit_mode && !$doc_mode){?>
  <link rel="stylesheet" href="../include/default.css">
  <link rel="stylesheet" href="../include/basic.css" type="text/css">
  <script language="JavaScript" src="../../include/form_check.js"></script>
  <script language="JavaScript" src="../../include/function.js"></script>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>


  <!-- Include Core Datepicker Stylesheet -->
  <link rel="stylesheet" href="../../include/ui.datepicker.css" type="text/css" media="screen" title="core css file" charset="utf-8" />
  <!-- Include jQuery -->
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
  <!-- Include Core Datepicker JavaScript -->
  <script src="../../include/ui.datepicker.js" type="text/javascript" charset="utf-8"></script>
  <!-- Attach the datepicker to dateinput after document is ready -->
  <script type="text/javascript" charset="utf-8">
    jQuery(function($){$(".dateinput").datepicker();});
  </script>
  <script type="text/javascript" src="../../qtip/jquery.qtip-1.0.0-rc3.min.js"></script>
  <script type="text/javascript" src="../../include/jquery.maskedinput.min.js"></script>

  <style type="text/css">
  #partner2{
    font-size:10pt;
    padding:2px;
    border:1px solid #ccc;
  }
  </style>
<?}?>

</head>

<body leftmargin="0" topmargin="0">

<div style="width:700px;padding:30px;text-align:center;margin-left:auto;margin-right:auto;">

  <table width="<?=$page_width?>" border="0" cellspacing="0" cellpadding="0" align="center">


    <?if($edit_mode && !$doc_mode){?>
    <form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
    <input type="hidden" name="mode" value='save'>
    <input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
    <input type="hidden" name="memo" value='<?=$rs[memo]?>'>
    <?}?>

      <tr>
        <td align="left"><img src="http://daumgolf.net/html/bkoff2/cmp/info.gif"  width="550" height="125"/></td>
      </tr>
      <tr>
        <td height="50" align="center"><h1 style="font-size:20px;font-weight:bold">예약요청서</h1></td>
      </tr>
    <tr>
        <td>
    <table width="<?=$page_width?>" border="0" cellspacing="1" cellpadding="0" class="tbl" style="border-collapse:collapse;border:1px solid #ccc ">
          <tr>
            <td width="25%" height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">고객명</span></td>
            <td align="left" height="30" bgcolor="#ffffff" style="padding-left:10px;font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[name]?> (인원 <?=$rs[people]?>)</td>
          </tr>
          <tr>
            <td height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">골프장명</span></td>
            <td align="left" height="30" bgcolor="#ffffff" style="padding-left:10px;font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs2[name]?></td>
          </tr>
          <tr>
            <td height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">수    신</span></td>
            <td align="left" height="30" bgcolor="#ffffff" style="padding-left:10px;font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">

          <?if($edit_mode && !$doc_mode){?>
          <select name="partner2" id="partner2">
          <option value=""></option>
          <?=option_str($PARTNERS,$PARTNERS,$rs[partner2]);?>
          </select>
          <?}else{?>
            <?=$rs[partner2]?>
          <?}?>

      </td>
          </tr>
          <tr>
            <td height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">발    신</span></td>
            <td align="left" height="30" bgcolor="#ffffff" style="padding-left:10px;font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">다음골프 <?=$staff?></td>
          </tr>
          <tr>
            <td height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">일    정</span></td>
            <td align="left" height="30" bgcolor="#ffffff" style="padding-left:10px;font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[d_date]?> ~ <?=$rs[r_date]?>

      </td>
          </tr>
</table></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td><table width="<?=$page_width?>" border="0" cellspacing="1" cellpadding="0" class="tbl" style="border-collapse:collapse;border:1px solid #ccc ">
          <tr>
            <td width="25%" height="30" align="center" bgcolor="#f4f4f4" style="color:#F03;;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold;">항공</span></td>
            <td height="30" colspan="3" bgcolor="#ffffff" align="center" style="padding-left:10px;font-size:10pt;font-family:'Malgun Gothic','돋움'; color:#F03;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold;"><?=$d_air?> (<?=$airport_in?>) </span></td>
          </tr>
          <tr>
            <td width="25%" height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">*출발</span></td>
            <td width="25%" height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$d_air_no?></td>
            <td width="25%" height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$d_time_s?></td>
            <td width="25%" height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$d_time_e?></td>
          </tr>
          <tr>
            <td width="25%" height="30" align="center" bgcolor="#f4f4f4"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">*도착</span></td>
            <td width="25%" height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$r_air_no?></td>
            <td width="25%" height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$r_time_s?></td>
            <td width="25%" height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$r_time_e?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>

      <!--
      <tr>
        <td><table width="<?=$page_width?>" border="0" cellspacing="1" cellpadding="0" class="tbl" style="border-collapse:collapse;border:1px solid #ccc ">
          <tr>
            <td width="20%" rowspan="5" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">지상비</span></td>
            <td width="15%" height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">항목</span></td>
            <td width="20%" height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">금액(인)</span></td>
            <td width="10%" height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">인원</span></td>
            <td width="10%" height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">박수</span></td>
            <td width="25%" height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">합계</span></td>
          </tr>
          <tr>
            <td rowspan="2" align="center" bgcolor="#f8f8f8" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">입금가</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
          </tr>
          <tr>
            <td rowspan="2" align="center" bgcolor="#f8f8f8" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">기타</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
          </tr>
          <tr>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">&nbsp;</td>
          </tr>
        </table></td>
      </tr>
      -->
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td align="left"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">- 대표자명 : <?=$rs[name]?></span></td>
      </tr>
      <tr>
        <td><table width="<?=$page_width?>" border="0" cellspacing="1" cellpadding="0" class="tbl" style="border-collapse:collapse;border:1px solid #ccc ">
          <tr>
            <td width="10%" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">고객명</span></td>
            <td width="10%" height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">성별</span></td>
            <td width="20%" height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">영문명</span></td>
            <td width="20%" height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">주민번호</span></td>
            <td width="20%" height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">여권번호</span></td>
            <td width="20%" height="30" align="center" bgcolor="#f4f4f4" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">유효기간</span></td>
          </tr>

      <?
      $sql2= "select * from cmp_people where code=$rs[code] and bit=1 order by id_no asc";
      $dbo2->query($sql2);

      while($rs2=$dbo2->next_record()){

        if($rs2[rn]){
          $aes = new AES($rs2[rn], $inputKey, $blockSize);
          $dec=$aes->decrypt();
          $rs2[rn] = $dec;
        }
        if($rs2[passport_no]){
          $aes = new AES($rs2[passport_no], $inputKey, $blockSize);
          $dec=$aes->decrypt();
          $rs2[passport_no] = $dec;
        }
      ?>
      <tr>
            <td align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs2[name]?></td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs2[sex]?></td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs2[name_eng]?></td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=substr($rs2[rn],0,7)?>*******</td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs2[passport_no]?></td>
            <td height="30" align="center" bgcolor="#ffffff" style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs2[passport_limit]?></td>
          </tr>
      <?}?>

        </table></td>
      </tr>
      <tr>
        <td height="5"></td>
      </tr>
      <tr>
        <td><table width="<?=$page_width?>" border="0" cellspacing="1" cellpadding="0" class="tbl" style="border-collapse:collapse;border:1px solid #ccc ">
          <tr>
            <td width="25%" height="30" align="center" bgcolor="#f4f4f4"style="font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc"><span style="font-size:10pt;font-family:'Malgun Gothic','돋움';font-weight:bold">요청사항</span></td>

      <td align="left" bgcolor="#ffffff" style="padding:10px;font-size:10pt;font-family:'Malgun Gothic','돋움';border-left:1px solid #ccc;border-bottom:1px solid #ccc">
      <?//=nl2br($rs[memo])?>
      <?if(!$edit_mode || $doc_mode){?>
      <?=nl2br($rs[memo2])?>
      <?}else{?>
      <?=html_textarea("memo2",0,13)?>
      <?}?>
      </td>

          </tr>
     </table></td>
      </tr>

    <?if($edit_mode){?>
    </form>
    <?}?>
</table>


    <?if($edit_mode && !$doc_mode){?>
    <div style="padding:20px 0 30px 0">
    <!-- Button Begin---------------------------------------------->
    <table border="0" width="200" cellspacing="0" cellpadding="0" align="right">
    <tr align="right">
      <td><span class="btn_pack medium bold"><a href="#" onClick="document.fmData.submit()"> 저장 </a></span></td>
      <td><span class="btn_pack medium bold"><a href="<?=SELF?>?id_no=<?=$rs[id_no]?>&doc_mode=1"> Word 저장 </a></span></td>
      <td><span class="btn_pack medium bold"><a href="javascript:self.close()"> 창닫기 </a></span></td>
    </tr>
    </table>
    <!-- Button End------------------------------------------------>
    </div>
    <?}?>


</div>

</body>
</html>
