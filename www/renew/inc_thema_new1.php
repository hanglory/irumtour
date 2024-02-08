<?
include_once("script/include_common_file.php");
?>
          <!--tpl_tit_sec01--> 
           
            
          <!--tpl_theme_prdgr-->
          <div class="tpl_theme_prdgr">
            <ul>

                <?
                $sql2 = str_replace("CODE2",$code2,$sql_best_c2);
                list($rows) =$dbo->query($sql2);   
                //checkVar(mysql_error(),$sql2);
                while($rs=$dbo->next_record()){
                    $rs[pr] = titleCut2($rs[pr],130);      
                    $filename = $PUBLIC_PATH . "public/goods/".$rs[filename1];
                    $thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs[filename1];
                    $pic =($rs[filename1])? thumbnail($filename, 596, 320, 0, 1, 100, 0, "", "", $thumb) : "images/main/thumb_theme01.jpg";
                ?>  


                  <li><a href="detailview.html?tid=<?=$rs[tid]?>">
                    <div class="prdimg"><img src="<?=$pic?>" width="280" height="205" /></div>
                                        
                    <!--season_box-->
                    <div class="season_box">
                      <div class="season_prdtit"><?=$rs[subject]?></div>
                      <div class="season_prdcmt"><?=$rs[pr]?></div>
                      <div class="season_prdprice"><?=nf($rs[price_adult])?><span class="won">원~</span></div>
                    </div>
                    </a>
                    <!--//season_box-->
                  </li>

                <?}?>
              
            </ul>
          </div>
          <!--//tpl_theme_prdgr-->
      