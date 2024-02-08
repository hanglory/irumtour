<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour";
$MENU = "tour";
$TITLE = "상품관리";
$file_rows = 1; //파일 업로드 갯수


#### category
If($ctg1){
	$sql = "select * from ez_tour_category1 where id_no=$ctg1";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	$TITLE	.= " > " . $rs[subject];
}

if($REMOTE_ADDR=="1106.246.54.27"){
	$sql = "alter table ez_tour add meal_type char(1) not null default 0 ";
	$dbo->query($sql);
	checkVar(mysql_error(),$sql);
}

#### operation

if($mode){

	$category1 = "${category_step1}-${category_step2}-${category_step3}";
	$category2 = "${category2_step1}-${category2_step2}-${category2_step3}";
	$category3 = "${category3_step1}-${category3_step2}-${category3_step3}";
	$category4 = "${category4_step1}-${category4_step2}-${category4_step3}";
	$category5 = "${category5_step1}-${category5_step2}-${category5_step3}";
	$category6 = "${category6_step1}-${category6_step2}-${category6_step3}";

	If($category1=="--") $category1="";
	If($category2=="--") $category2="";
	If($category3=="--") $category3="";
	If($category4=="--") $category4="";
	If($category5=="--") $category5="";
	If($category6=="--") $category6="";

	/*
	//If(substr($category1,-2)=="--") $category1="";
	If(substr($category2,-2)=="--") $category2="";
	If(substr($category3,-2)=="--") $category3="";
	If(substr($category4,-2)=="--") $category4="";
	If(substr($category5,-2)=="--") $category5="";
	If(substr($category6,-2)=="--") $category6="";
	*/

	$category1 = str_replace("--","-",$category1);
	$category2 = str_replace("--","-",$category2);
	$category3 = str_replace("--","-",$category3);
	$category4 = str_replace("--","-",$category4);
	$category5 = str_replace("--","-",$category5);
	$category6 = str_replace("--","-",$category6);
	$category7 = str_replace("--","-",$category7);

	if(!$multi_bit){
		$category2="";
		$category3="";
		$category4="";
		$category5="";
		$category6="";
	}


	$price_adult = ceil(str_replace(",","",$price_adult));
	$hit = rnf($hit);
}


if($mode=="save"){

	$path="../../public/goods";	//업로드할 파일의 경로
	$maxsize=$maxFileSize *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한

	for($i=1; $i <= $file_rows; $i++){

		$fn = "file" . $i;

		if($_FILES[$fn]["size"]){
			#------------------------------------------
			$fname=$_FILES[$fn]["tmp_name"]; //파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES[$fn]["name"];	//파일의 이름
			$fname_size=$_FILES[$fn]["size"];		//파일의 사이즈
			$fname_type=$_FILES[$fn]["type"];		//파일의 type
			$filename=$tid . "_" . $i;		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfiles[$i] = $upfile;
			$upfile_real[$i] = $_FILES[$fn]["name"];
			$upfileQuery[$i] = ($upfile)? "filename${i} = '". $upfiles[$i] ."',filename${i}_real='".$_FILES[$fn]["name"]."', ":"" ;
		}
	}
}

if ($mode=="save"){

	$bit=1; //상품 활성화

	$subject = str_replace("\"","“",$subject);


	$sql="
	   update ez_tour Set
			$upfileQuery[1]
			$upfileQuery[2]
			$upfileQuery[3]
			$upfileQuery[4]
			$upfileQuery[5]
			category1 = '$category1',
			category2 = '$category2',
			category3 = '$category3',
			category4 = '$category4',
			category5 = '$category5',
			category6 = '$category6',
			subject = '$subject',
			pr = '$pr',
			nation = '$nation',
			content1 = '$content1',
			content2 = '$content2',
			content3 = '$content3',
			plan_type = '$plan_type',
			days = '$days',
			days1 = '$days1',
			days2 = '$days2',
			period = '$period',
			tourlist_max = '$tourlist_max',
			tourlist_min = '$tourlist_min',
			air_name = '$air_name',
			price_adult = '$price_adult',
			addprice_bit = '$addprice_bit',
			memo = '$memo',
			review_link = '$review_link',
			sale_group = '$sale_group',

			golf_id_no='$golf_id_no',
			d_air_no='$d_air_no',
			r_air_no='$r_air_no',
			d_air_time1='$d_air_time1',
			d_air_time2='$d_air_time2',
			r_air_time1='$r_air_time1',
			r_air_time2='$r_air_time2',

			golf_name='$golf_name',
			golf2_1_name='$golf2_1_name',
			golf2_2_name='$golf2_2_name',
			golf2_3_name='$golf2_3_name',
			golf2_4_name='$golf2_4_name',
			golf2_5_name='$golf2_5_name',
			golf2_6_name='$golf2_6_name',
			golf2_1_id_no='$golf2_1_id_no',
			golf2_2_id_no='$golf2_2_id_no',
			golf2_3_id_no='$golf2_3_id_no',
			golf2_4_id_no='$golf2_4_id_no',
			golf2_5_id_no='$golf2_5_id_no',
			golf2_6_id_no='$golf2_6_id_no',
			d_air_id_no='$d_air_id_no',
			r_air_id_no='$r_air_id_no',

			hotel_name='$hotel_name',
			hotel2_name='$hotel2_name',

			meal_type='$meal_type',

			hotel_id_no='$hotel_id_no',
			hotel2_id_no='$hotel2_id_no',
			golf2_1='$golf2_1',
			golf2_2='$golf2_2',
			golf2_3='$golf2_3',
			golf2_4='$golf2_4',
			hotel='$hotel',
			hit='$hit',
			cancel_txt='$cancel_txt',

			hole1='$hole1',
			hole2='$hole2',
			hole3='$hole3',
			hole4='$hole4',
			hole5='$hole5',
			hole6='$hole6',

			bit = '$bit'
	   where tid=$tid
	";

	if($id_no){
		$url = "view_${filecode}.php?id_no=$id_no&ctg1=$ctg1";
	}else{
		$url = "list_${filecode}.php?ctg1=$ctg1";
	}

	if($dbo->query($sql)){

		//If($link){redirect2($link);exit;}

		If($id_no) msggo("저장하였습니다.",$url);
		Else echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();self.close()</script>";

	}else{
		checkVar(mysql_error(),$sql);
	}
	exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){

		$sql = "select * from $table where id_no = $check[$i]";
		$dbo->query($sql);
		$rs=$dbo->next_record();

		@unlink("../../public/goods/$rs[filename1]");
		@unlink("../../public/goods/$rs[filename2]");
		@unlink("../../public/goods/$rs[filename3]");
		@unlink("../../public/goods/$rs[filename4]");
		@unlink("../../public/goods/$rs[filename5]");

		$sql = "delete from $table where id_no = $check[$i]";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php?ctg1=$ctg1");exit;

}elseif ($mode=="category"){ //카테고리 변경

	$category1 = "${chng_category_step1}-${chng_category_step2}-${chng_category_step3}";

	If($category1=="--") $category1="";
	//If(substr($category1,-2)=="--") $category1="";

	for($i = 0; $i < count($check);$i++){

		$sql = "
			update $table set
				category1='$category1',
				category2='',
				category3='',
				category4='',
				category5='',
				category6=''
				where id_no = $check[$i]
			";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php?category_step1=$category_step1&category_step2=$category_step2&category_step3=$category_step3");exit;


}elseif ($mode=="file_drop"){

	$sql = "update $table set $mode2 ='' where id_no=$id_no";
	$dbo->query($sql);
	@unlink("../../public/goods/$filename");
	redirect2("?id_no=$id_no&$_SESSION[link]");
	exit;


}elseif ($mode=="golf"){

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

			parent.document.getElementById('golf2_5_name').value='$rs[golf2_5_name]';
			parent.document.getElementById('golf2_5_id_no').value='$rs[golf2_5_id_no]';

			parent.document.getElementById('golf2_6_name').value='$rs[golf2_6_name]';
			parent.document.getElementById('golf2_6_id_no').value='$rs[golf2_6_id_no]';

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


}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
	$tid=$rs[tid];

	//$rs[subject] = stripslashes($rs[subject]);

	$category1 = explode("-",$rs[category1]);
	$category2 = explode("-",$rs[category2]);
	$category3 = explode("-",$rs[category3]);
	$category4 = explode("-",$rs[category4]);
	$category5 = explode("-",$rs[category5]);
	$category6 = explode("-",$rs[category6]);

	$rs[price_adult]= number_format($rs[price_adult]);
	$multi_bit = ($rs[category2] || $rs[category3] || $rs[category4] || $rs[category5] || $rs[category6] )?1:0;

	$arr  =explode(">",$rs[golf_name]);
	$rs[golf] = trim($arr[count($arr)-1]);

	$arr  =explode(">",$rs[golf2_1_name]);	$rs[golf2_1] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_2_name]);	$rs[golf2_2] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_3_name]);	$rs[golf2_3] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_4_name]);	$rs[golf2_4] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_5_name]);	$rs[golf2_5] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[golf2_6_name]);	$rs[golf2_6] = trim($arr[count($arr)-1]);

	$arr  =explode(">",$rs[hotel_name]);	$rs[hotel] = trim($arr[count($arr)-1]);
	$arr  =explode(">",$rs[hotel2_name]);	$rs[hotel2] = trim($arr[count($arr)-1]);


}

//default value
if(!$rs[id_no]){

	$tid=getUniqNo();

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$sql = "insert into ez_tour (tid,reg_date,reg_date2) values ($tid,'$reg_date','$reg_date2')";
	$dbo->query($sql);

	$month = date("m");

	$price_adult= number_format($price_adult);
	If(!$category1[0] && $ctg1)	 $category1[0]	=$ctg1;

}
//-------------------------------------------------------------------------------
?>
<?include("../top_min.html");?>
<?include("../../include/tour_options.php");?>
<script type="text/javascript" src="../../cheditor/cheditor.js"></script>
<script type="text/javascript" src="../../include/bbs_frame.js"></script>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;
	var ctg1 = fm.category_step1.value;

	if(check_select(fm.category_step1,'여행분류를')=='wrong'){return }
	if(check_blank(fm.subject,'여행상품명을',0)=='wrong'){return }
	fm.submit();

}

function mng_days(){
	newWin('pop_days.php?tid=<?=$tid?>',950,600,1,1);
}

function mng_days_detail(){
	newWin('pop_days02.php?tid=<?=$tid?>',950,600,1,1);
}


function mng_contents(assort){
	newWin('pop_contents.php?tid=<?=$tid?>&assort='+assort,950,600,1,1);
}

function mng_etc(div){
	newWin('pop_etc.php?tid=<?=$tid?>&div='+div,950,600,1,1);
}


//파일수정
function fedit(id,bit){
	if(bit==1){
		$(".fsave"+id).hide();
		$(".fdrop"+id).show();
	}else{
		$(".fsave"+id).show();
		$(".fdrop"+id).hide();
	}
}

/*이미지 미리보기*/
function show_file(sfile){
	$("#preview_photo").show();
	$('#preview_photo').load('photo.php', {
	  'sfile': sfile
	});
	//location.href="photo.php?sfile="+sfile;
}

function hide_file(file){
	$("#preview_photo").hide();
}

function reset_code(){
	$("#goods_name_tmp").text('');
	$("#goods_name").val('');
	$("#goods_code").val('');
}

$(function(){

	setOption(document.getElementById('category_step1'),'','<?=$category1[1]?>');
	setOption2(document.getElementById('category_step2'),'','<?=$category1[2]?>');

	<?If($category2[1]){?>
	setOption(document.getElementById('category2_step1'),'2','<?=$category2[1]?>');
	setOption2(document.getElementById('category2_step2'),'2','<?=$category2[2]?>');
	<?}?>
	<?If($category3[1]){?>
	setOption(document.getElementById('category3_step1'),'3','<?=$category3[1]?>');
	setOption2(document.getElementById('category3_step2'),'3','<?=$category3[2]?>');
	<?}?>
	<?If($category4[1]){?>
	setOption(document.getElementById('category4_step1'),'4','<?=$category4[1]?>');
	setOption2(document.getElementById('category4_step2'),'4','<?=$category4[2]?>');
	<?}?>
	<?If($category5[1]){?>
	setOption(document.getElementById('category5_step1'),'5','<?=$category5[1]?>');
	setOption2(document.getElementById('category5_step2'),'5','<?=$category5[2]?>');
	<?}?>
	<?If($category6[1]){?>
	setOption(document.getElementById('category6_step1'),'6','<?=$category6[1]?>');
	setOption2(document.getElementById('category6_step2'),'6','<?=$category6[2]?>');
	<?}?>

	<?If(!$multi_bit){?>
	$("#multi_ctgs").hide();
	<?}?>

   $("#multi_bit").click(function(){
		if($(this).attr("checked")=="checked"){
		  $("#multi_ctgs").show();
		}else{
		  $("#multi_ctgs").hide();
		}
   });


});
</script>

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

<center>

<style type="text/css">
.subject_train{font-weight:normal;width:100px;text-align:right;padding-right:5px;}
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

    <table border="0" cellspacing="1" cellpadding="3" width="950">
		<form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="tid" value='<?=$tid?>'>
        <input type="hidden" name="ctg1" value="<?=$ctg1?>">

		<input type="hidden" name="golf_name" id="golf_name" value='<?=$rs[golf_name]?>'>
		<input type="hidden" name="golf_id_no" id="golf_id_no" value='<?=$rs[golf_id_no]?>'>
		<input type="hidden" name="air_id_no" id="air_id_no" value='<?=$rs[air_id_no]?>'>
		<input type="hidden" name="d_air_no" id="d_air_no" value='<?=$rs[d_air_no]?>'>
		<input type="hidden" name="r_air_no" id="r_air_no" value='<?=$rs[r_air_no]?>'>

		<input type="hidden" name="d_air_time1" id="d_air_time1" value='<?=$rs[d_air_time1]?>'>
		<input type="hidden" name="d_air_time2" id="d_air_time2" value='<?=$rs[d_air_time2]?>'>
		<input type="hidden" name="r_air_time1" id="r_air_time1" value='<?=$rs[r_air_time1]?>'>
		<input type="hidden" name="r_air_time2" id="r_air_time2" value='<?=$rs[r_air_time2]?>'>

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


		<input type="hidden" name="d_air_id_no" id="d_air_id_no" value='<?=$rs[d_air_id_no]?>'>
		<input type="hidden" name="r_air_id_no" id="r_air_id_no" value='<?=$rs[r_air_id_no]?>'>

		<input type="hidden" name="hotel_name" id="hotel_name" value='<?=$rs[hotel_name]?>'>
		<input type="hidden" name="hotel_id_no" id="hotel_id_no" value='<?=$rs[hotel_id_no]?>'>

		<input type="hidden" name="hotel2_name" id="hotel2_name" value='<?=$rs[hotel2_name]?>'>
		<input type="hidden" name="hotel2_id_no" id="hotel2_id_no" value='<?=$rs[hotel2_id_no]?>'>



		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>
	    <tr>
          <td  class="subject" width="130">* 카테고리</td>
          <td>
				<?
				$sql2 = "select * from ez_tour_category1 order by seq asc";
				$dbo2->query($sql2);
				while($rs2= $dbo2->next_record()){
					$keys .= "," . $rs2[subject];
					$vals .= "," . $rs2[id_no];
				}
				?>
			   <select name="category_step1" id="category_step1" onchange="setOption(this,'','')"><!--setOption(this,'','')-->
				 <?=option_str($keys,$vals,$category1[0])?>
			   </select>
			   <select name="category_step2" id="category_step2" onchange="setOption2(this,'','');">
			   </select>
			   <select name="category_step3" id="category_step3">
			   </select>

          </td>
        </tr>
		<tr><td colspan="2" class="tblLine"></td></tr>

	    <div style="display:none">
		<tr>
          <td  class="subject"><label><input type="checkbox" name="multi_bit" id="multi_bit" value="1" <?=($multi_bit)?"checked='checked'":""?>> 멀티카테고리</label></td>
          <td>

			   <div id="multi_ctgs">

			   <select name="category2_step1" id="category2_step1" onchange="setOption(this,2,'')">
				 <?=option_str($keys,$vals,$category2[0])?>
			   </select>
			   <select name="category2_step2" id="category2_step2" class="ctg2" onchange="setOption2(this,2,'')">
			   </select>
			   <select name="category2_step3" id="category2_step3">
			   </select>
			   <br>
			   <select name="category3_step1" id="category3_step1" onchange="setOption(this,3,'')">
				 <?=option_str($keys,$vals,$category3[0])?>
			   </select>
			   <select name="category3_step2" id="category3_step2" class="ctg2" onchange="setOption2(this,3,'')">
			   </select>
			   <select name="category3_step3" id="category3_step3">
			   </select>

			   <br>
			   <select name="category4_step1" id="category4_step1" onchange="setOption(this,4,'')">
				 <?=option_str($keys,$vals,$category4[0])?>
			   </select>
			   <select name="category4_step2" id="category4_step2" class="ctg2" onchange="setOption2(this,4,'')">
			   </select>
			   <select name="category4_step3" id="category4_step3">
			   </select>

			   <br>
			   <select name="category5_step1" id="category5_step1" onchange="setOption(this,5,'')">
				 <?=option_str($keys,$vals,$category5[0])?>
			   </select>
			   <select name="category5_step2" id="category5_step2" class="ctg2" onchange="setOption2(this,5,'')">
			   </select>
			   <select name="category5_step3" id="category5_step3">
			   </select>

			   <br>
			   <select name="category6_step1" id="category6_step1" onchange="setOption(this,6,'')">
				 <?=option_str($keys,$vals,$category6[0])?>
			   </select>
			   <select name="category6_step2" id="category6_step2" class="ctg2" onchange="setOption2(this,6,'')">
			   </select>
			   <select name="category6_step3" id="category6_step3">
			   </select>

			   </div>
          </td>
        </tr>
		<tr><td colspan="2" class="tblLine"></td></tr>
		</div>


        <tr>
          <td  class="subject">* 여행명(상품명)</td>
          <td>
            <?=html_input('subject',100,255)?> <!--여행명-->
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">간략한설명</td>
          <td>
            <?=html_textarea('pr',0,3)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td  class="subject">표시</td>
          <td>
			<select name="sale_group">
			 <?=option_str($SALE_GROUP,$SALE_GROUP_VAL,$rs[sale_group])?>
			 </select>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>



        <tr>
          <td class="subject">고객관리 상품명</td>
          <td colspan="3">
			   <span id="golf_wrap"></span>
	           <?=html_input('golf',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf()"> 검색 </a></span>

			   <?if($rs[golf_id_no]){?>
			   &nbsp;&nbsp;&nbsp;
			   (상품코드 : <a href="javascript:newWin('../cmp/view_golf.php?id_no=<?=$rs[golf_id_no]?>',870,700,1,1,'golf')"><?=$rs[golf_id_no]?></a>)
			   <?}?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">골프장명</td>
          <td colspan="3">
			   <span id="golf2_1_wrap"></span>
	           <?=html_input('golf2_1',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_1')"> 검색 </a></span>
			   <select name="hole1"><?=option_str($HOLE,$HOLE,$rs[hole1])?></select>
			   <br/>

			   <span id="golf2_2_wrap"></span>

			   <?=html_input('golf2_2',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_2')"> 검색 </a></span>
			   <select name="hole2"><?=option_str($HOLE,$HOLE,$rs[hole2])?></select>
			   <br/>
			   <span id="golf2_3_wrap"></span>

	           <?=html_input('golf2_3',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_3')"> 검색 </a></span>
			   <select name="hole3"><?=option_str($HOLE,$HOLE,$rs[hole3])?></select>
			   <br/>
			   <span id="golf2_4_wrap"></span>

			   <?=html_input('golf2_4',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_4')"> 검색 </a></span>
			   <select name="hole4"><?=option_str($HOLE,$HOLE,$rs[hole4])?></select>
			   <br/>
			   <span id="golf2_5_wrap"></span>

			   <?=html_input('golf2_5',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_5')"> 검색 </a></span>
			   <select name="hole5"><?=option_str($HOLE,$HOLE,$rs[hole5])?></select>
			   <br/>
			   <span id="golf2_6_wrap"></span>
			   <?=html_input('golf2_6',30,50)?> <span class="btn_pack medium bold"><a href="javascript:find_golf2('golf2_6')"> 검색 </a></span>
			   <select name="hole6"><?=option_str($HOLE,$HOLE,$rs[hole6])?></select>
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
          <td class="subject">1일차 식사표기</td>
          <td colspan="3">
		       <?
			   $MEAL_TYPE1="1type(호텔식),2type(불포함)";
			   $MEAL_TYPE2="0,2";
			   ?>
			   <?=radio($MEAL_TYPE1,$MEAL_TYPE2,$rs[meal_type],'meal_type')?>
			   (일정표 TYPE A,B,C,D형에만 해당합니다.)
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

        <tr>
          <td  class="subject">여행국가</td>
          <td>

			<?=html_input('nation',20,30)?>

			<span class="subject">기본일정</span>

			 <?=html_input('days1',4,20,'box c')?>박
			 <?=html_input('days2',4,20,'box c')?>일

			<span class="subject">적용기간</span>

			 <?=html_input('period',20,20)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">인원설정</td>
          <td>
             전체좌석수 : <?=html_input('tourlist_max',4,10,'box numberic')?> 명
			 <span style="width:20px"></span>
			 최소인원 : <?=html_input('tourlist_min',4,10,'box numberic')?> 명
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td  class="subject">이용항공 / 좌석</td>
          <td>
            <?=html_input('air_name',30,50)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">표시 가격</td>
          <td>
	           <?=html_input('price_adult',20,15,'box numberic')?>원~
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr class="hide">
          <td  class="subject"><label><!-- <input type="checkbox" name="addprice_bit" value="1" <?=($rs[addprice_bit])?"checked='checked'":""?>> -->추가 가격</label></td>
          <td>

			<iframe width="100%" name="addprice" id="addprice"
				onLoad="calcHeight(this.id);"
				src="include_addprice.php?tid=<?=$tid?>"
				scrolling="NO"
				frameborder="0"
				>
			</iframe>

		  </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td  class="subject">정보입력</td>
          <td>
			<span class="btn_pack small bold"><a href="javascript:mng_contents('10_information')"> 상품안내 </a></span>&nbsp;&nbsp
			<span class="btn_pack small bold"><a href="javascript:mng_contents('30_golf')"> 골프장소개 </a></span>&nbsp;&nbsp
			<span class="btn_pack small bold"><a href="javascript:mng_contents('40_hotel')"> 호텔/리조트소개 </a></span>&nbsp;&nbsp
			<span class="btn_pack small bold"><a href="javascript:mng_contents('50_gallery')"> 포토갤러리 </a></span>&nbsp;&nbsp
			<span class="btn_pack small bold"><a href="javascript:mng_contents('60_cancel')"> 예약환불규정 </a></span>&nbsp;&nbsp
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

		<tr>
          <td  class="subject">일정설정</td>
          <td>
			<span class="btn_pack small bold"><a href="javascript:mng_days()"> 일정 설정 </a></span>&nbsp;&nbsp;
			<span class="btn_pack small bold"><a href="javascript:mng_days_detail()"> 일정 보기 </a></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

			<span class="btn_pack small bold"><a href="javascript:mng_contents('remark_1_days')"> REMARK(상단) </a></span>&nbsp;&nbsp
			<span class="btn_pack small bold"><a href="javascript:mng_contents('remark_2_days')"> REMARK(하단) </a></span>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td  class="subject">포함내역</td>
          <td>
	           <?=html_textarea('content1',0,3)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">불포함내역</td>
          <td>
	           <?=html_textarea('content2',0,3)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

		<tr>
          <td  class="subject">참고사항</td>
          <td>
	           <?=html_textarea('content3',0,3)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">예약환불규정</td>
          <td>
            <?=html_textarea('cancel_txt',0,5)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


		<?
		for($j=1; $j <= $file_rows;$j++){
		$FILENAME= "filename" . $j;
		$FILENAME_REAL= "filename".$j."_real";
		$FILE_NO=$j;
		?>
		<tr>
          <td  class="subject">이미지<?=$j?><r/td>
          <td>
			<?
			if($rs[$FILENAME]):
			@$fileSize = filesize("../../public/goods/${rs[$FILENAME]}");
			?>

			<span class="hide fsave<?=$FILE_NO?>"><input class=box type="file" name="file<?=$FILE_NO?>" size="40"></span>
			<span class="btn_pack small bold fdrop<?=$FILE_NO?>"><a href="javascript:if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=<?=$FILENAME?>&filename=<?=$rs[$FILENAME]?>'}"> 파일삭제 </a></span>

			&nbsp;&nbsp;
			<span class="btn_pack small bold fdrop<?=$FILE_NO?>"><a href="javascript:fedit('<?=$FILE_NO?>',0)"> 파일수정 </a></span>	&nbsp;
			<span class="btn_pack small bold fsave<?=$FILE_NO?> hide"><a href="javascript:fedit('<?=$FILE_NO?>',1)"> 수정취소 </a></span>

			&nbsp;&nbsp;

			<span class="fdrop<?=$FILE_NO?>">
			<a href="../../include/download.php?file=<?=$rs[$FILENAME]?>&orgin_file_name=<?=$rs[$FILENAME_REAL]?>&dir=public/goods&ctg1=<?=$ctg1?>" onmouseover="show_file('../../public/goods/<?=$rs[$FILENAME]?>')" onmouseout="hide_file()"><?=$rs[$FILENAME_REAL]?> (<?=ceil($fileSize/1024)?>KB)</a>
			</span>

			<?else:?>
			<input class=box type="file" name="file<?=$FILE_NO?>" size="40">
			<font color="orange">Alert</font></b> <font color="#666666">: Max <?=$maxFileSize?>MB</font>
			<?endif;?>

			<?If($j==1){?>
			<div style="position:absolute;left:500px;border:3px solid #ccc;display:none" id="preview_photo"></div>
			<?}?>

          </td>
        </tr>
		<tr><td colspan="2" class="tblLine"></td></tr>

		<?}?>

        <tr>
          <td  class="subject">답사후기 링크</td>
          <td>
			<?=html_input('review_link',80,200,'box eng_only')?>
			<span style="color:red">URL만 입력해 주세요.</span>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">조회수</td>
          <td>
			<?=html_input('hit',13,10,'box c')?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td  class="subject">관리자메모</td>
          <td>
            <?=html_textarea('memo',0,3)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>

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

</center>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>


<?


if($REMOTE_ADDR=="11106.246.54.27"){

	$sql = "select * from ez_tab_contents where assort='60_cancel' order by id_no desc ";
	$dbo->query($sql);
	checkVar(mysql_error(),$sql);
	while($rs= $dbo->next_record()){

		$rs[content] = str_replace("<br","\n<br",$rs[content]);
		$rs[content] = str_replace("<BR","\n<BR",$rs[content]);
		$rs[content] = strip_tags($rs[content]);
		$rs[content] = addslashes($rs[content]);
		$rs[content] = str_replace("&nbsp;"," ",$rs[content]);

		$sql2 = "update ez_tour set cancel_txt='$rs[content]' where tid='$rs[tid]' ";
		$dbo2->query($sql2);
		if(mysql_error()) checkVar(mysql_error(),$sql2);


	}

}

?>