<?
include_once("./include_common_file.php");



$phone1 = rnf($phone1);
$phone2 = rnf($phone2);
$phone3 = rnf($phone3);
$cell1 = rnf($cell1);
$cell2 = rnf($cell2);
$cell3 = rnf($cell3);

if($mobile){
    $arr = explode("-",$cell);
    $cell1 = rnf($arr[0]);
    $cell2 = rnf($arr[1]);
    $cell3 = rnf($arr[2]);    
    $arr = @explode("-",$phone);
    $phone1 = rnf($arr[0]);
    $phone2 = rnf($arr[1]);
    $phone3 = rnf($arr[2]);
}

$phone = "${phone1}-${phone2}-${phone3}";
$cell = "${cell1}-${cell2}-${cell3}";
$pwd_db = create_hash($pwd);
$pwd_current_db = create_hash($pwd_current);


$cp_id = $_SESSION[CID];
$id =($cp_id)? $cp_id.".".trim($id) : trim($id);


switch ($mode){

    case "save":

        $reg_date = date('Y/m/d');
        $reg_date2 = date('H:i:s');

        $assort=1;

        $id_ext = $_SESSION['EXT_LOGIN']['ID'];
        $assort_ext = $_SESSION['EXT_LOGIN']['ASSORT'];        
        if($id_ext){
            $id=$assort_ext.".".$id_ext;
            $pwd_db=create_hash($id_ext);
            $_SESSION['EXT_LOGIN']['ID']="";
            $_SESSION['EXT_LOGIN']['ASSORT']="";
            $_SESSION['EXT_LOGIN']['DATA']="";
            unset($_SESSION['EXT_LOGIN']);
        }


        $sql = "
                select * from ez_member where id='$id'
            ";
        $dbo->query($sql);
        $rs=$dbo->next_record();
        if($rs[id]){
            msggo("$cp_id 이미 등록된 아이디입니다.","../mem_login.html");
            exit;
        }

        $sql="
           insert into ez_member (
              cp_id,
              id_ext,
              assort_ext,
              id,
              pwd,   
              name,
              phone1,
              phone2,
              phone3,
              phone,
              cell1,
              cell2,
              cell3,
              cell,
              email,
              email_bit,
              name_eng,
              name_eng2,
              zipcode,
              address,
              address2,
              memo,
              reg_date,
              reg_date2
          ) values (
              '$cp_id',
              '$id_ext',
              '$assort_ext',
              '$id',
              '$pwd_db',
              '$name',
              '$phone1',
              '$phone2',
              '$phone3',
              '$phone',
              '$cell1',
              '$cell2',
              '$cell3',
              '$cell',
              '$email',
              '$email_bit',
              '$name_eng',
              '$name_eng2',
              '$zipcode',
              '$address',
              '$address2',
              '$memo',
              '$reg_date',
              '$reg_date2'
        )";

        if($dbo->query($sql)){
            $url = "../mypage01.html";

            if($id_ext){
                $_SESSION["sessMember"] = array(
                    "id"=> $id,
                    "name"=> $name,
                    "phone"=> $cell,
                );
                $_SESSION["name"]=$name;
                $_SESSION["phone"]=$cell;
                redirect2($url);exit;                
            }            

            login($id,$pwd,$url);
        }else{
            if(strstr("@211.226.255.74@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
                checkVar(mysql_error(),$sql);
            }else{
                error('죄송합니다. 가입되지 않았습니다. 관리자에게 문의바랍니다.');
            }
        }
        exit;

        break; //join End


    case "modify":

        $id = $_SESSION["sessMember"]["id"];
        $sql = "select * from ez_member where id='$id' and id<>'' and cp_id='$cp_id' limit 1";
        $dbo->query($sql);
        $rs = $dbo -> next_record();
        if(!$rs[id_ext]){
            if(!validate_password($pwd_current, $rs[pwd])) {
                error('비밀번호를 확인해 주세요.');exit;
            }
        }



        $pwdQuery = ($pwd)? " pwd = '$pwd_db', ":"";

        $reg_date = date('Y/m/d');
        $reg_date2 = date('H:i:s');

        $sql="
           update ez_member set
              $pwdQuery
              name = '$name',
              phone1 = '$phone1',
              phone2 = '$phone2',
              phone3 = '$phone3',
              phone = '$phone',
              cell1 = '$cell1',
              cell2 = '$cell2',
              cell3 = '$cell3',
              cell = '$cell',
              email = '$email',
              email_bit = '$email_bit',
              name_eng = '$name_eng',
              name_eng2 = '$name_eng2',
              zipcode = '$zipcode',
              address = '$address',
              address2 = '$address2',
              memo = '$memo'
           where 
                id='$id' 
                and cp_id='$cp_id' 
           limit 1
        ";

        if ($dbo->query($sql)){
            //checkVar("",$sql);
            //checkVar("",mysql_error());exit;
            //error("회원정보가 수정되었습니다.");
            echo "
                <script>
                alert('회원정보가 수정되었습니다.');
                parent.location.reload();
                </script>
            ";
            exit;

        }else{
            // checkVar("",$sql);
            // checkVar("",mysql_error());exit;
            if(!strstr($id,".")){
                error('죄송합니다. 처리되지 않았습니다. 비밀번호를 확인해 주세요.');exit;
            }else{
                error('죄송합니다. 처리되지 않았습니다. 관리자에게 문의바랍니다.');exit;
            }

        }
}
?>