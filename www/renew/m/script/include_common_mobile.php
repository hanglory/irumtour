<?
exit;
session_start();

ini_set('session.bug_compat_warn', 0);
ini_set('session.bug_compat_42', 0);

header("Content-Type: text/html; charset=EUC-KR");


$abs_path = "../new";

#### include
include_once ("$abs_path/include/config.php");
include_once ("$abs_path/include/fun_basic.php");
include_once ("$abs_path/include/fun_bbs.php");
include_once ("$abs_path/include/fun_order.php");
include_once ("$abs_path/include/fun_tour.php");
include_once ("$abs_path/include/vars.php");
include_once ("$abs_path/lib/thumb.lib.php");
include_once ("$abs_path/public/inc/site.inc");


#### DB Connent
include_once ("$abs_path/include/info_dbconn.php");
include_once ("$abs_path/lib/class.$database.php");

$dbo = new MiniDB($info);
$dbo2 = new MiniDB($info);
$dbo3 = new MiniDB($info);
$dbo9 = new MiniDB($info);
$dbo_ = new MiniDB($info);

$SELF= basename($_SERVER["SCRIPT_NAME"]);
$SESSID = session_id();

$NOTCTG = " and id_no not in (23,24,25,26,27,37)";

if(!$_SESSION["sessMember"]["id"]) $sessMember[id] = "";
?>
