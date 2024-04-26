<?
// ���� �̹����� �ѱ�� ������ ���� ����� �̹����� ������
// ����, ����, ���ϰ��, �������, true
function createThumb($imgWidth, $imgHeight, $imgSource, $imgThumb='', $iscut=false)
{
    if (!$imgThumb)
        $imgThumb = $imgSource;

    $size = getimagesize($imgSource);

    if ($size[2] == 1)
        $source = imagecreatefromgif($imgSource);
    else if ($size[2] == 2)
        $source = imagecreatefromjpeg($imgSource);
    else if ($size[2] == 3)
        $source = imagecreatefrompng($imgSource);
    else
        return;

    $rate = $imgWidth / $size[0];
    $height = (int)($size[1] * $rate);

    if ($height < $imgHeight) {

        $target = @imagecreatetruecolor($imgWidth, $height);

    } else {

        $target = @imagecreatetruecolor($imgWidth, $imgHeight);

    }

    @imagecopyresampled($target, $source, 0, 0, 0, 0, $imgWidth, $height, $size[0], $size[1]);
    @imagejpeg($target, $imgThumb, 100);
    @chmod($imgThumb, 0606); // ���� ������ ���Ͽ� ���ϸ�� ����
}

// ���α׷� : �Ҵ�� 2.0.x
// �� �� �� : �ƺ��Ҵ� (echo4me@gmail.com)
// ���̼��� : ���α׷�(���� �Ҵ��)�� ���̼����� GPL�̸�, �����α׷��� ���� �Ǵ� �����Ͽ� �Ǹ��ϴ� ���� ������� �ʽ��ϴ�.
//            �Ҵ���� ������ sir.co.kr�� opencode.co.kr������ �� �� �ֽ��ϴ�.

@ini_set("memory_limit", "512M");

// $file_name   : ���ϸ�
// $width       : ������� ��
// $height      : ������� ���� (�������� ������ ������� ���̸� ���)
//                * $width, $height�� ��� ���� ������, �̹��� ������ �״�� thumb�� ����
// $is_create   : ������� �̹� ���� ��, ���� �������� ���θ� ����
// $is_crop     : ���� ���̰� $height�� ���� �� crop �� �������� ����
//                0 : crop ���� �ʽ��ϴ�
//                1 : �⺻ crop
//                2 : �߰��� �������� crop
// $quality     : ������� quality (jpeg, png���� �ش��ϸ�, gif���� �ش� ����)
// $small_thumb : true(1)�̸�, �̹����� ������� ��/���̺��� ���� ������ ���� ����
// $watermark   : ���͸�ũ ��¿� ���� ����
//                $watermark[][filename] - ���͸�ũ ���ϸ�
//                $watermark[location] - center, top, top_left, top_right, bottom, bottom_left, bottom_right
//                $watermark[x],$watermark[y] - location������ offset
// $filter      : php imagefilter, http://kr.php.net/imagefilter
//                $filter[type], [arg1] ... [arg4]
// $noimg       : $noimg(�̹�������)
/*
thumbnail�� if �����Դϴ�. ����ȭ ���� �ʰ� �ʹ� ���� if�� ����Ƚ��ϴ�. ��..��...

$width�� ���� ������
    - $height�� ���� ������
       - $width > �̹���ũ��
           - $height > �̹���ũ�� : �̹��� ũ���� ���� ����
           - else
                - $is_crop : ũ��
                - else : ������� ���� ����
       - else
          $ratio�� $height�� ���ؼ�,
          - $height > $tmp_y : ������� �� ���� (���̰� �� ���� �մϴ�) <-- �̺κп��� ���̸� ���߰� �б⸦ crop���ڴ� �ǰߵ� �־��
          - else : �̹��� ������ ������ �� ���̸� crop
    - $height�� ���� ������ (crop ���� �ʽ��ϴ�)
       - $width�� �̹��� ũ�⺸�� �� ũ�� : �̹��� ũ���� ���� ����
       - else : ������� ���� ����

$width�� ���� ������ (���̷θ� �����ϴ� �������� ���)
    - $height�� �̹��� ũ�⺸�� �� ũ�� : �̹��� ũ���� ���� ����
    - else
        - $is_crop : crop
        - else : ������� ���� ����
*/
function thumbnail($file_name, $width=0, $height=0, $is_create=false, $is_crop=2, $quality=70, $small_thumb=true, $watermark="", $filter="", $noimg="")
{
    if (!$file_name)
        return;

    // ����� ���丮
    $real_dir = dirname($_SERVER['DOCUMENT_ROOT'] . "/nothing");
    $dir = dirname(file_path($file_name));
    $file = basename($file_name);

    $thumb_dir = $dir . "/thumb";

    // ������� ������ ���丮
    $thumb_path = $thumb_dir . "/" . $width . "x" . $height . "_" . $quality;

    if (!file_exists($thumb_dir)) {
        @mkdir($thumb_dir, 0707);
        @chmod($thumb_dir, 0707);
    }

    if (!file_exists($thumb_path)) {
        @mkdir($thumb_path, 0707);
        @chmod($thumb_path, 0707);
    }

    $source_file = $dir . "/" . $file;

    $size = @getimagesize($source_file);

    // animated gif�� ���ؼ� ���� ����� ������ �ڸ�Ʈ�� Ǯ���ּ���. �Ʒ��ڵ�� cpu�� disk access�� ũ�� ������ �� �ֽ��ϴ�
    //if ($size[2] == IMG_GIF && is_ani_gif($file_name)) return;

    // �̹��� ������ ���� ���
    if (!$size[0]) {

        // $nomimg�� ������ ������ �� �̹��� ������ ����
        if (!$noimg && !file_exists($noimg)) {

            if (!$height) $height = $width;
            $thumb_file = $thumb_dir . "/" . $width . "x" . $height . "_noimg.gif";
            // noimg �������� �����ϴ� ��쿡�� ���� noimg �������� �ȸ���ϴ�.
            if (!@file_exists($thumb_file)) {
                if (!$width)
                    $width = 30;
                if (!$height)
                    $height = 30;
                $target = imagecreate($width, $height);
                imagecolorallocate($target, 250, 250, 250);
                imagecopy($target, $target, 0, 0, 0, 0, $width, $height);
                imagegif($target, $thumb_file, $quality);
                @chmod($thumb_file, 0606); // ���� ������ ���Ͽ� ���ϸ�� ����
            }
            return str_replace($real_dir, "", $thumb_file);

        } else {
            return $noimg;
        }
    }

    $thumb_file = $thumb_path . "/" . $file;

    // �������� �����鼭 �ҽ����Ϻ��� ���� ��¥�� �ֱ��� ��
    if (@file_exists($thumb_file)) {
        $thumb_time = @filemtime($thumb_file);
        $source_time = @filemtime($source_file);
        if ($is_create == false && $source_time < $thumb_time) {
            return str_replace($real_dir, "", $thumb_file);
        }
    }

    // $width, $height ���� ��� ���� ���� ���� ������ �״�� thumb�� ����
    if (!$width && !$height)
        $width = $size[0];

    // ���� �̹����� ��쿡�� ������� �����ϴ� �ɼ��� ����, ���� �̹����� size�� thumb���� ������ ������� ������ �ʴ´� (���̰� �������� ������ pass~!)
    if (!$small_thumb && $width >= $size[0] && $height && $height >= $size[1])
        return str_replace($real_dir, "", $source_file);

    $is_imagecopyresampled = false;
    $is_large = false;

    if ($size[2] == 1)
        $source = imagecreatefromgif($source_file);
    else if ($size[2] == 2)
        $source = imagecreatefromjpeg($source_file);
    else if ($size[2] == 3)
        $source = imagecreatefrompng($source_file);
    else if ($size[2] == 6)
    {
        // bmp ������ gif �������� ������� ����
        $source = ImageCreateFromBMP($source_file);
        $size[2] = 1;
    }

    if ($width) {
        $x = $width;
        if ($height) {

            if ($width > $size[0]) {  // $width�� �̹��� ������ Ŭ�� ($width�� resize�� ���ʿ�)
                if ($height > $size[1]) {
                    $x = $size[0];
                    $tmp_y = $size[1];
                    $target = imagecreatetruecolor($x, $tmp_y);
                    imagecopyresampled($target, $source, 0, 0, 0, 0, $x, $tmp_y, $size[0], $size[1]);
                } else {
                    if ($is_crop) { // ��ġ�� ���̸� �߶���� �մϴ�
                        $x = $size[0];
                        $y = $size[1];
                        $tmp_y = $height;
                        $target = imagecreatetruecolor($x, $tmp_y);
                        $tmp_target = imagecreatetruecolor($x, $tmp_y);
                        imagecopyresampled($tmp_target, $source, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);
                        imagecopy($target, $tmp_target, 0, 0, 0, 0, $x, $tmp_y);
                    } else {
                        $y = $height;
                        $rate = $y / $size[1];
                        $x = (int)($size[0] * $rate);
                        $target = imagecreatetruecolor($x, $y);
                        imagecopyresampled($target, $source, 0, 0, 0, 0, $x, $y, $size[0], $size[1]);
                    }
                }
            } else { // $width�� �̹��� ������ ���� �� (���� resize�� �ʿ�)
                $y = $height;
                $rate = $x / $size[0];
                $tmp_y = (int)($size[1] * $rate);
                if ($height > $tmp_y) {
                    if ($is_crop) {     // ���̰� �����Ƿ� �̹����� ���� crop
                        $rate = $y / $size[1];
                        $tmp_x = (int)($size[0] * $rate);
                        $target = imagecreatetruecolor($x, $y);
                        $tmp_target = imagecreatetruecolor($tmp_x, $y);
                        imagecopyresampled($tmp_target, $source, 0, 0, 0, 0, $tmp_x, $y, $size[0], $size[1]);
                        // copy�ϴ� ��ġ�� �̹����� �����߽��� �ǰ� ����
                        $src_x = (int)(($tmp_x - $x)/2);
                        imagecopy($target, $tmp_target, 0, 0, $src_x, 0, $x, $y);
                    } else {
                        $target = imagecreatetruecolor($x, $tmp_y);
                        imagecopyresampled($target, $source, 0, 0, 0, 0, $x, $tmp_y, $size[0], $size[1]);
                    }
                } else {
                    if ($is_crop) {
                        $target = imagecreatetruecolor($x, $y);
                        $tmp_target = imagecreatetruecolor($x, $tmp_y);
                        imagecopyresampled($tmp_target, $source, 0, 0, 0, 0, $x, $tmp_y, $size[0], $size[1]);
                        imagecopy($target, $tmp_target, 0, 0, 0, 0, $x, $y);
                    } else {
                        $rate = $y / $size[1];
                        $tmp_x = (int)($size[0] * $rate);
                        $target = imagecreatetruecolor($tmp_x, $y);
                        imagecopyresampled($target, $source, 0, 0, 0, 0, $tmp_x, $y, $size[0], $size[1]);
                    }
                }
            }
        }
        else
        { // $height�� ���� ���� ��� (crop�� �ش� ������ ����? ^^)
            if ($width >= $size[0]) { // ������� ������ $width�� �� ũ��, �̹����� ������ �濡���� ����ϴ� (Ȯ��� ���� ������)
                $x = $size[0];
                $tmp_y = $size[1];
            } else {
                $rate = $x / $size[0];
                $tmp_y = (int)($size[1] * $rate);
            }

            $target = imagecreatetruecolor($x, $tmp_y);
            imagecopyresampled($target, $source, 0, 0, 0, 0, $x, $tmp_y, $size[0], $size[1]);
        }
    }
    else // $width�� ���� $height�� �ִ� ���
    {
        if ($height > $size[1]) {   // ������� ���̺��� $height�� �� ũ��, �̹����� ���̷� ������� ����ϴ� (Ȯ��� ���� ������)
            $y = $size[1];
            $tmp_x = $size[0];
            $target = imagecreatetruecolor($tmp_x, $y);
            imagecopyresampled($target, $source, 0, 0, 0, 0, $tmp_x, $y, $size[0], $size[1]);
        } else {
            $x = $size[0];
            $y = $height;
            $tmp_y = $size[1];
            if ($is_crop) {
                $target = imagecreatetruecolor($x, $y);
                $tmp_target = imagecreatetruecolor($x, $tmp_y);
                imagecopyresampled($tmp_target, $source, 0, 0, 0, 0, $x, $tmp_y, $size[0], $size[1]);
                imagecopy($target, $tmp_target, 0, 0, 0, 0, $x, $tmp_y);
            } else {
                $rate = $y / $size[1];
                $tmp_x = (int)($size[0] * $rate);
                $target = imagecreatetruecolor($tmp_x, $y);
                imagecopyresampled($target, $source, 0, 0, 0, 0, $tmp_x, $y, $size[0], $size[1]);
            }
        }
    }

    // �̹��� ����Ƽ�� ������
    ob_start();
    if ($size[2] == 1)
        imagegif($target, "", $quality);
    else if ($size[2] == 2)
        imagejpeg($target, "", $quality);
    else if ($size[2] == 3)
        imagepng($target, "", round(10 - ($quality / 10))); //imagepng�� ����Ƽ�� 0~9���� ��� �����մϴ� (Lusia). 0(no compression) �Դϴ�
    $tmp_image_str = ob_get_contents();
    ob_end_clean();
    $target = imagecreatefromstring($tmp_image_str);
    unset($tmp_image_str);

    // watermark �̹��� �־��ֱ�
    if (trim($watermark) && count($watermark) > 0) {
        foreach ($watermark as $w1) {
            // �����̸��� ���丮�� ����
            $w1_file = $w1['filename'];
            if (!$w1_file) continue;

            $w_dir = dirname(file_path($w1_file));
            $w_file = basename($w1_file);

            $w1_file = $w_dir . "/" . $w_file;

            // ���͸�ũ ������ ������ ���͸�ũ�� ���� �ʽ��ϴ�
            if (!file_exists($w1_file))
                break;

            // ���͸�ũ �̹����� width, height
            $sizew = getimagesize($w1_file);
            $wx = $sizew[0];
            $wy = $sizew[1];
            // watermark �̹��� �о���̱�
            if ($sizew[2] == 1)
                $w1_source = imagecreatefromgif($w1_file);
            else if ($sizew[2] == 2)
                $w1_source = imagecreatefromjpeg($w1_file);
            else if ($sizew[2] == 3)
                $w1_source = imagecreatefrompng($w1_file);

            // $target �̹����� width, height
            $sx = imagesx($target);
            $sy = imagesy($target);

            switch ($w1[location]) {
              case "center"       :
                    $tx = (int)($sx/2 - $wx/2) + $w1[x];
                    $ty = (int)($sy/2 - $wy/2) + $w1[y];
                    break;
              case "top"          :
                    $tx = (int)($sx/2 - $wx/2) + $w1[x];
                    $ty = $w1[y];
                    break;
              case "top_left"     :
                    $tx = $w1[x];
                    $ty = $w1[y];
                    break;
              case "top_right"    :
                    $tx = $sx - $wx - $w1[x];
                    $ty = $w1[y];
                    break;
              case "bottom"       :
                    $tx = (int)($sx/2 - $wx/2) + $w1[x];
                    $ty = $sy - $w1[y] - $wy;
                    break;
              case "bottom_left"  :
                    $tx = $w1[x];
                    $ty = $sy - $w1[y] - $wy;
                    break;
              case "bottom_right" :
              default             :
                    $tx = $sx - $w1[x] - $wx;
                    $ty = $sy - $w1[y] - $wy;
            }
            imagecopyresampled($target, $w1_source, $tx, $ty, 0, 0, $wx, $wy, $wx, $wy);
        }
    }

    // php imagefilter
    if ($filter) {
        $filter_type = $filter[type];
        switch($filter_type) {
          case  IMG_FILTER_COLORIZE : imagefilter($target, $filter_type, $filter[arg1], $filter[arg2], $filter[arg3], $filter[arg4]);
                                      break;
          case  IMG_FILTER_PIXELATE : imagefilter($target, $filter_type, $filter[arg1], $filter[arg2]);
                                      break;
          case  IMG_FILTER_BRIGHTNESS :
          case  IMG_FILTER_CONTRAST :
          case  IMG_FILTER_SMOOTH   : imagefilter($target, $filter_type, $filter[arg1]);
                                      break;
          case  IMG_FILTER_NEGATE   :
          case  IMG_FILTER_GRAYSCALE:
          case  IMG_FILTER_EDGEDETECT:
          case  IMG_FILTER_EMBOSS   :
          case  IMG_FILTER_GAUSSIAN_BLUR :
          case  IMG_FILTER_SELECTIVE_BLUR:
          case  IMG_FILTER_MEAN_REMOVAL:  imagefilter($target, $filter_type);
                                          break;
          case  99: UnsharpMask($target, $filter[arg1], $filter[arg2], $filter[arg3]);
                                        break;
          default                   : ; // ���� Ÿ���� Ʋ���� �ƹ��͵� ���մϴ�
        }
    }

    $quality=100;
    if ($size[2] == 1)
        imagegif($target, $thumb_file, 100);
    else if ($size[2] == 2)
        imagejpeg($target, $thumb_file, 100);
    else if ($size[2] == 3)
        imagepng($target, $thumb_file, 0); //imagepng�� ����Ƽ�� 0~9���� ��� �����մϴ� (Lusia). 0(no compression) �Դϴ�
    @chmod($thumb_file, 0606); // ���� ������ ���Ͽ� ���ϸ�� ����

    // �޸𸮸� �ν��ݴϴ� - http://kr2.php.net/manual/kr/function.imagedestroy.php
    if ($target)
        imagedestroy($target);
    if ($source)
        imagedestroy($source);
    if ($tmp_target)
        imagedestroy($tmp_target);

    return str_replace($real_dir, "", $thumb_file);
}

// php imagefilter for PHP4 - http://mgccl.com/2007/03/02/imagefilter-function-for-php-user-without-bundled-gd
//
//include this file whenever you have to use imageconvolution...
//you can use in your project, but keep the comment below :)
//great for any image manipulation library
//Made by Chao Xu(Mgccl) 3/1/07
//www.webdevlogs.com
//V 1.0
if(!function_exists('imagefilter')){
	function imagefilter($source, $var, $arg1 = null, $arg2 = null, $arg3 = null){
		#define('IMAGE_FILTER_NEGATE',0);
		#define('IMAGE_FILTER_GRAYSCALE',0);
		#define('IMAGE_FILTER_BRIGHTNESS',2);
		#define('IMAGE_FILTER_CONTRAST',3);
		#define('IMAGE_FILTER_COLORIZE',4);
		#define('IMAGE_FILTER_EDGEDETECT',5);
		#define('IMAGE_FILTER_EMBOSS',6);
		#define('IMAGE_FILTER_GAUSSIAN_BLUR',7);
		#define('IMAGE_FILTER_SELECTIVE_BLUR',8);
		#define('IMAGE_FILTER_MEAN_REMOVAL',9);
		#define('IMAGE_FILTER_SMOOTH',10);
		$max_y = imagesy($source);
		$max_x = imagesx($source);
	   switch ($var){
	       case 0:
	           $y = 0;
	           while($y<$max_y) {
	               $x = 0;
	               while($x<$max_x){
	                   $rgb = imagecolorat($source,$x,$y);
	                   $r = 255 - (($rgb >> 16) & 0xFF);
	                   $g = 255 - (($rgb >> 8) & 0xFF);
	                   $b = 255 - ($rgb & 0xFF);
	                   $a = $rgb >> 24;
	                   $new_pxl = imagecolorallocatealpha($source, $r, $g, $b, $a);
	                   if ($new_pxl == false){
	                       $new_pxl = imagecolorclosestalpha($source, $r, $g, $b, $a);
	                   }
	                   imagesetpixel($source,$x,$y,$new_pxl);
	                   ++$x;
	               }
	               ++$y;
	           }
	           return true;
	       break;
	       case 1:
	           $y = 0;
	           while($y<$max_y) {
	               $x = 0;
	               while($x<$max_x){
	                   $rgb = imagecolorat($source,$x,$y);
	                   $a = $rgb >> 24;
	                   $r = ((($rgb >> 16) & 0xFF)*0.299)+((($rgb >> 8) & 0xFF)*0.587)+(($rgb & 0xFF)*0.114);
	                   $new_pxl = imagecolorallocatealpha($source, $r, $r, $r, $a);
	                   if ($new_pxl == false){
	                       $new_pxl = imagecolorclosestalpha($source, $r, $r, $r, $a);
	                   }
	                   imagesetpixel($source,$x,$y,$new_pxl);
	                   ++$x;
	               }
	               ++$y;
	           }
	           return true;
	       break;
	       case 2:
	           $y = 0;
	           while($y<$max_y) {
	               $x = 0;
	               while($x<$max_x){
	                   $rgb = imagecolorat($source,$x,$y);
	                   $r = (($rgb >> 16) & 0xFF) + $arg1;
	                   $g = (($rgb >> 8) & 0xFF) + $arg1;
	                   $b = ($rgb & 0xFF) + $arg1;
	                   $a = $rgb >> 24;
	                     $r = ($r > 255)? 255 : (($r < 0)? 0:$r);
	                   $g = ($g > 255)? 255 : (($g < 0)? 0:$g);
	                   $b = ($b > 255)? 255 : (($b < 0)? 0:$b);
	                   $new_pxl = imagecolorallocatealpha($source, $r, $g, $b, $a);
	                   if ($new_pxl == false){
	                       $new_pxl = imagecolorclosestalpha($source, $r, $g, $b, $a);
	                   }
	                   imagesetpixel($source,$x,$y,$new_pxl);
	                   ++$x;
	               }
	               ++$y;
	           }
	           return true;
	       break;
	       case 3:
	           $contrast = pow((100-$arg1)/100,2);
	           $y = 0;
	           while($y<$max_y) {
	               $x = 0;
	               while($x<$max_x){
	                   $rgb = imagecolorat($source,$x,$y);
	                   $a = $rgb >> 24;
	                   $r = (((((($rgb >> 16) & 0xFF)/255)-0.5)*$contrast)+0.5)*255;
	                   $g = (((((($rgb >> 8) & 0xFF)/255)-0.5)*$contrast)+0.5)*255;
	                   $b = ((((($rgb & 0xFF)/255)-0.5)*$contrast)+0.5)*255;
	                   $r = ($r > 255)? 255 : (($r < 0)? 0:$r);
	                   $g = ($g > 255)? 255 : (($g < 0)? 0:$g);
	                   $b = ($b > 255)? 255 : (($b < 0)? 0:$b);
	                   $new_pxl = imagecolorallocatealpha($source, $r, $g, $b, $a);
	                   if ($new_pxl == false){
	                       $new_pxl = imagecolorclosestalpha($source, $r, $g, $b, $a);
	                   }
	                   imagesetpixel($source,$x,$y,$new_pxl);
	                   ++$x;
	               }
	               ++$y;
	           }
	           return true;
	       break;
	       case 4:
	           $x = 0;
	           while($x<$max_x){
	               $y = 0;
	               while($y<$max_y){
	                   $rgb = imagecolorat($source, $x, $y);
	                   $r = (($rgb >> 16) & 0xFF) + $arg1;
	                   $g = (($rgb >> 8) & 0xFF) + $arg2;
	                   $b = ($rgb & 0xFF) + $arg3;
	                   $a = $rgb >> 24;
	                   $r = ($r > 255)? 255 : (($r < 0)? 0:$r);
	                   $g = ($g > 255)? 255 : (($g < 0)? 0:$g);
	                   $b = ($b > 255)? 255 : (($b < 0)? 0:$b);
	                   $new_pxl = imagecolorallocatealpha($source, $r, $g, $b, $a);
	                   if ($new_pxl == false){
	                       $new_pxl = imagecolorclosestalpha($source, $r, $g, $b, $a);
	                   }
	                   imagesetpixel($source,$x,$y,$new_pxl);
	                   ++$y;
	                   }
	               ++$x;
	           }
	           return true;
	       break;
	       case 5:
	           return imageconvolution($source, array(array(-1,0,-1), array(0,4,0), array(-1,0,-1)), 1, 127);
	       break;
	       case 6:
	           return imageconvolution($source, array(array(1.5, 0, 0), array(0, 0, 0), array(0, 0, -1.5)), 1, 127);
	       break;
	       case 7:
	           return imageconvolution($source, array(array(1, 2, 1), array(2, 4, 2), array(1, 2, 1)), 16, 0);
	       break;
	       case 8:
	   for($y = 0; $y<$max_y; $y++) {
	       for ($x = 0; $x<$max_x; $x++) {
	             $flt_r_sum = $flt_g_sum = $flt_b_sum = 0;
	           $cpxl = imagecolorat($source, $x, $y);
	           for ($j=0; $j<3; $j++) {
	               for ($i=0; $i<3; $i++) {
	                   if (($j == 1) && ($i == 1)) {
	                       $flt_r[1][1] = $flt_g[1][1] = $flt_b[1][1] = 0.5;
	                   } else {
	                       $pxl = imagecolorat($source, $x-(3>>1)+$i, $y-(3>>1)+$j);

	                       $new_a = $pxl >> 24;
	                       //$r = (($pxl >> 16) & 0xFF);
	                       //$g = (($pxl >> 8) & 0xFF);
	                       //$b = ($pxl & 0xFF);
	                       $new_r = abs((($cpxl >> 16) & 0xFF) - (($pxl >> 16) & 0xFF));
	                       if ($new_r != 0) {
	                           $flt_r[$j][$i] = 1/$new_r;
	                       } else {
	                           $flt_r[$j][$i] = 1;
	                       }

	                       $new_g = abs((($cpxl >> 8) & 0xFF) - (($pxl >> 8) & 0xFF));
	                       if ($new_g != 0) {
	                           $flt_g[$j][$i] = 1/$new_g;
	                       } else {
	                           $flt_g[$j][$i] = 1;
	                       }

	                       $new_b = abs(($cpxl & 0xFF) - ($pxl & 0xFF));
	                       if ($new_b != 0) {
	                           $flt_b[$j][$i] = 1/$new_b;
	                       } else {
	                           $flt_b[$j][$i] = 1;
	                       }
	                   }

	                   $flt_r_sum += $flt_r[$j][$i];
	                   $flt_g_sum += $flt_g[$j][$i];
	                   $flt_b_sum += $flt_b[$j][$i];
	               }
	           }

	           for ($j=0; $j<3; $j++) {
	               for ($i=0; $i<3; $i++) {
	                   if ($flt_r_sum != 0) {
	                       $flt_r[$j][$i] /= $flt_r_sum;
	                   }
	                   if ($flt_g_sum != 0) {
	                       $flt_g[$j][$i] /= $flt_g_sum;
	                   }
	                   if ($flt_b_sum != 0) {
	                       $flt_b[$j][$i] /= $flt_b_sum;
	                   }
	               }
	           }

	           $new_r = $new_g = $new_b = 0;

	           for ($j=0; $j<3; $j++) {
	               for ($i=0; $i<3; $i++) {
	                   $pxl = imagecolorat($source, $x-(3>>1)+$i, $y-(3>>1)+$j);
	                   $new_r += (($pxl >> 16) & 0xFF) * $flt_r[$j][$i];
	                   $new_g += (($pxl >> 8) & 0xFF) * $flt_g[$j][$i];
	                   $new_b += ($pxl & 0xFF) * $flt_b[$j][$i];
	               }
	           }

	           $new_r = ($new_r > 255)? 255 : (($new_r < 0)? 0:$new_r);
	           $new_g = ($new_g > 255)? 255 : (($new_g < 0)? 0:$new_g);
	           $new_b = ($new_b > 255)? 255 : (($new_b < 0)? 0:$new_b);
	           $new_pxl = ImageColorAllocateAlpha($source, (int)$new_r, (int)$new_g, (int)$new_b, $new_a);
	           if ($new_pxl == false) {
	               $new_pxl = ImageColorClosestAlpha($source, (int)$new_r, (int)$new_g, (int)$new_b, $new_a);
	           }
	           imagesetpixel($source,$x,$y,$new_pxl);
	       }
	   }
	   return true;
	       break;
	       case 9:
	           return imageconvolution($source, array(array(-1,-1,-1),array(-1,9,-1),array(-1,-1,-1)), 1, 0);
	       break;
	       case 10:
	           return imageconvolution($source, array(array(1,1,1),array(1,$arg1,1),array(1,1,1)), $arg1+8, 0);
	       break;
	   }
	}
}

if(!function_exists('file_path')){
// ������ ��θ� ������ �ɴϴ� (�Ҵ���, /lib/common.lib.php�� ���ǵ� �Լ�)
function file_path($path) {

    $dir = dirname($path);
    $file = basename($path);

    if (substr($dir,0,1) == "/") {
        $real_dir = dirname($_SERVER['DOCUMENT_ROOT'] . "/nothing");
        $dir = $real_dir . $dir;
    }

    return $dir . "/" . $file;
}
}

////////////////////////////////////////////////////////////////////////////////////////////////
////
////                  Unsharp Mask for PHP - version 2.1.1
////
////    Unsharp mask algorithm by Torstein H��nsi 2003-07.
////             thoensi_at_netcom_dot_no.
////               Please leave this notice.
////
////   http://vikjavev.no/computing/ump.php
////
///////////////////////////////////////////////////////////////////////////////////////////////
function UnsharpMask($img, $amount, $radius, $threshold)
{

  // $img is an image that is already created within php using
  // imgcreatetruecolor. No url! $img must be a truecolor image.

  // Attempt to calibrate the parameters to Photoshop:
	if ($amount > 500) $amount = 500;
	$amount = $amount * 0.016;
	if ($radius > 50) $radius = 50;
	$radius = $radius * 2;
	if ($threshold > 255) $threshold = 255;

	$radius = abs(round($radius)); 	// Only integers make sense.
//	if ($radius == 0) {	return $img; imagedestroy($img); break;	}
    if ($radius == 0) {	imagedestroy($img); return $img; }
	$w = imagesx($img); $h = imagesy($img);
	$imgCanvas = $img;
	$imgCanvas2 = $img;
	$imgBlur = imagecreatetruecolor($w, $h);

	// Gaussian blur matrix:
	//	1	2	1
	//	2	4	2
	//	1	2	1

	// Move copies of the image around one pixel at the time and merge them with weight
	// according to the matrix. The same matrix is simply repeated for higher radii.
	for ($i = 0; $i < $radius; $i++)
		{
		imagecopy	  ($imgBlur, $imgCanvas, 0, 0, 1, 1, $w - 1, $h - 1); // up left
		imagecopymerge ($imgBlur, $imgCanvas, 1, 1, 0, 0, $w, $h, 50); // down right
		imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 1, 0, $w - 1, $h, 33.33333); // down left
		imagecopymerge ($imgBlur, $imgCanvas, 1, 0, 0, 1, $w, $h - 1, 25); // up right
		imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 1, 0, $w - 1, $h, 33.33333); // left
		imagecopymerge ($imgBlur, $imgCanvas, 1, 0, 0, 0, $w, $h, 25); // right
		imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 1, $w, $h - 1, 20 ); // up
		imagecopymerge ($imgBlur, $imgCanvas, 0, 1, 0, 0, $w, $h, 16.666667); // down
		imagecopymerge ($imgBlur, $imgCanvas, 0, 0, 0, 0, $w, $h, 50); // center
		}
	$imgCanvas = $imgBlur;

	// Calculate the difference between the blurred pixels and the original
	// and set the pixels
	for ($x = 0; $x < $w; $x++)
		{ // each row
		for ($y = 0; $y < $h; $y++)
			{ // each pixel
			$rgbOrig = ImageColorAt($imgCanvas2, $x, $y);
			$rOrig = (($rgbOrig >> 16) & 0xFF);
			$gOrig = (($rgbOrig >> 8) & 0xFF);
			$bOrig = ($rgbOrig & 0xFF);
			$rgbBlur = ImageColorAt($imgCanvas, $x, $y);
			$rBlur = (($rgbBlur >> 16) & 0xFF);
			$gBlur = (($rgbBlur >> 8) & 0xFF);
			$bBlur = ($rgbBlur & 0xFF);

			// When the masked pixels differ less from the original
			// than the threshold specifies, they are set to their original value.
			$rNew = (abs($rOrig - $rBlur) >= $threshold) ? max(0, min(255, ($amount * ($rOrig - $rBlur)) + $rOrig)) : $rOrig;
			$gNew = (abs($gOrig - $gBlur) >= $threshold) ? max(0, min(255, ($amount * ($gOrig - $gBlur)) + $gOrig)) : $gOrig;
			$bNew = (abs($bOrig - $bBlur) >= $threshold) ? max(0, min(255, ($amount * ($bOrig - $bBlur)) + $bOrig)) : $bOrig;

			if (($rOrig != $rNew) || ($gOrig != $gNew) || ($bOrig != $bNew))
				{
				$pixCol = ImageColorAllocate($img, $rNew, $gNew, $bNew);
				ImageSetPixel($img, $x, $y, $pixCol);
				}
			}
		}
	return $img;
}

/*********************************************/
/* Fonction: ImageCreateFromBMP              */
/* Author:   DHKold                          */
/* Contact:  admin@dhkold.com                */
/* Date:     The 15th of June 2005           */
/* Version:  2.0B                            */
/*********************************************/

function ImageCreateFromBMP($filename)
{
 //Ouverture du fichier en mode binaire
   if (! $f1 = fopen($filename,"rb")) return FALSE;
echo $filename;
 //1 : Chargement des ent?tes FICHIER
   $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($f1,14));
   if ($FILE['file_type'] != 19778) return FALSE;

 //2 : Chargement des ent?tes BMP
   $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel'.
                 '/Vcompression/Vsize_bitmap/Vhoriz_resolution'.
                 '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($f1,40));
   $BMP['colors'] = pow(2,$BMP['bits_per_pixel']);
   if ($BMP['size_bitmap'] == 0) $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
   $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel']/8;
   $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
   $BMP['decal'] = ($BMP['width']*$BMP['bytes_per_pixel']/4);
   $BMP['decal'] -= floor($BMP['width']*$BMP['bytes_per_pixel']/4);
   $BMP['decal'] = 4-(4*$BMP['decal']);
   if ($BMP['decal'] == 4) $BMP['decal'] = 0;

 //3 : Chargement des couleurs de la palette
   $PALETTE = array();
   if ($BMP['colors'] < 16777216)
   {
    $PALETTE = unpack('V'.$BMP['colors'], fread($f1,$BMP['colors']*4));
   }

 //4 : Cr?ation de l'image
   $IMG = fread($f1,$BMP['size_bitmap']);
   $VIDE = chr(0);

   $res = imagecreatetruecolor($BMP['width'],$BMP['height']);
   $P = 0;
   $Y = $BMP['height']-1;
   while ($Y >= 0)
   {
    $X=0;
    while ($X < $BMP['width'])
    {
     if ($BMP['bits_per_pixel'] == 24)
        $COLOR = unpack("V",substr($IMG,$P,3).$VIDE);
     elseif ($BMP['bits_per_pixel'] == 16)
     {
        $COLOR = unpack("v",substr($IMG,$P,2));
        $blue  = (($COLOR[1] & 0x001f) << 3) + 7;
        $green = (($COLOR[1] & 0x03e0) >> 2) + 7;
        $red   = (($COLOR[1] & 0xfc00) >> 7) + 7;
        $COLOR[1] = $red * 65536 + $green * 256 + $blue;
     }
     elseif ($BMP['bits_per_pixel'] == 8)
     {
        $COLOR = unpack("n",$VIDE.substr($IMG,$P,1));
        $COLOR[1] = $PALETTE[$COLOR[1]+1];
     }
     elseif ($BMP['bits_per_pixel'] == 4)
     {
        $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
        if (($P*2)%2 == 0) $COLOR[1] = ($COLOR[1] >> 4) ; else $COLOR[1] = ($COLOR[1] & 0x0F);
        $COLOR[1] = $PALETTE[$COLOR[1]+1];
     }
     elseif ($BMP['bits_per_pixel'] == 1)
     {
        $COLOR = unpack("n",$VIDE.substr($IMG,floor($P),1));
        if     (($P*8)%8 == 0) $COLOR[1] =  $COLOR[1]        >>7;
        elseif (($P*8)%8 == 1) $COLOR[1] = ($COLOR[1] & 0x40)>>6;
        elseif (($P*8)%8 == 2) $COLOR[1] = ($COLOR[1] & 0x20)>>5;
        elseif (($P*8)%8 == 3) $COLOR[1] = ($COLOR[1] & 0x10)>>4;
        elseif (($P*8)%8 == 4) $COLOR[1] = ($COLOR[1] & 0x8)>>3;
        elseif (($P*8)%8 == 5) $COLOR[1] = ($COLOR[1] & 0x4)>>2;
        elseif (($P*8)%8 == 6) $COLOR[1] = ($COLOR[1] & 0x2)>>1;
        elseif (($P*8)%8 == 7) $COLOR[1] = ($COLOR[1] & 0x1);
        $COLOR[1] = $PALETTE[$COLOR[1]+1];
     }
     else
        return FALSE;
     imagesetpixel($res,$X,$Y,$COLOR[1]);
     $X++;
     $P += $BMP['bytes_per_pixel'];
    }
    $Y--;
    $P+=$BMP['decal'];
   }

 //Fermeture du fichier
   fclose($f1);

 return $res;
}

// animated gif ���������� Ȯ��
// http://kr2.php.net/imagecreatefromgif
/*
function is_ani_gif($filename)
{
        $filecontents=file_get_contents($filename);

        $str_loc=0;
        $count=0;
        while ($count < 2) # There is no point in continuing after we find a 2nd frame
        {

                $where1=strpos($filecontents,"\x00\x21\xF9\x04",$str_loc);
                if ($where1 === FALSE)
                {
                        break;
                }
                else
                {
                        $str_loc=$where1+1;
                        $where2=strpos($filecontents,"\x00\x2C",$str_loc);
                        if ($where2 === FALSE)
                        {
                                break;
                        }
                        else
                        {
                                if ($where1+8 == $where2)
                                {
                                        $count++;
                                }
                                $str_loc=$where2+1;
                        }
                }
        }

        if ($count > 1)
        {
                return(true);
        }
        else
        {
                return(false);
        }
}
*/
?>