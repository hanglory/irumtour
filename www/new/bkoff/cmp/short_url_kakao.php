<?
include_once("../include/common_file.php");

$sql2 = "select * from cmp_staff where id='$user_id'";
$dbo2->query($sql2);
$rs2=$dbo2->next_record();
$cp_url = $rs2[cp_url];
$url_basic = ($cp_url)? $cp_url : "http://irumtour.net";
$url_basic = ($cp_url)? $cp_url : "http://irumtour.net";
$img =($rs2[filename2])? $url_basic . "/new/public/cmp_files/".$rs2[filename2] : "http://irumtour.net/new/images/cmp/paper_logo.jpg";

$sql = "select * from cmp_estimate where id_no='$id_no'";
$dbo->query($sql);
$rs=$dbo->next_record();
$long_url1 =  "${url_basic}/new/bkoff/cmp/form06.html?code=". str_replace("+","{p}",encrypt($rs[id_no],$SALT));
$long_url2 =  "${url_basic}/new/bkoff/cmp/form08.html?code=". str_replace("+","{p}",encrypt($rs[id_no],$SALT));

$url_short = short_url($long_url1);

$arr = explode(">",$rs[golf_name]);
$default_golf_name= $arr[count($arr)-1];
$title = "일정표 - $default_golf_name";


checkVar("id_no",$id_no);
checkVar("long_url1",$long_url1);
checkVar("long_url2",$long_url2);
checkVar("url_short",$url_short);
checkVar("golf_name",$rs[golf_name]);
checkVar("default_golf_name",$default_golf_name);
checkVar("img",$img);
//exit;
?>
<!DOCTYPE html>
<html>
<head>
<script src="//code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="//developers.kakao.com/sdk/js/kakao.min.js"></script>
<script type='text/javascript'>
  //<![CDATA[
    // // 사용할 앱의 JavaScript 키를 설정해 주세요.
    Kakao.init('<?=$KAKAO_KEY?>');
    // // 카카오링크 버튼을 생성합니다. 처음 한번만 호출하면 됩니다.
    function sendLink() {
      Kakao.Link.sendDefault({
        objectType: 'feed',
        content: {
          title: '<?=$title?>',
          imageUrl: '<?=$img?>',
          link: {
            mobileWebUrl: '<?=$long_url1?>',
            webUrl: '<?=$long_url1?>'
          }
        },
        buttons: [
          {
            title: '일정표 바로가기',
            link: {
              mobileWebUrl: '<?=$long_url1?>',
              webUrl: '<?=$long_url1?>'
            }
          }
        ]
      });
    }
  //]]>
  sendLink();
</script>

</head>
<body>



</body>
</html>

