<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");
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


if(!strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
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
    $sql = "
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
            $FILTER_PARTNER_QUERY_CPID
        group by b.bit_oversea,right(left($dtype,7),2)
    ";
    $dbo->query($sql);
    //if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql);
    while($rs=$dbo->next_record()){

        if(!$rs[did]) $rs[did]="공란";

        $did = $rs[did];
        $did2 = $rs[did2];

        $DATA[$did][$did2]["people"] = $rs[sum_people];
        $DATA[$did][$did2]["fee"] = $rs[sum_fee];
        $arr[] = $rs[did];
    }

    $arr = @array_unique($arr);

    ?>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="subject" >구분</th>
            <?if($bit_show[1]){?><th class="subject">1월</th><?}?>
            <?if($bit_show[2]){?><th class="subject">2월</th><?}?>
            <?if($bit_show[3]){?><th class="subject">3월</th><?}?>
            <?if($bit_show[4]){?><th class="subject">4월</th><?}?>
            <?if($bit_show[5]){?><th class="subject">5월</th><?}?>
            <?if($bit_show[6]){?><th class="subject">6월</th><?}?>
            <?if($bit_show[7]){?><th class="subject">7월</th><?}?>
            <?if($bit_show[8]){?><th class="subject">8월</th><?}?>
            <?if($bit_show[9]){?><th class="subject">9월</th><?}?>
            <?if($bit_show[10]){?><th class="subject">10월</th><?}?>
            <?if($bit_show[11]){?><th class="subject">11월</th><?}?>
            <?if($bit_show[12]){?><th class="subject">12월</th><?}?>
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
            <td style="background-color:#f0f0f0"><?=trim($arr2[0])?></td>
            <?if($bit_show[1]){?><td><?=nf($DATA[$did]["01"]["fee"])?></td><?}?>
            <?if($bit_show[2]){?><td><?=nf($DATA[$did]["02"]["fee"])?></td><?}?>
            <?if($bit_show[3]){?><td><?=nf($DATA[$did]["03"]["fee"])?></td><?}?>
            <?if($bit_show[4]){?><td><?=nf($DATA[$did]["04"]["fee"])?></td><?}?>
            <?if($bit_show[5]){?><td><?=nf($DATA[$did]["05"]["fee"])?></td><?}?>
            <?if($bit_show[6]){?><td><?=nf($DATA[$did]["06"]["fee"])?></td><?}?>
            <?if($bit_show[7]){?><td><?=nf($DATA[$did]["07"]["fee"])?></td><?}?>
            <?if($bit_show[8]){?><td><?=nf($DATA[$did]["08"]["fee"])?></td><?}?>
            <?if($bit_show[9]){?><td><?=nf($DATA[$did]["09"]["fee"])?></td><?}?>
            <?if($bit_show[10]){?><td><?=nf($DATA[$did]["10"]["fee"])?></td><?}?>
            <?if($bit_show[11]){?><td><?=nf($DATA[$did]["11"]["fee"])?></td><?}?>
            <?if($bit_show[12]){?><td><?=nf($DATA[$did]["12"]["fee"])?></td><?}?>
            <td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line)?></td>
            <td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line/$sum_line2)?></td>
        </tr>
       <?}?>



        <tr style="background-color:#ffe6cc">
            <td>합계</td>
            <?if($bit_show[1]){?><td><?=nf($sum1)?></td><?}?>
            <?if($bit_show[2]){?><td><?=nf($sum2)?></td><?}?>
            <?if($bit_show[3]){?><td><?=nf($sum3)?></td><?}?>
            <?if($bit_show[4]){?><td><?=nf($sum4)?></td><?}?>
            <?if($bit_show[5]){?><td><?=nf($sum5)?></td><?}?>
            <?if($bit_show[6]){?><td><?=nf($sum6)?></td><?}?>
            <?if($bit_show[7]){?><td><?=nf($sum7)?></td><?}?>
            <?if($bit_show[8]){?><td><?=nf($sum8)?></td><?}?>
            <?if($bit_show[9]){?><td><?=nf($sum9)?></td><?}?>
            <?if($bit_show[10]){?><td><?=nf($sum10)?></td><?}?>
            <?if($bit_show[11]){?><td><?=nf($sum11)?></td><?}?>
            <?if($bit_show[12]){?><td><?=nf($sum12)?></td><?}?>
            <td class="r"><?=nf($sum13)?></td>
            <td class="r"><?=nf($sum14)?></td>
        </tr>

        <tr style="background-color:#ffff">
            <td>&nbsp;</td>
            <?if($bit_show[1]){?><td></td><?}?>
            <?if($bit_show[2]){?><td></td><?}?>
            <?if($bit_show[3]){?><td></td><?}?>
            <?if($bit_show[4]){?><td></td><?}?>
            <?if($bit_show[5]){?><td></td><?}?>
            <?if($bit_show[6]){?><td></td><?}?>
            <?if($bit_show[7]){?><td></td><?}?>
            <?if($bit_show[8]){?><td></td><?}?>
            <?if($bit_show[9]){?><td></td><?}?>
            <?if($bit_show[10]){?><td></td><?}?>
            <?if($bit_show[11]){?><td></td><?}?>
            <?if($bit_show[12]){?><td></td><?}?>
            <td class="r"></td>
            <td class="r"></td>
        </tr>



    <?
    $sql = "
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
            $FILTER_PARTNER_QUERY_CPID
        group by bit_oversea,right(left($dtype,7),2)
    ";
    $dbo->query($sql);
    //if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql);
    while($rs=$dbo->next_record()){

        if(!$rs[did]) $rs[did]="공란";

        $did = $rs[did];
        $did2 = $rs[did2];

        $DATA[$did][$did2]["people"] = $rs[sum_people];
        $DATA[$did][$did2]["fee"] = $rs[sum_fee];
        $arr[] = $rs[did];
    }

    $arr = @array_unique($arr);

    ?>



        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="subject" >구분</th>
            <?if($bit_show[1]){?><th class="subject">1월</th><?}?>
            <?if($bit_show[2]){?><th class="subject">2월</th><?}?>
            <?if($bit_show[3]){?><th class="subject">3월</th><?}?>
            <?if($bit_show[4]){?><th class="subject">4월</th><?}?>
            <?if($bit_show[5]){?><th class="subject">5월</th><?}?>
            <?if($bit_show[6]){?><th class="subject">6월</th><?}?>
            <?if($bit_show[7]){?><th class="subject">7월</th><?}?>
            <?if($bit_show[8]){?><th class="subject">8월</th><?}?>
            <?if($bit_show[9]){?><th class="subject">9월</th><?}?>
            <?if($bit_show[10]){?><th class="subject">10월</th><?}?>
            <?if($bit_show[11]){?><th class="subject">11월</th><?}?>
            <?if($bit_show[12]){?><th class="subject">12월</th><?}?>
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

        reset($arr);
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
            <td style="background-color:#f0f0f0"><?=trim($arr2[0])?></td>
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
            <?if($bit_show[1]){?><td><?=nf($sum1)?></td><?}?>
            <?if($bit_show[2]){?><td><?=nf($sum2)?></td><?}?>
            <?if($bit_show[3]){?><td><?=nf($sum3)?></td><?}?>
            <?if($bit_show[4]){?><td><?=nf($sum4)?></td><?}?>
            <?if($bit_show[5]){?><td><?=nf($sum5)?></td><?}?>
            <?if($bit_show[6]){?><td><?=nf($sum6)?></td><?}?>
            <?if($bit_show[7]){?><td><?=nf($sum7)?></td><?}?>
            <?if($bit_show[8]){?><td><?=nf($sum8)?></td><?}?>
            <?if($bit_show[9]){?><td><?=nf($sum9)?></td><?}?>
            <?if($bit_show[10]){?><td><?=nf($sum10)?></td><?}?>
            <?if($bit_show[11]){?><td><?=nf($sum11)?></td><?}?>
            <?if($bit_show[12]){?><td><?=nf($sum12)?></td><?}?>
            <td class="r"><?=nf($sum13)?></td>
            <td class="r"><?=nf($sum14)?></td>
        </tr>


    </table>


</div>

</body>
</html>
