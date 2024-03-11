<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");

$dtype=($dtype)? $dtype : "tour_date";
$year =($year)? $year : date("Y");
$previousYear = $year - 1;

/*
if($REMOTE_ADDR=="106.246.54.27"){
    $sql = "update cmp_reservation set view_path='기타' where view_path not in ('신규','재방문','추천','기타')";
    //$sql = "select * from  cmp_reservation where view_path not in ('신규','재방문','추천','기타')";
    list($rows) = $dbo->query($sql);
    checkVar($rows.mysql_error(),$sql);
}
*/


$sql = "update cmp_golf set bit_oversea='해외' where nation<>'한국'";
$dbo->query($sql);

$sql = "update cmp_golf set bit_oversea='국내' where nation='한국'";
$dbo->query($sql);


$date_s = ($date_s)? $date_s : "${year}/01/01";
$date_e = ($date_e)? $date_e : "${year}/12/31";

$p_date_s = "${previousYear}/01/01";
$p_date_e = "${previousYear}/12/31";

$year_this = substr($date_s,0,4);
$year_prev = substr($date_s,0,4)-1;
$date_s2 = $year_prev . substr($date_s,4);
$date_e2 = $year_prev . substr($date_e,4);

$arr_year_sum = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
$arr_pyear_sum = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
$arr_person_year_sum = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
$arr_person_pyear_sum = array(0,0,0,0,0,0,0,0,0,0,0,0,0);
$sum_year_pp = 0;
$sum_pyear_pp = 0;


if(substr($date_s,0,4)!=substr($date_e,0,4)){
    error("검색하시는 시작일의 년도와 종료일의 연도가 같아야 합니다.");
    exit;
}


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "기간별매출(국내/해외)";
$TITLE .=($dtype=="d_date")? "(출국일자 기준)" : "(예약일자 기준)";


if($year==date("Y")){
    $cm = ceil(date("m",strtotime(date("Y/m/d")." -0 month")));
    for($i=1;$i<=12;$i++){
        $bit_show[$i]=($i>$cm)?"":"show";
    }
}else{
    for($i=1;$i<=12;$i++){
        $bit_show[$i]="show";
    }
}

?>
<?include("../top.html");?>
<style type="text/css">
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:center;padding-right:2px;width:6%;}
.r{padding-right:5px !important}
</style>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?>

        </td>
      </tr>
      <tr>
        <td> </td>
      </tr>
       <tr>
        <td background="../images/common/bg_title.gif" height="5"></td>
      </tr>
    </table>

    <br/>


    <!--내용이 들어가는 곳 시작-->

    <!-- Search Begin------------------------------------------------>
    <div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
    <form name=fmSearch method="get">
    <input type=hidden name='position' value="">
    <input type=hidden name='ctg1' value="<?=$ctg1?>">


    <tr height=22>
    <td valign='bottom' align=right>



    <input type="text" name="year" id="year" size="6" maxlength="4" value="<?=$year?>" class="box c">년

    &nbsp;

    <?
    /*
    $STAFF1="";$STAFF2="";
    $sql = "select * from cmp_staff where bit_hide<>1 order by name asc";
    $dbo->query($sql);
    while($rs=$dbo->next_record()){
        $STAFF1.=",$rs[name]";
        $STAFF2.=",$rs[name] ($rs[id])";
    }
    */
    ?>
    <!-- <select name="staff">
        <?=option_str("담당자 전체".$STAFF1,$STAFF2,$staff)?>
    </select> -->

    <select name="dtype" style="width:130px">
        <?=option_str("예약일기준,출국일기준","tour_date,d_date",$dtype)?>
    </select>

    <input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
    </td>
    <tr>
    </form>
    </table>
    </div>
    <!-- Search End------------------------------------------------>




<h1>매출</h1>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="subject" >구분</th>
            <th class="subject" >연도</th>
            <? for ($i = 1; $i <= 12; $i++){
                echo "<th class='subject'>". $i."월</th>";
            }
?>
            <th class="subject" >합계</th>
            <th class="subject" >객단가</th>
        </tr>
        <tr>
            <td style="background-color:#f0f0f0" rowspan="2">미구분</td>
            <!--미구분 올해-->
<?
$sql1 = "
        select
            bit_oversea as did,
            right(left($dtype,7),2) as did2,
            sum(people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
        from cmp_reservation as a left join cmp_golf as b
        on a.golf_id_no=b.id_no
        where
            ($dtype >= '$date_s' and $dtype <='$date_e')
            $filter
            and bit_oversea is null
            $FILTER_PARTNER_QUERY_CPID
        group by b.bit_oversea,right(left($dtype,7),2)
    ";

$sql2 ="
SELECT
    bit_oversea AS did,
    DATE_FORMAT($dtype, '%Y/%m') AS did2,
    SUM(people) AS sum_people,
    SUM(
        (SELECT SUM((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund))
        FROM cmp_people
        WHERE code = a.code AND bit = 1)
    ) AS sum_fee
FROM cmp_reservation AS a
LEFT JOIN cmp_golf AS b ON a.golf_id_no = b.id_no
WHERE
        ($dtype >= '$date_s' and $dtype <='$date_e')
        $filter
        $FILTER_PARTNER_QUERY_CPID
GROUP BY bit_oversea, DATE_FORMAT(tour_date, '%Y/%m')
ORDER BY   
   		bit_oversea ASC,
   		bit_oversea IS NULL, -- null이 가장 나중에 나오도록 함
			SUBSTRING(did2, 1, 4) DESC, 
			did2 ASC";

$dbo->query($sql1);
            echo "<td>금년</td>";
            for($month_cnt=1;$month_cnt<=12;$month_cnt++){
                $rs=$dbo->next_record();
                for($month_cnt;$rs[did2] > $month_cnt; $month_cnt++){
                    echo "<td>0</td>";
                }
                if($rs[did2] == $month_cnt){
                    echo "<td>".@nf($rs[sum_fee])."</td>";
                }
                else{ // 달이 12월 전에 끝나는경우
                    echo "<td>0</td>";
                }
                $sum_money += $rs[sum_fee];
                $sum_person += $rs[sum_people];
                $arr_year_sum[$month_cnt] = $rs[sum_fee];

            }
$sum_year_pp = round($sum_money/$sum_person,0);
?>
            <td style="background-color:#f0f0f0" class="r"><?=nf($sum_money)?></td>
            <td style="background-color:#f0f0f0" class="r"><?=nf(round($sum_money/$sum_person, 0))?></td>

        </tr>
        <tr>
            <!--미구분 전년-->
            <?
            $sql1 = "
        select
            bit_oversea as did,
            right(left($dtype,7),2) as did2,
            sum(people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
        from cmp_reservation as a left join cmp_golf as b
        on a.golf_id_no=b.id_no
        where
            ($dtype >= '$p_date_s' and $dtype <='$p_date_e')
            $filter
            and bit_oversea is null
            $FILTER_PARTNER_QUERY_CPID
        group by b.bit_oversea,right(left($dtype,7),2)
    ";
            $dbo->query($sql1);
            if($debug) checkVar(mysql_error(),$sql1);
            echo "<td>전년</td>";
            $sum_person = $sum_money = 0;

            for($month_cnt=1;$month_cnt<=12;$month_cnt++){
                $rs=$dbo->next_record();
                for($month_cnt;$rs[did2] > $month_cnt; $month_cnt++){
                    echo "<td>0</td>";
                }
                if($rs[did2] == $month_cnt){
                    echo "<td>".nf($rs[sum_fee])."</td>";
                }
                else{
                    echo "<td>0</td>";
                }
                $sum_money += $rs[sum_fee];
                $sum_person += $rs[sum_people];
                $arr_pyear_sum[$month_cnt] = $rs[sum_fee];
            }
$sum_pyear_pp = round($sum_money/$sum_person,0);
            ?>
            <td style="background-color:#f0f0f0" class="r"><?=nf($sum_money)?></td>
            <td style="background-color:#f0f0f0" class="r"><?=nf(round($sum_money/$sum_person, 0))?></td>

        </tr>
        <tr>
            <td style="background-color:#f0f0f0" rowspan="2">국내</td>

            <?
            $sql1 = "
        select
            bit_oversea as did,
            right(left($dtype,7),2) as did2,
            sum(people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
        from cmp_reservation as a left join cmp_golf as b
        on a.golf_id_no=b.id_no
        where
            ($dtype >= '$date_s' and $dtype <='$date_e')
            $filter
            and bit_oversea = '국내'
            $FILTER_PARTNER_QUERY_CPID
        group by b.bit_oversea,right(left($dtype,7),2)
    ";
            $dbo->query($sql1);
            echo "<td>금년</td>";
            $sum_person = $sum_money = 0;
            for($month_cnt=1;$month_cnt<=12;$month_cnt++){
                $rs=$dbo->next_record();
                for($month_cnt;$rs[did2] > $month_cnt; $month_cnt++){
                    echo "<td>0</td>";
                }
                if($rs[did2] == $month_cnt){
                    echo "<td>".nf($rs[sum_fee])."</td>";
                }
                else{ // 달이 12월 전에 끝나는경우
                    echo "<td>0</td>";
                }
                $sum_money += $rs[sum_fee];
                $sum_person += $rs[sum_people];
                $arr_year_sum[$month_cnt] += $rs[sum_fee];
            }
$sum_year_pp += round($sum_money/$sum_person,0);
            ?>
            <td style="background-color:#f0f0f0" class="r"><?=nf($sum_money)?></td>
            <td style="background-color:#f0f0f0" class="r"><?=nf(round($sum_money/$sum_person, 0))?></td>

        </tr>
        <tr>

            <?
            $sql1 = "
        select
            bit_oversea as did,
            right(left($dtype,7),2) as did2,
            sum(people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
        from cmp_reservation as a left join cmp_golf as b
        on a.golf_id_no=b.id_no
        where
            ($dtype >= '$p_date_s' and $dtype <='$p_date_e')
            $filter
            and bit_oversea = '국내'
            $FILTER_PARTNER_QUERY_CPID
        group by b.bit_oversea,right(left($dtype,7),2)
    ";
            $dbo->query($sql1);
            if($debug) checkVar(mysql_error(),$sql1);
            echo "<td>전년</td>";
            $sum_person = $sum_money = 0;

            for($month_cnt=1;$month_cnt<=12;$month_cnt++){
                $rs=$dbo->next_record();
                for($month_cnt;$rs[did2] > $month_cnt; $month_cnt++){
                    echo "<td>0</td>";
                }
                if($rs[did2] == $month_cnt){
                    echo "<td>".nf($rs[sum_fee])."</td>";
                }
                else{
                    echo "<td>0</td>";
                }
                $sum_money += $rs[sum_fee];
                $sum_person += $rs[sum_people];
                $arr_pyear_sum[$month_cnt] += $rs[sum_fee];
            }
            $sum_pyear_pp += round($sum_money/$sum_person,0);
            ?>
            <td style="background-color:#f0f0f0" class="r"><?=nf($sum_money)?></td>
            <td style="background-color:#f0f0f0" class="r"><?=nf(round($sum_money/$sum_person, 0))?></td>

        </tr>
        <tr>
            <td style="background-color:#f0f0f0" rowspan="2">해외</td>

            <?
            $sql1 = "
        select
            bit_oversea as did,
            right(left($dtype,7),2) as did2,
            sum(people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
        from cmp_reservation as a left join cmp_golf as b
        on a.golf_id_no=b.id_no
        where
            ($dtype >= '$date_s' and $dtype <='$date_e')
            $filter
            and bit_oversea = '해외'
            $FILTER_PARTNER_QUERY_CPID
        group by b.bit_oversea,right(left($dtype,7),2)
    ";
            $dbo->query($sql1);
            echo "<td>금년</td>";
            $sum_person = $sum_money = 0;
            for($month_cnt=1;$month_cnt<=12;$month_cnt++){
                $rs=$dbo->next_record();
                for($month_cnt;$rs[did2] > $month_cnt; $month_cnt++){
                    echo "<td>0</td>";
                }
                if($rs[did2] == $month_cnt){
                    echo "<td>".nf($rs[sum_fee])."</td>";
                }
                else{ // 달이 12월 전에 끝나는경우
                    echo "<td>0</td>";
                }
                $sum_money += $rs[sum_fee];
                $sum_person += $rs[sum_people];
                $arr_year_sum[$month_cnt] += $rs[sum_fee];
            }
            $sum_year_pp += round($sum_money/$sum_person,0);
            ?>
            <td style="background-color:#f0f0f0" class="r"><?=nf($sum_money)?></td>
            <td style="background-color:#f0f0f0" class="r"><?=nf(round($sum_money/$sum_person, 0))?></td>

        </tr>
        <tr>

            <?
            $sql1 = "
        select
            bit_oversea as did,
            right(left($dtype,7),2) as did2,
            sum(people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
        from cmp_reservation as a left join cmp_golf as b
        on a.golf_id_no=b.id_no
        where
            ($dtype >= '$p_date_s' and $dtype <='$p_date_e')
            $filter
            and bit_oversea = '해외'
            $FILTER_PARTNER_QUERY_CPID
        group by b.bit_oversea,right(left($dtype,7),2)
    ";
            $dbo->query($sql1);
            if($debug) checkVar(mysql_error(),$sql1);
            echo "<td>전년</td>";
            $sum_person = $sum_money = 0;

            for($month_cnt=1;$month_cnt<=12;$month_cnt++){
                $rs=$dbo->next_record();
                for($month_cnt;$rs[did2] > $month_cnt; $month_cnt++){
                    echo "<td>0</td>";
                }
                if($rs[did2] == $month_cnt){
                    echo "<td>".nf($rs[sum_fee])."</td>";
                }
                else{
                    echo "<td>0</td>";
                }
                $sum_money += $rs[sum_fee];
                $sum_person += $rs[sum_people];
                $arr_pyear_sum[$month_cnt] += $rs[sum_fee];
            }
            $sum_pyear_pp += round($sum_money/$sum_person,0);
            ?>
            <td style="background-color:#f0f0f0" class="r"><?=nf($sum_money)?></td>
            <td style="background-color:#f0f0f0" class="r"><?=nf(round($sum_money/$sum_person, 0))?></td>

        </tr>
        <tr style="background-color:#ffe6cc">
            <td rowspan="2">합계</td>
            <td >금년</td>
            <? for($i=1;$i<13;$i++){
                echo "<td>".nf($arr_year_sum[$i])."</td>";
            }
            ?>
            <td class="r"><?=nf(sumArrayValues($arr_year_sum))?></td>
            <td class="r"><?=nf($sum_year_pp)?></td>
        </tr>
        <tr style="background-color:#ffe6cc">
            <td>전년</td>
            <? for($i=1;$i<13;$i++){
                echo "<td>".nf($arr_pyear_sum[$i])."</td>";
            }
            ?>
            <td class="r"><?=nf(sumArrayValues($arr_pyear_sum))?></td>
            <td class="r"><?=nf($sum_pyear_pp)?></td>
        </tr>
    </table>

<br>
<h1>인원</h1>

<table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

    <tr align=center height=25 bgcolor="#F7F7F6">
        <th class="subject" >구분</th>
        <th class="subject" >구분</th>
        <? for ($i = 1; $i <= 12; $i++){
            echo "<th class='subject'>". $i."월</th>";
        }
        ?>
        <th class="subject" >합계</th>
        <th class="subject" >객단가</th>
    </tr>
    <tr>
        <td style="background-color:#f0f0f0" rowspan="2">미구분</td>

        <?
        $sql1 = "
        select
            bit_oversea as did,
            right(left($dtype,7),2) as did2,
            sum(people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
        from cmp_reservation as a left join cmp_golf as b
        on a.golf_id_no=b.id_no
        where
            ($dtype >= '$date_s' and $dtype <='$date_e')
            $filter
            and bit_oversea is null
            $FILTER_PARTNER_QUERY_CPID
        group by b.bit_oversea,right(left($dtype,7),2)
    ";
        $dbo->query($sql1);
        echo "<td>금년</td>";
        $sum_person = $sum_money = 0;
        for($month_cnt=1;$month_cnt<=12;$month_cnt++){
            $rs=$dbo->next_record();
            for($month_cnt;$rs[did2] > $month_cnt; $month_cnt++){
                echo "<td>0</td>";
            }
            if($rs[did2] == $month_cnt){
                echo "<td>".@nf($rs[sum_people])."</td>";
            }
            else{ // 달이 12월 전에 끝나는경우
                echo "<td>0</td>";
            }
            $sum_money += $rs[sum_fee];
            $sum_person += $rs[sum_people];
            $arr_person_year_sum[$month_cnt] = $rs[sum_people];
        }

        ?>
        <td style="background-color:#f0f0f0" class="r"><?=nf($sum_person)?></td>
        <td style="background-color:#f0f0f0" class="r"><?=nf(round($sum_money/$sum_person, 0))?></td>

    </tr>
    <tr>

        <?
        $sql1 = "
        select
            bit_oversea as did,
            right(left($dtype,7),2) as did2,
            sum(people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
        from cmp_reservation as a left join cmp_golf as b
        on a.golf_id_no=b.id_no
        where
            ($dtype >= '$p_date_s' and $dtype <='$p_date_e')
            $filter
            and bit_oversea is null
            $FILTER_PARTNER_QUERY_CPID
        group by b.bit_oversea,right(left($dtype,7),2)
    ";
        $dbo->query($sql1);
        if($debug) checkVar(mysql_error(),$sql1);
        echo "<td>전년</td>";
        $sum_person = $sum_money = 0;

        for($month_cnt=1;$month_cnt<=12;$month_cnt++){
            $rs=$dbo->next_record();
            for($month_cnt;$rs[did2] > $month_cnt; $month_cnt++){
                echo "<td>0</td>";
            }
            if($rs[did2] == $month_cnt){
                echo "<td>".nf($rs[sum_people])."</td>";
            }
            else{
                echo "<td>0</td>";
            }
            $sum_money += $rs[sum_fee];
            $sum_person += $rs[sum_people];
            $arr_person_pyear_sum[$month_cnt] = $rs[sum_people];
        }

        ?>
        <td style="background-color:#f0f0f0" class="r"><?=nf($sum_person)?></td>
        <td style="background-color:#f0f0f0" class="r"><?=nf(round($sum_money/$sum_person, 0))?></td>

    </tr>
    <tr>
        <td style="background-color:#f0f0f0" rowspan="2">국내</td>

        <?
        $sql1 = "
        select
            bit_oversea as did,
            right(left($dtype,7),2) as did2,
            sum(people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
        from cmp_reservation as a left join cmp_golf as b
        on a.golf_id_no=b.id_no
        where
            ($dtype >= '$date_s' and $dtype <='$date_e')
            $filter
            and bit_oversea = '국내'
            $FILTER_PARTNER_QUERY_CPID
        group by b.bit_oversea,right(left($dtype,7),2)
    ";
        $dbo->query($sql1);
        echo "<td>금년</td>";
        $sum_person = $sum_money = 0;
        for($month_cnt=1;$month_cnt<=12;$month_cnt++){
            $rs=$dbo->next_record();
            for($month_cnt;$rs[did2] > $month_cnt; $month_cnt++){
                echo "<td>0</td>";
            }
            if($rs[did2] == $month_cnt){
                echo "<td>".nf($rs[sum_people])."</td>";
            }
            else{ // 달이 12월 전에 끝나는경우
                echo "<td>0</td>";
            }
            $sum_money += $rs[sum_fee];
            $sum_person += $rs[sum_people];
            $arr_person_year_sum[$month_cnt] += $rs[sum_people];
        }

        ?>
        <td style="background-color:#f0f0f0" class="r"><?=nf($sum_person)?></td>
        <td style="background-color:#f0f0f0" class="r"><?=nf(round($sum_money/$sum_person, 0))?></td>

    </tr>
    <tr>

        <?
        $sql1 = "
        select
            bit_oversea as did,
            right(left($dtype,7),2) as did2,
            sum(people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
        from cmp_reservation as a left join cmp_golf as b
        on a.golf_id_no=b.id_no
        where
            ($dtype >= '$p_date_s' and $dtype <='$p_date_e')
            $filter
            and bit_oversea = '국내'
            $FILTER_PARTNER_QUERY_CPID
        group by b.bit_oversea,right(left($dtype,7),2)
    ";
        $dbo->query($sql1);
        if($debug) checkVar(mysql_error(),$sql1);
        echo "<td>전년</td>";
        $sum_person = $sum_money = 0;

        for($month_cnt=1;$month_cnt<=12;$month_cnt++){
            $rs=$dbo->next_record();
            for($month_cnt;$rs[did2] > $month_cnt; $month_cnt++){
                echo "<td>0</td>";
            }
            if($rs[did2] == $month_cnt){
                echo "<td>".nf($rs[sum_people])."</td>";
            }
            else{
                echo "<td>0</td>";
            }
            $sum_money += $rs[sum_fee];
            $sum_person += $rs[sum_people];
            $arr_person_pyear_sum[$month_cnt] += $rs[sum_people];
        }

        ?>
        <td style="background-color:#f0f0f0" class="r"><?=nf($sum_person)?></td>
        <td style="background-color:#f0f0f0" class="r"><?=nf(round($sum_money/$sum_person, 0))?></td>

    </tr>
    <tr>
        <td style="background-color:#f0f0f0" rowspan="2">해외</td>

        <?
        $sql1 = "
        select
            bit_oversea as did,
            right(left($dtype,7),2) as did2,
            sum(people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
        from cmp_reservation as a left join cmp_golf as b
        on a.golf_id_no=b.id_no
        where
            ($dtype >= '$date_s' and $dtype <='$date_e')
            $filter
            and bit_oversea = '해외'
            $FILTER_PARTNER_QUERY_CPID
        group by b.bit_oversea,right(left($dtype,7),2)
    ";
        $dbo->query($sql1);
        echo "<td>금년</td>";
        $sum_person = $sum_money = 0;
        for($month_cnt=1;$month_cnt<=12;$month_cnt++){
            $rs=$dbo->next_record();
            for($month_cnt;$rs[did2] > $month_cnt; $month_cnt++){
                echo "<td>0</td>";
            }
            if($rs[did2] == $month_cnt){
                echo "<td>".nf($rs[sum_people])."</td>";
            }
            else{ // 달이 12월 전에 끝나는경우
                echo "<td>0</td>";
            }
            $sum_money += $rs[sum_fee];
            $sum_person += $rs[sum_people];
            $arr_person_year_sum[$month_cnt] += $rs[sum_people];
        }

        ?>
        <td style="background-color:#f0f0f0" class="r"><?=nf($sum_person)?></td>
        <td style="background-color:#f0f0f0" class="r"><?=nf(round($sum_money/$sum_person, 0))?></td>

    </tr>
    <tr>

        <?
        $sql1 = "
        select
            bit_oversea as did,
            right(left($dtype,7),2) as did2,
            sum(people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
        from cmp_reservation as a left join cmp_golf as b
        on a.golf_id_no=b.id_no
        where
            ($dtype >= '$p_date_s' and $dtype <='$p_date_e')
            $filter
            and bit_oversea = '해외'
            $FILTER_PARTNER_QUERY_CPID
        group by b.bit_oversea,right(left($dtype,7),2)
    ";
        $dbo->query($sql1);
        if($debug) checkVar(mysql_error(),$sql1);
        echo "<td>전년</td>";
        $sum_person = $sum_money = 0;

        for($month_cnt=1;$month_cnt<=12;$month_cnt++){
            $rs=$dbo->next_record();
            for($month_cnt;$rs[did2] > $month_cnt; $month_cnt++){
                echo "<td>0</td>";
            }
            if($rs[did2] == $month_cnt){
                echo "<td>".nf($rs[sum_people])."</td>";
            }
            else{
                echo "<td>0</td>";
            }
            $sum_money += $rs[sum_fee];
            $sum_person += $rs[sum_people];
            $arr_person_pyear_sum[$month_cnt] += $rs[sum_people];
        }

        ?>
        <td style="background-color:#f0f0f0" class="r"><?=nf($sum_person)?></td>
        <td style="background-color:#f0f0f0" class="r"><?=nf(round($sum_money/$sum_person, 0))?></td>

    <tr style="background-color:#ffe6cc">
        <td rowspan="2">합계</td>
        <td >금년</td>
        <? for($i=1;$i<13;$i++){
            echo "<td>".nf($arr_person_year_sum[$i])."</td>";
        }
        ?>
        <td class="r"><?=nf(sumArrayValues($arr_person_year_sum))?></td>
        <td class="r"><?=nf($sum_year_pp)?></td>
    </tr>
    <tr style="background-color:#ffe6cc">
        <td>전년</td>
        <? for($i=1;$i<13;$i++){
            echo "<td>".nf($arr_person_pyear_sum[$i])."</td>";
        }
        ?>
        <td class="r"><?=nf(sumArrayValues($arr_person_pyear_sum))?></td>
        <td class="r"><?=nf($sum_pyear_pp)?></td>
    </tr>
    </tr>
</table>
<!--
        <?
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $sum4 = 0;
        $sum5 = 0;
        $sum6 = 0;
        $sum7 = 0;
        $sum8 = 0;
        $sum9 = 0;
        $sum10 = 0;
        $sum11 = 0;
        $sum12 = 0;
        $sum13 = 0;
        $sum14 = 0;

        while(list($key,$val)=@each($arr)){
            $arr2 = explode("(",$val);
            $did = $val;
            $did2 = $year_this;

            $sum_line=0;
            $sum_line2=0;

            if($bit_show[1])    $sum_line=$DATA[$did]["01"]["fee"];
            if($bit_show[2])    $sum_line+=$DATA[$did]["02"]["fee"];
            if($bit_show[3])    $sum_line+=$DATA[$did]["03"]["fee"];
            if($bit_show[4])    $sum_line+=$DATA[$did]["04"]["fee"];
            if($bit_show[5])    $sum_line+=$DATA[$did]["05"]["fee"];
            if($bit_show[6])    $sum_line+=$DATA[$did]["06"]["fee"];
            if($bit_show[7])    $sum_line+=$DATA[$did]["07"]["fee"];
            if($bit_show[8])    $sum_line+=$DATA[$did]["08"]["fee"];
            if($bit_show[9])    $sum_line+=$DATA[$did]["09"]["fee"];
            if($bit_show[10])   $sum_line+=$DATA[$did]["10"]["fee"];
            if($bit_show[11])   $sum_line+=$DATA[$did]["11"]["fee"];
            if($bit_show[12])   $sum_line+=$DATA[$did]["12"]["fee"];

            if($bit_show[1])    $sum_line2=$DATA[$did]["01"]["people"];
            if($bit_show[2])    $sum_line2+=$DATA[$did]["02"]["people"];
            if($bit_show[3])    $sum_line2+=$DATA[$did]["03"]["people"];
            if($bit_show[4])    $sum_line2+=$DATA[$did]["04"]["people"];
            if($bit_show[5])    $sum_line2+=$DATA[$did]["05"]["people"];
            if($bit_show[6])    $sum_line2+=$DATA[$did]["06"]["people"];
            if($bit_show[7])    $sum_line2+=$DATA[$did]["07"]["people"];
            if($bit_show[8])    $sum_line2+=$DATA[$did]["08"]["people"];
            if($bit_show[9])    $sum_line2+=$DATA[$did]["09"]["people"];
            if($bit_show[10])   $sum_line2+=$DATA[$did]["10"]["people"];
            if($bit_show[11])   $sum_line2+=$DATA[$did]["11"]["people"];
            if($bit_show[12])   $sum_line2+=$DATA[$did]["12"]["people"];

            if($bit_show[1])    $sum1 += $DATA[$did]["01"]["fee"];
            if($bit_show[2])    $sum2 += $DATA[$did]["02"]["fee"];
            if($bit_show[3])    $sum3 += $DATA[$did]["03"]["fee"];
            if($bit_show[4])    $sum4 += $DATA[$did]["04"]["fee"];
            if($bit_show[5])    $sum5 += $DATA[$did]["05"]["fee"];
            if($bit_show[6])    $sum6 += $DATA[$did]["06"]["fee"];
            if($bit_show[7])    $sum7 += $DATA[$did]["07"]["fee"];
            if($bit_show[8])    $sum8 += $DATA[$did]["08"]["fee"];
            if($bit_show[9])    $sum9 += $DATA[$did]["09"]["fee"];
            if($bit_show[10])   $sum10 += $DATA[$did]["10"]["fee"];
            if($bit_show[11])   $sum11 += $DATA[$did]["11"]["fee"];
            if($bit_show[12])   $sum12 += $DATA[$did]["12"]["fee"];
            $sum13 += $sum_line;
            $sum14 += @($sum_line/$sum_line2);

        ?>
        <tr>
            <td style="background-color:#f0f0f0" rowspan="2"><?=($arr2[0])?trim($arr2[0]):"미구분"?></td>
            <?if($bit_show[1]){?><td class="month1"><?=nf($DATA[$did]["01"]["fee"])?></td><?}?>
            <?if($bit_show[2]){?><td class="month2"><?=nf($DATA[$did]["02"]["fee"])?></td><?}?>
            <?if($bit_show[3]){?><td class="month3"><?=nf($DATA[$did]["03"]["fee"])?></td><?}?>
            <?if($bit_show[4]){?><td class="month4"><?=nf($DATA[$did]["04"]["fee"])?></td><?}?>
            <?if($bit_show[5]){?><td class="month5"><?=nf($DATA[$did]["05"]["fee"])?></td><?}?>
            <?if($bit_show[6]){?><td class="month6"><?=nf($DATA[$did]["06"]["fee"])?></td><?}?>
            <?if($bit_show[7]){?><td class="month7"><?=nf($DATA[$did]["07"]["fee"])?></td><?}?>
            <?if($bit_show[8]){?><td class="month8"><?=nf($DATA[$did]["08"]["fee"])?></td><?}?>
            <?if($bit_show[9]){?><td class="month9"><?=nf($DATA[$did]["09"]["fee"])?></td><?}?>
            <?if($bit_show[10]){?><td class="month10"><?=nf($DATA[$did]["10"]["fee"])?></td><?}?>
            <?if($bit_show[11]){?><td class="month11"><?=nf($DATA[$did]["11"]["fee"])?></td><?}?>
            <?if($bit_show[12]){?><td class="month12"><?=nf($DATA[$did]["12"]["fee"])?></td><?}?>
            <td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line)?></td>
            <td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line/$sum_line2)?></td>
        </tr>
       <?}?>



        <tr style="background-color:#ffe6cc">
            <td>합계</td>
            <?if($bit_show[1]){?><td class="month1"><?=nf($sum1)?></td><?}?>
            <?if($bit_show[2]){?><td class="month2"><?=nf($sum2)?></td><?}?>
            <?if($bit_show[3]){?><td class="month3"><?=nf($sum3)?></td><?}?>
            <?if($bit_show[4]){?><td class="month4"><?=nf($sum4)?></td><?}?>
            <?if($bit_show[5]){?><td class="month5"><?=nf($sum5)?></td><?}?>
            <?if($bit_show[6]){?><td class="month6"><?=nf($sum6)?></td><?}?>
            <?if($bit_show[7]){?><td class="month7"><?=nf($sum7)?></td><?}?>
            <?if($bit_show[8]){?><td class="month8"><?=nf($sum8)?></td><?}?>
            <?if($bit_show[9]){?><td class="month9"><?=nf($sum9)?></td><?}?>
            <?if($bit_show[10]){?><td class="month10"><?=nf($sum10)?></td><?}?>
            <?if($bit_show[11]){?><td class="month11"><?=nf($sum11)?></td><?}?>
            <?if($bit_show[12]){?><td class="month12"><?=nf($sum12)?></td><?}?>
            <td class="r"><?=nf($sum13)?></td>
            <td class="r"><?=nf($sum14)?></td>
        </tr>


    </table>


    <br/>



    <?
    $sql = "
        select
            bit_oversea as did,
            right(left($dtype,7),2) as did2,
            sum(people) as sum_people,
            sum(
                (select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price 
                from cmp_people 
                where code=a.code and bit=1)
            ) as sum_fee
        from cmp_reservation as a left join cmp_golf as b
        on a.golf_id_no=b.id_no
        where
            ($dtype >= '$date_s' and $dtype <='$date_e')
            $filter
            $FILTER_PARTNER_QUERY_CPID
        group by bit_oversea,right(left($dtype,7),2)
    ";
    $dbo->query($sql);
    //if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql);
    while($rs=$dbo->next_record()){

        //if(!$rs[did]) $rs[did]="xxx";

        $did = $rs[did];
        $did2 = $rs[did2];

        $DATA[$did][$did2]["people"] = $rs[sum_people];
        $DATA[$did][$did2]["fee"] = $rs[sum_fee];
        $arr[] = $rs[did];
    }

    $arr = @array_unique($arr);

    ?>


<h1>인원</h1>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="subject" >구분</th>
            <?if($bit_show[1]){?><th class="subject month1">1월</th><?}?>
            <?if($bit_show[2]){?><th class="subject month2">2월</th><?}?>
            <?if($bit_show[3]){?><th class="subject month3">3월</th><?}?>
            <?if($bit_show[4]){?><th class="subject month4">4월</th><?}?>
            <?if($bit_show[5]){?><th class="subject month5">5월</th><?}?>
            <?if($bit_show[6]){?><th class="subject month6">6월</th><?}?>
            <?if($bit_show[7]){?><th class="subject month7">7월</th><?}?>
            <?if($bit_show[8]){?><th class="subject month8">8월</th><?}?>
            <?if($bit_show[9]){?><th class="subject month9">9월</th><?}?>
            <?if($bit_show[10]){?><th class="subject month10">10월</th><?}?>
            <?if($bit_show[11]){?><th class="subject month11">11월</th><?}?>
            <?if($bit_show[12]){?><th class="subject month12">12월</th><?}?>
            <th class="subject" >합계</th>
            <th class="subject" >객단가</th>
        </tr>
        <?
        $sum1 = 0;
        $sum2 = 0;
        $sum3 = 0;
        $sum4 = 0;
        $sum5 = 0;
        $sum6 = 0;
        $sum7 = 0;
        $sum8 = 0;
        $sum9 = 0;
        $sum10 = 0;
        $sum11 = 0;
        $sum12 = 0;
        $sum13 = 0;
        $sum14 = 0;

        @reset($arr);
        while(list($key,$val)=@each($arr)){
            $arr2 = explode("(",$val);
            $did = $val;
            $did2 = $year_this;

            $sum_line=0;
            $sum_line2=0;

            if($bit_show[1])    $sum_line=$DATA[$did]["01"]["fee"];
            if($bit_show[2])    $sum_line+=$DATA[$did]["02"]["fee"];
            if($bit_show[3])    $sum_line+=$DATA[$did]["03"]["fee"];
            if($bit_show[4])    $sum_line+=$DATA[$did]["04"]["fee"];
            if($bit_show[5])    $sum_line+=$DATA[$did]["05"]["fee"];
            if($bit_show[6])    $sum_line+=$DATA[$did]["06"]["fee"];
            if($bit_show[7])    $sum_line+=$DATA[$did]["07"]["fee"];
            if($bit_show[8])    $sum_line+=$DATA[$did]["08"]["fee"];
            if($bit_show[9])    $sum_line+=$DATA[$did]["09"]["fee"];
            if($bit_show[10])   $sum_line+=$DATA[$did]["10"]["fee"];
            if($bit_show[11])   $sum_line+=$DATA[$did]["11"]["fee"];
            if($bit_show[12])   $sum_line+=$DATA[$did]["12"]["fee"];

            if($bit_show[1])    $sum_line2=$DATA[$did]["01"]["people"];
            if($bit_show[2])    $sum_line2+=$DATA[$did]["02"]["people"];
            if($bit_show[3])    $sum_line2+=$DATA[$did]["03"]["people"];
            if($bit_show[4])    $sum_line2+=$DATA[$did]["04"]["people"];
            if($bit_show[5])    $sum_line2+=$DATA[$did]["05"]["people"];
            if($bit_show[6])    $sum_line2+=$DATA[$did]["06"]["people"];
            if($bit_show[7])    $sum_line2+=$DATA[$did]["07"]["people"];
            if($bit_show[8])    $sum_line2+=$DATA[$did]["08"]["people"];
            if($bit_show[9])    $sum_line2+=$DATA[$did]["09"]["people"];
            if($bit_show[10])   $sum_line2+=$DATA[$did]["10"]["people"];
            if($bit_show[11])   $sum_line2+=$DATA[$did]["11"]["people"];
            if($bit_show[12])   $sum_line2+=$DATA[$did]["12"]["people"];

            if($bit_show[1])    $sum1 += $DATA[$did]["01"]["people"];
            if($bit_show[2])    $sum2 += $DATA[$did]["02"]["people"];
            if($bit_show[3])    $sum3 += $DATA[$did]["03"]["people"];
            if($bit_show[4])    $sum4 += $DATA[$did]["04"]["people"];
            if($bit_show[5])    $sum5 += $DATA[$did]["05"]["people"];
            if($bit_show[6])    $sum6 += $DATA[$did]["06"]["people"];
            if($bit_show[7])    $sum7 += $DATA[$did]["07"]["people"];
            if($bit_show[8])    $sum8 += $DATA[$did]["08"]["people"];
            if($bit_show[9])    $sum9 += $DATA[$did]["09"]["people"];
            if($bit_show[10])   $sum10 += $DATA[$did]["10"]["people"];
            if($bit_show[11])   $sum11 += $DATA[$did]["11"]["people"];
            if($bit_show[12])   $sum12 += $DATA[$did]["12"]["people"];
            $sum13 += $sum_line2;
            $sum14 += @($sum_line/$sum_line2);

        ?>
        <tr>
            <td style="background-color:#f0f0f0"><?=($arr2[0])?trim($arr2[0]):"미구분"?></td>
            <?if($bit_show[1]){?><td><?=nf($DATA[$did]["01"]["people"])?></td><?}?>
            <?if($bit_show[2]){?><td><?=nf($DATA[$did]["02"]["people"])?></td><?}?>
            <?if($bit_show[3]){?><td><?=nf($DATA[$did]["03"]["people"])?></td><?}?>
            <?if($bit_show[4]){?><td><?=nf($DATA[$did]["04"]["people"])?></td><?}?>
            <?if($bit_show[5]){?><td><?=nf($DATA[$did]["05"]["people"])?></td><?}?>
            <?if($bit_show[6]){?><td><?=nf($DATA[$did]["06"]["people"])?></td><?}?>
            <?if($bit_show[7]){?><td><?=nf($DATA[$did]["07"]["people"])?></td><?}?>
            <?if($bit_show[8]){?><td><?=nf($DATA[$did]["08"]["people"])?></td><?}?>
            <?if($bit_show[9]){?><td><?=nf($DATA[$did]["09"]["people"])?></td><?}?>
            <?if($bit_show[10]){?><td><?=nf($DATA[$did]["10"]["people"])?></td><?}?>
            <?if($bit_show[11]){?><td><?=nf($DATA[$did]["11"]["people"])?></td><?}?>
            <?if($bit_show[12]){?><td><?=nf($DATA[$did]["12"]["people"])?></td><?}?>
            <td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line2)?></td>
            <td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line/$sum_line2)?></td>
        </tr>
       <?}?>



        <tr style="background-color:#ffe6cc">
            <td>합계</td>
            <?if($bit_show[1]){?><td class="month1"><?=nf($sum1)?></td><?}?>
            <?if($bit_show[2]){?><td class="month2"><?=nf($sum2)?></td><?}?>
            <?if($bit_show[3]){?><td class="month3"><?=nf($sum3)?></td><?}?>
            <?if($bit_show[4]){?><td class="month4"><?=nf($sum4)?></td><?}?>
            <?if($bit_show[5]){?><td class="month5"><?=nf($sum5)?></td><?}?>
            <?if($bit_show[6]){?><td class="month6"><?=nf($sum6)?></td><?}?>
            <?if($bit_show[7]){?><td class="month7"><?=nf($sum7)?></td><?}?>
            <?if($bit_show[8]){?><td class="month8"><?=nf($sum8)?></td><?}?>
            <?if($bit_show[9]){?><td class="month9"><?=nf($sum9)?></td><?}?>
            <?if($bit_show[10]){?><td class="month10"><?=nf($sum10)?></td><?}?>
            <?if($bit_show[11]){?><td class="month11"><?=nf($sum11)?></td><?}?>
            <?if($bit_show[12]){?><td class="month12"><?=nf($sum12)?></td><?}?>
            <td class="r"><?=nf($sum13)?></td>
            <td class="r"><?=nf($sum14)?></td>
        </tr>


    </table>
-->
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
        <tr>
          <td colspan="12">

          <br>
          <!-- Button Begin---------------------------------------------->
          <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
             <tr>
              <td width="60%" align="left">

              </td>
              <td align="right">
                <span class="btn_pack medium bold"><a href="list_<?=$filecode?>_excel.php?year=<?=$year?>&date_s=<?=$date_s?>&date_e=<?=$date_e?>&dtype=<?=$dtype?>"> 엑셀 </a></span>
              </td>
            </tr>
          </table>
          <!-- Button End------------------------------------------------>

          </td>
        </tr>


    </table>


    <!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
