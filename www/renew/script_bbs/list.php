<?
include_once("common.php");


#### 기본 정보
$table = "ez_bbs";
$table2 = "ez_bbs_comment";
$view_row = ($SET_ASSORT=="2")?$EZ_BOARD_CONFIG_GALLERY_ROWS : $EZ_BOARD_CONFIG_ROWS;

$filter = " where bid = '$bid' and notice <> 1 and cp_id='$CID'";
$filterNotice = " where bid = '$bid' and notice = 1 and cp_id='$CID'";
if($CID) $filterNotice.=" and bit_cp<>1";
$column = "*";
$basicLink = "bid=$bid&filter=$filter";


#### Page
if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
#검색조건
$keyword = antiInjection(trim($keyword));

$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword)	 $filter.=" and $target like '%$keyword%' ";
if($category)	 $filter.=" and category like '%$category%' ";
if($flag)		 $filter.=" and flag = '$flag' ";
if($CID)         $filter.=" and bit_cp<>1";
//$filter = ($filter)? " where " . substr($filter,5) : "";

#query
$sql1 = "select $column from $table  $filter";			//자료수
$sql2 = $sql1 . " order by id_no desc limit  $start, $view_row";
//checkVar("",$sql2);

####자료갯수
list($rows)=$dbo->query($sql1);//검색된 자료의 갯수
$row_search = $rows;



####페이지 처리
$var=ceil($row_search/$view_row);
if ($var > 1){
	$total_page=$var;
}
else{
	$total_page=1;
}


####검색 항목
$selectTxt = "제목,내용,글쓴이";
$selectValue ="subject,content,name";


#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&flag=$flag&bid=$bid";
session_register("sessBoardLink");
$_SESSION["sessBoardLink"] = "page=$page&" . $link;
?>
<link rel="stylesheet" type="text/css" href="./script_bbs/css/ez_board.css" />
<link rel="stylesheet" type="text/css" href="./script_bbs/css/ez_default.css" />
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="./script_bbs/css/ez_board_ie.css" />
<![endif]-->




<div id="ez_conents_wrap">

<!-- search -->
    <div class="search_box">

	  <div id="tbl_bbslist_srch">
		<form name="fmSearch" method="get">
		<input type="hidden" name="bid" value="<?=$bid?>">
			<fieldset>
				<legend>게시물 검색</legend>
				<select name="target" class='serch_select' style="height:31px">
				<?=option_str($selectTxt,$selectValue,$target)?>
				</select>
				<input id="bbslistsrch_keyword" type="text" name="keyword" maxlength="40" class="serch_input" style="height:31px"/>
				<input type="image" src="public/ez_board/button/<?=$BTN_FIND?>"alt="검색" align="absmiddle" class="border0" style="height:31px"/>
			</fieldset>
		</form>
	  </div>

    </div>
	<!-- //search -->


	<?@include("public/inc/bbs_".$bid . "_top.inc");?>

	<?
	//게시판의 종류
	switch($SET_ASSORT){
		case "1": $list_name="normal"; break;
		case "2": $list_name="gallery"; break;
		case "3": $list_name="faq"; break;
		case "4": $list_name="webzine"; break;

	}

	include_once("list_".$list_name.".php");
	?>


	<?if(!$row_search){?>
	<div id="bbslist_msg">자료를 찾을 수 없습니다.</div>

	<?}?>

	<!-- paging -->
	<?include_once('navi.php')?>
	<!-- //paging -->

	<!-- //bbslist -->
	<?if($SET_POWER_WRITE <= $SET_GRADE){?>
	<!-- button -->
	<div class="right">
		<a href="?bmode=write&bid=<?=$bid?>"><img src="public/ez_board/button/<?=$BTN_WRITE?>"></a>
	</div>
	<!-- //button -->
	<?}?>




	<?@include("public/inc/bbs_".$bid . "_bottom.inc");?>


</div><!-- <div id="ez_conents_wrap"> -->