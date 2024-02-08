<?
header("Content-Type: text/html; charset=UTF-8");

/*타사이트에서 이용 금지*/
$ref_dir= str_replace(basename($_SERVER["HTTP_REFERER"]),"",$_SERVER["HTTP_REFERER"]);
if(!strstr($ref_dir,$_SERVER["HTTP_HOST"])){
	Header( "HTTP/1.1 301 Moved Permanently" );
	Header( "Location: /index.html" );
}


$arr = explode("/",$_GET[file]);
$filename = "../${_GET[dir]}/${_GET[file]}";
$filename_ = $arr[count($arr)-1];
$origin=($_GET[orgin_file_name])?$_GET[orgin_file_name]:$filename_;

//if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){echo $filename;exit;}


if(
	strstr($filename,".php") ||
	strstr($filename,".inc") ||
	strstr($filename,".exe") ||
	strstr($filename,".js") ||
	strstr($filename,".html")
){
	echo "<script>alert('잘못된 요청입니다.')</script>";exit;
}

if(!strstr($_GET[dir],"public")){
	echo "<script>alert('잘못된 요청입니다.')</script>";exit;
}


header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment;filename=$origin");

$fp = fopen($filename,"r");
$data = fread($fp,filesize($filename));
echo $data;
fclose($fp);
?>