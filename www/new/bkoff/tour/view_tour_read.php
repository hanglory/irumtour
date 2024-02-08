<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour";
$MENU = "tour";
$TITLE = "상품관리";
$file_rows = 1; //파일 업로드 갯수


$sql = "select * from $table where id_no=$id_no";
if($tid) $sql = "select * from $table where tid=$tid";
$dbo->query($sql);
$rs= $dbo->next_record();
$tid=$rs[tid];


//-------------------------------------------------------------------------------
?>
<?include("../top_min.html");?>
<?include("../../include/tour_options.php");?>
<script type="text/javascript" src="../../include/bbs_frame.js"></script>
<script language="JavaScript">
function chkForm()
{
    var fm = document.fmData;
    //if(check_blank(fm.subject,'여행상품명을',0)=='wrong'){return }
    fm.submit();

}
</script>

<style type="text/css">
.subject_train{font-weight:normal;width:100px;text-align:right;padding-right:5px;}
.tbl{
    border-collapse:collapse;
    border-top:2px solid #5E90AE;
    border-bottom:3px solid #E1E1E1;
}
.tbl tr{
    border-bottom:1px solid #ccc !important;
}
.tbl td{
    padding:10px;
}
.sub_contents *{
    width:100%;
}
</style>



<center>

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="title_con" style="padding-left:10px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
      </tr>
      <tr>
        <td> </td>
      </tr>
       <tr>
        <td background="../images/common/bg_title.gif" height="5"></td>
      </tr>
    </table>

    <!--내용이 들어가는 곳 시작-->

    <br>

    <table border="0" cellspacing="1" cellpadding="3" width="950" class="tbl">
        <form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
        <input type="hidden" name="mode" value='save'>
        <input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
        <input type="hidden" name="tid" value='<?=$tid?>'>
        <input type="hidden" name="ctg1" value="<?=$ctg1?>">

        <tr>
          <td class="subject" width="130"> 카테고리</td>
          <td>
            <div><?=str_replace("여행","",get_category_name($rs[category1]))?></div>
            <div><?=str_replace("여행","",get_category_name($rs[category2]))?></div>
            <div><?=str_replace("여행","",get_category_name($rs[category3]))?></div>
            <div><?=str_replace("여행","",get_category_name($rs[category4]))?></div>
            <div><?=str_replace("여행","",get_category_name($rs[category5]))?></div>
            <div><?=str_replace("여행","",get_category_name($rs[category6]))?></div>
          </td>
        </tr>
        


        <tr>
          <td  class="subject"> 여행명(상품명)</td>
          <td>
            <?=$rs[subject]?>
          </td>
        </tr>
        

        <tr>
          <td  class="subject">페이지이름(METATAG)</td>
          <td>
            <?=$rs[seo_title]?>
          </td>
        </tr>
        

        <tr>
          <td  class="subject">페이지설명(METATAG)</td>
          <td>
            <?=$rs[seo_description]?>
          </td>
        </tr>
        



        <tr>
          <td class="subject">간략한설명</td>
          <td>
            <?=nl2br($rs[pr])?>
          </td>
        </tr>
    


        <tr>
          <td class="subject">고객관리 상품명</td>
          <td colspan="3">
               <span id="golf_wrap"></span>
               <?=($rs[golf])? $rs[golf] : "없음"?>

               <?if($rs[golf_id_no]){?>
               &nbsp;&nbsp;&nbsp;
               <!-- (상품코드 : <a href="javascript:newWin('../cmp/view_golf.php?id_no=<?=$rs[golf_id_no]?>',870,700,1,1,'golf')"><?=$rs[golf_id_no]?></a>) -->
               <?}?>
          </td>
        </tr>
        

        <tr>
          <td class="subject">골프장명</td>
          <td colspan="3">
               <?
               $rs[hole1] = ($rs[hole1])? $rs[hole1] : "18홀";
               $rs[hole2] = ($rs[hole2])? $rs[hole2] : "18홀";
               $rs[hole3] = ($rs[hole3])? $rs[hole3] : "18홀";
               $rs[hole4] = ($rs[hole4])? $rs[hole4] : "18홀";
               $rs[hole5] = ($rs[hole5])? $rs[hole5] : "18홀";
               $rs[hole6] = ($rs[hole6])? $rs[hole6] : "18홀";
               ?>
               <div><?=$rs[golf2_1]?></div>
               <div><?=$rs[golf2_2]?></div>
               <div><?=$rs[golf2_3]?></div>
               <div><?=$rs[golf2_4]?></div>
               <div><?=$rs[golf2_5]?></div>
               <div><?=$rs[golf2_6]?></div>
          </td>
        </tr>
        

        <tr>
          <td class="subject">관광지명</td>
          <td colspan="3">
               <div><?=$rs[tour]?> <?=($rs[tour_days])? $rs[tour_days]."일차":""?></div>
               <div><?=$rs[tour2]?> <?=($rs[tour_days2])? $rs[tour_days2]."일차":""?></div>
               <div><?=$rs[tour3]?> <?=($rs[tour_days3])? $rs[tour_days3]."일차":""?></div>
               <div><?=$rs[tour4]?> <?=($rs[tour_days4])? $rs[tour_days4]."일차":""?></div>
               <div><?=$rs[tour5]?> <?=($rs[tour_days5])? $rs[tour_days5]."일차":""?></div>
               <div><?=$rs[tour6]?> <?=($rs[tour_days6])? $rs[tour_days6]."일차":""?></div>
          </td>
        </tr>
        


        <tr>
          <td class="subject">호텔명</td>
          <td colspan="3">

               <div><?=$rs[hotel]?> <?=($rs[hotel_days])? $rs[hotel_days]."일차":""?></div>
               <div><?=$rs[hotel2]?> <?=($rs[hotel_days2])? $rs[hotel_days2]."일차":""?></div>

          </td>
        </tr>
        

        <tr>
          <td class="subject">일정표 TYPE</td>
          <td colspan="3">
               <?=$rs[plan_type]?> TYPE
          </td>
        </tr>
        

        <tr>
          <td class="subject">1일차 식사표기</td>
          <td colspan="3">
               <?
               if(!$rs[id_no]) $rs[meal_type]=2;
               $MEAL_TYPE1="1type(호텔식),2type(불포함)";
               $MEAL_TYPE2="0,2";
               ?>
               <?=radio($MEAL_TYPE1,$MEAL_TYPE2,$rs[meal_type],'meal_type')?>
               (일정표 TYPE A,B,C,D,L형에만 해당합니다.)
          </td>
        </tr>
        

        <tr>
          <td class="subject"></td>
          <td colspan="3">
               <div id="air_info">
                <?if($rs[d_air_no]){?>▶출국편명:<?=$rs[d_air_no]?> (출발시간:<?=$rs[d_air_time1]?> / 도착시간:<?=$rs[d_air_time2]?>)<?}?> 
                <?if($rs[d_air_no_m]){?> - 국내선으로 이동 <?=$rs[d_air_no_m]?> (출발시간:<?=$rs[d_air_time1_m]?> / 도착시간:<?=$rs[d_air_time2_m]?>)<?}?>
                
               </div>
               <div id="air_info2"><?if($rs[r_air_no]){?>▶귀국편명:<?=$rs[r_air_no]?> (출발시간:<?=$rs[r_air_time1]?> / 도착시간:<?=$rs[r_air_time2]?>)<?}?> 
               
               </div>

          </td>
        </tr>
        


        <tr>
          <td  class="subject">여행국가</td>
          <td>

            <?=$rs[nation]?>

            <span class="subject">기본일정</span>

             <?=$rs[days1]?>박
             <?=$rs[days2]?>일

            <span class="subject">적용기간</span>
             <?=$rs[period]?>
          </td>
        </tr>
        

        <tr>
          <td class="subject">싱글사용</td>
          <td>
             <?=($rs[bit_single])?"싱글사용":"미적용"?>
          </td>
        </tr>
                

        <tr>
          <td  class="subject">인원설정</td>
          <td>
             전체좌석수 : <?=nf($rs[tourlist_max])?> 명
             <span style="width:20px"></span>
             최소인원 : <?=nf($rs[tourlist_min])?> 명
          </td>
        </tr>
        
        <tr>
          <td  class="subject">이용항공 / 좌석</td>
          <td>
            <?=nf($rs[air_name])?>
          </td>
        </tr>
        

        <tr>
          <td  class="subject">표시 가격</td>
          <td>
               <?=nf($rs[price_adult])?>원
          </td>
        </tr>
        




        <tr>
          <td  class="subject">정보입력</td>
          <td>
                <div class="sub_contents">
                    <?
                    $sql3 = "select * from ez_tab_contents where tid=$rs[tid] and assort='10_information' order by id_no desc limit 1";
                    $dbo3->query($sql3);
                    $rs3=$dbo3->next_record();
                    echo $rs3[content];
                    ?>
                </div>

          </td>
        </tr>
        



        <tr>
          <td  class="subject">포함내역</td>
          <td>
               <?=nl2br($rs[content1]);?>
          </td>
        </tr>
        

        <tr>
          <td  class="subject">불포함내역</td>
          <td>
               <?=nl2br($rs[content2]);?>
          </td>
        </tr>
        

        <tr>
          <td  class="subject">참고사항</td>
          <td>
               <?=nl2br($rs[content3]);?>
          </td>
        </tr>
        

        <tr>
          <td  class="subject">예약환불규정</td>
          <td>
            <?=nl2br($rs[cancel_txt]);?>
          </td>
        </tr>
        

    </form>
    </table>

    <br/>

    <!-- Button Begin---------------------------------------------->
    <table border="0" cellspacing="1" cellpadding="3" width="950">
    <tr>
        <td align="right">
            <span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
        </td>
    </tr>
    </table>
    <!-- Button End------------------------------------------------>



</center>

    <!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>
