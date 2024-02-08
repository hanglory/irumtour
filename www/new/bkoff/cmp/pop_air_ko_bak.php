<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_air";
$TITLE = "국내항공정보(API)";
$key = "0g7LgnItlv2wg%2Bx%2BKcBPQKYOkBRi1%2Bt94uaZFjbLDFaX6zKtUBy5CqB12z6yLidx2kNdDCiany4gEc2ZC644WQ%3D%3D";
$test_mode=0;

###action
####API
if($golf_id_no){
	$sql = "select * from cmp_golf where id_no=$golf_id_no";
	list($rows) = $dbo->query($sql);
	$rs=$dbo->next_record();
	if(!$city_code && $rs[cityCode1]) $city_code = $rs[cityCode1];
	if(!$city_code2 && $rs[cityCode2]) $city_code2 = $rs[cityCode2]; 
}

if($test_mode) $tour_date="2015/10/05";
if($tour_date){

	$tour_date2 = rnf($tour_date);	

	if($find_airport){
		$schAirLine = strtoupper(substr($find_airport,0,2));
		$schFlightNum = substr($find_airport,2);
		// checkVar("schAirLine",$schAirLine);
		// checkVar("schFlightNum",$schFlightNum);
	}
	$ch = curl_init();
	$url = 'http://openapi.airport.co.kr/service/rest/FlightScheduleList/getDflightScheduleList'; /*URL*/
	$queryParams = '?' . urlencode('ServiceKey') . '='.$key; /*Service Key*/
	
	if(!$test_mode) $queryParams .= '&' . urlencode('schDate') . '=' . urlencode($tour_date2); /**/
	if(!$test_mode) $queryParams .= '&' . urlencode('schDeptCityCode') . '=' . urlencode($city_code); /**/
	if(!$test_mode) $queryParams .= '&' . urlencode('schArrvCityCode') . '=' . urlencode($city_code2); /**/
	if(!$test_mode && $schAirLine) $queryParams .= '&' . urlencode('schAirLine') . '=' . urlencode($schAirLine); /**/
	if(!$test_mode && $schFlightNum) $queryParams .= '&' . urlencode('schFlightNum') . '=' . urlencode($schFlightNum); /**/

	if($test_mode) $queryParams .= '&' . urlencode('schDate') . '=' . urlencode('20151005'); /**/
	if($test_mode) $queryParams .= '&' . urlencode('schDeptCityCode') . '=' . urlencode('GMP'); /**/
	if($test_mode) $queryParams .= '&' . urlencode('schArrvCityCode') . '=' . urlencode('HND'); /**/

	if($page) $queryParams .= '&' . urlencode('pageNo') . '=' . urlencode($page); /**/
	$queryParams .= '&' . urlencode('serviceKey') . '=' . urlencode($key); /**/


	curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$response = curl_exec($ch);
	curl_close($ch);


	if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
		// checkVar("url",$url);
		// checkVar("queryParams",$queryParams);
		// checkVar("tour_date2",$tour_date2);
		// checkVar("city_code",$city_code);
		// checkVar("city_code2",$city_code2);
		//var_dump($response);
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
	//if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@"))checkVar("$totalCount",$numOfRows);
	$row_search = $totalCount;
}


#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&tour_date=$tour_date&city_code=$city_code&city_code2=$city_code2&golf_id_no=$golf_id_no&d_date=$d_date&r_date=$r_date&find_airport=$find_airport";
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
		str +=" / 도착시간 : " + air_time2 +")";

		if(air_no_m!=""){
			str +=" - 국내선으로 이동 " + air_no_m;
			str +=" (출발시간 : " + air_time1_m ;
			str +=" / 도착시간 : " + air_time2_m +")";			
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
		str +=" / 도착시간 : " + air_time2 +")";
		opener.document.getElementById('air_info2').innerHTML = str;
		alert("귀국편 정보가 선택되었습니다.");
		self.close();
	}


	//self.close();
}

function set_air_api(bit,code){
	var url = "pop_air_cn.php?mode=air";
	url+="&bit="+bit;
	url+="&code="+code;
	actarea.location.href=url;
}

//-->
</script>
<script language="JavaScript">

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
  

	<select name="city_code" class='select'>
		<option value="">출발</option>
		<?=option_str($AIRPORT_KO_NAME,$AIRPORT_KO_CODE,$city_code)?>
	</select>	

	<select name="city_code2" class='select'>
		<option value="">도착</option>
		<?=option_str($AIRPORT_KO_NAME,$AIRPORT_KO_CODE,$city_code2)?>
	</select>		

	<input type="text" name="tour_date" size="11" maxlength="10" class="box c dateinput" readonly placeholder="날짜" value="<?=$tour_date?>">

	<input type="text" name="find_airport" size="10" maxlength="10" class="box c" placeholder="항공편" value="<?=$find_airport?>">

	<input class=button type="button" value=" 검 색 " onFocus='blur(this)' onclick="find_date('','')">
	<?if($d_date){?><input class="button" type="button" value="출발일로검색" onFocus='blur(this)' onclick="find_date('d','<?=$d_date?>')"><?}?>
	<?if($r_date){?><input class="button" type="button" value="귀국일로검색" onFocus='blur(this)' onclick="find_date('r','<?=$r_date?>')"><?}?>

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
			<th class="subject" rowspan="2">국가</th>
			<th class="subject" rowspan="2">국내공항</th>
			<th class="subject" rowspan="2">항공사명</th>
			<th class="subject" colspan="4">출발</th>
			<th class="subject" rowspan="2">출발선택</th>
			<th class="subject" colspan="4">귀국</th>
			<th class="subject" rowspan="2">귀국선택</th>
		</tr>
			<tr align=center height=25 bgcolor="#F7F7F6">
			<th class="subject" >항공편</th>
			<th class="subject" >출발시간</th>
			<th class="subject" >도착시간</th>
			<th class="subject" >출발요일</th>
			<th class="subject" >항공편</th>
			<th class="subject" >출발시간</th>
			<th class="subject" >도착시간</th>
			<th class="subject" >출발요일</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}


//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql_2);

$rs[assort] = ($city_code=="ICN" || $city_code=="GMP")? "D":"R";


$i=0;

if($totalCount==1){

	while(list($key,$val)=each($arr[body][items][item])){
		if($rs[assort]=="D"){
			if($key=="startcity") $rs[airport_in] = $val;
			elseif($key=="airportCode") $rs[startcityCode] = $val;
			elseif($key=="airlineKorean") $rs[d_air] = $val;
			elseif($key=="domesticNum") $rs[d_air_no] = $val;
			elseif($key=="domesticStartTime") $rs[d_time_s] = substr($val,0,2).":".substr($val,-2);
			elseif($key=="domesticArrivalTime") $rs[d_time_e] = substr($val,0,2).":".substr($val,-2);
		}else{
			if($key=="startcity") $rs[airport_in] = $val;
			elseif($key=="airportCode") $rs[startcityCode] = $val;
			elseif($key=="airlineKorean") $rs[d_air] = $val;
			elseif($key=="domesticNum") $rs[r_air_no] = $val;
			elseif($key=="domesticStartTime") $rs[r_time_s] = substr($val,0,2).":".substr($val,-2);
			elseif($key=="domesticArrivalTime") $rs[r_time_e] = substr($val,0,2).":".substr($val,-2);			
		}
		if($key=="domesticMon") $rs[d_wday] = ($val=="Y")?"월":"";
		elseif($key=="domesticTue") $rs[d_wday] .= ($val=="Y")?"화":"";
		elseif($key=="domesticWed") $rs[d_wday] .= ($val=="Y")?"수":"";
		elseif($key=="domesticThu") $rs[d_wday] .= ($val=="Y")?"목":"";
		elseif($key=="domesticFri") $rs[d_wday] .= ($val=="Y")?"금":"";
		elseif($key=="domesticSat") $rs[d_wday] .= ($val=="Y")?"토":"";
		elseif($key=="domesticSun") $rs[d_wday] .= ($val=="Y")?"일":"";	
	}

	$d_wday = "";
	if(strstr($rs[d_wday],"월")) $d_wday.=",월";
	if(strstr($rs[d_wday],"화")) $d_wday.=",화";
	if(strstr($rs[d_wday],"수")) $d_wday.=",수";
	if(strstr($rs[d_wday],"목")) $d_wday.=",목";
	if(strstr($rs[d_wday],"금")) $d_wday.=",금";
	if(strstr($rs[d_wday],"토")) $d_wday.=",토";
	if(strstr($rs[d_wday],"일")) $d_wday.=",일";
	$rs[d_wday] = substr($d_wday,1);
	if($rs[assort]=="R"){
		$rs[r_wday] = $rs[d_wday];
		$rs[d_wday] ="";
	}

	$code = $rs[nation];
	$code .= "{@}".$rs[city];
	$code .= "{@}".$rs[r_airport];
	$code .= "{@}".$rs[airport];
	$code .= "{@}".$rs[d_air];
	$code .= "{@}".$rs[flightId];
	$code .= "{@}".$rs[scheduleDateTime];
	$code .= "{@}".$rs[d_wday];
	$code .= "{@}".$rs[r_flightId];
	$code .= "{@}".$r_atime;
	$code .= "{@}".$rs[r_wday];
	$code .= "{@}".$tour_date;	
	$code .= "{@}".$atime;	
	$code .= "{@}".$rs[r_scheduleDateTime];
?>

	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td style="height:35px">한국<?//=$rs[assort]?></td>
	      <td><?=$rs[airport_in]?></td>
		   <td><?=$rs[d_air]?></td>
	      <td><?=$rs[d_air_no]?></td>
	      <td><?=$rs[d_time_s]?></td>
	      <td><?=$rs[d_time_e]?></td>
	      <td><?=$rs[d_wday]?></td>
	      <td>
	      	<?if($rs[assort]=="D" && $rs[d_air_no]){?>
	      	<span class="btn_pack medium bold"><a href="#" onClick="set_air('d','<?=$rs[id_no]?>','<?=$rs[d_air_no]?>','<?=$rs[d_time_s]?>','<?=$rs[d_time_e]?>','<?=$rs[d_air_no_m]?>','<?=$rs[d_time_s_m]?>','<?=$rs[d_time_e_m]?>')"> 선택 </a></span>
	      	<?}?>
	      </td>
	      <td><?=$rs[r_air_no]?></td>
	      <td><?=$rs[r_time_s]?></td>
	      <td><?=$rs[r_time_e]?></td>
	      <td><?=$rs[r_wday]?></td>
	      <td>
	      	<?if($rs[assort]=="R" && $rs[r_air_no]){?>
	      	<span class="btn_pack medium bold"><a href="#" onClick="set_air('r','<?=$rs[id_no]?>','<?=$rs[r_air_no]?>','<?=$rs[r_time_s]?>','<?=$rs[r_time_e]?>','','','')"> 선택 </a></span>
	      	<?}?>
	      </td>
	    </tr>

<?
}else{

	while(@list($key,$val)=each($arr[body][items][item])){
		//checkVar($key,$val);

		while(list($key2,$val2)=@each($val)){
			//checkVar($key2,$val2);

			if($rs[assort]=="D"){
				if($key2=="startcity") $rs[airport_in] = $val2;
				elseif($key2=="airportCode") $rs[startcityCode] = $val2;
				elseif($key2=="airlineKorean") $rs[d_air] = $val2;
				elseif($key2=="domesticNum") $rs[d_air_no] = $val2;
				elseif($key2=="domesticStartTime") $rs[d_time_s] = substr($val2,0,2).":".substr($val2,-2);
				elseif($key2=="domesticArrivalTime") $rs[d_time_e] = substr($val2,0,2).":".substr($val2,-2);
			}else{
				if($key2=="startcity") $rs[airport_in] = $val2;
				elseif($key2=="airportCode") $rs[startcityCode] = $val2;
				elseif($key2=="airlineKorean") $rs[d_air] = $val2;
				elseif($key2=="domesticNum") $rs[r_air_no] = $val2;
				elseif($key2=="domesticStartTime") $rs[r_time_s] = substr($val2,0,2).":".substr($val2,-2);
				elseif($key2=="domesticArrivalTime") $rs[r_time_e] = substr($val2,0,2).":".substr($val2,-2);			
			}
			if($key2=="domesticMon") $rs[d_wday] = ($val2=="Y")?"월":"";
			elseif($key2=="domesticTue") $rs[d_wday] .= ($val2=="Y")?"화":"";
			elseif($key2=="domesticWed") $rs[d_wday] .= ($val2=="Y")?"수":"";
			elseif($key2=="domesticThu") $rs[d_wday] .= ($val2=="Y")?"목":"";
			elseif($key2=="domesticFri") $rs[d_wday] .= ($val2=="Y")?"금":"";
			elseif($key2=="domesticSat") $rs[d_wday] .= ($val2=="Y")?"토":"";
			elseif($key2=="domesticSun") $rs[d_wday] .= ($val2=="Y")?"일":"";	
		}	


		$d_wday = "";
		if(strstr($rs[d_wday],"월")) $d_wday.=",월";
		if(strstr($rs[d_wday],"화")) $d_wday.=",화";
		if(strstr($rs[d_wday],"수")) $d_wday.=",수";
		if(strstr($rs[d_wday],"목")) $d_wday.=",목";
		if(strstr($rs[d_wday],"금")) $d_wday.=",금";
		if(strstr($rs[d_wday],"토")) $d_wday.=",토";
		if(strstr($rs[d_wday],"일")) $d_wday.=",일";
		$rs[d_wday] = substr($d_wday,1);
		if($rs[assort]=="R"){
			$rs[r_wday] = $rs[d_wday];
			$rs[d_wday] ="";
		}

		$code = $rs[nation];
		$code .= "{@}".$rs[city];
		$code .= "{@}".$rs[r_airport];
		$code .= "{@}".$rs[airport];
		$code .= "{@}".$rs[d_air];
		$code .= "{@}".$rs[flightId];
		$code .= "{@}".$rs[scheduleDateTime];
		$code .= "{@}".$rs[d_wday];
		$code .= "{@}".$rs[r_flightId];
		$code .= "{@}".$r_atime;
		$code .= "{@}".$rs[r_wday];
		$code .= "{@}".$tour_date;	
		$code .= "{@}".$atime;	
		$code .= "{@}".$rs[r_scheduleDateTime];


?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td style="height:35px">한국<?//=$rs[assort]?></td>
	      <td><?=$rs[airport_in]?></td>
		  <td><?=$rs[d_air]?></td>
	      <td><?=$rs[d_air_no]?></td>
	      <td><?=$rs[d_time_s]?></td>
	      <td><?=$rs[d_time_e]?></td>
	      <td><?=$rs[d_wday]?></td>
	      <td>
	      	<?if($rs[assort]=="D" && $rs[d_air_no]){?>
	      	<span class="btn_pack medium bold"><a href="#" onClick="set_air('d','<?=$rs[id_no]?>','<?=$rs[d_air_no]?>','<?=$rs[d_time_s]?>','<?=$rs[d_time_e]?>','<?=$rs[d_air_no_m]?>','<?=$rs[d_time_s_m]?>','<?=$rs[d_time_e_m]?>')"> 선택 </a></span>
	      	<?}?>
	      </td>
	      <td><?=$rs[r_air_no]?></td>
	      <td><?=$rs[r_time_s]?></td>
	      <td><?=$rs[r_time_e]?></td>
	      <td><?=$rs[r_wday]?></td>
	      <td>
	      	<?if($rs[assort]=="R" && $rs[r_air_no]){?>
	      	<span class="btn_pack medium bold"><a href="#" onClick="set_air('r','<?=$rs[id_no]?>','<?=$rs[r_air_no]?>','<?=$rs[r_time_s]?>','<?=$rs[r_time_e]?>','','','')"> 선택 </a></span>
	      	<?}?>
	      </td>
	    </tr>
<?
	}
}
?>
	</table>

	<div style="color:red;padding-top:10px">
		* <b>소요시간</b>은 한국공항공사에서 보내주는 시간으로 실제 시간과 오차가 있을수 있습니다.<br>
		<span style="color:gray">* [공공저작물출처표시] <a href='https://www.data.go.kr/tcs/dss/selectApiDataDetailView.do?publicDataPk=15000126' target="_blank">CCKorea/ CC BY</a>. 본 저작물은 '한국공항공사'에서 '2021년'작성하여 공공누리 제1유형으로 개방한 '한국공항공사_항공기 운항정보'을 이용하였습니다.</span>
	</div>



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
				<!-- <span class="btn_pack medium bold"><a href="javascript:newWin('view_air.php?ctg1=<?=$ctg1?>',870,500,1,1,'air')"> 타임스케쥴 등록 </a></span>
				<span class="btn_pack medium bold"><a href="list_air.php" target="_blank"> 타임스케쥴 바로가기 </a></span>
				<span class="btn_pack medium bold"><a href="../../api/air/airport.php?mode=airport" target="actarea"> 공항코드 갱신(API) </a></span> -->
				<span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
			  </td>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>


</div>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>