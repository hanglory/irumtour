<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");



if($mode=="tax"){
    $sql = "delete from cmp_tax where year='$year' and month='$month'";
    $dbo->query($sql);

    $price=rnf($price);
    $sql = "insert into cmp_tax (year,month,price) values ('$year','$month','$price')";
    $dbo->query($sql);
    if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql);} 
    $price_=nf(rnf($price));
    echo "
        <script>
            parent.location.reload();
        </script>
    ";
    exit;   
}

elseif($mode=="ex_profit"){

    $sql = "delete from cmp_etc_in where year='$year' and month='$month'";
    $dbo->query($sql);

    $price=rnf($price);
    $sql = "insert into cmp_etc_in (year,month,price) values ('$year','$month','$price')";
    $dbo->query($sql);
    if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql);} 
    $price_=nf(rnf($price));
    echo "
        <script>
            parent.location.reload();
        </script>
    ";
    exit;   
}


$dtype=($dtype)? $dtype : "d_date";


$year = ($year)? $year : date("Y");
$month = ($month)? $month : date("m");
$month = num2($month);


$date_s = date("Y/m/d",strtotime(date("$year/${month}/01")));
$date_e = date("Y/m/d",strtotime($date_s." +1 month")-1);

$date_s_ = date("Y/m/d",strtotime($date_s." -1 year"));
$date_e_ = date("Y/m/d",strtotime($date_s_." +1 month")-1);


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper";
$TITLE = "손익계산서";
$TITLE .=($dtype=="d_date")? "(출국일자 기준)" : "(예약일자 기준)";

$dtype=($dtype)?$dtype:"d_date";
?>
<?include("../top.html");?>
<script type="text/javascript">
function save_item(mode,year,month,price){
    var url = "<?=SELF?>";
    url += "?mode="+mode;
    url += "&year="+year;
    url += "&month="+month;
    url += "&price="+price;
    actarea.location.href=url;
}   
</script>
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
    <form name="fmSearch" method="get">
    <input type="hidden" name="position" value="">


    <tr height=22>
    <td valign='bottom' align="right">

    <!--
    <input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?=$date_s?>" class="box c dateinput">
    ~
    <input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?=$date_e?>" class="box c dateinput">
    -->

    <select name="dtype">
        <?=option_str("출국일기준,예약일","d_date,tour_date",$dtype)?>
    </select>

    기준년도 : <input type="text" name="year" id="year" size="13" maxlength="10" value="<?=$year?>" class="box c">

    <select name="month">
        <?=option_int(1,12,1,$month)?>
    </select>월

    <input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
    </td>
    <tr>
    </form>
    </table>
    </div>
    <!-- Search End------------------------------------------------>

    <?
    //+price_prev3,+price_refund 은 취소금액이라 매출에서 뺌
    $sql = "select
            sum(price_prev+price_prev2+price_prev3) as price_in,
            sum(price_air+price_land+price_refund) as price_out
        from cmp_people as a
        where bit=1
        and code in (
            select
                code
            from cmp_reservation as a
            where
            $dtype >='$date_s' and $dtype <='$date_e'
        )
    ";

    $dbo->query($sql);
    //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){ checkVar(mysql_error(),$sql);}
    $rs=$dbo->next_record();

    $price_in = $rs[price_in];//기간별 매출에서 입금액
    $price_out = $rs[price_out];//기간별 매출에서 출금액  

    //작년
    //+price_prev3,+price_refund 은 취소금액이라 매출에서 뺌
    $sql = "select
            sum(price_prev+price_prev2+price_prev3) as price_in,
            sum(price_air+price_land+price_refund) as price_out
        from cmp_people as a
        where bit=1
        and code in (
            select
                code
            from cmp_reservation as a
            where
            $dtype >='$date_s_' and $dtype <='$date_e_'
        )
    ";

    $dbo->query($sql);
    //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){ checkVar(mysql_error(),$sql);}
    $rs=$dbo->next_record();

    $price_in_ = $rs[price_in];//기간별 매출에서 입금액
    $price_out_ = $rs[price_out];//기간별 매출에서 출금액 
    ?>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
    
        <tr>
            <th class="subject" width="25%">구분</th>
            <th class="subject" width="25%">금액</th>
            <th class="subject" width="25%">전년동월</th>
            <th class="subject" width="25%">증가율</th>
        </tr>
        
        <tr>
            <td class="c" style="background-color:#f0f0f0">매출액</td>
            <td class="r"><?=nf($price_in)?></td>
            <td class="r"><?=nf($price_in_)?></td>
            <td class="r"><?=pm_rate($price_in,$price_in_)?>%</td>
        </tr>       
        <tr>
            <td class="c" style="background-color:#f0f0f0">매출원가</td>
            <td class="r"><?=nf($price_out)?></td>
            <td class="r"><?=nf($price_out_)?></td>
            <td class="r"><?=pm_rate($price_out,$price_out_)?>%</td>
        </tr>
        

        <?
        $cal1=$price_in-$price_out;
        $cal2=$price_in_-$price_out_;
        ?>
        <tr>
            <td class="subject c">매출총이익</td>
            <td class="subject r"><?=nf($cal1)?></td>
            <td class="subject r"><?=nf($cal2)?></td>
            <td class="subject r"><?=pm_rate($cal1,$cal2)?>%</td>
        </tr>



        <?
        /*판매관리비*/
        $sql = "
            select
                sum(a.price) as price
                from
                cmp_expense as a left join ez_category1 as b
                on a.category1=b.id_no
                where year = $year and month='$month'
                and b.bit_out=0
        ";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        $expense=$rs[price];
        $year2 = $year-1;
        //if($_SERVER["REMOTE_ADDR"]=="106.246.54.27") checkVar($rs[price].mysql_error(),$sql);

        $sql = "
            select
                sum(a.price) as price
                from
                cmp_expense as a left join ez_category1 as b
                on a.category1=b.id_no
                where year = $year2 and month='$month'
                and b.bit_out=0
        ";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        $expense_=$rs[price];       
        //if($_SERVER["REMOTE_ADDR"]=="106.246.54.27") checkVar(mysql_error(),$sql);
        ?>
        <tr>
            <td class="c" style="background-color:#f0f0f0">판매관리비</td>
            <td class="r"><?=nf($expense)?></td>
            <td class="r"><?=nf($expense_)?></td>
            <td class="r"><?=pm_rate($expense,$expense_)?>%</td>
        </tr>





        <?
        /*영업이익*/
        $profit = $cal1 - $expense;
        $profit_ = $cal2 - $expense_;
        ?>
        <tr>
            <td class="subject c" style="background-color:#f0f0f0">영업이익</td>
            <td class="subject r"><?=nf($profit)?></td>
            <td class="subject r"><?=nf($profit_)?></td>
            <td class="subject r"><?=pm_rate($profit,$profit_)?>%</td>
        </tr>

        <?
        $sql = "select * from cmp_etc_in where year='$year' and month='$month'";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        $etc_in=$rs[price];

        $sql = "select * from cmp_etc_in where year='$year2' and month='$month'";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        $etc_in_=$rs[price];        
        ?>
        <tr>
            <td class="subject c" style="background-color:#f0f0f0">영업외이익</td>
            <!-- <td class="subject r"><?//=nf($ex_profit)?></td> -->
            <!-- <td class="subject r"><?=nf($ex_profit_)?></td> -->
            <td class="subject" style="text-align:right;padding-right:13px;"><input type="text" class="box numberic" style="width:200px;font-size:9pt;" maxlength="12" onchange="save_item('ex_profit','<?=$year?>','<?=$month?>',this.value)" id="ex_profit" value="<?=nf($etc_in)?>" onfocus="this.select()"></td>
            <td class="subject r"><?=nf($etc_in_)?></td>
            <td class="subject r"><?=pm_rate($etc_in,$etc_in_)?>%</td>
        </tr>


        <?
        /*법인세전 이익*/
        $tex_profit = $profit+$etc_in;
        $tex_profit_ = $profit_+$etc_in_;
        ?>
        <tr>
            <td class="subject c" style="background-color:#f0f0f0">법인세전이익</td>
            <td class="subject r"><?=nf($tex_profit)?></td>
            <td class="subject r"><?=nf($tex_profit_)?></td>
            <td class="subject r"><?=pm_rate($tex_profit,$tex_profit_)?>%</td>
        </tr>




        <?
        $sql = "select * from cmp_tax where year='$year' and month='$month'";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        $tax=$rs[price];

        $sql = "select * from cmp_tax where year='$year2' and month='$month'";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        $tax_=$rs[price];
        ?>

        <tr>
            <td class="subject c" style="background-color:#f0f0f0">법인세</td>
            <td class="subject" style="text-align:right;padding-right:13px;"><input type="text" class="box numberic" style="width:200px;font-size:9pt;" maxlength="12" onchange="save_item('tax','<?=$year?>','<?=$month?>',this.value)" id="tax" value="<?=nf($tax)?>" onfocus="this.select()"></td>
            <td class="subject r"><?=nf($tax_)?></td>
            <td class="subject r"><?=pm_rate($tax,$tax_)?>%</td>
        </tr>   

        <!-- 순이익 -->
        <?
        $total_profit = $tex_profit - $tax;
        $total_profit_ = $tex_profit_ - $tax_;
        ?>
        <tr>
            <td class="subject c" style="background-color:#f0f0f0">순이익</td>
            <td class="subject r"><?=nf($total_profit)?></td>
            <td class="subject r"><?=nf($total_profit_)?></td>
            <td class="subject r"><?=pm_rate($total_profit,$total_profit_)?>%</td>
        </tr>               
    </table>





    <p>&nbsp;</p>
    <p>&nbsp;</p>


















    <!--누계-->
    <?
    $year = substr($date_s,0,4);
    $year2 = $year-1;
    $date_s = $year."/01/01";
    $date_e = date("Y/m/d",strtotime("${year}/${month}/01 +1 month")-1);
    $date_s_ = substr($date_s_,0,4)."/01/01";
    $date_e_ = date("Y/m/d",strtotime("${year2}/${month}/01 +1 month")-1);
    
    echo "<strong> - 누계 ( $date_s ~ $date_e ) </strong>";

    //+price_prev3,+price_refund 은 취소금액이라 매출에서 뺌
    $sql = "select
            sum(price_prev+price_prev2+price_prev3) as price_in,
            sum(price_air+price_land+price_refund) as price_out
        from cmp_people as a
        where bit=1
        and code in (
            select
                code
            from cmp_reservation as a
            where
            $dtype >='$date_s' and $dtype <='$date_e'
        )
    ";
    $dbo->query($sql);
    //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}
    $rs=$dbo->next_record();

    $price_in = $rs[price_in];//기간별 매출에서 입금액
    $price_out = $rs[price_out];//기간별 매출에서 출금액  

    //작년
    //+price_prev3,+price_refund 은 취소금액이라 매출에서 뺌
    $sql = "select
            sum(price_prev+price_prev2+price_prev3) as price_in,
            sum(price_air+price_land+price_refund) as price_out
        from cmp_people as a
        where bit=1
        and code in (
            select
                code
            from cmp_reservation as a
            where
            $dtype >='$date_s_' and $dtype <='$date_e_'
        )
    ";

    $dbo->query($sql);
    //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}
    $rs=$dbo->next_record();

    $price_in_ = $rs[price_in];//기간별 매출에서 입금액
    $price_out_ = $rs[price_out];//기간별 매출에서 출금액 
    ?>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
    
        <tr>
            <th class="subject" width="25%">구분</th>
            <th class="subject" width="25%">금액</th>
            <th class="subject" width="25%">전년동월</th>
            <th class="subject" width="25%">증가율</th>
        </tr>
        
        <tr>
            <td class="c" style="background-color:#f0f0f0">매출액</td>
            <td class="r"><?=nf($price_in)?></td>
            <td class="r"><?=nf($price_in_)?></td>
            <td class="r"><?=pm_rate($price_in,$price_in_)?>%</td>
        </tr>       
        <tr>
            <td class="c" style="background-color:#f0f0f0">매출원가</td>
            <td class="r"><?=nf($price_out)?></td>
            <td class="r"><?=nf($price_out_)?></td>
            <td class="r"><?=pm_rate($price_out,$price_out_)?>%</td>
        </tr>
        

        <?
        $cal1=$price_in-$price_out;
        $cal2=$price_in_-$price_out_;
        ?>
        <tr>
            <td class="subject c">매출총이익</td>
            <td class="subject r"><?=nf($cal1)?></td>
            <td class="subject r"><?=nf($cal2)?></td>
            <td class="subject r"><?=pm_rate($cal1,$cal2)?>%</td>
        </tr>



        <?
        /*판매관리비*/
        $sql = "
            select
                sum(a.price) as price
                from
                cmp_expense as a left join ez_category1 as b
                on a.category1=b.id_no
                where year = $year and month<='$month'
                and b.bit_out=0
        ";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        $expense=$rs[price];
        $year2 = $year-1;


        $sql = "
            select
                sum(a.price) as price
                from
                cmp_expense as a left join ez_category1 as b
                on a.category1=b.id_no
                where year = $year2 and month<='$month'
                and b.bit_out=0
        ";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        $expense_=$rs[price];       
        //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){ checkVar(mysql_error(),$sql);}
        ?>
        <tr>
            <td class="c" style="background-color:#f0f0f0">판매관리비</td>
            <td class="r"><?=nf($expense)?></td>
            <td class="r"><?=nf($expense_)?></td>
            <td class="r"><?=pm_rate($expense,$expense_)?>%</td>
        </tr>





        <?
        /*영업이익*/
        $profit = $cal1 - $expense;
        $profit_ = $cal2 - $expense_;
        ?>
        <tr>
            <td class="subject c" style="background-color:#f0f0f0">영업이익</td>
            <td class="subject r"><?=nf($profit)?></td>
            <td class="subject r"><?=nf($profit_)?></td>
            <td class="subject r"><?=pm_rate($profit,$profit_)?>%</td>
        </tr>

        <?
        // 월별비용의 입금내역소개
        $sql = "
            select
                sum(a.price) as bank_in
                from
                cmp_expense as a left join ez_category1 as b
                on a.category1=b.id_no
                where a.year = $year and a.month<='".ceil($month)."'
                and b.category1 in ('정부지원금','입금','자산')
        ";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        //$bank_in=0-$rs[bank_in];
        $bank_in=$rs[bank_in];
        $sql = "
            select
                sum(a.price) as bank_in2
                from
                cmp_expense as a left join ez_category1 as b
                on a.category1=b.id_no
                where a.year = $year2 and a.month<='".ceil($month)."'
                and b.category1 in ('정부지원금','입금','자산')
        ";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        //$bank_in2=0-$rs[bank_in2];
        $bank_in2=$rs[bank_in2];
        //checkVar($bank_in2.mysql_error(),$sql);

        // $sql = "select * from cmp_etc_in where year='$year' and month<='$month'";
        // $dbo->query($sql);
        // $rs=$dbo->next_record();
        // $etc_in=$rs[price];
        //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){ checkVar(mysql_error(),$sql);}

        // $sql = "select * from cmp_etc_in where year='$year2' and month<='$month'";
        // $dbo->query($sql);
        // $rs=$dbo->next_record();
        // $etc_in_=$rs[price];        
        //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){ checkVar(mysql_error(),$sql);}
        ?>
        <tr>
            <td class="subject c" style="background-color:#f0f0f0">영업외이익</td>
            <!-- <td class="subject r"><?//=nf($ex_profit)?></td> -->
            <!-- <td class="subject r"><?=nf($ex_profit_)?></td> -->
            <td class="subject r"><?=nf($bank_in)?><!--월별비용의 입금내역소개--></td>
            <td class="subject r"><?=nf($bank_in2)?></td>
            <td class="subject r"><?=pm_rate($bank_in,$bank_in2)?>%</td>
        </tr>


        <?
        /*법인세전 이익*/
        $tex_profit = $profit+$bank_in;
        $tex_profit_ = $profit_+$bank_in2;
        ?>
        <tr>
            <td class="subject c" style="background-color:#f0f0f0">법인세전이익</td>
            <td class="subject r"><?=nf($tex_profit)?></td>
            <td class="subject r"><?=nf($tex_profit_)?></td>
            <td class="subject r"><?=pm_rate($tex_profit,$tex_profit_)?>%</td>
        </tr>




        <?
        $sql = "select * from cmp_tax where year='$year' and month<='$month'";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        $tax=$rs[price];
        //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){ checkVar(mysql_error(),$sql);}

        $sql = "select * from cmp_tax where year='$year2' and month<='$month'";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        $tax_=$rs[price];
        //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){ checkVar(mysql_error(),$sql);}
        ?>

        <tr>
            <td class="subject c" style="background-color:#f0f0f0">법인세</td>
            <td class="subject r"><?=nf($tax)?></td>
            <td class="subject r"><?=nf($tax_)?></td>
            <td class="subject r"><?=pm_rate($tax,$tax_)?>%</td>
        </tr>   

        <!-- 순이익 -->
        <?
        $total_profit = $tex_profit - $tax;
        $total_profit_ = $tex_profit_ - $tax_;
        ?>
        <tr>
            <td class="subject c" style="background-color:#f0f0f0">순이익</td>
            <td class="subject r"><?=nf($total_profit)?></td>
            <td class="subject r"><?=nf($total_profit_)?></td>
            <td class="subject r"><?=pm_rate($total_profit,$total_profit_)?>%</td>
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
                <span class="btn_pack medium bold"><a href="list_<?=$filecode?>_excel.php?<?=$_SERVER[QUERY_STRING]?>"> 엑셀 </a></span>
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

