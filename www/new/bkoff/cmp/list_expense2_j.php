<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");

$dtype=($dtype)? $dtype : "d_date";


$date_s = ($date_s)? $date_s : $PAPER_DEFAULT_DAY1;
$date_e = ($date_e)? $date_e : $PAPER_DEFAULT_DAY2;
$year = ($year)? $year : date("Y");
$year_origin=$year;


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "경영실적";
$TITLE .=($dtype=="d_date")? "(출국일자 기준)" : "(예약일자 기준)";
?>
<?include("../top.html");?>
<style type="text/css">
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:right;padding-right:2px;}
.r{text-align:right !important;padding-right:20px !important;}
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


    <tr height=22>
    <td valign='bottom' align=right>

    <!--
    <input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?=$date_s?>" class="box c dateinput">
    ~
    <input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?=$date_e?>" class="box c dateinput">
    -->

    <select name="dtype">
        <?=option_str("출국일기준,예약일","d_date,tour_date",$dtype)?>
    </select>

    기준년도 : <input type="text" name="year" id="year" size="13" maxlength="10" value="<?=$year?>" class="box c">

    <input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
    </td>
    <tr>
    </form>
    </table>
    </div>
    <!-- Search End------------------------------------------------>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
<?
for($y=0; $y<3;$y++){

    $sum1=0;
    $sum2=0;
    $sum3=0;
?>
    <?
    if($year){
        $YEAR_PREV = date("Y/m/d",mktime(0,0,0,1,1,$year));
        $YEAR_THIS = date("Y/m/d",mktime(0,0,0,1,1,$year+1)-1);
    }

    $sql = "
        select
            left($dtype,7) as did,
            sum(people) as sum_people,
            sum(price_prev - (select sum(price_air + price_land+price_refund) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
            from cmp_reservation as a
            where
                $dtype >= '$YEAR_PREV'
                and $dtype <='$YEAR_THIS'
            group by left($dtype,7)

    ";

    $dbo->query($sql);
    //if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql);
    while($rs=$dbo->next_record()){

        $sql2 = "
            select 
                sum((c.price_prev+c.price_prev2+c.price_prev3)-(c.price_air+c.price_land+c.price_refund)) as sum_fee
            from cmp_people as c left join cmp_reservation as d
            on c.code=d.code
            where c.bit=1
                and left(d.$dtype,7) = '$rs[did]'
        ";
        $dbo2->query($sql2);
        $rs2=$dbo2->next_record();
        //if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql2);}
        $rs[sum_fee] = $rs2[sum_fee];

        $did = $rs[did];
        $DATA[$did]["people"] = $rs[sum_people];
        $DATA[$did]["fee"] = $rs[sum_fee];
    }

    ?>

        <tr>
            <th class="subject" colspan="5"><?=$year?>년</th>
        </tr>
        <tr>
            <th class="subject" >구분</th>
            <th class="subject" >인원</th>
            <th class="subject" >매출</th>
            <th class="subject" >비용</th>
            <th class="subject" >순이익</th>
        </tr>
        <?
        $ctg="";
        $sql = "select * from ez_category1 where bit_out<>1 order by seq";
        $dbo->query($sql);
        while($rs=$dbo->next_record()){

            $sql2 = "select * from ez_category2 where category1='$rs[category1]' order by seq";
            list($rows) = $dbo2->query($sql2);

            while($rs2=$dbo2->next_record()){
                $ctg.=",'$rs[id_no]_$rs2[id_no]'";
            }

        }
        $ctg = substr($ctg,1);


        $ctg2="";
        $sql = "select * from ez_category1 where bit_out=1 order by seq";
        $dbo->query($sql);
        while($rs=$dbo->next_record()){

            $sql2 = "select * from ez_category2 where category1='$rs[category1]' order by seq";
            list($rows) = $dbo2->query($sql2);

            while($rs2=$dbo2->next_record()){
                $ctg2.=",'$rs[id_no]_$rs2[id_no]'";
            }

        }
        $ctg2 = substr($ctg2,1);



        for($i=1;$i<=12;$i++){
            $did = $year . "/" . num2($i);
            $month = rnf($i);
            $sql = "
                select 
                    (
                        select
                            sum(price)
                        from cmp_expense
                        where year=$year and month=$month
                        and concat(category1,'_',category2) in ($ctg)
                    ) as price1,
                    (
                        select
                            sum(price)
                        from cmp_expense
                        where year=$year and month=$month
                        and concat(category1,'_',category2) in ($ctg2)
                    ) as price2
            ";
            $dbo->query($sql);
            $rs=$dbo->next_record();      
            //if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}  
            $rs[price] = $rs[price1]-$rs[price2];
            $sum1 += $DATA[$did]["people"];
            $sum2 += $DATA[$did]["fee"];
            $sum3 += $rs["price"];

        ?>
        <tr>
            <td class="c" style="background-color:#f0f0f0"><?=num2($i)?>월</td>
            <td class="r"><?=nf($DATA[$did]["people"])?></td>
            <td class="r"><?=nf($DATA[$did]["fee"])?></td>
            <td class="r"><?=nf($rs["price"])?></td>
            <td class="r"><?=nf($DATA[$did]["fee"]-$rs["price"])?></td>
        </tr>
        <?}?>
        <tr>
            <td class="subject c">누계</td>
            <td class="subject r" ><?=nf($sum1)?></td>
            <td class="subject r" ><?=nf($sum2)?></td>
            <td class="subject r" ><?=nf($sum3)?></td>
            <td class="subject r" ><?=nf($sum2-$sum3)?></td>
        </tr>
        <?if($y<2){?>
        <tr>
            <td colspan="5">&nbsp;</td>
        </tr>
        <?}?>
<?
    $year -=1;
}
?>
    </table>





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
                <span class="btn_pack medium bold"><a href="list_<?=$filecode?>_excel.php?year=<?=$year_origin?>&date_s=<?=$date_s?>&date_e=<?=$date_e?>&dtype=<?=$dtype?>"> 엑셀 </a></span>
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

