<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_air";
$MENU = "cmp_basic";
$TITLE = "항공사별 타임 스케쥴";

#### staff
$sql = "select * from cmp_staff order by name asc";
$dbo->query($sql);
while($rs=$dbo->next_record()){
	$STAFF.=",$rs[name] ($rs[id])";
}


#### operation
if ($mode=="save"){

	$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');
	$d_air = trim($d_air);
	$sql = "select * from cmp_cargo where air='$d_air' order by id_no desc limit 1";
	$dbo->query($sql);
	$rs=$dbo->next_record();

	$airport_counter = $rs[cargo_counter];


	$sqlInsert="
	   insert into cmp_air (
	      nation,
	      city,
	      d_air,
	      d_air_no,
	      d_time_s,
	      d_time_e,
	      d_wday,
	      r_air_no,
	      r_time_s,
	      r_time_e,
	      r_wday,
	      airport_in,
	      airport_out,
	      airport_counter,
	      airport_place,
	      staff
	  ) values (
	      '$nation',
	      '$city',
	      '$d_air',
	      '$d_air_no',
	      '$d_time_s',
	      '$d_time_e',
	      '$d_wday',
	      '$r_air_no',
	      '$r_time_s',
	      '$r_time_e',
	      '$r_wday',
	      '$airport_in',
	      '$airport_out',
	      '$airport_counter',
	      '$airport_place',
	      '$staff'
	)";


	$sqlModify="
	   update cmp_air set
	      nation = '$nation',
	      city = '$city',
	      d_air = '$d_air',
	      d_air_no = '$d_air_no',
	      d_time_s = '$d_time_s',
	      d_time_e = '$d_time_e',
	      d_wday = '$d_wday',
	      r_air_no = '$r_air_no',
	      r_time_s = '$r_time_s',
	      r_time_e = '$r_time_e',
	      r_wday = '$r_wday',
		  airport_in='$airport_in',
	      airport_out='$airport_out',
	      airport_counter='$airport_counter',
	      airport_place='$airport_place',
	      staff = '$staff'
	   where id_no=$id_no
	";

	if($id_no){
		$sql =$sqlModify;
		$url = "view_${filecode}.php?id_no=$id_no&ctg1=$ctg1";
	}else{
		$sql =$sqlInsert;
		$url = "list_${filecode}.php?ctg1=$ctg1";
	}

	//checkVar(mysql_error(),$sql);exit;

	if($dbo->query($sql)){
		If($id_no) msggo("저장하였습니다.",$url);
		Else echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();self.close()</script>";

	}else{
		checkVar(mysql_error(),$sql);
	}
	exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){

		$sql = "delete from $table where id_no = $check[$i]";
		$dbo->query($sql);
	}
	//redirect2("list_${filecode}.php?ctg1=$ctg1");exit;
	back();exit;


}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
}
?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;

	if(check_blank(fm.d_air,'항공사명을',0)=='wrong'){return }
	fm.submit();

}

jQuery(function($){

	$(".num").keypress(function(event){
		if(event.which && (event.which < 48 || event.which > 57)){
			event.preventDefault();
		}
	});

	$("#d_time_s").mask("99:99");
	$("#d_time_e").mask("99:99");
	$("#r_time_s").mask("99:99");
	$("#r_time_e").mask("99:99");

	$("#airport_counter").css("border","0");
});
</script>

<div style="padding:0 10px 0 10px">

	<table width="97%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con" style="padding-left:10px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
	  </tr>
	  <tr>
		<td> </td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	</table>

	<!--내용이 들어가는 곳 시작-->

	<br>

    <table border="0" cellspacing="1" cellpadding="3" width="97%">
		<form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="code" value='<?=$code?>'>
		<input type="hidden" name="golf_name" id="golf_name" value='<?=$rs[golf_name]?>'>
		<input type="hidden" name="golf_id_no" id="golf_id_no" value='<?=$rs[golf_id_no]?>'>
		<input type="hidden" name="air_id_no" id="air_id_no" value='<?=$rs[air_id_no]?>'>
		<input type="hidden" name="d_air_no" id="d_air_no" value='<?=$rs[d_air_no]?>'>
		<input type="hidden" name="r_air_no" id="r_air_no" value='<?=$rs[r_air_no]?>'>

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td class="subject">국가</td>
          <td>
	           <select name="nation">
	           <?=option_str($NATIONS,$NATIONS,$rs[nation])?>
	           </select>
          </td>

          <td class="subject">도시</td>
          <td>
	           <?=html_input('city',30,28)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">항공사명</td>
          <td colsan="3">
	           <select name="d_air">
			   <option value="">선택</option>
			   <?=option_str($AIRLINES,$AIRLINES,$rs[d_air])?>
			   </select>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">국내공항</td>
          <td>
	           <?=html_input('airport_in',30,40)?>

	          <?if($rs[airport_counter]){?>
	           /
			  <b>체크인 카운터</b> : <?=html_input('airport_counter',20,30,'box readonly')?>
			  <?}?>
          </td>

          <td class="subject">현지공항</td>
          <td>
	           <?=html_input('airport_out',30,40)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject" style="background-color:#FFF">[ 출국 ] </td>
          <td colsan="3">
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject">항공편</td>
          <td>
	           <?=html_input('d_air_no',30,28)?>

			   &nbsp;&nbsp;&nbsp;&nbsp;
			   <b>도시</b>
			   <select name="airport_place">
				<option value="">선택</option>
				<?=option_str($AIRPORTS_OUT,$AIRPORTS_OUT,$rs[airport_place])?>
			   </select>

          </td>

          <td class="subject">출발요일</td>
          <td>
	           <?=html_input('d_wday',30,28)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">출발시간</td>
          <td>
	           <?=html_input('d_time_s',6,4)?>
          </td>

          <td class="subject">도착시간</td>
          <td>
	            <?=html_input('d_time_e',6,4)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" style="background-color:#FFF">[ 귀국 ] </td>
          <td colsan="3">
          </td>
        </tr>

        <tr>
          <td class="subject">항공편</td>
          <td>
	           <?=html_input('r_air_no',30,28)?>
          </td>

          <td class="subject">출발요일</td>
          <td>
	           <?=html_input('r_wday',30,28)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">출발시간</td>
          <td>
	           <?=html_input('r_time_s',6,4)?>
          </td>

          <td class="subject">도착시간</td>
          <td>
	            <?=html_input('r_time_e',6,4)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>



        <tr><td colspan="12" bgcolor='#E1E1E1' height=1></td></tr>

        <tr>
		  <td colspan=10>
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
					<td><span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span></td>
				</tr>
			  </table>
			  <!-- Button End------------------------------------------------>
		  </td>
        </tr>

        <tr>
	  <td colspan=10 height=20>
	  </td>
        </tr>
	</form>
	</table>

</div>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>