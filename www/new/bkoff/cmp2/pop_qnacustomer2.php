<?
include_once("../include/common_file.php");



####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_qna";
$TITLE = "문의 고객정보";

$name = trim($name);

$sql = "select * from $table where name like '%$name%'  order by name asc";
list($rows) =  $dbo->query($sql);

?>
<?include("../top_min.html");?>
<script type="text/javascript">
<!--
function set_user(id,i,j){
	opener.$("#name").val($(".name_"+j).text());
	opener.$("#phone").val($(".phone_"+j).text());
	self.close();
}

//-->
</script>

<div style="padding:0 10px 0 10px">

	<table width="97%" border="0" cellspacing="0" cellpadding="0">
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

	<br>

	<!-- Search Begin------------------------------------------------>
	<div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="97%" id="tbl_list">
	<form name=fmSearch method="get">
	<input type=hidden name='i' value="<?=$i?>">
	<input type=hidden name='page' value="<?=$page?>">


	<tr height=22>
	<td align=right valign=top>
	<input class=box type="text" name="name" size="15" maxlength="40" value='<?=$name?>'>
	<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>


    <table border="0" cellspacing="0" cellpadding="3" width="97%" id="tbl_list">

		<tr><td colspan="12"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >고객명</th>
		<th class="subject" >연락처</th>
		<th class="subject" >국가</th>
		<th class="subject" >인원</th>
		<th class="subject" >수준</th>
		<th class="subject" >기간</th>
		<th class="subject" >담당자</th>
		<th class="subject" ></th>
		</tr>

		<?
		$j=1;
		while($rs=$dbo->next_record()){
		?>
	    <tr height="35" align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><span class="name_<?=$j?>"><?=$rs[name]?></span></td>
	      <td><span class="phone_<?=$j?>"><?=$rs[phone]?></span></td>
	      <td><span class="nation_<?=$j?>"><?=$rs[nation]?></span></td>
	      <td><span class="people_<?=$j?>"><?=$rs[people]?></span></td>
	      <td><span class="qlevel_<?=$j?>"><?=$rs[qlevel]?></span></td>
	      <td><span class="period_<?=$j?>"><?=$rs[period]?></span></td>
	      <td><span class="staff_<?=$j?>"><?=$rs[main_staff]?></span></td>
	      <td>
			<span class="btn_pack small bold"><a href="javascript:set_user('<?=$rs[id_no]?>','<?=$i?>','<?=$j?>')"> 선택 </a></span>
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