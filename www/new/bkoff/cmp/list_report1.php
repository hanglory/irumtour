<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"기간별매출");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_reservation";
$MENU = "cmp_basic";
$LEFT_HIDDEN = "1";
$TITLE = "기간별매출";

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


####국내 해외 분리
$sql = "update cmp_golf set bit_oversea='해외' where nation<>'한국'";
$dbo->query($sql);
$sql = "update cmp_golf set bit_oversea='국내' where nation='한국'";
$dbo->query($sql);



####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
if(!$target2) $target2="tour_date";
if(!$period_s) $period_s=date("Y/m/d",mktime(0,0,0,date("m"),1,date("Y")));
if(!$period_e) $period_e=date("Y/m/d",mktime(0,0,0,date("m")+1,1,date("Y"))-1);


#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : trim($keyword);
if($keyword){
    if($target=="a.golf_name"){
        $filter .=" and ($target like '%$keyword%' or b.nation='$keyword' or b.name like '%${keyword}%') ";
    }else{
    	$filter .=" and $target like '%$keyword%' ";
    }
	$best="";	 //배너 select 초기화
	$findMode=1;
}

if($period_s){ $filter.= " and $target2 >='$period_s' ";$rs[period_s]=$period_s;$find_bit=1;}
if($period_e){ $filter.= " and $target2 <='$period_e' ";$rs[period_e]=$period_e;$find_bit=1;}


if($research){

	//checkVar("1",$rkeyword1);
	//checkVar("2",$rkeyword2);

	if($rkeyword1) $filter .= " and $rtarget1 like '%$rkeyword1%'";
	if($rkeyword2) $filter .= " and $rtarget2 like '%$rkeyword2%'";

}

if(strstr($_SESSION["sessLogin"]["staff_type"],"partner")) $filter.=" and a.main_staff like '%($user_id)'";

if($bit_oversea=="O") $filter.=" and b.bit_oversea ='해외'";
elseif($bit_oversea=="D") $filter.=" and b.bit_oversea ='국내'";


#query
if($target!="a.partner"){
	
    $sql_1 = "
        select
            a.*,
            (select subject from cmp_estimate where id_no=a.origin_id_no) as est_subject,
            b.bit_oversea as nation,
            (select city from cmp_golf where id_no=a.golf_id_no) as city,
            (select count(*) from cmp_people where bit=1 and code=a.code and price_air>0 and date_out='') as blank_date1,
            (select count(*) from cmp_people where bit=1 and code=a.code and price_land>0 and date_out2='') as blank_date2,
            (select count(*) from cmp_people where bit=1 and code=a.code and price_refund>0 and date_out3='') as blank_date3,
            (select count(*) from cmp_people where bit=1 and code=a.code and price_prev>0 and date_in='') as blank_date4,
            (select count(*) from cmp_people where bit=1 and code=a.code and price_prev2>0 and date_in2='') as blank_date5,
            (select count(*) from cmp_people where bit=1 and code=a.code and price_prev3>0 and date_in3='') as blank_date6,
            (select count(*) from cmp_people where bit=1 and code=a.code and bit_cancel=1) as bit_cancel
            from $table as a left join cmp_golf as b
            on a.golf_id_no=b.id_no
            where
            a.id_no>0
            $filter
            $FILTER_PARTNER_QUERY_CPID
        ";        


	$sql_2 = $sql_1 . " order by a.id_no desc"; // limit  $start, $view_row

}else{

#query
	$sql_1 = "
		select
			a.*,
			a.partner,
			b.nation,
			b.city,
			(select subject from cmp_estimate where id_no=a.origin_id_no) as est_subject,
			(select count(*) from cmp_people where bit=1 and code=a.code and bit_cancel=1) as bit_cancel
			from $table as a left join cmp_golf as b
			on a.golf_id_no=b.id_no
			where
			a.id_no>0
			$filter
            $FILTER_PARTNER_QUERY_CPID
		";			//자료수
	$sql_2 = $sql_1 . " order by a.id_no desc"; // limit  $start, $view_row
}

if($debug) checkVar("",$sql_2);

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
$selectTxt = "대표자명,국가/지역/골프장명,경로,출발일,결제수단,담당자,서브 담당자,거래처";
$selectValue ="a.name,a.golf_name,a.view_path,a.d_date,a.pay_method,a.main_staff,a.sub_staff,a.partner";
if($user_id=="tester" || $user_id=="test"){
  $selectTxt = "대표자명,국가/지역/골프장명,경로,출발일,결제수단,담당자,거래처";
  $selectValue ="a.name,a.golf_name,a.view_path,a.d_date,a.pay_method,a.main_staff,a.partner";  
}


#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&period_s=$period_s&period_e=$period_e&target2=$target2";
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
		fm.action="view_<?=$filecode?>.php";
		fm.mode.value="drop";
		fm.submit();
	}
}

function chk_research(obj){
	var fm = document.fmSearch;

	if(obj.checked==true){
	}else{
		$("#rtarget1").val("");
		$("#rtarget2").val("");
		$("#rtarget3").val("");
		$("#rkeyword1").val("");
		$("#rkeyword2").val("");
		$("#rkeyword3").val("");
		$("#key_wrap").text("");
	}
}

$(function(){
  <?if($user_id!="tester" && $user_id!="test"){?>
    $(".htxt_price1").text($(".txt_price1").text());        
    $(".htxt_price2").text($(".txt_price2").text());        
    $(".htxt_price3").text($(".txt_price3").text());        
    $(".htxt_price4").text($(".txt_price4").text());        
    $(".htxt_price5").text($(".txt_price5").text());        
    $(".htxt_price6").text($(".txt_price6").text());        
    $(".htxt_price7").text($(".txt_price7").text());        
    $(".htxt_price8").text($(".txt_price8").text());        
    $(".htxt_price9").text($(".txt_price9").text());
  <?}?>
});
//-->
</script>
<style type="text/css">
.red{color:red;font-weight:bold}
</style>



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

	<input type=hidden name='rtarget1' id='rtarget1' value="<?=$target?>">
	<input type=hidden name='rtarget2' id='rtarget2' value="<?=$rtarget1?>">
	<input type=hidden name='rtarget3' id='rtarget3' value="<?=$rtarget2?>">

	<input type=hidden name='rkeyword1' id='rkeyword1' value="<?=$keyword?>">
	<input type=hidden name='rkeyword2' id='rkeyword2' value="<?=$rkeyword1?>">
	<input type=hidden name='rkeyword3' id='rkeyword3' value="<?=$rkeyword2?>">


	<?if($keyword){?>
	<tr>
		<td colspan="3" align="right">

			<?if($research){?>
			<div id="key_wrap">(키워드 : <?=$keyword?> <?=($rkeyword1)?",$rkeyword1":""?> <?=($rkeyword2)?",$rkeyword2":""?>)</div>
			<?}else{?>
			<?}?>

			<label><input type="checkbox" name="research" value="1" <?=($research)?"checked":""?> onclick="chk_research(this)"> 결과내 검색</label>
		</td>
	</tr>
	<?}?>

	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=number_format($total_page)?> page /  <?=number_format($page)?> page }<?}?></font></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>

	<select name="target2" class='select'>
	<?=option_str("예약일,출발일","tour_date,d_date",$target2)?>
	</select>

	<?=html_input("period_s",13,10,'box dateinput')?> ~
	<?=html_input("period_e",13,10,'box dateinput')?>


    <select name="bit_oversea" class='select'>
    <?=option_str("전체,국내,해외",",D,O",$bit_oversea)?>
    </select>

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
       <thead>  
	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" rowspan="2">대표자명</th>
		<th class="subject" rowspan="2">국가</th>
		<th class="subject" rowspan="2">지역</th>
		<th class="subject" rowspan="2">상품명</th>
		<th class="subject" rowspan="2">경로</th>
		<th class="subject" rowspan="2">인원</th>
		<th class="subject" rowspan="2">예약일</th>
		<th class="subject" rowspan="2">출발일</th>
		<th class="subject" rowspan="2">박수</th>
		<th class="subject" >총판매가</th>
		<th class="subject" colspan="4">출금내역</th>
		<th class="subject" rowspan="2">매출<br/>입금액(계약금+잔금)-출금액</th>
		<th class="subject" rowspan="2">1인수익</th>
		<th class="subject" rowspan="2">고객입금액</th>
		<!-- <th class="subject" rowspan="2">가지급금</th> -->
		<th class="subject" rowspan="2">거래처</th>
        <th class="subject" rowspan="2">담당자</th>
		<th class="subject" rowspan="2">결제수단</th>
		<!-- <th class="subject" rowspan="2">입금액</th> -->
		<!-- <th class="subject" rowspan="2">골프공</th> -->
		<!-- <th class="subject" rowspan="2">항공카버</th> -->
		<!-- <th class="subject" rowspan="2">달러북</th> -->
		<th class="subject" rowspan="2">마감</th>
		</tr>


	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" >인원수<br/>*1인판매가</th>
		<th class="subject" >항공</th>
		<th class="subject" >지상</th>
		<th class="subject" >고객환불</th>
		<th class="subject" >소계</th>
		</tr>


        <?if($user_id!="tester" && $user_id!="test"){?>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
            <td height="35" colspan="5" style="font-weight:bold;color:red">합계</td>
            <td><span class="red bold htxt_price1">1</span></td>
            <td></td>
            <td></td>
            <td></td>
            <td><span class="red bold htxt_price2">2</span></td>
            <td><span class="red bold htxt_price3">3</span></td>
            <td><span class="red bold htxt_price4">4</span></td>
            <td><span class="red bold htxt_price5">5</span></td>
            <td><span class="red bold htxt_price6">6</span></td>
            <td><span class="red bold htxt_price7">7</span></td>
            <td><span class="red bold htxt_price8">8</span></td>
            <td><span class="red bold htxt_price9">9</span></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?}?>
        </thead>

        <tbody>
<?
$price1=0;
$price2=0;
$price3=0;
$price4=0;
$price5=0;
$price6=0;
$price7=0;
$price8=0;
$price9=0;
$price10=0;
$price11=0;
$price12=0;
$price13=0;

if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	$rs[golf_name] = ($rs[est_subject])? $rs[est_subject] : $rs[golf_name];
	if(!strstr($rs[golf_name],">")) $rs[golf_name] ="$rs[nation]>$rs[city]>$rs[golf_name]";

	$golf_name = explode(">",$rs[golf_name]);

	//if(strstr("@106.246.54.27@221.154.110.119@",$_SERVER["REMOTE_ADDR"])){checkVar($rs[golf_id_no],$rs[golf_name]);}

	$night = (strtotime($rs[r_date]) -  strtotime($rs[d_date]))/86400;//박수

	$sql3 = "select
			count(*) as cnt,
			sum(price) as price,
			sum(price_air) as price_air,
			sum(price_land) as price_land,
			sum(price_refund) as price_refund,
			sum(price_prev+price_prev2+price_prev3) as price_prev,
			sum((price_prev+price_prev2+price_prev3)-(price_air+price_land+price_refund)) as fee
		from cmp_people where code=$rs[code] and bit=1";
	$dbo3->query($sql3);
	$rs3= $dbo3->next_record();
	//if($debug) checkVar(mysql_error(),$sql3);

	if($rs[d_date]<="2017/12/31"){
		@$gain_one = ($rs3[price] - ($rs3[price_air]+$rs3[price_land]+$rs3[price_refund]))/$rs[people];
	}else{
		@$gain_one = (rnf($rs3[fee]))/$rs[people];
	}

	$staff = explode("(",$rs[main_staff]);

	/*
	if($REMOTE_ADDR=="106.246.54.27"){
		$update_price = $rs3[price]-($rs3[price_air]+$rs3[price_land]);
		$sql3 = "update cmp_reservation set fee=$update_price where id_no=$rs[id_no]";
		$dbo3->query($sql3);
		//checkVar(mysql_error(),$sql3);
	}
	*/

	$css = ($rs[bit_cancel])? "color:red":"";
	//if($rs[bit_cancel]) $rs[people]-=$rs[bit_cancel];


	$blank_date1 = 0;
	$blank_date1 = $rs[blank_date1]+$rs[blank_date2]+$rs[blank_date3]+$rs[blank_date4]+$rs[blank_date5]+$rs[blank_date6];

?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
			<td height="35"><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',1000,650,1,1,'','reservation')" style="<?=$css?>"><?=$rs[name]?></a><?if($blank_date1){?><span style="color:red;font-weight:bold;font-size:16px">*</span><?}?></td>
			<td style="<?=$css?>"><?=$rs[nation]?></td>
			<td style="<?=$css?>"><?=trim($golf_name[1])?></td>
			<td style="<?=$css?>"><?=trim($golf_name[2])?></td>
			<td style="<?=$css?>"><?=$rs[view_path]?></td>
			<td style="<?=$css?>"><?=nf($rs[people])?></td>
			<td style="<?=$css?>"><?=$rs[tour_date]?></td>
			<td style="<?=$css?>"><?=$rs[d_date]?></td>
			<td style="<?=$css?>"><?=$night?></td>
			<td style="<?=$css?>" class="numberic"><?=nf($rs3[price])?><!--매출/인원수*1인판매가--></td>
			<td style="<?=$css?>" class="numberic"><?=nf($rs3[price_air])?></td>
			<td style="<?=$css?>" class="numberic"><?=nf($rs3[price_land])?></td>
			<td style="<?=$css?>" class="numberic"><?=nf($rs3[price_refund])?></td>
			<td style="<?=$css?>" class="numberic red"><?=nf($rs3[price_air]+$rs3[price_land]+$rs3[price_refund])?></td>
			<td style="<?=$css?>" class="numberic"><?=nf($rs3[fee])?><!-- 매출 --></td>
			<td style="<?=$css?>" class="numberic"><?=nf($gain_one)?><!-- 1인수익 --></td>
			<td style="<?=$css?>" class="numberic red"><?=nf($rs3[price_prev])?><!-- 고객입금액 --></td>
			<!-- <td style="<?=$css?>" class="numberic"><?=nf($rs[price_tmp_output])?></td> --><!--가지급금  -->
			<td style="<?=$css?>"><?=$rs[partner]?></td>
			<td style="<?=$css?>"><?=$staff[0]?></td>
            <td style="<?=$css?>"><?=$rs[pay_method]?></td>
			<!-- <td style="<?=$css?>" class="numberic"><?=nf($rs[payed_price])?></td> --><!-- 입금액 -->
			<!-- <td style="<?=$css?>" class="numberic"><?=$rs[golf_ball]?></td> -->
			<!-- <td style="<?=$css?>" class="numberic"><?=$rs[air_cover]?></td> -->
			<!-- <td style="<?=$css?>" class="numberic"><?=$rs[dollarbook]?></td> -->
			<td style="<?=$css?>"><?=($rs[bit_close])?"Y":"<span style='color:#ccc'>N</span>"?></td>
	    </tr>
<?
    if($rs[bit_cancel] && !$rs3[fee]){//취소 중 매출이 0인 경우 전체 합계에서 제외

    }else{

    	$price1+=$rs[people];
    	$price2+=$rs3[price];
    	$price3+=$rs3[price_air];
    	$price4+=$rs3[price_land];
    	$price13+=$rs3[price_refund];
    	$price5+=($rs3[price_air]+$rs3[price_land]+$rs3[price_refund]);
    	$price6+=($rs3[fee]);
    	$price7+=$gain_one;
    	$price8+=($rs[price_prev]);
    	$price9+=($rs[price_tmp_output]);

    	$price10+=$rs[golf_ball];
    	$price11+=$rs[air_cover];
    	$price13+=$rs[dollarbook];
    	$price12+=$rs[payed_price];

    	$num--;
    }
}
?>
    </tbody>
    <tfoot>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
			<td height="35" colspan="5" style="font-weight:bold;color:red">합계</td>
			<td style="font-weight:bold;color:red"><span class="txt_price1"><?=nf($price1)?></span></td>
			<td></td>
			<td></td>
			<td></td>
			<td class="numberic" style="font-weight:bold;color:red"><span class="txt_price2"><?=nf($price2)?></span></td>
			<td class="numberic" style="font-weight:bold;color:red"><span class="txt_price3"><?=nf($price3)?></span></td>
			<td class="numberic" style="font-weight:bold;color:red"><span class="txt_price4"><?=nf($price4)?></span></td>
			<td class="numberic" style="font-weight:bold;color:red"><span class="txt_price5"><?=nf($price13)?></span></td>
			<td class="numberic" style="font-weight:bold;color:red"><span class="txt_price6"><?=nf($price5)?></span></td>
			<td class="numberic" style="font-weight:bold;color:red"><span class="txt_price7"><?=nf($price6)?></span></td>
			<td class="numberic" style="font-weight:bold;color:red"><span class="txt_price8"><?=nf($price7)?></span></td>
			<td class="numberic" style="font-weight:bold;color:red"><span class="txt_price9"><?=nf($price8)?></span></td>
			<!-- <td class="numberic" style="font-weight:bold;color:red"><?=nf($price9)?></td> 가지급금라인-->
			<td></td>
			<td></td>
			<!-- <td class="numberic" style="font-weight:bold;color:red"><?=nf($price12)?></td> 입금액 라인-->
			<!-- <td class="numberic" style="font-weight:bold;color:red"><?=nf($price10)?></td> -->
			<!-- <td class="numberic" style="font-weight:bold;color:red"><?=nf($price11)?></td> -->
			<!-- <td class="numberic" style="font-weight:bold;color:red"><?=nf($price13)?></td> -->
			<td class="numberic" style="font-weight:bold;color:red"></td>
	    </tr>
    </tfoot>
	</table>
<?php
    if($bit_oversea=="O" || $bit_oversea=="D"){
        // strtotime 함수와 mktime 함수를 사용하여 7개월 전으로 계산
        $timestamp = strtotime($period_e);
        $seven_months_ago_timestamp = mktime(0, 0, 0, date("m", $timestamp) - 7, date("d", $timestamp), date("Y", $timestamp));
        $seven_months_ago = date("Y/m", $seven_months_ago_timestamp);

        if($bit_oversea=="D") $where_add = " AND tour_date >= '".$seven_months_ago."/01' AND tour_date <= '".$period_e."' AND b.bit_oversea = '국내' ";
        else    $where_add = " AND tour_date >= '".$seven_months_ago."/01' AND tour_date <= '".$period_e."' AND b.bit_oversea = '해외' ";
        $sql_3 = "SELECT
  substr(a.tour_date, 1, 7) AS tour_month,
  COUNT(*) AS count_per_month,
  ROUND(COUNT(*) * 100.0 / total.total_count, 2) AS percentage_of_total
FROM
  cmp_reservation AS a
  LEFT JOIN cmp_golf AS b ON a.golf_id_no = b.id_no
  CROSS JOIN (
    SELECT COUNT(*) AS total_count
    FROM cmp_reservation AS a
    LEFT JOIN cmp_golf AS b ON a.golf_id_no = b.id_no
    WHERE
      a.id_no > 0
      ".$where_add."
      AND a.cp_id = ''
  ) AS total
WHERE
  a.id_no > 0
      ".$where_add."
  AND a.cp_id = ''
GROUP BY substr(a.tour_date, 1, 7), total.total_count
ORDER BY  tour_month DESC;";

        if($debug) checkVar("",$sql_3);
        $dbo->query($sql_3);
?>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_stat_list">
        <form name="fmData" method="post">
            <input type=hidden name=mode value='drop'>
            <thead>
            <tr align=center height=25 bgcolor="#F7F7F6">
                <th class="subject" rowspan="2">구분</th>
                <th class="subject" rowspan="2">월</th>
                <th class="subject" rowspan="2">인원</th>
                <th class="subject" rowspan="2">비율</th>
            </tr>
<?php
        while($rs3=$dbo->next_record()){
?>
            <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
                <td height="35"> M(해당월)</td>
                <td style="<?=$css?>"><?=$rs[nation]?></td>
                <td style="<?=$css?>"><?=trim($golf_name[1])?></td>
                <td style="<?=$css?>"><?=trim($golf_name[2])?></td>

<?php

        }
    }
?>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
        <tr>
		  <td colspan="12">

		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
			 <tr>
			  <td width="60%" align="left">

			  </td>

			  <?if(strstr($_SESSION["sessLogin"]["proof"],"엑셀다운로드")){?>
			  <td align="right">
				<span class="btn_pack medium bold"><a href="list_report1_print.php?period_s=<?=$period_s?>&period_e=<?=$period_e?>&target=<?=$target?>&target2=<?=$target2?>&keyword=<?=$keyword?>"> 엑셀 </a></span>
			  </td>
			  <?}?>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>

		  </td>
        </tr>

		<!--
       	<?if(!$seq_mode){?>
		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
			<?include_once('../../include/navigation.php')?>
			<?=$navi?>
		  </td>
        </tr>
		<?}?> -->
	</form>
	</table>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>


<?
/*
if($REMOTE_ADDR=="106.246.54.27"){

	$sql = "select a.*,(select sum(price_prev) from cmp_people where bit=1 and code=a.code) as p from cmp_reservation as a where price_prev<>(select sum(price_prev) from cmp_people where code=a.code and bit=1) order by id_no desc";
	list($rows) = $dbo->query($sql);
	checkVar($rows.mysql_error(),$sql);
	while($rs=$dbo->next_record()){
		//checkVar($rs[reg_date]."/".$rs[id_no]."/".$rs[price_prev],$rs[p]);
		$sql2 = "update cmp_reservation set price_prev=$rs[p] where id_no=$rs[id_no]";
		$dbo2->query($sql2);
		checkVar(mysql_error(),$sql2);
	}
}
*/
?>