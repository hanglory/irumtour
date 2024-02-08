<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_blacklist";
$MENU = "order";
$TITLE = "블랙컨슈머 관리";



#### mode
if($mode=="save"){

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$cell = "${cell1}-${cell2}-${cell3}";
	$cell = str_replace(" ","",$cell);
	If($cell=="--")$cell="";

	$sqlInsert="
	   insert into $table (
		  name,
		  cell1,
		  cell2,
		  cell3,
		  cell,
		  staff,
		  memo,
		  reg_date,
		  reg_date2
	  ) values (
		  '$name',
		  '$cell1',
		  '$cell2',
		  '$cell3',
		  '$cell',
		  '$staff',
		  '$memo',
		  '$reg_date',
		  '$reg_date2'
	)";

	$sqlModify="
	   update $table set
		  name = '$name',
		  cell1 = '$cell1',
		  cell2 = '$cell2',
		  cell3 = '$cell3',
		  cell = '$cell',
		  staff = '$staff',
		  memo = '$memo'
	   where id_no='$id_no'
	";

	$sql = ($id_no)?$sqlModify :  $sqlInsert;
	$url =($id_no)? "view_${filecode}.php?id_no=$id_no": "list_${filecode}.php?$sessLink";


	if($dbo->query($sql)){
		msggo("저장하였습니다.",$url);
	}else{
		checkVar(mysql_error(),$sql);
	}
	exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){

		$sql = "delete from $table where id_no = $check[$i]";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php");exit;

}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
}
//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(){

	var fm  =document.fmData;
	if(check_blank(fm.name,'고객명을',0)=='wrong'){return}
	if(check_blank(fm.cell1,'핸드폰번호를',3)=='wrong'){return}
	if(check_blank(fm.cell2,'핸드폰번호를',3)=='wrong'){return}
	if(check_blank(fm.cell3,'핸드폰번호를',4)=='wrong'){return}

	fm.submit();
}


//-->
</script>

<?include("../top.html");?>



		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
			</tr>
			<tr>
				<td colspan="3"> </td>
			</tr>
			<tr>
				<td background="../images/common/bg_title.gif" height="5"></td>
			</tr>
		</table>


		<br>


      <table border="0" cellspacing="1" cellpadding="3" width="750">

		<form name="fmData" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="save">
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>


		<tr><td colspan="4"  bgcolor='#5E90AE' height=2></td></tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">고객명</td>
          <td colspan="3">
			<?=html_input('name',30,30)?>
          </td>
        </tr>

		<tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">고객 핸드폰번호</td>
          <td colspan="3">
			<?=html_input('cell1',3,3)?> -
			<?=html_input('cell2',4,4)?> -
			<?=html_input('cell3',4,4)?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">등록자(직원명)</td>
          <td colspan="3">
			<?=html_input('staff',30,30)?>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">내용</td>
          <td colspan="3">
			<?=html_textarea('memo',80,10)?>
          </td>
        </tr>


        <tr><td colspan="4" bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan="4" bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
		<td colspan="4">
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
				<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
				<td><span class="btn_pack medium bold"><a href="#" onClick="location.href='list_<?=$filecode?>.php?<?=$sessLink?>'"> 리스트 </a></span></td>
		    </tr>
		  </table>
		  <!-- Button End------------------------------------------------>
		</td>
		</tr>
      </table>

	</form>

	<!--내용이 들어가는 곳 끝-->


<!-- Copyright -->
<?include_once("../bottom.html");?>

