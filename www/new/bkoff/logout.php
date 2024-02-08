<?
########################로그아웃 페이지#######################
session_start();
session_unregister("sessLogin");

unset($sessLogin);
unset($_SESSION["sessLogin"]);


echo("<script>parent.location.replace('login.html');</script>");
?>