var oEditors = [];
nhn.husky.EZCreator.createInIFrame({
	oAppRef: oEditors,
	elPlaceHolder: "content",
	sSkinURI: "/renew/se2/SmartEditor2Skin_admin.html",
	htParams : {bUseToolbar : true,
		fOnBeforeUnload : function(){
			//alert("�ƽ�!");
		}
	}, //boolean
	fOnAppLoad : function(){
		//oEditors.getById["content"].exec("PASTE_HTML", ["�ε��� �Ϸ�� �Ŀ� ������ ���ԵǴ� text�Դϴ�."]);
	},
	fCreator: "createSEditor2"
});

function pasteHTML() {
	var sHTML = "<span style='color:#FF0000;'>�̹����� ���� ������� �����մϴ�.<\/span>";
	oEditors.getById["content"].exec("PASTE_HTML", [sHTML]);
}

function showHTML() {
	var sHTML = oEditors.getById["content"].getIR();
	alert(sHTML);
}

function submitContents(elClickedObj) {
	oEditors.getById["content"].exec("update_CONTENTS_FIELD", []);	// �������� ������ textarea�� ����˴ϴ�.

	// �������� ���뿡 ���� �� ������ �̰����� document.getElementById("content").value�� �̿��ؼ� ó���ϸ� �˴ϴ�.

	try {
		elClickedObj.form.submit();
	} catch(e) {}
}

function setDefaultFont() {
	var sDefaultFont = '�ü�';
	var nFontSize = 24;
	oEditors.getById["content"].setDefaultFont(sDefaultFont, nFontSize);
}
