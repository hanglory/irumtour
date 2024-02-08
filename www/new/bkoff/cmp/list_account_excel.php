<?
include_once("../include/common_file.php");


chk_power($_SESSION["sessLogin"]["proof"],"기본설정");



####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_account";
$MENU = "cmp_basic";
$TITLE = "송금계좌 정보";

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
$filter2 = str_replace("partner like","company like",$filter);


#query
$sql_1 = "
    select
        id_no,
        'account' as stype,
        partner,
        bank,
        account,
        owner,
        reg_date
    from cmp_account as a where id_no>0
        and cp_id='$CP_ID'
        $filter

    union all

    select
        id_no,
        'partner' as stype,
        company as partner,
        bank,
        account,
        owner,
        reg_date
    from cmp_partner as a where account<>''
        and cp_id='$CP_ID'
        $filter2

    group by account,owner,bank
";
$sql_2 = $sql_1 . " order by id_no desc";


if(!strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
$xls_name = date("Ymd")."_account.xls";
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



    <table border="1" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

		<th class="subject" >번호</th>
		<th class="subject" >거래처</th>
		<th class="subject" >은행명</th>
		<th class="subject" >계좌번호</th>
		<th class="subject" >예금주</th>
		<th class="subject" >등록일</th>
		</tr>
<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql_2);
//checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record()){

?>
	    <tr align='center'>
	      <td><?=$num?></td>
	      <td><?=$rs[partner]?></td>
	      <td><?=$rs[bank]?></td>
	      <td><?=$rs[account]?></td>
	      <td><?=$rs[owner]?></td>
	      <td><?=substr($rs[reg_date],0,10)?></td>
	    </tr>
<?
	$num--;
}
?>
	</table></body>
</html>
