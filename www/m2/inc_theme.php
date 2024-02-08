<?include_once("./script/include_common_mobile.php");?>
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

	$('.mgr1 li').click(function(){
	  var str = this.id;
	  var arr = str.split("_");
	  var no = arr[1];

	  $('.mgr1 li').removeClass("on_theme");
	  $('#slider_'+no).addClass("on_theme");

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


      <!--theme_menugr-->
      <div class="theme_menugr">

		<ul class="mgr1">
		  <li id="slider_0" class="on_theme mgr1">골프 & 휴양</li>
		  <li id="slider_1">골프 & 시내</li>
		  <li id="slider_2" class="mgr1">LPGA, PGA 대회코스</li>
		  <li id="slider_3">연휴출발</li>
		</ul>

      </div>
      <!--//theme_menugr-->


		<ul class="theme_slider">

			<?
			for($i=0; $i<4;$i++){
				$code = $codes[$i];
				$filter =" and (category1 like '$code%' or category2 like '$code%' or category3 like '$code%' or category4 like '$code%' or category5 like '$code%' or category6 like '$code%') ";

				#query
				$sql1 = "select * from ez_tour where top_c2=1 $filter $PROOF_FILTER";			//자료수
				$sql2 = $sql1 . " order by hit desc limit  2";
				$dbo->query($sql2);
				//checkVar(mysql_error(),$sql2);
			?>

			<li>
				<?
				$rs=$dbo->next_record();
				$filename = $PUBLIC_PATH . "public/goods/".$rs[filename1];
				$thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs[filename1];

				$pic =($rs[filename1])? thumbnail($filename, 165, 161, 0, 1, 100, 0, "", "", $thumb) : "images/main/img_thumb03.jpg";
			    $url = "./itemview.html?tid=$rs[tid]";
				?>

				  <a href="<?=$url?>" target="<?=$rs[target]?>">
				  <!--theme_thumb-->
				  <div class="theme_thumb mgr1"><img src="<?=$pic?>" width="100%" />
					<div class="theme_title"><?=$rs[subject]?></div>
				  </div>
				  <!--//theme_thumb-->
				  </a>

				<!--theme_prd02-->
				<?
				$rs=$dbo->next_record();
				$filename = $PUBLIC_PATH . "public/goods/".$rs[filename1];
				$thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs[filename1];

				$pic =($rs[filename1])? thumbnail($filename, 165, 161, 0, 1, 100, 0, "", "", $thumb) : "images/main/img_thumb03.jpg";
			    $url = "./itemview.html?tid=$rs[tid]";
				?>

				  <!--theme_thumb-->
				  <div class="theme_thumb">
				  	<a href="<?=$url?>" target="<?=$rs[target]?>">
				    <img src="<?=$pic?>" width="100%" />
					<div class="theme_title"><?=$rs[subject]?></div>
					</a>
				  </div>
				  <!--//theme_thumb-->

			</li>
			<?}?>
		</ul>





