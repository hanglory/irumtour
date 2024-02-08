<?
include_once("../include/common_file.php");
?>
<script type="text/javascript">
<!--
$(function(){
	$(".rn").mask("999999-9999999");
});
//-->
</script>


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">

	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<th class="subject" >고객명</th>
		<th class="subject" ></th>
		<th class="subject" >성별</th>
		<th class="subject" >영문명</th>
		<th class="subject" >주민번호</th>
		<th class="subject" >여권번호</th>
		<th class="subject" >연락처</th>
		<th class="subject" >메모</th>
		<th class="subject" ></th>
		</tr>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><?=html_input('name',8,30)?></td>
	      <td><span class="btn_pack small bold"><a href="#" onClick="chkForm()"> 검색 </a></span></td>
	      <td><select name="sex"><?=option_str("M,F","M,F",$rs[sex])?></select></td>
	      <td><?=html_input('name_eng',20,30)?></td>
	      <td><?=html_input('rn',15,14,'box rn')?></td>
	      <td><?=html_input('passport_no',10,30)?></td>
	      <td><?=html_input('phone',15,30)?></td>
	      <td><?=html_input('memo',15,30)?></td>
	      <td>
			<span class="btn_pack small bold"><a href="#" onClick="chkForm()"> 저장 </a></span>&nbsp;
			<span class="btn_pack small bold"><a href="#" onClick="chkForm()"> 취소 </a></span>
		  </td>
	    </tr>


	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><?=html_input('name',8,30)?></td>
	      <td></td>
	      <td><select name="sex"><?=option_str("M,F","M,F",$rs[sex])?></select> </td>
	      <td><?=html_input('name_eng',20,30)?></td>
	      <td><?=html_input('rn',15,14,'box rn')?></td>
	      <td><?=html_input('passport_no',10,30)?></td>
	      <td><?=html_input('phone',15,30)?></td>
	      <td><?=html_input('memo',15,30)?></td>
	      <td>
			<span class="btn_pack small bold"><a href="#" onClick="chkForm()"> 수정 </a></span>&nbsp;
			<span class="btn_pack small bold"><a href="#" onClick="chkForm()"> 취소 </a></span>
		  </td>
	    </tr>
	</table>