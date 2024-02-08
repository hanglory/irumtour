<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"핸드폰번호다운로드"); 

$sql = $_SESSION[down_sql];


$sex = $_GET[gender];

if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
$xls_name = "cellnumber_" . date("Ymd").".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/vnd.ms-excel; charset=euc-kr");
header("Content-Disposition: attachment;filename=$xls_name;");
header( "Content-Description: PHP4 Generated Data" );
}
?>

<table border="1">
<tr>
    <td>이름</td>
    <td>핸드폰번호</td>
    <td>담당자</td>
</tr>
<?

$dbo->query($sql);
//if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql);
while($rs=$dbo->next_record()){

if(!$sex){
?>
<tr>
    <td><?=$rs[name]?></td>
    <td style="mso-number-format:'@'"><?=$rs[phone]?></td>
    <td><?=$rs[main_staff]?></td>
</tr>
<?
}

    $filter_sub = "";
    if($sex)  $filter_sub = " and sex='$sex'";
    $sql3 = "select * from cmp_people where code='$rs[code]' and phone<>'' and phone<>'$rs[phone]' and seq<=$rs[people] $filter_sub";
    $dbo3->query($sql3);
    //if(strstr("@211.226.255.74@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql3);}
    while($rs3=$dbo3->next_record()){
?>
<tr>
    <td><?=$rs3[name]?></td>
    <td style="mso-number-format:'@'"><?=$rs3[phone]?></td>
    <td><?=$rs[main_staff]?></td>
</tr>
<?        
    }
}
?>
</table>