<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_calendar";
$MENU = "cmp_basic";
$TITLE = "일정관리";


#### staff
$sql = "select * from cmp_staff where bit_hide<>1 order by name asc";
$dbo->query($sql);
while($rs=$dbo->next_record()){
	$STAFF.=",$rs[name] ($rs[id])";
}


#### operation
if ($mode=="save"){

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$id = $_SESSION['sessLogin']['id'];
	$name = $_SESSION['sessLogin']['name'];

	$sqlInsert="
	   insert into $table (
            cp_id,
			id,
			name,
			color,
			sc_date,
			content,
			reg_date,
			reg_date2
	  ) values (
			'$CP_ID',
            '$id',
			'$name',
			'$color',
			'$sc_date',
			'$content',
			'$reg_date',
			'$reg_date2'
	)";


	$sqlModify="
	   update $table set
			color ='$color',
			content ='$content'
	   where id_no='$id_no'
	";

	if($id_no){
		$sql =$sqlModify;
		$url = "view_${filecode}.php?id_no=$id_no&ctg1=$ctg1";
	}else{
		$sql =$sqlInsert;
		$url = "list_${filecode}.php?ctg1=$ctg1&page=1";
	}

	//checkVar(mysql_error(),$sql);exit;

	if($dbo->query($sql)){
		If($id_no)  echo "<script type='text/javascript'>opener.location.reload();self.close();</script>";
		Else echo "<script type='text/javascript'>opener.location.reload();self.close();</script>";

	}else{
		checkVar(mysql_error(),$sql);
	}
	exit;

}elseif ($mode=="drop_one"){

	$sql = "delete from $table where id_no = $id_no";
	$dbo->query($sql);
	echo "<script type='text/javascript'>opener.location.reload();self.close();</script>";
	exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){

		$sql = "delete from $table where id_no = $check[$i]";
		$dbo->query($sql);
	}
	echo "<script type='text/javascript'>opener.location.reload();self.close();</script>";
	exit;


}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
}
?>
<?include("../top_min.html");?>
<script src='../../bgrins/spectrum.js'></script>
<link rel='stylesheet' href='../../bgrins/spectrum.css' />
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;
	fm.mode.value="save";
	if(check_blank(fm.content,'내용을',0)=='wrong'){return }
	fm.submit();

}

function drop(){
	var fm = document.fmData;
	fm.mode.value="drop_one";
	if(confirm("삭제하시겠습니까?")){
		fm.submit();
	}
}

jQuery(function($){

	$("#color").spectrum({
	    allowEmpty: true,
	    preferredFormat: true,
	    showInput: true,
	});

	$("#content").css("padding","10px");


});
</script>
<style type="text/css">
body{padding: 0 10px;}
</style>


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
		<input type="hidden" name="sc_date" value='<?=$sc_date?>'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>

		<tr><td colspan="2"  bgcolor='#5E90AE' height=2></td></tr>


        <tr>
          <td class="subject" width="15%">날짜</td>
          <td>
	           <?=$sc_date?>
	           (<?=getDow($sc_date)?>)
	           &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	           <?=html_input('color',30,40)?> <?=$rs[color]?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td class="subject" width="15%">내용</td>
          <td>
	           <?=html_textarea("content",0,17)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>



        <tr><td colspan="2" bgcolor='#E1E1E1' height='1'></td></tr>

        <tr>
				  <td colspan="2">
					  <br>
					  <!-- Button Begin---------------------------------------------->
					  <table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
						<tr align="right">
							<td>
								<span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span>
								&nbsp;&nbsp;&nbsp;
								<?if($rs[id_no]){?>
								<span class="btn_pack medium bold"><a href="#" onClick="drop()"> 삭제 </a></span>
								&nbsp;&nbsp;&nbsp;
								<?}?>
								<span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
							</td>
						</tr>
					  </table>
					  <!-- Button End------------------------------------------------>
				  </td>
        </tr>


	</form>
	</table>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>