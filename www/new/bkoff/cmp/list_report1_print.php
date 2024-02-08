<?
include_once("../include/common_file_report.php");



####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_reservation";
$MENU = "cmp_basic";
$LEFT_HIDDEN = "1";
$TITLE = "기간별매출";



#### 기본 정보
$column = "*";
$basicLink = "";


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)


if(!$target2) $target2="tour_date";
if(!$period_s) $period_s=date("Y/m/d",mktime(0,0,0,date("m"),1,date("Y")));
if(!$period_e) $period_e=date("Y/m/d",mktime(0,0,0,date("m")+1,1,date("Y"))-1);


#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : trim($keyword);
if($keyword){
    $filter .=" and $target like '%$keyword%' ";
    $best="";    //배너 select 초기화
    $findMode=1;
}

if($period_s){ $filter.= " and $target2 >='$period_s' ";$rs[period_s]=$period_s;$find_bit=1;}
if($period_e){ $filter.= " and $target2 <='$period_e' ";$rs[period_e]=$period_e;$find_bit=1;}


if($research){

    checkVar("1",$rkeyword1);
    checkVar("2",$rkeyword2);

    if($rkeyword1) $filter .= " and $rtarget1 like '%$rkeyword1%'";
    if($rkeyword2) $filter .= " and $rtarget2 like '%$rkeyword2%'";

}

if(strstr($_SESSION["sessLogin"]["staff_type"],"partner")) $filter.=" and main_staff like '%($user_id)'";

#query
if($target!="a.partner"){
    $sql_1 = "
        select
            a.*,
            (select subject from cmp_estimate where id_no=a.origin_id_no) as est_subject,
            (select nation from cmp_golf where id_no=a.golf_id_no) as nation,
            (select city from cmp_golf where id_no=a.golf_id_no) as city,
            (select count(*) from cmp_people where bit=1 and code=a.code and price_air>0 and date_out='') as blank_date1,
            (select count(*) from cmp_people where bit=1 and code=a.code and price_land>0 and date_out2='') as blank_date2,
            (select count(*) from cmp_people where bit=1 and code=a.code and price_refund>0 and date_out3='') as blank_date3,
            (select count(*) from cmp_people where bit=1 and code=a.code and price_prev>0 and date_in='') as blank_date4,
            (select count(*) from cmp_people where bit=1 and code=a.code and price_prev2>0 and date_in2='') as blank_date5,
            (select count(*) from cmp_people where bit=1 and code=a.code and price_prev3>0 and date_in3='') as blank_date6,
            (select count(*) from cmp_people where bit=1 and code=a.code and bit_cancel=1) as bit_cancel
            from $table as a
            where
            a.id_no>0
            $filter
        ";          //자료수
    $sql_2 = $sql_1 . " order by a.id_no desc"; // limit  $start, $view_row

}else{

#query
    $sql_1 = "
        select
            a.*,
            a.partner,
            b.nation,
            b.city,
            (select subject from cmp_estimate where id_no=a.origin_id_no) as est_subject,
            (select count(*) from cmp_people where bit=1 and code=a.code and bit_cancel=1) as bit_cancel
            from $table as a left join cmp_golf as b
            on a.golf_id_no=b.id_no
            where
            a.id_no>0
            $filter
        ";          //자료수
    $sql_2 = $sql_1 . " order by a.id_no desc"; // limit  $start, $view_row
}


if(!strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
$xls_name = "report1_" . date("Ymd") . ".xls";
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

</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="padding:10px">


    <table border="0" cellspacing="0" cellpadding="3" width="3500" id="tbl_cmp_list" style="border-collapse:collapse;border:1px solid #ccc ">
	    <tr align=center height=25 bgcolor="#F7F7F6">
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">대표자명</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">국가</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">지역</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">상품명</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">경로</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">인원</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">예약일</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">출발일</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">박수</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" >총판매가</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" colspan="4">출금내역</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">매출<br/>입금액(계약금+잔금)-출금액</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">1인수익</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">고객입금액</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">담당자</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">결제수단</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" rowspan="2">마감</th>
        </tr>


        <tr align=center height=25 bgcolor="#F7F7F6">
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">인원수<br/>*1인판매가</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">항공</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">지상</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">고객환불</th>
        <th style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">소계</th>
		</tr>

<?
$price1=0;
$price2=0;
$price3=0;
$price4=0;
$price5=0;
$price6=0;
$price7=0;
$price8=0;
$price9=0;
$price10=0;
$price11=0;
$price12=0;
$price13=0;

if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//if(strstr("@112.172.15.90@221.154.110.119@",$_SERVER["REMOTE_ADDR"])) checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

    $rs[golf_name] = ($rs[est_subject])? $rs[est_subject] : $rs[golf_name];
    if(!strstr($rs[golf_name],">")) $rs[golf_name] ="$rs[nation]>$rs[city]>$rs[golf_name]";

    $golf_name = explode(">",$rs[golf_name]);

    //if(strstr("@106.246.54.27@221.154.110.119@",$_SERVER["REMOTE_ADDR"])){checkVar($rs[golf_id_no],$rs[golf_name]);}

    $night = (strtotime($rs[r_date]) -  strtotime($rs[d_date]))/86400;//박수

    $sql3 = "select
            count(*) as cnt,
            sum(price) as price,
            sum(price_air) as price_air,
            sum(price_land) as price_land,
            sum(price_refund) as price_refund,
            sum(price_prev+price_prev2+price_prev3) as price_prev,
            sum((price_prev+price_prev2+price_prev3)-(price_air+price_land+price_refund)) as fee
        from cmp_people where code=$rs[code] and bit=1";
    $dbo3->query($sql3);
    $rs3= $dbo3->next_record();
    //if($_SERVER["REMOTE_ADDR"]=="106.246.54.27") checkVar(mysql_error(),$sql3);

    if($rs[d_date]<="2017/12/31"){
        @$gain_one = ($rs3[price] - ($rs3[price_air]+$rs3[price_land]+$rs3[price_refund]))/$rs[people];
    }else{
        @$gain_one = (rnf($rs3[fee]))/$rs[people];
    }

    $staff = explode("(",$rs[main_staff]);

    /*
    if($REMOTE_ADDR=="106.246.54.27"){
        $update_price = $rs3[price]-($rs3[price_air]+$rs3[price_land]);
        $sql3 = "update cmp_reservation set fee=$update_price where id_no=$rs[id_no]";
        $dbo3->query($sql3);
        //checkVar(mysql_error(),$sql3);
    }
    */

    $css = ($rs[bit_cancel])? "color:red":"";
    //if($rs[bit_cancel]) $rs[people]-=$rs[bit_cancel];


    $blank_date1 = 0;
    $blank_date1 = $rs[blank_date1]+$rs[blank_date2]+$rs[blank_date3]+$rs[blank_date4]+$rs[blank_date5]+$rs[blank_date6];

?>
	    <tr align='center'>
		    <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" height="35"><?=$rs[name]?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=trim($golf_name[0])?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=trim($golf_name[1])?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=trim($golf_name[2])?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[view_path]?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=nf($rs[people])?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[tour_date]?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[d_date]?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$night?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=nf($rs3[price])?><!--매출/인원수*1인판매가--></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=nf($rs3[price_air])?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=nf($rs3[price_land])?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=nf($rs3[price_refund])?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic red"><?=nf($rs3[price_air]+$rs3[price_land]+$rs3[price_refund])?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=nf($rs3[fee])?><!-- 매출 --></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic"><?=nf($gain_one)?><!-- 1인수익 --></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc" class="numberic red"><?=nf($rs3[price_prev])?><!-- 고객입금액 --></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$staff[0]?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[pay_method]?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=($rs[bit_close])?"Y":"<span style='color:#ccc'>N</span>"?></td>
	    </tr>
<?

    if($rs[bit_cancel] && !$rs3[fee]){//취소 중 매출이 0인 경우 전체 합계에서 제외

    }else{

        $price1+=$rs[people];
        $price2+=$rs3[price];
        $price3+=$rs3[price_air];
        $price4+=$rs3[price_land];
        $price13+=$rs3[price_refund];
        $price5+=($rs3[price_air]+$rs3[price_land]+$rs3[price_refund]);
        $price6+=($rs3[fee]);
        $price7+=$gain_one;
        $price8+=($rs[price_prev]);
        $price9+=($rs[price_tmp_output]);

        $price10+=$rs[golf_ball];
        $price11+=$rs[air_cover];
        $price13+=$rs[dollarbook];
        $price12+=$rs[payed_price];

        $num--;
    }
    $num--;
}
?>

	    <tr align='center'>
		    <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red" height="35" colspan="5" style="font-weight:bold;color:red">합계</td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price1)?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price2)?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price3)?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price4)?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price13)?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price5)?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price6)?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price7)?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"><?=nf($price8)?></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"></td>
            <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc;font-weight:bold;color:red"></td>
	    </tr>
	</table>

</body>
</html>