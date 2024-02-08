<?
include_once("../include/common_file.php");



$sql2 = "select * from cmp_golf where id_no=$golf_id_no";
$dbo2->query($sql2);
$rs2=$dbo2->next_record();
$golf_name = $rs2[name];
$nation = $rs2[nation];



if($mode=="save"){

	if(!$id_no){
		$sql = "
			select * from cmp_set_price
			where
				golf_id_no=$golf_id_no
				and
				date_s<='$date_e'
				and
				date_e>='$date_s'
			";
		list($rows) = $dbo->query($sql);
		if($rows){
			error("시작이 또는 종료일이 겹치는 날짜가 있습니다. 다시 확인해 주세요.");
			exit;
		}
	}


	$sqlInsert="
		insert into cmp_set_price (
           cp_id,
		   golf_id_no,
		   date_s,
		   date_e,
		   w_0,
		   w_1,
		   w_2,
		   w_3,
		   w_4,
		   w_5,
		   w_6
	   ) values (
		   '$CP_ID',
           '$golf_id_no',
		   '$date_s',
		   '$date_e',
		   '$w_0',
		   '$w_1',
		   '$w_2',
		   '$w_3',
		   '$w_4',
		   '$w_5',
		   '$w_6'
	 )";


	 $sqlModify="
		update cmp_set_price set
		   golf_id_no = '$golf_id_no',
		   date_s = '$date_s',
		   date_e = '$date_e',
		   w_0 = '$w_0',
		   w_1 = '$w_1',
		   w_2 = '$w_2',
		   w_3 = '$w_3',
		   w_4 = '$w_4',
		   w_5 = '$w_5',
		   w_6 = '$w_6'
		where id_no='$id_no'
	 ";

	$sql = ($id_no)?	 $sqlModify : $sqlInsert;
	$dbo->query($sql);
	//checkVar(mysql_error(),$sql);exit;
	redirect2(SELF. "?golf_id_no=$golf_id_no");
	exit;
}
elseif($mode=="drop"){
	$sql = "delete from cmp_set_price where id_no=$id_no and golf_id_no=$golf_id_no";
	$dbo->query($sql);
	//checkVar(mysql_error(),$sql);exit;
	back();
	exit;
}





####기초 정보
$filecode = substr(SELF,5,-4);
$TITLE = "지상비 ($golf_name)" ;



?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;

	if(check_blank(fm.date_s,'시작일을')=='wrong'){return }
	if(check_blank(fm.date_e,'종료일을')=='wrong'){return }
	fm.submit();

}

function update(id_no){
	location.href="<?=SELF?>?golf_id_no=<?=$golf_id_no?>&id_no="+id_no;
}

function drop(id_no){
	if(confirm('정말 삭제하시겠습니까?')){
		location.href="<?=SELF?>?mode=drop&golf_id_no=<?=$golf_id_no?>&id_no="+id_no;
	}
}

$(function(){
	<?if($nation!="일본"){?>
	$("#w_0").on("change",function(){
		var p = $("#w_0").val();
		$("#w_1").val(p);
		$("#w_2").val(p);
		$("#w_3").val(p);
		$("#w_4").val(p);
		$("#w_5").val(p);
		$("#w_6").val(p);
	});
	<?}?>
});
</script>
<style type="text/css">
body{padding:0 10px;}    
.numberic{font-family:verdana;font-size:10px;letter-spacing: -1px;}    
.d{width:11%;}
.w{width:9%;}
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

	<form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
	<input type="hidden" name="mode" value='save'>
	<input type="hidden" name="id_no" value='<?=$id_no?>'>
	<input type="hidden" name="golf_id_no" value='<?=$golf_id_no?>'>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_normal">

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject d" >시작일</th>
		<th class="subject d" >종료일</th>
		<th class="subject w" >월</th>
		<th class="subject w" >화</th>
		<th class="subject w" >수</th>
		<th class="subject w" >목</th>
		<th class="subject w" >금</th>
		<th class="subject w" >토</th>
		<th class="subject w" >일</th>
		</tr>

	    <?
        if($id_no){
    		$sql = "select * from cmp_set_price where golf_id_no=$golf_id_no and id_no=$id_no $FILTER_PARTNER_QUERY";
    		$dbo->query($sql);
            if($debug) checkVar(mysql_error(),$sql);
    		$rs=$dbo->next_record();
        }
		?>
		<tr height="35" align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><input type="text" name="date_s" id="date_s" size="11" readonly class="box dateinput c" value="<?=$rs[date_s]?>"></td>
	      <td><input type="text" name="date_e" id="date_e" size="11" readonly class="box dateinput c" value="<?=$rs[date_e]?>"></td>
	      <td><input type="text" name="w_0" id="w_0" size="10" maxlength="10" class="box comma numberic" value="<?=$rs[w_0]?>"></td>
	      <td><input type="text" name="w_1" id="w_1" size="10" maxlength="10" class="box comma numberic" value="<?=$rs[w_1]?>"></td>
	      <td><input type="text" name="w_2" id="w_2" size="10" maxlength="10" class="box comma numberic" value="<?=$rs[w_2]?>"></td>
	      <td><input type="text" name="w_3" id="w_3" size="10" maxlength="10" class="box comma numberic" value="<?=$rs[w_3]?>"></td>
	      <td><input type="text" name="w_4" id="w_4" size="10" maxlength="10" class="box comma numberic" value="<?=$rs[w_4]?>"></td>
	      <td><input type="text" name="w_5" id="w_5" size="10" maxlength="10" class="box comma numberic" value="<?=$rs[w_5]?>"></td>
	      <td><input type="text" name="w_6" id="w_6" size="10" maxlength="10" class="box comma numberic" value="<?=$rs[w_6]?>"></td>
	    </tr>
	</table>

    <div style="clear:both;width:100%;text-align:right;padding:3px">
       <span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span> &nbsp;&nbsp;
       <span class="btn_pack medium bold"><a href="<?=SELF?>?golf_id_no=<?=$golf_id_no?>"> 취소 </a></span>
    </div>
	</form>


	<br/>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_normal">

	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject d" >시작일</th>
		<th class="subject d" >종료일</th>
		<th class="subject w" >월</th>
		<th class="subject w" >화</th>
		<th class="subject w" >수</th>
		<th class="subject w" >목</th>
		<th class="subject w" >금</th>
		<th class="subject w" >토</th>
		<th class="subject w" >일</th>
		<th class="" >수정/삭제</th>
		</tr>

		<?
		$sql = "select * from cmp_set_price where golf_id_no=$golf_id_no $FILTER_PARTNER_QUERY order by date_s desc,date_e desc ";
		$dbo->query($sql);
        if($debug) checkVar(mysql_error(),$sql);
		while($rs=$dbo->next_record()){
		?>
	    <tr height="35" align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><?=$rs[date_s]?></td>
	      <td><?=$rs[date_e]?></td>
	      <td><?=nf($rs[w_0])?></td>
	      <td><?=nf($rs[w_1])?></td>
	      <td><?=nf($rs[w_2])?></td>
	      <td><?=nf($rs[w_3])?></td>
	      <td><?=nf($rs[w_4])?></td>
	      <td><?=nf($rs[w_5])?></td>
	      <td><?=nf($rs[w_6])?></td>
	      <td>
			<span class="btn_pack medium bold"><a href="#" onClick="update(<?=$rs[id_no]?>)"> 수정 </a></span>&nbsp;
			<span class="btn_pack medium bold"><a href="#" onClick="drop(<?=$rs[id_no]?>)"> 삭제 </a></span>
		  </td>
	    </tr>
		<?}?>

	</table>


	<div style="clear:both;width:100%;text-align:right;padding:3px">
	   <span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
	</div>



	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>