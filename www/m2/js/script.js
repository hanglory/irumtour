// 로드시
$(document).ready(function(){
    prevent_a();

	// 메뉴 클릭시 스크롤 스피드 
	// $(window).load(function(){
	// 	$(".gnb a").mPageScroll2id({ //PC메뉴
	// 		scrollSpeed:900
	// 	});
	// });
});

//a 태그 클릭시 이동하지 않도록
function prevent_a() {
	$(document).on('click', 'a[href="#"]', function(e){
	    e.preventDefault();
	});
}












