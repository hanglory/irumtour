<?include_once("script/include_common_file.php");?>
<script type="text/javascript">
<!--
$(function(){
	var slider = $('.theme_slider').bxSlider({
		mode:'fade',
		pager:false,
		controls:false,
		auto: false,
		autoControls: false
	});

	$('.rolling_text li').click(function(){
	  var str = this.id;
	  var arr = str.split("_");
	  var no = arr[1];

	  $('.rolling_text li').removeClass("theme_on");
	  $('#slider_'+no).addClass("theme_on");

	  slider.goToSlide(no);
	  return false;
	});


});
//-->
</script>

<?
$codes[0]	="14-63-";
$codes[1]	="14-65-";
$codes[2]	="15-";
$codes[3]	="16-";
?>
          <!--Theme_Menu-->
          <div class="theme_menu">
            <ul class="rolling_text">
              <li id="slider_0" class="theme_on"><a href="#"><span class="theme_txt">테마추천1</span><br />골프 &amp; 휴양 </a></li>
              <li id="slider_1"><a href="#"><span class="theme_txt">테마추천2</span><br />골프 &amp; 시내 </a></li>
              <li id="slider_2"><a href="#"><span class="theme_txt">테마추천3</span><br />LPGA, PGA 대회코스</a></li>
              <li id="slider_3"><a href="#"><span class="theme_txt">테마추천4</span><br />연휴출발</a></li>
            </ul>

          </div>
          <!--//Theme_Menu-->

          <!--Theme_Prdgr-->
          <div class="theme_prdgr">

			<ul class="theme_slider">

			    <?
				for($i=0; $i<4;$i++){
					$code = $codes[$i];
					$filter =" and (category1 like '$code%' or category2 like '$code%' or category3 like '$code%' or category4 like '$code%' or category5 like '$code%' or category6 like '$code%') ";

					#query
					$sql1 = "select * from ez_tour where top_c2=1 $filter $PROOF_FILTER";			//자료수
					$sql2 = $sql1 . " order by hit desc limit  2";
					$dbo->query($sql2);
				?>

				<li>
					<?
					$rs=$dbo->next_record();
					$filename = $PUBLIC_PATH . "public/goods/".$rs[filename1];
					$thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs[filename1];

					$pic =($rs[filename1])? thumbnail($filename, 596, 320, 0, 1, 100, 0, "", "", $thumb) : "images/main/thumb_theme01.jpg";
					?>
					<div class="theme_prd01">
					  <div class="item2"><a href="detailview.html?tid=<?=$rs[tid]?>"><img src="<?=$pic?>" height="596" width="320" /></a>
						<div class="caption">
							<a href="detailview.html?tid=<?=$rs[tid]?>"><span class="theme_title"><?=$rs[subject]?></span></a>
							<a href="detailview.html?tid=<?=$rs[tid]?>"><p class="theme_pr"><?=$rs[pr]?></p></a>
						</div>
					  </div>

					</div>
					<!--//theme_prd01-->


					<!--theme_prd02-->
					<?
					$rs=$dbo->next_record();
					$filename = $PUBLIC_PATH . "public/goods/".$rs[filename1];
					$thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs[filename1];

					$pic =($rs[filename1])? thumbnail($filename, 320, 290, 0, 1, 100, 0, "", "", $thumb) : "images/main/thumb_theme02.jpg";

					?>
					<div class="theme_prd02">

						<div class="item"><a href="detailview.html?tid=<?=$rs[tid]?>"><img src="<?=$pic?>" height="320" width="290" /></a>
						  <div class="caption"><a href="detailview.html?tid=<?=$rs[tid]?>"><span class="theme_title2"><?=$rs[subject]?></span></a>
						  </div>
						</div>


					</div>
					<!--//theme_prd02-->
				</li>
				<?}?>
			</ul>


          </div>
          <!--//Theme_Prdgr-->