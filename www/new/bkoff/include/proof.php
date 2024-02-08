<?
session_start();

# 로그인하지 않은 사용자 걸러내기

if($_SESSION["sessLogin"]["ip"] != $_SERVER["REMOTE_ADDR"] || $_SESSION["sessLogin"]["domain"] != $_SERVER["HTTP_HOST"]){

	echo("<script>alert('로그인이 필요합니다!!!');parent.location.href='../login.html'</script>");
	exit;

}

$bgcolor = "#FFFFFF";
?>