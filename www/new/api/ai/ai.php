<?php
session_start();

/*
https://aiopen.etri.re.kr/guide_conversation.php#group06
*/
include '/home/hosting_users/irumtour/www/new/api/api_common.php';
include '/home/hosting_users/irumtour/www/new/api/bank/common.php';
header("Content-Type: text/html; charset=UTF-8");


/*
http://geniedialog.etri.re.kr:8090/EtriDialog/view/login.go;jsessionid=A295D6AE67FB3196D1A27E5F98253D3C
xSsWtGjUoWbg6m2tr3t4lDjZY706vuH5cfx67ylEAzw=
ccn121422!
*/

$accessKey ="bdaf9669-c8d8-43da-9c82-360ac72103b4";

$openApiURL = "http://aiopen.etri.re.kr:8000/Dialog";
$method = "open_dialog";
$name = "irum_weather"; //test
$access_method = "internal_data"; // internal_data,external_data


//OPEN DIALOG
if($mode=="open"){

    $openApiURL = "http://aiopen.etri.re.kr:8000/Dialog";
    $method = "open_dialog";
    $access_method = "internal_data";
 

    $request = array(
            "access_key" => $accessKey,
                    "argument" => array (
                        "method" => $method,
                        "name" => $name,
                        "access_method" => $access_method
                    )
    );
    
    $server_output = "";
    $ch = curl_init();
    $header = array(
        "Content-Type:application/json; charset=UTF-8",
    );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_URL, $openApiURL);
    curl_setopt($ch, CURLOPT_VERBOSE, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //추가함
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode ( $request) );

    $server_output = curl_exec ($ch);
    curl_close ($ch);

    $obj = json_decode($server_output);
    $uuid = $obj->return_object->uuid;
    $system_text = $obj->return_object->result->system_text;
    $state = $obj->return_object->result->state;
    $message = $obj->return_object->result->message;

    $_SESSION["uuid"]=$uuid;
    
    //checkVar("uuid",$uuid);
    //checkVar("system_text",$system_text);
    //checkVar("state",$state);
    //checkVar("message",$message);

    echo $system_text;


}//open

elseif($mode=="dialog"){ //?mode=dialog&uuid=408240833423240526

    $openApiURL = "http://aiopen.etri.re.kr:8000/Dialog";
    //$uuid = "UUID_FROM_OPEN_DAILOG";
    $method = "dialog";
    //$text = "대구 날씨 좀 알려줘";
 
    $request = array(
            "access_key" => $accessKey,
            "argument" => array (
                        "method" => $method,
                        "text" => $text,
                        "uuid" => $_SESSION["uuid"]
                    )
    );
 
    try {
            $server_output = "";
            $ch = curl_init();
            $header = array(
                "Content-Type:application/json; charset=UTF-8",
            );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_URL, $openApiURL);
            curl_setopt($ch, CURLOPT_VERBOSE, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); //추가함
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode ( $request) );
 
            $server_output = curl_exec ($ch);
            if($server_output === false) {
                echo "Error Number:".curl_errno($ch)."\n";
                echo "Error String:".curl_error($ch)."\n";
            }
 
            curl_close ($ch);
    } catch ( Exception $e ) {
        echo $e->getMessage ();
    }
 
    //print_r($server_output);

    $obj = json_decode($server_output);
    $uuid = $obj->return_object->uuid;
    $result = $obj->result;
    $system_text = $obj->return_object->result->system_text;
    $state = $obj->return_object->result->state;
    $message = $obj->return_object->result->message;
    
    //checkVar("uuid",$uuid);
    //checkVar("system_text",$system_text);
    //checkVar("state",$state);
    //checkVar("message",$message);

    if($result && !$system_text){
        Header( "HTTP/1.1 301 Moved Permanently" );
        Header( "Location: ./ai.php?mode=open" );
    }else{
        $system_text = str_replace("(chat)","<p>",$system_text);
        $system_text = str_replace("(/chat)","</p>",$system_text);
        echo $system_text;
    }

}
exit;

?>