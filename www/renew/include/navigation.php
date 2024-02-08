<?
#### 리스트에서의 페이지 이동
//First,Prev 시작
$thisSite =  "https://irumtour.net/new";


if($page!=1){	//현재의 페이지가 처음이 아니므로 앞으로 갈 수 있어야 함
	$prev_page=$page-1;
	$navi .= "<a class=gray href ='?page=1&${link}' onfocus='blur(this)'><img border=0 src='${thisSite}/bkoff/images/button/first.gif'  align='absmiddle'></a>&nbsp;&nbsp;<a class=gray href ='?page=$prev_page&${link}' onfocus='blur(this)'><img border=0 src='${thisSite}/bkoff/images/button/prev.gif' align='absmiddle'></a>&nbsp;";
}
//First,Prev 끝

//페이지 표시 시작
$blockPage=ceil($page/10);

$block_first=($blockPage*10)-9;
$block_last=$blockPage*10;

for($i=$block_first; $i <= $block_last; $i++):
if($i <= $total_page):
	If ($i==$page):
		$navi .=($i == $total_page)? "  <b>$i</b>": "  <b>$i</b> <font color=#9E9E9E>l</font>";
	else:
		$navi .=($i == $total_page)? "  <a class=gray class=navi href='?page=${i}&${link}' onfocus='blur(this)' title='$i page로 이동'>$i</a> " : "  <a class=gray class=navi href='?page=${i}&${link}' onfocus='blur(this)' title='$i page로 이동'>$i</a> <font color=#9E9E9E>l</font>";
	endif;
  endif;
endfor;



//Next,Last 시작
if($page != $total_page): //현재의 페이지가 끝이 아니므로 뒤로 갈 수 있어야 함.
	$next_page=$page+1;
	$navi .= "&nbsp;<a class=gray href ='?page=$next_page&${link}' onfocus='blur(this)'><img border=0 src='${thisSite}/bkoff/images/button/next.gif' align='absmiddle'></a>&nbsp;";
	$navi .= "&nbsp;<a class=gray href ='?page=${total_page}&${link}' onfocus='blur(this)'><img border=0 src='${thisSite}/bkoff/images/button/last.gif'  align='absmiddle'></a>&nbsp;";
endif;
//Next,Last 끝
?>