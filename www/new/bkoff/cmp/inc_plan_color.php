<?
if(SELF!="form06.html"){
    $txt2 = str_replace("{골프장이미지}","",$txt2);
    $txt1 = str_replace("{골프장이미지}","",$txt1);
}

$css_colorR="font-weight:bold;color:#FF0000;line-height:130%";
$css_colorB="font-weight:bold;color:#0000FF;line-height:130%";
$css_colorG="font-weight:bold;color:#00FF00;line-height:130%";

$txt2 = str_replace("[","<span class='golf'>",$txt2);
$txt2 = str_replace("]","</span>",$txt2);

$txt2 = str_replace("<<","<span class='color1'>",$txt2);
$txt2 = str_replace(">>","</span>",$txt2);

$txt2 = str_replace("((","<span class='color2'>",$txt2);
$txt2 = str_replace("))","</span>",$txt2);

$str = str_ireplace("<FCR>","<span style='$css_colorR'>",$txt2);
$str = str_ireplace("</FCR>","</span>",$txt2);

$str = str_ireplace("<FCB>","<span style='$css_colorB'>",$txt2);
$str = str_ireplace("</FCB>","</span>",$txt2);

$str = str_ireplace("<FCG>","<span style='$css_colorG'>",$txt2);
$str = str_ireplace("</FCG>","</span>",$txt2);
?>