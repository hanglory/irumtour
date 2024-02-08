<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"고객별예약정보관리대장");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_people";
$MENU = "cmp_basic";
$LEFT_HIDDEN="0";
$TITLE = "현금영수증발행";


if($mode=="save" && $id_no){
	$sql ="update cmp_people set memo='$memo' where id_no=$id_no";
	$dbo->query($sql);
	exit;
}


####각종 기초 정보 결정
$view_row=20;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$column = "*";
$basicLink = "";



####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
if(!$target2) $target2="tour_date";
if(!$period_s) $period_s=date("Y/m/d");
if(!$period_e) $period_e=date("Y/m/d");//date("Y/m/d",mktime(0,0,0,date("m")+1,1,date("Y"))-1);

#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;

if($keyword){
	$filter .=" and $target like '%$keyword%' ";
	$best="";	 //배너 select 초기화
	$findMode=1;
}

#query
$sql_1 = "
	select 
		'1' as assort,
		b.name as leader,
		a.name,
		a.phone,
		a.price,
		a.price_prev as price_sb,
		a.date_in as date_sb,
		a.code,
		a.id_no
	from cmp_people as a left join cmp_reservation as b
	on a.code=b.code
	where a.code<>'' 
	and a.name<>''
	and a.bit=1
	and a.price_prev>0
	and (a.date_in >='$period_s' and a.date_in <='$period_e')
    and b.cp_id='$CP_ID'
	$filter

	union all

	select 
		'2' as assort,
		b.name as leader,
		a.name,
		a.phone,
		a.price,
		a.price_prev2 as price_sb,
		a.date_in2 as date_sb,
		a.code,
		a.id_no
	from cmp_people as a left join cmp_reservation as b
	on a.code=b.code
	where a.code<>'' 
	and a.name<>''
	and a.bit=1
	and a.price_prev2>0
	and (a.date_in2 >='$period_s' and a.date_in2 <='$period_e')	
    and b.cp_id='$CP_ID'
	$filter

	union all

	select 
		'3' as assort,
		b.name as leader,
		a.name,
		a.phone,
		a.price,
		a.price_prev3 as price_sb,
		a.date_in3 as date_sb,
		a.code,
		a.id_no
	from cmp_people as a left join cmp_reservation as b
	on a.code=b.code
	where a.code<>'' 
	and a.name<>''
	and a.bit=1
	and a.price_prev3>0
	and (a.date_in3 >='$period_s' and a.date_in3 <='$period_e')		
    and b.cp_id='$CP_ID'
	$filter
";
$sql_2 = $sql_1 . " order by code desc,date_sb asc";
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
$selectTxt = "대표자,고객명,담당자";
$selectValue ="b.name,a.name,b.main_staff";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1";
$sessLink = "page=$page&" . $link;


$rs[period_s] = $period_s;
$rs[period_e] = $period_e;
?>
<?include("../top.html");?>
<script language="JavaScript">
function cashbill_send(code,id_no,assort,name,price,phone){
	//let url = "../../api/Cashbill/RegistIssue.php";
	let para="";
	para+="?id_no="+id_no;
	para+="&code="+code;
	para+="&assort="+assort;
	para+="&name="+name;
	para+="&price="+price;
	para+="&phone="+phone;
	javascript:newWin('view_<?=$filecode?>.php'+para,870,350,1,1,'','regcashbill')
}

function cashbill_detail(mgtKey){
	let url = "../../api/Cashbill/GetViewURL.php";
	url+="?mgtKey="+mgtKey;
	javascript:newWin(url,800,700,1,1,'','detailcashbill')
}

function cashbill_cancel(mgtKey){
	if(confirm("발행을 취소하시겠습니까?")){
		let url = "../../api/Cashbill/CancelIssue.php";
		url+="?mgtKey="+mgtKey;
		actarea.location.href=url;
	}
}
</script>


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
	<form name="fmSearch" method="get">
	<input type="hidden" name='position' value="">
	<input type="hidden" name='ctg1' value="<?=$ctg1?>">


	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=number_format($total_page)?> page /  <?=number_format($page)?> page }<?}?></font></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>


	입금일 : 
	<?=html_input("period_s",13,10,'box dateinput')?> ~
	<?=html_input("period_e",13,10,'box dateinput')?>


	<select name="target" class='select'>
	<?=option_str($selectTxt,$selectValue,$target)?>
	</select>

	<input class=box type="text" name="keyword" size="15" maxlength="40" value='<?=$keyword?>'>
	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align="center" height="25" bgcolor="#F7F7F6">
		<th class="subject" >대표자명</th>
		<th class="subject" >고객명</th>
		<th class="subject" >핸드폰</th>
		<th class="subject" >항목</th>
		<th class="subject" >입금액</th>
		<th class="subject" >입금일</th>
		<th class="subject" >현금영수증 문서번호</th>
		<th class="subject" >발행</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
if($debug) checkVar($target.mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	$payed_type="";
	if($rs[assort]==1) $payed_type = "계약금";
	elseif($rs[assort]==2) $payed_type = "잔금";
	elseif($rs[assort]==3) $payed_type = "거래처환불";

	$sql3 = "select * from cmp_cashbill where people_id_no=$rs[id_no] and assort=$rs[assort]";
	$dbo3->query($sql3);
	$rs3=$dbo3->next_record();
	//if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar($rs3[mgtKey].mysql_error(),$sql3);}		
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      
	      <td><a href="javascript:newWin('view_reservation.php?code=<?=$rs[code]?>',1200,650,1,1,'','estimate')"><?=$rs[leader]?></a></td>

	      <td height="30"><?=$rs[name]?></td>
	      <td height="30"><?=$rs[phone]?></td>
	      <td height="30"><?=$payed_type?></td>
	      <td height="30"><?=nf($rs[price_sb])?></td>
	      <td height="30"><?=$rs[date_sb]?></td>	      
	      <td height="30"><a href="javascript:cashbill_detail('<?=$rs3[mgtKey]?>')"><?=$rs3[mgtKey]?></a></td>
	      <td height="30">
	      	<?if($rs[phone] && !$rs3[mgtKey]){?>
	      		<span class="btn_pack medium bold"><a href="javascript:cashbill_send('<?=$rs[code]?>','<?=$rs[id_no]?>','<?=$rs[assort]?>','<?=$rs[name]?>','<?=rnf($rs[price_sb])?>','<?=rnf($rs[phone])?>')"> 현금영수증발행 </a></span>
	      	<?}elseif(!$rs[phone]){?>
	      		<span class="gray">핸드폰번호 없음</span>
	      	<?}?>
	      	<?if($rs3[mgtKey]){?>
	      		<!-- <span class="btn_pack medium bold"><a href="javascript:cashbill_cancel('<?=$rs3[mgtKey]?>')"> 현금영수증취소 </a></span> -->
	      	<?}?>
	      </td>	      
	    </tr>
<?
	$prev_leader = $rs[leader];

	$num--;
}
?>
	</table>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">

		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
			<!-- navigation Begin---------------------------------------------->
			<?include_once('../../include/navigation.php')?>
			<?//=$navi?>
			<!-- navigation End------------------------------------------------>
		  </td>
        </tr>
	</table>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
