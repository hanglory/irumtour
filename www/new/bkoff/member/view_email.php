<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "mailling";
$MENU = "member";
$TITLE = "회원메일발송";


#### mode
if($mode=="save"){


		$reg_date = time();

		$sqlInsert="
		   insert into $table (
				subject,
				email,
				target,
				start_date,
				end_date,
				bit_proof,
				content,
				reg_date,
				targets,
				send_count
		  ) values (
				'$subject',
				'$email',
				'$target',
				'$start_date',
				'$end_date',
				'$bit_proof',
				'$content',
				'$reg_date',
				'$TARGETS',
				'$send_count'
		)";


		$sqlModify="
		   update $table set
				subject='$subject',
				email='$email',
				target='$target',
				start_date='$start_date',
				end_date='$end_date',
				bit_proof='$bit_proof',
				targets='$TARGETS',
				content='$content'
		   where id_no='$id_no'
		";

		/*
		if($id_no){
			$sql = $sqlModify;
			$url = "view_${filecode}.php?id_no=$id_no";
		}else{
			$sql = $sqlInsert;
			$url = "list_${filecode}.php";
		}
		*/
		$sql = $sqlInsert;
		$url = "list_${filecode}.php";

		//checkVar("",$sql);exit;

		if($dbo->query($sql)){

			//if(!$id_no){ //메일링 명단 만들기
				$filter =($target)? " and ($target >= '$start_date' and $target <= '$end_date 23:59:59') " : "";

				if($TARGETS){
					$TARGETS = "'".str_replace(",","','",trim($TARGETS)) . "'";
					$filter = " and id in ($TARGETS)";
				}

				$sql = "select name,email,left(reg_date,10) as reg_date,id from member where email_bit = '1' and email<>'' $filter";
				$dbo->query($sql);
				while($rs=$dbo->next_record()){
					$sql2 = "insert into mailling_tmp (name,email,mailling_id_no,proof_date,id) values ('$rs[name]','$rs[email]','$reg_date','$rs[reg_date]','$rs[id]')";
					$dbo2->query($sql2);
				}
			//}

			//msggo("저장하였습니다.",$url);
			echo "<script>alert('저장하였습니다.');parent.location.href='$url';</script>";
		}else{
			checkVar(mysql_error(),$sql);
		}
		exit;

}elseif ($mode=="drop"){

	for($i = 0; $i < count($check);$i++){

		$sql = "delete from $table where reg_date = $check[$i]";
		if($dbo->query($sql)){
			$sql2 = "delete from mailling_tmp where mailling_id_no = $check[$i]  ";
			$dbo2->query($sql2);
		}
	}
	redirect2("list_${filecode}.php");exit;



}else{
	$sql = "select *  from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();
	$TARGETS =$rs[targets];
}

if(!$TARGETS){
	for($i=0; $i<count($check);$i++){
		$id = $check[$i];
		$TARGETS .= "," . $check2[$id];
	}
	$TARGETS = substr($TARGETS,1);
}
//-------------------------------------------------------------------------------
?>
<script type="text/javascript" src="/cheditor/cheditor.js"></script>
<script language="JavaScript" src="../../include/function.js"></script>
<script language="JavaScript">
<!--
function chkForm(){

	var fm  =document.fmData;

	if(check_blank(fm.subject,'제목을',0)=='wrong'){return }
	if(check_blank(fm.email,'보내는 사람 이메일을',0)=='wrong'){return }
	oEditors.getById["content"].exec("UPDATE_IR_FIELD", []);

	var content = myeditor.outputBodyHTML();
	fm.content.value = content;

	if(confirm('메일링리스트를 생성하시겠습니까?')){
		fm.action="";
		fm.submit();
	}
}


function test(){

	var fm  =document.fmData;

	if(check_blank(fm.subject,'제목을',0)=='wrong'){return }
	if(check_blank(fm.email,'보내는 사람 이메일을',0)=='wrong'){return }
	oEditors.getById["content"].exec("UPDATE_IR_FIELD", []);

	if(document.getElementById("content").value==""){
		alert('내용을 입력하세요');
		return;
	}
	if(check_blank(fm.test_email,'테스트 메일 수신 주소를',0)=='wrong'){return }

	if(confirm('메일을 발송하시겠습니까?')){
		fm.action="test_mail_send.php";
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

      <table border="0" cellspacing="1" cellpadding="3" width="750">

		<form name="fmData" method=post enctype="multipart/form-data" target="actarea2">
		<input type="hidden" name="mode" value="save">
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="id_chk" value='0'>


		<tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>


        <tr>
          <td class="subject">제목</td>
          <td>
            <input class="box" type="text" name="subject" value="<?=$rs[subject]?>" size=80 maxlength="200">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject">보내는 사람 메일</td>
          <td>
			<input class="box" type="text" name="email" value="<?=$rs[email]?>" size=30 maxlength="50">
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

		<?if($TARGETS){?>
        <tr>
          <td class="subject">발송대상</td>
          <td>
            <textarea cols=80 rows=3 class="box" readonly name="TARGETS"><?=$TARGETS?></textarea>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>
		<?}else{?>
        <tr>
          <td class="subject">조건</td>
          <td>
            <?//=radio("전체회원,회원가입일,방문일,최종주문일",",reg_date,last_login,last_buy",$rs[target],"target")?>
            <?=radio("전체회원,회원가입일,방문일",",reg_date,last_login",$rs[target],"target")?>&nbsp;&nbsp;&nbsp;
			<input class="box dateinput" type="text" name="start_date" size="10" maxlength="10" value='<?=$rs[start_date]?>'> ~
			<input class="box dateinput" type="text" name="end_date" size="10" maxlength="10" value='<?=$rs[end_date]?>'>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>
		<?}?>

        <tr>
          <td class="subject">수신거부기능</td>
          <td>
			<?$rs[bit_proof]=($rs[bit_proof])?$rs[bit_proof]:"0"?>
            <?=radio("사용,사용하지 않음","0,1",$rs[bit_proof],'bit_proof')?>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject">내용</td>
          <td colspan="3">
		    {회원명} : 회원 이름으로 대치됩니다.

				<!-- Html Editor Begin -->
				<textarea id="contents" name="contents"><?=stripslashes($rs[content])?></textarea>
				<script type="text/javascript">
				var myeditor = new cheditor();              // 에디터 개체를 생성합니다.
				myeditor.config.editorHeight = '300px';     // 에디터 세로폭입니다.
				myeditor.config.editorWidth = '100%';        // 에디터 가로폭입니다.
				myeditor.inputForm = 'contents';             // textarea의 ID 이름입니다.
				myeditor.run();                             // 에디터를 실행합니다.
				</script>
				<!-- Html Editor End -->

          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>



		<?if($rs[id_no]){?>
        <tr><td colspan=2>&nbsp;</td></tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject">등록일</td>
          <td>
            <?=date("Y/m/d",$rs[reg_date])?>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject"></td>
          <td>
            위의 내용을 수정하신 후,
          </td>
        </tr>

		<?}?>

        <tr>
          <td class="subject"></td>
          <td>
            "<b>메일링 리스트 생성</b>" 버튼을 누르시면 위의 조건에 맞는 새로운 메일링 리스트가 생성됩니다.<br>
			메일의 발송은 "이메일 발송 리스트"에서 보내실 수 있습니다. <br>
			(스팸메일 블럭 방지를 위해 1회에 최대 500통의 메일을 보내실 수 있습니다.)
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>

        <tr>
          <td class="subject">테스트 메일 수신 주소</td>
          <td>
			<input class="box" type="text" name="test_email" value="<?=$rs[test_email]?>" size=30 maxlength="50">
			<span class="btn_pack small"><a href="#" onClick="test()"> 테스트 발송 </a></span>
          </td>
        </tr>
        <tr><td colspan=2 bgcolor='#CCCCCC'></td></tr>


        <tr><td colspan=2 bgcolor='#E1E1E1' height=3></td></tr>
        <tr><td colspan=2 bgcolor='#FFFFFF' height=10></td></tr>

        <tr>
		<td colspan=2>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="230" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
				<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 메일링 리스트 생성 </a></span></td>
				<td><span class="btn_pack medium bold"><a href="#" onClick="location.href='list_<?=$filecode?>.php?<?=$sessLink2?>'"> 리스트 </a></span></td>
		    </tr>
		  </table>
		  <!-- Button End------------------------------------------------>
		</td>
		</tr>
      </table>

	</form>

	<!--내용이 들어가는 곳 끝-->


<!-- Copyright -->
<?include_once("../bottom.html");?>
<iframe name="actarea2" style="display:none;"></iframe>