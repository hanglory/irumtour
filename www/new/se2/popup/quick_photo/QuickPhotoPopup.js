	//���� ���� �� �ʱ�ȭ
	var nImageInfoCnt = 0;
	var htImageInfo = [];		//image file���� ����
	var aResult = [];
	
	var rFilter = /^(image\/bmp|image\/gif|image\/jpg|image\/jpeg|image\/png)$/i;  
	var rFilter2 = /^(bmp|gif|jpg|jpeg|png)$/i; 
	var nTotalSize = 0;
	var nMaxImageSize = 10*1024*1024;
	var nMaxTotalImageSize = 50*1024*1024;
	var nMaxImageCount = 10;
	var nImageFileCount = 0;
	var bSupportDragAndDropAPI = false;
	var oFileUploader;
	var bAttachEvent = false;

	//��ũ���� ���� �Ҵ�
	var elContent= $("pop_content");  
	var elDropArea = jindo.$$.getSingle(".drag_area",elContent);
	var elDropAreaUL = jindo.$$.getSingle(".lst_type",elContent);
	var elCountTxtTxt = jindo.$$.getSingle("#imageCountTxt",elContent);
	var elTotalSizeTxt = jindo.$$.getSingle("#totalSizeTxt",elContent);
	var elTextGuide = $("guide_text");
	var welUploadInputBox = $Element("uploadInputBox");
	var oNavigator = jindo.$Agent().navigator();
	
	//��ũ��-���� 
	var welBtnConfirm = $Element("btn_confirm");				//Ȯ�� ��ư
	var welBtnCancel= $Element("btn_cancel");				//��� ��ư
	
	//������ ���ε� element
	var welTextGuide = $Element(elTextGuide);
	var welDropArea = $Element(elDropArea);
	var welDropAreaUL = $Element(elDropAreaUL); 
	var fnUploadImage = null;
	
	//File API ���� ���η� ����
	function checkDragAndDropAPI(){
		try{
			if( !oNavigator.ie ){
				if(!!oNavigator.safari && oNavigator.version <= 5){
					bSupportDragAndDropAPI = false;
				}else{
					bSupportDragAndDropAPI = true;
				}
			} else {
				bSupportDragAndDropAPI = false;
			}
		}catch(e){
			bSupportDragAndDropAPI = false;
		}
	}
	
	//--------------- html5 ������ ���������� (IE9 ����) ---------------
	/** 
	 * �̹����� ÷�� �� Ȱ��ȭ�� ��ư ����
	 */
     function goStartMode(){
    	 var sSrc = welBtnConfirm.attr("src")|| "";
    	 if(sSrc.indexOf("btn_confirm2.png") < 0 ){
    		 welBtnConfirm.attr("src","../../img/photoQuickPopup/btn_confirm2.png");
    		 fnUploadImage.attach(welBtnConfirm.$value(), "click");
    	 }
     } 
     /**
      * �̹����� ÷�� �� ��Ȱ��ȭ�� ��ư ����
      * @return
      */
     function goReadyMode(){
    	 var sSrc = welBtnConfirm.attr("src")|| "";
    	 if(sSrc.indexOf("btn_confirm2.png") >= 0 ){
    		 fnUploadImage.detach(welBtnConfirm.$value(), "click");
	    	 welBtnConfirm.attr("src","../../img/photoQuickPopup/btn_confirm.png");
    	 }
     }   
	
	/**
	 * �Ϲ� ���ε� 
	 * @desc oFileUploader�� upload�Լ��� ȣ����. 
	 */
	function generalUpload(){
		oFileUploader.upload();
	}
	
    /** 
     * �̹��� ÷�� �� �ȳ� �ؽ�Ʈ�� ������ ������� '����'�ϴ� �Լ�.
     * @return
     */
 	function readyModeBG (){
 		var sClass = welTextGuide.className();
 		if(sClass.indexOf('nobg') >= 0){
 			welTextGuide.removeClass('nobg');
 			welTextGuide.className('bg');
 		}
 	}
 	
 	/**
 	 * �̹��� ÷�� �� �ȳ� �ؽ�Ʈ�� ������ ����� '����'�ϴ� �Լ�. 
 	 * @return
 	 */
 	function startModeBG (){
 		var sClass = welTextGuide.className();
 		if(sClass.indexOf('nobg') < 0){
	 		welTextGuide.removeClass('bg');
	 		welTextGuide.className('nobg');
 		}
 	}

	//--------------------- html5  �����Ǵ� ���������� ����ϴ� �Լ�  --------------------------
 	/**
 	 * �˾��� ����� ���ε� ���� ������ ��.
 	 * @param {Object} nCount ���� ���ε� ������ ���� ���
 	 * @param {Object} nVariable �����Ǵ� ��
 	 */
 	function updateViewCount (nCount, nVariable){
 		var nCnt = nCount + nVariable;
 		elCountTxtTxt.innerHTML = nCnt +"장";
 		nImageFileCount = nCnt;
 		return nCnt;
 	}
 	
 	/**
 	 * �˾��� ����� ���ε�� ���� �� �뷮
 	 */
 	function updateViewTotalSize(){
 		var nViewTotalSize = Number(parseInt((nTotalSize || 0), 10) / (1024*1024));
 		elTotalSizeTxt.innerHTML = nViewTotalSize.toFixed(2) +"MB";
 	}
 	
 	/**
 	 * �̹��� ��ü �뷮 ����.
 	 * @param {Object} sParentId
 	 */
 	function refreshTotalImageSize(sParentId){
 		var nDelImgSize = htImageInfo[sParentId].size;
 		if(nTotalSize - nDelImgSize > -1 ){
 			nTotalSize = nTotalSize - nDelImgSize;
 		} 
 	}
	
 	/**
 	 * hash table���� �̹��� ���� �ʱ�ȭ.
 	 * @param {Object} sParentId
 	 */
 	function removeImageInfo (sParentId){
 		//������ �̹����� ������ �ʱ�ȭ �Ѵ�.
 		htImageInfo[sParentId] = null;
 	}
 	
 	
 	/**
 	 * byte�� ���� �̹��� �뷮�� ȭ�鿡 ǥ�ø� ���� ������
 	 * @param {Object} nByte
 	 */
 	function setUnitString (nByte) {
 		var nImageSize;
 		var sUnit;
 		
 		if(nByte < 0 ){
 			nByte = 0;
 		}
 		
 		if( nByte < 1024) {
 			nImageSize = Number(nByte);
 			sUnit = 'B';
 			return nImageSize + sUnit;
 		} else if( nByte > (1024*1024)) {
 			nImageSize = Number(parseInt((nByte || 0), 10) / (1024*1024));
 			sUnit = 'MB';
 			return nImageSize.toFixed(2) + sUnit;
 		} else {
 			nImageSize = Number(parseInt((nByte || 0), 10) / 1024);
 			sUnit = 'KB';
 			return nImageSize.toFixed(0) + sUnit;
 		}
     }
 	
 	/**
 	 * ȭ�� ��Ͽ� �����ϰ� �̸��� �߶� ǥ��.
 	 * @param {Object} sName ���ϸ�
 	 * @param {Object} nMaxLng �ִ� ����
 	 */
 	function cuttingNameByLength (sName, nMaxLng) {
 		var sTemp, nIndex;
 		if(sName.length > nMaxLng){
 			nIndex = sName.indexOf(".");
 			sTemp = sName.substring(0,nMaxLng) + "..." + sName.substring(nIndex,sName.length) ;
 		} else {
 			sTemp = sName;
 		}
 		return sTemp;
 	}
 	
 	/**
 	 * Total Image Size�� üũ�ؼ� �߰��� �̹����� ������ ������ ������.
 	 * @param {Object} nByte
 	 */
 	function checkTotalImageSize(nByte){
 		if( nTotalSize + nByte < nMaxTotalImageSize){
 			nTotalSize = nTotalSize + nByte;
 			return false;
 		} else {
 			return true;
 		}
 	}
	
 	// �̺�Ʈ �ڵ鷯 �Ҵ�
 	function dragEnter(ev) {
 		ev.stopPropagation();
 		ev.preventDefault();
     }
 	
     function dragExit(ev) {
     	ev.stopPropagation();
     	ev.preventDefault();
     }
     
 	function dragOver(ev) {
 		ev.stopPropagation();
 		ev.preventDefault();
     }
 	
	/**
	 * ��� ������ ������ ������ ���� �߻��ϴ� �̺�Ʈ
	 * @param {Object} ev
	 */
    function drop(ev) {
		ev.stopPropagation();
		ev.preventDefault();
		
		if (nImageFileCount >= 10){
			alert("�ִ� 10������� ����� �� �ֽ��ϴ�.");
			return;
		}
		
		if(typeof ev.dataTransfer.files == 'undefined'){
			alert("HTML5 ������ ���������� �̷������ �ʴ� �������Դϴ�.");
		}else{
			//���� ����
			var wel,
				files,
				nCount,
				sListTag = '';
			
			//�ʱ�ȭ	
			files = ev.dataTransfer.files;
			nCount = files.length;
			
			if (!!files && nCount === 0){
				//������ �ƴ�, ������������ �̹����� �巡�� ���� ���.
				alert("�������� ÷�ι���� �ƴմϴ�.");
				return ;
			}
			
			for (var i = 0, j = nImageFileCount ; i < nCount ; i++){
				if (!rFilter.test(files[i].type)) {
					alert("�̹������� (jpg,gif,png,bmp)�� ���ε� �����մϴ�.");
				} else if(files[i].size > nMaxImageSize){
					alert("�̹��� �뷮�� 10MB�� �ʰ��Ͽ� ����� �� �����ϴ�.");
				} else {
					//���ѵ� ���� ���ε� ����.
					if ( j < nMaxImageCount ){
						sListTag += addImage(files[i]);
						
						//���� ���������� ����
						j = j+1;
						nImageInfoCnt = nImageInfoCnt+1;
					} else {
						alert("�ִ� 10������� ����� �� �ֽ��ϴ�.");
						break;			
					}
				}
			}
			if(j > 0){
				//��� �̹��� ����
				startModeBG();
				if ( sListTag.length > 1){
					welDropAreaUL.prependHTML(sListTag);
				}
				//�̹��� �ѻ����� view update 
				updateViewTotalSize();
				//�̹�ġ �� �� view update
				nImageFileCount = j;
				updateViewCount(nImageFileCount, 0);
				// ���� ��ư Ȱ��ȭ
				goStartMode();
			}else{
				readyModeBG();
			}
		}
    }
	
    /**
     * �̹����� �߰��ϱ� ���ؼ� file�� �����ϰ�, ��Ͽ� �����ֱ� ���ؼ� string�� ����� �Լ�.
     * @param ofile �Ѱ��� �̹��� ����
     * @return
     */
    function addImage(ofile){
    	//���� ������
		var ofile = ofile,
			sFileSize = 0,
			sFileName = "",
			sLiTag = "",
			bExceedLimitTotalSize = false,
			aFileList = [];
		
		sFileSize = setUnitString(ofile.size);
		sFileName = cuttingNameByLength(ofile.name, 15);
		bExceedLimitTotalSize = checkTotalImageSize(ofile.size);

		if( !!bExceedLimitTotalSize ){
			alert("��ü �̹��� �뷮�� 50MB�� �ʰ��Ͽ� ����� �� �����ϴ�. \n\n (���ϸ� : "+sFileName+", ������ : "+sFileSize+")");
		} else {
			//�̹��� ���� ����							
			htImageInfo['img'+nImageInfoCnt] = ofile;
			
    		//List ��ũ�� �����ϱ�
			aFileList.push('	<li id="img'+nImageInfoCnt+'" class="imgLi"><span>'+ sFileName +'</span>');
			aFileList.push('	<em>'+ sFileSize +'</em>');
	        aFileList.push('	<a onclick="delImage(\'img'+nImageInfoCnt+'\')"><img class="del_button" src="../../img/photoQuickPopup/btn_del.png"  width="14" height="13" alt="÷�� ���� ����"></a>');
			aFileList.push('	</li> ');   
			
			sLiTag = aFileList.join(" ");
			aFileList = [];
		}
		return sLiTag;
    }
    
    /**
     * HTML5 DragAndDrop���� ������ �߰��ϰ�, Ȯ�ι�ư�� ���� ��쿡 �����Ѵ�.
     * @return
     */
    function html5Upload() {	
    	var tempFile,
    		sUploadURL;
    	sUploadURL= location.href.replace(/\/[^\/]*$/, '') + '/FileUploader_html5.php'; 	//upload URL
    	
    	//������ �ϳ��� ������, ����� ����.
    	for(var j=0, k=0; j < nImageInfoCnt; j++) {
    		tempFile = htImageInfo['img'+j];
    		try{
	    		if(!!tempFile){
	    			//Ajax����ϴ� �κ�. ���ϰ� ���δ��� url�� �����Ѵ�.
	    			callAjaxForHTML5(tempFile,sUploadURL);
	    			k += 1;
	    		}
	    	}catch(e){}
    		tempFile = null;
    	}
	}
    
    function callAjaxForHTML5 (tempFile, sUploadURL){
    	var oAjax = jindo.$Ajax(sUploadURL, {
			type: 'xhr',
			method : "post",
			onload : function(res){ // ��û�� �Ϸ�Ǹ� ����� �ݹ� �Լ�
				if (res.readyState() == 4) {
					//���� �ÿ�  responseText�� ������ array�� ����� �κ�.
					makeArrayFromString(res._response.responseText);
				}
			},
			timeout : 3,
			onerror :  jindo.$Fn(onAjaxError, this).bind()
		});
		oAjax.header("contentType","multipart/form-data");
		oAjax.header("file-name",encodeURIComponent(tempFile.name));
		oAjax.header("file-size",tempFile.size);
		oAjax.header("file-Type",tempFile.type);
		oAjax.request(tempFile);
    }
    
    function makeArrayFromString(sResString){
    	var	aTemp = [],
    		aSubTemp = [],
    		htTemp = {}
    		aResultleng = 0;
    	
 		try{
 			if(!sResString || sResString.indexOf("sFileURL") < 0){
 	    		return ;
 	    	}
 			aTemp = sResString.split("&");
	    	for (var i = 0; i < aTemp.length ; i++){
	    		if( !!aTemp[i] && aTemp[i] != "" && aTemp[i].indexOf("=") > 0){
	    			aSubTemp = aTemp[i].split("=");
	    			htTemp[aSubTemp[0]] = aSubTemp[1];
	    		}
	 		}
 		}catch(e){}
 		
 		aResultleng = aResult.length;
    	aResult[aResultleng] = htTemp;
    	
    	if(aResult.length == nImageFileCount){
    		setPhotoToEditor(aResult); 
    		aResult = null;
    		window.close();
    	}
    }
    
    /**
 	 * ���� ���� �ÿ� ȣ��Ǵ� �Լ�
 	 * @param {Object} sParentId 
 	 */
 	function delImage (sParentId){
 		var elLi = jindo.$$.getSingle("#"+sParentId);
 		
 		refreshTotalImageSize(sParentId);
 		
 		updateViewTotalSize();
 		updateViewCount(nImageFileCount,-1);
 		//���� file array���� ���� ����.
 		removeImageInfo(sParentId);
 		//�ش� li����
 		$Element(elLi).leave();
 		
 		//������ �̹����ΰ��.
 		if(nImageFileCount === 0){
 			readyModeBG();
 			//���� �߰� ��ư ��Ȱ��ȭ
 			goReadyMode();
 		}
 		
 		// drop ���� �̺�Ʈ �ٽ� Ȱ��ȭ.
 		if(!bAttachEvent){
 			addEvent();
 		}
 	}

 	/**
     * �̺�Ʈ �Ҵ�
     */
	function addEvent() {
		bAttachEvent = true;
		elDropArea.addEventListener("dragenter", dragEnter, false);
		elDropArea.addEventListener("dragexit", dragExit, false);
		elDropArea.addEventListener("dragover", dragOver, false);
		elDropArea.addEventListener("drop", drop, false);
	}
	
	function removeEvent(){
		bAttachEvent = false;
		elDropArea.removeEventListener("dragenter", dragEnter, false);
	    elDropArea.removeEventListener("dragexit", dragExit, false);
	    elDropArea.removeEventListener("dragover", dragOver, false);
	    elDropArea.removeEventListener("drop", drop, false);	
	}
 	
	/**
	 * Ajax ��� �� error�� �߻��� �� ó���ϴ� �Լ��Դϴ�.
	 * @return
	 */
	function onAjaxError (){
		alert("[���̵�]���� ���δ��� ����URL������ �ʿ��մϴ�.-onAjaxError"); //��ġ ���̵� �ȳ� ������. �� ���񽺿����� ����. 
	}

 	/**
      * �̹��� ���ε� ����
      * Ȯ�� ��ư Ŭ���ϸ� ȣ��Ǵ� msg
      */
     function uploadImage (e){
    	 if(!bSupportDragAndDropAPI){
    		 generalUpload();
    	 }else{
    		 html5Upload();
    	 }
     }
     
 	/**
 	 * jindo�� ���� ���ε� ���.(iframe�� Form�� Submit�Ͽ� �������þ��� ������ ���ε��ϴ� ������Ʈ)
 	 */
 	function callFileUploader (){
 		oFileUploader = new jindo.FileUploader(jindo.$("uploadInputBox"),{
 			sUrl  :  location.href.replace(/\/[^\/]*$/, '') +'/FileUploader.php',	//���� URL�Դϴ�.
 	        sCallback : location.href.replace(/\/[^\/]*$/, '') + '/callback.html',	//���ε� ���Ŀ� iframe�� redirect�� �ݹ��������� �ּ�
 	    	sFiletype : "*.jpg;*.png;*.bmp;*.gif",						//����� ������ ����. ex) "*", "*.*", "*.jpg", ������(;)	
 	    	sMsgNotAllowedExt : 'JPG, GIF, PNG, BMP Ȯ���ڸ� �����մϴ�',	//����� ������ ������ �ƴѰ�쿡 ����ִ� ���â�� ����
 	    	bAutoUpload : false,									 	//������ ���õʰ� ���ÿ� �ڵ����� ���ε带 �������� ���� (upload �޼ҵ� ����)
 	    	bAutoReset : true 											// ���ε��� ���Ŀ� �������� ���� ��ų�� ���� (reset �޼ҵ� ����)
 	    }).attach({
 	    	select : function(oCustomEvent) {
 	    		//���� ������ �Ϸ�Ǿ��� �� �߻�
// 		    	 oCustomEvent (�̺�Ʈ ��ü) = {
// 	    			sValue (String) ���õ� File Input�� ��
// 	    			bAllowed (Boolean) ���õ� ������ ������ ���Ǵ� �������� ����
// 	    			sMsgNotAllowedExt (String) ������ �ʴ� ���� ������ ��� ����� ���޼���
// 	    		}
//  				���õ� ������ ������ ���Ǵ� ��츸 ó�� 
 	    		if(oCustomEvent.bAllowed === true){
 		    		goStartMode();
 		    	}else{
 		    		goReadyMode();
 		    		oFileUploader.reset();
 		    	}
// 	    		bAllowed ���� false�� ��� �������� �Բ� alert ���� 
// 	    		oCustomEvent.stop(); ����� bAllowed �� false�̴��� alert�� ������� ����
 	    	},
 	    	success : function(oCustomEvent) {
 	    		//alert("success");
 	    		// ���ε尡 ���������� �Ϸ�Ǿ��� �� �߻�
 	    		// oCustomEvent(�̺�Ʈ ��ü) = {
 	    		//	htResult (Object) �������� �������ִ� ��� ��ü (���� ������ ���� ���������� ���ð���)
 	    		// }
 	    		var aResult = []; 
 	    		aResult[0] = oCustomEvent.htResult;
 	    		setPhotoToEditor(aResult); 
 	    		//��ư ��Ȱ��ȭ
 	    		goReadyMode();
 	    		oFileUploader.reset();
				window.close();
 	    	},
 	    	error : function(oCustomEvent) {
 	    		//���ε尡 �������� �� �߻�
 	    		//oCustomEvent(�̺�Ʈ ��ü) = {
 	    		//	htResult : { (Object) �������� �������ִ� ��� ��ü. �����߻��� errstr ������Ƽ�� �ݵ�� �����ϵ��� ���� ������ �����Ͽ����Ѵ�.
 	    		//		errstr : (String) �����޽���
 	    		// 	}
 	    		//}
 	    		//var wel = jindo.$Element("info");
 	    		//wel.html(oCustomEvent.htResult.errstr);
 	    		alert(oCustomEvent.htResult.errstr);
 	    	}
 	    });
 	}
	
    /**
     * ������ �ݱ� ��ư Ŭ��
     */
    function closeWindow(){
	   	if(bSupportDragAndDropAPI){
	   		removeEvent();
	   	}
	 //  	window.close();
    }
    
	window.onload = function(){
  		checkDragAndDropAPI();
  		
  		
  		if(bSupportDragAndDropAPI){
  			$Element("pop_container2").hide();
  			$Element("pop_container").show();
  			
  			welTextGuide.removeClass("nobg");
  			welTextGuide.className("bg");
  			
  			addEvent();
  		} else {
  			$Element("pop_container").hide();
  			$Element("pop_container2").show();
  			callFileUploader();
  		}
  		fnUploadImage = $Fn(uploadImage,this);
  		$Fn(closeWindow,this).attach(welBtnCancel.$value(), "click");
	};

	/**
	 *  �����κ��� ���� ����Ÿ�� �����Ϳ� �����ϰ� â�� ����.
	 * @parameter aFileInfo [{},{},...] 
	 * @ex aFileInfo = [
	 * 	{
			sFileName : "nmms_215646753.gif",
			sFileURL :"http://static.naver.net/www/u/2010/0611/nmms_215646753.gif",
			bNewLine : true
		},
		{
			sFileName : "btn_sch_over.gif",
			sFileURL :"http://static1.naver.net/w9/btn_sch_over.gif",
			bNewLine : true
		}
	 * ]
	 */
 	function setPhotoToEditor(oFileInfo){
		if (!!opener && !!opener.nhn && !!opener.nhn.husky && !!opener.nhn.husky.PopUpManager) {
			//����Ʈ ������ �÷������� ���ؼ� �ִ� ��� (oFileInfo�� Array)
			opener.nhn.husky.PopUpManager.setCallback(window, 'SET_PHOTO', [oFileInfo]);
			//������ �ٷ� tag�� �ִ� ��� (oFileInfo�� String���� <img src=....> )
			//opener.nhn.husky.PopUpManager.setCallback(window, 'PASTE_HTML', [oFileInfo]);
		}
	}
 	
 	// 2012.05 ����] jindo.$Ajax.prototype.request���� file�� form�� �������� ����. 
 	jindo.$Ajax.prototype.request = function(oData) {
 		this._status++;
 		var t   = this;
 		var req = this._request;
 		var opt = this._options;
 		var data, v,a = [], data = "";
 		var _timer = null;
 		var url = this._url;
 		this._is_abort = false;

 		if( opt.postBody && opt.type.toUpperCase()=="XHR" && opt.method.toUpperCase()!="GET"){
 			if(typeof oData == 'string'){
 				data = oData;
 			}else{
 				data = jindo.$Json(oData).toString();	
 			}	
 		}else if (typeof oData == "undefined" || !oData) {
 			data = null;
 		} else {
 			data = oData;
 		}
 		
 		req.open(opt.method.toUpperCase(), url, opt.async);
 		if (opt.sendheader) {
 			if(!this._headers["Content-Type"]){
 				req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded; charset=utf-8");
 			}
 			req.setRequestHeader("charset", "utf-8");
 			for (var x in this._headers) {
 				if(this._headers.hasOwnProperty(x)){
 					if (typeof this._headers[x] == "function") 
 						continue;
 					req.setRequestHeader(x, String(this._headers[x]));
 				}
 			}
 		}
 		var navi = navigator.userAgent;
 		if(req.addEventListener&&!(navi.indexOf("Opera") > -1)&&!(navi.indexOf("MSIE") > -1)){
 			/*
 			 * opera 10.60���� XMLHttpRequest�� addEventListener�� �߰��Ǿ����� ���������� �������� �ʾ� opera�� ������ dom1������� ������.
 			 * IE9������ opera�� ���� ������ ����.
 			 */
 			if(this._loadFunc){ req.removeEventListener("load", this._loadFunc, false); }
 			this._loadFunc = function(rq){ 
 				clearTimeout(_timer);
 				_timer = undefined; 
 				t._onload(rq); 
 			}
 			req.addEventListener("load", this._loadFunc, false);
 		}else{
 			if (typeof req.onload != "undefined") {
 				req.onload = function(rq){
 					if(req.readyState == 4 && !t._is_abort){
 						clearTimeout(_timer); 
 						_timer = undefined;
 						t._onload(rq);
 					}
 				};
 			} else {
 	            /*
 				 * IE6������ onreadystatechange�� ���������� ����Ǿ� timeout�̺�Ʈ�� �߻��ȵ�.
 				 * �׷��� interval�� üũ�Ͽ� timeout�̺�Ʈ�� ���������� �߻��ǵ��� ����. �񵿱� ����϶���
 		
 	             */
 				if(window.navigator.userAgent.match(/(?:MSIE) ([0-9.]+)/)[1]==6&&opt.async){
 					var onreadystatechange = function(rq){
 						if(req.readyState == 4 && !t._is_abort){
 							if(_timer){
 								clearTimeout(_timer);
 								_timer = undefined;
 							}
 							t._onload(rq);
 							clearInterval(t._interval);
 							t._interval = undefined;
 						}
 					};
 					this._interval = setInterval(onreadystatechange,300);

 				}else{
 					req.onreadystatechange = function(rq){
 						if(req.readyState == 4){
 							clearTimeout(_timer); 
 							_timer = undefined;
 							t._onload(rq);
 						}
 					};
 				}
 			}
 		}

 		req.send(data);
 		return this;
 	};