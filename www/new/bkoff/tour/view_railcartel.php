<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour";
$MENU = "tour";
$TITLE = "상품관리";


#### category
If($ctg1){
	$sql = "select * from ez_tour_category1 where id_no=$ctg1";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	$TITLE	.= " > " . $rs[subject];
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

	//If(substr($category1,-2)=="--") $category1="";
	If(substr($category2,-2)=="--") $category2="";
	If(substr($category3,-2)=="--") $category3="";
	If(substr($category4,-2)=="--") $category4="";
	If(substr($category5,-2)=="--") $category5="";
	If(substr($category6,-2)=="--") $category6="";

	if(!$multi_bit){
		$category2="";
		$category3="";
		$category4="";
		$category5="";
		$category6="";
	}

	for($i=0; $i <count($region);$i++){
		if($region[$i]) $regions .="," . str_replace(",","",addslashes($region[$i]));
	}
	$region = substr($regions,1);

	for($i=0; $i <count($icons);$i++){
		if($icons[$i]) $icon .="," . str_replace(",","",addslashes($icons[$i]));
	}
	$icons = substr($icon,1);

	for($i=0; $i <count($station);$i++){
		if($station[$i]) $stations .="," . str_replace(",","",addslashes($station[$i]));
	}
	$station = substr($stations,1);

	for($i=0; $i <count($vehicle);$i++){
		if($vehicle[$i]) $vehicles .="," . str_replace(",","",addslashes($vehicle[$i]));
	}
	$vehicle = substr($vehicles,1);

	for($i=0; $i <count($hotel_size);$i++){
		if($hotel_size[$i]) $hotel_sizes .="," . str_replace(",","",addslashes($hotel_size[$i]));
	}
	$hotel_size = substr($hotel_sizes,1);

	for($i=0; $i <count($local_company);$i++){
		if($local_company[$i]) $local_companys .="," . str_replace(",","",addslashes($local_company[$i]));
	}
	$local_company = substr($local_companys,1);

	$price_adult = ceil(str_replace(",","",$price_adult));
	$price_child = ceil(str_replace(",","",$price_child));
	$origin_price_adult = ceil(str_replace(",","",$origin_price_adult));
	$origin_price_child = ceil(str_replace(",","",$origin_price_child));
	$price_adult_partner = ceil(str_replace(",","",$price_adult_partner));
	$price_child_partner = ceil(str_replace(",","",$price_child_partner));
	$oil_price = ceil(str_replace(",","",$oil_price));
	$railcartel_fee = ceil(str_replace(",","",$railcartel_fee));
	//$point = ceil(str_replace(",","",$point));
}


if($mode=="save"){

	$path="../../public/goods";	//업로드할 파일의 경로
	$maxsize=$maxFileSize *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한

	for($i=1; $i <= 5; $i++){

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

	$station = ""; //레일카텔의 경우 출발역은 없음. 열차관리(레일텔) 에서 개별설정

	$sql="
	   update ez_tour set
			$upfileQuery[1]
			$upfileQuery[2]
			$upfileQuery[3]
			$upfileQuery[4]
			region = '$region',
			show_top = '$show_top',
			category1 = '$category1',
			category2 = '$category2',
			category3 = '$category3',
			category4 = '$category4',
			category5 = '$category5',
			category6 = '$category6',
			price_adult = '$price_adult',
			price_child = '$price_child',
			price_adult_partner = '$price_adult_partner',
			price_child_partner = '$price_child_partner',
			sale_group = '$sale_group',
			tour_group = '$tour_group',
			bit_jg = '$bit_jg',
			bit_cp = '$bit_cp',
			bit_a = '$bit_a',
			bit_b = '$bit_b',
			bit_c = '$bit_c',
			subject = '$subject',
			memo = '$memo',
			icons = '$icons',
			pr = '$pr',
			point = '$point',
			tourlist_min = '$tourlist_min',
			tourlist_max = '$tourlist_max',
			tourlist_seat = '$tourlist_seat',
			tourlist_stand = '$tourlist_stand',
			other_stand = '$other_stand',
			fix_stand = '$fix_stand',
			place_season = '$place_season',
			staff = '$staff',
			station = '$station',
			days = '$days',
			days2 = '$days2',
			tour_company = '$tour_company',
			local_company = '$local_company',
			vehicle = '$vehicle',
			addprice_bit = '$addprice_bit',
			origin_price_adult = '$origin_price_adult',
			origin_price_child = '$origin_price_child',
			railcartel_fee = '$railcartel_fee',
			hotel_size = '$hotel_size',
			hotel_style = '$hotel_style',
			hotel_max = '$hotel_max',
			site = '$site',
			date_start='$date_start',
			date_end='$date_end',
			datetime_start='$datetime_start',
			datetime_end='$datetime_end',
			oil_price='$oil_price',
			oil_price_bit='$oil_price_bit',
			air='$air',
			air_name='$air_name',
			ship_name='$ship_name',
			use_period='$use_period',
			spa_name='$spa_name',
			spa_location='$spa_location',
			spa_use='$spa_use',
			ship_intro='$ship_intro',
			ship_port='$ship_port',
			ship_use='$ship_use',
			train_bit = '$train_bit',
			bit = '$bit'
	   where tid=$tid
	";

	if($id_no){
		$url = "view_${filecode}.php?id_no=$id_no&ctg1=$ctg1";
	}else{
		$url = "list_${filecode}.php?ctg1=$ctg1";
	}

	if($dbo->query($sql)){

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

		$sql = "delete from $table where id_no = $check[$i]";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php?ctg1=$ctg1");exit;

}elseif ($mode=="category"){ //카테고리 변경

	$category1 = "${chng_category_step1}-${chng_category_step2}-${chng_category_step3}";

	If($category1=="--") $category1="";
	If(substr($category1,-2)=="--") $category1="";

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


}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
	$tid=$rs[tid];

	$category1 = explode("-",$rs[category1]);
	$category2 = explode("-",$rs[category2]);
	$category3 = explode("-",$rs[category3]);
	$category4 = explode("-",$rs[category4]);
	$category5 = explode("-",$rs[category5]);
	$category6 = explode("-",$rs[category6]);

	$rs[price_adult]= number_format($rs[price_adult]);
	$rs[price_child]= number_format($rs[price_child]);

	$rs[price_adult_partner]= number_format($rs[price_adult_partner]);
	$rs[price_child_partner]= number_format($rs[price_child_partner]);

	$rs[origin_price_adult]= number_format($rs[origin_price_adult]);
	$rs[origin_price_child]= number_format($rs[origin_price_child]);
	$rs[railcartel_fee]= number_format($rs[railcartel_fee]);

	$multi_bit = ($rs[category2] || $rs[category3] || $rs[category4] || $rs[category5] || $rs[category6] )?1:0;

}

//default value
if(!$rs[id_no]){

	$tid=getUniqNo();

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$sql = "insert into ez_tour (tid,reg_date,reg_date2) values ($tid,'$reg_date','$reg_date2')";
	$dbo->query($sql);

	$month = date("m");
	$rs[bit_jg]=1;
	if($month>=3 && $month<=6) $rs[place_season]="봄";
	elseif($month>=7 && $month<=8) $rs[place_season]="여름";
	elseif($month>=9 && $month<=10) $rs[place_season]="가을";
	else $rs[place_season]="겨울";

	$price_adult= number_format($price_adult);
	$price_child= number_format($price_child);

	$price_adult_partner= number_format($price_adult_partner);
	$price_child_partner= number_format($price_child_partner);

	$origin_price_adult= number_format($rs[origin_price_adult]);
	$origin_price_child= number_format($rs[origin_price_child]);


	If(!$category1[0] && $ctg1)	 $category1[0]	=$ctg1;
}

//레일카텔고정
$category1[0] = 1;
$category1[1] = 7;
$ctg1=1;
$ctg2=7;
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

function mng_season(){
	var season = document.fmData.place_season_tmp.value;
	newWin('pop_place.php?tid=<?=$tid?>&season='+season,800,600,1,1);
}

function mng_train(){
	newWin('pop_railcartel_train.php?tid=<?=$tid?>',850,600,1,1);
}

function mng_hotel(){
	newWin('pop_railcartel_hotel.php?tid=<?=$tid?>',850,600,1,1);
}

function mng_rentcar(){
	newWin('pop_railcartel_rentcar.php?tid=<?=$tid?>',850,600,1,1);
}

function mng_days(){
	newWin('pop_days.php?tid=<?=$tid?>',850,600,1,1);
}

function mng_days_detail(){
	newWin('pop_days02.php?tid=<?=$tid?>',850,600,1,1);
}

function mng_price(partner){
	var bstations="";
	var days = $("#days").val();

	for(i=0; i<9;i++){
		if($("#station"+i).val()!="") bstations += "," + $("#station"+i).val();
	}

	if(partner==1){//현지여행사용
		newWin('pop_price_partner.php?tid=<?=$tid?>&days='+days+'&bstations='+bstations.substring(1),950,600,1,1);
	}else{
		newWin('pop_price.php?tid=<?=$tid?>&days='+days+'&bstations='+bstations.substring(1),950,600,1,1);
	}
}

function mng_fee(){
	newWin('pop_agent_fee.php?tid=<?=$tid?>',850,600,1,1);
}

function mng_contents(assort){
	newWin('pop_contents.php?tid=<?=$tid?>&assort='+assort,850,600,1,1);
}

function mng_etc(div){
	newWin('pop_etc.php?tid=<?=$tid?>&div='+div,850,600,1,1);
}


/*이미지 미리보기*/
function show_file(file){
	$("#preview_photo").show();
	$("#preview_photo").load("../photo.php", {'file': file});
}
function hide_file(file){
	$("#preview_photo").hide();
}


$(function(){

	setOption(document.getElementById('category_step1'),'','<?=$category1[1]?>');
	setOption2(document.getElementById('category_step2'),'','<?=$category1[2]?>');

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

    <table border="0" cellspacing="1" cellpadding="3" width="850">
		<form name="fmData" method=post enctype="multipart/form-data">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="tid" value='<?=$tid?>'>
        <input type=hidden name="ctg1" value="<?=$ctg1?>">

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>
	    <tr>
          <td  class="subject" width="130">* 메인카테고리</td>
          <td>
				<?
				$sql2 = "select * from ez_tour_category1 order by binary subject asc";
				$dbo2->query($sql2);
				while($rs2= $dbo2->next_record()){
					$keys .= "," . $rs2[subject];
					$vals .= "," . $rs2[id_no];
				}
				?>
			   <select name="category_step1" id="category_step1" onchange="this.value='<?=$ctg1?>';alert('레일카텔은 상품체계가 달라서 대분류를 변경할 수 없습니다.')"><!--setOption(this,'','')-->
				 <?=option_str($keys,$vals,$category1[0])?>
			   </select>
			   <select name="category_step2" id="category_step2" onchange="this.value='<?=$ctg2?>';alert('레일카텔은 상품체계가 달라서 중분류를 변경할 수 없습니다.')">
			   </select>
			   <select name="category_step3" id="category_step3">
			   </select>
          </td>
        </tr>
		<tr><td colspan="2" class="tblLine"></td></tr>

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
          <td  class="subject">아이콘 설정</td>
          <td>
            <?=checkbox($ICONS,$ICONS,$rs[icons],'icons')?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">지역</td>
          <td>
			 <span style="width:630px"><?=checkbox($REGION,$REGION,$rs[region],'region')?></span>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td  class="subject">구분</td>
          <td>


			 <select name="tour_company">
			 <?=option_str("당사 운행 상품,타사 운행 상품","당사,타사",$rs[tour_company])?>
			 </select>
			 <span style="width:20px"></span>

			 <select name="sale_group">
			 <?=option_str($SALE_GROUP,$SALE_GROUP_VAL,$rs[sale_group])?>
			 </select>
			 <span style="width:20px"></span>

			 <label for="bit_jg">
				<input type="checkbox" name="bit_jg" id="bit_jg" value="1" <?=($rs[bit_jg])?'checked=checked':''?> /> 지구투어
			 </label>
			 <label for="bit_cp">
				<input type="checkbox" name="bit_cp" id="bit_cp" value="1" <?=($rs[bit_cp])?'checked=checked':''?> /> CP
			 </label>
			 <label for="bit_a">
				<input type="checkbox" name="bit_a" id="bit_a" value="1" <?=($rs[bit_a])?'checked=checked':''?> /> A
			 </label>
			 <label for="bit_b">
				<input type="checkbox" name="bit_b" id="bit_b" value="1" <?=($rs[bit_b])?'checked=checked':''?> /> B
			 </label>
			 <label for="bit_c">
				<input type="checkbox" name="bit_c" id="bit_c" value="1" <?=($rs[bit_c])?'checked=checked':''?> /> C
			 </label>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>



        <tr>
          <td  class="subject">할인전 가격</td>
          <td>
             성인 : <?=html_input('origin_price_adult',10,10,'box numberic')?>
			 소인 : <?=html_input('origin_price_child',10,10,'box numberic')?>
			 <span style="width:20px"></span>
			  * 입력하시면 사선이 그어진 상태로 표시됩니다.
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">표시 가격</td>
          <td>
             성인 : <?=html_input('price_adult',10,10,'box numberic')?>
			 소인 : <?=html_input('price_child',10,10,'box numberic')?>
			 <span style="width:20px"></span>
			 포인트 : <?=html_input('point',3,10,'box numberic')?>%
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">지구투어 수수료</td>
          <td>
             <?=html_input('railcartel_fee',10,10,'box numberic')?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject"><span class="train_mode">레일카텔 설정</td>
          <td>
			<span class="btn_pack small bold"><a href="javascript:mng_train()"> 기차 설정 </a></span>
			<span style="width:10px"></span>
			<span class="btn_pack small bold"><a href="javascript:mng_hotel()"> 호텔 설정 </a></span>
			<span style="width:10px"></span>
			<span class="btn_pack small bold"><a href="javascript:mng_rentcar()"> 렌트카 설정 </a></span>
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
          <td  class="subject">관광지설정</td>
          <td>
            <?=radio("봄,여름,가을,겨울","봄,여름,가을,겨울",$rs[place_season],"place_season")?>
			<span style="width:20px"></span>
			<span class="btn_pack small bold"><a href="javascript:mng_season()"> 관광지 관리 </a></span>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">정보입력</td>
          <td>
			<span class="btn_pack small bold"><a href="javascript:mng_contents('10_information')"> 상품정보 </a></span>&nbsp;&nbsp
			<span class="btn_pack small bold"><a href="javascript:mng_contents('20_guide')"> 이용안내 </a></span>&nbsp;&nbsp
			<span class="btn_pack small bold"><a href="javascript:mng_contents('30_facility')"> 시설안내 </a></span>&nbsp;&nbsp
			<span class="btn_pack small bold"><a href="javascript:mng_contents('40_plan')"> 운행일정 </a></span>&nbsp;&nbsp
			<span class="btn_pack small bold"><a href="javascript:mng_contents('50_time')"> 운행코스/시간표 </a></span>&nbsp;&nbsp
			<span class="btn_pack small bold"><a href="javascript:mng_contents('60_hotel')"> 호텔정보 </a></span>&nbsp;&nbsp
			<span class="btn_pack small bold"><a href="javascript:mng_contents('70_map')"> 찾아가는 길 </a></span>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">정보입력2</td>
          <td>
			<span class="btn_pack small bold"><a href="javascript:mng_etc('1')"> 정보입력 </a></span> &nbsp;&nbsp; (<?=$TOUR_ETC1?> 등)
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">유의사항 정보입력</td>
          <td>
			<span class="btn_pack small bold"><a href="javascript:mng_etc('2')"> 유의사항 </a></span> &nbsp;&nbsp; (<?=$TOUR_ETC2?> 등)
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


		<?
		for($j=1; $j < 5;$j++){
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
			<span class="btn_pack small bold fdrop<?=$FILE_NO?>"><a href="javascript:fedit('<?=$FILE_NO?>',0)"> 파일수정 </a></span>
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
          <td  class="subject">상품 담당자</td>
          <td>
            <?=html_textarea('staff',0,3)?>
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


	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>