<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_air";
$TITLE = "항공정보 선택(API)";
$key = "0g7LgnItlv2wg%2Bx%2BKcBPQKYOkBRi1%2Bt94uaZFjbLDFaX6zKtUBy5CqB12z6yLidx2kNdDCiany4gEc2ZC644WQ%3D%3D";
$test_mode=0;

###action
if($mode=="air"){

	$air_arr = explode("{@}",$code);

	$nation=$air_arr[0];
	$city=$air_arr[1];
	$airport_in=$air_arr[2];
	$airport_out=$air_arr[3];
	$d_air=$air_arr[4];
	$d_air_no=$air_arr[5];
	$d_time_s=$air_arr[6];
	$d_wday=$air_arr[7];
	$r_air_no=$air_arr[8];
	$r_time_s=$air_arr[9];
	$r_wday=$air_arr[10];
	$tour_date=$air_arr[11];

	$sql="
	   insert into cmp_air (
	      nation,
	      city,
	      d_air,
	      d_air_no,
	      d_time_s,
	      d_time_e,
	      d_air_no_m,
	      d_air_no_m2,
	      d_time_s_m,
	      d_time_e_m,
	      d_wday,
	      r_air_no,
	      r_time_s,
	      r_time_e,
	      r_add1,
	      r_add2,
	      r_wday,
	      airport_in,
	      airport_out,
	      airport_counter,
	      airport_place,
	      staff,
	      code
	  ) values (
	      '$nation',
	      '$city',
	      '$d_air',
	      '$d_air_no',
	      '$d_time_s',
	      '$d_time_e',
	      '$d_air_no_m',
	      '$d_air_no_m2',
	      '$d_time_s_m',
	      '$d_time_e_m',	      
	      '$d_wday',
	      '$r_air_no',
	      '$r_time_s',
	      '$r_time_e',
	      '$r_add1',
	      '$r_add2',
	      '$r_wday',
	      '$airport_in',
	      '$airport_out',
	      '$airport_counter',
	      '$airport_place',
	      '$staff',
	      '$code'
	)";
	

	$sql2 = "select * from cmp_air where code='$code'";
	$dbo2->query($sql2);
	$rs2=$dbo2->next_record();
	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar($rs2[id_no].mysql_error(),$sql2);}		
	if(!$rs2[id_no]) $dbo->query($sql);


	$sql = "select * from cmp_air where code='$code'";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar($rs[id_no].mysql_error(),$sql);}		

	if($bit=="d" && $rs[id_no]){//출국
		echo "
			<script>
				parent.set_air('$bit','$rs[id_no]','$rs[d_air_no]','$rs[d_time_s]','','','','');
			</script>
		";
	}else{//귀국
		echo "
			<script>
				parent.set_air('$bit','$rs[id_no]','$rs[r_air_no]','$rs[r_time_s]','','','','');
			</script>
		";
	}


	exit;
}


####API
if($golf_id_no){
	$sql = "select * from cmp_golf where id_no=$golf_id_no";
	list($rows) = $dbo->query($sql);
	$rs=$dbo->next_record();
	if($rs[cityCode1]) $city_code = $rs[cityCode1];
	if($rs[cityCode2]) $city_code2 = $rs[cityCode2]; 
}

if($test_mode) $tour_date="2015/10/05";
if($tour_date){

	$tour_date2 = rnf($tour_date);	
	$ch = curl_init();
	$url = 'http://openapi.airport.co.kr/service/rest/FlightScheduleList/getIflightScheduleList'; /*URL*/
	$queryParams = '?' . urlencode('ServiceKey') . '='.$key; /*Service Key*/
	
	if(!$test_mode) $queryParams .= '&' . urlencode('schDate') . '=' . urlencode($tour_date2); /**/
	if(!$test_mode) $queryParams .= '&' . urlencode('schDeptCityCode') . '=' . urlencode($city_code); /**/
	if(!$test_mode) $queryParams .= '&' . urlencode('schArrvCityCode') . '=' . urlencode($city_code2); /**/

	if($test_mode) $queryParams .= '&' . urlencode('schDate') . '=' . urlencode('20151005'); /**/
	if($test_mode) $queryParams .= '&' . urlencode('schDeptCityCode') . '=' . urlencode('GMP'); /**/
	if($test_mode) $queryParams .= '&' . urlencode('schArrvCityCode') . '=' . urlencode('HND'); /**/

	$queryParams .= '&' . urlencode('pageNo') . '=' . urlencode($page); /**/
	$queryParams .= '&' . urlencode('serviceKey') . '=' . urlencode($key); /**/

	curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$response = curl_exec($ch);
	curl_close($ch);

	if($_SERVER["REMOTE_ADDR"]=="1106.246.54.27"){
		checkVar("tour_date2",$tour_date2);
		checkVar("city_code",$city_code);
		checkVar("city_code2",$city_code2);
		var_dump($response);
	}


	$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA); 
	$json = json_encode($xml); 
	$arr = json_decode($json,TRUE);

	$page = $arr[body][pageNo];
	//checkVar("page",$page);
	$totalCount = $arr[body][totalCount];
	//checkVar("totalCount",$totalCount);	
	$numOfRows = $arr[body][numOfRows];
	//checkVar("numOfRows",$numOfRows);
	$total_page = ceil($totalCount / $numOfRows);
	//checkVar("total_page",$total_page);
	$row_search = $totalCount;
}


#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&tour_date=$tour_date&city_code=$city_code&city_code2=$city_code2&golf_id_no=$golf_id_no&d_date=$d_date&r_date=$r_date";
$sessLink = "page=$page&" . $link;

?>
<?include("../top_min.html");?>
<script type="text/javascript">
<!--
function find_date(fdtype,tour_date){
	var fm = document.fmSearch;
	fm.fdtype.value=fdtype;
	if(tour_date!="") fm.tour_date.value=tour_date;
	fm.submit();
}

function set_air(bit,id_no,air_no,air_time1,air_time2,air_no_m,air_time1_m,air_time2_m){
	var str="";

	if(bit=="d"){

		opener.$("#d_air_id_no").val(id_no);
		opener.$("#d_air_no").val(air_no);
		opener.$("#d_air_time1").val(air_time1);
		opener.$("#d_air_time2").val(air_time2);

		opener.$("#d_air_no_m").val(air_no_m);
		opener.$("#d_air_time1_m").val(air_time1_m);
		opener.$("#d_air_time2_m").val(air_time2_m);		

		str ="출국편명 : " + air_no;
		str +=" (출발시간 : " + air_time1 ;
		str +=")";
		//str +=" / 도착시간 : " + air_time2 +")";

		if(air_no_m!=""){
			str +=" - 국내선으로 이동 " + air_no_m;
			str +=" (출발시간 : " + air_time1_m ;
			str +=")";			
			//str +=" / 도착시간 : " + air_time2_m +")";			
		}

		opener.document.getElementById('air_info').innerHTML = str;
		alert("출국편 정보가 선택되었습니다.");
	}else{
		opener.$("#r_air_id_no").val(id_no);
		opener.$("#r_air_no").val(air_no);
		opener.$("#r_air_time1").val(air_time1);
		opener.$("#r_air_time2").val(air_time2);

		str ="귀국편명 : " + air_no;
		str +=" (출발시간 : " + air_time1 ;
		str +=")";
		//str +=" / 도착시간 : " + air_time2 +")";
		opener.document.getElementById('air_info2').innerHTML = str;
		alert("귀국편 정보가 선택되었습니다.");
		self.close();
	}


	//self.close();
}

function set_air_api(bit,code){
	var url = "<?=SELF?>?mode=air";
	url+="&bit="+bit;
	url+="&code="+code;
	actarea.location.href=url;
}
//-->
</script>
<script language="JavaScript">

</script>

<div style="padding:0 10px 0 10px">

	<table width="97%" border="0" cellspacing="0" cellpadding="0">
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
    <table border="0" cellspacing="0" cellpadding="3" width="97%" id="tbl_list">
	<form name="fmSearch" method="get" action="<?=SELF?>">
	<input type="hidden" name='golf_id_no' value="<?=$golf_id_no?>">
	<input type="hidden" name='d_date' value="<?=$d_date?>">
	<input type="hidden" name='r_date' value="<?=$r_date?>">
	<input type="hidden" name='fdtype' value="">

	<tr height="22">
	<td><font color="#666666">* <?=($status)?> 자료수: <?=nf($row_search)?>개 <?if(!$seq_mode){?>{ <?=nf($total_page)?> page /  <?=nf($page)?> page }<?}?></font></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>
  

	<?
	$AIRPORT_KEY="";
	$AIRPORT_VAL="";
	$sql = "select * from cmp_air_airport order by cityKor asc";
	$dbo->query($sql);
	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql);}
	while($rs=$dbo->next_record()){
		$AIRPORT_KEY.=",".$rs[cityCode];
		$AIRPORT_VAL.=",$rs[cityKor] ($rs[cityCode])";
	}
	?>
	<select name="city_code" class='select' style="width:150px">
	<?=option_str("출발 도시 선택".$AIRPORT_VAL,$AIRPORT_KEY,$city_code)?>
	</select>	

	<select name="city_code2" class='select' style="width:150px">
	<?=option_str("도착 도시 선택".$AIRPORT_VAL,$AIRPORT_KEY,$city_code2)?>
	</select>		

	<input type="text" name="tour_date" size="11" maxlength="10" class="box c dateinput" readonly placeholder="날짜" value="<?=$tour_date?>">

	<input class=button type="button" value=" 검 색 " onFocus='blur(this)' onclick="find_date('','')">
	<?if($d_date){?><input class="button" type="button" value="출발일로검색" onFocus='blur(this)' onclick="find_date('d','<?=$d_date?>')"><?}?>
	<?if($r_date){?><input class="button" type="button" value="귀국일로검색" onFocus='blur(this)' onclick="find_date('r','<?=$r_date?>')"><?}?>

	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>

    <table border="0" cellspacing="0" cellpadding="3" width="97%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" rowspan="2">구분</th>
		<th class="subject" rowspan="2">도시</th>
		<th class="subject" rowspan="2">국내공항</th>
		<th class="subject" rowspan="2">항공사명</th>
		<th class="subject" colspan="3">출발</th>
		<th class="subject" rowspan="2">출발선택</th>
		<th class="subject" colspan="3">귀국</th>
		<th class="subject" rowspan="2">귀국선택</th>
		</tr>
	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" >항공편</th>
		<th class="subject" >출발시간</th>
		<th class="subject" >출발요일</th>
		<th class="subject" >항공편</th>
		<th class="subject" >출발시간</th>
		<th class="subject" >출발요일</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}


//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql_2);


$i=0;
while(@list($key,$val)=each($arr[body][items][item])){

	$i++;	
	//checkVar($key,$val);

	$airlineKorean="";
	$airport="";
	$city="";
	$internationalEddate="";
	$internationalFri="";
	$internationalIoType="";
	$internationalMon="";
	$internationalNum="";
	$internationalSat="";
	$internationalStdate="";
	$internationalSun="";
	$internationalThu="";
	$internationalTime="";
	$internationalTue="";
	$internationalWed="";

	while(list($key2,$val2)=each($val)){
		//checkVar($key2,$val2);

		if($key2=="airlineKorean") $airlineKorean = $val2;
		elseif($key2=="airport") $airport=$val2;
		elseif($key2=="city") $city=$val2;
		elseif($key2=="internationalEddate") $internationalEddate=$val2;
		elseif($key2=="internationalFri") $internationalFri=$val2;
		elseif($key2=="internationalIoType") $internationalIoType=$val2;
		elseif($key2=="internationalMon") $internationalMon=$val2;
		elseif($key2=="internationalNum") $internationalNum=$val2;
		elseif($key2=="internationalSat") $internationalSat=$val2;
		elseif($key2=="internationalStdate") $internationalStdate=$val2;
		elseif($key2=="internationalSun") $internationalSun=$val2;
		elseif($key2=="internationalThu") $internationalThu=$val2;
		elseif($key2=="internationalTime") $internationalTime=$val2;
		elseif($key2=="internationalTue") $internationalTue=$val2;
		elseif($key2=="internationalWed") $internationalWed=$val2;
	}

	$week="";
	if($internationalMon=="Y") $week.="월";
	if($internationalTue=="Y") $week.="화";
	if($internationalWed=="Y") $week.="수";
	if($internationalThu=="Y") $week.="목";
	if($internationalFri=="Y") $week.="금";
	if($internationalSat=="Y") $week.="토";
	if($internationalSun=="Y") $week.="일";

	//checkVar("i",$i);
	//checkVar("airlineKorean",$airlineKorean);
	//checkVar("airport",$airport);
	//checkVar("city",$city);
	//checkVar("internationalIoType",$internationalIoType);
	//checkVar("internationalNum",$internationalNum);
	//checkVar("internationalTime",$internationalTime);

	unset($rs);
	if($internationalIoType=="OUT"){
		$airarr = explode("/",$airport);
		$rs[city] = $airarr[0];
		$rs[airport_in] = $airarr[1]."공항";

		$airarr2 = explode("/",$city);
		$rs[airport_out] = $airarr2[1]."공항";

		$rs[d_air]=$airlineKorean;
		$rs[d_air_no]=$internationalNum;
		$rs[d_time_s]=substr($internationalTime,0,2) . ":".substr($internationalTime,-2);
		$rs[d_wday]=$week;
	}else{
		$airarr = explode("/",$city);
		$rs[city] = $airarr[0];
		$rs[airport_in] = $airarr[1]."공항";

		$airarr2 = explode("/",$airport);
		$rs[airport_out] = $airarr2[1]."공항";

		$rs[d_air]=$airlineKorean;
		$rs[r_air]=$airlineKorean;
		$rs[r_air_no]=$internationalNum;
		$rs[r_time_s]=substr($internationalTime,0,2) . ":".substr($internationalTime,-2);
	
		$rs[r_wday]=$week;
	}

	$code = $rs[nation];
	$code .= "{@}".$rs[city];
	$code .= "{@}".$rs[airport_in];
	$code .= "{@}".$rs[airport_out];
	$code .= "{@}".$rs[d_air];
	$code .= "{@}".$rs[d_air_no];
	$code .= "{@}".$rs[d_time_s];
	$code .= "{@}".$rs[d_wday];
	$code .= "{@}".$rs[r_air_no];
	$code .= "{@}".$rs[r_time_s];
	$code .= "{@}".$rs[r_wday];
	$code .= "{@}".$tour_date;
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="35"><?=($internationalIoType=="IN")?"입국":"출국"?></td>
	      <td><?=$rs[city]?></td>
	      <td><?=$rs[airport_in]?></td>
		  <td><?=$rs[d_air]?></td>
	      <td><?=$rs[d_air_no]?></td>
	      <td><?=$rs[d_time_s]?></td>
	      <td><?=$rs[d_wday]?></td>
	      <td><?if($internationalIoType=="OUT"){?><span class="btn_pack medium bold"><a href="javascript:set_air_api('d','<?=$code?>')"> 선택 </a></span><?}?></td>
	      <td><?=$rs[r_air_no]?></td>
	      <td><?=$rs[r_time_s]?></td>
	      <td><?=$rs[r_wday]?></td>
	      <td><?if($internationalIoType=="IN"){?><span class="btn_pack medium bold"><a href="javascript:set_air_api('r','<?=$code?>')"> 선택 </a></span><?}?></td>
	    </tr>
<?
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
				<span class="btn_pack medium bold"><a href="javascript:newWin('view_air.php?ctg1=<?=$ctg1?>',870,500,1,1,'air')"> 타임스케쥴 등록 </a></span>
				<span class="btn_pack medium bold"><a href="list_air.php" target="_blank"> 타임스케쥴 바로가기 </a></span>
				<span class="btn_pack medium bold"><a href="../../api/air/airport.php?mode=airport" target="actarea"> 공항코드 갱신(API) </a></span>
				<span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
			  </td>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>


</div>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>