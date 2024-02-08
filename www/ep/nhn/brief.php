<?
session_start();
header("Content-Type: text/html; charset=UTF-8");


$path_root = str_replace("/www","",$_SERVER["DOCUMENT_ROOT"]);


#### include
include_once("$path_root/www/new/include/config.php");
include_once("$path_root/www/new/include/fun_basic.php");
include_once("$path_root/www/new/include/fun_order.php");
include_once("$path_root/www/new/include/fun_tour.php");
include_once("$path_root/www/new/include/vars.php");


#### DB Connent
include_once("$path_root/info/info_dbconn.php");
include_once("$path_root/www/new/lib/class.$database.php");
$dbo = new MiniDB($info);
$dbo2 = new MiniDB($info);


$https = ($_SERVER['HTTPS']=='on')? "https":"http";
$DOMAIN = $https . "://" . $_SERVER['HTTP_HOST'];



#### operation
$dir_ep = $_SERVER['DOCUMENT_ROOT']."/ep/nhn";
$filename= $dir_ep."/brief.txt";
$today  = date("Y/m/d");

$sql = "
    select
        a.*
    from ez_tour as a
    where
        bit=1 and sale_group='T' 
        and date_edit='$today'
    order by subject asc
";
$dbo->query($sql);

$config = "id" .  "\t";
$config .= "title" .  "\t";
$config .= "price_pc" .  "\t";
$config .= "link" .  "\t";
$config .= "image_link" .  "\t";
$config .= "category_name1" .  "\t";
$config .= "category_name2" .  "\t";
$config .= "shipping" .  "\t";
$config .= "point" .  "\t";
$config .= "class" .  "\t";
$config .= "update_time". "\n";

while($rs=$dbo->next_record()){

	$date = str_replace("/","-",$rs[date_edit]). " " .$rs[date_edit2];
	$rs["subject"] = str_replace("\"","",$rs["subject"]);
	$rs["subject"] = str_replace("'","",$rs["subject"]);

	$ctg_ = explode("-",$rs[category1]);
	$ctg = get_category_name($rs[category1]);
	$ctg2  =explode(">",$ctg);
	$code1 = $ctg2[0];
	$code2 = $ctg2[1];
	$code3 = $ctg2[2];

	$config .= $rs["tid"] .  "\t";
	$config .= $rs["subject"] .  "\t";
	$config .= $rs["price_adult"] .  "\t";
    $config .= $DOMAIN . "/renew/detailview.html?tid=". $rs["tid"] .  "\t";
    $config .= $rs[main_photo] . "\t";
	$config .= $code1 .  "\t";
	$config .= $code2 .  "\t";
	$config .= "0" . "\t";
	$config .= "\t";
	$config .= "I\t";
	$config .= $date .  "\n";
}


$fp=fopen($filename, "w");	//파일 쓰기모드로 열기, 파일이 있다면 overwirte
fwrite($fp,$config);
fclose($fp);


//echo $config;
?>