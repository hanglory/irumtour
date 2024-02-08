<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_order";
$MENU = "order";
$TITLE = "예약접수관리";



#### mode
if($mode=="save"){

	$tour_option_price= str_replace(",","",$tour_option_price);
	$price= str_replace(",","",$price);
	$phone = "${phone1}-${phone2}-${phone3}";
	$cell = "${cell1}-${cell2}-${cell3}";


	$sql="
	   update $table set
			tour_date = '$tour_date',
			adult = '$adult',
			with_order = '$with_order',
			price = '$price',
			tour_options = '$tour_options',
			tour_option_price = '$tour_option_price',
			name = '$name',
			name_eng = '$name_eng',
			name_eng2 = '$name_eng2',
			cell1 = '$cell1',
			cell2 = '$cell2',
			cell3 = '$cell3',
			cell = '$cell',
			status = '$status',
			date_pay = '$date_pay',
			date_repay = '$date_repay',
			date_send = '$date_send',
			email = '$email',
			pwd = '$pwd',
			memo = '$memo'
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

	$rs[tour_option_price]= number_format($rs[tour_option_price]);
	$rs[price]= number_format($rs[price]);
}
//-------------------------------------------------------------------------------
?>
<?include("../top_min.html");?>
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

<div style="text-align:center">

		<table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
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


      <table border="0" cellspacing="1" cellpadding="3" width="98%" align="center">

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
			<?=html_input("tour_date",15,15)?>
          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">총인원</td>
          <td>
			<?=html_input("adult",3,3,'box numberic')?>명
			&nbsp;&nbsp;&nbsp;
			<span class="btn_pack small bold"><a href="javascript:newWin('list_people.php?oid=<?=$rs[oid]?>',1000,600,1,1)"> 여행자 정보 </a></span>
          </td>

          <td class="subject" width="20%">예약자 포함 여부</td>
          <td>
			<label><input type="checkBox" name="with_order" value="1" <?=($rs[with_order])?"checked":""?>>예약자 포함</label>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">상품금액</td>
          <td colspan="3">
			<?=html_input("price",20,30)?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">추가라운드</td>
          <td colspan="3">
			<?If($rs[tour_options]==":::선택하세요:::") $rs[tour_options] ="";?>
			<?=html_input("tour_options",20,50)?>	  +( 추가금액: <?=html_input("tour_option_price",10,50,'box numberic')?>원)
          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject l" colspan="4" style="background-color:#fff;padding-left:10px">* 예약자정보</td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">한글이름</td>
          <td>
			<?=html_input("name",30,30)?>
          </td>

          <td class="subject" width="20%">영문이름</td>
          <td>
			성: <?=html_input("name_eng",10,40)?>
			이름 : <?=html_input("name_eng2",10,40)?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">전화번호</td>
          <td>
			<?=html_input("cell1",6,4)?>-<?=html_input("cell2",6,4)?>-<?=html_input("cell3",6,4)?>
          </td>

          <td class="subject" width="20%">휴대폰번호</td>
          <td>
			<?=html_input("cell1",6,4)?>-<?=html_input("cell2",6,4)?>-<?=html_input("cell3",6,4)?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">이메일</td>
          <td>
			<?=html_input("email",30,50)?>
          </td>

          <td class="subject" width="20%">비밀번호</td>
          <td>
			<?=html_input2("pwd",20,20)?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">요청사항</td>
          <td colspan="3">
			<?=nl2br($rs[message])?>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>

        <tr>
          <td class="subject l" colspan="4" style="background-color:#fff;padding-left:10px">* 상품정보</td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">기본일정</td>
          <td>
			<?=$rs[days]?>
          </td>

          <td class="subject" width="20%">상품가격</td>
          <td>
			<?=number_format($rs[price_origin])?>원
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">추가라운드</td>
          <td>
			<?=$rs[tour_options]?>
          </td>

          <td class="subject" width="20%">이용항공/좌석</td>
          <td>
			<?=$rs[air_name]?>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">포함내역</td>
          <td colspan="3">
			<?=$rs[content1]?>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">불포함내역</td>
          <td colspan="3">
			<?=$rs[content2]?>
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
          <td class="subject" width="20%">결제 수단</td>
          <td  colspan="3">
			<?=get_payassort($rs[pay_assort])?> <?=$rs[inputer]?>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">결제 정보</td>
          <td  colspan="3">
			<?=($rs[pay_info])?$rs[pay_info]:"결제 정보 없음"?>
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
				<td><span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span></td>
		    </tr>
		  </table>
		  <!-- Button End------------------------------------------------>
		</td>
		</tr>
      </table>

	</form>

	<!--내용이 들어가는 곳 끝-->

</div>


<!-- Copyright -->
<?include_once("../bottom_min.html");?>