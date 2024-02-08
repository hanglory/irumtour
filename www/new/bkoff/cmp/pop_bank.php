<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_bank";
$TITLE = "계좌조회";


####API
$date_s = ($date_s)? $date_s : date("Y/m/d",strtotime(date("Y/m/d")." -3 day"));;
$date_e = ($date_e)? $date_e : date("Y/m/d");
include("../../api/bank/savedb.php");


####각종 기초 정보 결정
$view_row=10;	//한 페이지에 보여줄 행 수를 결정

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
$io = ($io)? $io : "i";
if($keyword){
    if($target=="accIn"){
        $filter .=" and accIn=". rnf($keyword);
    }else{
    	$filter .=" and $target like '%$keyword%' ";
    }
	$best="";	 //배너 select 초기화
	$findMode=1;
}
if($date_s){
	$filter .=" and trdate>='".rnf($date_s)."'";
}
if($date_e){
	$filter .=" and trdate<='".rnf($date_e)."'";
}
if($name){
	$filter .=" and remark1='$name'";
	$keyword=$name;
}

$filter .=" and account_no='$accno'";
if($cp_id && $CP_ID) $filter .=" and cp_id='$cp_id'";//독립형 파트너인 경우


if($io=="i") $filter .=" and accIn>0";
else $filter .=" and accOut>0";

if($filter) $filter = " where " . substr($filter,5);

#query
$sql_1 = "select $column from $table $filter";			//자료수
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
$selectTxt = "입금자,영업점,비고,입금액,출금액";
$selectValue ="remark1,remark2,remark3,accIn,accOut";


#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&id=$id&inno=$inno&io=$io&j=$j&date_s=$date_s&date_e=$date_e";
$sessLink = $link;
?>
<?include("../top_min.html");?>
<script type="text/javascript">
function save_input_data(id_no,id,j,price,date,io){
	var inno="<?=$inno?>";
	var no = (inno==1)? '' : inno;
	var no2 = (inno==1)? 1 : inno;
	var inout = (io=="i")? "in":"out";
	opener.$("#"+id).val(price);
	opener.$("#date_"+inout+no+"_"+j).val(date);
	opener.$("#bank_"+inout+no2+"_id_"+j).val(id_no);
	self.close();
}
//-->
</script>

<div style="padding:0 10px 0 10px">

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con" style="padding-left:10px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
	  </tr>
	  <tr>
		<td> </td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	</table>

	<!--내용이 들어가는 곳 시작-->

	<br>


	<!-- Search Begin------------------------------------------------>
	<div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
	<form name="fmSearch" method="get" action="<?=SELF?>">
	<input type="hidden" name="io" value="<?=$io?>">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="j" value="<?=$j?>">
	<input type="hidden" name="inno" value="<?=$inno?>">

	<tr height="22">
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=number_format($total_page)?> page /  <?=number_format($page)?> page }<?}?></font></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>?id=<?=$id?>&j=<?=$j?>&io=<?=$io?>&inno=<?=$inno?>'">
	<?endif;?>

	<input type="text" name="date_s" value="<?=$date_s?>" size="11" maxlength="10" class="box dateinput c"> ~
	<input type="text" name="date_e" value="<?=$date_e?>" size="11" maxlength="10" class="box dateinput c">

	<select name="target" class='select'>
	<?=option_str($selectTxt,$selectValue,$target)?>
	</select>

	<input class=box type="text" name="keyword" size="15" maxlength="40" value='<?=$keyword?>'>
	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	<input class=button type="submit" name="Submit" value=" API재호출 " onFocus='blur(this)' style="width:80px">
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type="hidden" name="mode" value="drop">

	    <tr align="center" height="25" bgcolor="#F7F7F6">
			<th class="subject">거래일자</th>
			<th class="subject">거래일시</th>
			<?if($io=="i"){?><th class="subject">입금액</th><?}?>
			<?if($io=="o"){?><th class="subject">출금액</th><?}?>
			<th class="subject">입금자</th>
			<th class="subject">영업점</th>
			<th class="subject">비고</th>
			<th class="subject">처리</th>
		</tr>

<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
if($debug) checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){
	$price = ($io=="i")? $rs[accIn] : $rs[accOut];
	$rs[trdate2] =substr($rs[trdate],0,4)."/";
	$rs[trdate2] .=substr($rs[trdate],4,2)."/";
	$rs[trdate2] .=substr($rs[trdate],-2);
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="30"><?=$rs[trdate2]?></td>
	      <td><?=substr($rs[trdt],-6)?></td>
	      <?if($io=="i"){?><td class="r" style="padding-right:5px"><?=nf($rs[accIn])?></td><?}?>
	      <?if($io=="o"){?><td class="r" style="padding-right:5px"><?=nf($rs[accOut])?></td><?}?>
	      <td><?=$rs[remark1]?></td>
	      <td><?=$rs[remark2]?></td>
	      <td><?=$rs[remark3]?></td>
	      <td>
	      	<?if(!$rs[bit]){?>
	      	<span class="btn_pack medium bold"><a href="javascript:save_input_data('<?=$rs[id_no]?>','<?=$id?>','<?=$j?>','<?=nf($price)?>','<?=$rs[trdate2]?>','<?=$io?>')">선택</a></span>
	      	<?}else{?>
	      		처리함
	      	<?}?>
	      </td>
	    </tr>
<?
	$num--;
}
?>
	</table>


	<table width="97%">
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
		  <table width="97%" border="0" cellspacing="0" cellpadding="0" align="right">
			 <tr>
			  <td width="60%" align="left">

			  </td>
			  <td align="right" style="padding-right:23px">
				<span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
			  </td>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>



</div>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>