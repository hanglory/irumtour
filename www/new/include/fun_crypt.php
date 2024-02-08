<?php

class DesComponent {
	var $key = 'korTiger';

	function encrypt($string) {
		$iv="korTiger";
 		$size = mcrypt_get_block_size ( MCRYPT_DES, MCRYPT_MODE_CBC );
        $string = $this->pkcs5Pad ( $string, $size );

		$data =  mcrypt_encrypt(MCRYPT_DES, $this->key, $string, MCRYPT_MODE_CBC, $iv);

		$data = base64_encode($data);
		return $data;
	}

	function decrypt($string) {
		$iv="korTiger";
		$string = base64_decode($string);
		$result =  mcrypt_decrypt(MCRYPT_DES, $this->key, $string, MCRYPT_MODE_CBC, $iv);
   $result = $this->pkcs5Unpad( $result );

		return $result;
	}

	 function pkcs5Pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen ( $text ) % $blocksize);
        return $text . str_repeat ( chr ( $pad ), $pad );
    }

    function pkcs5Unpad($text)
    {
        $pad = ord ( $text {strlen ( $text ) - 1} );
        if ($pad > strlen ( $text ))
            return false;
        if (strspn ( $text, chr ( $pad ), strlen ( $text ) - $pad ) != $pad)
            return false;
        return substr ( $text, 0, - 1 * $pad );
    }

}

/*
$des = new DesComponent();
echo "인코딩 : ";
echo $des->encrypt("super");
echo "<BR>디코딩 : ";
echo $des->decrypt("RSLT3DQwNzE=");
*/



/*SHA-512 hash 암호화*/
function enc_hash($data,$hash_type="sha512"){
    return hash($hash_type, $data, false);    
}




function enc_aes($data,$crypt_pass='',$crypt_iv=''){
    global $acode;
    global $user_key;
    if($data){
        if(!$crypt_pass) $crypt_pass=$acode;
        if(!$crypt_iv) $crypt_iv=$user_key;
        $endata = @openssl_encrypt($data , "aes-128-cbc", $crypt_pass, true, $crypt_iv);
        $endata = base64_encode($endata);
        // $endata = str_replace("+","{P}",$endata);
        $endata = str_replace("+","__P__",$endata);
        return $endata;
    }
}

function dec_aes($endata,$crypt_pass='',$crypt_iv=''){
    global $acode;
    global $user_key;
    if($endata){
        $endata = str_replace("{P}","+",$endata);
        $endata = str_replace("__P__","+",$endata);
        if(!$crypt_pass) $crypt_pass=$acode;
        if(!$crypt_iv) $crypt_iv=$user_key;    
        $data = base64_decode($endata);
        return @openssl_decrypt($data, "aes-128-cbc", $crypt_pass, true, $crypt_iv);
    }
}
?>