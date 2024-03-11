<?
include_once("../include/common_file.php");

####기초 정보
$filecode = substr(SELF, 5, -4);
$table = "";
$MENU = "cmp_paper";
$TITLE = "고객정보 프로파일링";
$LEFT_HIDDEN = "1";


####각종 기초 정보 결정
$view_row = 20;   //한 페이지에 보여줄 행 수를 결정

if (!$page) {     //페이지 디폴트 정보 결정
    $page = 1;
}
$start = ($view_row * ($page - 1)) + 1; //페이지에 따라 처음 불러올 row의 포인터를 결정
$start = $start - 1;


#### 기본 정보
$column = "*";
$basicLink = "";


####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
#검색조건
if ($num_s || $num_e) {
    if ($num_s && !$num_e) $num_e = $num_s;
    elseif (!$num_s && $num_e) $num_s = 0;
    $filter .= " and (a.people >= $num_s and a.people<=$num_e)";
    $findMode = 1;
}
if ($date_s || $date_e) {
    if ($date_s && !$date_e) $date_e = $date_s;
    elseif (!$date_s && $date_e) $date_s = $date_e;
    $filter .= " and ($dtype >= '$date_s' and $dtype<='$date_e')";
    $findMode = 1;
}

if ($price_s || $price_e) {
    $price_s = rnf($price_s);
    $price_e = rnf($price_e);
    if ($price_s && !$price_e) $price_e = $price_s;
    elseif (!$price_s && $price_e) $price_s = $price_e;
    $filter .= " and ((a.price/a.people) >= '$price_s' and (a.price/a.people)<='$price_e')";
    $findMode = 1;
}


if ($nation) {
    $filter .= " and b.nation='$nation'";
    $findMode = 1;

    $CITY = "";
    $sql = "select distinct city from cmp_golf where nation='$nation' and city<>'' order by city asc";
    $dbo->query($sql);
    //if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}
    while ($rs = $dbo->next_record()) {
        $CITY .= "," . $rs[city];
    }
}
if ($city) {
    $filter .= " and b.city='$city'";
    $findMode = 1;
}
if ($keyword) {
    $filter .= " and b.name like '%${keyword}%'";
    $findMode = 1;
}


#query
$sql_1 = "
    select
        a.id_no,
        a.d_date,
        a.main_staff,
        a.code,
        a.name as leader,
        a.name,
        a.phone,
        a.people,
        b.nation,
        b.city,
        b.name as goods,
        (a.price/a.people) as price_one
    from cmp_reservation as a left join cmp_golf as b
    on a.golf_id_no=b.id_no
    where
        a.phone<>''
        $filter
        $FILTER_PARTNER_QUERY_CPID
    group by phone

";
$sql_2 = $sql_1 . " order by a.d_date desc,(a.price/a.people) desc limit  $start, $view_row";
$_SESSION[down_sql] = $sql_1;

####자료갯수
list($rows) = $dbo->query($sql_1);//검색된 자료의 갯수
$row_search = $rows;


####페이지 처리

$var = ceil($row_search / $view_row);
if ($var > 1) {
    $total_page = $var;
} else {
    $total_page = 1;
}


####자료가 하나도 없을 경우의 처리
if (!$row_search) {
    $error[noData] = accentError("해당하는 자료가 없습니다.");
}


####검색 항목
$selectTxt = "관광지명,국가,지역,내용";
$selectValue = "name,nation,city,content";


#### Link
$keyword2 = base64_encode($keyword);
$link = "nation=$nation&city=$city&keyword";
$sessLink = "page=$page&" . $link;

$price_s = nf($price_s);
$price_e = nf($price_e);
?>
<? include("../top.html"); ?>
<script type="text/javascript">
    function find() {
        document.fm_find.submit();
    }

    function newWinForm(frm) {
        frm.target = "ListAge";
        frm.action = "view_download.php";
        var ListAgeWindow = window.open("", "ListAge", "width=1200px,height=650px,status=no,resizable=no,channelmode=0,directories=no,location=no,address=no,menubar=no,toolbar=no,scrollbars=yes,left=0,top=0");
        frm.submit();
    }
</script>
<style type="text/css">
    .gap {
        display: inline-block;
        margin: 0 10px 0 10px;
    }
</style>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td class="title_con"><img src="../images/common/ic_title.gif" align="absmiddle"><?= $TITLE ?>

        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td background="../images/common/bg_title.gif" height="5"></td>
    </tr>
</table>


<!--내용이 들어가는 곳 시작-->

<!-- Search Begin------------------------------------------------>
<div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
        <form name="fm_find" method="get" action="<?= SELF ?>">
            <tr height="22">
                <td><font color="#666666">* <?= ($status) ?> 검색된 예약 건: <?= number_format($row_search) ?>
                        개 <? if (!$seq_mode) { ?>{ <?= number_format($total_page) ?> page /  <?= number_format($page) ?> page }<? } ?></font>
                </td>
                <td valign='bottom' align=right>
                    <div style="margin-bottom:5px">

                        <input class=button type="button" name="view_btn" value=" 연령 " onFocus='blur(this)'
                               onclick="newWinForm(document.fm_find)">
                        <strong>인원</strong>&nbsp;&nbsp;
                        <input type="text" name="num_s" id="num_s" size="3" maxlength="2" value="<?= $num_s ?>"
                               class="box c numberic"> ~
                        <input type="text" name="num_e" id="num_e" size="3" maxlength="2" value="<?= $num_e ?>"
                               class="box c numberic">명
                        <span class="gap">|</span>


                        <select name="sex">
                            <option value="">성별</option>
                                <?= option_str("남성,여성", "M,F", $sex) ?>
                        </select>
                        <span class="gap">|</span>


                        <strong>판매가(1인)</strong>&nbsp;&nbsp;
                        <input type="text" name="price_s" id="price_s" size="11" maxlength="10" value="<?= $price_s ?>"
                               class="box c comma numberic"> ~
                        <input type="text" name="price_e" id="price_e" size="11" maxlength="10" value="<?= $price_e ?>"
                               class="box c comma numberic">원
                        <span class="gap">|</span>


                        <select name="dtype">
                            <?= option_str("예약일기준,출국일기준", "a.tour_date,a.d_date", $dtype) ?>
                        </select>
                        <input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?= $date_s ?>"
                               class="box c dateinput"> ~
                        <input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?= $date_e ?>"
                               class="box c dateinput">

                    </div>


                    <select name="nation" onchange="find()">
                        <option value="">국가</option>
                            <?= option_str($NATIONS, $NATIONS, $nation) ?>
                    </select>

                    <? if ($nation) { ?>
                        <select name="city" onchange="find()">
                            <?= option_str("지역" . $CITY, $CITY, $city) ?>
                        </select>
                    <? } ?>

                    <input class=box type="text" name="keyword" size="15" maxlength="40" value='<?= $keyword ?>'
                           placeholder="상품명">
                    <input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
                    <? if ($findMode): ?>
                        <input class=button type="button" value="전체목록" onclick="location.href='<?= SELF ?>'">
                    <? endif; ?>
                </td>
            <tr>
        </form>
    </table>
</div>
<!-- Search End------------------------------------------------>

<table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
    <tr align=center height=25 bgcolor="#F7F7F6">
        <th class="subject">국가</th>
        <th class="subject">지역</th>
        <th class="subject">출국일</th>
        <th class="subject">상품명</th>
        <th class="subject">1인 판매가</th>
        <th class="subject">대표자</th>
        <th class="subject">이름</th>
        <th class="subject">핸드폰</th>
    </tr>
    <?
    if ($page != 1) {
        $num = $row_search - ($view_row * ($page - 1));
    } else {
        $num = $row_search;
    }

    $dbo->query($sql_2);
    if ($debug) checkVar(mysql_error(), $sql_2);
    while ($rs = $dbo->next_record()) {
        ?>

        <? if (!$sex) {
            ?>
            <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'"
                onMouseOut="this.style.backgroundColor='#FFFFFF'">
                <td height="28"><?= $rs[nation] ?></td>
                <td><?= $rs[city] ?></td>
                <td><?= $rs[d_date] ?></td>
                <td><?= $rs[goods] ?></td>
                <td><?= nf($rs[price_one]) ?></td>
                <td>
                    <a href="javascript:newWin('view_reservation.php?id_no=<?= $rs[id_no] ?>',1200,650,1,1,'','reservation')"
                       style="<?= $css ?>"><?= $rs[leader] ?></a></td>
                <td><?= $rs[name] ?></td>
                <td><?= $rs[phone] ?></td>
            </tr>
        <?
        } ?>
        <?
        $filter_sub = "";
        if ($sex) $filter_sub = " and sex='$sex'";
        $sql3 = "select * from cmp_people where code='$rs[code]' and phone<>'' and phone<>'$rs[phone]' and seq<=$rs[people] $filter_sub";
        $dbo3->query($sql3);
        //if(strstr("@211.226.255.74@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql3);}
        while ($rs3 = $dbo3->next_record()) {
            ?>

            <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'"
                onMouseOut="this.style.backgroundColor='#FFFFFF'">
                <td height="28" colspan="5"></td>
                <td><?= $rs[leader] ?></td>
                <td><?= $rs3[name] ?></td>
                <td><?= $rs3[phone] ?></td>
            </tr>

            <?
            $num--;
        }
    }
    ?>
</table>
<table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
    <tr>
        <td colspan="12">

            <br>
            <!-- Button Begin---------------------------------------------->
            <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
                <tr>
                    <td width="60%" align="left">

                    </td>
                    <td align="right">
                        <span class="btn_pack medium bold"><a
                                    href="list_download_excel.php?gender=<?= $sex ?>"> 다운로드 </a></span>&nbsp;
                    </td>
                </tr>
            </table>
            <!-- Button End------------------------------------------------>

        </td>
    </tr>


    <? if (!$seq_mode) { ?>
        <tr>
            <td colspan="12" align=center style="padding-top:20px">
                <!-- navigation Begin---------------------------------------------->
                <? include_once('../../include/navigation.php') ?>
                <?= $navi ?>
                <!-- navigation End------------------------------------------------>
            </td>
        </tr>
    <? } ?>
</table>


<!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<? include_once("../bottom.html"); ?>
