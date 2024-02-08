<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_pack_request";
$MENU = "order";
$TITLE = "셀프견적서";



#### mode
if($mode=="save"){

	$sql="
	   update ez_pack_request set
		  staff = '$staff',
		  memo = '$memo',
		  status = '$status'
	   where id_no='$id_no'
	";

	$url = "view_${filecode}.php?id_no=$id_no";

	//checkVar("",$sql);exit;

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
	redirect2("list_${filecode}.php");exit;



}elseif ($mode=="copy"){

	$code = ($code)? $code : getUniqNo();
	$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$sql = "select * from ez_pack_request where id_no=$id_no";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	if($rs[id_no]){

		$bit_single = ($rs[bit_single])?"T":"F";

		$memo = "문의날자 : $rs[reg_date] \n";
		$memo .= "출발일 : $rs[date_s] \n";
		$memo .= "체류일자 : $rs[days1] \n";
		$memo .= "여행인원 : $rs[date_s] \n";
		$memo .= "희망국가/지역 : $rs[nation] / $rs[city] \n";
		$memo .= "실글룸사용여부 : $bit_single \n";

		$memo =addslashes($memo);

		$view_path = "투어문의";

		$sql="
			insert into cmp_estimate (
			   view_path,
			   staff,
			   code,
			   people,
			   name,
			   phone,
			   email,
			   memo,
			   reg_date,
			   reg_date2
		   ) values (
			   '$view_path',
			   '$staff',
			   '$code',
			   '$rs[people]',
			   '$rs[name]',
			   '$rs[phone]',
			   '$rs[email]',
			   '$memo',
			   '$reg_date',
			   '$reg_date2'
		 )";
		 if($dbo->query($sql)){
			error("데이터를 견적서로 복사했습니다.");
		 }else{
		 	checkVar(mysql_error(),$sql);exit;
		 }

	}

	back();exit;


}elseif ($mode=="file_drop"){
		$sql = "update $table set $mode2 ='' where id_no=$id_no";
		$dbo->query($sql);
		redirect2("?id_no=$id_no&$_SESSION[link]");exit;

}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
}
//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(){

	var fm  =document.fmData;

	fm.submit();
}

function data_copy(){
	if(confirm('견적서로 데이터를 보내시겠습니까?')){
		location.href="<?=SELF?>?mode=copy&id_no=<?=$rs[id_no]?>";
	}
}
//-->
</script>

<?include("../top.html");?>



		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
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

		<form name="fmData" method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value="save">
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="code" value='<?=$code?>'>


		<tr><td colspan="4"  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan="4" class='bar'></td></tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">문의날짜</td>
          <td>
			<?=$rs[reg_date]?>
          </td>

          <td class="subject" width="20%">출발일</td>
          <td>
			<?=$rs[date_s]?>
          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">체류일수</td>
          <td>
			<?=$rs[days1]?>
          </td>

          <td class="subject" width="20%">여행인원</td>
          <td>
			<?=$rs[people]?>명
          </td>
        </tr>



        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">희망국가</td>
          <td>
			<?=$rs[nation]?>
          </td>

          <td class="subject" width="20%">희망지역</td>
          <td>
			<?=$rs[city]?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">성명</td>
          <td>
			<?=$rs[name]?>
          </td>

          <td class="subject" width="20%">핸드폰</td>
          <td>
			<?=$rs[cell]?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">싱글룸사용여부</td>
          <td colspan="3">
			<?=($rs[bit_single])?"T":"F"?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">확인처리자</td>
          <td colspan="3">
			<?=html_input('staff',30,30)?>  처리결과 : <select name="status"><?=option_str("접수,처리중,완료","접수,처리중,완료",$rs[status])?></select>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">관리자메모</td>
          <td colspan="3">
			<?=html_textarea('memo',80,5)?>
          </td>
        </tr>


        <tr><td colspan="4" bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan="4" bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
		<td colspan="4">
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="250" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
				<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
				<td><span class="btn_pack medium bold"><a href="javascript:data_copy()"> 견적서 이동 </a></span></td>
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

