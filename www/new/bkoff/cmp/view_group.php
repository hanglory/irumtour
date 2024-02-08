<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_request";
$MENU = "cmp_basic";
$TITLE = "골프투어문의";
$filter = "";
$column = "*";
$basicLink = "";
$LEFT_HIDDEN="1";


$FILTER_PARTNER_QUERY = str_replace("cp_id","CID",$FILTER_PARTNER_QUERY);


#### mode
if($mode=="save"){

    $sql="
       update ez_request set
          staff = '$staff',
          memo = '$memo',
          status = '$status'
       where 
            id_no='$id_no'
            $FILTER_PARTNER_QUERY
    ";

    $url = "view_${filecode}.php?id_no=$id_no";

    //checkVar("",$sql);exit;

    if($dbo->query($sql)){
        msggo("저장하였습니다.",$url);
    }else{
        checkVar(mysql_error(),$sql);
    }
    exit;

}elseif ($mode=="drop"){

    for($i = 0; $i < count($check);$i++){

        $sql = "delete from $table where id_no = $check[$i] $FILTER_PARTNER_QUERY";
        $dbo->query($sql);
    }
    redirect2("list_${filecode}.php");exit;



}elseif ($mode=="copy"){

    $code = ($code)? $code : getUniqNo();
    $staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

    $reg_date = date('Y/m/d');
    $reg_date2 = date('H:i:s');

    $sql = "select * from ez_request where id_no=$id_no $FILTER_PARTNER_QUERY";
    $dbo->query($sql);
    $rs=$dbo->next_record();
    if($rs[id_no]){

        $memo = "문의날자 : $rs[reg_date] \n";
        $memo .= "출발일 : $rs[date_s] ~ $rs[date_e] \n";
        $memo .= "희망국가/지역 : $rs[nation] / $rs[area] \n";
        $memo .= "소속/단체명 : $rs[org] \n";
        //$memo .= "투어성격 : $rs[assort] $rs[assort_memo1] $rs[assort_memo2] \n";
        $memo .= "골프장종류 : $rs[etc1] \n";
        $memo .= "호텔/골프증수준 : $rs[etc2] \n";
        $memo .= "식사수준 : $rs[etc3] \n";
        $memo .= "객실수준 : $rs[etc4] \n";
        $memo .= "실글룸사용여부 : $rs[etc5] \n";
        $memo .= "기타요청사항 : $rs[content] \n";

        $memo =addslashes($memo);

        $view_path = "투어문의";

        $sql="
            insert into cmp_estimate (
               cp_id,
               view_path,
               staff,
               code,
               people,
               name,
               phone,
               email,
               memo,
               send_date,
               reg_date,
               reg_date2
           ) values (
               '$CP_ID',
               '$view_path',
               '$staff',
               '$code',
               '$rs[people]',
               '$rs[name]',
               '$rs[phone]',
               '$rs[email]',
               '$memo',
               '$reg_date',
               '$reg_date',
               '$reg_date2'
         )";
         if($dbo->query($sql)){
            error("데이터를 견적서로 복사했습니다.");
         }else{
            checkVar(mysql_error(),$sql);exit;
         }

    }

    back();exit;


}elseif ($mode=="file_drop"){
        $sql = "update $table set $mode2 ='' where id_no=$id_no $FILTER_PARTNER_QUERY";
        $dbo->query($sql);
        redirect2("?id_no=$id_no&$_SESSION[link]");exit;

}else{
    $sql = "select * from $table where id_no=$id_no $FILTER_PARTNER_QUERY";
    $dbo->query($sql);
    $rs= $dbo->next_record();
    if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}
}
//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function chkForm(){

    var fm  =document.fmData;

    fm.submit();
}

function data_copy(){
    if(confirm('견적서로 데이터를 보내시겠습니까?')){
        location.href="<?=SELF?>?mode=copy&id_no=<?=$rs[id_no]?>";
    }
}
//-->
</script>

<?include("../top.html");?>




        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
            </tr>
            <tr>
                <td colspan="3"> </td>
            </tr>
            <tr>
                <td background="../images/common/bg_title.gif" height="5"></td>
            </tr>
        </table>


        <br>


      <table border="0" cellspacing="1" cellpadding="3" width="100%">

        <form name="fmData" method="post" enctype="multipart/form-data">
        <input type="hidden" name="mode" value="save">
        <input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
        <input type="hidden" name="code" value='<?=$code?>'>


        <tr><td colspan="4"  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">문의날짜</td>
          <td colspan="3">
            <?=$rs[reg_date]?>
            <?=$rs[reg_date2]?>
          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">출발일</td>
          <td>
            <?=$rs[date_s]?>    ~ <?=$rs[date_e]?>
          </td>

          <td class="subject" width="20%">예상인원</td>
          <td>
            <?=$rs[people]?>명
          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">희망국가</td>
          <td>
            <?=$rs[nation]?>
          </td>

          <td class="subject" width="20%">희망지역</td>
          <td>
            <?=$rs[area]?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">소속/단체명</td>
          <td>
            <?=$rs[org]?>
          </td>

          <td class="subject" width="20%">성명</td>
          <td>
            <?=$rs[name]?>
          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">연락처</td>
          <td>
            <?=$rs[phone]?>
          </td>

          <td class="subject" width="20%">이메일주소</td>
          <td>
            <a href="mailto:<?=$rs[email]?>"><?=$rs[email]?></a>
          </td>
        </tr>

<!-- 
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">투어성격</td>
          <td colspan="3">
            <?=$rs[assort]?>
            <?If($rs[assort]=="비즈니스(접대)"){?>
            <?=$rs[assort_memo1]?>
            <?}ElseIf($rs[assort]=="단체행사"){?>
            <?=$rs[assort_memo2]?>
            <?}?>
          </td>
        </tr> -->


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">골프장종류</td>
          <td>
            <?=$rs[etc1]?>
          </td>

          <td class="subject" width="20%">호텔/골프장 수준</td>
          <td>
            <?=$rs[etc2]?>
          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">식사수준</td>
          <td>
            <?=$rs[etc3]?>
          </td>

          <td class="subject" width="20%">객실수준</td>
          <td>
            <?=$rs[etc4]?>
          </td>
        </tr>


        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">싱글룸사용여부</td>
          <td colspan="3">
            <?=$rs[etc5]?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">기타요청사항</td>
          <td colspan="3">
            <?=nl2br($rs[content])?>
          </td>
        </tr>

        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">확인처리자</td>
          <td colspan="3">
            <?=html_input('staff',30,30)?>  처리결과 : <select name="status"><?=option_str("접수,처리중,완료","접수,처리중,완료",$rs[status])?></select>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
        <tr>
          <td class="subject" width="20%">관리자메모</td>
          <td colspan="3">
            <?=html_textarea('memo',80,5)?>
          </td>
        </tr>


        <tr><td colspan="4" bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan="4" bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
        <td colspan="4">
          <!-- Button Begin---------------------------------------------->
          <table border="0" width="250" cellspacing="0" cellpadding="0" align="right">
            <tr align="right">
                <td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
                <td><span class="btn_pack medium bold"><a href="javascript:data_copy()"> 견적서 이동 </a></span></td>
                <td><span class="btn_pack medium bold"><a href="#" onClick="location.href='list_<?=$filecode?>.php?<?=$sessLink?>'"> 리스트 </a></span></td>
            </tr>
          </table>
          <!-- Button End------------------------------------------------>
        </td>
        </tr>
      </table>

    </form>

    <!--내용이 들어가는 곳 끝-->


<!-- Copyright -->
<?include_once("../bottom.html");?>

