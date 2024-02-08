<?
include_once("../include/common_file.php");


chk_power($_SESSION["sessLogin"]["proof_erp"],"엑셀다운로드");


if(!strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
$xls_name = date("Ymd")."_account.xls";
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
<title>Irumtour</title>
<style type="text/css" media="screen">
	*{font-size:11pt;}
</style>
</head>



    <table border="1" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
<?

/*파트너용 필터*/
$FILTER_PARTNER_QUERY = str_replace("cp_id","b.cp_id",$FILTER_PARTNER_QUERY);
$filter.=($FILTER_PARTNER_QUERY)? $FILTER_PARTNER_QUERY : " and b.cp_id=''";


	if($keyword){
		if($target=="a.account"){
			$filter1=" and a.account like '%$keyword%'";	
			$filter2=" and a.account2 like '%$keyword%'";	
		}else{
			$keyword = trim($keyword);
			$filter = " and $target like '%$keyword%'";
		}
	}

	if($pay_fix){
		$pay_fix_ = ($pay_fix=="T")? 1:0;

		if(!$pay_fix_){
			$filter_fix1=" and (pay_fix =0 or pay_fix='' or pay_fix is null)";
			$filter_fix2=" and (pay_fix2 =0 or pay_fix2='' or pay_fix2 is null)";
			$filter_fix3=" and (pay_fix3 =0 or pay_fix3='' or pay_fix3 is null)";
		}else{
			$filter_fix1=" and pay_fix=$pay_fix_";
			$filter_fix2=" and pay_fix2=$pay_fix_";
			$filter_fix3=" and pay_fix3=$pay_fix_";
		}
	}

	$sql ="
		select 
			'air' as assort,
			a.code,
			a.id_no,
			a.name,
			a.account,
			a.pay_fix,
			a.trans_memo,
			a.trans_memo2,
			a.price_air,
			'' as price_land,
			'' as price_refund,
			b.main_staff as staff,
			a.date_out as date_out_,
			a.send_date,
			a.datetime_out,
			b.name as leader,
			b.id_no as reserv_id_no,
			b.tour_date,
			b.d_date,
			(select name from cmp_golf where id_no=b.golf_id_no) as golf,
			(select partner from cmp_golf where id_no=b.golf_id_no) as partner
			from cmp_people as a right join cmp_reservation as b
			on a.code=b.code
			where
				a.bit=1
				and a.name<>''
				and price_air>0
				and (a.date_out >= '$date_s' and a.date_out <='$date_e')
				$filter
				$filter1
				$filter_fix1

		union all 

		select 
			'land' as assort,
			a.code,
			a.id_no,
			a.name,
			a.account2 as account,
			a.pay_fix2 as pay_fix,
			a.trans_memo,
			a.trans_memo2,
			'' as price_air,
			a.price_land,
			'' as price_refund,
			b.main_staff as staff,
			a.date_out2 as date_out_,
			a.send_date2 as send_date,
			a.datetime_out,
			b.name as leader,
			b.id_no as reserv_id_no,
			b.tour_date,
			b.d_date,
			(select name from cmp_golf where id_no=b.golf_id_no) as golf,
			(select partner from cmp_golf where id_no=b.golf_id_no) as partner
			from cmp_people as a right join cmp_reservation as b
			on a.code=b.code
			where
				a.bit=1
				and a.name<>''
				and price_land>0
				and (a.date_out2 >= '$date_s' and a.date_out2 <='$date_e')
				$filter			
				$filter2			
				$filter_fix2

		union all 


		select 
			'refund' as assort,
			a.code,
			a.id_no,
			a.name,
			a.account3 as account,
			a.pay_fix3 as pay_fix,
			a.trans_memo,
			a.trans_memo2,
			'' as price_air,
			'' as price_land,
			a.price_refund,
			b.main_staff as staff,
			a.date_out3 as date_out_,
			a.send_date3 as send_date,
			a.datetime_out,
			b.name as leader,
			b.id_no as reserv_id_no,
			b.tour_date,
			b.d_date,
			(select name from cmp_golf where id_no=b.golf_id_no) as golf,
			(select partner from cmp_golf where id_no=b.golf_id_no) as partner
			from cmp_people as a right join cmp_reservation as b
			on a.code=b.code
			where
				a.bit=1
				and a.name<>''
				and price_refund>0
				and (a.date_out3 >= '$date_s' and a.date_out3 <='$date_e')
				$filter			
				$filter2	
				$filter_fix3						

		order by date_out_ desc,datetime_out desc, leader asc	
	";

	$dbo->query($sql);
	if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){
		$price = 0;
		$price += $rs[price_air];
		$price += $rs[price_land];
		$price += $rs[price_refund];

		$price_type="";
		if($rs[price_air]) $price_type="항공";
		elseif($rs[price_land]) $price_type="지상비";
		elseif($rs[price_refund]) $price_type="환불";

		$leader="";
		$leader = explode("(",$rs[leader]);		

		$account= @explode(" ",$rs[account]);
		$account_bank = $account[0];
		$account_etc = substr($rs[account],strlen($account[0]));
		$account_etc_arr = @explode(" ",$account_etc);
		$account_num="";
		$last_num="";
		while(list($key,$val)=each($account_etc_arr)){
			if(rnf($val)){
				$account_num.="-".$val;
				$last_num=$val;
			}
		}
		$account_num = substr($account_num,1);
		$account_etc_arr2=@explode($last_num,$account_etc);
		$account_owner=$account_etc_arr2[1];

		if($account_num){
?>
	    <tr align='center'>
	      <td><?=$account[0]?></td>
	      <td><?=$account_num?></td>
	      <td align="right"><?=nf($price)?></td>
	      <td><?=$account_owner?></td>
	      <td><?=$leader[0]?> 이룸투어</td>
	      <td><?=$leader[0]?> <?=$price_type?></td>
	    </tr>
  
<?
		}
}
?>
	</table></body>
</html>
