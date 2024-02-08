<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_temperature";
$MENU = "cmp_basic";
$TITLE = "국가별 평균기온";

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
	$city = trim(str_replace(" ","",$city));

	$rs[month1] =addslashes($rs[month1]);
	$rs[month2] =addslashes($rs[month2]);
	$rs[month3] =addslashes($rs[month3]);
	$rs[month4] =addslashes($rs[month4]);
	$rs[month5] =addslashes($rs[month5]);
	$rs[month6] =addslashes($rs[month6]);
	$rs[month7] =addslashes($rs[month7]);
	$rs[month8] =addslashes($rs[month8]);
	$rs[month9] =addslashes($rs[month9]);
	$rs[month10] =addslashes($rs[month10]);
	$rs[month11] =addslashes($rs[month11]);
	$rs[month12] =addslashes($rs[month12]);

	$sqlInsert="
	   insert into cmp_temperature (
	      nation,
	      city,
	      month1,
	      month2,
	      month3,
	      month4,
	      month5,
	      month6,
	      month7,
	      month8,
	      month9,
	      month10,
	      month11,
	      month12
	  ) values (
	      '$nation',
	      '$city',
	      '$month1',
	      '$month2',
	      '$month3',
	      '$month4',
	      '$month5',
	      '$month6',
	      '$month7',
	      '$month8',
	      '$month9',
	      '$month10',
	      '$month11',
	      '$month12'
	)";


	$sqlModify="
	   update cmp_temperature set
			nation = '$nation',
			city = '$city',
			month1='$month1',
			month2='$month2',
			month3='$month3',
			month4='$month4',
			month5='$month5',
			month6='$month6',
			month7='$month7',
			month8='$month8',
			month9='$month9',
			month10='$month10',
			month11='$month11',
			month12='$month12'
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
	redirect2("list_${filecode}.php?ctg1=$ctg1");exit;


}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
}


	$rs[month1] =stripslashes($rs[month1]);
	$rs[month2] =stripslashes($rs[month2]);
	$rs[month3] =stripslashes($rs[month3]);
	$rs[month4] =stripslashes($rs[month4]);
	$rs[month5] =stripslashes($rs[month5]);
	$rs[month6] =stripslashes($rs[month6]);
	$rs[month7] =stripslashes($rs[month7]);
	$rs[month8] =stripslashes($rs[month8]);
	$rs[month9] =stripslashes($rs[month9]);
	$rs[month10] =stripslashes($rs[month10]);
	$rs[month11] =stripslashes($rs[month11]);
	$rs[month12] =stripslashes($rs[month12]);
?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;

	if(check_select(fm.nation,'국가명을')=='wrong'){return }
	if(check_blank(fm.city,'도시명을',0)=='wrong'){return }
	fm.submit();

}

jQuery(function($){

	$(".num").keypress(function(event){
		if(event.which && (event.which < 48 || event.which > 57)){
			event.preventDefault();
		}
	});

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

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td class="subject" width="20%">국가</td>
          <td>
	           <select name="nation">
			   <option value="">선택하세요.</option>
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
          <td class="subject">1월</td>
          <td colspan="3">
	           <?=html_input('month1',100,190)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">2월</td>
          <td colspan="3">
	           <?=html_input('month2',100,190)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">3월</td>
          <td colspan="3">
	           <?=html_input('month3',100,190)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">4월</td>
          <td colspan="3">
	           <?=html_input('month4',100,190)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">5월</td>
          <td colspan="3">
	           <?=html_input('month5',100,190)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">6월</td>
          <td colspan="3">
	           <?=html_input('month6',100,190)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">7월</td>
          <td colspan="3">
	           <?=html_input('month7',100,190)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">8월</td>
          <td colspan="3">
	           <?=html_input('month8',100,190)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">9월</td>
          <td colspan="3">
	           <?=html_input('month9',100,190)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">10월</td>
          <td colspan="3">
	           <?=html_input('month10',100,190)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">11월</td>
          <td colspan="3">
	           <?=html_input('month11',100,190)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">12월</td>
          <td colspan="3">
	           <?=html_input('month12',100,190)?>
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
					<td><span class="btn_pack medium bold"><a href="#" onClick="opener.location.reload();self.close()"> 창닫기 </a></span></td>
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