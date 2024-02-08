<?
include_once("./script/include_common_mobile.php");

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
$sql2 = $sql1 . " order by hit desc limit $start, $total_rows";
list($last_row) = $dbo->query($sql1);
$dbo->query($sql2);
//if($REMOTE_ADDR=="106.246.54.27") checkVar($last_row . mysql_error(),$sql2);

$i=1;
while($rs=$dbo->next_record()){
	$mgr22 = ($i%3)? "mgr22":"";

	$arr = explode(">",get_category_name($rs[category1]));

	$filename = $PUBLIC_PATH . "public/goods/".$rs[filename1];
	$thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs[filename1];
	$pic = thumbnail($filename, 385, 246, 0, 1, 100, 0,'','', $thumb);

?>
    <!--item_wrap : 상품리스트-->

    <!--list_wrap-->
    <div class="list_wrap">
	  <a href="itemview.html?tid=<?=$rs[tid]?>">
        <!--list_gr-->
        <div class="list_gr">
          <div class="best02_thumb"><img src="<?=thumbnail($filename, 483, 300, 0, 1, 100, 0, "", "", $thumb)?>" onerror="this.src='images/main/img_thumb02.jpg'" width="100%" alt="" /></div>
          <div class="best02_info">
            <div class="best02_t01"><?=$rs[subject]?></div>
            <div class="best02_t02" style="height:25px"><?=$rs[pr]?></div>
            <div class="best02_t03"><?=number_format($rs[price_adult])?><span class="best_won02">원<?=($rs[bit_price_wave])?'':'~'?></span></div>
          </div>
        </div>
        <!--//list_gr-->
	  </a>
      </div>
      <!--//list_wrap-->
<?
	$i++;
}
?>



            <?
			if($last_row>$total_rows){
			?>
			<a href="javascript:load_submain(<?=$total_rows?>)"><div class="list_more02"><img src="../renew/images/submain/btn_more02.png" /></div></a>
			<?}?>


			<div id="submain_wrap<?=$total_rows?>"></div>

