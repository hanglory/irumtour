<?
include_once("../include/common_file.php");

 chk_power($_SESSION["sessLogin"]["proof"],"인사관리");

####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_paper3";
$LEFT_HIDDEN = "0";
$TITLE = "출장휴가달력";


$year=($year)? $year : date("Y");
$month=($month)? $month:date("m");

$sql = "select * from cmp_staff order by name asc";
$dbo->query($sql);
while($rs=$dbo->next_record()){
	$staff .="," . $rs[id];
	$COLOR[$rs[id]]=$rs[color];
}
$staff = substr($staff,1);


function get_totaldays($year,$month) {

	$maxdate = date(t, mktime(0, 0, 0, $month, 1, $year));
	return $maxdate;
/*
      $date = 1;
      while(checkdate($month,$date,$year)) {
	  $date++;
      }

      $date--;
      return $date;
      */
}

	//오늘 날짜 구하기
	if(!$year){
		$timeinfo=getdate(time());
		$year=$timeinfo["year"];
		$month=$timeinfo["mon"];
		$day=$timeinfo["mday"]+2;
	}
	$timeinfo=getdate(time());
	$gyear=$timeinfo["year"];
	$gmonth=$timeinfo["mon"];
	$gday=$timeinfo["mday"]+2;



   // 해당연월의 달력을 출력한다.

   function showCalendar($year,$month,$total_days,$gyear,$gmonth,$gday)
   {
      global $mode;
	  global $rs;
      global $dbo;
      global $dbo2;
	  global $sessLogin;
	  global $assort;
	  global $REMOTE_ADDR;
	  global $staff_id;
      global $FILTER_PARTNER_QUERY;
      global $debug;

	  $month = ceil($month);


	  $date_info = substr($rs[date_info],1);
	  $date_info_array = explode(";",$date_info );

      $first_day = date('w', mktime(0,0,0,$month,1,$year));

	  //네비게이션
	  $prev_year=$year-1;
	  $next_year=$year+1;
	  $prev_month=$month-1;
	  $next_month=$month+1;

      echo("<table width='100%' border=0 cellpadding=3 cellspacing=1 bgcolor='#D9D9D9'>\n");
      echo("<tr bgcolor=\"#FFFFFF\" bordercolor=\"#FFFFFF\" height=30>\n");
      echo("   <td align=center><font color=red><b>일요일</b></font></td>\n");
      echo("   <td align=center><b>월요일</b></td>\n");
      echo("   <td align=center><b>화요일</b></td>\n");
      echo("   <td align=center><b>수요일</b></td>\n");
      echo("   <td align=center><b>목요일</b></td>\n");
      echo("   <td align=center><b>금요일</b></td>\n");
      echo("   <td align=center><font color=blue><b>토요일</b></a></td>\n");
      echo("</TR>\n");
      echo("<tr bgcolor=\"#E5E5E5\" bordercolor=\"#FFFFFF\" height=30>\n");


      $col = 0;
      for($i = 0; $i < $first_day; $i++) {
         echo("   <TD BGCOLOR=\"#F7F7F7\">&nbsp;</TD>\n");
         $col++;
      }

      if($staff_id){$filter = " and name like '%$staff_id%'";}

      for($j = 1; $j <= $total_days; $j++) {
		$ymd_m =(strlen($month)<2)? "0" . $month : $month;
		$ymd_d =(strlen($j)<2)? "0" . $j : $j;
		$ymd = "${year}/${ymd_m}/${ymd_d}";

		$sdate = mktime(0,0,0,$month,$j,$year);

		$sql = "
			select
				*
				from
				cmp_hrm
				where date_out<='$ymd' and date_return>='$ymd'
				$filter
                $FILTER_PARTNER_QUERY;
				";

		$dbo->query($sql);
		if($debug && $j==1) checkVar(mysql_error(),$sql);

		$contents="";
		while($rs = $dbo->next_record()){
			$golf_arr = explode(">",$rs[golf_name]);
			$golf = $golf_arr[count($golf_arr)-1];
			$night = ((strtotime($rs[r_date]) -  strtotime($rs[d_date]))/86400)+1;//박수

			$arr = explode("(",$rs[name]);
			$id = substr($arr[1],0,-1);
			$color_s = staff_color($id);
			$proof = ($rs[proof])? "" : "-미승인";

			$contents .= "<div><a href=\"javascript:newWin('view_hrm.php?id_no=$rs[id_no]',870,400,1,1,'','reservation')\" class=soft  style='color:$color_s'><span class='golf'> [$rs[assort]] $rs[name] $proof $rs[place]</span></a></div>";
		}
		$dateValue = "${year}${month}${j}";
		$dateValue2 = ceil(date(Y)).ceil(date(m)).ceil(date(d));
		$color =($dateValue==$dateValue2)? "#ECECFF" : "FFFFFF";
		echo("   <TD BGCOLOR=\"$color\" align=left valign=top width=14% height=80 class='black' style='cursor:hand;padding-left:10px'><span style='color:black;font-weight:normal'>${j}</span><br> ${contents} <br/></TD>\n");


         $col++;

         if($col == 7) {
            echo("</TR>\n");
	    if($j != $total_days) {
	       echo("<tr bgcolor=\"#E5E5E5\" bordercolor=\"#FFFFFF\">\n");
	    }
	    $col = 0;
         }
      }

      while($col > 0 && $col < 7) {
         echo("   <TD BGCOLOR=\"#F7F7F7\">&nbsp;</TD>\n");
	 $col++;
      }

      echo("</TR>\n");
      echo("</TABLE>\n");

   }//달력 함수 끝

?>
<?include("../top.html");?>
<style type="text/css">
.golf{
	display:inline-block;
	width:95%;
	height:18px;
	overflow:hidden;
	cursor:pointer;
}
</style>
<script type="text/javascript">
<!--
function set_staff(id){
	var url = "<?=SELF?>?year=<?=$year?>&month=<?=$month?>&assort=<?=$assort?>";
	url += "&staff_id=" + id;

	location.href=url;
}
//-->
</script>

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
	  </tr>
	  <tr>
		<td> </td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	</table>


		<!--내용이 들어가는 곳 시작-->

		<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
            <tr>
              <td valign="top">


<?
$prevUnix = mktime(0,0,0,$month -1,1,$year);
$nextUnix = mktime(0,0,0,$month +1,1,$year);
?>

			  <table width="100%" align=center border=0 cellpadding="0" cellspacing="0">
                  <tr>
                    <td colspan=3 align=center><img src="../images/diary/month<?=ceil($month)?>.gif" width="145" height="70">
                    </td>
                  </tr>
                  <tr>
                    <td width="20%">&nbsp;</td>
                    <td width="60%" height="30" align="center"> <a href="?year=<?=date("Y",$prevUnix)?>&month=<?=date("m",$prevUnix)?>&assort=<?=$assort?>" target="_self"  class=dark><img src="../images/diary/board_arrow02.gif" width="11" height="11" border="0" align="absmiddle">
                      <strong><?=date("Y.m",$prevUnix)?></strong></a> <font color="#999999">|</font>
                      <a href="?year=<?=date("Y",$nextUnix)?>&month=<?=date("m",$nextUnix)?>&assort=<?=$assort?>" target="_self" class=dark> <strong><?=date("Y.m",$nextUnix)?> </strong>
                      <img src="../images/diary/board_arrow03.gif" width="11" height="11" border="0" align="absmiddle"></a></td>
                    <td width="20%" align="right" valign="bottom"> </td>
                  </tr>
                </table>


                <?if($_SESSION["sessLogin"]["staff_type"]!="partner"){?>

                    <?
                    if(strstr("partner_i",$_SESSION["sessLogin"]["staff_type"])) $filter=" and id='$user_id' ";
                    elseif(strstr("partner_a,partner_g",$_SESSION["sessLogin"]["staff_type"])) $filter=" and cp_id='$CP_ID' ";
                    else $filter = " and staff_type not in ('partner_a','partner_g')";
                    ?>

    				<div style="text-align:right">
    				<?
    				$sql = "
                        select
                            *
                        from cmp_staff
                        where
                            id<>''
							and bit_hide<>1
                            $filter
                        order by name asc
                    ";
    				$dbo->query($sql);
                    //if($debug) checkVar(mysql_error(),$sql);
    				while($rs=$dbo->next_record()){
    					$STAFF.=",$rs[name] ($rs[id])";
    					$STAFF2.=",$rs[name]";
    				}
    				?>
    	           <select name="$staff_id" onchange="set_staff(this.value)">
    				<?=option_str("전체".$STAFF,$STAFF2,$staff_id)?>
    			   </select>
    			   </div>

               <?}?>


<br>

<?
	$inputY = $year;
	$inputM = $month;

	$totaldays = get_totaldays($inputY,$inputM);
	echo showCalendar($inputY,$inputM,$totaldays,$gyear,$gmonth,$gday);
?>





		<!--내용이 들어가는 곳 종료-->


		</td></tr>

        <tr>
			  <?if(strstr($_SESSION["sessLogin"]["proof"],"엑셀다운로드")){?>
			  <td align="right" colspan=10>
				<!-- <span class="btn_pack medium bold"><a href="list_report2_excel.php?year=<?=$year?>&month=<?=$month?>&staff_id=<?=$staff_id?>"> 엑셀 </a></span> -->
			  </td>
			  <?}?>
        </tr>


	</table>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
