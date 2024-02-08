
	<table id="tbl_bbslist" summary="이 표는 <?=$SET_SUBJECT?> 목록입니다">
		<caption class="hide-caption"><?=$SET_SUBJECT?></caption>
	<?
	list($rows) = $dbo2->query("select * from $table $filterNotice order by reg_date desc");
	if($rows){
	?>
		<thead>
			<tr>
				<th>구 분</th>
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
	$dbo2->query("select * from $table $filterNotice order by reg_date desc");
	$EZ_BOARD_CONFIG_LENGTH2 = $EZ_BOARD_CONFIG_LENGTH-8;
	while($rs = $dbo2->next_record()){
		$ico_secret = ($rs[secret])? "<img src='images/ez_board/ico_secret.jpg' align='absmiddle'> " : "";
		$url = ($rs[secret])? "?bmode=pass&mode2=read&bid=$bid&page=$page&doc_no=$rs[id_no]" : "?bmode=read&bid=$bid&page=$page&id_no=$rs[id_no]";
	?>
			<tr>
				<td><img src="images/ez_board/ico_notice.gif" align='absmiddle'></td>
				<td class="subject left" ><a href="<?=$url?>" onFocus='blur(this)'><?=titleCut2(stripslashes($rs[subject]),$EZ_BOARD_CONFIG_LENGTH2)?></a> <?=$ico_secret?>  <?=new_icon($rs[reg_date])?></td>
				<td><?=$rs[name]?></td>
				<td><?=date("Y.m.d",$rs[reg_date])?></td>
			</tr>
	<?
		$j++;
	}
	?>
	<?
	if($page!=1){$num=$row_search-($view_row*($page-1));}
	else{$num=$row_search;}

	$dbo->query($sql2);
	while($rs=$dbo->next_record()){
		$ico_secret = ($rs[secret])? "<img src='images/ez_board/ico_secret.jpg' align='absmiddle'> " : "";
		$url = ($rs[secret])? "?bmode=pass&mode2=read&bid=$bid&page=$page&doc_no=$rs[id_no]" : "?bmode=read&bid=$bid&page=$page&id_no=$rs[id_no]";

		$filename = "public/bbs_files/".$rs[filename];
		$thumb = "public/thumb/tb_".$rs[filename];
	?>
			<tr>
				<td class="left w10"><img src="<?=thumbnail($filename, 100, 100, 0, 1, 100, 0, "", "", $thumb)?>" class="webzine_img" onerror="this.src='public/ez_board/bg/<?=$DEFAULT_IMG?>'" width="100"  height="100"></td>
				<td class="list_webzine" colspan="3" valign="top">
					<a href="<?=$url?>&l=1" onFocus='blur(this)' class="bold"><?=titleCut2(stripslashes($rs[subject]),$EZ_BOARD_CONFIG_LENGTH)?></a> <?=$ico_secret?>  <?=new_icon($rs[reg_date])?> <?if($SET_MEMO=="T"){?>(<?=number_format($rs[cnt_comment])?>)<?}?>
					<div class="list_webzine_content">
						<a href="<?=$url?>&l=1" onFocus='blur(this)'><?=titleCut2(stripslashes(strip_tags($rs[content])),340)?></a>
					</div>
				</td>
			</tr>
	<?
		$num--;
	}
	?>
		</tbody>
		</table>