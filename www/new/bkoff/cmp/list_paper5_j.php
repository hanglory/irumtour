<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"통계");

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


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper2";
$TITLE = "지역별";
$TITLE .=($ctype)? "> $ctype " : "";
$TITLE .=($dtype=="d_date")? "(출국일자 기준)" : "(예약일자 기준)";
?>
<?include("../top.html");?>
<style type="text/css">
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:center;padding-right:2px;}
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
        <form name="fmSearch" method="get">
        <input type="hidden" name="position" value="">
        <input type="hidden" name="ctg1" value="<?=$ctg1?>">

        <tr height="22">
            <td valign="bottom" align="right">
                <input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?=$date_s?>" class="box c dateinput">
                ~
                <input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?=$date_e?>" class="box c dateinput">

                <select name="ctype">
                    <?=option_str("일본,태국,중국,한국","일본,태국,중국,한국",$ctype)?>
                </select>

                <select name="dtype">
                    <?=option_str("예약일기준,출국일기준","tour_date,d_date",$dtype)?>
                </select>

                <input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
            </td>
        <tr>
        </form>
        </table>
    </div>
    <!-- Search End------------------------------------------------>

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


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="subject" >구분</th>
            <!-- <th class="subject" width="15%">기간</th> -->
            <th class="subject" width="15%">인원</th>
            <th class="subject" width="15%">비율</th>
            <th class="subject" width="15%">매출</th>
            <th class="subject" width="15%">객단가</th>
        </tr>
        <?
        $total_this1=0;
        $total_this2=0;
        $total_prev1=0;
        $total_prev2=0;
        while(list($key,$val)=@each($arr)){
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
            <td rowspan="1" style="background-color:#f0f0f0"><a href="list_paper17.php?date_s=<?=$date_s?>&date_e=<?=$date_e?>&ctype=<?=$ctype?>&city=<?=$val?>&dtype=<?=$dtype?>"><?=$val?></a></td>
            <!-- <td>금년</td> -->
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
<!--        <tr style="background-color:#f0f0f0">
            <td>작년</td>
            <td><?=nf($DATA[$did][$did2]["people"])?>명</td>
            <td><?=$x?>%</td>
            <td class="r"><?=@nf($sum_prev2)?>원</td>
            <td class="r"><?=@nf($sum_prev2/$sum_prev1)?>원</td>
        </tr> -->
        <?
        $total_this1+=$sum_this1;
        $total_this2+=$sum_this2;
        $total_prev1+=$sum_prev1;
        $total_prev2+=$sum_prev2;

        }
        ?>


        <tr style="background-color:#ffe6cc">
            <td rowspan="1">합계</td>
            <!-- <td>금년</td> -->
            <td><?=nf($total_this1)?>명</td>
            <td>100%</td>
            <td class="r"><?=@nf($total_this2)?>원</td>
            <td class="r"><?=@nf($total_this2/$total_this1)?>원</td>
        </tr>

        <!-- <tr style="background-color:#ffe6cc">
            <td>작년</td>
            <td><?=nf($total_prev1)?>명</td>
            <td>100%</td>
            <td class="r"><?=@nf($total_prev2)?>원</td>
            <td class="r"><?=@nf($total_prev2/$total_prev1)?>원</td>
        </tr> -->
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
                <span class="btn_pack medium bold"><a href="list_<?=$filecode?>_excel.php?year=<?=$year?>&date_s=<?=$date_s?>&date_e=<?=$date_e?>&dtype=<?=$dtype?>&ctype=<?=$ctype?>"> 엑셀 </a></span>
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
