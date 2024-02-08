<?
include_once("../include/common_file.php");


####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_people";
$MENU = "cmp_basic";
$TITLE = "예약정보 > 고객정보 입력";
$file_rows = 1; //파일 업로드 갯수
$birth_mode=0;
if($apis==100){
    $apis=1;
    $birth_mode=1;
    $TITLE = "고객예약정보 > 고객정보 (생일고객)";
}


#### staff
$sql = "select * from cmp_staff where bit_hide<>1 order by name asc";
$dbo->query($sql);
while($rs=$dbo->next_record()){
    $STAFF.=",$rs[name] ($rs[id])";
}


#### operation
if ($mode=="save"){

    $staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

    $reg_date = date('Y/m/d');
    $reg_date2 = date('H:i:s');

    $bit=1;

    $sql = "update $table set bit=0 where code=$code";
    $dbo->query($sql);

    $p="";

    $bit_cancel_all=0;

    for($i=0; $i < $people+$people_cancel;$i++){

            $name[$i] = ($name[$i])? $name[$i] : "일행";
            $p .= "{@}"  . $name[$i] . "{@}";

            $price[$i] = rnf($price[$i]);
            $price_air[$i]= rnf($price_air[$i]);
            $price_land[$i]= rnf($price_land[$i]);
            $price_refund[$i]= rnf($price_refund[$i]);
            $price_prev[$i]= rnf($price_prev[$i]);
            $price_prev2[$i]= rnf($price_prev2[$i]);
            $price_prev3[$i]= rnf($price_prev3[$i]);
            $price_input[$i]= rnf($price_input[$i]);
            $price_last[$i]= $price[$i]- ($price_prev[$i] + $price_prev2[$i] + $price_prev3[$i]);
            $passport_limit[$i] = strtoupper($passport_limit[$i]);
            $bit_cancel[$i] = rnf($bit_cancel[$i]);
            //$price_prev = rnf($price_prev);

            $rn[$i] = trim($rn[$i]);
            $name[$i] = trim($name[$i]);
            $passport_no[$i] = trim($passport_no[$i]);

            if($rn[$i]){

                $s = substr(rnf($rn[$i]),6,1);
                $sex[$i] = ($s=="9" || $s=="1" || $s=="3" || $s=="5" || $s=="7")? "M":"F";

                $aes = new AES($rn[$i], $inputKey, $blockSize);
                $enc = $aes->encrypt();
                $rn[$i] = $enc;
            }

            if($passport_no[$i]){
              $aes = new AES($passport_no[$i], $inputKey, $blockSize);
              $enc = $aes->encrypt();
              $passport_no[$i] = $enc;
            }

            if(($date_out[$i]!=$date_out_old[$i])|| ($date_out2[$i]!=$date_out2_old[$i])){
                $datetime_out = date("Y/m/d H:i:s");
                $datetime_query = "datetime_out = '$datetime_out',";
            }


            $seq = $i+1;

            $sqlInsert="
               insert into $table (
                  code,
                  cp_id,
                  id,
                  name,
                  sex,
                  name_eng,
                  rn,
                  passport_no,
                  passport_limit,
                  skypass,
                  phone,
                  price,
                  price_air,
                  price_land,
                  price_refund,
                  price_prev,
                  price_prev2,
                  price_prev3,
                  price_input,
                  price_last,
                  memo,
                  seq,
                  bit,
                  bit_cancel,
                  bit_cancel_date,
                  insure,
                  staff,
                  date_in,
                  date_in2,
                  date_in3,
                  date_out,
                  date_out2,
                  date_out3,
                  datetime_out,
                  memo_in,
                  memo_out,
                  bank_out1_id,
                  bank_out2_id,
                  bank_out3_id,
                  bank_in1_id,
                  bank_in2_id,
                  bank_in3_id,
                  reg_date,
                  reg_date2
              ) values (
                  '$code',
                  '$CP_ID',
                  '$id[$i]',
                  '$name[$i]',
                  '$sex[$i]',
                  '$name_eng[$i]',
                  '$rn[$i]',
                  '$passport_no[$i]',
                  '$passport_limit[$i]',
                  '$skypass[$i]',
                  '$phone[$i]',
                  '$price[$i]',
                  '$price_air[$i]',
                  '$price_land[$i]',
                  '$price_refund[$i]',
                  '$price_prev[$i]',
                  '$price_prev2[$i]',
                  '$price_prev3[$i]',
                  '$price_input[$i]',
                  '$price_last[$i]',
                  '$memo[$i]',
                  '$seq',
                  '$bit',
                  '$bit_cancel[$i]',
                  '$bit_cancel_date[$i]',
                  '$insure[$i]',
                  '$staff',
                  '$date_in[$i]',
                  '$date_in2[$i]',
                  '$date_in3[$i]',
                  '$date_out[$i]',
                  '$date_out2[$i]',
                  '$date_out3[$i]',
                  '$datetime_out',
                  '$memo_in[$i]',
                  '$memo_out[$i]',
                  '$bank_out1_id[$i]',
                  '$bank_out2_id[$i]',
                  '$bank_out3_id[$i]',
                  '$bank_in1_id[$i]',
                  '$bank_in2_id[$i]',
                  '$bank_in3_id[$i]',
                  '$reg_date',
                  '$reg_date2'
            )";

            $outprice_query = "";
            if($price_air[$i]!=$price_air_old[$i]) $outprice_query = "account='',";
            if($price_land[$i]!=$price_land_old[$i]) $outprice_query .= "account2='',";
            if($price_refund[$i]!=$price_refund_old[$i]) $outprice_query .= "account3='',";

            $sqlModify="
               update $table set
                  $datetime_query
                  $outprice_query
                  id = '$id[$i]',
                  name = '$name[$i]',
                  sex = '$sex[$i]',
                  name_eng = '$name_eng[$i]',
                  rn = '$rn[$i]',
                  passport_no = '$passport_no[$i]',
                  passport_limit = '$passport_limit[$i]',
                  skypass = '$skypass[$i]',
                  phone = '$phone[$i]',
                  price = '$price[$i]',
                  price_air = '$price_air[$i]',
                  price_land = '$price_land[$i]',
                  price_refund = '$price_refund[$i]',
                  price_prev = '$price_prev[$i]',
                  price_prev2 = '$price_prev2[$i]',
                  price_prev3 = '$price_prev3[$i]',
                  price_input = '$price_input[$i]',
                  price_last = '$price_last[$i]',
                  memo = '$memo[$i]',
                  seq = '$seq',
                  date_in = '$date_in[$i]',
                  date_in2 = '$date_in2[$i]',
                  date_in3 = '$date_in3[$i]',
                  date_out = '$date_out[$i]',
                  date_out2 = '$date_out2[$i]',
                  date_out3 = '$date_out3[$i]',
                  memo_in = '$memo_in[$i]',
                  memo_out = '$memo_out[$i]',
                  insure = '$insure[$i]',
                  bank_out1_id='$bank_out1_id[$i]',
                  bank_out2_id='$bank_out2_id[$i]',
                  bank_out3_id='$bank_out3_id[$i]',
                  bank_in1_id='$bank_in1_id[$i]',
                  bank_in2_id='$bank_in2_id[$i]',
                  bank_in3_id='$bank_in3_id[$i]',
                  bit = '$bit',
                  bit_cancel_date = '$bit_cancel_date[$i]',
                  bit_cancel = '$bit_cancel[$i]'
               where id_no='$id_no[$i]'
            ";

            if($id_no[$i]){
                $sql =$sqlModify;
            }else{
                $sql =$sqlInsert;
            }

            if($dbo->query($sql)){
                //checkVar(mysql_error(),$sql);

                if($bit_cancel[$i]) $bit_cancel_all+=1;

                $id_code = $code . "_" . $i;

                //cmp_customer update
                $sql = "delete from cmp_customer where name='$name[$i]' and rn='$rn[$i]' ";
                $dbo->query($sql);

                $sql="
                   insert into cmp_customer (
                      cp_id,
                      id,
                      name,
                      leader,
                      sex,
                      name_eng,
                      rn,
                      passport_no,
                      passport_limit,
                      skypass,
                      phone,
                      staff
                  ) values (
                      '$CP_ID',
                      '$id[$i]',
                      '$name[$i]',
                      '$leader',
                      '$sex[$i]',
                      '$name_eng[$i]',
                      '$rn[$i]',
                      '$passport_no[$i]',
                      '$passport_limit[$i]',
                      '$skypass[$i]',
                      '$phone[$i]',
                      '$staff'
                )";
                $dbo->query($sql);


                $sql = "select sum(price_air+price_land+price_refund) as price_tmp_output from cmp_people where code=$code and bit=1";
                $dbo->query($sql);
                $rs=$dbo->next_record();

                $sql = "
                    update cmp_reservation set
                        people=(select count(*) from cmp_people where code=$code and bit=1 and bit_cancel<>1),
                        people_cancel=(select count(*) from cmp_people where code=$code and bit=1 and bit_cancel=1),
                        price=(select sum(price) from cmp_people where code=$code and bit=1),
                        price_last = (select sum(price)-sum(price_prev+price_prev2+price_prev3) from cmp_people where code=$code and bit=1),
                        price_prev = (select sum(price_prev+price_prev2+price_prev3) from cmp_people where code=$code  and bit=1),
                        price_customer_input = (select sum(price_input) from cmp_people where code=$code and bit=1),
                        fee = (select sum(price-(price_air+price_land+price_refund)) from cmp_people where code=$code and bit=1),
                        price_tmp_output = $rs[price_tmp_output]
                    where code=$code";
                $dbo->query($sql);


                $price_tmp_output = nf($rs[price_tmp_output]);
                //checkVar(mysql_error(),$sql);exit;

                if($price_prev[$i] && $bank_in1_id[$i]){ $sql = "update cmp_bank set bit=1 where id_no=".$bank_in1_id[$i];$dbo->query($sql);}
                if($price_prev2[$i] && $bank_in2_id[$i]){ $sql = "update cmp_bank set bit=1 where id_no=".$bank_in2_id[$i];$dbo->query($sql);}
                if($price_prev3[$i] && $bank_in3_id[$i]){ $sql = "update cmp_bank set bit=1 where id_no=".$bank_in3_id[$i];$dbo->query($sql);}

                if(!$price_prev[$i] && $bank_in1_id[$i]){ $sql = "update cmp_bank set bit=0 where id_no=".$bank_in1_id[$i];$dbo->query($sql);}
                if(!$price_prev2[$i] && $bank_in2_id[$i]){ $sql = "update cmp_bank set bit=0 where id_no=".$bank_in2_id[$i];$dbo->query($sql);}
                if(!$price_prev3[$i] && $bank_in3_id[$i]){ $sql = "update cmp_bank set bit=0 where id_no=".$bank_in3_id[$i];$dbo->query($sql);}

                if($price_air[$i] && $bank_out1_id[$i]){ $sql = "update cmp_bank set bit=1 where id_no=".$bank_out1_id[$i];$dbo->query($sql);}
                if($price_land[$i] && $bank_out2_id[$i]){ $sql = "update cmp_bank set bit=1 where id_no=".$bank_out2_id[$i];$dbo->query($sql);}
                if($price_refund[$i] && $bank_out3_id[$i]){ $sql = "update cmp_bank set bit=1 where id_no=".$bank_out3_id[$i];$dbo->query($sql);}

                if(!$price_air[$i] && $bank_out1_id[$i]){ $sql = "update cmp_bank set bit=0 where id_no=".$bank_out1_id[$i];$dbo->query($sql);}
                if(!$price_land[$i] && $bank_out2_id[$i]){ $sql = "update cmp_bank set bit=0 where id_no=".$bank_out2_id[$i];$dbo->query($sql);}
                if(!$price_refund[$i] && $bank_out3_id[$i]){ $sql = "update cmp_bank set bit=0 where id_no=".$bank_out3_id[$i];$dbo->query($sql);}

                //checkVar($i,$price_prev2[$i]);

            }else{
                checkVar(mysql_error(),$sql);exit;
            }

    }


    $sql2 = $dbo2->query("select sum(price) as total ,count(bit) as cnt from cmp_people where bit=1 and bit_cancel<>1 and code=$code");
    $rs2=$dbo2->next_record();
    $price = nf($rs2[total]);


    echo "
    <script>
        opener.document.getElementById('price').value='$price'
        opener.document.getElementById('price_tmp_output').value='$price_tmp_output'
        opener.location.reload();
    </script>";



    $bit_cancel=($bit_cancel_all)?1:0;

    $sql2 = $dbo2->query("update cmp_reservation set peoples='$p',bit_cancel='$bit_cancel' where code=$code");
    $rs2=$dbo2->next_record();

    $sql = "select count(*) as people from cmp_people where code=$code and bit=1 and bit_cancel<>1";
    $dbo->query($sql);
    $rs=$dbo->next_record();
    $url =SELF."?code=$code";
    $url.="&apis=$apis";
    $url.="&people=$rs[people]";
    $url.="&leader=$leader";
    $url.="&leader_phone=$leader_phone";
    $url.="&tour_date=$tour_date";


    echo "
        <script>
            alert('저장하였습니다.');
            opener.location.reload();
            location.href='${url}'
        </script>
    ";
    exit;

}elseif ($mode=="drop"){

    $sql = "delete from $table where id_no = $id_no and code=$code";
    $dbo->query($sql);

    $sql2 = "select * from cmp_people where code=$code";
    $dbo2->query($sql2);
    $p="";
    while($rs2=$dbo2->next_record()){
        $p .= "{@}"  . $rs2[name] . "{@}";
    }

    $sql = "select sum(price_air+price_land+price_refund) as price_tmp_output from cmp_people where code=$code and bit=1";
    $dbo->query($sql);
    $rs=$dbo->next_record();
    $sql = "
        update cmp_reservation set
            peoples='$p',
            people=(select count(*) from cmp_people where code=$code and bit=1 and bit_cancel<>1),
            people_cancel=(select count(*) from cmp_people where code=$code and bit=1 and bit_cancel=1),
            price=(select sum(price) from cmp_people where code=$code and bit=1),
            price_last = (select sum(price)-sum(price_prev+price_prev2+price_prev3) from cmp_people where code=$code and bit=1),
            price_prev = (select sum(price_prev+price_prev2+price_prev3) from cmp_people where code=$code  and bit=1),
            price_customer_input = (select sum(price_input) from cmp_people where code=$code and bit=1),
            fee = (select sum(price-(price_air+price_land+price_refund)) from cmp_people where code=$code and bit=1),
            price_tmp_output = '$rs[price_tmp_output]'
        where code=$code";
    $dbo->query($sql);

    $sql = "select count(*) as people from cmp_people where code=$code and bit=1 and bit_cancel<>1";
    $dbo->query($sql);
    $rs=$dbo->next_record();
    $url =SELF."?code=$code";
    $url.="&apis=$apis";
    $url.="&people=$rs[people]";
    $url.="&leader=$leader";
    $url.="&leader_phone=$leader_phone";
    $url.="&tour_date=$tour_date";

    echo "
        <script>
            alert('삭제하였습니다.');
            opener.location.reload();
            location.href='${url}'
        </script>
    ";
    exit;

}


$sql = "select * from cmp_reservation where code=$code";
list($rows) = $dbo->query($sql);
if($rows){
    $rs=$dbo->next_record();
    $leader = $rs[name];
    $bit_close=$rs[bit_close];
    $people_cancel = $rs[people_cancel];
}

/*
$sql2 = "
    select
        sum(price) as price,
        sum(price_prev+price_prev2) as prev,
        sum(price_input) as input,
        sum(price_air+price_land) as payed
    from cmp_people
    where code=$code and bit=1";
$dbo2->query($sql2);
$rs2=$dbo2->next_record();
$price=nf($rs2[price]);
$price_customer_input=nf($rs2[input]);
$price_prev=nf($rs2[prev]);
$price_last=nf($rs2[price]-$rs2[prev]);
$price_payed=nf($rs2[payed]);

$price_total_payed_ =$rs2[price] - $rs2[payed];
$price_one_payed_= ($rs2[price] - $rs2[payed])/$people;

$price_total_payed=nf($price_total_payed_);
$price_one_payed=nf($price_one_payed_);

$sql2 = "update cmp_reservation set profit_total='$price_total_payed_',profit_one='$price_one_payed_' where code=$code  ";
$dbo2->query($sql2);
//checkVar("",$sql2);
*/

//취소자 여부 확인
$sql3 = "select count(*) as cnt from cmp_people where code=$rs[code] and bit=1 and bit_cancel=1";
$dbo3->query($sql3);
$rs3=$dbo3->next_record();
$bit_cancel=$rs3[cnt];


$sql3 = "select
        count(*) as cnt,
        sum(price) as price,
        sum(price_air) as price_air,
        sum(price_land) as price_land,
        sum((price_prev+price_prev2+price_prev3)-(price_air+price_land+price_refund)) as fee
    from cmp_people where code=$rs[code] and bit=1";
$dbo3->query($sql3);
$rs3= $dbo3->next_record();
$gain_all = $rs3[fee];
if($rs[d_date]<="2017/12/31"){
    @$gain_one = ($rs3[price] - ($rs3[price_air]+$rs3[price_land]))/$rs[people];
}else{
    @$gain_one = $rs[fee]/$rs[people];
    // checkVar("d_date",$rs[d_date]);
    // checkVar("price_prev",$rs[price_prev]);
    // checkVar("price_air",$rs3[price_air]);
    // checkVar("price_land",$rs3[price_land]);
    // checkVar("people",$rs[people]);
    // checkVar("gain_all",$gain_all);
    // checkVar("gain_one",$gain_one);
}

$price_total_payed_ =$gain_all;
$price_one_payed_= $gain_one;

$price_total_payed=nf($price_total_payed_);
$price_one_payed=nf($price_one_payed_);

$sql2 = "update cmp_reservation set profit_total='$price_total_payed_',profit_one='$price_one_payed_' where code=$code  ";
$dbo2->query($sql2);
//checkVar(mysql_error(),$sql2);


//$people = rnf($_GET[people]);
//$people+=$people_cancel; //취소자 포함
$people = ($people)?$people : 1;

?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
    var fm = document.fmData;
    var people = "<?=$people?>";

    /*
    for(i=1;i<=people;i++){
        if($("#name_"+i).val()==""){alert("고객명을 입력해 주세요.");$("#name_"+i).focus();return;}
    }
    */

    fm.submit();

}

function find(i){

    if(i){
        var name = $("#name_"+i).val();
        if(name==""){alert("고객명을 입력하세요.");$("#name_"+i).focus();return;}
    }else{
        var name = $("#name").val();
        if(name==""){alert("고객명을 입력하세요.");$("#name").focus();return;}
    }

    newWin('pop_customer.php?code=<?=$code?>&name='+name+'&i='+i,950,400,1,1,'','customer')

}

function find2(i){

    if(i){
        var phone = $("#phone_"+i).val();
        if(phone==""){alert("연락처를 입력하세요.");$("#phone_"+i).focus();return;}
    }else{
        var phone = $("#phone").val();
        if(phone==""){alert("연락처를 입력하세요.");$("#phone").focus();return;}
    }

    newWin('pop_customer.php?phone='+phone+'&i='+i,950,400,1,1,'','customer')

}

function set_golf(k,v){
    $("#golf_name").val(k);
    $("#golf_id_no").val(v);
}

function modify(i){
    var fm = document.fmData2;

    fm.mode.value= "save";
    fm.id_no.value=$("#id_no_"+ i).val();
    fm.id.value=$("#id_"+ i).val();
    fm.name.value=$("#name_"+ i).val();
    fm.sex.value=$("#sex_"+ i).val();
    fm.name_eng.value=$("#name_eng_"+ i).val();
    fm.rn.value=$("#rn_"+ i).val();
    fm.passport_no.value=$("#passport_no_"+ i).val();
    fm.phone.value=$("#phone_"+ i).val();
    fm.price.value=$("#price_"+ i).val();
    fm.price_air.value=$("#price_air_"+ i).val();
    fm.price_land.value=$("#price_land_"+ i).val();
    fm.memo.value=$("#memo_"+ i).val();

    fm.submit();

}

function drop(i){
    var fm = document.fmData2;
    if(confirm('삭제하시겠습니까')){
        fm.mode.value= "drop";
        fm.id_no.value=$("#id_no_"+ i).val();
        fm.submit();
    }

}

function cell(col,cell){
    actarea.location.href="../action.php?mode=cell&col="+col+"&cell="+cell;
}

function num(col,price){
    //actarea.location.href="../action.php?mode=num&col="+col+"&price="+price;
}

function passport_limit(col,date){
    actarea.location.href="../action.php?mode=limit&col="+col+"&date="+date;
}

function pop_passport(j){
    var passport = $("#passport_no_b_"+j).val();
    var url ="set_passport.php?mode2=apis&code=<?=$code?>";
    url += "&j=" + j;
    url += "&passport_no=" + passport;
    url += "&name=" + $("#name_"+j).val();

    // if(passport=="" || passport==" "){
    //  alert("여권번호를 입력해 주세요.");
    //  $("#passport_no_"+j).focus();
    //  return;
    // }

    newWin(url,850,600,1,1);
}

function pop_files(assort,id_no,j){
    var url ="set_files.php?code=<?=$code?>";
    url += "&assort=" + assort;
    url += "&id_no=" + id_no;
    url += "&j=" + j;
    url += "&name=" + $("#name_"+j).val();
    url += "&cell=" + $("#phone_"+j).val();

    if(id_no==""){
        alert("파일을 등록하시려면 먼저 저장해 주세요.");
        return;
    }

    newWin(url,850,200,1,1);
}

function get_bank_data(io,id,j,inno){
    <?if(!$bit_close){?>
    var url = "pop_bank.php?id="+id;
    url += "&inno="+inno;
    url += "&io="+io;
    url += "&j="+j;
    if(io=="i") url += "&name="+$("#name_"+j).val();
    newWin(url,900,600,1,1,'pop_bank');
    <?}?>
}

jQuery(function($){

    $(".num").keypress(function(event){
        if(event.which && (event.which < 48 || event.which > 57)){
            event.preventDefault();
        }
    });

    $(".rn").mask("000000-0000000");
    $(".passport").mask("00AAA0000");
    $(".cell").mask("000-0000-0000");

    /*
    opener.$("#price").val('<?=$price?>');
    opener.$("#price_prev").val('<?=$price_prev?>');
    opener.$("#price_customer_input").val('<?=$price_customer_input?>');
    opener.$("#price_last").val('<?=$price_last?>');
    opener.$("#profit_total").text('<?=$price_total_payed?>');
    opener.$("#profit_one").text('<?=$price_one_payed?>');
    */
    $("#price_prev").val(opener.$("#price_prev").val());

    /*     2016-11-10 최경아 이사님 요청 자동 복사 기능 제거
    var people = "<?=nf($people)?>";
    $("#price_1").on("change",function(){
        var num =comma(this.value);
        $("#price_1").val(num);
        for(i=2; i<=people;i++){
            $("#price_"+i).val(num);
        }
    });
    $("#price_air_1").on("change",function(){
        var num =comma(this.value);
        $("#price_air_1").val(num);
        for(i=2; i<=people;i++){
            $("#price_air_"+i).val(num);
        }
    });
    $("#price_land_1").on("change",function(){
        var num =comma(this.value);
        $("#price_land_1").val(num);
        for(i=2; i<=people;i++){
            $("#price_land_"+i).val(num);
        }
    });


    $("#price_prev_1").on("change",function(){
        var num =comma(this.value);
        $("#price_prev_1").val(num);
        for(i=2; i<=people;i++){
            $("#price_prev_"+i).val(num);
        }
    });
    */


    /*
    $("#price_prev_1").on("change",function(){
        var num =comma(this.value);
        $("#price_prev_1").val(num);
    });
    */

    <?for($i=1; $i<=$people;$i++){?>
    $('#name_<?=$i?>').keypress(function(e){
        if(e.which == 13) find(<?=$i?>);
    });
    <?}?>

    $(".apis3").show();
    <?if($apis==2){?>
        $(".apis1").hide();
        $(".apis2").show();
    <?}elseif($apis==3){?>
        $(".apis1").hide();
        $(".apis2").show();
    <?}else{?>
        $(".apis1").show();
        $(".apis2").hide();
    <?}?>



    <?if($bit_close){?>
    $(".numberic").attr("readonly",true);
    $(".dateinput").attr("readonly",true);
    $(".dateinput").datepicker( "destroy");
    <?}?>

    opener.document.getElementById('btn_cancel').style.color='';
    <?if($bit_cancel){?>
    opener.document.getElementById('btn_cancel').style.color='red';
    <?}?>


    $("input").on("focus",function(){
        $(this).select();
    });


    <?if($_SESSION["sessLogin"]["staff_type"] != "ceo" && $_SESSION["sessLogin"]["staff_type"]!="partner_a"){?>
    $(".onlyceo").attr("readonly",true);
    <?}?>


});
</script>
<style type="text/css">
.sum{text-align:right;padding-right:12px}
input{padding:0;margin:0;letter-spacing: -0.8px;font-family: verdana;}
#tbl_list{padding: 0;margin: 0}
.sr{}

.blue{color:blue !important;}
.red{color:red !important;}

<?if($apis==3){?>
.bc_0{display: none}
<?}?>
</style>

<div style="padding:10px">

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

    <!--내용이 들어가는 곳 시작-->

    <br>

    <?if($REMOTE_ADDR=="1106.246.54.27"){?>
    <p class="sf2">! 여권 유효기간 형식 : 날짜 + 월(영문) + 년도, 예 : 24NOV2025</p>
    <?}?>
    <table border="0" cellspacing="0" cellpadding="0" width="100%" id="tbl_list">

        <form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
        <input type="hidden" name="mode" value='save'>
        <input type="hidden" id="code" name="code" value='<?=$code?>'>
        <input type="hidden" id="price_prev" name="price_prev">
        <input type="hidden" id="leader" name="leader" value="<?=$leader?>">
        <input type="hidden" id="apis" name="apis" value="<?=$apis?>">
        <input type="hidden" id="people" name="people" value="<?=$people?>">
        <input type="hidden" id="people_cancel" name="people_cancel" value="<?=$bit_cancel?>">
        <input type="hidden" id="bit_birth_mode" name="bit_birth_mode" value="<?=$birth_mode?>">

        <tr><td colspan="21"  bgcolor='#5E90AE' height=2></td></tr>
        <tr align=center height=25 bgcolor="#F7F7F6">
        <th class="subject" >고객명</th>
        <th class="subject" ></th>
        <th class="subject apis1" >성별</th>
        <th class="subject apis1" >영문명</th>
        <th class="subject apis1" >주민번호</th>
        <th class="subject apis1" >여권번호</th>
        <th class="subject apis1" >이티켓</th>
        <th class="subject apis1" >유효기간</th>
        <th class="subject apis1" >연락처</th>
        <th class="subject apis1" >스카이패스</th>
        <!-- <th class="subject" ></th> -->
        <th class="subject apis2" >판매가</th>
        <th class="subject apis2 blueapis2" >출금액1<br/>(항공)</th>
        <th class="subject apis2" >출금일1</th>
        <th class="subject apis2 blue" >출금액2<br/>(지상비)</th>
        <th class="subject apis2" >출금일2</th>
        <th class="subject apis2 blue" >출금액3<br/>(고객환불)</th>
        <th class="subject apis2" >출금일3</th>
        <th class="subject apis2" >출금메모</th>
        <th class="subject apis2 red" >입금액1<br/>(계약금)</th>
        <th class="subject apis2" >입금일1</th>
        <th class="subject apis2 red" >입금액2<br/>(잔금)</th>
        <th class="subject apis2" >입금일2</th>
        <th class="subject apis2 red" >입금액3<br/>(거래처환불)</th>
        <th class="subject apis2" >입금일3</th>
        <th class="subject apis2" >입금메모</th>
        <th class="subject apis2" >잔액</th>
        <th class="subject apis2" >보험</th>
        <th class="subject apis3" >취소</th>
        <th class="subject apis2" ></th>
        </tr>
        <tr><td colspan="21" class="tblLine"></td></tr>

        <?
        $sum1=0;
        $sum2=0;
        $sum3=0;
        $sum4=0;
        $sum5=0;
        $sum6=0;
        $sum7=0;
        $sum8=0;

        $code = $_GET[code];

        $apis_filter="";


        // $sql = "
        //     select
        //         *
        //     from $table
        //     where
        //         code=$code
        //         and bit=1
        //     order by seq asc
        // ";
        $sql = "
            select
                a.*
            from $table as a left join cmp_reservation as b
            on a.code=b.code
            where
                a.code=$code
                and a.bit=1
                and b.cp_id='$CP_ID'
            order by a.seq asc
        ";

        //if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);}
        list($rows) = $dbo->query($sql);
        //if($debug) checkVar($rows.mysql_error(),$sql);
        $rows_not_cancel = $rows-$bit_cancel;
        //checkVar("rows_not_cancel",$rows_not_cancel);

        $bit_id_nos=array();
        //checkVar($rows,$people);
        //for($j=1; $j <= $people; $j++){
        if($rows<$people) $rows=$people;
        if($rows_not_cancel < $_GET[people]) $rows=$_GET[people]+$bit_cancel;


        for($j=1; $j <= $rows; $j++){

            $rs=$dbo->next_record();

            if($rs[name] && ($rs[rn] || $rs[passport_no])){
                $rt = find_rn_customer($rs[name],$rs[passport_no],$rs[rn]);
                if(!$rs[passport_no]) $rs[passport_no] = $rt[passport_no];
                if(!$rs[rn]) $rs[rn] = $rt[rn];
                if(!$rs[passport_limit]) $rs[passport_limit] = $rt[passport_limit];
            }

            $bit_id_nos[]=$rs[id_no];

            if($rs[rn]){
            $aes = new AES($rs[rn], $inputKey, $blockSize);
            $dec=$aes->decrypt();
            $rs[rn] = $dec;
            }

            if($rs[passport_no]){
            $rs[passport_no_] = $rs[passport_no];
            $aes = new AES($rs[passport_no], $inputKey, $blockSize);
            $dec=$aes->decrypt();
            $rs[passport_no] = $dec;
            }

            $limit_color = "blank";
            if($rs[passport_limit]) $limit_color = chk_limit($rs[passport_limit],6);

            $id_code = $code . "_" . $j;
            $id_code2 = $code . "_" . $rs[id_no];
            //$sql2 = "select * from cmp_passport where passport_no='$rs[passport_no_]' and passport_no<>''";
            $sql2 = "select * from cmp_passport where id_code='$id_code' and cp_id='$CP_ID' limit 1";
            list($photo_rows) = $dbo2->query($sql2);
            $sql2 = "select * from cmp_files where id_code='${id_code2}_1'";
            list($photo_rows2) = $dbo2->query($sql2);
            //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@") && $photo_rows2) checkVar($photo_rows2,$sql2);
            $sql2 = "select * from cmp_files where id_code='${id_code2}_2'";
            list($photo_rows3) = $dbo2->query($sql2);
            //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@") && $photo_rows3) checkVar($photo_rows3,$sql2);
            $sql2 = "select * from cmp_files where id_code='${id_code2}_4'";
            list($photo_rows4) = $dbo2->query($sql2);
            //if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@") && $photo_rows4) checkVar($photo_rows4,$sql2);

            $rn_css = (!$rs[rn] || rn_check($rs[rn]))? "":"background-color:#ffcece;";

            $rs[passport_no_] = str_replace("+","@",$rs[passport_no_]);


            if($j==1){
                if(!$rs[name] && !$rs[name_eng] && !$rs[passport_no] && !$rs[birth] && !$rs[rn]){
                    $rs[name] = $leader;
                    $rs[phone] = $leader_phone;
                    $bit_default=1;
                }
            }


            if($birth_mode){
                $bit_birth_hide=1;
                if($rs[rn]){
                    $bit_birth_hide=(strstr($birth,substr($rs[rn],0,6)))?0:1;
                    //if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar("$birth / $rs[rn]",$bit_birth_hide);}
                }
            }

        ?>
        <tr height="35" align='center' onMouseOver="this.style.backgroundColor='#EEEEFF'" onMouseOut="this.style.backgroundColor='#FFFFFF'" class="bc_<?=$rs[bit_cancel]?> <?=($bit_birth_hide)?'hide':''?>">
            <td class="">
                <input type="hidden" name="id_no[]" id="id_no_<?=$j?>" value="<?=$rs[id_no]?>">
                <input type="hidden" name="id[]" id="id_<?=$j?>" value="<?=$rs[id]?>">
                <input type="hidden" name="price_air_old[]" id="price_air_old<?=$j?>" value="<?=$rs[price_air]?>">
                <input type="hidden" name="price_land_old[]" id="price_land_old<?=$j?>" value="<?=$rs[price_land]?>">
                <input type="hidden" name="price_refund_old[]" id="price_refund_old<?=$j?>" value="<?=$rs[price_refund]?>">
                <input type="hidden" name="passport_no_b_<?=$j?>" id="passport_no_b_<?=$j?>" value="<?=$rs[passport_no_]?>">
                <input type="text" name="name[]" id="name_<?=$j?>" value="<?=$rs[name]?>" size="8" maxlength="30" class="box" />
            </td>
            <td class=""><span class="btn_pack medium bold"><a href="javascript:find(<?=$j?>)">검색</a></span></td>
            <td class="apis1"><select name="sex[]" id="sex_<?=$j?>"><?=option_str("M,F","M,F",$rs[sex])?></select></td>
            <td class="apis1"><input type="text" name="name_eng[]" id="name_eng_<?=$j?>" value="<?=$rs[name_eng]?>" size="20" maxlength="30" class="box" /></td>
            <td class="apis1"><input type="text" name="rn[]" id="rn_<?=$j?>" value="<?=$rs[rn]?>" size="15" maxlength="14" class="box c rn" style="<?=$rn_css?>"/></td>
            <td class="apis1">
                <table>
                    <tr>
                        <td><input type="text" name="passport_no[]" id="passport_no_<?=$j?>" value="<?=$rs[passport_no]?>" size="12" maxlength="30" class="box c" /></td>
                        <td>
                            <a href="javascript:pop_passport(<?=$j?>)"><img id="plink_<?=$j?>" src="/renew/images/ez_board/ico_file0<?=($photo_rows)?'3':'2'?>.gif"></a>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="apis1">
                <a href="javascript:pop_files(4,'<?=$rs[id_no]?>','<?=$j?>')"><img id="pfile4_<?=$j?>" src="/renew/images/ez_board/ico_file0<?=($photo_rows4)?'3':'2'?>.gif"></a>
            </td>
            <td class="apis1"><input type="text" name="passport_limit[]" id="passport_limit_<?=$j?>" value="<?=$rs[passport_limit]?>" size="11" maxlength="30" class="box c passport" onchange="passport_limit('passport_limit_<?=$j?>',this.value)"  style="color:<?=$limit_color?>"/></td>
            <td class="apis1"><input type="text" name="phone[]" id="phone_<?=$j?>" value="<?=$rs[phone]?>" size="13" maxlength="30" class="box cell" onchange="cell('phone_<?=$j?>',this.value)" /></td>
            <td class="apis1"><input type="text" name="skypass[]" id="skypass_<?=$j?>" value="<?=$rs[skypass]?>" size="13" maxlength="30" class="box" onchange="cell('skypass_<?=$j?>',this.value)" /></td>
            <!-- <td><span class="btn_pack small bold"><a href="javascript:find2(<?=$j?>)"> 검색 </a></span></td> -->

            <td class="apis2"><input type="text" name="price[]" id="price_<?=$j?>" value="<?=nf($rs[price])?>" size="8" maxlength="11" class="box numberic price" onkeyup="num('price_<?=$j?>',this.value)"/></td>
            <td class="apis2">

                <table>
                    <tr>
                        <td>
                            <input type="text" name="price_air[]" id="price_air_<?=$j?>" value="<?=nf($rs[price_air])?>" size="8" maxlength="11" class="blue box numberic" onkeyup="num('price_air_<?=$j?>',this.value)" onclick="get_bank_data('o','price_air_<?=$j?>',<?=$j?>,1)"/>

                            <input type="hidden" id="bank_out1_id_<?=$j?>" name="bank_out1_id[]" value="<?=$rs[bank_out1_id]?>">
                        </td>
                        <td><a href="javascript:pop_files(1,'<?=$rs[id_no]?>','<?=$j?>')"><img id="pfile1_<?=$j?>" src="/renew/images/ez_board/ico_file0<?=($photo_rows2)?'3':'2'?>.gif"></a></td>
                    </tr>
                </table>


            </td>
            <td class="apis2"><input type="text" name="date_out[]" id="date_out_<?=$j?>" value="<?=$rs[date_out]?>" size="9" maxlength="10" class="box dateinput" onkeyup="num('date_out_<?=$j?>',this.value)"/><input type="hidden" name="date_out_old[]" value="<?=$rs[date_out]?>"></td>

            <td class="apis2">
                <table>
                    <tr>
                        <td>
                            <input type="text" name="price_land[]" id="price_land_<?=$j?>" value="<?=nf($rs[price_land])?>" size="8" maxlength="11" class="blue box numberic" onkeyup="num('price_land_<?=$j?>',this.value)" onclick="get_bank_data('o','price_land_<?=$j?>',<?=$j?>,2)"/>

                            <input type="hidden" id="bank_out2_id_<?=$j?>" name="bank_out2_id[]" value="<?=$rs[bank_out2_id]?>">
                        </td>
                        <td><a href="javascript:pop_files(2,'<?=$rs[id_no]?>','<?=$j?>')"><img id="pfile2_<?=$j?>" src="/renew/images/ez_board/ico_file0<?=($photo_rows3)?'3':'2'?>.gif"></a>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="apis2"><input type="text" name="date_out2[]" id="date_out2_<?=$j?>" value="<?=$rs[date_out2]?>" size="9" maxlength="10" class="box dateinput" onkeyup="num('date_out2_<?=$j?>',this.value)"/><input type="hidden" name="date_out2_old[]" value="<?=$rs[date_out2]?>"></td>


            <td class="apis2">
                <table>
                    <tr>
                        <td>
                            <input type="text" name="price_refund[]" id="price_refund_<?=$j?>" value="<?=nf($rs[price_refund])?>" size="8" maxlength="11" class="blue box numberic" onkeyup="num('price_refund_<?=$j?>',this.value)" onclick="get_bank_data('o','price_refund_<?=$j?>',<?=$j?>,3)"/>

                            <input type="hidden" id="bank_out3_id_<?=$j?>" name="bank_out3_id[]" value="<?=$rs[bank_out3_id]?>">
                        </td>
                        <td><a href="javascript:pop_files(3,'<?=$rs[id_no]?>','<?=$j?>')"><img id="pfile3_<?=$j?>" src="/renew/images/ez_board/ico_file0<?=($photo_rows4)?'3':'2'?>.gif"></a></td>
                    </tr>
                </table>
            </td>
            <td class="apis2"><input type="text" name="date_out3[]" id="date_out3_<?=$j?>" value="<?=$rs[date_out3]?>" size="9" maxlength="10" class="box dateinput" onkeyup="num('date_out3_<?=$j?>',this.value)"/><input type="hidden" name="date_out3_old[]" value="<?=$rs[date_out3]?>"></td>




            <!-- 출금메모 -->
            <td class="apis2"><input type="text" name="memo_out[]" id="memo_out_<?=$j?>" value="<?=$rs[memo_out]?>" size="20" maxlength="200" class="box" onkeyup="num('memo_out_<?=$j?>',this.value)"/></td>

            <td class="apis2">
                <input type="text" name="price_prev[]" id="price_prev_<?=$j?>" value="<?=nf($rs[price_prev])?>" size="8" maxlength="11" class="red box numberic onlyceo" onkeyup="num('price_prev_<?=$j?>',this.value)" onclick="get_bank_data('i','price_prev_<?=$j?>',<?=$j?>,1)"/>
                <input type="hidden" id="bank_in1_id_<?=$j?>" name="bank_in1_id[]" value="<?=($rs[price_prev])?$rs[bank_in1_id]:""?>">
            </td>
            <td class="apis2">
                <input type="text" name="date_in[]" id="date_in_<?=$j?>" value="<?=$rs[date_in]?>" size="9" maxlength="10" class="box dateinput onlyceo" onkeyup="num('date_in_<?=$j?>',this.value)"/>
            </td>
            <td class="apis2">
                <input type="text" name="price_prev2[]" id="price_prev2_<?=$j?>" value="<?=nf($rs[price_prev2])?>" size="8" maxlength="11" class="red box numberic onlyceo" onkeyup="num('price_prev2_<?=$j?>',this.value)" onclick="get_bank_data('i','price_prev2_<?=$j?>',<?=$j?>,2)"/>
                <input type="hidden" id="bank_in2_id_<?=$j?>" name="bank_in2_id[]" value="<?=($rs[price_prev2])?$rs[bank_in2_id]:""?>">
            </td>
            <td class="apis2">
                <input type="text" name="date_in2[]" id="date_in2_<?=$j?>" value="<?=$rs[date_in2]?>" size="9" maxlength="10" class="box dateinput onlyceo" onkeyup="num('date_in2_<?=$j?>',this.value)"/>
            </td>
            <td class="apis2">
                <input type="text" name="price_prev3[]" id="price_prev3_<?=$j?>" value="<?=nf($rs[price_prev3])?>" size="8" maxlength="11" class="red box numberic onlyceo" onkeyup="num('price_prev3_<?=$j?>',this.value)" onclick="get_bank_data('i','price_prev3_<?=$j?>',<?=$j?>,3)"/>
                <input type="hidden" id="bank_in3_id_<?=$j?>" name="bank_in3_id[]" value="<?=($rs[price_prev3])?$rs[bank_in3_id]:""?>">
            </td>
            <td class="apis2">
                <input type="text" name="date_in3[]" id="date_in3_<?=$j?>" value="<?=$rs[date_in3]?>" size="9" maxlength="10" class="box dateinput onlyceo" onkeyup="num('date_in3_<?=$j?>',this.value)"/>
            </td>


            <!-- 입금메모 -->
            <td class="apis2"><input type="text" name="memo_in[]" id="memo_in_<?=$j?>" value="<?=$rs[memo_in]?>" size="20" maxlength="200" class="box" onkeyup="num('memo_in_<?=$j?>',this.value)"/></td>

            <td class="apis2"><input type="text" name="price_last[]" id="price_last_<?=$j?>" value="<?=nf($rs[price_last])?>" size="8" maxlength="11" class="box numberic" style="border:0;" readonly  onkeyup="num('price_last_<?=$j?>',this.value)"/></td>
            <td class="apis2"><input type="checkbox" name="insure[]" id="insure_<?=$j?>" value="1" <?=($rs[insure])?"checked":""?> style="border:0;"/></td>
            <!-- <td class="apis2"><input type="text" name="memo[]" id="memo_<?=$j?>" value="<?=$rs[memo]?>" size="9" maxlength="30" class="box" /></td> -->


            <td class="apis3">
                <input type="hidden" name="bit_cancel[]" id="bit_cancel_<?=$j?>" value="<?=($rs[bit_cancel])?1:0?>">
                <input type="hidden" name="bit_cancel_date[]" id="bit_cancel_date_<?=$j?>" value="<?=$rs[bit_cancel_date]?>">
                <input type="checkbox" value="1" <?=($rs[bit_cancel])?'checked':""?> onclick="if(this.checked==true){$('#bit_cancel_<?=$j?>').val(1);}else{$('#bit_cancel_<?=$j?>').val(0);} $('#bit_cancel_date_<?=$j?>').val('<?=date('Y/m/d')?>')">
            </td>

            <td class="apis2"><span class="btn_pack small bold"><a href="#" onClick="drop(<?=$j?>)">X</a></span></td>
        </tr>
        <tr><td colspan="21" class="tblLine"></td></tr>
        <?

            if($apis!=3){
                $sum1+=$rs[price];
                $sum2+=$rs[price_air];
                $sum3+=$rs[price_land];
                $sum4+=$rs[price_prev];
                $sum5+=$rs[price_last];
                $sum6+=$rs[price_prev2];
                $sum7+=$rs[price_refund];
                $sum8+=$rs[price_prev3];
            }else{ //취소인 경우
                if($rs[bit_cancel]){
                    $sum1+=$rs[price];
                    $sum2+=$rs[price_air];
                    $sum3+=$rs[price_land];
                    $sum4+=$rs[price_prev];
                    $sum5+=$rs[price_last];
                    $sum6+=$rs[price_prev2];
                    $sum7+=$rs[price_refund];
                    $sum8+=$rs[price_prev3];
                }
            }

        }
        ?>
        </form>

        <tr class="apis2">
            <th class="sum"></th>
            <th class="sum"></th>
            <th height="30"><span class="sr"><?=nf($sum1)?></th>
            <th><span class="sr blue"><?=nf($sum2)?></th>
            <th class="sum"></th>
            <th><span class="sr red"><?=nf($sum3)?></th>
            <th class="sum"></th>
            <th><span class="sr red"><?=nf($sum7)?></th>
            <th class="sum"></th>
            <th class="sum"></th>
            <th><span class="sr blue"><?=nf($sum4)?></th>
            <th class="sum"></th>
            <th><span class="sr red"><?=nf($sum6)?></th>
            <th class="sum"></th>
            <th><span class="sr red"><?=nf($sum8)?></th>
            <th class="sum"></th>
            <th class="sum"></th>
            <th><span class="sr"><?=nf($sum5)?></span></th>
            <th class="sum"></th>
            <th class="sum"></th>
        <tr><td colspan="21" class="tblLine"></td></tr>
        </tr>

    </table>


          <br>
          <!-- Button Begin---------------------------------------------->
          <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
             <tr>
              <td width="60%" align="left">

              </td>
              <td align="right" style="padding-right:23px">
                <span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span>&nbsp;&nbsp;&nbsp;
                <span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
              </td>
            </tr>
          </table>
          <!-- Button End------------------------------------------------>


</div>




    <form name="fmData2" method="post" enctype="multipart/form-data" action="<?=SELF?>">
    <input type="hidden" name="mode" id="mode" value='drop'>
    <input type="hidden" name="code" id="code" value='<?=$code?>'>
    <input type="hidden" name="id_no" id="id_no">
    <input type="hidden" name="memo" id="memo">
    <input type="hidden" name="apis" id="apis" value="<?=$apis?>">
    </form>
    <!--내용이 들어가는 곳 끝-->


<?
$sql = "
        delete from cmp_people
        where
            bit=0
            and code=$code
            and code<>''
    ";
//list($rows) = $dbo->query($sql);
//checkVar($rows.mysql_error(),$sql);

// if(is_array($bit_id_nos)){
//     $bit_id_no="";
//     foreach ($bit_id_nos as $key => $value) {
//         if($value) $bit_id_no.=",$value";
//     }
//     $bit_id_no = substr($bit_id_no,1);
//     if($bit_id_no){
//         $sql = "select * from cmp_people where code=$code and id_no not in ($bit_id_no) order by seq asc";
//         list($rows)=$dbo->query($sql);
//         if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar($rows.mysql_error(),$sql);}
//         while($rs=$dbo->next_record()){
//             checkVar($rs[bit],$rs[name]);
//         }
//     }
// }
?>




<!-- Copyright -->
<?include_once("../bottom_min.html");?>