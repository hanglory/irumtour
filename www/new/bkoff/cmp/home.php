<?
include_once("../include/common_file.php");


if($_SESSION["sessLogin"]["staff_type"] == "ceo" && !$partner){
	//echo "1";
	$sql = "select * from cmp_staff where staff_type in ('ceo','staff','partner_a','partner_i','partner_g') and bit_login=1";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
		$partners .="or main_staff like '%($rs[id])%' ";
	}
	$partners = substr($partners,3);
	$partner_filter = " and ($partners)";
}elseif($_SESSION["sessLogin"]["staff_type"] == "ceo" && $partner==1){
	//echo "2";
	$sql = "select * from cmp_staff where staff_type  in ('partner_a','partner_i','partner_g') and bit_login=1";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
		$partners .="or main_staff like '%($rs[id])%' ";
	}
	$partners = substr($partners,3);
	$partner_filter = " and ($partners)";
}elseif(($_SESSION["sessLogin"]["staff_type"] == "staff" || $_SESSION["sessLogin"]["staff_type"] == "ceo") && $partner==2){
	//echo "3";
	$sql = "select * from cmp_staff where staff_type in ('staff','leader') and bit_login=1 and bit_hide<>1";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
		$partners .="or main_staff like '%($rs[id])%' ";
	}
	$partners = substr($partners,3);
	$partner_filter = " and ($partners)";
}else{
	//echo "4";
	$partner_filter = " and main_staff like '%(".$_SESSION["sessLogin"]["id"].")'";
}

$partner_filter .= " and cp_id='$CP_ID'";
?>
<style type="text/css">
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:center;padding-right:2px;width:6%;}
.r{padding-right:5px !important}
</style>


	<?
	$arr="";unset($arr);
	$year_this = date("Y");
	$year_prev = $year_this-1;
	$today = date("Y/m/d");
    $today_year = date("Y/m/d",strtotime(date("Y/m/d")." -1 year"));

	//출국일기준 금년
	$dtype="d_date";
	$year = date("Y");
	$sql = "
		select
			main_staff as did,
			$year as did2,
			sum(people) as sum_people,
			sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
			from cmp_reservation as a
		where
			left($dtype,4) = '$year' and $dtype<='$today'
			$partner_filter
		group by main_staff,left($dtype,4)
	";
	$dbo->query($sql);
	//if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@"))  checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){
		$did = $rs[did];
		$did2 = $rs[did2];

		$DATA[$did][$did2]["people"] = $rs[sum_people];
		$DATA[$did][$did2]["fee"] = $rs[sum_fee];
		$arr[] = $rs[did];

		//checkVar($rs[did],$rs[did2]);
		//checkVar($rs[sum_people],$rs[sum_fee]);
	}
	$arr = @array_unique($arr);


	//예약일기준 금년
	$dtype="tour_date";
	$year = date("Y");
	$sql = "
		select
			main_staff as did,
			$year as did2,
			sum(people) as sum_people,
			sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
			from cmp_reservation as a
		where
			left($dtype,4) = '$year' and $dtype<='$today'
			$partner_filter
		group by main_staff,left($dtype,4)
	";
	$dbo->query($sql);
	//if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){
		$did = $rs[did];
		$did2 = $rs[did2];

		$DATA2[$did][$did2]["people"] = $rs[sum_people];
		$DATA2[$did][$did2]["fee"] = $rs[sum_fee];
		$arr[] = $rs[did];

		//checkVar($rs[did],$rs[did2]);
		//checkVar($rs[sum_people],$rs[sum_fee]);
	}
	$arr = @array_unique($arr);



	//출국일기준 작년
	$dtype="d_date";
	$year = date("Y")-1;
	$sql = "
		select
			main_staff as did,
			$year as did2,
			sum(people) as sum_people,
			sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
			from cmp_reservation as a
		where
			left($dtype,4) = '$year' and $dtype<='$today_year'
			$partner_filter
		group by main_staff,left($dtype,4)
	";
	$dbo->query($sql);
	//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){
		$did = $rs[did];
		$did2 = $rs[did2];

		$DATA_PREV[$did][$did2]["people"] = $rs[sum_people];
		$DATA_PREV[$did][$did2]["fee"] = $rs[sum_fee];
		$arr[] = $rs[did];

		//checkVar($rs[did],$rs[did2]);
		//checkVar($rs[sum_people],$rs[sum_fee]);
	}
	$arr = @array_unique($arr);


	//예약일기준 작년
	$dtype="tour_date";
	$year = date("Y")-1;
	$sql = "
		select
			main_staff as did,
			$year as did2,
			sum(people) as sum_people,
			sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
			from cmp_reservation as a
		where
			left($dtype,4) = '$year' and $dtype<='$today_year'
			$partner_filter
		group by main_staff,left($dtype,4)
	";
	$dbo->query($sql);
	//if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){
		$did = $rs[did];
		$did2 = $rs[did2];

		$DATA_PREV2[$did][$did2]["people"] = $rs[sum_people];
		$DATA_PREV2[$did][$did2]["fee"] = $rs[sum_fee];
		$arr[] = $rs[did];

		//checkVar($rs[did],$rs[did2]);
		//checkVar($rs[sum_people],$rs[sum_fee]);
	}
	$arr = @array_unique($arr);
	?>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

        <tr style="background-color:#ffe6cc">
            <td rowspan="2">합계</td>
            <td>금년</td>
            <td><span id="sum_home_1_1"></span></td>
            <td><span id="sum_home_1_2"></span></td>
            <td><span id="sum_home_1_3"></span></td>
        </tr>

        <tr style="background-color:#ffe6cc">
            <td>작년</td>
            <td><span id="sum_home_2_1"></span></td>
            <td><span id="sum_home_2_2"></span></td>
            <td><span id="sum_home_2_3"></span></td>
        </tr>


	    <tr align=center height=25 bgcolor="#F7F7F6">
			<th class="subject" >담당자</th>
			<th class="subject">기간</th>
			<th class="subject">출국일기준</th>
			<th class="subject">예약일기준</th>
			<th class="subject">매출</th>
		</tr>
		<?
		$sum1 = 0;
		$sum2 = 0;
		$sum3 = 0;
		$sum_prev1 = 0;
		$sum_prev2 = 0;
		$sum_prev3 = 0;

		while(list($key,$val)=@each($arr)){
			$arr2 = explode("(",$val);
			$did = $val;
			$did2 = $year_this;

			$did_prev2 = $did2-1;

			$sum1 += $DATA[$did][$did2]["people"];
			$sum2 += $DATA2[$did][$did2]["people"];
			$sum3 += $DATA[$did][$did2]["fee"];
			$sum_prev1 += $DATA_PREV[$did][$did_prev2]["people"];
			$sum_prev2 += $DATA_PREV2[$did][$did_prev2]["people"];
			$sum_prev3 += $DATA_PREV[$did][$did_prev2]["fee"];

		?>
		<tr>
			<td rowspan="2" style="background-color:#f0f0f0"><?=trim($arr2[0])?></td>
			<td>금년</td>
			<td><?=nf($DATA[$did][$did2]["people"])?></td>
			<td>
				<?=nf($DATA2[$did][$did2]["people"])?>
				<!-- (<?=@round(($DATA2[$did][$did2]["people"]/$DATA[$did][$did2]["people"])*100,2)?>%) -->
			</td>
			<td><?=nf($DATA[$did][$did2]["fee"])?></td>
		</tr>
		<tr>
			<td style="background-color:#f0f0f0">작년</td>
			<td style="background-color:#f0f0f0"><?=nf($DATA_PREV[$did][$did_prev2]["people"])?></td>
			<td style="background-color:#f0f0f0"><?=nf($DATA_PREV2[$did][$did_prev2]["people"])?></td>
			<td style="background-color:#f0f0f0"><?=nf($DATA_PREV[$did][$did_prev2]["fee"])?></td>
		</tr>
	   <?}?>



		<tr style="background-color:#ffe6cc">
			<td rowspan="2">합계</td>
			<td>금년</td>
			<td><?=nf($sum1)?></td>
			<td><?=nf($sum2)?></td>
			<td><?=nf($sum3)?></td>
		</tr>

		<tr style="background-color:#ffe6cc">
			<td>작년</td>
			<td><?=nf($sum_prev1)?></td>
			<td><?=nf($sum_prev2)?></td>
			<td><?=nf($sum_prev3)?></td>
		</tr>


	</table>

    <script>
    $(function(){
        $("#sum_home_1_1").text('<?=nf($sum1)?>');
        $("#sum_home_1_2").text('<?=nf($sum2)?>');
        $("#sum_home_1_3").text('<?=nf($sum3)?>');
        $("#sum_home_2_1").text('<?=nf($sum_prev1)?>');
        $("#sum_home_2_2").text('<?=nf($sum_prev2)?>');
        $("#sum_home_2_3").text('<?=nf($sum_prev3)?>');
    });
    </script>


