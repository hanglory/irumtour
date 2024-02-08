<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"엑셀다운로드");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_people";
$MENU = "cmp_basic";
$LEFT_HIDDEN="1";
$TITLE = "기간별입출금현황";


if($mode=="save" && $id_no){
	$sql ="update cmp_people set memo='$memo' where id_no=$id_no";
	$dbo->query($sql);
	exit;
}


####각종 기초 정보 결정
$view_row=20;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$column = "*";
$basicLink = "";



####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
if(!$target2) $target2="tour_date";
if(!$period_s) $period_s=date("Y/m/d",mktime(0,0,0,date("m"),1,date("Y")));
if(!$period_e) $period_e=date("Y/m/d",mktime(0,0,0,date("m")+1,1,date("Y"))-1);

#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;

if($target2=="total"){
	$filter .="
		and (
			(date_in >='$period_s' and date_in <='$period_e')
			or (date_in2 >='$period_s' and date_in2 <='$period_e')
			or (date_out >='$period_s' and date_out <='$period_e')
			or (date_out2 >='$period_s' and date_out2 <='$period_e')
		)
	";
	$rs[period_s]=$period_s;
	$rs[period_e]=$period_e;
	$find_bit=1;
}else{
	if($period_s){ $filter.= " and $target2 >='$period_s' ";$rs[period_s]=$period_s;$find_bit=1;}
	if($period_e){ $filter.= " and $target2 <='$period_e' ";$rs[period_e]=$period_e;$find_bit=1;}
}

if($keyword){
	$filter .=" and $target like '%$keyword%' ";
	$best="";	 //배너 select 초기화
	$findMode=1;
}



#query
$sql_1 = "
	select 
		a.*,
		b.name as leader,
		(select count(name) from cmp_people where code=a.code and name<>'' and bit=1) as cnt
	from cmp_people as a left join cmp_reservation as b
	on a.code=b.code
	where 
        a.code<>'' 
	    and a.name<>''
	    and a.bit=1
        and b.cp_id='$CP_ID'
	    $filter
";
$sql_2 = $sql_1 . " order by code desc,id_no asc";
//checkVar("",$sql_2);

####자료갯수
list($rows)=$dbo->query($sql_1);//검색된 자료의 갯수
$row_search = $rows;


if(!strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
	$xls_name = "payment_".date("Ymd").".xls";		
	header("Content-type: application/vnd.ms-excel");
	header("Content-Type: application/vnd.ms-excel; charset=euc-kr");
	header("Content-Disposition: attachment;filename=;");
	header( "Content-Description: PHP4 Generated Data" );
}
?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>

    <table border="1" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >대표자명</th>
		<th class="subject" >고객명</th>
		<th class="subject" >판매가</th>
		<th class="subject blue" >출금액1<br/>(항공요금)</th>
		<th class="subject blue" >출금일1</th>
		<th class="subject blue" >출금액2<br/>(지상비)</th>
		<th class="subject blue" >출금일2</th>
		<th class="subject blue" >출금액3<br/>(고객환불)</th>
		<th class="subject blue" >출금일3</th>
		<th class="subject red" >입금액1<br>(계약금)</th>
		<th class="subject red" >입금일1</th>
		<th class="subject red" >입금액2<br>(잔금)</th>
		<th class="subject red" >입금일2</th>
		<th class="subject red" >입금액3<br>(거래처환불)</th>
		<th class="subject red" >입금일3</th>
		<th class="subject" >메모</th>
		</tr>
<?
$sum1=0;
$sum2=0;
$sum3=0;
$sum4=0;
$sum5=0;
$sum6=0;

if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//if($REMOTE_ADDR=="106.246.54.27") checkVar($target.mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	$row = ($prev_leader!=$rs[leader])?1:0;	


	if($rs[date_out] >=$period_s && $rs[date_out] <=$period_e){}else{$rs[price_air]=0;$rs[date_out]="";}
	if($rs[date_out2] >=$period_s && $rs[date_out2] <=$period_e){}else{$rs[price_land]=0;$rs[date_out2]="";}
	if($rs[date_out3] >=$period_s && $rs[date_out3] <=$period_e){}else{$rs[price_refund]=0;$rs[date_out3]="";}
	if($rs[date_in] >=$period_s && $rs[date_in] <=$period_e){}else{$rs[price_prev]=0;$rs[date_in]="";}
	if($rs[date_in2] >=$period_s && $rs[date_in2] <=$period_e){}else{$rs[price_prev2]=0;$rs[date_in2]="";}
	if($rs[date_in3] >=$period_s && $rs[date_in3] <=$period_e){}else{$rs[price_prev3]=0;$rs[date_in3]="";}
	

	$sum1 += $rs[price];
	$sum2 += $rs[price_air];
	$sum3 += $rs[price_land];
	$sum4 += $rs[price_prev];
	$sum5 += $rs[price_last];
	$sum6 += $rs[price_prev2];
	$sum7 += $rs[price_refund];
	$sum8 += $rs[price_prev3];
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      
	      <?if($target!="a.name" && $target2!="a.date_in" && $target2!="a.date_out" && $target2!="a.date_in2" && $target2!="a.date_out2" && $target2!="total"){?>
	      <?if($row){?>	
	      <td rowspan="<?=$rs[cnt]?>"><?=$rs[leader]?></td>
	      <?}?>
	      <?}else{?>
	      <td><?=$rs[leader]?></td>
	      <?}?>

	      <td height="30"><?=$rs[name]?></td>
	      <td class="r pr10"><?=nf($rs[price])?></td>
	      <td class="r pr10 blue"><?=nf($rs[price_air])?></td>
	      <td class="blue"><?=($rs[date_out])?></td>
	      <td class="r pr10 blue"><?=nf($rs[price_land])?></td>
	      <td class="blue"><?=($rs[date_out2])?></td>

	      <td class="r pr10 blue"><?=nf($rs[price_refund])?></td>
	      <td class="blue"><?=($rs[date_out3])?></td>

	      <td class="r pr10 red"><?=nf($rs[price_prev])?></td>
	      <td class="red"><?=($rs[date_in])?></td>
	      <td class="r pr10 red"><?=nf($rs[price_prev2])?></td>
	      <td class="red"><?=($rs[date_in2])?></td>

	      <td class="r pr10 red"><?=nf($rs[price_prev3])?></td>
	      <td class="red"><?=($rs[date_in3])?></td>

	      <td><?=$rs[memo]?></td>
	    </tr>
<?
	$prev_leader = $rs[leader];

	$num--;
}
?>
        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="subject" colspan="2" rowspan="2">합계</th>
            <th class="r pr10" style="text-align: right !important" rowspan="2"><?=nf($sum1)?></th>
            <th class="r pr10 blue" style="text-align: right !important"><?=nf($sum2)?></th>
            <th class="subject" ></th>
            <th class="r pr10 blue" style="text-align: right !important"><?=nf($sum3)?></th>
            <th class="subject" ></th>      
            <th class="r pr10 blue" style="text-align: right !important"><?=nf($sum7)?></th>
            <th class="subject" ></th>
            <th class="r pr10 red" style="text-align: right !important"><?=nf($sum4)?></th>
            <th class="subject" ></th>
            <th class="r pr10 red" style="text-align: right !important"><?=nf($sum6)?></th>
            <th class="subject" ></th>
            <th class="r pr10 red" style="text-align: right !important"><?=nf($sum8)?></th>
            <th class="subject" ></th>
            <th class="subject" ></th>      
        </tr>
        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="r pr10 blue" colspan="5" style="text-align: right !important"><?=nf($sum2+$sum3+$sum7)?></th>
            <th class="subject" ></th>
            <th class="subject" ></th>
            <th class="r pr10 red" colspan="4" style="text-align: right !important"><?=nf($sum4+$sum6+$sum8)?></th>
            <th class="subject" ></th>      
            <th class="subject" ></th>      
        </tr>        
	</table>

</body>
</html>
