<?

/******************************************************
	장바구니 item 담기
	사용법 : itemAdd(code,option);

	code: 상품코드
	name : 상품이름
	quantify : 상품 수량
	price : 상품가격
	mileage : 상품 마일리지
	option : 상품 옵션
	present : 사은품
	payment : 결재방법제한 (1 모두가능, 2 현금만)
*******************************************************/

function itemADD($code,$name,$quantity,$price,$mileage,$option,$present='',$payment='',$delivery_price=0,$delivery_ctg=''){

	global $sessCart;
	$addQuantity = 0;

	$price = str_replace(",","",$price);
	$mileage = str_replace(",","",$mileage);

	// 쇼핑카트 생성
	if(!session_is_registered("sessCart")){
		session_register("sessCart");
	}


	//前 처리
	$quantity=($quantity)? $quantity: 1;

	$j=0;
	for($i=0; $i<count($option); $i++){//옵션을 하나의 변수에 저장
		$allOption .= $option[$i];
		$optionPrice += substr($option[$i],strpos($option[$i],"{div}")+5,-5);
	}

	//쇼핑카트에 저장
	if($code){

		//같은 상품 확인 (단 옵션이 다를 경우는 다른 상품으로 간주) + 배송비 확인

		if(count($sessCart)){

			while(list($i) = each($sessCart)){

				//같은상품 확인
				if($sessCart[$i]["code"]==$code && $sessCart[$i]["option"]==$allOption ){

					$sessCart[$i]["quantity"] += $quantity;

					$addQuantity = 1;

				}

			}
		}

		//카트에 담기

		if(!$addQuantity){	 //수량만 변경한 경우에는 담지 않는다

			$sessCart[] = array(
				"code" => $code,
				"name" => $name,
				"quantity" => $quantity,
				"price" => $price,
				"optionPrice"=> $optionPrice,
				"option"=> $allOption,
				"mileage" => $mileage,
				"present" => $present,
				"payment" => $payment,
				"delivPrice" => $delivery_price,
				"delivCtg" => $delivery_ctg,
				);

		}

	}//endif



	return $addQuantity;

}


/******************************************************
	장바구니 item 삭제
	사용법 : itemDrop(drop_num);
*******************************************************/
function itemDrop($drop_num){

	global $sessCart;

	if(count($drop_num)){

		for($i=0; $i < count($drop_num); $i++ ):
			unset($sessCart[$drop_num[$i]]);
		endfor;

	}
}


/******************************************************
	장바구니 item 모두 삭제
	사용법 : itemAllDrop();
*******************************************************/
function itemAllDrop(){

	global $sessCart;

	session_unregister("sessCart");

	unset($sessCart);
}




/******************************************************
	장바구니 상품 수량 변경
	사용법 : itemChkQuantity(item_no,수량);
*******************************************************/
function itemChkQuantity($item_no,$new_quantity){

	global $sessCart;

	$new_quantity = ($new_quantity < 1)? 1: $new_quantity;


	$sessCart[$item_no[$i]]["quantity"]= $new_quantity;

}


/******************************************************
	바로구매용 장바구니 item 담기
	사용법 : itemAdd(code,option);

	code: 상품코드
	name : 상품이름
	quantify : 상품 수량
	price : 상품가격
	mileage : 상품 마일리지
	option : 상품 옵션
	present : 사은품
*******************************************************/
function itemDirectADD($code,$name,$quantity,$price,$mileage,$option,$present='',$payment='',$delivery_price=0,$delivery_ctg=''){

	global $sessDirectCart;

	$addQuantity = 0;

	$price = str_replace(",","",$price);
	$mileage = str_replace(",","",$mileage);

	// 쇼핑카트 생성
	session_register("sessDirectCart");


	//前 처리
	$quantity=($quantity)? $quantity: 1;

	$j=0;
	for($i=0; $i<count($option); $i++){//옵션을 하나의 변수에 저장
		$allOption .= $option[$i];
		$optionPrice += substr($option[$i],strpos($option[$i],"{div}")+5,-5);
	}

	//쇼핑카트에 저장
	if($code){

		//카트에 담기

		$sessDirectCart[0] = array(
			"code" => $code,
			"name" => $name,
			"quantity" => $quantity,
			"price" => $price,
			"optionPrice"=> $optionPrice,
			"option"=> $allOption,
			"mileage" => $mileage,
			"present" => $present,
			"payment" => $payment,
			"delivPrice" => $delivery_price,
			"delivCtg" => $delivery_ctg,
			);

	}//endif



	return $addQuantity;

}


?>