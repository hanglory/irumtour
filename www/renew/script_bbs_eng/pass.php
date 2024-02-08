<?
include_once("common.php");

$id = $_SESSION[sessMember][id];

if($id && $mode2=="drop"){$mode="drop";}
elseif($id && $mode2=="read"){$mode="read";}

if($mode=="drop"){ //게시물삭제

	if($id){
		$sql_ = "and id='$id' ";
		$msg = "You are not logged in.";
	}else{
		$sql_ = "and pwd=password('$pwd') and pwd<>'' ";
		$msg = "Your password is not identical. You are logged in.";
	}

	$sql = "select * from ez_bbs where bid='$bid' and id_no='$doc_no' " . $sql_;
	list($rows) = $dbo->query($sql);

	if($rows){
		$rs=$dbo->next_record();
		if($rs[filename]) @unlink("public/bbs_files/$rs[file1]");
		if($rs[filename2]) @unlink("public/bbs_files/$rs[file2]");
		if($rs[filename3]) @unlink("public/bbs_files/$rs[file3]");

		$sql = "delete from ez_bbs where bid='$bid' and id_no='$doc_no' " . $sql_;
		$dbo->query($sql);

		$sql = "delete from ez_bbs_comment where bid='$bid' and id_no='$doc_no' " . $sql_;
		$dbo->query($sql);

		msggo("Deleted.","?bmode=list&page=1&bid=$bid&u=1");

	}else{

		msggo($msg,"?bmode=read&bid=${bid}&id_no=$doc_no&u=1");

	}
	exit;

}
elseif($mode=="cmt_drop"){//코멘트 삭제

	if($id){
		$sql_ = "and id='$id' ";
		$msg = "You are not logged in.";
	}else{
		$sql_ = "and pwd=password('$pwd') and pwd<>'' ";
		$msg = "Your password is not identical. You are logged in.";
	}

	$sql = "select * from ez_bbs_comment where id_no='$id_no' and doc_no='$doc_no' " . $sql_;
	list($rows) = $dbo->query($sql);

	if($rows){

		$sql = "delete from ez_bbs_comment where id_no='$id_no' and doc_no='$doc_no' " . $sql_;
		if($dbo->query($sql)){
			bbs_counter_comment($doc_no);
		}
		redirect2("?bmode=read&bid=${bid}&id_no=$doc_no&u=1");

	}else{

		//error($msg);
		msggo($msg,"?bmode=read&bid=${bid}&id_no=$doc_no&u=1");
	}
	exit;

}
elseif($mode=="edit"){//수정

	if($id){
		$sql_ = "and id='$id' ";
		$msg = "You are logged in.";
	}else{
		$sql_ = "and pwd=password('$pwd') and pwd<>'' ";
		$msg = "Your password is not identical. You are logged in.";
	}

	$sql = "select * from ez_bbs where id_no='$doc_no' and bid='$bid' " . $sql_;
	list($rows) = $dbo->query($sql);

	if($rows){

		redirect2("?bmode=write&bid=${bid}&id_no=$doc_no&u=1");

	}else{

		//error($msg);
		msggo($msg,"?bmode=read&bid=${bid}&id_no=$doc_no&u=1");
	}

	exit;

}

elseif($mode=="read"){//읽기

	if($id){
		$sql_ = "and id='$id' ";
		$msg = "You are logged in.";
	}else{
		$sql_ = "and pwd=password('$pwd') and pwd<>'' ";
		$msg = "Your password is not identical. You are logged in.";
	}

	$sql = "select * from ez_bbs where id_no='$doc_no' and bid='$bid' " . $sql_;
	list($rows) = $dbo->query($sql);

	if($rows){

		redirect2("?bmode=read&bid=${bid}&id_no=$doc_no&u=1");

	}else{

		//error($msg);
		$link = $_SESSION["sessBoardLink"];
		msggo($msg,"?bmode=list&$link");
	}

	exit;

}


?>

<link rel="stylesheet" type="text/css" href="./script_bbs/css/ez_board.css" />
<link rel="stylesheet" type="text/css" href="./script_bbs/css/ez_default.css" />
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="./script_bbs/css/ez_board_ie.css" />
<![endif]-->


	<script type="text/javascript" src="/include/function.js"></script>
	<script type="text/javascript" src="/include/form_check.js"></script>

	<script language="JavaScript">
	<!--
	function chkForm(){
		var fm = document.fmPwd;

		if(check_blank(fm.pwd,'Password',0)=='wrong'){return false }

	}
	//-->
	</script>


<div id="ez_conents_wrap">

	<?include("public/inc/bbs_".$bid . "_top.inc");?>


	<div id="pass_wrap">
	<form name="fmPwd" method="post" onsubmit="return chkForm()">
	<input type="hidden" name="mode" value="<?=$mode2?>">
	<input type="hidden" name="bid" value="<?=$bid?>">
	<input type="hidden" name="doc_no" value="<?=$doc_no?>">
	<input type="hidden" name="id_no" value="<?=$id_no?>">
		<img src="images/ez_board/imgpw01.gif" align="absmiddle">
		<div id="pass_box">
			<img src="images/ez_board/txt_pw.gif" align="absmiddle">
			<input type="password" name="pwd" class="input-text"/>
			<input type="image" src="public/ez_board/button/<?=$BTN_SUBMIT?>" hspace="4" align="absmiddle" class="border0" />
		</div>
	</form>
	</div>

	<?include("public/inc/bbs_".$bid . "_bottom.inc");?>

</div><!-- <div id="ez_conents_wrap"> -->