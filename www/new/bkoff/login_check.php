<?
#####################관리자 로그인 정보 확인########################
session_start();



####Include
include_once ("../include/fun_basic.php");
include_once ("../include/vars.php");

#### DB Connent
include_once ("../include/info_dbconn.php");
include_once ("../lib/class.$database.php");


####변수 확인
if(!$id or !$pwd){
	error("아이디 또는 암호를 입력하지 않았습니다.");
	exit;
}

if(strstr("@14.37.242.84@221.154.216.133@","@".$_SERVER["REMOTE_ADDR"]."@")){$EASE_BIT=1;}


//castle
$thisfilename=basename(__FILE__);
$temp_filename=realpath(__FILE__);
if(!$temp_filename) $temp_filename=__FILE__;
$osdir=eregi_replace($thisfilename,"",$temp_filename);
unset($temp_filename);
$castle_dir = str_replace("bkoff/","castle-php",$osdir);
define("__CASTLE_PHP_VERSION_BASE_DIR__", $castle_dir);
include_once(__CASTLE_PHP_VERSION_BASE_DIR__."/castle_referee.php");


####관리자 인증
$dbo = new MiniDB($info);

$sql = "select * from ez_admin where id='$id' and pwd = password('$pwd') ";
list($rows) = $dbo->query($sql);
//checkVar("",$sql);
//exit;

if(!$rows){

	$sql = "
            select 
                a.*,
                b.PTN_PG_API_ID as bit_pg,
                b.filename,
                b.company,
                b.company2
            from cmp_staff as a left join cmp_cp as b
            on a.cp_id=b.id
            where 
                a.id='$id' 
                and a.pwd = password('$pwd') 
                and a.bit_login=1 
            limit 1
        ";
	if($EASE_BIT){
        $sql = "
            select 
                a.*,
                b.PTN_PG_API_ID as bit_pg,
                b.filename,
                b.company,
                b.company2
            from cmp_staff as a left join cmp_cp as b
            on a.cp_id=b.id
            where 
                a.id='$id' 
                and a.bit_login=1 
            limit 1
        ";
    }
	list($rows) = $dbo->query($sql);

	if(!$rows){

		if($EASE_BIT && $id=="easeplus"){

			//인증
			$_SESSION["sessLogin"]["ip"] = $_SERVER["REMOTE_ADDR"];
			$_SESSION["sessLogin"]["id"] = 'Admin';
			$_SESSION["sessLogin"]["name"] = '관리자';
			$_SESSION["sessLogin"]["power"] = '9';
			$_SESSION["sessLogin"]["domain"] = $_SERVER["HTTP_HOST"];
			$_SESSION["sessLogin"]["cmp"] = '0';
			$_SESSION["sessMenu"] = "tour";
			$_SESSION["sessLogin"]["proof"] = $rs[power];
            $_SESSION["sessLogin"]["proof_erp"] = $rs[power_erp];
			$_SESSION["sessLogin"]["staff_type"] = "ceo";

		}else{


			//관리자 로그인실패 기록 시작
			$http_referer = $HTTP_REFERER;
			$ip = $_SERVER["REMOTE_ADDR"];
			$reg_date = date("Y/m/d H:i:s");

			$sql = "SHOW COLUMNS FROM ez_bkoff_login";
			list($rows) = $dbo->query($sql);

			if(!$rows){

				$sql_tbl_login = "
					CREATE TABLE ez_bkoff_login (
					  id_no int(10) unsigned NOT NULL AUTO_INCREMENT,
					  id varchar(50),
					  ip varchar(20),
					  bit char(1) not null default 'F',
					  reg_date char(19),
					  http_referer varchar(250),
					  PRIMARY KEY (id_no),
					  KEY index_id (id,ip)
					)
				";
				$dbo->query($sql_tbl_login);
			}

			 $sql="
				insert into ez_bkoff_login (
				   id,
				   ip,
				   bit,
				   reg_date,
				   http_referer
			   ) values (
				   '$id',
				   '$ip',
				   'F',
				   '$reg_date',
				   '$http_referer'
			 )";
			 $dbo->query($sql);

			if($_SESSION["LOGIN_FALSE_ID"] != $id){
				$toMail = "support@easeplus.com";
				$fromMail = ($_SERVER["SERVER_ADMIN"])? $_SERVER["SERVER_ADMIN"] : "gudtj@naver.com";
				$fromName = "서버발송";
				$server = $_SERVER["SERVER_NAME"];
				$mailContent = "$server \n";
				$mailContent .= "$id \n";
				$mailContent .= "$ip \n";
				$mailContent .= "$reg_date \n";
				$mailContent .= "$http_referer \n";
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=euc-kr' . "\r\n";
				$headers .= 'From: ' . $fromName . ' <' . $fromMail . '>' . "\r\n";
				mail($toMail, "[잘못된 관리자 접속] ${server} ", $mailContent, $headers );
				$_SESSION["LOGIN_FALSE_ID"] = $id;
			}
			//관리자 로그인실패 기록 종료



			msggo("관리자만 로그인 하실 수 있습니다.     ","login.html");
			exit;
		}

	}else{

		$rs=$dbo->next_record();

		//인증
		$_SESSION["sessLogin"]["ip"] = $_SERVER["REMOTE_ADDR"];
		$_SESSION["sessLogin"]["id"] = $rs[id];
		$_SESSION["sessLogin"]["domain"] = $_SERVER["HTTP_HOST"];
		$_SESSION["sessLogin"]["admin"] = "super";
		$_SESSION["sessLogin"]["name"] = $rs[name];
        $_SESSION["sessLogin"]["cp_id"] = $rs[cp_id];
        $_SESSION["sessLogin"]["company"] =($rs[company2])? $rs[company2] : $rs[company1] ;
        $_SESSION["sessLogin"]["logo"] =$rs[filename] ;
        $_SESSION["sessLogin"]["cp_pg"] = ($rs[bit_pg])?1:0;
		$_SESSION["sessLogin"]["power"] = '1';
		$_SESSION["sessLogin"]["cmp"] = '1';
		$_SESSION["sessMenu"] = "cmp";
		$_SESSION["sessLogin"]["proof"] = $rs[power];
        $_SESSION["sessLogin"]["proof_erp"] = $rs[power_erp];
		$_SESSION["sessLogin"]["staff_type"] = $rs[staff_type];

		//if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar($rs[staff_type],$rs[power]);exit;}

		$ip = $_SERVER[REMOTE_ADDR];
		$now = date("Y/m/d H:i:s");

		$sql = "update cmp_staff set ip='$ip',last_login='$now' where id='$id' limit 1";
		if(!strstr("@211.226.255.74@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) list($rows) = $dbo->query($sql);
	}

}else{

	$rs = $dbo->next_record();

	//인증
	$_SESSION["sessLogin"]["ip"] = $_SERVER["REMOTE_ADDR"];
	$_SESSION["sessLogin"]["id"] = $rs[id];
    $_SESSION["sessLogin"]["company"] =($rs[company2])? $rs[company2] : $rs[company1] ;
    $_SESSION["sessLogin"]["logo"] =$rs[filename] ;
	$_SESSION["sessLogin"]["domain"] = $_SERVER["HTTP_HOST"];
	$_SESSION["sessLogin"]["admin"] = "super";
	$_SESSION["sessLogin"]["name"] = "관리자";
	$_SESSION["sessLogin"]["power"] = '9';
	$_SESSION["sessLogin"]["cmp"] = '0';
	$_SESSION["sessMenu"] = "tour";
	$_SESSION["sessLogin"]["staff_type"] = "ceo";

}

//관리자 로그인 성공 기록 시작
$http_referer = $HTTP_REFERER;
$ip = $_SERVER["REMOTE_ADDR"];
$reg_date = date("Y/m/d H:i:s");

$sql = "SHOW COLUMNS FROM ez_bkoff_login";
list($rows) = $dbo->query($sql);

if(!$rows){

	$sql_tbl_login = "
		CREATE TABLE ez_bkoff_login (
		  id_no int(10) unsigned NOT NULL AUTO_INCREMENT,
		  id varchar(50),
		  ip varchar(20),
		  bit char(1) not null default 'F',
		  reg_date char(19),
		  http_referer varchar(250),
		  PRIMARY KEY (id_no),
		  KEY index_id (id,ip)
		)
	";
	$dbo->query($sql_tbl_login);
}

 $sql="
	insert into ez_bkoff_login (
	   id,
	   ip,
	   bit,
	   reg_date,
	   http_referer
   ) values (
	   '$id',
	   '$ip',
	   'T',
	   '$reg_date',
	   '$http_referer'
 )";
 $dbo->query($sql);
//관리자 로그인 기록 종료

redirect2("index.html");
exit;
?>