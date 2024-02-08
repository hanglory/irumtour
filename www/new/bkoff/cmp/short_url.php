<?
include_once("../include/common_file.php");

$url_short = short_url($url);
checkVar("url",$url);
checkVar("url_short",$url_short);
?>
<!DOCTYPE html>
<html>
<head>
<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript">

$(function(){

  var t = document.createElement("textarea");
  document.body.appendChild(t);
  t.value = "<?=$url_short?>";
  t.select();
  document.execCommand('copy');
  document.body.removeChild(t);
  alert("복사되었습니다.");

});

</script>


</head>
<body>



</body>
</html>



