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

if(!strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
$xls_name = date("Ymd")."_profit_loss.xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/vnd.ms-excel; charset=euc-kr");
header("Content-Disposition: attachment;filename=;");
header( "Content-Description: PHP4 Generated Data" );
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Irumtour</title>
<style type="text/css" media="screen">
	*{font-size:11pt;}
	.r{text-align: right;}
</style>
</head>
<body>

	

	<?
	//+price_prev3,+price_refund 은 취소금액이라 매출에서 뺌
	$sql = "select
			sum(price_prev+price_prev2) as price_in,
			sum(price_air+price_land) as price_out
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
	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql);}
	$rs=$dbo->next_record();

	$price_in = $rs[price_in];//기간별 매출에서 입금액
	$price_out = $rs[price_out];//기간별 매출에서 출금액 	

	//작년
	//+price_prev3,+price_refund 은 취소금액이라 매출에서 뺌
	$sql = "select
			sum(price_prev+price_prev2) as price_in,
			sum(price_air+price_land) as price_out
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
	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql);}
	$rs=$dbo->next_record();

	$price_in_ = $rs[price_in];//기간별 매출에서 입금액
	$price_out_ = $rs[price_out];//기간별 매출에서 출금액 
	?>

	<table border="1" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
	
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
		/*업업외 이익*/
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
			<td class="subject r"><?=nf($etc_in)?></td>
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









    <!--누계-->
    <?
    $year = substr($date_s,0,4);
    $year2 = $year-1;
    $date_s = $year."/01/01";
    $date_e = date("Y/m/d",strtotime("${year}/${month}/01 +1 month")-1);
    $date_s_ = substr($date_s_,0,4)."/01/01";
    $date_e_ = date("Y/m/d",strtotime("${year2}/${month}/01 +1 month")-1);

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

    <table border="1" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
    
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
        $bank_in=0-$rs[bank_in];
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
        $bank_in2=0-$rs[bank_in2];
        //checkVar($bank_in2.mysql_error(),$sql);

        $sql = "select * from cmp_etc_in where year='$year' and month<='$month'";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        $etc_in=$rs[price];
        //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){ checkVar(mysql_error(),$sql);}

        $sql = "select * from cmp_etc_in where year='$year2' and month<='$month'";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        $etc_in_=$rs[price];        
        //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){ checkVar(mysql_error(),$sql);}
        ?>
        <tr>
            <td class="subject c" style="background-color:#f0f0f0">영업외이익</td>
            <!-- <td class="subject r"><?//=nf($ex_profit)?></td> -->
            <!-- <td class="subject r"><?=nf($ex_profit_)?></td> -->
            <td class="subject r"><?=nf($bank_in)?><!--월별비용의 입금내역소개--></td>
            <td class="subject r"><?=nf($bank_in2)?></td>
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







</body>
</html>