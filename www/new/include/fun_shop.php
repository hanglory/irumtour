<?

/******************************************************
	현재 위치 링크
	사용법 :  viewPosition($category,$deep)
*******************************************************/
function viewPosition($category,$code){

	global $forFncViewPosition;

	$category = explode("#",substr($category,0,-1));

	$deep = 2;

	for($i=1;$i <= $deep;$i++){

		$mkCtg = $category[$i];

		switch($i){

			case "1":
				$result .= "> $category[$i] ";
				$mkCtgTop = $mkCtg;
				break;

			case "2":
				$result .= "> <a class='blackDot' href='product_list.php?ctg=${mkCtgTop}>${mkCtg}&top_ctg=$mkCtgTop&top_code=$code' onFocus='blur(this)'>$category[$i]</a> ";
				break;

		}

	}

	return $result;

}




/******************************************************
	카테고리 표시
	사용법 :  viewCategory($category,$deep)
*******************************************************/
function viewCategory($category,$deep){

	$category = explode("#",substr($category,0,-1));

	global $top_id;
	global $top_ctg;
	global $info;

	$dbo = new MiniDB($info);

	$dbo -> query("select id_no from category2 where category2='$category[2]' ");
	$rsId=$dbo->next_record();

	$sql ="
		select
		a.category4
		from category4 as a left join category3 as b
		on a.uplevel = b.id_no
		where b.category3='$category[3]'
			and b.uplevel = '$rsId[id_no]'
		";

	$dbo->query($sql);

	while($rs = $dbo ->next_record()){

		$link = "$category[1]#$category[2]#$category[3]#";
		$list .= ($category[$deep]!=$rs[category4])?
			"ㅣ<a class=dark href='".SELF."?top_ctg=$top_ctg&deep=4&ctg=".base64_encode($link.$rs[category4])."' onFocus=blur(this)>$rs[category4]</a>":
			"ㅣ<b>$rs[category4]</b>";
	}

	$result[listCtg] = substr($list,2);

	$result[listCtg] = ($result[listCtg])? "[ $result[listCtg] ]" : $result[listCtg];

	$result[currentCtg] = $category[3];

	return $result;

}

/******************************************************
	2단계용 카테고리 표시
	사용법 :  viewCategory2($category)
*******************************************************/
function viewCategory2($category){

	$category = explode("#",substr($category,0,-1));

	$result[listCtg] = '';

	$result[currentCtg] = $category[2];

	return $result;

}


/******************************************************
	2단계용 카테고리 product_list용
	사용법 :  viewCategory3($top_ctg,top_code)
*******************************************************/
function viewCategory3($top_ctg,$top_code){

	global $info;

	$dbo = new MiniDB($info);

	$sql = "select * from category2 where uplevel='$top_code' order by id_no asc";

	$dbo->query($sql);
	while($rs=$dbo->next_record()){
		$result .="<a class=blackDot href='product_list.php?ctg=${top_ctg}>${rs[category2]}&top_ctg=${top_ctg}&top_code=${top_code}'>${rs[category2]} |</a> ";
	}
	return substr($result,0,-2);

}

/******************************************************
	2단계용 카테고리 product_list용
	사용법 :  viewCategory3_new($top_ctg,top_code)
*******************************************************/
function viewCategory3_new($top_ctg,$top_code){

	global $info;

	$dbo = new MiniDB($info);

	$sql = "select * from category2 where uplevel='$top_code' order by category2 asc";

	$dbo->query($sql);
	while($rs=$dbo->next_record()){
		$result .="<a class=blackDot href='product_list_new.php?ctg=${top_ctg}>${rs[category2]}&top_ctg=${top_ctg}&top_code=${top_code}'>${rs[category2]} |</a> ";
	}
	return substr($result,0,-2);

}


/******************************************************
	최상위 카테고리 알아내기
	사용법 :  getTopCategory($category)
*******************************************************/
function getTopCategory($category){

	$mkCtg1 = substr($category,1);
	$mkCtg2 = substr($mkCtg1,0,strpos($mkCtg1,"#"));

	return $mkCtg2;

}

/******************************************************
	DB의 카테고리를 보기 좋게 표시
	사용법 :  rpCtg($category)
*******************************************************/
function rpCtg($category){

	$result = str_replace('#','>',$category);
	$result = substr($result,1,-1);

	return $result;

}



/******************************************************
	상품의 옵션이 있는 경우 화면에 보여주기 위한 처리
	사용법 :  option($options)
*******************************************************/
function option($options){
	if($options):
		$row=explode("{row}",$options);
		$option_count=count($row)-1;

		for($k=0; $k < $option_count ;$k++){
			$key=explode("{key}",$row[$k]);

			for($h=0; $h < count($key)-1 ;$h++){
				$col=explode("{col}",$key[1]);

				for($s=0; $s < count($col)-1 ;$s++){
					$div=explode("{div}",$col[$s]);
					$option_price=($div[1])? "(+". number_format($div[1])." 원)":"";

					$option[$key[0]] .="<option value='${key[0]}{key}${div[0]}{div}${div[1]}{row}'>$div[0] $option_price\n";
				}
			}
		}
	endif;	 //if($options)

	return $option;
}



/******************************************************
	추가항목의 처리
	사용법 :  item($item)
*******************************************************/
function item($item){
	if($item):

		$row=explode("{row}",$item);

		$key=explode("{key}",$row[0]);

		for($k=0; $k < count($row)-1 ;$k++){
			$key=explode("{key}",$row[$k]);

			$result[$key[0]] = $key[1];

		}
	endif;

	return $result;
}



/******************************************************
	상품의 판매 구분 아이콘으로 보여주기
	사용법 :  viewProductAssort(구분)
*******************************************************/

function viewProductAssort($assort) {

	$iconPath = 'images/icon';

	$assort_array = split(",",$assort);


	if($assort_array[0]>0){

		for($f=0; $f < count($assort_array)-1;$f++){

			$result  .= "\n<img src='".$iconPath."/product_assort".trim($assort_array[$f]).".gif' border=0>";

		}

	}

	return $result;

}







/******************************************************
	Sub main의 이미지 타이틀의 이름 결정
	사용법 :  viewTitleImg(대분류이름)
*******************************************************/

function viewTitleImg($category){

	$assort = explode(",",CATEGORY);

	checkVar("카테고리 전체",CATEGORY,1);

	switch($category){

		case $assort[0]:
			$img_name = "ctg_sub_title_01.jpg";
			break;

		case $assort[1]:
			$img_name = "ctg_sub_title_02.jpg";
			break;

		case $assort[2]:
			$img_name = "ctg_sub_title_03.jpg";
			break;

		case $assort[3]:
			$img_name = "ctg_sub_title_05.jpg";
			break;

		case $assort[4]:
			$img_name = "ctg_sub_title_06.jpg";
			break;

		case $assort[5]:
			$img_name = "ctg_sub_title_08.jpg";
			break;

		case $assort[6]:
			$img_name = "ctg_sub_title_09.jpg";
			break;

		case $assort[7]:
			$img_name = "ctg_sub_title_10.jpg";
			break;

		case $assort[8]:
			$img_name = "ctg_sub_title_11.jpg";
			break;

		case $assort[9]:
			$img_name = "ctg_sub_title_12.jpg";
			break;

		case $assort[10]:
			$img_name = "ctg_sub_title_13.jpg";
			break;

		case "가구/홈패션":
			$img_name = "ctg_sub_title_10.jpg";
			break;

	}

	return $img_name;

}




/******************************************************
	마지막 쇼핑 지역을 기억하기 위한 함수
	사용법 :  setPoint(REQUEST_URI);
*******************************************************/
function setPoint($uri){

	if(!session_is_registered("sessShoppingPoint")){
		session_register("sessShoppingPoint");
	}

	return $uri;
}




/******************************************************
	상품의 조회수를 기록하기 위한 함수
	사용법 :  addHitCount($pid);
*******************************************************/
function addHitCount($pid){

	global $info;
	global $sessClick;

	if(!$sessClick[$pid]){

		$sessClick[$pid]=1;
		$dbo = new MiniDB($info);
		$now = time();

		$sql = "
			update product set
				hit = hit+1
				where product_id = '$pid'
			";

		$dbo -> query($sql);

		$sql = "
			insert into hit_log (
				pid,
				reg_date
				)values(
				'$pid',
				'$now'
				)
			";

		$dbo -> query($sql);

	}

}




/******************************************************
	옵션보기
	사용법 : option_view(옵션);
	리턴값 :	option[option] - 옵션내용, option[optionPrice] - 옵션가격
*******************************************************/

function option_view($options){
	if($options){
		$row=explode("{row}",$options);

		$price =0;
		$plusPrice =0;

		for($i=0;$i < count($row)-1;$i++){

			$price = substr($row[$i],strrpos($row[$i],"{div}")+5);

			$result[optionPrice] +=$price;

			if($price){
				$result[option] .="<br>♠ " . substr(str_replace("{key}",": ",$row[$i]),0,strpos($row[$i],"{div}")-3) . " [<b>추가금액:" . number_format($price)." 원</b>]";
			}else{
				$result[option] .="<br>♠ " .  str_replace("{div}","",str_replace("{key}",": ",$row[$i]));
			}
		}

	}

	return $result;
}

/******************************************************
	옵션보기
	사용법 : option_view4sheet(옵션);
	리턴값 :	option[option] - 옵션내용, option[optionPrice] - 옵션가격
*******************************************************/

function option_view4sheet($options){
	if($options){
		$row=explode("{row}",$options);

		$price =0;
		$plusPrice =0;

		for($i=0;$i < count($row)-1;$i++){

			$price = substr($row[$i],strrpos($row[$i],"{div}")+5);

			$result[optionPrice] +=$price;

			if($price){
				$result[option] .="<br>♠ " . substr(str_replace("{key}",": ",$row[$i]),0,strpos($row[$i],"{div}")-3);
			}else{
				$result[option] .="<br>♠ " .  str_replace("{div}","",str_replace("{key}",": ",$row[$i]));
			}
		}

	}

	return $result;
}

/******************************************************
	사은품보기
	사용법 : present_view(사은품);
*******************************************************/

function present_view($present){
	if($present){

		$result = "<br><font color=#FF00CC>♥ 사은품: " . $present . "</font>";

	}

	return $result;
}





/******************************************************
	주문 상태를 색상으로 구분하여 주는 함수
	사용법 :  setOrderStatsColor(상태)
*******************************************************/

function setOrderStatsColor($status){

	switch ($status){

		case "입금대기":
			$color_no = "#008080";
			break;

		case "배송준비";
			$color_no = "#0000CC";
			break;

		case "배송중":
			$color_no = "#E56C00";
			break;

		case "배송완료":
			$color_no = "#669933";
			break;

		case "주문취소":
			$color_no = "#FF6633";
			break;

		case "반품":
			$color_no = "#FF6633";
			break;

	}

	$result =  "<font color='$color_no'>$status</font>";

	return $result;

}



/******************************************************
	판매 불가능한 상품의 sale 컬럼을 F 로 만들어 주는 함수
	판매 가능한 상품은 sale 컬럼을 T로 만들어 준다
	사용법 :  setSaleStatus()
*******************************************************/
function setSaleStatus(){

	global $info;

	$time = time();

	$dbo = new MiniDB($info);

	$sql = "
		update product set sale = 'T'
			where sale ='F'
				and (	period_start < $time and (period_end > 1 and period_end > $time))
		";

	$dbo -> query($sql);


	$sql = "
		update product set sale = 'T'
			where sale ='F'
				and (limited_quantity > 0 and limited_current < limited_quantity)
		";

	$dbo -> query($sql);


	$sql = "
		update product set sale = 'F'
			where sale ='T'
				and (period_start > $time
				or (period_end > 1 and period_end < $time)
				or (limited_quantity >=1 and limited_current = limited_quantity ))
		";

	$dbo -> query($sql);

}


/******************************************************
	판매가능한 상품인지 조회하기
	사용법 :  getSaleStatus($sale,$period_start,$period_end,$limited_quantity,$limited_current)
*******************************************************/
function getSaleStatus($sale,$period_start,$period_end,$limited_quantity,$limited_current){

	if($sale == 'F'){

		return 0;

	}else{

		$now = time();

		if($period_start > $now) return 0;						//판매시작기간이 시작되지 않음

		elseif($period_end>1 && $period_end < $now) return 0;		//판매시작기간이 종료

		elseif($limited_quantity>1 && $limited_quantity<=$limited_current) return 0;		//한정수량 모두 판매

		else return 1;

		checkVar("result",$result);

	}

}

/******************************************************
	이전상품 상품 코드 구하는 함수
	사용법 :  getPrevGoods(product_id,category)
*******************************************************/
function getPrevGoods($pid,$ctg){

	global $info;

	$sql = "
		select
			product_id
			from product
			where sale ='T'
				and category1 = '$ctg'
				and product_id < $pid
			order by product_id desc
			limit 1
		";


	$dbo = new MiniDB($info);

	$dbo -> query($sql);

	$rs = $dbo->next_record();


	$prev_link = ($rs[product_id])? "location.href='product.php?pid=$rs[product_id]'" : "javascript:alert('현재 카테고리의 처음 상품입니다.')" ;

	return $prev_link;

}



/******************************************************
	다음상품 상품 코드 구하는 함수
	사용법 :  getNextGoods(product_id,category)
*******************************************************/
function getNextGoods($pid,$ctg){

	global $info;

	$sql = "
		select
			product_id
			from product
			where sale ='T'
				and category1 = '$ctg'
				and product_id > $pid
			order by product_id asc
			limit 1
		";


	$dbo = new MiniDB($info);

	$dbo -> query($sql);

	$rs = $dbo->next_record();

	$next_link = ($rs[product_id])? "location.href='product.php?pid=$rs[product_id]'" : "javascript:alert('현재 카테고리의 마지막 상품입니다.')" ;

	return $next_link;

}





/******************************************************
	이미지의 width와 height 구하기
	사용법 :  getWHImg(경로를 포함한 파일이름,최소사이즈)
*******************************************************/

function getWHImg($photo,$width=0,$height=0){

	$file_exist = file_exists($photo);


		if(file_exists($photo)){

			@$pic_info=GetImageSize($photo);	//파일의 정보  - array[0]:너비 array[1]:높이 array[2]:타입(1=gif 2=jpg 3=png)  array[3]: 너비/높이 문자열

			$pic_info[width] =($pic_info[0] > $width && $width)? $width : $pic_info[0];

			$pic_info[height] =($pic_info[1] > $height && $height)? $height : $pic_info[1];


			return $pic_info;

		}

}


/******************************************************
	이미지 파일의 존재여부 확인
	사용법 :  chkPic(size,Product ID)
*******************************************************/

function chkPic($path){

	$photo = $path.".jpg";

	$photo = (file_exists($photo))? $photo : $path . ".gif";

	return $photo;

}


/******************************************************
	배송료 산정하기
	사용법 :  addDelivPrice(category,price)
	리턴값:   카테고리명을 리턴
*******************************************************/

function addDelivPrice($category,$price=0){

	//if($price < 50000){
		//배송비 부과 카테고리
		$delivCtg[] = "#컴퓨터/SW#소프트웨어#";
		$delivCtg[] = "#패션/잡화#패션의류#";
		$delivCtg[] = "#음반/DVD#DVD#";

		for($i=0; $i < count($delivCtg); $i++){

			if (substr($category,0,strlen($delivCtg[$i])) == $delivCtg[$i]) {
				$currCtg=explode("#",$delivCtg[$i]);
				$result = $currCtg[count($currCtg)-2];
				break;
			}
		}
	//}
	return $result;

}
?>