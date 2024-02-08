<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_golf";
$MENU = "cmp_basic";
$TITLE = "상품정보";


#### operation
if ($mode=="save"){

	if(!$staff){
		$staff=$_SESSION[sessLogin][name] . " (". $_SESSION[sessLogin][id] . ")";
	}
	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$sqlInsert="
	   insert into cmp_golf (
	      nation,
	      city,
	      name,
	      golf_name,
	      golf_name2,
	      golf_name3,
	      golf_name4,
	      hotel_name,
	      hotel2_name,

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
	      hotel_id_no,
	      hotel2_id_no,

	      main_staff,
	      main_staff_id,
	      phone,
	      meeting_place,
	      meeting_board,
	      local_staff,
	      phone2,
	      point_include,
	      point_not_include,
	      meal,
	      etc,
	      bit,
	      partner,
		  car,
	      staff,

		  air_city,
		  golf2_5,
		  golf2_5_name,
		  golf2_5_id_no,
		  car2,
		  car3,
		  etc2,
		   ag1,
		   ag2,
		   ag3,
		   ag4,
		   ag5,
		   ag6,
		   ag7,
		   ag8,
		   gh1,
		   gh2,
		   gh3,
		   gh4,
		   gh5,
		   gh6,
		   gh7,
		   gh8,
		   ah1,
		   ah2,
		   ah3,
		   ah4,
		   ah5,
		   ah6,
		   ah7,
		   ah8,
		  cancel_text
	  ) values (
	      '$nation',
	      '$city',
	      '$name',
	      '$golf_name',
	      '$golf_name2',
	      '$golf_name3',
	      '$golf_name4',
	      '$hotel_name',
	      '$hotel2_name',

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
	      '$hotel_id_no',
	      '$hotel2_id_no',

	      '$main_staff',
	      '$main_staff_id',
	      '$phone',
	      '$meeting_place',
	      '$meeting_board',
	      '$local_staff',
	      '$phone2',
	      '$point_include',
	      '$point_not_include',
	      '$meal',
	      '$etc',
	      '$bit',
	      '$partner',
	      '$car',
	      '$staff',
	      '$air_city',
	      '$golf2_5',
	      '$golf2_5_name',
	      '$golf2_5_id_no',
	      '$car2',
	      '$car3',
	      '$etc2',
	      '$ag1',
	      '$ag2',
	      '$ag3',
	      '$ag4',
	      '$ag5',
	      '$ag6',
	      '$ag7',
	      '$ag8',
	      '$gh1',
	      '$gh2',
	      '$gh3',
	      '$gh4',
	      '$gh5',
	      '$gh6',
	      '$gh7',
	      '$gh8',
	      '$ah1',
	      '$ah2',
	      '$ah3',
	      '$ah4',
	      '$ah5',
	      '$ah6',
	      '$ah7',
	      '$ah8',
	      '$cancel_text'
	)";

	$sqlModify="
	   update cmp_golf set
	      nation = '$nation',
	      city = '$city',
	      name = '$name',
	      golf_name = '$golf_name',
	      golf_name2 = '$golf_name2',
	      golf_name3 = '$golf_name3',
	      golf_name4 = '$golf_name4',
	      hotel_name = '$hotel_name',
	      hotel2_name = '$hotel2_name',

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
	      hotel_id_no='$hotel_id_no',
	      hotel2_id_no='$hotel2_id_no',

	      main_staff = '$main_staff',
	      main_staff_id = '$main_staff_id',
	      phone = '$phone',
	      meeting_place = '$meeting_place',
	      meeting_board = '$meeting_board',
	      local_staff = '$local_staff',
	      phone2 = '$phone2',
	      point_include = '$point_include',
	      point_not_include = '$point_not_include',
	      meal = '$meal',
	      etc = '$etc',
	      bit = '$bit',
	      partner = '$partner',
	      car = '$car',
	      staff = '$staff',
	      air_city = '$air_city',
	      golf2_5 = '$golf2_5',
	      golf2_5_name = '$golf2_5_name',
	      golf2_5_id_no = '$golf2_5_id_no',
	      car2 = '$car2',
	      car3 = '$car3',
	      etc2 = '$etc2',

	      ag1 ='$ag1',
	      ag2 ='$ag2',
	      ag3 ='$ag3',
	      ag4 ='$ag4',
	      ag5 ='$ag5',
	      ag6 ='$ag6',
	      ag7 ='$ag7',
	      ag8 ='$ag8',
	      gh1 ='$gh1',
	      gh2 ='$gh2',
	      gh3 ='$gh3',
	      gh4 ='$gh4',
	      gh5 ='$gh5',
	      gh6 ='$gh6',
	      gh7 ='$gh7',
	      gh8 ='$gh8',
	      ah1 ='$ah1',
	      ah2 ='$ah2',
	      ah3 ='$ah3',
	      ah4 ='$ah4',
	      ah5 ='$ah5',
	      ah6 ='$ah6',
	      ah7 ='$ah7',
	      ah8 ='$ah8',

	      cancel_text = '$cancel_text'
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
		If($id_no) msggo("저장하였습니다.",$url);
		Else echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();self.close()</script>";

	}else{
		checkVar(mysql_error(),$sql);
	}
	exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){

		$sql = "delete from $table where id_no = $check[$i]";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php?ctg1=$ctg1");exit;

}elseif($mode=="golf2"){

	$arr = explode("_",$id);
	$seq = $arr[1];
	$THME="";

	$golf = trim($golf);
	$sql = "select * from cmp_golf2 where name like '%$golf%' order by nation asc,city asc, name asc";
	list($rows) = $dbo->query($sql);
	while($rs=$dbo->next_record()){
		$KEY .= ",$rs[nation] > $rs[city] > $rs[name]";
		$VAL .= ",".$rs[id_no];

		$THME .="<div class='hide' id='ag_${rs[id_no]}_$seq'>$rs[ag]</div>";
		$THME .="<div class='hide' id='gh_${rs[id_no]}_$seq'>$rs[gh]</div>";

	}
	$str = ($rows)? "선택하세요":"검색된 골프장이 없습니다.";

	echo "
		<select name='${id}_tmp' id='${id}_tmp' onchange=\"set_golf2('$id',this.options[this.selectedIndex].text,this.value,$seq)\" style=\"width:450px\">
		";
			echo option_str($str.$KEY,$VAL);
	echo "
		<option value='$golf'>$golf</option>
		</select>
		$THME
	";
	exit;

}elseif($mode=="hotel"){

	$seq =($id=="hotel2")?2:1;
	$THME="";

	$hotel = trim($hotel);
	$sql = "select * from cmp_hotel where name like '%$hotel%' order by nation asc,city asc, name asc";
	list($rows) = $dbo->query($sql);
	while($rs=$dbo->next_record()){
		$KEY .= ",$rs[nation] > $rs[city] > $rs[name]";
		$VAL .= ",".$rs[id_no];

		$THME .="<div class='hide' id='ah_${rs[id_no]}_$seq'>$rs[ah]</div>";
	}
	$str = ($rows)? "선택하세요":"검색된 호텔명이 없습니다.";
	echo "
		<select name='${id}_tmp' id='${id}_tmp' onchange=\"set_hotel('$id',this.options[this.selectedIndex].text,this.value,$seq)\" style=\"width:450px\">
		";
			echo option_str($str.$KEY,$VAL);
	echo "
		<option value='$hotel'>$hotel</option>
		</select>
		$THME
	";
	exit;

}else{


	#### staff
	$sql = "select * from cmp_staff where bit_hide<>1 order by name asc";
	$dbo->query($sql);
	while($rs=$dbo->next_record()){
		$STAFF.=",$rs[name] ($rs[id])";
	}
	$STAFF = substr($STAFF,1);


	//거래처 불러오기
	$PARTNERS = "";
	$sql = "select * from cmp_partner order by company asc";
	$dbo->query($sql);
	//if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql);
	while($rs= $dbo->next_record()){
		$PARTNERS .= ",$rs[company]";
	}
	$PARTNERS = substr($PARTNERS,1);

	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();

	$arr  =explode(">",$rs[golf2_1_name]);	$rs[golf2_1] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_2_name]);	$rs[golf2_2] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_3_name]);	$rs[golf2_3] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_4_name]);	$rs[golf2_4] = trim($arr[count($arr)-1]);

	$arr  =explode(">",$rs[hotel_name]);	$rs[hotel] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[hotel2_name]);	$rs[hotel2] = trim($arr[count($arr)-1]);
}


?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;

	if(check_select(fm.name,'골프장명을')=='wrong'){return }
	if(check_select(fm.partner,'거래처명을')=='wrong'){return }
	if(check_blank(fm.meeting_place,'공항미팅위치를',0)=='wrong'){return }
	if(check_blank(fm.meeting_board,'미팅보드를',0)=='wrong'){return }
	if(check_blank(fm.local_staff,'현지담당을',0)=='wrong'){return }
	if(check_blank(fm.phone2,'비상연락처를',0)=='wrong'){return }
	fm.submit();

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

function find_hotel(id){
	var fm = document.fmData;
	var hotel = $("#"+id).val();
	if(hotel==""){alert('검색할 호텔명을 입력해 주세요.');$("#"+id).focus(); return; }

	$.ajax({
	  url: "<?=SELF?>",
	  type: "POST",
	  data: {
		'mode': 'hotel',
		'id': id,
		'hotel': hotel
	  },
	  success: function(data) {
		$("#"+id+"_wrap").html(data);
	  }
	});
	$("#"+id).val('');
}

function set_golf2(id,k,v,seq){
	$("#"+id+"_name").val(k);
	$("#"+id+"_id_no").val(v);

	var ag = $("#ag_"+v+"_" + seq).text();
	var gh = $("#gh_"+v+"_" + seq).text();

	$("#ag" + seq).val(ag);
	$("#gh" + seq).val(gh);

}

function set_hotel(id,k,v,seq){
	$("#"+id+"_name").val(k);
	$("#"+id+"_id_no").val(v);

	var ah = $("#ah_"+v+"_" + seq).text();
	$("#ah" + seq).val(ah);
}

jQuery(function($){

	$(".num").keypress(function(event){
		if(event.which && (event.which < 48 || event.which > 57)){
			event.preventDefault();
		}
	});

	$("#d_time_s").mask("99:99");
	$("#d_time_e").mask("99:99");
	$("#r_time_s").mask("99:99");
	$("#r_time_e").mask("99:99");

	$("#golf2_1").on("change",function(){if(this.value==""){$("#golf2_1_name").val('');$("#golf2_1_id_no").val('')}});
	$("#golf2_2").on("change",function(){if(this.value==""){$("#golf2_2_name").val('');$("#golf2_2_id_no").val('')}});
	$("#golf2_3").on("change",function(){if(this.value==""){$("#golf2_3_name").val('');$("#golf2_3_id_no").val('')}});
	$("#golf2_4").on("change",function(){if(this.value==""){$("#golf2_4_name").val('');$("#golf2_4_id_no").val('')}});
	$("#hotel").on("change",function(){if(this.value==""){$("#hotel_name").val('');$("#hotel_id_no").val('')}});
	$("#hotel2").on("change",function(){if(this.value==""){$("#hotel2_name").val('');$("#hotel2_id_no").val('')}});

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
		if(e.which == 13) find_hotel('hotel');
	});

	$('#hotel2').keypress(function(e){
		if(e.which == 13) find_hotel('hotel2');
	});

});
</script>
<style type="text/css">
.tm_col{display:inline-block;width:80px;font-family: verdana;font-size:12px;}
</style>

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
		<input type="hidden" name="hotel2_name" id="hotel2_name" value='<?=$rs[hotel2_name]?>'>
		<input type="hidden" name="hotel2_id_no" id="hotel2_id_no" value='<?=$rs[hotel2_id_no]?>'>

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td class="subject" width="15%">국가</td>
          <td>
	           <select name="nation">
	           <?=option_str($NATIONS,$NATIONS,$rs[nation])?>
	           </select>
          </td>
          <td class="subject">담당자</td>
          <td>
	           <select name="staff">
				<?=option_str($STAFF,$STAFF,$rs[staff])?>
			   </select>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">공항지역</td>
          <td>
	           <?=html_input('air_city',30,28)?>
          </td>

          <td class="subject">지역</td>
          <td>
	           <?=html_input('city',30,28)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>



        <tr>
          <td class="subject">상품명</td>
          <td colspan="3">
	           <?=html_input('name',30,40)?>

			   <span class="btn_pack medium bold"><a href="javascript:newWin('set_price.php?golf_id_no=<?=$rs[id_no]?>',950,500,1,1)" id="btn1"> 지상비 </a></span>&nbsp;
			   <span class="btn_pack medium bold"><a href="javascript:newWin('set_price2.php?golf_id_no=<?=$rs[id_no]?>',600,500,1,1)" id="btn1"> 변동일(호텔) </a></span>&nbsp;
			   <span class="btn_pack medium bold"><a href="javascript:newWin('set_price3.php?golf_id_no=<?=$rs[id_no]?>',600,500,1,1)" id="btn1"> 변동일(골프) </a></span>

          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">골프장명</td>
          <td colspan="3">
			   <span id="golf2_1_wrap"></span>
	           <?=html_input('golf2_1',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_1')"> 검색 </a></span>
			   <br/>

			   <span id="golf2_2_wrap"></span>
			   <?=html_input('golf2_2',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_2')"> 검색 </a></span>

			   <br/>


			   <span id="golf2_3_wrap"></span>
	           <?=html_input('golf2_3',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_3')"> 검색 </a></span>

			   <br/>


			   <span id="golf2_4_wrap"></span>
			   <?=html_input('golf2_4',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_4')"> 검색 </a></span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">소요시간</td>
          <td colspan="3">
			   <span class="tm_col">공항-골프장 :</span> <?=html_input('ag1',10,28)?>소요,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			   <span class="tm_col">골프장-호텔 :</span> <?=html_input('gh1',10,28)?>소요
			   <br/>
			   <span class="tm_col">공항-골프장 :</span> <?=html_input('ag2',10,28)?>소요,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			   <span class="tm_col">골프장-호텔 :</span> <?=html_input('gh2',10,28)?>소요
			   <br/>
			   <span class="tm_col">공항-골프장 :</span> <?=html_input('ag3',10,28)?>소요,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			   <span class="tm_col">골프장-호텔 :</span> <?=html_input('gh3',10,28)?>소요
			   <br/>
			   <span class="tm_col">공항-골프장 :</span> <?=html_input('ag4',10,28)?>소요,&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			   <span class="tm_col">골프장-호텔 :</span> <?=html_input('gh4',10,28)?>소요
			   <br/>
			   <span class="tm_col">호텔-공항 :</span> <?=html_input('ah1',10,28)?>소요<br>
			   <span class="tm_col">호텔-공항 :</span> <?=html_input('ah2',10,28)?>소요
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">호텔명</td>
          <td>
			   <span id="hotel_wrap"></span>
	           <?=html_input('hotel',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_hotel('hotel')"> 검색 </a></span><br/>
			   <span id="hotel2_wrap"></span>
	           <?=html_input('hotel2',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_hotel('hotel2')"> 검색 </a></span>
          </td>

          <td class="subject">소요시간</td>
          <td>
			   호텔-공항 : <?=html_input('ah1',10,28)?>소요
			   <br/>
			   호텔-공항 : <?=html_input('ah2',10,28)?>소요
          </td>

        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">거래처</td>
          <td>
			<select name="partner" id="partner">
			<option value=""></option>
			<?=option_str($PARTNERS,$PARTNERS,$rs[partner]);?>
			</select>
          </td>

          <td class="subject">담당자</td>
          <td>
      	    <?
      	    $MSTAFF="";
      	    $MSTAFF2="";
			$sql2 = "select * from cmp_partner order by name asc";
			$dbo2->query($sql2);
			while($rs2=$dbo2->next_record()){
				$MSTAFF.=",$rs2[name]";
				$MSTAFF2.=",$rs2[id_no]_$rs2[phone]";
			}
			$STAFF = substr($STAFF,1);
			if(!$rs[main_staff_id]){
				$MSTAFF=",".$rs[main_staff].$MSTAFF;
				$MSTAFF2=",".$MSTAFF2;
			}
      	    ?>
      	    <script>
      	    function sel_main_staff(obj){
      	    	var name = obj.options[obj.selectedIndex].text;
      	    	var str = obj.value;
      	    	var arr = str.split("_");
      	    	var phone = arr[1];
      	    	$("#main_staff").val(name);
      	    	$("#phone").val(phone);
      	    }
      	    </script>
      	    <select name="main_staff_id" id="main_staff_id" onchange="sel_main_staff(this)">
      	    	<?=option_str($MSTAFF,$MSTAFF2,$rs[main_staff_id])?>
      	    </select>
            <span class="hide"><?=html_input('main_staff',30,30)?></span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">연락처</td>
          <td colspan="3">
	           <?=html_input('phone',30,40)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">공항미팅위치</td>
          <td>
	           <?=html_input('meeting_place',45,45)?>
          </td>
          <td class="subject">차량</td>
          <td>
	           <select name="car" style="width:30%">
				<option value="">차량1</option>
				<?=option_str($CAR1,$CAR1,$rs[car])?>
			   </select>
	           <select name="car2" style="width:30%">
				<option value="">차량2</option>
				<?=option_str($CAR2,$CAR2,$rs[car2])?>
			   </select>
	           <select name="car3" style="width:30%">
				<option value="">차량3</option>
				<?=option_str($CAR3,$CAR3,$rs[car3])?>
			   </select>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">미팅보드</td>
          <td colspan="3">
	           <?=html_input('meeting_board',45,45)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">현지담당</td>
          <td>
	           <?=html_input('local_staff',20,30)?>
          </td>

          <td class="subject">비상연락처</td>
          <td>
	           <?=html_input('phone2',30,40)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">포함사항</td>
          <td colspan="3">
	           <?=html_textarea("point_include",0,3)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">불포함사항</td>
          <td colspan="3">
	           <?=html_textarea("point_not_include",0,3)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">참고사항</td>
          <td colspan="3">
	           <?=html_textarea("etc",0,3)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">참고사항2</td>
          <td colspan="3">
	           <?=html_textarea("etc2",0,3)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">취소규정</td>
          <td colspan="3">
	           <?=html_textarea("cancel_text",0,3)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">식사</td>
          <td colspan="3">
	           <?=html_textarea("meal",0,3)?>
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