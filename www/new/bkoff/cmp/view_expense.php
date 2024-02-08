<?
include_once("../include/common_file.php");

$arr = explode("-",$code);

$year = $arr[0];
$month = $arr[1];
$category1 = $arr[2];
$category2 = $arr[3];
$category1_txt = $arr[4];
$category2_txt = $arr[5];


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_expense";
$MENU = "cmp_basic";
$TITLE = "지출내역 : ";
$TITLE .= " ${year}년";
$TITLE .= " ${month}월";
$TITLE .= " ${category1_txt} > ";
$TITLE .= " ${category2_txt}";

#### staff
$sql = "select * from cmp_staff where bit_hide<>1 order by name asc";
$dbo->query($sql);
while($rs=$dbo->next_record()){
	$STAFF.=",$rs[name] ($rs[id])";
}


#### operation
if ($mode=="save"){

	$reg_date = date('Y/m/d');
	$reg_date2 = date('H:i:s');

	if($price<0){
		$price = rnf($price);
		$price = 0- $price;
	}else{
		$price = rnf($price);
	}

	$sqlInsert="
		insert into $table (
           cp_id,
		   year,
		   month,
		   price,
		   memo,
		   reg_date,
		   reg_date2,
		   category1,
		   category2
		) values (
		   '$CP_ID',
           '$year',
		   '$month',
		   '$price',
		   '$memo',
		   '$reg_date',
		   '$reg_date2',
		   '$category1',
		   '$category2'
		)";


	$sqlModify="
		update $table set
		   year = '$year',
		   month = '$month',
		   price = '$price',
		   memo = '$memo',
		   category1 = '$category1',
		   category2 = '$category2'
		where id_no='$id_no'
            and cp_id='$CP_ID'
		";

	if($id_no){
		$sql =$sqlModify;
		$url = "view_${filecode}.php?id_no=$id_no&code=$code";
	}else{
		$sql =$sqlInsert;
		$url = "list_${filecode}.php?code=$code";
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

	$sql = "delete from $table where id_no = $id_no and cp_id='$CP_ID'";
	$dbo->query($sql);
	back();exit;


}else{
	$sql = "select * from $table where id_no=$id_no and cp_id='$CP_ID'";
	$dbo->query($sql);
	$rs= $dbo->next_record();
	$rs[price]	= nf($rs[price]);
}
?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
	var fm = document.fmData;

	if(check_blank(fm.price,'금액을',0)=='wrong'){return }
	fm.submit();

}

function del(id_no){
	if(confirm('삭제하시겠습니까?')){
		location.href="<?=SELF?>?mode=drop&id_no="+id_no+"&code=<?=$code?>";
	}
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

		<input type="hidden" name="code" value='<?=$code?>'>
		<input type="hidden" name="year" value='<?=$year?>'>
		<input type="hidden" name="month" value='<?=$month?>'>
		<input type="hidden" name="category1" value='<?=$category1?>'>
		<input type="hidden" name="category2" value='<?=$category2?>'>

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

		<tr>
          <td class="subject" width="15%">금액</td>
          <td>
	           <?=html_input('price',20,20,'box numberic')?> 원 ( 마이너스인 경우 숫자 앞에 마이너스 기호를 넣어 입력해 주세요. 예: "-1000" )
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
		<tr>
          <td class="subject">비고</td>
          <td>
	           <?=html_textarea('memo',0,20)?>
          </td>
        </tr>

        <tr><td colspan="12" bgcolor='#E1E1E1' height=1></td></tr>

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

	<center>

	    <table border="0" cellspacing="0" cellpadding="3" width="97%" id="tbl_cmp_list" align="center">
		    <tr align=center height=25 bgcolor="#F7F7F6"'>
			<th class="subject" width="10%">번호</th>
			<th class="subject" width="15%">금액</th>
			<th class="subject" >비고</th>
			<th class="subject"  width="15%">등록일</th>
			<th class="subject"  width="15%"></th>
			</tr>
	<?
	$i=1;

	$sql = "select * from cmp_expense where year=$year and month=$month and category1=$category1 and category2=$category2 and cp_id='$CP_ID' order by id_no desc";

	$dbo->query($sql);
	//checkVar(mysql_error(),$sql);
	while($rs=$dbo->next_record()){

	?>
		    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
		      <td height="30"><?=$i?></td>
		      <td><?=nf($rs[price])?>원</td>
		      <td style="text-align:left;padding-left:10px"><?=nl2br($rs[memo])?></td>
		      <td><?=$rs[reg_date]?></td>
		      <td>
				<span class="btn_pack medium bold"><a href="<?=SELF?>?id_no=<?=$rs[id_no]?>&code=<?=$code?>"> 수정 </a></span>	&nbsp;
				<span class="btn_pack medium bold"><a href="javascript:del('<?=$rs[id_no]?>')"> 삭제 </a></span>
			  </td>
		    </tr>
	<?
		$i++;
	}
	?>
		</table>

	</center>
	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>