
	function sns_twitter(url,title){
		var content = escape(url);
		window.open("http://twitter.com/home?status=" + encodeURIComponent(title) + " " + content, 'twitter', '');
	}


	function sns_facebook(url,title){
		var content = escape(url);
		window.open('http://facebook.com/sharer.php?u='+content+'&t='+encodeURIComponent(title),'facebook','','');
	}
