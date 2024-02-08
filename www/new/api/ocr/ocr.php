<?php
include '/home/hosting_users/irumtour/www/new/api/api_common.php';
include '/home/hosting_users/irumtour/www/new/api/bank/common.php';
header("Content-Type: text/html; charset=UTF-8");
header('Content-Type: application/json');



/*토큰 발급을 위한 아이디와 비밀번호: */
$ocr_id = "irumplace@irumtour.net";
$ocr_pwd = "irumtour";
$ip = $_SERVER["REMOTE_ADDR"];


$debug=0;

if($_SERVER["REMOTE_ADDR"]==$_SERVER["SERVER_ADDR"] && $_POST["mode"]=="ocr" && $filename){

    //Oauth - Client ID, Client Secret 조회
    $url = "https://auth.useb.co.kr/oauth/get-client-secret";
    $post   = array(
        'email' => $ocr_id,
        'password' => $ocr_pwd
        ); 
    $post_json = json_encode($post);    
    $headers = array("Content-Type:application/json");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);    
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    $response = curl_exec($ch);
    $error_msg = curl_error($ch);
    curl_close($ch);

    $json = json_decode($response,true);
    $transaction_id = $json[transaction_id];
    $client_id = $json[data][client_id];
    $client_secret = $json[data][client_secret];
    if($debug){
        checkVar("client_id",$client_id);
        checkVar("client_secret",$client_secret);
        checkVar("transaction_id",$transaction_id);
    }



    /*토큰발급*/
    $url = "https://auth.useb.co.kr/oauth/token";
    $auth = base64_encode($client_id . ":" . $client_secret);
    $headers = array(
        'Authorization:' . $auth,
        'Content-Type:application/json'
        );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    $response = curl_exec($ch);
    $error_msg = curl_error($ch);
    curl_close($ch);

    $json = json_decode($response,true);
    $success = $json[success];
    $expire = $json[expire];
    $transaction_id = $json[transaction_id];
    $jwt = $json[jwt];

    if($debug){
        checkVar("success ",$success );
        checkVar("expire ",$expire );
        checkVar("transaction_id ",$transaction_id );
        checkVar("jwt ",$jwt );
    }
    //exit;








    /*OCR(한국여권)*/
    $file_dir = $_SERVER['DOCUMENT_ROOT']."/new/public/cmp_pass/";
    // $filename = "test2.jpg";
    $file = $file_dir.$filename;
    //checkVar("file",filesize($file));exit;
    $handle = fopen($file, "r"); 
    $data = fread($handle, filesize($file)); 

    $url = "https://api3.useb.co.kr/ocr/passport";
    $post   = array(
        'image_base64' => base64_encode($data),
        'image' => $data,
        'secret_mode' => false
        ); 
    $post_json = json_encode($post);


    $headers = array(
        'Authorization:' . $jwt,
        'Content-Type:application/json, image/jpeg, png'
        );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_json);
    curl_setopt($ch, CURLOPT_INFILESIZE,filesize($file));
    curl_setopt($ch, CURLOPT_URL, $url);
    $ret = curl_exec($ch);

    $error_msg = curl_error($ch);
    curl_close($ch);

    if($debug) print_r($ret);

    unset($json);$json="";
    $json = json_decode($ret,true);
    $success = $json[success];

    if($success){

        $idType = $json[data][idType];
        $userName = $json[data][userName];
        $userNameEng = $json[data][userNameEng];
        $passportNo = $json[data][passportNo];
        $juminNo1 = $json[data][juminNo1];
        $juminNo2 = $json[data][juminNo2];
        $_juminNo2 = $json[data][_juminNo2];
        $issueDate = $json[data][issueDate];
        $expiryDate = $json[data][expiryDate];
        //$mrz1 = $json[data][mrz1];
        //$mrz2 = $json[data][mrz2];

        $sex_no = substr($juminNo2,0,1);
        $sex = (!$sex_no || $sex_no==1)? "M":"F";


        $rn = trim($juminNo1)."-".trim($juminNo2);
        $passport_no = trim($passportNo);

        if($rn){
        $aes = new AES($rn, $inputKey, $blockSize);
        $enc = $aes->encrypt();
        $rn = $enc;
        }

        if($passport_no){
        $aes = new AES($passport_no, $inputKey, $blockSize);
        $enc = $aes->encrypt();
        $passport_no = $enc;
        }

        $JSON_DATA["success"]="true";
        $JSON_DATA["ip"]=$ip;
        $JSON_DATA["idType"]=$idType;
        $JSON_DATA["userName"]=$userName;
        $JSON_DATA["userNameEng"]=$userNameEng;
        $JSON_DATA["issueDate"]=$issueDate;
        $JSON_DATA["expiryDate"]=$expiryDate;
        //$JSON_DATA["mrz1"]=$mrz1;
        //$JSON_DATA["mrz2"]=$mrz2;
        $JSON_DATA["passport_no"]=$passport_no;
        $JSON_DATA["rn"]=$rn;
        $JSON_DATA["sex"]=$sex;

        // $JSON_DATA["juminNo2"]=$juminNo2;
        // $JSON_DATA["_juminNo2"]=$_juminNo2;

        // foreach ($json[data] as $key => $value) {
        //     checkVar("----","-----------");
        //     checkVar($key,$value);
        //     checkVar("----","-----------");
        // }
        // foreach ($JSON_DATA as $key => $value) {
        //     checkVar("----","-----------");
        //     checkVar($key,$value);
        //     checkVar("----","-----------");
        // }
        // exit;

        echo to_han(json_encode($JSON_DATA));

        if($debug){
            checkVar("success ",$success );
            checkVar("idType",$idType);
            checkVar("userName",$userName);
            checkVar("userNameEng",$userNameEng);
            checkVar("passportNo",$passportNo);
            checkVar("juminNo1",$juminNo1);
            checkVar("juminNo2",$juminNo2);
            checkVar("_juminNo2",$_juminNo2);
            checkVar("issueDate",$issueDate);
            checkVar("expiryDate",$expiryDate);
            checkVar("mrz1",$mrz1);
            checkVar("mrz2",$mrz2);
            checkVar("rn",$rn);
            checkVar("passport_no",$passport_no);
            $rs[rn] = $rn;
            $rs[passport_no] = $passport_no;
            if($rs[rn]){
            $aes = new AES($rs[rn], $inputKey, $blockSize);
            $dec=$aes->decrypt();
            $rs[rn] = $dec;
            }
            if($rs[passport_no]){
            $aes = new AES($rs[passport_no], $inputKey, $blockSize);
            $dec=$aes->decrypt();
            $rs[passport_no] = $dec;
            }
        }


    }

}
