<?
//해당 날짜의 좌석수 조회;
// $bit 1 좌석수만 조회, 2 상태 정보도 조회 (1예약가능,2출발확정,3예약마감,4가격문의)
Function get_seat($tid,$tour_date,$bit=0){
	global  $info;
	$dbo = new MiniDB($info);
	$today = Date("Y/m/d");

	$sql = "select * from ez_tour_calendar where tid= $tid and tour_date='$tour_date' order by id_no desc limit 1 ";
	$dbo->query($sql);
	$rs = $dbo->next_record();

	$seat=0;
	$seat_m=0;
	$status = "";//기본상태 예약마감
	$selling_seat = get_selling($tid,$tour_date);//팔린좌석

	If($tour_date > $today){ //지난 날짜는 무조건 마감

		If($rs[status]){ //출발안함이 아닌 경우
			$status = $rs[status];

			$seat = $rs[tour_max] - $selling_seat;
			If($seat<=0){
				//$seat=0;
				$status = 3;
			}
		}
		If($status==1 && $selling_seat>=$rs[tour_min]) $status=2; //최소인원 이상은 출발확정
		If($seat<1 && $rs[tour_standby]){$seat=($rs[tour_max]+$rs[tour_standby])-$selling_seat; $status=5;} //대기예약
	}



	switch($status){
		Case "1": $status_name="예약가능";break;
		Case "2": $status_name="출발확정";break;
		Case "3": $status_name="예약마감";break;
		Case "4": $status_name="가격문의";break;
		Case "5": $status_name="대기예약";break;
	}

	$result_arr[seat]=$seat;
	$result_arr[status]=$status;
	$result_arr[status_name]=$status_name;

	If($bit){
		Return	 $result_arr;
	}else{
		Return $seat;
	}


}



//해당 날짜의 팔린 상품 수
Function get_selling($tid,$tour_date){
	Global  $info;
	Global  $REMOTE_ADDR;
	$dbo = new MiniDB($info);

	$sql = "select sum(people) as qty from ez_order where tid=$tid and bit=1 and tour_date='$tour_date' and status<>'취소'";
	$dbo->query($sql);
	$rs=$dbo->next_record();

	$result = $rs[qty];

	Return $result;

}

//요일 확인
Function get_week($date,$bit=0){
	$arr = explode("/",$date);
	$week = Date("w",mktime(0,0,0,$arr[1],$arr[2],$arr[0]));

	If($bit){
		switch($week){
			case 0:$week = "일";	break;
			case 1:	$week = "월";	break;
			case 2:	$week = "화";	break;
			case 3:	$week = "수";	break;
			case 4:	$week = "목";	break;
			case 5:	$week = "금";	break;
			case 6:	$week = "토";	break;
		}
	}

	Return $week;
}


/*카테고리 파일 접두어 확인*/
Function get_prefix($str){

	switch($str){
		case 1:	$result = "domestic";	break;
		case 2:	$result = "jeju";	break;
		case 3:	$result = "overseas";	break;
		case 4:	$result = "cruise";	break;
		case 5:	$result = "spa";	break;
		case 6:	$result = "ship";	break;
		case 7:	$result = "hotel";	break;
	}

	Return $result;
}

/*오늘 본 상품 저장*/
function save_today($tid){

	global $info;
	global $sessid;
	$dbo = new MiniDB($info);
	$today = time();

	$sql = "select * from ez_tour where tid=$tid";
	$dbo->query($sql);
	$rs=$dbo->next_record();

	$sql="delete from ez_today where sessid='$sessid' and tid=$tid";
	$dbo->query($sql);

	$sql="insert into ez_today (tid,subject,filename1,today,sessid) values ('$rs[tid]','$rs[subject]','$rs[filename1]','$today','$sessid')";
	$dbo->query($sql);
}

/*오늘 본 상품 지우기*/
function drop_today(){

	global $info;
	$dbo = new MiniDB($info);
	$day = time()-86400;

	$sql="delete from ez_today where today < $day ";
	$dbo->query($sql);

}


/*주문되지 않은 상품 지우기*/
function drop_order_tmp(){

	global $info;
	$dbo = new MiniDB($info);
	$day = Date("Y/m/d",time()-(86400*2));

	$sql="delete from ez_order where reg_date <= '$day' and bit<>1";
	$dbo->query($sql);

}


/*숙박 표기 0박->0박0일*/
function days_format($str,$str2=""){

	If($str2){
		$result = $str . $str2;
	}else{
		$s = (substr($str,0,1));
		If($s>0){
			$j = $str+1;
			$result .= $j. "일";
		}else{
			$result = $str;
		}
	}

	Return $result;
}

/*카테고리 path*/
Function get_pathname($val){
	switch($val){
		Case "1": $path="domestic"; break; //국내여행
		Case "2": $path="jeju"; break; //제주여행
		Case "3": $path="overseas"; break; //해외여행
		Case "4": $path="cruise"; break; //크루즈여행
		Case "5": $path="spa"; break; //스파(온천)
		Case "6": $path="ship"; break; //선박(유람선)
		Case "7": $path="hotel"; break; //호텔(팬션/콘도)
	}
	Return $path;
}

/*카테고리 이름 확인하기*/
function get_category_name($ctg){

	global  $info;
	$dbo = new MiniDB($info);

	$arr = explode("-",$ctg);

	for($i=0; $i <count($arr);$i++){
		$j=$i+1;
		$sql ="select * from ez_tour_category${j} where id_no=$arr[$i]";
		$dbo->query($sql);
		$rs=$dbo->next_record();
		if($rs[subject]) $subject .=">".$rs[subject];

	}
	$subject = substr($subject,1);

	return $subject;
}


/*카테고리 이름 확인하기2*/
function get_category_name_path($code,$deep){

	global  $info;
	$dbo = new MiniDB($info);

	$sql ="select * from ez_tour_category${deep} where id_no=$code";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	$subject =$rs[subject];

	return $subject;

}

/*배너*/
function banner($size,$seq=1){

	global $info;
	global $code1;
	$dbo = new MiniDB($info);
	if(!$code1) $code1 =999;

	$sql ="select * from ez_banner where size='$size' and seq = $seq and bit=1 and code1=$code1";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	$url = ($rs[url])? $rs[url]:'#';
	$size_ = explode("*",$size);

	$result = "<a href=\"$url\" target=\"$rs[target]\"><img src=\"/public/banner/$rs[filename]\" width=\"$size_[0]\" height=\"$size_[1]\" alt=\"\" /></a>";

	If($code1=="999") $code1="";

	return $result;

}

/*예약하는 날짜의 요일 확인*/
Function get_order_week($tour_date,$abs_holiday_pre_bit=0){
	Global  $info;
	Global  $HOLIDAY;
	$dbo = new MiniDB($info);
	$date_arr= explode("/",$tour_date);
	$w="";

	//공휴일 체크
	$tour_date2 = mktime(0,0,0,$date_arr[1],$date_arr[2],$date_arr[0]);
	$today_holiday = ";A@".Date("m@d@Y;",$tour_date2);
	$holiday_bit=(strstr($HOLIDAY,$today_holiday))?1:0;
	If($holiday_bit) $w="8";

	//공휴일 전일 체크
	If(!$holiday_bit){
		$tour_date3 = mktime(0,0,0,$date_arr[1],$date_arr[2]+1,$date_arr[0]);
		$today_holiday_pre = ";A@".Date("m@d@Y;",$tour_date3);
		$holiday_pre_bit=(strstr($HOLIDAY,$today_holiday_pre))?1:0;
		If($holiday_pre_bit) $w="9";
	}

	//공휴일 전전일 체크
	If(!$holiday_bit && !$holiday_pre_bit && !$abs_holiday_pre_bit){
		$tour_date4 = mktime(0,0,0,$date_arr[1],$date_arr[2]+2,$date_arr[0]);
		$today_holiday_pre2 = ";A@".Date("m@d@Y;",$tour_date4);
		$holiday_pre2_bit=(strstr($HOLIDAY,$today_holiday_pre2))?1:0;
		If($holiday_pre2_bit) $w="10";
	}

	If(!$w){
		$w = Date("w",$tour_date2);
	}

	If(!$w) $w=7;

	Return $w;
}

/*결제 수단*/
Function get_paymethod($str){
	switch($str){
		Case "bank": $result="무통장"; break;
		Case "card": $result="신용카드"; break;
	}
	Return $result;
}

/*상품 링크 생성*/
Function tour_link($tid){
	Global  $info;
	Global  $REMOTE_ADDR;
	$dbo = new MiniDB($info);

	$sql = "select tid,subject from ez_tour where tid=$tid and bit=1 and sale_group='T'";
	$dbo->query($sql);
	$rs=$dbo->next_record();

	$link = "<a href='itemview.html?tid=$tid'>$rs[subject]</a>";

	Return $link;
}


/*catgegory filter*/
Function filter_category($code1,$code2="",$code3="",$alias=""){

	Global $REMOTE_ADDR;

	If($alias) $alias_=$alias . ".";

	if($code3 && $code2 && $code1){
		$full = "${code1}-${code2}-${code3}";
		$filter = " and (${alias_}category1 = '$full' or ${alias_}category2 = '$full' or ${alias_}category3 = '$full' or ${alias_}category4 = '$full' or ${alias_}category5 = '$full' or ${alias_}category6 = '$full')";
	}
	elseif($code2 && $code1){
		$full = "${code1}-${code2}-%";
		$filter = " and (${alias_}category1 like '$full' or ${alias_}category2 like '$full' or ${alias_}category3 like '$full' or ${alias_}category4 like '$full' or ${alias_}category5 like '$full' or ${alias_}category6 like '$full')";
	}
	elseif($code1){
		$full = "${code1}-%";
		$filter = " and (${alias_}category1 like '$full' or ${alias_}category2 like '$full' or ${alias_}category3 like '$full' or ${alias_}category4 like '$full' or ${alias_}category5 like '$full' or ${alias_}category6 like '$full')";
	}

	//If($REMOTE_ADDR=="1.215.151.146") checkVar("",$filter);

   Return $filter;

}

/*블랙리스트 확인*/
Function check_black($cell,$bit=0){
	Global  $info;
	Global  $REMOTE_ADDR;
	$dbo = new MiniDB($info);

	$cell = str_replace("-","",Trim($cell));
	$cell = str_replace(" ","",$cell);

	$sql = "select * from ez_blacklist where replace(cell,'-','') = '$cell' ";
	list($rows) = $dbo->query($sql);
	$rs=$dbo->next_record();

	$result = ($bit)? $rs[memo] : $rows;

	return $result;
}

/*include_tour_list_type3의 날짜 색상*/
Function get_calendar_status_color($status){
	If($status=="1") $result = "#2194E5";
	elseIf($status=="2") $result = "#478F23";
	elseIf($status=="3") $result = "#767676";
	elseIf($status=="4") $result = "#5F5F5F";

	Return $result;
}

/*color picker*/
Function colorpicker($id){
	Global $COLOR_CODE;
	$color = explode(",",$COLOR_CODE);

	$reult = "<div style=\"position:relative;display:inline\">";

		For($i=0; $i < count($color);$i++){
			$result .= "<span style='background-color:".$color[$i].";width:15px;height:20px;cursor:pointer' onclick=\"document.getElementById('$id').value='".$color[$i]."';document.getElementById('$id').style.color='".$color[$i]."'\"></span>";
		}

	$result .="</div>";

	Return $result;
}

/*항공사 마크*/
Function get_air_mark($str){

	switch($str){
	  Case "아시아나": $ico = "ic_asiana.gif"; break;
	  Case "대한항공": $ico = "ic_koreanair.gif"; break;
	  Case "진에어": $ico = "ic_jinair.gif"; break;
	  Case "부산에어": $ico = "ic_busan.gif"; break;
	  Case "제주항공": $ico = "ic_jejuair.gif"; break;
	  Case "티웨이": $ico = "tway.gif"; break;
	}

	$result = "<img src='images/jeju/$ico' alt='$str' />";
	Return $result;

}

/*날짜로 *박*일 계산*/
function get_day_night($date1,$date2,$plan_type=""){
	$sdate = $date1;
	$i=0;
	while($sdate<=$date2){
		$arr = explode("/",$sdate);
		$sdate = @Date("Y/m/d",mktime(0,0,0,$arr[1],$arr[2]+1,$arr[0]));
		$i++;
	}

	$j = $i-1;
	$result =(!$j)? "당일" : "${j}박${i}일";

	if($plan_type){
		$night = $i;
		if($plan_type=="E" || $plan_type=="F" || $plan_type=="K" || $plan_type=="I" || $plan_type=="J"){
		   $days1 = $night-2;
		   $days2 = $night;
		}else{
		   $days1 = $night-1;
		   $days2 = $night;
		}		
		$result =(!$days1)? "당일" : "${days1}박${days2}일";
	}
	Return $result;
}
function get_days($date1,$date2){
    $sdate = $date1;
    $i=0;
    while($sdate<=$date2){
        $arr = explode("/",$sdate);
        $sdate = @Date("Y/m/d",mktime(0,0,0,$arr[1],$arr[2]+1,$arr[0]));
        $i++;
    }

    $j = $i-1;
    $result =(!$j)? "당일" : "${i}일";
    Return $result;
}

/*렌트카 가격계산  - 나만의 여행 만들기,레일카텔*/
Function get_rent_price($tid,$sdate,$edate,$stime,$etime){

	global $info;
	$dbo = new MiniDB($info);
	$PRICE_ORIGIN=0;
	$PRICE=0;

	//렌트 시간
	$arr= explode("/",$sdate);
	$arr2= explode("/",$edate);
	$date1 = mktime($stime,0,0,$arr[1],$arr[2],$arr[0]);
	$date2 = mktime($etime,0,0,$arr2[1],$arr2[2],$arr2[0]);

	$time = $date2-$date1;
	$time = ($time/60)/60; //렌트 시간

	$bdate= $date1;

	//기본 가격
	$sql = "select * from ez_rentcar_setting where tid=$tid and period_s='' and period_e='' order by id_no desc limit 1";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	$price_origin_basic = $rs[price_origin];
	$price_basic = $rs[price];

	While($bdate<=$date2){
		$bdate2 = Date("Y/m/d",$bdate);

		If($bdate2!=$sdate && $bdate2!=$edate) $usetime= 24;
		ElseIf($bdate2==$sdate) $usetime = 24-$stime;
		ElseIf($bdate2==$edate) $usetime = $etime;

		If($sdate==$edate) $usetime = $etime-$stime;

		$sql = "select * from ez_rentcar_setting where tid=$tid and period_s<='$bdate2' and period_e>='$bdate2' ";
		list($rows) = $dbo->query($sql);
		If($rows){
			$rs=$dbo->next_record();
			$PRICE_ORIGIN += $rs[price_origin] * $usetime;
			$PRICE += $rs[price] * $usetime;
		}else{
			$PRICE_ORIGIN += $price_origin_basic * $usetime;
			$PRICE += $price_basic * $usetime;
		}

		$bdate += 86400;
	}

	$rate = ($PRICE && $PRICE_ORIGIN)? round(100-(($PRICE/$PRICE_ORIGIN)*100),0):0;

	//checkVar("time",$time);
	//checkVar("PRICE_ORIGIN",$PRICE_ORIGIN);
	//checkVar("PRICE",$PRICE);

	$result["origin"] = $PRICE_ORIGIN;
	$result["price"] = $PRICE;
	$result["rate"] = $rate;
	$result["time"] = $time;

	return $result;

}

Function get_rent_time($sdate,$edate,$stime,$etime){


	//렌트 시간
	$arr= explode("/",$sdate);
	$arr2= explode("/",$edate);
	$date1 = mktime($stime,0,0,$arr[1],$arr[2],$arr[0]);
	$date2 = mktime($etime,0,0,$arr2[1],$arr2[2],$arr2[0]);

	$time = $date2-$date1;
	$time = ($time/60)/60; //렌트 시간

	Return $time;

}

/*숙박 가격계산  - 나만의 여행 만들기,레일카텔*/
Function get_hotel_price($tid,$days,$subject,$hotel_room=1,$addqty=0){
	Global  $info;
	Global  $HOLIDAY;

	$dbo = new MiniDB($info);
	$PRICE_ORIGIN=0;
	$PRICE=0;

	If($days){
		$arr = explode(",",$days);

		For($i=0; $i < count($arr);$i++){
			$w = get_order_week($arr[$i],1); //공휴일 전전일은 체크X

			If($w==9) $col = "week_8"; //공휴일 전일
			elseIf($w==8) $col = "week_7"; //공휴일
			elseIf($w==7 || $w==6) $col = "week_6"; //주말
			Else $col = "week_normal"; //평일

			$sql  = "select $col as price,week_origin,qty_max,qty_min,qty_add from ez_hotel_room where tid=$tid and subject='$subject' and period_s<='$arr[$i]' and period_e>='$arr[$i]' order by id_no desc limit 1";
			$dbo->query($sql);
			$rs=$dbo->next_record();
			$PRICE_ORIGIN += $rs[week_origin];
			$PRICE += $rs[price]*$hotel_room;
			If($addqty){
			  $addqty = (($addqty+$rs[qty_min])>$rs[qty_max])?$rs[qty_max]:$addqty;
			  $PRICE +=$rs[qty_add]*$addqty;
			}

			If(!$rs[price]){
				$PRICE=0;
				break;
			}
		}
	}

	$result["origin"] = $PRICE_ORIGIN;
	$result["price"] = $PRICE;

	Return $result;
}


/*평균값 더하기 include_price_table_hotel.php*/
Function get_avg_plus($val,$avg){

	$val = str_replace(",","",$val);

	$result = $val + ($val*$avg);

	$result = number_format($result);
	$result = substr($result,0,-3) . "000";

	Return $result;

}


function get_air($code){
	$code = substr($code,0,2);
	if($code=="AC") $air_name="에어캐나다";
	elseif($code=="AF") $air_name="에어프랑스";
	elseif($code=="AY") $air_name="핀에어";
	elseif($code=="BR") $air_name="에바항공";
	elseif($code=="CA") $air_name="중국국제항공";
	elseif($code=="CX") $air_name="케세이퍼시픽항공";
	elseif($code=="DL") $air_name="델타항공";
	elseif($code=="FM") $air_name="상해항공";
	elseif($code=="GA") $air_name="가루다인도네시아항공";
	elseif($code=="IR") $air_name="이란항공";
	elseif($code=="LH") $air_name="루프트한자항공";
	elseif($code=="MH") $air_name="말레이시아항공";
	elseif($code=="MU") $air_name="중국동방항공";
	elseif($code=="NW") $air_name="노스웨스트항공";
	elseif($code=="QF") $air_name="콴타스항공";
	elseif($code=="KE") $air_name="대한항공";
	elseif($code=="OZ") $air_name="아시아나항공";
	elseif($code=="BX") $air_name="에어부산";
	elseif($code=="LJ") $air_name="진에어항공";
	elseif($code=="7C") $air_name="제주항공";
	elseif($code=="ZE") $air_name="이스타항공";
	elseif($code=="TW") $air_name="티웨이항공";
	elseif($code=="NX") $air_name="에어마카오";
	elseif($code=="PR") $air_name="필리핀항공";
	elseif($code=="QR") $air_name="카타르항공";
	elseif($code=="SU") $air_name="러시아항공";
	elseif($code=="TK") $air_name="터키항공";
	elseif($code=="UA") $air_name="유나이티드항공";
	elseif($code=="U2") $air_name="이지젯항공";
	elseif($code=="QV") $air_name="라오스항공";
	elseif($code=="SQ") $air_name="싱가폴항공";
	elseif($code=="TG") $air_name="타이항공";
	elseif($code=="FV") $air_name="에어아시아";
	elseif($code=="5J") $air_name="세부퍼시픽항공";
	elseif($code=="AE") $air_name="만다린항공";
	elseif($code=="AI") $air_name="인도항공";
	elseif($code=="AZ") $air_name="이탈리아항공";
	elseif($code=="CI") $air_name="중화항공";
	elseif($code=="CZ") $air_name="중국남방항공";
	elseif($code=="EK") $air_name="에미레이트항공";
	elseif($code=="FV") $air_name="에어러시아";
	elseif($code=="JL") $air_name="일본항공";
	elseif($code=="KL") $air_name="네덜란드항공";
	elseif($code=="MS") $air_name="이집트항공";
	elseif($code=="NH") $air_name="전일본공수";	
	elseif($code=="NZ") $air_name="에어뉴질랜드";	
	return $air_name;
}


function my_tag($str,$bit=""){

	$css_golf="font-weight:bold;background-color:yellow;line-height:130%";
	$css_color1="font-weight:bold;background-color:#86B404;line-height:130%";
	$css_color2="font-weight:bold;background-color:#81BEF7;line-height:130%";

	$str = str_replace("[","<span style='$css_golf'>",$str);
	$str = str_replace("]","</span>",$str);

	$str = str_replace("<<","<span style='$css_color1'>",$str);
	$str = str_replace(">>","</span>",$str);

	$str = str_replace("((","<span style='$css_color2'>",$str);
	$str = str_replace("))","</span>",$str);


	return $str;
}
?>