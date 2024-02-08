<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour";
$MENU = "tour";
$TITLE = "상품관리";
$filter = " where bit=1";
If($position=="top_") $position="";
$seq_mode=0;


if($mode){
    $category=$category_step1;
    $category.="-".$category_step2;
    $category.="-".$category_step3;
}




switch($mode){

    case "up":
        $top2 = $top-1;
        $sql1 = "update $table set seq=seq+1 where sale_group='T' and seq=$top2 and category1 ='$category'";
        $sql2 = "update $table set seq=seq-1 where sale_group='T' and id_no = '$id_no' and category1 ='$category'";

        $dbo->query($sql1);
        $dbo->query($sql2);

        echo "<script>history.back(-1)</script>";
        exit;
        break;

    case "down":
        $top2 = $top+1;
        $sql1 = "update $table set seq=seq-1 where sale_group='T' and seq=$top2 and category1 ='$category'";
        $sql2 = "update $table set seq=seq+1 where sale_group='T' and id_no = '$id_no' and category1 ='$category'";


        $dbo->query($sql1);
        $dbo->query($sql2);

        echo "<script>history.back(-1)</script>";
        exit;
        break;
}




$sql = "select * from ez_tour_category3 where code2=$category_step2";
list($rows)= $dbo->query($sql);
if($rows){
    if(!$best && $category_step1 && $category_step2 && $category_step3 && $sale_group=="T" && !$keyword) $seq_mode=1;
}else{
    if(!$best && $category_step1 && $category_step2 && !$category_step3 && $sale_group=="T" && !$keyword) $seq_mode=1;
}


#### category
If($category_step1) $ctg1 = $category_step1;
Else $category_step1 = $ctg1;

if($best=="c2"){
    $category_step1="14";
    $ctg1="14";
    $seq_mode=1;        
    if($sale_group!="F") $sale_group="T";
}
elseif($best=="c1" || $best=="c4"){
    $category_step1="";
    $category_step2="";
    $category_step3="";
    $ctg1="";
    $ctg2="";
    $ctg3="";
    $seq_mode=1;
    if($sale_group!="F") $sale_group="T";
}


If($ctg1){
    $sql = "select * from ez_tour_category1 where id_no=$ctg1";
    $dbo->query($sql);
    $rs=$dbo->next_record();
    $TITLE  .= " > " . $rs[subject];
}


####등록이 완료되지 않고 남아 있는 상품 지우기
$time_drop = date("Y/m/d",time()-86400);
$sql = "delete from ez_tour where reg_date<'$time_drop' and (bit is null or bit='') ";
list($rows) = $dbo->query($sql);
//checkVar(mysql_error(),$sql);
//checkVar("rows",$rows);



####각종 기초 정보 결정
$view_row=20;   //한 페이지에 보여줄 행 수를 결정

if(!$page){     //페이지 디폴트 정보 결정
    $page=1;
}
$start=($view_row*($page-1))+1; //페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$column = "*";
$basicLink = "";


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)

#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword){
    $filter .=" and $target like '%$keyword%' ";
    //$best="";  //배너 select 초기화
    $findMode=1;
}

if($best){
    $position = "top_" . $best;
}



$category=$category_step1."-";
if($category_step2) $category.=$category_step2."-";
if($category_step3) $category.=$category_step3;


if($category!="-"){
    $filter.="
        and (
            category1 like '${category}%'
            or category2 like '${category}%'
            or category3 like '${category}%'
            or category4 like '${category}%'
            or category5 like '${category}%'
            or category6 like '${category}%'
        )
    ";
    $find_bit=1;
}



$filter .=($CP_ID)? " and cp_id in ('$CP_ID','')" : " and cp_id=''";
if($sale_group){
  $filter .= " and sale_group='$sale_group'";
  $find_bit=1;
}

if($position){
  $filter .= " and $position='1'";
  $find_bit=1;
}

if($tour_group){
  $filter .= " and tour_group='$tour_group'";
  $find_bit=1;
}


$filter2 = substr($filter2,3);
if($filter2) $filter.=" and ($filter2)";

$sort =($seq_mode)? "seq asc" : "id_no desc";
if($seq_mode){
    $sort ="seq asc";
}else{
    $sort ="id_no desc";
}
if($position) $sort="hit desc ";

#query
$sql_1 = "
    select 
        * 
    from $table 
    $filter
    ";
$sql_2 =  $sql_1 . " order by $sort "; //limit  $start, $view_row



/*seq s*/
if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
//include("inc_tour_seq_check.php");//cp에 해당하는 ez_tour_seq가 없으면 생성
//if($seq_mode) include("inc_tour_seq.php");
}
/*seq f*/



####자료갯수
list($rows)=$dbo->query($sql_1);//검색된 자료의 갯수
$row_search = $rows;



####페이지 처리
$var=ceil($row_search/$view_row);
$total_page = ($var > 1)? $var : 1;



####자료가 하나도 없을 경우의 처리
if(!$row_search){
   $error[noData] = accentError("해당하는 자료가 없습니다.");
}



####검색 항목
$selectTxt = "상품명,상품코드,지역,현지여행사,관광지계절,적용기간,일정표 TYPE,이용항공/좌석";
$selectValue ="subject,tid,region,local_company,place_season,period,plan_type,air_name";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1";
$sessLink = "page=$page&" . $link;


if($best) $seq_mode=0;
//-------------------------------------------------------------------------------
?>
<?include("../top.html");?>
<script language="JavaScript">
<!--
function selectAll(){
    fm = document.fmData;
    for(var i = 1; i < fm.elements.length; i++){
        fm.elements[i].checked = (fm.checkAll.checked == 1)? 1 : 0;
    }
}

function del(){
    var j = 0;
    fm = document.fmData;

    for(var i = 1; i < fm.elements.length; i++){
        if(fm.elements[i].checked == 1){
            j++;
        }
    }
    if(j == 0){
        alert("삭제할 상품을 선택하지 않으셨습니다.");
        return;
    }
    if(confirm("선택한 상품을 삭제하시겠습니까?")){
        fm.action="view_<?=$filecode?>.php";
        fm.mode.value="drop";
        fm.submit();
    }
}

function chng_category(){
    var j = 0;
    fm = document.fmData;

    if(check_select(fm.chng_category_step1,'변경할 여행분류를')=='wrong'){return }

    for(var i = 1; i < fm.elements.length; i++){
        if(fm.elements[i].checked == 1){
            j++;
        }
    }
    if(j == 0){
        alert("카테고리를 변경할 상품을 선택하지 않으셨습니다.");
        return;
    }
    if(confirm("카테고리를 변경하시겠습니까?")){
        fm.action="view_<?=$filecode?>.php";
        fm.mode.value="category";
        fm.submit();
    }
}

function chng_period(){
    var j = 0;
    fm = document.fmData;

    if(check_blank(fm.period,'적용기간을',0)=='wrong'){return }

    for(var i = 1; i < fm.elements.length; i++){
        if(fm.elements[i].checked == 1){
            j++;
        }
    }
    if(j == 0){
        alert("적용기간을 변경할 상품을 선택하지 않으셨습니다.");
        return;
    }
    if(confirm("적용기간을 변경하시겠습니까?")){
        fm.action="view_<?=$filecode?>.php";
        fm.mode.value="period";
        fm.submit();
    }
}

function chng_sale(){
    var j = 0;
    fm = document.fmData;

    for(var i = 1; i < fm.elements.length; i++){
        if(fm.elements[i].checked == 1){
            j++;
        }
    }
    if(j == 0){
        alert("게시/감추기를 변경할 상품을 선택하지 않으셨습니다.");
        return;
    }
    if(confirm("게시/감추기를 적용하시겠습니까?")){
        fm.action="view_<?=$filecode?>.php";
        fm.mode.value="sale";
        fm.submit();
    }
}
function chng_price(){
    var j = 0;
    fm = document.fmData;

    for(var i = 1; i < fm.elements.length; i++){
        if(fm.elements[i].checked == 1){
            j++;
        }
    }
    if(j == 0){
        alert("금액을 변경할 상품을 선택하지 않으셨습니다.");
        return;
    }
    if(confirm("금액을 변경 하시겠습니까?")){
        fm.action="view_<?=$filecode?>.php";
        fm.mode.value="price_add";
        fm.submit();
    }
}

function mng_days(tid){
    newWin('pop_days02.php?tid='+tid,960,800,1,1);
}

function mng_price(tid,days,bstations){
    newWin('pop_price.php?tid='+tid+'&days='+days+'&bstations='+bstations,950,800,1,1);
}

function mng_hotel_price(tid){
    newWin('pop_hotel_price.php?tid='+tid,850,800,1,1);
}

function mng_calendar(tid){
    newWin('pop_calendar.php?tid='+tid,600,800,1,1);
}

function set_position(str,bit){
    var j = 0;
    var fm = document.fmData;

    for(var i = 1; i < fm.elements.length; i++){
        if(fm.elements[i].checked == 1){
            j++;
        }
    }

    if(j == 0){
        alert("상품을 선택하지 않으셨습니다.");
        return;
    }
    if(confirm("변경하시겠습니까?")){
        fm.mode.value="position";
        fm.action="set_position.php";
        fm.position_assort.value=str;
        fm.position_bit.value=bit;
        fm.best_copy.value=str;
        fm.submit();
    }
}

function set_banner(){
    var best = $("#best").val();
    if(best !=""){
        get_position(best);
    }
}

function get_position(str){
    var fm = document.fmSearch;
    fm.position.value="top_"+str;

    if(fm.category_step1.value==""){fm.best.value='';alert('먼저 상품 분류를 선택해 주세요');return}

    fm.submit();
}

function copy(tid){
    if(confirm('상품을 복사하시겠습니까?')){
        location.href="tour_copy.php?mode=copy&tid="+tid;
    }
}

function save_best(best,tid,obj){
    let bit =(obj.checked==true)?1:0;
    let url = "list_tour_j.php?mode=set_best";
    url+="&tid="+tid;
    url+="&best="+best;
    url+="&code1=<?=$category_step1?>";
    url+="&code2=<?=$category_step2?>";
    url+="&code3=<?=$category_step3?>";
    url+="&bit="+bit;
    actarea.location.href=url;
}


//-->
</script>
<?include("../../include/tour_options.php");?>
<?include("../../include/tour_options2.php");?>
<script type="text/javascript">
<!--
<?
$category1 =explode("-",$category);
?>
$(function(){
    setOption(document.getElementById('category_step1'),'','<?=$category1[1]?>');
    setOption2(document.getElementById('category_step2'),'','<?=$category1[2]?>');
});

function seq_updown(id_no,basic_seq,c_seq,seq){
    var url="<?=SELF?>?mode=seq_updown&category1=<?=$category?>&id_no="+id_no+"&basic_seq="+basic_seq+"&c_seq="+c_seq+"&seq="+seq
    location.href=url;
}
//-->
</script>


    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?>

        </td>
      </tr>
      <tr>
        <td> </td>
      </tr>
       <tr>
        <td background="../images/common/bg_title.gif" height="5"></td>
      </tr>
    </table>


    <!--내용이 들어가는 곳 시작-->

    <!-- Search Begin------------------------------------------------>
    <div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
    <form name=fmSearch method="get">
    <input type=hidden name='position' value="">
    <input type=hidden name='ctg1' value="<?=$ctg1?>">
    <tr>
        <td colspan="3" align="right" style="padding: 0 5px 5px 0">

        <?
        $sql3 = "select * from ez_tour_category1 order by seq asc";
        $dbo3->query($sql3);
        while($rs3= $dbo3->next_record()){
            $keys .= "," . $rs3[subject];
            $vals .= "," . $rs3[id_no];
        }
        ?>
       <select name="category_step1" id="category_step1" onchange="setOption(this,'','');set_banner()">
         <?=option_str("대분류".$keys,$vals,$category1[0])?>
       </select>
       <select name="category_step2" id="category_step2" onchange="setOption2(this,'','');set_banner()">
       </select>
       <select name="category_step3" id="category_step3" onchange="set_banner()">
       </select>


        <select onchange="get_position(this.value)" id="best" name="best">
            <option value="">::추천상품 보기::</option>
            <?=option_str($BEST,$BEST_VAL,$best)?>
        </select>

        </td>
    </tr>


    <tr height="22">
    <td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=number_format($total_page)?> page /  <?=number_format($page)?> page }<?}?></font></td>
    <td valign='bottom' align=right>
    <?if($keyword || $find_bit):?>
    <input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
    <?endif;?>


     <select name="sale_group">
     <option value="">전체보기</optioni>
     <?=option_str($SALE_GROUP,$SALE_GROUP_VAL,$sale_group)?>
     </select>


    <select name="target" class='select'>
    <?=option_str($selectTxt,$selectValue,$target)?>
    </select>

    <input class=box type="text" name="keyword" size="15" maxlength="40" value='<?=$keyword?>'>
    <input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
    </td>
    <tr>
    </form>
    </table>
    </div>
    <!-- Search End------------------------------------------------>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>
       <input type=hidden name="position_bit" value="">
       <input type=hidden name="position_assort" value="">
       <input type=hidden name="ctg1" value="<?=$ctg1?>">
       <input type=hidden name="best_copy" value="">

       <input type=hidden name="category_step1" value="<?=$category_step1?>">
       <input type=hidden name="category_step2" value="<?=$category_step2?>">
       <input type=hidden name="category_step3" value="<?=$category_step3?>">


        <tr><td colspan="12"  bgcolor='#E1E1E1' height=3></td></tr>
        <tr>
          <td colspan="12">

          <br>
          <!-- Button Begin---------------------------------------------->
          <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
             <tr>
              <td width="60%" align="left">

<!--               <select name="chng_category_step1" id="chng_category_step1" onchange="chng_setOption(this,'','')">
                 <?=option_str("대분류".$keys,$vals)?>
               </select>
               <select name="chng_category_step2" id="chng_category_step2" onchange="chng_setOption2(this,'','')">
               </select>
               <select name="chng_category_step3" id="chng_category_step3">
               </select>
               <span class="btn_pack medium bold"><a href="javascript:chng_category()"> 카테고리 변경</a></span>
 -->
              </td>
              <td align="right">
                <span class="btn_pack medium bold"><a href="javascript:newWin('view_<?=$filecode?>.php?ctg1=<?=$ctg1?>',1000,700,1,1)"> 등록 </a></span>&nbsp;
                <span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span>
              </td>
            </tr>
          </table>
          <!-- Button End------------------------------------------------>

          </td>
        </tr>
        <?if(!$seq_mode){?>
        <tr><td colspan="12" height=10></td></tr>
        <tr>
          <td colspan="12">


            <!-- 신 -->
            <?if($position=="top_c1"){?>
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('c1',0)"> 시즌별품(메인) 해제</a></span>&nbsp;
            <?}elseif($position=="top_c2"){?>
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('c2',0)"> 테마별(메인) 해제</a></span>&nbsp;
            <?}elseif($position=="top_c4"){?>
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('c4',0)"> 초특가(메인) 해제</a></span>&nbsp;
            <?}elseif($position=="top_c3"){?>
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('c3',0)"> 인기상품(서브) 해제</a></span>&nbsp;
            <?}?>

            <?if($position==""){?>

                <!-- 신 -->
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('c1',1)"> 시즌별추천상품(메인) 설정</a></span>&nbsp;

                <?if($category_step1 && !$category_step2){?>
                    <?if($category_step1=="14"){?><span class="btn_pack medium bold"><a href="#" onClick="set_position('c2',1)"> 테마별(메인) 설정</a></span>&nbsp;<?}?>
                    <?if($category_step1=="16"){?><span class="btn_pack medium bold"><a href="#" onClick="set_position('c4',1)"> 초특가(메인) 설정</a></span>&nbsp;<?}?>
                    <span class="btn_pack medium bold"><a href="#" onClick="set_position('c3',1)"> 인기상품(서브) 설정</a></span>&nbsp;
                <?}?>
                    
            <?}?>

          </td>
        </tr>

        <?}?>
        <tr><td colspan="12" height=10></td></tr>
        

        <tr><td colspan="12"  bgcolor='#5E90AE' height=2></td></tr>
        <tr align=center height=25 bgcolor="#F7F7F6">
        <td class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
        <!-- <td class="subject">번호</td> -->
        <td class="subject l" width="150">카테고리</td>
        <td class="subject" width="30">코드</td>
        <td class="subject l">상품명</td>
        <td class="subject" width="30">Type</td>
        <td class="subject">적용기간 </td>
        <td class="subject">이용항공/좌석 </td>
        <td class="subject r">가격</td>
        <td class="subject">메인</td>
        <td class="subject" width="80">기능</td>
        <td class="subject" width="30">순위</td>
        </tr>
        <tr><td colspan="12" class='bar'></td></tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
if($debug) checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

    $category1 = explode("-",$rs[category1]);
    $category2 = explode("-",$rs[category2]);
    $category3 = explode("-",$rs[category3]);
    $category4 = explode("-",$rs[category4]);
    $category5 = explode("-",$rs[category5]);
    $category6 = explode("-",$rs[category6]);

    $ctg1_while =($ctg1)? $ctg1 : $category1[0];

    $train_mode = ($category1[0]==1 || $category1[0]==2 || $category3[0]==1 || $category4[0]==1 || $category5[0]==1 || $category6[0]==1)?1:0;
    $hotel_mode = ($category1[0]==7 || $category1[0]==7 || $category3[0]==7 || $category4[0]==7 || $category5[0]==7 || $category6[0]==7)?1:0;
    $railcartel_mode = ($category1[0]==1 && $category1[1]==7)?1:0;

    if($seq_mode){
        $seq = ($row_search+1)-$num;
        $sql3 = "update $table set seq=$seq where id_no=$rs[id_no]";
        $dbo3->query($sql3);
    }


    $bests="";
    $sql3 = "select * from ez_tour_seq where tid='$rs[tid]' and code1='$category_step1' and code2='$category_step2' and code3='$category_step3' and best<>'' and cp_id='$CP_ID'";
    $dbo3->query($sql3);
    //checkVar(mysql_error(),$sql3);
    while($rs3 =$dbo3->next_record()){
        $bests.="@$rs3[best]@";
    }
?>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td height="35"><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
          <!-- <td><?=$num?></td> -->
          <td class="l sf">
            <div><?=str_replace("여행","",get_category_name($rs[category1]))?></div>
            <div><?=str_replace("여행","",get_category_name($rs[category2]))?></div>
            <div><?=str_replace("여행","",get_category_name($rs[category3]))?></div>
            <div><?=str_replace("여행","",get_category_name($rs[category4]))?></div>
            <div><?=str_replace("여행","",get_category_name($rs[category5]))?></div>
            <div><?=str_replace("여행","",get_category_name($rs[category6]))?></div>
          </td>
          <td><a href="../../../renew/detailview.html?tid=<?=$rs[tid]?>" target="_blank"><?=$rs[tid]?></a></td>
          <td class="l pl10"><a class="soft <?=($rs[sale_group]=="F")?'gray':''?>" href="javascript:newWin('view_<?=$filecode?>.php?id_no=<?=$rs[id_no];?>&ctg1=<?=$ctg1_while?>',990,700,1,1)" ><?=$rs[subject]?></a></td>
          <td class="c"><?=$rs[plan_type]?></td>
          <td class="c"><?=$rs[period]?></td>
          <td class="c"><?=$rs[air_name]?></td>
          <td class="r pr10"><?=number_format($rs[price_adult])?></td>
          <td class="c">
                <label><input type="checkbox" onclick="save_best('c1','<?=$rs[tid]?>',this)" <?=(strstr($bests,"c1"))?"checked":""?>> 시즌</label>
                <?if($category_step1==14 && $category_step2){?>
                <label><input type="checkbox" onclick="save_best('c2','<?=$rs[tid]?>',this)" <?=(strstr($bests,"c2"))?"checked":""?>> 테마</label>
                <?}?>
                <label><input type="checkbox" onclick="save_best('c4','<?=$rs[tid]?>',this)" <?=(strstr($bests,"c4"))?"checked":""?>> 초특가</label>
          </td>          
          <td class="c">
            <span class="btn_pack small bold"><a href="javascript:mng_calendar(<?=$rs[tid]?>)"> 출발일 </a></span>&nbsp;

            <!-- <span class="btn_pack small bold"><a href="javascript:mng_days(<?=$rs[tid]?>)"> 일정 </a></span>&nbsp; -->

            <span class="btn_pack small bold"><a href="javascript:copy(<?=$rs[tid]?>)"> 복사 </a></span>
          </td>
          <td>
            <?if($seq_mode){?>
                <?if($num!=1){?><a href='?mode=down&id_no=<?=$rs[id_no]?>&top=<?=$rs[seq]?>&category_step1=<?=$category_step1?>&category_step2=<?=$category_step2?>&category_step3=<?=$category_step3?>&best=<?=$best?>&sale_group=<?=$sale_group?>' onfocus="blur(this)" class=soft>▼</a><?}?>
                <?if($num!=$row_search){?><a href='?mode=up&id_no=<?=$rs[id_no]?>&top=<?=$rs[seq]?>&category_step1=<?=$category_step1?>&category_step2=<?=$category_step2?>&category_step3=<?=$category_step3?>&best=<?=$best?>&sale_group=<?=$sale_group?>' onfocus="blur(this)" class=soft>▲</a><?}?>
            <?}?>

            <?if($best) echo $rs[hit]?>
          </td>
        </tr>
        <tr><td colspan="12" class='bar'></td></tr>
<?
    $num--;
}
?>
        <tr><td colspan="12" height=1><?=$error[noData]?></td></tr>
        <tr><td colspan="12"  bgcolor='#E1E1E1' height=3></td></tr>
        <tr>
          <td colspan="12">

          <br>
          <!-- Button Begin---------------------------------------------->
          <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
             <tr>
              <td width="80%" align="left">

               <select name="chng_category_step1" id="chng_category_step1" onchange="chng_setOption(this,'','')">
                 <?=option_str("대분류".$keys,$vals)?>
               </select>
               <select name="chng_category_step2" id="chng_category_step2" onchange="chng_setOption2(this,'','')">
               </select>
               <select name="chng_category_step3" id="chng_category_step3">
               </select>
               <span class="btn_pack medium bold"><a href="javascript:chng_category()"> 카테고리 변경</a></span>

               &nbsp;&nbsp; |
               &nbsp;&nbsp;

               <input type="text" name="period" id="period" value="" size="20" maxlength="20" class="box" placeholder="적용기간" />  
               <span class="btn_pack medium bold"><a href="javascript:chng_period()"> 적용기간 변경</a></span>

               &nbsp;&nbsp; |
               &nbsp;&nbsp;


               <select name="sale_group" id="sale_group">
                <?=option_str($SALE_GROUP,$SALE_GROUP_VAL)?>
               </select>
               <span class="btn_pack medium bold"><a href="javascript:chng_sale()"> 게시/감추 변경</a></span>



               &nbsp;&nbsp; |
               &nbsp;&nbsp;


               <select name="price_assort"><?=option_str("-,+","-,+")?></select>
               <input type="text" name="price_add" id="price_add" value="" size="4" maxlength="3" class="box numberic comma" placeholder="0" />만원
               <span class="btn_pack medium bold"><a href="javascript:chng_price()"> 금액 변경</a></span>


              </td>
              <td align="right">
                <span class="btn_pack medium bold"><a href="javascript:newWin('view_<?=$filecode?>.php?ctg1=<?=$ctg1?>',1000,700,1,1)"> 등록 </a></span>&nbsp;
                <span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span>
              </td>
            </tr>
          </table>
          <!-- Button End------------------------------------------------>

          </td>
        </tr>
        <?if(!$seq_mode){?>
        <tr><td colspan="12" height=10></td></tr>
        <tr>
          <td colspan="12">

            <!-- 구 -->
            <?if($position=="top_best"){?>
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('best',0)"> 카테고리 베스트 해제</a></span>&nbsp;
            <?}elseif($position=="top_premium"){?>
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('premium',0)"> Premium golf Tours  해제</a></span>&nbsp
            <?}elseif($position=="top_category"){?>
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('category',0)"> Category Best Tours  해제</a></span>&nbsp;
            <?}elseif($position=="top_recomm"){?>
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('recomm',0)"> 베스트 추천상품  해제</a></span>&nbsp;

            <!-- 신 -->
            <?}elseif($position=="top_c1"){?>
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('c1',0)"> 시즌별품(메인) 해제</a></span>&nbsp;
            <?}elseif($position=="top_c2"){?>
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('c2',0)"> 테마별(메인) 해제</a></span>&nbsp;
            <?}elseif($position=="top_c3"){?>
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('c3',0)"> 인기상품(서브) 해제</a></span>&nbsp;
            <?}?>

            <?if($position==""){?>

                <!-- 신 -->
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('c1',1)"> 시즌별품(메인) 설정</a></span>&nbsp;
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('c2',1)"> 테마별(메인) 설정</a></span>&nbsp;

                <?if($category_step1 && !$category_step2){?>
                <span class="btn_pack medium bold"><a href="#" onClick="set_position('c3',1)"> 인기상품(서브) 설정</a></span>&nbsp;
                <?}?>
                
            <?}?>

          </td>
        </tr>
        <?}?>

        <?if(!$seq_mode){?>
        <tr>
          <td colspan="12"  align=center style="padding-top:20px">
            <!-- navigation Begin---------------------------------------------->
            <?include_once('../../include/navigation.php')?>
            <?//=$navi?>
            <!-- navigation End------------------------------------------------>
          </td>
        </tr>
        <?}?>
    </form>
    </table>


    <!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>

