<?
/******************************************************
	사용가능한 마일리지 계산하는 함수
	사용법 :  getUsablePoint(아이디)
*******************************************************/
function getUsablePoint($mem_id){

	global $info;
	global $POINT;  //가입축하 마일리지

	$dbo = new MiniDB($info);


	$sql = "select sum(point*num) as sumPoint from banner_order where id='$mem_id' and status='발송완료'";
	$dbo->query($sql);
	$rs = $dbo->next_record();

	$usablePoint =  $POINT + $rs[sumPoint];



	/*
	//적립된 마일리지 불러오기
	$orderPoint = getOrderPoint($mem_id);


	//예치금 불러오기
	//$useDeposit = getUseDeposit($mem_id);


	//이벤트성 마일리지 불러오기
	//$eventPoint = getEventPoint($mem_id);


	//사용한 마일리지 불러오기
	$usePoint = getUsePoint($mem_id);



	//사용가능한 마일리지 계산하기
	$usablePoint = ($orderPoint + $useDeposit + $eventPoint) - $usePoint;
	*/

	return $usablePoint;

}



/******************************************************
	적립된 마일리지 불러오는 함수
	사용법 :  getOrderPoint(아이디)
*******************************************************/
function getOrderPoint($mem_id){

	global $info;

	$dbo = new MiniDB($info);


	$sql = "
		select
			sum(product_mileage * product_quantity) as orderPoint
			from order_log
			where customer_rn='$mem_id' and member_id <>'' and complete_date >1
				and (status <>'반품' and status <> '주문취소')
		";

	$dbo ->query($sql);

	$rs = $dbo -> next_record();

	return $rs[orderPoint];

}


/******************************************************
	사용한 마일리지 불러오는 함수
	사용법 :  getUsePoint(아이디)
*******************************************************/
function getUsePoint($mem_id){

	global $info;

	$dbo = new MiniDB($info);


	$sql = "
			select
				sum(use_mileage) as usePoint

				from order_info
				where order_rn='$mem_id'
					and return_amount = 0
			";


	$dbo ->query($sql);

	$rs = $dbo -> next_record();


	return $rs[usePoint];

}


/******************************************************
	이벤트성 마일리지 불러오는 함수
	사용법 :  getEventPoint(아이디)
*******************************************************/
function getEventPoint($mem_id){


	global $info;

	$dbo = new MiniDB($info);


	$sql = "select sum(mileage) as eventPoint "
				."from event_mileage "
				."where member_id='$mem_id'"
				;

	$dbo ->query($sql);

	$rs = $dbo -> next_record();


	return $rs[eventPoint];

	return 0;
}


/******************************************************
	예치금 불러오는 함수
	사용법 :  getUseDeposit(아이디)
*******************************************************/
function getUseDeposit($mem_id){

	return 0;

}


/******************************************************
	적립된 예정 마일리지
	사용법 :  getNrPoint(아이디번호)
*******************************************************/
function getNrPoint($mem_id){

	global $info;

	$dbo = new MiniDB($info);


	$sql = "
		select
			sum(product_mileage * product_quantity) as orderPoint
			from order_log
			where member_id='$mem_id'
				and complete_date < 1
				and (status <> '주문취소' and status <> '반품')
		";


	$dbo ->query($sql);

	$rs = $dbo -> next_record();

	return $rs[orderPoint];

}
?>