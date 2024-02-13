<?
include_once("../include/common_file.php");


chk_power($_SESSION["sessLogin"]["proof"],"담당자관리");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_staff";
$MENU = "cmp_paper";
$TITLE = "권한관리";



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
    $best="";    //배너 select 초기화
    $findMode=1;
}



/*권한 제한 s*/
include("inc_filter.php");
/*권한 제한 f*/



#query
$sql_1 = "
    select 
        a.*,
        (select company from cmp_cp where id=a.cp_id) as cp_company 
    from $table as a
    where 
        a.id<>'' 
        $filter
";        
$sql_2 = $sql_1 . " order by id_no desc limit  $start, $view_row";

$sql_3 = $sql_1 . " order by FIELD(staff_type,'staff','leader_partner','ceo','partner','partner_i','partner_a','partner_g','') limit $start,$view_row";


####자료갯수
list($rows)=$dbo->query($sql_1);//검색된 자료의 갯수
list($rows)=$dbo->query($sql_1);//검색된 자료의 갯수
$row_search = $rows;



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
$selectTxt = "이름,아이디";
$selectValue ="name,id";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1&mposition=$mposition";
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
        alert("삭제할 자료를 선택하지 않으셨습니다.");
        return;
    }
    if(confirm("선택한 자료를 삭제하시겠습니까?")){
        fm.action="view_<?=$filecode?>.php";
        fm.mode.value="drop";
        fm.submit();
    }
}

function bit_hide(){
    var j = 0;
    fm = document.fmData;

    for(var i = 1; i < fm.elements.length; i++){
        if(fm.elements[i].checked == 1){
            j++;
        }
    }
    if(j == 0){
        alert("변경할 자료를 선택하지 않으셨습니다.");
        return;
    }
    if(confirm("변경할 자료를 삭제하시겠습니까?")){
        fm.action="view_<?=$filecode?>.php";
        fm.mode.value="bit_hide";
        fm.submit();
    }
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


    <tr height=22>
    <td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=number_format($total_page)?> page /  <?=number_format($page)?> page }<?}?></font></td>
    <td valign='bottom' align=right>
    <?if($keyword || $find_bit):?>
    <input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
    <?endif;?>

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

        <tr align="center" height=25 bgcolor="#F7F7F6">
        <th class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></th>
        <th class="subject" >번호</th>
        <th class="subject" ><a href="<?=SELF?>?mposition=1">구분 </a></th>
        <th class="subject" >접속허용</th>
        <th class="subject" >파트너</th>
        <th class="subject" >아이디</th>
        <th class="subject" >이름</th>
        <th class="subject" >접속IP</th>
        <th class="subject" >마지막 로그인</th>
        <th class="subject" >등록일</th>
        </tr>


<?
if($page!=1){
    $num=$row_search-($view_row*($page-1));
} else{$num=$row_search;
}
if($mposition == 1){
    $dbo->query($sql_3);
    if($debug) checkVar(mysql_error(),$sql_3);
} else{
    $dbo->query($sql_2);
    if($debug) checkVar(mysql_error(),$sql_2);
}

while($rs=$dbo->next_record()){

    $bit_login = ($rs[bit_login])?"허용":"차단";
?>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td height="35"><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
          <td><?=$num?></td>
          <td>
            <?
            if($rs[staff_type]=="ceo") echo "CEO";
            elseif($rs[staff_type]=="leader_partner") echo "직원+파트너";
            elseif($rs[staff_type]=="staff") echo "직원";
            elseif($rs[staff_type]=="partner_i") echo "파트너I";
            elseif($rs[staff_type]=="partner_a") echo "파트너A";
            elseif($rs[staff_type]=="partner_g") echo "파트너G";
            ?>
          </td>
          <td><?=color($bit_login)?> <span style="color:red"><?=($rs[bit_hide])?"(비활성화)":""?></span></td>
          <td><?=$rs[cp_company]?></td>
          <td><?=$rs[id]?></td>
          <td><a class=soft href="view_<?=$filecode?>.php?id_no=<?=$rs[id_no]?>&<?=$basicLink?>" onFocus="blur(this)" title="상세정보보기"><?=$rs[name]?></a>

          </td>
          <td><?=$rs[ip]?></td>
          <td><?=$rs[last_login]?></td>
          <td><?=$rs[reg_date]?></td>
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
                        <select id="bit_hide" name="bit_hide">
                            <?=option_str("활성화,비활성화","0,1")?>
                        </select>
                        <span class="btn_pack medium bold"><a href="javascript:bit_hide()"> 변경 </a></span>&nbsp;
                  </td>
                  <td align="right">
                    <span class="btn_pack medium bold"><a href="view_<?=$filecode?>.php"> 등록 </a></span>&nbsp;
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
