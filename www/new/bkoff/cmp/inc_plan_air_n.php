<?
if($d_air_time1_m=="__:__") $d_air_time1_m="";
if($d_air_time2_m=="__:__") $d_air_time2_m="";

if($d_air_no_m) $contents[1] .= "$d_air_no_m2  \r";
if($d_air_time1_m) $contents[1] .= "$d_air_no_m $d_air_time1_m";
if($d_air_time2_m) $contents[1] .= "~ $d_air_time2_m \r";
?>