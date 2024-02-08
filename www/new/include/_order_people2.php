<?
include_once("script/include_common_file.php");
include_once("include/fun_order.php");
header("Content-Type: text/plain");
header("Content-Type: text/html; charset=UTF-8");

$sql2 = "select * from ez_order_people where oid=$oid order by seq";
$dbo2->query($sql2);

while($rs2=$dbo2->next_record()){
?>


		<table width="960" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="center" style="padding-top:15px;">

			<table width="935" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="4" bgcolor="#495164" height="2"></td>
                </tr>
              <tr>
                <td width="170" class="sj_tit"><img src="images/members/ict04.gif"></td>
                <td width="305" class="sj_sub">
						<?=$rs2[name]?>
						<?=($rs[sex])?$rs[sex]:""?>&nbsp;
				</td>
                <td width="169"  class="sj_tit03" align="left"><img src="images/members/ict05.gif"></td>
                <td width="291" class="sj_sub"><?=$rs2[name_eng]?> <?=$rs2[name_eng2]?>&nbsp;</td>
              </tr>
              <tr>
                <td class="sj_tit"><img src="images/members/ict06.gif"></td>
                <td class="sj_sub">
					<?=$rs2[phone1]?> - <?=$rs2[phone2]?> -<?=$rs2[phone3]?>&nbsp;
				</td>
                <td class="sj_tit03"><img src="images/members/ict07.gif"></td>
                <td class="sj_sub">
					<?=$rs2[cell1]?> - <?=$rs2[cell2]?> -<?=$rs2[cell3]?>&nbsp;
				</td>
              </tr>
               <tr>
                 <td class="sj_tit"><img src="images/booking/ict01_st04.gif"></td>
                 <td class="sj_sub">
						<?=$rs2[passport]?>&nbsp;
				 </td>
                 <td class="sj_tit03"><img src="images/booking/ict01_st05.gif"></td>
                 <td class="sj_sub"><?=$rs2[passport_no]?>&nbsp;</td>
               </tr>
               <tr>
                <td class="sj_tit"><img src="images/booking/ict01_st07.gif"></td>
                <td class="sj_sub">
						<?=$rs2[visa]?>&nbsp;
				</td>
                <td class="sj_tit03"><img src="images/booking/ict01_st06.gif"></td>
                <td class="sj_sub"><?=$rs2[passport_limit]?>&nbsp;</td>
               </tr>

            </table></td>
          </tr>
		</table>

  <?
  }
  ?>