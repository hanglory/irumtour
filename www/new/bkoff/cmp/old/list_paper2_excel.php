<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"보고서");


$dtype=($dtype)? $dtype : "d_date";



$date_s = ($date_s)? $date_s : $PAPER_DEFAULT_DAY1;
$date_e = ($date_e)? $date_e : $PAPER_DEFAULT_DAY2;

$year_this = substr($date_s,0,4);
$year_prev = substr($date_s,0,4)-1;
$date_s2 = $year_prev . substr($date_s,4);
$date_e2 = $year_prev . substr($date_e,4);


if(substr($date_s,0,4)!=substr($date_e,0,4)){
	error("검색하시는 시작일의 년도와 종료일의 연도가 같아야 합니다.");
	exit;
}



if($REMOTE_ADDR!="106.246.54.27"){
$xls_name = "report1_" . date("Ymd") . ".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/vnd.ms-excel; charset=euc-kr");
header("Content-Disposition: attachment;filename=$xls_name;");
header( "Content-Description: PHP4 Generated Data" );
}

$TEN=1;
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
#tbl_cmp_list{border-collapse:collapse;border:1px solid #000 }
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:right;padding-right:2px;}
#tbl_cmp_list td{font-size:9pt;border-left:1px solid #000;border-bottom:1px solid #000;}
#tbl_cmp_list tH{font-size:9pt;border-left:1px solid #000;border-bottom:1px solid #000}
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="padding:10px">



	<?
	if($year){
		$YEAR_PREV = date("Y/m/d",mktime(0,0,0,1,1,$year-1));
		$YEAR_THIS = date("Y/m/d",mktime(0,0,0,1,1,$year+1)-1);
	}

	$VIEW_PATH .= ",기타2";
	$paths = explode(",",$VIEW_PATH);
	$arr="";

	$total = "1";

	if($staff) $filter = " and main_staff = '$staff' ";


	$sql = "
		select
			left($dtype,4) as did,
			sum(people) as sum_people,
			sum(fee) as sum_fee
			from cmp_reservation
		where
			($dtype >= '$date_s' and $dtype <='$date_e')
			or
			($dtype >= '$date_s2' and $dtype <='$date_e2')
		group by left($dtype,4)
	";
	$dbo->query($sql);
	//checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){
		$did = $rs[did];
		$did3 = ($did3)? $did3 : "기타2";
		$DATA2[$did]["people"] = $rs[sum_people];
		$DATA2[$did]["fee"] = $rs[sum_fee];
	}

	$sql = "
		select
			main_staff as did,
			left($dtype,4) as did2,
			view_path as did3,
			sum(people) as sum_people,
			sum(fee) as sum_fee
			from cmp_reservation
		where
			(($dtype >= '$date_s' and $dtype <='$date_e')
			or
			($dtype >= '$date_s2' and $dtype <='$date_e2'))
			$filter
		group by main_staff,view_path,left($dtype,4)
	";
	$dbo->query($sql);
	//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){
		$did = $rs[did];
		$did2 = $rs[did2];
		$did3 = $rs[did3];
		$did3 = ($did3)? $did3 : "기타2";
		$DATA[$did][$did2][$did3]["people"] = $rs[sum_people];
		$DATA[$did][$did2][$did3]["fee"] = $rs[sum_fee];

		$arr[] = $rs[did];
	}

	$arr = @array_unique($arr);

	?>


    <table border="0" cellspacing="0" cellpadding="3" width="3500" id="tbl_cmp_list" style="border-collapse:collapse;border:1px solid #000 ">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
			<th class="subject" >구분</th>
			<th class="subject" >기간</th>
			<th class="subject" colspan="2">신규</th>
			<th class="subject" colspan="2">재방문</th>
			<th class="subject" colspan="2">추천</th>
			<th class="subject" colspan="2">기타</th>
			<th class="subject" >계</th>
			<th class="subject" >비율</th>
			<th class="subject" >매출</th>
			<th class="subject" >객단가</th>
		</tr>
		<?
		$sum1_1 = 0;
		$sum1_2 = 0;
		$sum1_3 = 0;
		$sum1_4 = 0;
		$sum1_5 = 0;
		$sum1_6 = 0;
		$sum1_7 = 0;
		$sum2_1 = 0;
		$sum2_2 = 0;
		$sum2_3 = 0;
		$sum2_4 = 0;
		$sum2_5 = 0;
		$sum2_6 = 0;
		$sum2_7 = 0;

		$sum1_p1=0;
		$sum2_p1=0;

		while(list($key,$val)=@each($arr)){
			$arr2 = explode("(",$val);
			$did = $val;
			$did2 = $year_this;

			$sum_this1=0;
			$sum_this2=0;

			$sum_this1=0;
			for($i=0;$i<count($paths);$i++){
				$sum_this1+=$DATA[$did][$did2][$paths[$i]]["people"];
				$sum_this2+=$DATA[$did][$did2][$paths[$i]]["fee"];
			}

			$x = @round(($sum_this1 / $DATA2[$did2]["people"])*100,2);
			$id = substr($arr2[1],0,-1);


			$sum1_1 += $DATA[$did][$did2]["신규"]["people"];
			$sum1_2 += $DATA[$did][$did2]["재방문"]["people"];
			$sum1_3 += $DATA[$did][$did2]["추천"]["people"];
			$sum1_4 += $DATA[$did][$did2]["기타"]["people"]+$DATA[$did][$did2]["기타2"]["people"];
			$sum1_5 += $sum_this1;
			$sum1_6 += $sum_this2;
			$sum1_7 += @($sum_this2/$sum_this1);
			$sum1_p1 +=$sum_this1;

		?>
		<tr>
			<td rowspan="2" style="background-color:#f0f0f0"><?=trim($arr2[0])?></td>
			<td>금년</td>
			<td><?=nf($DATA[$did][$did2]["신규"]["people"])?>명</td>
			<td><?=@round(($DATA[$did][$did2]["신규"]["people"]/$sum_this1)*100,2)?>%</td>
			<td><?=nf($DATA[$did][$did2]["재방문"]["people"])?>명</td>
			<td><?=@round(($DATA[$did][$did2]["재방문"]["people"]/$sum_this1)*100,2)?>%</td>
			<td><?=nf($DATA[$did][$did2]["추천"]["people"])?>명</td>
			<td><?=@round(($DATA[$did][$did2]["추천"]["people"]/$sum_this1)*100,2)?>%</td>
			<td><?=nf($DATA[$did][$did2]["기타"]["people"]+$DATA[$did][$did2]["기타2"]["people"])?>명</td>
			<td><?=@round((($DATA[$did][$did2]["기타"]["people"]+$DATA[$did][$did2]["기타2"]["people"])/$sum_this1)*100,2)?>%</td>
			<td><?=nf($sum_this1)?>명</td>
			<td><?=$x?>%</td>
			<td class="r"><?=@nf($sum_this2)?>원</td>
			<td class="r"><?=@nf($sum_this2/$sum_this1)?>원</td>
		</tr>

		<?
			$did2 = $year_prev;
			$sum_prev1=0;
			$sum_prev2=0;
			for($i=0;$i<count($paths);$i++){
				$sum_prev1+=$DATA[$did][$did2][$paths[$i]]["people"];
				$sum_prev2+=$DATA[$did][$did2][$paths[$i]]["fee"];
			}

			$x = @round(($sum_prev1 / $DATA2[$did2]["people"])*100,2);

			$sum2_1 += $DATA[$did][$did2]["신규"]["people"];
			$sum2_2 += $DATA[$did][$did2]["재방문"]["people"];
			$sum2_3 += $DATA[$did][$did2]["추천"]["people"];
			$sum2_4 += $DATA[$did][$did2]["기타"]["people"]+$DATA[$did][$did2]["기타2"]["people"];
			$sum2_5 += $sum_prev1;
			$sum2_6 += $sum_prev2;
			$sum2_7 += @($sum_prev2/$sum_prev1);
			$sum2_p1 +=$sum_prev1;

		?>
		<tr style="background-color:#f0f0f0">
			<td>작년</td>
			<td><?=nf($DATA[$did][$did2]["신규"]["people"])?>명</td>
			<td><?=@round(($DATA[$did][$did2]["신규"]["people"]/$sum_prev1)*100,2)?>%</td>
			<td><?=nf($DATA[$did][$did2]["재방문"]["people"])?>명</td>
			<td><?=@round(($DATA[$did][$did2]["재방문"]["people"]/$sum_prev1)*100,2)?>%</td>
			<td><?=nf($DATA[$did][$did2]["추천"]["people"])?>명</td>
			<td><?=@round(($DATA[$did][$did2]["추천"]["people"]/$sum_prev1)*100,2)?>%</td>
			<td><?=nf($DATA[$did][$did2]["기타"]["people"]+$DATA[$did][$did2]["기타2"]["people"])?>명</td>
			<td><?=@round((($DATA[$did][$did2]["기타"]["people"]+$DATA[$did][$did2]["기타2"]["people"])/$sum_prev1)*100,2)?>%</td>
			<td><?=nf($sum_prev1)?>명</td>
			<td><?=$x?>%</td>
			<td class="r"><?=@nf($sum_prev2)?>원</td>
			<td class="r"><?=@nf($sum_prev2/$sum_prev1)?>원</td>
		</tr>
		<?}?>




		<tr style="background-color:#ffe6cc">
			<td rowspan="2" style="background-color:#ffe6cc">합계</td>
			<td>금년</td>
			<td><?=nf($sum1_1)?>명</td>
			<td></td>
			<td><?=nf($sum1_2)?>명</td>
			<td></td>
			<td><?=nf($sum1_3)?>명</td>
			<td></td>
			<td><?=nf($sum1_4)?>명</td>
			<td></td>
			<td><?=nf($sum1_5)?>명</td>
			<td></td>
			<td class="r"><?=nf($sum1_6)?>원</td>
			<td class="r"><?=nf($sum1_7)?>원</td>
		</tr>
		<tr  style="background-color:#ffe6cc">
			<td>작년</td>
			<td><?=nf($sum2_1)?>명</td>
			<td></td>
			<td><?=nf($sum2_2)?>명</td>
			<td></td>
			<td><?=nf($sum2_3)?>명</td>
			<td></td>
			<td><?=nf($sum2_4)?>명</td>
			<td></td>
			<td><?=nf($sum2_5)?>명</td>
			<td></td>
			<td class="r"><?=nf($sum2_6)?>원</td>
			<td class="r"><?=nf($sum2_7)?>원</td>
		</tr>

	</table>



</div>

</body>
</html>
