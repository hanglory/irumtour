<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour_category1";
$MENU = "tour";
$TITLE = "상품관리 - 기차";
$today = time();


#### operation
if ($mode=="save"){

		//chkVars();

		$table = ($ctg)? "ez_tour_".$ctg : $table;

		if($sub_subject){
			$table = "ez_tour_category";
			$table .=substr($ctg,-1)+1;
			$parent_id_no=$id_no;
			$subject=$sub_subject;
			$id_no="";
			$seq = "";
		}

		if($table == "ez_tour_category1"){
			$sqlInsert="
			   insert into $table (
          cp_id,
				  subject,
				  bit_hide,
				  seq
			  ) values (
				  '$CP_ID',
          '$subject',
				  '$bit_hide',
				  '$seq'
			)";

			$sqlModify="
			   update $table set
				  subject = '$subject',
				  bit_hide = '$bit_hide',
				  seq='$seq'
			   where id_no='$id_no'
			";
		}
		elseif($table == "ez_tour_category2"){

		   If(!$seq){
			 $sql = "select * from ez_tour_category2 where code1=$parent_id_no ";
			 list($rows) = $dbo->query($sql);
			 $seq = $rows+1;
		   }

			$sqlInsert="
			   insert into ez_tour_category2 (
          cp_id,
				  code1,
				  subject,
				  bit_hide,
				  seq	,
				  bit,
				  seq_date
			  ) values (
				  '$CP_ID',
          '$parent_id_no',
				  '$subject',
				  '$bit_hide',
				  '$seq',
				  '$bit',
				  '$today'
			)";

			$sqlModify="
			   update ez_tour_category2 set
				  code1 = '$parent_id_no',
				  subject = '$subject',
				  bit_hide = '$bit_hide',
				  seq = '$seq',
				  bit = '$bit',
				  seq_date = '$today'
			   where id_no='$id_no'
			";
		}
		elseif($table == "ez_tour_category3"){

		   If(!$seq){
			 $sql = "select * from ez_tour_category3 where code2=$parent_id_no ";
			 list($rows) = $dbo->query($sql);
			 $seq = $rows+1;
		   }

			$sqlInsert="
			   insert into ez_tour_category3 (
          cp_id,
				  code2,
				  subject,
				  bit_hide,
				  seq,
				  seq_date
			  ) values (
				  '$CP_ID',
          '$parent_id_no',
				  '$subject',
				  '$bit_hide',
				  '$seq',
				  '$today'
			)";

			$sqlModify="
			   update ez_tour_category3 set
				  code2 = '$parent_id_no',
				  subject = '$subject',
				  bit_hide = '$bit_hide',
				  seq = '$seq',
				  seq_date = '$today'
			   where id_no='$id_no'
			";
		}
		elseif($table == "ez_tour_category4"){

		   If(!$seq){
			 $sql = "select * from ez_tour_category4 where code3=$parent_id_no ";
			 list($rows) = $dbo->query($sql);
			 $seq = $rows+1;
		   }

			$sqlInsert="
			   insert into ez_tour_category4 (
          cp_id,
				  code3,
				  subject,
				  bit_hide,
				  seq,
				  seq_date
			  ) values (
				  '$CP_ID',
          '$parent_id_no',
				  '$subject',
				  '$bit_hide',
				  '$seq',
				  '$today'
			)";

			$sqlModify="
			   update ez_tour_category4 set
				  code3 = '$parent_id_no',
				  subject = '$subject',
				  bit_hide = '$bit_hide',
				  seq = '$seq',
				  seq_date = '$today'
			   where id_no='$id_no'
			";
		}

		if($id_no){
			$sql = $sqlModify;
		}else{
			$sql = $sqlInsert;
		}

		//checkVar("",$sql);exit;

		if($dbo->query($sql)){

			//checkVar(mysql_error(),$sql);exit;

			//redirect2("category.php?ctg=$ctg&id_no=$id_no");
			echo "<script>parent.document.getElementById('tree').src='category_tree.php';history.back(-1);</script>";
		}else{
			//error("같은 이름의 분류가 있습니다.");
			//checkVar(mysql_error(),$sql);exit;
			checkVar(mysql_error(),$sql);
		}
		exit;

}elseif ($mode=="drop"){

	$table = ($ctg)? "ez_tour_".$ctg : $table;
	$sql = "delete from $table where id_no=$id_no";
	$dbo->query($sql);

	echo "<script>parent.document.getElementById('tree').src='category_tree.php';history.back(-1);</script>";
	exit;

}else{
	$table = "ez_tour_" . $ctg;

	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs=$dbo->next_record();

	if($ctg=="category1") $ctg1 = $rs[id_no];
	elseif($ctg=="category2"){ $ctg1 = $rs[code1];$ctg2 = $rs[id_no];}
	elseif($ctg=="category3"){ $ctg1 = $rs[code1];$ctg2 = $rs[code2];$ctg3 = $rs[id_no];}
	elseif($ctg=="category4"){ $ctg1 = $rs[code1];$ctg2 = $rs[code2];$ctg3 = $rs[code3];$ctg4 = $rs[id_no];}

	/*
	checkVar("ctg1",$ctg1);
	checkVar("ctg2",$ctg2);
	checkVar("ctg3",$ctg3);
	checkVar("ctg4",$ctg4);
	*/
}

If($rs[code3]) $code= $rs[code3];
elseIf($rs[code2]) $code= $rs[code2];
else $code= $rs[code1];
//-------------------------------------------------------------------------------
?>
<?include("../top_min.html");?>
<script language="JavaScript">
<!--
function chkForm(){
	var fm = document.fmData;
	if(check_blank(fm.subject,'분류명을',0)=='wrong'){return }
	fm.submit();
}

function del(id_no,ctg){
	if(id_no==""){alert('삭제할 카테고리를 선택하지 않으셨습니다.');return}
	if(confirm('삭제하시면 하위 카테고리가 있는 경우 함께 삭제됩니다.\n\n단, 여행상품이 지워지지는 않습니다. \n\n카테고리를 삭제하시겠습니까?')){
		location.href="?mode=drop&id_no=" + id_no + "&ctg="+ctg;
	}
}
//-->
</script>


	<!--내용이 들어가는 곳 시작-->


		<!--관리/등록/삭제-->
		<table border="0" cellspacing="1" cellpadding="3" class="viewWidth" width="100%">
			<form name="fmData" method="post" enctype="multipart/form-data">
			<input type="hidden" name="mode" value='save'>
			<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
			<input type="hidden" name="ctg" value='<?=$ctg?>'>
			<input type="hidden" name="parent_id_no" value='<?=$code?>'>

			<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>
			<?If($rs[id_no]){?>
			<tr>
			  <td  class="subject" width="25%">고유번호</td>
			  <td>
				   <?=$rs[id_no]?>
			  </td>
			</tr>
			<tr><td colspan="2" class="tblLine"></td></tr>
			<?}?>
			<tr>
			  <td  class="subject" width="25%">카테고리</td>
			  <td>
				   <?=($ctg1)?get_category_name_path($ctg1,1):"대분류"?>
				   <?=($ctg2)?">".get_category_name_path($ctg2,2):""?>
				   <?=($ctg3)?">".get_category_name_path($ctg3,3):""?>
				   <?=($ctg4)?">".get_category_name_path($ctg4,4):""?>
			  </td>
			</tr>
			<tr><td colspan="2" class="tblLine"></td></tr>

			<tr>
			  <td class="subject">카테고리명</td>
			  <td>
				<?=html_input('subject',30,40)?>
          <?if($rs[cp_id]==$CP_ID){?>
				  <label><input type="checkbox" name="bit_hide" value="1" <?=($rs[bit_hide])?'checked':''?>>감추기</label>
          <?}?>

			  </td>
			</tr>
			<tr><td colspan="2" class="tblLine"></td></tr>
			<tr>
			  <td class="subject">순서
			  </td>
			  <td>
				<?=html_input('seq',3,3,'box c')?>
			  </td>
			</tr>



			<?if($id_no && !$ctg4){?>
			<tr><td colspan="2" class="tblLine"></td></tr>
			<tr>
			  <td class="subject">하위 카테고리명 추가</td>
			  <td>
				<?=html_input('sub_subject',30,40)?>
			  </td>
			</tr>
			<?}?>
			<tr><td colspan="2" class="tblLine"></td></tr>

			<tr>
			  <td colspan=10>
				  <br>
				  <!-- Button Begin---------------------------------------------->
				  <table border="0" width="130" cellspacing="0" cellpadding="0" align="right">
					<tr align="right">
						<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
						<?if($id_no){?><td><span class="btn_pack medium bold"><a href="#" onClick="del('<?=$rs[id_no]?>','<?=$ctg?>')"> 삭제 </a></span></td><?}?>
					</tr>
				  </table>
				  <!-- Button End------------------------------------------------>
			  </td>
			</tr>
			<tr>
			  <td colspan=10 height=20>
			  </td>
			</tr>
		</form>
		</table>

		<!--//관리/등록/삭제-->
