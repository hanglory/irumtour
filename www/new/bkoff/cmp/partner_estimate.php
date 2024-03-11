<?
include_once("../include/common_file.php");
include_once "../../SMS/xmlrpc.inc.php";
include_once "../../SMS/class.EmmaSMS.php";
//include_once ("../../include/fun_alim.php");

chk_power($_SESSION["sessLogin"]["proof"],"간략견적요청");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_partner_request";
$MENU = "cmp_basic";
$TITLE = "간략견적요청";



#### staff
$sql = "select * from cmp_staff where bit_hide<>1 order by name asc";
$dbo->query($sql);
while($rs=$dbo->next_record()){
	$STAFF.=",$rs[name] ($rs[id])";
}


#### operation
if ($mode=="save"){

	$phone = "${phone1}-${phone2}-${phone3}";

	$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";
	$id = $_SESSION['sessLogin']['id'];


	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$phone = "${phone1}-${phone2}-${phone3}";

	For($i=0; $i<count($area1);$i++){
		$area .="," . $area1[$i];
	}
	For($i=0; $i<count($area2);$i++){
		$area .="," . $area2[$i];
	}
	For($i=0; $i<count($area3);$i++){
		$area .="," . $area3[$i];
	}
	For($i=0; $i<count($area4);$i++){
		$area .="," . $area4[$i];
	}
	For($i=0; $i<count($area5);$i++){
		$area .="," . $area5[$i];
	}


	$area = secu(str_replace(",,",",",substr($area,1)));

	$ip=$_SERVER[REMOTE_ADDR];

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$sqlInsert="
	   insert into cmp_partner_request (
	      id,
	      date_s,
	      date_e,
	      people,
	      nation,
	      area,
	      org,
	      name,
	      phone,
	      email,
	      assort,
	      assort_memo1,
	      assort_memo2,
	      content,
	      etc1,
	      etc2,
	      etc3,
	      etc4,
	      etc5,
	      memo,
	      status,
	      reg_date,
	      reg_date2,
	      ip
	  ) values (
	      '$id',
	      '$date_s',
	      '$date_e',
	      '$people',
	      '$nation',
	      '$area',
	      '$org',
	      '$name',
	      '$phone',
	      '$email',
	      '$assort',
	      '$assort_memo1',
	      '$assort_memo2',
	      '$content',
	      '$etc1',
	      '$etc2',
	      '$etc3',
	      '$etc4',
	      '$etc5',
	      '$memo',
	      '$status',
	      '$reg_date',
	      '$reg_date2',
	      '$ip'
	)";


	$sqlModify="
	   update cmp_partner_request set
	      date_s = '$date_s',
	      date_e = '$date_e',
	      people = '$people',
	      nation = '$nation',
	      area = '$area',
	      org = '$org',
	      name = '$name',
	      phone = '$phone',
	      email = '$email',
	      assort = '$assort',
	      assort_memo1 = '$assort_memo1',
	      assort_memo2 = '$assort_memo2',
	      content = '$content',
	      etc1 = '$etc1',
	      etc2 = '$etc2',
	      etc3 = '$etc3',
	      etc4 = '$etc4',
	      etc5 = '$etc5',
	      memo = '$memo',
	      status = '$status',
	      staff = '$staff',
	      ip = '$ip'
	   where id_no='$id_no'
	";

	if($id_no){
		$sql =$sqlModify;
	}else{
		$sql =$sqlInsert;
	}

	//checkVar(mysql_error(),$sql);exit;

	if($dbo->query($sql)){

		//$OFFICE_TEL_PARTNER = "01053985785";
		if($OFFICE_TEL_PARTNER){
			$sms_type = "L";
			$sms = new EmmaSMS();
			$sms->login($sms_id, $sms_passwd);
			$message = "파트너 간략견적 요청(${name}님)이 접수되었습니다.";
			if(!$id_no) $ret = $sms->send($OFFICE_TEL_PARTNER, $OFFICE_TEL, $message, $sms_date, $sms_type);
		}

		If($id_no) msggo("수정되었습니다..",$url);
		Else echo "<script type='text/javascript'>alert('접수되었습니다..');opener.location.reload();self.close()</script>";

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


}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
}

$rs[main_staff] =($rs[main_staff])?$rs[main_staff]:"$sessLogin[name] ($sessLogin[id])";
?>
<?include("../top_min.html");?>
<script language="JavaScript">
$(function(){
	$("[name=nation]").click(function(){
		var str = $(this).val();
		$("#area1").hide();$("#area2").hide();$("#area3").hide();$("#area4").hide();
		if(str=="동남아") $("#area1").show();
		else if(str=="일본") $("#area2").show();
		else if(str=="중국") $("#area3").show();
		else if(str=="기타해외") $("#area4").show();
		else if(str=="한국") $("#area5").show();
	});

	$(".assort").click(function(){
		$("#assort_tmp").val($(this).val());
	});

});
function chkForm()
{
	var fm = document.fmData;


	if(fm.nation_tmp.value=="") fm.nation_tmp.value="동남아";

	if(check_blank(fm.date_s,'출발일을',0)=='wrong'){return }
	if(check_blank(fm.date_e,'출발일을',0)=='wrong'){return }
	if(check_blank(fm.people,'예상인원을',0)=='wrong'){return }
	if(check_num(fm.people,'예상인원')=='wrong'){return }

	if(fm.nation_tmp.value==""){alert('희망국가를'); return}
	if(fm.nation_tmp.value=="동남아" && fm.area1_tmp.value==1){alert('희망지역을 선택해 주세요.'); return}
	if(fm.nation_tmp.value=="일본" && fm.area2_tmp.value==1){alert('희망지역을 선택해 주세요.'); return}
	if(fm.nation_tmp.value=="중국" && fm.area3_tmp.value==1){alert('희망지역을 선택해 주세요.'); return}
	if(fm.nation_tmp.value=="기타해외" && fm.area4_tmp.value==1){alert('희망지역을 선택해 주세요.'); return}
	if(fm.nation_tmp.value=="한국" && fm.area5_tmp.value==1){alert('희망지역을 선택해 주세요.'); return}

	if(check_blank(fm.org,'단체명을',0)=='wrong'){return }
	if(check_blank(fm.name,'성명을',0)=='wrong'){return }
	if(check_blank(fm.phone1,'연락처를',0)=='wrong'){return }
	if(check_blank(fm.phone2,'연락처를',0)=='wrong'){return }
	if(check_blank(fm.phone3,'연락처를',0)=='wrong'){return }
	if(check_num(fm.phone1,'연락처')=='wrong'){return }
	if(check_num(fm.phone2,'연락처')=='wrong'){return }
	if(check_num(fm.phone3,'연락처')=='wrong'){return }
	if(check_blank(fm.email,'이메일주소를',0)=='wrong'){return }
	if(check_email(fm.email)=='wrong'){return}

	if(fm.assort_tmp.value==""){alert('투어성격을 선택해 주세요.'); return}
	if(fm.etc1_tmp.value==""){alert('골프장종류를 선택해 주세요.'); return}
	if(fm.etc2_tmp.value==""){alert('호텔/골프장 수준을 선택해 주세요.'); return}
	if(fm.etc3_tmp.value==""){alert('식사수준을 선택해 주세요.'); return}
	if(fm.etc4_tmp.value==""){alert('객실수준을 선택해 주세요.'); return}
	if(fm.etc5_tmp.value==""){alert('싱글룸 사용여부를 선택해 주세요.'); return}


	fm.submit();

}
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

		<input type="hidden" name="assort_tmp" id="assort_tmp" value="">
		<input type="hidden" name="asm" id="asm" value="<?=session_id()?>">

		<tr><td colspan="4"  bgcolor='#5E90AE' height=2></td></tr>


		<tr>
			<th class="subject" scope="row">*문의날짜</th>
			<td colspan="3"><span class="red bold"><?=date("Y년 m월 d일")?></span></td>
		</tr>
		<tr><td colspan="4" class="tblLine"></td></tr>
		<tr>
			<th class="subject" scope="row">*여행출발일</th>
			<td colspan="3"><?=html_input("date_s",13,10,'box dateinput')?> ~ <?=html_input("date_e",13,10,'box dateinput')?></td>
		</tr>
		<tr><td colspan="4" class="tblLine"></td></tr>
		<tr>
			<th class="subject" scope="row">*예상인원</th>
			<td colspan="3"><?=html_input("people",5,5)?>명</td>
		</tr>
		<tr><td colspan="4" class="tblLine"></td></tr>


		<tr>
			<th class="subject" scope="row">*희망국가</th>
			<td colspan="3"><?=radio($EVENT_NATION,$EVENT_NATION,'동남아','nation')?></td>
		</tr>
		<tr><td colspan="4" class="tblLine"></td></tr>
		<tr>
			<th class="subject" scope="row">*희망지역</th>
			<td colspan="3">
				<div id="area1"><?=checkbox($EVENT_AREA1,$EVENT_AREA1,'','area1')?></div>
				<div id="area2" class="hide"><?=checkbox($EVENT_AREA2,$EVENT_AREA2,'','area2')?></div>
				<div id="area3" class="hide"><?=checkbox($EVENT_AREA3,$EVENT_AREA3,'','area3')?></div>
				<div id="area4" class="hide"><?=checkbox($EVENT_AREA4,$EVENT_AREA4,'','area4')?></div>
				<div id="area5" class="hide"><?=checkbox($EVENT_AREA5,$EVENT_AREA5,'','area5')?></div>
			</td>
		</tr>
		<tr><td colspan="4" class="tblLine"></td></tr>

		<tr>
			<th class="subject" rowspan="4" scope="row">*문의자정보</th>
			<td class="sub_tit" width="100">* 소속/단체명</td>
			<td><?=html_input("org",30,40)?></td>
		</tr>
		<tr>
			<td class="sub_tit">* 성명</td>
			<td><?=html_input("name",20,30)?></td>
		</tr>
		<tr>
			<td class="sub_tit">* 연락처</td>
			<td>
				<?=html_input("phone1",5,4)?> - <?=html_input("phone2",5,4)?> - <?=html_input("phone3",5,4)?>
			</td>
		</tr>
		<tr>
			<td class="sub_tit">* E-mail주소</td>
			<td><?=html_input("email",50,50)?></td>
		</tr>
		<tr><td colspan="4" class="tblLine"></td></tr>
		<tr>
			<th class="subject" scope="row">*투어성격</th>
			<td colspan="3">
				<div style="line-height:250%">
					<label><input name="assort" type="radio" value="개인투어" class="assort"> 개인투어</label><br/>
					<label><input name="assort" type="radio" value="비즈니스(접대)" class="assort">
					비즈니스(접대)</label> ( <?=html_input("assort_memo1",20,40)?> )<br/>
					<label><input name="assort" type="radio" value="단체행사" class="assort">
					단체행사</label> ( <?=html_input("assort_memo2",20,40)?> )
				</div>
			</td>
		</tr>
		<tr><td colspan="4" class="tblLine"></td></tr>
		<tr>
			<th class="subject" scope="row">*골프장종류</th>
			<td colspan="3"><?=radio($EVENT_ETC1,$EVENT_ETC1,'','etc1')?></td>
		</tr>
		<tr><td colspan="4" class="tblLine"></td></tr>
		<tr>
			<th class="subject" scope="row">*호텔/골프장수준</th>
			<td colspan="3"><?=radio($EVENT_ETC2,$EVENT_ETC2,'','etc2')?></td>
		</tr>
		<tr><td colspan="4" class="tblLine"></td></tr>
		<tr>
			<th class="subject" scope="row">*식사수준</th>
			<td colspan="3"><?=radio($EVENT_ETC3,$EVENT_ETC3,'','etc3')?></td>
		</tr>
		<tr><td colspan="4" class="tblLine"></td></tr>
		<tr>
			<th class="subject" scope="row">*객실수준</th>
			<td colspan="3"><?=radio($EVENT_ETC4,$EVENT_ETC4,'','etc4')?></td>
		</tr>
		<tr><td colspan="4" class="tblLine"></td></tr>
		<tr>
			<th class="subject" scope="row">*싱글룸 사용여부</th>
			<td colspan="3"><?=radio($EVENT_ETC5,$EVENT_ETC5,'','etc5')?></td>
		</tr>
		<tr><td colspan="4" class="tblLine"></td></tr>
		<tr>
			<th class="subject" scope="row">기타 요청사항</th>
			<td colspan="3"><textarea cols="30" rows="5" class="box"  name="content" style="width:96%; height:150px;"></textarea></td>
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