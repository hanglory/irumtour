<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"통계");
chk_power($_SESSION["sessLogin"]["proof"],"엑셀다운로드");

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


if(!strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
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

    $arr="";

    $total = "1";

    if($ctype){$filter = " and b.nation = '$ctype'";}


    $sql = "
        select
            left(a.$dtype,4) as did,
            sum(a.people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
            from cmp_reservation as a left join cmp_golf as b
            on a.golf_id_no=b.id_no
        where
            ((a.$dtype >= '$date_s' and a.$dtype <='$date_e')
            or
            (a.$dtype >= '$date_s2' and a.$dtype <='$date_e2'))
            $filter
            and b.city<>''
        group by left(a.$dtype,4)
    ";
    $dbo->query($sql);
    //checkVar(mysql_error(),$sql);
    while($rs=$dbo->next_record()){
        $did = $rs[did];
        $DATA2[$did]["people"] = $rs[sum_people];
        $DATA2[$did]["fee"] = $rs[sum_fee];
    }

    $sql = "
        select
            b.city as did,
            left(a.$dtype,4) as did2,
            sum(a.people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
            from cmp_reservation as a left join cmp_golf as b
            on a.golf_id_no=b.id_no
        where
            ((a.$dtype >= '$date_s' and a.$dtype <='$date_e')
            or
            (a.$dtype >= '$date_s2' and a.$dtype <='$date_e2'))
            $filter
            and b.city<>''
        group by b.nation,b.city,left(a.$dtype,4)
    ";
    $dbo->query($sql);
    //if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql);
    while($rs=$dbo->next_record()){
        $did = $rs[did];
        $did2 = $rs[did2];
        $DATA[$did][$did2]["people"] = $rs[sum_people];
        $DATA[$did][$did2]["fee"] = $rs[sum_fee];

        $arr[] = $rs[did];
    }

    @$arr = array_unique($arr);
    @sort($arr);

    ?>



    <table border="0" cellspacing="0" cellpadding="3" width="3500" id="tbl_cmp_list" style="border-collapse:collapse;border:1px solid #000 ">

        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="subject">구분</th>
            <th class="subject">기간</th>
            <th class="subject">인원</th>
            <th class="subject">비율</th>
            <th class="subject">매출</th>
            <th class="subject">객단가</th>
        </tr>
        <?
        $total_this1=0;
        $total_this2=0;
        $total_prev1=0;
        $total_prev2=0;
        while(list($key,$val)=each($arr)){
            $sum_this1=0;
            $sum_this2=0;
            $sum_prev1=0;
            $sum_prev2=0;

            $did = $val;
            $did2 = $year_this;

            $sum_this1+=$DATA[$did][$did2]["people"];
            $sum_this2+=$DATA[$did][$did2]["fee"];


            $x = @round(($DATA[$did][$did2]["people"] / $DATA2[$did2]["people"])*100,2);
            $id = substr($arr2[1],0,-1);

            //checkVar($sum_this1,$DATA[$did][$did2]["people"]);
        ?>
        <tr>
            <td rowspan="2" style="background-color:#f0f0f0;text-align:center;"><?=$val?></td>
            <td style="text-align:center">금년</td>
            <td><?=nf($DATA[$did][$did2]["people"])?>명</td>
            <td><?=$x?>%</td>
            <td class="r"><?=@nf($sum_this2)?>원</td>
            <td class="r"><?=@nf($sum_this2/$sum_this1)?>원</td>
        </tr>

        <?
            $did2 = $year_prev;
            $sum_prev1+=$DATA[$did][$did2]["people"];
            $sum_prev2+=$DATA[$did][$did2]["fee"];

            $x = @round(($DATA[$did][$did2]["people"] / $DATA2[$did2]["people"])*100,2);
        ?>
        <tr style="background-color:#f0f0f0">
            <td style="text-align:center">작년</td>
            <td><?=nf($DATA[$did][$did2]["people"])?>명</td>
            <td><?=$x?>%</td>
            <td class="r"><?=@nf($sum_prev2)?>원</td>
            <td class="r"><?=@nf($sum_prev2/$sum_prev1)?>원</td>
        </tr>
        <?
        $total_this1+=$sum_this1;
        $total_this2+=$sum_this2;
        $total_prev1+=$sum_prev1;
        $total_prev2+=$sum_prev2;

        }
        ?>


        <tr style="background-color:#ffe6cc">
            <td rowspan="2" style="text-align:center">합계</td>
            <td style="text-align:center">금년</td>
            <td><?=nf($total_this1)?>명</td>
            <td><?=$x?>%</td>
            <td class="r"><?=@nf($total_this2)?>원</td>
            <td class="r"><?=@nf($total_this2/$total_this1)?>원</td>
        </tr>

        <tr style="background-color:#ffe6cc">
            <td style="text-align:center">작년</td>
            <td><?=nf($total_prev1)?>명</td>
            <td><?=$x?>%</td>
            <td class="r"><?=@nf($total_prev2)?>원</td>
            <td class="r"><?=@nf($total_prev2/$total_prev1)?>원</td>
        </tr>
    </table>

</div>

</body>
</html>
