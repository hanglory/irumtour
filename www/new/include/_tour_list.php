<?

####각종 기초 정보 결정
$view_row=10;	//한 페이지에 보여줄 행 수를 결정

if(!$page){		//페이지 디폴트 정보 결정
	$page=1;
}
$start=($view_row*($page-1))+1;	//페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
$filter="";
If($code1) $code =$code1;
If($code2) $code ="${code1}-${code2}";
If($code3) $code ="${code1}-${code2}-${code3}";


If($code1 && $code2 && $code3) $filter .=" and (category1 ='$code' or category2 ='$code' or category3 ='$code' or category4 ='$code' or category5 ='$code' or category6 ='$code') ";
Else $filter .=" and (category1 like '$code%' or category2 like '$code%' or category3 like '$code%' or category4 like '$code%' or category5 like '$code%' or category6 like '$code%') ";

$sort = ($sort)? $sort : "id_no desc";

#query
$sql1 = "select * from ez_tour $filter $PROOF_FILTER";			//자료수
$sql2 = $sql1 . " order by $sort  limit  $start, $view_row";
checkVar("111",$sql2);


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


#### Link
$link = "keyword=$keyword&target=$target&sort=$sort&category1=$category1&category2=$category2&category3=$category3";
$sessLink = "page=$page&" . $link;
?>

		<div id="tour_list">
            <table width="725" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2">&nbsp;</td>
              </tr>
              <tr>
                <td width="114" style="padding-bottom:10px;"><img src="images/sub/stitle04.gif" alt="상품리스트"></td>
                <td width="611" style="padding-bottom:10px;" class="left"><span class="txt_green">[태국]</span>에 <span class="txt_red">10개</span>의 상품이 등록되어 있습니다.</td>
                </tr>
               <tr>
                <td colspan="2" class="sort"><a href="#">인기상품순</a><img src="images/sub/ic_bar.gif"><a href="#">신상품순</a><img src="images/sub/ic_bar.gif"><a href="#">높은가격순</a><img src="images/sub/ic_bar.gif"><a href="#">낮은가격순</a></td>
                </tr>
              <tr>
                <td colspan="2">&nbsp;</td>
                </tr>
             </table>



            <table width="720" border="0" cellspacing="0" cellpadding="0">
			<?
			if($page!=1){$num=$row_search-($view_row*($page-1));}
			else{$num=$row_search;}

			$dbo->query($sql2);
			while($rs=$dbo->next_record()){
			?>

			  <tr>
                <td width="214" height="113" align="center" class="ctg_photo"><a href="itemview.html"><img src="images/sub/img_dft03.gif" width="208" height="107"></a></td>
                <td width="15">&nbsp;</td>
                <td width="496"><table width="496" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td height="30" class="txt_black">치앙마이 최고명문 골프리조트 "하이랜드 골프& 스파 리조트" 투어</td>
                  </tr>
                  <tr>
                    <td>치앙마이 북동쪽으로 약 45Km 떨어진 쌈깡펭이란 곳에 위치하고 있는 하이랜드 골프장은
2006년 새롭게 개장한 신설골프장입니다. 코스의 업다운과 언듈레이션은 ...</td>
                  </tr>
                  <tr>
                    <td height="30"><img src="images/sub/ict01.gif"><span class="list_price">1,390,000원~</span><img src="images/sub/ict02.gif"><span class="list_cts">3박5일</span><img src="images/sub/ict03.gif"><span class="list_cts">7월~9월</span><img src="images/sub/ict04.gif"><span class="list_cts">대한항공</span></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td colspan="3" background="images/sub/bg_dot.gif" height="47">&nbsp;</td>
              </tr>
			<?
				$num--;
			}

			if(!$row_search){
			?>
			  <tr>
				<td class="center">검색된 상품이 없습니다.</td>
			  </tr>
              <tr>
                <td colspan="3" background="images/sub/bg_dot.gif" height="47">&nbsp;</td>
              </tr>
			<?}?>

             </table>

			<div style="padding:10px;text-align:center;">
				<!-- navigation Begin---------------------------------------------->
				<?include_once('./include/navigation.php')?>
				<?=$navi?>
				<!-- navigation End------------------------------------------------>
			</div>


			</div>