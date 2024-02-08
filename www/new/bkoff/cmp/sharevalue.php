<?
session_start();
header("Content-Type: text/html; charset=utf-8");

error_reporting(E_ALL & ~E_STRICT & ~E_NOTICE & ~E_DEPRECATED);
ini_set("display_errors",$display_errors );


while(list($key,$val)=each($_GET)){$$key=$val;}
while(list($key,$val)=each($_POST)){$$key=$val;}

#### Include
include_once('../../include/fun_basic.php');
include_once('../../include/fun_crypt.php');
include_once('../../include/fun_api.php');
include_once("../../include/vars.php");

#### DB Connent
include_once ("../../include/info_dbconn.php");
include_once ("../../lib/class.$database.php");

$dbo = new MiniDB($info);

$display_errors=1;
$login_id = $_SESSION['sessLogin']['id'];




/*토큰생성*/
if($type=="reg" && $login_id){

    $sql = "
            select 
                * 
            from cmp_staff
            where 
                id='$login_id'
                and id<>''
            limit 1
        ";
    $dbo->query($sql);
    $rs=$dbo->next_record();

    $name = $rs[name];
    $cell = $rs[cell1];
    if($rs[cell2]) $cell .= "-".$rs[cell2];
    if($rs[cell3]) $cell .= "-".$rs[cell3];
    $email = $rs[email];


    if(!$name || !$cell || !$email){
        msggo("필수정보(이름,핸드폰번호,이메일주소)가 빠져있습니다. \\n\\n개인정보를 수정하신 후 다시 시도해 주세요.","edit_user.php");
        exit;
    }


    $token = hash("sha256", $login_id.time());
    $url = "https://irumplace.smartbookingplus.com/?param=".$token;
    //checkVar("token",$token);
    //checkVar("url",$url);
    $sql = "
            update cmp_staff set
                token='$token'
            where 
                id='$login_id'
                and id<>''
            limit 1
        ";
    $dbo->query($sql);

    Header( "HTTP/1.1 301 Moved Permanently" );
    Header( "Location: $url" );

    //checkVar(mysql_error(),$sql);
}

if($_GET[param]){

    $proof_ip = "";
    //$proof_ip .= "@14.37.242.84";
    $proof_ip .= "@3.36.136.194";
    $proof_ip .= "@54.180.33.97";
    $proof_ip .= "@121.128.79.129";
    if(!strstr($proof_ip,$_SERVER['REMOTE_ADDR'])){exit;}




    $token = $_GET[param];
    $sql = "
            select 
                * 
            from cmp_staff
            where 
                token='$token'
                and id<>''
            limit 1
        ";
    $dbo->query($sql);
    $rs=$dbo->next_record();
    $user_id = $rs[id];
    $name = $rs[name];
    $cell = $rs[cell1];
    if($rs[cell2]) $cell .= "-".$rs[cell2];
    if($rs[cell3]) $cell .= "-".$rs[cell3];
    $email = $rs[email];

    $data = array(
            'user_id' => $user_id,
            'name' => $name,
            'mobile' => $cell,
            'email' => $email,
        );

    $json = to_han(json_encode($data));

    header('Content-Type: application/json; charset=utf-8');
    echo $json;

}




