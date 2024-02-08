<?
include_once("../include/common_file.php");


chk_power($_SESSION["sessLogin"]["proof"],"기본설정");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_customer";
$MENU = "cmp_paper";
$TITLE = "고객정보(APIS)";
if(strstr("@14.37.242.84@221.154.216.133@","@".$_SERVER["REMOTE_ADDR"]."@")){$debug=1;}


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

    $keyword = trim($keyword);

    if($target=="a.rn"){
        $aes = new AES($keyword, $inputKey, $blockSize);
        $enc = $aes->encrypt();
        $rn = $enc;
        $filter .=" and a.rn = '$rn'";
    }elseif($target=="a.passport_no"){
        $aes = new AES($keyword, $inputKey, $blockSize);
        $enc = $aes->encrypt();
        $passport_no = $enc;
        $filter .=" and a.passport_no = '$passport_no'";
    }else{
        $filter .=" and $target like '%$keyword%' ";
    }
    $best="";    //배너 select 초기화
    $findMode=1;
}

//$filter .= " group by phone,skypass"; // 20200827 추가 list_customer_excel.php, pop_customer.php에도 추가

#query
$sql_1 = "
      select 
          a.*,
          b.id_no as id_no_reserv,
          b.name as leader,
          concat(a.passport_no,a.phone,a.rn) as chk
      from cmp_people as a left join cmp_reservation as b
      on a.code=b.code
      where 
          a.name<>''
          and concat(a.passport_no,a.phone,a.rn)<>''
          and a.bit_hide_customer=0
          and a.bit=1
          and b.cp_id='$CP_ID'
          $filter
      group by chk
";  
//group by a.rn,a.passport_no,a.phone,a.skypass    


$sql_2 = $sql_1 . " order by a.name asc limit  $start, $view_row";
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
$selectTxt = "이름,대표자,성별,주민번호,영문이름,연락처,여권번호,스카이패스,담당자";
$selectValue ="a.name,b.name,a.sex,a.rn,a.name_eng,a.phone,a.passport_no,skypass,a.staff";


#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1";
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

function pop_passport(code,passport_no,name,j){
    var url ="set_passport.php?";
    url += "code=" + code;
    url += "&j=" + j;
    url += "&passport_no=" + passport_no;
    url += "&name=" + name;
    url += "&customer_id_no=" + code;

    // if(passport_no=="" || passport_no==" "){
    //  alert("여권번호가 있어야 여권 사진을 등록하실 수 있습니다.");
    // }

    newWin(url,850,600,1,1);
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

        <tr align=center height=25 bgcolor="#F7F7F6">
        <th class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></th>
        <th class="subject" >대표자</th>
        <th class="subject" >고객명</th>
        <th class="subject" >성별</th>
        <th class="subject" >영문명</th>
        <th class="subject" >주민번호</th>
        <th class="subject" >여권번호</th>
        <?if($user_id!="tester" && $user_id!="test"){?><th class="subject" >여권</th><?}?>
        <th class="subject" >유효기간</th>
        <th class="subject" >연락처</th>
        <th class="subject" >스카이패스</th>
        </tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
if($debug) checkVar(mysql_error(),$sql_2);
$j=1;
while($rs=$dbo->next_record()){

    if($rs[rn]){
    $aes = new AES($rs[rn], $inputKey, $blockSize);
    $dec=$aes->decrypt();
    $rs[rn] = $dec;
    }
  if(strstr($rs[passport_no],"error_")) $rs[passport_no]="";
    if($rs[passport_no]){
    $rs[passport_no_] = $rs[passport_no];
    $aes = new AES($rs[passport_no], $inputKey, $blockSize);
    $dec=$aes->decrypt();
    $rs[passport_no] = $dec;
    }

    $sql2 = "select * from cmp_passport where ((id_code='$rs[id_code]' and id_code<>'') or (passport_no='$rs[passport_no_]' and passport_no<>'')) and filename1<>'' and cp_id='$CP_ID'";
    list($photo_rows) = $dbo2->query($sql2);
    $rs[passport_no_] = str_replace("+","@",$rs[passport_no_]);
?>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" style="height:30px">
          <td height="35"><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
          <td><?=($rs[leader])?$rs[leader]:"<font color='gray'>미등록</font>"?></td>
          <td><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[id_no_reserv]?>',1200,650,1,1,'','reservation')" style="<?=$css?>"><?=$rs[name]?></a></td>
          <td><?=$rs[sex]?></td>
          <td><?=$rs[name_eng]?></td>
          <td><?=$rs[rn]?></td>
          <td><?=$rs[passport_no]?></td>
        <?if($user_id!="tester" && $user_id!="test"){?>
          <td><span class="btn_pack medium bold"><a href="javascript:pop_passport('<?=$rs[id_no]?>','<?=$rs[passport_no_]?>','<?=$rs[name]?>',<?=$num?>)" id="plink_<?=$num?>" style="color:<?=($photo_rows)?'#ff0066':'gray'?>">여권</a></span></td>
        <?}?>
          <td><?=$rs[passport_limit]?></td>
          <td><?=$rs[phone]?></td>
          <td><?=$rs[skypass]?></td>
        </tr>
<?
    $num--;
    $j++;
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
                <span class="btn_pack medium bold"><a href="list_customer_excel.php?nation=<?=$nation?>&target=<?=$target?>&keyword=<?=$keyword?>"> 엑셀 다운로드 </a></span>&nbsp;
              <?}?>
                
              <!--            
              <?if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){?>
                <span class="btn_pack medium bold"><a href="javascript:pop_passport('','ocr','OCR등록','')" id="plink_<?=$num?>" style="color:<?=($photo_rows)?'#ff0066':'gray'?>"> OCR등록(H) </a></span>&nbsp;
              <?}?> 
              -->

                <!-- <span class="btn_pack medium bold"><a href="javascript:newWin('view_<?=$filecode?>.php?ctg1=<?=$ctg1?>',870,400,1,1,'golf')"> 등록 </a></span>&nbsp; -->

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
