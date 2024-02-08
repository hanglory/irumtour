<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_account";
$TITLE = "계좌번호 선택";


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_account";
$MENU = "cmp_basic";
$TITLE = "송금계좌 정보";



####저장
if($mode=="save"){

	$arr = explode("_",$id_no);
	$id_no_= $arr[0];
	$col = ($arr[1]=="air")? "account" : "account2";
	if($arr[1]=="refund") $col = "account3";

	$sql = "update cmp_people set $col='$account' where id_no=$id_no_";
	$dbo->query($sql);
	echo "<script>opener.document.getElementById('account_${id_no}').innerHTML='${account}';self.close();</script>";

	exit;

}



####각종 기초 정보 결정
$view_row=15;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$column = "*";
$basicLink = "";


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)

#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword){
	$filter .=" and $target like '%$keyword%' ";

	$best="";	 //배너 select 초기화
	$findMode=1;
}



#query
// $sql_1 = "
//     select
//         'account' as stype,
//         partner,
//         bank,
//         account,
//         owner,
//         reg_date
//     from cmp_account as a where id_no>0
//         $filter
// ";


$filter2 = str_replace("partner like","company like",$filter);
$sql_1 = "
    select
        id_no,
        'account' as stype,
        partner,
        bank,
        account,
        owner,
        reg_date
    from cmp_account as a where id_no>0
        $filter

    union all

    select
        id_no,
        'partner' as stype,
        company as partner,
        bank,
        account,
        owner,
        reg_date
    from cmp_partner as a where account<>''
        $filter2

    group by account,owner,bank
";





$sql_2 = $sql_1 . " order by id_no desc limit  $start, $view_row";
//checkVar("",$sql_2);

####자료갯수
list($rows)=$dbo->query($sql_1);//검색된 자료의 갯수
$row_search = $rows;



####페이지 처리

$var=ceil($row_search/$view_row);
if ($var > 1){
	$total_page=$var;
}
else{
	$total_page=1;
}



####자료가 하나도 없을 경우의 처리
if(!$row_search){
   $error[noData] = accentError("해당하는 자료가 없습니다.");
}



####검색 항목
$selectTxt = "거래처,은행명,계좌번호,예금주";
$selectValue ="partner,bank,account,owner";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&id_no=$id_no";
$sessLink = "page=$page&" . $link;
?>
<?include("../top_min.html");?>
<script type="text/javascript">
function drop(){
	if(confirm('삭제하시겠습니까?')){
		location.href="<?=SELF?>?mode=save&id_no=<?=$id_no?>&account=";
	}
}
</script>

<div style="padding:0 10px 0 10px">


	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?>

		</td>
	  </tr>
	  <tr>
		<td> </td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	</table>


	<!--내용이 들어가는 곳 시작-->

	<!-- Search Begin------------------------------------------------>
	<div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
	<form name=fmSearch method="get">
	<input type=hidden name='id_no' value="<?=$id_no?>">


	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=number_format($total_page)?> page /  <?=number_format($page)?> page }<?}?></font></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>?id_no=<?=$id_no?>'">
	<?endif;?>

	<select name="target" class='select'>
	<?=option_str($selectTxt,$selectValue,$target)?>
	</select>

	<input class=box type="text" name="keyword" size="15" maxlength="40" value='<?=$keyword?>'>
	<span class="btn_pack medium bold"><a href="javascript:document.fmSearch.submit()"> 검색 </a></span> &nbsp; | &nbsp;
	<span class="btn_pack medium bold"><a href="javascript:drop()"> 계좌삭제 </a></span>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >번호</th>
		<th class="subject" >거래처</th>
		<th class="subject" >은행명</th>
		<th class="subject" >계좌번호</th>
		<th class="subject" >예금주</th>
		<th class="subject" >등록일</th>
		<th class="subject" >선택</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="30"><?=$num?><?//=(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@"))? $rs[stype]:""?></td>
	      <td>
            <?if($rs[stype]=="account"){?>
                <a href="javascript:newWin('view_account.php?id_no=<?=$rs[id_no]?>',870,500,1,1,'account')" onFocus="blur(this)">
                    <?=$rs[partner]?>
                </a>
            <?}else{?>
                <a href="view_partner.php?id_no=<?=$rs[id_no]?>" target="_blank">
                    <?=$rs[partner]?>
                </a>
            <?}?>
          </td>
	      <td><?=$rs[bank]?></td>
	      <td><?=$rs[account]?></td>
	      <td><?=$rs[owner]?></td>
	      <td><?=substr($rs[reg_date],0,10)?></td>
	      <td><span class="btn_pack medium bold"><a href="<?=SELF?>?mode=save&id_no=<?=$id_no?>&account=<?=$rs[bank]?> <?=$rs[account]?> <?=$rs[owner]?>&stype=<?=$rs[stype]?>"> 선택 </a></span></td>
	    </tr>
<?
	$num--;
}
?>
	</table>


	<table width="100%">
		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
			<!-- navigation Begin---------------------------------------------->
			<?include_once('../../include/navigation.php')?>
			<?=$navi?>
			<!-- navigation End------------------------------------------------>
		  </td>
        </tr>
        </table>


		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
			 <tr>
			  <td align="right" style="padding-right:23px">

				<span class="btn_pack medium bold"><a href="javascript:newWin('view_account.php',870,500,1,1,'account')"> 송금계좌 등록 </a></span> &nbsp; &nbsp;
				<span class="btn_pack medium bold"><a href="list_account.php" target="_blank"> 송금계좌 관리 바로가기 </a></span> &nbsp; &nbsp;
				<span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
			  </td>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>



</div>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>