<?
include_once("../include/common_file_report.php");


$resv_id_no = rnf($resv_id_no);
$sql = "select * from cmp_reservation where id_no=$resv_id_no";
$dbo->query($sql);
$rs= $dbo->next_record();
$arr =explode("(",$rs[main_staff]);
$staff=$arr[0];
$staff_id=substr($arr[1],0,-1);

//상품명 불러오기
$sql2 = "select * from cmp_golf where id_no=$rs[golf_id_no]";
$dbo2->query($sql2);
$rs2= $dbo2->next_record();
$golf_name = $rs2[name];

//첫번째 골프장 불러오기
$sql2 = "select * from cmp_golf2 where id_no=$rs2[golf2_1_id_no]";
$dbo2->query($sql2);
$rs2= $dbo2->next_record();
$golfclulb_id = $rs2[id_no];
$fax = $rs2[reg_fax];

//직원핸드폰 불러오기
$sql2 = "select * from cmp_staff where id='$staff_id'";
$dbo2->query($sql2);
$rs2= $dbo2->next_record();
$staff_cell = "${rs2[cell1]}-${rs2[cell2]}-${rs2[cell3]}";
$staff_info = staff_full_name($staff_id);

//골프장 불러오기
$PARTNERS = "";
$PARTNERS2 = "";
//if($rs2[nation]) $filter_partner = " where nation='$rs2[nation]'";
$sql3 = "select * from cmp_golf2 where nation='한국' order by binary name asc";
$dbo3->query($sql3);
//checkVar(mysql_error(),$sql3);
while($rs3= $dbo3 ->next_record()){
  $PARTNERS .= ",$rs3[name]";
  $PARTNERS2 .= ",$rs3[id_no]";
}
$PARTNERS = substr($PARTNERS,1);
$PARTNERS2 = substr($PARTNERS2,1);


$days = get_day_night($rs[d_date],$rs[r_date],$rs[plan_type]);
if($days){
  $arr = explode("박",$days);
  $night = nf($arr[1]);
}


if(rnf($id_no)) $filter = " and id_no=$id_no";
$sql3 = "
    select * from cmp_fax where resv_id_no='$resv_id_no' $filter order by id_no desc limit 1
  ";
$dbo3->query($sql3);
$rs3=$dbo3->next_record();
//checkVar($rs3[fax].mysql_error(),$sql3);
$arr_teeoff = unserialize($rs3[teeoff]);
$rs[golfclulb_id] = $rs3[golfclulb_id];
$rs[subject] = $rs3[subject];
$rs[club_staff] = $rs3[club_staff];
$rs[fax] = $rs3[fax];
$rs[content] = $rs3[content];
$rs[fax_id_no] = $rs3[fax_id_no];



if(!$rs[subject]) $rs[subject] = $golf_name;
if(!$rs[golfclulb_id]) $rs[golfclulb_id] = $golfclulb_id;
if(!$rs[fax]) $rs[fax] = $fax;


$content_add="";
if(!$nigh) $night=1;  
for($i=0;$i<$night;$i++){
    $j = $i+1;
    $content_add .= "<tr>";
    if($night>1) $content_add .= "<th>${j}일차 TEE OFF</th>";
    else $content_add .= "<th>TEE OFF</th>";
    $content_add .= "<td>".$arr_teeoff[$i]."</td>";
    $content_add .= "</tr>";
}
$rs[people] = nf($rs[people]);
$rs[content] = nl2br($rs[content]);
if($rs[phone]) $phone = substr(rnf($rs[phone]),0,3) ."-". substr(rnf($rs[phone]),3,-4) ."-". substr(rnf($rs[phone]),-4);

$fax_content="<!DOCTYPE html>
<html>
<head>
  <meta charset='utf-8'>
  <title>팩스예약요청서</title>
  <style type='text/css'>
  h1{text-align:center;}
  h2{text-align:left;font-size:1em;}
  .bx_content{widht: 100%;padding:0 50px;}
  .tbl_normal{width: 100%;border-left: 1px solid #ccc;border-top: 1px solid #ccc;border-collapse:collapse;}
  .tbl_normal th,.tbl_normal td{padding: 10px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;}
  .tbl_normal th{background-color:#f4f4f4;width:20%;}
  .tbl_normal td{text-align:left !imporant;padding-left: 10px;}
  .img_top{width:100%;text-align:left}
  .box{border: 1px solid #ccc;padding:5px;}
  .btn{width: 100%;margin-top:30px;text-align:right;}
  .btn span{margin-left:10px;margin-bottom: 50px}
  </style>
</head>
<body>
  <div class='bx_content'>
  <div class='img_top'><img src='http://irumtour.net/new/bkoff/cmp/info.gif'></div>
  <h1>팩스예약요청서</h1>
  <table class='tbl_normal'>
    <tr>
    <th>골프장명</th>
    <td>${rs[subject]}</td>
    </tr>
    <tr>
    <th>수신</th>
    <td>${rs[club_staff]}</td>
    </tr>
    <tr>
    <th>팩스번호</th>
    <td>${rs[fax]}</td>
    </tr>
    <tr>
    <th>발신</th>
    <td>이룸투어 ${staff_info} (담당자 연락처 : ${staff_cell})</td>
    </tr>
    <tr>
    <th>일정</th>
    <td>${rs[d_date]} ~ ${rs[r_date]} (${days})</td>
    </tr>
  </table>
  <br/>
  <h2> - 예약요청내용</h2>
  <table class='tbl_normal'>
    <tr>
    <th>고객명</th>
    <td>${rs[name]}</td>
    </tr>
    <tr>
    <th>고객연락처</th>
    <td>${phone}</td>
    </tr>
    <tr>
    <th>인원</th>
    <td>${rs[people]}명</td>
    </tr>
    ${content_add}
  </table>
  <br/>
  <table class='tbl_normal'>
    <tr>
    <th>요청사항</th>
    <td>${rs[content]}</td>
    </tr>
  </table>
  </div>
</body>
</html>
";

//echo $fax_content;


$filename_fax= "../../public/fax/fax_${user_id}.html";
$fp=fopen($filename_fax, "w");  //파일 쓰기모드로 열기, 파일이 있다면 overwirte
if(!fwrite($fp,$fax_content)){
    fclose($fp);
    echo "error";
    //error("예기치 못한 에러가 발생하였습니다. 관리자에게 문의하여 주시기 바랍니다.");exit;
}
fclose($fp);
//checkVar("filename_fax",$filename_fax);

$rs[club_staff] = str_replace("&","",$rs[club_staff]);
$rs[club_staff] = str_replace("'","",$rs[club_staff]);
$rs[club_staff] = str_replace("\"","",$rs[club_staff]);

$url_fax = "../../api/Fax/SendFAX.php?mode=send";
$url_fax .= "&resv_id_no=".$resv_id_no;//회원아이디
$url_fax .= "&user_id=".$user_id;//회원아이디
$url_fax .= "&Sender=".rnf($OFFICE_FAX);//발신번호
$url_fax .= "&SenderName=㈜이룸플레이스";//발신자이름
$url_fax .= "&rcvnm=".urlencode($rs[club_staff]);//담당자
$url_fax .= "&rcv=".rnf($rs[fax]);//팩스 수신번호

//checkVar("url_fax",$url_fax);

redirect2($url_fax);
?>