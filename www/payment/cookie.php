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
include_once($path_root."/info/info_dbconn.php");
include_once($path_root."/www/new/lib/class.$database.php");

$dbo = new MiniDB($info);


if($mode=="order"){

    // foreach ($_POST as $key => $value) {
    //     checkVar($key,$value);
    // }

    $AMOUNT = rnf($AMOUNT);
    $oid = rnf($ORDERNO);

    $https = ($_SERVER['HTTPS']=='on')? "https":"http";
    $DOMAIN = $https . "://" . $_SERVER['HTTP_HOST'];
    $RETURNURL = $DOMAIN."/payment/result.php";

    $sql = "select * from cmp_card where oid=$oid";
    $dbo->query($sql);
    $rs=$dbo->next_record();
    //if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}
    if($rs[oid]){
        $sql="
           update cmp_card set
              subject = '$PRODUCTNAME',
              name = '$BUYERNAME',
              cell = '$BUYERPHONE',
              email = '$BUYEREMAIL',
              price = '$AMOUNT'
           where id_no='$rs[id_no]'
           limit 1
        ";
    }else{
        $reg_date = date('Y/m/d');
        $reg_date2 = date('H:i:s');
        $oid = getUniqNo();
        $sql="
           insert into cmp_card (
              subject,
              name,
              cell,
              email,
              price,
              pg_tid,
              reg_date,
              reg_date2,
              oid
          ) values (
              '$PRODUCTNAME',
              '$BUYERNAME',
              '$BUYERPHONE',
              '$BUYEREMAIL',
              '$AMOUNT',
              '',
              '$reg_date',
              '$reg_date2',
              '$oid'
        )";
    }
    $dbo->query($sql);
    //if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}


    $url ="index.html?oid=${oid}&auto=1";
    Header( "HTTP/1.1 301 Moved Permanently" );
    Header( "Location: $url" );
    exit;

}

?>