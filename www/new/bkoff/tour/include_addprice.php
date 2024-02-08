<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour_addprice";
$TITLE = "일정설정";



if($mode=="save"){

	$sql = "delete from $table where tid=$tid";
	$dbo->query($sql);

	for($i=0;$i<count($subject);$i++){

		$addprice[$i] = price_format($addprice[$i]);

		$sql="
		   insert into $table (
			  tid,
			  subject,
			  addprice,
			  qty
		  ) values (
			  '$tid',
			  '$subject[$i]',
			  '$addprice[$i]',
			  '$qty[$i]'
		)";

		if($subject[$i]) $dbo->query($sql);

	}
	redirect2("?tid=$tid");
	exit;

}elseif($mode=="del_addprice"){

	$sql = "delete from $table where id_no=$id_no";
	$dbo->query($sql);
	back();
	exit;

}
?>
<?include("../top_min.html");?>
<script language="JavaScript">
<!--
function add_val(no){
	var fm =document.fmData;
	if($("#subject"+no).val()=="")$("#subject"+no).focus();
	else fm.submit();
}

function del_addprice(id_no){
	$("#subject0").val("");
	$("#addprice0").val("");
	location.href="?mode=del_addprice&tid=<?=$tid?>&id_no=" + id_no;
}
//-->
</script>


    <table border="0" cellspacing="1" cellpadding="3" width="100%" align="center" >
		<form name="fmData" method="post">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="tid" value='<?=$tid?>'>

        <?
		$i=1;
		$sql = "select * from $table where tid=$tid order by id_no asc";
		$dbo->query($sql);
		while($rs=$dbo->next_record()){
		?>
		<tr>
          <td>
            항목명 : <input type="text" name="subject[]" id="subject<?=$i?>" value="<?=$rs[subject]?>" size="20" maxlength="35" class="box" onblur="add_val('<?=$i?>')"/>
			금액 : <input type="text" name="addprice[]" id="addprice<?=$i?>" value="<?=number_format($rs[addprice])?>" size="10" maxlength="10" class="box numberic" onblur="add_val('<?=$i?>')"/>

			&nbsp;&nbsp;&nbsp;
			<span class="btn_pack small bold"><a href="javascript:del_addprice(<?=$rs[id_no]?>)"> 삭제 </a></span>
          </td>
        </tr>
		<?
			$i++;
		}
		?>
		<tr>
          <td>
            항목명 : <input type="text" name="subject[]" id="subject0" value="" size="20" maxlength="35" class="box"/>
			금액 : <input type="text" name="addprice[]" id="addprice0" value="" size="10" maxlength="10" class="box numberic"/>
			&nbsp;&nbsp;&nbsp;
			<span class="btn_pack small bold"><a href="javascript:add_val(0)"> 저장 </a></span>
          </td>
        </tr>

	</form>
	</table>



	<!--내용이 들어가는 곳 끝-->
	<iframe name="actarea" style="display:none;"></iframe>


</body>
</html>