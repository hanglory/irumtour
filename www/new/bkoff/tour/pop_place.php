<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "ez_tour_place";
$MENU = "tour";
$TITLE = "여행지관리";


#### operation

if ($mode=="save"){

		$reg_date = date("Y/m/d");

		$naming = "place_";
		if($_FILES["file1"]["size"]){
			#------------------------------------------
			$path="../../public/place";	//업로드할 파일의 경로
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

		$reg_date = date('Y/m/d');
		$reg_date2 = date('H:i:s');

		$sql = "select * from $table where tid=$tid and season='$season'";
		list($rows) = $dbo->query($sql);

		$seq =$rows+1;

		$sqlInsert="
		   insert into $table (
			  tid,
			  season,
			  subject,
			  filename,
			  content,
			  seq,
			  reg_date,
			  reg_date2
		  ) values (
			  '$tid',
			  '$season',
			  '$subject',
			  '$upfile1',
			  '$content',
			  '$seq',
			  '$reg_date',
			  '$reg_date2'
		)";


		$sqlModify="
		   update ez_tour_place set
			  $upfileQuery1
			  season = '$season',
			  subject = '$subject',
			  content = '$content'
		   where id_no='$id_no'
		";

		if($id_no){
			$sql = $sqlModify;
			$url = "pop_place.php?tid=$tid&season=$season";
		}else{
			$sql = $sqlInsert;
			$url = "pop_place.php?tid=$tid&season=$season";
		}

		if($dbo->query($sql)){
			redirect2($url);
		}else{
			checkVar(mysql_error(),$sql);
		}
		exit;

}elseif ($mode=="file_drop"){
		$sql = "update $table set $mode2 ='' where id_no=$id_no";
		$dbo->query($sql);
		@unlink("../../public/place/$filename");
		redirect2("?tid=$tid&season=$season&id_no=$id_no");exit;
}elseif ($mode=="seq_updown"){
	/*리스트 순서 변경*/
	$filter = " and season='$season' and tid=$tid";

	$sql_up = "update $table set $basic_seq=$basic_seq +1 where $basic_seq>=$seq and id_no<>$id_no $filter";
	$sql_down = "update $table set $basic_seq=$basic_seq -1 where $basic_seq<=$seq and id_no<>$id_no $filter";


	$sql = "update $table set $basic_seq=$seq where id_no=$id_no $filter" ;
	if($dbo->query($sql)){
		//checkVar(mysql_error(),$sql);
		$sql = ($c_seq > $seq)? $sql_up : $sql_down;
		$dbo->query($sql);
	}

	//checkVar("",$sql);exit;
	back();
	exit;

}elseif ($mode=="drop"){

	$sql = "select *  from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs=$dbo->next_record();
	if($rs[filename]) @unlink("../../public/place/$rs[filename]");

	$sql = "delete from $table where id_no = $id_no";
	$dbo->query($sql);

	redirect2("?tid=$tid&season=$season");exit;

}else{
	$sql = "select * from $table where id_no=$id_no";
	$dbo->query($sql);
	$rs= $dbo->next_record();

}

//default value
if(!$rs[id_no]){
	$rs[season]=$season;
}
//-------------------------------------------------------------------------------
?>
<?include("../top_min.html");?>
<script type="text/javascript" src="/cheditor/cheditor.js"></script>
<script language="JavaScript">
<!--
function chk_form(){
	var fm = document.fmData;
	if(check_blank(fm.subject,'명칭을',0)=='wrong'){return }

	var content = myeditor.outputBodyHTML();
	fm.content.value = content;

	if(document.getElementById("contents").value==""){
		alert('내용을 입력하세요');
		return;
	}
	fm.submit();
}

function drop(id_no){
	if(confirm('삭제하시겠습니까?')){
		location.href="?mode=drop&tid=<?=$tid?>&season=<?=$season?>&id_no="+id_no;
	}
}

function seq_updown(id_no,basic_seq,c_seq,seq){
	location.href="?mode=seq_updown&tid=<?=$tid?>&season=<?=$season?>&id_no="+id_no+"&basic_seq="+basic_seq+"&c_seq="+c_seq+"&seq="+seq
}

function chg_season(str){
	location.href="?tid=<?=$tid?>&season="+str
}
//-->
</script>


	<table width="95%" border="0" cellspacing="0" cellpadding="0" align="center">
	  <tr>
		<td class="title_con" style="padding-left:20px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
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


    <table border="0" cellspacing="1" cellpadding="3" width="95%" align="center">
		<form name="fmData"  method="post" enctype="multipart/form-data">
		<input type="hidden" name="mode" value='save'>
		<input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
		<input type="hidden" name="tid" value='<?=$tid?>'>

		<tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>
	    <tr>
          <td  class="subject" width="130">계절</td>
          <td>
				<?=radio("봄,여름,가을,겨울","봄,여름,가을,겨울",$rs[season],"season")?>
          </td>
        </tr>
		<tr><td colspan="2" class="tblLine"></td></tr>

        <tr>
          <td  class="subject">명칭</td>
          <td>
            <?=html_input('subject',45,45)?>
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>


	<?if($rs[filename]):
		@$fileSize = filesize("../../public/place/${rs[filename]}");
		?>
        <tr>
          <td  class="subject">사진<r/td>
          <td>
			<input type=button class=button value='파일삭제' onClick="if(confirm('파일을 삭제하시겠습니까?')){location.href='?id_no=<?=$rs[id_no]?>&mode=file_drop&tid=<?=$tid?>&season=<?=$season?>&mode2=filename&filename=<?=$rs[filename]?>'}">
            <a class=soft href="../../include/download.php?file=<?=$rs[filename]?>&orgin_file_name=<?=$rs[filename_real]?>&dir=public/place" onFocus="blur(this)"><?=$rs[filename_real]?> (<?=ceil($fileSize/1024)?>KB)</a>

          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?else:?>
		<tr>
          <td  class="subject">사진 업로드</td>
          <td>
            <input class=box type="file" name="file1" size=40>
			<font color="orange">Alert</font></b> <font color="#666666">:  128X98 고정</font>
          </td>
        </tr>
        <tr><td colspan="2" class='bar'></td></tr>
	<?endif;?>


        <tr>
          <td  class="subject">관광지설정</td>
          <td>
				<!-- Html Editor Begin -->
				<textarea id="contents" name="content"><?=$rs[content]?></textarea>
				<script type="text/javascript">
				var myeditor = new cheditor();              // 에디터 개체를 생성합니다.
				myeditor.config.editorHeight = '100px';     // 에디터 세로폭입니다.
				myeditor.config.editorWidth = '100%';        // 에디터 가로폭입니다.
				myeditor.inputForm = 'contents';             // textarea의 ID 이름입니다.
				myeditor.run();                             // 에디터를 실행합니다.
				</script>
				<!-- Html Editor End -->
          </td>
        </tr>
        <tr><td colspan="2" class="tblLine"></td></tr>

	  <td colspan=10>
		  <br>
		  <!-- Button Begin---------------------------------------------->
		  <table border="0" width="200" cellspacing="0" cellpadding="0" align="right">
		    <tr align="right">
				<td><span class="btn_pack medium bold"><a href="javascript:chk_form()"> 저장 </a></span></td>
				<td><span class="btn_pack medium bold"><a href="pop_place.php?tid=<?=$tid?>&season=<?=$season?>"> 취소 </a></span></td>
				<td><span class="btn_pack medium bold"><a href="javascript:self.close()"> 창닫기 </a></span></td>
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


	    <table border="0" cellspacing="0" cellpadding="3" width="95%" id="tbl_list" align="center">
        <tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>
	    <tr align=center height=25 bgcolor="#F7F7F6"'>
		<td class="subject">

			<select name="season" onchange="chg_season(this.value)">
				<?=option_str("봄,여름,가을,겨울","봄,여름,가을,겨울",$season)?>
			</select>

		</td>
		<td class="subject"><b>이미지</b></td>
		<td class="subject l" align="left" width="40%"><b>내용</b></td>
		<td class="subject" width="100"><b>삭제</b></td>
		<td class="subject"><b>순서</b></td>
		</tr>
		<tr><td colspan="8" class='bar'></td></tr>
<?
$sql = "select * from $table where tid='$tid' and season='$season' order by seq asc";
list($row_search)=$dbo->query($sql);
$i=1;
while($rs=$dbo->next_record()){
		$filename = "../../public/place/".$rs[filename];
		$thumb = "../../public/thumb/tb_".$rs[filename];

		$sql2 = "update $table set seq=$i where id_no=$rs[id_no]";
		$dbo2->query($sql2);
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td><?=$rs[season]?></td>
		  <td height="110"><a class=soft href="?tid=<?=$tid?>&season=<?=$season?>&id_no=<?=$rs[id_no];?>" onFocus='blur(this)'><img src="../../public/place/<?=thumbnail($filename, 128, 98, 0, 1, 100, 0, "", "", $thumb)?>" border="0"></a></td>
	      <td align="left"><?=titleCut(strip_tags($rs[content]),200)?></td>
	      <td>
				<span class="btn_pack small bold"><a href="?tid=<?=$tid?>&season=<?=$season?>&id_no=<?=$rs[id_no];?>"> 수정 </a></span>	<br>
				<span class="btn_pack small bold"><a href="#" onClick="drop('<?=$rs[id_no]?>')"> 삭제 </a></span>
		  </td>
	      <td>
			<select name="seq_move" onchange="seq_updown('<?=$rs[id_no]?>','seq','<?=$rs[seq]?>',this.value)">
				<?=option_int(1,$row_search,1,$i)?>
			</select>
		  </td>
        <tr><td colspan="8" class='bar'></td></tr>
<?
	$num--;
	$i++;
}
?>
		<tr><td colspan=9 height=1><?=$error[noData]?></td></tr>
        <tr><td colspan=9  bgcolor='#E1E1E1' height=3></td></tr>

	</table>

	<!--내용이 들어가는 곳 끝-->

</body>
</html>