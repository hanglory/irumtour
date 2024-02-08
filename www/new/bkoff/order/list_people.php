<?
include_once("../include/common_file.php");


#### 기본 정보
$table = "ez_order_people";

$sql = "select * from ez_order where oid=$oid";
$dbo->query($sql);
$rs=$dbo->next_record();
$SUBJECT = $rs[subject];
$ADULT = $rs[adult];


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
$filter = " where oid=$oid";
$sort = ($sort)? $sort : "seq asc";

#query
$sql1 = "select * from $table  $filter";			//자료수
$sql2 = $sql1 . " order by $sort";
//checkVar("",$sql2);


####자료갯수
list($tourlist_rows)=$dbo->query($sql1);//검색된 자료의 갯수
$row_search = $rows;



####자료가 하나도 없을 경우의 처리
if(!$row_search){
   $error[noData] = accentError("해당하는 자료가 없습니다.");
}


#### Link
$link = "keyword=$keyword&target=$target&sort=$sort";
$sessLink = "page=$page&" . $link;

//-------------------------------------------------------------------------------
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>지구투어 관리자>여행자목록</title>
<link rel="stylesheet" href="../include/default.css">
<link rel="stylesheet" href="../include/basic.css" type="text/css">
<script language="JavaScript" src="../../include/form_check.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script language="JavaScript" src="../../include/function.js"></script>
<script type="text/javascript">
<!--
$(function(){
	set_people(<?=$ADULT?>);

});

function set_people(seat){

	if(seat == "") seat = $("#adult").val();
	if(seat == "") seat = 1;

	$('#people').load('../../include_order_people.php', {
	  'sp': 'admin',
	  'tid': '<?=$tid?>',
	  'oid': '<?=$oid?>',
	  'adult': $("#adult").val(),
	  'seat': seat
	});
}

//-->
</script>
</head>


<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

	<div style="width:98%;padding-left:2%;">


		<table width="100%" border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle">여행자 목록 - <?=$SUBJECT?> </td>
		  </tr>
		  <tr>
			<td> </td>
		  </tr>
		   <tr>
			<td background="../images/common/bg_title.gif" height="5"></td>
		  </tr>
		</table>

		<br>


		<!--여행자정보 입력-->
		<div id="people"></div>
		<!--//여행자정보 입력-->


		<div style="margin-top:10px">
		<!-- Button Begin---------------------------------------------->
		<table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
		 <tr>
		  <td></td>
		  <td align="right"><!-- <span class="btn_pack medium bold"><a href="#" onClick="location.href='view_<?=$filecode?>.php'"> 등록 </a></span>&nbsp; -->
		  <span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span></td>
		</tr>
		</table>
		<!-- Button End------------------------------------------------>
		</div>


	</div>


	<!--기능을 위해 사용하는 빈 iframe-->
	<iframe name="actarea" style="display:none;" title="내용 없음"></iframe>

	 </body>
</html>

