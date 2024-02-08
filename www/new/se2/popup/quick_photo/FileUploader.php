<?php
//기본 리다이렉트
echo $_REQUEST["htImageInfo"];

$url = $_REQUEST["callback"] .'?callback_func='. $_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);
if (bSuccessUpload) { //성공 시 파일 사이즈와 URL 전송



    $tmp_name = $_FILES['Filedata']['tmp_name'];//기존코드1

    /*se2 이미지 여부 확인 220630 s*/
    $chk_img = getimagesize($tmp_name);
    if($chk_img[2]>0 && $chk_img[2]<=16){//이미지
    }else{//이미지 아님
        exit;
    }
    /*se2 이미지 여부 확인f*/

    $name =  substr(str_replace(" ","_",microtime()),2) . substr($_FILES['Filedata']['name'],-4);//기존코드2


	$new_path = "../../../public/se_upload/".$name;
	@move_uploaded_file($tmp_name, $new_path);

	//파일 리사이즈 시작
	$src=$new_path;        //-- 원본
	$fix_w = 900; //가로사이즈
	$pic_info = getimagesize($src);
	if($fix_w < $pic_info[0]){
		$percent = round(($fix_w / $pic_info[0]),2);
		$fix_h = ceil($pic_info[1]*$percent);

		$dst= $src;     //-- 저장
		$quality = '100';    //-- jpg 퀄리티
		$ratio = 'true';        //-- 원본 이미지비율을 유지
		$get_size = _getimagesize($src, $fix_w,$fix_h);
		resize_image($dst, $src, $get_size, $quality, $ratio);
	}
	//파일 리사이즈 종료


	$url .= "&bNewLine=true";
	$url .= "&sFileName=".$name;
	$url .= "&size=". $_FILES['Filedata']['size'];
	//아래 URL을 변경하시면 됩니다.
	$url .= "&sFileURL=http://${_SERVER[HTTP_HOST]}/new/public/se_upload/".$name;


} else { //실패시 errstr=error 전송
	$url .= '&errstr=error';
}
header('Location: '. $url);


//이이지 라사이즈용 함수
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

// $img : 원본이미지
// $m : 목표크기 pixel
// $ratio : 비율 강제설정
function _getimagesize($img, $w,$h){
    $v = @getImageSize($img);
	return array_merge($v, array("w"=>$w, "h"=>$h));
}

?>
