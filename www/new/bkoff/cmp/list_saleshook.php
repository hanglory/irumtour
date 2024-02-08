<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"인사관리");


$bit_power_action = ($_SESSION["sessLogin"]["id"]=="sanha" || $_SESSION["sessLogin"]["id"]=="chadori" || $_SERVER[REMOTE_ADDR]=="106.246.54.27")?1:0;

####save
if($mode=="proof"){
	if($bit_power_action){
		$proof_date = date("Y/m/d");
		$proof_date2 = date("H:i:s");
		$sql = "
				update cmp_saleshook set
					id_proof='$user_id',
					proof_date='$proof_date',
					proof_date2='$proof_date2'
				where id_no=$id_no and doc_no=$doc_no	
			";
		$dbo->query($sql);
		echo "
			<script>
				parent.location.reload();
			</script>
		";
	}
	exit;
}
elseif($mode=="drop"){
	if($bit_power_action){
		for($i = 0; $i < count($check);$i++){

			$sql = "delete from cmp_saleshook where id_no = $check[$i]";
			$dbo->query($sql);

		}
		back();
		exit;	
	}
}

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_saleshook";
$MENU = "cmp_basic";
$LEFT_HIDDEN="1";
$TITLE = "판촉물 사용승인 현황";

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

#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword){

	if($target=="customer") $filter .=" and doc_no in (select id_no from cmp_reservation where name like '%$keyword%') ";
	elseif($target=="staff") $filter .=" and id in (select id from cmp_staff where name like '%$keyword%') ";
	else $filter .=" and $target like '%$keyword%' ";	

	$findMode=1;
}



#query
$sql_1 = "select $column from $table where id_no>0 $filter";			//자료수
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
$selectTxt = "직원명,고객명";
$selectValue ="staff,customer";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1";
$sessLink = "page=$page&" . $link;

?>
<?include("../top.html");?>
<script language="JavaScript">
<!--
function selectAll(){
	fm = document.fmData;
	for(var i = 1; i < fm.elements.length; i++){
		fm.elements[i].checked = (fm.checkAll.checked == 1)? 1 : 0;
	}
}

function del(){
	var j = 0;
	fm = document.fmData;

	for(var i = 1; i < fm.elements.length; i++){
		if(fm.elements[i].checked == 1){
			j++;
		}
	}
	if(j == 0){
		alert("삭제할 상품을 선택하지 않으셨습니다.");
		return;
	}
	if(confirm("선택한 상품을 삭제하시겠습니까?")){
		fm.action="<?=SELF?>";
		fm.mode.value="drop";
		fm.submit();
	}
}


function proof(doc_no,id_no){
	var url = "<?=SELF?>?mode=proof";
	url+="&doc_no="+doc_no;
	url+="&id_no="+id_no;

	if(confirm('승인하시겠습니까?')){
		actarea.location.href=url;
	}
}
//-->
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
	<form name=fmSearch method="get">
	<input type=hidden name='position' value="">
	<input type=hidden name='ctg1' value="<?=$ctg1?>">


	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=number_format($total_page)?> page /  <?=number_format($page)?> page }<?}?></font></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>

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
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<?if($bit_power_action){?><th class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></th><?}?>
		<th class="subject" >번호</th>
		<th class="subject" >상품명</th>
		<th class="subject" >고객명</th>
		<th class="subject" >요청담당자</th>
		<th class="subject" >일정</th>
		<th class="subject" >골프공</th>
		<th class="subject" >골프골(고급)</th>
		<th class="subject" >항공커버</th>
		<th class="subject" >달러북</th>
		<th class="subject" >신청일</th>
		<th class="subject" >승인</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	$sql3="
		select 
			a.*,
			(select name from cmp_golf where id_no = a.golf_id_no) as golf_name
		from cmp_reservation as a
		where id_no=$rs[doc_no]
		";
	$dbo3->query($sql3);
	$rs2=$dbo3->next_record();
	//checkVar(mysql_error(),$sql3);

?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <?if($bit_power_action){?><td><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td><?}?>
	      <td height="30"><?=$num?></td>
	      <td><?=$rs2[golf_name]?></td>
	      <td><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[doc_no]?>',1100,650,1,1,'','reservation')" style="<?=$css?>"><?=$rs2[name]?></a></td>
	      <td>
	      	<?if($_SESSION["sessLogin"]["id"]==$rs[id] || $bit_power_action){?>
	      	<a href="javascript:newWin('form11.html?id_no=<?=$rs[doc_no]?>&id_no2=<?=$rs[id_no]?>&id=<?=$rs[id]?>',870,650,1,1,'','reservation')"><?=staff_full_name($rs[id])?></a>
	      	<?}?>
	      </td>
	      <td><?=$rs2[d_date]?> ~ <?=$rs2[r_date]?></td>
	      <td><?=nf($rs[golf])?></td>
	      <td><?=nf($rs[golf2])?></td>
	      <td><?=nf($rs[air])?></td>
	      <td><?=nf($rs[book])?></td>
	      <td><?=$rs[reg_date]?></td>
	      <td>

			<?if(!$rs[id_proof] && ($bit_power_action)){?>
				<span class="btn_pack medium bold"><a href="javascript:proof(<?=$rs[doc_no]?>,<?=$rs[id_no]?>)"> 승인 </a></span>
			<?}else{?>
				<?=($rs[id_proof])?"<span style='color:green;text-weight:bold'>승인</span>":"<span style='color:gray'>미승인</span>"?>	
			<?}?>

	      </td>
	    </tr>
<?
	$num--;
}
?>
	</table>



    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
        <?if($bit_power_action){?>
        <tr>
		  <td colspan="12">

		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
			 <tr>
			  <td width="60%" align="left">

			  </td>
			  <td align="right">
				<span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span>
			  </td>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>

		  </td>
        </tr>
        <?}?>

       	<?if(!$seq_mode){?>
		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
			<!-- navigation Begin---------------------------------------------->
			<?include_once('../../include/navigation.php')?>
			<?=$navi?>
			<!-- navigation End------------------------------------------------>
		  </td>
        </tr>
		<?}?>
	</form>
	</table>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
리