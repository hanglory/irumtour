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
?>