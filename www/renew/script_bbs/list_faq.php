	<script type="text/javascript">
	<!--
	function view_ans(id,bit){

		if(document.getElementById(id).style.display=="block" && bit!=1) document.getElementById(id).style.display="none";
		else document.getElementById(id).style.display="block";
	}
	//-->
	</script>
	<table id="tbl_bbslist" class="tbl_faq_bbslist" summary="이 표는 <?=$SET_SUBJECT?> 목록입니다">
		<caption class="hide-caption"><?=$SET_SUBJECT?></caption>

	<?
	if($page!=1){$num=$row_search-($view_row*($page-1));}
	else{$num=$row_search;}

	$dbo->query($sql2);
	while($rs=$dbo->next_record()){
	?>
			<tr>
				<td class="subject left bold hand" onclick="view_ans('faq_<?=$num?>')"><img src="images/ez_board/ico_faq_q.gif" width="30"> <?=titleCut2(stripslashes($rs[subject]),$EZ_BOARD_CONFIG_LENGTH)?><?=new_icon($rs[reg_date])?>

				<div class="faq_content" id="faq_<?=$num?>" onclick="view_ans('faq_<?=$num?>',1)">
					<?=$rs[content]?>
				</div>

				</td>
			</tr>
	<?
		$num--;
	}
	?>
		</tbody>
		</table>