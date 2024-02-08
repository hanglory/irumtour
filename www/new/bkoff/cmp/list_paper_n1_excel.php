<?
include_once("../include/common_file.php");


if($partner==1){
	chk_power($_SESSION["sessLogin"]["proof"],"파트너현황");

	$user_id = $_SESSION["sessLogin"]["id"];
	$partner_filter = " and main_staff like '%(${user_id})'";
	$MENU = "cmp_partner";
}
elseif($partner==2){
	chk_power($_SESSION["sessLogin"]["proof"],"통계");
	$sql = "select * from cmp_staff where staff_type in ('staff','leader') ";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
		$partners .="or main_staff like '%($rs[id])%' ";
	}
	$partners = substr($partners,3);
	$partner_filter = " and ($partners)";
	$MENU = "cmp_paper2";
}
else{
	chk_power($_SESSION["sessLogin"]["proof"],"경영관리");
	$MENU = "cmp_paper";
}
//if($staff_partner){error("권한이 없습니다.");exit;}


if($_SESSION["sessLogin"]["staff_type"] == "ceo" && $partner){
    $sql = "select * from cmp_staff where staff_type  in ('partner_a','partner_i','partner_g')";
    $dbo->query($sql);
    while($rs=$dbo->next_record()){
        $partners .="or main_staff like '%($rs[id])%' ";
    }
    $partners = substr($partners,3);
    $partner_filter = " and ($partners)";

}elseif(strstr("partner_a,partner_g",$_SESSION["sessLogin"]["staff_type"])){
    $partner_ab = ($_SESSION["sessLogin"]["staff_type"]=="partner_a")? "'partner_a','partner_g'":"'partner_g'";
    $sql = "select * from cmp_staff where cp_id='$CP_ID' and staff_type in ($partner_ab)";
    $dbo->query($sql);
    while($rs=$dbo->next_record()){
        $partners .="or main_staff like '%($rs[id])%' ";
    }
    $partners = substr($partners,3);
    $partner_filter = " and ($partners)";
}



$dtype=($dtype)? $dtype : "tour_date";
$year =($year)? $year : date("Y");

/*
if($REMOTE_ADDR=="106.246.54.27"){
	$sql = "update cmp_reservation set view_path='기타' where view_path not in ('신규','재방문','추천','기타')";
	//$sql = "select * from  cmp_reservation where view_path not in ('신규','재방문','추천','기타')";
	list($rows) = $dbo->query($sql);
	checkVar($rows.mysql_error(),$sql);
}
*/


$date_s = ($date_s)? $date_s : "${year}/01/01";
$date_e = ($date_e)? $date_e : "${year}/12/31";

$year_this = substr($date_s,0,4);
$year_prev = substr($date_s,0,4)-1;
$date_s2 = $year_prev . substr($date_s,4);
$date_e2 = $year_prev . substr($date_e,4);


if(substr($date_s,0,4)!=substr($date_e,0,4)){
	error("검색하시는 시작일의 년도와 종료일의 연도가 같아야 합니다.");
	exit;
}


$sql = "select * from cmp_staff where bit_hide<>1";
list($rows)=$dbo->query($sql);
//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar($rows.mysql_error(),$sql);}
while($rs=$dbo->next_record()){
	$col = "$rs[name] ($rs[id])";

	$goal = ($dtype=="d_date")? $rs[goal2] : $rs[goal];
	$arr = explode(",",$goal);
	while(list($key,$val)=each($arr)){
		$key2=$key+1;
		$STAFF[$col][$key2]=$val;
	}
}


if(!strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
$xls_name = "report_" . date("Ymd") . ".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/vnd.ms-excel; charset=euc-kr");
header("Content-Disposition: attachment;filename=$xls_name;");
header( "Content-Description: PHP4 Generated Data" );
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<style type="text/css">
#tbl_cmp_list{border-collapse:collapse;border:1px solid #000 }
#tbl_cmp_list th,#tbl_cmp_list td{padding:5px 0 5px 0;text-align:right;padding-right:2px;}
#tbl_cmp_list th,#tbl_cmp_list td{font-size:9pt;border-left:1px solid #000;border-bottom:1px solid #000;}
#tbl_cmp_list th{font-size:9pt;border-left:1px solid #000;border-bottom:1px solid #000;text-align: center}
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="padding:10px">




	<?
	if($year){
		$YEAR_PREV = date("Y/m/d",mktime(0,0,0,1,1,$year-1));
		$YEAR_THIS = date("Y/m/d",mktime(0,0,0,1,1,$year+1)-1);
	}


	$sql = "
		select
			main_staff as did,
			left($dtype,7) as did2,
			sum(people) as sum_people,
			sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
			from cmp_reservation as a
			where
				$dtype >= '$YEAR_PREV'
				and $dtype <='$YEAR_THIS'
				and main_staff<>''
				$partner_filter
                $FILTER_PARTNER_QUERY_CPID
			group by main_staff,left($dtype,7)
	";
	$dbo->query($sql);

	unset($arr);
	//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){
		$did = $rs[did];
		$did2 = $rs[did2];
		$DATA[$did][$did2]["people"] = $rs[sum_people];
		$DATA[$did][$did2]["fee"] = $rs[sum_fee];
		$arr[] = $rs[did];

	}
	$arr = @array_unique($arr);
	?>


    (단위 : 천원)
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

        <tr align=center height=25 bgcolor="#F7F7F6">
        <th class="subject" >구분</th>
        <th class="subject" >구분</th>
        <?
        for($i=1;$i<=12;$i++){
        ?>
        <th class="subject" ><?=$i?>월</th>
        <?}?>
        <th class="subject" >합계</th>
        </tr>




        <?
        @sort($arr);
        while(list($key,$val)=@each($arr)){
            $arr2 = explode("(",$val);
            $did = $val;
            $did2 = $year_this;
        ?>


            <!-- 목표 -->
            <tr align='center'>
              <td height="35" rowspan="10" class="c" style="background-color:#f0f0f0"><?=(trim($arr2[0]))?trim($arr2[0]):"기타"?></td>
              <td class="c" style="background-color:#f0f0f0">목표</td>
            <?
            $sum_prev1=0;
            $sum_prev2=0;
            for($i=1;$i<=12;$i++){
                $sum_prev1+=$STAFF[$did][$i];

                $last_sum1[$i]+=$STAFF[$did][$i];
            ?>
              <td class="r" style="background-color:#f0f0f0"><?=nf($STAFF[$did][$i]/$TEN)?></td>
            <?}?>
              <td style="background-color:#f0f0f0"><?=@nf($sum_prev1/$TEN)?></td>
            </tr>


            <!-- 목표누계 -->
            <tr align='center'>
              <td class="c" style="background-color:#f0f0f0">목표누계</td>
            <?
            $ssum_prev=0;
            unset($ssum_1);
            for($i=1;$i<=12;$i++){
                $ssum_prev+=$STAFF[$did][$i];
                $ssum_1[$i]=$ssum_prev;
            ?>
              <td class="r" style="background-color:#f0f0f0"><?=nf($ssum_prev/$TEN)?></td>
            <?}?>
              <td style="background-color:#f0f0f0"><?=nf($ssum_prev/$TEN)?></td>
            </tr>


            <!-- 금년실적 -->
            <tr align='center'>
              <td class="c">금년실적</td>
            <?
            $sum_this1=0;
            $sum_this2=0;
            for($i=1;$i<=12;$i++){
                $did2 = substr($YEAR_THIS,0,4) . "/" . num2($i);
                $sum_this1+=$DATA[$did][$did2]["people"];
                $sum_this2+=$DATA[$did][$did2]["fee"];

                $last_sum2[$i]+=$DATA[$did][$did2]["fee"];
            ?>
              <td class="r"><?=nf($DATA[$did][$did2]["fee"]/$TEN)?></td>
            <?}?>
              <td><?=@nf($sum_this2/$TEN)?></td>
            </tr>

            <!-- 금년실적누계 -->
            <tr align='center'>
              <td class="c" style="background-color:#f0f0f0">금년실적누계</td>
            <?
            $sum_line1=0;
            $sum_line2=0;
            $sum_line1_=0;
            $sum_line2_=0;
            unset($ssum_2);
            for($i=1;$i<=12;$i++){
                $did2 = substr($YEAR_THIS,0,4) . "/" . num2($i);
                $sum_line1+=$DATA[$did][$did2]["people"];
                if($DATA[$did][$did2]["fee"]>0){
                    $sum_line2+=rnf(nf($DATA[$did][$did2]["fee"]/$TEN));
                    $sum_line2_+=rnf(nf($DATA[$did][$did2]["fee"]));
                }
                else{
                    $sum_line2-=rnf(nf($DATA[$did][$did2]["fee"]/$TEN));
                    $sum_line2_-=rnf(nf($DATA[$did][$did2]["fee"]));
                }
                $total_month[$i]=$sum_line2;

                $ssum_2[$i]=$sum_line2_;
            ?>
              <td class="r" style="background-color:#f0f0f0"><?=nf($sum_line2_/$TEN)?></td>
            <?}?>
              <td style="background-color:#f0f0f0"><?=@nf($sum_line2_/$TEN)?></td>
            </tr>


            <!-- 달성율 -->
            <tr align='center'>
              <td class="c" style="background-color:#f0f0f0">달성율</td>
            <?
            $sum_prev1=0;
            $sum_prev2=0;
            for($i=1;$i<=12;$i++){
                $did2 = substr($YEAR_THIS,0,4) . "/" . num2($i);
                $result1=($STAFF[$did][$i])? $STAFF[$did][$i] : 0;
                $result2=($DATA[$did][$did2]["fee"])?$DATA[$did][$did2]["fee"]:0;
                $result = @round(($result2/$result1)*100,2);

                $sum_prev1+=$result1;
                $sum_prev2+=$result2;

            ?>
              <td class="r" style="background-color:#f0f0f0"><?=($result2)? $result:0?>%</td>
            <?}?>

              <?
              $result_all = @round(($sum_prev2/$sum_prev1)*100,2);
              ?>
              <td style="background-color:#f0f0f0"><?=$result_all?>%</td>
            </tr>



            <!-- 누계 달성율 -->
            <tr align='center'>
              <td class="c" style="background-color:#f0f0f0">누계 달성율</td>
            <?
            $sum_c6_c4=0;
            for($i=1;$i<=12;$i++){
                $did2 = substr($YEAR_THIS,0,4) . "/" . num2($i);
                $sum_c6_c4 = @round((($ssum_2[$i]/$ssum_1[$i])*100),2);

            ?>
              <td class="r" style="background-color:#f0f0f0"><?=$sum_c6_c4?>%</td>
            <?}?>
              <td style="background-color:#f0f0f0"><?=$sum_c6_c4?>%</td>
            </tr>



            <!-- 전년실적 -->
            <tr align='center'>
              <td class="c">전년실적</td>
            <?
            $sum_prev1=0;
            $sum_prev2=0;
            for($i=1;$i<=12;$i++){
                $did2 = substr($YEAR_PREV,0,4) . "/" . num2($i);
                $sum_prev1+=$DATA[$did][$did2]["people"];
                $sum_prev2+=$DATA[$did][$did2]["fee"];

                $last_sum3[$i] += $DATA[$did][$did2]["fee"];
            ?>
              <td class="r"><?=nf($DATA[$did][$did2]["fee"]/$TEN)?></td>
            <?}?>
              <td><?=@nf($sum_prev2/$TEN)?></td>
            </tr>

            <!-- 증가율 -->
            <tr align='center'>
              <td class="c">증가율</td>
            <?
            $sum=0;
            for($i=1;$i<=12;$i++){
                $did_prev = substr($YEAR_PREV,0,4) . "/" . num2($i);
                $did_this = substr($YEAR_THIS,0,4) . "/" . num2($i);

                $x = @(($DATA[$did][$did_this]["fee"]-$DATA[$did][$did_prev]["fee"])/$DATA[$did][$did_prev]["fee"])*100;
                $x = @round($x,2);
            ?>
              <td><?=get_m_color($x)?>%</td>
            <?}?>
              <td><?=@get_m_color(round((($sum_this2-$sum_prev2)/$sum_prev2)*100,2))?>%</td>
            </tr>


            <!-- 전년실적누계 -->
            <tr align='center'>
              <td class="c" style="background-color:#f0f0f0">전년실적누계</td>
            <?
            $sum_2line1=0;
            $sum_2line2=0;
            for($i=1;$i<=12;$i++){
                $did2 = substr($YEAR_PREV,0,4) . "/" . num2($i);
                $sum_2line1+=$DATA[$did][$did2]["people"];
                if($DATA[$did][$did2]["fee"]>0) $sum_2line2+=rnf(nf($DATA[$did][$did2]["fee"]/$TEN));
                else $sum_2line2-=rnf(nf($DATA[$did][$did2]["fee"]/$TEN));
                $total_2month[$i]=$sum_2line2;
            ?>
              <td class="r" style="background-color:#f0f0f0"><?=nf($sum_2line2)?></td>
            <?}?>
              <td style="background-color:#f0f0f0"><?=@nf($sum_2line2)?></td>
            </tr>


            <!-- 누계 증가율 -->
            <tr align='center'>
              <td class="c" style="background-color:#f0f0f0">누계증가율</td>
            <?
            $sum=0;
            for($i=1;$i<=12;$i++){
                $x = @(($total_month[$i]-$total_2month[$i])/$total_2month[$i])*100;
                $x = @round($x,2);
            ?>
              <td style="background-color:#f0f0f0"><?=get_m_color($x)?>%<br></td>
            <?}?>
              <td style="background-color:#f0f0f0"><?=get_m_color($x)?>%</td>
            </tr>

        <?}?>








        <!-- 합계 시작 -->

            <!-- 목표 -->
            <tr align='center'>
              <td height="35" rowspan="10" class="c" style="background-color:#f0f0f0">합계</td>
              <td class="c" style="background-color:#f0f0f0">목표</td>
            <?
            $sum_prev1=0;
            $sum_prev2=0;
            for($i=1;$i<=12;$i++){
                $sum_prev1+=$last_sum1[$i];
            ?>
              <td class="r" style="background-color:#f0f0f0"><?=nf($last_sum1[$i]/$TEN)?></td>
            <?}?>
              <td style="background-color:#f0f0f0"><?=@nf($sum_prev1/$TEN)?></td>
            </tr>


            <!-- 목표누계 -->
            <tr align='center'>
              <td class="c" style="background-color:#f0f0f0">목표누계</td>
            <?
            $ssum_prev=0;
            unset($ssum_1);
            for($i=1;$i<=12;$i++){
                $ssum_prev+=$last_sum1[$i];
                $ssum_1[$i]=$ssum_prev;
            ?>
              <td class="r" style="background-color:#f0f0f0"><?=nf($ssum_prev/$TEN)?></td>
            <?}?>
              <td style="background-color:#f0f0f0"><?=nf($ssum_prev/$TEN)?></td>
            </tr>


            <!-- 금년실적 -->
            <tr align='center'>
              <td class="c">금년실적</td>
            <?
            $sum_this1=0;
            $sum_this2=0;
            for($i=1;$i<=12;$i++){
                $did2 = substr($YEAR_THIS,0,4) . "/" . num2($i);
                $sum_this2+=$last_sum2[$i];
            ?>
              <td class="r"><?=nf($last_sum2[$i]/$TEN)?></td>
            <?}?>
              <td><?=@nf($sum_this2/$TEN)?></td>
            </tr>

            <!-- 금년실적누계 -->
            <tr align='center'>
              <td class="c" style="background-color:#f0f0f0">금년실적누계</td>
            <?
            $sum_line1=0;
            $sum_line2=0;
            $sum_line1_=0;
            $sum_line2_=0;
            unset($ssum_2);
            for($i=1;$i<=12;$i++){
                $did2 = substr($YEAR_THIS,0,4) . "/" . num2($i);
                $sum_line1+=$DATA[$did][$did2]["people"];
                if($last_sum2[$i]>0){
                    $sum_line2+=rnf(nf($last_sum2[$i]/$TEN));
                    $sum_line2_+=rnf(nf($last_sum2[$i]));
                }
                else{
                    $sum_line2-=rnf(nf($last_sum2[$i]/$TEN));
                    $sum_line2_-=rnf(nf($last_sum2[$i]));
                }
                $total_month[$i]=$sum_line2;

                $ssum_2[$i]=$sum_line2_;
            ?>
              <td class="r" style="background-color:#f0f0f0"><?=nf($sum_line2_/$TEN)?></td>
            <?}?>
              <td style="background-color:#f0f0f0"><?=@nf($sum_line2_/$TEN)?></td>
            </tr>


            <!-- 달성율 -->
            <tr align='center'>
              <td class="c" style="background-color:#f0f0f0">달성율</td>
            <?
            $sum_prev1=0;
            $sum_prev2=0;
            for($i=1;$i<=12;$i++){
                $did2 = substr($YEAR_THIS,0,4) . "/" . num2($i);
                $result1=($last_sum2[$i])? $last_sum2[$i] : 0;
                $result2=($last_sum2[$i])?$last_sum2[$i]:0;
                $result = @round(($result2/$result1)*100,2);

                $sum_prev1+=$result1;
                $sum_prev2+=$result2;

            ?>
              <td class="r" style="background-color:#f0f0f0"><?=($result2)? $result:0?>%</td>
            <?}?>

              <?
              $result_all = @round(($sum_prev2/$sum_prev1)*100,2);
              ?>
              <td style="background-color:#f0f0f0"><?=$result_all?>%</td>
            </tr>



            <!-- 누계 달성율 -->
            <tr align='center'>
              <td class="c" style="background-color:#f0f0f0">누계 달성율</td>
            <?
            $sum_c6_c4=0;
            for($i=1;$i<=12;$i++){
                $did2 = substr($YEAR_THIS,0,4) . "/" . num2($i);
                $sum_c6_c4 = @round((($ssum_2[$i]/$ssum_1[$i])*100),2);

            ?>
              <td class="r" style="background-color:#f0f0f0"><?=$sum_c6_c4?>%</td>
            <?}?>
              <td style="background-color:#f0f0f0"><?=$sum_c6_c4?>%</td>
            </tr>



            <!-- 전년실적 -->
            <tr align='center'>
              <td class="c">전년실적</td>
            <?
            $sum_prev1=0;
            $sum_prev2=0;
            for($i=1;$i<=12;$i++){
                $did2 = substr($YEAR_PREV,0,4) . "/" . num2($i);
                $sum_prev1+=$DATA[$did][$did2]["people"];
                $sum_prev2+=$last_sum3[$i];
            ?>
              <td class="r"><?=nf($last_sum3[$i]/$TEN)?></td>
            <?}?>
              <td><?=@nf($sum_prev2/$TEN)?></td>
            </tr>

            <!-- 증가율 -->
            <tr align='center'>
              <td class="c">증가율</td>
            <?
            $sum=0;
            for($i=1;$i<=12;$i++){
                $did_prev = substr($YEAR_PREV,0,4) . "/" . num2($i);
                $did_this = substr($YEAR_THIS,0,4) . "/" . num2($i);

                $x = @(($last_sum2[$i]-$last_sum3[$i])/$last_sum3[$i])*100;
                $x = @round($x,2);
            ?>
              <td><?=get_m_color($x)?>%</td>
            <?}?>
              <td><?=@get_m_color(round((($sum_this2-$sum_prev2)/$sum_prev2)*100,2))?>%</td>
            </tr>


            <!-- 전년실적누계 -->
            <tr align='center'>
              <td class="c" style="background-color:#f0f0f0">전년실적누계</td>
            <?
            $sum_2line1=0;
            $sum_2line2=0;
            for($i=1;$i<=12;$i++){
                $did2 = substr($YEAR_PREV,0,4) . "/" . num2($i);
                $sum_2line1+=$DATA[$did][$did2]["people"];
                if($last_sum3[$i]>0) $sum_2line2+=rnf(nf($last_sum3[$i]/$TEN));
                else $sum_2line2-=rnf(nf($last_sum3[$i]/$TEN));
                $total_2month[$i]=$sum_2line2;
            ?>
              <td class="r" style="background-color:#f0f0f0"><?=nf($sum_2line2)?></td>
            <?}?>
              <td style="background-color:#f0f0f0"><?=@nf($sum_2line2)?></td>
            </tr>


            <!-- 누계 증가율 -->
            <tr align='center'>
              <td class="c" style="background-color:#f0f0f0">누계증가율</td>
            <?
            $sum=0;
            for($i=1;$i<=12;$i++){
                $x = @(($total_month[$i]-$total_2month[$i])/$total_2month[$i])*100;
                $x = @round($x,2);
            ?>
              <td style="background-color:#f0f0f0"><?=get_m_color($x)?>%<br></td>
            <?}?>
              <td style="background-color:#f0f0f0"><?=get_m_color($x)?>%</td>
            </tr>

        <!-- 합계 종료 -->




	</table>


</div>

</body>
</html>
