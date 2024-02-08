<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_air";
$MENU = "tour";
$TITLE = "항공권 관리";


#### mode
if($mode=="save"){

	$price_origin_adult = price_format($price_origin_adult);
	$price_origin_baby = price_format($price_origin_baby);
	$price_origin_child = price_format($price_origin_child);
	$price_adult = price_format($price_adult);
	$price_origin_baby = price_format($price_origin_baby);
	$price_child = price_format($price_child);

	$period_start = "${period_s} ${period_s2}:${period_s3}";
	$period_end = "${period_e} ${period_e2}:${period_e3}";

	If($period_start  >= $period_end){
		Error("날짜 설정이 잘못되었습니다 .\\n\\n출발일시와 도착일시가 같거나 도착일이 출발일 이전입니다.");
		exit;
	}

	If($go_return=="편도"){
		$place2 = "";
		$period_e = "";
		$period_e2 = "";
		$period_e3 = "";
	}else{
		$days = get_day_night($period_s,$period_e);
	}

	$sqlInsert="
	   insert into ez_air (
		  code,
		  company,
		  go_return,
		  place1,
		  place2,
		  period_s,
		  period_s2,
		  period_s3,
		  period_e,
		  period_e2,
		  period_e3,
		  price_origin_adult,
		  price_origin_child,
		  price_origin_baby,
		  price_adult,
		  price_child,
		  price_baby,
		  days
	  ) values (
		  '$code',
		  '$company',
		  '$go_return',
		  '$place1',
		  '$place2',
		  '$period_s',
		  '$period_s2',
		  '$period_s3',
		  '$period_e',
		  '$period_e2',
		  '$period_e3',
		  '$price_origin_adult',
		  '$price_origin_child',
		  '$price_origin_baby',
		  '$price_adult',
		  '$price_child',
		  '$price_baby',
		  '$days'
	)";

	$sqlModify="
	   update ez_air set
		  code = '$code',
		  company = '$company',
		  go_return = '$go_return',
		  place1 = '$place1',
		  place2 = '$place2',
		  period_s = '$period_s',
		  period_s2 = '$period_s2',
		  period_s3 = '$period_s3',
		  period_e = '$period_e',
		  period_e2 = '$period_e2',
		  period_e3 = '$period_e3',
		  price_origin_adult = '$price_origin_adult',
		  price_origin_child = '$price_origin_child',
		  price_origin_baby = '$price_origin_baby',
		  price_adult = '$price_adult',
		  price_child = '$price_child',
		  price_baby = '$price_baby',
		  days = '$days'
	   where id_no='$id_no'
	";

	if($id_no){
		$sql = $sqlModify;
		$url = "view_${filecode}.php?id_no=$id_no";
	}else{
		$sql = $sqlInsert;
		$url = "view_${filecode}.php";
	}

	//checkVar("",$sql);exit;

	if($dbo->query($sql)){
		echo "<script>alert('저장하였습니다.');parent.location.href='$url'</script>";
		//msggo("저장하였습니다.",$url);
	}else{
		checkVar(mysql_error(),$sql);
	}
	exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){

		$sql = "delete from $table where code = $check[$i]";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php");exit;

}elseif ($mode=="file_drop"){
		$sql = "update $table set $mode2 ='' where id_no=$id_no";
		$dbo->query($sql);
		@unlink("../../public/air/$filename");
		redirect2("?id_no=$id_no&$_SESSION[link]");exit;

}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
	if($rs[id_no]){
	$rs[price_origin_adult] = number_format($rs[price_origin_adult]);
	$rs[price_origin_child] = number_format($rs[price_origin_child]);
	$rs[price_origin_baby] = number_format($rs[price_origin_baby]);
	$rs[price_adult] = number_format($rs[price_adult]);
	$rs[price_child] = number_format($rs[price_child]);
	$rs[price_baby] = number_format($rs[price_baby]);
	}
}

$code = ($rs[code])?$rs[code] : getUniqNo();
$rs[go_return] = ($rs[go_return])? $rs[go_return] : "왕복";
//-------------------------------------------------------------------------------
?>
<?include("../top.html");?>
<script language="JavaScript">
<!--
function chkForm(){

	var fm  =document.fmData;

	if(fm.company_tmp.value==""){alert("항공사를 선택해주세요.");return }
	if(check_select(fm.place1,'출발지를')=='wrong'){return }
	if(check_select(fm.place2,'도착지를')=='wrong'){return }
	if(check_blank(fm.price_adult,'성인 가격을',0)=='wrong'){return }
	if(check_blank(fm.price_child,'소인 가격을',0)=='wrong'){return }
	if(check_blank(fm.price_baby,'유아 가격을',0)=='wrong'){return }
	fm.submit();
}

$(function(){

	var go_return = "<?=$rs[go_return]?>";
	if(go_return=="왕복") $(".return").css("display","block");

	$(".go_return").click(function(){
		if($(this).val()=="왕복") $(".return").css("display","block");
		else $(".return").css("display","none");
	});
});

//-->
</script>



		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=($mode=="sub")?"${rs[parent_company]}의 소속":""?><?=$TITLE?></td>
			</tr>
			<tr>
				<td colspan="3"> </td>
			</tr>
			<tr>
				<td background="../images/common/bg_title.gif" height="5"></td>
			</tr>
		</table>


		<br>


      <table border="0" cellspacing="1" cellpadding="3" width="750">

		<form name="fmData" method="post" enctype="multipart/form-data" target="actarea">
		<input type="hidden" name="mode" value="save">
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="code" value='<?=$code?>'>


		<tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan="2" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">항공사</td>
          <td>
			<?=radio($AIR,$AIR,$rs[company],'company')?>
          </td>
        </tr>

        <tr><td colspan="2" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">편도/왕복</td>
          <td>
			<?=radio("편도,왕복","편도,왕복",$rs[go_return],'go_return')?>
          </td>
        </tr>

        <tr><td colspan="2" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">출발일시(가는 편)</td>
          <td>
			<select name="place1"><option value="">선택</option><?=option_str($AIRPORT,$AIRPORT,$rs[place1])?></select>

			<?=html_input('period_s',10,30,'box dateinput readonly')?>
			<select name="period_s2">
				<option value=""></option>
				<?=option_int(1,23,1,$rs[period_s2],2);?>
			</select>
			<select name="period_s3">
				<?=option_int(0,59,1,$rs[period_s3],2);?>
			</select>

          </td>
        </tr>

        <tr class="return"><td colspan="2" class='bar'></td></tr>
        <tr class="return">
          <td class="subject" width="20%">출발일시(돌아오는 편)</td>
          <td>
			<select name="place2"><option value="">선택</option><?=option_str($AIRPORT,$AIRPORT,$rs[place2])?></select>

			<?=html_input('period_e',10,30,'box dateinput readonly')?>
			<select name="period_e2">
				<option value=""></option>
				<?=option_int(1,23,1,$rs[period_e2],2);?>
			</select>
			<select name="period_e3">
				<?=option_int(0,59,1,$rs[period_e3],2);?>
			</select>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td class="subject">성인</td>
          <td>
			 원가격 : <?=html_input('price_origin_adult',10,10,'box numberic')?> - 실제가격 <?=html_input('price_adult',10,10,'box numberic')?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td class="subject">소아</td>
          <td>
			 원가격 : <?=html_input('price_origin_child',10,10,'box numberic')?> - 실제가격 <?=html_input('price_child',10,10,'box numberic')?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td class="subject">유아</td>
          <td>
			 원가격 : <?=html_input('price_origin_baby',10,10,'box numberic')?> - 실제가격 <?=html_input('price_baby',10,10,'box numberic')?>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>


        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan=2 bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
		<td colspan=2>
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
      </table>

	</form>

	<!--내용이 들어가는 곳 끝-->


<!-- Copyright -->
<?include_once("../bottom.html");?>

