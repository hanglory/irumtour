<?
session_start();


#### 페이지의 캐쉬읽기를 금지
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");


#### Include
include_once ("../include/config.php");
include_once ("../include/fun_basic.php");
include_once ("../include/fun_login.php");
include_once ("../../new/password-hashing-master/PasswordHash.php");


#### DB Connent
include_once ("../../../info/info_dbconn.php");
include_once ("../lib/class.$database.php");

$dbo = new MiniDB($info);



#### 로그인/로그아웃 처리
if($mode == "login"){ //login

    $id=secu($id);
    $pwd = secu($pwd);

    if(!$id || !$pwd){
        error('해당하는 회원자료가 없습니다.');exit;
    }

    login($id,$pwd,$ok_url);

}else{ //logout

    logout();

}
?>