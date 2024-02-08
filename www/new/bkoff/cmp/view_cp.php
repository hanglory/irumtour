<?
include_once("../include/common_file.php");


chk_power($_SESSION["sessLogin"]["proof"],"기본설정");


if($CP_ID) exit; //독립형 파트너 제외


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_cp";
$MENU = "cmp_paper";
$TITLE = "파트너 등록";
$maxFileSize = intval(substr(ini_get(upload_max_filesize),0,-1));




#### mode
if($mode=="save"){


    $BANK=get_bank_code_popbill($bank);
    $CORPNUM=rnf($biz_no);
    $BANK_ACCOUNT=rnf($bank_account);   

    $filename= "../../public/cp/config_cp_". $id .".inc";
    $fp=fopen($filename, "w");  //파일 쓰기모드로 열기, 파일이 있다면 overwirte
    $config ="<?\n";
    $config .="\$PTN_PG_API_ID='$PTN_PG_API_ID';\n";
    $config .="\$PTN_PG_API_KEY='$PTN_PG_API_KEY';\n";
    $config .="\$CORPNUM='$CORPNUM';\n";
    $config .="\$LINKID='$LinkID';\n";
    $config .="\$USERID='$linkhub_userid';\n";
    $config .="\$BANK_ACCOUNT='$BANK_ACCOUNT';\n";
    $config .="\$BANK='$BANK';\n";
    $config .="\$BANK_OWNER='$bank_owner';\n";
    $config .="\$FRANCHISECORPNAME='$company';\n";
    $config .="\$FRANCHISECEONAME='$ceo';\n";
    $config .="\$FRANCHISEADDR='$address $address2';\n";
    $config .="\$FRANCHISETEL='$phone';\n";
    $config .="?";
    $config .=">";
    fwrite($fp,$config);
    fclose($fp);


    /*
    $sql =(!$id_no)? "select * from cmp_cp where biz_no='$biz_no'" : "select * from cmp_cp where biz_no='$biz_no' and id_no<>'$id_no'";
    $dbo->query($sql);
    //if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}      
    $rs=$dbo->next_record();
    if($rs[biz_no]){
        alert("같은 사업자등록번호가 이미 등록되어 있습니다. 확인해 주세요.");
        exit;
    }
    */


    if($_FILES["file1"]["size"]){
        #------------------------------------------
        $path="../../public/partner";   //업로드할 파일의 경로
        $maxsize=10 *(1024*1024) ;          //2MB   업로드 가능한 최대 사이즈 제한
        $fname=$_FILES["file1"]["tmp_name"];                        //파일이름을 담고 있는 변수 이름
        $fname_name=$_FILES["file1"]["name"];   //파일의 이름
        $fname_size=$_FILES["file1"]["size"];       //파일의 사이즈
        $fname_type=$_FILES["file1"]["type"];       //파일의 type
        $filename=$id;      //파일이름 작명
        $type = "image"; // 일반파일 normal, 이미지만 image
        #------------------------------------------
        include("../../include/file_upload.php");
        $upfile1 = $upfile;
        $upfile1_real = $_FILES["file1"]["name"];
        $upfileQuery1 = ($upfile)? "filename = '$upfile1', ":"" ;
    }
    if($_FILES["file2"]["size"]){
        #------------------------------------------
        $path="../../public/partner";   //업로드할 파일의 경로
        $maxsize=10 *(1024*1024) ;          //2MB   업로드 가능한 최대 사이즈 제한
        $fname=$_FILES["file2"]["tmp_name"];                        //파일이름을 담고 있는 변수 이름
        $fname_name=$_FILES["file2"]["name"];   //파일의 이름
        $fname_size=$_FILES["file2"]["size"];       //파일의 사이즈
        $fname_type=$_FILES["file2"]["type"];       //파일의 type
        $filename=$id."_certi";      //파일이름 작명
        $type = "normal"; // 일반파일 normal, 이미지만 image
        #------------------------------------------
        include("../../include/file_upload.php");
        $upfile2 = $upfile;
        $upfile2_real = $_FILES["file2"]["name"];
        $upfileQuery2 = ($upfile)? "filename2 = '$upfile2', ":"" ;
    }
    if($_FILES["file3"]["size"]){
        #------------------------------------------
        $path="../../public/partner";   //업로드할 파일의 경로
        $maxsize=10 *(1024*1024) ;          //2MB   업로드 가능한 최대 사이즈 제한
        $fname=$_FILES["file3"]["tmp_name"];                        //파일이름을 담고 있는 변수 이름
        $fname_name=$_FILES["file3"]["name"];   //파일의 이름
        $fname_size=$_FILES["file3"]["size"];       //파일의 사이즈
        $fname_type=$_FILES["file3"]["type"];       //파일의 type
        $filename=$id."_header";      //파일이름 작명
        $type = "image"; // 일반파일 normal, 이미지만 image
        #------------------------------------------
        include("../../include/file_upload.php");
        $upfile3 = $upfile;
        $upfile3_real = $_FILES["file3"]["name"];
        $upfileQuery3 = ($upfile)? "filename3 = '$upfile3', ":"" ;
    }
    if($_FILES["file4"]["size"]){
        #------------------------------------------
        $path="../../public/partner";   //업로드할 파일의 경로
        $maxsize=10 *(1024*1024) ;          //2MB   업로드 가능한 최대 사이즈 제한
        $fname=$_FILES["file4"]["tmp_name"];                        //파일이름을 담고 있는 변수 이름
        $fname_name=$_FILES["file4"]["name"];   //파일의 이름
        $fname_size=$_FILES["file4"]["size"];       //파일의 사이즈
        $fname_type=$_FILES["file4"]["type"];       //파일의 type
        $filename=$id."_kakao";      //파일이름 작명
        $type = "image"; // 일반파일 normal, 이미지만 image
        #------------------------------------------
        include("../../include/file_upload.php");
        $upfile4 = $upfile;
        $upfile4_real = $_FILES["file4"]["name"];
        $upfileQuery4 = ($upfile)? "filename4 = '$upfile4', ":"" ;
    }
    if($_FILES["file5"]["size"]){
        #------------------------------------------
        $path="../../public/partner";   //업로드할 파일의 경로
        $maxsize=10 *(1024*1024) ;          //2MB   업로드 가능한 최대 사이즈 제한
        $fname=$_FILES["file5"]["tmp_name"];                        //파일이름을 담고 있는 변수 이름
        $fname_name=$_FILES["file5"]["name"];   //파일의 이름
        $fname_size=$_FILES["file5"]["size"];       //파일의 사이즈
        $fname_type=$_FILES["file5"]["type"];       //파일의 type
        $filename=$id."_sign";      //파일이름 작명
        $type = "image"; // 일반파일 normal, 이미지만 image
        #------------------------------------------
        include("../../include/file_upload.php");
        $upfile5 = $upfile;
        $upfile5_real = $_FILES["file5"]["name"];
        $upfileQuery5 = ($upfile)? "filename5 = '$upfile5', ":"" ;
    }
    if($_FILES["file6"]["size"]){
        #------------------------------------------
        $path="../../public/partner";   //업로드할 파일의 경로
        $maxsize=10 *(1024*1024) ;          //2MB   업로드 가능한 최대 사이즈 제한
        $fname=$_FILES["file6"]["tmp_name"];                        //파일이름을 담고 있는 변수 이름
        $fname_name=$_FILES["file6"]["name"];   //파일의 이름
        $fname_size=$_FILES["file6"]["size"];       //파일의 사이즈
        $fname_type=$_FILES["file6"]["type"];       //파일의 type
        $filename=$id."_sign2";      //파일이름 작명
        $type = "image"; // 일반파일 normal, 이미지만 image
        #------------------------------------------
        include("../../include/file_upload.php");
        $upfile6 = $upfile;
        $upfile6_real = $_FILES["file6"]["name"];
        $upfileQuery6 = ($upfile)? "filename6 = '$upfile6', ":"" ;
    }

    if($_FILES["file7"]["size"]){
        #------------------------------------------
        $path="../../public/partner";   //업로드할 파일의 경로
        $maxsize=10 *(1024*1024) ;          //2MB   업로드 가능한 최대 사이즈 제한
        $fname=$_FILES["file7"]["tmp_name"];                        //파일이름을 담고 있는 변수 이름
        $fname_name=$_FILES["file7"]["name"];   //파일의 이름
        $fname_size=$_FILES["file7"]["size"];       //파일의 사이즈
        $fname_type=$_FILES["file7"]["type"];       //파일의 type
        $filename=$id."_ico";      //파일이름 작명
        $type = "normal"; // 일반파일 normal, 이미지만 image
        #------------------------------------------
        include("../../include/file_upload.php");
        $upfile7 = $upfile;
        $upfile7_real = $_FILES["file7"]["name"];
        $upfileQuery7 = ($upfile)? "filename7 = '$upfile7', ":"" ;
    }


    $reg_date = date('Y/m/d');
    $reg_date2 = date('H:i:s');
    $pwd_db = create_hash($pwd);
    $pwdQuery = ($pwd)? " pwd = '$pwd_db', ":"";

    if($rn){
        $aes = new AES($rn, $inputKey, $blockSize);
        $enc = $aes->encrypt();
        $rn = $enc;
    }

    $sms_id = $_POST[sms_id];
    $sms_passwd = $_POST[sms_passwd];

    $sqlInsert="
       insert into cmp_cp (
          id,
          partner_type,
          biz_type1,
          biz_type2,
          company,
          company2,
          pwd,
          biz_no,
          rn,
          zipcode,
          address,
          address2,
          ceo,
          phone,
          email,
          staff,
          staff_phone,
          filename,
          filename2,
          filename3,
          filename4,
          filename5,
          filename6,
          filename7,
          bit_block,
          blog,
          facebook,
          instagram,
          youtube,
          naver_form,
          kakao,    
          fax_name,
          fax,
          tour_licence,
          stock_licence,
          sale_licence,  
        cp_url,
        color,
        bank_account,
        bank,
        bank_owner,
        paper_color,  
        PTN_PG_API_ID,
        PTN_PG_API_KEY,        
        index_type,      
        cp_naver_client_id,
        cp_naver_client_secret,
        cp_kakao_appkey,
        corpNum,
        LinkID,
        linkhub_userid,
        bit_close_domestic,
        est_sns,
        sms_id,
        sms_passwd,        
          reg_date,
          reg_date2
      ) values (
          '$id',
          '$partner_type',
          '$biz_type1',
          '$biz_type2',
          '$company',
          '$company2',
          '$pwd_db',
          '$biz_no',
          '$rn',
          '$zipcode',
          '$address',
          '$address2',
          '$ceo',
          '$phone',
          '$email',
          '$staff',
          '$staff_phone',
          '$upfile1',
          '$upfile2',
          '$upfile3',
          '$upfile4',
          '$upfile5',
          '$upfile6',
          '$upfile7',
          '$bit_block',
          '$blog',
          '$facebook',
          '$instagram',
          '$youtube',          
          '$naver_form',
          '$kakao',   
          '$fax_name',
          '$fax',
          '$tour_licence',
          '$stock_licence',
          '$sale_licence', 
        '$cp_url',
        '$color',
        '$bank_account',
        '$bank',
        '$bank_owner',
        '$paper_color',
        '$PTN_PG_API_ID',
        '$PTN_PG_API_KEY',        
        '$index_type',  
        '$cp_naver_client_id',
        '$cp_naver_client_secret',
        '$cp_kakao_appkey',
        '$corpNum',
        '$LinkID',        
        '$linkhub_userid',        
        '$bit_close_domestic',        
        '$est_sns',      
        '$sms_id',
        '$sms_passwd',          
          '$reg_date',
          '$reg_date2'
    )";


    $sqlModify="
       update cmp_cp set
          $upfileQuery1
          $upfileQuery2
          $upfileQuery3
          $upfileQuery4
          $upfileQuery5
          $upfileQuery6
          $upfileQuery7
          partner_type = '$partner_type',
          biz_type1 = '$biz_type1',
          biz_type2 = '$biz_type2',          
          company = '$company',
          company2 = '$company2',
          biz_no = '$biz_no',
          rn = '$rn',
          zipcode = '$zipcode',
          address = '$address',
          address2 = '$address2',
          ceo = '$ceo',
          phone = '$phone',
          email = '$email',
          staff = '$staff',
          staff_phone = '$staff_phone',
          blog = '$blog',
          facebook='$facebook',
          instagram='$instagram',
          youtube='$youtube',          
          naver_form = '$naver_form',
          kakao = '$kakao',       
          fax_name='$fax_name',
          fax='$fax',
          tour_licence='$tour_licence',
          stock_licence='$stock_licence',
          sale_licence='$sale_licence',                   
          bit_block = '$bit_block',
          cp_naver_client_id='$cp_naver_client_id',
          cp_naver_client_secret='$cp_naver_client_secret',
          cp_kakao_appkey='$cp_kakao_appkey',
        cp_url = '$cp_url',
        color = '$color',
        PTN_PG_API_ID = '$PTN_PG_API_ID',
        PTN_PG_API_KEY = '$PTN_PG_API_KEY',
        index_type = '$index_type',
        corpNum='$corpNum',
        LinkID='$LinkID',
        linkhub_userid='$linkhub_userid',
        bit_close_domestic='$bit_close_domestic',
        est_sns = '$est_sns',
        bank_account = '$bank_account',
        bank = '$bank',
        bank_owner = '$bank_owner',
        sms_id = '$sms_id',
        sms_passwd = '$sms_passwd',
        paper_color = '$paper_color'
       where id_no='$id_no'
    ";

    if($id_no){
        $sql = $sqlModify;
        $url = "view_${filecode}.php?id_no=$id_no";
    }else{
        $sql = $sqlInsert;
        $url = "list_${filecode}.php";
    }

    if($dbo->query($sql)){
    
        if(!$id_no){//신규(ez_tour best 생성)
            include("../tour/inc_cp_best_set.php");
        }

        echo "<script>alert('저장하였습니다.');parent.location.href='$url';</script>";
        //msggo("저장하였습니다.",$url);
    }else{
        if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
            checkVar(mysql_error(),$sql);exit;
        }else{
            echo "<script>alert('저장하지 못했습니다.');parent.location.href='$url';</script>";
        }
    }
    exit;

}elseif ($mode=="drop"){
    for($i = 0; $i < count($check);$i++){
        $sql = "select *  from $table where id_no=$check[$i]";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        if($rs[filename]) @unlink("../../public/partner/$rs[filename]");
    
        $sql = "delete from $table where id_no = $check[$i]";
        $dbo->query($sql);
    }
    back();exit;

}elseif ($mode=="file_drop"){
        $sql = "update $table set $mode2 ='' where id_no=$id_no";
        $dbo->query($sql);
        @unlink("../../public/partner/$filename");
        redirect2("?id_no=$id_no&$_SESSION[link]");exit;    


}elseif($mode=="bit_hide"){

        for($i = 0; $i < count($check);$i++){
            $sql = "update $table set bit_hide=$bit_hide where id_no = $check[$i] $FILTER_PARTNER_QUERY";
            $dbo->query($sql);
        }
        back();exit;

}else{
    $sql = "select * from $table where id_no=$id_no";
    $dbo->query($sql);
    $rs= $dbo->next_record();


    if($rs[rn]){
        $aes = new AES($rs[rn], $inputKey, $blockSize);
        $dec=$aes->decrypt();
        $rs[rn] = $dec;
    }
}


$rs[fax_name] = ($rs[fax_name])? $rs[fax_name] : "팩스번호";
//-------------------------------------------------------------------------------
?>
<?include("../top.html");?>
<script src='../../bgrins/spectrum.js'></script>
<link rel='stylesheet' href='../../bgrins/spectrum.css' />
<script language="JavaScript">
<!--
function chkForm(){

    var fm  =document.fmData;

        var fm = document.fmData;

        <?if(!$rs[id] && $onoff!=2 && $onoff_bit!=2){?>
            if(check_blank(fm.id,'아이디를',3)=='wrong'){return}
            if(check_strnum(fm.id,'아이디')=='wrong'){return}
        <?}?>

        if(check_blank(fm.company,'업체명을',0)=='wrong'){return}

        /*  
        if(check_strnum(fm.pwd,'비밀번호')=='wrong'){return}

        if(fm.pwd.value!=fm.pwd_check.value){
            alert("비밀번호와 비밀번호 확인이 다릅니다.");
            fm.pwd_check.focus();
        }
        */  

        fm.submit();

    fm.submit();
}

<?if($_SESSION["sessLogin"]["staff_type"]=="ceo"){?>
function reset_account(){
    let url="drop_cp.php?mode=drop";
    url +="&cp_id=<?=$rs[id]?>"
    if(confirm("이 계정의 모든 데이터와 파일이 삭제됩니다.\n진행하시겠습니까?")){
        if(confirm("이 작업은 되돌릴 수 없습니다.\n정말 진행하시겠습니까?")){
            actarea.location.href=url;
        }
    }
}
<?}?>


$(function(){
    $("#biz_no,#corpNum").mask("999-99-99999");

    $("#color,#paper_color").spectrum({
        allowEmpty: true,
        preferredFormat: true,
        showInput: true,
    });

    $("#cp_url").attr("placeholder","http://파트너아이디.irumplace.com");

    $("#cp_naver_client_id").attr("placeholder","Client ID");
    $("#cp_naver_client_secret").attr("placeholder","Client Secret");
    $("#cp_kakao_appkey").attr("placeholder","앱키(JavaScript키)");
    $("#corpNum").attr("placeholder","220-87-31753");
    $("#LinkID").attr("placeholder","IRUMPLACE");
});
//-->
</script>
<style type="text/css">
.addr input{
    margin-bottom:2px;
}   
.bx_attachfile{
    margin:3px 0 3px 0;

}
.bx_attachfile img{
    border:1px solid #ccc;
    max-width:500px;
}    
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


      <table border="0" cellspacing="1" cellpadding="3" width="750">

        <form name="fmData" method="post" enctype="multipart/form-data" target="actarea">
        <input type="hidden" name="mode" value="save">
        <input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
        <input type="hidden" id="address_old" value='<?=$rs[address_old]?>'>

        <tr><td colspan="4"  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">아이디</td>
          <td>
            <?if(!$rs[id_no]){?>
                <?=html_input("id",20,30)?>
            <?}else{?>  
                <?=$rs[id]?>
                <input type="hidden" name="id" value='<?=$rs[id]?>'>
            <?}?>
          </td>
          <td class="subject">파트너종류</td>
          <td>
            <select name="partner_type">
                <option value="">선택</option>
                <?=option_str("파트너I,파트너A,파트너G","partner_i,partner_a,partner_g",$rs[partner_type])?>
            </select>            
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>   

        <tr>
          <td class="subject" width="18%">업체명</td>
          <td>
            <?=html_input("company",20,30)?>
          </td>
          <td class="subject" width="17%">업체명칭</td>
          <td>
            <?=html_input("company2",30,50)?>
          </td>

        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>   

<!--         <tr>
          <td class="subject" width="15%">패스워드</td>
          <td>
            <?=html_input("pwd",20,20)?>
          </td>

          <td class="subject" width="15%">패스워드 확인</td>
          <td>
            <?=html_input("pwd_check",20,20)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>    -->     
       
        <tr>
          <td class="subject" width="15%">사업자등록번호</td>
          <td>
            <?=html_input("biz_no",15,12,'box c')?>
          </td>

          <td class="subject" width="15%"><?=html_input("fax_name",13,30,'box bold r')?></td>
          <td>
            <?=html_input("fax",30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>        
              
        <tr>
          <td class="subject" width="15%">업태</td>
          <td>
            <?=html_input("biz_type1",30,50)?>
          </td>

          <td class="subject" width="15%">업종</td>
          <td>
            <?=html_input("biz_type2",30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>        
       
       
        <tr>
          <td class="subject" width="15%">주소</td>
          <td colspan="3">

            <div class="addr">
                <?=html_input("zipcode",8,5,'box c')?> <span class="btn_pack medium bold"><a href="javascript:set_zip()"> 우편번호 </a></span><br/>
                <?=html_input("address",60,150)?><br/>
                <?=html_input("address2",60,150)?>
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
        <tr><td colspan="4" class="tblLine"></td></tr>        
       
       
        <tr>
          <td class="subject" width="15%">대표자명</td>
          <td>
            <?=html_input("ceo",30,50)?>
          </td>

          <td class="subject" width="15%">대표전화번호</td>
          <td>
            <?=html_input("phone",30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>        
       
       
        <tr>
          <td class="subject" width="15%">담당자</td>
          <td>
            <?=html_input("staff",30,50)?>
          </td>

          <td class="subject" width="15%">담당자번호</td>
          <td>
            <?=html_input("staff_phone",30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>    
       
        <tr>
          <td class="subject" width="15%">이메일</td>
          <td>
            <?=html_input("email",30,50)?>
          </td>
          <td class="subject" width="15%">관광사업자등록번호</td>
          <td>
            <?=html_input("tour_licence",30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <tr>
          <td class="subject" width="15%" style="letter-spacing: -1px;">여행업인허가증권번호</td>
          <td>
            <?=html_input("stock_licence",30,50)?>
          </td>
          <td class="subject" width="15%">통신판매번호</td>
          <td>
            <?=html_input("sale_licence",30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>                

       
        <tr>
          <td class="subject" width="15%">네이버블로그</td>
          <td colspan="3">
            <?=html_input("blog",80,150)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>      
      
        <tr>
          <td class="subject" width="15%">페이스북</td>
          <td colspan="3">
            <?=html_input("facebook",80,150)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>      
      
        <tr>
          <td class="subject" width="15%">인스타그램</td>
          <td colspan="3">
            <?=html_input("instagram",80,150)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>      
      
        <tr>
          <td class="subject" width="15%">유튜브</td>
          <td colspan="3">
            <?=html_input("youtube",80,150)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>      
      
        <tr>
          <td class="subject" width="15%">카톡링크</td>
          <td colspan="3">
            <?=html_input("kakao",80,150)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>        
       
        <!-- <tr>
          <td class="subject" width="15%">네이버폼</td>
          <td colspan="3">
            <?=html_input("naver_form",80,150)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>          -->


        <tr>
          <td class="subject" width="15%">여행상품정보</td>
          <td colspan="3">
            <textarea name="est_sns" class="box" cols="80" rows="3" placeholder="일정표 '여행상품정보SNS'에 표시"><?=$rs[est_sns]?></textarea></span>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


        <!--담당자 정보에서 이전 s-->
        <tr>
            <td class="subject">파트너링크</td>
            <td colspan="3">
                <?=html_input('cp_url',45,40)?> 
            </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
    

        <tr>
            <td class="subject">링크허브정보</td>
            <td colspan="3">
                <strong>LinkID</strong> : <?=html_input('LinkID',25,45)?>
                <strong>링크허브ID</strong> : <?=html_input('linkhub_userid',25,45)?>
            </td>
        </tr>   
        <tr><td colspan="4" class="tblLine"></td></tr>
        <tr>
            <td class="subject">계좌번호</td>
            <td colspan="3">
                <select name="bank">
                    <option value="">은행선택</option>
                    <?=option_str($BANK_NAMES,$BANK_NAMES,$rs[bank])?>
                </select>
                계좌번호:<?=html_input('bank_account',25,45)?>
                예금주:<?=html_input('bank_owner',20,30)?>
            </td>
        </tr>   
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
            <td class="subject">색상</td>
            <td colspan="3">
                <?=html_input('color',30,40)?> <?=$rs[color]?>
            </td>
        </tr>

        <tr><td colspan="4" class="tblLine"></td></tr>
        <tr>
            <td class="subject">견적서 색상</td>
            <td colspan="3">
                <?=html_input('paper_color',30,40)?> <?=$rs[paper_color]?>
            </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>          
        <!--담당자 정보에서 이전 f-->


        <tr>
            <td class="subject">템플릿 타입</td>
            <td colspan="3">
                <?$rs[index_type]=($rs[index_type])?$rs[index_type]:1?>
                <?=radio("Type1,Type2,Type3","1,2,3",$rs[index_type],'index_type')?>

                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <label><input type="checkbox" name="bit_close_domestic" value="1" <?=($rs[bit_close_domestic])?'checked':''?>> 홈페이지 국내 숨기기</label>
            </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


    <?if($rs[filename]):
        @$fileSize = filesize("../../public/partner/${rs[filename]}");
        $pic_info=@getimagesize("../../public/partner/${rs[filename]}");
        ?>
        <tr>
          <td class="subject">로고<r/td>
          <td colspan="3">
            <input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename&filename=<?=$rs[filename]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename]?>&orgin_file_name=<?=$rs[filename]?>&dir=public/partner" onFocus="blur(this)"><?=$rs[filename]?> (<?=ceil($fileSize/1024)?>KB)</a>

            <?if($pic_info[2]){?>
            <div class="bx_attachfile">
                <img src="../../public/partner/<?=$rs[filename]?>">
            </div>
            <?}?>

          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
    <?else:?>
        <tr>
          <td class="subject">로고 업로드</td>
          <td colspan="3">
            <input class=box type="file" name="file1" size=40>
            <font color="orange">Alert</font></b> <font color="#666666">: <?=$maxFileSize?>MB 이상은 업로드 되지 않습니다. 가로 180px 이하,세로 70px 이하)</font>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
    <?endif;?>
       




    <?if($rs[filename7]):
        @$fileSize = filesize("../../public/partner/${rs[filename7]}");
        $pic_info=@getimagesize("../../public/partner/${rs[filename7]}");
        ?>
        <tr>
          <td class="subject">파비콘<r/td>
          <td colspan="7">
            <input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename7&filename=<?=$rs[filename7]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename7]?>&orgin_file_name=<?=$rs[filename7]?>&dir=public/partner" onFocus="blur(this)"><?=$rs[filename7]?> (<?=ceil($fileSize/1027)?>KB)</a>

            <?if($pic_info[2]){?>
            <div class="bx_attachfile">
                <img src="../../public/partner/<?=$rs[filename7]?>" height="16">
            </div>
            <?}?>    

          </td>
        </tr>
        <tr><td colspan="7" class='bar'></td></tr>
    <?else:?>
        <tr>
          <td class="subject">파비콘 업로드</td>
          <td colspan="3">
            <input class=box type="file" name="file7" size=40>
            <font color="orange">Alert</font></b> <font color="#666666">: <?=$maxFileSize?>MB 이상은 업로드 되지 않습니다.</font>
          </td>
        </tr> 
        <tr><td colspan="4" class='bar'></td></tr>
    <?endif;?>






    <?if($rs[filename5]):
        @$fileSize = filesize("../../public/partner/${rs[filename5]}");
        $pic_info=@getimagesize("../../public/partner/${rs[filename5]}");
        ?>
        <tr>
          <td class="subject">명판도장<r/td>
          <td colspan="3">
            <input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename5&filename=<?=$rs[filename5]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename5]?>&orgin_file_name=<?=$rs[filename5]?>&dir=public/partner" onFocus="blur(this)"><?=$rs[filename5]?> (<?=ceil($fileSize/1024)?>KB)</a>
            
            <?if($pic_info[2]){?>
            <div class="bx_attachfile">
                <img src="../../public/partner/<?=$rs[filename5]?>" height="200">
                가로 350px, 세로 150px 고정
            </div>
            <?}?>

          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
    <?else:?>
        <tr>
          <td class="subject">명판도장 업로드</td>
          <td colspan="3">
            <input class=box type="file" name="file5" size="40">
            <font color="orange">Alert</font></b> <font color="#666666">: <?=$maxFileSize?>MB 이상은 업로드 되지 않습니다.</font>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
    <?endif;?>




    <?if($rs[filename6]):
        @$fileSize = filesize("../../public/partner/${rs[filename6]}");
        $pic_info=@getimagesize("../../public/partner/${rs[filename6]}");
        ?>
        <tr>
          <td class="subject">법인도장<r/td>
          <td colspan="3">
            <input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename6&filename=<?=$rs[filename6]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename6]?>&orgin_file_name=<?=$rs[filename6]?>&dir=public/partner" onFocus="blur(this)"><?=$rs[filename6]?> (<?=ceil($fileSize/1024)?>KB)</a>
            
            <?if($pic_info[2]){?>
            <div class="bx_attachfile">
                <img src="../../public/partner/<?=$rs[filename6]?>" width="100">
            </div>
            <?}?>

          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
    <?else:?>
        <tr>
          <td class="subject">법인도장 업로드</td>
          <td colspan="3">
            <input class=box type="file" name="file6" size="40">
            <font color="orange">Alert</font></b> <font color="#666666">: <?=$maxFileSize?>MB 이상은 업로드 되지 않습니다.</font>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
    <?endif;?>



    <?if($rs[filename2]):
        @$fileSize = filesize("../../public/partner/${rs[filename2]}");
        $pic_info=@getimagesize("../../public/partner/${rs[filename2]}");
        ?>
        <tr>
          <td class="subject">사업자등록증<r/td>
          <td colspan="3">
            <input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename2&filename=<?=$rs[filename2]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename2]?>&orgin_file_name=<?=$rs[filename2]?>&dir=public/partner" onFocus="blur(this)"><?=$rs[filename2]?> (<?=ceil($fileSize/1024)?>KB)</a>
            
            <?if($pic_info[2]){?>
            <div class="bx_attachfile">
                <img src="../../public/partner/<?=$rs[filename2]?>" width="300">
            </div>
            <?}?>

          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
    <?else:?>
        <tr>
          <td class="subject">사업자등록증 업로드</td>
          <td colspan="3">
            <input class=box type="file" name="file2" size="40">
            <font color="orange">Alert</font></b> <font color="#666666">: <?=$maxFileSize?>MB 이상은 업로드 되지 않습니다.</font>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
    <?endif;?>


    <?if($rs[filename3]):
        @$fileSize = filesize("../../public/partner/${rs[filename3]}");
        $pic_info=@getimagesize("../../public/partner/${rs[filename3]}");
        ?>
        <tr>
          <td class="subject">헤더<r/td>
          <td colspan="3">
            <input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename3&filename=<?=$rs[filename3]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename3]?>&orgin_file_name=<?=$rs[filename3]?>&dir=public/partner" onFocus="blur(this)"><?=$rs[filename3]?> (<?=ceil($fileSize/1024)?>KB)</a>

            <?if($pic_info[2]){?>
            <div class="bx_attachfile">
                <img src="../../public/partner/<?=$rs[filename3]?>" width="600">
            </div>
            <?}?>            
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
    <?else:?>
        <tr>
          <td class="subject">헤더 업로드</td>
          <td colspan="3">
            <input class=box type="file" name="file3" size=40>
            <font color="orange">Alert</font></b> <font color="#666666">: <?=$maxFileSize?>MB 이상은 업로드 되지 않습니다.</font>
          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
    <?endif;?>


    <?if($rs[filename4]):
        @$fileSize = filesize("../../public/partner/${rs[filename4]}");
        $pic_info=@getimagesize("../../public/partner/${rs[filename4]}");
        ?>
        <tr>
          <td class="subject">카톡이미지<r/td>
          <td colspan="4">
            <input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename4&filename=<?=$rs[filename4]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename4]?>&orgin_file_name=<?=$rs[filename4]?>&dir=public/partner" onFocus="blur(this)"><?=$rs[filename4]?> (<?=ceil($fileSize/1024)?>KB)</a>

            <?if($pic_info[2]){?>
            <div class="bx_attachfile">
                <img src="../../public/partner/<?=$rs[filename4]?>" height="200">
            </div>
            <?}?>    

          </td>
        </tr>
        <tr><td colspan="4" class='bar'></td></tr>
    <?else:?>
        <tr>
          <td class="subject">카톡이미지 업로드</td>
          <td colspan="3">
            <input class=box type="file" name="file4" size=40>
            <font color="orange">Alert</font></b> <font color="#666666">: <?=$maxFileSize?>MB 이상은 업로드 되지 않습니다.</font>
          </td>
        </tr> 
        <tr><td colspan="4" class='bar'></td></tr>
    <?endif;?>



        <tr>
            <td class="subject" style="background: #fff;">
                <strong>*PG연동</strong>
            </td>
            <td colspan="3"><td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        

        <tr>
            <td class="subject">연동아이디(PG)</td>
            <td colspan="3">
                <?=html_input('PTN_PG_API_ID',20,40)?> 
            </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        
        <tr>
            <td class="subject">연동키(PG)</td>
            <td colspan="3">
                <?=html_input('PTN_PG_API_KEY',60,100)?>
            </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>




        <tr>
            <td class="subject" style="background: #fff;">
                <strong>*간편로그인 연동</strong>
            </td>
            <td colspan="3"><td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        

        <tr>
            <td class="subject">NAVER로그인</td>
            <td colspan="3">
                <?=html_input('cp_naver_client_id',30,40)?> 
                <?=html_input('cp_naver_client_secret',20,40)?> 
                <span class="btn_pack medium bold"><a href="https://developers.naver.com/apps/#/register?api=nvlogin" target="_blank"> Client ID 발급 </a></span>
            </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        
        <tr>
            <td class="subject">KAKAO로그인</td>
            <td colspan="3">
                <?=html_input('cp_kakao_appkey',52,40)?> 
                <span class="btn_pack medium bold"><a href="https://developers.kakao.com/console/app" target="_blank"> 앱키 발급 </a></span>

            </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>





        <tr>
            <td class="subject" style="background: #fff;">
                <strong>*SMS 연동</strong>
            </td>
            <td colspan="3"><td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        

        <tr>
            <td class="subject">ID</td>
            <td colspan="3">
                <?=html_input('sms_id',30,40)?> 
                <span class="btn_pack medium bold"><a href="https://hosting.whois.co.kr/new/sms.php" target="_blank"> SMS 신청하기 </a></span>
            </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        
        <tr>
            <td class="subject">Password</td>
            <td colspan="3">
                <?=html_input('sms_passwd',30,40)?> 

            </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>






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
          <table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
            <tr>
                <td>
                    <?if($_SESSION["sessLogin"]["staff_type"]=="ceo"){?>
                    <span class="btn_pack medium bold"><a href="javascript:reset_account()"> 계정초기화 </a></span> &nbsp;&nbsp;&nbsp;
                    <?}?>
                </td>
                <td align="right">
                    <span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span> &nbsp;&nbsp;&nbsp;
                    <span class="btn_pack medium bold"><a href="#" onClick="location.href='list_<?=$filecode?>.php?<?=$sessLink?>'"> 리스트 </a></span>
                </td>
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

