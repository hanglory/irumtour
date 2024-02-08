<?
include_once("script/include_common_file.php");
include_once("include/fun_order.php");
header("Content-Type: text/plain");
header("Content-Type: text/html; charset=UTF-8");

//가격계산
$PRICE = get_sum($oid);
?>
<link rel="stylesheet" type="text/css" href="/new/css/style.css"/></link>
<script type="text/javascript">
<!--
function save_people(i){

    var url ="";
    url += "&sex="+$("#sex_"+i).val();
    url += "&name="+encodeURIComponent($("#name_"+i).val());
    url += "&name_eng="+$("#name_eng_"+i).val();
    url += "&name_eng2="+$("#name_eng2_"+i).val();
    url += "&phone1="+$("#phone1_"+i).val();
    url += "&phone2="+$("#phone2_"+i).val();
    url += "&phone3="+$("#phone3_"+i).val();
    url += "&cell1="+$("#cell1_"+i).val();
    url += "&cell2="+$("#cell2_"+i).val();
    url += "&cell3="+$("#cell3_"+i).val();
    url += "&passport="+$("#passport_"+i).val();
    url += "&passport_no="+$("#passport_no_"+i).val();
    url += "&visa="+$("#visa_"+i).val();
    url += "&passport_limit="+$("#passport_limit_"+i).val();
    url += "&adult="+$("#adult").val();
    url += "&seq="+i;

	actarea.location.href="/new/script/set_people.php?mode=save&sp=<?=$sp?>&oid=<?=$oid?>"+url;
}

<?if($action){?>
$(function(){
    var url = "";
    url += "&sex=<?=$sex?>";
    url += "&name=" + encodeURIComponent('<?=$name?>');
    url += "&name_eng=<?=$name_eng?>";
    url += "&name_eng2=<?=$name_eng2?>";
    url += "&phone1=<?=$phone1?>";
    url += "&phone2=<?=$phone2?>";
    url += "&phone3=<?=$phone3?>";
    url += "&cell1=<?=$cell1?>";
    url += "&cell2=<?=$cell2?>";
    url += "&cell3=<?=$cell3?>";
    url += "&passport=<?=$passport?>";
    url += "&passport_no=<?=$passport_no?>";
    url += "&visa=<?=$visa?>";
    url += "&passport_limit=<?=$passport_limit?>";
    url += "&adult="+$("#adult").val();
    url += "&seq=1";

	actarea.location.href="/new/script/set_people.php?mode=save&action=<?=$action?>&oid=<?=$oid?>"+url;
});
<?}?>


$("#qty").text('<?=number_format($adult)?>');
$(".ctg_price").text('<?=number_format($PRICE)?>');
//-->
</script>

  <?
  $sql2 = "select * from ez_order_people where oid=$oid order by seq";
  $dbo2->query($sql2);

  For($i=1; $i <= $seat;$i++){
	$rs2=$dbo2->next_record();
  ?>

		<table width="960" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td align="center" style="padding-top:15px;">

			<table width="935" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="4" bgcolor="#495164" height="2"></td>
                </tr>
              <tr>
                <td width="170" class="sj_tit"><img src="/new/images/members/ict04.gif"></td>
                <td width="305" class="sj_sub">
						<input type="text" name="name" value="<?=$rs2[name]?>" size="20" maxlength="30" class="input kor" id="name_<?=$i?>" onchange="save_people(<?=$i?>)"/>
						<select name="sex" id="sex_<?=$i?>" class="select" onchange="save_people(<?=$i?>)">
							<option value="">성별</option>
							<?=option_str("남,여","남,여",$rs2[sex])?>
						</select>
				</td>
                <td width="169"  class="sj_tit03" align="left"><img src="/new/images/members/ict05.gif"></td>
                <td width="291" class="sj_sub">성: <input type="text" name="name_eng" value="<?=$rs2[name_eng]?>" size="15" maxlength="30" class="input eng_only" id="name_eng_<?=$i?>" onchange="save_people(<?=$i?>)"/>
                   이름 : <input type="text" name="name_eng2" value="<?=$rs2[name_eng2]?>" size="15" maxlength="30" class="input eng_only" id="name_eng2_<?=$i?>" onchange="save_people(<?=$i?>)"/></td>
              </tr>
              <tr>
                <td class="sj_tit"><img src="/new/images/members/ict06.gif"></td>
                <td class="sj_sub">
					<input type="text" name="phone1" value="<?=$rs2[phone1]?>" size="7" maxlength="4" class="input eng_only" id="phone1_<?=$i?>" onchange="save_people(<?=$i?>)"/>
					-
					<input type="text" name="phone2" value="<?=$rs2[phone2]?>" size="7" maxlength="4" class="input eng_only" id="phone2_<?=$i?>" onchange="save_people(<?=$i?>)"/>
					-
					<input type="text" name="phone3" value="<?=$rs2[phone3]?>" size="7" maxlength="4" class="input eng_only" id="phone3_<?=$i?>" onchange="save_people(<?=$i?>)"/>

				</td>
                <td class="sj_tit03"><img src="/new/images/members/ict07.gif"></td>
                <td class="sj_sub">
					<input type="text" name="cell1" value="<?=$rs2[cell1]?>" size="7" maxlength="4" class="input eng_only" id="cell1_<?=$i?>" onchange="save_people(<?=$i?>)"/>
					-
					<input type="text" name="cell2" value="<?=$rs2[cell2]?>" size="7" maxlength="4" class="input eng_only" id="cell2_<?=$i?>" onchange="save_people(<?=$i?>)"/>
					-
					<input type="text" name="cell3" value="<?=$rs2[cell3]?>" size="7" maxlength="4" class="input eng_only" id="cell3_<?=$i?>" onchange="save_people(<?=$i?>)"/>
				</td>
              </tr>
               <tr>
                 <td class="sj_tit"><img src="/new/images/booking/ict01_st04.gif"></td>
                 <td class="sj_sub">
						<select name="passport" id="passport_<?=$i?>" class="select" onchange="save_people(<?=$i?>)">
							<option value="">선택하세요</option>
							<?=option_str("여권있음,여권없음","여권있음,여권없음",$rs2[passport])?>
						</select>
				 </td>
                 <td class="sj_tit03"><img src="/new/images/booking/ict01_st05.gif"></td>
                 <td class="sj_sub"><input type="text" name="passport_no" value="<?=$rs2[passport_no]?>" size="30" maxlength="30" class="input" id="passport_no_<?=$i?>" onchange="save_people(<?=$i?>)"/></td>
               </tr>
               <tr>
                <td class="sj_tit"><img src="/new/images/booking/ict01_st07.gif"></td>
                <td class="sj_sub">
						<select name="visa" id="visa_<?=$i?>" class="select" onchange="save_people(<?=$i?>)">
							<option value="">선택하세요</option>
							<?=option_str("비자있음,비자없음","비자있음,비자없음",$rs2[visa])?>
						</select>
				</td>
                <td class="sj_tit03"><img src="/new/images/booking/ict01_st06.gif"></td>
                <td class="sj_sub"><input type="text" name="passport_limit" value="<?=$rs2[passport_limit]?>" size="10" maxlength="10" class="input" id="passport_limit_<?=$i?>" onchange="save_people(<?=$i?>)" class="eng_only"/> (예 : 2020/01/01)</td>
               </tr>

            </table></td>
          </tr>
		</table>

  <?
  }
  ?>