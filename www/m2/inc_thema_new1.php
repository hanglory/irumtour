 
<?
$j=0;
foreach ($ctg_theme as $code2 => $value){
    $j++;
?>
<div id="tab_recomm_<?=$j?>" class="tab_recomm">

      <!--tpl01_sec02_menu-->
      <div class="tpl01_sec02_menu">
        <ul>
         <?
         $k=0;
         foreach ($ctg_theme as $key2 => $value2){
            $k++;
            $on =($j==$k)?"on":"";
         ?>              
              <li class="<?=$on?>" onclick="tab_thema(<?=$k?>)">
                <a href="javascript:void(0)"><?=$value2?></a>
              </li>
         <?}?> 
        </ul>
      </div>
      <!--//tpl01_sec02_menu-->
      
      
      <!--tpl01_sec02_prdgr-->
      <div class="tpl01_sec02_prdgr  no_scroll">
        <ul>

        <?
        //테마
        $sql2 = str_replace("CODE2",$code2,$sql_best_c2);
        $dbo->query($sql2);              
        //checkVar(mysql_error(),$sql2);
        while($rs=$dbo->next_record()){
            $rs[pr] = titleCut2($rs[pr],130);      
            $filename = $PUBLIC_PATH . "public/goods/".$rs[filename1];
            $thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs[filename1];
            $pic =($rs[filename1])? thumbnail($filename, 596, 320, 0, 1, 100, 0, "", "", $thumb) : "images/templet03/thumb02.gif";
        ?>               
          <li>
            <a href="itemview.html?tid=<?=$rs[tid]?>">
                <p class="theme_img"><img src="<?=$pic?>" width="100%" height="160" /></p>
                <p class="theme_tit"><?=$rs[subject]?></p>
                <p class="theme_cmt"><?=$rs[pr]?></p>
                <p class="theme_price"><?=nf($rs[price_adult])?><span class="won">원~</span></p>
            </a>
          </li>
        <?}?>
          
        </ul>
      </div>
      <!--//tpl01_sec02_prdgr-->

</div>
<?}?>
