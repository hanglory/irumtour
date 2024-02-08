function newWin(file,width,height,scroll,resize,leftPoint,subject){

	var topPoint = (screen.height/2)-(height/2);
	var leftPoint = (screen.width/2)-(width/2);
    subject="";

	scroll = (scroll =='' )? 'no' : scroll;
	resize = (resize =='' )? 'no' : resize;

	window.open (file,subject,"scrollbars="+scroll+",resizable="+resize+",width="+width+",height="+height+",top="+topPoint+",left="+leftPoint);
}

function number_format(number,decimals ){
	var number_str = String(number);
	var new_number_str = '';
	number_info = number_str.split('.');
	number_info_int = number_info[0];
	if (decimals != null ) {
	if (decimals > 0) new_number_str ='.' + ((decimals > 0) ?Math.round(Number('0.' +number_info[1]) * Math.pow(10, decimals)):'');
	else if (decimals < 0) number_info_int = String(Math.round(Number(number_info[0]) / Math.pow(10, decimals *(-1))) *Math.pow(10, decimals*(-1)));
	}
	len = number_info_int.length;
	for (i = len; i >= 0; i--) {
	if ( (len - i) > 1 && (len - i) % 3 == 1) new_number_str = ',' + new_number_str;
	new_number_str = number_info_int.substring(i, i+1) + new_number_str;
	}
	return new_number_str;

	/*
	alert(number_format(123446.234 ,-2)); // 100 단위에서 반올림
	alert(number_format(123446.234 ,2)); // 소수점 2자리 까지만 표시
	alert(number_format(123446.234)); // 가장 일반적인 형태
	*/
}


//관리자 메뉴의 메뉴의 마우스 이벤트 반응 함수
function menuC(fm,assort){
	if(assort==1){
		bgColor = "#EEEEFF";
		fontColor = "#9900FF";
	}else{
		bgColor = "#FFFFFF";
		fontColor = "#000000";
	}

	fm.style.backgroundColor=bgColor;
	fm.style.color=fontColor;
}


function menuA(fm,assort){
	if(assort==1){
		bgColor = "#CC0033";
		fontColor = "#F7F7F7";
	}else{
		bgColor = "#F7F7F7";
		fontColor = "#808080";
	}

	fm.style.backgroundColor=bgColor;
	fm.style.color=fontColor;
}


//링크 점선 없애 주기 <body onFocus="blurAll()">
function blurAll(){
for (i=0;i<document.links.length;i++)
 document.links[i].onfocus=document.links[i].blur
}


//마우스 오버 때에 이미지 미리보기
function msgposit(){
message.style.posLeft = event.x + 50 + document.body.scrollLeft
message.style.posTop = event.y - 120 + document.body.scrollTop
}

function msgposit2(){
message.style.posLeft = event.x + 50 + document.body.scrollLeft
message.style.posTop = event.y - 40 + document.body.scrollTop
}

function msgset(str,str2){
var text
text ='<table align="center" border="0" cellpadding="0" cellspacing="0" bgcolor="" style="border-width:1; border-color:gray; border-style:solid;">'
text += '<tr><td align=center>'+str+'<br>'+str2+'</td></tr></table>'
message.innerHTML=text
}

function msghide(){
message.innerHTML=''
}

//엔터치면 다음으로 
function nextCol(fm){
	if(event.keyCode==13){
		event.keyCode=8
		fm.focus();
	}
}

function allblur() {
  for (i = 0; i < document.links.length; i++) {
    var obj = document.links[i];
    if(obj.addEventListener) obj.addEventListener("focus", oneblur, false);
    else if(obj.attachEvent) obj.attachEvent("onfocus", oneblur);
  }
}
 
function oneblur(e) {
  var evt = e ? e : window.event;

  if(evt.target) evt.target.blur();
  else if(evt.srcElement) evt.srcElement.blur();
}


/*****************************************************************************/
/* 쿠키관련 함수
/*****************************************************************************/
function getCookieVal (offset) {
   var endstr = document.cookie.indexOf (";", offset);
   if (endstr == -1) endstr = document.cookie.length;
   return unescape(document.cookie.substring(offset, endstr));
}

function GetCookie (name) {
   var arg = name + "=";
   var alen = arg.length;
   var clen = document.cookie.length;
   var i = 0;
   while (i < clen) {	//while open
      var j = i + alen;
      if (document.cookie.substring(i, j) == arg)
         return getCookieVal (j);
      i = document.cookie.indexOf(" ", i) + 1;
      if (i == 0) break; 
   }	//while close
   return null;
}

function SetCookie (name, value) {
   var argv = SetCookie.arguments;
   var argc = SetCookie.arguments.length;
   var expires = (2 < argc) ? argv[2] : null;
   var path = (3 < argc) ? argv[3] : null;
   var domain = (4 < argc) ? argv[4] : null;
   var secure = (5 < argc) ? argv[5] : false;
   document.cookie = name + "=" + escape (value) +
      ((expires == null) ? "" : 
         ("; expires=" + expires.toGMTString())) +
      ((path == null) ? "" : ("; path=" + path)) +
      ((domain == null) ? "" : ("; domain=" + domain)) +
      ((secure == true) ? "; secure" : "");
} 

//-->

//브라우저 판별
function chk_bw(){
	var bw =  navigator.appName;
	var result;
	if(bw=='Microsoft Internet Explorer'){
		result = 'ms';
	}else{
		result = 'other';
	}
	return result;
}

function setDateData(date1,date2){

	var fm = document.fmSearch;
	fm.start_date.value=date1;
	fm.end_date.value=date2;

}

//문자열 치환
function str_replace(str,src,dest){
	var reg = new RegExp(src,"gi");
	return str.replace(reg,dest);
}
//-->


function check_kor(fm){
 var str = fm.value;
 for(i=0; i<str.length; i++)
 {
  if(!((str.charCodeAt(i) > 0x3130 && str.charCodeAt(i) < 0x318F) || (str.charCodeAt(i) >= 0xAC00 && str.charCodeAt(i) <= 0xD7A3)))
  {
   alert("반드시 한글만 입력하세요");
   fm.value = "";
   return false;
  }
  else; // 처리
 }
}

function cancel(url){

	if(url=="") url = "index.html";
	if(confirm('취소하시겠습니까?')){
		location.href=url;
	}
}

function back(){

	history.back(-1);

}


function submain(code1,code2){
	var url = "submain.html";
	url += "?code1="+code1;
	url += "&code2="+code2;
	location.href=url;
}

function sublist(code1,code2,code3){
	var url = "sublist.html";
	if(code3==undefined) code3="";
	url += "?code1="+code1;
	url += "&code2="+code2;
	url += "&code3="+code3;
	location.href=url;
}

function comma(no){
    var num = no.toString().replace(/,/g, '');
    return num.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


/*빈값 체크*/
function is_empty(str){    
    if(typeof str == "undefined" || str == null || str == "")
        return true;
    else
        return false ;
}

$(function(){

    $('.comma').on('click',function(){$(this).select()});

    $(".numberic").keypress(function(event){
        if(event.which && (event.which < 48 || event.which > 57)){
            event.preventDefault();
        }
    });
    $(".numberic,.numberic2").on("focus",function(){this.select();});

    $(".comma").on("keyup",function(){
        $(this).val(comma(this.value));
    });


    //$(".biz_no").mask("999-99-99999");
    //$(".birth,.dateinput").mask("9999/99/99");
    //$('.birth6').mask('9999');
    //$('.cell').mask('999-9999-9999');
    //$('.cell').attr("placeholder",'000-0000-0000');
    $(".cell").on("keyup",function(){ 
        $(this).val( $(this).val().replace(/[^0-9]/g, "").replace(/(^02|^0505|^1[0-9]{3}|^0[0-9]{2})([0-9]+)?([0-9]{4})$/,"$1-$2-$3").replace("--", "-") );
    });

    //$('#cell1').mask('000');
    //$('#cell2').mask('0000');
    //$('#cell3').mask('0000');
    //$('#phone1').mask('0000');
    //$('#phone2').mask('0000');
    //$('#phone3').mask('0000');      

});