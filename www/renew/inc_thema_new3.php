 
<?
$j=0;
foreach ($ctg_theme as $code2 => $value){
    $j++;
?>
<div id="tab_recomm_<?=$j?>" class="tab_recomm">

          <!--theme_mmenu03-->
          <div class="theme_mmenu03">
            <ul>

             <?
             $k=0;
             foreach ($ctg_theme as $key2 => $value2){
                $k++;
                $theme03_on =($j==$k)?"theme03_on":"";
             ?>                
              <li class="<?=$theme03_on?>" onclick="tab_thema(<?=$k?>)">
                <a href="javascript:void(0)"><p>테마추천0<?=$k?></p><?=$value2?>
                    <?if($j==$k){?>
                    <div class="on_arrow"><img src="images/templet03/theme_arrow.png"  alt="" /></div>
                    <?}?>
                </a>
              </li>
             <?}?>
            </ul>
          </div>
          <!--//theme_mmenu03-->
          
          <!--tpl03_themegr-->
          <div class="tpl03_themegr">
            
            <!--tpl_theme_prdgr03-->
            <div class="tpl_theme_prdgr03">

            <ul>    


            <?
            //테마
            $sql2 = str_replace("CODE2",$code2,$sql_best_c2);
            list($rows) =$dbo->query($sql2);              
            
            while($rs=$dbo->next_record()){
                $rs[pr] = titleCut2($rs[pr],130);      
                $filename = $PUBLIC_PATH . "public/goods/".$rs[filename1];
                $thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs[filename1];
                $pic =($rs[filename1])? thumbnail($filename, 596, 320, 0, 1, 100, 0, "", "", $thumb) : "images/templet03/thumb02.gif";
            ?>               
              <li>
                <a href="detailview.html?tid=<?=$rs[tid]?>">
                <div class="theme_prdgr03_img">
                  <div class="img_theme"><img src="<?=$pic?>" width="187" height="187" /></div>
                  <div class="theme_prdgr03_go"><img src="images/templet03/btn_go.png" alt="go" /></div>
                </div>
                <div class="theme_prdgr03_cts"><?=$rs[subject]?></div>
                <div class="theme_prdgr03_price"><?=nf($rs[price_adult])?><span class="won">원~</span></div>
                </a>
              </li>
              <?}?>
              
            </ul>
            </div>
            <!--//tpl_theme_prdgr03-->
          
          </div>
          <!--//tpl03_themegr-->
  

</div>
<?}?>
