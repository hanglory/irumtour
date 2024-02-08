<?
include_once("../include/common_file.php");




include($_SERVER['DOCUMENT_ROOT']."/new/bkoff/cmp/inc_chk_power_j.php");








chk_power($_SESSION["sessLogin"]["proof"],"견적서관리대장");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_estimate";
$MENU = "cmp_basic";
$LEFT_HIDDEN="1";
$TITLE = "일정표 등록";

####각종 기초 정보 결정
$view_row=15;   //한 페이지에 보여줄 행 수를 결정

if(!$page){     //페이지 디폴트 정보 결정
    $page=1;
}
$start=($view_row*($page-1))+1; //페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$column = "*";
$basicLink = "";


$sql2 = "select * from cmp_staff where id='$user_id'";
$dbo2->query($sql2);
$rs2=$dbo2->next_record();
$cp_url = $rs2[cp_url];



####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword){
    $filter .=" and $target like '%$keyword%' ";
    $best="";    //배너 select 초기화
    $findMode=1;
}

if($date_s || $date_e){
    if($date_s) $filter.=" and $dtype >='$date_s'";
    if($date_e) $filter.=" and $dtype <='$date_e'";
}
if($bit_worked) $filter.=" and bit_worked=1";

if(strstr($_SESSION["sessLogin"]["staff_type"],"partner")) $filter.=" and (a.main_staff like '%($user_id)' or a.bit_read_all=1)";

#query
$sql_1 = "
    select 
        a.*,
        b.nation
    from $table as a left join cmp_golf as b
    on a.golf_id_no=b.id_no
    where a.id_no>0 
    $filter
    ";
$sql_2 = $sql_1 . " order by a.id_no desc limit  $start, $view_row";    


//checkVar("",$sql_2);



####자료갯수
list($rows)=$dbo->query($sql_1);//검색된 자료의 갯수
$row_search = $rows;

if($keyword){
    $sql_3 = "
        select 
            count(b.origin_id_no) as cnt
            from $table as a left join cmp_reservation as b
            on a.id_no = b.origin_id_no
            where a.id_no>0 $filter
            and b.origin_id_no<>''
        ";
    $dbo3->query($sql_3);
    $rs3=$dbo3->next_record();
    $cnt_y = $rs3[cnt];
    $percent = round(($cnt_y/$row_search)*100,1);
}



####페이지 처리
$var=ceil($row_search/$view_row);
if ($var > 1){
    $total_page=$var;
}
else{
    $total_page=1;
}



####자료가 하나도 없을 경우의 처리
if(!$row_search){
   $error[noData] = accentError("해당하는 자료가 없습니다.");
}



####검색 항목
$selectTxt = "대표자,경로,발송일,담당자,서브 담당자,예약일,출국일,귀국일,골프장명,연락처,출발지,국가";
$selectValue ="a.name,a.view_path,a.send_date,a.main_staff,a.sub_staff,a.tour_date,a.d_date,a.r_date,a.golf_name,a.phone,a.point_dep,b.nation";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1&dtype=$dtype&date_s=$date_s&date_e=$date_e&bit_worked=$bit_worked";
$sessLink = "page=$page&" . $link;

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


function copy(id_no){
    actarea.location.href="list_<?=$filecode?>_copy.php?id_no="+id_no;
}


function copy_to_clip(val) {
  var t = document.createElement("textarea");
  document.body.appendChild(t);
  t.value = val;
  t.select();
  document.execCommand('copy');
  document.body.removeChild(t);
  alert("복사되었습니다.");
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
    <form name="fmSearch" method="get">
    <input type="hidden" name='position' value="">
    <input type="hidden" name='ctg1' value="<?=$ctg1?>">


    <tr height=22>
    <td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=number_format($total_page)?> page /  <?=number_format($page)?> page }<?}?>
    <?if($keyword){?>(예약율 : <?=nf($cnt_y)?>/<?=nf($row_search)?>건 <?=$percent?>%)<?}?>
    </font></td>
    <td valign='bottom' align=right>
    <?if($keyword || $find_bit):?>
    <input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
    <?endif;?>


    <select name="dtype">
        <?=option_str("발송일,출국일,귀국일","send_date,d_date,r_date",$dtype)?>
    </select>

    <input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?=$date_s?>" class="box c dateinput">
    ~
    <input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?=$date_e?>" class="box c dateinput">

    <label><input type="checkbox" name="bit_worked" id="bit_worked" value="1" <?=($bit_worked)?'checked':''?>> 미처리</label>

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

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
       <form name="fmData" method="post">
       <input type=hidden name=mode value='drop'>

        <tr align=center height=25 bgcolor="#F7F7F6">
        <th class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></th>
        <th class="subject">대표자명</th>
        <th class="subject">출발지</th>
        <th class="subject">예약</th>
        <th class="subject">경로</th>
        <th class="subject">담당자</th>
        <th class="subject">발송일</th>
        <th class="subject">출국일</th>
        <th class="subject">귀국일</th>
        <th class="subject">체류일</th>
        <th class="subject">인원</th>
        <th class="subject">국가</th>
        <th class="subject">상품명</th>
        <th class="subject">거래처</th>
        <th class="subject">판매가</th>
        <th class="subject">일정표</th>
        <th class="subject">복사</th>
        <th class="subject">링크</th>
        </tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar($LOGIN_STAFF_TYPE.mysql_error(),$sql_2);}
while($rs=$dbo->next_record()){

    $sql3="select * from cmp_ex_link where code='$rs[code]'";
    $dbo3->query($sql3);
    $rs3=$dbo3->next_record();
    $url1 = $rs3[url];

    $sql3="select id_no from cmp_reservation where origin_id_no=$rs[id_no]";
    $dbo3->query($sql3);
    $rs2=$dbo3->next_record();
    $id_no_reservation = $rs2[id_no];
    $bit_copy = ($rs2[id_no])?"Y":"";
    //if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar($bit_copy,$sql3);}

    $sql3="select * from cmp_golf where id_no=$rs[golf_id_no]";
    $dbo3->query($sql3);
    $rs2=$dbo3->next_record();
    
    $long_url1 =  "${DOMAIN}/new/bkoff/cmp/form06.html?code=". str_replace("+","{p}",encrypt($rs[id_no],$SALT));
    $long_url2 =  "${DOMAIN}/new/bkoff/cmp/form08.html?code=". str_replace("+","{p}",encrypt($rs[id_no],$SALT));

?>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td height="35"><input type="checkbox" name="check[]" value="<?=$rs[code];?>"></td>
          <td><a href="javascript:newWin('view_<?=$filecode?>.php?id_no=<?=$rs[id_no]?>',1200,650,1,1,'','estimate')"><?=$rs[name]?></a></td>
          <td><?=$rs[point_dep]?></td>
          <td><a href="javascript:newWin('view_reservation.php?id_no=<?=$id_no_reservation?>',1200,650,1,1,'','reservation')" style="<?=$css?>"><?=$bit_copy?></a></td>
          <td><?=$rs[view_path]?><?//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){echo $rs[plan_type];}?></td>
          <td><?=$rs[main_staff]?></td>
          <td><?=substr($rs[send_date],2)?></td>
          <td><?=substr($rs[d_date],2)?></td>
          <td><?=substr($rs[r_date],2)?></td>
          <td><?=get_day_night($rs[d_date],$rs[r_date])?></td>
          <td><?=nf($rs[people])?></td>
          <td><?=$rs[nation]?></td>
          <td align="left" style="padding-left:10px"><span style="display:inline-block;width:100%;height:20px;overflow-x: hidden;"><?=($rs[subject])? $rs[subject]: $rs[golf_name]?></span></td>
          <td><?=titleCut2($rs[partner],10)?></td>
          <td><?=nf($rs[price])?></td>
          <td>
            <?if($rs[plan_type]){?>
            <span class="btn_pack medium bold"><a href="javascript:newWin('form06.html?id_no=<?=$rs[id_no]?>',950,650,1,1,'','reservation<?=$rs[id_no]?>')">일정표<?=($REMOTE_ADDR=="221.154.110.119")? $rs[plan_type] : ''?></a>
            </span>
            <?}else{?>
            <span class="btn_pack medium bold"><a href="javascript:alert('일정표 TYPE이 없어 견적서를 열 수 없습니다.')">일정표<?=($REMOTE_ADDR=="221.154.110.119")? $rs[plan_type] : ''?></a>
            </span>             
            <?}?>
          </td>
          <td><span class="btn_pack medium bold"><a href="javascript:copy(<?=$rs[id_no];?>)"> 복사 </a></span></td>
          <td> 
            <?if($url1){?>
                <span class="btn_pack medium bold"><a href="javascript:copy_to_clip('<?=$url1?>')"> 링크복사 </a></span>
                <span class="btn_pack medium bold"><a href="<?=$url1?>" target="_blank"> 바로가기 </a></span>
            <?}?>
          </td>
        </tr>
<?
    $num--;
}
?>
    </table>
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
        <tr>
          <td colspan="12">

          <br>
          <!-- Button Begin---------------------------------------------->
          <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
             <tr>
              <td width="60%" align="left">

              </td>
              <td align="right">
              <?if(strstr($_SESSION["sessLogin"]["proof"],"엑셀다운로드")){?>
                <span class="btn_pack medium bold"><a href="list_estimate_excel.php?target=<?=$target?>&keyword=<?=$keyword?>&dtype=<?=$dtype?>&date_s=<?=$date_s?>&date_e=<?=$date_e?>"> 엑셀 다운로드 </a></span>&nbsp;
              <?}?>
                <span class="btn_pack medium bold"><a href="javascript:newWin('view_<?=$filecode?>.php?ctg1=<?=$ctg1?>',1200,650,1,1,'','estimate')"> 등록 </a></span>&nbsp;
                <span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span>
              </td>
            </tr>
          </table>
          <!-- Button End------------------------------------------------>

          </td>
        </tr>

        <?if(!$seq_mode){?>
        <tr>
          <td colspan="12"  align=center style="padding-top:20px">
            <!-- navigation Begin---------------------------------------------->
            <?include_once('../../include/navigation.php')?>
            <?=$navi?>
            <!-- navigation End------------------------------------------------>
          </td>
        </tr>
        <?}?>
    </form>
    </table>



    <!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>


