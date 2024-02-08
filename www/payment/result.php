<?
session_start();
header("Content-Type: text/html; charset=UTF-8");

$arr = explode("/www",$_SERVER['DOCUMENT_ROOT']);
$path_root =$arr[0];

#### include
include_once($path_root."/www/new/include/config.php");
include_once($path_root."/www/new/include/cmp_config.php");
include_once($path_root."/www/new/include/fun_basic.php");
include_once($path_root."/www/new/include/vars.php");
include_once($path_root."/www/new/public/inc/site.inc");

#### DB Connent
include_once($path_root . "/info/info_dbconn.php");
include_once($path_root."/www/new/lib/class.$database.php");

$dbo = new MiniDB($info);


reset($_POST);


if($_POST[RESULTCODE]=="0000"){

    $pg_info = "결과:".$_POST['RESULTMSG']."<br/>";
    $pg_info .= "금액:".nf($_POST['AMOUNT'])."<br/>";
    $pg_info .= "승인번호:".$_POST['ACCEPTNO']."<br/>";
    $pg_info .= "TID:".$_POST['TID']."<br/>";
    $pg_info .= "TRNO:".$_POST['TRNO']."<br/>";
    $pg_info .= "승인일자:".$_POST['ACCEPTDATE']."<br/>";

    $oid = rnf($_POST['ORDERNO']);
    $price = rnf($_POST['AMOUNT']);
    $TID = $_POST['TID'];

    $sql="
        update cmp_card set
            pg_tid='$TID',
            pg_info='$pg_info',
            price_payed='$price'
        where oid=$oid
		limit 1
    ";
    $dbo->query($sql);
    $url = "result.html?oid=$oid";

    if(mysql_error()){
        checkVar(mysql_error(),$sql);exit;
    }else{
        Header( "HTTP/1.1 301 Moved Permanently" );
        Header( "Location: $url" );
    }
    exit;

}
?>