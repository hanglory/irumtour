<?
include_once("../include/common_file.php");


####option
//include_once("../../public/inc/category1.inc");

####각종 기초 정보 결정
$view_row=10;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$filecode = substr(SELF,5,-4);
$table = "tour_option";
$MENU = "tour";
$TITLE = "중분류";
$filter = "";
$column = "*";
$basicLink = "";


switch($mode){
	case "save":
		$price = str_replace(",","",Trim($price));
		$sql = "insert into $table (tid,subject,price) values ('$tid','$subject','$price')";
		$dbo->query($sql);

		back();exit;
		break;

	case "drop":
		$sql = "delete from $table where id_no = $id_no ";
		$dbo->query($sql);
		back();exit;
		break;

}
//-------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>:::EasePlus:::</title>
<link rel="stylesheet" href="../include/default.css">
<link rel="stylesheet" href="../include/basic.css" type="text/css">
<link href="../../jquery-ui/css/cupertino/jquery-ui-1.9.0.custom.css" rel="stylesheet">
<script language="JavaScript" src="../../include/form_check.js"></script>
<script language="JavaScript" src="../../include/function.js"></script>
<script language="JavaScript" src="../../include/jquery-1.8.0.min.js"></script>
<script language="JavaScript" src="../../jquery-ui/js/jquery-ui-1.9.0.custom.min.js"></script>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<script language="JavaScript">
function chkForm(fm){
	if(check_blank(fm.subject,'옵션명을',0)=='wrong'){return}
	fm.submit();
}
</script>


	 <table border="0" cellspacing="1" cellpadding="3" width="100%"  class="viewWidth">

		<form name=fmData method=post>
		<input type=hidden name=mode value='save'>
		<input type=hidden name=tid value='<?=$tid?>'>

        <tr>
          <td height="20">

			<span class="bold">옵션명</span> : <?=html_input('subject',30,50)?>&nbsp;&nbsp;&nbsp;&nbsp;
            <span class="bold">추가비용</span> : <?=html_input('price',10,10)?>

			<span class="btn_pack small bold"><a href="javascript:chkForm(document.fmData)"> 추가 </a></span>&nbsp;
          </td>
        </tr>

      </table>

</form>


	 <table border="0" cellspacing="1" cellpadding="3" width="100%"  class="viewWidth">
<?
$sql = "select * from tour_option where tid='$tid' order by subject asc";
$dbo->query($sql);
$i=1;
while($rs=$dbo->next_record()){
	$rs[price] = number_format($rs[price]);
?>
	    <tr>
          <td height="20">
			<div style="padding-top:3px">
			<?=$i?>.
			<?=$rs[subject]?> ( + <?=($rs[price])?$rs[price]:"0"?>)
			<span class="btn_pack small bold"><a href="?mode=drop&id_no=<?=$rs[id_no]?>"> 삭제 </a></span>&nbsp;
			</div>
          </td>
        </tr>
<?
	$i++;
}
?>
      </table>


</body>
</html>
