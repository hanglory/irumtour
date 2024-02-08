<?
include_once("../include/common_file.php");

if($mode=="cal_sum"){

	$cal_price_origin = rnf($cal_price_origin);
	$cal_price_rate = trim($cal_price_rate);
	$cal_price2 = rnf($cal_price2);
	$cal_price3 = rnf($cal_price3);
	$cal_price1_b = rnf($cal_price1_b);
	$cal_price1_b2 = rnf($cal_price1_b2);
	$cal_price1 = $cal_price_origin * $cal_price_rate;

	$cal_amount = $cal_price1 + $cal_price1_b + $cal_price1_b2 + $cal_price2 + $cal_price3;

	$cal_price_origin_ = nf($cal_price_origin);
	$cal_price1_ = nf($cal_price1);
	$cal_price2_ = nf($cal_price2);
	$cal_price3_ = nf($cal_price3);
	$cal_amount_ = nf($cal_amount);

	$cal_price_origin_b_ = nf($cal_price_origin_b);
	$cal_price1_b_ = nf($cal_price1_b);
	$cal_price1_b2_ = nf($cal_price1_b2);

	echo "
		<script>
			parent.document.getElementById('cal_price_origin').value='$cal_price_origin_';
			parent.document.getElementById('cal_price1').value='$cal_price1_';
			parent.document.getElementById('cal_price2').value='$cal_price2_';
			parent.document.getElementById('cal_price3').value='$cal_price3_';
			parent.document.getElementById('cal_amount').value='$cal_amount_';

			parent.document.getElementById('cal_price1_b').value='$cal_price1_b_';
			parent.document.getElementById('cal_price1_b2').value='$cal_price1_b2_';
		</script>
	";
	exit;

}
?>
<?include("../top_min.html");?>
<script language="JavaScript">
function cal_sum(){
	var url = "<?=SELF?>?mode=cal_sum&id_no=<?=$id_no?>";
	url +="&cal_price_origin="+ $("#cal_price_origin").val();
	url +="&cal_price_rate="+ $("#cal_price_rate").val();
	url +="&cal_price2="+ $("#cal_price2").val();
	url +="&cal_price3="+ $("#cal_price3").val();
	url +="&cal_price1_b="+ $("#cal_price1_b").val();
	url +="&cal_price1_b2="+ $("#cal_price1_b2").val();

	actarea.location.href=url;
}

jQuery(function($){

	$(".num").keypress(function(event){
		if(event.which && (event.which < 48 || event.which > 57)){
			event.preventDefault();
		}
	});


	$("#tbl_normal input").on("keyup",function(){
		cal_sum();
	});

	$("#tbl_normal input").on("focus",function(){
		this.select();
	});


});
</script>
<style type="text/css">
.readonly{border:0}
</style>

<div style="padding:0 10px 0 10px">

	<table width="97%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con" style="padding-left:10px"><img src="../images/common/ic_title.gif" align="absmiddle">판매가계산기</td>
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


        <tr>
          <td colspan="4">
				<table id="tbl_normal" style="width:99%">
					<tr>
						<th>구분</th>
						<th>외화</th>
						<th>원화</th>
					</tr>
					<tr>
						<th>지상비1</th>
						<td>외화 : <?=html_input("cal_price_origin",10,10,'box numberic')?> X 환율: <?=html_input("cal_price_rate",10,10,'box numberic')?></td>
						<td><?=html_input("cal_price1",15,15,'box readonly numberic')?></td>
					</tr>
					<tr>
						<th>지상비2</th>
						<td><!-- <?=html_input("cal_price_origin_txt",36,40)?> --></td>
						<td><?=html_input("cal_price1_b",15,15,'box numberic')?></td>
					</tr>
					<tr>
						<th>지상비3</th>
						<td><!-- <?=html_input("cal_price_origin_txt2",36,40)?> --></td>
						<td><?=html_input("cal_price1_b2",15,15,'box numberic')?></td>
					</tr>
					<tr>
						<th>항공</th>
						<td></td>
						<td><?=html_input("cal_price2",15,15,'box numberic ')?></td>
					</tr>
					<tr>
						<th>수수료</th>
						<td></td>
						<td><?=html_input("cal_price3",15,15,'box numberic ')?></td>
					</tr>
					<tr>
						<th>판매가</th>
						<th></th>
						<th><?=html_input("cal_amount",15,15,'box readonly numberic ')?></th>
					</tr>
					</table>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
		  <td colspan=10>
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="370" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td>
						<span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
					</td>
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