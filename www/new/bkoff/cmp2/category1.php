<?
include_once("../include/common_file.php");

#### Menu
$assort_subject=($assort)?$assort:"��ü";
$filecode = "category1";
$TITLE = "��ǰ�з�(��з�)";
$MENU = "goods";
$table = "ez_category1";


switch($mode){

	case "up":
		$top2 = $top-1;
		$sql1 = "update $table set seq=seq+1 where seq=$top2  " ;
		$sql2 = "update $table set seq=seq-1 where id_no = '$id_no'   " ;

		$dbo->query($sql1);
		$dbo->query($sql2);

		echo "<script>history.back(-1)</script>";
		exit;
		break;

	case "down":
		$top2 = $top+1;
		$sql1 = "update $table set seq=seq-1 where seq=$top2   " ;
		$sql2 = "update $table set seq=seq+1 where id_no = '$id_no'   " ;

		$dbo->query($sql1);
		$dbo->query($sql2);
		//checkVar(mysql_error(),$sql1);
		//checkVar(mysql_error(),$sql2);exit;

		echo "<script>history.back(-1)</script>";
		exit;
		break;
}




####���� ���� ���� ����
$view_row=20;	//�� �������� ������ �� ���� ����

if(!$page){		//������ ����Ʈ ���� ����
	$page=1;
}
$start=($view_row*($page-1))+1;	//�������� ���� ó�� �ҷ��� row�� �����͸� ����
$start=$start-1;



#### �⺻ ����


$column = "*";
$basicLink = "";


####�����͸� �ҷ����� ���� ����(search�� ��츦 ����)


#�˻�����
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword){
	$filter.=" and $target like '%$keyword%' ";
	$findMode=1;
}

if($filter) $filter = " where " . substr($filter,5);

#query
$sql1 = "select $column from $table $filter";			//�ڷ��
$sql2 = $sql1 . " order by seq asc "; //limit  $start, $view_row
//checkVar("",$sql2);


####�ڷ᰹��
list($rows)=$dbo->query($sql1);//�˻��� �ڷ��� ����
$row_search = $rows;



####������ ó��

$var=ceil($row_search/$view_row);
if ($var > 1){
	$total_page=$var;
}
else{
	$total_page=1;
}



####�ڷᰡ �ϳ��� ���� ����� ó��
if(!$row_search){
   $error[noData] = accentError("�ش��ϴ� �ڷᰡ �����ϴ�.");
}



####�˻� �׸�
$selectTxt = "�����з���,�����з���";
$selectValue ="category1,eng_category1";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target&assort=$assort&assor2=$assort2";
$sessLink = "page=$page&" . $link;


//-------------------------------------------------------------------------------
?>
<script language="JavaScript">
<!--
function selectAll(){
	fm = document.fmBbs;
	for(var i = 1; i < fm.elements.length; i++){
		fm.elements[i].checked = (fm.checkAll.checked == 1)? 1 : 0;
	}
}


function del(){
	var j = 0;
	fm = document.fmBbs;

	for(var i = 1; i < fm.elements.length; i++){
		if(fm.elements[i].checked == 1){
			j++;
		}
	}
	if(j == 0){
		alert("������ �ڷḦ �������� �����̽��ϴ�.");
		return;
	}
	if(confirm("������ �ڷḦ �����Ͻðڽ��ϱ�?")){
		fm.action="view_category1.php";
		fm.submit();
	}
}
//-->
</script>
<?include("../top.html");?>



	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
	  </tr>
	  <tr>
		<td> </td>
	  </tr>
	   <tr>
		<td background="../images/common/bg_title.gif" height="5"></td>
	  </tr>
	</table>
      <br>

	<!-- Search Begin------------------------------------------------>
	<table border=0  width=100% cellspacing="0" cellpadding="0">
	<form name="fmSearch">
	<input type="hidden" name='flag' value="<?=$flag?>">
	<input type="hidden" name='assort' value="<?=$assort?>">
	<input type="hidden" name='assort2' value="<?=$assort2?>">

	<tr height=22>
	<td><font color="#666666">* <?=($status)?> �ڷ��: <?=number_format($row_search)?>�� { <?=number_format($total_page)?> page /  <?=number_format($page)?> page }</font></td>
	<td valign='bottom' align=right>
	<?if($findMode):?>
	<input class=button type="button" value="��ü���" onclick="location.href='<?=SELF?>'">
	<?endif;?>

	<select name="target" class='select'>
	<?=option_str($selectTxt,$selectValue,$target)?>
	</select>

	<span class="top"><input class="box" type="text" name="keyword" maxlength="40" value='<?=($keyword=="Iw==")? "#":$keyword;?>'></span>
	<span class="btn_pack small"><a href="#" onClick="document.fmSearch.submit()"> �˻� </a></span>
	</td>
	<tr>
	</form>
	</table>
	<!-- Search End------------------------------------------------>

	<!--������ ���� �� ����-->


    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
       <form name=fmBbs>
       <input type=hidden name=mode value='drop'>
       <input type=hidden name=assort value="<?=$assort?>">
       <input type=hidden name=assort2 value="<?=$assort2?>">

        <tr><td colspan="9"  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<td class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
		<td class="subject" width="50"><b>��ȣ</b></td>
		<td class="subject"><b>�빮����</b></td>
		<!-- <td class="subject"><b>�빮����(����)</b></td> -->
		<td class="subject"><b></b></td>
		</tr>
		<tr><td colspan="9"  bgcolor='#E1E1E1'></td></tr>

<?
if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql2);
$j=1;
while($rs=$dbo->next_record()){
	$dbo3->query("update $table set seq=$j where id_no=$rs[id_no]");
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="35"><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"></td>
	      <td><?=$num?></td>
	      <td><a class=soft href="view_<?=$filecode?>.php?id_no=<?=$rs[id_no];?>&assort=<?=$assort?>&assort2=<?=$assort2?>" onFocus='blur(this)'><?=$rs[category1]?></a></td>
		  <!-- <td><?=$rs[eng_category1]?></td> -->
		  <td><?if(!$findMode){?>
			<?if($num!=1){?><a href='?mode=down&id_no=<?=$rs[id_no]?>&top=<?=$rs[seq]?>&assort=<?=$assort?>&assort2=<?=$assort2?>' onfocus="blur(this)" class=soft>��</a><?}?>
			<?if($num!=$row_search){?><a href='?mode=up&id_no=<?=$rs[id_no]?>&top=<?=$rs[seq]?>&assort=<?=$assort?>&assort2=<?=$assort2?>' onfocus="blur(this)" class=soft>��</a><?}?>
			<?}?>
		  </td>
	    </tr>
        <tr><td colspan="9"  bgcolor='#CCCCFF'></td></tr>
<?
	$j++;
	$num--;
}
?>
		<tr><td colspan=9 height=1><?=$error[noData]?></td></tr>
        <tr><td colspan=9  bgcolor='#E1E1E1' height=3></td></tr>
        <tr>
	  <td colspan=9>

	  <br>
	  <!-- Button Begin---------------------------------------------->
			  <table width="130" border="0" cellspacing="0" cellpadding="0" align="right">
				 <tr align="right">
				  <td><span class="btn_pack medium bold"><a href="#" onClick="location.href='view_<?=$filecode?>.php?assort=<?=$assort?>&assort2=<?=$assort2?>'"> ��� </a></span></td>
				  <td><span class="btn_pack medium bold"><a href="#" onClick="del()"> ���� </a></span></td>
				</tr>
			  </table>
	  <!-- Button End------------------------------------------------>

	  </td>
        </tr>

	</form>
	</table>





	<!--������ ���� �� ��-->

<!-- Copyright -->
<?include_once("../bottom.html");?>