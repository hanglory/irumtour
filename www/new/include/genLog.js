if(document.URL.substring(0,4) != 'file') 
{ 
var s_day = new Date(); 

if (typeof(parent.document) != "unknown") 
var url = parent.document.URL; 
else 
var url = "none"; 

if(document.referrer == url) 
var log_ref=parent.document.referrer; 
else 
var log_ref=document.referrer; 

var user_time=s_day.getFullYear()+"-"+(s_day.getMonth()+1)+"-"+s_day.getDate()+" "+s_day.getHours()+":"+s_day.getMinutes()+":"+s_day.getSeconds(); 

var user_zone=s_day.getTimezoneOffset()/60; 
var user_ss=screen.width+"*"+screen.height; 
var user_sc=screen.colorDepth; 
var java_ab=(navigator.javaEnabled()==true)?"y":"n"; 

var str="<img src='http://www.easeplus.com/script/visitorLog.php?"; 
str+="ut="+escape(user_time)+"&uz="+escape(user_zone)+"&uss="+escape(user_ss)+"&usc="+escape(user_sc); 
str+="&ja="+escape(java_ab)+"&lr="+escape(log_ref)+"&lu="+escape(document.URL); 
str+="' border=0 height=1 width=1>"; 

document.write(str); 
} 
