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
				update cmp_reservation set
					gift_id_proof='$user_id',
					gift_proof_date='$proof_date',
					gift_proof_date2='$proof_date2'
				where 
                    id_no=$id_no
                    and cp_id='$CP_ID'
			";
		if($bit==2){
			$sql = "
				update cmp_reservation set
					gift_id_proof='',
					gift_proof_date='',
					gift_proof_date2=''
				where 
                    id_no=$id_no
                    and cp_id='$CP_ID'
			";
		}	
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
$table = "cmp_reservation";
$MENU = "cmp_paper";
$LEFT_HIDDEN="0";
$TITLE = "판촉물 현황";

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
$dtype=($dtype)? $dtype : "d_date";

$date_s = ($date_s)? $date_s : $PAPER_DEFAULT_DAY1;
$date_e = ($date_e)? $date_e : $PAPER_DEFAULT_DAY2;

$year_this = substr($date_s,0,4);
$year_prev = substr($date_s,0,4)-1;
$date_s2 = $year_prev . substr($date_s,4);
$date_e2 = $year_prev . substr($date_e,4);


if(substr($date_s,0,4) > substr($date_e,0,4)){
	error("날짜가 잘못되었습니다. 다시 확인해 주세요.");
	exit;
}

$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword){
	$filter .=" and main_staff like '%$keyword%' ";
	$findMode=1;
}



#query
$sql_1 = "
	select 
		*,
		(select name from cmp_golf where id_no = a.golf_id_no) as golf_name
	from $table as a
	where id_no>0 
	   and golf_id_no>0
	   and (golf_ball+golf_ball2+air_cover+dollarbook)>0
	   and (${dtype} >= '$date_s' and ${dtype} <='$date_e')
       and cp_id='$CP_ID'
	   $filter
	";			//자료수
$sql_2 = $sql_1 . " order by tour_date desc,id_no desc ";// limit $start, $view_row
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


function proof(id_no,bit){
	var url = "<?=SELF?>?mode=proof";
	url+="&id_no="+id_no;
	url+="&bit="+bit;

	var msg = (bit==1)? "승인하시겠습니까?":"승인을 취소하시겠습니까?";

	if(confirm(msg)){
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
	<td valign='bottom' align=right>


	<input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?=$date_s?>" class="box c dateinput">
	~
	<input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?=$date_e?>" class="box c dateinput">

	<select name="dtype">
		<?=option_str("예약일기준,출국일기준","tour_date,d_date",$dtype)?>
	</select>

	<input type="text" name="keyword" id="keyword" size="13" maxlength="10" value="<?=$main_staff?>" placeholder="직원명" class="box"> 	
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
		<th class="subject" >상품명</th>
		<th class="subject" >경로</th>
		<th class="subject" >인원</th>
		<th class="subject" >예약일</th>
		<th class="subject" >출발일</th>
		<th class="subject" >박수</th>
		<th class="subject" >골프공</th>
		<th class="subject" >골프골(고급)</th>
		<th class="subject" >항공커버</th>
		<th class="subject" >마스크</th>
		<th class="subject" >담당자</th>
		<th class="subject" >승인자</th>
		<th class="subject" >승인</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
if($debug) checkVar(mysql_error(),$sql_2);
$sum1=0;
$sum2=0;
$sum3=0;
$sum4=0;
$sum5=0;
while($rs=$dbo->next_record()){

	$night = get_day_night($rs[d_date],$rs[r_date],$rs[plan_type]);
	$arr = explode("(",$rs[main_staff]);
	$arr2 = explode("(",staff_full_name($rs[gift_id_proof]));

	$sum1+=$rs[people];
	$sum2+=$rs[golf_ball];
	$sum3+=$rs[golf_ball2];
	$sum4+=$rs[air_cover];
	$sum5+=$rs[dollarbook];
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="30"><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',1100,650,1,1,'','reservation')" style="<?=$css?>"><?=$rs[golf_name]?></a></td>
	      <td><?=$rs[view_path]?></td>
	      <td><?=$rs[people]?></td>
	      <td><?=$rs[tour_date]?></td>
	      <td><?=$rs[d_date]?></td>
	      <td><?=$night?></td>
	      <td><?=nf($rs[golf_ball])?></td>
	      <td><?=nf($rs[golf_ball2])?></td>
	      <td><?=nf($rs[air_cover])?></td>
	      <td><?=nf($rs[dollarbook])?></td>
	      <td><?=$arr[0]?></td>
	      <td><?=$arr2[0]?></td>
	      <td>

			<?if(!$rs[gift_id_proof] && ($bit_power_action)){?>
				<span class="btn_pack medium bold"><a href="javascript:proof(<?=$rs[id_no]?>,1)"> 승인 </a></span>
			<?}else{?>
				<?if($bit_power_action){?>
					<span class="btn_pack medium bold"><a href="javascript:proof(<?=$rs[id_no]?>,2)"> 취소 </a></span>
				<?}else{?>
					<?=($rs[gift_id_proof])?"<span style='color:green;text-weight:bold'>승인</span>":"<span style='color:gray'>미승인</span>"?>	
				<?}?>
			<?}?>

	      </td>
	    </tr>
<?
	$num--;
}
?>
	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" colspan="2">합계</th>
		<th class="subject" ><?=nf($sum1)?></th>
		<th class="subject" colspan="3"></th>
		<th class="subject" ><?=nf($sum2)?></th>
		<th class="subject" ><?=nf($sum3)?></th>
		<th class="subject" ><?=nf($sum4)?></th>
		<th class="subject" ><?=nf($sum5)?></th>
		<th class="subject" colspan="3"></th>
		</tr>
	</table>



	<!-- 재고 s -->
	<br/>

	<?
	$sql = "
		select
    		sum(golf_ball) as golf_ball,
    		sum(golf_ball2) as golf_ball2,
    		sum(air_cover) as air_cover,
    		sum(dollarbook) as dollarbook
		from cmp_reservation as a
		where golf_id_no>0
    		and (golf_ball+golf_ball2+air_cover+dollarbook)>0
    		and gift_id_proof<>''
            and cp_id='$CP_ID'
		";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	if($debug) {checkVar(mysql_error(),$sql);}		
	?>	
	 <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
	    <tr align=center height=25 bgcolor="#F7F7F6">
			<th class="subject" >구분</th>
			<th class="subject" >골프공</th>
			<th class="subject" >골프골(고급)</th>
			<th class="subject" >항공커버</th>
			<th class="subject" >마스크</th>
		</tr>
	    <tr align=center height=25 bgcolor="#F7F7F6">
			<th class="subject" >기준재고</th>
			<th class="subject" ><?=nf($STOCK_GOLF)?></th>
			<th class="subject" ><?=nf($STOCK_GOLF2)?></th>
			<th class="subject" ><?=nf($STOCK_AIR)?></th>
			<th class="subject" ><?=nf($STOCK_BOOK)?></th>
		</tr>
		<tr align=center height=25 bgcolor="#F7F7F6">
			<th class="subject" >승인</th>
			<th class="subject" ><?=nf($rs[golf_ball])?></th>
			<th class="subject" ><?=nf($rs[golf_ball2])?></th>
			<th class="subject" ><?=nf($rs[air_cover])?></th>
			<th class="subject" ><?=nf($rs[dollarbook])?></th>
		</tr>		
		<tr align=center height=25 bgcolor="#F7F7F6">
			<th class="subject" >현재재고</th>
			<th class="subject" ><?=nf($STOCK_GOLF-$rs[golf_ball])?></th>
			<th class="subject" ><?=nf($STOCK_GOLF2-$rs[golf_ball2])?></th>
			<th class="subject" ><?=nf($STOCK_AIR-$rs[air_cover])?></th>
			<th class="subject" ><?=nf($STOCK_BOOK - $rs[dollarbook])?></th>
		</tr>		
	</table>	
	<!-- 재고 e -->





    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">


       	<?if(!$seq_mode){?>
		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
			<!-- navigation Begin---------------------------------------------->
			<?include_once('../../include/navigation.php')?>
			<?//=$navi?>
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