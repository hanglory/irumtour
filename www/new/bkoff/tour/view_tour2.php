<?
include_once("../include/common_file.php");


if($golf_name){
	chkVars();
}


if($mode=="golf"){

	$golf = trim($golf);
	$sql = "select * from cmp_golf where name like '%$golf%' order by nation asc,city asc, name asc";
	list($rows) = $dbo->query($sql);
	while($rs=$dbo->next_record()){
		$KEY .= ",$rs[nation] > $rs[city] > $rs[name]";
		$VAL .= ",".$rs[id_no];
	}
	$str = ($rows)? "선택하세요":"검색된 상품명 없습니다.";
	echo "
		<select name='golf_tmp' id='golf_tmp' onchange=\"set_golf(this.options[this.selectedIndex].text,this.value)\" style='width:180px'>
		";
			echo option_str($str.$KEY,$VAL);
	echo "
		</select>
	";
	exit;

}elseif($mode=="golf2"){

	$golf = str_replace(" ","",trim($golf));
	$sql = "select * from cmp_golf2 where replace(name,' ','') like '%$golf%' order by nation asc,city asc, name asc";
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
		</select>
	";
	exit;

}elseif($mode=="hotel"){

	$hotel = str_replace(" ","",trim($hotel));
	$sql = "select * from cmp_hotel where replace(name,' ','') like '%$hotel%' order by nation asc,city asc, name asc";
	list($rows) = $dbo->query($sql);
	while($rs=$dbo->next_record()){
		$KEY .= ",$rs[nation] > $rs[city] > $rs[name]";
		$VAL .= ",".$rs[id_no];
	}
	$str = ($rows)? "선택하세요":"검색된 상품명 없습니다.";

	$set_hotel = ($bit)? "set_hotel2":"set_hotel";

	echo "
		<select name='hotel_tmp' id='hotel_tmp' onchange=\"${set_hotel}(this.options[this.selectedIndex].text,this.value)\">
		";
			echo option_str($str.$KEY,$VAL);
	echo "
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
	$arr  =explode(">",$rs[golf2_5_name]);	$rs[golf2_5] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_6_name]);	$rs[golf2_6] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[hotel_name]);	$rs[hotel] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[hotel2_name]);	$rs[hotel2] = trim($arr[count($arr)-1]);

	echo "<script>
			parent.document.getElementById('golf2_1_name').value='$rs[golf2_1_name]';
			parent.document.getElementById('golf2_1_id_no').value='$rs[golf2_1_id_no]';
			parent.document.getElementById('golf2_2_name').value='$rs[golf2_2_name]';
			parent.document.getElementById('golf2_2_id_no').value='$rs[golf2_2_id_no]';
			parent.document.getElementById('golf2_3_name').value='$rs[golf2_3_name]';
			parent.document.getElementById('golf2_3_id_no').value='$rs[golf2_3_id_no]';
			parent.document.getElementById('golf2_4_name').value='$rs[golf2_4_name]';
			parent.document.getElementById('golf2_4_id_no').value='$rs[golf2_4_id_no]';

			parent.document.getElementById('golf2_5_name').value='$rs[golf2_4_name]';
			parent.document.getElementById('golf2_5_id_no').value='$rs[golf2_4_id_no]';

			parent.document.getElementById('golf2_6_name').value='$rs[golf2_4_name]';
			parent.document.getElementById('golf2_6_id_no').value='$rs[golf2_4_id_no]';

			parent.document.getElementById('hotel_name').value='$rs[hotel_name]';
			parent.document.getElementById('hotel_id_no').value='$rs[hotel_id_no]';
			parent.document.getElementById('hotel2_name').value='$rs[hotel2_name]';
			parent.document.getElementById('hotel2_id_no').value='$rs[hotel2_id_no]';

			parent.document.getElementById('golf2_1').value='$rs[golf2_1]';
			parent.document.getElementById('golf2_2').value='$rs[golf2_2]';
			parent.document.getElementById('golf2_3').value='$rs[golf2_3]';
			parent.document.getElementById('golf2_4').value='$rs[golf2_4]';
			parent.document.getElementById('golf2_5').value='$rs[golf2_5]';
			parent.document.getElementById('golf2_6').value='$rs[golf2_6]';
			parent.document.getElementById('hotel').value='$rs[hotel]';
			parent.document.getElementById('hotel2').value='$rs[hotel2]';
		</script>";

	exit;

}
?>
<?include("../top_min.html");?>
<script language="JavaScript">


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

function find_hotel(bit){
	bit = (bit==2)? bit : "";
	var fm = document.fmData;
	var hotel = $("#hotel" + bit).val();
	if(bit==2){if(check_blank(fm.hotel2,'검색할 호텔명 명을',0)=='wrong'){return }}
	else{if(check_blank(fm.hotel,'검색할 호텔명 명을',0)=='wrong'){return }}

	$.ajax({
	  url: "<?=SELF?>",
	  type: "POST",
	  data: {
		'mode': 'hotel',
		'hotel': hotel,
		'bit': bit
	  },
	  success: function(data) {
		$("#hotel_wrap"+bit).html(data);
	  }
	});
	$("#hotel"+bit).val('');
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

function set_hotel2(k,v){
	$("#hotel2_name").val(k);
	$("#hotel2_id_no").val(v);
}


function air_info(){
	var fm = document.fmData;
	if(fm.golf_id_no.value==""){alert("상품명을 먼저 선택하세요.");return }
	newWin('../cmp/pop_air.php?golf_id_no='+fm.golf_id_no.value,1200,670,1,1,'','pop_air');
}


jQuery(function($){


	$("#golf2_1").on("change",function(){if(this.value==""){$("#golf2_1_name").val('');$("#golf2_1_id_no").val('')}});
	$("#golf2_2").on("change",function(){if(this.value==""){$("#golf2_2_name").val('');$("#golf2_2_id_no").val('')}});
	$("#golf2_3").on("change",function(){if(this.value==""){$("#golf2_3_name").val('');$("#golf2_3_id_no").val('')}});
	$("#golf2_4").on("change",function(){if(this.value==""){$("#golf2_4_name").val('');$("#golf2_4_id_no").val('')}});
	$("#golf2_5").on("change",function(){if(this.value==""){$("#golf2_5_name").val('');$("#golf2_5_id_no").val('')}});
	$("#golf2_6").on("change",function(){if(this.value==""){$("#golf2_6_name").val('');$("#golf2_6_id_no").val('')}});
	$("#hotel").on("change",function(){if(this.value==""){$("#hotel_name").val('');$("#hotel_id_no").val('')}});
	$("#hotel2").on("change",function(){if(this.value==""){$("#hotel2_name").val('');$("#hotel2_id_no").val('')}});



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

	$('#golf2_5').keypress(function(e){
		if(e.which == 13) find_golf2('golf2_5');
	});

	$('#golf2_6').keypress(function(e){
		if(e.which == 13) find_golf2('golf2_6');
	});

	$('#hotel').keypress(function(e){
		if(e.which == 13) find_hotel();
	});


});
</script>


<div style="padding:0 10px 0 10px">


    <table border="0" cellspacing="1" cellpadding="3" width="97%">
		<form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">

		<input type="1hidden" name="golf_name" id="golf_name" value='<?=$rs[golf_name]?>'>
		<input type="1hidden" name="golf_id_no" id="golf_id_no" value='<?=$rs[golf_id_no]?>'>
		<input type="1hidden" name="air_id_no" id="air_id_no" value='<?=$rs[air_id_no]?>'>
		<input type="1hidden" name="d_air_no" id="d_air_no" value='<?=$rs[d_air_no]?>'>
		<input type="1hidden" name="r_air_no" id="r_air_no" value='<?=$rs[r_air_no]?>'>

		<input type="1hidden" name="d_air_time1" id="d_air_time1" value='<?=$rs[d_air_time1]?>'>
		<input type="1hidden" name="d_air_time2" id="d_air_time2" value='<?=$rs[d_air_time2]?>'>
		<input type="1hidden" name="r_air_time1" id="r_air_time1" value='<?=$rs[r_air_time1]?>'>
		<input type="1hidden" name="r_air_time2" id="r_air_time2" value='<?=$rs[r_air_time2]?>'>

		<input type="1hidden" name="golf2_1_name" id="golf2_1_name" value='<?=$rs[golf2_1_name]?>'>
		<input type="1hidden" name="golf2_1_id_no" id="golf2_1_id_no" value='<?=$rs[golf2_1_id_no]?>'>
		<input type="1hidden" name="golf2_2_name" id="golf2_2_name" value='<?=$rs[golf2_2_name]?>'>
		<input type="1hidden" name="golf2_2_id_no" id="golf2_2_id_no" value='<?=$rs[golf2_2_id_no]?>'>
		<input type="1hidden" name="golf2_3_name" id="golf2_3_name" value='<?=$rs[golf2_3_name]?>'>
		<input type="1hidden" name="golf2_3_id_no" id="golf2_3_id_no" value='<?=$rs[golf2_3_id_no]?>'>
		<input type="1hidden" name="golf2_4_name" id="golf2_4_name" value='<?=$rs[golf2_4_name]?>'>
		<input type="1hidden" name="golf2_4_id_no" id="golf2_4_id_no" value='<?=$rs[golf2_4_id_no]?>'>

		<input type="1hidden" name="golf2_5_name" id="golf2_5_name" value='<?=$rs[golf2_5_name]?>'>
		<input type="1hidden" name="golf2_5_id_no" id="golf2_5_id_no" value='<?=$rs[golf2_5_id_no]?>'>
		<input type="1hidden" name="golf2_6_name" id="golf2_6_name" value='<?=$rs[golf2_6_name]?>'>
		<input type="1hidden" name="golf2_6_id_no" id="golf2_6_id_no" value='<?=$rs[golf2_6_id_no]?>'>


		<input type="1hidden" name="d_air_id_no" id="d_air_id_no" value='<?=$rs[d_air_id_no]?>'>
		<input type="1hidden" name="r_air_id_no" id="r_air_id_no" value='<?=$rs[r_air_id_no]?>'>

		<input type="1hidden" name="hotel_name" id="hotel_name" value='<?=$rs[hotel_name]?>'>
		<input type="1hidden" name="hotel_id_no" id="hotel_id_no" value='<?=$rs[hotel_id_no]?>'>

		<input type="1hidden" name="hotel2_name" id="hotel2_name" value='<?=$rs[hotel2_name]?>'>
		<input type="1hidden" name="hotel2_id_no" id="hotel2_id_no" value='<?=$rs[hotel2_id_no]?>'>


		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td class="subject">상품명</td>
          <td colspan="3">
			   <span id="golf_wrap"></span>
	           <?=html_input('golf',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf()"> 검색 </a></span>

			   <?if($rs[golf_id_no]){?>
			   &nbsp;&nbsp;&nbsp;
			   (상품코드 : <a href="javascript:newWin('view_golf.php?id_no=<?=$rs[golf_id_no]?>',870,700,1,1,'golf')"><?=$rs[golf_id_no]?></a>)
			   <?}?>
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
			   <?=html_input('golf2_4',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_4')"> 검색 </a></span><br/>

			   <span id="golf2_5_wrap"></span>
			   <?=html_input('golf2_5',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_5')"> 검색 </a></span><br/>
			   <span id="golf2_6_wrap"></span>
			   <?=html_input('golf2_6',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_6')"> 검색 </a></span>
		  </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">호텔명</td>
          <td colspan="3">
			   <span id="hotel_wrap"></span>
	           <?=html_input('hotel',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_hotel()"> 검색 </a></span>


			   &nbsp;<?=html_input('hotel_days',30,50)?>일차 (콤마로 구분)


			   <br/>
			   <span id="hotel_wrap2"></span>
	           <?=html_input('hotel2',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_hotel(2)"> 검색 </a></span>

			   &nbsp;<?=html_input('hotel_days2',30,50)?>일차

          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">일정표 TYPE</td>
          <td colspan="3">
			   <?=radio($PLAN_TYPE1,$PLAN_TYPE2,$rs[plan_type],'plan_type')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject"><span class="btn_pack medium bold"><a href="javascript:air_info()"> 항공정보 </a></span></td>
          <td colspan="3">
	           <div id="air_info"><?if($rs[d_air_no]){?>▶출국편명:<?=$rs[d_air_no]?> (출발시간:<?=$rs[d_air_time1]?> / 도착시간:<?=$rs[d_air_time2]?>)<?}?> <?if($REMOTE_ADDR=="106.246.54.27"){?>(ID_NO:<?=$rs[d_air_id_no]?>)<?}?></div>
	           <div id="air_info2"><?if($rs[r_air_no]){?>▶귀국편명:<?=$rs[r_air_no]?> (출발시간:<?=$rs[r_air_time1]?> / 도착시간:<?=$rs[r_air_time2]?>)<?}?> <?if($REMOTE_ADDR=="106.246.54.27"){?>(ID_NO:<?=$rs[r_air_id_no]?>)<?}?></div>

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


	</form>
	</table>
						<span class="btn_pack medium bold"><a href="javascript:document.fmData.submit()"> 저장 </a></span>
</div>


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>