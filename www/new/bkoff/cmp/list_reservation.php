<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"고객별예약정보관리대장");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_reservation";
$MENU = "cmp_basic";
$LEFT_HIDDEN="1";
$TITLE = "예약정보";



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
	$filter .=" and $target like '%$keyword%' ";
	$best="";	 //배너 select 초기화
	$findMode=1;
}


/*
parter_i(이룸투어의 파트너)
partner_g(partner_a의 직원)
는 자기 것만 볼 수 있도록
*/
if(strstr("partner_i,partner_g",$_SESSION["sessLogin"]["staff_type"])){
	$filter.=" and main_staff like '%(".$_SESSION["sessLogin"]["id"].")'";      
}



$dtype = ($dtype)? $dtype : "d_date";
if(!$date_s) $date_s=date("Y/m/d",strtotime(date("Y/m/d")." -12 month"));
if(!$date_e) $date_e=date("Y/m/d",strtotime(date("Y/m/d")." +12 month"));
$filter.=" and a.$dtype >='$date_s'";
$filter.=" and a.$dtype <='$date_e'";



#query
$sql_1 = "
	select 
			a.*,
			(select count(*) from cmp_people where code=a.code and bit_cancel=1) as bit_cancel
		from $table as a
	where 
        cp_id='$CP_ID'
        $filter
	";			//자료수
$sql_2 = $sql_1 . " order by tour_date desc,id_no desc limit  $start, $view_row";
//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27") checkVar("",$sql_2);

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
$selectTxt = "대표자,경로,담당자,서브 담당자,예약일,출국일,귀국일,상품명,핸드폰";
$selectValue ="name,view_path,main_staff,sub_staff,tour_date,d_date,r_date,subject,phone";
if($user_id=="tester" || $user_id=="test"){
  $selectTxt = "대표자,경로,담당자,예약일,출국일,귀국일,상품명,핸드폰";
  $selectValue ="name,view_path,main_staff,tour_date,d_date,r_date,subject,phone";  
}


#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1&date_s=$date_s&date_e=$date_e&dtype=$dtype";
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


function copy(id_no){
	actarea.location.href="list_<?=$filecode?>_copy.php?id_no="+id_no;
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


    
    <select name="dtype">
        <?=option_str("출국일,귀국일,예약일","d_date,r_date,reg_date",$dtype)?>
    </select>

    <input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?=$date_s?>" class="box c dateinput">
    ~
    <input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?=$date_e?>" class="box c dateinput">
    



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
		<th class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></th>
		<th class="subject" >대표자명</th>
        <th class="subject" >출발지</th>
		<th class="subject" >핸드폰</th>
		<th class="subject" >경로</th>
		<th class="subject" >담당자</th>
		<th class="subject" >예약일</th>
		<th class="subject" >출국일</th>
		<th class="subject" >귀국일</th>
		<th class="subject" >총인원</th>
		<th class="subject" >상품명</th>
		<th class="subject" >거래처</th>
		<th class="subject" >판매가</th>
		<th class="subject" ></th>
		</tr>
<?
//삭제 권한
$bit_drop_power=0;
if(
    $_SESSION["sessLogin"]["staff_type"] == "ceo" ||
    $_SESSION["sessLogin"]["staff_type"] == "partner_a"
) $bit_drop_power=1;



if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//if(strstr("@14.37.242.84@221.154.216.133@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	$sql2 = $dbo2->query("select sum(price) as total ,count(bit) as cnt from cmp_people where bit=1 and code=$rs[code]");
	$rs2=$dbo2->next_record();
	$price = $rs2[total];
	$cnt = $rs2[cnt];

	$arr = explode("(",$rs[main_staff]);


	$sql3="select * from cmp_golf where id_no=$rs[golf_id_no]";
	$dbo3->query($sql3);
	$rs3=$dbo3->next_record();

	$css = ($rs[bit_cancel])? "color:red":"";


    //삭제권한
    if(strstr($rs[main_staff],"(".$_SESSION["sessLogin"]["id"].")")) $bit_drop_power=1;
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="35"><?if($bit_drop_power){?><input type="checkbox" name="check[]" value="<?=$rs[code];?>"><?}?></td>
	      <td ><a href="javascript:newWin('view_<?=$filecode?>.php?id_no=<?=$rs[id_no]?>',1200,650,1,1,'','reservation')" style="<?=$css?>"><?=$rs[name]?></a></td>
	      <td style="<?=$css?>"><?=$rs[point_dep]?></td>
          <td style="<?=$css?>"><?=$rs[phone]?></td>
	      <td style="<?=$css?>"><?=$rs[view_path]?></td>
	      <td style="<?=$css?>"><?=$rs[main_staff]?></td>
	      <td style="<?=$css?>"><?=$rs[tour_date]?></td>
	      <td style="<?=$css?>"><?=$rs[d_date]?></td>
	      <td style="<?=$css?>"><?=$rs[r_date]?></td>
	      <td style="<?=$css?>"><?=nf($rs[people])?></td>
	      <td style="l pl10"><?=($rs[subject])?$rs[subject]:$rs[golf_name]?></td>
	      <td style="<?=$css?>"><?=$rs[partner]?></td>
	      <td style="<?=$css?>"><?=nf($price)?></td>
	      <td><span class="btn_pack medium bold"><a href="javascript:copy(<?=$rs[id_no];?>)"> 복사 </a></span></td>
	    </tr>
<?
	$num--;
}
?>
	</table>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
        <tr>
		  <td colspan="12">

		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
			 <tr>
			  <td width="60%" align="left">

			  </td>
			  <td align="right">
				<?if($_SESSION["sessLogin"]["staff_type"] == "ceo"){?>
				<span class="btn_pack medium bold"><a href="javascript:newWin('read_<?=$filecode?>.php?<?=$sessLink?>',screen.width-100,700,1,1,'','reservation')"> 전체 자료 </a></span>&nbsp;
				<?}?>
			    

			    <?if(strstr($_SESSION["sessLogin"]["proof"],"엑셀다운로드")){?>
				<span class="btn_pack medium bold"><a href="list_reservation_excel.php?target=<?=$target?>&keyword=<?=$keyword?>"> 엑셀 다운로드 </a></span>&nbsp;
				<?}?>
				<span class="btn_pack medium bold"><a href="javascript:newWin('view_<?=$filecode?>.php?ctg1=<?=$ctg1?>',1200,650,1,1,'','reservation')"> 등록 </a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span>
			  </td>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>

		  </td>
        </tr>


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
