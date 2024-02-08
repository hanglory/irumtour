	<table id="tbl_bbslist" summary="이 표는 <?=$SET_SUBJECT?> 목록입니다">
		<caption class="hide-caption"><?=$SET_SUBJECT?></caption>
	<?
	list($rows) = $dbo2->query("select * from $table $filterNotice order by reg_date desc");
	if($rows){
	?>
		<thead>
			<tr>
				<th>번호</th>
				<th>제 목</th>
				<th>작성자</th>
				<th>작성일</th>
			</tr>
		</thead>
		<tbody>
	<?
	}else{
	?>
	<style type="text/css">
	#ez_conents_wrap #tbl_bbslist {background-image:none;}
	#ez_conents_wrap #tbl_bbslist td{border-top: 1px solid #c9c9c9;}
	</style>
	<?
	}
	$j=1;

	//이미지 가로 사이즈
	$pic_width= ceil(($EZ_BOARD_CONFIG_WIDTH/$EZ_BOARD_CONFIG_GALLERY_1ROWS)-40);


	$EZ_BOARD_CONFIG_LENGTH2 = $EZ_BOARD_CONFIG_LENGTH-8;
	while($rs = $dbo2->next_record()){
		$ico_secret = ($rs[secret])? "<img src='images/ez_board/ico_secret.jpg' align='absmiddle'> " : "";
		$url = ($rs[secret])? "?bmode=pass&mode2=read&bid=$bid&page=$page&doc_no=$rs[id_no]" : "?bmode=read&bid=$bid&page=$page&id_no=$rs[id_no]";
	?>
			<tr>
				<td><img src="images/ez_board/ico_notice.gif" align='absmiddle'></td>
				<td class="subject left"><a href="<?=$url?>&l=1" onFocus='blur(this)'><?=titleCut2(stripslashes($rs[subject]),$EZ_BOARD_CONFIG_LENGTH2)?></a> <?=$ico_secret?></td>
				<td><?=$rs[name]?></td>
				<td><?=date("Y.m.d",$rs[reg_date])?></td>
			</tr>
	<?
		$j++;
	}
	?>


			<tr>
	<?
	if($page!=1){$num=$row_search-($view_row*($page-1));}
	else{$num=$row_search;}

	list($rows) = $dbo->query($sql2);
	$rest = $EZ_BOARD_CONFIG_GALLERY_1ROWS - ($rows%$EZ_BOARD_CONFIG_GALLERY_1ROWS);
	$total_col = $rest+$rows;
	$j=1;
	for($i=0; $i<$total_col;$i++){
		$rs=$dbo->next_record();

		if($rs[id_no]){
			$ico_secret = ($rs[secret])? "<img src='images/ez_board/ico_secret.jpg' align='absmiddle'> " : "";
			$url = ($rs[secret])? "?bmode=pass&mode2=read&bid=$bid&page=$page&doc_no=$rs[id_no]" : "?bmode=read&bid=$bid&page=$page&id_no=$rs[id_no]";

			$filename = "public/bbs_files/".$rs[filename];
			$thumb = "public/thumb/tb_".$rs[filename];
	?>

				<td width="<?=ceil(100/$EZ_BOARD_CONFIG_GALLERY_1ROWS)?>%">
					<a href="<?=$url?>&l=1" onFocus='blur(this)'><img src="<?=thumbnail($filename, $pic_width, $pic_width, 0, 1, 100, 0, "", "", $thumb)?>" class="gallery_img" onerror="this.src='public/ez_board/bg/<?=$DEFAULT_IMG?>'" width="<?=$pic_width?>"  height="<?=$pic_width?>"></a>
				</td>
	<?
			if(!($j%$EZ_BOARD_CONFIG_GALLERY_1ROWS)) echo "</tr><tr>";
			$j++;
			$num--;
		}else{
			echo "<td>&nbsp;</td>";
		}
	}
	?>

			</tr>
		</tbody>
		</table>