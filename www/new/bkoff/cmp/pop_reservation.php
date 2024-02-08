<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_people";
$MENU = "cmp_basic";
$TITLE = "고객예약정보 > 고객정보 입력";
$file_rows = 1; //파일 업로드 갯수


$sql = "update cmp_reservation set price_last = price-(price_prev+price_prev2)";
list($rows) = $dbo->query($sql);
//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql);


#### staff
$sql = "select * from cmp_staff where bit_hide<>1 order by name asc";
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

	$p="";

	for($i=0; $i <count($name);$i++){

			$p .= "{@}"  . $name[$i] . "{@}";

			$price[$i] = rnf($price[$i]);
			$price_air[$i]= rnf($price_air[$i]);
			$price_land[$i]= rnf($price_land[$i]);
			$price_refund[$i]= rnf($price_refund[$i]);
			$price_prev[$i]= rnf($price_prev[$i]);
			$price_prev2[$i]= rnf($price_prev2[$i]);
			$price_prev3[$i]= rnf($price_prev3[$i]);
			$price_input[$i]= rnf($price_input[$i]);
			$price_last[$i]= $price[$i]- ($price_prev[$i] + $price_prev2[$i]+ $price_prev3[$i]);
			$passport_limit[$i] = strtoupper($passport_limit[$i]);
			//$price_prev = rnf($price_prev);

			$rn[$i] = trim($rn[$i]);
			$passport_no[$i] = trim($passport_no[$i]);

			if($rn[$i]){

				$s = substr(rnf($rn[$i]),6,1);
				$sex[$i] = ($s=="9" || $s=="1" || $s=="3" || $s=="5" || $s=="7")? "M":"F";

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
				  price_refund,
				  price_prev,
				  price_prev2,
				  price_prev3,
				  price_input,
				  price_last,
				  memo,
				  seq,
				  bit,
				  staff,
				  date_in,
				  date_in2,
				  date_out,
				  date_out2,
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
				  '$price_refund[$i]',
				  '$price_prev[$i]',
				  '$price_prev2[$i]',
				  '$price_prev3[$i]',
				  '$price_input[$i]',
				  '$price_last[$i]',
				  '$memo[$i]',
				  '$seq',
				  '$bit',
				  '$staff',
				  '$date_in[$i]',
				  '$date_in2[$i]',
				  '$date_out[$i]',
				  '$date_out2[$i]',
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
				  price_refund = '$price_refund[$i]',
				  price_prev = '$price_prev[$i]',
				  price_prev2 = '$price_prev2[$i]',
				  price_prev3 = '$price_prev3[$i]',
				  price_input = '$price_input[$i]',
				  price_last = '$price_last[$i]',
				  memo = '$memo[$i]',
				  seq = '$seq',
				  date_in = '$date_in[$i]',
				  date_in2 = '$date_in2[$i]',
				  date_out = '$date_out[$i]',
				  date_out2 = '$date_out2[$i]',
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
				      cp_id,
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
				      '$CP_ID',
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

				$sql = "select sum(price_air+price_land+price_refund) as price_tmp_output from cmp_people where code=$code and bit=1 ";
				$dbo->query($sql);
				$rs=$dbo->next_record();

				$sql = "
					update cmp_reservation set
						price=(select sum(price) from cmp_people where code=$code and bit=1),
						price_last = (select sum(price)-sum(price_prev+price_prev2+price_prev3) from cmp_people where code=$code  and bit=1),
						price_prev = (select sum(price_prev+price_prev2) from cmp_people where code=$code  and bit=1),
						price_customer_input = (select sum(price_input) from cmp_people where code=$code  and bit=1),
						fee = (select sum(price-(price_air+price_land+price_refund)) from cmp_people where code=$code and bit=1),
						price_tmp_output = $rs[price_tmp_output]
					where code=$code";
				$dbo->query($sql);

				$price_tmp_output = nf($rs[price_tmp_output]);
				//checkVar(mysql_error(),$sql);exit;

			}else{
				checkVar(mysql_error(),$sql);exit;
			}

	}


	$sql2 = $dbo2->query("select sum(price) as total ,count(bit) as cnt from cmp_people where bit=1 and code=$code");
	$rs2=$dbo2->next_record();
	$price = nf($rs2[total]);
	echo "
	<script>
		opener.document.getElementById('price').value='$price'
		opener.document.getElementById('price_tmp_output').value='$price_tmp_output'
	</script>";

	$sql2 = $dbo2->query("update cmp_reservation set peoples='$p' where code=$code");
	$rs2=$dbo2->next_record();

	error("저장하였습니다.");
	exit;

}elseif ($mode=="drop"){

	$sql = "delete from $table where id_no = $id_no and code=$code";
	$dbo->query($sql);

	$sql2 = "select * from cmp_people where code=$code";
	$dbo2->query($sql2);
	$p="";
	while($rs2=$dbo2->next_record()){
		$p .= "{@}"  . $rs2[name] . "{@}";
	}
 	$sql2 = "update cmp_reservation set peoples='$p' where code=$code";
	$dbo2->query($sql2);

	error("삭제하였습니다.");exit;

}


$sql = "select * from cmp_reservation where code=$code";
list($rows) = $dbo->query($sql);
if($rows){
	$rs=$dbo->next_record();
	$leader = $rs[name];
}


$sql2 = "select sum(price) as price,sum(price_prev+price_prev2+price_prev3) as prev,sum(price_input) as input,sum(price_air+price_land+price_refund) as payed from cmp_people where code=$code and bit=1";
$dbo2->query($sql2);
$rs2=$dbo2->next_record();
$price=nf($rs2[price]);
$price_customer_input=nf($rs2[input]);
$price_prev=nf($rs2[prev]);
$price_last=nf($rs2[price]-$rs2[prev]);
$price_payed=nf($rs2[payed]);

$price_total_payed_ =$rs2[price] - $rs2[payed];
$price_one_payed_= ($rs2[price] - $rs2[payed])/$people;

$price_total_payed=nf($price_total_payed_);
$price_one_payed=nf($price_one_payed_);

$sql2 = "update cmp_reservation set profit_total='$price_total_payed_',profit_one='$price_one_payed_' where code=$code  ";
$dbo2->query($sql2);


$people = ($people)?$people : 1;
?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;
	var people = "<?=$people?>";

	/*
	for(i=1;i<=people;i++){
		if($("#name_"+i).val()==""){alert("고객명을 입력해 주세요.");$("#name_"+i).focus();return;}
	}
	*/

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

	newWin('pop_customer.php?code=<?=$code?>&name='+name+'&i='+i,950,400,1,1,'','customer')

}

function find2(i){

	if(i){
		var phone = $("#phone_"+i).val();
		if(phone==""){alert("연락처를 입력하세요.");$("#phone_"+i).focus();return;}
	}else{
		var phone = $("#phone").val();
		if(phone==""){alert("연락처를 입력하세요.");$("#phone").focus();return;}
	}

	newWin('pop_customer.php?phone='+phone+'&i='+i,950,400,1,1,'','customer')

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
	fm.price_refund.value=$("#price_refund_"+ i).val();
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

function cell(col,cell){
	actarea.location.href="../action.php?mode=cell&col="+col+"&cell="+cell;
}

function num(col,price){
	//actarea.location.href="../action.php?mode=num&col="+col+"&price="+price;
}

function passport_limit(col,date){
	actarea.location.href="../action.php?mode=limit&col="+col+"&date="+date;
}

function pop_passport(j){
	var passport = $("#passport_no_"+j).val();
	var url ="set_passport.php?code=<?=$code?>";
	url += "&j=" + j;
	url += "&passport_no=" + passport;
	url += "&name=" + $("#name_"+j).val();

	if(passport=="" || passport==" "){
		alert("여권번호를 입력해 주세요.");
		$("#passport_no_"+j).focus();
		return;
	}

	newWin(url,850,600,1,1);
}

function pop_files(assort,id_no,j){
	var url ="set_files.php?code=<?=$code?>";
	url += "&assort=" + assort;
	url += "&id_no=" + id_no;
	url += "&j=" + j;
	url += "&name=" + $("#name_"+j).val();

	if(id_no==""){
		alert("파일을 등록하시려면 먼저 저장해 주세요.");
		return;
	}

	newWin(url,850,200,1,1);
}

jQuery(function($){

	$(".num").keypress(function(event){
		if(event.which && (event.which < 48 || event.which > 57)){
			event.preventDefault();
		}
	});

	$(".rn").mask("999999-9999999");
	$(".passport").mask("99aaa9999");

	opener.$("#price").val('<?=$price?>');
	opener.$("#price_prev").val('<?=$price_prev?>');
	opener.$("#price_customer_input").val('<?=$price_customer_input?>');
	opener.$("#price_last").val('<?=$price_last?>');
	opener.$("#profit_total").text('<?=$price_total_payed?>');
	opener.$("#profit_one").text('<?=$price_one_payed?>');

	$("#price_prev").val(opener.$("#price_prev").val());

    /*	   2016-11-10 최경아 이사님 요청 자동 복사 기능 제거
	var people = "<?=nf($people)?>";
	$("#price_1").on("change",function(){
		var num =comma(this.value);
		$("#price_1").val(num);
		for(i=2; i<=people;i++){
			$("#price_"+i).val(num);
		}
	});
	$("#price_air_1").on("change",function(){
		var num =comma(this.value);
		$("#price_air_1").val(num);
		for(i=2; i<=people;i++){
			$("#price_air_"+i).val(num);
		}
	});
	$("#price_land_1").on("change",function(){
		var num =comma(this.value);
		$("#price_land_1").val(num);
		for(i=2; i<=people;i++){
			$("#price_land_"+i).val(num);
		}
	});


	$("#price_prev_1").on("change",function(){
		var num =comma(this.value);
		$("#price_prev_1").val(num);
		for(i=2; i<=people;i++){
			$("#price_prev_"+i).val(num);
		}
	});
	*/


	/*
	$("#price_prev_1").on("change",function(){
		var num =comma(this.value);
		$("#price_prev_1").val(num);
	});
	*/

	<?for($i=1; $i<=$people;$i++){?>
	$('#name_<?=$i?>').keypress(function(e){
		if(e.which == 13) find(<?=$i?>);
	});
	<?}?>

});
</script>
<style type="text/css">
.sum{text-align:right;padding-right:12px}
input{padding:0;margin:0;letter-spacing: -0.8px;font-family: verdana;}
#tbl_list{padding: 0;margin: 0}
.sr{float:right;padding-right: 5px}

.blue{color:blue !important;}
.red{color:red !important;}
</style>

<div style="padding:10px">

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

	<?if($REMOTE_ADDR=="106.246.54.27"){?>
    <p class="sf2">! 여권 유효기간 형식 : 날짜 + 월(영문) + 년도, 예 : 24NOV2025</p>
	<?}?>
    <table border="0" cellspacing="0" cellpadding="0" width="100%" id="tbl_list">

		<form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" id="code" name="code" value='<?=$code?>'>
		<input type="hidden" id="price_prev" name="price_prev">
		<input type="hidden" id="leader" name="leader" value="<?=$leader?>">

		<tr><td colspan="20"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >고객명</th>
		<th class="subject" ></th>
		<th class="subject" >성별</th>
		<th class="subject" >영문명</th>
		<th class="subject" >주민번호</th>
		<th class="subject" >여권번호</th>
		<th class="subject" >유효기간</th>
		<th class="subject" >연락처</th>
		<!-- <th class="subject" ></th> -->
		<th class="subject" >판매가</th>
		<th class="subject blue" >출금액1<br/>(항공)</th>
		<th class="subject" >출금일1</th>
		<th class="subject blue" >출금액2<br/>(지상비)</th>
		<th class="subject" >출금일2</th>
		<th class="subject red" >입금액1<br/>(계약금)</th>
		<th class="subject" >입금일1</th>
		<th class="subject red" >입금액2<br/>(잔금)</th>
		<th class="subject" >입금일2</th>
		<th class="subject" >잔액</th>
		<!-- <th class="subject" >메모</th> -->
		<th class="subject" ></th>
		</tr>
        <tr><td colspan="20" class="tblLine"></td></tr>

		<?
		$sum1=0;
		$sum2=0;
		$sum3=0;
		$sum4=0;
		$sum5=0;
		$sum6=0;

		$code = $_GET[code];

		$sql = "select * from $table where code=$code and bit=1 order by id_no asc";
		$dbo->query($sql);
		//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql);

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

			$limit_color = "blank";
			if($rs[passport_limit]) $limit_color = chk_limit($rs[passport_limit],6);

			$id_code = $code . "_" . $j;
			$id_code2 = $code . "_" . $rs[id_no];
			$sql2 = "select * from cmp_passport where passport_no='$rs[passport_no]'";
			list($photo_rows) = $dbo2->query($sql2);
			$sql2 = "select * from cmp_files where id_code='${id_code2}_1'";
			list($photo_rows2) = $dbo2->query($sql2);
			//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27") checkVar($photo_rows2,$sql2);
			$sql2 = "select * from cmp_files where id_code='${id_code2}_2'";
			list($photo_rows3) = $dbo2->query($sql2);

			$rn_css = (!$rs[rn] || rn_check($rs[rn]))? "":"background-color:#ffcece;";

		?>
		<tr height="35" align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
			<td>
				<input type="hidden" name="id_no[]" id="id_no_<?=$j?>" value="<?=$rs[id_no]?>">
				<input type="hidden" name="id[]" id="id_<?=$j?>" value="<?=$rs[id]?>">
				<input type="text" name="name[]" id="name_<?=$j?>" value="<?=$rs[name]?>" size="7" maxlength="30" class="box" /></td>
				<td><span class="btn_pack medium bold"><a href="javascript:find(<?=$j?>)">검색</a></span></td>
				<td><select name="sex[]" id="sex_<?=$j?>"><?=option_str("M,F","M,F",$rs[sex])?></select></td>
				<td><input type="text" name="name_eng[]" id="name_eng_<?=$j?>" value="<?=$rs[name_eng]?>" size="15" maxlength="30" class="box" /></td>
				<td><input type="text" name="rn[]" id="rn_<?=$j?>" value="<?=$rs[rn]?>" size="14" maxlength="14" class="box rn" style="<?=$rn_css?>"/></td>
				<td>
					<table>
						<tr>
							<td><input type="text" name="passport_no[]" id="passport_no_<?=$j?>" value="<?=$rs[passport_no]?>" size="12" maxlength="30" class="box" /></td>
							<td><a href="javascript:pop_passport(<?=$j?>)"><img id="plink_<?=$j?>" src="/renew/images/ez_board/ico_file0<?=($photo_rows)?'3':'2'?>.gif"></a></td>
						</tr>
					</table>
				</td>
				<td><input type="text" name="passport_limit[]" id="passport_limit_<?=$j?>" value="<?=$rs[passport_limit]?>" size="9" maxlength="30" class="box passport" onchange="passport_limit('passport_limit_<?=$j?>',this.value)"  style="color:<?=$limit_color?>"/></td>
				<td><input type="text" name="phone[]" id="phone_<?=$j?>" value="<?=$rs[phone]?>" size="13" maxlength="30" class="box" onchange="cell('phone_<?=$j?>',this.value)" /></td>
				<!-- <td><span class="btn_pack small bold"><a href="javascript:find2(<?=$j?>)"> 검색 </a></span></td> -->

				<td><input type="text" name="price[]" id="price_<?=$j?>" value="<?=nf($rs[price])?>" size="9" maxlength="11" class="box numberic" onkeyup="num('price_<?=$j?>',this.value)"/></td>
				<td>

					<table>
						<tr>
							<td><input type="text" name="price_air[]" id="price_air_<?=$j?>" value="<?=nf($rs[price_air])?>" size="9" maxlength="11" class="blue box numberic" onkeyup="num('price_air_<?=$j?>',this.value)"/></td>
							<td><a href="javascript:pop_files(1,'<?=$rs[id_no]?>','<?=$j?>')"><img id="pfile1_<?=$j?>" src="/renew/images/ez_board/ico_file0<?=($photo_rows2)?'3':'2'?>.gif"></a></td>
						</tr>
					</table>


				</td>
				<td><input type="text" name="date_out[]" id="date_out_<?=$j?>" value="<?=$rs[date_out]?>" size="10" maxlength="10" class="box dateinput" onkeyup="num('date_out_<?=$j?>',this.value)"/></td>

				<td>

					<table>
						<tr>
							<td><input type="text" name="price_land[]" id="price_land_<?=$j?>" value="<?=nf($rs[price_land])?>" size="9" maxlength="11" class="blue box numberic" onkeyup="num('price_land_<?=$j?>',this.value)"/></td>
							<td><a href="javascript:pop_files(2,'<?=$rs[id_no]?>','<?=$j?>')"><img id="pfile2_<?=$j?>" src="/renew/images/ez_board/ico_file0<?=($photo_rows3)?'3':'2'?>.gif"></a></td>
						</tr>
					</table>
				</td>



				<td><input type="text" name="date_out2[]" id="date_out2_<?=$j?>" value="<?=$rs[date_out2]?>" size="10" maxlength="10" class="box dateinput" onkeyup="num('date_out2_<?=$j?>',this.value)"/></td>
				<td><input type="text" name="price_prev[]" id="price_prev_<?=$j?>" value="<?=nf($rs[price_prev])?>" size="9" maxlength="11" class="red box numberic" onkeyup="num('price_prev_<?=$j?>',this.value)"/></td>
				<td><input type="text" name="date_in[]" id="date_in_<?=$j?>" value="<?=$rs[date_in]?>" size="10" maxlength="10" class="box dateinput" onkeyup="num('date_in_<?=$j?>',this.value)"/></td>
				<td><input type="text" name="price_prev2[]" id="price_prev2_<?=$j?>" value="<?=nf($rs[price_prev2])?>" size="9" maxlength="11" class="red box numberic" onkeyup="num('price_prev2_<?=$j?>',this.value)"/></td>
				<td><input type="text" name="date_in2[]" id="date_in2_<?=$j?>" value="<?=$rs[date_in2]?>" size="10" maxlength="10" class="box dateinput" onkeyup="num('date_in2_<?=$j?>',this.value)"/></td>
				<td><input type="text" name="price_last[]" id="price_last_<?=$j?>" value="<?=nf($rs[price_last])?>" size="9" maxlength="11" class="box numberic" style="border:0;" readonly  onkeyup="num('price_last_<?=$j?>',this.value)"/></td>

				<!-- <td><input type="text" name="memo[]" id="memo_<?=$j?>" value="<?=$rs[memo]?>" size="10" maxlength="30" class="box" /></td> -->
				<td><span class="btn_pack medium bold"><a href="#" onClick="drop(<?=$j?>)">X</a></span></td>
		</tr>
        <tr><td colspan="20" class="tblLine"></td></tr>
		<?
			$sum1+=$rs[price];
			$sum2+=$rs[price_air];
			$sum3+=$rs[price_land];
			$sum4+=$rs[price_prev];
			$sum5+=$rs[price_last];
			$sum6+=$rs[price_prev2];
		}
		?>
		</form>

		<tr>
			<th colspan="8" height="30">합계</th>
			<th><span class="sr"><?=nf($sum1)?></th>
			<th><span class="sr blue" style="padding-right:20px"><?=nf($sum2)?></th>
			<th class="sum"></th>
			<th><span class="sr red" style="padding-right:20px"><?=nf($sum3)?></th>
			<th class="sum"></th>
			<th><span class="sr blue"><?=nf($sum4)?></th>
			<th class="sum"></th>
			<th><span class="sr red"><?=nf($sum6)?></th>
			<th class="sum"></th>
			<th><span class="sr"><?=nf($sum5)?></span></th>
			<th class="sum"></th>
        <tr><td colspan="20" class="tblLine"></td></tr>
		</tr>

	</table>

		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
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