<?php
 	$sFileInfo = '';
	$headers = array();
	foreach ($_SERVER as $k => $v){

		if(substr($k, 0, 9) == "HTTP_FILE"){
			$k = substr(strtolower($k), 5);
			$headers[$k] = $v;
		}
	}

	$file = new stdClass;
	//$file->name = rawurldecode($headers['file_name']);
	$file->name = substr(str_replace(" ","_",microtime()),2) . substr(rawurldecode($headers['file_name']),-4);
	$file->size = $headers['file_size'];
	$file->content = file_get_contents("php://input");

	$newPath = '../../../new/public/se_upload/'.iconv("utf-8", "cp949", $file->name);

	if(file_put_contents($newPath, $file->content)) {


		//���� �������� ����
		$src=$newPath;        //-- ����
		$fix_w = 600; //���λ�����
		$pic_info = getimagesize($src);
		if($fix_w < $pic_info[0]){
			$percent = round(($fix_w / $pic_info[0]),2);
			$fix_h = ceil($pic_info[1]*$percent);

			$dst= $src;     //-- ����
			$quality = '100';    //-- jpg ����Ƽ
			$ratio = 'true';        //-- ���� �̹��������� ����
			$get_size = _getimagesize($src, $fix_w,$fix_h);
			resize_image($dst, $src, $get_size, $quality, $ratio);
		}
		//���� �������� ����


		$sFileInfo .= "&bNewLine=true";
		$sFileInfo .= "&sFileName=".$file->name;
		$sFileInfo .= "&sFileURL=http://" . $_SERVER["HTTP_HOST"] . "/new/public/se_upload/".$file->name;
	}
	echo $sFileInfo;



//������ �������� �Լ�
function resize_image($destination, $departure, $size, $quality='80', $ratio='false'){

    if($size[2] == 1)    //-- GIF
        $src = imageCreateFromGIF($departure);
    elseif($size[2] == 2) //-- JPG
        $src = imageCreateFromJPEG($departure);
    else    //-- $size[2] == 3, PNG
        $src = imageCreateFromPNG($departure);
	    $dst = imagecreatetruecolor($size['w'], $size['h']);


    $dstX = 0;
    $dstY = 0;
    $dstW = $size['w'];
    $dstH = $size['h'];

    if($ratio != 'false' && $size['w']/$size['h'] <= $size[0]/$size[1]){
        $srcX = ceil(($size[0]-$size[1]*($size['w']/$size['h']))/2);
        $srcY = 0;
        $srcW = $size[1]*($size['w']/$size['h']);
        $srcH = $size[1];
    }elseif($ratio != 'false'){
        $srcX = 0;
        $srcY = ceil(($size[1]-$size[0]*($size['h']/$size['w']))/2);
        $srcW = $size[0];
        $srcH = $size[0]*($size['h']/$size['w']);
    }else{
        $srcX = 0;
        $srcY = 0;
        $srcW = $size[0];
        $srcH = $size[1];
    }



    @imagecopyresampled($dst, $src, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
    @imagejpeg($dst, $destination, $quality);
    @imagedestroy($src);
    @imagedestroy($dst);

    return TRUE;
}

// $img : �����̹���
// $m : ��ǥũ�� pixel
// $ratio : ���� ��������
function _getimagesize($img, $w,$h){
    $v = @getImageSize($img);
	return array_merge($v, array("w"=>$w, "h"=>$h));
}
 ?>
