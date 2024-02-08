<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour";
$MENU = "tour";
$TITLE = "렌트카설정";
if($mode=="copy") $TITLE .= " (Copy mode : 내용을 수정하여 저장하시면 새로 등록됩니다.)";

#### operation

if ($mode=="save"){

	$sql1 = "delete from ez_railcartel_part where tid=$tid and sub_tid=$sub_tid and assort='$assort'";

	$sql2="
	   insert into ez_railcartel_part (
		  assort,
		  tid,
		  sub_tid
	  ) values (
		  '$assort',
		  '$tid',
		  '$sub_tid'
	)";

	$sql = ($bit)? $sql2 : $sql1;
	$dbo->query($sql);
	exit;

}

####각종 기초 정보 결정
$view_row=10;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
#검색조건
if($keyword)	 {
	$keyword =trim($keyword);
	$filter.=" and $target like '%$keyword%' ";$findMode=1;
}


#query
$sql1 = "select * from ez_tour where category1='rentcar' $filter";			//자료수
$sql2 = $sql1 . " order by rent_area asc,subject asc limit  $start, $view_row";

If($bit){
	$sql1 = "select a.* from ez_tour as a right join ez_railcartel_part as b on a.tid=b.sub_tid where b.assort='rentcar' and b.tid=$tid $filter";			//자료수
	$sql2 = $sql1 . " order by a.rent_area asc,a.subject asc limit  $start, $view_row";
}


####자료갯수
list($rows)=$dbo->query($sql1);//검색된 자료의 갯수
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
$selectTxt = "렌트카명,차종,인도지역";
$selectValue ="subject,rent_type,rent_area";



#### Link
$link = "keyword=$keyword&target=$target&sort=$sort";
$sessLink = "page=$page&" . $link;


//-------------------------------------------------------------------------------
?>
<link rel="stylesheet" type="text/css" href="../../css/style.css" /></link>
<?include("../top_min.html");?>
<script type="text/javascript" src="/cheditor/cheditor.js"></script>
<style type="text/css">
.w10{width:10%}
.tbl_title{font-weight:bold;padding:20px 0 10px 30px;}
.tour_table{padding-left:30px;}
</style>
<script language="JavaScript">
<!--
$(function(){
	$(".choice").click(function(){
		tid = '<?=$tid?>';
		sub_tid = $(this).val();

		if(tid==""){
			alert('오류가 있어 창을 닫습니다.');
			self.close();
		}

		var bit=0;
		if(this.checked==true) bit=1;

		url = "pop_railcartel_rentcar.php?mode=save";
		url += "&assort=rentcar";
		url += "&tid="+tid;
		url += "&sub_tid="+sub_tid;
		url += "&bit="+bit;

		actarea.location.href=url;

	});

	$("#bit").click(function(){
		tid = '<?=$tid?>';

		if(tid==""){
			alert('오류가 있어 창을 닫습니다.');
			self.close();
		}

		var bit=0;
		if(this.checked==true) bit=1;

		url = "pop_railcartel_rentcar.php?tid="+tid;
		url += "&bit="+bit;

		location.href=url;

	});
});
//-->
</script>


	<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
	  <tr>
		<td class="title_con" style="padding-left:20px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
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
		<table border=0 width="95%" align="center">
		<form name="fmSearch">
		<input type="hidden" name="tid" value='<?=$tid?>'>

		<tr height=22>
		<td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 { <?=number_format($total_page)?> page /  <?=number_format($page)?> page }</font></td>
		<td valign='bottom' align=right>
		<?if($findMode):?>
		<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>?tid=<?=$tid?>'">
		<?endif;?>

		<label><input type="checkbox" id="bit" value="1" <?=($bit)?"checked='checked'":""?> /> 선택된 렌트카만 보기 </label>
		&nbsp;&nbsp;&nbsp;

		<select name="target" class='select'>
		<?=option_str($selectTxt,$selectValue,$target)?>
		</select>

		<span class="top"><input class="box" type="text" name="keyword" maxlength="40" value='<?=($keyword=="Iw==")? "#":$keyword;?>'></span>
		<span class="btn_pack small"><a href="#" onClick="document.fmSearch.submit()"> 검색 </a></span>
		</td>
		<tr>
		</form>
		</table>
		<!-- Search End------------------------------------------------>


	   <table border="0" cellspacing="1" cellpadding="3" width="95%" align="center">
		<form name="fmData"  method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="tid" value='<?=$tid?>'>


		<tr><td colspan="10"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr>
          <td class="subject c">번호</td>
          <td class="subject c">인도지역</td>
		  <td class="subject c">렌트카명</td>
          <td class="subject c">차종</td>
          <td class="subject c">설정</td>
        </tr>
		<tr><td colspan="10" class="tblLine"></td></tr>

		<?
		if($page!=1){$num=$row_search-($view_row*($page-1));}
		else{$num=$row_search;}

		$dbo->query($sql2);
		//checkVar(mysql_error(),$sql2);
		while($rs=$dbo->next_record()){
			$sql2 = "select * from ez_railcartel_part where tid=$tid and sub_tid=$rs[tid]";
			list($rows2) = $dbo2->query($sql2);
		?>
	    <tr align="center">
			<td class="c" height="25"><?=$num?></td>
			<td class="c"><?=$rs[rent_area]?></td>
			<td class="c"><?=$rs[subject]?></td>
			<td class="c"><?=$rs[rent_type]?></td>
			<td class="c"><input type="checkbox" name="check[]" value="<?=$rs[tid];?>" class="choice" <?=($rows2)?"checked='checked'":""?>></td>
        </tr>
		<tr><td colspan="10" class="tblLine"></td></tr>
		<?
			$num--;
		}
		?>
        <tr><td colspan="9"  bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan="9"  bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
		  <td colspan="9"  align=center>
			<!-- navigation Begin---------------------------------------------->
			<?include_once('../../include/navigation.php')?>
			<?=$navi?>
			<!-- navigation End------------------------------------------------>
		  </td>
        </tr>

		<tr>
		  <td colspan=10>
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td width="74%" align="left" valign="top" style="padding-left:18px"></td>
					<td><span class="btn_pack medium bold"><a href="javascript:self.close()"> 창닫기 </a></span></td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>
		  </td>
        </tr>

        <tr>
	  <td colspan=10 height=20>
	  </td>
        </tr>
	</form>
	</table>


	<!--결과-->

	<?
	$sql = "select * from ez_tour_rentcar_list where tid='$tid' group by tid,rentcar,rentcar_go,rentcar_return,updown,station order by id_no asc";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
	?>

	<div class='tbl_title'>
		<img src="/images/view/ic_stitle.gif" alt=""/><?=$rs[station]?> / <?=($rs[updown]=="up")?"상행":"하행"?> / 가는편: <?=$rs[rentcar_go]?>, 오는편: <?=$rs[rentcar_return]?>
	</div>

	<div class="tour_table">
	<table class="tbl_table">
	<tr>
		<th>기간</th>
		<th>구분</th>
		<th>월요일</th>
		<th>화요일</th>
		<th>수요일</th>
		<th>목요일</th>
		<th>금요일</th>
		<th>토요일</th>
		<th>공휴일</th>
		<!-- <th>공휴일전일</th> -->
		<th width="60">수정</th>
	</tr>

	<?
	$sql2 = "select * from ez_tour_rentcar where tid='$tid' and rentcar='$rs[rentcar]' and rentcar_go='$rs[rentcar_go]' and rentcar_return='$rs[rentcar_return]' and updown='$rs[updown]' and station = '$rs[station]' order by period_s asc, period_e asc";
	$dbo2->query($sql2);
	//If($REMOTE_ADDR=="1.215.151.146") checkVar(mysql_error(),$sql2);
	$j=0;
	while($rs2=$dbo2->next_record()){
	?>
		<tr>
			<td class="c" rowspan="2"><?=(!$rs2[period_s])?"전체기간":""?><?=$rs2[period_s]?><br><?=($rs2[period_e])?"~":""?><?=$rs2[period_e]?> </td>
			<td class="c">대인</td>
			<td class="c"><?=number_format($rs2[week_1])?></td>
			<td class="c"><?=number_format($rs2[week_2])?></td>
			<td class="c"><?=number_format($rs2[week_3])?></td>
			<td class="c"><?=number_format($rs2[week_4])?></td>
			<td class="c"><?=number_format($rs2[week_5])?></td>
			<td class="c"><?=number_format($rs2[week_6])?></td>
			<td class="c"><?=number_format($rs2[week_7])?></td>
			<!-- <td class="c"><?=number_format($rs2[week_8])?></td> -->
			<td class="c" rowspan="2">
				<span class="btn_pack small bold"><a href="?tid=<?=$tid?>&code=<?=$rs2[code]?>&days=<?=$days?>&bstations=<?=$bstations?>"> 수정 </a></span><br>
				<span class="btn_pack small bold"><a href="?mode=copy&tid=<?=$tid?>&code=<?=$rs2[code]?>&days=<?=$days?>&bstations=<?=$bstations?>"> 복사 </a></span><br>
				<span class="btn_pack small bold"><a href="javascript:drop2('<?=$rs2[id_no]?>','<?=$rs2[code]?>')"> 삭제 </a></span>
			</td>
		</tr>
		<tr>
			<td class="c">소인</td>
			<td class="c"><?=number_format($rs2[week2_1])?></td>
			<td class="c"><?=number_format($rs2[week2_2])?></td>
			<td class="c"><?=number_format($rs2[week2_3])?></td>
			<td class="c"><?=number_format($rs2[week2_4])?></td>
			<td class="c"><?=number_format($rs2[week2_5])?></td>
			<td class="c"><?=number_format($rs2[week2_6])?></td>
			<td class="c"><?=number_format($rs2[week2_7])?></td>
			<!-- <td class="c"><?=number_format($rs2[week_8])?></td> -->
		</tr>
	<?
		$prev = $rs2[week];
		$j++;
	}?>
	</table>
	</div>

	<?
	}
	?>
	<!--//결과-->


	<div style="height:50px"></div>



	<!--내용이 들어가는 곳 끝-->
	<iframe name="actarea" style="display:none;" title=""></iframe>

</body>
</html>