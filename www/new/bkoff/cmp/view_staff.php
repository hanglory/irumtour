<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"담당자관리");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_staff";
$MENU = "cmp_paper";
$TITLE = "권한관리";
$time = time();


$FILTER_PARTNER_QUERY_CPID = str_replace("a.","",$FILTER_PARTNER_QUERY_CPID);


#### mode
if($mode=="save"){

    if($_FILES["file1"]["size"]){
        #------------------------------------------
        $path="../../public/cmp_files"; //업로드할 파일의 경로
        $maxsize=10 *(1024*1024) ;          //2MB   업로드 가능한 최대 사이즈 제한
        $fname=$_FILES["file1"]["tmp_name"];                        //파일이름을 담고 있는 변수 이름
        $fname_name=$_FILES["file1"]["name"];   //파일의 이름
        $fname_size=$_FILES["file1"]["size"];       //파일의 사이즈
        $fname_type=$_FILES["file1"]["type"];       //파일의 type
        $filename="est_${code}_${time}_1";      //파일이름 작명
        $type = "image"; // 일반파일 normal, 이미지만 image
        #------------------------------------------
        include("../../include/file_upload.php");
        $upfile1 = $upfile;
        $upfile1_real = $_FILES["file1"]["name"];
        $upfileQuery1 = ($upfile)? "filename1 = '$upfile1', ":"" ;
    }
    if($_FILES["file2"]["size"]){
        #------------------------------------------
        $path="../../public/cmp_files"; //업로드할 파일의 경로
        $maxsize=10 *(1024*1024) ;          //2MB   업로드 가능한 최대 사이즈 제한
        $fname=$_FILES["file2"]["tmp_name"];                        //파일이름을 담고 있는 변수 이름
        $fname_name=$_FILES["file2"]["name"];   //파일의 이름
        $fname_size=$_FILES["file2"]["size"];       //파일의 사이즈
        $fname_type=$_FILES["file2"]["type"];       //파일의 type
        $filename="est_${code}_${time}_2";      //파일이름 작명
        $type = "image"; // 일반파일 normal, 이미지만 image
        #------------------------------------------
        include("../../include/file_upload.php");
        $upfile2 = $upfile;
        $upfile2_real = $_FILES["file2"]["name"];
        $upfileQuery2 = ($upfile)? "filename2 = '$upfile2', ":"" ;
    }

    $target_rate=rnf($target_rate);

    $reg_date = date('Y/m/d');
    $reg_date2 = date('H:i:s');

    $pwdQuery = ($pwd)? " pwd= password('$pwd'), ":"";

    $reg_date = date('Y/m/d');
    $reg_date2 = date('H:i:s');

    for($i=0; $i<count($power);$i++){
        $powers .=  ",".$power[$i];
    }
    $power = substr($powers,1);

    for($i=0; $i<count($power_erp);$i++){
        $power_erps .=  ",".$power_erp[$i];
    }
    $power_erp = substr($power_erps,1);

    $partner = ($staff_type=="ceo")? 1 : 0;

    while(list($key,$val)=each($goal)){
        $goals.=",".rnf($val);
    }
    $goal = substr($goals,1);

    while(list($key,$val)=each($goal2)){
        $goal2s.=",".rnf($val);
    }
    $goal2 = substr($goal2s,1);

    $cp_id = $_POST[cp_id];
    if($CP_ID){
        $cp_id = $CP_ID;
        $staff_type = "partner_g";
    }

    // if($cp_id && !strstr("partner_a,partner_g",$staff_type) && !strstr($cp_id,"irumtour")){
    //     checkVar("cp_id",$cp_id);
    //     checkVar("staff_type",$staff_type);
    //     error("이룸투어가 아닌 파트너의 경우 파트너 타입이 파트너A 또는 파트너G여야 합니다.확인해 주세요. ");
    //     exit;
    // }


    $sqlInsert="
       insert into $table (
          id,
          pwd,
          name,
          color,
          partner,
          cp_id,
          mposition,
          phone1,
          phone2,
          phone3,
          cell1,
          cell2,
          cell3,
          email,
          bit_login,
          power,
          power_erp,
          filename1,
          filename2,
          staff_type,
          goal,
          goal2,
          target_rate,
          kakao_link,
          paper_color,
          bank_account,     
          company,
          cp_url,     
          reg_date,
          reg_date2
      ) values (
          '$id',
          password('$pwd'),
          '$name',
          '$color',
          '$partner',
          '$cp_id',
          '$mposition',
          '$phone1',
          '$phone2',
          '$phone3',
          '$cell1',
          '$cell2',
          '$cell3',
          '$email',
          '$bit_login',
          '$power',
          '$power_erp',
          '$upfile1',
          '$upfile2',
          '$staff_type',
          '$goal',
          '$goal2',
          '$target_rate',
          '$kakao_link',
          '$paper_color',
          '$bank_account',            
          '$company',             
          '$cp_url',              
          '$reg_date',
          '$reg_date2'
    )";

    $query_cp="";
    if(!$CP_ID){
        $query_cp.="cp_id='$cp_id',";
        $query_cp.="staff_type='$staff_type',";
    }


    $sqlModify="
       update $table set
          $query_cp  
          $pwdQuery
          $upfileQuery1
          $upfileQuery2
          company = '$company',
          cp_url = '$cp_url',
          name = '$name',
          color = '$color',
          partner = '$partner',
          mposition = '$mposition',
          phone1 = '$phone1',
          phone2 = '$phone2',
          phone3 = '$phone3',
          cell1 = '$cell1',
          cell2 = '$cell2',
          cell3 = '$cell3',
          bit_login = '$bit_login',
          goal = '$goal',
          goal2 = '$goal2',
          power = '$power',
          power_erp = '$power_erp',
          target_rate = '$target_rate',
          kakao_link = '$kakao_link',
          paper_color='$paper_color',
          bank_account='$bank_account',
          email = '$email'
       where 
            id_no='$id_no'
            $FILTER_PARTNER_QUERY
       limit 1
    ";


    if($id_no){
        $sql = $sqlModify;
        $url = "view_${filecode}.php?id_no=$id_no";
    }else{
        $sql = $sqlInsert;
        $url = "list_${filecode}.php";
    }

    if($dbo->query($sql)){
        msggo("저장하였습니다.",$url,1);
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

}elseif ($mode=="chkid"){
        $id= trim($id);
        if($id){
            $sql = "select * from $table where id='$id' ";
            list($rows) = $dbo->query($sql);
            if($rows){
                echo "<script>alert('이미 같은 아이디가 존재합니다.');parent.document.fmData.id.value='';parent.document.fmData.id.focus();parent.document.fmData.id_chk.value=0;</script>";
            }else{
                echo "<script>parent.document.fmData.id_chk.value=1;</script>";
            }

        }else{
            echo "<script>parent.document.fmData.id.value='';parent.document.fmData.id_chk.value=0;</script>";
        }
        exit;

}elseif($mode=="file_drop"){

        $sql = "select filename${no} as f from $table where id_no=$id_no $FILTER_PARTNER_QUERY";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        //checkVar(mysql_error(),$sql);

        if($rs[f]){
            @unlink("../../public/cmp_files/${rs[file]}");
            //checkVar("","../../public/cmp_files/${rs[file]}");
        }
        $sql = "update $table set filename${no}='' where id_no=$id_no";
        $dbo->query($sql);
        //checkVar(mysql_error(),$sql);exit;
        back();exit;


}elseif($mode=="bit_hide"){

        $bit_login=($bit_hide)?0:1;
        for($i = 0; $i < count($check);$i++){
            $sql = "update $table set bit_hide=$bit_hide, bit_login=$bit_login where id_no = $check[$i] $FILTER_PARTNER_QUERY";
            $dbo->query($sql);
        }
        back();exit;

}else{

    $cp_key="";
    $cp_val="";
    $sql = "select * from cmp_cp where bit_hide<>1 order by company asc";
    if($CP_ID){
        $sql = "select * from cmp_cp where id='$CP_ID' order by company asc";
    }
    $dbo->query($sql);
    //if($debug) checkVar(mysql_error(),$sql);
    while($rs=$dbo->next_record()){
        $cp_key.=",".$rs[id];
        $cp_val.=",$rs[company] ($rs[id] $rs[biz_no])";
    }

    $sql = "select * from $table where id_no=$id_no $FILTER_PARTNER_QUERY";
    $dbo->query($sql);
    $rs= $dbo->next_record();
    //if($debug) checkVar(mysql_error(),$sql);

    $rs[target_rate] = nf($rs[target_rate]);
    $rs[target_rate] = ($rs[target_rate])? $rs[target_rate] : 50;

}
//-------------------------------------------------------------------------------
?>
<?include("../top.html");?>
<script src='../../bgrins/spectrum.js'></script>
<link rel='stylesheet' href='../../bgrins/spectrum.css' />
<script language="JavaScript">
<!--
function chkForm(){

    var fm  =document.fmData;

<?if(!$rs[id]){?>
    if(check_blank(fm.id,'아이디를',4)=='wrong'){return }
    if(check_strnum(fm.id,'아이디')=='wrong'){return }
    if(fm.id_chk.value==0){fm.id.focus(); return }
    if(check_blank(fm.pwd,'비밀번호를',4)=='wrong'){return }
    if(check_blank(fm.pwd_check,'확인을 위한 비밀번호를',4)=='wrong'){return }
<?}?>
    if(fm.pwd.value != fm.pwd_check.value){alert('비밀번호가 서로 다릅니다.   ');fm.pwd_check.focus();return }

    if(check_blank(fm.name,'이름을',0)=='wrong'){return }
    if(check_select(fm.staff_type,'직원구분을')=='wrong'){return }

    fm.submit();
}


function chkid(){
    fm = document.fmData;
    var id;
    id = fm.id.value;

    actarea.location.href="?mode=chkid&id="+id;

}


function file_drop(no)
{
    var url="<?=SELF?>?mode=file_drop&code=<?=$code?>&id_no=<?=$id_no?>&assort=<?=$assort?>&no="+no;
    if(confirm('삭제하시겠습니까?')){
        location.href=url;
    }

}

$(function(){

    $("#color,#paper_color").spectrum({
        allowEmpty: true,
        preferredFormat: true,
        showInput: true,
    });

    $("#cp_url").attr("placeholder","http://파트너아이디.irumtour.net");

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


      <table border="0" cellspacing="1" cellpadding="3" width="90%">

        <form name="fmData" method="post" enctype="multipart/form-data" target="actarea">
        <input type="hidden" name="mode" value="save">
        <input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
        <input type="hidden" name="id_chk" value='0'>


        <tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td class="subject" width="140">아이디</td>
          <td>
            <?if(!$rs[id_no]){?>
            <input class="box" type="text" name="id" value="<?=$rs[id]?>" size=20 maxlength="20" onchange="document.fmData.id_chk.value=0"  onfocus="chkid()" onblur="chkid()" onchange="chkid()">
            <font color="#FF6633">아이디를 입력하시면 자동으로 중복체크를 하게 됩니다.</font>
            <?}else{?>
            <b><?=$rs[id]?></b>
            <?}?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">비밀번호</td>
          <td>
            <input class="box" type="password" name="pwd"  size=20 maxlength="20">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td class="subject">비밀번호 확인</td>
          <td>
            <input class="box" type="password" name="pwd_check"  size=20 maxlength="20">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td class="subject">직원명</td>
          <td>
            <input class="box" type="text" name="name" value="<?=$rs[name]?>" size="30" maxlength="30">
            , <strong>직함</strong> <input class="box" type="text" name="mposition" value="<?=$rs[mposition]?>" size=10 maxlength="20">

            <span style="display:none">
                <label><input type="checkbox" value="1" name="partner" id="partner" <?=($rs[partner])?"checked":""?>> 파트너스</label>
            </span>

            <?if(!$CP_ID){?>
            <select name="staff_type">
                <option value="">선택</option>
                <?=option_str("CEO,직원+파트너,직원,파트너I,파트너A,파트너G","ceo,leader_partner,staff,partner_i,partner_a,partner_g",$rs[staff_type])?>
            </select>
            <?}?>

            &nbsp;&nbsp;
            <strong>정산요율</strong>
            <?=html_input("target_rate",4,3,'box c')?>%

          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td class="subject">입사일</td>
          <td>
          <input type="text" name="join_day" id="join_day" size="13" maxlength="10" value="<?=$rs[join_day]?>" class="box c dateinput">
             /  <strong>생일</strong> <input type="text" name="birth_day" id="birth_day" size="13" maxlength="10" value="<?=$rs[birth_day]?>" class="box c dateinput">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        
        <?if(!$CP_ID){?>
        <tr>
          <td class="subject">파트너</td>
          <td>
            <select name="cp_id">
                <?=option_str($cp_val,$cp_key,$rs[cp_id])?>
            </select>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <?}?>

        <tr>
          <td class="subject">연락처</td>
          <td>
            <input class="box" type="text" name="phone1" value="<?=$rs[phone1]?>" size="5" maxlength="4"> -
            <input class="box" type="text" name="phone2" value="<?=$rs[phone2]?>" size="5" maxlength="4"> -
            <input class="box" type="text" name="phone3" value="<?=$rs[phone3]?>" size="5" maxlength="4">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">핸드폰번호</td>
          <td>
            <input class="box" type="text" name="cell1" value="<?=$rs[cell1]?>" size="5" maxlength="4"> -
            <input class="box" type="text" name="cell2" value="<?=$rs[cell2]?>" size="5" maxlength="4"> -
            <input class="box" type="text" name="cell3" value="<?=$rs[cell3]?>" size="5" maxlength="4">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">이메일</td>
          <td>
            <input class="box" type="text" name="email" value="<?=$rs[email]?>" size="30" maxlength="30">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">카카오링크</td>
          <td>
            <input class="box" type="text" name="kakao_link" value="<?=$rs[kakao_link]?>" size="30" maxlength="150">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr> 

        <tr>
          <td class="subject">주소</td>
          <td>
        <div class="addr">
                <?=html_input("zipcode",8,5,'box c')?> <span class="btn_pack medium bold"><a href="javascript:set_zip()"> 우편번호 </a></span><br/>
                <?=html_input("address",60,150)?><br/>
            </div>
            <!--다음 주소-->
            <span id="guide" style="color:#999;display:none"></span>
            <!-- <script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script> -->
            <script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>
            <script>
            function set_zip() {
                new daum.Postcode({
                    oncomplete: function(data) {
                        // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                        // 도로명 주소의 노출 규칙에 따라 주소를 조합한다.
                        // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                        var fullRoadAddr = data.roadAddress; // 도로명 주소 변수
                        var extraRoadAddr = ''; // 도로명 조합형 주소 변수

                        // 법정동명이 있을 경우 추가한다.
                        if(data.bname !== ''){
                            extraRoadAddr += data.bname;
                        }
                        // 건물명이 있을 경우 추가한다.
                        if(data.buildingName !== ''){
                            extraRoadAddr += (extraRoadAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                        }
                        // 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                        if(extraRoadAddr !== ''){
                            extraRoadAddr = ' (' + extraRoadAddr + ')';
                        }
                        // 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 추가한다.
                        if(fullRoadAddr !== ''){
                            fullRoadAddr += extraRoadAddr;
                        }

                        // 우편번호와 주소 정보를 해당 필드에 넣는다.
                        //document.getElementById("zipcode").value = data.postcode1 + "-" + data.postcode2;
                        document.getElementById("zipcode").value = data.zonecode;
                        document.getElementById("address").value = fullRoadAddr;
                        document.getElementById("address_old").value = data.jibunAddress;
                        document.getElementById("address2").focus();

                        // 사용자가 '선택 안함'을 클릭한 경우, 예상 주소라는 표시를 해준다.
                        if(data.autoRoadAddress) {
                            //예상되는 도로명 주소에 조합형 주소를 추가한다.
                            var expRoadAddr = data.autoRoadAddress + extraRoadAddr;
                            document.getElementById("guide").innerHTML = '(예상 도로명 주소 : ' + expRoadAddr + ')';

                        } else if(data.autoJibunAddress) {
                            var expJibunAddr = data.autoJibunAddress;
                            document.getElementById("guide").innerHTML = '(예상 지번 주소 : ' + expJibunAddr + ')';

                        } else {
                            document.getElementById("guide").innerHTML = '';
                        }
                    }
                }).open();
            }
            </script>
            <!--다음 주소-->        
            </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <tr>
          <td class="subject">접속 허용</td>
          <td>
            <?
            if(!$rs[id_no]) $rs[bit_login]=1;
            ?>
            <?=radio("허용,차단","1,0",$rs[bit_login],'bit_login')?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr class="hide">
          <td class="subject">목표(예약일)</td>
          <td>
            <?
            $arr = explode(",",$rs[goal]);
            for($i=0;$i<12;$i++){
            ?>
                <p style="padding:1px"><?=num2($i+1)?>월 <input class="box numberic" type="text" name="goal[]" value="<?=nf($arr[$i])?>" size="12" maxlength="30"></p>
            <?}?>
          </td>
        </tr>
        <tr class="hide"><td colspan="2" class="tblLine"></td></tr>


        <tr class="hide">
          <td class="subject">목표(출국일)</td>
          <td>
            <?
            $arr = explode(",",$rs[goal2]);
            for($i=0;$i<12;$i++){
            ?>
                <p style="padding:1px"><?=num2($i+1)?>월 <input class="box numberic" type="text" name="goal2[]" value="<?=nf($arr[$i])?>" size="12" maxlength="30"></p>
            <?}?>
          </td>
        </tr>
        <tr class="hide"><td colspan="2" class="tblLine"></td></tr>




        <tr>
          <td class="subject">권한</td>
          <td>
            <?
            // if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
            //     checkVar("ERP_POWER",$ERP_POWER);
            //     checkVar("ERP_POWER_2023",$ERP_POWER_2023);
            // }
            $ERP_POWER = $ERP_POWER_2023;


            //권한 관리 제한
            $POWER_EXCEPT="";//제외할 권한
            if($_SESSION['sessLogin']['staff_type']=="leader_partner"){//직원+파트너
                $POWER_EXCEPT="경영관리,엑셀다운로드,핸드폰번호다운로드";
            }
            elseif($_SESSION['sessLogin']['staff_type']=="partner_a"){//파트너A
                $POWER_EXCEPT="파트너";
            }                
            elseif($_SESSION['sessLogin']['staff_type']=="ceo"){
                $POWER_EXCEPT="";
            }else{
                $POWER_EXCEPT=$ERP_POWER;
            }
            ?>
            <div class="label_checkbox">
                
                <?
                $arr =explode(",",$ERP_POWER);
                foreach ($arr as $key => $value) {
                    $checked = (strstr($rs[power_erp],$value))?"checked":"";
                    $hide = (strstr($POWER_EXCEPT,$value))?"hide":"";
                ?>    
                    <span class="<?=$hide?>">
                        <label><input type="checkbox" value="<?=$value?>" name="power_erp[]" class="power_erp" title="<?=$value?>" <?=$checked?>> <?=$value?></label>
                    </span>
                <?}?>


            </div>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>



        <tr class="hide">
          <td class="subject">권한(구버전)</td>
          <td>
            <div class="label_checkbox">
                <?=checkbox($STAFF_POWER,$STAFF_POWER2,$rs[power],'power')?>
            </div>
          </td>
        </tr>
        <tr class="hide"><td colspan="2" class="tblLine"></td></tr>
    


        <tr>
            <td class="subject">견적서 색상</td>
            <td>
                <?=html_input('paper_color',30,40)?> <?=$rs[paper_color]?>
            </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>  


    


        <tr>
            <td class="subject">고객송출현황 색상</td>
            <td>
                <?=html_input('color',30,40)?> <?=$rs[color]?>
            </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>  






        <?if($rs[id_no]){?>
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">등록일</td>
          <td>
            <?=$rs[reg_date]?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
        <?}?>

        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan=2 bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
        <td colspan=2>
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

