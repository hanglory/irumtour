<?
session_start();

ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);

header("Content-Type: text/html; charset=UTF-8");



#### include
include_once ("../include/config.php");
include_once ("../include/fun_basic.php");
include_once ("../include/fun_login.php");
include_once ("../include/vars.php");
include_once ("../../new/public/inc/site.inc");
include_once ("../../new/password-hashing-master/PasswordHash.php");
include_once ("../../new/SMS/xmlrpc.inc.php");
include_once ("../../new/SMS/class.EmmaSMS.php");


#### DB Connent
include_once ("../../../info/info_dbconn.php");
include_once ("../lib/class.$database.php");

$dbo = new MiniDB($info);


// 네이버 로그인 콜백
$client_id = $Client_ID;
$client_secret = $Client_Secret;
$CALLBACK_URL = $DOMAIN."/renew/script/naver_login.php";

// checkVar("path_inc",$path_inc);
// checkVar("asp_id",$asp_id);
// checkVar("API_NAVER_ID",$API_NAVER_ID);
// checkVar("API_NAVER_SECRET",$API_NAVER_SECRET);
// checkVar("CALLBACK_URL",$CALLBACK_URL);


$code = $_GET["code"];
$state = $_GET["state"];
$redirectURI = urlencode($CALLBACK_URL);
$url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=".$redirectURI."&code=".$code."&state=".$state;
$is_post = false;
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, $is_post);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$headers = array();
$response = curl_exec ($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//checkVar("status_code",$status_code);
curl_close ($ch);
if($status_code == 200) {
    $json = json_decode($response);
    //echo "<hr>";
    while(list($key,$val)=@each($json)){
        //echo  "@${key}@ : $val <br/>";
        if($key=="access_token") $access_token  = $val;
    }
    //checkVar("access_token",$access_token);

    //회원 프로필 조회 API 명세
    $token = $access_token;
    $header = "Bearer ".$token; // Bearer 다음에 공백 추가
    $url = "https://openapi.naver.com/v1/nid/me";
    $is_post = false;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, $is_post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = array();
    $headers[] = "Authorization: ".$header;
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec ($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //echo "status_code:".$status_code."<br>";
    curl_close ($ch);
    if($status_code == 200) {

        $json = json_decode($response);
        while(list($key,$val)=each($json)){
            //echo  "${key} : $val <br/>";
            if($key=="response"){
                $access_token  = $val;
                while(list($key2,$val2)=each($val)){
                    //echo  "${key2} : $val2 <br/>";
                    if($key2=="id") $id_ext  = $val2;
                    $data_login[$key2] = $val2; 
                }
            }
        }
        $data_login = serialize($data_login);

        // checkVar("id_ext",$id_ext);
        // checkVar("data_login",$data_login);

        $_SESSION['EXT_LOGIN']['ID'] = $id_ext;
        $_SESSION['EXT_LOGIN']['DATA'] = $data_login;
        $_SESSION['EXT_LOGIN']['ASSORT'] = "naver";

        $idpw ="naver.".$id_ext;      
        $sql = "select * from ez_member where id='$idpw' and id_ext='$id_ext' ";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        if($rs[id]){
            login($idpw,$id_ext,$ok_url);
        }else{
            redirect2("/renew/join.html");
        }
        exit;

    } else {
        if(strstr("@1112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){echo $response;exit;}
        else{
            msggo("처리되지 않았습니다.",$DOMAIN."/renew/mem_login.html");
        }
    }

} else {
        if(strstr("@1112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){echo $response;exit;}
        else{
            msggo("처리되지 않았습니다.",$DOMAIN."/renew/mem_login.html");
        }
}
?>