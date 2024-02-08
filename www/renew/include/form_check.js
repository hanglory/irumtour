function check_blank(fm,name,length){
	if(fm.value=="" || fm.value.substr(0,1)==" "){
		alert(name + " 입력하지 않으셨거나 첫 글자에 공백이 있습니다.\n\n" +  name + " 정확히 입력하여 주십시오.");
		fm.focus();
		fm.select();
		return "wrong";
	}

	if(fm.value.length < length){
		alert(name + " " +length +"자 이상 입력하여 주십시오.");
		fm.focus();
		return "wrong";
	}

}

function check_checkbox(fm,name){
	if(fm.value=="" || fm.value==0){
		alert(name + " 체크하지 않았습니다.\n\n" +  name + " 을 체크하여 주십시오.");
		return "wrong";
	}
}

function check_select(fm,name){
	if(fm.selectedIndex <1){
		alert(name + " 선택하십시오.");
		fm.focus();
		return "wrong";
	}		
}


function check_email(fm){

	var str1 = fm.value.indexOf("@");
	var str2 = fm.value.indexOf(".");
	var str3 = fm.value.substring(str1,str2);
	var str4 = fm.value.substring(str2);

	if (str1 <1 || str2 <3  || str3.length <2 || str4.length <2){
		alert("잘못된 이메일 주소입니다. \n\n확인하여 주십시오.");
		fm.focus();
		return "wrong";
	}
}


function check_num(fm,name){
	str=fm.value
	for (i = 0; i < str.length; i++) {		
		if (str.charAt(i) >= '0' && str.charAt(i) <= '9')
			continue;
		else {
			alert(name + "에는 숫자만 사용하실 수 있습니다.");
			fm.focus();
			fm.select();
			return "wrong";
		}
	}
}


function check_strnum(fm,name){
	str=fm.value
	for (i = 0; i < str.length; i++) {		
		if (str.charAt(i) >= '0' && str.charAt(i) <= '9')
			continue;
		else if (str.charAt(i) >= 'a' && str.charAt(i) <= 'z')
			continue;
		else if (str.charAt(i) >= 'A' && str.charAt(i) <= 'Z')
			continue;
		else {
			alert(name + "에는 영문자(대문자,소문자)와 숫자만 사용하실 수 있습니다.");
			fm.focus();
			fm.select();
			return "wrong";
		}
	}
}

function check_english(fm,name){
	str=fm.value
	for (i = 0; i < str.length; i++) {		
		if (str.charAt(i) >= '0' && str.charAt(i) <= '9')
			continue;
		else if (str.charAt(i) >= 'a' && str.charAt(i) <= 'z')
			continue;
		else if (str.charAt(i) >= 'A' && str.charAt(i) <= 'Z')
			continue;
		else if (str.charAt(i) == '-') continue;
		else if (str.charAt(i) == '_') continue;
		else if (str.charAt(i) == '=') continue;
		else if (str.charAt(i) == '+') continue;
		else if (str.charAt(i) == '\\') continue;
		else if (str.charAt(i) == '|') continue;
		else if (str.charAt(i) == '[') continue;
		else if (str.charAt(i) == ']') continue;
		else if (str.charAt(i) == '{') continue;
		else if (str.charAt(i) == '}') continue;
		else if (str.charAt(i) == ';') continue;
		else if (str.charAt(i) == ':') continue;
		else if (str.charAt(i) == "\"") continue;
		else if (str.charAt(i) == "'") continue;
		else if (str.charAt(i) == ',') continue;
		else if (str.charAt(i) == '<') continue;
		else if (str.charAt(i) == '.') continue;
		else if (str.charAt(i) == '>') continue;
		else if (str.charAt(i) == '/') continue;
		else if (str.charAt(i) == '?') continue;
		else {
			alert(name + "에는 영문만 사용하실 수 있습니다.");
			fm.focus();
			fm.select();
			return "wrong";
		}
	}
}

function auto_focus(thisLength,nextControl,count){

	if(thisLength >= count){

		nextControl.focus();
		
	}
}


function check_image(fm,name){
	var str=fm.value;
	var chkStr=str.split(".");
	
	if(str !=''){
		if(chkStr[1] != 'jpg' && chkStr[1] != 'jpeg' && chkStr[1] != 'gif' && chkStr[1] != 'png' && chkStr[1] != 'bmp' && chkStr[1] != 'png' && chkStr[1] != 'JPG' && chkStr[1] != 'JPEG' && chkStr[1] != 'GIF' && chkStr[1] != 'PNG' && chkStr[1] != 'BMP' && chkStr[1] != 'PNG'){
			alert( name + "은 이미지 파일만 업로드 하실 수 있습니다.");
			fm.focus();
			return "wrong";
		}
	}
}