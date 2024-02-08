<?
session_start();

ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);

header("Content-Type: text/html; charset=EUC-KR");



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

$https = ($_SERVER['HTTPS']=='on')? "https":"http";
$DOMAIN = $https . "://" . $_SERVER['HTTP_HOST'];


// 카카오 로그인 콜백
$client_id = $API_KAKAO_ID;
$CALLBACK_URL = $DOMAIN."/renew/script/kakao_login.php";


$code = $_GET["code"];
$callbacURI = urlencode($CALLBACK_URL); // Call Back URL
//토큰
$url = "https://kauth.kakao.com/oauth/token?grant_type=authorization_code&client_id=".$client_id."&redirect_uri=".$callbacURI."&code=".$code;


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST,false); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = array();
$loginResponse = curl_exec ($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close ($ch);

//사용자 정보 요청
$accessToken= json_decode($loginResponse)->access_token;
$header = "Bearer ".$accessToken;
$getProfileUrl = "https://kapi.kakao.com/v2/user/me";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $getProfileUrl);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = array();
$headers[] = "Authorization: ".$header;
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$profileResponse = curl_exec ($ch);
$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE); 
curl_close ($ch);


$profileResponse = json_decode($profileResponse);
$id_ext = $profileResponse->id;
$data_login[email] = $profileResponse->properties->email;
$data_login[gender] = $profileResponse->properties->gender;
$data_login[birthday] = $profileResponse->properties->birthday;
// $age_range = $profileResponse->properties->age_range;
// $nickname = $profileResponse->properties->nickname
if($id_ext){
    $_SESSION['EXT_LOGIN']['ID'] = $id_ext;
    $_SESSION['EXT_LOGIN']['DATA'] = $data_login;
    $_SESSION['EXT_LOGIN']['ASSORT'] = "kakao"; 

    $idpw ="kakao.".$id_ext;      
    $sql = "select * from ez_member where id='$idpw' and id_ext='$id_ext' ";
    $dbo->query($sql);
    $rs=$dbo->next_record();
    if($rs[id]){
        login($idpw,$id_ext,$ok_url);
    }else{
        redirect2("/renew/join.html");
    }
    exit;

}

?>