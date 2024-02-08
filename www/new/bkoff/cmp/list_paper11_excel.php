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
    //$filter .=" and a.bit_cancel <>1";
    $sql = "
        select
            a.code,
            a.bit_cancel,
            a.view_path as did,
            right(left(a.tour_date,7),2) as did2,
            b.bit_oversea as did3,
            a.people
        from cmp_reservation as a left join cmp_golf as b
        on a.golf_id_no=b.id_no
        where
            ($dtype >= '$date_s' and $dtype <='$date_e')
            $filter
            $FILTER_PARTNER_QUERY_CPID
    ";
    $dbo->query($sql);


    
    //if($debug) checkVar(mysql_error(),$sql);
    while($rs=$dbo->next_record()){

        $sql3 = "
            select
                count(*) as cnt,
                sum(price) as price,
                sum(price_air) as price_air,
                sum(price_land) as price_land,
                sum(price_refund) as price_refund,
                sum(price_prev+price_prev2+price_prev3) as price_prev,
                sum((price_prev+price_prev2+price_prev3)-(price_air+price_land+price_refund)) as fee
            from cmp_people 
            where 
                code=$rs[code] 
                and bit=1";
        $dbo3->query($sql3);
        $rs3= $dbo3->next_record();


        if($rs[bit_cancel] && !$rs3[fee]){//취소 중 매출이 0인 경우 전체 합계에서 제외

        }else{
            if(!$rs[did]) $rs[did]="공란";
            $did = $rs[did];
            $did2 = $rs[did2];
            $did3 = ($rs[did3])? $rs[did3] : "미분류";

            $DATA[$did][$did2][$did3]["people"] += $rs[people];
            $DATA[$did][$did2][$did3]["fee"] += $rs3[fee];
            $arr[] = $rs[did];
        }

        //if($_SERVER["REMOTE_ADDR"]=="106.246.54.27") checkVar($did,$did2);
    }

    $arr = @array_unique($arr);

    ?>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="subject" colspan="2">구분</th>
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

        $i_nation=0;
        $arr_nation = array('국내','해외','미분류');
        @reset($arr);
        while(list($key,$val)=@each($arr)){
            foreach ($arr_nation as $key_nation => $did_nation) {
                $arr2 = explode("(",$val);
                $did = $val;
                $did2 = $year_this;
                $did3 = $did_nation;

                $sum_line[$did3]=0;
                $sum_line2[$did3]=0;

                if($bit_show[1])    $sum_line[$did3]=$DATA[$did]["01"][$did3]["fee"];
                if($bit_show[2])    $sum_line[$did3]+=$DATA[$did]["02"][$did3]["fee"];
                if($bit_show[3])    $sum_line[$did3]+=$DATA[$did]["03"][$did3]["fee"];
                if($bit_show[4])    $sum_line[$did3]+=$DATA[$did]["04"][$did3]["fee"];
                if($bit_show[5])    $sum_line[$did3]+=$DATA[$did]["05"][$did3]["fee"];
                if($bit_show[6])    $sum_line[$did3]+=$DATA[$did]["06"][$did3]["fee"];
                if($bit_show[7])    $sum_line[$did3]+=$DATA[$did]["07"][$did3]["fee"];
                if($bit_show[8])    $sum_line[$did3]+=$DATA[$did]["08"][$did3]["fee"];
                if($bit_show[9])    $sum_line[$did3]+=$DATA[$did]["09"][$did3]["fee"];
                if($bit_show[10])   $sum_line[$did3]+=$DATA[$did]["10"][$did3]["fee"];
                if($bit_show[11])   $sum_line[$did3]+=$DATA[$did]["11"][$did3]["fee"];
                if($bit_show[12])   $sum_line[$did3]+=$DATA[$did]["12"][$did3]["fee"];

                if($bit_show[1])    $sum_line2[$did3]=$DATA[$did]["01"][$did3]["people"];
                if($bit_show[2])    $sum_line2[$did3]+=$DATA[$did]["02"][$did3]["people"];
                if($bit_show[3])    $sum_line2[$did3]+=$DATA[$did]["03"][$did3]["people"];
                if($bit_show[4])    $sum_line2[$did3]+=$DATA[$did]["04"][$did3]["people"];
                if($bit_show[5])    $sum_line2[$did3]+=$DATA[$did]["05"][$did3]["people"];
                if($bit_show[6])    $sum_line2[$did3]+=$DATA[$did]["06"][$did3]["people"];
                if($bit_show[7])    $sum_line2[$did3]+=$DATA[$did]["07"][$did3]["people"];
                if($bit_show[8])    $sum_line2[$did3]+=$DATA[$did]["08"][$did3]["people"];
                if($bit_show[9])    $sum_line2[$did3]+=$DATA[$did]["09"][$did3]["people"];
                if($bit_show[10])   $sum_line2[$did3]+=$DATA[$did]["10"][$did3]["people"];
                if($bit_show[11])   $sum_line2[$did3]+=$DATA[$did]["11"][$did3]["people"];
                if($bit_show[12])   $sum_line2[$did3]+=$DATA[$did]["12"][$did3]["people"];

                if($bit_show[1])    $sum1 += $DATA[$did]["01"][$did3]["fee"];
                if($bit_show[2])    $sum2 += $DATA[$did]["02"][$did3]["fee"];
                if($bit_show[3])    $sum3 += $DATA[$did]["03"][$did3]["fee"];
                if($bit_show[4])    $sum4 += $DATA[$did]["04"][$did3]["fee"];
                if($bit_show[5])    $sum5 += $DATA[$did]["05"][$did3]["fee"];
                if($bit_show[6])    $sum6 += $DATA[$did]["06"][$did3]["fee"];
                if($bit_show[7])    $sum7 += $DATA[$did]["07"][$did3]["fee"];
                if($bit_show[8])    $sum8 += $DATA[$did]["08"][$did3]["fee"];
                if($bit_show[9])    $sum9 += $DATA[$did]["09"][$did3]["fee"];
                if($bit_show[10])   $sum10 += $DATA[$did]["10"][$did3]["fee"];
                if($bit_show[11])   $sum11 += $DATA[$did]["11"][$did3]["fee"];
                if($bit_show[12])   $sum12 += $DATA[$did]["12"][$did3]["fee"];
                $sum13 += $sum_line[$did3];
                $sum14 += @($sum_line[$did3]/$sum_line2[$did3]);
            }
        ?>

        <?
        reset($arr_nation);
        $in=0;
        foreach ($arr_nation as $key_nation => $did3) {
            $in++;
        ?>
        <tr>
            <?if($in==1){?><td style="background-color:#f0f0f0" rowspan="3"><?=trim($arr2[0])?></td><?}?>
            <td style="background-color:#f0f0f0"><?=$did3?></td>
            <?if($bit_show[1]){?><td class="month1"><?=nf($DATA[$did]["01"][$did3]["fee"])?></td><?}?>
            <?if($bit_show[2]){?><td class="month2"><?=nf($DATA[$did]["02"][$did3]["fee"])?></td><?}?>
            <?if($bit_show[3]){?><td class="month3"><?=nf($DATA[$did]["03"][$did3]["fee"])?></td><?}?>
            <?if($bit_show[4]){?><td class="month4"><?=nf($DATA[$did]["04"][$did3]["fee"])?></td><?}?>
            <?if($bit_show[5]){?><td class="month5"><?=nf($DATA[$did]["05"][$did3]["fee"])?></td><?}?>
            <?if($bit_show[6]){?><td class="month6"><?=nf($DATA[$did]["06"][$did3]["fee"])?></td><?}?>
            <?if($bit_show[7]){?><td class="month7"><?=nf($DATA[$did]["07"][$did3]["fee"])?></td><?}?>
            <?if($bit_show[8]){?><td class="month8"><?=nf($DATA[$did]["08"][$did3]["fee"])?></td><?}?>
            <?if($bit_show[9]){?><td class="month9"><?=nf($DATA[$did]["09"][$did3]["fee"])?></td><?}?>
            <?if($bit_show[10]){?><td class="month10"><?=nf($DATA[$did]["10"][$did3]["fee"])?></td><?}?>
            <?if($bit_show[11]){?><td class="month11"><?=nf($DATA[$did]["11"][$did3]["fee"])?></td><?}?>
            <?if($bit_show[12]){?><td class="month12"><?=nf($DATA[$did]["12"][$did3]["fee"])?></td><?}?>
            <td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line[$did3])?></td>
            <td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line[$did3]/$sum_line2[$did3])?></td>
        </tr>
        <?}?>      

       <?}?>



        <tr style="background-color:#ffe6cc">
            <td colspan="2">합계</td>
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

    //if($debug) checkVar(mysql_error(),$sql);
    while($rs=$dbo->next_record()){

        if(!$rs[bit_cancel] && $rs[sum_fee]){
            if(!$rs[did]) $rs[did]="공란";

            $did = $rs[did];
            $did2 = $rs[did2];
            $did3 = ($rs[did3])? $rs[did3] : "미분류";

            $DATA[$did][$did2][$did3]["people"] = $rs[sum_people];
            $DATA[$did][$did2][$did3]["fee"] = $rs[sum_fee];
            $arr[] = $rs[did];
        }
    }

    $arr = @array_unique($arr);

    ?>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="subject" colspan="2">구분</th>
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
            foreach ($arr_nation as $key_nation => $did_nation) {

                $arr2 = explode("(",$val);
                $did = $val;
                $did2 = $year_this;
                $did3 = $did_nation;

                $sum_line[$did3]=0;
                $sum_line2[$did3]=0;

                if($bit_show[1])    $sum_line[$did3]=$DATA[$did]["01"][$did3]["fee"];
                if($bit_show[2])    $sum_line[$did3]+=$DATA[$did]["02"][$did3]["fee"];
                if($bit_show[3])    $sum_line[$did3]+=$DATA[$did]["03"][$did3]["fee"];
                if($bit_show[4])    $sum_line[$did3]+=$DATA[$did]["04"][$did3]["fee"];
                if($bit_show[5])    $sum_line[$did3]+=$DATA[$did]["05"][$did3]["fee"];
                if($bit_show[6])    $sum_line[$did3]+=$DATA[$did]["06"][$did3]["fee"];
                if($bit_show[7])    $sum_line[$did3]+=$DATA[$did]["07"][$did3]["fee"];
                if($bit_show[8])    $sum_line[$did3]+=$DATA[$did]["08"][$did3]["fee"];
                if($bit_show[9])    $sum_line[$did3]+=$DATA[$did]["09"][$did3]["fee"];
                if($bit_show[10])   $sum_line[$did3]+=$DATA[$did]["10"][$did3]["fee"];
                if($bit_show[11])   $sum_line[$did3]+=$DATA[$did]["11"][$did3]["fee"];
                if($bit_show[12])   $sum_line[$did3]+=$DATA[$did]["12"][$did3]["fee"];

                if($bit_show[1])    $sum_line2[$did3]=$DATA[$did]["01"][$did3]["people"];
                if($bit_show[2])    $sum_line2[$did3]+=$DATA[$did]["02"][$did3]["people"];
                if($bit_show[3])    $sum_line2[$did3]+=$DATA[$did]["03"][$did3]["people"];
                if($bit_show[4])    $sum_line2[$did3]+=$DATA[$did]["04"][$did3]["people"];
                if($bit_show[5])    $sum_line2[$did3]+=$DATA[$did]["05"][$did3]["people"];
                if($bit_show[6])    $sum_line2[$did3]+=$DATA[$did]["06"][$did3]["people"];
                if($bit_show[7])    $sum_line2[$did3]+=$DATA[$did]["07"][$did3]["people"];
                if($bit_show[8])    $sum_line2[$did3]+=$DATA[$did]["08"][$did3]["people"];
                if($bit_show[9])    $sum_line2[$did3]+=$DATA[$did]["09"][$did3]["people"];
                if($bit_show[10])   $sum_line2[$did3]+=$DATA[$did]["10"][$did3]["people"];
                if($bit_show[11])   $sum_line2[$did3]+=$DATA[$did]["11"][$did3]["people"];
                if($bit_show[12])   $sum_line2[$did3]+=$DATA[$did]["12"][$did3]["people"];

                if($bit_show[1])    $sum1 += $DATA[$did]["01"][$did3]["people"];
                if($bit_show[2])    $sum2 += $DATA[$did]["02"][$did3]["people"];
                if($bit_show[3])    $sum3 += $DATA[$did]["03"][$did3]["people"];
                if($bit_show[4])    $sum4 += $DATA[$did]["04"][$did3]["people"];
                if($bit_show[5])    $sum5 += $DATA[$did]["05"][$did3]["people"];
                if($bit_show[6])    $sum6 += $DATA[$did]["06"][$did3]["people"];
                if($bit_show[7])    $sum7 += $DATA[$did]["07"][$did3]["people"];
                if($bit_show[8])    $sum8 += $DATA[$did]["08"][$did3]["people"];
                if($bit_show[9])    $sum9 += $DATA[$did]["09"][$did3]["people"];
                if($bit_show[10])   $sum10 += $DATA[$did]["10"][$did3]["people"];
                if($bit_show[11])   $sum11 += $DATA[$did]["11"][$did3]["people"];
                if($bit_show[12])   $sum12 += $DATA[$did]["12"][$did3]["people"];
                $sum13 += $sum_line2[$did3];
                $sum14 += @($sum_line[$did3]/$sum_line2[$did3]);
            }
        ?>

        <?
        reset($arr_nation);
        $in=0;
        foreach ($arr_nation as $key_nation => $did3) {
            $in++;
        ?>        
        <tr>
            <?if($in==1){?><td style="background-color:#f0f0f0" rowspan="3"><?=trim($arr2[0])?></td><?}?>
            <td style="background-color:#f0f0f0"><?=$did3?></td>            
            <?if($bit_show[1]){?><td><?=nf($DATA[$did]["01"][$did3]["people"])?></td><?}?>
            <?if($bit_show[2]){?><td><?=nf($DATA[$did]["02"][$did3]["people"])?></td><?}?>
            <?if($bit_show[3]){?><td><?=nf($DATA[$did]["03"][$did3]["people"])?></td><?}?>
            <?if($bit_show[4]){?><td><?=nf($DATA[$did]["04"][$did3]["people"])?></td><?}?>
            <?if($bit_show[5]){?><td><?=nf($DATA[$did]["05"][$did3]["people"])?></td><?}?>
            <?if($bit_show[6]){?><td><?=nf($DATA[$did]["06"][$did3]["people"])?></td><?}?>
            <?if($bit_show[7]){?><td><?=nf($DATA[$did]["07"][$did3]["people"])?></td><?}?>
            <?if($bit_show[8]){?><td><?=nf($DATA[$did]["08"][$did3]["people"])?></td><?}?>
            <?if($bit_show[9]){?><td><?=nf($DATA[$did]["09"][$did3]["people"])?></td><?}?>
            <?if($bit_show[10]){?><td><?=nf($DATA[$did]["10"][$did3]["people"])?></td><?}?>
            <?if($bit_show[11]){?><td><?=nf($DATA[$did]["11"][$did3]["people"])?></td><?}?>
            <?if($bit_show[12]){?><td><?=nf($DATA[$did]["12"][$did3]["people"])?></td><?}?>
            <td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line2[$did3])?></td>
            <td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line[$did3]/$sum_line2[$did3])?></td>
        </tr>
       <?}?>
       <?}?>



        <tr style="background-color:#ffe6cc">
            <td colspan="2">합계</td>
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

</div>

</body>
</html>
