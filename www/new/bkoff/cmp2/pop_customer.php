<?
include_once("../include/common_file.php");



####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_customer";
$TITLE = "고객정보";

$name = trim($name);

$sql = "select * from $table where name like '%$name%'  order by name asc";
list($rows) =  $dbo->query($sql);

?>
<?include("../top_min.html");?>
<script type="text/javascript">
<!--
function set_user(id,i,j){

	<?if($page){?>
		opener.$("#name").val($(".name_"+j).text());
		opener.$("#phone").val($(".phone_"+j).text());
	<?}else{?>
		if(i){
			opener.$("#id_"+i).val(id);
			opener.$("#name_"+i).val($(".name_"+j).text());
			opener.$("#sex_"+i).val($(".sex_"+j).text());
			opener.$("#name_eng_"+i).val($(".name_eng_"+j).text());
			opener.$("#rn_"+i).val($(".rn_"+j).text());
			opener.$("#passport_no_"+i).val($(".passport_no_"+j).text());
			opener.$("#passport_limit_"+i).val($(".passport_limit_"+j).text());
			opener.$("#phone_"+i).val($(".phone_"+j).text());

		}else{
			opener.$("#id").val(id);
			opener.$("#name").val($(".name_"+j).text());
			opener.$("#sex").val($(".sex_"+j).text());
			opener.$("#name_eng").val($(".name_eng_"+j).text());
			opener.$("#rn").val($(".rn_"+j).text());
			opener.$("#passport_no").val($(".passport_no_"+j).text());
			opener.$("#passport_limit_").val($(".passport_limit_"+j).text());
			opener.$("#phone").val($(".phone_"+j).text());
		}
	<?}?>
	self.close();
}

//-->
</script>
<script language="JavaScript">

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
		<th class="subject" >대표자</th>
		<th class="subject" >고객명</th>
		<th class="subject" >성별</th>
		<th class="subject" >영문명</th>
		<th class="subject" >주민번호</th>
		<th class="subject" >여권번호</th>
		<th class="subject" >유효기간</th>
		<th class="subject" >연락처</th>
		<th class="subject" ></th>
		</tr>

		<?
		$j=1;
		while($rs=$dbo->next_record()){
			if($rs[rn]){
			$aes = new AES($rs[rn], $inputKey, $blockSize);
			$dec=$aes->decrypt();
			$rs[rn] = $dec;
			}
			if($rs[passport_no]){
			$aes = new AES($rs[passport_no], $inputKey, $blockSize);
			$dec=$aes->decrypt();
			$rs[passport_no] = $dec;
			}

		?>
	    <tr height="35" align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><span class="leader_<?=$j?>"><?=$rs[leader]?></span></td>
	      <td><span class="name_<?=$j?>"><?=$rs[name]?></span></td>
	      <td><span class="sex_<?=$j?>"><?=$rs[sex]?></span></td>
	      <td><span class="name_eng_<?=$j?>"><?=$rs[name_eng]?></span></td>
	      <td><span class="rn_<?=$j?>"><?=$rs[rn]?></span></td>
	      <td><span class="passport_no_<?=$j?>"><?=$rs[passport_no]?></span></td>
	      <td><span class="passport_limit_<?=$j?>"><?=$rs[passport_limit]?></span></td>
	      <td><span class="phone_<?=$j?>"><?=$rs[phone]?></span></td>
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