<?
include_once("../include/common_file.php");



####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "bbs";
$TITLE = "게시판 등록 관리";
$path_c= explode("/",$_SERVER["SCRIPT_FILENAME"]);
$www = ($path_c[count($path_c)-4]!="www")? $path_c[count($path_c)-4]."/": "";


#### DB Connent
include_once ("../../include/info_dbconn.php");
include_once ("../../lib/class.$database.php");

$dbo = new MiniDB($info);
$dbo2 = new MiniDB($info);

$table = "ez_bbs_info";

#### mode

switch($mode){
	case "save":

		$reg_date = date('Y/m/d');
		$reg_date2 = date('H:i:s');


		#### operation
		$filename= "../../public/inc/bbs_".$bid."_top.inc";
		$fp=fopen($filename, "w");	//파일 쓰기모드로 열기, 파일이 있다면 overwirte
		if(!fwrite($fp,stripslashes($top_code))){
			$top_code="";
		}
		fclose($fp);


		#### operation
		$filename= "../../public/inc/bbs_".$bid."_bottom.inc";
		$fp=fopen($filename, "w");	//파일 쓰기모드로 열기, 파일이 있다면 overwirte
		if(!fwrite($fp,stripslashes($bottom_code))){
			$bottom_code="";
		}
		fclose($fp);

		if($assort==3){ //faq
			$file="F";
			$memo="F";
			$secret="F";
		}
		elseif($assort==4){ //image+board
			$secret="F";
		}

		$sqlInsert="
		   insert into ez_bbs_info (
			  bid,
			  subject,
			  assort,
			  power_read,
			  power_write,
			  power_comment,
			  power_reply,
			  mb,
			  file,
			  memo,
			  secret,
			  top_code,
			  bottom_code,
			  reg_date,
			  reg_date2
		  ) values (
			  '$bid',
			  '$subject',
			  '$assort',
			  '$power_read',
			  '$power_write',
			  '$power_comment',
			  '$power_reply',
			  '$mb',
			  '$file',
			  '$memo',
			  '$secret',
			  '$top_code',
			  '$bottom_code',
			  '$reg_date',
			  '$reg_date2'
		)";


		$sqlModify="
		   update ez_bbs_info set
			  bid = '$bid',
			  subject = '$subject',
			  assort = '$assort',
			  power_read = '$power_read',
			  power_write = '$power_write',
			  power_comment = '$power_comment',
			  power_reply = '$power_reply',
			  mb = '$mb',
			  file = '$file',
			  memo = '$memo',
			  secret = '$secret',
			  top_code = '$top_code',
			  bottom_code = '$bottom_code'
		   where id_no='$id_no'
		";

		$sql = ($id_no)? $sqlModify : $sqlInsert;
		$goUrl = ($id_no)? "reg_bbs.php?id_no=$id_no" : "list_bbs_info.php";

		$dbo->query($sql);

		if(mysql_error()){checkVar("",$sql);checkVar("Message",mysql_error());exit;}
		redirect2($goUrl);
		break;


	case "drop":
		for($i = 0; $i < count($check);$i++){
			$sql = "delete from $table where id_no = $check[$i]";
			$dbo->query($sql);
		}
		redirect2("list_bbs_info.php"); exit;

	case "bid":
		$bid = trim($bid);
		$sql = "select * from $table where bid='$bid'";
		list($rows) = $dbo->query($sql);

		if($rows){
			echo "
				<script>
				parent.document.getElementById('bid').value='';
				parent.document.getElementById('bid').focus();
				alert('이미 사용중인 아이디 입니다.');
				</script>
			";
		}
		exit;
		break;


	default:
		$sql = "select * from $table where id_no = $id_no";
		$dbo->query($sql);
		$rs = $dbo->next_record();
}

$power_read = ($rs[power_read])? $rs[power_read] : "0";
$power_write = ($rs[power_write])? $rs[power_write] : "0";
$power_comment = ($rs[power_comment])? $rs[power_comment] : "0";
$power_reply = ($rs[power_reply])? $rs[power_reply] : "0";
//-------------------------------------------------------------------------------
?>
<?include("../top.html");?>

<script language="JavaScript">
<!--
function chkForm(){
	var fm = document.fmData;
	if(check_blank(fm.subject,'게시판이름을',0)=='wrong'){return}
	fm.submit();
}

function bbs_assort(div){
	document.getElementById('file_a').style.display="inline";document.getElementById('file_na').style.display="none";
	document.getElementById('memo_a').style.display="inline";document.getElementById('memo_na').style.display="none";
	document.getElementById('secret_a').style.display="inline";document.getElementById('secret_na').style.display="none";

	if(div==3){//faq
		document.getElementById('file_a').style.display="none";document.getElementById('file_na').style.display="inline";
		document.getElementById('memo_a').style.display="none";document.getElementById('memo_na').style.display="inline";
		document.getElementById('secret_a').style.display="none";document.getElementById('secret_na').style.display="inline";
	}else if(div==4){//image+board
		document.getElementById('secret_a').style.display="none";document.getElementById('secret_na').style.display="inline";
	}


}
//-->
</script>


		<table width="100%" border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
			</tr>
			<tr>
				<td colspan="3"> </td>
			</tr>
			<tr>
				<td background="../images/common/bg_title.gif" height="5"></td>
			</tr>
		</table>


		<br>

	<!--내용이 들어가는 곳 시작-->
      <table border="0" cellspacing="1" cellpadding="3" width="750">

		<form name="fmData" onSubmit="return chkForm(document.fmData)" method=post enctype="multipart/form-data">
		<input type=hidden name='mode' value='save'>
		<input type=hidden name=id_no value='<?=$rs[id_no]?>'>

        <tr><td colspan=2 bgcolor='#408080'></td></tr>


		<?if($rs[id_no]):?>
        <tr>
          <td class="subject">게시판 아이디</td>
          <td>
            <font  color="gray"><b><?=$rs[bid]?></b></font>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			Link : <a href='../../board.php?bid=<?=$rs[bid]?>' target="_blank" class=soft>http://<?=$HTTP_HOST ?>/<?=$www?>board.php?bid=<?=$rs[bid]?></a>
			<input type="hidden" name="bid" id="bid" value="<?=$rs[bid]?>">
          </td>
        </tr>
		<?else:?>
        <tr>
          <td class="subject">게시판 아이디</td>
          <td>
            <input class=box type="text" name="bid" id="bid" size=20 maxlength=20 onchange="actarea.location.href='?mode=bid&bid='+this.value">
          </td>
        </tr>
		<?endif;?>

        <tr><td colspan="2" class='bar'></td></tr>
		<tr>
          <td class="subject">게시판이름</td>
          <td>
            <input class=box type="text" name="subject" value="<?=$rs[subject]?>" size=50 maxlength=40>
			예) 자유게시판
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td class="subject">게시판종류</td>
          <td>
            <select class="select" name="assort" onchange="bbs_assort(this.value)">
				<?=option_str('일반게시판,이미지게시판,자주묻는질문형식,이미지+일반게시판','1,2,3,4',$rs[assort]);?>
			</select>
			<font color="gray">내용을 열람할 수 있는 권한</font>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>


        <tr>
          <td class="subject">권한 - Read</td>
          <td>
            <select class=select name=power_read>
				<?=option_str(MEMBERGROUP,MEMBERGROUP_ID,$power_read);?>
			</select>
			<font color="gray">내용을 열람할 수 있는 권한</font>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td class="subject">권한 - Write</td>
          <td>
            <select class=select name=power_write>
				<?=option_str(MEMBERGROUP,MEMBERGROUP_ID,$power_write);?>
			</select>
			<font color="gray">글을 등록할 수 있는 권한</font>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td class="subject">권한 - Reply</td>
          <td>
            <select class=select name=power_comment>
				<?=option_str(MEMBERGROUP,MEMBERGROUP_ID,$power_comment);?>
			</select>
			<font color="gray">코멘트를 등록할 수 있는 권한</font>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>



        <tr>
          <td class="subject">업로드 용량 제한</td>
          <td>

			<?$allowSize = intval(substr(ini_get(upload_max_filesize),0,-1));?>
			<?$j = ($rs[mb])?$rs[mb]:ceil($allowSize/2);?>
            <select class=select name=mb>
				<?=option_int(1,$allowSize,1,$j);?>
			</select>
			MB
			<?//=$allowSize = intval(substr(ini_get(upload_max_filesize),0,-1)) * 1024 * 1024;?>
			[<font color="gray">현재 시스템의 최대 업로드 용량은 <?=$allowSize?>MB입니다</font>]
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

		<?$file=($rs[file])? $rs[file] : "T";?>
        <tr>
          <td class="subject">파일첨부기능</td>
          <td>
            	<span id="file_a"><?=radio("사용함,사용하지 않음","T,F",$file,'file');?></span> <span id="file_na" style="display:none">사용할 수 없습니다.</span>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

		<?$memo=($rs[memo])? $rs[memo] : "T";?>
        <tr>
          <td class="subject">코멘트기능</td>
          <td>
            	<span id="memo_a"><?=radio("사용함,사용하지 않음","T,F",$memo,'memo');?></span> <span id="memo_na" style="display:none">사용할 수 없습니다.</span>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

		<?
		$secret=($rs[secret])? $rs[secret] : "F";?>
        <tr>
          <td class="subject">비밀글 기능</td>
          <td>
            	<span id="secret_a"><?=radio("사용함,사용하지 않음","T,F",$secret,'secret');?></span> <span id="secret_na" style="display:none">사용할 수 없습니다.</span>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>


        <tr>
          <td class="subject">게시판 상단에 <br>삽입할 코드(HTML)</td>
          <td>
            	<textarea name=top_code class=box cols=90 rows=9><?=$rs[top_code]?></textarea>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr>
          <td class="subject">게시판 하단에 <br>삽입할 코드(HTML)</td>
          <td>
            	<textarea name=bottom_code class=box cols=90 rows=9><?=$rs[bottom_code]?></textarea>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>

        <tr><td colspan=2 bgcolor='#408080'></td></tr>

        <tr>
		<td colspan=2>

		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="140" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
				<td><span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span></td>
				<td><span class="btn_pack medium bold"><a href="#" onClick="location.href='list_bbs_info.php?<?=$sessLink?>'"> 리스트 </a></span></td>
		    </tr>
		  </table>
		  <!-- Button End------------------------------------------------>

		</td>
	</tr>
	</form>
      </table>


	<!--내용이 들어가는 곳 끝-->


<script type="text/javascript">
<!--
bbs_assort('<?=$rs[assort]?>');
//-->
</script>

<!-- Copyright -->
<?include_once("../bottom.html");?>
