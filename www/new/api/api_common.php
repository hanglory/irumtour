<?
session_start();
header("Content-Type: text/html; charset=utf-8");

#### Include
include_once("../../include/config.php");
include_once("../../include/cmp_config.php");
include_once('../../include/fun_basic.php');
include_once("../../lib/AES.php");

#### DB Connent
include_once("../../include/info_dbconn.php");
include_once("../../lib/class.$database.php");

#### DB Connent
include_once("../../include/info_dbconn.php");
include_once("../../lib/class.$database.php");

include_once("../../public/inc/cmp_config.inc");
$dbo = new MiniDB($info);

if($_SESSION['sessLogin']['cp_id']){
    $filename_cp = "../../public/cp/config_cp_". $_SESSION['sessLogin']['cp_id'] .".inc";
    @include_once($filename_cp);
    $UserID = $USERID;
    $CorpNum = $CORPNUM;
}
?>