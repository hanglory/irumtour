function fgn_no_chksum(reg_no) {
    var sum = 0;
    var odd = 0;
    
    buf = new Array(13);
    for (i = 0; i < 13; i++) buf[i] = parseInt(reg_no.charAt(i));

    odd = buf[7]*10 + buf[8];
    
    if (odd%2 != 0) {
      return false;
    }

    if ((buf[11] != 6)&&(buf[11] != 7)&&(buf[11] != 8)&&(buf[11] != 9)) {
      return false;
    }
    	
    multipliers = [2,3,4,5,6,7,8,9,2,3,4,5];
    for (i = 0, sum = 0; i < 12; i++) sum += (buf[i] *= multipliers[i]);


    sum=11-(sum%11);
    
    if (sum>=10) sum-=10;

    sum += 2;

    if (sum>=10) sum-=10;

    if ( sum != buf[12]) {
        return false;
    }
    else {
        return true;
    }
}