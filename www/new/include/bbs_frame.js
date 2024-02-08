function calcHeight(id){
  var the_width=document.getElementById(id).contentWindow.document.body.scrollWidth;
  var the_height=	document.getElementById(id).contentWindow.document.body.scrollHeight;
  document.getElementById(id).width=the_width;
  document.getElementById(id).height=the_height;
}