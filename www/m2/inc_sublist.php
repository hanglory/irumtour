<?
include_once("./script/include_common_mobile.php");


$keyword = secu($keyword);

$filter = $PROOF_FILTER;
///$filter .=" and subject like '%$keyword%' ";


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
	$pic =($rs[filename1])? thumbnail($filename, 310, 198, 0, 1, 100, 0,'','', $thumb) : "images/main/img_thumb02.jpg";


?>
    <!--item_wrap : 상품리스트-->




    <!--list_wrap-->
    <div class="list_wrap">
		<a href="itemview.html?tid=<?=$rs[tid]?>">
        <!--list_gr-->
        <div class="list_gr">
          <div class="list_thumb"><img src="<?=$pic?>" width="100%" alt="" /></div>
          <div class="list_info">
            <div class="list_t01"><?=$rs[subject]?></div>
            <div class="list_t02"><?=$rs[pr]?></div>
            <div class="list_t03"><?=nf($rs[price_adult])?><span class="best_won02">원<?=($rs[bit_price_wave])?'':'~'?></span></div>
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


<!-- 
            <?
			if($last_row>$total_rows){
			?>
			<a href="javascript:load_sublist(<?=$total_rows?>,'<?=$sort?>')"><div class="list_more02"><img src="../renew/images/submain/btn_more02.png" /></div></a>
			<?}?>


			<div id="sublist_wrap<?=$total_rows?>"></div> -->

