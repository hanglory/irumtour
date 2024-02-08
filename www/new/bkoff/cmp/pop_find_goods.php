<?
include_once("../include/common_file.php");



####기초 정보
$filecode = substr(SELF,5,-4);
$TITLE = "상품 검색";

$keyword2 = str_replace(" ","",secu($keyword));
$id_no = rnf($id_no);
$filter = ($id_no)? " and id_no=$id_no":"";
$filter = ($keyword)? " and replace(name,' ','') like '%$keyword2%' ":"";

$sql = "
        select 
            * 
        from cmp_golf 
        where 
            id_no>0 
            $filter 
            $FILTER_PARTNER_GOLF_QUERY
        order by nation asc,city asc, name asc
    ";
list($rows) =  $dbo->query($sql);
?>
<?include("../top_min.html");?>
<script type="text/javascript">
<!--
function set_data(id_no,name,partner,staff){

	opener.document.getElementById('golf').value=name;
	opener.document.getElementById('golf_name').value=name;
	opener.document.getElementById('golf_id_no').value=id_no;

	opener.document.getElementById('partner').value=partner;
	<?if(!$bit){?>
	opener.document.getElementById('partner_staff').value=staff;
	<?}?>


	opener.set_golf(name,id_no);

	self.close();
}

$(function(){
	$("#keyword").focus();
});
//-->
</script>

<div style="padding:0 10px 0 10px">

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con" style="padding-left:10px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
	  </tr>
	  <tr>
		<td> </td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	</table>

	<!--내용이 들어가는 곳 시작-->

	<!-- Search Begin------------------------------------------------>
	<div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
	<form name="fmSearch" method="get" action="<?=SELF?>">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="hidden" name="id_no" value="<?=$id_no?>">
	<input type="hidden" name="bit" value="<?=$bit?>">


	<tr height=22>
	<td align=right valign=top>
	<input class=box type="text" name="keyword" id="keyword" size="15" maxlength="40" value='<?=$keyword?>'>
	<span class="btn_pack medium bold"><a href="javascript:document.fmSearch.submit()"> 검색 </a></span>
	&nbsp;&nbsp;&nbsp;&nbsp;
	<span class="btn_pack medium bold"><a href="javascript:self.close()"> 창닫기 </a></span>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">

		<tr><td colspan="12" bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height=25 bgcolor="#F7F7F6">
		<th class="subject" >국가</th>
		<th class="subject" >지역</th>
		<th class="subject" >상품명</th>
		<th class="subject" >거래처</th>
		<th class="subject" ></th>
		</tr>

		<?
		$j=1;
        if($debug) checkVar(mysql_error(),$sql);
		while($rs=$dbo->next_record()){
			$rs[name]=addslashes($rs[name]);
		?>
	    <tr height="30" align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><?=$rs[nation]?></td>
	      <td><?=$rs[city]?></td>
	      <td><?=$rs[name]?></td>
	      <td><?=$rs[partner]?></td>
	      <td>
			<span class="btn_pack medium bold"><a href="javascript:set_data('<?=$rs[id_no]?>','<?=$rs[name]?>','<?=$rs[partner]?>','<?=$rs[main_staff]?>')"> 선택 </a></span>
		  </td>
	    </tr>
        <tr><td colspan="12" class="tblLine"></td></tr>
		<?
		$j++;
		}?>
		<?if(!$rows){?>
        <tr><td colspan="12" class="c" height="50">해당 하는 자료를 찾을 수 없습니다.</td></tr>
        <tr><td colspan="12" class="tblLine"></td></tr>
		<?}?>

	</table>

</div>

	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>