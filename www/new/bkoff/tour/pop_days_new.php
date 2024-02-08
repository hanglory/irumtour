<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour_days_new";
$TITLE = "일정설정";



if($mode=="save"){

	$time_arr= explode(":",$timetable);
	If(!$time_arr[0]) $time_arr[0]=0;
	If(!$time_arr[1]) $time_arr[1]=0;
	$timetable = setInt2Str($time_arr[0]) . ":" . setInt2Str($time_arr[1]);
	$timetable_seq=($timetable_seq)?$timetable_seq:1;
	If($timetable=="0:0"){
		$timetable = "00:00";
	}else{
		$timetable = substr($timetable,0,5);
	}

	$sql = "select timetable_seq +1 as seq from $table where tid=$tid and id_no<>'$id_no' and days='$days' and left(timetable,5)='$timetable' order by timetable_seq desc";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	If($rs[seq]) $timetable_seq  =$rs[seq];

	$timetable .= " " . $timetable_seq;

	If($EASEPLUS2){
		checkVar($rs[seq],$sql);
		exit;
	}


	If(!$area) $area = "&nbsp;";

	$sqlInsert="
	   insert into $table (
		  tid,
		  area,
		  days,
		  bus,
		  timetable,
		  timetable_txt,
		  timetable_seq,
		  time_valign,
		  etc,
		  content
	  ) values (
		  '$tid',
		  '$area',
		  '$days',
		  '$bus',
		  '$timetable',
		  '$timetable_txt',
		  '$timetable_seq',
		  '$time_valign',
		  '$etc',
		  '$content'
	)";


	$sqlModify="
	   update $table set
		  days = '$days',
		  area = '$area',
		  bus = '$bus',
		  timetable = '$timetable',
		  timetable_txt = '$timetable_txt',
		  timetable_seq = '$timetable_seq',
		  time_valign = '$time_valign',
		  etc = '$etc',
		  content = '$content'
	   where id_no='$id_no'
	";

	$sql = ($id_no)? $sqlModify : $sqlInsert;
	$dbo->query($sql);
	redirect2("pop_days02.php?tid=$tid");

}elseif($mode=="drop"){

	$sql = "delete from $table where id_no=$id_no";
	$dbo->query($sql);
	back();
	exit;

}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs=$dbo->next_record();

	$rs[days] = ($rs[days])? $rs[days] : "1day";
	$rs[timetable] = substr($rs[timetable],0,5);

	//If($rs[timetable]=="00:00") $rs[timetable]="";
	$rs[timetable_seq]= ($rs[timetable_seq])?$rs[timetable_seq]:1;
	$rs[time_valign]=($rs[time_valign])?$rs[time_valign]:"top";
}

?>
<?include("../top_min.html");?>
<script type="text/javascript" src="/cheditor/cheditor.js"></script>
<script language="JavaScript">
function chk_form(){
	var fm =document.fmData;

	//if(check_blank(fm.timetable,'시간을',5)=='wrong'){return }

	oEditors.getById["content"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.

	try {
		elClickedObj.form.submit();
	} catch(e) {}

	if(document.getElementById("content").value==""){
		alert('본문을 입력하세요');
		return;
	}
	fm.submit();
}

function mng_days(){
	location.href='pop_days.php?tid=<?=$tid?>';
}

function mng_days_detail(){
	location.href='pop_days02.php?tid=<?=$tid?>';
}

$(function(){
	$(".only_num").keypress(function(event){
		if(event.which && (event.which < 48 || event.which > 57)){
			event.preventDefault();
		}
	});

});
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

    <table border="0" cellspacing="1" cellpadding="3" width="95%" align="center">
		<form name="fmData" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" id="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="tid" id="tid" value='<?=$tid?>'>

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>
	    <tr>
          <td  class="subject" width="10%">날짜</td>
          <td>
			<select name="days" id="days">
			<?=option_str("First day,1day,2day,3day,4day,5day,6day,7day,8day,9day,10day,Last day","0day,1day,2day,3day,4day,5day,6day,7day,8day,9day,10day,99day",$rs[days])?>
			</select>
			&nbsp;&nbsp;
			<b>지역</b>
			<input class="box" type="text" name="area" size="20" maxlength="20"   value="<?=$rs[area]?>">
          </td>
        </tr>
		<tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">교통</td>
          <td>
            <input class="box" type="text" name="bus" size="20" maxlength="20"   value="<?=$rs[bus]?>">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">시간</td>
          <td>
			<input class="box" type="text" name="timetable" id="timetable" size="5" maxlength="5" value="<?=$rs[timetable]?>" /> HH:ii 형식,예: 16:30
			(시간 순으로 정렬됩니다.)

			<span class="subject">시간 정렬</span>
			<select name="time_valign"><?=option_str("상단,중간,하단","top,middle,bottom",$rs[time_valign])?></select>

			<span class="subject">시간 대체 텍스트</span>
			<input class="box" style="text-align:center" type="text" name="timetable_txt" id="timetable_txt" size="6" maxlength="6" value="<?=$rs[timetable_txt]?>" />
			(시간 대신 표시됨. 예: "전일정")

			<input class="box" type="hidden" name="timetable_seq" id="timetable_seq" size="2" maxlength="2" value="<?=$rs[timetable_seq]?>" />
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td  class="subject">내용</td>
          <td>
			<textarea name="content" id="content" rows="10" cols="100" style="width:100%; height:300px;" class="box"><?=$rs[content]?></textarea>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td  class="subject">비고</td>
          <td>
		    <textarea name="etc" class="box" rows="3" cols="20"><?=$rs[etc]?></textarea>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

		<tr>
		  <td colspan=10>
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="230" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td><span class="btn_pack medium bold"><a href="javascript:chk_form()"> 저장 </a></span></td>
					<td><span class="btn_pack medium bold"><a href="javascript:mng_days_detail()"> 일정보기 </a></span></td>
					<td><span class="btn_pack medium bold"><a href="javascript:self.close()"> 창닫기 </a></span></td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>
		  </td>
        </tr>

        <tr>
		  <td colspan=10 height=20>

				<br />
				<div class="msg">
					다른 사이트에서 데이터를 긁어다 붙이는 경우 그 사이트에만 해당하는 불필요한 코드(CSS,Javascirpt 코드)들이 함께 따라오게 됩니다.<br/>
					이 경우 홈페이지의 틀을 깨뜨리거나 예기치 않은 영향을 줄 수 있습니다.<br>
					문장, 또는 이미지 단위로 조금씩 복사하는 방식 (또는 캡춰하여 이미지로 올리는 방식)으로 사용하시고 꼭 홈페이지에 제대로 표시되는지 확인해 주세요.
				</div>
		  </td>
        </tr>
	</form>
	</table>

	<p style="height:30px"></p>

	<!--내용이 들어가는 곳 끝-->
	<iframe name="actarea" style="display:none;"></iframe>

</body>
</html>

<?
$rs[content] = nl2br(strip_tags($rs[content]));
echo $rs[content];
?>