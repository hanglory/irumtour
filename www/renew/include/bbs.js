function checkwrite(fm){
	
	if(check_blank(fm.name,'작성자를',0)=='wrong'){return false}
	if(fm.mail.value != '' && check_email(fm.mail)=='wrong'){return false}

	if(check_blank(fm.title,'제목을',0)=='wrong'){return false}

	oEditors[0].exec("update_IR_FIELD", []);
	if(fm.content.value=="" || fm.content.value.substr(0,1)==" "){
		//alert("내용을 입력하지 않으셨습니다.");
		//return false;
	}
	if(check_blank(fm.pwd,'비밀번호를',4)=='wrong'){return false}

}


function checkwriteAlbum(fm){

	if(check_blank(fm.name,'작성자를',0)=='wrong'){return false}
	if(fm.mail.value != '' && check_email(fm.mail)=='wrong'){return false}
	if(check_blank(fm.title,'제목을',0)=='wrong'){return false}
	oEditors[0].exec("update_IR_FIELD", []);
	if(fm.content.value=="" || fm.content.value.substr(0,1)==" "){
		//alert("내용을 입력하지 않으셨습니다.");
		//return false;
	}
	if(check_blank(fm.pwd,'비밀번호를',4)=='wrong'){return false}

	if (fm.file1.value =='') {
		alert("사진 파일을 선택하세요.");
		return false;
	 }

	var str=fm.file1.value;
	var chkStr=str.split(".");

	var lng = chkStr.length;
	lng = lng-1;
	
	if(str !=''){
		if(chkStr[lng] != 'jpg' && chkStr[lng] != 'jpeg' && chkStr[lng] != 'gif' && chkStr[lng] != 'png' && chkStr[lng] != 'bmp' && chkStr[lng] != 'png' && chkStr[lng] != 'JPG' && chkStr[lng] != 'JPEG' && chkStr[lng] != 'GIF' && chkStr[lng] != 'PNG' && chkStr[lng] != 'BMP' && chkStr[lng] != 'PNG'){
			alert("이미지 파일만 업로드 하실 수 있습니다.");
			return false;
		}
	}

}


//modify

function checkmodify(fm){

	if(fm.mail.value != '' && check_email(fm.mail)=='wrong'){return false}
	if(check_blank(fm.title,'제목을',0)=='wrong'){return false}
	oEditors[0].exec("update_IR_FIELD", []);
	if(fm.content.value=="" || fm.content.value.substr(0,1)==" "){
		//alert("내용을 입력하지 않으셨습니다.");
		//return false;
	}
	if(check_blank(fm.pwd,'비밀번호를',4)=='wrong'){return false}


}


//reply

function checkreply(fm){

	if(check_blank(fm.name,'작성자를',0)=='wrong'){return false}
	if(fm.mail.value != '' && check_email(fm.mail)=='wrong'){return false}
	if(check_blank(fm.title,'제목을',0)=='wrong'){return false}
	oEditors[0].exec("update_IR_FIELD", []);
	if(check_blank(fm.pwd,'비밀번호를',4)=='wrong'){return false}


}

function checkdrop(fm){

	if(check_blank(fm.pwd,'비밀번호를',0)=='wrong'){return}

	if(confirm('정말 이 글을 삭제 하시겠습니까?')){
		fm.submit();
	}

}

//view

function openwindow(vfile,vwidth,vheight){
	window.open ("./mall/code/pop_image.php?vfile=" + vfile ,"","toolbar=no,location=no,menubar=no,scrollbars=no,resizable=yes,width=" + vwidth + ",height=" + vheight)
}


function fmdropcheck(fm){

	if(fm.pwd.value == ''){

		alert("글을 삭제하시려면 비밀번호를 입력하세요.");	

		fm.pwd.focus();
		
		return;
	}
	
	fm.submit();

}

//comment

function checkcomment(fm){

	if(check_blank(fm.comment,'내용을',0)=='wrong'){return false}
	if(check_blank(fm.name,'작성자를',0)=='wrong'){return false}
	if(check_blank(fm.pwd,'비밀번호를',4)=='wrong'){return false}

}


function commentDrop(){
	fm = document.fmdrop;
	if(fm.pwd.value=="" || fm.pwd.value.substr(0,1)==" "){
		alert("비밀번호를 입력하지 않으셨거나 첫 글자에 공백이 있습니다.\n\n비밀번호를 정확히 입력하여 주십시오.");
		fm.pwd.focus();
		fm.pwd.select();
		return;
	}
	fm.submit();
}


function comm_edit(comment,no,origin_no,name){
	fm = document.fmComment;
	fm.comment.value=comment;
	fm.comm_no.value = no;
	fm.comm_origin_no.value = origin_no;
	fm.comm_mode.value = 'modify';
	fm.name.value = name;
}

//FAQ 게시판용
function view(menu){
	var st = document.all(menu).style.display;
	if(st=='') document.all(menu).style.display='none';
	else document.all(menu).style.display='';
}


