<?
include_once("../include/common_file.php");

#### Menu
$filecode = substr(SELF,5,-4);
$TITLE = "공휴일설정";
$MENU = "tour";


#### operation
$filename= "../../public/inc/holiday.inc";

if ($mode=="save"){

		$fp=fopen($filename, "w");	//파일 쓰기모드로 열기, 파일이 있다면 overwirte

		$config ="<?\n";
		$config .="##-------------------------------------------\n";
		$config .="##주의! (이 파일을 수동으로 조작하지 마세요.)\n";
		$config .="##-------------------------------------------\n";

		$config .="\$HOLIDAY='$holiday';\n";
		$config .="?";
		$config .=">";

		if(!fwrite($fp,$config)){
			error("환경설정파일을 생성하던 중 예기치 못한 에러가 발생하였습니다. 관리자에게 문의하여 주시기 바랍니다.");exit;
		}
		fclose($fp);

		msggo("저장하였습니다.","?");
}else{
	@include($filename);
}
?>
<?include("../top.html");?>
<script language="JavaScript" src="../../include/function.js"></script>

<script language="JavaScript">
<!--
function chkColor(fm,name){
	var dColor;
	var str = document.fmData.holiday_tmp.value;

	name_old = 	name.substring(0,name.length-5);

	switch(str){
		case "초기상태":
			var dColor = "gray";

			var holiday = document.fmData.holiday.value;

			name2 =  '@' + name + ';' ;
			name_old2 =  '@' + name_old + ';' ;
			//alert(name2 + ',' +  name_old2);

			if(holiday.indexOf(name2,0)>0){
			//alert('있어서 지운다');
				var start = holiday.indexOf(name2,0)-2;
				var length = name.length+4;
				document.fmData.holiday.value =holiday.substr(0,start) + holiday.substr(start+length);
			}
			if(holiday.indexOf(name_old2,0)>0){
			//alert('있어서 지운다');
				var start = holiday.indexOf(name_old2,0)-2;
				var length = name.length+4;
				document.fmData.holiday.value =holiday.substr(0,start) + holiday.substr(start+length);
			}
			break;
	case "공휴일":
			var dColor = "#ff0066";
			var holiday = document.fmData.holiday.value;

			name2 =  '@' + name + ';' ;
			if(holiday.indexOf(name2,0)>0){
				alert('해당 날짜가 이미 설정되어 있습니다.\n바꾸시려면 초기상태로 바꾸신 후 다시 시도하세요');
				return;
			}

			name =  ';A@' + name + ';' ;
			document.fmData.holiday.value =(holiday.indexOf(name2,0)<1)? holiday+name : holiday;
			//document.fmData.holiday.value =(holiday.indexOf(name,0)<1)? holiday + ';A@' + name + ';' : holiday;
			break;

	}

	fm.style.color=dColor;
}
//-->
</script>
<style type="text/css">
#tbl_col {border:1px solid #ccc;border-collapse: collapse;}
#tbl_col th{background-color:#efeff8}
#tbl_col th,#tbl_col td {padding: 3px;border-right: 1px solid #ccc;border-bottom: 1px solid #ccc;}
</style>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
	  </tr>
	  <tr>
		<td> </td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	</table>


	<!--내용이 들어가는 곳 시작-->

	<table width="100%">
	<tr>
		<td valign="top">

		<!--관리/등록/삭제-->
		<table border="0" cellspacing="1" cellpadding="3" class="viewWidth" width="850">
			<form name=fmData  method=post enctype="multipart/form-data">
			<input type=hidden name=mode value='save'>
			<input type="hidden" name="holiday" value="<?=$HOLIDAY?>" size=50>
		<tr>
          <td>

			<label><input type=radio name='holiday_tmp_r' value='초기상태' onClick="document.fmData.holiday_tmp.value=this.value"><font color="000000">초기상태</font></label>,
			<label><input type=radio name='holiday_tmp_r' value='공휴일' onClick="document.fmData.holiday_tmp.value=this.value" checked><font color="ff0066">공휴일</font></label>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			( 현재 상태 : <input type=text name='holiday_tmp' value="공휴일" readonly style="border:0;font-family:verdana;font-size:9pt;padding-top:3px" size="5"> )
			<br>
			<font color='FF6600'>Help</font> <font color="gray">: 왼쪽 Radio 버튼 선택 후 날짜를 클릭하세요.</font>

			<br>

		    <fieldset style="padding: 0; width: 850px; height: 280px; overflow:auto; border:0;" >
			<table><tr valign=top align=left><td>
<?
$s_month_=mktime(0,0,0,1,date("d"),date("Y"));
$s_month=date("m",$s_month_);

$i = 0;
while($i < 12){


	$time =mktime(date("H"),date("i"),date("s"),date("m",$s_month_)+$i,date("d",$s_month_),date("Y",$s_month_));
	$year =  date("Y",$time);
	$month = date("m",$time);

	$inputY = $year;
	$inputM = $month;
	$inputM = $s_month + $i;

	if($inputM >12) $inputM= $inputM-12;

	$tmp_inputm=strlen($inputM);
	if($tmp_inputm==1)	$inputM="0".$inputM;

	$totaldays = get_totaldays($inputY,$inputM);
	echo "</td><td>";
	showCalendar($inputY,$inputM,$totaldays,$gyear,$gmonth,$gday);
	echo "</td><td>";
	$i++;
}

			?>

			</td></tr></table>
			</fieldset>


			  </td>
			</tr>
			<tr><td colspan="2" class="tblLine"></td></tr>

			<tr>
			  <td colspan=10>
				  <br>
				  <!-- Button Begin---------------------------------------------->
				  <table border="0" width="130" cellspacing="0" cellpadding="0" align="right">
					<tr align="right">
						<td><span class="btn_pack medium bold"><a href="#" onClick="document.fmData.submit()"> 저장 </a></span></td>
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

		<!--//관리/등록/삭제-->

		</td>
	</tr>
	</table>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>

<?
function get_totaldays($year,$month) {

	$maxdate = date(t, mktime(0, 0, 0, $month, 1, $year));
	return $maxdate;
/*
      $date = 1;
      while(checkdate($month,$date,$year)) {
	  $date++;
      }

      $date--;
      return $date;
      */
}

//오늘 날짜 구하기
if(!$year){
	$timeinfo=getdate(time());
	$year=$timeinfo["year"];
	$month=$timeinfo["mon"];
	$day=$timeinfo["mday"]+2;
}
$timeinfo=getdate(time());
$gyear=$timeinfo["year"];
$gmonth=$timeinfo["mon"];
$gday=$timeinfo["mday"]+2;



// 해당연월의 달력을 출력한다.

function showCalendar($year,$month,$total_days,$gyear,$gmonth,$gday)
{
  global $mode;
  global $HOLIDAY;

  $holiday = substr($HOLIDAY,1);
  $holiday_array = explode(";",$holiday);

  $first_day = date('w', mktime(0,0,0,$month,1,$year));

  //네비게이션
  $prev_year=$year-1;
  $next_year=$year+1;
  $prev_month=$month-1;
  $next_month=$month+1;

  echo("<table width=175 height=250 id='tbl_col'  align=\"center\">\n");
  echo("<tr align=center><th colspan=7 height=35r><font color=\"#333366\">\n");
  echo("${year}年");
  echo(" ${month}月");
  echo("</font></th></tr>\n");
  echo("<tr height='35'  height=30>\n");
  echo("   <th width=25 align=center><font color=red>일</font></th>\n");
  echo("   <th width=25 align=center>월</th>\n");
  echo("   <th width=25 align=center>화</th>\n");
  echo("   <th width=25 align=center>수</th>\n");
  echo("   <th width=25 align=center>목</th>\n");
  echo("   <th width=25 align=center>금</th>\n");
  echo("   <th width=25 align=center><font color=blue>토</a></th>\n");
  echo("</TR>\n");
  echo("<tr height=30>\n");


  $col = 0;
  for($i = 0; $i < $first_day; $i++) {
	 echo("   <TD>&nbsp;</TD>\n");
	 $col++;
  }

  for($j = 1; $j <= $total_days; $j++) {
	$style='';
	for($k=0; $k < count($holiday_array); $k++){
		if(substr($holiday_array[$k],2)==${month}.'@'.${j}.'@'.${year}){
			switch(substr($holiday_array[$k],0,1)){
				case "S": $style="color:0167A0";break;
				case "A": $style="color:ff0066";break;
				case "E": $style="color:A7A7AA";break;
				default: $style="color:0647A0";
			}

		}
	}
	$style=($style)? $style : "color:gray";

	echo("   <td  align=center><input type=button style='font-family:verdana;rwidth:15px;height:15px;background-color:FFFFFF;border:0;$style' value='$j' onFocus='blur(this)'  onClick=chkColor(this,'${month}@${j}@${year}') name=${month}${j}${year}></td>\n");

	 $col++;

	 if($col == 7) {
		echo("</TR>\n");
	if($j != $total_days) {
	   echo("<tr>\n");
	}
	$col = 0;
	 }
  }

  while($col > 0 && $col < 7) {
	 echo("   <TD>&nbsp;</TD>\n");
 $col++;
  }

  echo("</TR>\n");
  echo("</TABLE>\n");

}//달력 함수 끝
?>