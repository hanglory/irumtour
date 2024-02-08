<?
include_once("../include/common_file.php");


#### 기본 정보
$filecode = substr(SELF,5,-4);
$table = "ez_request";
$MENU = "cmp_basic";
$TITLE = "골프투어문의";
$filter = "";
$column = "*";
$basicLink = "";
$LEFT_HIDDEN="1";



####파트너
$sql = "
    select
        *
    from cmp_cp
    where
        partner_type in ('','partner_i')
    order by company asc
";
$dbo->query($sql);
if($debug){checkVar(mysql_error(),$sql);}
$CIDS="";
$CIDS2="";
while($rs=$dbo->next_record()){
    $CIDS.=",".$rs[company]."($rs[id])";
    $CIDS2.=",".$rs[id];    
}



####각종 기초 정보 결정
$view_row=20;   //한 페이지에 보여줄 행 수를 결정

if(!$page){     //페이지 디폴트 정보 결정
    $page=1;
}
$start=($view_row*($page-1))+1; //페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
#검색조건


$filter = " and CID='$CP_ID'";
if(!strstr($_SESSION["sessLogin"]["staff_type"],"partner") && $CID_F) $filter = " and CID='$CID_F'";

if($keyword)     {
    $keyword =trim($keyword);
    $filter.=" and $target like '%$keyword%' ";$findMode=1;
}


$filter = ($filter)? " where " . substr($filter,5) : "";
$sort = ($sort)? $sort : "id_no desc";

#query
$sql1 = "select $column from $table  $filter";          //자료수
$sql2 = $sql1 . " order by $sort  limit  $start, $view_row";
//checkVar("",$sql2);


####자료갯수
list($rows)=$dbo->query($sql1);//검색된 자료의 갯수
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
$selectTxt = "성명,소속,희망지역,희망국가,구분";
$selectValue ="name,org,nation,area,assort";

#### Link
$link = "keyword=$keyword&target=$target&sort=$sort&";
$sessLink = "page=$page&" . $link;

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
        alert("삭제할 자료를 선택하지 않으셨습니다.");
        return;
    }
    if(confirm("선택한 자료를 삭제하시겠습니까?")){
        fm.action="view_<?=$filecode?>.php";
        fm.submit();
    }
}

//-->
</script>





    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
      </tr>
      <tr>
        <td> </td>
      </tr>
       <tr>
        <td background="../images/common/bg_title.gif" height="5"></td>
      </tr>
    </table>

    <br>

    <!-- Search Begin------------------------------------------------>
    <table border=0  width=100% cellspacing="0" cellpadding="0">
    <form name="fmFind" method="get" action="<?=SELF?>">
    <input type="hidden" name='assort' value="<?=$assort?>">
    <input type="hidden" name='assort2' value="<?=$assort2?>">

    <tr height=22>
        <td>
            <font color="#666666">* <?=($status)?> 자료수: <?=nf($row_search)?>개 { <?=nf($total_page)?> page /  <?=nf($page)?> page }</font>
        </td>
        <td valign='bottom' align=right>
            <?if($findMode):?>
            <input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
            <?endif;?>


            <?if(!strstr($_SESSION["sessLogin"]["staff_type"],"partner")){?>
            <select name="CID_F" class='select'>
                <?=option_str("파트너".$CIDS,$CIDS2,$CID_F)?>
            </select>
            <?}?>

            <select name="target" class='select'>
                <?=option_str($selectTxt,$selectValue,$target)?>
            </select>

            <span class="top"><input class="box" type="text" name="keyword" maxlength="40" value='<?=($keyword=="Iw==")? "#":$keyword;?>'></span>
            <span class="btn_pack medium"><a href="#" onClick="document.fmFind.submit()"> 검색 </a></span>
        </td>
    <tr>
    </form>
    </table>
    <!-- Search End------------------------------------------------>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
       <form name="fmData" method="post" action="<?=SELF?>">
       <input type="hidden" name="mode" value="drop">

        <tr><td colspan="12"  bgcolor='#5E90AE' height=2></td></tr>
        <tr align=center height="30" bgcolor="#F7F7F6">
            <td><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
            <td class="subject">번호</td>
            <td class="subject">구분</td>
            <td class="subject">성명</td>
            <td class="subject">단체명</td>
            <td class="subject">연락처</td>
            <td class="subject">출발일</td>
            <td class="subject">희망지역</td>
            <td class="subject">희망국가</td>
            <td class="subject">처리자</td>
            <td class="subject">상태</td>
            <td class="subject">문의날짜</td>
          </tr>
        <tr><td colspan="12"  bgcolor='#E1E1E1'></td></tr>
        <tr><td colspan="12"  bgcolor='#E1E1E1'></td></tr>
<?
if($debug) checkVar(mysql_error(),$sql2);
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql2);
while($rs=$dbo->next_record()){
?>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" class="lh">
          <td><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
          <td align="center"><?=$num?></td>
          <td align="center"><?=$rs[assort]?></td>
          <td align="center"><a class="soft" href="view_<?=$filecode?>.php?id_no=<?=$rs[id_no]?>&<?=$basicLink?>" onFocus="blur(this)" title="상세정보보기"><?=$rs[name]?></a></td>
          <td align="center"><?=$rs[org]?></td>
          <td align="center"><?=$rs[phone]?></td>
          <td align="center"><?=$rs[date_s]?>~<?=$rs[date_e]?></td>
          <td align="center"><?=$rs[nation]?></td>
          <td align="center"><?=$rs[area]?></td>
          <td align="center"><?=$rs[staff]?></td>
          <td align="center"><?=$rs[status]?></td>
          <td align="center"><?=$rs[reg_date]?></td>

        </tr>
        <tr><td colspan="12" class='bar'></td></tr>
<?
    $num--;
}
?>
        <tr><td colspan="12" height=1><?=$error[noData]?></td></tr>
        <tr><td colspan="12"  bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan="12"  bgcolor='#FFFFFF' height=10></td></tr>
        <tr>
          <td colspan="12">

            <!-- Button Begin---------------------------------------------->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
                <tr>
                    <td></td>
                    <td align="right"><!-- <span class="btn_pack medium bold"><a href="#" onClick="location.href='view_<?=$filecode?>.php'"> 등록 </a></span>&nbsp; -->
                    <span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span></td>
                </tr>
            </table>
            <!-- Button End------------------------------------------------>

          </td>
        </tr>

        <tr>
          <td colspan="12"  align=center>
            <!-- navigation Begin---------------------------------------------->
            <?include_once('../../include/navigation.php')?>
            <?=$navi?>
            <!-- navigation End------------------------------------------------>
          </td>
        </tr>
    </form>
    </table>



<!-- Copyright -->
<?include_once("../bottom.html");?>
