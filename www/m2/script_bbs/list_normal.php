
	<table id="tbl_bbslist" summary="이 표는 <?=$SET_SUBJECT?> 목록입니다">
		<caption class="hide-caption"><?=$SET_SUBJECT?></caption>
		<thead>
			<tr>
				<th>번호</th>
				<th>제 목</th>
				<th>작성일</th>
			</tr>
		</thead>
		<tbody>
	<?
	$j=1;
	$dbo2->query("select * from $table $filterNotice order by reg_date desc");
	$EZ_BOARD_CONFIG_LENGTH2 = $EZ_BOARD_CONFIG_LENGTH-8;
	while($rs = $dbo2->next_record()){
		$ico_secret = ($rs[secret])? "<img src='/html/images/ez_board/ico_secret.jpg' align='absmiddle'> " : "";
		$url = ($rs[secret])? "?bmode=pass&mode2=read&bid=$bid&doc_no=$rs[id_no]&page=$page" : "?bmode=read&bid=$bid&id_no=$rs[id_no]";
	?>
			<tr>
				<td><img src="/renew/images/ez_board/ico_notice.gif" align='absmiddle'></td>
				<td class="subject left"><span class="subject100x"><a href="<?=$url?>&l=1" onFocus='blur(this)'><?=stripslashes($rs[subject])?></a> <?=$ico_secret?></span></td>
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
		$ico_secret = ($rs[secret])? "<img src='/html/images/ez_board/ico_secret.jpg' align='absmiddle'> " : "";
		$url = ($rs[secret])? "?bmode=pass&mode2=read&bid=$bid&doc_no=$rs[id_no]&page=$page" : "?bmode=read&bid=$bid&id_no=$rs[id_no]";
	?>
			<tr>
				<td><?=$num?></td>
				<td class="subject left"><span class="subject100x"><a href="<?=$url?>&l=1" onFocus='blur(this)'><?=stripslashes($rs[subject])?></a></span> <?=$ico_secret?> <?=new_icon($rs[reg_date])?> <?if($SET_MEMO=="T"){?>(<?=number_format($rs[cnt_comment])?>)<?}?></td>
				<td><?=date("Y.m.d",$rs[reg_date])?></td>
			</tr>
	<?
		$num--;
	}
	?>
		</tbody>
		</table>