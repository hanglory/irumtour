<?
include_once("../include/common_file.php");


chk_power($_SESSION["sessLogin"]["proof"],"기본설정");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_partner";
$MENU = "cmp_basic";
$TITLE = "거래처 정보";
$file_rows=2;

if($mode=="save"){


    $path="../../public/cmp";   //업로드할 파일의 경로
    $maxsize=$maxFileSize *(1024*1024) ;            //2MB   업로드 가능한 최대 사이즈 제한

    for($i=1; $i <= $file_rows; $i++){

        $fn = "file" . $i;

        if($_FILES[$fn]["size"]){
            #------------------------------------------
            $fname=$_FILES[$fn]["tmp_name"]; //파일이름을 담고 있는 변수 이름
            $fname_name=$_FILES[$fn]["name"];   //파일의 이름
            $fname_size=$_FILES[$fn]["size"];       //파일의 사이즈
            $fname_type=$_FILES[$fn]["type"];       //파일의 type
            $filename=time() . "_" . $i;        //파일이름 작명
            $type = "normal"; // 일반파일 normal, 이미지만 image
            #------------------------------------------
            include("../../include/file_upload.php");
            $upfiles[$i] = $upfile;
            $upfile_real[$i] = $_FILES[$fn]["name"];
            $upfileQuery[$i] = ($upfile)? "filename${i} = '". $upfiles[$i] ."',filename${i}_real='".$_FILES[$fn]["name"]."', ":"" ;
        }
    }
}

#### mode
if($mode=="save"){

    $company = trim($company);

    if(!$id_no || ($id_no && $company!=$company_old)){
        $company2 = str_replace(" ","",$company);
        $sql = "select * from cmp_partner where trim(replace(company,' ',''))='$company2' ";
        list($rows) = $dbo->query($sql);
        if($rows){
            error("이미 등록된 거래처 입니다. 거래처 명을 확인해 주세요.");
            exit;
        }
    }

    $names="";
    $names_all="";
    if($name1){$names.=",".str_replace(",","",$name1); $names_all.=",".str_replace(",","",$name1)."{@}".$cell1 . "{@}". $nateon1 ;}
    if($name2){$names.=",".str_replace(",","",$name2); $names_all.=",".str_replace(",","",$name2)."{@}".$cell2 . "{@}". $nateon2 ;}
    if($name3){$names.=",".str_replace(",","",$name3); $names_all.=",".str_replace(",","",$name3)."{@}".$cell3 . "{@}". $nateon3 ;}
    if($name4){$names.=",".str_replace(",","",$name4); $names_all.=",".str_replace(",","",$name4)."{@}".$cell4 . "{@}". $nateon4 ;}
    if($name5){$names.=",".str_replace(",","",$name5); $names_all.=",".str_replace(",","",$name5)."{@}".$cell5 . "{@}". $nateon5 ;}
    $name = substr($names,1);
    $name_all = substr($names_all,1);

    $reg_date = date('Y/m/d H:i:s');

     $sqlInsert="
        insert into cmp_partner (
            cp_id,
            nation,
            company,
            biz_no,
            name,
            name_all,
            phone,
            ceo,
            biz_phone,
            staff_name,
            local_staff,
            em_phone,
            bank,
            owner,
            account,           
           filename1,
           filename2,            
            reg_date
       ) values (
            '$CP_ID',
            '$nation',
            '$company',
            '$biz_no',
            '$name',
            '$name_all',
            '$phone',
            '$ceo',
            '$biz_phone',
            '$staff_name',
            '$local_staff',
            '$em_phone',
            '$bank',           
            '$owner',          
            '$account',
           '$upfiles[1]',
           '$upfiles[2]',                    
            '$reg_date'
     )";


     $sqlModify="
        update cmp_partner set
           $upfileQuery[1]
           $upfileQuery[2]        
            nation = '$nation',
            company = '$company',
            biz_no = '$biz_no',
            name = '$name',
            name_all = '$name_all',
            phone = '$phone',
            ceo='$ceo',
            biz_phone='$biz_phone',
            staff_name='$staff_name',
            local_staff='$local_staff',
            em_phone='$em_phone',
            bank='$bank',
            owner='$owner',
            account='$account'         
        where 
            id_no='$id_no'
            and cp_id='$CP_ID'
        limit 1
     ";


    if($id_no){
        $sql = $sqlModify;
        $url = "view_${filecode}.php?id_no=$id_no";
    }else{
        $sql = $sqlInsert;
        $url = "list_${filecode}.php";
    }

    //checkVar("",$sql);exit;

    if($dbo->query($sql)){
        echo "<script>alert('저장하였습니다.');parent.location.href='$url';</script>";
        //msggo("저장하였습니다.",$url);
    }else{
        checkVar(mysql_error(),$sql);
    }
    exit;

}elseif ($mode=="drop"){

    for($i = 0; $i < count($check);$i++){

        $sql = "select *  from $table where id_no=$check[$i] and cp_id='$CP_ID'";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        if($rs[filename1]) @unlink("../../public/cmp/$rs[filename1]");
        if($rs[filename2]) @unlink("../../public/cmp/$rs[filename2]");

        $sql = "delete from $table where id_no = $check[$i] and cp_id='$CP_ID'";
        $dbo->query($sql);
    }
    redirect2("list_${filecode}.php");exit;

}elseif ($mode=="file_drop"){

    $sql = "update $table set $mode2 ='' where id_no=$id_no and cp_id='$CP_ID'";
    $dbo->query($sql);
    @unlink("../../public/cmp/$filename");
    redirect2("?id_no=$id_no&$_SESSION[link]");
    exit;

}else{
    $sql = "select * from $table where id_no=$id_no and cp_id='$CP_ID'";
    $dbo->query($sql);
    $rs= $dbo->next_record();

    $arr = explode(",",$rs[name_all]);
    $cols = array("name","cell","nateon");
    foreach ($arr as $key => $value) {
        $arr2 = explode("{@}",$value);
        $no = $key+1;
        foreach ($arr2 as $key2 => $value2) {
            $rs[$cols[$key2].$no] = $value2;
        }        
    }

}
//-------------------------------------------------------------------------------
?>
<?include("../top.html");?>
<script language="JavaScript">
<!--
function chkForm(){

    var fm  =document.fmData;

    if(check_select(fm.nation,'국가명을')=='wrong'){return }
    if(check_blank(fm.company,'거래처명을',0)=='wrong'){return }

    fm.submit();
}


//파일수정
function fedit(id,bit){
    if(bit==1){
        $(".fsave"+id).hide();
        $(".fdrop"+id).show();
    }else{
        $(".fsave"+id).show();
        $(".fdrop"+id).hide();
    }
}

/*이미지 미리보기*/
function show_file(sfile){
    /*
    $("#preview_photo").show();
    $('#preview_photo').load('photo.php', {
      'sfile': sfile
    });
    //location.href="photo.php?sfile="+sfile;
    */
}

$(function(){
     $("#biz_no").mask("999-99-99999");
     $("#cell1,#cell2,#cell3,#cell4,#cell5").mask("010-9999-9999");
     $("#name1,#name2,#name3,#name4,#name5").attr("placeholder","이름");
     $("#cell1,#cell2,#cell3,#cell4,#cell5").attr("placeholder","핸드폰번호");
     $("#nateon1,#nateon2,#nateon3,#nateon4,#nateon5").attr("placeholder","네이트온");
});
//-->
</script>

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


      <table border="0" cellspacing="1" cellpadding="3" width="750">

        <form name="fmData" method="post" enctype="multipart/form-data" target="actarea">
        <input type="hidden" name="mode" value="save">
        <input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
        <input type="hidden" name="company_old" value='<?=$rs[company]?>'>


        <tr><td colspan="4"  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">국가</td>
          <td colspan="3">
               <select name="nation" id="nation">
               <option value="">선택</option>
               <?=option_str($NATIONS,$NATIONS,$rs[nation])?>
               </select>
          </td>

        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="15%">거리처명</td>
          <td>
            <?=html_input("company",30,50)?>
          </td>

          <td class="subject" width="15%">연락처</td>
          <td>
            <?=html_input("phone",30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>        
        <tr>
          <td class="subject">담당자</td>
          <td colspan="3">
            <div class="staff">
                <p style='margin-bottom:2px'><?=html_input("name1",20,50)?> <?=html_input("cell1",20,50)?> <?=html_input("nateon1",39,50)?></p>
                <p style='margin-bottom:2px'><?=html_input("name2",20,50)?> <?=html_input("cell2",20,50)?> <?=html_input("nateon2",39,50)?></p>
                <p style='margin-bottom:2px'><?=html_input("name3",20,50)?> <?=html_input("cell3",20,50)?> <?=html_input("nateon3",39,50)?></p>
                <p style='margin-bottom:2px'><?=html_input("name4",20,50)?> <?=html_input("cell4",20,50)?> <?=html_input("nateon4",39,50)?></p>
                <p style='margin-bottom:2px'><?=html_input("name5",20,50)?> <?=html_input("cell5",20,50)?> <?=html_input("nateon5",39,50)?></p>
            </div>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">사업자등록번호</td>
          <td colspan="3">
            <?=html_input("biz_no",15,12)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <tr>
          <td class="subject">대표자</td>
          <td>
            <?=html_input("ceo",30,50)?>
          </td>

          <td class="subject">대표번호</td>
          <td>
            <?=html_input("biz_phone",30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">현지담당자</td>
          <td colspan="3">
            <?=html_input("local_staff",30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">비상연락처</td>
          <td colspan="3">
            <?=html_input("em_phone",30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">계좌번호</td>
          <td colspan="3">
            은행 <?=html_input("bank",10,50)?>
            계좌 <?=html_input("account",30,50)?>
            예금주 <?=html_input("owner",20,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

    <?
        for($j=1; $j <= $file_rows;$j++){
        $FILENAME= "filename" . $j;
        $FILENAME_REAL= "filename".$j."_real";
        $FILE_NO=$j;
        ?>
        <tr>
          <td  class="subject"><?=($j==1)?"사업자등록증":"통장사본"?><r/td>
          <td colspan="3">
            <?
            if($rs[$FILENAME]):
            @$fileSize = filesize("../../public/cmp/${rs[$FILENAME]}");
            ?>

            <span class="hide fsave<?=$FILE_NO?>"><input class=box type="file" name="file<?=$FILE_NO?>" size="40"></span>
            <span class="btn_pack small bold fdrop<?=$FILE_NO?>"><a href="javascript:if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=<?=$FILENAME?>&filename=<?=$rs[$FILENAME]?>'}"> 파일삭제 </a></span>

            &nbsp;&nbsp;
            <span class="btn_pack small bold fdrop<?=$FILE_NO?>"><a href="javascript:fedit('<?=$FILE_NO?>',0)"> 파일수정 </a></span>    &nbsp;
            <span class="btn_pack small bold fsave<?=$FILE_NO?> hide"><a href="javascript:fedit('<?=$FILE_NO?>',1)"> 수정취소 </a></span>

            &nbsp;&nbsp;

            <span class="fdrop<?=$FILE_NO?>">
            <a href="../../include/download.php?file=<?=$rs[$FILENAME]?>&orgin_file_name=<?=$rs[$FILENAME_REAL]?>&dir=public/cmp&ctg1=<?=$ctg1?>" onmouseover="show_file('../../public/cmp/<?=$rs[$FILENAME]?>')" onmouseout="hide_file()"><?=$rs[$FILENAME_REAL]?> (<?=ceil($fileSize/1024)?>KB)</a>
            </span>

            <?else:?>
            <input class=box type="file" name="file<?=$FILE_NO?>" size="40">
            <font color="orange">Alert</font></b> <font color="#666666">: Max <?=$maxFileSize?>MB</font>
            <?endif;?>

            <?If($j==1){?>
            <div style="position:absolute;left:500px;border:3px solid #ccc;display:none" id="preview_photo"></div>
            <?}?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <?}?>


        <?if($rs[id_no]){?>
        <tr><td colspan="4">&nbsp;</td></tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">등록일</td>
          <td colspan="3">
            <?=$rs[reg_date]?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <?}?>

        <tr><td colspan="4" bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan="4" bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
        <td colspan="4">
          <!-- Button Begin---------------------------------------------->
          <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
            <tr align="right">
                <td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
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

