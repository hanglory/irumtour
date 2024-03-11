<?
function color($str){
	switch($str){
		case "허용":$color='green';break;
		case "차단":$color='gray';break;
	}

	return "<span style='color:$color'>$str</span>";

}

function chk_power($sess,$str){
	global $_SERVER;

    if(!$_SESSION['sessLogin']['proof_erp']){
    	$url = (strstr(SELF,'view_'))? "../cmp/basic2.php":"../cmp/basic.php";

    	if(!strstr($sess,$str)){
    		redirect2($url);
    		exit;
    	}
    }
}

function get_m_color($int){
	if($int<0) $result = "<span style='color:red'>$int</span>";
	else $result = "<span style='color:green'>$int</span>";

	return $result;
}


function full_date($date){
	$d = date("m/d",strtotime($date));
	$w =convertWeek(date("w",strtotime($date)));

	$result = "($d)<br/>$w";

	return $result;

}


function ico_meal($str){
	$str=str_replace("조:","<img src='http://irumtour.net/new/bkoff/cmp/img/ic_meal01.png' style='vertical-align:bottom'> ",$str);
	$str=str_replace("중:","<img src='http://irumtour.net/new/bkoff/cmp/img/ic_meal02.png' style='vertical-align:bottom'> ",$str);
	$str=str_replace("석:","<img src='http://irumtour.net/new/bkoff/cmp/img/ic_meal03.png' style='vertical-align:bottom'> ",$str);
	return $str;
}

function staff_full_name($id){

	global  $info;
	$dbo = new MiniDB($info);

	$sql = "select * from cmp_staff where id='$id'";
	$dbo->query($sql);
	$rs = $dbo->next_record();

	$staff = "$rs[name] $rs[mposition]";

	return $staff;
}

function pm_rate($no1,$no2){//증감율
	$gep = @(($no1-$no2)/$no2)*100;
	$gep = @round($gep,2);
	return $gep;
}



/*주민번호나 여권번호가 없을 때 자동으로 채워주기*/
function find_rn_customer($name,$passport_no,$rn){
    global  $info;
    $dbo = new MiniDB($info);

    if($name && ($passport_no || $rn)){
        $filter = ($passport_no)? " and passport_no='$passport_no'" :  " and passport_no='$passport_no'";
        $sql = "
                select
                    name,
                    rn,
                    passport_no,
                    passport_limit
                from cmp_people
                where
                    name='$name'
                    $filter

                union all

                select
                    name,
                    rn,
                    passport_no,
                    passport_limit
                from cmp_customer
                where
                    name='$name'
                    $filter                        

                order by  passport_limit desc,passport_no desc, rn desc
                limit 1
            ";
        $dbo->query($sql);
        $rs=$dbo->next_record();
    }

    return $rs;
}


/*직원 구별용 개별 색상*/
function staff_color($id){
    global  $info;
    $dbo = new MiniDB($info);
    $sql = "select * from cmp_staff where id='$id'";
    $dbo->query($sql);
    $rs = $dbo->next_record();
    return $rs[color];
}




/*생일고객 확인s*/
function check_recver_birth($d_date,$r_date,$code){
    global $info;
    global $inputKey;
    global $blockSize;
    global $CP_ID;
    $dbo = new MiniDB($info);



    if($d_date && $r_date && $code && $d_date<=$r_date){

        $chk_birthday="";
        $chk_days="";
        $date=$d_date;
        //if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar($d_date,$r_date);
        $j=0;
        while($date<=$r_date){
            $j++;
            if($j>50) break;
            $chk_days .= "@".substr(rnf($date),-4);
            $date = date("Y/m/d",strtotime($date." +1 day"));
        }
        $chk_days.="@";
        //if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar("chk_days",$chk_days);

        $sql = "
                select 
                    a.* 
                from cmp_people as a left join cmp_reservation as b
                on a.code=b.code
                where 
                    a.code=$code 
                    and a.bit=1
                    and b.cp_id='$CP_ID'
                order by a.seq asc
            ";
        $dbo->query($sql);
        while($rs=$dbo->next_record()){
            
            $birth="";

            if($rs[name] && ($rs[rn] || $rs[passport_no])){
                $rt = find_rn_customer($rs[name],$rs[passport_no],$rs[rn]);
                if(!$rs[rn]) $rs[rn] = $rt[rn];
            }

            if($rs[rn]){
                $aes = new AES($rs[rn], $inputKey, $blockSize);
                $dec=$aes->decrypt();
                $rs[rn] = substr($dec,0,6);
                $birth=(strlen($rs[rn])==6)? substr($rs[rn],-4):"";

                $chk_birthday.=(strstr($chk_days,$birth))? "@".$rs[rn] : "";
            }            

            //if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar($rs[name]."/".$rs[rn],$birth);
        }
        //if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar(mysql_error(),$sql);
    }

    //if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")) checkVar("result",$chk_birthday);
    return $chk_birthday;
}
/*생일고객 확인f*/


/*일정표 시간 첫줄 행바꿈s*/
function plan_time($plan_time){
    if(strstr($plan_time,"{S}")){
        $plan_time = str_replace("{S}\r"," \r",$plan_time);    
        $plan_time = str_replace("{S}","",$plan_time);    
    }
    $result = $plan_time;
    return $result;
}
/*일정표 시간 첫줄 행바꿈f*/
/*array 더하기s*/
function sumArrayValues($array) {
    $sum = 0;
    foreach ($array as $value) {
        if (is_numeric($value)) {
            $sum += $value;
        }
    }
    return $sum;
}
/*array 더하기f*/
?>