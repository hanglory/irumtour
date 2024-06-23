<?php
include_once("../include/common_file.php");


chk_power($_SESSION["sessLogin"]["proof"],"견적서관리대장");



####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_estimate";
$MENU = "cmp_basic";
$LEFT_HIDDEN="1";
$TITLE = "골프투어문의 통계";

####각종 기초 정보 결정
$view_row=15;   //한 페이지에 보여줄 행 수를 결정

if(!$page) $page=1;   //페이지 디폴트 정보 결정

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
if(!$date_e) $date_e= date("Y/m/d",strtotime(date("Y/m/d")));
$date_one_year_ago = date('Y/m/d', strtotime('-1 year', strtotime($date_e)));

$filter.= "WHERE reg_date >= '$date_one_year_ago' AND
                 reg_date <= '$date_e'";
// $year = ($year)? $year : date("Y");

if($bit_worked) $filter.=" and bit_worked=1";

if(strstr("partner_i,partner_g",$_SESSION["sessLogin"]["staff_type"])) $filter.=" and (a.main_staff like '%($user_id)' or a.bit_read_all=1)";

$FILTER_PARTNER_QUERY=str_replace("and cp_id","and a.cp_id",$FILTER_PARTNER_QUERY);
$FILTER_PARTNER_QUERY=str_replace("and main_staff","and a.main_staff",$FILTER_PARTNER_QUERY);


#query
#국가별 sorting 쿼리
$nation_sql = "
    SELECT DISTINCT nation
        FROM ez_request
        WHERE id_no > 0
        ORDER BY 
            CASE nation
                WHEN '동남아' THEN 1
                WHEN '태국' THEN 2
                WHEN '필리핀' THEN 3
                WHEN '베트남' THEN 4
                WHEN '일본' THEN 5
                WHEN '중국' THEN 6
                WHEN '미주' THEN 7
                WHEN '기타해외' THEN 8
                WHEN '한국' THEN 9
                ELSE 10
            END";

#국가 쿼리
$sql_2 = "
    SELECT
    nation,
    substr(reg_date, 1, 7) AS sd,
    COUNT(*) AS count
    FROM
        ez_request
        $filter
    GROUP BY
        nation,
        sd
    ORDER BY
        sd DESC,
        CASE nation
            WHEN '동남아' THEN 1
            WHEN '태국' THEN 2
            WHEN '필리핀' THEN 3
            WHEN '베트남' THEN 4
            WHEN '일본' THEN 5
            WHEN '중국' THEN 6
            WHEN '미주' THEN 7
            WHEN '기타해외' THEN 8
            WHEN '한국' THEN 9
            ELSE 10
        END;";

#변수 정의
$dbo->query($nation_sql);
if($debug) checkVar(mysql_error(),$nation_sql);
$nation_arr = array();
while($rs=$dbo->next_record()){
    $nation_arr[] = $rs[nation];
    //echo "$nation_arr";
}

$nation_cnt = count($nation_arr);
$month_tot = 0;
$nation_tot = array(0,0,0,0,0,0,0,0);
$every_tot = 0;
?>
<?php include("../top.html");?>
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
            <th class="subject">나라</th>
            <?php
            for ($i = 0; $nation_cnt > $i; $i++)
            {
                echo "<th class='subject'>$nation_arr[$i]</th>\n";
            }
            ?>
            <th class="subject">합계</th>
        </tr>


            <!-- /tr -->
        <?php
        $dbo->query($sql_2);
        if($debug) checkVar(mysql_error(),$sql_2);

        $_1 = "";
        $i = $nation_cnt;
        while ($rs = $dbo->next_record()){
            if ($rs[sd] == $_1)
            { //월이 변경되지 않았을때
                for ($j = 0; $nation_cnt > $j; $j++) {
                    if ($nation_arr[$i] == $rs[nation]) { ?>
                        <td> <?= $rs[count] ?></td>

                        <?php
                        $nation_tot[$i] += $rs[count];
                        $month_tot += $rs[count];
                        $i++;
                        break;
                    } else {
                        echo "<td>0</td>\n";
                    }
                    $i++;
                }
                if($rs[nation] == "한국"){
                    echo "<td>$month_tot</td>";
                    $every_tot += $month_tot;
                    $month_tot = 0;
                }
            } else {    //월이 변경 되면

                if ($nation_cnt > $i) {   // 월은 변경됐는데 아직 나라가 남아 있으면<td>값을 채운다
                    for (; $nation_cnt > $i; $i++) {
                        echo "<td>0</td>\n";
                    }
                    echo "<td>$month_tot</td>\n";
                    $every_tot += $month_tot;
                    $month_tot = 0;
                    echo "</tr>";
                }
                if ($nation_cnt == $i) $i = 0;
?>

        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'"
            onMouseOut="this.style.backgroundColor='#FFFFFF'">
            <th class='subject' height="35"><?= $rs[sd] ?>월</th>
<?php
                for ($j = 0; $nation_cnt > $j; $j++) {
                    if ($nation_arr[$i] == $rs[nation]) {
?>
                        <td> <?= $rs[count] ?></td>
                        <?
                        $nation_tot[$i] += $rs[count];
                        $month_tot += $rs[count];
                        $i++;
                        break;
                    } else {
                        echo "<td>0</td>\n";
                    }
                    $i++;
                }//for
                if($rs[nation] == "한국"){
                    echo "<td>$month_tot</td>";
                    $every_tot += $month_tot;
                    $month_tot = 0;
                }
            }//else(month change)
            $_1 = $rs[sd];
        }//while
            for( ; $nation_cnt > $i; $i++){ //종료 했는데 아직 나라를 다 안채웠으면 나라끝날때까지 채움
                echo "<td>0</td>\n";
            }
            ?>
        </tr>
        <tr>
            <th class='subject' height="35">전체</th>
            <?php
            for ($i = 0; $nation_cnt > $i; $i++){
                echo "<th class='subject'>$nation_tot[$i]</th>";
            }//for
            echo "<th class='subject'>$every_tot</th>";
            ?>
        </tr>
    </table>

    <!--내용이 들어가는 곳 끝-->

    <!-- Copyright -->
<?php include_once("../bottom.html");?>