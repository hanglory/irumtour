<?
/*ez_order_select 초기화*/
Function reset_order_select($tid,$oid){
	Global  $info;
	$dbo = new MiniDB($info);

	$sql = "delete from ez_order_select where tid=$tid and oid=$oid";
	If($tid && $oid) $dbo->query($sql);

}

/*ez_order_select 합계*/
Function sum_order_select($tid,$oid,$bit=0){
	Global  $info;
	$dbo = new MiniDB($info);
	$price=0;
	$qty=0;

	$sql = "select sum(price) as price,sum(qty) as qty,select_type from ez_order_select where tid=$tid and oid=$oid group by select_type ";
	$dbo->query($sql);
	While($rs=$dbo->next_record()){
		$price += $rs[price];
		$qty +=($rs[select_type]=="option")? 0: $rs[qty] ;
	}

	If($bit){
		$result = $price;
	}else{
		$result[price] = $price;
		$result[qty] = $qty;
	}

	Return $result;

}

/*ez_order_select의 라인 합계*/
Function sum_order_select_line($tid,$oid,$seq){
	Global $info;
	Global $select_type;
	$dbo = new MiniDB($info);

	$seq2 = substr($seq,0,3);

	$sql = "select sum(price) as price from ez_order_select where tid=$tid and oid=$oid and select_type='$select_type' and left(seq,3)=$seq2 ";
	$dbo->query($sql);
	$rs=$dbo->next_record();

	Return $rs[price];

}


/*************포인트*************/

/*사용가능한 포인트*/
Function able_point(){
	Global  $info;
	Global  $sessMember;
	Global  $REMOTE_ADDR;
	$dbo = new MiniDB($info);

	$status = "'행사완료','인보이스'";
	$point_m=0;
	$status = iconv("utf-8","euc-kr",$status);

	$sql = "select sum(point) as point from ez_order where id='$sessMember[id]' and bit=1 and status in ($status) ";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	$point_tour = $rs[point];

	$sql = "select sum(point) as point from ez_special_point where id='$sessMember[id]'";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	$point_event = $rs[point];

	$point_m = used_point();

	$result = ($point_tour+$point_event) -$point_m;

	Return $result;

}
 /*사용한 포인트*/
Function used_point(){
	Global  $info;
	Global  $sessMember;
	Global  $REMOTE_ADDR;
	$dbo = new MiniDB($info);

	$sql = "select sum(use_point) as point from ez_order where id='$sessMember[id]' and bit=1 ";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	$point_tour = $rs[point];

	$sql = "select sum(use_point) as point from ez_special_point where id='$sessMember[id]'";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	$point_event = $rs[point];

	$result = $point_tour+$point_event;

	Return $result;

}

?>