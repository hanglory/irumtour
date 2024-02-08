<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_air";
$TITLE = "항공정보 선택(API)";
$test_mode=0;
$serviceKey = "0g7LgnItlv2wg%2Bx%2BKcBPQKYOkBRi1%2Bt94uaZFjbLDFaX6zKtUBy5CqB12z6yLidx2kNdDCiany4gEc2ZC644WQ%3D%3D";


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
	$d_time_e = $air_arr[12];
	$r_time_e = $air_arr[13];
	$airport_place = $airport_in;

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
				parent.set_air('$bit','$rs[id_no]','$rs[d_air_no]','$rs[d_time_s]','$rs[d_time_e]','','','');
			</script>
		";
	}else{//귀국
		echo "
			<script>
				parent.set_air('$bit','$rs[id_no]','$rs[r_air_no]','$rs[r_time_s]','$rs[r_time_e]','','','');
			</script>
		";
	}


	exit;
}


####API
if($golf_id_no ){
	$sql = "select * from cmp_golf where id_no=$golf_id_no";
	list($rows) = $dbo->query($sql);
	$rs=$dbo->next_record();
	$arr = explode("(",trim($rs[cityCode2]));
	$city_code = "INC";
	$cityCode2 = substr($arr[1],0,-1);
	if(!$find_airport && $cityCode2) $find_airport = $cityCode2;
	//checkVar($city_code,$cityCode2);
}


//$api="r";

if($api=="r"){
	$sql = "delete from cmp_air_tmp";
	$dbo->query($sql);

	/*도착 s*/
	$ch = curl_init();

	//$url = 'http://openapi.airport.kr/openapi/service/StatusOfPassengerFlights/getPassengerArrivals'; /*URL*/
    $url = 'http://apis.data.go.kr/B551177/StatusOfPassengerFlightsOdp/getPassengerArrivalsOdp'; /*URL*/
	$queryParams = '?' . urlencode('serviceKey') . '='.$serviceKey; /*Service Key*/
	$queryParams .= '&' . urlencode('from_time') . '=' . urlencode('0000'); /**/
	$queryParams .= '&' . urlencode('to_time') . '=' . urlencode('2400'); /**/
	//$queryParams .= '&' . urlencode('airport') . '=' . urlencode('HKG'); /**/
	//$queryParams .= '&' . urlencode('flight_id') . '='; /**/
	//$queryParams .= '&' . urlencode('airline') . '='; /**/
	$queryParams .= '&' . urlencode('lang') . '=' . urlencode('K'); /**/

	curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$response = curl_exec($ch);
	curl_close($ch);

	//var_dump($response);

	$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA); 
	$json = json_encode($xml); 
	$arr = json_decode($json,TRUE);

	while(@list($key,$val)=each($arr[body][items][item])){

		$r_airport="";
		$r_airportCode="";
		$r_cityCode="";
		$r_elapsetime="";
		$r_estimatedDateTime="";
		$r_flightId="";
		$r_remark="";
		$r_scheduleDateTime="";

		while(list($key2,$val2)=each($val)){
			
			if($key2=="airline") $airline = $val2;
			elseif($key2=="airport") $airport=$val2;
			elseif($key2=="airportCode") $airportCode = $val2;
			elseif($key2=="cityCode") $cityCode = $val2;
			elseif($key2=="elapsetime") $elapsetime = $val2;
			elseif($key2=="estimatedDateTime") $estimatedDateTime = $val2;
			elseif($key2=="flightId") $flightId = $val2;
			elseif($key2=="remark") $remark = $val2;
			elseif($key2=="scheduleDateTime") $scheduleDateTime = $val2;

		}


		$sql = "
				insert into cmp_air_tmp(
					assort,
					r_airport,
					r_airportCode,
					r_cityCode,
					r_elapsetime,
					r_estimatedDateTime,
					r_flightId,
					r_remark,
					r_scheduleDateTime		
				)values(
					'A',
					'$airport',
					'$airportCode',
					'$cityCode',
					'$elapsetime',
					'$estimatedDateTime',
					'$flightId',
					'$remark',
					'$scheduleDateTime'
				)
			";
		$dbo->query($sql);
		//if(strstr("@211.226.255.74@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}		

	}
	/*도착 f*/


	/*출발 s*/
	$ch = curl_init();
	$url = 'http://apis.data.go.kr/B551177/StatusOfPassengerFlightsOdp/getPassengerArrivalsOdp'; /*URL*/
	$queryParams = '?' . urlencode('serviceKey') . '='.$serviceKey; /*Service Key*/
	$queryParams .= '&' . urlencode('from_time') . '=' . urlencode('0000'); /**/
	$queryParams .= '&' . urlencode('to_time') . '=' . urlencode('2400'); /**/
	//$queryParams .= '&' . urlencode('flight_id') . '=' . urlencode('2400'); /**/
	//$queryParams .= '&' . urlencode('airline') . '='; /**/
	$queryParams .= '&' . urlencode('lang') . '=' . urlencode('K'); /**/

	curl_setopt($ch, CURLOPT_URL, $url . $queryParams);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	$response = curl_exec($ch);
	curl_close($ch);

	//var_dump($response);

	$xml = simplexml_load_string($response, "SimpleXMLElement", LIBXML_NOCDATA); 
	$json = json_encode($xml); 
	$arr = json_decode($json,TRUE);

	while(@list($key,$val)=each($arr[body][items][item])){

		$airport="";
		$airportCode="";
		$cityCode="";
		$elapsetime="";
		$estimatedDateTime="";
		$flightId="";
		$remark="";
		$scheduleDateTime="";

		while(list($key2,$val2)=each($val)){
			
			if($key2=="airline") $airline = $val2;
			elseif($key2=="airport") $airport=$val2;
			elseif($key2=="airportCode") $airportCode = $val2;
			elseif($key2=="cityCode") $cityCode = $val2;
			elseif($key2=="elapsetime") $elapsetime = $val2;
			elseif($key2=="estimatedDateTime") $estimatedDateTime = $val2;
			elseif($key2=="flightId") $flightId = $val2;
			elseif($key2=="remark") $remark = $val2;
			elseif($key2=="scheduleDateTime") $scheduleDateTime = $val2;

		}

		$sql = "
				insert into cmp_air_tmp(
				    assort,
					airport,
					airportCode,
					cityCode,
					elapsetime,
					estimatedDateTime,
					flightId,
					remark,
					scheduleDateTime		
				)values(
					'D',
					'$airport',
					'$airportCode',
					'$cityCode',
					'$elapsetime',
					'$estimatedDateTime',
					'$flightId',
					'$remark',
					'$scheduleDateTime'
				)
			";
		$dbo->query($sql);

	}
	/*출발 f*/
}




####각종 기초 정보 결정
$view_row=10;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;


#검색조건
// if($golf_id_no){
//     $sql = "select * from cmp_golf where id_no=$golf_id_no";
//     list($rows) = $dbo->query($sql);
//     $rs=$dbo->next_record();
//     $rs[air_city] = str_replace("국제공항","",$rs[air_city]);
//     $arr_air_city = explode(" ",$rs[air_city]);
//     $find_airport = trim($arr_air_city[0]);

// }

if($assort){$filter.=" and assort='$assort'";$findMode=1;}
if($find_airport){
	$find_airport = trim($find_airport);
	$filter.=" and (
		airport like '%$find_airport%' or airportCode like '%$find_airport%'
		or r_airport like '%$find_airport%' or r_airportCode like '%$find_airport%'		
		or flightId like '%$find_airport%'
		or r_flightId like '%$find_airport%'
		) ";
	$findMode=1;
}

#query
$sql_1 = "
		select 
			*,
			(select elapsetime from cmp_air_time where assort=a.assort and airportCode=a.airportCode) as elapsetime_db
			from cmp_air_tmp as a
		where id_no>0 
		$filter
	";
$sql_2 = $sql_1 . " order by id_no desc limit  $start, $view_row";

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


$link = "find_airport=$find_airport&assort=$assort&golf_id_no=$golf_id_no";
$sessLink = $link;
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
		str +=" / 도착시간 : " + air_time2 +")";

		if(air_no_m!=""){
			str +=" - 국내선으로 이동 " + air_no_m;
			str +=" (출발시간 : " + air_time1_m ;
			str +=")";			
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
		str +=")";
		str +=" / 도착시간 : " + air_time2 +")";
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

	<tr height="22">
	<td><font color="#666666">* <?=($status)?> 자료수: <?=nf($row_search)?>개 <?if(!$seq_mode){?>{ <?=nf($total_page)?> page /  <?=nf($page)?> page }<?}?></font></td>
	<td valign='bottom' align=right>
	<?if($findMode):?>
	<input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
	<?endif;?>
  


	<select name="assort" class='select' style="width:150px">
	<?=option_str("출국/귀국,출국,귀국",",D,A",$assort)?>
	</select>		

	<input type="text" name="find_airport" size="23" maxlength="10" class="box c" placeholder="공항명 or 공항코드 or 항공편" value="<?=$find_airport?>">

	<input class=button type="submit" value=" 검 색 " onFocus='blur(this)'>

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
		<th class="subject" colspan="6">출발</th>
		<th class="subject" rowspan="2">출발선택</th>
		<th class="subject" colspan="6">귀국</th>
		<th class="subject" rowspan="2">귀국선택</th>
		</tr>
	    <tr align=center height=25 bgcolor="#F7F7F6">
		
		<th class="subject" >공항명</th>
		<th class="subject" >공항코드</th>
		<th class="subject" >항공편</th>
		<th class="subject" >출발시간</th>
		<th class="subject" >소요시간</th>
		<th class="subject" >도착시간</th>

		<th class="subject" >공항명</th>
		<th class="subject" >공항코드</th>
		<th class="subject" >항공편</th>
		<th class="subject" >출발시간</th>
		<th class="subject" >소요시간</th>
		<th class="subject" >도착시간</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//if(strstr("@211.226.255.74@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	if($rs[assort]=="D"){//출국
		if($rs[elapsetime_db] && !$rs[elapsetime]) $rs[elapsetime]=$rs[elapsetime_db];
	}else{
		if($rs[r_elapsetime_db] && !$rs[r_elapsetime]) $rs[r_elapsetime]=$rs[r_elapsetime_db];
	}

	$h = ceil(substr($rs[scheduleDateTime],0,2));
	$m = substr($rs[scheduleDateTime],-2);
	$rs[scheduleDateTime] = num2($h) . ":" . $m;
	//if($rs[estimatedDateTime]){
	//	$h = ceil(substr($rs[estimatedDateTime],0,2));
	//	$m = substr($rs[estimatedDateTime],-2);
	//	$rs[estimatedDateTime] = num2($h) . ":" . $m;	
	//}

	$h2 = ceil(substr($rs[elapsetime],0,2));
	$m2 = substr($rs[elapsetime],-2);
	$add_time = ($m2+($h2*60))*60;
	$atime_ = mktime($h, $m, 0, 12, 32, 1997);
 	$atime =($rs[elapsetime])? date("H:i",mktime($h, $m, 0, 12, 32, 1997)+$add_time) : "";

	//$rs[scheduleDateTime] = substr($rs[scheduleDateTime],0,2) . ":" . substr($rs[scheduleDateTime],-2);			
	//$rs[r_scheduleDateTime] = substr($rs[r_scheduleDateTime],0,2) . ":" . substr($rs[r_scheduleDateTime],-2);			

	$r_h = ceil(substr($rs[r_scheduleDateTime],0,2));
	$r_m = substr($rs[r_scheduleDateTime],-2);
	$rs[r_scheduleDateTime] = num2($r_h) . ":" . $r_m;
	//if($rs[r_estimatedDateTime]){
	//	$r_h = ceil(substr($rs[r_estimatedDateTime],0,2));
	//	$r_m = substr($rs[r_estimatedDateTime],-2);
	//	$rs[r_estimatedDateTime] = num2($r_h) . ":" . $r_m;
	//}


	if(strlen($rs[r_elapsetime])==4){
		$r_h2 = ceil(substr($rs[r_elapsetime],0,2));
		$r_m2 = substr($rs[r_elapsetime],-2);
		$r_add_time = ($r_m2+($r_h2*60))*60;
		$r_atime_ = mktime($r_h, $r_m, 0, 12, 32, 1997);
		$r_atime = date("H:i",mktime($r_h, $r_m, 0, 12, 32, 1997)-$r_add_time);
	}else{
		$rs[r_elapsetime]="";
	}

	if($rs[assort]=="A"){//입국
		$rs[scheduleDateTime]="";
		$rs[elapsetime]="";
		$atime="";
	}else{
		$rs[r_scheduleDateTime]="";
		$rs[r_elapsetime]="";
		$r_atime="";
	}

	if($rs[elapsetime]) $rs[elapsetime] = substr($rs[elapsetime],0,2) . ":" . substr($rs[elapsetime],-2);			
	if($rs[r_elapsetime]) $rs[r_elapsetime] = substr($rs[r_elapsetime],0,2) . ":" . substr($rs[r_elapsetime],-2);			

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
	      <td height="35"><?=($rs[assort]=="A")?"입국":"출국"?></td>

	      <td><?=$rs[airport]?></td>
		  <!-- <td><?=get_air($rs[flightId])?></td> -->
	      <td><?=$rs[airportCode]?></td>
		  <td><?=$rs[flightId]?></td>
	      <td><?=$rs[scheduleDateTime]?></td>
	      <td>
	      	<?
	      	if($rs[elapsetime] && $rs[elapsetime]!="Ar:ay" && !$rs[elapsetime_db]){
	      		echo $rs[elapsetime];
	      	}else{
	      		if($rs[airportCode]){
	      	?>
	      		<span class="btn_pack medium bold"><a href="javascript:newWin('pop_air_cn2.php?assort=<?=$rs[assort]?>&airportCode=<?=$rs[airportCode]?>',400,250,0,0,'air_time')"> <?=($rs[elapsetime_db])?$rs[elapsetime_db]:"입력"?> </a></span>	
	      		
	      	<?	
	      		}
	      	}
	      	?>		
	      </td>
	      <td><?=$atime?></td>
	      <td><?if($rs[assort]=="D"){?><span class="btn_pack medium bold"><a href="javascript:set_air_api('d','<?=$code?>')"> 선택 </a></span><?}?></td>
	      <td><?=$rs[r_airport]?></td>
	      <!-- <td><?=get_air($rs[r_flightId])?></td> -->
	      <td><?=$rs[r_airportCode]?></td>
		   <td><?=$rs[r_flightId]?></td>
	      <td><?=$r_atime?></td>
	      <td><?=$rs[r_elapsetime]?></td>
	      <td><?=$rs[r_scheduleDateTime]?></td>
	      <td><?if($rs[assort]=="A"){?><span class="btn_pack medium bold"><a href="javascript:set_air_api('r','<?=$code?>')"> 선택 </a></span><?}?></td>
	    </tr>
<?
}
?>
	</table>

	<div style="color:red;padding-top:10px">
		* <b>소요시간</b>은 항공사에서 보내주는 시간으로 실제 시간과 오차가 있을수 있습니다.<br>
		* <b>출국의 도착시간</b>과 <b>귀국의 출발시간</b>은 소요시간을 기준으로 계산한 정보여서 실제 시간과 오차가 있을 수 있습니다.
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