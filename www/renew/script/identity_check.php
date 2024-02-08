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
include_once ("../include/vars.php");
include_once ("../../new/public/inc/site.inc");
include_once ("../../new/password-hashing-master/PasswordHash.php");
include_once ("../../new/SMS/xmlrpc.inc.php");
include_once ("../../new/SMS/class.EmmaSMS.php");
$sms = new EmmaSMS();
$sms->login($SMS_ID, $SMS_PASSWD);


#### DB Connent
include_once ("../../../info/info_dbconn.php");
include_once ("../lib/class.$database.php");

$dbo = new MiniDB($info);


$table = "ez_member";

#### 아이디,비밀번호 찾기
$id = secu(trim($id));
$name = secu(trim($name));
$cell = rnf(secu(trim($cell)));

$filter = ($name)? " and name='$name' ":"";
$filter .= ($id)? " and id='$id' ":"";

$sql = "select * from $table where replace(cell,'-','')='$cell' $filter";
list($rows)=$dbo->query($sql);

//if($REMOTE_ADDR=="106.246.54.18"){checkVar($rows,$sql);exit;}


$type="sms";//sms or email


if($rows){
    $rs = $dbo->next_record();
    $cell = $rs[cell];

    if($rs[id_ext]){
        msggo(strtoupper($rs[assort_ext])." 간편 로그인을 이용하세요.","../mem_login.html");
    }


    if($id){

        $new_pwd = substr(time(),-6);
        $pwd_db = create_hash($new_pwd);
        $sql = "update $table set pwd='$pwd_db' where id_no=$rs[id_no] limit 1";
        $dbo->query($sql);

        if($type=="sms"){
            $sms_to = rnf($rs[cell]);

            $message = "[${SITE_NAME}] 요청하신 임시 비밀번호는 ${new_pwd} 입니다.";
            $ret = $sms->send($sms_to, $SMS_FROM, $message, $sms_date, $sms_type);            
            msggo("문자를 발송해 드렸습니다.","../mem_login.html");
            exit;
        }

        //$pwd = base64_encode($new_pwd);
        //redirect2("mail_out.php?mode=lost&id=${rs[id]}&name=${rs[name]}&pwd=${pwd}&email=${rs[email]}");

    }else{


        if($type=="sms"){
            $sms_to = rnf($rs[cell]);

            $message = "[${SITE_NAME}] 요청하신 회원 아이디는 ${rs[id]} 입니다.";
            $ret = $sms->send($sms_to, $SMS_FROM, $message, $sms_date, $sms_type);            
            msggo("문자를 발송해 드렸습니다.","../mem_login.html");
            exit;
        }

        //redirect2("mail_out.php?mode=lost&id=${rs[id]}&name=${rs[name]}&pwd=${pwd}&email=${rs[email]}&bit=1");

    }

}else{
    //checkVar("",$sql);exit;
    error('죄송합니다. 해당하는 회원 정보가 없습니다.\\n\\n다시한번 확인 하여 주시기 바랍니다.');
}
?>