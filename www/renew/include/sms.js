function calcLen(no) {
var memo_len = "memo" + no + "_len";
var memo_txt = "memo" + no + "_txt";
var obj = document.getElementById(memo_len);
var remain = cal_length(document.getElementById(memo_txt).value);
obj.innerHTML = remain;
if ( remain < 0 ) {
obj.style.color = "red";
} else {
obj.style.color = "black";
}
window.setTimeout('calcLen('+no+')',200);
}

function cal_length(val) {

	var temp_estr = escape(val);
	var s_index = 0;
	var e_index = 0;
	var temp_str = "";
	var cnt = 0;

	while ((e_index = temp_estr.indexOf("%u", s_index)) >= 0)
	{
		temp_str += temp_estr.substring(s_index, e_index);
		s_index = e_index + 6;
		cnt ++;
	}


	temp_str += temp_estr.substring(s_index);
	temp_str = unescape(temp_str);
	return ((cnt * 2) + temp_str.length) + "";
}