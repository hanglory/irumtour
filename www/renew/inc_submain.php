<?
include_once("script/include_common_file.php");

$keyword = secu($keyword);

$filter = $PROOF_FILTER;


if($code1!=27 && $code1!=28){
	$filter .= " and top_c3='1' ";
}


if($stype=="n") $filter .=" and nation='$keyword' ";

$total_rows =($total_rows)? $total_rows+6 : 6;
$code_path = "";
if($code1) $code_path = "${code1}-";
if($code2) $code_path .= "${code2}-";
if($code3) $code_path .= "$code3";
if($code_path){
	$filter .=" and
		(
			category1 like '$code_path%'
			or category2 like '$code_path%'
			or category3 like '$code_path%'
			or category4 like '$code_path%'
			or category5 like '$code_path%'
			or category6 like '$code_path%'
		)
	";
}

$filter = substr($filter,5);

$start = $total_rows - 6;

$sql1 ="
	select
		*
	from ez_tour
	where
		$filter
	";
//$sql2 = $sql1 . " order by hit desc limit $start, $total_rows";
$sql2 = $sql1 . " order by hit desc";
list($last_row) = $dbo->query($sql1);
$dbo->query($sql2);
if($REMOTE_ADDR=="1106.246.54.27") checkVar($last_row . mysql_error(),$sql2);

$i=1;
while($rs=$dbo->next_record()){
	$mgr22 = ($i%3)? "mgr22":"";

	$arr = explode(">",get_category_name($rs[category1]));

	$filename = $PUBLIC_PATH . "public/goods/".$rs[filename1];
	$thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs[filename1];
	$pic = thumbnail($filename, 385, 246, 0, 1, 100, 0,'','', $thumb);

?>

            <!--prd_submain : 상품-->
            <div class="prd_submain <?=$mgr22?>">

			        <a href="detailview.html?tid=<?=$rs[tid]?>">

              <!--Prd_Thumb-->
              <div class="prd_thumb"><a href="detailview.html"><img src="<?=$pic?>" onerror="this.src='images/submain/img_thumb01.gif'" width="385" height="246" /></a></div>
              <!--Prd_//Thumb-->

              <!--prd_info01-->
              <div class="prd_info01">
                <div class="title_prd"><?=$rs[subject]?></div>
                <div class="comment_prd"><?=$rs[pr]?></div>
              </div>
              <!--//prd_info01-->

              <!--prd_price02-->
              <div class="prd_price02">
                <div class="price"><?=nf($rs[price_adult])?><span class="list_won">원~</span></div>
                <div class="price_more"><a href="detailview.html?code1=<?=$code1?>&code2=<?=$code2?>&code3=<?=$code3?>&tid=<?=$rs[tid]?>"><img src="images/submain/btn_more.gif" alt="더보기" /></a></div>
              </div>
              <!--//prd_price02-->




            <!--prd_submain_on : 상품-->
            <div class="prd_submain_on <?=$mgr22?>">
			        <a href="detailview.html?code1=<?=$code1?>&code2=<?=$code2?>&code3=<?=$code3?>&tid=<?=$rs[tid]?>">
              <!--gr_prd_info-->
              <div class="gr_prd_info">

                <!--Region-->
                <div class="region">
                  <ul>
                    <li><?=$arr[1]?></li>
                  </ul>
                </div>
                <!--//Region-->

                <div class="title_prd_on02"><?=$rs[subject]?></div>
                <div class="comment_prd_on"><?=$rs[pr]?></div>
                <div class="price_on"><?=nf($rs[price_adult])?><span class="list_won">원~</span></div>
                <div class="list_more_on"><a href="detailview.html?code1=<?=$code1?>&code2=<?=$code2?>&code3=<?=$code3?>&tid=<?=$rs[tid]?>"><img src="images/submain/detail_more.gif" onmouseover="this.src='images/submain/detail_more_on.gif'" onmouseout="this.src='images/submain/detail_more.gif'" alt="자세히보기" /></a></div>

        				<div class="box_line">
        				  <span class="line_01"></span>
        				  <span class="line_02"></span>
        				  <span class="line_03"></span>
        				  <span class="line_04"></span>
        				</div>

              </div>
              <!--//gr_prd_info-->
			        </a>
            </div>
            <!--//prd_submain_on : 상품-->


            </div>
            <!--//prd_submain : 상품-->
<?
	$i++;
}
?>

<!--             <?
			if($last_row>$total_rows){
			?>
			<a href="javascript:load_submain(<?=$total_rows?>)"><div class="list_more02"><img src="images/submain/btn_more02.png" /></div></a>
			<?}?>


			<div id="submain_wrap<?=$total_rows?>"></div>
 -->
