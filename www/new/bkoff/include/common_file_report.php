<?
session_start();

while(list($key,$val)=each($_GET)){$$key=$val;}
while(list($key,$val)=each($_POST)){$$key=$val;}

#### Include
include_once('../../include/fun_basic.php');
include_once('../../include/fun_tour.php');
include_once('../../include/fun_bbs.php');
include_once('../../include/fun_cmp.php');
include_once("../../include/config.php");
include_once("../../include/cmp_config.php");
include_once("../../include/vars.php");
include_once("../../lib/thumb.lib.php");
include "../../lib/AES.php";
include_once('../../public/inc/cmp_config.inc');


#### DB Connent
include_once ("../../include/info_dbconn.php");
include_once ("../../lib/class.$database.php");

$dbo = new MiniDB($info);
$dbo2 = new MiniDB($info);
$dbo3 = new MiniDB($info);
$dbo4 = new MiniDB($info);
$dbo_ = new MiniDB($info);



/*권한체크s*/
if(SELF=="form05.html"){
    $bit_close_power = 1;
    include($_SERVER['DOCUMENT_ROOT']."/new/bkoff/cmp/inc_chk_power.php");
}
/*권한체크f*/




$code = str_replace("{p}","+",$code);

$HTTPS=($_SERVER["HTTPS"]=="on")? "https://":"http://";
$DOMAIN = $HTTPS.$_SERVER["HTTP_HOST"];	
$CPAGE = $DOMAIN  . $_SERVER[REQUEST_URI];


$staff_header="";
$user_id = $_SESSION["sessLogin"]["id"];
$sql_ = "
    select 
        a.*,
        (select filename3 from cmp_cp where id=a.cp_id) as partner_file 
    from cmp_staff as a
    where 
        id='$user_id' and id<>''
    ";
$dbo_->query($sql_);
$rs_=$dbo_->next_record();
if($rs_[filename1])	$staff_header = $DOMAIN."/new/public/cmp_files/".$rs_[filename1];
elseif(!$rs_[filename1] && $rs_[partner_file]) $staff_header = $DOMAIN."/new/public/partner/".$rs_[partner_file];


$LOGIN_STAFF_TYPE = $rs_[staff_type];
$_SESSION["sessLogin"]["staff_type"] = $rs_[staff_type];
$_SESSION["sessLogin"]["proof"] = $rs_[power];
$_SESSION["sessLogin"]["proof_erp"] = $rs_[power_erp];
$CP_ID=(strstr($rs_[staff_type],"partner") && $rs_[staff_type]!="partner_i")? $rs_[cp_id] : "";//독립형 파트너일 때만 갖는 값(reservation table 등의 cp_id 값에 해당) 

$_SESSION[sessLogin][staff_type]=$rs_[staff_type];
$staff_mode = ($_SESSION[sessLogin][staff_type]=="staff")? 1:0;



$arr_browser = array ("iPhone","iPod","IEMobile","Mobile","lgtelecom","PPC");
for($indexi = 0 ; $indexi < count($arr_browser) ; $indexi++) {
  if(strpos($_SERVER['HTTP_USER_AGENT'],$arr_browser[$indexi]) == true){
    $MOBILE=1;
  }
}

$arr_=explode("/bkoff",$_SERVER["SCRIPT_NAME"]);
$caurl=$DOMAIN.$arr_[0];
//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27") checkVar($arr_[0],$caurl);


/*form 링크의 경우*/
if(strstr(SELF,"form") && $code2 && !$CP_ID){
  $code2 = str_replace(" ","+",$code2);
  $CP_ID = decrypt($code2,$SALT);
}

?>