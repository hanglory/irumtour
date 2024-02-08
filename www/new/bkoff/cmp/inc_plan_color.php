<?
if(SELF!="form06.html"){
    $txt2 = str_replace("{골프장이미지}","",$txt2);
    $txt1 = str_replace("{골프장이미지}","",$txt1);
}


$txt2 = str_replace("[","<span class='golf'>",$txt2);
$txt2 = str_replace("]","</span>",$txt2);

$txt2 = str_replace("<<","<span class='color1'>",$txt2);
$txt2 = str_replace(">>","</span>",$txt2);

$txt2 = str_replace("((","<span class='color2'>",$txt2);
$txt2 = str_replace("))","</span>",$txt2);
?>