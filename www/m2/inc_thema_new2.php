<script type="text/javascript">
function tab_button_thema(i){
    $(".tab_button_thema").hide();
    $("#tab_button_thema_"+i).show();
}


$(function(){

    $('.tpl02_theme').bxSlider({   
        auto:false,
        pager:false,
        controls:false,
        minSlides: 2,
        maxSlides: 2,
        moveSlides:1,
        slideWidth: 596,
        slideMargin: 10,
    });


    tab_button_thema(1);
});    
</script>

 
<?
$j=0;
foreach ($ctg_theme as $code2 => $value){
    $j++;
?>

<div id="tab_button_thema_<?=$j?>" class="tab_button_thema" style="width:100%;">

      <!--tpl02_sec02_menu-->
      <div class="tpl02_sec02_menu">
        <ul>
         <?
         $k=0;
         foreach ($ctg_theme as $key2 => $value2){
            $k++;
            $on =($j==$k)?"on":"";
         ?>              
              <li class="<?=$on?>" onclick="tab_button_thema(<?=$k?>)">
                <a href="javascript:void(0)"><?=$value2?></a>
              </li>
         <?}?> 
        </ul>
      </div>
      <!--//tpl02_sec02_menu-->
      
       
        <!--tpl02_theme-->
        <div class="tpl02_theme"> 
         
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
          <div class="prd02_theme">
            <dl>
              <a href="itemview.html?tid=<?=$rs[tid]?>">
              <dt><img src="<?=$pic?>" width="100%" height="150" /></dt>
              <dd>
                <p class="theme_pname"><?=$rs[subject]?></p>
                <p class="theme_pcmt"><?=$rs[pr]?></p>
                <p class="theme_price"><?=nf($rs[price_adult])?><span class="won">원~</span></p>
              </dd>
              </a>
            </dl>
          </div>
        <?}?>         
         
        </div>
        <!--//tpl02_theme-->   




</div>
<?}?>