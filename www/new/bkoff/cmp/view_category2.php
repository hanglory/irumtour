<?
include_once("../include/common_file.php");



####각종 기초 정보 결정
$view_row=10;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$filecode = substr(SELF,5,-4);
$table = "ez_category2";
$MENU = "cmp_paper";
$TITLE = "비용 대분류 정보";
$filter = "";
$column = "*";
$basicLink = "";


function chk_val_ctg3($val){
	$val = trim($val);
	$val = str_replace("'","",$val);
	$val = str_replace("\"","",$val);
	$val = str_replace(",,",",",$val);
	if(substr($val,0,1)==",") $val = substr($val,1);
	if(substr($val,-1)==",") $val = substr($val,0,-1);
	$val = trim($val);
	return $val;
}

switch($mode){
	case "save":
		if(!$id_no){

			$category3= chk_val_ctg3($category3);

			$sql = "select max(seq)+1 as seq from $table where category1='$category1' and cp_id='$CP_ID'";
			$dbo->query($sql);
			$rs=$dbo->next_record();
			$seq= $rs[seq];
			$seq= ($seq)?$seq:1;

			$sql="
				insert into $table (
                    cp_id,
					category1,
					category2,
					category3,
					category2_en,
					category2_jp,
					category3_eng,
					seq
				) values (
					'$CP_ID',
                    '$category1',
					'$category2',
					'$category3',
					'$category2_en',
					'$category2_jp',
					'$category3_eng',
					'$seq'
				)
				";

			$goUrl = "category2.php?category1=$category1";


		}else{	//modify인 경우

			$category3= chk_val_ctg3($category3);

			$sql ="update $table set ";
			$sql .= "category1='$category1',";
			$sql .= "category2='$category2',";
			$sql .= "category3='$category3',";
			$sql .= "category2_en='$category2_en',";
			$sql .= "category2_jp='$category2_jp',";
			$sql .= "category3_eng='$category3_eng'";
			$sql .= " where id_no=$id_no and cp_id='$CP_ID'";

			$basicLink = "table=$table&table2=$table2";
			$goUrl = "?id_no=$id_no&$basicLink";
		}

		//if($_SERVER["REMOTE_ADDR"]=="106.246.54.27"){checkVar("",$sql);exit;}

		if($dbo->query($sql)){

			if($id_no){
				$sql = "update cmp_expense set category1='$category1',category2='$category2' where category1='$category1_old' and category2='$category2_old' and cp_id='$CP_ID'";
				$dbo->query($sql);
				$sql = "update ez_category2 set category2='$category2' where category1='$category1_old' and category2='$category2_old' and cp_id='$CP_ID'";
				$dbo->query($sql);
			}

		}

		//checkVar(mysql_error(),$sql);exit;
		//include_once("make_js.php");
		redirect2($goUrl);
		exit;
		break;


	 case "drop":

		for($i = 0; $i < count($check);$i++){
			$sql = "select *  from $table where id_no=$check[$i] and cp_id='$CP_ID'";
			$dbo->query($sql);
			$rs=$dbo->next_record();
			if($rs[filename1]) @unlink("../../public/category/$rs[filename1]");
			if($rs[filename2]) @unlink("../../public/category/$rs[filename2]");
			if($rs[filename3]) @unlink("../../public/category/$rs[filename3]");
			if($rs[filename4]) @unlink("../../public/category/$rs[filename4]");
			if($rs[filename5]) @unlink("../../public/category/$rs[filename5]");
			if($rs[filename6]) @unlink("../../public/category/$rs[filename6]");

			$sql = "update $table set seq=seq-1 where seq > '$rs[seq]'  and cp_id='$CP_ID'";
			$dbo->query($sql);

			$sql = "delete from $table where id_no = $check[$i] and cp_id='$CP_ID'";
			$dbo->query($sql);
		}
		redirect2("category2.php?assort=$assort&assort2=$assort2&category1=$category1");exit;

	default:
		$sql = "select * from $table where id_no = $id_no";
		$dbo->query($sql);
		$rs = $dbo->next_record();
}

$rs[category1] = ($rs[category1])?$rs[category1]:$category1;
?>
<script language="JavaScript">
<!--
function chkForm(fm){
	fm = document.fmData;
	if(check_select(fm.category1,'대분류를')=='wrong'){return }
	if(check_blank(fm.category2,'소분류를',0)=='wrong'){return }
	//if(check_blank(fm.category3,'소분류를',0)=='wrong'){return }
	fm.submit();
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

      <table border="0" cellspacing="1" cellpadding="3" class="viewWidth" width=100%>

		<form name=fmData method=post enctype="multipart/form-data">
		<input type=hidden name=mode value='save'>
		<input type=hidden name=id_no value='<?=$rs[id_no]?>'>
		<input type=hidden name=category1_old value='<?=$rs[category1]?>'>
		<input type=hidden name=category2_old value='<?=$rs[category2]?>'>

		<tr><td colspan=2 bgcolor='#408080' height="1"></td></tr>

 		<tr>
          <td height="20" class="subject" width="20%"><b>*대분류</b></td>
          <td height="20">

<?
$sql2= "select * from ez_category1 where cp_id='$CP_ID' order by seq asc";
$dbo2->query($sql2);
while($rs2=$dbo2->next_record()){
	$categorys .= ",". $rs2[category1];
}

?>
            <select name="category1" class="category1">
				<?=option_str("선택하세요".$categorys,$categorys,$rs[category1])?>
			</select>

          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

		<tr>
          <td height="20" class="subject">*<b>소분류</b></td>
          <td height="20">
            <input class=box type="text" name="category2" value="<?=$rs[category2]?>" size=40 maxlength=40>
          </td>
        </tr>
        <!-- <tr><td colspan="2" class="tblLine"></td></tr>
		<tr>
          <td height="20" class="subject"><b>소분류</b></td>
          <td height="20">
            <?=html_textarea("category3",0,5)?>
			콤마(,)로 구분
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr> -->




        <tr><td colspan=2 bgcolor='#408080' height="1"></td></tr>

        <tr>
		<td colspan=2>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" cellspacing="5" cellpadding="0" align="right">
		    <tr>
			<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
			<td><span class="btn_pack medium bold"><a href="category2.php"> 목록 </a></span></td>
		      <td width = 20>&nbsp;</td>
		    </tr>
		  </table>
		  <!-- Button End------------------------------------------------>
		</td>
		</tr>
      </table>

    </td>
  </tr>
</table>

</form>

<!-- Copyright -->
<?include_once("../bottom.html");?>
