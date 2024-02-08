<?
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . 'GMT');
header('Cache-Control: no-cache, must-revalidate');
header('Pragma: no-cache');
header('Content-Type: text/xml');

echo $file;exit;
?>
<!DOCTYPE html system "ABOUT:LEGACY-COMPAT">
<head>
<meta charset="UTF-8">
<meta http-equiv="Cache-Control" content = "no-cache">
<meta http-equiv="Progma" content = "no-cache">
<script type="text/javascript">
<!--
$(function(){

});
//-->
</script>
</head>

<body>

<img src="<?=$file?>?<?=time()?>" alt="" width="150"/>

</body>

</html>