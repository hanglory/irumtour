<?
include_once("../include/common_file.php");


//chk_power($_SESSION["sessLogin"]["proof"],"견적서관리대장");


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_basic";
$LEFT_HIDDEN="1";
$TITLE = "상품정보크롤링";
//https://developers.band.us/develop/guide/api/get_posts


/*카페목록*/
$url = "https://openapi.band.us/v2.1/bands";
$url .="?access_token=".$NAVER_Access_Token;


$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, false);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
$error_msg = curl_error($ch);
curl_close($ch);

$json = json_decode($response,true);

$BAND_NAMES_1="";
$BAND_KEYS_1="";
$BAND_NAMES="";
$BAND_KEYS="";
while(list($key,$val)=each($json[result_data][bands])){
	while(list($key2,$val2)=each($val)){

		if($key2=="name") $BAND_NAMES .="{@}".$val2;
		if($key2=="band_key") $BAND_KEYS .="{@}".$val2;

		if(!$BAND_KEYS_1){
			if($key2=="name") $BAND_NAMES_1 =$val2;
			if($key2=="band_key") $BAND_KEYS_1 =$val2;
		}
	}
}

$band_key = ($band_key)? $band_key : $BAND_KEYS_1;

?>
<?include("../top.html");?>
<script language="JavaScript">
<!--
function copy_to_clip(val) {
  var t = document.createElement("textarea");
  document.body.appendChild(t);
  t.value = val;
  t.select();
  document.execCommand('copy');
  document.body.removeChild(t);
  alert("복사되었습니다.");
}

function change_band(){
	let fm = document.fm_find;
	fm.submit();
}

function load_band(){
	let after=$("#after").val();
	let bx_id=$("#bx_id").val();
	let no=$("#no").val();
	$("#btn_more").text("로딩중....");
	$('#sc_band'+bx_id).load('inc_naverband.php',{
	  'band_key':'<?=$band_key?>', 
	  'after':after,
	  'bx_id':bx_id,
	  'no':no
	});
}

$(function(){
	var e = document.getElementById("band_key");
	var band_name = e.options[e.selectedIndex].text;
	$("#sub_title").text(" > " + band_name);

	load_band();
});
//-->
</script>
<style type="text/css">
#btn_more{
	border:1px solid gray;
	background-color: #eee;
	text-align: center;
	padding: 14px;
	margin-top: 10px;
	cursor: pointer;
}	
</style>


	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?> <span id="sub_title"></span>
		</td>
	  </tr>
	  <tr>
		<td> </td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	</table>


	<!--내용이 들어가는 곳 시작-->

	<!-- Search Begin------------------------------------------------>
	<div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
	<form name="fm_find" method="get" action="<?=SELF?>">

		<tr height=22>
		<td><!-- <font color="#666666">* <?=($status)?> 자료수: <?=nf($row_search)?>개 <?if(!$seq_mode){?>{ <?=nf($total_page)?> page /  <?=nf($page)?> page }<?}?></font> --></td>
		<td valign='bottom' align=right>
		<?if($keyword || $find_bit):?>
		<input class=button type="button" value="초기화" onclick="location.href='<?=SELF?>'">
		<?endif;?>

		<select name="band_key" id="band_key" onchange="change_band()">
			<?=option_str("밴드선택".$BAND_NAMES,$BAND_KEYS,$band_key,'{@}')?>
		</select>

		<!-- <input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'> -->
		</td>
		<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>



	<div id="sc_band"></div>


	<input type="hidden" id="after" value="">
	<input type="hidden" id="bx_id" value="">
	<input type="hidden" id="no" value="">
	<div id="btn_more" onclick="load_band()">더보기</div>  

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>


