<p class="center">

<?
#### ����Ʈ������ ������ �̵�
//First,Prev ����
$thisSite =  "./";

if($page!=1){	//������ �������� ó���� �ƴϹǷ� ������ �� �� �־�� ��
	$prev_page=$page-1;
	$navi .= "<a class='first' href ='?page=1&${link}' onfocus='blur(this)'><img border=0 src='${thisSite}/images/ez_board/btn_navi_01.gif'  align='absmiddle' alt='ó��'></a>&nbsp;&nbsp;<a class='prev' href ='?page=$prev_page&${link}' onfocus='blur(this)'><img border=0 src='${thisSite}/images/ez_board/btn_navi_02.gif' align='absmiddle' alt='����'></a>&nbsp;";
}
//First,Prev ��

//������ ǥ�� ����
$blockPage=ceil($page/10);

$block_first=($blockPage*10)-9;
$block_last=$blockPage*10;

for($i=$block_first; $i <= $block_last; $i++):
if($i <= $total_page):
	If ($i==$page):
		$navi .="<strong>$i</strong>";
	else:
		$navi .=" <a href='?page=${i}&${link}' onfocus='blur(this)' title='$i page�� �̵�'>$i</a> ";
	endif;
  endif;
endfor;



//Next,Last ����
if($page != $total_page): //������ �������� ���� �ƴϹǷ� �ڷ� �� �� �־�� ��.
	$next_page=$page+1;
	$navi .= "&nbsp;<a class='next' href ='?page=$next_page&${link}' onfocus='blur(this)' alt='����'><img border=0 src='${thisSite}/${thisSite}/images/ez_board/btn_navi_03.gif' align='absmiddle'></a>&nbsp;";
	$navi .= "&nbsp;<a class='end' href ='?page=${total_page}&${link}' onfocus='blur(this)' alt='��'><img border=0 src='${thisSite}/${thisSite}/images/ez_board/btn_navi_04.gif'  align='absmiddle'></a>&nbsp;";
endif;
//Next,Last ��

echo $navi;
?>

</p>