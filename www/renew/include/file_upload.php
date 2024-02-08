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


/*디렉토리 생성*/
$sub_dir = date('ym');
$path_full = $path . "/". $sub_dir;

if(!is_dir($path_full)){
	 @mkdir($path_full, 0777);
	 @chmod($path_full, 0777);
}


/*파일 업로드*/
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
    if($type=="image"){
        if($chk_img[2]>0 && $chk_img[2]<=16){//이미지
        }else{//이미지 아님
            echo "<script>alert('이미지가 아니어서 업로드할 수 없습니다.');history.back(-1);</script>";
            exit;
        }
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




	//같은 파일 처리(혹시 같은 이름의 파일이 있다면 삭제한다.)
	$upfile=$filename .".". $fname_name_ex[$count];
	$exist=file_exists("$path_full/$upfile");
	if($exist):
		@unlink("$path_full/$upfile");
	endif;

	//파일을 지정한 디렉토리에 저장
	if(!copy($fname,"$path_full/$upfile")):
		echo("<script>alert('파일 업로드에 실패하였습니다.');history.back();</script>");
		exit;
	endif;


endif;

//임시파일 삭제
@unlink($fname);

$upfile = $sub_dir . "/" . $upfile;
?>