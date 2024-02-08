<?
####파일 처리 루틴 (상품등록 전용)

/*
#기본 설정(example)
#------------------------------------------
$path="../../goods_photo";		//업로드할 파일의 경로
$maxsize="2097152"	;				//2MB	업로드 가능한 최대 사이즈 제한
$fname=$fname;						//파일이름을 담고 있는 변수 이름
$fname_name=$fname_name;	//파일의 이름
$fname_size=$fname_size;		//파일의 사이즈
$fname_type=$fname_type;		//파일의 type
$filename=$code;					//파일이름 작명
#------------------------------------------
*/


if($fname and $fname != "none"):		//업로드할 파일을 선택한 경우에만 작동

	//파일의 크기가 지정한 사이즈 보다 클경우  돌려보냄
	if($fname_size > $maxsize):
		echo("<script>alert('파일의 크기는 " .  $maxsize/1024  . "KB를 초과할 수 없습니다.');history.back();</script>");
		exit;
	endif;


    /*file_upload check s*/
    /*이미지 여부 확인 220630 s*/
    $fname=str_replace("%00","",$fname);
    $chk_img = getimagesize($fname);
        if($chk_img[2]>0 && $chk_img[2]<=16){//이미지
        }else{//이미지 아님
            echo "<script>alert('이미지가 아니어서 업로드할 수 없습니다.');history.back(-1);</script>";
            exit;
        }
    /*이미지 여부 확인f*/

    /*업로드 허용여부 s*/
    $fname_name_ex=explode("." , $fname_name);
    $count = count($fname_name_ex)-1;
    $filename_ext = strtolower($fname_name_ex[$count]);
    $allow_ext = array("dwg", "txt", "zip", "hwp", "csv", "ppt", "pptx", "doc", "docx", "xls", "xlsx", "pdf");
    if(
        (strstr($fname_type,"image") && (!$chk_img[2] || $chk_img[2]>16)) || 
        (!strstr($fname_type,"image") && !in_array($filename_ext, $allow_ext))
    ){
        echo("<script>alert('업로드가 금지된 파일입니다.');history.back();</script>");
        exit;
    }
    /*업로드 허용여부 s*/
    /*file_upload check f*/


	//같은 파일 처리(혹시 같은 이름의 파일이 있다면 변경.)
	$fname_name_ex=explode("." , $fname_name);
	$upfile=$filename .".". $fname_name_ex[1];
	$n=1;
	while(file_exists("$path/$upfile")){
		$upfile=$filename . "_" . $n .".". $fname_name_ex[1];
		$n++;
	}

	//파일을 지정한 디렉토리에 저장
	if(!copy($fname,"$path/origin_$upfile")):
		echo("<script>alert('파일 업로드에 실패하였습니다.');history.back();</script>");
		exit;
	endif;

	####썸네일 만들기

	$src="$path/origin_$upfile";        //-- 원본

	for($i=0; $i<count($fix_size);$i++){

		$wh = explode(",",$fix_size[$i]);

		$thum_path = ($wh[0]==259)? "":"/" . $wh[0];

		$dst="${path}${thum_path}/${upfile}";     //-- 저장

		$quality = '90';    //-- jpg 퀄리티
		$fix_w = $wh[0];    //-- 줄일 크기 pixel (너비, 또는 높이에 적용)
		$fix_h =  $wh[1];    //-- 줄일 크기 pixel (너비, 또는 높이에 적용)
		//$ratio = '3:4';        //-- 이미지를 4:3 비율로 잘라냄
		$ratio = 'false';        //-- 원본 이미지비율을 유지

		$get_size = _getimagesize($src, $fix_w,$fix_h);
		$result = resize_image($dst, $src, $get_size, $quality, $ratio);

	}

	@unlink($src);//원본 삭제



endif;

//임시파일 삭제
@unlink($fname);
?>