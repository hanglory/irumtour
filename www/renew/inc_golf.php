<?
$code = secu($code);
$inc_table = secu($inc_table);

$sql_ = "select * from $inc_table where id_no=$code";
$dbo_->query($sql_);
$rs_=$dbo_->next_record();

$filename = $PUBLIC_PATH ."public/cmp/".$rs_[filename1];
$thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs_[filename1];
$pic1 =($rs_[filename1])? thumbnail($filename, 180, 0, 0, 1, 100, 0, '','', $thumb) : "images/detail/img_thumb02.gif";
$bpic1 = thumbnail($filename, 916, 0, 0, 1, 100, 0, '','', $thumb);

$filename = $PUBLIC_PATH ."public/cmp/".$rs_[filename2];
$thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs_[filename2];
$pic2=($rs_[filename2])? thumbnail($filename, 180, 0, 0, 1, 100, 0, '','', $thumb) : "images/detail/img_thumb02.gif";
$bpic2 = thumbnail($filename, 916, 0, 0, 1, 100, 0, '','', $thumb);

$filename = $PUBLIC_PATH ."public/cmp/".$rs_[filename3];
$thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs_[filename3];
$pic3 = thumbnail($filename, 180, 0, 0, 1, 100, 0, '','', $thumb);
$pic3 =($rs_[filename3])? thumbnail($filename, 180, 0, 0, 1, 100, 0, '','', $thumb) : "images/detail/img_thumb02.gif";
$bpic3 = thumbnail($filename, 916, 0, 0, 1, 100, 0, '','', $thumb);

$filename = $PUBLIC_PATH ."public/cmp/".$rs_[filename4];
$thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs_[filename4];
$pic4 = thumbnail($filename, 180, 0, 0, 1, 100, 0, '','', $thumb);
$pic4 =($rs_[filename3])? thumbnail($filename, 180, 0, 0, 1, 100, 0, '','', $thumb) : "images/detail/img_thumb02.gif";
$bpic4 = thumbnail($filename, 916, 0, 0, 1, 100, 0, '','', $thumb);

$filename5 = $PUBLIC_PATH ."public/cmp/".$rs_[filename5];


//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar("$inc_table / $golf_over",$i);}

$bit_view=1;
if($inc_table=="cmp_golf2" && rnf($golf_over) && strstr($golf_over,",${i},")) $bit_view=0;


if($bit_view){
?>
<script type="text/javascript">
<!--
function show_detail(id){
	$("#"+id).css("top",$("#position_scroll").text()+"px");
	$("#"+id).show('fade');
}

function show_close(id){
	$(".pop_sub").hide();
}

//-->
</script>

<?
if($inc_table=="cmp_hotel"){
	$cc="hotel";
	$cc2="02";
	$inc = "hotel";
	$inc_mobile = "hotel";
}elseif($inc_table=="cmp_tour"){
	$cc="tourism";
	$cc2="03";
	$inc = "tour";	
	$inc_mobile = "tourism";
}else{
	$cc="cc";
	$cc2="";
	$inc = "golf2";
	$inc_mobile = "cc";
}

?>


		<div id="golf_wrap">

				   <!--골프장소개-->
                   <div class="<?=$cc?>_group">

                     <div class="<?=$cc?>_title">
                       <div class="<?=$cc?>_name">&nbsp;&nbsp;<?=$rs_[name]?></div>
                       <div class="<?=$cc?>_more"><a href="javascript:show_detail('pop_<?=$code?>')"><img src="images/detail/btn_more<?=$cc2?>.gif" alt="자세히보기" /></a></div>
                     </div>

                     <div class="<?=$cc?>_cts">
						<div class="golf_content_short">

						<?=nl2br(check_content_https($rs_[content]))?>
						</div>

                        <div class="<?=$cc?>_imggr">
                          <div class="<?=$cc?>_thumb mgr10"><img src="<?=$pic1?>" onerror="this.src='images/detail/img_thumb02.gif'" width="180" height="135" alt="골프장 전경"/></div>
                          <div class="<?=$cc?>_thumb mgr10"><img src="<?=$pic2?>" onerror="this.src='images/detail/img_thumb02.gif'" width="180" height="135" alt="골프장 전경"/></div>
                          <div class="<?=$cc?>_thumb"><img src="<?=$pic3?>" onerror="this.src='images/detail/img_thumb02.gif'" width="180" height="135" alt="골프장 전경"/></div>
                        </div>


                     </div>

                   </div>
                   <!--//골프장소개-->

                   <!--모달윈도우-->
                     <div id="pop_<?=$code?>" class="pop_sub" style="display:none">
                       <!--pop_wrap-->
                       <div id="pop_wrap">

                         <!--pop_header-->
                         <div id="pop_header<?=$cc2?>">
                           <div class="pop_title<?=$cc2?>"><?=$rs_[name]?>
                             <div class="pop_close"><a href="javascript:show_close()"><img src="images/detail/pop_close.png" alt="닫기"  /></a></div>
                           </div>
                         </div>
                         <!--//pop_header-->

                         <!--pop_contents-->
                         <div id="pop_contents">

                           <div class="pop_cts">

							<?if($rs_[filename5]){?>
							<center>
							<img src="<?=$filename5?>" alt="골프장 이미지">
							</center>
							<?}?>

						   <?=nl2br(check_content_https($rs_[content]))?>
						   </div>

                           <div class="pop_img">

							   <center>

								<div class="cc_imggr">
								  <?if($rs_[filename1]){?><div class="mgb10"><img src="<?=$bpic1?>" onerror="this.src='images/detail/img_thumb02.gif'" width="916" alt="골프장 전경"/></div><?}?>
								  <?if($rs_[filename2]){?><div class="mgb10"><img src="<?=$bpic2?>" onerror="this.src='images/detail/img_thumb02.gif'" width="916" alt="골프장 전경"/></div><?}?>
								  <?if($rs_[filename3]){?><div class="mgb10"><img src="<?=$bpic3?>" onerror="this.src='images/detail/img_thumb02.gif'" width="916" alt="골프장 전경"/></div><?}?>
								  <?if($rs_[filename4]){?><div class="mgb10"><img src="<?=$bpic4?>" onerror="this.src='images/detail/img_thumb02.gif'" width="916" alt="골프장 전경"/></div><?}?>
								</div>

							   </center>

						   </div>

                           <div class="pop_cts">
						   <?=nl2br(check_content_https($rs_[content2]))?>
						   </div>

                             <a href="javascript:show_close()"><img src="/renew/images/ez_board/btn_close.gif" border="0" align="absmiddle" style="width:20px;height:20px;float:right">

                         </div>
                         <!--//pop_contents-->


                       </div>
                       <!--//pop_wrap-->


                     </div>
                     <!--//모달윈도우-->
			</div>




			<div id="golf_wrap_mobile" style="display:none">

				 <div class="<?=$cc?>_title">
				   <div class="<?=$cc?>_name"><img src="images/detail/ic_<?=$inc_mobile?>.png"  height="20" alt="<?=$rs_[name]?>"> <?=$rs_[name]?></div>
				 </div>

				 <div class="cc_cts">
					<div class="golf_content_short">
					<?//=nl2br($rs_[content])?>
					</div>

					<div>
					  <span><img src="<?=$pic1?>" onerror="this.src='images/detail/img_thumb02.gif'" width="100%" alt="골프장 전경"/></span>
					</div>

					 <div style="margin-top:5px">
					 <a href="/m2/pop_<?=$inc_mobile?>.html?code=<?=$code?>&inc=<?=$inc?>" target="_blank"><img src="/renew/images/detail/btn_more<?=$cc2?>.gif" alt="자세히보기" /></a>
					 </div>

				 </div>

			</div>
<?}?>			