<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"고객별예약정보관리대장");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "";
$MENU = "cmp_paper";
$TITLE = "고객정보 프로파일링(연령대별통계)";
$LEFT_HIDDEN = "1";

#### 기본 정보
$column = "*";
$basicLink = "";

####데이터를 불러오기 위한 쿼리(search의 경우를 위해)
#검색조건
if($num_s || $num_e){
    if($num_s && !$num_e) $num_e=$num_s;
    elseif(!$num_s && $num_e) $num_s=0;
    $filter.=" and (a.people >= $num_s and a.people<=$num_e)";
    $findMode=1;
}
if($date_s || $date_e){
    if($date_s && !$date_e) $date_e=$date_s;
    elseif(!$date_s && $date_e) $date_s=$date_e;
    $filter.=" and ($dtype >= '$date_s' and $dtype<='$date_e')";
    $findMode=1;
}

if($price_s || $price_e){
    $price_s = rnf($price_s);
    $price_e = rnf($price_e);
    if($price_s && !$price_e) $price_e=$price_s;
    elseif(!$price_s && $price_e) $price_s=$price_e;
    $filter.=" and ((a.price/a.people) >= '$price_s' and (a.price/a.people)<='$price_e')";
    $findMode=1;
}



if($nation){
    $filter .=" and b.nation='$nation'";
    $findMode=1;

    $CITY="";
    $sql = "select distinct city from cmp_golf where nation='$nation' and city<>'' order by city asc";
    $dbo->query($sql);
    //if(strstr("@112.172.15.90@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}
    while($rs=$dbo->next_record()){
        $CITY.=",".$rs[city];
    }
}
if($city){
    $filter .=" and b.city='$city'";
    $findMode=1;
}
if($keyword){
    $filter .=" and b.name like '%${keyword}%'";
    $findMode=1;
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
        (a.price/a.people) as price_one,
        c.rn,
        c.sex
    from cmp_reservation as a 
    left join cmp_golf as b on a.golf_id_no=b.id_no
    LEFT JOIN cmp_people as c ON a.code = c.code
    where
        a.phone<>''
        $filter
        $FILTER_PARTNER_QUERY_CPID
    group by phone
";
$_SESSION[down_sql]=$sql_1;

####자료갯수
list($rows)=$dbo->query($sql_1);//검색된 자료의 갯수
$row_search = $rows;
if($debug) checkVar($rows.mysql_error(),$sql_1);

###  주민번호 복호화 
$i=0;
while($rs=$dbo->next_record()){
    if($rs[rn]){
        $aes = new AES($rs[rn], $inputKey, $blockSize);
        $dec=$aes->decrypt();
        $rn[$i] = substr($dec,0,2);
    }else{
        $rn[$i] = "";
    }
    $sex[$i] = $rs[sex];
    $d_date[$i] = substr($rs[d_date],0,4);
    $i++;
}

for($j=0; $j < count($d_date); $j++){
    $age = (int)$d_date[$j] - (int)("19".$rn[$j]);
    if($rn[$j] == "") $age = 0; //주민번호가 없으면 0으로 세팅
    if($age > 100 ){  // 100세보다 많으면 2000년 생으로 변경 하자
        $age = (int)$d_date[$j] - (int)("20".$rn[$j]);
    }
//    echo "age=$age";
    if($sex[$j] == 'M'){
        if($age  >= 10 && $age <= 19){
            $ageM1++;
        }else if($age >=20 && $age <=29){
            $ageM2++;
        }else if($age >=30 && $age <=39){
            $ageM3++;
        }else if($age >=40 && $age <=49){
            $ageM4++;
        }else if($age >=50 && $age <=59){
            $ageM5++;
        }else if($age >=60 && $age <=69){
            $ageM6++;
        }else if($age >=70){
            $ageM7++;
        }else{
            $ageM++;
        }
    }else{
        if($age  >= 10 && $age <= 19){
            $ageF1++;
        }else if($age >=20 && $age <=29){
            $ageF2++;
        }else if($age >=30 && $age <=39){
            $ageF3++;
        }else if($age >=40 && $age <=49){
            $ageF4++;
        }else if($age >=50 && $age <=59){
            $ageF5++;
        }else if($age >=60 && $age <=69){
            $ageF6++;
        }else if($age >=70){
            $ageF7++;
        }else{
            $ageF++;
        }
    }
}
/*
echo "$ageM, $ageM1, $ageM2, $ageM3, $ageM4, $ageM5, $ageM6, $ageM7,";
echo "$ageF, $ageF1, $ageF2, $ageF3, $ageF4, $ageF5, $ageF6, $ageF7,";
echo "$row_search";
*/
?>

<div style="padding:0 10px 0 10px">

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="title_con" style="padding-left:10px"><img src="../images/common/ic_title.gif" align="absmiddle"><?=$TITLE?></td>
      </tr>
      <tr>
        <td> </td>
      </tr>
       <tr>
        <td background="../images/common/bg_title.gif" height="5"></td>
      </tr>
    </table>
</div>
    <!--내용이 들어가는 곳 시작-->

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">
        <tbody>
        <tr><td colspan=2  bgcolor='#5E90AE' height=2></td></tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td class="subject" width="15%">총 고객</td>
          <td width="37%"><?=$row_search?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td class="subject" width="15%">남성</td>
          <td width="37%"><?=$ageM+$ageM1+$ageM2+$ageM3+$ageM4+$ageM5+$ageM6+$ageM7?>명 (<?=($ageM+$ageM1+$ageM2+$ageM3+$ageM4+$ageM5+$ageM6+$ageM7)/$row_search*100?> )%</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <td class="subject" width="15%">10대</td>
          <td width="37%"><?=$ageM1?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">20대</th>
          <td width="37%"><?=$ageM2?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">30대</th>
          <td width="37%"><?=$ageM3?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">40대</th>
          <td width="37%"><?=$ageM4?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">50대</th>
          <td width="37%"><?=$ageM5?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">60대</th>
          <td width="37%"><?=$ageM6?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">70대이상</th>
          <td width="37%"><?=$ageM7?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">기타</th>
          <td width="37%"><?=$ageM?>명</td>
        </tr>

        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">여성</th>
          <td width="37%"><?=$ageF+$ageF1+$ageF2+$ageF3+$ageF4+$ageF5+$ageF6+$ageF7?>명 (<?=($ageF+$ageF1+$ageF2+$ageF3+$ageF4+$ageF5+$ageF6+$ageF7)/$row_search*100?> )%</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">10대</th>
          <td width="37%"><?=$ageF1?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">20대</th>
          <td width="37%"><?=$ageF2?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">30대</th>
          <td width="37%"><?=$ageF3?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">40대</th>
          <td width="37%"><?=$ageF4?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">50대</th>
          <td width="37%"><?=$ageF5?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">60대</th>
          <td width="37%"><?=$ageF6?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">70대이상</th>
          <td width="37%"><?=$ageF7?>명</td>
        </tr>
        <tr align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
          <th class="subject" width="15%">기타</th>
          <td width="37%"><?=$ageF?>명</td>
        </tr>
        </tbody>
    </table>

<!-- Copyright -->
<?include_once("../bottom_min.html");?>
