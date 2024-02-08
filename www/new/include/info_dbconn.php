<?
#### DB 연결 정보

$database = "mysql"; //mysql | msql | sybase | oci8 | oracle | pgsql | pgsql-4.1.0 | mssql | ifx
$varchar = "VARCHAR";


$info = array(
	"host"=>"localhost",
	"user"=>"irumtour2",
	"pass"=>"ccn121422!",
	"base"=>"irumtour2",
	"connect"=>"connect" // connect | pconnect
);
//ccn121422!

if ("oracle"==$database || "oci8"==$database) {
	$info["base"] = "ORCL";
	//PutEnv("ORACLE_SID=ORCL");
	//PutEnv("ORACLE_HOME=/home/oracle/app/oracle/product/8.0.5");
	//PutEnv("NLS_LANG=AMERICAN_AMERICA.KO16KSC5601");
	//PutEnv("NLS_LANG=KOREAN_KOREA.KO16KSC5601");
}


extract($info);
?>
