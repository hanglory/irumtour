<?
$abs_pass = ".";

#### include
include_once ("$abs_pass/include/fun_basic.php");
include_once ("$abs_pass/include/fun_bbs.php");
include_once ("$abs_pass/include/vars.php");
include_once("$abs_pass/lib/thumb.lib.php"); //����� ����� ����

#### inc
include_once ("$abs_pass/public/inc/ez_board_button.inc");
include_once ("$abs_pass/public/inc/ez_board_config.inc");

#### DB Connent
include_once ("$abs_pass/include/info_dbconn.php");
include_once ("$abs_pass/lib/class.$database.php");

$dbo = new MiniDB($info);
$dbo2 = new MiniDB($info);


$bmode = secu($bmode);
$id_no = rnf(secu($id_no));
$page = rnf(secu($page));
$doc_no = rnf(secu($doc_no));
$flag = secu($flag);
$keyword = secu($keyword);
$target = secu($target);

/*�Խ��Ǽ���*/
$IMG_PATH =""; //�̹��� ���

/*�Խ��� ����*/
$sql = "select * from ez_bbs_info where bid='$bid' ";
$dbo->query($sql);
$rs=$dbo->next_record();
$SET_SUBJECT = $rs[subject];
$SET_ASSORT = $rs[assort];
$SET_POWER_READ = $rs[power_read];
$SET_POWER_WRITE = $rs[power_write];
$SET_POWER_REPLY = $rs[power_comment];
$SET_MB = $rs[mb];
$SET_FILE = $rs[file];
$SET_MEMO = $rs[memo];
$SET_SECRET = $rs[secret];
$SET_TOP_CODE = $rs[top_code];
$SET_BOTTOM_CODE = $rs[bottom_code];
$SET_GRADE = ($_SESSION[sessMember][grade])?$_SESSION[sessMember][grade]:0;
?>

<style type="text/css">
#ez_conents_wrap{ width:<?=$EZ_BOARD_CONFIG_WIDTH?>px; }
<?
if($BG_LIST){
	$bg_list = "public/ez_board/bg/$BG_LIST";
	$bg_list_info = GetImageSize($bg_list);
?>
#ez_conents_wrap #tbl_bbslist {background-image:url("<?=$bg_list?>");background-repeat:repeat-x;}
#ez_conents_wrap #tbl_bbslist th {height:<?=$bg_list_info[1]?>px;}
<?}?>
</style>