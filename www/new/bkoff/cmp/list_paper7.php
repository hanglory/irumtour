<?
include_once("../include/common_file.php");
header("Content-Type: text/html; charset=UTF-8");

//chk_power($_SESSION["sessLogin"]["proof"],"보고서");
$LEFT_HIDDEN="1";


if($mode=="save"){
	$return_price = rnf($price);
	$return_price2 = rnf($price2);
	$arr = explode("-",$code2);
	$code= $arr[0];

	$sql = "select * from cmp_paper7 where code2='$code2' and code2<>'' ";
	list($rows) = $dbo->query($sql);

	$memo = addslashes($memo);

	if($rows){
		$sql ="
			update cmp_paper7 set
				return_date='$return_date',
				partner='$partner',
				memo='$memo',
				return_price = '$return_price',
				return_price2 = '$return_price2'
			where code2='$code2' and code2<>''
		";
	}else{
		$sql="
			insert into cmp_paper7 (
			   code,
			   code2,
			   return_date,
			   partner,
			   memo,
			   return_price,
			   return_price2
		   ) values (
			   '$code',
			   '$code2',
			   '$return_date',
			   '$partner',
			   '$memo',
			   '$return_price',
			   '$return_price2'
		 )";
	}
	$dbo->query($sql);

	$return_price_ = nf($return_price);

	echo "
		<script>
			parent.sum();
			parent.document.getElementById('return_${code2}').value = '$return_price_';
		</script>
	";

	exit;
}
elseif($mode=="sum"){

	$keyword = iconv("euc-kr","utf-8",$keyword);

	$sql = "
		select
			sum(a.return_price) as return_price,
			sum(a.return_price2) as return_price2
		from cmp_paper7 as a left join cmp_reservation as b
		on a.code=b.code
		where
		(select count(*) from cmp_people where code=b.code and bit=1 and name<>'' and bit_cancel=1)>1
		and (b.${dtype} >= '$date_s' and b.${dtype} <='$date_e')
		and b.name like '%$keyword%'
	";
	$dbo->query($sql);
	$rs=$dbo->next_record();

	//checkVar($rs[return_price] . mysql_error(),$sql);

	$return_price_ = nf($rs[return_price]);
	$return_price2_ = nf($rs[return_price2]);

	echo "
		<script>
			parent.document.getElementById('sum2').innerHTML = '$return_price_';
			parent.document.getElementById('sum3').innerHTML = '$return_price2_';
		</script>
	";

	exit;

}

$dtype=($dtype)? $dtype : "d_date";

$date_s = ($date_s)? $date_s : date("Y/01/01");
$date_e = ($date_e)? $date_e : date("Y/12/31");

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
$TITLE = "취소자 리스트";

?>
<?include("../top.html");?>
<style type="text/css">
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:center;padding-right:2px;}
.r{padding-right:5px !important}
</style>

<script type="text/javascript">
<!--
function sum(){
	var url ="<?=SELF?>?mode=sum";
	url +="&date_s=<?=$date_s?>";
	url +="&date_e=<?=$date_e?>";
	url +="&dtype=<?=$dtype?>";
	url +="&keyword=<?=$keyword?>";
	actarea.location.href=url;
}

function rprice_save(code2){
	var url = "<?=SELF?>?mode=save";
	url +="&code2="+code2;
	url +="&return_date="+$("#return_date_"+code2).val();
	url +="&partner="+$("#partner_"+code2).val();
	url +="&price="+$("#return_"+code2).val();
	url +="&price2="+$("#return2_"+code2).val();
	url +="&memo="+$("#memo_"+code2).val();

	actarea.location.href=url;
}

$(function(){
	$(".numberic").on("focus",function(){
		this.select();
	});

	sum();
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

	대표자 : <input type="text" name="keyword" id="keyword" value="<?=$keyword?>" size="10" maxlength="20">

	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>

	<?
	if($keyword){
		$keyword = trim($keyword);
		$filter = " and name like '%$keyword%'";
	}

	$sql = "
		select
			*
			from cmp_reservation
		where
			name<>''
			and (select count(*) from cmp_people where code=cmp_reservation.code and bit=1 and name<>'' and bit_cancel=1)>=1
			and ($dtype >= '$date_s' and $dtype <='$date_e')
			$filter
		order by id_no desc
	";

	list($rows) = $dbo->query($sql);
	//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27") checkVar($rows. mysql_error(),$sql);

	?>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align="center" height="25" bgcolor="#F7F7F6"'>
			<th class="subject" width="150">대표자명</th>
			<th class="subject" width="75">고객명</th>
			<th class="subject" width="70">담당자</th>
			<th class="subject" width="100">거래처</th>
			<th class="subject" width="65">취소일자</th>
			<th class="subject" width="65">환불일자</th>
			<th class="subject" width="80">항공요금</th>
			<th class="subject" width="80">환불예정액</th>
			<th class="subject" width="80">환불액</th>
			<th class="subject" >메모</th>
		</tr>

		<?
		$sum=0;
		while($rs= $dbo->next_record()){

			$sql2 = "select * from cmp_people where code='$rs[code]' and bit=1 and name<>'' and bit_cancel=1 order by seq asc";
			list($rows2)=$dbo2->query($sql2);
			//checkVar(mysql_error(),$sql2);

			$sql3= "select * from cmp_paper7 where code='$rs[code]' and code2<>'' ";
			$dbo3->query($sql3);
			$PRICE="";
			$PRICE2="";
			$MEMO="";
			while($rs3=$dbo3->next_record()){
				$PRICE[$rs3[code2]]=$rs3[return_price];
				$PRICE2[$rs3[code2]]=$rs3[return_price2];
				$MEMO[$rs3[code2]]=stripslashes($rs3[memo]);
				$PARTNER[$rs3[code2]]=stripslashes($rs3[partner]);
				$RETURN_DATE[$rs3[code2]]=($rs3[return_date]);
			}

			if($rows2){
			$j=0;
			while($rs2=$dbo2->next_record()){
				$code2 = $rs[code] . "-" . $rs2[id_no];
				$sum += $rs2[price_air];
		?>
		<tr style="background-color:#fff">
			<?if(!$j){?><td rowspan="<?=$rows2?>"><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',950,650,1,1,'','reservation')"><?=$rs[name]?></a></td><?}?>
			<td><?=$rs2[name]?></td>
			
			<!-- 담당자 -->
			<td><?=str_replace(strstr($rs[main_staff],"("),"",$rs[main_staff])?></td>
			
			<!-- 거래처 -->
			<td>
				<input type="text" name="partner_<?=$code2?>" id="partner_<?=$code2?>" value="<?=$PARTNER[$code2]?>" size="17" maxlength="30" class="box" onchange="rprice_save('<?=$code2?>')"/>
			</td>
			
			<!-- 취소일자 -->
			<td><?=$rs2[bit_cancel_date]?></td>

			<!-- 환불일자 -->
			<td>
				<input type="text" name="return_date_<?=$code2?>" id="return_date_<?=$code2?>" value="<?=$RETURN_DATE[$code2]?>" size="11" maxlength="10" class="box dateinput" onchange="rprice_save('<?=$code2?>')"/>
			</td>

			<td class="r"><?=nf($rs2[price_air])?></td>
			<td class="r" style="padding-left:5px">
				<input type="text" name="return2_<?=$code2?>" id="return2_<?=$code2?>" value="<?=nf($PRICE2[$code2])?>" size="12" maxlength="30" class="box numberic w100" onchange="rprice_save('<?=$code2?>')"/>
			</td>

			<td class="r" style="padding-left:5px">
				<input type="text" name="return_<?=$code2?>" id="return_<?=$code2?>" value="<?=nf($PRICE[$code2])?>" size="12" maxlength="30" class="box numberic w100" onchange="rprice_save('<?=$code2?>')" style="color:red"/>
			</td>
			<td style="padding:0 5px 0 5px">
				<input type="text" name="memo_<?=$code2?>" id="memo_<?=$code2?>" value="<?=$MEMO[$code2]?>" size="12" maxlength="30" class="box w100" onchange="rprice_save('<?=$code2?>')"/>
			</td>
		</tr>
		<?
				$j++;
			}
			}else{
				$code2 = $rs[code] . "-" . $rs[id_no];
				$sum += $rs[price_air];
		?>
		<tr style="background-color:#fff">
			<td><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',950,650,1,1,'','reservation')"><?=$rs[name]?></a></td>
			<td><?=$rs[name]?></td>

			<!-- 담당자 -->
			<td><?=str_replace(strstr($rs[main_staff],"("),"",$rs[main_staff])?></td>
			
			<!-- 거래처 -->
			<td><input type="text" name="partner_<?=$code2?>" id="partner_<?=$code2?>" value="<?=$PARTNER[$code2]?>" size="17" maxlength="30" class="box" onchange="rprice_save('<?=$code2?>')"/></td>

			<!-- 취소일자 -->
			<td><?=$rs2[bit_cancel_date]?></td>
			
			<!-- 환불일자 -->
			<td>
				<input type="text" name="return_date_<?=$code2?>" id="return_date_<?=$code2?>" value="<?=$RETURN_DATE[$code2]?>" size="11" maxlength="10" class="box dateinput" onchange="rprice_save('<?=$code2?>')"/>
			</td>

			<td class="r"><?=nf($rs[price_air])?></td>
			<td class="r" style="padding-left:5px">
				<input type="text" name="return2_<?=$code2?>" id="return2_<?=$code2?>" value="<?=nf($PRICE2[$code2])?>" size="12" maxlength="30" class="box numberic w100" onchange="rprice_save('<?=$code2?>')"/>
			</td>

			<td class="r" style="padding-left:5px">
				<input type="text" name="return_<?=$code2?>" id="return_<?=$code2?>" value="<?=nf($PRICE[$code2])?>" size="12" maxlength="30" class="box numberic w100"  style="color:red"  zonchange="rprice_save('<?=$code2?>')"/>
			</td>
			<td style="padding:0 5px 0 5px">
				<input type="text" name="memo_<?=$code2?>" id="memo_<?=$code2?>" value="<?=$MEMO[$code2]?>" size="12" maxlength="30" class="box w100" onchange="rprice_save('<?=$code2?>')"/>
			</td>
		</tr>
		<?
			}
		}
		?>


		<tr style="background-color:#ffe6cc">
			<td colspan="6">합계</td>
			<td class="r"><?=nf($sum)?></td>
			<td class="r"><span id="sum3" style="padding-right:5px">0</span></td>
			<td class="r"><span id="sum2" style="padding-right:5px;color:red">0</span></td>
			<td></td>
		</tr>

	</table>



	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
