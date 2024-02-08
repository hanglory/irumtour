<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");


$easeplus=1;
if($mode=="save"){

    $sql = "select * from cmp_cal_etc where code='$code'";
    $dbo->query($sql);
    //checkVar(mysql_error(),$sql);
    $rs=$dbo->next_record();
    if(!$rs[id_no]){
        $sql = "insert into cmp_cal_etc (code) values ('$code')";
        $dbo->query($sql);
        //checkVar(mysql_error(),$sql);
    }


    if(strstr($ckey,"add")) $cval=rnf($cval);

    $sql="
        update cmp_cal_etc set
            $ckey = '$cval'
        where code='$code'
        ";
    $dbo->query($sql);
    //checkVar(mysql_error(),$sql);

    if(strstr($ckey,"add")){
        echo "
            <script>
                parent.location.reload();
            </script>
        ";
    }

    exit;
}
elseif($mode=="sum"){
    $sql = "
        select
            sum(a.return_price) as return_price
        from cmp_paper7 as a left join cmp_reservation as b
        on a.code=b.code
        where
        b.bit_cancel=1
        and (b.${dtype} >= '$date_s' and b.${dtype} <='$date_e')
        and b.name like '%$keyword%'
    ";
    $dbo->query($sql);
    $rs=$dbo->next_record();

    //checkVar($rs[return_price] . mysql_error(),$sql);

    $return_price_ = nf($rs[return_price]);

    echo "
        <script>
            parent.document.getElementById('sum2').innerHTML = '$return_price_';
        </script>
    ";

    exit;

}

$dtype=($dtype)? $dtype : "d_date";

$date_s = ($date_s)? $date_s : $PAPER_DEFAULT_DAY1;
$date_e = ($date_e)? $date_e : $PAPER_DEFAULT_DAY2;

$year_this = substr($date_s,0,4);
$year_prev = substr($date_s,0,4)-1;
$date_s2 = $year_prev . substr($date_s,4);
$date_e2 = $year_prev . substr($date_e,4);


if(substr($date_s,0,4) > substr($date_e,0,4)){
    error("날짜가 잘못되었습니다. 다시 확인해 주세요.");
    exit;
}


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "파트너 정산";


$code=$date_s."_".$date_e."_". $dtype."_".$staff;
$code = str_replace("/","",$code);
$code = str_replace("(","",$code);
$code = str_replace(")","",$code);
?>
<?include("../top.html");?>
<style type="text/css">
#tbl_cmp_list td,#tbl_cmp_list th{padding:5px 0 5px 0;text-align:center;padding-right:2px;}
.r{padding-right:5px !important}
</style>

<script type="text/javascript">
<!--
$(function(){
    $(".box").on("change",function(){
        var url = "<?=SELF?>?mode=save&code=<?=$code?>";
        url +="&ckey="+this.id;
        url +="&cval="+this.value;
        actarea.location.href=url;
    });
});
//-->
</script>

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



    <input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?=$date_s?>" class="box c dateinput">
    ~
    <input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?=$date_e?>" class="box c dateinput">


    <select name="dtype">
        <?=option_str("예약일기준,출국일기준","tour_date,d_date",$dtype)?>
    </select>

    <?
    $sql = "select * from cmp_staff order by name asc";
    list($rows)=$dbo->query($sql);
    //if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar($rows.mysql_error(),$sql);}
    while($rs=$dbo->next_record()){
        $STAFF1.=",".$rs[name];
        $STAFF2.=",(".$rs[id].")";
    }
    ?>
    <select name="staff">
        <?=option_str("직원명".$STAFF1,$STAFF2,$staff)?>
    </select>

    <input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
    </td>
    <tr>
    </form>
    </table>
    </div>
    <!-- Search End------------------------------------------------>

    <br/>

    <?
    $staff_id= substr($staff,1,-1);
    $sql = "select * from cmp_staff where id='$staff_id'";
    $dbo->query($sql);
    $rs=$dbo->next_record();
    $target_rate = ($rs[target_rate])? $rs[target_rate] : 50;
    //if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar($rs[target_rate].mysql_error(),$sql);}


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
        where $dtype >='$date_s' and $dtype <='$date_e'
        and main_staff like '%${staff}'
    ";
    $dbo->query($sql);
    $rs=$dbo->next_record();
    //if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar($rs[id_no].mysql_error(),$sql);
    //if(strstr("@112.172.15.90@221.154.110.119@",$_SERVER["REMOTE_ADDR"])){checkVar("golf_id_no",$rs[golf_id_no]);}

    $rs[sum_fee] = ($rs[total_in1]+$rs[total_in2]+$rs[total_in3]);
    $rs[sum_fee] -= ($rs[total_out1]+$rs[total_out2]+$rs[total_out3]);


    $sql2 = "select * from cmp_cal_etc where code='$code'";
    $dbo2->query($sql2);
    $rs2=$dbo2->next_record();
    //checkVar($rs2[add1].mysql_error(),$sql2);
    ?>

    <?if($rs[sum_fee]!=$rs[sum_fee_chk]){?><div style="color:red">! 날짜가 기록되지 않은 실적이 있습니다</div><?}?>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

        <tr align=center height=25 bgcolor="#F7F7F6">
        <th class="subject" width="20%">매출</th>
        <th class="subject" width="20%">요율(<?=$target_rate?>%)</th>
        <th class="subject" width="20%"></th>
        <th class="subject" width="20%"></th>
        <th class="subject" width="20%">비고</th>
        </tr>

        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th><?=nf($rs[sum_fee])?><!-- 매출 --></th>
          <td><?=nf($rs[sum_fee]*($target_rate/100))?><!-- 요율 A --></td>
          <td><!-- 빈칸 --></td>
          <td><!-- 빈칸 --></td>
          <td><input type="text" id="etc1" value="<?=$rs2[etc1]?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 비고 --></td>
        </tr>

        <tr align=center height=25 bgcolor="#F7F7F6">
        <th class="subject" >비용</th>
        <th class="subject" >단가</th>
        <th class="subject" >개수</th>
        <th class="subject" >금액</th>
        <th class="subject" >비고</th>
        </tr>

        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td>골프공<!-- 비용 --></td>
          <td><?=nf($danga_g)?><!-- 단가 --></td>
          <td><?=nf($rs[ball])?><!-- 개수 --></td>
          <td><?=nf($danga_g*$rs[ball])?><!-- 금액 --></td>
          <td><input type="text" id="etc2" value="<?=$rs2[etc2]?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 비고 --></td>
        </tr>
        <?
        $sum1+=$danga_g;
        $sum2+=$rs[ball];
        $sum3+=$danga_g*$rs[ball];
        ?>

        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td>골프공(고급)<!-- 비용 --></td>
          <td><?=nf($danga_g2)?><!-- 단가 --></td>
          <td><?=nf($rs[ball2])?><!-- 개수 --></td>
          <td><?=nf($danga_g2*$rs[ball2])?><!-- 금액 --></td>
          <td><input type="text" id="etc6" value="<?=$rs2[etc6]?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 비고 --></td>
        </tr>
        <?
        $sum1+=$danga_g2;
        $sum2+=$rs[ball2];
        $sum3+=$danga_g2*$rs[ball2];
        ?>

        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td>항공커버<!-- 비용 --></td>
          <td><?=nf($danga_c)?><!-- 단가 --></td>
          <td><?=nf($rs[cover])?><!-- 개수 --></td>
          <td><?=nf($danga_c*$rs[cover])?><!-- 금액 --></td>
          <td><input type="text" id="etc3" value="<?=$rs2[etc3]?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 비고 --></td>
        </tr>
        <?
        $sum1+=$danga_c;
        $sum2+=$rs[cover];
        $sum3+=$danga_c*$rs[cover];
        ?>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td>달러북<!-- 비용 --></td>
          <td><?=nf($danga_b)?><!-- 단가 --></td>
          <td><?=nf($rs[book])?><!-- 개수 --></td>
          <td><?=nf($danga_b*$rs[book])?><!-- 금액 --></td>
          <td><input type="text" id="etc7" value="<?=$rs2[etc7]?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 비고 --></td>
        </tr>
        <?
        $sum1+=$danga_c;
        $sum2+=$rs[cover];
        $sum3+=$danga_c*$rs[cover];
        ?>


        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td>기타<!-- 비용 --></td>
          <td><input type="text" id="add1" value="<?=nf($rs2[add1])?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 단가 --></td>
          <td><input type="text" id="add2" value="<?=nf($rs2[add2])?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 개수 --></td>
          <td><?=nf($rs2[add1]*$rs2[add2])?><!-- 금액 --></td>
          <td><input type="text" id="etc5" value="<?=$rs2[etc5]?>" class="box c" length="45" style="width:97%;margin:0 3px 0 3px"><!-- 비고 --></td>
        </tr>
        <?
        $sum1+=$rs2[add1];
        $sum2+=$rs2[add2];
        $sum3+=$rs2[add1]*$rs2[add2];
        ?>

        <tr align=center height=25 bgcolor="#F7F7F6">
        <th class="subject" >비용소계</th>
        <th class="subject" ><?//=nf($sum1)?><!-- 비용소개값 --></th>
        <th class="subject" ><?//=nf($sum2)?><!-- 빈칸 --></th>
        <th class="subject" ><?=nf($sum3)?><!-- 합계 B--></th>
        <th class="subject" ><!-- 빈칸 --></th>
        </tr>

        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th>정산액</th>
          <!-- <th><?=nf(($rs[sum_fee]/2)-$sum3)?></th> -->
          <th><?=nf(($rs[sum_fee]*($target_rate/100))-$sum3)?><!-- 정산액 A-B --></th>
          <td><!-- 빈칸 --></td>
          <td><!-- 빈칸 --></td>
          <td><!-- 빈 --></td>
        </tr>
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
                <span class="btn_pack medium bold"><a href="list_<?=$filecode?>_excel.php?staff=<?=$staff?>&date_s=<?=$date_s?>&date_e=<?=$date_e?>&dtype=<?=$dtype?>"> 엑셀 </a></span>
              </td>
            </tr>
          </table>
          <!-- Button End------------------------------------------------>

          </td>
        </tr>
    </table>


    <br/>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle">월별 현황</td>
      </tr>
      <tr>
        <td> </td>
      </tr>
       <tr>
        <td background="../images/common/bg_title.gif" height="5"></td>
      </tr>
    </table>

    <br/>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
        <tr align=center height=25 bgcolor="#F7F7F6">
        <th class="subject" width="20%">기간</th>
        <th class="subject" width="20%">매출액</th>
        <th class="subject" width="20%">정산액</th>
        </tr>

        <?
        $arr = explode("/",$date_s);
        for($i=1; $i<($arr[1]);$i++){

            $date_s = date("Y/m/d",mktime(0,0,0,$i,1,ceil($arr[0])));
            $date_e = date("Y/m/d",strtotime($date_s." +1 month")-1);

            $sql = "
                select
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
                where $dtype >='$date_s' and $dtype <='$date_e'
                and main_staff like '%${staff}'
            ";
            $dbo->query($sql);
            $rs=$dbo->next_record();
            //checkVar(mysql_error(),$sql);
            $rs[sum_fee] = ($rs[total_in1]+$rs[total_in2]+$rs[total_in3]);
            $rs[sum_fee] -= ($rs[total_out1]+$rs[total_out2]+$rs[total_out3]);


            $code=$date_s."_".$date_e."_". $dtype."_".$staff;
            $code = str_replace("/","",$code);
            $code = str_replace("(","",$code);
            $code = str_replace(")","",$code);


            $sql2 = "select * from cmp_cal_etc where code='$code'";
            $dbo2->query($sql2);
            $rs2=$dbo2->next_record();
            //checkVar(mysql_error(),$sql2);

            $sum3=0;
            $sum3+=$rs2[add1]*$rs2[add2];

            $sum1+=$danga_g;
            $sum2+=$rs[ball];
            $sum3+=$danga_g*$rs[ball];

            $sum1+=$danga_g2;
            $sum2+=$rs[ball2];
            $sum3+=$danga_g2*$rs[ball2];

            $sum1+=$danga_c;
            $sum2+=$rs[cover];
            $sum3+=$danga_c*$rs[cover];

            $sum1+=$danga_c;
            $sum2+=$rs[cover];
            $sum3+=$danga_c*$rs[cover];            
        ?>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td><?=num2($i)?>월<!-- 기간 --></td>
          <td><?=nf($rs[sum_fee])?><!-- 매출 --></td>
          <td><?=nf(($rs[sum_fee]*($target_rate/100))-$sum3)?><!-- 정산액 --></td>
        </tr>
        <?}?>
    </table>



    <!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
