<?
include_once("../include/common_file.php");



#### Menu
$filecode = substr(SELF,5,-4);
$TITLE = "서브배너";
$MENU = "basic";
$table = "ez_nbanner2";

$category=$category_step1;
$category.="-".$category_step2;
$category.="-".$category_step3;


switch($mode){

	case "up":
		$top2 = $top-1;
		$sql1 = "update ez_nbanner2_seq set seq=seq+1 where seq=$top2 and code1='$code1' and code2='$code2' and code3='$code3' $FILTER_PARTNER_QUERY";
		$sql2 = "update ez_nbanner2_seq set seq=seq-1 where code = '$id_no' and code1='$code1' and code2='$code2' and code3='$code3' $FILTER_PARTNER_QUERY";

		$dbo->query($sql1);
		$dbo->query($sql2);

		echo "<script>history.back(-1)</script>";
		exit;
		break;

	case "down":
		$top2 = $top+1;
		$sql1 = "update ez_nbanner2_seq set seq=seq-1 where seq=$top2 and code1='$code1' and code2='$code2' and code3='$code3' $FILTER_PARTNER_QUERY";
		$sql2 = "update ez_nbanner2_seq set seq=seq+1 where code = '$id_no' and code1='$code1' and code2='$code2' and code3='$code3' $FILTER_PARTNER_QUERY";

		$dbo->query($sql1);
		$dbo->query($sql2);
		//checkVar(mysql_error(),$sql1);
		//checkVar(mysql_error(),$sql2);exit;

		echo "<script>history.back(-1)</script>";
		exit;
		break;
}




//-------------------------------------------------------------------------------
?>
<?include("../top.html");?>

<?include("../../include/tour_options.php");?>
<?include("../../include/tour_options2.php");?>
<script type="text/javascript">
<!--
<?
$category1 =explode("-",$category);
?>
$(function(){
	setOption(document.getElementById('category_step1'),'','<?=$category1[1]?>');
	setOption2(document.getElementById('category_step2'),'','<?=$category1[2]?>');
});

//-->
</script>

<script language="JavaScript">
<!--
function selectAll(){
	fm = document.fmBbs;
	for(var i = 1; i < fm.elements.length; i++){
		fm.elements[i].checked = (fm.checkAll.checked == 1)? 1 : 0;
	}
}


function hide(){
    var j = 0;
    fm = document.fmBbs;
    fm.mode.value="hide";

    for(var i = 1; i < fm.elements.length; i++){
        if(fm.elements[i].checked == 1){
            j++;
        }
    }
    if(j == 0){
        alert("감출 자료를 선택하지 않으셨습니다.");
        return;
    }
    if(confirm("선택한 자료를 감추시겠습니까?")){
        fm.action="view_<?=$filecode?>.php";
        fm.submit();
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
		alert("삭제할 자료를 선택하지 않으셨습니다.");
		return;
	}
	if(confirm("선택한 자료를 삭제하시겠습니까?")){
		fm.action="view_<?=$filecode?>.php";
		fm.submit();
	}
}
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

	<!-- Search Begin------------------------------------------------>
	<div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
	<form name=fmSearch method="get">
	<input type=hidden name='position' value="">
	<input type=hidden name='ctg1' value="<?=$ctg1?>">
	<tr>
		<td colspan="3" align="right" style="padding: 0 5px 5px 0">

		<?
		$sql3 = "select * from ez_tour_category1 order by binary seq asc";
		$dbo3->query($sql3);
		while($rs3= $dbo3->next_record()){
		$keys .= "," . $rs3[subject];
		$vals .= "," . $rs3[id_no];
		}
		?>
		<select name="category_step1" id="category_step1" onchange="setOption(this,'','');">
		<?=option_str("대분류".$keys,$vals,$category1[0])?>
		</select>
		<select name="category_step2" id="category_step2" onchange="setOption2(this,'','');">
		</select>
		<select name="category_step3" id="category_step3"  class="hide">
		</select>

		<input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
	</td>
	<tr>
	</form>
	</table>
	</div>
	<!-- Search End------------------------------------------------>


	<!--내용이 들어가는 곳 시작-->

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
       <form name=fmBbs>
       <input type=hidden name=mode value='drop'>
       <input type=hidden name=assort value="<?=$assort?>">

        <tr><td colspan="8" bgcolor='#5E90AE' height="2"></td></tr>
	    <tr align=center height=25 bgcolor="#F7F7F6">
		<td class="subject"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
		<td class="subject" width="50"><b>번호</b></td>
		<td class="subject"><b>분류</b></td>
		<td class="subject"><b>이미지</b></td>
		<td class="subject l"><b>링크</b></td>
        <td class="subject l"><b>text</b></td>
		<!-- <td class="subject"><b>복사</b></td> -->
		<td class="subject"><b></b></td>
		</tr>
		<tr><td colspan=8  bgcolor='#E1E1E1'></td></tr>

<?


####CP용 복제
if($CP_ID) include("./inc_banner_cp_copy2.php");



if($category_step1 && $category_step2){
    $sql = "
        select
            a.*,
            (
                select 
                    seq 
                from ez_nbanner2_seq 
                where 
                    cp_id='$CP_ID' 
                    and code1='$category_step1'
                    and code2='$category_step2'
                    and code=a.id_no
                limit 1
            ) as seq
        from ez_nbanner2 as a
        where
            a.cp_id='$CP_ID'
            and (
                category1='${category_step1}-${category_step2}-'
                or category2='${category_step1}-${category_step2}-'
                or category3='${category_step1}-${category_step2}-'
                or category4='${category_step1}-${category_step2}-'
                or category5='${category_step1}-${category_step2}-'
                or category6='${category_step1}-${category_step2}-'
            )

        ";
    $dbo->query($sql);
    //checkVar(mysql_error(),$sql);    
    $j=0;
    while($rs=$dbo->next_record()){
        $j++;
        if($j!=$rs[seq]){
            $sql2 = "insert into ez_nbanner2_seq (code,code1,code2,seq,cp_id) values ('$rs[id_no]','$category_step1','$category_step2',$j,'$CP_ID')";
            $dbo2->query($sql2);
            //checkVar(mysql_error(),$sql2);
        }
    }
}






$code1 = $category_step1;
$code2 = $category_step2;
$code3 = $category_step3;


####각종 기초 정보 결정
$view_row=20;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$column = "*";
$basicLink = "";


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)


#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword){
	$filter.=" and $target like '%$keyword%' ";
}

if($code1) $filter .=" and b.code1=$code1 and b.code2='$code2' and b.code3='$code3'";



#query
if($category_step1 && $category_step2){
    $sql1 = "
    		select
    			a.*,
    			b.seq as bseq
    		from $table as a left join ez_nbanner2_seq as b
    		on a.id_no=b.code
            where 
                a.cp_id='$CP_ID'
    		    $filter
    		group by a.id_no
    	";			//자료수
    $sql2 = $sql1 . " order by b.seq asc limit  $start, $view_row";
    //checkVar("",$sql2);
}else{
    $sql1 = "
            select
                a.*
            from $table as a
            where 
                cp_id='$CP_ID'
        ";          //자료수
    $sql2 = $sql1 . " order by id_no desc limit  $start, $view_row";    
}

####자료갯수
list($rows)=$dbo->query($sql1);//검색된 자료의 갯수
$row_search = $rows;



####페이지 처리

$var=ceil($row_search/$view_row);
if ($var > 1){
	$total_page=$var;
}
else{
	$total_page=1;
}



####자료가 하나도 없을 경우의 처리
if(!$row_search){
   $error[noData] = accentError("해당하는 자료가 없습니다.");
}



####검색 항목
$selectTxt = "제목,내용";
$selectValue ="title,content";



#### Link
$keyword2 = base64_encode($keyword);
$link = "keyword=$keyword&target=$target";
$sessLink = "page=$page&" . $link;

if($page!=1){$num=$row_search-($view_row*($page-1));}
else{$num=$row_search;}

$dbo->query($sql2);

$findMode = ($code1 && $row_search)?1 : 0;


//if($debug) checkVar($row_search.mysql_error(),$sql2);
while($rs=$dbo->next_record()){
    $color = ($rs[bit_hide])?"gray":"black";
?>
	    <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
	      <td height="50"><?if(!$rs[id_no_origin]){?><input type="checkbox" name="check[]" value="<?=$rs[id_no];?>"><?}?></td>
	      <td><?=$row_search-($num-1)?></td>
		  <td height="50">
			<div><?=str_replace("여행","",get_category_name($rs[category1]))?></div>
			<div><?=str_replace("여행","",get_category_name($rs[category2]))?></div>
			<div><?=str_replace("여행","",get_category_name($rs[category3]))?></div>
			<div><?=str_replace("여행","",get_category_name($rs[category4]))?></div>
			<div><?=str_replace("여행","",get_category_name($rs[category5]))?></div>
			<div><?=str_replace("여행","",get_category_name($rs[category6]))?></div>
		  </td>
	      <td>
            <a class=soft href="view_<?=$filecode?>.php?id_no=<?=$rs[id_no];?>&assort=<?=$assort?>" onFocus='blur(this)'>
                <?if(is_file("../../public/banner/$rs[filename]")){?>
                    <img src="../../public/banner/<?=$rs[filename]?>" width="300" style="margin:5px;outline:1px solid gray">
                <?}else{?>
                    이미지 없음
                <?}?>
            </a>
          </td>
	      <td align="left"><?if($rs[url]){?><a class="soft" href="<?=$rs[url]?>" onFocus='blur(this)' target="_blank"><?=$rs[url]?></a><?}?></td>
          <td class="l">
              <div style="padding:4px;border-bottom: 1px solid #eee;font-weight:bold;color:<?=$color?>"><?=$rs[text1]?></div>
              <div style="padding:4px;border-bottom: 1px solid #eee;color:<?=$color?>"><?=nl2br($rs[text2])?></div>
              <div style="padding:4px;color:<?=$color?>"><?=$rs[text3]?></div>
          </td>
		  <!-- <td><span class="btn_pack medium bold"><a href="list_nbanner2_copy.php?mode=copy&id_no=<?=$rs[id_no]?>"> 복사 </a></span></td> -->

		  <td>
			<?if($findMode){?>
			<?if($num!=1){?><a href='?mode=down&id_no=<?=$rs[id_no]?>&top=<?=$rs[bseq]?>&code1=<?=$code1?>&code2=<?=$code2?>&code3=<?=$code3?>' onfocus="blur(this)" class=soft>▼</a><?}?>
			<?if($num!=$row_search){?><a href='?mode=up&id_no=<?=$rs[id_no]?>&top=<?=$rs[bseq]?>&code1=<?=$code1?>&code2=<?=$code2?>&code3=<?=$code3?>' onfocus="blur(this)" class=soft>▲</a><?}?>
			<?}?>

			<?//=$rs[bseq]?>

		  </td>
	    </tr>
        <tr><td colspan="8" bgcolor='#CCC' height="1"></td></tr>
<?
	$num--;
}
?>
		<tr><td colspan="8" height=1><?=$error[noData]?></td></tr>
        <tr><td colspan="8"  bgcolor='#E1E1E1' height=3></td></tr>
        <tr>
		  <td colspan="8">

		  <br>
		  <!-- Button Begin---------------------------------------------->
            <table width="180" border="0" cellspacing="0" cellpadding="0" align="right">
                <tr align="right">
				  <td><span class="btn_pack medium bold"><a href="#" onClick="location.href='view_<?=$filecode?>.php?code1=<?=$code1?>&code2=<?=$code2?>'"> 등록 </a></span></td>
				  <td><span class="btn_pack medium bold"><a href="#" onClick="del()"> 삭제 </a></span></td>
                  <td><span class="btn_pack medium bold"><a href="#" onClick="hide()"> 감추기 </a></span></td>
				</tr>
			</table>
		  <!-- Button End------------------------------------------------>

		  </td>
        </tr>

        <tr>
	  <td colspan=8  align=center>
		<!-- navigation Begin---------------------------------------------->
		<?include_once('../../include/navigation.php')?>
		<?=$navi?>
		<!-- navigation End------------------------------------------------>
	  </td>
        </tr>
	</form>
	</table>





	<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>