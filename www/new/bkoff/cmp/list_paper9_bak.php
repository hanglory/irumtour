<?
include_once("../include/common_file.php");

if($partner==1){
    chk_power($_SESSION["sessLogin"]["proof"],"파트너실적");
}
else{
    chk_power($_SESSION["sessLogin"]["proof"],"통계");
}


if($_SERVER["REMOTE_ADDR"]=="106.246.54.277"){
    $_SESSION["sessLogin"]["staff_type"]="partner";
    $_SESSION["sessLogin"]["id"]="sanha";
}

if($_SESSION["sessLogin"]["staff_type"] == "ceo" && !$partner){
    $sql = "select * from cmp_staff where staff_type in ('ceo','staff','partner_a','partner_i','partner_g')";
    $dbo->query($sql);
    while($rs=$dbo->next_record()){
        $partners .="or main_staff like '%($rs[id])%' ";
    }
    $partners = substr($partners,3);
    $partner_filter = " and ($partners)";
}elseif($_SESSION["sessLogin"]["staff_type"] == "ceo" && $partner==1){
    $sql = "select * from cmp_staff where staff_type in ('partner_a','partner_i','partner_g')";
    $dbo->query($sql);
    while($rs=$dbo->next_record()){
        $partners .="or main_staff like '%($rs[id])%' ";
    }
    $partners = substr($partners,3);
    $partner_filter = " and ($partners)";
}elseif(($_SESSION["sessLogin"]["staff_type"] == "leader" || $_SESSION["sessLogin"]["staff_type"] == "staff" || $_SESSION["sessLogin"]["staff_type"] == "ceo") && $partner==2){
    $sql = "select * from cmp_staff where staff_type in ('staff','leader')";
    $dbo->query($sql);
    while($rs=$dbo->next_record()){
        $partners .="or main_staff like '%($rs[id])%' ";
    }
    $partners = substr($partners,3);
    $partner_filter = " and ($partners)";
}else{
    $partner_filter = " and main_staff like '%(".$_SESSION["sessLogin"]["id"].")'";
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


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU =($partner)? "cmp_partner":"cmp_paper";
if($partner==2) $MENU = "cmp_paper2";
$TITLE =($partner)? "파트너별 실적": "실적(인원 및 매출)";
$TITLE .=($dtype=="d_date")? "(출국일자 기준)" : "(예약일자 기준)";
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
    <input type=hidden name='partner' value="<?=$partner?>">


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




    <?
    $sql = "
        select
            main_staff as did,
            right(left($dtype,7),2) as did2,
            sum(people) as sum_people,
            sum((select sum((price_prev+price_prev2+price_prev3)-(price_air + price_land + price_refund)) as payed_price from cmp_people where code=a.code and bit=1)) as sum_fee
            from cmp_reservation as a
        where
            ($dtype >= '$date_s' and $dtype <='$date_e')
            $filter
            $partner_filter
        group by main_staff,right(left($dtype,7),2)
    ";
    $dbo->query($sql);
    //if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql);
    while($rs=$dbo->next_record()){
        $did = $rs[did];
        $did2 = $rs[did2];

        $DATA[$did][$did2]["people"] = $rs[sum_people];
        $DATA[$did][$did2]["fee"] = $rs[sum_fee];
        $arr[] = $rs[did];
    }

    $arr = @array_unique($arr);

    ?>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

        <tr align=center height=25 bgcolor="#F7F7F6"'>
            <th class="subject" >구분</th>
            <th class="subject">1월</th>
            <th class="subject">2월</th>
            <th class="subject">3월</th>
            <th class="subject">4월</th>
            <th class="subject">5월</th>
            <th class="subject">6월</th>
            <th class="subject">7월</th>
            <th class="subject">8월</th>
            <th class="subject">9월</th>
            <th class="subject">10월</th>
            <th class="subject">11월</th>
            <th class="subject">12월</th>
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

            $sum_line=$DATA[$did]["01"]["fee"];
            $sum_line+=$DATA[$did]["02"]["fee"];
            $sum_line+=$DATA[$did]["03"]["fee"];
            $sum_line+=$DATA[$did]["04"]["fee"];
            $sum_line+=$DATA[$did]["05"]["fee"];
            $sum_line+=$DATA[$did]["06"]["fee"];
            $sum_line+=$DATA[$did]["07"]["fee"];
            $sum_line+=$DATA[$did]["08"]["fee"];
            $sum_line+=$DATA[$did]["09"]["fee"];
            $sum_line+=$DATA[$did]["10"]["fee"];
            $sum_line+=$DATA[$did]["11"]["fee"];
            $sum_line+=$DATA[$did]["12"]["fee"];

            $sum_line2=$DATA[$did]["01"]["people"];
            $sum_line2+=$DATA[$did]["02"]["people"];
            $sum_line2+=$DATA[$did]["03"]["people"];
            $sum_line2+=$DATA[$did]["04"]["people"];
            $sum_line2+=$DATA[$did]["05"]["people"];
            $sum_line2+=$DATA[$did]["06"]["people"];
            $sum_line2+=$DATA[$did]["07"]["people"];
            $sum_line2+=$DATA[$did]["08"]["people"];
            $sum_line2+=$DATA[$did]["09"]["people"];
            $sum_line2+=$DATA[$did]["10"]["people"];
            $sum_line2+=$DATA[$did]["11"]["people"];
            $sum_line2+=$DATA[$did]["12"]["people"];

            $sum1 += $DATA[$did]["01"]["people"];
            $sum2 += $DATA[$did]["02"]["people"];
            $sum3 += $DATA[$did]["03"]["people"];
            $sum4 += $DATA[$did]["04"]["people"];
            $sum5 += $DATA[$did]["05"]["people"];
            $sum6 += $DATA[$did]["06"]["people"];
            $sum7 += $DATA[$did]["07"]["people"];
            $sum8 += $DATA[$did]["08"]["people"];
            $sum9 += $DATA[$did]["09"]["people"];
            $sum10 += $DATA[$did]["10"]["people"];
            $sum11 += $DATA[$did]["11"]["people"];
            $sum12 += $DATA[$did]["12"]["people"];
            $sum13 += $sum_line2;
            $sum14 += @($sum_line/$sum_line2);

        ?>
        <tr>
            <td style="background-color:#f0f0f0"><?=trim($arr2[0])?></td>
            <td><?=nf($DATA[$did]["01"]["people"])?></td>
            <td><?=nf($DATA[$did]["02"]["people"])?></td>
            <td><?=nf($DATA[$did]["03"]["people"])?></td>
            <td><?=nf($DATA[$did]["04"]["people"])?></td>
            <td><?=nf($DATA[$did]["05"]["people"])?></td>
            <td><?=nf($DATA[$did]["06"]["people"])?></td>
            <td><?=nf($DATA[$did]["07"]["people"])?></td>
            <td><?=nf($DATA[$did]["08"]["people"])?></td>
            <td><?=nf($DATA[$did]["09"]["people"])?></td>
            <td><?=nf($DATA[$did]["10"]["people"])?></td>
            <td><?=nf($DATA[$did]["11"]["people"])?></td>
            <td><?=nf($DATA[$did]["12"]["people"])?></td>
            <td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line2)?></td>
            <td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line/$sum_line2)?></td>
        </tr>
       <?}?>



        <tr style="background-color:#ffe6cc">
            <td>합계</td>
            <td><?=nf($sum1)?></td>
            <td><?=nf($sum2)?></td>
            <td><?=nf($sum3)?></td>
            <td><?=nf($sum4)?></td>
            <td><?=nf($sum5)?></td>
            <td><?=nf($sum6)?></td>
            <td><?=nf($sum7)?></td>
            <td><?=nf($sum8)?></td>
            <td><?=nf($sum9)?></td>
            <td><?=nf($sum10)?></td>
            <td><?=nf($sum11)?></td>
            <td><?=nf($sum12)?></td>
            <td class="r"><?=nf($sum13)?></td>
            <td class="r"><?=nf($sum14)?></td>
        </tr>


    </table>

    <br/>



    <?

    $arr = @array_unique($arr);

    ?>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="subject" >구분</th>
            <th class="subject">1월</th>
            <th class="subject">2월</th>
            <th class="subject">3월</th>
            <th class="subject">4월</th>
            <th class="subject">5월</th>
            <th class="subject">6월</th>
            <th class="subject">7월</th>
            <th class="subject">8월</th>
            <th class="subject">9월</th>
            <th class="subject">10월</th>
            <th class="subject">11월</th>
            <th class="subject">12월</th>
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

            $sum_line=$DATA[$did]["01"]["fee"];
            $sum_line+=$DATA[$did]["02"]["fee"];
            $sum_line+=$DATA[$did]["03"]["fee"];
            $sum_line+=$DATA[$did]["04"]["fee"];
            $sum_line+=$DATA[$did]["05"]["fee"];
            $sum_line+=$DATA[$did]["06"]["fee"];
            $sum_line+=$DATA[$did]["07"]["fee"];
            $sum_line+=$DATA[$did]["08"]["fee"];
            $sum_line+=$DATA[$did]["09"]["fee"];
            $sum_line+=$DATA[$did]["10"]["fee"];
            $sum_line+=$DATA[$did]["11"]["fee"];
            $sum_line+=$DATA[$did]["12"]["fee"];

            $sum_line2=$DATA[$did]["01"]["people"];
            $sum_line2+=$DATA[$did]["02"]["people"];
            $sum_line2+=$DATA[$did]["03"]["people"];
            $sum_line2+=$DATA[$did]["04"]["people"];
            $sum_line2+=$DATA[$did]["05"]["people"];
            $sum_line2+=$DATA[$did]["06"]["people"];
            $sum_line2+=$DATA[$did]["07"]["people"];
            $sum_line2+=$DATA[$did]["08"]["people"];
            $sum_line2+=$DATA[$did]["09"]["people"];
            $sum_line2+=$DATA[$did]["10"]["people"];
            $sum_line2+=$DATA[$did]["11"]["people"];
            $sum_line2+=$DATA[$did]["12"]["people"];

            $sum1 += $DATA[$did]["01"]["fee"];
            $sum2 += $DATA[$did]["02"]["fee"];
            $sum3 += $DATA[$did]["03"]["fee"];
            $sum4 += $DATA[$did]["04"]["fee"];
            $sum5 += $DATA[$did]["05"]["fee"];
            $sum6 += $DATA[$did]["06"]["fee"];
            $sum7 += $DATA[$did]["07"]["fee"];
            $sum8 += $DATA[$did]["08"]["fee"];
            $sum9 += $DATA[$did]["09"]["fee"];
            $sum10 += $DATA[$did]["10"]["fee"];
            $sum11 += $DATA[$did]["11"]["fee"];
            $sum12 += $DATA[$did]["12"]["fee"];
            $sum13 += $sum_line;
            $sum14 += @($sum_line/$sum_line2);

        ?>
        <tr>
            <td style="background-color:#f0f0f0"><?=trim($arr2[0])?></td>
            <td><?=nf($DATA[$did]["01"]["fee"])?></td>
            <td><?=nf($DATA[$did]["02"]["fee"])?></td>
            <td><?=nf($DATA[$did]["03"]["fee"])?></td>
            <td><?=nf($DATA[$did]["04"]["fee"])?></td>
            <td><?=nf($DATA[$did]["05"]["fee"])?></td>
            <td><?=nf($DATA[$did]["06"]["fee"])?></td>
            <td><?=nf($DATA[$did]["07"]["fee"])?></td>
            <td><?=nf($DATA[$did]["08"]["fee"])?></td>
            <td><?=nf($DATA[$did]["09"]["fee"])?></td>
            <td><?=nf($DATA[$did]["10"]["fee"])?></td>
            <td><?=nf($DATA[$did]["11"]["fee"])?></td>
            <td><?=nf($DATA[$did]["12"]["fee"])?></td>
            <td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line)?></td>
            <td class="r" style="background-color:#F7F7F6"><?=@nf($sum_line/$sum_line2)?></td>
        </tr>
       <?}?>



        <tr style="background-color:#ffe6cc">
            <td>합계</td>
            <td><?=nf($sum1)?></td>
            <td><?=nf($sum2)?></td>
            <td><?=nf($sum3)?></td>
            <td><?=nf($sum4)?></td>
            <td><?=nf($sum5)?></td>
            <td><?=nf($sum6)?></td>
            <td><?=nf($sum7)?></td>
            <td><?=nf($sum8)?></td>
            <td><?=nf($sum9)?></td>
            <td><?=nf($sum10)?></td>
            <td><?=nf($sum11)?></td>
            <td><?=nf($sum12)?></td>
            <td class="r"><?=nf($sum13)?></td>
            <td class="r"><?=nf($sum14)?></td>
        </tr>


    </table>


    <br/>


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
                <span class="btn_pack medium bold"><a href="list_<?=$filecode?>_excel.php?year=<?=$year?>&date_s=<?=$date_s?>&date_e=<?=$date_e?>&dtype=<?=$dtype?>&partner=<?=$partner?>"> 엑셀 </a></span>
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
