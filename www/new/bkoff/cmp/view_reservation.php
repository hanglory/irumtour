<?
include_once("../include/common_file.php");

chk_power($_SESSION["sessLogin"]["proof"],"고객별예약정보관리대장");

####기초 정보
$filecode = substr(SELF,5,-4);
$table = "cmp_reservation";
$MENU = "cmp_basic";
$TITLE = "예약정보";
$file_rows = 1; //파일 업로드 갯수


#### staff
$sql = "select * from cmp_staff where bit_hide<>1 order by name asc";
$dbo->query($sql);
while($rs=$dbo->next_record()){
    $STAFF.=",$rs[name] ($rs[id])";
}
if(strstr($_SESSION["sessLogin"]["staff_type"],"partner")) $STAFF =$_SESSION[sessLogin][name] ." (". $_SESSION[sessLogin][id] .")";




#### operation
if ($mode=="save"){

    $staff=$sessLogin[name] . " (". $sessLogin[id] . ")";

    $reg_date = date('Y/m/d');
    $reg_date2 = date('H:i:s');

    $price = rnf($price);
    $payed_price = rnf($payed_price);
    $price_last = rnf($price_last);
    $price_prev = rnf($price_prev);
    $price_customer_input = rnf($price_customer_input);
    $price_tmp_output = rnf($price_tmp_output);
    //$price_last = $price-$price_customer_input;
    $memo_payment = $content;
    if($content) $bit_content = 1;

    $subject = ($subject)? $subject : $golf_name;

    $sqlInsert="
       insert into cmp_reservation (
          code,
          subject,
          golf_name,
          point_dep,
          golf_id_no,
          name,
          customer_type,
          phone,
          fax,
          email,
          phone2,
          zipcode,
          address,
          address2,
          view_path,
          main_staff,
          sub_staff,
          d_date,
          r_date,
          golf,
          price,
          price_customer_input,
          price_tmp_output,
          price_prev,
          price_last,
          pay_date,
          pay_check,
          pay_method,
          bit_tax,
          bit_cash,
          bit_sending,
          bit_sending2,
          air_info,
          people,
          memo,
          memo_payment,
          air_id_no,
          d_air_no,
          r_air_no,
          tour_date,
          tl,
          bsp,
          golf_ball,
          golf_ball2,
          air_cover,
          dollarbook,
          partner,
          plan_type,
          d_air_id_no,
          r_air_id_no,
          d_air_time1,
          d_air_time2,
          r_air_time1,
          r_air_time2,
          bit_ticket,
          bit_bizseat,
          payed_price,

            plan_add1_a,
            plan_add1_b,
            plan_add1_c,
            plan_add1_d,
            plan_add2_a,
            plan_add2_b,
            plan_add2_c,
            plan_add2_d,
            plan_add8_a,
            plan_add8_b,
            plan_add8_c,
            plan_add8_d,
            plan_add9_a,
            plan_add9_b,
            plan_add9_c,
            plan_add9_d,

          partner_staff,
          bit_content,
          cp_id,


        hotel_id_no,
        hotel2_id_no,
        hotel_name,
        hotel2_name,
        hotel,
        hotel2,
        ah1,
        ah2,


          reg_date,
          reg_date2
      ) values (
          '$code',
          '$subject',
          '$golf_name',
          '$point_dep',
          '$golf_id_no',
          '$name',
          '$customer_type',
          '$phone',
          '$fax',
          '$email',
          '$phone2',
          '$zipcode',
          '$address',
          '$address2',
          '$view_path',
          '$main_staff',
          '$sub_staff',
          '$d_date',
          '$r_date',
          '$golf',
          '$price',
          '$price_customer_input',
          '$price_tmp_output',
          '$price_prev',
          '$price_last',
          '$pay_date',
          '$pay_check',
          '$pay_method',
          '$bit_tax',
          '$bit_cash',
          '$bit_sending',
          '$bit_sending2',
          '$air_info',
          '$people',
          '$memo',
          '$memo_payment',
          '$air_id_no',
          '$d_air_no',
          '$r_air_no',
          '$tour_date',
          '$tl',
          '$bsp',
          '$golf_ball',
          '$golf_ball2',
          '$air_cover',
          '$dollarbook',
          '$partner',
          '$plan_type',
          '$d_air_id_no',
          '$r_air_id_no',
          '$d_air_time1',
          '$d_air_time2',
          '$r_air_time1',
          '$r_air_time2',
          '$bit_ticket',
          '$bit_bizseat',
          '$payed_price',

            '$plan_add1_a',
            '$plan_add1_b',
            '$plan_add1_c',
            '$plan_add1_d',
            '$plan_add2_a',
            '$plan_add2_b',
            '$plan_add2_c',
            '$plan_add2_d',
            '$plan_add8_a',
            '$plan_add8_b',
            '$plan_add8_c',
            '$plan_add8_d',
            '$plan_add9_a',
            '$plan_add9_b',
            '$plan_add9_c',
            '$plan_add9_d',

          '$partner_staff',
          '$bit_content',
          '$CP_ID',

            '$hotel_id_no',
            '$hotel2_id_no',
            '$hotel_name',
            '$hotel2_name',
            '$hotel',
            '$hotel2',
            '$ah1',
            '$ah2',

          '$reg_date',
          '$reg_date2'
    )";


    $sqlModify="
       update cmp_reservation set
          code = '$code',
          point_dep = '$point_dep',
          subject = '$subject',
          golf_name = '$golf_name',
          golf_id_no = '$golf_id_no',
          name = '$name',
          customer_type = '$customer_type',
          phone = '$phone',
          fax = '$fax',
          email = '$email',
          phone2 = '$phone2',
          zipcode = '$zipcode',
          address = '$address',
          address2 = '$address2',
          view_path = '$view_path',
          main_staff = '$main_staff',
          sub_staff = '$sub_staff',
          d_date = '$d_date',
          r_date = '$r_date',
          golf = '$golf',
          price = '$price',
          price_customer_input ='$price_customer_input',
          price_tmp_output ='$price_tmp_output',
          price_prev = '$price_prev',
          price_last = '$price_last',
          pay_date = '$pay_date',
          pay_check = '$pay_check',
          pay_method = '$pay_method',
          bit_tax = '$bit_tax',
          bit_cash = '$bit_cash',
          bit_sending = '$bit_sending',
          bit_sending2 = '$bit_sending2',
          air_info = '$air_info',
          people = '$people',
          tour_date = '$tour_date',
          tl='$tl',
          bsp='$bsp',
          golf_ball='$golf_ball',
          golf_ball2='$golf_ball2',
          air_cover='$air_cover',
          dollarbook='$dollarbook',
          air_id_no = '$air_id_no',
          d_air_no = '$d_air_no',
          r_air_no = '$r_air_no',
          partner = '$partner',
          memo = '$memo',
          plan_type = '$plan_type',
          d_air_id_no='$d_air_id_no',
          r_air_id_no='$r_air_id_no',
          d_air_time1 = '$d_air_time1',
          d_air_time2 = '$d_air_time2',
          r_air_time1 = '$r_air_time1',
          r_air_time2 = '$r_air_time2',
          bit_ticket = '$bit_ticket',
          bit_bizseat = '$bit_bizseat',
          payed_price = '$payed_price',

            plan_add1_a='$plan_add1_a',
            plan_add1_b='$plan_add1_b',
            plan_add1_c='$plan_add1_c',
            plan_add1_d='$plan_add1_d',
            plan_add2_a='$plan_add2_a',
            plan_add2_b='$plan_add2_b',
            plan_add2_c='$plan_add2_c',
            plan_add2_d='$plan_add2_d',
            plan_add8_a='$plan_add8_a',
            plan_add8_b='$plan_add8_b',
            plan_add8_c='$plan_add8_c',
            plan_add8_d='$plan_add8_d',
            plan_add9_a='$plan_add9_a',
            plan_add9_b='$plan_add9_b',
            plan_add9_c='$plan_add9_c',
            plan_add9_d='$plan_add9_d',

            hotel_id_no = '$hotel_id_no',
            hotel2_id_no = '$hotel2_id_no',
            hotel_name = '$hotel_name',
            hotel2_name = '$hotel2_name',
            hotel = '$hotel',
            hotel2 = '$hotel2',
            ah1 = '$ah1',
            ah2 = '$ah2',


            partner_staff='$partner_staff',
            bit_content='$bit_content',
          memo_payment = '$memo_payment'
       where id_no='$id_no'
    ";

    if($id_no){
        $sql =$sqlModify;
        $url = "view_${filecode}.php?id_no=$id_no&ctg1=$ctg1";

    }else{
        $sql =$sqlInsert;
        $url = "list_${filecode}.php?ctg1=$ctg1";
    }

    //if(strstr("@14.37.242.84@221.154.216.133@","@".$_SERVER["REMOTE_ADDR"]."@")){checkVar(mysql_error(),$sql);exit;}

    if($dbo->query($sql)){

        /*
        //qna 자동복사
        $sql = "select * from cmp_qna where name='$name' and phone='$phone' ";
        list($rows2) = $dbo->query($sql);
        if(!$rows2){
            $sql="
               insert into cmp_qna (
                  qdate,
                  name,
                  phone,
                  content,
                  reg_date,
                  reg_date2
              ) values (
                  '$reg_date',
                  '$name',
                  '$phone',
                  '$memo',
                  '$reg_date',
                  '$reg_date2'
            )";
            $dbo->query($sql);
        }
        */

        /*
        //인원 정보 입력
        $sql = "select count(*) as people from cmp_people where code=$code";
        $dbo->query($sql);
        $rs=$dbo->next_record();

        $sql = "update cmp_reservation set people=$rs[people] where code=$code";
        $dbo->query($sql);
        */

        //If($link){redirect2($link);exit;}

        If($id_no) echo"<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();location.href='$url'</script>";
        Else echo "<script type='text/javascript'>alert('저장하였습니다.');opener.location.reload();self.close()</script>";

    }else{
        checkVar(mysql_error(),$sql);
    }
    exit;

}elseif ($mode=="sum"){

    $price = rnf($price);
    $price_prev = rnf($price_prev);
    $price_customer_input = rnf($price_customer_input);

    $sum = $price -  ($price_prev+$price_customer_input);
    $sum_ = nf($sum);
    $price_prev_ = nf($price_prev);
    $price_customer_input_ = nf($price_customer_input);
    echo"
        <script>
            parent.document.getElementById('price_prev').value='$price_prev_';
            parent.document.getElementById('price_customer_input').value='$price_customer_input_';
            parent.document.getElementById('price_last').value='$sum_';
        </script>
    ";

    exit;
}elseif ($mode=="drop"){

    for($i = 0; $i < count($check);$i++){

        $sql = "delete from $table where code = $check[$i]";
        $dbo->query($sql);

        $sql = "delete from cmp_people where code = $check[$i]";
        $dbo->query($sql);
    }
    redirect2("list_${filecode}.php?ctg1=$ctg1");exit;

}elseif($mode=="golf"){

    $sql = "select * from cmp_golf where name like '%$golf%' order by nation asc,city asc, name asc";
    list($rows) = $dbo->query($sql);
    while($rs=$dbo->next_record()){
        $KEY .= ",$rs[nation] > $rs[city] > $rs[name]";
        $VAL .= ",".$rs[id_no];
    }
    $str = ($rows)? "선택하세요":"검색된 상품명이 없습니다.";
    echo "
        <select name='golf_tmp' id='golf_tmp' onchange=\"set_golf(this.options[this.selectedIndex].text,this.value)\">
        ";
            echo option_str($str.$KEY,$VAL);
    echo "
        <option value='$golf'>$golf</option>
        </select>
    ";
    exit;

}elseif($mode=="price"){

    $price_customer_input = nf(rnf($price_customer_input));
    $price_last = nf(rnf($price) - rnf($price_customer_input));
    echo "
        <script>
            parent.document.getElementById('price_customer_input').value='$price_customer_input';
            parent.document.getElementById('price_last').value='$price_last';
        </script>
    ";
    exit;

}elseif($mode=="partner"){

    $dbo->query("select * from cmp_golf where id_no=$id_no");
    $rs= $dbo->next_record();

    echo "
        <script>
            parent.document.getElementById('partner').value='$rs[partner]';
        </script>
    ";
    exit;

}elseif($mode=="close"){

    $dbo->query("update cmp_reservation set bit_close=$bit_close where id_no=$id_no");
    $rs= $dbo->next_record();
    back();
    exit;

}elseif($mode=="pay_date_check"){

    if($d_date>'1950/01/01'){
        $pay_date = date("Y/m/d",strtotime($d_date." -14 day"));
        echo "
            <script>
                parent.document.getElementById('pay_date').value='$pay_date';
            </script>
        ";
    }else{
        echo "
            <script>
                alert('날짜를 정확히 입력해 주세요.');
                parent.document.getElementById('d_date').value='';
                parent.document.getElementById('d_date').focus();
            </script>
        ";
    }
    exit;

}else{

    //거래처 불러오기
    $PARTNERS = "";
    $sql = "select * from cmp_partner order by binary company asc";
    $dbo->query($sql);
    //if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql);
    while($rs= $dbo->next_record()){
        $PARTNERS .= ",$rs[company]";
    }
    $PARTNERS = substr($PARTNERS,1);

    $sql =($code && !$_GET[id_no])? "select * from $table where code=$code" : "select * from $table where id_no=$id_no";
    $dbo->query($sql);
    $rs= $dbo->next_record();
    $code=$rs[code];

    /*인원을 줄이는 경우 해당 줄어드는 만큼 인원 비활성화s*/
    $j=0;
    $p = $rs[people]+$rs[people_cancel];
    $sql2 = "select * from cmp_people where code='$rs[code]' and bit=1 order by id_no asc limit $p,100";
    $dbo2->query($sql2);
    while($rs2=$dbo2->next_record()){
        $j++;
        $sql3 = "update cmp_people set bit=0 where code='$rs[code]' and id_no=$rs2[id_no] and bit=1";
        $dbo3->query($sql3);
    }
    /*인원을 줄이는 경우 해당 줄어드는 만큼 인원 비활성화f*/


    $rs[price_last] = $rs[price] - $rs[price_prev];

    $rs[price] = nf($rs[price]);
    $rs[price_prev] = nf($rs[price_prev]);
    $rs[price_customer_input] = nf($rs[price_customer_input]);
    $rs[price_tmp_output] = nf($rs[price_tmp_output]);
    $rs[price_last] = nf($rs[price_last]);
    $arr  =explode(">",$rs[golf_name]);
    $rs[golf] = trim($arr[count($arr)-1]);

    //if(!$rs[profit_total]){
        $sql2 = "
            select
                sum(price) as price,
                sum(price_prev+price_prev2+price_prev3) as prev,
                sum(price_air+price_land+price_refund) as payed
            from cmp_people
            where code=$code and bit=1
            ";
        $dbo2->query($sql2);
        $rs2=$dbo2->next_record();
        $price=nf($rs2[price]);
        $price_total_payed_ =@($rs2[price] - $rs2[payed]);
        $price_one_payed_= @(($rs2[prev] - $rs2[payed])/$rs[people]);

        $sql2 = "update cmp_reservation set profit_total='$price_total_payed_',profit_one='$price_one_payed_' where code=$code  ";
        $dbo2->query($sql2);
        $rs[profit_total] =$price_total_payed_;
        $rs[profit_one] =$price_one_payed_;
        //if($REMOTE_ADDR=="106.246.54.27") checkVar(mysql_error(),$sql2);

        $rs[price] = nf($rs2[price]);//판매가
        $rs[price_last] = nf(($rs2[price]-$rs2[prev])-$rs[payed_price]);//잔금
        $rs[price_prev] = nf($rs2[prev]);//계약금

        $gain_all = $rs2[prev]-$rs2[payed];//총수익
        $gain_one = @($gain_all/$rs[people]);//1인수익

    //}

}

//취소자 여부 확인
$sql3 = "select count(*) as cnt from cmp_people where code=$rs[code] and bit=1 and bit_cancel=1";
$dbo3->query($sql3);
$rs3=$dbo3->next_record();
$bit_cancel=$rs3[cnt];


//대표자 전화번호 고객명단관리에 자동 저장
$sql3 = "select * from cmp_people where code=$rs[code] and bit=1 and name='$rs[name]' order by seq asc limit 1";
list($rows1) = $dbo3->query($sql3);
$rs3=$dbo3->next_record();
$sql3 = "select * from cmp_customer where name='$rs3[name]' and rn='$rs3[rn]' and phone=''";
list($rows2) = $dbo3->query($sql3);
if($rows1 && $rows2){
    $sql3 = "update cmp_customer set phone='$rs[phone]' where name='$rs3[name]' and rn='$rs3[rn]' and phone=''";
    list($rows3) = $dbo3->query($sql3);
}




$code = ($code)? $code : getUniqNo();
$rs[main_staff] =($rs[main_staff])?$rs[main_staff]:"$sessLogin[name] ($sessLogin[id])";


if(!$rs[pay_date] && $rs[d_date] &&  $rs[d_date]>'1950/01/01'){
    $rs[pay_date] = date("Y/m/d",strtotime($rs[d_date]." -14 day"));
}


$rs[subject] = ($rs[subject])? $rs[subject] : $rs[golf_name];



if(!$rs[hotel_id_no] && $rs[golf_id_no]){
    $sql3 = "
        select
            *
        from cmp_golf
        where id_no=$rs[golf_id_no]    
        ";
    $dbo3->query($sql3);
    $rs3=$dbo3->next_record();
    if($rs3[hotel_id_no]){
        $sql3 = "
            select
                *
            from cmp_hotel
            where id_no=$rs3[hotel_id_no]    
            ";
        $dbo3->query($sql3);
        $rs3=$dbo3->next_record();        
        $rs[hotel_id_no]=$rs3[id_no];
        $rs[hotel_name]=$rs3[name];
        $rs[hotel]=$rs3[name];
        $rs[ah1]=$rs3[ah];
    }
    if($rs3[hotel2_id_no]){
        $sql3 = "
            select
                *
            from cmp_hotel
            where id_no=$rs3[hotel2_id_no]    
            ";
        $dbo3->query($sql3);
        $rs3=$dbo3->next_record();        
        $rs[hotel2_id_no]=$rs3[hotel_id_no];
        $rs[hotel2_name]=$rs3[name];
        $rs[hotel2]=$rs3[name];
        $rs[ah2]=$rs3[ah];
    }    
}



/*생일고객 확인*/
$chk_birth = check_recver_birth($rs[d_date],$rs[r_date],$rs[code]);
?>
<?include("../top_min.html");?>
<script language="JavaScript">
function chkForm()
{
    var fm = document.fmData;

    if(check_blank(fm.name,'대표자명을',0)=='wrong'){return }

    /*
    if(check_blank(fm.phone,'대표자 핸드폰을',0)=='wrong'){return }
    if(check_select(fm.view_path,'경로')=='wrong'){return }
    if(check_select(fm.customer_type,'성향')=='wrong'){return }
    if(check_blank(fm.pay_date,'잔금입금일을',0)=='wrong'){return }
    if(check_blank(fm.tl,'TL을',0)=='wrong'){return }
    if(check_select(fm.pay_method,'결제수단을')=='wrong'){return }
    */


    oEditors.getById["content"].exec("UPDATE_CONTENTS_FIELD", []);  // 에디터의 내용이 textarea에 적용됩니다.

    try {
        elClickedObj.form.submit();
    } catch(e) {}

    if(document.getElementById("content").value==""){
        alert('본문을 입력하세요');
        return;
    }

    fm.submit();

}

function find_golf(){
    newWin('pop_find_goods.php',800,400,1,1,'','goods');
}
function find_hotel(bit){
    newWin('pop_find_hotel.php?mode2=reserv&bit='+bit,800,400,1,1,'','goods');
}
function clear_hotel(bit){
    if(bit==2){
        $("#ah2").val('');
    }else{
        $("#ah1").val('');
    }
    $("#hotel"+bit+"_id_no").val('');
    $("#hotel"+bit+"_name").val('');
    $("#hotel"+bit).val('');
}


function set_golf(k,v){
    $("#golf_name").val(k);
    $("#golf_id_no").val(v);

    actarea.location.href="<?=SELF?>?mode=partner&id_no="+v;
}

function pop_win(){
    var fm = document.fmData;
    if(check_blank(fm.name,'대표자명을',0)=='wrong'){return }
    //if(check_blank(fm.people,'총인원을',0)=='wrong'){return }
    //if(fm.people.value==0){alert('총인원을 입력하세요.');fm.people.value='';fm.people.select();return }
    newWin('pop_reservation.php?code=<?=$code?>&people='+document.getElementById('people').value+'&leader='+document.getElementById('name').value+'&tour_date='+document.getElementById('tour_date').value,1400,500,1,1,'','');
}

function pop_win_apis(apis){
    var fm = document.fmData;
    var win_width = (apis==1 || apis==100)? 1100:1700;
    if(check_blank(fm.name,'대표자명을',0)=='wrong'){return }
    //if(check_blank(fm.people,'총인원을',0)=='wrong'){return }
    //if(fm.people.value==0){alert('총인원을 입력하세요.');fm.people.value='';fm.people.select();return }

    newWin('pop_reservation_apis.php?code=<?=$code?>&apis='+apis+'&people='+document.getElementById('people').value+'&leader='+document.getElementById('name').value+'&tour_date='+document.getElementById('tour_date').value+'&birth=<?=$chk_birth?>',win_width,500,1,1,'','');
}


function air_info(){
    var fm = document.fmData;
    var url ="pop_air.php";
    url +="?golf_id_no="+fm.golf_id_no.value;
    url +="&d_date="+fm.d_date.value;
    url +="&r_date="+fm.r_date.value;
    if(fm.golf_id_no.value==""){alert("상품명을 먼저 선택하세요.");return }
    newWin(url,1200,670,1,1,'','pop_air');
}

function air_info_api(bit){
    var fm = document.fmData;
    var url ="pop_air_cn.php";
    if(bit==2) url ="pop_air_ko.php";
    url +="?golf_id_no="+fm.golf_id_no.value;
    url +="&d_date="+fm.d_date.value;
    url +="&r_date="+fm.r_date.value;
    url +="&api=r";
    if(fm.golf_id_no.value==""){alert("상품명을 먼저 선택하세요.");return }
    newWin(url,1200,670,1,1,'','pop_air');
}

function get_price_last(){
    var price=$("#price").val();
    var price_prev=$("#price_prev").val();
    var price_customer_input=$("#price_customer_input").val();
    var url ="<?=SELF?>?mode=price";
    url += "&price=" + price;
    url += "&price_prev=" + price_prev;
    url += "&price_customer_input=" + price_customer_input;
    actarea.location.href=url;

}

function find(){

    var name = $("#name").val();
    if(name==""){alert("고객명을 입력하세요.");$("#name").focus();return;}

    newWin('pop_qnacustomer2.php?page=origin&div=2&name='+name,850,400,1,1,'','customer')

}

function plist(){
    newWin('list_output.php?target=name&keyword='+document.getElementById('name').value,1300,800,1,1);
}

function sum(){
    var price=$("#price").val();
    var price_prev=$("#price_prev").val();
    var price_customer_input=$("#price_customer_input").val();
    var url = "<?=SELF?>?mode=sum";
    url +="&price="+price;
    url +="&price_prev="+price_prev;
    url +="&price_customer_input="+price_customer_input;
    actarea.location.href=url;
}

function set_close(bit){
    var txt=(bit==1)? "마감":"마감해지";
    var url = "<?=SELF?>?mode=close";
    url += "&id_no=<?=$id_no?>";
    url += "&bit_close="+bit;
    if(confirm(txt+"하시겠습니까? \n\n아직 저장하지 않은 데이터가 있다면 먼저 정하신 후 "+txt+" 해주세요.")){
        location.href=url;
    }
}

function show_hide_air_view(bit){
    if(bit==true) $(".bx_air_view").show();
    else $(".bx_air_view").hide();
}

jQuery(function($){

    $(".num").keypress(function(event){
        if(event.which && (event.which < 48 || event.which > 57)){
            event.preventDefault();
        }
    });


    $("#price_customer_input").on("keyup",function(){
        get_price_last();
    });

    $("#price").css("border","0");
    $("#price_last").css("border","0");
    $("#price_prev").css("border","0");
    $("#price_customer_input").css("border","0");

    $('#name').keypress(function(e){
        if(e.which == 13) find();
    });

    $('#golf').keypress(function(e){
        if(e.which == 13) find_golf();
    });

    $("#price_prev").on("change",function(){
        sum();
    });

    $("#price_customer_input").on("change",function(){
        sum();
    });

    $( "#d_date" ).datepicker({
      dateFormat: "yy/mm/dd",
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 3,
      onClose: function( selectedDate ) {
        $( "#r_date" ).datepicker( "option", "minDate", selectedDate );
      }
    });
    $( "#r_date" ).datepicker({
      dateFormat: "yy/mm/dd",
      defaultDate: "+1w",
      changeMonth: true,
      numberOfMonths: 2,
      onClose: function( selectedDate ) {
        $( "#d_date" ).datepicker( "option", "maxDate", selectedDate );
      }
    });

    $("#phone").on("change",function(){
        actarea.location.href="../action.php?mode=cell&col=phone&cell="+$("#phone").val();
    });

    $("#d_date").on("change",function(){
        var url = "<?=SELF?>?mode=pay_date_check";
        url+="&d_date="+this.value;
        actarea.location.href=url;
    });

    $("#golf").css("border",0);

    $(".bx_air_view").hide();

    $("#phone").mask("000-0000-0000");

});
</script>
<style type="text/css">
.long_url{
    display: inline-block;
    width:300px;
}
</style>

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

    <!--내용이 들어가는 곳 시작-->

    <br>

    <table border="0" cellspacing="1" cellpadding="3" width="100%">
        <form name="fmData" method="post" enctype="multipart/form-data" action="<?=SELF?>">
        <input type="hidden" name="mode" value='save'>
        <input type="hidden" name="id_no" value='<?=$rs[id_no]?>'>
        <input type="hidden" name="code" value='<?=$code?>'>
        <input type="hidden" name="golf_name" id="golf_name" value='<?=$rs[golf_name]?>'>
        <input type="hidden" name="golf_id_no" id="golf_id_no" value='<?=$rs[golf_id_no]?>'>
        <input type="hidden" name="golf_id_no_old" id="golf_id_no_old" value='<?=$rs[golf_id_no]?>'>
        <input type="hidden" name="air_id_no" id="air_id_no" value='<?=$rs[air_id_no]?>'>
        <input type="hidden" name="d_air_no" id="d_air_no" value='<?=$rs[d_air_no]?>'>
        <input type="hidden" name="r_air_no" id="r_air_no" value='<?=$rs[r_air_no]?>'>
        <input type="hidden" name="address_old" id="address_old" value=''>

        <input type="hidden" name="d_air_time1" id="d_air_time1" value='<?=$rs[d_air_time1]?>'>
        <input type="hidden" name="d_air_time2" id="d_air_time2" value='<?=$rs[d_air_time2]?>'>
        <input type="hidden" name="r_air_time1" id="r_air_time1" value='<?=$rs[r_air_time1]?>'>
        <input type="hidden" name="r_air_time2" id="r_air_time2" value='<?=$rs[r_air_time2]?>'>

        <input type="hidden" name="d_air_id_no" id="d_air_id_no" value='<?=$rs[d_air_id_no]?>'>
        <input type="hidden" name="r_air_id_no" id="r_air_id_no" value='<?=$rs[r_air_id_no]?>'>

        <input type="hidden" name="d_air_no_m" id="d_air_no_m" value='<?=$rs[d_air_no_m]?>'>
        <input type="hidden" name="d_air_time1_m" id="d_air_time1_m" value='<?=$rs[d_air_time1_m]?>'>
        <input type="hidden" name="d_air_time2_m" id="d_air_time2_m" value='<?=$rs[d_air_time2_m]?>'>

        <input type="hidden" name="partner_staff" id="partner_staff" value='<?=$rs[partner_staff]?>'>

        <tr><td colspan=8  bgcolor='#5E90AE' height=2></td></tr>

        <tr>
          <td class="subject" width="15%">대표자명</td>
          <td width="37%">
               <?=html_input('name',20,50)?> <span class="btn_pack medium bold"><a href="javascript:find()"> 검색 </a></span>

               <!-- <label><input type="checkbox" id="bit_cancel" name="bit_cancel" value="1" <?=($rs[bit_cancel])?'checked':""?>>취소</label> -->
               <label><input type="checkbox" id="bit_bizseat" name="bit_bizseat" value="1" <?=($rs[bit_bizseat])?'checked':""?>> 비즈니스석 </label>

          </td>

          <td class="subject" width="13%">대표자 핸드폰</td>
          <td>
               <?=html_input('phone',30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject">경로</td>
          <td>
               <select name="view_path">
               <option value="">선택</option>
               <?=option_str($VIEW_PATH,$VIEW_PATH,$rs[view_path])?>
               </select>
               &nbsp;&nbsp;&nbsp;
               <strong>출발지</strong>
               <?=html_input('point_dep',21,20,'box')?>
          </td>

          <td class="subject">담당자</td>
          <td>
               <select name="main_staff">
                <?=option_str($STAFF,$STAFF,$rs[main_staff])?>
               </select>

               <?if($user_id!="tester" && $user_id!="test"){?>
               <select name="sub_staff">
                <?=option_str($STAFF,$STAFF,$rs[sub_staff])?>
               </select>
               <?}?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">출국일</td>
          <td>
               <?=html_input('d_date',13,10,'box')?>
          </td>

          <td class="subject">귀국일</td>
          <td>
                <?=html_input('r_date',13,10,'box')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">예약일</td>
          <td colspan="3">
               <?=html_input('tour_date',13,10,'box dateinput')?>
               <!-- <span class="btn_pack medium bold"><a href="javascript:plist()"> 출력양식 </a></span>&nbsp; -->

                <?if($rs[id_no]){?>
                    <span class="btn_pack medium bold"><a href="javascript:newWin('form01.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation_pop')">예약요청서 </a></span>&nbsp;
                    <span class="btn_pack medium bold"><a href="javascript:newWin('form02.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation_pop')">예약확정서</a></span>&nbsp;
                    <span class="btn_pack medium bold"><a href="javascript:newWin('form03.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation_pop')">출발안내문</a></span>&nbsp;
                    <span class="btn_pack medium bold"><a href="javascript:newWin('form04.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation_pop')">샌딩의뢰서</a></span>&nbsp;
                    <span class="btn_pack medium bold"><a href="javascript:newWin('form07.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation_pop')">INVOICE</a></span>&nbsp;
                    <span class="btn_pack medium bold"><a href="javascript:newWin('form12.html?resv_id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation_pop')">팩스예약요청서</a></span>
                <?}?>

                <?
                $make_code = encrypt($rs[id_no],$SALT);
                $make_code = str_replace("+","{p}",$make_code);

                $code2 = encrypt($CP_ID,$SALT);
                $code2 = str_replace("+","{p}",$code2);

                $long_url1 = short_url("${DOMAIN}/new/bkoff/cmp/form01.html?code=$make_code&code2=$code2&time=".time());
                $long_url2 = short_url("${DOMAIN}/new/bkoff/cmp/form02.html?code=$make_code&code2=$code2&time=".time());
                $long_url3 = short_url("${DOMAIN}/new/bkoff/cmp/form03.html?code=$make_code&code2=$code2&time=".time());
                $long_url4 = short_url("${DOMAIN}/new/bkoff/cmp/form04.html?code=$make_code&code2=$code2&time=".time());
                $long_url5 = short_url("${DOMAIN}/new/bkoff/cmp/form07.html?code=$make_code&code2=$code2&time=".time());
                $long_url6 = short_url("${DOMAIN}/new/bkoff/cmp/form10.html?code=$make_code&code2=$code2&time=".time());
                ?>

                <p><span class="long_url">예약요청서 : <?=$long_url1?></span><span class="btn_pack medium bold"><a href="javascript:newWin('<?=$long_url1?>',870,650,1,1,'','')">바로가기</a></span></p>
                <p><span class="long_url">예약확정서 : <?=$long_url2?></span><span class="btn_pack medium bold"><a href="javascript:newWin('<?=$long_url2?>',870,650,1,1,'','')">바로가기</a></span></p>
                <p><span class="long_url">출발안내문 : <?=$long_url3?></span><span class="btn_pack medium bold"><a href="javascript:newWin('<?=$long_url3?>',870,650,1,1,'','')">바로가기</a></span></p>
                <p><span class="long_url">샌딩의뢰서 : <?=$long_url4?></span><span class="btn_pack medium bold"><a href="javascript:newWin('<?=$long_url4?>',870,650,1,1,'','')">바로가기</a></span></p>
                <p><span class="long_url">INVOICE : <?=$long_url5?></span><span class="btn_pack medium bold"><a href="javascript:newWin('<?=$long_url5?>',870,650,1,1,'','')">바로가기</a></span></p>

                <p><span class="long_url">항공취소요청서 : <?=$long_url6?></span><span class="btn_pack medium bold"><a href="javascript:newWin('<?=$long_url6?>',870,650,1,1,'','')">바로가기</a></span></p>

          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject">제목</td>
          <td colspan="3">
               <?=html_input('subject',60,140,'box ')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">상품명</td>
          <td colspan="3">
               <span id="golf_wrap"></span>
               <?=html_input('golf',60,50,'box readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_golf()"> 검색 </a></span>

               <?if($rs[golf_id_no]){?>
               &nbsp;&nbsp;&nbsp;
               (상품코드 : <a href="javascript:newWin('view_golf.php?id_no=<?=$rs[golf_id_no]?>',870,700,1,1,'golf')"><?=$rs[golf_id_no]?></a>)
               <?}?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">호텔명</td>
          <td colspan="3">

               <input type="hidden" id="hotel_id_no" name="hotel_id_no" value="<?=$rs[hotel_id_no]?>" style="width:100%"> 
               <input type="hidden" id="hotel2_id_no" name="hotel2_id_no" value="<?=$rs[hotel2_id_no]?>" style="width:100%"> 
               <input type="hidden" id="hotel_name" name="hotel_name" value="<?=$rs[hotel_name]?>" style="width:100%"> 
               <input type="hidden" id="hotel2_name" name="hotel2_name" value="<?=$rs[hotel2_name]?>" style="width:100%"> 
               <?=html_input('hotel',60,50,'box hotel readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_hotel('')"> 검색 </a></span>
               <span class="btn_pack medium bold"><a href="javascript:clear_hotel('')"> 삭제 </a></span>

               <br/>
               <?=html_input('hotel2',60,50,'box hotel readonly')?> <span class="btn_pack medium bold"><a href="javascript:find_hotel(2)"> 검색 </a></span>
               <span class="btn_pack medium bold"><a href="javascript:clear_hotel(2)"> 삭제 </a></span>     
               <div class="hide">
                   호텔-공항 : <?=html_input('ah1',10,28)?>소요
                   <br/>
                   호텔-공항 : <?=html_input('ah2',10,28)?>소요
               </div>
          </td>

        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>



        <tr>
          <td class="subject">일정표 TYPE</td>
          <td colspan="3">
               <?=radio($PLAN_TYPE1,$PLAN_TYPE2,$rs[plan_type],'plan_type')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>


        <tr>
          <td class="subject">국내선 항공일정
            <label><input type="checkbox" onclick="show_hide_air_view(this.checked)">보기</label>
          </td>
          <td colspan="3">

            <table class="tbl_normal bx_air_view">
                <tr>
                    <th width="150">구분</th>
                    <th>지역</th>
                    <th>교통편</th>
                    <th>시간</th>
                    <th>여행일정</th>
                </tr>
                <tr>
                    <th>지방출발</th>
                    <td><input type="text" name="plan_add1_a" id="plan_add1_a" value="<?=$rs[plan_add1_a]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add1_b" id="plan_add1_b" value="<?=$rs[plan_add1_b]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add1_c" id="plan_add1_c" value="<?=$rs[plan_add1_c]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add1_d" id="plan_add1_d" value="<?=$rs[plan_add1_d]?>" class="box" size="70" maxlength="100"></td>
                </tr>
                <tr>
                    <th>인천도착</th>
                    <td><input type="text" name="plan_add2_a" id="plan_add2_a" value="<?=$rs[plan_add2_a]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add2_b" id="plan_add2_b" value="<?=$rs[plan_add2_b]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add2_c" id="plan_add2_c" value="<?=$rs[plan_add2_c]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add2_d" id="plan_add2_d" value="<?=$rs[plan_add2_d]?>" class="box" size="70" maxlength="100"></td>
                </tr>
            </table>
            <table class="tbl_normal bx_air_view">
                <tr>
                    <th width="150">인천출발</th>
                    <td><input type="text" name="plan_add8_a" id="plan_add8_a" value="<?=$rs[plan_add8_a]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add8_b" id="plan_add8_b" value="<?=$rs[plan_add8_b]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add8_c" id="plan_add8_c" value="<?=$rs[plan_add8_c]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add8_d" id="plan_add8_d" value="<?=$rs[plan_add8_d]?>" class="box" size="70" maxlength="100"></td>
                </tr>
                <tr>
                    <th>지방도착</th>
                    <td><input type="text" name="plan_add9_a" id="plan_add9_a" value="<?=$rs[plan_add9_a]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add9_b" id="plan_add9_b" value="<?=$rs[plan_add9_b]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add9_c" id="plan_add9_c" value="<?=$rs[plan_add9_c]?>" class="box c" size="10" maxlength="30"></td>
                    <td><input type="text" name="plan_add9_d" id="plan_add9_d" value="<?=$rs[plan_add9_d]?>" class="box" size="70" maxlength="100"></td>
                </tr>

            </table>

          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">고객정보</td>
          <td colspan="3">
               <?=html_input('people',3,3,'box num numberic')?>명  &nbsp;&nbsp;
               <!-- <span class="btn_pack medium bold"><a href="javascript:pop_win()"> 고객정보</a></span> -->


                <span class="btn_pack medium bold"><a href="javascript:pop_win_apis(1)"> 아피스</a></span>
                <span class="btn_pack medium bold"><a href="javascript:pop_win_apis(2)"> 입출금정보</a></span>

                <?if($user_id!="tester"){?>
                <span class="btn_pack medium bold"><a href="javascript:pop_win_apis(3)" <?if($bit_cancel){?>style="color:red"<?}?> id="btn_cancel"> 취소현황</a></span>
                <?}?>

                <span class="btn_pack medium bold"><a href="javascript:newWin('form10.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','reservation_cancel')"> 취소요청서</a></span>

                <span class="btn_pack medium bold"><a href="javascript:newWin('form11.html?id_no=<?=$rs[id_no]?>',870,650,1,1,'','gift')">판촉물 사용승인 요청서</a></span>

                <?if($chk_birth){?>
                <span class="btn_pack medium bold"><a href="javascript:pop_win_apis(100)" style="color:red"> 생일고객</a></span>
                <?}else{?>
                <span class="btn_pack medium bold"><a href="javascript:alert('여행기간 동안 생일인 고객이 없습니다.')" style="color:gray"> 생일고객</a></span>
                <?}?>

          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
        <tr>
          <td class="subject">TL/BSP</td>
          <td colspan="3">
            TL(TIME LIMIT) : <?=html_input('tl',12,10,'box dateinput c')?>
            BSP : <select name="bsp"><option value="">선택</option><?=option_str($BSP,$BSP,$rs[bsp])?></select>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">성향</td>
          <td >
               <select name="customer_type" id="customer_type">
                <option value="">선택</option>
                <?=option_str($CUSTOMER_TYPE,$CUSTOMER_TYPE,$rs[customer_type])?>
               </select>
          </td>

          <td class="subject">발권여부/수단</td>
          <td >
               <select name="bit_ticket">
               <option value="">발권여부 선택</option>
                <?=option_str($YN.",self ",$YN.",self",$rs[bit_ticket])?>
               </select>

               <select name="ticket_method">
               <option value="">발권수단 선택</option>
                <?=option_str($PAY_METHOD,$PAY_METHOD,$rs[ticket_method])?>
               </select>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>



        <tr>
          <td class="subject">판매가</td>
          <td>
               <?=html_input('price',13,10,'box readonly')?>
          </td>

          <td class="subject">잔금</td>
          <td>
               <?=html_input('price_last',13,10,'box readonly')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">계약금</td>
          <td>
               <?=html_input('price_prev',13,10,'box readonly')?>
          </td>

          <td class="subject">잔금입금일</td>
          <td>
               <?=html_input('pay_date',14,10,'box dateinput')?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <!-- <td class="subject">고객입금액</td>
          <td>
               <?=html_input('price_customer_input',13,10,'box readonly')?>
          </td> -->

          <td class="subject">가지급금</td>
          <td>
               <?=html_input('price_tmp_output',13,10,'box num numberic readonly')?>
          </td>
          <td class="subject">1인수익 / 총수익</td>
          <td>
               <span id="profit_one"><?=nf($gain_one)?></span> /
               <span id="profit_total"><?=nf($gain_all)?></span>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

<!--
        <tr>
          <td class="subject">항공요금</td>
          <td>
               <?=html_input('price_air',30,50)?>
          </td>
          <td class="subject">지상비</td>
          <td>
               <?=html_input('price_randing',30,50)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>
-->

        <tr>
          <td class="subject">결제정보</td>
          <td>
               <select name="pay_method">
               <option value="">결제수단 선택</option>
                <?=option_str($PAY_METHOD,$PAY_METHOD,$rs[pay_method])?>
               </select>

               <select name="pay_check">
               <option value="">입금확인여부</option>
                <?=option_str($YN,$YN,$rs[pay_check])?>
               </select>

               <?$rs[payed_price]=nf($rs[payed_price])?>
               입금액:<?=html_input("payed_price",10,20,'box numberic')?>원

          </td>

          <td class="subject">판촉물</td>
          <td>

                <!--
                세금계산서 :
                <?if(!$rs[id_no]) $rs[bit_tax]="N";?>
                <select name="bit_tax">
                    <?=option_str($YN,$YN,$rs[bit_tax])?>
                </select>

                현금영수증 :
                <?if(!$rs[id_no]) $rs[bit_cash]="N";?>
                <select name="bit_cash">
                    <?=option_str($YN,$YN,$rs[bit_cash])?>
                </select>
                //-->

                <select name="golf_ball">
                    <option value="0">골프공</option>
                    <?=option_int(1,50,1,$rs[golf_ball])?>
                </select>

                <select name="golf_ball2">
                    <option value="0">골프공(고급)</option>
                    <?=option_int(1,50,1,$rs[golf_ball2])?>
                </select>

                <select name="air_cover">
                    <option value="0">항공카버</option>
                    <?=option_int(1,50,1,$rs[air_cover])?>
                </select>

                <select name="dollarbook">
                    <option value="0">달러북</option>
                    <?=option_int(1,50,1,$rs[dollarbook])?>
                </select>

          </td>
        </tr>

        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">
            <span class="btn_pack medium bold"><a href="javascript:air_info()"> 항공 </a></span>
            <span class="btn_pack medium bold"><a href="javascript:air_info_api(1)"> 해외 </a></span>
            <span class="btn_pack medium bold"><a href="javascript:air_info_api(2)"> 국내 </a></span>
          </td>
          <td>

               <?
                    if($rs[d_air_id_no]){
                        $sql3 = "select * from cmp_air where id_no=$rs[d_air_id_no]";
                        $dbo3->query($sql3);
                        $rs3=$dbo3->next_record();
                        $rs[d_air_no] = $rs3[d_air_no];
                        $rs[d_air_time1] = $rs3[d_time_s];
                        $rs[r_air_time2] = $rs3[d_time_e];
                    }

                    if($rs[r_air_id_no]){
                        $sql3 = "select * from cmp_air where id_no=$rs[r_air_id_no]";
                        $dbo3->query($sql3);
                        $rs3=$dbo3->next_record();
                        $rs[r_air_no] = $rs3[r_air_no];
                        $rs[r_air_time1] = $rs3[r_time_s];
                        $rs[r_air_time2] = $rs3[r_time_e];
                    }
               ?>

               <div id="air_info"><?if($rs[d_air_no]){?>▶출국편명:<?=$rs[d_air_no]?> (출발시간:<?=$rs[d_air_time1]?> <?if($rs[d_air_time2]){?>/ 도착시간:<?=$rs[d_air_time2]?><?}?>)<?}?>
                    <?if($rs[d_air_id_no]){?><a href="javascript:newWin('view_air.php?id_no=<?=$rs[d_air_id_no]?>',950,580,1,1,'','air')"><?=$rs[d_air_id_no]?></a><?}?>
               </div>
               <div id="air_info2"><?if($rs[r_air_no]){?>▶귀국편명:<?=$rs[r_air_no]?> (출발시간:<?=$rs[r_air_time1]?> <?if($rs[r_air_time2]){?>/ 도착시간:<?=$rs[r_air_time2]?><?}?>)<?}?>
                    <?if($rs[r_air_id_no]){?><a href="javascript:newWin('view_air.php?id_no=<?=$rs[r_air_id_no]?>',950,580,1,1,'','air')"><?=$rs[r_air_id_no]?></a><?}?>
               </div>
          </td>

          <td class="subject">샌딩여부</td>
          <td>
               <select name="bit_sending">
               <option value="">선택</option>
                <?=option_str($SENDING_YN,$SENDING_YN,$rs[bit_sending])?>
               </select>
                &nbsp;&nbsp;
               우편여부 :
               <select name="bit_sending2">
                <?=option_str("N,Y","N,Y",$rs[bit_sending2])?>
               </select>

          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">거래처</td>
          <td colspan="3">
            <select name="partner" id="partner">
            <option value=""></option>
            <?=option_str($PARTNERS,$PARTNERS,$rs[partner]);?>
            </select>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">항공PNR</td>
          <td colspan="3">
            <?=html_textarea('memo',0,12)?>
          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr>
          <td class="subject">관리자 메모 및<br/>입금내역</td>
          <td colspan="3">
            <?//=html_textarea('memo_payment',0,12)?>

            <?if(!$rs[bit_content]) $rs[memo_payment] = nl2br($rs[memo_payment])?>

            <!-- Html Editor Begin -->
            <textarea name="content" id="content" rows="10" cols="100" style="width:100%; height:250px; display:none;"><?=$rs[memo_payment]?></textarea>
            <script type="text/javascript" src="../../se2/js/HuskyEZCreator.js" charset="utf-8"></script>
            <script type="text/javascript" src="../../include/smart_editor.js"></script><!--sSkinURI 경로 수정 필요, SE2B_Configuration.js-->
            <!-- Html Editor End -->

          </td>
        </tr>
        <tr><td colspan="4" class="tblLine"></td></tr>

        <tr><td colspan="12" bgcolor='#E1E1E1' height=1></td></tr>

        <tr>
          <td colspan=10>
              <br>
              <!-- Button Begin---------------------------------------------->
              <table border="0" width="100%" cellspacing="0" cellpadding="0" align="right">
                <tr align="right">
                    <td>


                        <?if(!$rs[bit_close] && $id_no){?>
                        <span class="btn_pack medium bold"><a href="javascript:set_close(1)"> 마감 </a></span>&nbsp;
                        <?}?>


                        <?if($rs[bit_close] && strstr("partner_a,ceo",$_SESSION["sessLogin"][staff_type])){?>
                        <span class="btn_pack medium bold"><a href="javascript:set_close(0)"> 마감 해지 </a></span>&nbsp;
                        <?}?>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                        <span class="btn_pack medium bold"><a href="#" onClick="chkForm()"> 저장 </a></span>&nbsp;
                        <?if($rs[origin_id_no]){?>
                        <!-- <span class="btn_pack medium bold"><a href="javascript:newWin('view_estimate.php?id_no=<?=$rs[origin_id_no]?>',1200,650,1,1,'','reservation_pop6')"> 견적서 바로가기 </a></span>&nbsp; -->
                        <span class="btn_pack medium bold"><a href="view_estimate.php?id_no=<?=$rs[origin_id_no]?>"> 견적서 바로가기 </a></span>&nbsp;
                        <span class="btn_pack medium bold"><a href="javascript:newWin('form06.html?id_no=<?=$rs[origin_id_no]?>',950,650,1,1,'','reservation_pop7')"> 일정표 바로가기 </a></span>&nbsp;
                        <?}?>
                        <span class="btn_pack medium bold"><a href="#" onClick="self.close()"> 창닫기 </a></span>
                    </td>
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

</div>

    <!--내용이 들어가는 곳 끝-->

<!-- Copyright -->
<?include_once("../bottom_min.html");?>