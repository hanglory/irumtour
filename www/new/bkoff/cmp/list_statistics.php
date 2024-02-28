<?
include_once("../include/common_file.php");


chk_power($_SESSION["sessLogin"]["proof"],"견적서관리대장");



####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_estimate";
$MENU = "cmp_basic";
$LEFT_HIDDEN="1";
$TITLE = "일정표 통계";

####각종 기초 정보 결정
$view_row=15;   //한 페이지에 보여줄 행 수를 결정

if(!$page){     //페이지 디폴트 정보 결정
    $page=1;
}
$start=($view_row*($page-1))+1; //페이지에 따라 처음 불러올 row의 포인터를 결정
$start=$start-1;



#### 기본 정보
$column = "*";
$basicLink = "";

/*
$sql2 = "select * from cmp_staff where id='$user_id'";
$dbo2->query($sql2);
$rs2=$dbo2->next_record();
$cp_url = $rs2[cp_url];
*/


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)

#검색조건
$keyword = ($keyword2)? base64_decode($keyword2) : $keyword;
if($keyword){
    $filter .=" and $target like '%$keyword%' ";
    $best="";    //배너 select 초기화
    $findMode=1;
}

$dtype = ($dtype)? $dtype : "send_date";

if($year) {
    $filter .= " AND substr(a.$dtype,1,4) = $year ";
}
if(!$date_s) $date_s= date("Y/m/d",strtotime(date("Y/m/d")." -11 month")); //date("Y"); //"2015/06/01";
if(!$date_e) $date_e= date("Y/m/d",strtotime(date("Y/m/d")." +1 month"));
$filter.=" AND a.$dtype >='$date_s'";
$filter.=" AND a.$dtype <='$date_e'";
// $year = ($year)? $year : date("Y");



if($bit_worked) $filter.=" and bit_worked=1";


if(strstr("partner_i,partner_g",$_SESSION["sessLogin"]["staff_type"])) $filter.=" and (a.main_staff like '%($user_id)' or a.bit_read_all=1)";

$FILTER_PARTNER_QUERY=str_replace("and cp_id","and a.cp_id",$FILTER_PARTNER_QUERY);
$FILTER_PARTNER_QUERY=str_replace("and main_staff","and a.main_staff",$FILTER_PARTNER_QUERY);
#query
$sql_nation = "
        SELECT distinct nation 
            FROM cmp_estimate a 
            LEFT JOIN cmp_golf b  ON a.golf_id_no=b.id_no
            WHERE  a.id_no>0
            AND a.cp_id=''
            $filter
            ORDER BY nation ";

$sql_1 = "
    SELECT AA.view_path, AA.sd, AA.tot_cnt, BB.rev_cnt
    FROM(
        SELECT  view_path, substr(send_date,1,7) AS sd, COUNT(*) AS tot_cnt
        FROM cmp_estimate a
        WHERE a.id_no>0
        AND a.cp_id=''
        AND (a.view_path LIKE '%신규%' OR a.view_path LIKE '%재방문%' OR a.view_path LIKE '%투어문의%' )
        $filter
        GROUP BY 2,1
    ) AA LEFT JOIN (
        SELECT  a.view_path AS vpath, substr(a.send_date,1,7) AS sd, COUNT(c.origin_id_no) rev_cnt
        FROM cmp_estimate as a 
        LEFT JOIN cmp_reservation AS c on a.id_no = c.origin_id_no
        LEFT JOIN cmp_golf b on b.id_no = a.golf_id_no
        WHERE  a.id_no>0
        AND a.cp_id=''
        AND c.origin_id_no<>''
        AND (a.view_path LIKE '%신규%' OR a.view_path LIKE '%재방문%' OR a.view_path LIKE '%투어문의%' )
        $filter
        group BY 2, 1
    )BB  ON AA.view_path=BB.vpath AND AA.sd = BB.sd";

$sql_2 = "
    SELECT AA.nation, AA.sd, AA.tot_cnt, ifnull(BB.rev_cnt,0) AS rev_cnt
    FROM(
        SELECT  nation, substr(send_date,1,7) AS sd, COUNT(*) AS tot_cnt
        FROM cmp_estimate a 
        LEFT JOIN cmp_golf b  ON a.golf_id_no=b.id_no
        WHERE  a.id_no>0
        AND a.cp_id=''
        $filter
        GROUP BY 2,1
    ) AA  LEFT JOIN (
        SELECT
        nation, substr(a.send_date,1,7) AS sd, COUNT(c.origin_id_no) AS rev_cnt
        FROM cmp_estimate as a 
        LEFT JOIN cmp_reservation AS c on a.id_no = c.origin_id_no
        LEFT JOIN cmp_golf b on b.id_no = a.golf_id_no
        WHERE   a.id_no>0
        AND a.cp_id=''
        AND c.origin_id_no<>''
        $filter
        GROUP BY  2,1
    )BB  ON AA.nation=BB.nation AND AA.sd = BB.sd";

$nation_arr = array();
$nation_tot = array();
$nation_rev = array();
$nation_cnt = 0;
$dbo->query($sql_nation);
if($debug) checkVar(mysql_error(),$sql_2);
while($rs=$dbo->next_record())
{
    array_push($nation_arr, $rs[nation]);
    array_push($nation_tot, 0);
    array_push($nation_rev, 0);
    $nation_cnt++;
}

$path_arr = array("신규","재방문","투어문의");
$path_cnt = count($path_arr);
$path_tot = array(0,0,0);
$path_rev = array(0,0,0);
?>
<?include("../top.html");?>
    <script language="JavaScript">
        <!--
        function selectAll(){
            fm = document.fmData;
            for(var i = 1; i < fm.elements.length; i++){
                fm.elements[i].checked = (fm.checkAll.checked == 1)? 1 : 0;
            }
        }

        function del(){
            var j = 0;
            fm = document.fmData;

            for(var i = 1; i < fm.elements.length; i++){
                if(fm.elements[i].checked == 1){
                    j++;
                }
            }
            if(j == 0){
                alert("삭제할 상품을 선택하지 않으셨습니다.");
                return;
            }
            if(confirm("선택한 상품을 삭제하시겠습니까?")){
                fm.action="view_<?=$filecode?>.php";
                fm.mode.value="drop";
                fm.submit();
            }
        }

        function copy(id_no){
            actarea.location.href="list_<?=$filecode?>_copy.php?id_no="+id_no;
        }

        function copy_to_clip(val) {
            var t = document.createElement("textarea");
            document.body.appendChild(t);
            t.value = val;
            t.select();
            document.execCommand('copy');
            document.body.removeChild(t);
            alert("복사되었습니다.");
        }
        //-->
    </script>


    <!--상단 제목(일정표 통계)-->
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?>

            </td>
        </tr>
        <tr>
            <td> </td>
        </tr>
        <tr>
            <td background="../images/common/bg_title.gif" height="5"></td>
        </tr>
    </table>


    <!--내용이 들어가는 곳 시작-->

    <!-- Search Begin------------------------------------------------>
    <div style="padding:0 0 5px 0">
        <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
            <form name="fmSearch" method="get">
                <input type="hidden" name='position' value="">
                <input type="hidden" name='ctg1' value="<?=$ctg1?>">


                <tr height=22>
                    <td valign='bottom' align=right>
                        <input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?=$date_s?>" class="box c dateinput">
                        ~
                        <input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?=$date_e?>" class="box c dateinput">
                        <!--
                        <select name="year" class="select">
                            <?= option_int2(date("Y"), 2015, 1)?>
                        </select>
                         -->
                        <input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
                    </td>
                <tr>
            </form>
        </table>
    </div>
    <!-- Search End------------------------------------------------>
    <!-- Table Begin------------------------------------------------>

    <!-- 국가 분석 테이블-->
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="subject" rowspan="2">나라</th>
            <?php
            for ($i = 0; $nation_cnt > $i; $i++)
            {
                echo "<th class='subject' colspan='2'>$nation_arr[$i]</th>\n";
            }
            ?>
        </tr>
        <tr align=center height=25 bgcolor="#F7F7F6">
<?php
            for ($i = 0; $nation_cnt > $i; $i++)
            {
                echo "<th class='subject'>예약건수</th><th class='subject'>예약율(%)</th>\n";
            }
?>
        <!-- /tr -->
            <?
            $dbo->query($sql_2);
            if($debug) checkVar(mysql_error(),$sql_2);

            $_1 = "1";
            $i = $nation_cnt;
            while($rs=$dbo->next_record())
            {
                if($rs[sd] == $_1)  //월이 같으면
                {
                    for($j=0; $nation_cnt > $j ; $j++)
                    {
                        if($nation_arr[$i] == $rs[nation]){
            ?>
                            <td> <?=$rs[rev_cnt]?>/<?=$rs[tot_cnt]?></td> <td> <?=@round($rs[rev_cnt]/$rs[tot_cnt]*100,1)?> %</td>
            <?
                            $nation_tot[$i]+=$rs[tot_cnt];
                            $nation_rev[$i]+=$rs[rev_cnt];
                            $i++;
                            break;
                        } else {
                            echo "<td> </td><td> </td>\n";
                        }
                        $i++;
                    }
                } else {    //월이 변경 되면
                    if($nation_cnt > $i ){   // 월은 변경됐는데 아직 나라가 남아 있으면<td>값을 채운다
                        for( ; $nation_cnt > $i; $i++){
                            echo "<td> </td> <td> </td>\n";
                        }
                    }
                    if($nation_cnt == $i)   $i=0;
            ?>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
            <th class='subject' height="35"><?=$rs[sd]?>월</th>
            <?
                    for($j=0; $nation_cnt > $j ; $j++)
                    {
                        if($nation_arr[$i] == $rs[nation])
                        {
            ?>
                            <td> <?=$rs[rev_cnt]?>/<?=$rs[tot_cnt]?></td><td> <?=@round($rs[rev_cnt]/$rs[tot_cnt]*100,1)?> %</td>
            <?
                            $nation_tot[$i]+=$rs[tot_cnt];
                            $nation_rev[$i]+=$rs[rev_cnt];
                            $i++;
                            break;
                        } else {
                            echo "<td> </td> <td> </td>\n";
                        }
                        $i++;
                    }
                }   // 월 체크 종료
                $_1 = $rs[sd];
            }
            for( ; $nation_cnt > $i; $i++){ //종료 했는데 아직 나라를 다 안채웠으면 나라끝날때까지 채움
                echo "<td> </td> <td> </td>\n";
            }            
            ?>
        </tr>
        <tr>
            <th class='subject' height="35">전체</th>
<?php
            for ($i = 0; $nation_cnt > $i; $i++)
            {
                echo "<th class='subject'>$nation_rev[$i]</th><th class='subject'>".round($nation_rev[$i]/$nation_tot[$i]*100,1)."%</th>\n";
            }
?>
        </tr>
    </table>
    <!--경로 분석 테이블1-->
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

        <tr align=center height=25 bgcolor="#F7F7F6">
            <th class="subject" rowspan="2">경로</th>
<?php
            for ($i = 0; $path_cnt > $i; $i++)
            {
                echo "<th class='subject' colspan='2'>$path_arr[$i]</th>\n";
            }
?>
        </tr>
        <tr align=center height=25 bgcolor="#F7F7F6">
<?php
            for ($i = 0; $path_cnt > $i; $i++)
            {
                echo "<th class='subject'>예약건수</th><th class='subject'>예약율(%)</th>\n";
            }
?>
<?
            $dbo->query($sql_1);
            if($debug) checkVar(mysql_error(),$sql_1);

            $_1 = "";
            $i = 0;
            while($rs=$dbo->next_record()){

            if($rs[sd] == $_1){
                for($j=0; $path_cnt > $j ; $j++)
                {
                    if($path_arr[$i] == $rs[view_path]){
?>
                        <td> <?=$rs[rev_cnt]?></td> <td> <?=@round($rs[rev_cnt]/$rs[tot_cnt]*100,1)?> %</td>
<?
                    $path_tot[$i] += $rs[tot_cnt];
                    $path_rev[$i] += $rs[rev_cnt];
                    $i++;
                    break;
                    } else{
                    echo "<td> </td><td> </td>\n";
                    }
                $i++;
                }
            } else{ //월이 변경되면
            if($path_cnt == $i) $i =0;

?>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
            <th class='subject' height="35"><?=$rs[sd]?>월</th>
<?
                for($j=1; $path_cnt>$j;$j++)
                {
                    if($path_arr[$i] == $rs[view_path])
                    {
?>
                        <td> <?=$rs[rev_cnt]?></td><td> <?=@round($rs[rev_cnt]/$rs[tot_cnt]*100,1)?> %</td>
<?                      $path_tot[$i]+=$rs[tot_cnt];
                        $path_rev[$i]+=$rs[rev_cnt];
                        $i++;
                        break;
                    }else
                    {
                        echo "<td> </td><td> </td>\n";
                    }
                $i++;
                }
            }

            $_1 = $rs[sd];
            }
            ?>
        </tr>
        <tr>
            <th class='subject' height="35">전체</th>
            <?php
            for ($i = 0; $path_cnt > $i; $i++)
            {
                echo "<th class='subject'>$path_rev[$i]</th><th class='subject'>".round($path_rev[$i]/$path_tot[$i]*100,1)."%</th>\n";
            }
            ?>
        </tr>
    </table>
    <!--내용이 들어가는 곳 끝-->

    <!-- Copyright -->
<?include_once("../bottom.html");?>