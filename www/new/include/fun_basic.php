<?
$cfile=basename(__FILE__);
$tmpfile=realpath(__FILE__);
if(!$tmpfile) $tmpfile=__FILE__;
$inc_dir=eregi_replace($cfile,"",$tmpfile);
unset($tmpfile);
include_once($inc_dir."config.php");

reset($_GET);while(list($key,$val)=each($_GET)){$_GET[$key] =(is_array($_GET[$key]))? $_GET[$key]:secu($_GET[$key]);}reset($_GET);
reset($_POST);$chk_bid=0;while(list($key,$val)=each($_POST)){if($key=="content" || $key=="text2"){$_POST[$key] = secu($_POST[$key],1);}else{$_POST[$key] =(is_array($_POST[$key]))? $_POST[$key] : secu($_POST[$key]);}}reset($_POST);


/******************************************************
	회원 구분 리턴
*******************************************************/
function getMemberName($div_num) {

	global $MEMBER_DIV;
	global $MEMBER_DIV_VAL;

	$power_ = explode(",",$MEMBER_DIV);
	$power2_ = explode(",",$MEMBER_DIV_VAL);

	for($i=0; $i<count($power_);$i++){
		$id = $power2_[$i];
		$pinfo[$id] = $power_[$i];
	}


	$result = $pinfo[$div_num];

	return $result;

}

function getMemberNum($div) {

	$power_ = explode(",",MEMBERGROUP);
	$power2_ = explode(",",MEMBERGROUP_ID);

	for($i=0; $i<count($power_);$i++){
		$id = $power_[$i];
		$pinfo[$id] = $power2_[$i];
	}


	$result = $pinfo[$div];

	return $result;

}

/******************************************************
	요일 리턴
*******************************************************/
function getWeekName($div_num) {

	global $WEEKS;
	global $WEEKS_VAL;

	$power_ = explode(",",$WEEKS);
	$power2_ = explode(",",$WEEKS_VAL);

	for($i=0; $i<count($power_);$i++){
		$id = $power2_[$i];
		$pinfo[$id] = $power_[$i];
	}


	$result = $pinfo[$div_num];

	return $result;

}

function getWeekNum($div) {

	$power_ = explode(",",$WEEKS);
	$power2_ = explode(",",$WEEKS_VAL);

	for($i=0; $i<count($power_);$i++){
		$id = $power_[$i];
		$pinfo[$id] = $power2_[$i];
	}


	$result = $pinfo[$div];

	return $result;

}

Function getDow($date){
	$arr = explode("/",$date);
	$dow = Date("w",mktime(0,0,0,$arr[1],$arr[2],$arr[0]));

	$result = convertWeek($dow);

	return $result;
}

/******************************************************
	Alert Message
	사용법 :  javascrip Alert
*******************************************************/

function alert($msg) {

   echo("<script language=javascript>alert('$msg');</script>");
   exit;
}


/******************************************************
	history.back
*******************************************************/

function back() {

   echo("<script language=javascript>history.back(-1);</script>");
   exit;
}


/******************************************************
	에러처리
	사용법 :  error(에러 메세지)
*******************************************************/

function error($msg) {

	global $HTTP_REFERER;

	$goto_url = ($HTTP_REFERER)? "location.href='${HTTP_REFERER}'" : "history.back(-1)";

   echo("
   <script language=javascript>
		alert('$msg');
		$goto_url;
   </script>
   ");
   exit;
}


/******************************************************
	에러처리 - 에러메세지를 강조해서 보여주기
	사용법 :  accentError(에러 메세지,링크할 url)
*******************************************************/

function accentError($msg,$url='') {

	$result =($url)? "<div align=center style='padding:20px'><b><a href='$url' onFocus='blur(this)'><font color=red>$msg</font></b></a><div>":"<div align=center style='padding:20px'><b><font color=red>$msg</font></b><div>";

	return $result;
}


/******************************************************
	Pop up 메세지 출력 후 이동
	사용법 :  msggo(메세지,이동할 URL)
*******************************************************/
function msggo($msg,$url,$top=0) {
    if($top){
        echo("
           <script language=javascript>
                alert('$msg');
                parent.location.href=\"$url\";
           </script>
        ");
    }else{
        echo("
           <script language=javascript>
                alert('$msg');location.href=\"$url\";
           </script>
        ");
    }
    exit;
}


/******************************************************
	Pop up 메세지만 출력
	사용법 :  alertMsg(메세지)
*******************************************************/
function alertMsg($msg) {
   echo("
   <script language=javascript>
		alert('$msg');
   </script>
   ");
}


/******************************************************
	Page 이동
	사용법 :  redirect(이동할 URL)
*******************************************************/
function redirect($url){
   echo("
   <script language=javascript>
		location.href=\"$url\";
   </script>
   ");
   exit;
}


/******************************************************
	Page 이동 + back 금지
	사용법 :  redirect2(이동할 URL)
*******************************************************/
function redirect2($url){
   echo("
   <script language=javascript>
		location.replace(\"$url\");
   </script>
   ");
   exit;
}


/******************************************************
	문자열을 잘라주기
	사용법 :  titleCut(원래의 문자열,잘라줄 길이,답변단계-답변글은 더잘라주자,점점포함여부)
*******************************************************/
function titleCut($title,$config_title_length,$level=0,$dot=0){

	$hcount=0;
	$div=0;
	$cut=0;
	$title_len_last=$config_title_length-($level*2);

	for( $j=0; $j < $title_len_last ; $j++ ){
		$test= ord(substr( $title , $j, 1 ));
		if( ($test & 0x80) == 128 ){
		$hcount++;
		}
	}
	$div=$hcount % 2;
	if($div!=0){
		$cut=1;
	}
	$title_len=strlen($title);

	if ($title_len > $config_title_length){
		$dotDot = ($dot)? "":"<font style='letter-spacing:0px'>...</font>";
		$title_len_last=$config_title_length-($level*2);
		$title=substr($title,0,$title_len_last+$cut) . $dotDot;
	}

	$title = stripslashes($title);

	return $title;
}


function titleCut2($str, $len, $checkmb=false, $tail='...') {
    preg_match_all('/[\xEA-\xED][\x80-\xFF]{2}|./', $str, $match);

    $m    = $match[0];
    $slen = strlen($str);  // length of source string
    $tlen = strlen($tail); // length of tail string
    $mlen = count($m); // length of matched characters

    if ($slen <= $len) return $str;
    if (!$checkmb && $mlen <= $len) return $str;

    $ret   = array();
    $count = 0;

    for ($i=0; $i < $len; $i++) {
        $count += ($checkmb && strlen($m[$i]) > 1)?2:1;

        if ($count + $tlen > $len) break;
        $ret[] = $m[$i];
    }

    return join('', $ret).$tail;
}


/******************************************************
	자동링크 (메일과 url에 링크를 걸어주기)
	사용법 :  autoLink(글내용)
*******************************************************/
function autoLink($content)
{
    $content = eregi_replace("([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])", "<a class=soft href=\"\\1://\\2\\3\" target=\"_blank\"target=\"_blank\">\\1://\\2\\3</a>", $content);
	$content = eregi_replace("(([a-z0-9_]|\\-|\\.)+@([^[:space:]]*)([[:alnum:]-]))", "<a class=soft href=\"mailto:\\1\" target=\"_blank\">\\1</a>", $content);

	return($content);
}


/******************************************************
	문자열 암호화
	사용법 :  enStr(암호화 문자열)
*******************************************************/
function enStr($str){
	$encode_str=crypt($str);
	return $encode_str;
}



/******************************************************
	문자열 암호화 해독
	사용법 :  deStr(비교할 문자열, 암호화된 문자열)
	일치하면 $decode 0을 반환, 일치하지 않으면 0이 아닌 값을 반환
*******************************************************/
function deStr($decode_str,$encode_str){
	$str=crypt($decode_str,$encode_str);
	$result=strcmp($encode_str,$str);
	return $result;
}


/******************************************************
	문자열 암호화 (한글가능)
*******************************************************/
//define('SALT', 'whateveryouwant');


function encrypt($text,$SALT){
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function decrypt($text,$SALT){
	$text = str_replace(" ","+",$text);
	return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}


/******************************************************
	변수 정리
	사용법 :  enVars(변수)
*******************************************************/
function enVars($value) {
   $value = addslashes(trim($value));
   $value = htmlspecialchars($value);
   //$value = str_replace('<','&lt;',$value);
   //$value = str_replace("'","´",$value);
   $value = strip_tags($value);
   return $value;
}


/******************************************************
	변수정리한 경우 다시 풀어주기(출력시)
	사용법 :  deVars(변수)
*******************************************************/
function deVars($value,$tag='text'){
   $value = stripslashes(stripslashes(trim($value)));

   if($tag == "html"){

	$value = str_replace('&amp;','&',$value);
	$value = str_replace('&quot;','"',$value);
	$value = str_replace('&#039;',"'",$value);
	$value = str_replace('&lt;','<',$value);
	$value = str_replace('&gt;','>',$value);
	$value = stripslashes($value);

   }else{

	$value = str_replace('&','&amp;',$value);
	$value = str_replace('"','&quot;',$value);
	$value = str_replace("'",'&#039;',$value);
	$value = str_replace('<','&lt;',$value);
	$value = str_replace('>','&gt;',$value);
	$value = stripslashes($value);

	$value = nl2br($value);
	$value = nl2br($value);
	$value= autoLink($value);

   }

   return $value;
}


/******************************************************
	결과값을 html, text에 따라 다르게 출력
	사용법 :  checkHtml(변수,html여부)
	html여부 : html, text
*******************************************************/
function checkHtml($var,$tag){
	$result = ($tag=="html")? deVars($var,$tag) : nl2br(deVars($var,$tag));
	return($result);
}



/******************************************************
	변수정리한 경우 다시 풀어주기(출력시)
	사용법 :  option_int(변수)
*******************************************************/
function option_int($start,$end,$plus,$option_name=0,$len=0){

	for($i=$start; $i <= $end; $i+=$plus){

		$j = ($len)? setInt2Str($i) : $i;

		$selected=($option_name==$i)? "selected='selected'":"";
		$result .="<option value='$j' $selected>$j</option>\n\t\t\t";
	}
	return($result);
}

function option_int3($start,$end,$plus,$option_name,$len=0){

	for($i=$start; $i <= $end; $i+=$plus){

		$j = ($len)? setInt2Str($i) : $i;

		$selected=($option_name==$i)? "selected='selected'":"";
		$result .="<option value='$j' $selected>".number_format($j)."</option>\n\t\t\t";
	}
	return($result);
}


/******************************************************
	변수정리한 경우 다시 풀어주기(출력시)
	사용법 :  option_int2(변수)
*******************************************************/
function option_int2($start,$end,$plus,$option_name){

	for($i=$start; $i >= $end; $i-=$plus){
		$selected=($option_name==$i)? "selected='selected'":"";
		$result .="<option value='$i' $selected>$i</option>\n\t\t\t";
	}
	return($result);
}


/******************************************************
	Select Tag 만들어 주기
	사용법 :  option_str(text,value,selected 속성을 줄 value)
*******************************************************/
function option_str($data1,$data2,$option_name='',$gubun=','){

	$data1=explode($gubun,$data1);
	$data2=explode($gubun,$data2);
	for($i=0; $i < count($data1); $i++){$dataA[$i]=trim($data1[$i]);}
	for($i=0; $i < count($data2); $i++){$dataB[$i]=trim($data2[$i]);}
	for($i=0; $i < count($data2); $i++){
		$selected=(str_replace("'","",stripslashes($option_name))==str_replace("'","",stripslashes($dataB[$i])))? "selected='selected'":"";
		$result .="<option value=\"$dataB[$i]\" $selected>$dataA[$i]</option>\n\t\t\t";
	}

	return($result);
}


/******************************************************
	Radio Tag 만들어 주기
	사용법 :  radio(text,value,selected 속성을 줄 value,컨트롤 이름)
*******************************************************/
function radio($data1,$data2,$option_name,$name,$function=""){
	$data1=explode(",",$data1);
	$data2=explode(",",$data2);
	$name2 = $name . "_tmp";

	 $fn = ($function)? $function . "(this.value)":"";

	for($i=0; $i < count($data1); $i++){$dataA[$i]=$data1[$i];}
	for($i=0; $i < count($data2); $i++){$dataB[$i]=$data2[$i];}
	$result = "<input type='hidden' id='$name2' value='$option_name'>";
	for($i=0; $i < count($data2); $i++){
		$j = $i+1;
		$checked=($option_name==$dataB[$i])? "checked='checked'":"";
		$result .="<label><input type=\"radio\" name=\"$name\" class=\"radio_$j $name\" value=\"$dataB[$i]\" $checked onclick=\"document.getElementById('$name2').value=this.value;$fn\" /> $dataA[$i]</label>\n\t\t\t";
	}
	return($result);
}



/******************************************************
	Checkbox Tag 만들어 주기
	사용법 :  checkbox(text,value,DB에서 가져온 값,컨트롤 이름,한행에 표시할 갯수)
*******************************************************/
function checkbox($data1,$data2,$db_data,$name,$rows=3){
	$data1=explode(",",$data1);
	$data2=explode(",",$data2);
	for($i=0; $i < count($data1); $i++){$dataA[$i]=$data1[$i];}
	for($i=0; $i < count($data2); $i++){$dataB[$i]=$data2[$i];}
	$check=explode(",",$db_data);
	$j=0;
	$name2 = $name . "_tmp";
	$cnt = count($check);
	$result = "<input type='hidden' name='$name2' id='$name2' value='$cnt'>";
	for($i=0; $i < count($data2); $i++){
		if($dataB[$i]==$check[$j]) {$checked="checked";$j++;}else $checked="";
		$result .="<label><input type=\"checkbox\" value=\"$dataB[$i]\" name=\"${name}[]\" class=\"${name}\" title=\"$dataA[$i]\" $checked ronfocus=\"blur(this)\" onclick=\"if(this.checked){document.getElementById('$name2').value++}else{document.getElementById('$name2').value--}\" > $dataA[$i]</label>&nbsp;&nbsp;";
	}

	return($result);
}


/******************************************************
	입력을 받은 경우 이전 페이지 체크하기
	사용법 :  checkRef(정상적인 URL,정상적인 URL2)
*******************************************************/
function checkRef($url,$url2=''){

	global $HTTP_REFERER;

	$ref = substr($HTTP_REFERER,0,strlen($url));
	$ref2 = ($url2)? substr($HTTP_REFERER,0,strlen($url2)) : '' ;

	if($url != $ref && $url2 != $ref2){
		echo("비정상적인 접속입니다.");

		/*

		// test

		checkVar('url',$url);
		checkVar('url2',$url2);
		checkVar('ref',$ref);
		checkVar('ref2',$ref2);

		exit;

		*/
	}
}


/******************************************************
	해당월의 일수 구하기
	사용법 :  lastDay(년,월)
*******************************************************/
function lastDay($year,$month){
	$day=1;
	while(checkdate($month,$day,$year)){
		$day++;
	}
	$result=$day-1;

	return $result;
}


/******************************************************
	date 함수 date("w")의 숫자를 문자로 전환
	사용법 :  week(요일을 의미하는 숫자)
*******************************************************/
function convertWeek($int){
	switch($int){
		case 0:$result = "일";	break;
		case 1:	$result = "월";	break;
		case 2:	$result = "화";	break;
		case 3:	$result = "수";	break;
		case 4:	$result = "목";	break;
		case 5:	$result = "금";	break;
		case 6:	$result = "토";	break;
	}
	return $result;
}


/******************************************************
	변수값을 메일로 확인
	사용법 :  checkVarMail('확인해야할 변수',받을 이메일)
*******************************************************/
function checkVarMail($var,$email){
	$result = "변수 값 : " . $var;
	mail($email,"변수 확인 메일",$result);
}



/******************************************************
	변수값 확인
	사용법 :  checkVar('변수이름,확인해야할 변수',주석처리여부)
				주석처리 여부 : 1 주석처리, 0 화면 출력
*******************************************************/
function checkVar($varName,$var,$note=0){

	if($note){
		$result = "\n<!-- $varName : " . nl2br($var) . " -->";
	}else{
		$result = "\n<br> <b><font color='#FF6600'>$varName</font></b> : <font color='#666699'>" . nl2br($var) . "</font>";
	}

	echo $result;
}



/******************************************************
	benchmarking
	사용법 :	 getRunTime(시작시간)
		시작부분	$time_check_start = getRunTime();
		끝부분		getRunTime($time_check_start);
*******************************************************/
function getRunTime($start=''){

	$sec = explode(" ", microtime());

	$time = (double)$sec[0] + (double)$sec[1];

	if($start){
		$result = $time - (double)$start ;
	}else{
		$result = $time;
	}


	return $result;
}


/******************************************************
	한자리숫자를 두자리로(예: 1 -> 01) 만드는 함수
	사용법 :	 int_format(숫자)
*******************************************************/
function setInt2Str($no){
	$result = (strlen($no)<2)? "0" . $no : $no;
	return $result;
}
function num2($no){
	$result = (strlen($no)<2)? "0" . $no : $no;
	return $result;
}


/******************************************************
	변수의 길이가 길면 자르는 함수
	사용법 :	 chkLen(문자열,체크할 길이)
*******************************************************/
function chkLen($str,$length){

	$result = (strlen($str)<$length)? $str : titleCut($str,$length);

	return $result;
}


/******************************************************
	이미지의 가로세로 길이를 fix
*******************************************************/
function fixImage($photo,$size){
	$photo = trim($photo);
	$pic_info=@getimagesize($photo);	//파일의 정보  - array[0]:너비 array[1]:높이 array[2]:타입(1=gif 2=jpg 3=png)  array[3]: 너비/높이 문자열
	$wsize = ($pic_info[0]> $size)? $size : $pic_info[0];
	$hsize = ($pic_info[1]> $size)? $size : $pic_info[1];
	$result = ($pic_info[0]>$pic_info[1])? "width=$wsize":"height=$hsize";

	if(!$pic_info[0] && !$pic_info[1]) $result = "";

	return $result;
}


/******************************************************
	SQL Injection 방지, 로그인 관련 부분에 사용
	사용법 :	 antiInjection(체크할 변수명)
*******************************************************/
function antiInjection($string){

	$string = str_replace("--","",$string);
	$string = str_replace(";","",$string);
	$string = str_replace(" or ","",$string);
	$string = str_replace("\'","",$string);

	return $string;
}

function antij($string){

	$string = addslashes(Trim($string));
	$string = addslashes($string);
	$string = str_replace("--","",$string);
	$string = str_replace(";","",$string);
	$string = str_replace(" or ","",$string);
	$string = str_replace("\'","",$string);

	return $string;
}


function antiInjectionSimple($string){

	$string = str_replace("--","",$string);
	$string = str_replace("\'","",$string);

	return $string;
}


function secu($str,$tag=0){ //$tag:1 태그 허용

	global $key;
	global $_SERVER;

	$bit=0;
	$fn =$_SERVER['SCRIPT_FILENAME'];
	if(strstr($fn,"/bkoff/tour")) $bit=1;
    if(strstr($fn,"/bkoff/pop_contents")) $bit=1;
	elseif(strstr($fn,"/bkoff/bbs")) $bit=1;
	elseif(strstr($fn,"/bkoff/basic")) $bit=1;
	elseif(strstr($fn,"/bkoff/cmp")) $bit=1;

	if($bit){
		$str = trim($str);	
	}else{

		$str = trim($str);

		if(!$tag){
			$str = strip_tags($str);
		}

		//웹어플리케이션 취약점 제거
		$str = str_replace("|","",$str);

        if(!$bit || $key!="content"){
		  $str = str_replace(";","",$str);
        }

		$str = str_replace(" type","",$str);
		$str = str_replace(" cat","",$str);
		$str = str_replace(" dir","",$str);
		$str = str_replace(" ls","",$str);
		$str = str_replace(" ipconfig","",$str);
		$str = str_replace(" ifconfig","",$str);
		$str = str_replace(" set","",$str);
		$str = str_replace(" netstat","",$str);

		$str = str_replace("type ","",$str);
		$str = str_replace("cat ","",$str);
		$str = str_replace("dir ","",$str);
		$str = str_replace("ls ","",$str);
		$str = str_replace("ipconfig ","",$str);
		$str = str_replace("ifconfig ","",$str);
		$str = str_replace("set ","",$str);
		$str = str_replace("netstat ","",$str);

		$str = str_replace(" TYPE","",$str);
		$str = str_replace(" CAT","",$str);
		$str = str_replace(" DIR","",$str);
		$str = str_replace(" LS","",$str);
		$str = str_replace(" IPCONFIG","",$str);
		$str = str_replace(" IFCONFIG","",$str);
		$str = str_replace(" SET","",$str);
		$str = str_replace(" NETSTAT","",$str);

		$str = str_replace("TYPE ","",$str);
		$str = str_replace("CAT ","",$str);
		$str = str_replace("DIR ","",$str);
		$str = str_replace("LS ","",$str);
		$str = str_replace("IPCONFIG ","",$str);
		$str = str_replace("IFCONFIG ","",$str);
		$str = str_replace("SET ","",$str);
		$str = str_replace("NETSTAT ","",$str);	

	}

    //기타
    $str = str_replace("procedure ","",$str);
    $str = str_replace("--","",$str);
    if(!$bit || $key!="content"){
        $str = str_replace(";","",$str);
    }
    $str = str_replace(" or ","",$str);
    $str = str_replace(" OR ","",$str);
    $str = str_replace("\'","′",$str);
    $str = str_replace(" union ","",$str);
    $str = str_replace(" UNION ","",$str);
    
    if(strstr(strtolower($str),"alert(")) $str = str_replace("alert(","",$str);

    //DB
    $str = (strstr($str,'IF((ORD(MID((SELECT'))? "":$str;
    $str = (strstr($str,'/etc/pass'))? "":$str;


    //추가
    $str = (strstr($str,'/etc/pass'))? "":$str;
    $str = (strstr($str,'/String.fromCharCode'))? "":$str;
    $str = (strstr($str,'alert(String'))? "":$str;
    $str = (strstr($str,'String.fromCharCode'))? "":$str;


    //SQL Injection 방어
    $str=preg_replace("/\s{1,}1\=(.*)+/","",$str); // 공백이후 1=1이 있을 경우 제거
    $str=preg_replace("/\s{1,}(or|and|null|where|limit|curl|chmod|wget)/i","",$str); // 공백이후 or, and 등이 있을 경우 제거


    //XXE방어
    if(preg_match("/<!DOCTYPE/i", $str)){
        $str="";
    }

	return $str;
}



/******************************************************
   이미지 리사이즈
   $destination : 이미지가 저장될 경로
   $departure : 원본 이미지 경로
   $size : _getimagesize() 의 return 값을 넣을 것
   $quality : JPG 퀄리티
   $ratio : 비율 강제설정
*******************************************************/
function resize_image($destination, $departure, $size, $quality='80', $ratio='false'){

    if($size[2] == 1)    //-- GIF
        $src = imageCreateFromGIF($departure);
    elseif($size[2] == 2) //-- JPG
        $src = imageCreateFromJPEG($departure);
    else    //-- $size[2] == 3, PNG
        $src = imageCreateFromPNG($departure);
	    $dst = imagecreatetruecolor($size['w'], $size['h']);


    $dstX = 0;
    $dstY = 0;
    $dstW = $size['w'];
    $dstH = $size['h'];

    if($ratio != 'false' && $size['w']/$size['h'] <= $size[0]/$size[1]){
        $srcX = ceil(($size[0]-$size[1]*($size['w']/$size['h']))/2);
        $srcY = 0;
        $srcW = $size[1]*($size['w']/$size['h']);
        $srcH = $size[1];
    }elseif($ratio != 'false'){
        $srcX = 0;
        $srcY = ceil(($size[1]-$size[0]*($size['h']/$size['w']))/2);
        $srcW = $size[0];
        $srcH = $size[0]*($size['h']/$size['w']);
    }else{
        $srcX = 0;
        $srcY = 0;
        $srcW = $size[0];
        $srcH = $size[1];
    }



    @imagecopyresampled($dst, $src, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
    @imagejpeg($dst, $destination, $quality);
    @imagedestroy($src);
    @imagedestroy($dst);

    return TRUE;
}

// $img : 원본이미지
// $m : 목표크기 pixel
// $ratio : 비율 강제설정
function _getimagesize($img, $w,$h){
    $v = @getImageSize($img);
	return array_merge($v, array("w"=>$w, "h"=>$h));
}


/******************************************************
변수 값 체크
*******************************************************/
function chkVars($div="post"){

	global $_POST;
	global $_GET;

	$VARS = ($div=="post")? $_POST : $_GET;

	while(list($key,$val)=each($VARS)){
		checkVar($key,$val);
	}
	exit;
}


//UniqNo 생성
function getUniqNo(){

	global  $info;
	$dbo = new MiniDB($info);

	$sql = "select max(id_no)+1 as code from uniq_no";
	$dbo->query($sql);
	$rs = $dbo->next_record();

	if(!$rs[code]) $rs[code]=1;

	$sql = "update uniq_no set id_no='$rs[code]' ";
	if($dbo->query($sql)){
		$str =  $rs[code];
	}else{
		getUniqNo();
	}

	return $str;
}


/*form tag 생성*/
function html_input($name,$size,$maxlength,$class="box",$function="",$title="",$tabindex=''){

	global $rs;

	$fn = ($function)? "onchange=\"" . $function . "(this.value)\"" : "";
	$ti = ($tabindex)? "tabindex=\"$tabindex\"" : "";

	$readonly = (strstr($class,"readonly"))?"readonly='readonly'":"";
	$type = (strstr($name,"pwd"))? "password":"text";
	$value = (strstr($name,"pwd"))? "":$rs[$name];
	$title_code= ($title)?"title=\"$title\" ":"";

	$tag = "<input type=\"$type\" name=\"$name\" id=\"$name\" value=\"$value\" size=\"$size\" maxlength=\"$maxlength\" class=\"$class\" $fn $ti $readonly $title_code />";
	return $tag;
}

function html_input2($name,$size,$maxlength,$class="box",$function="",$title="",$tabindex=''){

	global $rs;

	$fn = ($function)? "onchange=\"" . $function . "(this.value)\"" : "";
	$ti = ($tabindex)? "tabindex=\"$tabindex\"" : "";

	$readonly = (strstr($class,"readonly"))?"readonly='readonly'":"";
	$type =  "text";
	$value =  $rs[$name];
	$title_code= ($title)?"title=\"$title\" ":"";

	$tag = "<input type=\"$type\" name=\"$name\" id=\"$name\" value=\"$value\" size=\"$size\" maxlength=\"$maxlength\" class=\"$class\" $fn $ti $readonly $title_code />";
	return $tag;
}



function html_textarea($name,$cols,$rows,$class="box"){

	global $rs;

	if(!$cols) $width = "style='width:100%'";

	$tag = "<textarea  name=\"$name\" id=\"$name\" cols=\"$cols\" $width rows=\"$rows\" class=\"$class\">$rs[$name]</textarea>";
	return $tag;
}


/*bkoff/site.php등에서 사용*/
function chk_opt_text($str){
	$result=trim($str);
	while(strstr($result,",,")){
		$result = str_replace(",,",",",$result);
	}

	while(strstr($result," ,")){
		$result = str_replace(" ,",",",$result);
	}

	while(strstr($result,", ")){
		$result = str_replace(", ",",",$result);
	}

	if(substr($result,0,1)==",") $result =substr($result,1);
	if(substr($result,-1)==",") $result =substr($result,0,-1);

	return $result;
}



/*가격 처리*/
function price_format($price){

	$result = ceil(str_replace(",","",$price));

	return $result;
}

/*숫자인지 여부*/
Function chk_num($val){
  $bit=1;

  For($i=0; $i<strlen($val);$i++){
	$key = substr($val,$i,1);
    if(!preg_match("/[0-9]/", $key)){
		$bit=0;
		break;
	}
  }
}

/*캐릭터셋변경*/
Function charset($str){
	$result = iconv("euc-kr","utf-8",$str);
	Return $result;
}

Function charset2($str){
	$result = iconv("utf-8","euc-kr",$str);
	Return $result;
}

/*평균구하기*/
Function get_avg($origin,$val,$round=0){

	$result = 100 - (($val / $origin)*100);
	$result = Round($result,$round);

	Return $result;

}

/*가격 계산*/
function get_sum($oid){

	Global  $info;
	Global  $adult;
	$dbo = new MiniDB($info);

	If($adult){
		$sql = "update ez_order set adult=$adult,price=(price_origin*$adult)+tour_option_price  where oid=$oid";
		$dbo->query($sql);
	}

	$sql = "select  (price_origin*adult)+tour_option_price as total from ez_order where oid=$oid";
	$dbo->query($sql);
	$rs = $dbo->next_record();

	$total = $rs[total];

	return $total;
}

Function get_status_color($str){
	switch($str){
		Case "접수중": $color="#cc66ff"; break;
		Case "입금요청": $color="#009900"; break;
		Case "결제완료": $color="#336699"; break;
		Case "결제오류": $color="#ff0000"; break;
		Case "행사완료": $color="#8e8e8e"; break;
		Case "인보이스": $color="#cc9900"; break;
	}

	$result= "<span style='color:$color'>$str</span>";
	Return $result;
}

Function get_payassort($str){
	If($str=="card") $result = "신용카드 결제";
	elseIf($str=="bank") $result = "무통장입금";
	Return $result;
}

Function get_ctg_name($code1){

	switch($code1){
		Case "2":$cname="southasia";break;
		Case "3":$cname="japan";break;
		Case "4":$cname="china";break;
		Case "5":$cname="abroad";break;
		Case "6":$cname="busan";break;
	}

	Return $cname;

}

function nf($int){
	return number_format($int);
}

function rnf($int){
	return eregi_replace("[^0-9]", "", $int);
}
function rnf2($str,$int = false){
   if( empty($str)){ return 0; }       
   $minus = false; // 음수를 판별 
   if( preg_match("/^[-]/",$str) > 0){ $minus = true; }
   $str = preg_replace("/[^0-9]*/s", "", $str);
   if( empty($str) || $str === '0'){ return 0; }
   if($minus === true){ $str = '-'.$str; } // 음수표현
   if( !empty($str) && $int === true ){ $str*=1;  } // 데이터 정수일경우
   return $str;
}

function str_format($str){

	$str = stripslashes(trim($str));
	$str = strip_tags($str);
	$str = str_replace("\"","",$str);
	$str = str_replace("'","",$str);

	return $str;
}

function chk_limit($date,$month){

   GLOBAL $REMOTE_ADDR;

   $date=str_replace("JAN","01",$date);
   $date=str_replace("FEB","02",$date);
   $date=str_replace("MAR","03",$date);
   $date=str_replace("APR","04",$date);
   $date=str_replace("MAY","05",$date);
   $date=str_replace("JUN","06",$date);
   $date=str_replace("JUL","07",$date);
   $date=str_replace("AUG","08",$date);
   $date=str_replace("SEP","09",$date);
   $date=str_replace("OCT","10",$date);
   $date=str_replace("NOV","11",$date);
   $date=str_replace("DEC","12",$date);

   $date=($date);

   $str1 = substr($date,0,2);
   $str3 = substr($date,-4);
   $str2 = substr($date,2,-4);

   $basic = date("Y/m/d",strtotime(date("Y/m/d")." +$month month"));

   if(strlen($date)>=8){
	   $str = "${str3}/${str2}/${str1}";
   }else{
		$str=$date;
   }

   $color =($str<$basic)? "red" : "black";

	return $color;
}


/*링크에서 자사 도메인 제거 bkoff/basic/hot.php*/
function set_tour_link($link){
	global $_SERVER;
	global $SITE_ROOT_PATH;

	$domain = "irumtour.net";
    $domain2 = "irumplace.com";

	if(strstr($link,$domain) || strstr($link,$domain2)){
		$arr = explode("/",$link);
		$c= count($arr)-1;
		$slink = "/${SITE_ROOT_PATH}/" . $arr[$c];
	}else{
		$slink = $link;
	}

	return $slink;
}

/*주민등록번호 검사*/
function rn_check($rn) {
  $resno = rnf($rn);

  // 형태 검사: 총 13자리의 숫자, 7번째는 1..4의 값을 가짐
  if (!ereg('^[[:digit:]]{6}[1-4][[:digit:]]{6}$', $resno))
    return false;

  // 날짜 유효성 검사
  $birthYear = ('2' >= $resno[6]) ? '19' : '20';
  $birthYear += substr($resno, 0, 2);
  $birthMonth = substr($resno, 2, 2);
  $birthDate = substr($resno, 4, 2);
  if (!checkdate($birthMonth, $birthDate, $birthYear))
    return false;

  // Checksum 코드의 유효성 검사
  for ($i = 0; $i < 13; $i++) $buf[$i] = (int) $resno[$i];
  $multipliers = array(2,3,4,5,6,7,8,9,2,3,4,5);
  for ($i = $sum = 0; $i < 12; $i++) $sum += ($buf[$i] *= $multipliers[$i]);
  if ((11 - ($sum % 11)) % 10 != $buf[12])
    return false;

  return true;
}


function post_request($url, $data='', $referer='')
 {
  // Convert the data array into URL Parameters like a=b&foo=bar etc.
  if(!empty($data))
  {
   $data = http_build_query($data);
  }

  // parse the given URL
  $url = parse_url($url);

  if ($url['scheme'] != 'http')
  {
   die('Error: Only HTTP request are supported !');
  }

  // extract host and path:
  $host = $url['host'];
  $path = $url['path'];

  // open a socket connection on port 80 - timeout: 30 sec
  if($fp = fsockopen($host, 80, $errno, $errstr, 30))
  {
   // send the request headers:
   fputs($fp, "POST $path HTTP/1.1\r\n");
   fputs($fp, "Host: $host\r\n");

   if ($referer != '')
   {
    fputs($fp, "Referer: $referer\r\n");
   }

   fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
   fputs($fp, "Content-length: ". strlen($data) ."\r\n");
   fputs($fp, "Connection: close\r\n\r\n");
   fputs($fp, $data);

   $result = '';

   while(!feof($fp))
   {
     // receive the results of the request
     $result .= fgets($fp, 128);
    }
   }
   else
   {
    return array(
      'status' => 'err',
      'error' => "$errstr ($errno)"
    );
   }
   fclose($fp);

  // split the result header from the content
  $result = explode("\r\n\r\n", $result, 2);

  $header = '';
  if(isset($result[0]))
  {
   $header = $result[0];
  }
  $content = '';
  if(isset($result[1]))
  {
   $content = $result[1];
  }

 // return as structured array:
  return array(
   'status' => 'ok',
   'header' => $header,
   'content' => $content
  );
 }
 // Submit those variables to the server

//단축 URL
function short_url($long_url){

	//https://developers.naver.com/apps/#/myapps/HAqQbgIXrPZjeVIWXjdR/overview
	$client_id = "HAqQbgIXrPZjeVIWXjdR";
	$client_secret = "Td6HU4TdB0";
	$encText = urlencode("$long_url");
	$postvars = "url=".$encText;
	$url = "https://openapi.naver.com/v1/util/shorturl";
	$is_post = true;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, $is_post);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch,CURLOPT_POSTFIELDS, $postvars);
	$headers = array();
	$headers[] = "X-Naver-Client-Id: ".$client_id;
	$headers[] = "X-Naver-Client-Secret: ".$client_secret;
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec ($ch);
	$status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	$result= "status_code:".$status_code."<br>";
	curl_close ($ch);
	if($status_code == 200) {
		$result.= $response;
	} else {
		$result.= "Error 내용:".$response;
	}

	$json = json_decode($response, true);

	return $json["result"]["url"];

}


/*5.4이하의 json 한글깨짐*/
function han ($s){ return reset(json_decode('{"s":"'.$s.'"}')); } 
function to_han ($str) { return preg_replace('/(\\\u[a-f0-9]+)+/e','han("$0")',$str); } to_han (json_encode($arrJson));


function get_bank_ico($bank){

    if($bank=="신한은행")       $result="shinhan";
    elseif($bank=="국민은행")   $result="kb";
    elseif($bank=="우리은행")   $result="woori";
    elseif($bank=="농협은행")   $result="nh";
    elseif($bank=="기업은행")   $result="ibk";
    elseif($bank=="하나은행")   $result="hana";
    elseif($bank=="씨티은행")   $result="city";
    elseif($bank=="sc제일은행") $result="sc";

    return $result;

}



function get_bank_code($bank){

    if($bank=="산업은행") $code="0002";
    elseif($bank=="기업은행") $code="0003";
    elseif($bank=="국민은행") $code="0004";
    elseif($bank=="수협은행") $code="0007";
    elseif($bank=="농협은행") $code="0011";
    elseif($bank=="우리은행") $code="0020";
    elseif($bank=="sc제일은행") $code="0023";
    elseif($bank=="대구은행") $code="0031";
    elseif($bank=="부산은행") $code="0032";
    elseif($bank=="광주은행") $code="0034";
    elseif($bank=="제주은행") $code="0035";
    elseif($bank=="전북은행") $code="0037";
    elseif($bank=="경남은행") $code="0039";
    elseif($bank=="새마을금고") $code="0045";
    elseif($bank=="신협은행") $code="0048";
    elseif($bank=="우체국") $code="0071";
    elseif($bank=="하나은행") $code="0081";
    elseif($bank=="신한은행") $code="0088";
    elseif($bank=="씨티은행") $code="0027";

    return $code;
}

function get_bank_code_popbill($str_bank){
    global $BANK_NAMES;
    global $BANK_NAMES_VAL;

    $result="";
    $arr = explode(",",$BANK_NAMES);
    $arr_val = explode(",",$BANK_NAMES_VAL);
    foreach ($arr as $key => $value) {
        if($str_bank==$value){$result=$arr_val[$key];break;}
    }
    return $result;

}


/*아이디 표기*/
function user_id($id){
    $result =(strstr($id,"."))? substr(strstr($id,"."),1) : $id;
    return $result;
}
function save_id($id,$cp_id=''){
    if($id && $cp_id && !strstr($id,$cp_id.".")) $id = $cp_id . "." . $id;
    return $id;
}


?>