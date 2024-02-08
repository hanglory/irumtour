<?
session_start();
exit;

#### 페이지의 캐쉬읽기를 금지
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");


#### Include
include_once ("../include/config.php");
include_once ("../include/fun_basic.php");


/*네이버 카카오 로그인을 위한 조치s*/
$https = ($_SERVER['HTTPS']=='on')? "https":"http";
$DOMAIN = $https . "://" . $_SERVER['HTTP_HOST'];
$CPAGE = $DOMAIN  . $_SERVER[REQUEST_URI];
$arr_domain = explode(".",strstr($DOMAIN,"//"));
$subdomain = str_replace("//","",$arr_domain[0]);

$irum_abs_files = "@join.html@mem_login.html@";
if(strstr($irum_abs_files,SELF)){
    if($_GET[fcid]){
        $go_url = "https://irumtour.net/renew/".SELF."?fcid=${fcid}&".$_SERVER["QUERY_STRING"];
        redirect2($go_url);
        exit;
    }
}else{
    $go_url = "http://${subdomain}.irumtour.net/renew/script/partner.php?".$_SERVER["QUERY_STRING"];
    redirect2($go_url);
    exit;    
}
/*네이버 카카오 로그인을 위한 조치e*/

$cid = ($subdomain!="www" && !strstr($subdomain,"irumtour"))? $subdomain : "";

$go_url = "https://irumtour.net/renew/script/partner.php?fcid=${fcid}";

checkVar("file",SELF);
checkVar("domain",$DOMAIN);
checkVar("subdomain",$subdomain);
checkVar("cid",$cid);
checkVar("QUERY_STRING",$_SERVER["QUERY_STRING"]);






?>