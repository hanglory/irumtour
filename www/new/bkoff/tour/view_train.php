<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour";
$MENU = "tour";
$TITLE = "열차관리(레일텔)";
$category1 = "train";


#### operation

if ($mode=="save"){

	$bit=1; //상품 활성화

	for($i=0; $i <count($station);$i++){
		if($station[$i]) $stations .="," . str_replace(",","",addslashes($station[$i]));
	}
	$station = substr($stations,1);

	$sql="
	   update $table set
			category1 = '$category1',
			subject = '$subject',
			memo = '$memo',
			staff = '$staff',
			station = '$station',
			bit = '$bit'
	   where tid=$tid
	";

	if($id_no){
		$url = "view_${filecode}.php?id_no=$id_no&ctg1=$ctg1";
	}else{
		$url = "list_${filecode}.php?ctg1=$ctg1";
	}

	if($dbo->query($sql)){

		If(!$msg_hidden){
			If($id_no) echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();history.back()</script>";
			Else echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();self.close()</script>";
		}

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
	$tid=$rs[tid];

}

#### default value
if(!$rs[id_no]){

	$tid=getUniqNo();

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	$sql = "insert into ez_tour (tid,reg_date,reg_date2) values ($tid,'$reg_date','$reg_date2')";
	$dbo->query($sql);


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

	fm.target="";
	fm.msg_hidden.value="0";

	if(check_blank(fm.subject,'열차명을',0)=='wrong'){return }

	fm.submit();

}

function mng_train(){
	var bstations="";
	var fm = document.fmData;

	fm.target="actarea";
	fm.msg_hidden.value="1";
	fm.submit();

	for(i=0; i<9;i++){
		if($("#station"+i).val()!="") bstations += "," + $("#station"+i).val();
	}
	station = bstations.substring(1)
	newWin("pop_train.php?tid=<?=$tid?>&bstations="+station,850,600,1,1);
}


function mng_fee(){
	var url;
	url  = "pop_agent_fee.php?tid=<?=$tid?>";
	newWin(url,850,600,1,1);
}
</script>

<div style="text-align:center">

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
		<form name="fmData" method=post enctype="multipart/form-data">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="tid" value='<?=$tid?>'>
		<input type="hidden" name="msg_hidden">

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td  class="subject">* 열차명</td>
          <td>
            <?=html_input('subject',40,60)?> 예) 서울→부산 KTX 111 08:00-11:00
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

		<tr>
          <td  class="subject">탑승지(출발역)</td>
          <td>
			<?
			$arr = explode(",",$rs[station]);
			for($i=0;$i<9;$i++){
			?>
			<input class="box" type="text" name="station[]" id="station<?=$i?>" value="<?=$arr[$i]?>" size="10" maxlength="30">
			<?}?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject"><span>기차 설정</span></td>
          <td>
			<span>
				<span class="btn_pack small bold"><a href="javascript:mng_train()"> 기차 설정 </a></span>
				<span style="width:10px"></span>
			</span>
			<!-- <span class="btn_pack small bold"><a href="javascript:mng_fee()"> 수수료 설정 </a></span> -->
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

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
					<td><span class="btn_pack medium bold"><a href="#" onClick="opener.location.reload();self.close()"> 창닫기 </a></span></td>
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