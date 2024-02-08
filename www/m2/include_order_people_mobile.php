<?
include_once("./script/include_common_mobile.php");
include_once("../html/include/fun_order.php");
@header("Content-Type: text/html; charset=EUC-KR");

?>
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
    url += "&birth="+$("#birth_"+i).val();
    url += "&passport="+$("#passport_"+i).val();
    url += "&passport_no="+$("#passport_no_"+i).val();
    url += "&visa="+$("#visa_"+i).val();
    url += "&passport_limit="+$("#passport_limit_"+i).val();
    url += "&adult="+$("#adult").val();
    url += "&seq="+i;

	actarea.location.href="/html/script/set_people.php?mode=save&sp=<?=$sp?>&tid=<?=$tid?>&oid=<?=$oid?>&seat=<?=$seat?>"+url;
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
    url += "&birth=<?=$birth?>";
    url += "&passport=<?=$passport?>";
    url += "&passport_no=<?=$passport_no?>";
    url += "&visa=<?=$visa?>";
    url += "&passport_limit=<?=$passport_limit?>";
    url += "&adult="+$("#adult").val();
    url += "&seq=1";

	actarea.location.href="/html/script/set_people.php?mode=save&action=<?=$action?>&tid=<?=$tid?>&oid=<?=$oid?>&seat=<?=$seat?>"+url;
});
<?}?>


$("#qty").text('<?=number_format($adult)?>');
$(".ctg_price").text('<?=number_format($PRICE)?>');

//-->
</script>
<style type="text/css">
/*
.tbl_form{border-top:1px solid #ccc;border-right:1px solid #ccc;}
.tbl_form th,.tbl_form td{border-left:1px solid #ccc;border-bottom:1px solid #ccc;}
*/
.tbl_form{border:1px solid #ccc;}
.tbl_form th,.tbl_form td{text-align:center;padding:5px}
</style>

  <?
  $sql2 = "select * from ez_order where oid=$oid";
  $dbo2->query($sql2);
  $rs2 = $dbo2->next_record();
  $bit_edit = (!$rs2[bit] || $rs2[status]=="접수중" || $sessLogin[id])?1:0;

  $sql2 = "select * from ez_order_people where oid=$oid order by seq";
  $dbo2->query($sql2);
  ?>

		<table class="tbl_form" width="100%" cellpadding="0" cellspacing="0" summary="실제 추가여행자 정보를 입력하세요">
				<caption>추가여행자정보</caption>
				<thead>
					<tr>
						<th>이름</th>
						<th>연락처</th>
						<th>생년월일</th>
					</tr>
				</thead>

				<tbody>
<?
if($bit_edit){
  For($i=1; $i <= $seat;$i++){
	$rs2=$dbo2->next_record();
?>
                <tr>
                  <td><input type="text" name="name_<?=$i?>" value="<?=$rs2[name]?>" size="8" maxlength="30" class="input kor" id="name_<?=$i?>" onchange="save_people(<?=$i?>)"/></td>

                  <td>
					<input type="text" name="phone1_<?=$i?>" value="<?=$rs2[phone1]?>" size="5" maxlength="4" class="input eng_only" id="phone1_<?=$i?>" onchange="save_people(<?=$i?>)"/>

					<input type="text" name="phone2_<?=$i?>" value="<?=$rs2[phone2]?>" size="5" maxlength="4" class="input eng_only" id="phone2_<?=$i?>" onchange="save_people(<?=$i?>)"/>

					<input type="text" name="phone3_<?=$i?>" value="<?=$rs2[phone3]?>" size="5" maxlength="4" class="input eng_only" id="phone3_<?=$i?>" onchange="save_people(<?=$i?>)"/></td>
                  <td><input type="text" name="birth_<?=$i?>" value="<?=$rs2[birth]?>" size="8" maxlength="10" class="input kor" id="birth_<?=$i?>" onchange="save_people(<?=$i?>)"/></td>

                </tr>
 <?
  }
}else{
   while($rs2=$dbo2->next_record()){
 ?>
                <tr>
                  <th scope="row">여행자 성명</th>
                  <td><?=$rs2[name]?></td>
                  <th class="line">연락처정보</th>
                  <td><?=$rs2[phone1]?>-<?=$rs2[phone2]?>-<?=$rs2[phone3]?></td>
                  <th scope="row">생년월일</th>
                  <td><?=$rs2[birth]?></td>
                </tr>
 <?
	}
 }
 ?>
			</tbody>
        </table>

