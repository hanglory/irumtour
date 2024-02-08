<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_people";
$MENU = "cmp_basic";
$TITLE = "고객별 예약 정보 관리 대장 > 고객정보 입력";
$file_rows = 1; //파일 업로드 갯수


$sql = "update cmp_reservation set price_last = price-price_customer_input";
list($rows) = $dbo->query($sql);
//checkVar(mysql_error(),$rows);


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
	$bit=1;

	$sql = "update $table set bit=0 where code=$code";
	$dbo->query($sql);

	for($i=0; $i <count($name);$i++){

			$price[$i] = rnf($price[$i]);
			$price_air[$i]= rnf($price_air[$i]);
			$price_land[$i]= rnf($price_land[$i]);
			$price_prev = rnf($price_prev);

			$rn[$i] = trim($rn[$i]);
			$passport_no[$i] = trim($passport_no[$i]);

			if($rn[$i]){
			$arr = explode("-",$rn[$i]);
			$sex_ = substr($arr[1],0,1);
			$sex[$i] = ($sex_==1 || $sex_==3)? "M":"F";

			$aes = new AES($rn[$i], $inputKey, $blockSize);
			$enc = $aes->encrypt();
			$rn[$i] = $enc;
			}

			if($passport_no[$i]){
			$aes = new AES($passport_no[$i], $inputKey, $blockSize);
			$enc = $aes->encrypt();
			$passport_no[$i] = $enc;
			}

			$seq	 = $i+1;

			$sqlInsert="
			   insert into $table (
				  code,
				  id,
				  name,
				  sex,
				  name_eng,
				  rn,
				  passport_no,
				  passport_limit,
				  phone,
				  price,
				  price_air,
				  price_land,
				  memo,
				  seq,
				  bit,
				  staff,
				  reg_date,
				  reg_date2
			  ) values (
				  '$code',
				  '$id[$i]',
				  '$name[$i]',
				  '$sex[$i]',
				  '$name_eng[$i]',
				  '$rn[$i]',
				  '$passport_no[$i]',
				  '$passport_limit[$i]',
				  '$phone[$i]',
				  '$price[$i]',
				  '$price_air[$i]',
				  '$price_land[$i]',
				  '$memo[$i]',
				  '$seq',
				  '$bit',
				  '$staff',
				  '$reg_date',
				  '$reg_date2'
			)";


			$sqlModify="
			   update $table set
				  id = '$id[$i]',
				  name = '$name[$i]',
				  sex = '$sex[$i]',
				  name_eng = '$name_eng[$i]',
				  rn = '$rn[$i]',
				  passport_no = '$passport_no[$i]',
				  passport_limit = '$passport_limit[$i]',
				  phone = '$phone[$i]',
				  price = '$price[$i]',
				  price_air = '$price_air[$i]',
				  price_land = '$price_land[$i]',
				  memo = '$memo[$i]',
				  seq = '$seq',
				  bit = '$bit'
			   where id_no='$id_no[$i]'
			";

			if($id_no[$i]){
				$sql =$sqlModify;
			}else{
				$sql =$sqlInsert;
			}

			//checkVar("",$sql);

			if($dbo->query($sql)){

				//cmp_customer update
				$sql = "delete from cmp_customer where name='$name[$i]' and rn='$rn[$i]' ";
				$dbo->query($sql);

				$sql="
				   insert into cmp_customer (
					  id,
					  name,
					  leader,
					  sex,
					  name_eng,
					  rn,
					  passport_no,
					  passport_limit,
					  phone,
					  staff
				  ) values (
					  '$id[$i]',
					  '$name[$i]',
					  '$leader',
					  '$sex[$i]',
					  '$name_eng[$i]',
					  '$rn[$i]',
					  '$passport_no[$i]',
					  '$passport_limit[$i]',
					  '$phone[$i]',
					  '$staff'
				)";
				$dbo->query($sql);

				$sql = "
					update cmp_reservation set
						price=(select sum(price) from cmp_people where code=$code and bit=1),
						price_last = (select sum(price)-price_customer_input from cmp_people where code=$code and bit=1)
					where code=$code";
				$dbo->query($sql);
			}

	}
	error("저장하였습니다.");
	exit;

}elseif ($mode=="drop"){

	$sql = "delete from $table where id_no = $id_no and code=$code";
	$dbo->query($sql);
	error("삭제하였습니다.");exit;

}


$sql = "select * from cmp_reservation where code=$code";
list($rows) = $dbo->query($sql);
if($rows){
	$rs=$dbo->next_record();
	$price=nf($rs[price]);
	$price_customer_input=nf($rs[price_customer_input]);
	$price_last=nf($rs[price_last]);
	$leader = $rs[name];
}else{
	$sql = "select sum(price) as price from cmp_people where code=$code";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	$price=nf($rs[price]);
	$price_customer_input=nf($rs[price_customer_input]);
	$price_last=nf($rs[price_last]);
}



$people = ($people)?$people : 1;
?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;
	var people = "<?=$people?>";

	for(i=1;i<=people;i++){
		//if($("#name_"+i).val()==""){alert("고객명을 입력해 주세요.");$("#name_"+i).focus();return;}
	}


	fm.submit();

}

function find(i){

	if(i){
		var name = $("#name_"+i).val();
		if(name==""){alert("고객명을 입력하세요.");$("#name_"+i).focus();return;}
	}else{
		var name = $("#name").val();
		if(name==""){alert("고객명을 입력하세요.");$("#name").focus();return;}
	}

	newWin('pop_customer.php?name='+name+'&i='+i,950,400,1,1,'','customer')

}

function set_golf(k,v){
	$("#golf_name").val(k);
	$("#golf_id_no").val(v);
}

function modify(i){
	var fm = document.fmData2;

	fm.mode.value= "save";
	fm.id_no.value=$("#id_no_"+ i).val();
	fm.id.value=$("#id_"+ i).val();
	fm.name.value=$("#name_"+ i).val();
	fm.sex.value=$("#sex_"+ i).val();
	fm.name_eng.value=$("#name_eng_"+ i).val();
	fm.rn.value=$("#rn_"+ i).val();
	fm.passport_no.value=$("#passport_no_"+ i).val();
	fm.phone.value=$("#phone_"+ i).val();
	fm.price.value=$("#price_"+ i).val();
	fm.price_air.value=$("#price_air_"+ i).val();
	fm.price_land.value=$("#price_land_"+ i).val();
	fm.memo.value=$("#memo_"+ i).val();

	fm.submit();

}

function drop(i){
	var fm = document.fmData2;
	if(confirm('삭제하시겠습니까')){
		fm.mode.value= "drop";
		fm.id_no.value=$("#id_no_"+ i).val();
		fm.submit();
	}

}

function num(col,price){
	//actarea.location.href="../action.php?mode=num&col="+col+"&price="+price;
}

jQuery(function($){

	$(".num").keypress(function(event){
		if(event.which && (event.which < 48 || event.which > 57)){
			event.preventDefault();
		}
	});

	$(".rn").mask("999999-9999999");

	opener.$("#price").val('<?=$price?>');
	opener.$("#price_customer_input").val('<?=$price_customer_input?>');
	opener.$("#price_last").val('<?=$price_last?>');

	$("#price_prev").val(opener.$("#price_prev").val());

	var people = "<?=nf($people)?>";
	$("#price_1").on("change",function(){
		for(i=2; i<=people;i++){
			$("#price_"+i).val(this.value);
		}
	});
	$("#price_air_1").on("change",function(){
		for(i=2; i<=people;i++){
			$("#price_air_"+i).val(this.value);
		}
	});
	$("#price_land_1").on("change",function(){
		for(i=2; i<=people;i++){
			$("#price_land_"+i).val(this.value);
		}
	});

	<?for($i=1; $i<=$people;$i++){?>
	$('#name_<?=$i?>').keypress(function(e){
		if(e.which == 13) find(<?=$i?>);
	});
	<?}?>

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


    <table border="0" cellspacing="0" cellpadding="3" width="97%" id="tbl_list">

		<form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" id="code" name="code" value='<?=$code?>'>
		<input type="hidden" id="price_prev" name="price_prev">
		<input type="hidden" id="leader" name="leader" value="<?=$leader?>">

		<tr><td colspan="15"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >고객명</th>
		<th class="subject" ></th>
		<th class="subject" >성별</th>
		<th class="subject" >영문명</th>
		<th class="subject" >주민번호</th>
		<th class="subject" >여권번호</th>
		<th class="subject" >유효기간</th>
		<th class="subject" >연락처</th>
		<th class="subject" >판매가</th>
		<th class="subject" >항공요금</th>
		<th class="subject" >지상비</th>
		<th class="subject" >계약금</th>
		<th class="subject" >잔액</th>
		<th class="subject" >메모</th>
		<th class="subject" ></th>
		</tr>
        <tr><td colspan="15" class="tblLine"></td></tr>

		<?
		$code = $_GET[code];

		$sql = "select * from $table where code=$code order by id_no asc";
		$dbo->query($sql);
		//checkVar(mysql_error(),$sql);

		for($j=1; $j <= $people; $j++){
			$rs=$dbo->next_record();

			if($rs[rn]){
			$aes = new AES($rs[rn], $inputKey, $blockSize);
			$dec=$aes->decrypt();
			$rs[rn] = $dec;
			}

			if($rs[passport_no]){
			$aes = new AES($rs[passport_no], $inputKey, $blockSize);
			$dec=$aes->decrypt();
			$rs[passport_no] = $dec;
			}
		?>
		<tr height="35" align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
			<td>
				<input type="hidden" name="id_no[]" id="id_no_<?=$j?>" value="<?=$rs[id_no]?>">
				<input type="hidden" name="id[]" id="id_<?=$j?>" value="<?=$rs[id]?>">
				<input type="text" name="name[]" id="name_<?=$j?>" value="<?=$rs[name]?>" size="8" maxlength="30" class="box" /></td>
				<td><span class="btn_pack small bold"><a href="javascript:find(<?=$j?>)">검색</a></span></td>
				<td><select name="sex[]" id="sex_<?=$j?>"><?=option_str("M,F","M,F",$rs[sex])?></select></td>
				<td><input type="text" name="name_eng[]" id="name_eng_<?=$j?>" value="<?=$rs[name_eng]?>" size="22" maxlength="30" class="box" /></td>
				<td><input type="text" name="rn[]" id="rn_<?=$j?>" value="<?=$rs[rn]?>" size="18" maxlength="14" class="box rn" onkeyup="chk_sex(this.valuem<?=$j?>)" /></td>
				<td><input type="text" name="passport_no[]" id="passport_no_<?=$j?>" value="<?=$rs[passport_no]?>" size="12" maxlength="30" class="box" /></td>
				<td><input type="text" name="passport_limit[]" id="passport_limit_<?=$j?>" value="<?=$rs[passport_limit]?>" size="12" maxlength="30" class="box" /></td>
				<td><input type="text" name="phone[]" id="phone_<?=$j?>" value="<?=$rs[phone]?>" size="15" maxlength="30" class="box" /></td>
				<td><input type="text" name="price[]" id="price_<?=$j?>" value="<?=nf($rs[price])?>" size="10" maxlength="9" class="box numberic" /></td>
				<td><input type="text" name="price_air[]" id="price_air_<?=$j?>" value="<?=nf($rs[price_air])?>" size="10" maxlength="9" class="box numberic" /></td>
				<td><input type="text" name="price_land[]" id="price_land_<?=$j?>" value="<?=nf($rs[price_land])?>" size="10" maxlength="9" class="box numberic" /></td>

				<td><input type="text" name="price_prev[]" id="price_prev_<?=$j?>" value="<?=nf($rs[price_prev])?>" size="12" maxlength="9" class="box numberic" onkeyup="num('price_prev_<?=$j?>',this.value)"/></td>
				<td><input type="text" name="price_last[]" id="price_last_<?=$j?>" value="<?=nf($rs[price_last])?>" size="12" maxlength="9" class="box numberic" style="border:0;" readonly  onkeyup="num('price_last_<?=$j?>',this.value)"/></td>

				<td><input type="text" name="memo[]" id="memo_<?=$j?>" value="<?=$rs[memo]?>" size="25" maxlength="100" class="box" /></td>
				<td><span class="btn_pack medium bold"><a href="#" onClick="drop(<?=$j?>)">삭제</a></span></td>
		</tr>
        <tr><td colspan="15" class="tblLine"></td></tr>
		<?
		}?>
		</form>
	</table>

		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table width="97%" border="0" cellspacing="0" cellpadding="0" align="right">
			 <tr>
			  <td width="60%" align="left">

			  </td>
			  <td align="right" style="padding-right:23px">
				<span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span>&nbsp;&nbsp;&nbsp;
				<span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
			  </td>
			</tr>
		  </table>
		  <!-- Button End------------------------------------------------>


</div>




	<form name="fmData2" method="post" enctype="multipart/form-data" action="<?=SELF?>">
	<input type="hidden" name="mode" id="mode" value='drop'>
	<input type="hidden" name="code" id="code" value='<?=$code?>'>
	<input type="hidden" name="id_no" id="id_no">
	<input type="hidden" name="memo" id="memo">
	</form>
	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>