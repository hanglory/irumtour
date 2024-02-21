<?
if(SELF!="form06.html"){
    $txt2 = str_replace("{골프장이미지}","",$txt2);
    $txt1 = str_replace("{골프장이미지}","",$txt1);
}

$css_colorR="font-weight:bold;color:red;line-height:130%";
$css_colorB="font-weight:bold;color:blue;line-height:130%";
$css_colorG="font-weight:bold;color:green;line-height:130%";

$txt2 = str_replace("[","<span class='golf'>",$txt2);
$txt2 = str_replace("]","</span>",$txt2);

$txt2 = str_replace("<<","<span class='color1'>",$txt2);
$txt2 = str_replace(">>","</span>",$txt2);

$txt2 = str_replace("((","<span class='color2'>",$txt2);
$txt2 = str_replace("))","</span>",$txt2);

$txt2 = str_ireplace("<FCR>","<span style='$css_colorR'>",$txt2);
$txt2 = str_ireplace("</FCR>","</span>",$txt2);

$txt2 = str_ireplace("<FCB>","<span style='$css_colorB'>",$txt2);
$txt2 = str_ireplace("</FCB>","</span>",$txt2);

$txt2 = str_ireplace("<FCG>","<span style='$css_colorG'>",$txt2);
$txt2 = str_ireplace("</FCG>","</span>",$txt2);
?>