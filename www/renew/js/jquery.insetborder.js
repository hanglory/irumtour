/*
	Author: Guilherme Oliveira
	You are free to use this plug in in any way you want non-commercially and commercially. 
	However if you redistribute (even when altered by you) you have to give credit to me. 
	How you give me credit is up to you. Here are two links you could link off to:
	
	https://twitter.com/_holiveira
	http://ghophp.github.io/

	OBS: Keep in mind that this plugin requires the hook to a position:relative element
*/

(function($) {
	
	$.fn.borderEffect = function(options) {
		
		options = $.extend({
			speed : 150,
			borderWidth : 3,
			borderColor : '#b58f27',
			borderClass : "border-effect",
			zIndex : 100
		}, options);
		
		return this.each(function(i) {

			$(this).hover(
				function() {
					
					$(this).find('.' + options.borderClass).animate({
						"border-width": options.borderWidth + "px",
						"opacity": "1"
					}, {
						"duration": options.speed, 
						"queue": false
					});
				},
				function() {

					$(this).find('.' + options.borderClass).animate({
						"border-width": "0px",
						"opacity": "0"
					}, {
						"duration": options.speed, 
						"queue": false
					});

				}
			);

			if($(this).find('.' + options.borderClass).length <= 0){

				var border = $("<div />", {
					"class": options.borderClass,
					"css"  : {
						"position": "absolute",
						"display": "block",
						"border": "0px solid " + options.borderColor,
						"top": 0,
						"bottom": 0,
						"right": 0,
						"left": 0,
						"opacity": "0",
						"z-index": options.zIndex
					}
				});

				$(this).append(border);
			}

			$(this).css('position', 'relative');
		});

	};
	
})(jQuery);