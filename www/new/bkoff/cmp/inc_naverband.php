<?
include_once("../include/common_file.php");
?>
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



    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

	    <tr align=center height="30" bgcolor="#F7F7F6">
			<th class="subject">번호</th>
			<th class="subject">작성자</th>
			<th class="subject">게시물</th>
			<th class="subject">작성시간</th>
		</tr>
<?
	/*카페목록*/
	if(!$band_key) $band_key= $BAND_KEYS_1;
	$url = "https://openapi.band.us/v2/band/posts";
	$url .="?access_token=".$NAVER_Access_Token;
	$url .="&band_key=".$band_key;
	if($after) $url .="&after=".$after;
	$url .="&limit=20";
	$url .="&locale=ko_KR"; //ko_KR

//    if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar("url",$url);}

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

	$after =$json[result_data][paging][next_params][after];
	//checkVar("after",$after);

	unset($DATA);
	$i=0;
	if($no) $i+=($no);
	while(list($key,$val)=@each($json[result_data][items])){
		while(list($key2,$val2)=each($val)){	
			$DATA[$key2] = $val2;
		}
		$i++;
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td style="vertical-align:top;padding-top:5px" width="70"><?=$i?></td>
	      <td style="vertical-align:top;padding-top:5px"><?=$DATA["author"]["name"]?></td>
	      <td style="vertical-align:top;text-align:left;padding:5px 5px 5px 10px;width:75%">
	      	<?=nl2br($DATA["content"])?>

	      	<div>
	      		<?
	      			while(list($k,$v)=each($DATA["photos"])){
	      				while(list($k2,$v2)=each($v)){
	      					if($k2=="url" && $v2) echo "<span style='vertical-align:top;padding:10px 10px 20px 0'><a href='$v2' target='_blank'><img src='$v2' width='30%' style='padding:5px;outline:1px solid #ccc;'></a></span>";
	      				}
	      			}
	      		?>
	      	</div>
	      </td>
	      <td style="vertical-align:top;padding-top:5px"><?=date("Y/m/d H:i:s",substr($DATA["created_at"],0,-3))?></td>
	    </tr>
<?
		$num--;
	}
?>
	</table>

	<br/>

	<?
	$time = time();
	?>

	<div id="sc_band<?=$time?>"></div>

	<script type="text/javascript">
	$(function(){
		$("#after").val('<?=$after?>');
		$("#bx_id").val('<?=$time?>');
		$("#no").val('<?=$i?>');
		$("#btn_more").text("더보기");
		<?if(!$after){?>
		$("#btn_more").hide();
		<?}?>
	});	
	</script>




