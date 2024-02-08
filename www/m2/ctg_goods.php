<?
include_once("./script/include_common_mobile.php");

$code1=($code1)?$code1:3;
$code = "${code1}-%";
$filter =" and (category1 like '$code%' or category2 like '$code%' or category3 like '$code%' or category4 like '$code%' or category5 like '$code%' or category6 like '$code%') ";

#query
$sql1 = "select * from ez_tour where top_best=1 $filter $PROOF_FILTER";			//자료수
$sql2 = $sql1 . " order by hit desc limit  4";
$dbo->query($sql2);
//if($REMOTE_ADDR=="106.246.54.27") checkVar("",$sql2);
?>
	  <!--m_tabmenu : 탭메뉴-->
      <table class="m_tabmenu" cellpadding="0" cellspacing="0" >
		<caption>탭메뉴</caption>
		<colgroup>
		  <col width="25%" />
			<col width="25%" />
            <col width="25%" />
            <col width="25%" />
	    </colgroup>
	    <tbody>
          <tr>
            <td <?if($code1==3){?>class="tab_on"<?}?>><a href="javascript:ctg_goods(3)">일본골프</a></td>
		    <td <?if($code1==2){?>class="tab_on"<?}?>><a href="javascript:ctg_goods(2)">동남아골프</a></td>
            <td <?if($code1==4){?>class="tab_on"<?}?>><a href="javascript:ctg_goods(4)">중국골프</a></td>
		    <td <?if($code1==9){?>class="tab_on"<?}?>><a href="javascript:ctg_goods(9)">미주골프</a></td>
          </tr>
	    </tbody>
      </table>
      <!--//m_tabmenu : 탭메뉴-->

      <!--best_gr : 상품나오는곳-->
      <div class="best_gr">

        <?
		$j=1;
		while($rs=$dbo->next_record()){
		$filename = "/new/public/goods/".$rs[filename1];
		$thumb = "/new/public/thumb/tb_".$rs[filename1];
		$link =($rs[tid])? "itemview.html?tid=$rs[tid]" : "#";

		$mgr1 = ($j%2)? "mgr":"";
		?>
		<!--상품1-->
        <div class="m_item01 <?=$mgr1?>">
          <div class="item01_tumb">
            <a href="detail_view.html"><a href="itemview.html?tid=<?=$rs[tid]?>"><img src="<?=thumbnail($filename, 483, 286, 0, 1, 100, 0, "", "", $thumb)?>" onerror="this.src='./images/main/img_thumb01.gif'" width="100%" alt="" /></a>
            <div class="num_label"><img src="images/main/no_<?=$j?>.png" width="100%"  alt="1" /></div>
          </div>
          <div class="item01_title"><?=$rs[subject]?></div>
          <div class="item01_info" style="height:75px"><?=$rs[pr]?></div>
          <div class="item01_price"><?=($rs[price_adult])?number_format($rs[price_adult]):""?><?=($rs[price_adult])?"원~":""?></div>
        </div>
        <!--//상품1-->
		<?
			$j++;
		}?>

      </div>
      <!--//best_gr : 상품나오는곳-->

