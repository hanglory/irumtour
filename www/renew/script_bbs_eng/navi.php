<p class="center">

<?
#### 리스트에서의 페이지 이동
//First,Prev 시작
$thisSite =  "./";

if($page!=1){	//현재의 페이지가 처음이 아니므로 앞으로 갈 수 있어야 함
	$prev_page=$page-1;
	$navi .= "<a class='first' href ='?page=1&${link}' onfocus='blur(this)'><img border=0 src='${thisSite}/images/ez_board/btn_navi_01.gif'  align='absmiddle' alt='처음'></a>&nbsp;&nbsp;<a class='prev' href ='?page=$prev_page&${link}' onfocus='blur(this)'><img border=0 src='${thisSite}/images/ez_board/btn_navi_02.gif' align='absmiddle' alt='이전'></a>&nbsp;";
}
//First,Prev 끝

//페이지 표시 시작
$blockPage=ceil($page/10);

$block_first=($blockPage*10)-9;
$block_last=$blockPage*10;

for($i=$block_first; $i <= $block_last; $i++):
if($i <= $total_page):
	If ($i==$page):
		$navi .="<strong>$i</strong>";
	else:
		$navi .=" <a href='?page=${i}&${link}' onfocus='blur(this)' title='$i page로 이동'>$i</a> ";
	endif;
  endif;
endfor;



//Next,Last 시작
if($page != $total_page): //현재의 페이지가 끝이 아니므로 뒤로 갈 수 있어야 함.
	$next_page=$page+1;
	$navi .= "&nbsp;<a class='next' href ='?page=$next_page&${link}' onfocus='blur(this)' alt='다음'><img border=0 src='${thisSite}/${thisSite}/images/ez_board/btn_navi_03.gif' align='absmiddle'></a>&nbsp;";
	$navi .= "&nbsp;<a class='end' href ='?page=${total_page}&${link}' onfocus='blur(this)' alt='끝'><img border=0 src='${thisSite}/${thisSite}/images/ez_board/btn_navi_04.gif'  align='absmiddle'></a>&nbsp;";
endif;
//Next,Last 끝

echo $navi;
?>

</p>