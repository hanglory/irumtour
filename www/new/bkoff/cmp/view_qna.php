<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"문의고객관리대장");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_qna";
$MENU = "cmp_basic";
$TITLE = "문의 고객 관리 대장";
$file_rows = 1; //파일 업로드 갯수

#### staff
$sql = "select * from cmp_staff where bit_hide<>1 order by name asc";
$dbo->query($sql);
while($rs=$dbo->next_record()){
	$STAFF.=",$rs[name] ($rs[id])";
}

#### operation


if ($mode=="save"){

	$staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');



	$sqlInsert="
	   insert into cmp_qna (
		  qdate,
		  name,
		  phone,
		  fax,
		  email,
		  nation,
		  people,
		  period,
		  qlevel,
		  staff,
		  content,
		  reg_date,
		  reg_date2,
		  main_staff
	  ) values (
		  '$qdate',
		  '$name',
		  '$phone',
		  '$fax',
		  '$email',
		  '$nation',
		  '$people',
		  '$period',
		  '$qlevel',
		  '$staff',
		  '$content',
		  '$reg_date',
		  '$reg_date2',
		  '$main_staff'
	)";


	$sqlModify="
	   update cmp_qna set
		  qdate = '$qdate',
		  name = '$name',
		  phone = '$phone',
		  fax = '$fax',
		  email = '$email',
		  nation = '$nation',
		  people = '$people',
		  period = '$period',
		  content = '$content',
		  qlevel = '$qlevel',
		  main_staff = '$main_staff'
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

		//If($link){redirect2($link);exit;}

		If($id_no) echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();history.back(-1)</script>";
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


}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();

}

if(!$rs[id_no]) $rs[main_staff] ="$sessLogin[name] ($sessLogin[id])";
?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;

	if(check_blank(fm.qdate,'일자를',0)=='wrong'){return }
	if(check_blank(fm.name,'고객명을',0)=='wrong'){return }
	fm.submit();

}

jQuery(function($){

	$(".num").keypress(function(event){
		if(event.which && (event.which < 48 || event.which > 57)){
			event.preventDefault();
		}
	});

});
</script>

<div style="padding:0 10px 0 10px;width:99%">

	<table width="98%" border="0" cellspacing="0" cellpadding="0">
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

    <table border="0" cellspacing="1" cellpadding="3" width="98%">
		<form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td  class="subject" width="20%">일자</td>
          <td colspan="3">
            <?=html_input('qdate',13,10,'box dateinput')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">고객명</td>
          <td colspan="3">
	           <?=html_input('name',30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">연락처</td>
          <td>
	           <?=html_input('phone',30,50)?>
          </td>
          <td  class="subject">팩스</td>
          <td>
	           <?=html_input('fax',30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">이메일</td>
          <td>
	           <?=html_input('email',30,50)?>
          </td>

          <td  class="subject">국가</td>
          <td>
	           <select name="nation">
				<option value="">선택</option>
				<?=option_str($NATIONS,$NATIONS,$rs[nation])?>
			   </select>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">인원</td>
          <td>
	           <?=html_input('people',3,3,'box num')?>
          </td>

          <td  class="subject">수준</td>
          <td>
	           <?=radio("상,중,하","상,중,하",$rs[qlevel],'qlevel')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">기간</td>
          <td colspan="3">
	           <?=html_input('period',20,30)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">담당자</td>
          <td colspan="3">
	           <select name="main_staff">
				<?=option_str("선택".$STAFF,$STAFF,$rs[main_staff])?>
			   </select>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">상담내역</td>
          <td colspan="3">
            <?=html_textarea('content',0,5)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr><td colspan="4" bgcolor='#E1E1E1' height=3></td></tr>

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