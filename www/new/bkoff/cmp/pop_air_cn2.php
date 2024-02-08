<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_air_time";
$MENU = "cmp_basic";
$TITLE = "항공소요시간";



#### operation
if ($mode=="save"){

	$sqlInsert="
	   insert into $table (
		assort,
		airportCode,
		elapsetime
	  ) values (
	    '$assort',
		'$airportCode',
		'$elapsetime'
	)";

	$sqlModify="
	   update $table set
	    assort= '$assort',
		airportCode= '$airportCode',
		elapsetime= '$elapsetime'
	   where id_no='$id_no'
	";

	//checkVar(mysql_error(),$sql);exit;

	$sql = ($id_no)?$sqlModify : $sqlInsert;

	if($dbo->query($sql)){
		echo "<script type='text/javascript'>opener.location.reload();self.close()</script>";

	}else{
		checkVar(mysql_error(),$sql);
	}
	exit;

}

$AIRPORT_KEY="";
$AIRPORT_VAL="";
$sql = "select * from cmp_air_airport order by cityKor asc";
$dbo->query($sql);
//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar(mysql_error(),$sql);}
while($rs=$dbo->next_record()){
	$AIRPORT_KEY.=",".$rs[cityCode];
	$AIRPORT_VAL.=",$rs[cityKor] ($rs[cityCode])";
}

$sql = "
		select * from $table
		where		
		assort='$assort'
		and airportCode='$airportCode'
	";
$dbo->query($sql);
$rs=$dbo->next_record();
?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;

	if(check_select(fm.airportCode,'공항을')=='wrong'){return }
	if(check_blank(fm.elapsetime,'소요시간을',5)=='wrong'){return }
	fm.submit();

}

jQuery(function($){
	$("#elapsetime").mask("99:99");	
});
</script>

<style type="text/css">
.tm_col{display:inline-block;width:80px;font-family: verdana;font-size:12px;}	
</style>

<div style="padding:0 10px 0 10px">

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
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

    <table border="0" cellspacing="1" cellpadding="3" width="100%">
		<form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td class="subject" width="25%">구분</td>
          <td>
	           <?=radio("출국,입국","D,A",$assort,'assort')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">공항</td>
          <td>
				<select name="airportCode" class='select' style="width:150px">
				<?=option_str("출발 도시 선택".$AIRPORT_VAL,$AIRPORT_KEY,$airportCode)?>
				</select>	

          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">소요시간</td>
          <td>
	           <?=html_input("elapsetime",6,5)?>
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