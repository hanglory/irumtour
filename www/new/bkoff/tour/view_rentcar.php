<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour";
$MENU = "tour";
$TITLE = "렌트카관리";


#### operation

if($mode){

	for($i=0; $i <count($icons);$i++){
		if($icons[$i]) $icon .="," . str_replace(",","",addslashes($icons[$i]));
	}
	$icons = substr($icon,1);

	for($i=0; $i <count($local_company);$i++){
		if($local_company[$i]) $local_companys .="," . str_replace(",","",addslashes($local_company[$i]));
	}
	$local_company = substr($local_companys,1);

	$price_adult = ceil(str_replace(",","",$price_adult));
	$origin_price_adult = ceil(str_replace(",","",$origin_price_adult));
	$point = ceil(str_replace(",","",$point));
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
			$filename=time() . "_" . $i;		//파일이름 작명
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

	$sql="
		update $table set
			$upfileQuery[1]
			$upfileQuery[2]
			$upfileQuery[3]
			$upfileQuery[4]
			subject='$subject',
			icons = '$icons',
			region='$region',
			tour_company='$tour_company',
			sale_group='$sale_group',
			bit_jg='$bit_jg',
			local_company='$local_company',
			rent_type='$rent_type',
			rent_trans='$rent_trans',
			rent_area='$rent_area',
			rent_licence='$rent_licence',
			rent_seat_cnt='$rent_seat_cnt',
			rent_cc='$rent_cc',
			rent_period='$rent_period',
			rent_age='$rent_age',
			rent_career='$rent_career',
			origin_price_adult='$origin_price_adult',
			price_adult='$price_adult',
			point='$point',
			staff='$staff',
			memo='$memo',
			addprice_bit='$addprice_bit',
			bit = '$bit'
	   where tid=$tid
	";

	if($id_no){
		$url = "view_${filecode}.php?id_no=$id_no&ctg1=$ctg1";
	}else{
		$url = "list_${filecode}.php?ctg1=$ctg1";
	}

	if($dbo->query($sql)){
		msggo("저장하였습니다.",$url);
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

}

//default value
if(!$rs[id_no]){

	$tid=getUniqNo();

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$sql = "insert into $table (tid,category1,reg_date,reg_date2) values ($tid,'rentcar','$reg_date','$reg_date2')";
	$dbo->query($sql);

	$month = date("m");
	$rs[bit_jg]=1;

	$price_adult= number_format($price_adult);

	$origin_price_adult= number_format($rs[origin_price_adult]);
}
//-------------------------------------------------------------------------------
?>
<?include("../top.html");?>
<?include("../../include/tour_options.php");?>
<script type="text/javascript" src="../../cheditor/cheditor.js"></script>
<script type="text/javascript" src="../../include/bbs_frame.js"></script>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;
	if(check_blank(fm.subject,'차량명을',0)=='wrong'){return }

	fm.submit();

}

function mng_season(){
	var season = document.fmData.place_season_tmp.value;
	newWin('pop_place.php?tid=<?=$tid?>&season='+season,800,800,1,1);
}

function mng_train(){
	newWin('pop_train.php?tid=<?=$tid?>',850,800,1,1);
}


function mng_days(){
	newWin('pop_days.php?tid=<?=$tid?>',850,800,1,1);
}

function mng_days_detail(){
	newWin('pop_days02.php?tid=<?=$tid?>',850,800,1,1);
}

function mng_price(){
	var days = $("#days").val();
	newWin('pop_price.php?tid=<?=$tid?>&days='+days,950,800,1,1);
}

function mng_fee(){
	newWin('pop_agent_fee.php?tid=<?=$tid?>',850,800,1,1);
}

function mng_contents(assort){
	newWin('pop_contents.php?tid=<?=$tid?>&assort='+assort,850,800,1,1);
}

function mng_etc(div){
	newWin('pop_etc.php?tid=<?=$tid?>&div='+div,850,800,1,1);
}

</script>
<style type="text/css">
.subject_train{font-weight:normal;width:100px;text-align:right;padding-right:5px;}
</style>


	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
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

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td  class="subject" width="15%">차량명</td>
          <td>
            <?=html_input('subject',100,255)?>
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
          <td  class="subject">수수료 설정</td>
          <td>
			<span class="btn_pack small bold"><a href="javascript:mng_fee()"> 수수료 설정 </a></span>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">구분</td>
          <td>

			 <select name="region">
			 <option value="">지역</option>
			 <?=option_str($REGION,$REGION,$rs[region])?>
			 </select>

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

			 <label for="bit_cp">
				<input type="checkbox" name="bit_cp" id="bit_cp" value="1" <?=($rs[bit_cp])?'checked=checked':''?> /> CP

			 <label for="bit_a">
				<input type="checkbox" name="bit_a" id="bit_a" value="1" <?=($rs[bit_a])?'checked=checked':''?> /> A

			 <label for="bit_b">
				<input type="checkbox" name="bit_b" id="bit_b" value="1" <?=($rs[bit_b])?'checked=checked':''?> /> B

			 <label for="bit_c">
				<input type="checkbox" name="bit_c" id="bit_c" value="1" <?=($rs[bit_c])?'checked=checked':''?> /> C

          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">현지여행사</td>
          <td>
			<?
			$arr = explode(",",$rs[local_company]);
			for($i=0;$i<9;$i++){?>
			<input class="box" type="text" name="local_company[]" value="<?=$arr[$i]?>" size="10" maxlength="30">
			<?}?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">차종</td>
          <td>
            <?=radio($RENT_TYPE,$RENT_TYPE,$rs[rent_type],'rent_type')?>
		  </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">변속기</td>
          <td>
            <?=radio($RENT_TRANS,$RENT_TRANS,$rs[rent_trans],'rent_trans')?>
		  </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">인승</td>
          <td>
            <?=html_input('rent_seat_cnt',2,2)?>인승

			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

			<span  class="subject">배기량</span>
			<?=html_input('rent_cc',4,4)?>cc

			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

			<span  class="subject">사용연료</span>
			<?=radio($RENT_OIL,$RENT_OIL,$rs[rent_oil],'rent_oil')?>

			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

			<span  class="subject">대여기간</span>
			<?=html_input('rent_period',2,2)?>시간
		  </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">인도지역</td>
          <td>
            <?=html_input('rent_area',30,50)?></td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">자격조건</td>
          <td>
            <?=html_input('rent_age',2,2,'box numberic')?>세 이상 경력 <?=html_input('rent_career',2,2,'box numberic')?>년 이상</td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">가능면허</td>
          <td>
            <?=radio('2종보통,1종보통,1종대형','2종보통,1종보통,1종대형',$rs[rent_licence],'rent_licence')?></td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">할인전 가격</td>
          <td>
             <?=html_input('origin_price_adult',10,10,'box numberic')?>
			  * 입력하시면 사선이 그어진 상태로 표시됩니다.
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">표시 가격</td>
          <td>
             <?=html_input('price_adult',10,10,'box numberic')?>
			 <span style="width:20px"></span>
			 포인트 : <?=html_input('point',10,10,'box')?>Point
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject"><input type="checkbox" name="addprice_bit" value="1" <?=($rs[addprice_bit])?"checked='checked'":""?>>추가 가격</td>
          <td>

			<iframe width="100%" height="20" name="addprice" id="addprice"
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
			<span class="btn_pack small bold"><a href="javascript:mng_contents('10_information')"> 상품정보 </a></span>&nbsp;&nbsp
			<span class="btn_pack small bold"><a href="javascript:mng_contents('20_guide')"> 이용안내 </a></span>&nbsp;&nbsp
			<span class="btn_pack small bold"><a href="javascript:mng_contents('70_map')"> 찾아가는 길 </a></span>&nbsp;&nbsp
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">유의사항 정보입력</td>
          <td>
			<span class="btn_pack small bold"><a href="javascript:mng_etc('3')"> 유의사항 </a></span> &nbsp;&nbsp; (<?=$TOUR_ETC3?> 등)
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
			<input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=<?=$FILENAME?>&filename=<?=$rs[$FILENAME]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[$FILENAME]?>&orgin_file_name=<?=$rs[$FILENAME_REAL]?>&dir=public/goods&ctg1=<?=$ctg1?>" onFocus="blur(this)"><?=$rs[$FILENAME_REAL]?> (<?=ceil($fileSize/1024)?>KB)</a>
			<?else:?>
			<input class=box type="file" name="file<?=$FILE_NO?>" size=40>
			<font color="orange">Alert</font></b> <font color="#666666">: Max <?=$maxFileSize?>MB</font>
			<?endif;?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
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
					<td><span class="btn_pack medium bold"><a href="#" onClick="location.href='list_<?=$filecode?>.php?<?=$sessLink?>'"> 리스트 </a></span></td>
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
<?include_once("../bottom.html");?>