 
<?
$j=0;
foreach ($ctg_theme as $code2 => $value){
    $j++;
?>
<div id="tab_recomm_<?=$j?>" class="tab_recomm">


        <!--Cts_center-->
        <div class="cts_center">
           
           <!--tit_sec01-->
           <div class="tit_sec01">
             <h2 class="titgr">
               <p class="eng_t02">Theme Recomended Tour</p>
               <p class="season_t02">테마별 추천상품</p>
             </h2>
             <div class="comt_title">테마가 있는 골프여행!<br />색다른 해외골프여행 만나보실까요? </div>
             
             <!--sec02_menu-->
             <div class="sec02_menu">
               <ul>
                 <?
                 $k=0;
                 foreach ($ctg_theme as $key2 => $value2){
                    $k++;
                    $theme_on =($j==$k)?"on":"";
                 ?>                   
                 <li class="<?=$theme_on?>" onclick="tab_thema(<?=$k?>)"><?=$value2?></li>
                 <?}?>
               </ul>
             </div>
             <!--//sec02_menu-->
             
           </div>
           <!--//tit_sec01-->   
           
           <!--sec02_prdgr-->
           <div class="sec02_prdgr" style="clear:both">
             

            <?
            //테마
            $sql2 = str_replace("CODE2",$code2,$sql_best_c2);
            list($rows) =$dbo->query($sql2);              
            $t=0;
            while($rs=$dbo->next_record()){
                $t++;
                $rs[pr] = strip_tags($rs[pr]);
                $rs[pr] = titleCut2($rs[pr],130);      
                $filename = $PUBLIC_PATH . "public/goods/".$rs[filename1];
                $thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs[filename1];
                $pic =($rs[filename1])? thumbnail($filename, 285, 210, 0, 1, 80, 0, "", "", $thumb) : "images/newmain/thumb01.gif";

                $mgr20 = ($t%4)? "mgr20":"";
            ?>   

             <!--theme_type01-->
             <div class="theme_type01 <?=$mgr20?>">
               <a href="detailview.html?tid=<?=$rs[tid]?>">
               <div class="theme_thumb01"><img src="<?=$pic?>" width="285" height="210" alt="<?=$rs[subject]?>"/></div>
               
               <div class="theme_ctsbox01">
                 <div class="theme_tit01"><?=$rs[subject]?></div>
                 <div class="theme_cmt01"><?=$rs[pr]?></div>
                 <div class="theme_price01"><?=nf($rs[price_adult])?><span class="won">원~</span></div>
               </div>
               </a>
             </div>
             <!--//theme_type01-->
             
            <?}?> 



           </div>
           <!--//sec02_prdgr-->
           
      
        </div>
        <!--//Cts_center-->

</div>
<?}?>        