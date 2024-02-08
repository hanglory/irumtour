<?
session_start();

header("Content-Type: text/html; charset=UTF-8");
header('Cache-Control: max-age=86400');

//header("Cache-Control: no cache");
//session_cache_limiter("private_no_expire");    
@header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
@header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
@header("Cache-Control: no-store, no-cache, must-revalidate");
@header("Cache-Control: post-check=0, pre-check=0", false);
@header("Pragma: no-cache");
$ver=strstr($_SERVER['HTTP_USER_AGENT'],"Android ");
$android_ver=(substr($ver,8,1));
$ver=strstr($_SERVER['HTTP_USER_AGENT'],"Chrome/");
$chrome_ver=(substr($ver,7,1));
//if($android_ver>=8 || $chrome_ver>=8){
    if(!function_exists('session_start_samesite')) {
        function session_start_samesite($options = array())
        {
            $res = @session_start($options);
            $headers = headers_list();
            foreach ($headers as $header) {
                if (!preg_match('~^Set-Cookie: PHPSESSID=~', $header)) continue;
                $header = preg_replace('~; secure(; HttpOnly)?$~', '', $header) . '; secure; SameSite=None';
                header($header, false);
                break;
            }
            return $res;
        }
    }
    session_start_samesite();
//}
header('P3P: CP="NOI CURa ADMa DEVa TAIa OUR DELa BUS IND PHY ONL UNI COM NAV INT DEM PRE"');
ini_set("session.cache_expire", 86400);
ini_set("session.gc_maxlifetime", 86400);
ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);


/*PUT DELETE Method 제한*/
if($_SERVER['REQUEST_METHOD']=="PUT" || $_SERVER['REQUEST_METHOD']=="DELETE"){exit;}



$abs_path_top = str_replace("/www","",$_SERVER["DOCUMENT_ROOT"]);
$abs_pass = ".";

#### include
include_once ($abs_path_top."/www/new/include/config.php");
include_once ($abs_path_top."/www/renew/include/fun_basic.php");
include_once ($abs_path_top."/www/renew/include/fun_login.php");
include_once ($abs_path_top."/www/renew/include/fun_bbs.php");
include_once ($abs_path_top."/www/renew/include/fun_tour.php");
include_once ($abs_path_top."/www/renew/include/vars.php");
include_once ($abs_path_top."/www/new/public/inc/site.inc");
include_once ($abs_path_top."/www/new/lib/thumb.lib.php");
include_once ($abs_path_top."/www/new/password-hashing-master/PasswordHash.php");
$COMPANY_NAME = $SITE_NAME;



#### DB Connent
include_once ($abs_path_top."/info/info_dbconn.php");
include_once ($abs_path_top."/www/new/lib/class.$database.php");

$dbo = new MiniDB($info);
$dbo2 = new MiniDB($info);
$dbo3 = new MiniDB($info);
$dbo4 = new MiniDB($info);
$dbo9 = new MiniDB($info);
$dbo_ = new MiniDB($info);


$tid=rnf($tid);
$oid=rnf($oid);
$code1=rnf($code1);
$code2=rnf($code2);
$code3=rnf($code3);
$page=rnf($page);
$keyword=secu($keyword);
$tour_date=secu($tour_date);


$SELF= basename($_SERVER["SCRIPT_NAME"]);
$SESSID = session_id();



/*index query*/
if(SELF=="index.html" || strstr(SELF,"inc_thema_new")){
include_once($abs_path_top."/www/renew/script/inc_index_query.php");
}



/*CID check s*/
$domain = str_replace("www.","",strtolower($_SERVER["SERVER_NAME"]));
if(strstr($_SERVER["HTTP_HOST"],"irumplace.com")) $domain = "irumplace.com";
elseif(strstr($_SERVER["HTTP_HOST"],"irumtour.net")) $domain = "irumtour.net";


$chk_cp=str_replace($domain,"",$_SERVER["HTTP_HOST"]);
$chk_cp=str_replace(".","",$chk_cp);
if($chk_cp && strtolower($chk_cp)!="www"){
	$_SESSION[CID]=$chk_cp;
    $SITE_URL = "";
}


$https = ($_SERVER['HTTPS']=='on')? "https":"http";
$DOMAIN = $https . "://" . strtolower($_SERVER['HTTP_HOST']);
$CPAGE = $DOMAIN  . $_SERVER[REQUEST_URI];
if(strstr($DOMAIN,"zeustour.kr")){
    $_SESSION[CID]="zeusgolftour";  
    $domain = "zeustour.kr";
}
elseif(strstr($DOMAIN,"golfwithpenguin.com")){
    $_SESSION[CID]="polgolf";  
    $domain = "golfwithpenguin.com";
}
elseif(strstr($DOMAIN,"kdctour.co.kr")){
    $_SESSION[CID]="sunshine";  
    $domain = "kdctour.co.kr";
    if(!strstr($DOMAIN,"https")){
      $url = "https://kdctour.co.kr".$_SERVER[REQUEST_URI];
      Header( "HTTP/1.1 301 Moved Permanently" );
      Header( "Location: $url" );
      exit;
    }
}
if($_SESSION[CID]=="zeusgolftour" && !strstr($DOMAIN,"zeustour.kr")){
    $url = "http://zeustour.kr".$_SERVER[REQUEST_URI];
    Header( "HTTP/1.1 301 Moved Permanently" );
    Header( "Location: $url" );
    exit;
}
if($_GET[fcid]) $_SESSION[CID]=secu($_GET[fcid]);


$CID=$_SESSION[CID];
$sql = "select * from cmp_cp where id='$CID'";
$dbo->query($sql);
$rs=$dbo->next_record();	
if($rs[id]){
    $_SESSION[CP_COMPANY] = $rs[company];
    $CP_COMPANY=$rs[company];
    $CP_COMPANY2=$rs[company2];
    $CP_ADDRESS=$rs[address] . " " . $rs[address2];
    $CP_PHONE=$rs[phone];
    $CP_PHONE_STAFF=$rs[staff_phone];
    $CP_FAX_NAME=$rs[fax_name];
    $CP_FAX=$rs[fax];
    $CP_KAKAO=$rs[kakao];
    $CP_NAVER=$rs[naver_form];
    $CP_BLOG=$rs[blog];
    $CP_EMAIL=$rs[email];
    $CP_BIZ_NO=$rs[biz_no];
    $CP_PARTNER_TYPE=$rs[partner_type];
    $CP_LOGO=$rs[filename];
    $CP_ICO=$rs[filename7];
    $CP_TOUR_LICENCE=$rs[tour_licence];
    $CP_STOCK_LICENCE=$rs[stock_licence];
    $CP_SALE_LICENCE=$rs[sale_licence];
    $CP_CEO=$rs[ceo];
    $CP_STAFF=$rs[staff];
    $CP_BANK=$rs[bank];
    $CP_BANK_ACCOUNT=$rs[bank_account];
    $CP_BANK_OWNER=$rs[bank_owner];
    $CP_SNS[blog]=$rs[blog];
    $CP_SNS[facebook]=$rs[facebook];
    $CP_SNS[instagram]=$rs[instagram];
    $CP_SNS[youtube]=$rs[youtube];
    $CP_BIT_CLOSE_DOMESTIC=$rs[bit_close_domestic];
    $CP_INDEX=($rs[index_type])?$rs[index_type]:1;
    $SITE_NAME = str_replace("(주)","",$CP_COMPANY);

    //간편로그인
    $Client_ID="";
    $Client_Secret="";
    $API_KAKAO_ID="";
    if($rs[cp_naver_client_id]) $Client_ID=$rs[cp_naver_client_id];
    if($rs[cp_naver_client_secret]) $Client_Secret =$rs[cp_naver_client_secret];
    if($rs[cp_kakao_appkey]) $API_KAKAO_ID=$rs[cp_kakao_appkey];

}
$SITE_NAME2 .= $SITE_NAME;
$SITE_NAME_ADD =has_batchim($SITE_NAME)?'와':'과';
$SITE_NAME_ADD2 =has_batchim($SITE_NAME)?'를':'을';
/*CID check f*/


/*https://irum...으로 통일*/
if(!$CID && ($_SERVER['HTTPS']!='on' || strstr($DOMAIN,"www."))){
    $url = "https://".str_replace("www.","",basename($DOMAIN)).$_SERVER[REQUEST_URI];
    redirect2($url);
    exit;
}


$MOBILE=0;
$arr_browser = array ("iPhone","iPod","IEMobile","Mobile","lgtelecom","PPC");
for($indexi = 0 ; $indexi < count($arr_browser) ; $indexi++) {
    if(strpos($_SERVER['HTTP_USER_AGENT'],$arr_browser[$indexi]) == true){
        $MOBILE=1;
    }
}




// if(strstr("@211.226.255.74@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
// /*네이버 카카오 로그인을 위한 조치s*/
// $https = ($_SERVER['HTTPS']=='on')? "https":"http";
// $DOMAIN = $https . "://" . $_SERVER['HTTP_HOST'];
// $CPAGE = $DOMAIN  . $_SERVER[REQUEST_URI];
// $arr_domain = explode(".",strstr($DOMAIN,"//"));
// $subdomain = str_replace("//","",$arr_domain[0]);
// $irum_abs_files = "@join.html@mem_login.html@";
// if($_GET[fcid]) $_SESSION[CID]=$_GET[fcid];
// if(strstr($irum_abs_files,SELF) && !strstr("@irumtour@www@",$subdomain)){
//     $go_url = "https://irumtour.net/renew/".SELF."?fcid=".$_SESSION[CID]."&".$_SERVER["QUERY_STRING"];
//     //checkVar("go_url1",$go_url);
//     redirect2($go_url);
//     exit;
// }
// elseif(!strstr($irum_abs_files,SELF) && strstr("@irumtour@www@",$subdomain) && $_SESSION[CID]){
//     $go_url = "http://".$_SESSION[CID].".irumtour.net/renew/".SELF."?".$_SERVER["QUERY_STRING"];
//     //checkVar("go_url2",$go_url);
//     redirect2($go_url);
//     exit;    
// }
// /*네이버 카카오 로그인을 위한 조치e*/
// }





/*
$arr_browser = array ("iPhone","iPod","IEMobile","Mobile","lgtelecom","PPC");
for($indexi = 0 ; $indexi < count($arr_browser) ; $indexi++) {
	if(strpos($_SERVER['HTTP_USER_AGENT'],$arr_browser[$indexi]) == true){

		$url = "/m/index.html";

		if(strstr($REQUEST_URI,"itemview")){
			$url = "/m/" . strstr($REQUEST_URI,"itemview");
		}

		Header( "HTTP/1.1 301 Moved Permanently" );
		Header( "Location: $url" );
		exit;

	}
}
*/


$tid=rnf($tid);
$oid=rnf($oid);
$code1=rnf($code1);
$code2=rnf($code2);
$code3=rnf($code3);
$page=rnf($page);
$keyword=secu($keyword);
$tour_date=secu($tour_date);

$user_id = $_SESSION["name"];
$user_phone = rnf($_SESSION["phone"]);

/*공격 IP 차단*/
$user_ip = $_SERVER["REMOTE_ADDR"];
$sql = "
	select 
	id_no,
	(select count(id_no) from ez_bkoff_login where ip='$user_ip' and bit='T') as bit_staff 
	from ez_castle_ip where ip='$user_ip' and cnt>5
";
$dbo->query($sql);
$rs=$dbo->next_record();
if($rs[id_no] && !$rs[bit_staff]){redirect2("/renew/castle-php/error2.html");exit;}
?>
