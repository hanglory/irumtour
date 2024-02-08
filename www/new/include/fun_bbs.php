<?
/*new icon 표시*/
function new_icon($wdate){

	$time=time()-86400;

	if($wdate > $time){		//24시간 이내의 글이면

		$result = "  <img src='images/ez_board/ico_new.gif' border=0 align=absmiddle>";

	}else{

		$result = "";

	}

	return $result;

}



/*조회수 증가*/
function bbs_counter($id_no){

	global $l;
	global $info;

	$dbo = new MiniDB($info);

	if($l=="1"){
		$dbo ->query ("update ez_bbs set cnt=cnt+1 where id_no=$id_no");
	}

}


/*댓글 증가*/
function bbs_counter_comment($id_no){

	global $info;

	$dbo = new MiniDB($info);

	list($rows) = $dbo ->query ("select id_no from ez_bbs_comment where doc_no=$id_no");
	$dbo ->query ("update ez_bbs set cnt_comment=$rows where id_no=$id_no");

}



/*read page 이미지 본문에 보여주기*/
function read_img($file){
	global $SET_ASSORT;
	global $EZ_BOARD_CONFIG_WIDTH;

	if($file){
		$fname = "public/bbs_files/$file";
		$img_info = GetImageSize($fname);
		$width = ($EZ_BOARD_CONFIG_WIDTH<$img_info[0])?$EZ_BOARD_CONFIG_WIDTH:$img_info[0];
		if($img_info[2]>0 && $img_info[2]<4) echo "<div><img src='$fname' width='$width' class='attach_file'></div>";
	}

}


/*게시판의 종류*/
function board_assort($div){
	if($div=="1") $name = "일반게시판";
	elseif($div=="2") $name = "이미지게시판";
	elseif($div=="3") $name = "FAQ 형식 게시판";
	elseif($div=="4") $name = "이미지+일반게시판";

	return $name;
}

?>