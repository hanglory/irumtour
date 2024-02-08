<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"견적서관리대장");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_estimate";
$MENU = "cmp_basic";
$TITLE = "견적서 관리 대장";
$file_rows = 1; //파일 업로드 갯수

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

	$price = rnf($price);

	$sqlInsert="
		insert into cmp_estimate (
		   staff,
		   code,
		   golf_name,
		   golf_id_no,
		   air_id_no,
		   d_air_no,
		   r_air_no,
		   name,
		   phone,
		   view_path,
		   main_staff,
		   d_date,
		   r_date,
		   send_date,
		   golf,
			golf2_1_id_no,
			golf2_2_id_no,
			golf2_3_id_no,
			golf2_4_id_no,
			golf2_1_name,
			golf2_2_name,
			golf2_3_name,
			golf2_4_name,
			golf2_1,
			golf2_2,
			golf2_3,
			golf2_4,
		   hotel,
		   hotel_id_no,
		   hotel_name,
		   room_type,
		   people,
		   price,
		   account,
		   email,
		   fax,
		   memo,
		   reg_date,
		   reg_date2
	   ) values (
		   '$staff',
		   '$code',
		   '$golf_name',
		   '$golf_id_no',
		   '$air_id_no',
		   '$d_air_no',
		   '$r_air_no',
		   '$name',
		   '$phone',
		   '$view_path',
		   '$main_staff',
		   '$d_date',
		   '$r_date',
		   '$send_date',
		   '$golf',
		   '$golf2_1_id_no',
		   '$golf2_2_id_no',
		   '$golf2_3_id_no',
		   '$golf2_4_id_no',
		   '$golf2_1_name',
		   '$golf2_2_name',
		   '$golf2_3_name',
		   '$golf2_4_name',
		   '$golf2_1',
		   '$golf2_2',
		   '$golf2_3',
		   '$golf2_4',
		   '$hotel',
		   '$hotel_id_no',
		   '$hotel_name',
		   '$room_type',
		   '$people',
		   '$price',
		   '$account',
		   '$email',
		   '$fax',
		   '$memo',
		   '$reg_date',
		   '$reg_date2'
	 )";


	 $sqlModify="
		update cmp_estimate set
		   staff = '$staff',
		   golf_name = '$golf_name',
		   golf_id_no = '$golf_id_no',
		   air_id_no = '$air_id_no',
		   d_air_no = '$d_air_no',
		   r_air_no = '$r_air_no',
		   name = '$name',
		   phone = '$phone',
		   view_path = '$view_path',
		   main_staff = '$main_staff',
		   d_date = '$d_date',
		   r_date = '$r_date',
		   send_date = '$send_date',
		   golf = '$golf',
		   golf2_1_id_no='$golf2_1_id_no',
		   golf2_2_id_no='$golf2_2_id_no',
		   golf2_3_id_no='$golf2_3_id_no',
		   golf2_4_id_no='$golf2_4_id_no',
		   golf2_1_name='$golf2_1_name',
		   golf2_2_name='$golf2_2_name',
		   golf2_3_name='$golf2_3_name',
		   golf2_4_name='$golf2_4_name',
		   golf2_1='$golf2_1',
		   golf2_2='$golf2_2',
		   golf2_3='$golf2_3',
		   golf2_4='$golf2_4',
		   hotel = '$hotel',
		   hotel_id_no='$hotel_id_no',
		   hotel_name='$hotel_name',
		   room_type='$room_type',
		   people = '$people',
		   price = '$price',
		   account = '$account',
		   email = '$email',
		   fax = '$fax',
		   memo = '$memo'
		where id_no='$id_no'
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

		If($id_no) echo"<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();location.href='$url'</script>";
		Else echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();self.close()</script>";

	}else{
		checkVar(mysql_error(),$sql);
	}
	exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){

		$sql = "delete from $table where code = $check[$i]";
		$dbo->query($sql);

	}
	redirect2("list_${filecode}.php?ctg1=$ctg1");exit;

}elseif($mode=="golf"){

	$golf = trim($golf);
	$sql = "select * from cmp_golf where name like '%$golf%' order by nation asc,city asc, name asc";
	list($rows) = $dbo->query($sql);
	while($rs=$dbo->next_record()){
		$KEY .= ",$rs[nation] > $rs[city] > $rs[name]";
		$VAL .= ",".$rs[id_no];
	}
	$str = ($rows)? "선택하세요":"검색된 상품명 없습니다.";
	echo "
		<select name='golf_tmp' id='golf_tmp' onchange=\"set_golf(this.options[this.selectedIndex].text,this.value)\">
		";
			echo option_str($str.$KEY,$VAL);
	echo "
		<option value='$golf'>$golf</option>
		</select>
	";
	exit;

}elseif($mode=="golf2"){

	$golf = trim($golf);
	$sql = "select * from cmp_golf2 where name like '%$golf%' order by nation asc,city asc, name asc";
	list($rows) = $dbo->query($sql);
	while($rs=$dbo->next_record()){
		$KEY .= ",$rs[nation] > $rs[city] > $rs[name]";
		$VAL .= ",".$rs[id_no];
	}
	$str = ($rows)? "선택하세요":"검색된 골프장이 없습니다.";
	echo "
		<select name='${id}_tmp' id='${id}_tmp' onchange=\"set_golf2('$id',this.options[this.selectedIndex].text,this.value)\">
		";
			echo option_str($str.$KEY,$VAL);
	echo "
		<option value='$golf'>$golf</option>
		</select>
	";
	exit;

}elseif($mode=="hotel"){

	$hotel = trim($hotel);
	$sql = "select * from cmp_hotel where name like '%$hotel%' order by nation asc,city asc, name asc";
	list($rows) = $dbo->query($sql);
	while($rs=$dbo->next_record()){
		$KEY .= ",$rs[nation] > $rs[city] > $rs[name]";
		$VAL .= ",".$rs[id_no];
	}
	$str = ($rows)? "선택하세요":"검색된 상품명 없습니다.";
	echo "
		<select name='hotel_tmp' id='hotel_tmp' onchange=\"set_hotel(this.options[this.selectedIndex].text,this.value)\">
		";
			echo option_str($str.$KEY,$VAL);
	echo "
		<option value='$hotel'>$hotel</option>
		</select>
	";
	exit;

}elseif($mode=="etc"){

	$sql = "select * from cmp_golf where id_no=$golf_id_no";
	$dbo->query($sql);
	$rs=$dbo->next_record();

	$arr  =explode(">",$rs[golf2_1_name]);	$rs[golf2_1] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_2_name]);	$rs[golf2_2] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_3_name]);	$rs[golf2_3] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_4_name]);	$rs[golf2_4] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[hotel_name]);	$rs[hotel] = trim($arr[count($arr)-1]);

	echo "<script>
			parent.document.getElementById('golf2_1_name').value='$rs[golf2_1_name]';
			parent.document.getElementById('golf2_1_id_no').value='$rs[golf2_1_id_no]';
			parent.document.getElementById('golf2_2_name').value='$rs[golf2_2_name]';
			parent.document.getElementById('golf2_2_id_no').value='$rs[golf2_2_id_no]';
			parent.document.getElementById('golf2_3_name').value='$rs[golf2_3_name]';
			parent.document.getElementById('golf2_3_id_no').value='$rs[golf2_3_id_no]';
			parent.document.getElementById('golf2_4_name').value='$rs[golf2_4_name]';
			parent.document.getElementById('golf2_4_id_no').value='$rs[golf2_4_id_no]';
			parent.document.getElementById('hotel_name').value='$rs[hotel_name]';
			parent.document.getElementById('hotel_id_no').value='$rs[hotel_id_no]';

			parent.document.getElementById('golf2_1').value='$rs[golf2_1]';
			parent.document.getElementById('golf2_2').value='$rs[golf2_2]';
			parent.document.getElementById('golf2_3').value='$rs[golf2_3]';
			parent.document.getElementById('golf2_4').value='$rs[golf2_4]';
			parent.document.getElementById('hotel').value='$rs[hotel]';
		</script>";

	exit;

}elseif($mode=="price"){

	$price_customer_input = nf(rnf($price_customer_input));
	$price_last = nf(rnf($price) - rnf($price_customer_input));
	echo "
		<script>
			parent.document.getElementById('price_customer_input').value='$price_customer_input';
			parent.document.getElementById('price_last').value='$price_last';
		</script>
	";
	exit;

}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
	$code=$rs[code];

	$rs[price] = nf($rs[price]);
	$rs[price_prev] = nf($rs[price_prev]);
	$rs[price_customer_input] = nf($rs[price_customer_input]);
	$rs[price_tmp_output] = nf($rs[price_tmp_output]);
	$rs[price_last] = nf($rs[price_last]);
	$arr  =explode(">",$rs[golf_name]);
	$rs[golf] = trim($arr[count($arr)-1]);

	$arr  =explode(">",$rs[golf2_1_name]);	$rs[golf2_1] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_2_name]);	$rs[golf2_2] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_3_name]);	$rs[golf2_3] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_4_name]);	$rs[golf2_4] = trim($arr[count($arr)-1]);

	$arr  =explode(">",$rs[hotel_name]);	$rs[hotel] = trim($arr[count($arr)-1]);


}

$code = ($code)? $code : getUniqNo();
$rs[main_staff] =($rs[main_staff])?$rs[main_staff]:"$sessLogin[name] ($sessLogin[id])";
?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;

	if(check_blank(fm.name,'고객명을',0)=='wrong'){return }
	fm.submit();

}

function find_golf(){
	var fm = document.fmData;
	var golf = $("#golf").val();
	if(check_blank(fm.golf,'검색할 상품명 명을',0)=='wrong'){return }

	$.ajax({
	  url: "<?=SELF?>",
	  type: "POST",
	  data: {
		'mode': 'golf',
		'golf': golf
	  },
	  success: function(data) {
		$("#golf_wrap").html(data);
	  }
	});
	$("#golf").val('');
}

function find_golf2(id){
	var fm = document.fmData;
	var golf = $("#"+id).val();

	if(golf==""){alert('골프장명을 입력해 주세요.');$("#"+id).focus(); return; }

	$.ajax({
	  url: "<?=SELF?>",
	  type: "POST",
	  data: {
		'mode': 'golf2',
		'id': id,
		'golf': golf
	  },
	  success: function(data) {
		$("#"+id+"_wrap").html(data);
	  }
	});
	$("#"+id).val('');
}

function find_hotel(){
	var fm = document.fmData;
	var hotel = $("#hotel").val();
	if(check_blank(fm.hotel,'검색할 호텔명 명을',0)=='wrong'){return }

	$.ajax({
	  url: "<?=SELF?>",
	  type: "POST",
	  data: {
		'mode': 'hotel',
		'hotel': hotel
	  },
	  success: function(data) {
		$("#hotel_wrap").html(data);
	  }
	});
	$("#hotel").val('');
}

function set_golf(k,v){
	$("#golf_name").val(k);
	$("#golf_id_no").val(v);

	$('#actarea').load('<?=SELF?>', {
		'mode': 'etc',
		'golf_id_no': v
	});

}

function set_golf2(id,k,v){
	$("#"+id+"_name").val(k);
	$("#"+id+"_id_no").val(v);
}

function set_hotel(k,v){
	$("#hotel_name").val(k);
	$("#hotel_id_no").val(v);
}

function pop_win(){
	var fm = document.fmData;
	if(check_blank(fm.people,'총인원을',0)=='wrong'){return }
	if(fm.people.value==0){alert('총인원을 입력하세요.');fm.people.value='';fm.people.select();return }
	newWin('pop_estimate.php?code=<?=$code?>&people='+document.getElementById('people').value,1200,500,1,1,'','pop_estimate');
}


function air_info(){
	var fm = document.fmData;
	if(fm.golf_id_no.value==""){alert("상품명을 먼저 선택하세요.");return }
	newWin('pop_air.php?golf_id_no='+fm.golf_id_no.value,1200,670,1,1,'','pop_air');
}

function etc_info(){
	var fm = document.fmData;
	if(fm.golf_id_no.value==""){alert("상품명을 먼저 선택하세요.");return }
	newWin('pop_etc.php?golf_id_no='+fm.golf_id_no.value,1200,670,1,1,'','pop_air');
}

function get_price_last(){
	var price=$("#price").val();
	var price_prev=$("#price_prev").val();
	var price_customer_input=$("#price_customer_input").val();
	var url ="<?=SELF?>?mode=price";
	url += "&price=" + price;
	url += "&price_prev=" + price_prev;
	url += "&price_customer_input=" + price_customer_input;
	actarea.location.href=url;

}

function find(){

	var name = $("#name").val();
	if(name==""){alert("고객명을 입력하세요.");$("#name").focus();return;}

	newWin('pop_qnacustomer2.php?page=origin&name='+name,950,400,1,1,'','customer')

}

jQuery(function($){

	$(".num").keypress(function(event){
		if(event.which && (event.which < 48 || event.which > 57)){
			event.preventDefault();
		}
	});

	$("#golf2_1").on("change",function(){if(this.value==""){$("#golf2_1_name").val('');$("#golf2_1_id_no").val('')}});
	$("#golf2_2").on("change",function(){if(this.value==""){$("#golf2_2_name").val('');$("#golf2_2_id_no").val('')}});
	$("#golf2_3").on("change",function(){if(this.value==""){$("#golf2_3_name").val('');$("#golf2_3_id_no").val('')}});
	$("#golf2_4").on("change",function(){if(this.value==""){$("#golf2_4_name").val('');$("#golf2_4_id_no").val('')}});
	$("#hotel").on("change",function(){if(this.value==""){$("#hotel_name").val('');$("#hotel_id_no").val('')}});



	$( "#d_date" ).datepicker({
	  dateFormat: "yy/mm/dd",
	  defaultDate: "+1w",
	  changeMonth: true,
	  numberOfMonths: 3,
	  onClose: function( selectedDate ) {
		$( "#r_date" ).datepicker( "option", "minDate", selectedDate );
	  }
	});
	$( "#r_date" ).datepicker({
	  dateFormat: "yy/mm/dd",
	  defaultDate: "+1w",
	  changeMonth: true,
	  numberOfMonths: 2,
	  onClose: function( selectedDate ) {
		$( "#d_date" ).datepicker( "option", "maxDate", selectedDate );
	  }
	});

	$('#name').keypress(function(e){
		if(e.which == 13) find();
	});

	$('#golf').keypress(function(e){
		if(e.which == 13) find_golf();
	});

	$('#golf2_1').keypress(function(e){
		if(e.which == 13) find_golf2('golf2_1');
	});

	$('#golf2_2').keypress(function(e){
		if(e.which == 13) find_golf2('golf2_2');
	});

	$('#golf2_3').keypress(function(e){
		if(e.which == 13) find_golf2('golf2_3');
	});

	$('#golf2_4').keypress(function(e){
		if(e.which == 13) find_golf2('golf2_4');
	});

	$('#hotel').keypress(function(e){
		if(e.which == 13) find_hotel();
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
		<input type="hidden" name="code" value='<?=$code?>'>
		<input type="hidden" name="golf_name" id="golf_name" value='<?=$rs[golf_name]?>'>
		<input type="hidden" name="golf_id_no" id="golf_id_no" value='<?=$rs[golf_id_no]?>'>
		<input type="hidden" name="air_id_no" id="air_id_no" value='<?=$rs[air_id_no]?>'>
		<input type="hidden" name="d_air_no" id="d_air_no" value='<?=$rs[d_air_no]?>'>
		<input type="hidden" name="r_air_no" id="r_air_no" value='<?=$rs[r_air_no]?>'>

		<input type="hidden" name="golf2_1_name" id="golf2_1_name" value='<?=$rs[golf2_1_name]?>'>
		<input type="hidden" name="golf2_1_id_no" id="golf2_1_id_no" value='<?=$rs[golf2_1_id_no]?>'>
		<input type="hidden" name="golf2_2_name" id="golf2_2_name" value='<?=$rs[golf2_2_name]?>'>
		<input type="hidden" name="golf2_2_id_no" id="golf2_2_id_no" value='<?=$rs[golf2_2_id_no]?>'>
		<input type="hidden" name="golf2_3_name" id="golf2_3_name" value='<?=$rs[golf2_3_name]?>'>
		<input type="hidden" name="golf2_3_id_no" id="golf2_3_id_no" value='<?=$rs[golf2_3_id_no]?>'>
		<input type="hidden" name="golf2_4_name" id="golf2_4_name" value='<?=$rs[golf2_4_name]?>'>
		<input type="hidden" name="golf2_4_id_no" id="golf2_4_id_no" value='<?=$rs[golf2_4_id_no]?>'>

		<input type="hidden" name="hotel_name" id="hotel_name" value='<?=$rs[hotel_name]?>'>
		<input type="hidden" name="hotel_id_no" id="hotel_id_no" value='<?=$rs[hotel_id_no]?>'>

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td class="subject">고객명</td>
          <td>
	           <?=html_input('name',20,50)?> <span class="btn_pack medium bold"><a href="javascript:find()"> 검색 </a></span>
          </td>

          <td class="subject">연락처</td>
          <td>
	           <?=html_input('phone',30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">경로</td>
          <td>
			   <select name="view_path">
	           <option value="">선택</option>
			   <?=option_str($VIEW_PATH_ESTI,$VIEW_PATH_ESTI,$rs[view_path])?>
			   </select>
          </td>

          <td class="subject">담당자</td>
          <td>
	           <select name="main_staff">
				<?=option_str("선택".$STAFF,$STAFF,$rs[main_staff])?>
			   </select>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">이메일</td>
          <td>
	           <?=html_input('email',30,40,'box ')?>
          </td>

          <td class="subject">팩스</td>
          <td>
	            <?=html_input('fax',30,40,'box ')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">출국일</td>
          <td>
	           <?=html_input('d_date',13,10,'box ')?>
          </td>

          <td class="subject">귀국일</td>
          <td>
	            <?=html_input('r_date',13,10,'box ')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">발송일</td>
          <td colspan="3">
	           <?=html_input('send_date',13,10,'box dateinput')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject">상품명</td>
          <td colspan="3">
			   <span id="golf_wrap"></span>
	           <?=html_input('golf',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf()"> 검색 </a></span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">골프장명</td>
          <td colspan="3">
			   <span id="golf2_1_wrap"></span>
	           <?=html_input('golf2_1',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_1')"> 검색 </a></span><br/>
			   <span id="golf2_2_wrap"></span>
			   <?=html_input('golf2_2',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_2')"> 검색 </a></span><br/>
			   <span id="golf2_3_wrap"></span>
	           <?=html_input('golf2_3',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_3')"> 검색 </a></span><br/>
			   <span id="golf2_4_wrap"></span>
			   <?=html_input('golf2_4',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_4')"> 검색 </a></span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">호텔명</td>
          <td colspan="3">
			   <span id="hotel_wrap"></span>
	           <?=html_input('hotel',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_hotel()"> 검색 </a></span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">룸타입</td>
          <td colspan="3">
			   <?=radio($ROOM_TYPE,$ROOM_TYPE,$rs[room_type],'room_type')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">인원</td>
          <td colspan="3">

			   <?=html_input('people',3,3,'box num numberic')?>명
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">판매가</td>
          <td colspan="3">
	           <?=html_input('price',13,10,'box num')?>
          </td>


        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">항공정보</td>
          <td colspan="3">
	           <span id="air_info"><?if($rs[d_air_no] || $rs[r_date_no]){?>출국편명:<?=$rs[d_air_no]?>,귀국편명:<?=$rs[r_air_no]?><?}?></span> <span class="btn_pack medium bold"><a href="javascript:air_info()"> 항공정보 </a></span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <!-- <tr>
          <td class="subject">참고사항</td>
          <td colspan="3">
	           <?=html_textarea('etc',0,5)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr> -->

        <tr>
          <td class="subject">관리자 메모</td>
          <td colspan="3">
            <?=html_textarea('memo',0,5)?>
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