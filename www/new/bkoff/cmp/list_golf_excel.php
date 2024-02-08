<?
include_once("../include/common_file.php");

//chk_power($_SESSION["sessLogin"]["proof"],"보고서");
chk_power($_SESSION["sessLogin"]["proof"],"엑셀다운로드");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_golf";
$MENU = "cmp_basic";
$TITLE = "상품정보";

####각종 기초 정보 결정
$view_row=20;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$column = "*";
$basicLink = "";


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)

#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword){
	$filter .=" and $target like '%$keyword%' ";
	$best="";	 //배너 select 초기화
	$findMode=1;
}



#query
$sql_1 = "select $column from $table where id_no>0 $filter";			//자료수
$sql_2 = $sql_1 . " order by id_no desc";
//checkVar("",$sql_2);

####자료갯수
list($rows)=$dbo->query($sql_1);//검색된 자료의 갯수
$row_search = $rows;



####페이지 처리

$var=ceil($row_search/$view_row);
if ($var > 1){
	$total_page=$var;
}
else{
	$total_page=1;
}



####자료가 하나도 없을 경우의 처리
if(!$row_search){
   $error[noData] = accentError("해당하는 자료가 없습니다.");
}



####검색 항목
$selectTxt = "상품명,국가,공항지역,지역,거래처,담당자";
$selectValue ="name,nation,air_city,city,partner,main_staff";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1";
$sessLink = "page=$page&" . $link;


if($REMOTE_ADDR!="106.246.54.18"){
$xls_name = "golf_" . date("Ymd") . ".xls";
header("Content-type: application/vnd.ms-excel");
header("Content-Type: application/vnd.ms-excel; charset=euc-kr");
header("Content-Disposition: attachment;filename=$xls_name;");
header( "Content-Description: PHP4 Generated Data" );
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<div style="padding:10px">


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list" style="border-collapse:collapse;border:1px solid #ccc ">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">국가</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">지역</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">상품명</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">거래처</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">담당자</th>
		<th class="subject"  style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc">연락처</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){


?>
	    <tr align='center'>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[nation]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[city]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[name]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[partner]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[main_staff]?></td>
	      <td style="font-size:9pt;border-left:1px solid #ccc;border-bottom:1px solid #ccc"><?=$rs[phone]?></td>
	    </tr>
<?
	$num--;
}
?>
	</table>

</div>

</body>
</html>
