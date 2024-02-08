<?
include_once("../include/common_file.php");
$TITLE = "키워드 리서치";

if($mode=="find"){

    $yesterday = date("Y-m-d",strtotime(date("Y/m/d")." -1 day"));

    $startDate = ($startDate)? str_replace("/","-",$startDate) : date("Y.m.d",strtotime(date("Y/m/d")." -1 month")-1);
    $endDate = ($endDate)? str_replace("/","-",$endDate) : $yesterday;
    if($endDate>$yesterday) $endDate=$yesterday;
    if($endDate<$startDate) $startDate=date("Y-m-d",strtotime(date("Y/m/d")." -1 month"));

    $data[startDate]=$startDate;
    $data[endDate]=$endDate;
    $data[timeUnit]=$timeUnit;
    $data[device]=$device;
    $data[gender]=$gender;
    if(is_array($ages)) $data[ages]=$ages;


    if($groupName1){
        $arr_keywords1=explode(",",$keywords1);
        $data[keywordGroups][]=array(
           "groupName"=>$groupName1,
           "keywords"=>$arr_keywords1,
        );
    }

    if($groupName2){
        $arr_keywords2=explode(",",$keywords2);
        $data[keywordGroups][]=array(
           "groupName"=>$groupName2,
           "keywords"=>$arr_keywords2,
        );
    }

    if($groupName3){
        $arr_keywords3=explode(",",$keywords3);
        $data[keywordGroups][]=array(
           "groupName"=>$groupName3,
           "keywords"=>$arr_keywords3,
        );
    }

    if($groupName4){
        $arr_keywords4=explode(",",$keywords4);
        $data[keywordGroups][]=array(
           "groupName"=>$groupName4,
           "keywords"=>$arr_keywords4,
        );
    }

    if($groupName5){
        $arr_keywords5=explode(",",$keywords5);
        $data[keywordGroups][]=array(
           "groupName"=>$groupName5,
           "keywords"=>$arr_keywords5,
        );
    }


    $json_data = to_han(json_encode($data));

    //print_r($json_data); exit;


    // 네이버 데이터랩 통합검색어 트렌드 Open API 예제
    $client_id = $Client_ID; // 네이버 개발자센터에서 발급받은 CLIENT ID
    $client_secret = $Client_Secret;// 네이버 개발자센터에서 발급받은 CLIENT SECRET
    $url = "https://openapi.naver.com/v1/datalab/search";
    //$body = "{\"startDate\":\"2017-01-01\",\"endDate\":\"2017-04-30\",\"timeUnit\":\"month\",\"keywordGroups\":[{\"groupName\":\"한글\",\"keywords\":[\"한글\",\"korean\"]},{\"groupName\":\"영어\",\"keywords\":[\"영어\",\"english\"]}],\"device\":\"pc\",\"ages\":[\"1\",\"2\"],\"gender\":\"f\"}";
    $body = $json_data;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = array();
    $headers[] = "X-Naver-Client-Id: ".$client_id;
    $headers[] = "X-Naver-Client-Secret: ".$client_secret;
    $headers[] = "Content-Type: application/json";
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    // SSL 이슈가 있을 경우, 아래 코드 주석 해제
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
    $response = curl_exec ($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //echo "status_code:".$status_code." ";
    curl_close ($ch);


    if($status_code == 200) {
    } else {
      $error =  "Error 내용:".$response;
    }


    $rt = json_decode($response);


    $rs[startDate]=$startDate;
    $rs[endDate]=$endDate;
    $rs[timeUnit]=$timeUnit;
    $rs[device]=$device;
    $rs[gender]=$gender;
    $rs[gender]=$gender;

    $rs[groupName1]=$groupName1;
    $rs[groupName2]=$groupName2;
    $rs[groupName3]=$groupName3;
    $rs[groupName4]=$groupName4;
    $rs[groupName5]=$groupName5;

    $rs[keywords1]=$keywords1;
    $rs[keywords2]=$keywords2;
    $rs[keywords3]=$keywords3;
    $rs[keywords4]=$keywords4;
    $rs[keywords5]=$keywords5;


    if(is_array($ages)){
        $ages_ = "";
        foreach ($ages as $key => $value) {
            $ages_ .= ",".$value;
        }
        $ages_ = substr($ages_,1);
    }

}else{


    if($keywords){
        $word = strstr($keywords,",");
        $rs[groupName1] = str_replace($word,"",$keywords);
        $rs[keywords1] = substr($word,1);
    }

}




?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
    var fm = document.fmData;
    if(check_blank(fm.groupName1,'주제어를',0)=='wrong'){return }
    fm.submit();
}

function set_keyword(i){
    var groupName = $("#groupName"+i).val();
    var keywords = $("#keywords"+i).val();
    var keyword = groupName;
    if(is_empty(keywords)==false) keyword+=","+keywords;
    opener.$("#keyword").val(keyword);
    self.close();
}

$(function(){
    $(".groupName").attr("placeholder","주제어 입력");
    $(".keywords").attr("placeholder","검색어 콤마로 구분하여 입력. 최대 20개");
});
</script>
<style type="text/css">
body{
    padding:0 10px;
}    
.tbl_td{padding:5px;} 
.bar{display:inline-block;height:100%;width:100%;color:#fff;}
</style>

<!-- Load c3.css -->
<link href="../../lib/c3/c3.css" rel="stylesheet">
<!-- Load d3.js and c3.js -->
<script src="https://d3js.org/d3.v5.min.js" charset="utf-8"></script>
<script src="../../lib/c3/c3.min.js"></script>

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

    <table border="0" cellspacing="1" cellpadding="3" width="100%" class="tbl_reg">
        <form name="fmData" method="post" action="<?=SELF?>">
        <input type="hidden" name="mode" value="find">
        <input type="hidden" name="keywords" value="<?=$keywords?>">

        <tr><td colspan="2" bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td class="subject" width="15%">* 주제어1</td>
          <td class="tbl_td">
            <?=html_input('groupName1',20,20,'box groupName')?>
            <?=html_input('keywords1',60,100,'box keywords')?>
            <?if($groupName1){?><span class="btn_pack medium bold"><a href="javascript:set_keyword(1)"> 적용 </a></span><?}?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td class="subject">* 주제어2</td>
          <td class="tbl_td">
            <?=html_input('groupName2',20,20,'box groupName')?>
            <?=html_input('keywords2',60,100,'box keywords')?>
            <?if($groupName2){?><span class="btn_pack medium bold"><a href="javascript:set_keyword(2)"> 적용 </a></span><?}?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td class="subject">* 주제어3</td>
          <td class="tbl_td">
            <?=html_input('groupName3',20,20,'box groupName')?>
            <?=html_input('keywords3',60,100,'box keywords')?>
            <?if($groupName3){?><span class="btn_pack medium bold"><a href="javascript:set_keyword(3)"> 적용 </a></span><?}?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td class="subject">* 주제어4</td>
          <td class="tbl_td">
            <?=html_input('groupName4',20,20,'box groupName')?>
            <?=html_input('keywords4',60,100,'box keywords')?>
            <?if($groupName4){?><span class="btn_pack medium bold"><a href="javascript:set_keyword(4)"> 적용 </a></span><?}?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td class="subject">* 주제어5</td>
          <td class="tbl_td">
            <?=html_input('groupName5',20,20,'box groupName')?>
            <?=html_input('keywords5',60,100,'box keywords')?>
            <?if($groupName5){?><span class="btn_pack medium bold"><a href="javascript:set_keyword(5)"> 적용 </a></span><?}?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <tr>
          <td class="subject">검색 조건</td>
          <td class="tbl_td">
                <?=html_input('startDate',11,10,'box c dateinput')?> ~
                <?=html_input('endDate',11,10,'box c dateinput')?>

                <?$timeUnit=($timeUnit)?$timeUnit:"week";?>
                <select name="timeUnit">
                    <?=option_str("일단위,주단위,월단위","date,week,month",$timeUnit)?>
                </select>
                <select name="device">
                    <?=option_str("모든 디바이스,PC,모바일",",pc,mo",$device)?>
                </select>
                <select name="gender">
                    <?=option_str("모든 성별,남성,여성",",m,f",$gender)?>
                </select>                
                <div style="display:block;width:100%;margin-top:10px">
                    <?=checkbox("19∼24세,25∼29세,30∼34세,35∼39세,40∼44세,45∼49세,50∼54세,55∼59세,60세 이상","3,4,5,6,7,8,9,10,11",$ages_,"ages")?>
                </div>

          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>        



        <tr><td colspan="2" bgcolor='#E1E1E1' height=3></td></tr>

        <tr>
          <td colspan="2">
              <br>
              <!-- Button Begin---------------------------------------------->
              <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
                <tr align="right">
                    <td>
                        <span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 검색 </a></span>
                        <span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
                    </td>
                </tr>
              </table>
              <!-- Button End------------------------------------------------>
          </td>
        </tr>

    </form>
    </table>


    <div style="outline: 1px solid #ccc;margin-top:10px;height:360px">
    <?if($status_code == 200) {?>
            <?
            $DATA =array();
            $rt = json_decode($response);          
            ?>
            <div style="display:inline-block;margin:40px 0 20px 0;width:100%;">
                      <div id="chart"></div>
                <script>
                $(function(){
                    var chart = c3.generate({
                        data: {
                            columns: [
                                <?
                                foreach ($rt->results as $key => $value) {
                                ?>
                                ['<?=$value->title?>',
                                    <?
                                    foreach ($value->data as $key2 => $value2) {
                                        echo round($value2->ratio).",";
                                    }
                                    ?>
                                ],
                                <?}?>
                            ]
                        }
                    });

                })
                </script>
            </div>
    <?}?>

        <?if($mode!="find"){?>
            <span style="color:gray;padding-top:170px;display:inline-block;">키워드를 입력하고 검색해 주세요.</span>
        <?}?>

        <span class="red"><?if($error) echo "키워드 리서치에 실패했습니다. 검색어와 기간을 확인해 주세요.";?></span>
    </div>
    <div style="margin-top:5px;color:darkblue">
        * 요청된 기간 중 검색 횟수가 가장 높은 시점을 100으로 두고 나머지는 상대적 값으로 표시됩니다. 
        특정 주제, 검색어의 트렌드를 파악할 수 있습니다.
    </div>



<!-- 
        <table border="0" cellspacing="1" cellpadding="3" width="100%">
            <tr><td colspan="2" bgcolor='#5E90AE' height=2></td></tr>
            <tr>
              <td class="subject" width="15%"><?=$value?></td>
              <td style="background:#eee;text-align:left;"><span class="bar">10.4%</span></td>
            </tr>
            <tr><td colspan="2" class="tblLine"></td></tr>
        </table> -->



    </div>


</center>

    <!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>
