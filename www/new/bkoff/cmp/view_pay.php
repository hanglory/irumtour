<?
include_once("../include/common_file.php");


//21.07.07 최경아대표님 요청에 따라 모든 사람에게 공개
//chk_power($_SESSION["sessLogin"]["proof"],"기본설정");



####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_card";
$MENU = "cmp_basic";
$TITLE = "신용카드 결제";
$LEFT_HIDDEN="1";



#### mode
if($mode=="save"){

    $reg_date = date('Y/m/d');
    $reg_date2 = date('H:i:s');
    $price=rnf($price);
    if(!$id_no) $oid = getUniqNo();

    //$cp_id = ($_SESSION["sessLogin"]["cp_pg"])? $_SESSION["sessLogin"]["cp_id"]:"";//개별 결제 기능 이용시 

    $sqlInsert="
       insert into cmp_card (
          cp_id,
          oid,
          subject,
          name,
          cell,
          email,
          price,
          pg_tid,
          pg_info,
          pay_time,
          reg_date,
          reg_date2
      ) values (
          '$CP_ID',
          '$oid',
          '$subject',
          '$name',
          '$cell',
          '$email',
          '$price',
          '$pg_tid',
          '$pg_info',
          '$pay_time',
          '$reg_date',
          '$reg_date2'
    )";


    if($id_no){
        $sql = $sqlModify;
        $url = "view_${filecode}.php?id_no=$id_no";
    }else{
        $sql = $sqlInsert;
        $url = "list_${filecode}.php";
    }

    //checkVar("",$sql);exit;

    if($dbo->query($sql)){
        //msggo("저장하였습니다.",$url);
        echo "
            <script>
                alert('저장하였습니다.');
                opener.location.reload();
                self.close();
            </script>
        ";
    }else{
        checkVar(mysql_error(),$sql);
    }
    exit;

}elseif ($mode=="drop"){

    for($i = 0; $i < count($check);$i++){

        $sql = "delete from $table where id_no = $check[$i]";
        $dbo->query($sql);
    }
    redirect2("list_${filecode}.php");exit;


}else{
    $sql = "select * from $table where id_no=$id_no";
    $dbo->query($sql);
    $rs= $dbo->next_record();
    //$rs[price] = nf($rs[price]);
}
//-------------------------------------------------------------------------------
?>
<?include("../top_min.html");?>
<script language="JavaScript">
<!--
function chkForm(){

    var fm  =document.fmData;

    if(check_blank(fm.subject,'상품명을',0)=='wrong'){return }
    if(check_blank(fm.name,'이름을',0)=='wrong'){return }
    if(check_blank(fm.cell,'핸드폰번호를',13)=='wrong'){return }
    if(check_blank(fm.price,'금액을',0)=='wrong'){return }

    fm.submit();

}
//-->
</script>
<style type="text/css">
body{padding:0 10px;}
</style>

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
        <input type="hidden" name="oid" value='<?=$rs[oid]?>'>


        <tr><td colspan="2"  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">상품명</td>
          <td colspan="3">
               <?=html_input("subject",40,45)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">이름</td>
          <td colspan="3">
               <?=html_input("name",30,50)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="25%">휴대폰</td>
          <td>
            <?=html_input("cell",15,13,'box cell')?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td class="subject">이메일</td>
          <td>
            <?=html_input("email",40,45)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td class="subject">금액</td>
          <td>
            <?=html_input("price",10,8)?> 원
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <?if($rs[id_no]){?>
        <tr><td colspan="2">&nbsp;</td></tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">등록일</td>
          <td>
            <?=$rs[reg_date]?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <?}?>

        <tr><td colspan="2" bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan="2" bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
        <td colspan="2">
          <!-- Button Begin---------------------------------------------->
          <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
            <tr align="right">

                    <td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>

                    <td><span class="btn_pack medium bold"><a href="#" onClick="opener.location.reload();self.close()"> 창닫기 </a></span></td>
            </tr>
          </table>
          <!-- Button End------------------------------------------------>
        </td>
        </tr>
      </table>

    </form>

    <!--내용이 들어가는 곳 끝-->


<!-- Copyright -->
<?include_once("../bottom_min.html");?>

