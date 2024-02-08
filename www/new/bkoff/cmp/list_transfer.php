<?
include_once("../include/common_file.php");
header("Content-Type: text/html; charset=UTF-8");


//chk_power($_SESSION["sessLogin"]["proof"],"보고서");
$LEFT_HIDDEN="1";
        

####save
if($mode=="save" && $id_no && $str){

    $col = ($assort=="air")? "date_out":"date_out2";
    if($assort=="refund") $col = "date_out3";

    $sql = "update cmp_people set $col='$str' where id_no=$id_no";
    list($rows)=$dbo->query($sql);
    //checkVar($rows.mysql_error(),$sql);exit;

    echo "<script>parent.location.reload()</script>";

    exit;
}
elseif($mode=="fix" && $id_no){

    if($assort=="air") $col = "pay_fix";
    elseif($assort=="land") $col = "pay_fix2";
    elseif($assort=="refund") $col = "pay_fix3";

    $sql = "update cmp_people set $col='$str' where id_no=$id_no";
    $dbo->query($sql);
    //checkVar($rows.mysql_error(),$sql);exit;

    echo "<script>parent.location.reload()</script>";

    exit;
}
elseif($mode=="fix_batch"){

    while(list($key,$val)=each($check)){

        $arr = explode("@",$val);
        $id_no = $arr[0];
        $assort = $arr[1];

        if($assort=="air") $col = "pay_fix";
        elseif($assort=="land") $col = "pay_fix2";
        elseif($assort=="refund") $col = "pay_fix3";

        $sql = "update cmp_people set $col='$str' where id_no=$id_no";
        $dbo->query($sql);
        //checkVar($rows.mysql_error(),$sql);

    }
    echo "<script>parent.location.reload()</script>";

    exit;
}
elseif($mode=="memo" && $id_no){

    $sql = "update cmp_people set trans_memo='$str' where id_no=$id_no";
    list($rows)=$dbo->query($sql);
    //checkVar($rows.mysql_error(),$sql);exit;

    exit;
}
elseif($mode=="memo2" && $id_no){

    $sql = "update cmp_people set trans_memo2='$str' where id_no=$id_no";
    list($rows)=$dbo->query($sql);
    //checkVar($rows.mysql_error(),$sql);exit;

    exit;
}



$dtype=($dtype)? $dtype : "d_date";

$date_s = ($date_s)? $date_s : date("Y/m/01");
$date_e = ($date_e)? $date_e : date("Y/m/d");

$year_this = substr($date_s,0,4);
$year_prev = substr($date_s,0,4)-1;
$date_s2 = $year_prev . substr($date_s,4);
$date_e2 = $year_prev . substr($date_e,4);


if(substr($date_s,0,4) > substr($date_e,0,4)){
    error("날짜가 잘못되었습니다. 다시 확인해 주세요.");
    exit;
}


/*파트너용 필터*/
$FILTER_PARTNER_QUERY = str_replace("cp_id","b.cp_id",$FILTER_PARTNER_QUERY);
$filter.=($FILTER_PARTNER_QUERY)? $FILTER_PARTNER_QUERY : " and b.cp_id=''";

//if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar("filter",$filter);


####기초 정보
$filecode = substr(SELF,5,-4);
$MENU = "cmp_transfer";
$TITLE = "송금요청";

?>
<?include("../top.html");?>
<style type="text/css">
#tbl_cmp_list td{padding:5px 0 5px 0;text-align:center;padding-right:2px;}
.r{padding-right:5px !important}
</style>

<script type="text/javascript">
<!--
function find(id_no){
    newWin("pop_transfer.php?id_no="+id_no,900,720,1,1,'transfer');
}

function save_memo(id_no,str){
    var url = "<?=SELF?>?mode=memo";
    url +="&id_no="+id_no;
    url +="&str="+str;
    actarea.location.href=url;
}
function save_memo2(id_no,str){
    var url = "<?=SELF?>?mode=memo2";
    url +="&id_no="+id_no;
    url +="&str="+str;
    actarea.location.href=url;
}

function save_senddate(assort,id_no,str){
    var url = "<?=SELF?>?mode=save";
    url +="&assort="+assort;
    url +="&id_no="+id_no;
    url +="&str="+str;
    actarea.location.href=url;
}

function save_fix(assort,id_no,str){
    var url = "<?=SELF?>?mode=fix";
    url +="&assort="+assort;
    url +="&id_no="+id_no;
    url +="&str="+str;
    actarea.location.href=url;
}

function selectAll(){
    fm = document.fmData;
    for(var i = 1; i < fm.elements.length; i++){
        fm.elements[i].checked = (fm.checkAll.checked == 1)? 1 : 0;
    }
}


function save_fix_batch(str){
    var j = 0;
    var bit = (str==1)?"완료":"취소";
    fm = document.fmData;
    fm.str.value=str;

    for(var i = 1; i < fm.elements.length; i++){
        if(fm.elements[i].checked == 1){
            j++;
        }
    }
    if(j == 0){
        alert(bit+" 처리할 자료를 선택하지 않으셨습니다.");
        return;
    }
    if(confirm("선택한 자료를 "+bit+" 처리 하시겠습니까?")){
        fm.submit();
    }
}

function pop_files(code,assort,id_no,name){
    var url ="set_files.php?code="+code;
    url += "&assort=" + assort;
    url += "&id_no=" + id_no;
    url += "&name=" + name;

    if(id_no==""){
        alert("파일을 등록하시려면 먼저 저장해 주세요.");
        return;     
    }

    newWin(url,850,200,1,1);
}

$(function(){
    $(".numberic").on("focus",function(){
        this.select();
    });
    //sum();
});
//-->
</script>
<style type="text/css">
.red{color:red;font-weight: bold}   
</style>

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

    <br/>

    <!--내용이 들어가는 곳 시작-->

    <!-- Search Begin------------------------------------------------>
    <div style="padding:0 0 5px 0">
    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_list">
    <form name="fmSearch" method="get" action="<?=SELF?>">
    <input type=hidden name='position' value="">
    <input type=hidden name='ctg1' value="<?=$ctg1?>">


    <tr height=22>
    <td valign='bottom' align=right>

    출금일 
    <input type="text" name="date_s" id="date_s" size="13" maxlength="10" value="<?=$date_s?>" class="box c dateinput">
    ~
    <input type="text" name="date_e" id="date_e" size="13" maxlength="10" value="<?=$date_e?>" class="box c dateinput">


    <!-- <select name="dtype">
        <?=option_str("예약일기준,출국일기준","tour_date,d_date",$dtype)?>
    </select> -->


    <select name="target">
        <?=option_str("대표자,성명,계좌번호","b.name,a.name,a.account",$target)?>
    </select>


    <?$pay_fix=($pay_fix)?$pay_fix:"F"?>
    <select name="pay_fix">
        <?=option_str("처리,미처리","T,F",$pay_fix)?>
    </select>   

    <input type="text" name="keyword" id="keyword" value="<?=$keyword?>" size="10" maxlength="20" class="box">

    <input class=button type="submit" name="Submit" value=" 검 색 " onFocus='blur(this)'>
    </td>
    <tr>
    </form>
    </table>
    </div>
    <!-- Search End------------------------------------------------>


    <?
    if($keyword){
        if($target=="a.account"){
            $filter1=" and a.account like '%$keyword%'";    
            $filter2=" and a.account2 like '%$keyword%'";   
        }else{
            $keyword = trim($keyword);
            $filter = " and $target like '%$keyword%'";
        }
    }

    // if($pay_fix){
    //  $pay_fix_ = ($pay_fix=="T")? 1:0;
    //  $filter_fix1=" and pay_fix=$pay_fix_";
    //  $filter_fix2=" and pay_fix2=$pay_fix_";
    //  $filter_fix3=" and pay_fix3=$pay_fix_";
    // }

    $sql ="
        select 
            'air' as assort,
            a.code,
            a.id_no,
            a.name,
            a.account,
            a.pay_fix,
            a.trans_memo,
            a.trans_memo2,
            a.price_air,
            '' as price_land,
            '' as price_refund,
            b.main_staff as staff,
            a.date_out as date_out_,
            a.send_date,
            a.datetime_out,
            a.seq,
            b.name as leader,
            b.id_no as reserv_id_no,
            b.tour_date,
            b.d_date,
            (select name from cmp_golf where id_no=b.golf_id_no) as golf,
            (select partner from cmp_golf where id_no=b.golf_id_no) as partner,
            (select sum(price_prev+price_prev2+price_prev3) from cmp_people where code=a.code) as price_in
            from cmp_people as a right join cmp_reservation as b
            on a.code=b.code
            where
                a.bit=1
                and a.name<>''
                and price_air>0
                and (a.date_out >= '$date_s' and a.date_out <='$date_e')
                $filter
                $filter1
                $filter_fix1

        union all 

        select 
            'land' as assort,
            a.code,
            a.id_no,
            a.name,
            a.account2 as account,
            a.pay_fix2 as pay_fix,
            a.trans_memo,
            a.trans_memo2,
            '' as price_air,
            a.price_land,
            '' as price_refund,
            b.main_staff as staff,
            a.date_out2 as date_out_,
            a.send_date2 as send_date,
            a.datetime_out,
            a.seq,
            b.name as leader,
            b.id_no as reserv_id_no,
            b.tour_date,
            b.d_date,
            (select name from cmp_golf where id_no=b.golf_id_no) as golf,
            (select partner from cmp_golf where id_no=b.golf_id_no) as partner,
            (select sum(price_prev+price_prev2+price_prev3) from cmp_people where code=a.code) as price_in
            from cmp_people as a right join cmp_reservation as b
            on a.code=b.code
            where
                a.bit=1
                and a.name<>''
                and price_land>0
                and (a.date_out2 >= '$date_s' and a.date_out2 <='$date_e')
                $filter         
                $filter2            
                $filter_fix2

        union all 


        select 
            'refund' as assort,
            a.code,
            a.id_no,
            a.name,
            a.account3 as account,
            a.pay_fix3 as pay_fix,
            a.trans_memo,
            a.trans_memo2,
            '' as price_air,
            '' as price_land,
            a.price_refund,
            b.main_staff as staff,
            a.date_out3 as date_out_,
            a.send_date3 as send_date,
            a.datetime_out,
            a.seq,
            b.name as leader,
            b.id_no as reserv_id_no,
            b.tour_date,
            b.d_date,
            (select name from cmp_golf where id_no=b.golf_id_no) as golf,
            (select partner from cmp_golf where id_no=b.golf_id_no) as partner,
            (select sum(price_prev+price_prev2+price_prev3) from cmp_people where code=a.code) as price_in
            from cmp_people as a right join cmp_reservation as b
            on a.code=b.code
            where
                a.bit=1
                and a.name<>''
                and price_refund>0
                and (a.date_out3 >= '$date_s' and a.date_out3 <='$date_e')
                $filter         
                $filter2    
                $filter_fix3                        

        order by date_out_ desc,datetime_out desc, leader asc   
    ";

    list($rows) = $dbo->query($sql);
    if($debug) checkVar($rows. mysql_error(),$sql);

    ?>


    <form name="fmData" method="get" action="<?=SELF?>" target="actarea">
    <input type="hidden" name="mode" value="fix_batch">
    <input type="hidden" name="str" value="1">

    <table border="0" cellspacing="0" cellpadding="3" width="100%" id="tbl_cmp_list">

        <tr align="center" height="25" bgcolor="#F7F7F6">
            <td width="20"><input type="checkbox" name="checkAll" value="checkbox" onClick="selectAll();" onFocus='blur(this)'></td>
            <th class="subject">대표자명</th>
            <th class="subject">출국일</th>
            <th class="subject">고객명</th>
            <th class="subject">상품명</th>
            <!-- <th class="subject">거래처</th> -->
            <th class="subject">계좌번호</th>
            <th class="subject">입금액합계</th>
            <th class="subject">출금액1<br/>(항공)</th>
            <th class="subject">출금액2<br/>(지상)</th>
            <th class="subject">출금액3<br/>(환불)</th>
            <th class="subject" width="57">메모</th>
            <th class="subject" width="57">메모2</th>
            <th class="subject">담당자</th>
            <th class="subject">출금일</th>
            <th class="subject" width="63">송금일</th>
            <th class="subject">예약일</th>
            <th class="subject">완료</th>
        </tr>

        <?
        $sum=0;
        $color = "blue";
        $total1=0;
        $total2=0;
        $total3=0;
        $total4=0;

        $total_fix1=0;
        $total_fix2=0;
        $total_fix3=0;
        while($rs= $dbo->next_record()){

            $color = ($rs[pay_fix])? "red":"blue";

            $total1+=$rs[price_air];
            $total2+=$rs[price_land];
            $total3+=$rs[price_refund];
            $total4+=$rs[price_in];

            $pay_fix_=($pay_fix=="T")?1:0;
            if($pay_fix_==$rs[pay_fix]){
                $total_fix1+=$rs[price_air];
                $total_fix2+=$rs[price_land];
                $total_fix3+=$rs[price_refund];


                $code = $rs[code];
                $id_code2 = $code . "_" . $rs[id_no];
                $sql2 = "select * from cmp_files where id_code='${id_code2}_1'";
                if($rs[assort]=="air") list($photo_rows1) = $dbo2->query($sql2);
                //checkVar($photo_rows1,$sql2);
                $sql2 = "select * from cmp_files where id_code='${id_code2}_2'";
                if($rs[assort]=="land") list($photo_rows2) = $dbo2->query($sql2);
                //checkVar($photo_rows2,$sql2);
                $sql2 = "select * from cmp_files where id_code='${id_code2}_4'";
                if($rs[assort]=="refund") list($photo_rows3) = $dbo2->query($sql2);
                //checkVar($photo_rows3,$sql2);


        ?>
        
        <tr onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'">
            <td><input type="checkbox" name="check[]" value="<?=$rs[id_no]?>@<?=$rs[assort]?>"></td>
            <td><a href="javascript:newWin('view_reservation.php?id_no=<?=$rs[reserv_id_no]?>',1100,650,1,1,'','reservation')"><?=$rs[leader]?></a></td><!-- 대표자명 -->
            <td><?=$rs[d_date]?></td><!-- 출국일 -->
            <td><?=$rs[name]?>(<?=$rs[id_no]?>/<?=$rs[assort]?>)</td><!-- 고객명 -->
            <td><?=$rs[golf]?></td><!-- 상품명 -->
            <!-- <td><?=$rs[partner]?></td> --><!-- 거래처 -->
            <td onclick="find('<?=$rs[id_no]?>_<?=$rs[assort]?>')" >
                <span id="account_<?=$rs[id_no]?>_<?=$rs[assort]?>"><?=$rs[account]?></span>
            </td><!-- 계좌번호 -->
            <td class="numberic <?=$color?>"><?=nf($rs[price_in])?></td><!-- 입금액합계 -->
            <td class="numberic <?=$color?>"><?=nf($rs[price_air])?>
                <a href="javascript:pop_files('<?=$code?>',1,'<?=$rs[id_no]?>','<?=$rs[name]?>')"><img id="pfile1_<?=$j?>" src="/renew/images/ez_board/ico_file0<?=($photo_rows1)?'3':'2'?>.gif"></a>
            </td><!-- 출금액1 -->
            <td class="numberic <?=$color?>"><?=nf($rs[price_land])?>
                <a href="javascript:pop_files('<?=$code?>',2,'<?=$rs[id_no]?>','<?=$rs[name]?>')"><img id="pfile2_<?=$j?>" src="/renew/images/ez_board/ico_file0<?=($photo_rows2)?'3':'2'?>.gif"></a>
            </td><!-- 출금액2 -->
            <td class="numberic <?=$color?>"><?=nf($rs[price_refund])?>
                <a href="javascript:pop_files('<?=$code?>',3,'<?=$rs[id_no]?>','<?=$rs[name]?>')"><img id="pfile3_<?=$j?>" src="/renew/images/ez_board/ico_file0<?=($photo_rows3)?'3':'2'?>.gif"></a>
            </td><!-- 출금액3 -->
            <td > <input type="text" size="15" maxlength="50" class="box" value="<?=$rs[trans_memo]?>" onchange="save_memo('<?=$rs[id_no]?>',this.value)"></td><!-- 메모 -->          
            <td > <input type="text" size="10" maxlength="50" class="box" value="<?=$rs[trans_memo2]?>" onchange="save_memo2('<?=$rs[id_no]?>',this.value)"></td><!-- 메모2 -->                       
            <td><?=str_replace(strstr($rs[staff],"("),"",$rs[staff])?></td><!-- 담당자 -->
            <td><?=$rs[date_out_]?></td><!-- 출금일 -->
            <td ><input type="text" size="11" maxlength="10" class="box dateinput" value="<?=$rs[send_date]?>" onchange="save_senddate('<?=$rs[assort]?>','<?=$rs[id_no]?>',this.value)"></td><!-- 송금일 -->
            <td><?=$rs[tour_date]?></td><!-- 예약일 -->
            <td>
                <?if($_SESSION["sessLogin"]["staff_type"]=="ceo"){?>

                    <?if($rs[pay_fix]){?>
                    <span class="btn_pack medium bold"><a href="javascript:save_fix('<?=$rs[assort]?>','<?=$rs[id_no]?>',0)"> 취소 </a></span>
                    <?}else{?>
                    <span class="btn_pack medium bold"><a href="javascript:save_fix('<?=$rs[assort]?>','<?=$rs[id_no]?>',1)"> 완료 </a></span>
                    <?}?>

                <?}?>
            </td><!-- 완료 -->
        </tr>
        <?
            }
        }
        ?>


        <tr style="background-color:#ffe6cc">
            <td colspan="7" class="subject">합계</td>
            <!-- <td class="numberic b"><?=nf($total4)?></td> -->
            <td class="numberic b"><?=nf($total_fix1)?></td>
            <td class="numberic b"><?=nf($total_fix2)?></td>
            <td class="numberic b"><?=nf($total_fix3)?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr style="background-color:#ffe6cc">
            <td colspan="7" class="subject"><?=($pay_fix=="T")?"처리":"미처리"?> 합계</td>
            <td class=" b c" colspan="3"><?=nf($total_fix1+$total_fix2+$total_fix3)?></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

    </table>
    </form>

    <div style="padding:10px 0 10px 10px;float:right">
        
        <?if($pay_fix=="F"){?>
        <span class="btn_pack medium bold"><a href="javascript:save_fix_batch(1)"> 완료 </a></span>
        <?}else{?>
        <span class="btn_pack medium bold"><a href="javascript:save_fix_batch(0)"> 취소 </a></span>
        <?}?>

        <?if(strstr($_SESSION["sessLogin"]["proof_erp"],"엑셀다운로드")){?>
        <span class="btn_pack medium bold"><a href="list_transfer_excel.php?date_s=<?=$date_s?>&date_e=<?=$date_e?>&target=<?=$target?>&pay_fix=<?=$pay_fix?>&keyword=<?=$keyword?>"> 엑셀 다운로드 </a></span>&nbsp;
        <?}?>
    </div>


    <!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom.html");?>
