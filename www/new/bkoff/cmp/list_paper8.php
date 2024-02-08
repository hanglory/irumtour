<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");

$filename= "../../public/inc/cmp_paper7.inc";

/*
$sql = "
CREATE TABLE cmp_paper7 (
  id_no int(10) unsigned NOT NULL AUTO_INCREMENT,
  code varchar(50),
  code2 varchar(50),
  return_price int not null default 0,
  UNIQUE KEY (code2),
  PRIMARY KEY (id_no)
)
";
$dbo->query($sql);
checkVar(mysql_error(),$sql);
*/

if($mode=="save"){
	$return_price = rnf($price);
	$arr = explode("-",$code2);
	$code= $arr[0];

	$sql = "delete from cmp_paper7 where code2='$code2' ";
	$dbo->query($sql);

	$sql="
		insert into cmp_paper7 (
		   code,
		   code2,
		   return_price
	   ) values (
		   '$code',
		   '$code2',
		   '$return_price'
	 )";
	$dbo->query($sql);
	checkVar(mysql_error(),$sql);

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
$TITLE = "비즈니스석 고객";

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

function rprice_save(code2,price){
	var url = "<?=SELF?>?mode=save";
	url +="&code2="+code2;
	url +="&price="+price;
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

	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>

	<br/>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >대표자명</th>
		<th class="subject" >핸드폰</th>
		<th class="subject" >경로</th>
		<th class="subject" >담당자</th>
		<th class="subject" >출국일</th>
		<th class="subject" >총인원</th>
		<th class="subject" >상품명</th>
		</tr>
<?
$sql_2= "
	select
		*
		from cmp_reservation
		where
		bit_bizseat=1
		and (${dtype} >= '$date_s' and ${dtype} <='$date_e')
		order by tour_date desc,id_no desc
";

$dbo->query($sql_2);
//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	$arr = explode("(",$rs[main_staff]);
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no]?>',950,650,1,1,'','reservation')"><?=$rs[name]?></a></td>
	      <td><?=$rs[phone]?></td>
	      <td><?=$rs[view_path]?></td>
	      <td><?=$arr[0]?></td>

	      <td><?=$rs[d_date]?></td>
	      <td><?=nf($rs[people])?></td>
	      <td class="l pl10"><?=$rs[golf_name]?></td>
	    </tr>
<?
}
?>
	</table>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
