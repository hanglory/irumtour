<?
function color($str){
	switch($str){
		case "허용":$color='green';break;
		case "차단":$color='gray';break;
	}

	return "<span style='color:$color'>$str</span>";

}

function chk_power($sess,$str){

	$url = (strstr(SELF,'view_'))? "../cmp/basic2.php":"../cmp/basic.php";

	if(!strstr($sess,$str)){
		redirect2($url);
		exit;
	}
}

function get_m_color($int){
	if($int<0) $result = "<span style='color:red'>$int</span>";
	else $result = "<span style='color:green'>$int</span>";

	return $result;
}


function full_date($date){
	$d = date("m/d",strtotime($date));
	$w =convertWeek(date("w",strtotime($date)));

	$result = "($d)<br/>$w";

	return $result;

}
?>