<?
include_once("../include/common_file.php");


####기초 정보
$sql = "select * from ez_tour where tid='$tid'";
$dbo->query($sql);
$rs=$dbo->next_record();
$PRICE_ADULT= number_format($rs[price_adult]);
$PRICE_CHILD= number_format($rs[price_child]);
$TOUR_NAME= $rs[subject];
$AIR= $rs[air_name];
$ctg1_arr = explode("-",$rs[category1]);
$ctg1 = $ctg1_arr[0];
$PRICE_MODE = 0;

If($ctg1=="3"){
	$PRICE_MODE = 1;
}


$MAX= $rs[tourlist_max];
$MIN= $rs[tourlist_min];
$STANDBY= $rs[tourlist_stand];
$OTHER= $rs[other_stand];
$FIX= $rs[fix_stand];

$filecode = substr(SELF,5,-4);
$table = "ez_tour_days";
$TITLE = "출발일설정 - " . $TOUR_NAME;


if($mode=="save"){

	$sql = "delete from ez_tour_calendar where tid=$tid and left(tour_date,7)='$tour_ym' ";
	$dbo->query($sql);

	//checkVar("",$sql);

	For($i=0; $i <count($tour_date);$i++){
		$j = $i+1;
		$status_ = "status_" . $j;
		$status = $$status_;

		$price_adult[$i] = str_replace(",","",$price_adult[$i]);
		$price_child[$i] = str_replace(",","",$price_child[$i]);

		$sql="
		   insert into ez_tour_calendar (
			  tid,
			  tour_date,
			  tour_max,
			  tour_min,
			  tour_standby,
			  tour_etc,
			  decide,
			  price_adult,
			  price_child,
			  status
		  ) values (
			  '$tid',
			  '$tour_date[$i]',
			  '$tour_max[$i]',
			  '$tour_min[$i]',
			  '$tour_standby[$i]',
			  '$tour_etc[$i]',
			  '$decide[$i]',
			  '$price_adult[$i]',
			  '$price_child[$i]',
			  '$status'
		)";

		If(
			  $tour_max[$i] ||
			  $tour_min[$i] ||
			  $tour_standby[$i] ||
			  $tour_etc[$i] ||
			  $decide[$i] ||
			  $status
		){
			$dbo->query($sql);
			//checkVar("",$sql);
		}
	}

	$sql = ($id_no)? $sqlModify : $sqlInsert;
	$dbo->query($sql);
	back();exit;

}


$year =($year)? $year : Date("Y");
$month =($month)? $month : Date("m");
$month = ceil($month);
?>
<?include("../top_min.html");?>
<script type="text/javascript" src="/cheditor/cheditor.js"></script>
<script language="JavaScript">
<!--
function chk_form(){
	var fm =document.fmData;

	if(confirm('저장하시겠습니까?')){
		fm.submit();
	}
}
//-->
</script>
<script type="text/javascript">
<!--
function mng_days(){
	location.href='pop_days.php?tid=<?=$tid?>';
}

function mng_days_detail(){
	location.href='pop_days02.php?tid=<?=$tid?>';
}

function radio_all(str){
	$("." + str).attr("checked","true");
}

function set_basic(){

	var val;

	var i = 0;
	 $(".basic").each(
		function(){
			if(this.checked){

				if(this.value=="tour_max") val = "<?=$MAX?>";
				else if(this.value=="tour_min") val = "<?=$MIN?>";
				else if(this.value=="tour_standby") val = "<?=$STANDBY?>";
				else if(this.value=="tour_etc") val = "<?=$OTHER?>";
				else if(this.value=="tour_fix") val = "<?=$FIX?>";
				else if(this.value=="price_adult") val = "<?=$PRICE_ADULT?>";
				else if(this.value=="price_child") val = "<?=$PRICE_CHILD?>";

				$("."+this.value).val(val);
				$(this).attr("checked",false);
			}
		}
	);

}

function set_clear(){
	var val;
	var i = 0;

	if(confirm('화면에서 내용을 지우시겠습니까?\n\n저장하지 않으면 변경되지는 않습니다.')){

		 $(".basic").each(
			function(){
				if(this.checked){

					if(this.value=="tour_max") val = "";
					else if(this.value=="tour_min") val = "";
					else if(this.value=="tour_standby") val = "";
					else if(this.value=="tour_etc") val = "";
					else if(this.value=="tour_fix") val = "";
					else if(this.value=="price_adult") val = "";
					else if(this.value=="price_child") val = "";

					$("."+this.value).val(val);
					$(this).attr("checked",false);

				}
			}
		);

	}
}

function set_basic_all(){

	var max = "<?=$MAX?>";
	var min = "<?=$MIN?>";
	var price_adult = "<?=$PRICE_ADULT?>";

	$(".tour_max").val(max);
	$(".tour_min").val(min);

	$(".price_adult").val(price_adult);
}

function set_clear_all(){

	if(confirm('화면에서 내용을 지우시겠습니까?\n\n저장하지 않으면 변경되지는 않습니다.')){
		$(".tour_max").val("");
		$(".tour_min").val("");
		$(".radio_1").attr("checked","true");

		$(".price_adult").val('');
	}
}


function prev_month(){
	var prev_year = "<?=date("Y",mktime(0,0,0,$month-1,1,$year))?>";
	var prev_month = "<?=ceil(date("m",mktime(0,0,0,$month-1,1,$year)))?>";

	var fm  =document.fmMonth;
	fm.year.value = prev_year;
	fm.month.value = prev_month;

	fm.submit();
}

function next_month(){
	var next_year = "<?=date("Y",mktime(0,0,0,$month+1,1,$year))?>";
	var next_month = "<?=ceil(date("m",mktime(0,0,0,$month+1,1,$year)))?>";

	var fm  =document.fmMonth;
	fm.year.value = next_year;
	fm.month.value = next_month;

	fm.submit();
}

function frm_cancel(){
	if(confirm('입력한 내용을 취소하고 원래 상태로 돌아가시겠습니까?')){
		document.fmData.reset();
	}
}
//-->
</script>
<style type="text/css">
body{padding:0 10px;}	
</style>


	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
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


	<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
	  <form name="fmMonth">
	  <input type="hidden" name="tid" value="<?=$tid?>">
	  <tr>
		<td>
			<select name="year">
				<?=option_int2(Date("Y")+1,Date("Y")-1,1,$year)?>
			</select>
			<select name="month">
				<?=option_int(1,12,1,$month)?>
			</select>

			<span class="btn_pack medium bold"><a href="javascript:document.fmMonth.submit()"> 이동 </a></span>&nbsp;
			<span class="btn_pack medium bold"><a href="javascript:prev_month()"> 이전달 </a></span>&nbsp;
			<span class="btn_pack medium bold"><a href="javascript:next_month()"> 다음달 </a></span>&nbsp;
		<!-- 	|
			<span class="btn_pack medium bold"><a href="javascript:set_basic()"> 기본값 </a></span>&nbsp;
			<span class="btn_pack medium bold"><a href="javascript:set_clear()"> 지우기 </a></span>&nbsp; -->
			|
			<span class="btn_pack medium bold"><a href="javascript:set_basic_all()"> 모두 기본값으로  </a></span>&nbsp;
			<span class="btn_pack medium bold"><a href="javascript:set_clear_all()"> 모두 지우기 </a></span>&nbsp;

			|

			<span class="btn_pack medium bold"><a href="javascript:chk_form()"> 저장 </a></span>&nbsp;
			<span class="btn_pack medium bold"><a href="javascript:frm_cancel()"> 취소 </a></span>


		</td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	  </form>
	</table>


    <table border="0" cellspacing="1" cellpadding="3" width="100%" align="center">
		<form name="fmData" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="tid" value='<?=$tid?>'>
		<input type="hidden" name="tour_ym" value='<?=$year?>/<?=num2($month)?>'>

		<tr><td colspan="12"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr>
          <th class="subject c">날짜</th>
          <th class="subject c">요일</th>
          <th class="subject c hide"><label><input type="checkbox" name="tour_max" class="basic" value="tour_max">전체좌석</label></th>
          <th class="subject c hide">잔여좌석</th>
          <th class="subject c hide"><label><input type="checkbox" name="tour_min" class="basic" value="tour_min">최소</label></th>

          <!-- <th class="subject c"><label><input type="checkbox" name="air" class="basic" value="air">이용항공</label></th> -->
          <th class="subject c"><label><input type="checkbox" name="price_adult" class="basic" value="price_adult">가격</label></th>

          <th class="subject c" style="letter-spacing:-1px">
			<label><input type='radio' name='status' value='radio_1' onclick="radio_all(this.value)" class="radio_1"/> 출발안함</label>
			<label><input type='radio' name='status' value='radio_2' onclick="radio_all(this.value)" class="radio_2"/> 예약가능</label>
			<label><input type='radio' name='status' value='radio_3' onclick="radio_all(this.value)" class="radio_3"/> 출발확정</label>
			<label><input type='radio' name='status' value='radio_4' onclick="radio_all(this.value)" class="radio_4"/> 예약마감</label>
		  </th>
        </tr>
		<tr><td colspan="12" class="tblLine"></td></tr>
<?
$i=1;
while(checkdate($month,$i,$year) && $i<40){
	$date  =mktime(0,0,0,$month,$i,$year);
	$week = Date("w",$date);
	If(!$week) $color = "#ffd9d9";elseIf($week==6) $color = "#d9ecff";	else $color = "#fff";
	$tour_date = Date("Y/m/d",$date);
	$sql = "select * from ez_tour_calendar where tid=$tid and tour_date = '$tour_date'";
	$dbo->query($sql);
	$rs=$dbo->next_record();

?>
        <tr>
          <td class="c" style="background-color:<?=$color?>"><?=$i?><input type="hidden" name="tour_date[]" value="<?=Date("Y/m/d",$date)?>"></td>
          <td class="c" style="background-color:<?=$color?>"><?=convertWeek($week)?></td>
          <td class="c hide" style="background-color:<?=$color?>"><input class="box numberic tour_max" type="text" name="tour_max[]" value="<?=$rs[tour_max]?>" size="5" maxlength="5"/></td>
          <td class="c hide" style="background-color:<?=$color?>"><?=$rs[tour_max]-get_selling($tid,$tour_date)?></td>
          <td class="c hide" style="background-color:<?=$color?>"><input class="box numberic tour_min" type="text" name="tour_min[]" value="<?=$rs[tour_min]?>" size="5" maxlength="5"/></td>
          <!-- <td class="c" style="background-color:<?=$color?>"><input class="box air" type="text" name="air[]" value="<?=($rs[air])?>" size="13" maxlength="20"/></td> -->          
          <td class="c" style="background-color:<?=$color?>"><input class="box numberic price_adult" type="text" name="price_adult[]" value="<?=number_format($rs[price_adult])?>" size="13" maxlength="20"/></td>

          <td class="c" style="background-color:<?=$color?>;letter-spacing:-1px"><?=radio($TOUR_DAY_STATUS,$TOUR_DAY_STATUS_VAL,$rs[status],"status_${i}")?></td>
        </tr>
        <tr><td colspan="12" class="tblLine"></td></tr>
<?
	$i++;
}

?>

        <tr><td colspan="12" class="tblLine"></td></tr>
		<tr>
		  <td colspan="12">
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="150" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td><span class="btn_pack medium bold"><a href="javascript:chk_form()"> 저장 </a></span></td>
					<td><span class="btn_pack medium bold"><a href="javascript:self.close()"> 창닫기 </a></span></td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>
		  </td>
        </tr>

        <tr>
	  <td colspan="12" height=20>
	  </td>
        </tr>
	</form>
	</table>



	<!--내용이 들어가는 곳 끝-->
	<iframe name="actarea" style="display:none;"></iframe>


</body>
</html>