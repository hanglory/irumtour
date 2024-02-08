var scrollTop,			// 스크롤 높이
	 gnbTop,				// GNB Top
	 lnbTop,				// LNB Top

	 win_w,				// 윈도우 넓이 - 메인
	 win_h,				// 윈도우 높이 - 메인

	 lyNums = 0,			// 레이어에 사용
	 lyOne = 0;			// 레이어에 사용

var pgCode, depth_1, depth_2, depth_3, depth_4;



$(window).load(function(){
	// 오픈시 지워야함
	$("body").append('<p class="testText"></p>');


	// 왼쪽메뉴열기_모바일
	$("body").on("click ", ".btnMenu_m", function(){
		winH = $(window).height();
		var gh = $("#gnb").height();
		$("#menuArea").fadeIn(300);
		$("#wrap").addClass("noScroll");
		TweenLite.to($("#wrap"), 0.3, {height:winH, "position":"relative"});
		TweenLite.to($("#wrap .aIn"), 0.3, {right:-528, "position":"absolute", delay:0.3});
		TweenLite.to($("#menuArea .menuList"), 0.3, {right:0, delay:0.3});
		$("body").append("<div id='grayLayer'><a href='#'></a></div>");
		$("#grayLayer").show().css("height",gh);
		//$("#wrap").bind('touchmove', function(e){e.preventDefault()});

	});

	// 왼쪽메뉴닫기_모바일
	$("body").on("click ", "#menuArea .btnMenu_mClose", function(){
		
		$("#menuArea").fadeOut(300);
		$("#wrap").removeClass("noScroll");
		TweenLite.to($("#wrap"), 0.3, {height:"auto", "position":"relative"});
		TweenLite.to($("#wrap .aIn"), 0.3, {right:0, "position":"relative"});
		TweenLite.to($("#menuArea .menuList"), 0.3, {right:-528});
		$("#grayLayer").remove();
		//$("#wrap").unbind('touchmove');
	});

	// wrap클릭시 메뉴닫기
	$("body").on("click ", "#grayLayer", function(){

		$("#menuArea").fadeOut(300);
		$("#wrap").removeClass("noScroll");
		TweenLite.to($("#wrap"), 0.3, {height:"auto", "position":"relative"});
		TweenLite.to($("#wrap .aIn"), 0.3, {right:0, "position":"relative"});
		TweenLite.to($("#menuArea .menuList"), 0.3, {right:-528});
		$("#grayLayer").remove();
		//$("#wrap").unbind('touchmove');
	});


	// 왼쪽 하위 메뉴
	$("body").on("click ", "#menuArea .menuList>.list>li", function(){
		var idx = $("#menuArea .menuList>.list>li").index($(this));
		$("#menuArea .menuList>.list>li").each(function(index){
			if(idx == index){
				if(!$(this).hasClass("active")){
					$(this).addClass("active");
					$(this).find(".sMenu").slideDown(300);
				}else{
					$(this).removeClass("active");
					$(this).find(".sMenu").slideUp(300);
				}
			}else{
				$(this).removeClass("active");
				$(this).find(".sMenu").slideUp(300);
			}
		});
	});


	$(window).on('throttledresize', function(){
		$(".depth3Menu .depth3sub").attr("style", ""); //
	}).resize();

});


// 레이어 열기
var layOne = true;
function openLay(name){
	if(layOne == true){
		winH = $(window).height();
		//TweenLite.to($("#wrap"), 0.3, {height:winH, "position":"relative"});
		//TweenLite.to($("#wrap #wrapArea"), 0.3, {"position":"absolute", delay:0.3});

		lyNums++;
		$(".layerBox").each(function(index){
			if($(this).hasClass(name)){
				layOne = false;
				$(this).layerScript({divs : name});
			}

		});

		// 레이어 스크롤
		$("body").on("scroll", "#layerArea", function(e){
			var siteTop = $("#layerBg").scrollTop();
			if(siteTop > 30){
				$(".layerArea .title").addClass("fixed");
			}else{
				$(".layerArea .title").removeClass("fixed");
			}
		});
	}
}
// 레이어 닫기
function layerClose(name){
	$(".layerBox").each(function(index){
		if($(this).hasClass(name)){
			var e = $(this);

			e.hide().attr("style","");
			$(".layerBgIn").remove();
			$(".layerBox").eq(0).show();

			lyNums --;
			if(lyNums == 0){
				$("html").css("overflow-y","auto");
				$("body").removeClass("lyOn");
				$("#layerBg").remove();
				$("#layerArea").removeClass("ons");
				$(".layerBox").hide();

				TweenLite.to($("#wrap"), 0.3, {height:"auto", "position":"relative"});
				TweenLite.to($("#wrap #wrapArea"), 0.3, {"position":"relative"});
			}
		}
	});
}

// Plugin Script
jQuery(function($){

	//[s] Layer Script
	$.fn.layerScript = function(o){
		o = $.extend({
			divs : ''
		}, o || {});

		var e = $(this),
			  bg = $('<div id="layerBg"></div>'),
			  bgIn = $('<div class="layerBgIn"></div>'),
			  ly_w,
			  ly_h,
			  closeDiv = o.divs;

		//tab
		if(e.hasClass("tab")){
			// 플러그인 텝 메뉴
			$(".tabArea.ly").tabScript({
				btns : '.btnTab>a',
				conts : '.tabConts',
				classd: 'active',
				nums: '0'
			});
		}

		$("body").attr("lyNums", lyNums);
		// 열기
		if(!$("body").hasClass("lyOn")){
			$("html").css("overflow-y","hidden");
			$("body").addClass("lyOn");
			$("#layerArea").addClass("ons");
			bg.prependTo($("body"));
		}

		e.show();
		layOne = true;

		if(lyNums == 2){
			bgIn.prependTo($("#layerArea .layerIn"));
			$(".layerBox").eq(0).hide();
			e.css("z-index",13);
		}

		// select box 디자인
		e.find(".selectType.lay").each(function(index){
			if(!$(this).hasClass("on")){
				$(this).addClass("on");
				$(this).find("select.selectCus").customSelect({customClass:"selType"});
			}
		});

		// 닫기
		$(this).find(".closeLy").off("click");
		$(this).find(".closeLy").on('click', function(){
			layerClose(closeDiv);
		});
	}
	//[e] Layer Script

	
});



