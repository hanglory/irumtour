<?
/*카테고리 이름 확인하기*/
function get_category_name($ctg){

	global  $info;
	$dbo = new MiniDB($info);

	echo $rs[ctg];

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
function get_day_night($date1,$date2){
	$sdate = $date1;
	$i=0;
	while($sdate<=$date2){
		$arr = explode("/",$sdate);
		$sdate = Date("Y/m/d",mktime(0,0,0,$arr[1],$arr[2]+1,$arr[0]));
		$i++;
	}

	$j = $i-1;
	$result =(!$j)? "당일" : "${j}박${i}일";

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

function ico_meal($str){

	global $MOBILE;

	$dir=($MOBILE)? "m2":"renew";

	$str=str_replace("조:","${div}<img src='/${dir}/images/detail/ic_eat01.gif' style='vertical-align:bottom'> ",$str);
	$str=str_replace("중:","${div}<img src='/${dir}/images/detail/ic_eat02.gif' style='vertical-align:bottom'> ",$str);
	$str=str_replace("석:","${div}<img src='/${dir}/images/detail/ic_eat03.gif' style='vertical-align:bottom'> ",$str);
	return $str;
}

?>