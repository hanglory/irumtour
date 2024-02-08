<?
/*여권 ocr*/
function ocr($filename){

	global $DOMAIN;

	$post   = array(
		'mode' => 'ocr',
		'filename' => $filename
		); 
	$url = $DOMAIN ."/new/api/ocr/ocr.php";

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
	$response = curl_exec($ch);
	$error_msg = curl_error($ch);
	curl_close($ch);
	$json = json_decode($response,true);

  // if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
  //   checkVar("url",$url);
  //   checkVar("response",$response);
  //   exit;
  // }

	if($json[success]=="true"){
		return $json;
	}	
}
?>