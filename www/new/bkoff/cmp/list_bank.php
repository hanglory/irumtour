<?
include_once("../include/common_file.php");


//chk_power($_SESSION["sessLogin"]["proof"],"기본설정");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_bank";
$MENU = "cmp_paper";
$TITLE = "입금내역";
$LEFT_HIDDEN = "1";
//$filter = " and cp_id='$cp_id'";



if($_SESSION['sessLogin']['cp_id']){
    $sql = "
            select 
                *
            from cmp_cp
            where id='$cp_id'
        ";
    $dbo->query($sql);
    $rs=$dbo->next_record();

    $cp_bank = get_bank_code($rs[bank]);
    $cp_account = rnf($rs[bank_account]);
    // checkVar(mysql_error(),$sql);
    // checkVar("bank",$cp_bank);
    // checkVar("bank_account",$cp_account);
    // checkVar("bank_owner",$rs[bank_owner]);
}

####API
$date_s = ($date_s)? $date_s : date("Y/m/d");
$date_e = ($date_e)? $date_e : $date_s;
include("../../api/bank/savedb.php");



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

    if($target=="accIn") $keyword=rnf($keyword);

    $filter .=" and $target like '%$keyword%' ";
    $best="";    //배너 select 초기화
    $findMode=1;
}
if($date_s){
    $filter .=" and trdate>='".rnf($date_s)."'";
}
if($date_e){
    $filter .=" and trdate<='".rnf($date_e)."'";
}
if($bit){
    $filter .=($bit=="T")? " and bit=1" : " and bit=0";
}

$filter .=" and account_no='$accno'";
if($cp_id && $CP_ID) $filter .=" and cp_id='$cp_id'";//독립형 파트너인 경우


#query
$sql_1 = "select $column from $table where id_no>0 $filter";            //자료수
$sql_2 = $sql_1 . " order by id_no desc ";
//checkVar("",$sql_2);

####자료갯수
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
$selectTxt = "입금자,영업점,비고,입금액";
$selectValue ="remark1,remark2,remark3,accIn";


#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&date_s=$date_s&date_e=$date_e";
$sessLink = $link;
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

//-->
</script>



    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?>
        <?=($bank_error && $bank_error!=" ")? "($bank_error)" : ""?>
        <span style="color:gray;font-size:8pt"><?=nf($cnt_save)?>개 업데이트</span>
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
    <td><font color="#666666">* <?=($status)?> 자료수: <?=number_format($row_search)?>개 <?if(!$seq_mode){?>{ <?=number_format($total_page)?> page /  <?=number_format($page)?> page }<?}?></font></td>
    <td valign='bottom' align=right>
    <?if($keyword || $find_bit):?>
    <input class=button type="button" value="전체목록" onclick="location.href='<?=SELF?>'">
    <?endif;?>


    <input type="text" name="date_s" value="<?=$date_s?>" size="11" maxlength="10" class="box dateinput c"> ~
    <input type="text" name="date_e" value="<?=$date_e?>" size="11" maxlength="10" class="box dateinput c">

    <select name="bit" class='select'>
    <?=option_str("처리여부,처리,미처리",",T,F",$bit)?>
    </select>
    
    <select name="target" class='select'>
    <?=option_str($selectTxt,$selectValue,$target)?>
    </select>

    <input class=box type="text" name="keyword" size="15" maxlength="40" value='<?=$keyword?>'>
    <input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
    <input class=button type="submit" name="Submit" value=" API재호출 " onFocus='blur(this)' style="width:80px">
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
            <th class="subject">거래일자</th>
            <th class="subject">거래일시</th>
            <th class="subject">입금액</th>
            <th class="subject">출금액</th>
            <th class="subject">입금자</th>
            <th class="subject">영업점</th>
            <th class="subject">비고</th>
            <th class="subject">처리</th>
        </tr>

<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
if($debug) checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){


?>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td height="30"><?=$rs[trdate]?></td>
          <td><?=$rs[trdt]?></td>
          <td class="r" style="padding-right:5px"><?=nf($rs[accIn])?></td>
          <td class="r" style="padding-right:5px"><?=nf($rs[accOut])?></td>
          <td><?=$rs[remark1]?></td>
          <td><?=$rs[remark2]?></td>
          <td><?=$rs[remark3]?></td>
          <!-- <td><?=$rs[remark4]?></td> -->
          <td><?=($rs[bit])? "<span class='green'>처리</span>":"<span class='gray'>미처리</span>"?></td>
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
                <!-- <span class="btn_pack medium bold"><a href="#" onClick="reload()"> 다시불러오기 </a></span> -->
              </td>
            </tr>
          </table>
          <!-- Button End------------------------------------------------>

          </td>
        </tr>


    </form>
    </table>


    <!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
