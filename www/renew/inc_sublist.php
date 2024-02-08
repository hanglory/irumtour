<?
include_once("script/include_common_file.php");


$filter = $PROOF_FILTER;

if($keyword){
    $keyword = secu($keyword);
    $keyword = substr($keyword,0,30);
    if($stype=="n") $filter .=" and nation='$keyword' ";
    else{
        if(strlen($keyword)<=2){msggo("검색어가 너무 짧습니다.","index.html");}
        $filter .=" and (
            subject like '%${keyword}%'
            or pr like '%${keyword}%'
            
            or nation like '%${keyword}%'
            or golf_name like '%${keyword}%'
            or golf2_1_name like '%${keyword}%'
            or golf2_2_name like '%${keyword}%'
            or golf2_3_name like '%${keyword}%'
            or golf2_4_name like '%${keyword}%'
            or golf2_5_name like '%${keyword}%'
            or golf2_6_name like '%${keyword}%'
            
            or tour_name like '%${keyword}%'
            or tour2_name like '%${keyword}%'
            or tour3_name like '%${keyword}%'
            or tour4_name like '%${keyword}%'
            or tour5_name like '%${keyword}%'
            or tour6_name like '%${keyword}%'
            or hotel_name like '%${keyword}%'
            or hotel2_name like '%${keyword}%'

        ) ";
    }
}


$total_rows =($total_rows)? $total_rows+6 : 6;
$code_path = "";
if($code1) $code_path = "${code1}-";
if($code2) $code_path .= "${code2}-";
if($code3) $code_path .= "$code3";
if($code_path){

	$p = ($code3)? "":"%";

	$filter .=" and
		(
			category1 like '$code_path${p}'
			or category2 like '$code_path${p}'
			or category3 like '$code_path${p}'
			or category4 like '$code_path${p}'
			or category5 like '$code_path${p}'
			or category6 like '$code_path${p}'
		)
	";
}


if($page_mode=="self"){//셀프견적서
	if($bit_single) $filter.=" and bit_single=1";
	if($tour_date) $filter.=" and tid in (select distinct tid from ez_tour_calendar where tour_date>='$tour_date' and tour_date<='$tour_date2' and status in (1,2))";

	$filter .=" and
		(
			category1 like '29-%'
			or category2 like '29-%'
			or category3 like '29-%'
			or category4 like '29-%'
			or category5 like '29-%'
			or category6 like '29-%'
		)
	";
}

//if($filter) $filter = substr($filter,5);
//if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar($keyword,$filter);

switch($sort){
	case "1" : $orderby= "id_no desc";break;
	case "2" : $orderby= "hit desc";break;
	case "3" : $orderby= "price_adult desc";break;
	case "4" : $orderby= "price_adult asc";break;
	default: $orderby ="rseq asc,seq asc";
}

$start = $total_rows - 6;


$category = "${code1}-"; 
if($code2) $category = "${code1}-${code2}-"; 
if($code3) $category = "${code1}-${code2}-${code3}-"; 
$sql1 = " 
    select
        *,
        (select seq from ez_tour_seq where tid=a.tid and code1='${code1}' and code2='${code2}' and code3='${code3}' and best='' and cp_id='$CID') as rseq
    from ez_tour as a
    where 
        a.bit=1
        and a.sale_group='T'
        and (
            concat(a.category1,'-') like '${category}%'
            or concat(a.category2,'-') like '${category}%'
            or concat(a.category3,'-') like '${category}%'
            or concat(a.category4,'-') like '${category}%'
            or concat(a.category5,'-') like '${category}%'
            or concat(a.category6,'-') like '${category}%'
        )
        and a.cp_id in ('$CID','')
        $filter
    group by a.tid
    ";

$sql2 = $sql1 . " order by $orderby ";
list($last_row) = $dbo->query($sql1);
$dbo->query($sql2);
//if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar($last_row . mysql_error(),$sql2);

$i=1;
while($rs=$dbo->next_record()){
	$mgr15 = ($i%3)? "mgr15":"";

	$arr = explode(">",get_category_name($rs[category1]));

	$filename = $PUBLIC_PATH . "public/goods/".$rs[filename1];
	$thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs[filename1];
	$pic = thumbnail($filename, 310, 198, 0, 1, 100, 0,'','', $thumb);


?>

            <!--Prd_Sublist : 상품-->
            <div class="prd_sublist <?=$mgr15?>">

			  <a href="detailview.html?code1=<?=$code1?>&code2=<?=$code2?>&code3=<?=$code3?>&tid=<?=$rs[tid]?>">
              <div class="prd_goods">
			  <!--Prd_Thumb-->
              <div class="prd_thumb"><img src="<?=$pic?>" onerror="this.src='images/submain/img_thumb02.gif'" width="310" height="198" /></div>
              <!--Prd_//Thumb-->

              <!--Prd_Info02-->
              <div class="prd_info02">
                <div class="title_prd"><?=$rs[subject]?></div>
                <div class="comment_prd"><?=$rs[pr]?></div>
              </div>
              <!--//Prd_Info02-->

              <!--Prd_price-->
              <div class="prd_price">
                <div class="price"><?=nf($rs[price_adult])?><span class="list_won">원<?=($rs[bit_price_wave])?'':'~'?></span></div>
                <div class="price_more"><img src="images/submain/btn_more.gif" alt="더보기" /></div>
              </div>
              <!--//Prd_price-->
			  </div>


					<!--Prd_sublist_on : 상품-->
					<div class="prd_sublist_on <?=$mgr15?>">

					  <!--gr_prd_info-->
					  <div class="gr_prd_info">

						<!--Region-->
						<div class="region">
						  <ul>
							<li><?=$arr[1]?></li>
						  </ul>
						</div>
						<!--//Region-->

						<div class="title_prd_on"><?=$rs[subject]?></div>
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

					</div>
					<!--//Prd_sublist_on : 상품-->


				</a>
				</div>
				<!--//Prd_Sublist : 상품-->


<?
	$i++;
}
?>


<!--             <?
			if($last_row>=$total_rows){
			?>
			<a href="javascript:load_sublist(<?=$total_rows?>,'<?=$sort?>')"><div class="list_more"><img src="images/submain/btn_more02.png" /></div></a>
			<?}?>

			<div id="sublist_wrap<?=$total_rows?>"></div>

			<?if(!$last_row){?>
			<div><a href="javascript:history.back(-1)"><img src="http://irumtour.net/renew/images/comty/img_self02.jpg"></a></div>
			<?}?>

 -->





