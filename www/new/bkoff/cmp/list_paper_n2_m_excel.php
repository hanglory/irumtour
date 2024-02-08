<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "담당자별 정산현황";


$year = ($year)? $year : date("Y");
$dtype = ($dtype)? $dtype : "d_date";


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
    $staff_id= substr($staff,1,-1);
    $sql = "select * from cmp_staff where id='$staff_id'";
    $dbo->query($sql);
    $rs=$dbo->next_record();
    $target_rate = ($rs[target_rate])? $rs[target_rate] : 50;
    //if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar($rs[target_rate].mysql_error(),$sql);}


    $FILTER_PARTNER_QUERY_CPID2 = str_replace("a.cp_id","b.cp_id",$FILTER_PARTNER_QUERY_CPID);
    $sql2 = "   
        select 
            left($dtype,7) as ym,
            count(a.insure) as ins
        from cmp_people as a inner join cmp_reservation as b 
        on a.code=b.code
        inner join cmp_golf as c
        on b.golf_id_no=c.id_no
        where a.bit=1
        and left(b.${dtype},4)='$year'
        and b.main_staff like '%${staff}'
        and c.nation<>'한국'
        $FILTER_PARTNER_QUERY_CPID2
        group by ym
        ";
    $dbo2->query($sql2);
    while($rs2=$dbo2->next_record()){
        $INS[$rs2[ym]] = $rs2[ins];
    }





    $danga_g = rnf($DANGA_GOLF);
    $danga_g2 = rnf($DANGA_GOLF2);
    $danga_c = rnf($DANGA_AIR);
    $danga_b = rnf($DANGA_BOOK);
    $danga_i = rnf($DANGA_INC);

    $sum1=0;
    $sum2=0;
    $sum3=0;

    $sql = "
        select
            left($dtype,7) as ym,
            sum(golf_ball) as ball,
            sum(golf_ball2) as ball2,
            sum(air_cover) as cover,
            sum(dollarbook) as book,
            sum((select count(insure) from cmp_people where code=a.code and bit=1)) as ins,
            sum((select sum(price_prev) from cmp_people where code=a.code and bit=1 and price_prev>0)) as total_in1,
            sum((select sum(price_prev2) from cmp_people where code=a.code and bit=1 and price_prev2>0)) as total_in2,
            sum((select sum(price_prev3) from cmp_people where code=a.code and bit=1 and price_prev3>0)) as total_in3,
            sum((select sum(price_air) from cmp_people where code=a.code and bit=1 and price_air>0)) as total_out1,
            sum((select sum(price_land) from cmp_people where code=a.code and bit=1 and price_land>0)) as total_out2,
            sum((select sum(price_refund) from cmp_people where code=a.code and bit=1 and price_refund>0)) as total_out3,
            sum(
                (
                    (select sum(price_prev) from cmp_people where code=a.code and bit=1)
                    +(select sum(price_prev2) from cmp_people where code=a.code and bit=1)
                    +(select sum(price_prev3) from cmp_people where code=a.code and bit=1)
                )
                -
                (
                    (select sum(price_air) from cmp_people where code=a.code and bit=1)
                    +(select sum(price_land) from cmp_people where code=a.code and bit=1)
                    +(select sum(price_refund) from cmp_people where code=a.code and bit=1)
                )
            ) as sum_fee_chk
        from cmp_reservation as a
        where 
            left($dtype,4)='$year'
            and main_staff like '%${staff}'
            $FILTER_PARTNER_QUERY_CPID
        group by ym
        order by ym
    ";
    $dbo->query($sql);
    //checkVar(mysql_error(),$sql);
    
    ?>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
        <tr align=center height=25 bgcolor="#F7F7F6">
        <th class="subject" width="20%">구분</th>
        <th class="subject" width="20%">매출</th>
        <th class="subject" width="20%">요율(<?=$target_rate?>%)</th>
        <th class="subject" width="20%">정산</th>
        </tr>


        <?
        $bit_cyear =($year==date("Y"))?1:0;

        while($rs=$dbo->next_record()){

            if(!$bit_cyear || (ceil(substr($rs[ym],-2))<date("m")) ){

                $rs[sum_fee] = ($rs[total_in1]+$rs[total_in2]+$rs[total_in3]);
                $rs[sum_fee] -= ($rs[total_out1]+$rs[total_out2]+$rs[total_out3]);   

                $rate = $rs[sum_fee]*($target_rate/100);
                $jungsan = $rate-($danga_i*$INS[$rs[ym]]);

                $sum1+=$rs[sum_fee];      
                $sum2+= $rate;
                $sum3+= $jungsan;

                $sum_etc=0;
                $sum_etc+=$danga_g*$rs[ball];
                $sum_etc+=$danga_g2*$rs[ball2];
                $sum_etc+=$danga_c*$rs[cover];
                $sum_etc+=$danga_b*$rs[book];
                $sum_etc+=$danga_b*$rs[book];
                $sum_etc+=$danga_i*$INS[$rs[ym]];
        ?>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th><?=substr($rs[ym],-2)?>월</th>
          <th><?=nf($rs[sum_fee])?><!-- 매출 --></th>
          <td><?=nf($rate)?><!-- 요율 A --></td>
          <td><?=nf($jungsan)?></td>
        </tr>
        <?
            }
        }
        ?>

        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="subject">합계</th>
            <th class="subject"><?=nf($sum1)?></th>
            <th class="subject"><?=nf($sum2)?></th>
            <th class="subject"><?=nf($sum3)?></th>
        </tr>
    </table>

</body>
</html>
