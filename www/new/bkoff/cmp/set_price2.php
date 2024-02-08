<?
include_once("../include/common_file.php");


$sql2 = "select * from cmp_golf where id_no=$golf_id_no";
$dbo2->query($sql2);
$rs2=$dbo2->next_record();
$golf_name = $rs2[name];


if($mode=="save"){

	if(!$id_no){
		$sql = "
			select * from cmp_set_price_hotel
			where
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
		insert into cmp_set_price_hotel (
           cp_id,
		   golf_id_no,
		   date_s,
		   date_e,
		   price
	   ) values (
		   '$CP_ID',
           '$golf_id_no',
		   '$date_s',
		   '$date_e',
		   '$price'
	 )";


	 $sqlModify="
		update cmp_set_price_hotel set
		   golf_id_no = '$golf_id_no',
		   date_s = '$date_s',
		   date_e = '$date_e',
		   price = '$price'
		where id_no='$id_no'
	 ";

	$sql = ($id_no)?	 $sqlModify : $sqlInsert;
	$dbo->query($sql);
	//checkVar(mysql_error(),$sql);exit;
	redirect2(SELF. "?golf_id_no=$golf_id_no");
	exit;
}
elseif($mode=="drop"){
	$sql = "delete from cmp_set_price_hotel where id_no=$id_no and golf_id_no=$golf_id_no";
	$dbo->query($sql);
	//checkVar(mysql_error(),$sql);exit;
	back();
	exit;
}





####기초 정보
$filecode = substr(SELF,5,-4);
$TITLE = "변동일(호텔) ($golf_name)" ;



?>
<?include("../top_min.html");?>
<style type="text/css">
body{overflow-x:hidden;}
.d{width:20%;}
.w{width:20%;}
</style>
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
</script>

<div style="padding:0 10px 0 10px;">

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

	<form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
	<input type="hidden" name="mode" value='save'>
	<input type="hidden" name="id_no" value='<?=$id_no?>'>
	<input type="hidden" name="golf_id_no" value='<?=$golf_id_no?>'>


    <table border="0" cellspacing="0" cellpadding="3" width="97%" id="tbl_normal">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject d" >시작일</th>
		<th class="subject d" >종료일</th>
		<th class="subject w" >추가금액</th>
		<th class="subject" ></th>
		</tr>

	    <?
		$sql = "select * from cmp_set_price_hotel where golf_id_no=$golf_id_no and id_no=$id_no";
		$dbo->query($sql);
		$rs=$dbo->next_record();
		?>
		<tr height="35" align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><input type="text" name="date_s" id="date_s" size="13" readonly class="box dateinput c" value="<?=$rs[date_s]?>"></td>
	      <td><input type="text" name="date_e" id="date_e" size="13" readonly class="box dateinput c" value="<?=$rs[date_e]?>"></td>
	      <td><input type="text" name="price" id="price" size="10" class="box numberic" value="<?=$rs[price]?>"></td>
	      <td>
			<span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span>&nbsp;
			<span class="btn_pack medium bold"><a href="<?=SELF?>?golf_id_no=<?=$golf_id_no?>"> 취소 </a></span>
		  </td>
	    </tr>


	</table>

	</form>


	<br>


    <table border="0" cellspacing="0" cellpadding="3" width="97%" id="tbl_normal">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject d" >시작일</th>
		<th class="subject d" >종료일</th>
		<th class="subject w" >추가금액</th>
		<th class="" >수정/삭제</th>
		</tr>

		<?
		$sql = "select * from cmp_set_price_hotel where golf_id_no=$golf_id_no order by date_s desc,date_e desc ";
		$dbo->query($sql);
		while($rs=$dbo->next_record()){

		?>
	    <tr height="35" align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><?=$rs[date_s]?></td>
	      <td><?=$rs[date_e]?></td>
	      <td><?=nf($rs[price])?></td>
	      <td>
			<span class="btn_pack medium bold"><a href="#" onClick="update(<?=$rs[id_no]?>)"> 수정 </a></span>&nbsp;
			<span class="btn_pack medium bold"><a href="#" onClick="drop(<?=$rs[id_no]?>)"> 삭제 </a></span>
		  </td>
	    </tr>
		<?}?>

	</table>


	<div style="text-align:right;">
	<span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
	</div>


</div>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>