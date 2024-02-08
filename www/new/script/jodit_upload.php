<?
session_start();
header("Content-Type: text/html; charset=UTF-8");


#### Include
include_once  ("../include/config.php");
include_once  ("../include/fun_basic.php");

$asp_id = $_SESSION['ASP_ID'];

$https = ($_SERVER['HTTPS']=='on')? "https":"http";
$DOMAIN = $https . "://" . $_SERVER['HTTP_HOST'];
$naming = "JD";
$path = "../public/bbs_files";
$maxsize=10 *(1024*1024);

if($_GET[action]=="upload"){

    foreach($_FILES['file']['name'] as $i=>$img){

        if($_FILES["file"]["size"][$i]){
            #------------------------------------------
            $fname=$_FILES["file"]["tmp_name"][$i];
            $fname_name=$_FILES["file"]["name"][$i];
            $fname_size=$_FILES["file"]["size"][$i];
            $fname_type=$_FILES["file"]["type"][$i];
            $filename=$naming . time();
            $type = "image";
            if($_GET['site_width_bbs']) $fix_size = $_GET['site_width_bbs'].",";//파일 크기
            #------------------------------------------
            include("../include/file_upload.php");
            $upfile = $upfile;

            $pic = "";
            $pic = $DOMAIN . "/new/public/bbs_files/" . $upfile;
            $file[] = array(
                'url' => $pic
            );

        }

    }

    $result = array(
        'error' => false,
        'msgs' => array(),
        'file' => $file,
    );

    echo json_encode($result);


    exit;
}