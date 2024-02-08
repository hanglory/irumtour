<?
session_start();
header("Content-Type: text/html; charset=utf-8");


while(list($key,$val)=each($_GET)){$$key=$val;}
while(list($key,$val)=each($_POST)){$$key=$val;}

#### Include
include_once('../../include/fun_basic.php');
include_once('../../include/fun_crypt.php');
include_once('../../include/fun_bbs.php');
include_once('../../include/fun_tour.php');
include_once('../../include/fun_cmp.php');
include_once('../include/proof.php');
include_once("../../include/config.php");
include_once("../../include/cmp_config.php");
include_once("../../password-hashing-master/PasswordHash.php");

include_once('../../include/fun_api.php');

include_once("../../include/vars.php");
include_once("../../lib/thumb.lib.php");
include_once("../../lib/AES.php");


#### inc
include_once('../../public/inc/ez_board_config.inc');
include_once('../../public/inc/site.inc');
include_once('../../public/inc/cmp_config.inc');


#### DB Connent
include_once ("../../include/info_dbconn.php");
include_once ("../../lib/class.$database.php");

$dbo = new MiniDB($info);
$dbo2 = new MiniDB($info);
$dbo3 = new MiniDB($info);
$dbo4 = new MiniDB($info);
$dbo_ = new MiniDB($info);


$debug=0;
if(strstr("@121.134.156.27@125.191.193.204@","@".$_SERVER["REMOTE_ADDR"]."@")) $debug=1;


/*sms*/
$sms_id = "irumplace";
$sms_passwd = "00615cmy!";


$https = ($_SERVER['HTTPS']=='on')? "https":"http";
$DOMAIN = $https . "://" . $_SERVER['HTTP_HOST'];
$CPAGE = $DOMAIN  . $_SERVER[REQUEST_URI];


$start_date =($start_date)?$start_date : date("Y/m/d");
$end_date =($end_date)?$end_date : date("Y/m/d");

$maxFileSize = intval(substr(ini_get(upload_max_filesize),0,-1));
$user_id=$_SESSION["sessLogin"]["id"];
$user_name=$_SESSION["sessLogin"]["name"];
$cp_id=$_SESSION["sessLogin"]["cp_id"]; //파트너 아이디


$sql_ = "select * from cmp_staff where id='".$_SESSION["sessLogin"]["id"]."'";
$dbo_->query($sql_);
$rs_=$dbo_->next_record();
$LOGIN_STAFF_TYPE = $rs_[staff_type];
$_SESSION["sessLogin"]["staff_type"] = $rs_[staff_type];
$_SESSION["sessLogin"]["proof"] = $rs_[power];
$_SESSION["sessLogin"]["proof_erp"] = $rs_[power_erp];
$CP_ID=(strstr($rs_[staff_type],"partner") && $rs_[staff_type]!="partner_i")? $rs_[cp_id] : "";//독립형 파트너일 때만 갖는 값(reservation table 등의 cp_id 값에 해당) 

$_SESSION[sessLogin][staff_type]=$rs_[staff_type];
$staff_mode = ($_SESSION[sessLogin][staff_type]=="staff")? 1:0;




/*권한체크s*/
include($_SERVER['DOCUMENT_ROOT']."/new/bkoff/cmp/inc_chk_power.php");    
/*권한체크f*/



/*파트너 필터링을 위한 쿼리s*/
$FILTER_PARTNER_QUERY_CPID=" and a.cp_id='$CP_ID' ";
$FILTER_PARTNER_QUERY_STAFFTYPE=" and a.staff_type in ('partner_a','partner_g') ";
/*파트너 필터링을 위한 쿼리f*/


/*파트너 필터링을 위한 쿼리2s*/
$FILTER_PARTNER_QUERY="";
if(strstr("partner_i",$_SESSION["sessLogin"]["staff_type"])){
    //partner_i는 자신의 데이터만
    $FILTER_PARTNER_QUERY = " and main_staff like '%($user_id)'";   
}
elseif(strstr("partner_a,partner_g",$_SESSION["sessLogin"]["staff_type"])){

    $filename= "../../public/cp/cmp_config_".$CP_ID.".inc";
    @include($filename);

    //독립형파트너(partner_a,partner_g)는 자신의 cp_id 데이터만
    $FILTER_PARTNER_QUERY = " and cp_id ='$cp_id'";

    //독립형 상품 제한
    $FILTER_PARTNER_GOLF_QUERY = "
        and bit_hide_partner=0
        and (
            cp_id ='zeusgolftour'
            or
            use_partners='$CP_ID'
            or
            (cp_id='' and use_partners='')
        )
    ";

    $sql_ = "select * from cmp_cp where id='$rs_[cp_id]' and id<>''";
    $dbo_->query($sql_);
    $rs_=$dbo_->next_record();
    if($rs_[sms_id] && $rs_[sms_passwd]){
        $sms_id = $rs_[sms_id];
        $sms_passwd = $rs_[sms_passwd];
    }
}

/*파트너 필터링을 위한 쿼리2f*/













if($user_id=="tester" || $user_id=="test"){

    $hide_memu_tmp.="@list_paper11.php";
    $hide_memu_tmp.="@list_paper9.php";


    if(strstr($hide_memu_tmp,SELF)){
      back();
    }
}
?>