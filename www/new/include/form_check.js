<!--
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

function check_rn(rn1,rn2){
	str1 = rn1.value;
	str2 = rn2.value;


	//19세 미만 걸러내기
	/*
	now = new Date();

	var standard = now.getYear() - 19;
	
	standard = standard + "";

	standard = standard.substring(2,4)

	var limit = parseInt(str1.substring(0,2),10);


	if(limit > standard.substring(0,2) || limit < 20){
	
		alert("죄송합니다.\n\n만19세 미만은 본 서비스를 이용하실 수 없습니다.");

		return "wrong";
	}
	*/


	//주문번호 정확성 체크
	check = parseInt(str2.substring(6,7));
	value0 = parseInt(str1.substring(0,1),10) * 2;
	value1 = parseInt(str1.substring(1,2),10) * 3;
	value2 = parseInt(str1.substring(2,3),10) * 4;
	value3 = parseInt(str1.substring(3,4),10) * 5;
	value4 = parseInt(str1.substring(4,5),10) * 6;
	value5 = parseInt(str1.substring(5,6),10) * 7;
	value6 = parseInt(str2.substring(0,1),10) * 8;
	value7 = parseInt(str2.substring(1,2),10) * 9;
	value8 = parseInt(str2.substring(2,3),10) * 2;
	value9 = parseInt(str2.substring(3,4),10) * 3;
	value10 = parseInt(str2.substring(4,5),10) * 4;
	value11 = parseInt(str2.substring(5,6),10) * 5;
	sum = value0+value1+value2+value3+value4+value5+value6+value7+value8+value9+value10+value11;
		
	sum = sum%11;
	result1 = 11 - sum;
	result2 = result1 % 10;

	if(result2 != check){
		alert("잘못된 주민등록번호 입니다.\n\n확인하여 주십시오.");
		rn1.focus();
		return "wrong";
	}

}

function check_fgn_rn(rn1,rn2){
        var fgn_reg_no = rn1.value + rn2.value;

	if (fgn_reg_no == ''){
		alert('외국인등록번호를 입력하십시오.');
		rn1.focus();
		return "wrong";
	}

	if (fgn_reg_no.length != 13) {
		alert('외국인등록번호 자리수가 맞지 않습니다.');
		rn1.focus();
		return "wrong";
	}
        if ((fgn_reg_no.charAt(6) == "5") || (fgn_reg_no.charAt(6) == "6"))
        {
           birthYear = "19";
        }
        else if ((fgn_reg_no.charAt(6) == "7") || (fgn_reg_no.charAt(6) == "8"))
        {
           birthYear = "20";
        }
        else if ((fgn_reg_no.charAt(6) == "9") || (fgn_reg_no.charAt(6) == "0"))
        {
           birthYear = "18";
        }
        else
        {
            alert("등록번호에 오류가 있습니다. 다시 확인하십시오.");
			rn1.focus();
			return "wrong";
        }        
        birthYear += fgn_reg_no.substr(0, 2);
        birthMonth = fgn_reg_no.substr(2, 2) - 1;
        birthDate = fgn_reg_no.substr(4, 2);
        birth = new Date(birthYear, birthMonth, birthDate);
        
        if ( birth.getYear() % 100 != fgn_reg_no.substr(0, 2) ||
             birth.getMonth() != birthMonth ||
             birth.getDate() != birthDate) {
          alert('생년월일에 오류가 있습니다. 다시 확인하십시오.');
			rn1.focus();
			return "wrong";
        }
        
        if (fgn_no_chksum(fgn_reg_no) == false){
        
            alert('외국인등록번호에 오류가 있습니다. 다시 확인하십시오.');
			rn1.focus();
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

//-->