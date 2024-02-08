 
<?
$j=0;
foreach ($ctg_theme as $code2 => $value){
    $j++;
?>
<div id="tab_recomm_<?=$j?>" class="tab_recomm">

      <!--sec02_menu-->
      <div class="sec02_menu">
        <ul>
         <?
         $k=0;
         foreach ($ctg_theme as $key2 => $value2){
            $k++;
            $on =($j==$k)?"on":"";
         ?>              
              <li class="<?=$on?>" onclick="tab_thema(<?=$k?>)">
                <?=$value2?>
              </li>
         <?}?> 
        </ul>
      </div>
      <!--//sec02_menu-->
      
      
        
      
      <!--sec02_prdgr-->
      <div class="sec02_prdgr">
      
        <!--recomm_area-->
        <div class="recomm_area no_scroll"> 
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
              
              <!--theme_type01-->
              <div class="theme_type01">
        
                <a href="itemview.html?tid=<?=$rs[tid]?>">
                <img src="<?=$pic?>" width="100%" height="280" alt="<?=$rs[subject]?>"/>
        
                <!--theme_ctsbox01-->
                <div class="theme_ctsbox01">
                  <div class="theme_tit01"><?=$rs[subject]?></div>
                  <div class="theme_price01"><?=nf($rs[price_adult])?><span class="won">원~</span></div>
                </div>
                <!--//theme_ctsbox01-->
                </a>
        
              </div>
              <!--//theme_type01-->
              
              
            </li>
             <?}?>
            
          </ul>
        </div>
        <!--//recomm_area-->
        
      </div>
      <!--//sec02_prdgr-->
      
      


</div>
<?}?>
