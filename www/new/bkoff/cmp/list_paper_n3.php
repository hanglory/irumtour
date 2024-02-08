<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"경영관리");


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper3";
$year = ($year)?$year:date("Y");
//$ASSORT="연차,출장원,공항샌딩,주간회의,월간회의,분기회의,상품교육";
$ASSORT=$ASSORT_HRM;
$ASSORT = str_replace(",생일","",$ASSORT);
$ASSORT = str_replace(",반차","",$ASSORT);
$assort=($assort)?$assort:"연차";
$TITLE = "출장 및 휴가 현황 ($assort)";
if(strstr("@14.37.242.84@221.154.216.133@","@".$_SERVER["REMOTE_ADDR"]."@")) $debug=1;



for($i=1;$i<=12;$i++){
	$totaldays = date(t, mktime(0, 0, 0, $i, 1, $year));
	//checkVar($i,$totaldays);

    $filter = ($assort=="연차")? "and assort in ('연차','반차')":"and assort='$assort'";

	for($j=1;$j<=$totaldays;$j++){
		$date = $year . "/" .num2($i)."/".num2($j);
		$sql = "
			select
                assort,
			    name
			from cmp_hrm
			where date_out<='$date' and date_return>='$date'
            and cp_id='$CP_ID'
            $filter
			group by name
		";
		$dbo->query($sql);
        if($debug && $i==1 && $j==1) checkVar(mysql_error(),$sql);
		while($rs=$dbo->next_record()){
			$arr=explode("(",$rs[name]);
			$arr[1] = substr($arr[1],0,-1);
			$DATA[$arr[1]][$i]+=($rs[assort]=="반차")? 0.5 : 1;
			//checkVar($DATA[$rs[name]][$i].mysql_error(),$sql);
			//if($debug) checkVar($arr[1],$rs[assort]);
		}
	}

}

?>
<?include("../top.html");?>
<style type="text/css">
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:right;padding-right:2px;}
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


	<tr height=22>
	<td valign='bottom' align=right>

	<select name="assort">
		<?=option_str($ASSORT,$ASSORT,$assort)?>
	</select>

	기준년도 : <input type="text" name="year" id="year" size="13" maxlength="10" value="<?=$year?>" class="box c">

	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>



    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align=center height=25 bgcolor="#F7F7F6">
	   		<th class="subject" >이름</th>
		<?
		for($i=1;$i<=12;$i++){
		?>
		<th class="subject" ><?=$i?>월</th>
		<?}?>
		<th class="subject" >합계</th>
		</tr>

		<?
        if(strstr("partner_i",$_SESSION["sessLogin"]["staff_type"])) $filter=" and id='$user_id' ";
        elseif(strstr("partner_a,partner_g",$_SESSION["sessLogin"]["staff_type"])) $filter=" and cp_id='$CP_ID' ";
        else $filter = " and staff_type not in ('partner_a','partner_g')";

		$sql = "
            select
                *
            from cmp_staff
            where
                id<>''
				and bit_hide<>1
                and staff_type='staff'
                $filter
            order by name asc
        ";
		$dbo->query($sql);
        //if($debug) checkVar(mysql_error(),$sql);
		while($rs=$dbo->next_record()){
		?>
			<tr align='center'>
				<th class="subject" ><?=$rs[name]?></th>
			<?
			$sum2=0;
			for($i=1;$i<=12;$i++){
				$did=$rs[id];
				//checkVar($did,$i);
				$sum[$i]+=$DATA[$did][$i];
				$sum2+=$DATA[$did][$i];
			?>
			  <td class="c"><?=($DATA[$did][$i])?></td>
			<?}?>
			  <td class="subject c"><?=($sum2)?></td>
		    </tr>
	    <?}?>

		<tr align='center'>
			<th class="subject" >합계</th>
		<?
		$sum2=0;
		for($i=1;$i<=12;$i++){
			$sum2+=$sum[$i];
		?>
		  <th class="subject"><?=($sum[$i])?></th>
		<?}?>
		  <th class="subject"><?=($sum2)?></th>
	    </tr>


	</table>


	</table>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
