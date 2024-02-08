<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"고객별예약정보관리대장");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_reservation";
$MENU = "cmp_basic";
$TITLE = "고객별 예약 정보 관리 대장";
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
	$price_prev = rnf($price_prev);
	$price_customer_input = rnf($price_customer_input);
	$price_tmp_output = rnf($price_tmp_output);
	$price_last = $price-$price_customer_input;

	$sqlInsert="
	   insert into cmp_reservation (
		  code,
		  golf_name,
		  golf_id_no,
		  name,
		  customer_type,
		  phone,
		  view_path,
		  main_staff,
		  d_date,
		  r_date,
		  golf,
		  price,
		  price_customer_input,
		  price_tmp_output,
		  price_prev,
		  price_last,
		  pay_date,
		  pay_check,
		  pay_method,
		  bit_tax,
		  bit_cash,
		  bit_sending,
		  air_info,
		  people,
		  memo,
		  memo_payment,
		  air_id_no,
		  d_air_no,
		  r_air_no,
		  tour_date,
		  tl,
		  bsp,
		  golf_ball,
		  air_cover,
		  reg_date,
		  reg_date2
	  ) values (
		  '$code',
		  '$golf_name',
		  '$golf_id_no',
		  '$name',
		  '$customer_type',
		  '$phone',
		  '$view_path',
		  '$main_staff',
		  '$d_date',
		  '$r_date',
		  '$golf',
		  '$price',
		  '$price_customer_input',
		  '$price_tmp_output',
		  '$price_prev',
		  '$price_last',
		  '$pay_date',
		  '$pay_check',
		  '$pay_method',
		  '$bit_tax',
		  '$bit_cash',
		  '$bit_sending',
		  '$air_info',
		  '$people',
		  '$memo',
		  '$memo_payment',
		  '$air_id_no',
		  '$d_air_no',
		  '$r_air_no',
		  '$tour_date',
		  '$tl',
		  '$bsp',
		  '$golf_ball',
		  '$air_cover',
		  '$reg_date',
		  '$reg_date2'
	)";


	$sqlModify="
	   update cmp_reservation set
		  code = '$code',
		  golf_name = '$golf_name',
		  golf_id_no = '$golf_id_no',
		  name = '$name',
		  customer_type = '$customer_type',
		  phone = '$phone',
		  view_path = '$view_path',
		  main_staff = '$main_staff',
		  d_date = '$d_date',
		  r_date = '$r_date',
		  golf = '$golf',
		  price = '$price',
		  price_customer_input ='$price_customer_input',
		  price_tmp_output ='$price_tmp_output',
		  price_prev = '$price_prev',
		  price_last = '$price_last',
		  pay_date = '$pay_date',
		  pay_check = '$pay_check',
		  pay_method = '$pay_method',
		  bit_tax = '$bit_tax',
		  bit_cash = '$bit_cash',
		  bit_sending = '$bit_sending',
		  air_info = '$air_info',
		  people = '$people',
		  tour_date = '$tour_date',
		  tl='$tl',
		  bsp='$bsp',
		  golf_ball='$golf_ball',
		  air_cover='$air_cover',
		  air_id_no = '$air_id_no',
		  d_air_no = '$d_air_no',
		  r_air_no = '$r_air_no',
		  memo = '$memo',
		  memo_payment = '$memo_payment'
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

		$sql = "select * from cmp_qna where name='$name' and phone='$phone' ";
		list($rows2) = $dbo->query($sql);
		if(!$rows2){
			$sql="
			   insert into cmp_qna (
				  qdate,
				  name,
				  phone,
				  content,
				  reg_date,
				  reg_date2
			  ) values (
				  '$reg_date',
				  '$name',
				  '$phone',
				  '$memo',
				  '$reg_date',
				  '$reg_date2'
			)";
			$dbo->query($sql);
		}


		/*
		//인원 정보 입력
		$sql = "select count(*) as people from cmp_people where code=$code";
		$dbo->query($sql);
		$rs=$dbo->next_record();

		$sql = "update cmp_reservation set people=$rs[people] where code=$code";
		$dbo->query($sql);
		*/

		//If($link){redirect2($link);exit;}

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

		$sql = "delete from cmp_people where code = $check[$i]";
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
	$str = ($rows)? "선택하세요":"검색된 상품명이 없습니다.";
	echo "
		<select name='golf_tmp' id='golf_tmp' onchange=\"set_golf(this.options[this.selectedIndex].text,this.value)\">
		";
			echo option_str($str.$KEY,$VAL);
	echo "
		<option value='$golf'>$golf</option>
		</select>
	";
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
	if(check_blank(fm.golf,'검색할 상품명을',0)=='wrong'){return }

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

function set_golf(k,v){
	$("#golf_name").val(k);
	$("#golf_id_no").val(v);
}

function pop_win(){
	var fm = document.fmData;
	if(check_blank(fm.name,'대표자명을',0)=='wrong'){return }
	if(check_blank(fm.people,'총인원을',0)=='wrong'){return }
	if(fm.people.value==0){alert('총인원을 입력하세요.');fm.people.value='';fm.people.select();return }
	newWin('pop_reservation.php?code=<?=$code?>&people='+document.getElementById('people').value+'&leader='+document.getElementById('name').value,1500,500,1,1,'','pop_reservation');
}


function air_info(){
	var fm = document.fmData;
	if(fm.golf_id_no.value==""){alert("상품명을 먼저 선택하세요.");return }
	newWin('pop_air.php?golf_id_no='+fm.golf_id_no.value,1200,670,1,1,'','pop_air');
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

	newWin('pop_qnacustomer.php?page=origin&name='+name,850,400,1,1,'','customer')

}

function plist(){
	window.open('list_output.php?target=name&keyword='+document.getElementById('name').value);
}

jQuery(function($){

	$(".num").keypress(function(event){
		if(event.which && (event.which < 48 || event.which > 57)){
			event.preventDefault();
		}
	});


	$("#price_customer_input").on("keyup",function(){
		get_price_last();
	});

	$("#price").css("border","0");
	$("#price_last").css("border","0");


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

	$("#price_prev").on("change",function(){
		sum();
	});

	$("#price_customer_input").on("change",function(){
		sum();
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

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td class="subject" width="15%">대표자명</td>
          <td>
	           <?=html_input('name',20,50)?> <span class="btn_pack medium bold"><a href="javascript:find()"> 검색 </a></span>
          </td>

          <td class="subject" width="17%">대표자 연락처</td>
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
			   <?=option_str($VIEW_PATH,$VIEW_PATH,$rs[view_path])?>
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
          <td class="subject">예약일</td>
          <td colspan="3">
	           <?=html_input('tour_date',13,10,'box dateinput')?>
			   <!-- <span class="btn_pack medium bold"><a href="javascript:plist()"> 출력양식 </a></span> -->

				<?if($rs[id_no]){?>
				<span class="btn_pack medium bold"><a href="javascript:newWin('form01.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation')">예약요청서 </a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="javascript:newWin('form02.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation')">출발 확정서</a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="javascript:newWin('form03.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation')">출발 안내문</a></span>&nbsp;
				<span class="btn_pack medium bold"><a href="javascript:newWin('form04.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation')">샌딩 의뢰서</a></span>
				<?}?>

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
          <td class="subject">고객정보</td>
          <td>
			   <?=html_input('people',3,3,'box num numberic')?>명  &nbsp;&nbsp;
	           <span class="btn_pack medium bold"><a href="javascript:pop_win()"> 고객정보</a></span>
          </td>
          <td class="subject">TL/BSP</td>
          <td>
		 	TL(TIME LIMIT) : <?=html_input('tl',13,10,'box dateinput')?>
		 	BSP : <?=html_input('bsp',20,45)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">성향</td>
          <td colspan="3">
			   <select name="customer_type" id="customer_type">
				<option value="">선택</option>
				<?=option_str($CUSTOMER_TYPE,$CUSTOMER_TYPE,$rs[customer_type])?>
			   </select>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">판매가</td>
          <td>
	           <?=html_input('price',13,10,'box readonly')?>
          </td>

          <td class="subject">잔금</td>
          <td>
	           <?=html_input('price_last',13,10,'box readonly')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">계약금</td>
          <td>
	           <?=html_input('price_prev',13,10,'box num numberic')?>
          </td>

          <td class="subject">잔금입금일</td>
          <td>
	           <?=html_input('pay_date',14,10,'box dateinput')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">고객입금액</td>
          <td>
	           <?=html_input('price_customer_input',13,10,'box num numberic')?>
          </td>

          <td class="subject">가지급금</td>
          <td>
			   <?=html_input('price_tmp_output',13,10,'box num numberic')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

<!--
        <tr>
          <td class="subject">항공요금</td>
          <td>
	           <?=html_input('price_air',30,50)?>
          </td>
          <td class="subject">지상비</td>
          <td>
	           <?=html_input('price_randing',30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
-->

        <tr>
          <td class="subject">결제정보</td>
          <td>
	           <select name="pay_method">
	           <option value="">선택</option>
				<?=option_str($PAY_METHOD,$PAY_METHOD,$rs[pay_method])?>
			   </select>

	           <select name="pay_check">
	           <option value="">입금확인여부</option>
				<?=option_str($YN,$YN,$rs[pay_check])?>
			   </select>

          </td>

          <td class="subject">골프공/항공카버</td>
          <td>

			    <!--
				세금계산서 :
				<?if(!$rs[id_no]) $rs[bit_tax]="N";?>
				<select name="bit_tax">
					<?=option_str($YN,$YN,$rs[bit_tax])?>
				</select>

				현금영수증 :
				<?if(!$rs[id_no]) $rs[bit_cash]="N";?>
				<select name="bit_cash">
					<?=option_str($YN,$YN,$rs[bit_cash])?>
				</select>
				//-->

				골프공 :
				<select name="golf_ball">
					<?=option_int(0,50,1,$rs[golf_ball])?>
				</select>

				항공카버 :
				<select name="air_cover">
					<?=option_int(0,50,1,$rs[air_cover])?>
				</select>

          </td>
        </tr>

        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">항공정보</td>
          <td>
	           <span id="air_info"><?if($rs[d_air_no] || $rs[r_date_no]){?>출국편명:<?=$rs[d_air_no]?>,귀국편명:<?=$rs[r_air_no]?><?}?></span> <span class="btn_pack medium bold"><a href="javascript:air_info()"> 항공정보 </a></span>
          </td>

          <td class="subject">샌딩여부</td>
          <td>
	           <select name="bit_sending">
	           <option value="">선택</option>
				<?=option_str($YN,$YN,$rs[bit_sending])?>
			   </select>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">관리자 메모</td>
          <td colspan="3">
            <?=html_textarea('memo',0,12)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">입금내역</td>
          <td colspan="3">
            <?=html_textarea('memo_payment',0,12)?>
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