<?
include_once("common.php");

bbs_counter($id_no);

if($mode=="save"){

	$reg_date = time();
	$id =$_SESSION["sessMember"]["id"];
	$name_first_letter = strtoupper(substr($name,0,1));

	if(ereg("(^[A-Z]$)",$name_first_letter)){
		//error("이름에 영문은 입력하실 수 없습니다.");exit;
	}

	$sql="
	   insert into ez_bbs_comment (
		  doc_no,
		  name,
		  pwd,
		  comment,
		  reg_date,
		  id
	  ) values (
		  '$doc_no',
		  '$name',
		  password('$pwd'),
		  '$comment',
		  '$reg_date',
		  '$id'
	)";

	if($dbo->query($sql)){
		bbs_counter_comment($id_no);
	}

	redirect2("?bmode=read&bid=$bid&id_no=$doc_no&u=1");
	exit;

}


if(!$u){
	$sql = "update ez_bbs set cnt=cnt+1 where id_no=$id_no limit 1";
	$dbo->query($sql);
}

$sql = "select * from ez_bbs where id_no=$id_no";
$dbo->query($sql);
$rs=$dbo->next_record();


if($SET_POWER_READ > $SET_GRADE){
	error("권한이 없습니다.");exit;
}


if(!$rs[id_no]){error("삭제된 게시물입니다.");}
?>

<link rel="stylesheet" type="text/css" href="./script_bbs/css/ez_board.css" />
<link rel="stylesheet" type="text/css" href="./script_bbs/css/ez_default.css" />
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="./script_bbs/css/ez_board_ie.css" />
<![endif]-->

<script type="text/javascript" src="./include/function.js"></script>
<script type="text/javascript" src="./include/form_check.js"></script>
<script language="javascript">
function checkForm(fm){

	if(check_blank(fm.name,'이름을',0)=='wrong'){return false}
	<?if(!$_SESSION[sessMember][id]){?>
	if(check_blank(fm.pwd,'비밀번호를',0)=='wrong'){return false}
	<?}?>
	if(check_blank(fm.comment,'내용을',0)=='wrong'){return false}

}

function cmt_drop(doc_no,id_no){

	<?if($_SESSION[sessMember][id]){?>
		location.href="?bmode=pass&mode=cmt_drop&bid=<?=$bid?>&doc_no="+doc_no+"&id_no="+id_no;
	<?}else{?>
		location.href="?bmode=pass&mode2=cmt_drop&bid=<?=$bid?>&doc_no="+doc_no+"&id_no="+id_no;
	<?}?>

}

function drop(){
	if(confirm("삭제하시겠습니까?")){
		location.href="?bmode=pass&mode2=drop&bid=<?=$bid?>&doc_no=<?=$rs[id_no]?>";
	}
}


function edit(){
	<?if($_SESSION[sessMember][id]){?>
		location.href="?bmode=pass&mode=edit&bid=<?=$bid?>&doc_no=<?=$rs[id_no]?>";
	<?}else{?>
		location.href="?bmode=pass&mode2=edit&bid=<?=$bid?>&doc_no=<?=$rs[id_no]?>";
	<?}?>
}
</script>


<div id="ez_conents_wrap">

	<?include("public/inc/bbs_".$bid . "_top.inc");?>


	<div id="ez_view_wrap">
		<h1><?=stripslashes($rs[subject])?></h1>

		<span id="author"><?=$rs[name]?> <?=($rs[id])?"( $rs[id] )":""?></span>
		<span id="wdate">| Date : <?=($rs[edit_date])?date("Y.m.d",$rs[edit_date]):date("Y.m.d",$rs[reg_date])?> | Hit : <?=number_format($rs[cnt])?></span>

	</div>

	<div class="underline"></div>
	<div class="ez_view-contents">

		<?read_img($rs[filename]);?>
		<?read_img($rs[filename2]);?>
		<?read_img($rs[filename3]);?>

		<?=stripslashes($rs[content])?>
	</div>

    <?if($rs[video_tag]){?><div style="padding:20px;text-align:center"><?=$rs[video_tag]?></div><?}?>

	<div class="underline"></div>



	<!-- ez_view-attach -->
	<?if($SET_ASSORT!=2 && ($rs[filename] || $rs[filename2] || $rs[filename3])){?>
	<div id="ez_view-attach">
		<img src="images/ez_board/ico_file.gif" alt="file icon"> <a href="include/download.php?file=<?=$rs[filename]?>&orgin_file_name=<?=$rs[filename_real]?>&dir=public/bbs_files"><?=$rs[filename_real]?> (<?=round(filesize("public/bbs_files/".$rs[filename])/(1024))?> KB)</a>&nbsp;&nbsp;&nbsp;

		<?if($rs[filename2]){?>
		<img src="images/ez_board/ico_file.gif" alt="file icon"> <a href="include/download.php?file=<?=$rs[filename2]?>&orgin_file_name=<?=$rs[filename2_real]?>&dir=public/bbs_files"><?=$rs[filename2_real]?> (<?=round(filesize("public/bbs_files/".$rs[filename2])/(1024))?> KB)</a>&nbsp;&nbsp;&nbsp;
		<?}?>

		<?if($rs[filename3]){?>
		<img src="images/ez_board/ico_file.gif" alt="file icon"> <a href="include/download.php?file=<?=$rs[filename3]?>&orgin_file_name=<?=$rs[filename3_real]?>&dir=public/bbs_files"><?=$rs[filename3_real]?> (<?=round(filesize("public/bbs_files/".$rs[filename3])/(1024))?> KB)</a></li>
		<?}?>
	</div>
	<?}?>
	<!-- //ez_view-attach -->



	<!-- button -->
	<div id="ez_view_btn">
		<ul>
			<?if($SET_POWER_WRITE <= $SET_GRADE){?>
			<li><a href="javascript:drop()"><img src="public/ez_board/button/<?=$BTN_DELETE?>" /></a></li>
			<li><a href="javascript:edit()"><img src="public/ez_board/button/<?=$BTN_EDIT?>" /></a></li>
			<?}?>
			<li><a href="?bmode=list&<?=$_SESSION["link"]?>"><img src="public/ez_board/button/<?=$BTN_LIST?>" /></a></li>
		</ul>
	</div>
	<!-- //-button -->



	<?if($SET_MEMO=="T" && $SET_POWER_REPLY <= $SET_GRADE){?>
	<!-- bbsview-댓글쓰기 -->
	<?
	if($BTN_REPLY){
		$reply_pic = "public/ez_board/button/$BTN_REPLY";
		$reply_pic_info = GetImageSize($reply_pic);
	}
	$reply_textarea_with = ($EZ_BOARD_CONFIG_WIDTH - $reply_pic_info[0])-50;
	?>
	<div id="ez_view_reply">

		<form name="fmCmt" method="post" onsubmit="return checkForm(this)">
		<input type="hidden" name="mode" value="save">
		<input type="hidden" name="doc_no" value="<?=$rs[id_no]?>">

		<div>
			<span class="bold">Name</span> <input type="text" name="name" size="10" maxlength="30" onkeyup="check_kor(this)">
			<?if(!$_SESSION[sessMember][id]){?> &nbsp;&nbsp;
			<span class="bold">Password</span> <input type="password" name="pwd" size="10" maxlength="30" value="" class="input-text">
		<?}?>
		</div>
		<textarea name="comment" rows="5" style="width:<?=$reply_textarea_with?>px;height:<?=$reply_pic_info[1]-5?>px"></textarea>
		<input type="image" src="public/ez_board/button/<?=$BTN_REPLY?>" id="btn_comment">

		</form>
	</div>
	<?}?>
	<!-- //bbsview-댓글쓰기 -->



	<!-- bbscmt-댓글 -->
	<?
	$sql = "select * from ez_bbs_comment where doc_no=$rs[id_no] order by id_no desc";
	list($rows) = $dbo->query($sql);
	if($rows){
	?>
	<div id="ez_view_reply_list">

		<?
		$i=1;
		while($rs=$dbo->next_record()){
		?>

		<div>
		<?=nl2br($rs[comment])?>
		</div>

		<span id="author"><?=$rs[name]?> <?=($rs[id])?"( $rs[id] )":""?></span>
		<span id="wdate">| <?=($rs[edit_date])?date("Y.m.d",$rs[edit_date]):date("Y.m.d",$rs[reg_date])?></span>


		<a href="javascript:cmt_drop(<?=$rs[doc_no]?>,<?=$rs[id_no]?>)"> <img src="images/ez_board/btn_delete.gif" align="absmiddle"></a>
		<?if($rows>$i){?><div class="underline"></div><?}?>

		<?$i++;}?>

	</div>
	<?}?>


	<!-- //bbscmt-댓글 -->
	<?include("public/inc/bbs_".$bid . "_bottom.inc");?>

	<!-- 게시판 목록-->
	<div id="inlist">
		<?
		$BOARD = "script_bbs/list.php";
		include($BOARD);
		?>
	</div>


</div><!-- <div id="ez_conents_wrap"> -->




