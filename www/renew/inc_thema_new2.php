<?
include_once("script/include_common_file.php");
?>



            <!--tpl_theme_prdgr02-->
            <div class="tpl_theme_prdgr02">
              <ul>

                <?
                $sql2 = str_replace("CODE2",$code2,$sql_best_c2);
                $sql2 = str_replace("limit 4","limit 3",$sql2);
                list($rows) =$dbo->query($sql2);               
                //if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar($code2,$sql_best_c2);
                while($rs=$dbo->next_record()){
                    $rs[pr] = titleCut2($rs[pr],130);      
                    $filename = $PUBLIC_PATH . "public/goods/".$rs[filename1];
                    $thumb = $PUBLIC_PATH . "public/thumb/tb_".$rs[filename1];
                    $pic =($rs[filename1])? thumbnail($filename, 596, 320, 0, 1, 100, 0, "", "", $thumb) : "images/main/thumb_theme01.jpg";
                ?>  


                <li>
                  <a href="detailview.html?tid=<?=$rs[tid]?>">
                  <div class="prdimg02"><img src="<?=$pic?>" onerror="this.src='images/templet02/thumb02.gif'" width="290" height="205" /></div>
                                     
                  <!--theme02_box-->
                  <div class="theme02_box">
                    <div class="season_prdtit"><?=$rs[subject]?></div>
                    <div class="season_prdcmt"><?=$rs[pr]?></div>
                    <div class="season_prdprice"><?=nf($rs[price_adult])?><span class="won">원~</span></div>
                  </div>
                  <!--//theme02_box-->
                  </a>
                </li>
                <?}?>
            </ul>
          </div>
          <!--//tpl_theme_prdgr02-->
          
          
