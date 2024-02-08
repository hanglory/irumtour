<?
include_once("../include/common_file.php");


//chk_power($_SESSION["sessLogin"]["proof"],"견적서관리대장");


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_basic";
$LEFT_HIDDEN="1";
$TITLE = "다음카페 검색";

//개발가이드 : https://developers.kakao.com/docs/latest/ko/daum-search/dev-guide#search-cafe
$query = $keyword;
$page = ($page)? $page : 1;
$view_row=20;

$query = urlencode($query);
$url = "https://dapi.kakao.com/v2/search/cafe";
$url .="?query=".$query;
$url .="&sort=$sort";
$url .="&page=$page";
$url .="&size=$view_row";

$headers = array(
    "Authorization: KakaoAK ".$KAKAO_RESET_KEY
);	
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, false);
//curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
$response = curl_exec($ch);
$error_msg = curl_error($ch);
curl_close($ch);

$json = json_decode($response,true);

// checkVar("error_msg",$error_msg);
// checkVar("검색어",$query);
// checkVar("검색된 문서 수",$json[meta][total_count]);
// checkVar("노출 가능 문서 수",$json[meta][pageable_count]);
// checkVar("현재 페이지가 마지막 페이지인지 여부",$json[meta][is_end]);


$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;
$row_search = $json[meta][pageable_count];
$var=ceil($row_search/$view_row);
if ($var > 1){
	$total_page=$var;
}
else{
	$total_page=1;
}
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}
$link = "keyword=$keyword&sort=$sort";

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
//-->
</script>


	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?>

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
	<form name="fmSearch" method="get" action="<?=SELF?>">
	<input type="hidden" name='position' value="">
	<input type="hidden" name='ctg1' value="<?=$ctg1?>">


	<tr height=22>
	<td><font color="#666666">* <?=($status)?> 자료수: <?=nf($row_search)?>개 <?if(!$seq_mode){?>{ <?=nf($total_page)?> page /  <?=nf($page)?> page }<?}?></font></td>
	<td valign='bottom' align=right>
	<?if($keyword || $find_bit):?>
	<input class=button type="button" value="초기화" onclick="location.href='<?=SELF?>'">
	<?endif;?>

	<select name="sort">
		<?=option_str("최신순,정확도순","recency,accuracy",$sort)?>
	</select>

	<input class=box type="text" name="keyword" size="25" maxlength="40" value='<?=$keyword?>' placeholder="키워드">
	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>


<?if($row_search){?>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align=center height="30" bgcolor="#F7F7F6">
			<th class="subject">번호</th>
			<th class="subject">카페이름</th>
			<th class="subject">카페글</th>
			<th class="subject">작성시간</th>
		</tr>
<?
	while(list($key,$val)=each($json[documents])){
		while(list($key2,$val2)=each($val)){
			//checkVar($key2,$val2);
			$DATA[$key2] = $val2;
		}	
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><?=$num?></td>
	      <td><?=$DATA["cafename"]?></td>
	      <td>
	      	<div style="padding:10px;text-align:left;">
		      	<?if($DATA["url"]){?><a href="<?=$DATA["url"]?>" target="_blank"><?}?>
		      			<?if($DATA["thumbnail"]){?><p style="float:left;padding:0 10px 10px 0"><img src="<?=$DATA["thumbnail"]?>" height="70"></p><?}?>
		      			<div style="font-weight:bold;margin-bottom:5px"><?=$DATA["title"]?></div>
		      			<div style="font-weight:normal;"><?=$DATA["contents"]?></div>
		      	<?if($DATA["url"]){?></a><?}?>
	      	</div>
	      </td>
	      <td><?=substr($DATA["datetime"],0,10)?> <?=substr($DATA["datetime"],11,8)?></td>
	    </tr>
<?
		$num--;
	}
?>
	</table>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
			<!-- navigation Begin---------------------------------------------->
			<?include_once('../../include/navigation.php')?>
			<?=$navi?>
			<!-- navigation End------------------------------------------------>
		  </td>
        </tr>
	</table>
<?}else{?>
	<table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
		<tr>
		  <td colspan="12"  align=center style="padding-top:20px">
			<div style="border:1px solid #ccc;padding:50px">검색어를 입력하세요.</div>
		  </td>
        </tr>
	</form>
	</table>
<?}?>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>


