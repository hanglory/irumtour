<?
include_once("../include/common_file.php");


chk_power($_SESSION["sessLogin"]["proof"],"기본설정");



####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_partner";
$MENU = "cmp_basic";
$TITLE = "거래처 정보";

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
$sql_1 = "
    select 
        *
    from $table 
    where 
        id_no>0
        and cp_id='$CP_ID' 
        $filter
    ";
$sql_2 = $sql_1 . " order by id_no desc ";
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
$selectTxt = "거래처,담당자,연락처,국가";
$selectValue ="company,name,phone,nation";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&ctg1=$ctg1&best=$best&ctg1=$ctg1";
$sessLink = "page=$page&" . $link;



if(!strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
$xls_name = date("Ymd")."_partner.xls";
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
<title>Irumtour</title>
<style type="text/css" media="screen">
	*{font-size:11pt;}
</style>
</head>

<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">



    <table border="1" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >번호</th>
		<th class="subject" >국가</th>
		<th class="subject" >거래처</th>
		<th class="subject" >담당자</th>
		<th class="subject" >연락처</th>
		<th class="subject" >등록일</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

	$bit_login = ($rs[bit_login])?"허용":"차단";
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><?=$num?></td>
	      <td><?=$rs[nation]?></td>
	      <td><?=$rs[company]?></td>
	      <td><?=$rs[name]?></td>
	      <td><?=$rs[phone]?></td>
	      <td><?=substr($rs[reg_date],0,10)?></td>
	    </tr>
<?
	$num--;
}
?>
	</table>
</body>
</html>