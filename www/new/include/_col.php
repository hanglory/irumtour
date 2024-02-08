<?
include_once("script/include_common_file.php");
$tour_ym = "${y}/" . num2($m);
$sql = "select * from ez_tour_calendar where tid=$tid and left(tour_date,7)='$tour_ym' ";
$dbo->query($sql);
?>
<table width="927" border="0" cellspacing="0" cellpadding="0" >
  <tr>
<?
While($dbo->next_record()){
	$arr = explode("/",$rs[tour_date]);
	$date  =mktime(0,0,0,$arr[1],$arr[2],$arr[0]);
	$week = Date("w",$date);
?>
	<td width="30" class="cal_month"><?=convertWeek($week)?></td>
<?
}
?>

    <!-- <td width="30" class="cal_month"><span class="cal_sat">토</span></td>
    <td width="30" class="cal_month"><span class="cal_sun">일</span></td>
 -->
  </tr>
  <tr>
    <td class="cal_date02"><span class="cal_blue">1</span></td>
    <td class="cal_date"><span class="cal_blue">2</span></td>
    <td class="cal_date"><span class="cal_blue">3</span></td>
    <td class="cal_date"><span class="cal_blue">4</span></td>
    <td class="cal_date"><span class="cal_blue">5</span></td>
    <td class="cal_date"><span class="cal_red">6</span></td>
    <td class="cal_date"><span class="cal_red">7</span></td>
    <td class="cal_date"><span class="cal_red">8</span></td>
    <td class="cal_date"><span class="cal_red">9</span></td>
    <td class="cal_date"><span class="cal_red">10</span></td>
    <td class="cal_date"><span class="cal_gray">11</span></td>
    <td class="cal_date"><span class="cal_gray">12</span></td>
    <td class="cal_date"><span class="cal_gray">13</span></td>
    <td class="cal_date"><span class="cal_gray">14</span></td>
    <td class="cal_date"><span class="cal_gray">15</span></td>
    <td class="cal_date">16</td>
    <td class="cal_date">17</td>
    <td class="cal_date">18</td>
    <td class="cal_date">19</td>
    <td class="cal_date">20</td>
    <td class="cal_date">21</td>
    <td class="cal_date">22</td>
    <td class="cal_date">23</td>
    <td class="cal_date">24</td>
    <td class="cal_date">25</td>
    <td class="cal_date">26</td>
    <td class="cal_date">27</td>
    <td class="cal_date">28</td>
    <td class="cal_date">29</td>
    <td class="cal_date">30</td>
    <td class="cal_date">31</td>
  </tr>
</table>