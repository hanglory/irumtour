<?
include_once("../include/common_file.php");


#### Menu
$filecode = substr(SELF,5,-4);
$TITLE = "서브배너";
$MENU = "basic";
$table = "ez_nbanner2";


#### operation

if ($mode=="save"){

		$category1 = "${category_step1}-${category_step2}-${category_step3}";
		$category2 = "${category2_step1}-${category2_step2}-${category2_step3}";
		$category3 = "${category3_step1}-${category3_step2}-${category3_step3}";
		$category4 = "${category4_step1}-${category4_step2}-${category4_step3}";
		$category5 = "${category5_step1}-${category5_step2}-${category5_step3}";
		$category6 = "${category6_step1}-${category6_step2}-${category6_step3}";

		If($category1=="--") $category1="";
		If($category2=="--") $category2="";
		If($category3=="--") $category3="";
		If($category4=="--") $category4="";
		If($category5=="--") $category5="";
		If($category6=="--") $category6="";

		$category1 = str_replace("--","-",$category1);
		$category2 = str_replace("--","-",$category2);
		$category3 = str_replace("--","-",$category3);
		$category4 = str_replace("--","-",$category4);
		$category5 = str_replace("--","-",$category5);
		$category6 = str_replace("--","-",$category6);
		$category7 = str_replace("--","-",$category7);

		$reg_date = date("Y/m/d");

		$naming = 'lmir_';
		if($_FILES["file1"]["size"]){
			#------------------------------------------
			$path="../../public/banner";	//업로드할 파일의 경로
			$maxsize=10 *(1024*1024) ;			//2MB	업로드 가능한 최대 사이즈 제한
			$fname=$_FILES["file1"]["tmp_name"];						//파일이름을 담고 있는 변수 이름
			$fname_name=$_FILES["file1"]["name"];	//파일의 이름
			$fname_size=$_FILES["file1"]["size"];		//파일의 사이즈
			$fname_type=$_FILES["file1"]["type"];		//파일의 type
			$filename=$naming . time();		//파일이름 작명
			$type = "normal"; // 일반파일 normal, 이미지만 image
			#------------------------------------------
			include("../../include/file_upload.php");
			$upfile1 = $upfile;
			$upfile1_real = $_FILES["file1"]["name"];
			$upfileQuery1 = ($upfile)? "filename = '$upfile1', ":"" ;
		}

        if(strstr($url,"me2.do")){
            echo "
                <script>
                    alert('배너에는 단축 URL을 사용할 수 없습니다.');
                    parent.document.fmData.url.focus();
                </script>
            ";
            exit;
        }
        $url = set_tour_link($url);

		$reg_date = time();

		$sqlInsert="
		   insert into $table (
              cp_id,
			  category1,
			  category2,
			  category3,
			  category4,
			  category5,
			  category6,
			  text1,
			  text2,
			  text3,
			  target,
			  url,
			  filename,
			  bit_hide,
			  reg_date,
			  seq
		  ) values (
			  '$CP_ID',
              '$category1',
			  '$category2',
			  '$category3',
			  '$category4',
			  '$category5',
			  '$category6',
			  '$text1',
			  '$text2',
			  '$text3',
			  '$target',
			  '$url',
			  '$upfile1',
			  '$bit_hide',
			  '$reg_date',
			  '0'
		)";

		$sqlModify="
		   update $table set
			  $upfileQuery1
			  category1 = '$category1',
			  category2 = '$category2',
			  category3 = '$category3',
			  category4 = '$category4',
			  category5 = '$category5',
			  category6 = '$category6',
			  bit_hide = '$bit_hide',
			  text1 = '$text1',
			  text2 = '$text2',
			  text3 = '$text3',
			  url = '$url',
			  target = '$target'
		   where id_no='$id_no'
            $FILTER_PARTNER_QUERY
            limit 1
		";

		if($id_no){
			$sql = $sqlModify;
			$url = "view_${filecode}.php?id_no=$id_no&assort=$assort";
		}else{
			$sql = $sqlInsert;
			$url = "list_${filecode}.php?assort=$assort";
		}

		if($dbo->query($sql)){

			if($id_no){
				$code = $id_no;
				if($category1_old && $category1!=$category1_old){
					$arr = explode("-",$category1_old);
					$sql = "select seq from ez_nbanner2_seq where code = $code and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
					$dbo->query($sql);
					$rs=$dbo->next_record();
					//checkVar($rs[seq] . mysql_error(),$sql);
					if($rs[seq]){
						$sql = "update ez_nbanner2_seq set seq=seq-1 where seq > '$rs[seq]' and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
						$dbo->query($sql);
						//checkVar(mysql_error(),$sql);
						$sql = "delete from ez_nbanner2_seq where code = $code and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
						$dbo->query($sql);
						//checkVar(mysql_error(),$sql);
						$category1_old="";
					}
				}
				if($category2_old && $category2!=$category2_old){
					$arr = explode("-",$category2_old);
					$sql = "select seq from ez_nbanner2_seq where code = $code and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
					$dbo->query($sql);
					$rs=$dbo->next_record();
					//checkVar($rs[seq] . mysql_error(),$sql);
					if($rs[seq]){
						$sql = "update ez_nbanner2_seq set seq=seq-1 where seq > '$rs[seq]' and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
						$dbo->query($sql);
						//checkVar(mysql_error(),$sql);
						$sql = "delete from ez_nbanner2_seq where code = $code and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
						$dbo->query($sql);
						//checkVar(mysql_error(),$sql);
						$category2_old="";
					}
				}
				if($category3_old && $category3!=$category3_old){
					$arr = explode("-",$category3_old);
					$sql = "select seq from ez_nbanner2_seq where code = $code and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
					$dbo->query($sql);
					$rs=$dbo->next_record();
					//checkVar($rs[seq] . mysql_error(),$sql);
					if($rs[seq]){
						$sql = "update ez_nbanner2_seq set seq=seq-1 where seq > '$rs[seq]' and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
						$dbo->query($sql);
						//checkVar(mysql_error(),$sql);
						$sql = "delete from ez_nbanner2_seq where code = $code and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
						$dbo->query($sql);
						//checkVar(mysql_error(),$sql);
						$category3_old="";
					}
				}
				if($category4_old && $category4!=$category4_old){
					$arr = explode("-",$category4_old);
					$sql = "select seq from ez_nbanner2_seq where code = $code and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
					$dbo->query($sql);
					$rs=$dbo->next_record();
					//checkVar($rs[seq] . mysql_error(),$sql);
					if($rs[seq]){
						$sql = "update ez_nbanner2_seq set seq=seq-1 where seq > '$rs[seq]' and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
						$dbo->query($sql);
						//checkVar(mysql_error(),$sql);
						$sql = "delete from ez_nbanner2_seq where code = $code and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
						$dbo->query($sql);
						//checkVar(mysql_error(),$sql);
						$category4_old="";
					}
				}
				if($category5_old && $category5!=$category5_old){
					$arr = explode("-",$category5_old);
					$sql = "select seq from ez_nbanner2_seq where code = $code and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' ";
					$dbo->query($sql);
					$rs=$dbo->next_record();
					//checkVar($rs[seq] . mysql_error(),$sql);
					if($rs[seq]){
						$sql = "update ez_nbanner2_seq set seq=seq-1 where seq > '$rs[seq]' and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' ";
						$dbo->query($sql);
						//checkVar(mysql_error(),$sql);
						$sql = "delete from ez_nbanner2_seq where code = $code and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' ";
						$dbo->query($sql);
						//checkVar(mysql_error(),$sql);
						$category5_old="";
					}
				}
				if($category6_old && $category6!=$category6_old){
					$arr = explode("-",$category6_old);
					$sql = "select seq from ez_nbanner2_seq where code = $code and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
					$dbo->query($sql);
					$rs=$dbo->next_record();
					//checkVar($rs[seq] . mysql_error(),$sql);
					if($rs[seq]){
						$sql = "update ez_nbanner2_seq set seq=seq-1 where seq > '$rs[seq]' and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
						$dbo->query($sql);
						//checkVar(mysql_error(),$sql);
						$sql = "delete from ez_nbanner2_seq where code = $code and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
						$dbo->query($sql);
						//checkVar(mysql_error(),$sql);
						$category6_old="";
					}
				}



			}else{

				$sql = "select * from ez_nbanner2 where reg_date=$reg_date $FILTER_PARTNER_QUERY order by id_no desc limit 1";
				$dbo->query($sql);
				$rs=$dbo->next_record();
				$code=$rs[id_no];
			}

			if(!$category1_old && $category1){
				$arr = explode("-",$category1);
				$sql = "insert into ez_nbanner2_seq (code,code1,code2,code3,seq,cp_id) values ('$code','$arr[0]','$arr[1]','$arr[2]',0,'$CP_ID')";
				$dbo->query($sql);
				$sql = "update ez_nbanner2_seq set seq=seq+1 where code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
				$dbo->query($sql);
			}
			if(!$category2_old && $category2){
				$arr = explode("-",$category2);
				$sql = "insert into ez_nbanner2_seq (code,code1,code2,code3,seq,cp_id) values ('$code','$arr[0]','$arr[1]','$arr[2]',0,'$CP_ID')";
				$dbo->query($sql);
				$sql = "update ez_nbanner2_seq set seq=seq+1 where code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
				$dbo->query($sql);
			}
			if(!$category3_old && $category3){
				$arr = explode("-",$category3);
				$sql = "insert into ez_nbanner2_seq (code,code1,code2,code3,seq,cp_id) values ('$code','$arr[0]','$arr[1]','$arr[2]',0,'$CP_ID')";
				$dbo->query($sql);
				$sql = "update ez_nbanner2_seq set seq=seq+1 where code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
				$dbo->query($sql);
			}
			if(!$category4_old && $category4){
				$arr = explode("-",$category4);
				$sql = "insert into ez_nbanner2_seq (code,code1,code2,code3,seq,cp_id) values ('$code','$arr[0]','$arr[1]','$arr[2]',0,'$CP_ID')";
				$dbo->query($sql);
				$sql = "update ez_nbanner2_seq set seq=seq+1 where code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
				$dbo->query($sql);
			}
			if(!$category5_old && $category5){
				$arr = explode("-",$category5);
				$sql = "insert into ez_nbanner2_seq (code,code1,code2,code3,seq,cp_id) values ('$code','$arr[0]','$arr[1]','$arr[2]',0,'$CP_ID')";
				$dbo->query($sql);
				$sql = "update ez_nbanner2_seq set seq=seq+1 where code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
				$dbo->query($sql);
			}
			if(!$category6_old && $category6){
				$arr = explode("-",$category6);
				$sql = "insert into ez_nbanner2_seq (code,code1,code2,code3,seq,cp_id) values ('$code','$arr[0]','$arr[1]','$arr[2]',0,'$CP_ID')";
				$dbo->query($sql);
				$sql = "update ez_nbanner2_seq set seq=seq+1 where code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
				$dbo->query($sql);
			}

            echo "
                <script>
                    alert('저장하였습니다.');
                    parent.location.href='$urls';
                </script>
            ";
		}else{
			checkVar(mysql_error(),$sql);
		}
		exit;

}elseif ($mode=="file_drop"){
		$sql = "update $table set $mode2 ='' where id_no=$id_no $FILTER_PARTNER_QUERY";
		$dbo->query($sql);
		@unlink("../../public/banner/$filename");
		redirect2("?id_no=$id_no&$sessLink");exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){
		$sql = "select *  from $table where id_no=$check[$i] $FILTER_PARTNER_QUERY";
		$dbo->query($sql);
		$rs=$dbo->next_record();
		if($rs[filename]) @unlink("../../public/banner/$rs[filename]");

		if($rs[category1]){
			$arr = explode("-",$rs[category1]);
			$sql = "update ez_nbanner2_seq set seq=seq-1 where seq > '$rs[seq]' and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
			$dbo->query($sql);
		}
		if($rs[category2]){
			$arr = explode("-",$rs[category2]);
			$sql = "update ez_nbanner2_seq set seq=seq-1 where seq > '$rs[seq]' and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
			$dbo->query($sql);
		}
		if($rs[category3]){
			$arr = explode("-",$rs[category3]);
			$sql = "update ez_nbanner2_seq set seq=seq-1 where seq > '$rs[seq]' and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
			$dbo->query($sql);
		}
		if($rs[category4]){
			$arr = explode("-",$rs[category4]);
			$sql = "update ez_nbanner2_seq set seq=seq-1 where seq > '$rs[seq]' and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
			$dbo->query($sql);
		}
		if($rs[category5]){
			$arr = explode("-",$rs[category5]);
			$sql = "update ez_nbanner2_seq set seq=seq-1 where seq > '$rs[seq]' and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
			$dbo->query($sql);
		}
		if($rs[category6]){
			$arr = explode("-",$rs[category6]);
			$sql = "update ez_nbanner2_seq set seq=seq-1 where seq > '$rs[seq]' and code1='$arr[0]' and code2='$arr[1]' and code3='$arr[2]' $FILTER_PARTNER_QUERY";
			$dbo->query($sql);
		}

		$sql = "delete from ez_nbanner2_seq where code = $check[$i] $FILTER_PARTNER_QUERY";
		$dbo->query($sql);

		$sql = "delete from $table where id_no = $check[$i] $FILTER_PARTNER_QUERY";
		$dbo->query($sql);
	}
	redirect2("list_${filecode}.php?assort=$assort");exit;

}elseif ($mode=="file_drop"){
		$sql = "update $table set filename ='' where id_no=$id_no $FILTER_PARTNER_QUERY";
		$dbo->query($sql);
		@unlink("../../public/banner/$filename");
		redirect2("?id_no=$id_no&$_SESSION[sessLink]");exit;


}elseif ($mode=="hide"){

    for($i = 0; $i < count($check);$i++){
        $sql = "update $table set bit_hide=1  where id_no = $check[$i] $FILTER_PARTNER_QUERY";
        $dbo->query($sql);
    }
    back();exit;


}else{
	$sql = "select * from $table where id_no=$id_no $FILTER_PARTNER_QUERY";
	$dbo->query($sql);
	$rs= $dbo->next_record();

	$category1 = explode("-",$rs[category1]);
	$category2 = explode("-",$rs[category2]);
	$category3 = explode("-",$rs[category3]);
	$category4 = explode("-",$rs[category4]);
	$category5 = explode("-",$rs[category5]);
	$category6 = explode("-",$rs[category6]);

}

if(!$rs[id_no] && $code1){
    $rs[category1] = "${code1}-${code2}-";
    $category1 = explode("-",$rs[category1]);
}
$code1=$category1[0];
$code2=$category1[1];

//-------------------------------------------------------------------------------
?>
<?include("../top.html");?>
<?include("../../include/tour_options.php");?>
<script language="JavaScript">
<!--
function chkForm(){
	var fm = document.fmData;
	if(check_select(fm.category_step1,'카테고리를')=='wrong'){return }
    if(check_blank(fm.text1,'text1을',0)=='wrong'){return }
	fm.submit();
}

$(function(){

	setOption(document.getElementById('category_step1'),'','<?=$category1[1]?>');
	setOption2(document.getElementById('category_step2'),'','<?=$category1[2]?>');

	<?If($category2[1]){?>
	setOption(document.getElementById('category2_step1'),'2','<?=$category2[1]?>');
	setOption2(document.getElementById('category2_step2'),'2','<?=$category2[2]?>');
	<?}?>
	<?If($category3[1]){?>
	setOption(document.getElementById('category3_step1'),'3','<?=$category3[1]?>');
	setOption2(document.getElementById('category3_step2'),'3','<?=$category3[2]?>');
	<?}?>
	<?If($category4[1]){?>
	setOption(document.getElementById('category4_step1'),'4','<?=$category4[1]?>');
	setOption2(document.getElementById('category4_step2'),'4','<?=$category4[2]?>');
	<?}?>
	<?If($category5[1]){?>
	setOption(document.getElementById('category5_step1'),'5','<?=$category5[1]?>');
	setOption2(document.getElementById('category5_step2'),'5','<?=$category5[2]?>');
	<?}?>
	<?If($category6[1]){?>
	setOption(document.getElementById('category6_step1'),'6','<?=$category6[1]?>');
	setOption2(document.getElementById('category6_step2'),'6','<?=$category6[2]?>');
	<?}?>
});
//-->
</script>


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

	<!--내용이 들어가는 곳 시작-->
    <table border="0" cellspacing="1" cellpadding="3" class="viewWidth" width="750">
		<form name="fmData"  method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="assor" value='<?=$assort?>'>
		<input type="hidden" name="category1_old" value='<?=$rs[category1]?>'>
		<input type="hidden" name="category2_old" value='<?=$rs[category2]?>'>
		<input type="hidden" name="category3_old" value='<?=$rs[category3]?>'>
		<input type="hidden" name="category4_old" value='<?=$rs[category4]?>'>
		<input type="hidden" name="category5_old" value='<?=$rs[category5]?>'>
		<input type="hidden" name="category6_old" value='<?=$rs[category6]?>'>

        <tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

	    <tr>
          <td  class="subject" width="130">* 카테고리</td>
          <td>
				<?
				$sql2 = "select * from ez_tour_category1 order by seq asc";
				$dbo2->query($sql2);
				while($rs2= $dbo2->next_record()){
					$keys .= "," . $rs2[subject];
					$vals .= "," . $rs2[id_no];
				}
				?>
			   <select name="category_step1" id="category_step1" onchange="setOption(this,'','')"><!--setOption(this,'','')-->
				 <?=option_str($keys,$vals,$category1[0])?>
			   </select>
			   <select name="category_step2" id="category_step2" onchange="setOption2(this,'','');">
			   </select>
			   <select name="category_step3" id="category_step3" class="hide">
			   </select>
			   <br/>

			   <select name="category2_step1" id="category2_step1" onchange="setOption(this,2,'')">
				 <?=option_str($keys,$vals,$category2[0])?>
			   </select>
			   <select name="category2_step2" id="category2_step2" class="ctg2" onchange="setOption2(this,2,'')">
			   </select>
			   <select name="category2_step3" id="category2_step3" class="hide">
			   </select>
			   <br>
			   <select name="category3_step1" id="category3_step1" onchange="setOption(this,3,'')">
				 <?=option_str($keys,$vals,$category3[0])?>
			   </select>
			   <select name="category3_step2" id="category3_step2" class="ctg2" onchange="setOption2(this,3,'')">
			   </select>
			   <select name="category3_step3" id="category3_step3" class="hide">
			   </select>

			   <br>
			   <select name="category4_step1" id="category4_step1" onchange="setOption(this,4,'')">
				 <?=option_str($keys,$vals,$category4[0])?>
			   </select>
			   <select name="category4_step2" id="category4_step2" class="ctg2" onchange="setOption2(this,4,'')">
			   </select>
			   <select name="category4_step3" id="category4_step3" class="hide">
			   </select>


			   <br>
			   <select name="category5_step1" id="category5_step1" onchange="setOption(this,5,'')">
				 <?=option_str($keys,$vals,$category5[0])?>
			   </select>
			   <select name="category5_step2" id="category5_step2" class="ctg2" onchange="setOption2(this,5,'')">
			   </select>
			   <select name="category5_step3" id="category5_step3" class="hide">
			   </select>

			   <br>
			   <select name="category6_step1" id="category6_step1" onchange="setOption(this,6,'')">
				 <?=option_str($keys,$vals,$category6[0])?>
			   </select>
			   <select name="category6_step2" id="category6_step2" class="ctg2" onchange="setOption2(this,6,'')">
			   </select>
			   <select name="category6_step3" id="category6_step3" class="hide">
			   </select>


          </td>
        </tr>
		<tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject" width="20%">text1</td>
          <td>
            <input class="box" type="text" name="text1" value="<?=$rs[text1]?>" size="80" maxlength="150">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">text2</td>
          <td>
            <!-- <input class="box" type="text" name="text2" value="<?=$rs[text2]?>" size="80" maxlength="150"> -->
            <textarea name="text2" class="box" rows="3" style="width:300px"><?=$rs[text2]?></textarea>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td class="subject">text3</td>
          <td>
            <input class="box" type="text" name="text3" value="<?=$rs[text3]?>" size="80" maxlength="150">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


		<tr>
          <td class="subject">URL</td>
          <td>
            <input class="box" type="text" name="url" value="<?=$rs[url]?>" size="80" maxlength="190">
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

		<tr>
          <td class="subject">열기/보이기</td>
          <td>
            <select name="target"><?=option_str("현재창,새창","_self,_blank",$rs[target])?></select>
            <select name="bit_hide"><?=option_str("보이기,감추기","0,1",$rs[bit_hide])?></select>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

	<?if($rs[filename]):
		@$fileSize = filesize("../../public/banner/${rs[filename]}");
		?>
        <tr>
          <td class="subject">이미지<br />2000 * 450</td>
          <td height="20">
            <?if(!$rs[id_no_origin]){?>
			<input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&mode2=filename&filename=<?=$rs[filename]?>'}">
            <?}?>
            <a class=soft href="../../include/download.php?file=<?=$rs[filename]?>&size=<?=$fileSize?>&dir=public/banner" onFocus="blur(this)"><?=$rs[filename]?> (<?=ceil($fileSize/1024)?>KB)</a>
			<div style="padding:15px 0 15px 0"><img src="../../public/banner/<?=$rs[filename]?>" width="500"></div>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
	<?else:?>
		<tr>
          <td  class="subject">이미지</td>
          <td height="20">
            <input class="box" type="file" name="file1" size=40> 2000 * 450
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>
	<?endif;?>

        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>
        <tr>
		  <td colspan=10>
			  <br>
			  <!-- Button Begin---------------------------------------------->
			  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
				<tr align="right">
					<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
					<td><span class="btn_pack medium bold"><a href="#" onClick="location.href='list_<?=$filecode?>.php?<?=$sessLink?>&code1=<?=$code1?>&code2=<?=$code2?>'"> 리스트 </a></span></td>
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



	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>