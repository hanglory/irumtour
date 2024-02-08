<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_order";
$MENU = "order";
$TITLE = "예약접수관리";



#### mode
if($mode=="save"){

	$use_point= str_replace(",","",$use_point);
	$point= str_replace(",","",$point);
	$price= str_replace(",","",$price);

	$sql="
	   update $table set
			tour_date='$tour_date',
			people='$people',
			with_order='$with_order',
			use_point='$use_point',
			point='$point',
			pay_assort='$pay_assort',
			return_account_bank = '$return_account_bank',
			return_account_owner = '$return_account_owner',
			return_account = '$return_account',
			price='$price',
			name='$name',
			birth='$birth',
			cell='$cell',
			email='$email',
			zipcode='$zipcode',
			address='$address',
			message='$message',
			date_pay='$date_pay',
			date_repay='$date_repay',
			status='$status',
			date_send='$date_send',
			memo='$memo'
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


}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();

	$rs[use_point]= number_format($rs[use_point]);
	$rs[point]= number_format($rs[point]);
	$rs[price]= number_format($rs[price]);
}
//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(){

	var fm  =document.fmData;

	fm.submit();
}

function Addr_pop(fm,zipcode,address,nextctl){
	newWin("../../autoaddr.php?fm="+fm+"&zipcode="+zipcode+"&address="+address+"&nextctl="+nextctl, 400,400);
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


		<tr><td colspan="4"  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">상품명</td>
          <td colspan="3">
			<?=$rs[subject]?>
          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">예약일</td>
          <td>
			<?=$rs[reg_date]?>  (예약번호 : <?=$rs[oid]?>)
          </td>

          <td class="subject" width="20%">출발일</td>
          <td>
			<?=html_input("tour_date",10,10,'box dateinput')?>
          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">총인원</td>
          <td colspan="3">
			<?=html_input("people",3,3,'box numberic')?>명
			&nbsp;&nbsp;&nbsp;
			<span class="btn_pack small bold"><a href="javascript:newWin('list_people.php?oid=<?=$rs[oid]?>',800,600,1,1)"> 여행자 정보 </a></span>
          </td>

          <!-- <td class="subject" width="20%">예약자 포함 여부</td>
          <td>
			<label><input type="checkBox" name="with_order" value="1" <?=($rs[with_order])?"checked":""?>>예약자 포함</label>
          </td> -->
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>

        <tr>
          <td class="subject" width="20%">탑승지/숙박선택/인원</td>
          <td colspan="3">
				  <table width="97%" border="0" cellspacing="0" cellpadding="0">
				  <?
				  $sql2= "select distinct station from ez_order_select where oid='$rs[oid]'";
				  $dbo2->query($sql2);
				  While($rs2=$dbo2->next_record()){
				  ?>
				  <tr>
					<?If($rs2[station]){?><td width="13%" class="txt_black">-<?=$rs2[station]?></td><?}?>
					<td style="padding:6px 0px;">
					  <?
					  $sql3= "select * from ez_order_select where oid='$rs[oid]' and station='$rs2[station]' order by seq asc";
					  $dbo3->query($sql3);
					  $i=0;
					  While($rs3=$dbo3->next_record()){
						If($i && $prev!=$rs3[subject]) echo "<br />";
					  ?>
						<span>
							<?If($rs3[subject]!="가격" && $prev!=$rs3[subject]){?><span class="txt_red">[<?=$rs3[subject]?>]</span><?}?>
							<strong><?=$rs3[assort]?></strong> <?=$rs3[qty]?>명
						</span>
					  <?
						$i++;
						$prev = $rs3[subject];
					  }
					  ?>
					</td>
				  </tr>
				  <?
				  }
				  ?>
				 </table>
          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">사용된 포인트</td>
          <td>
			<?=html_input("use_point",10,10,'box numberic')?>	Point
          </td>

          <td class="subject" width="20%">적립될 포인트</td>
          <td>
			<?=html_input("point",10,10,'box numberic')?> Point
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">최종결제금액</td>
          <td colspan="3">
			<?=html_input("price",10,10,'box numberic')?>원
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">결제방식</td>
          <td colspan="3">
			<?=radio($ORDER_PAYMETHOD,$ORDER_PAYMETHOD_VAL,$rs[pay_assort],"pay_assort")?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">환불계좌</td>
          <td colspan="3">
			은행:<?=html_input("return_account_bank",10,10)?>
			계좌번호:<?=html_input("return_account",20,20)?>
			예금주:<?=html_input("return_account_owner",10,10)?>
          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject l" colspan="4" style="background-color:#fff;padding-left:10px">* 예약자정보</td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">성명</td>
          <td>
			<?=html_input("name",30,50)?>
          </td>

          <td class="subject" width="20%">생년월일</td>
          <td>
			<?=html_input("birth",10,10,'box dateinput')?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">휴대폰번호</td>
          <td>
			<?=html_input("cell",30,30)?>
          </td>

          <td class="subject" width="20%">이메일주소</td>
          <td>
			<?=html_input("email",30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%" height="55">주소</td>
          <td colspan="3">
			<?=html_input('zipcode',7,7)?>
			<img src="/images/member/btn_zipcode.gif" align="absmiddle" class="hand" onclick="Addr_pop('document.fmData','zipcode','address','message')"><br />
			<?=html_input('address',80,200)?>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">남기실말씀</td>
          <td colspan="3">
			<?=html_textarea('message',80,5)?>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>


        <tr>
          <td class="subject l" colspan="4" style="background-color:#fff;padding-left:10px">* 관리정보</td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">상태</td>
          <td colspan="3">
			<select name="status">
				<?=option_str($ORDER_STATUS,$ORDER_STATUS,$rs[status])?>
			</select>
          </td>

        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">입금일</td>
          <td  colspan="3">
			<?=html_input("date_pay",50,50,'box')?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">환불일</td>
          <td>
			<?=html_input("date_repay",30,30,'box')?>
          </td>

          <td class="subject" width="20%">송금일</td>
          <td>
			<?=html_input("date_send",30,30,'box')?>
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

