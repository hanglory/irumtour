<?
$abs_path = "../new";

#### include
include_once ("$abs_path/include/fun_basic.php");
include_once ("$abs_path/include/fun_bbs.php");
include_once ("$abs_path/include/vars.php");
include_once("$abs_path/lib/thumb.lib.php"); //썸네일 만들기 위해

#### inc
include_once ("$abs_path/public/inc/ez_board_button.inc");
include_once ("$abs_path/public/inc/ez_board_config.inc");

#### DB Connent
include_once ("../new/include/info_dbconn.php");
include_once ("$abs_path/lib/class.$database.php");

$dbo = new MiniDB($info);
$dbo2 = new MiniDB($info);

/*게시판설정*/
$IMG_PATH =""; //이미지 경로

$BID=(strstr($bid,"goods_"))? "goods":$bid;

/*게시판 정보*/
$sql = "select * from ez_bbs_info where bid='$BID' ";
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
$SET_CATEGORY = $rs[category];
$EZ_BOARD_CONFIG_GALLERY_1ROWS = 1;

if($BID=="goods") $EZ_BOARD_CONFIG_WIDTH="765";
?>

<script type="text/javascript">
<!--
function category(str){
	var fm = document.fmSearch;
	fm.category.value = str;
	fm.submit();
}
//-->
</script>
<style type="text/css">
#ez_conents_wrap{ width:100%; }
<?
if($BG_LIST){
	$bg_list = "/new/public/ez_board/bg/$BG_LIST";
	$bg_list_info = GetImageSize($bg_list);
?>
#ez_conents_wrap #tbl_bbslist {background-image:url("<?=$bg_list?>");background-repeat:repeat-x;}
#ez_conents_wrap #tbl_bbslist th {height:<?=$bg_list_info[1]-11?>px !important;}
<?}?>
</style>