<?
/*권한체크s*/
//if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){

if($_SESSION["sessLogin"]["staff_type"]!="ceo"){

    $power_exception_files ="@edit_user.php@";  
    if(!strstr($_SESSION["sessLogin"]["staff_type"],"partner")) $power_exception_files .="@sharevalue.php@"; 

    if(!strstr($power_exception_files,SELF)){//개인정보 수정은 제외

        if($_SESSION['sessLogin']['staff_type']!="ceo"){

            $sess_proof_erp = $_SESSION['sessLogin']['proof_erp'];
            if($sess_proof_erp){
                $bit_erp_power=1;

                $MENU_CHK_NAME = array();
                $MENU_CHK_NAME["list_golf.php"]="기본설정";
                $MENU_CHK_NAME["list_estimate.php"]="일정표등록";
                $MENU_CHK_NAME["list_estimate_j.php"]="일정표등록";
                $MENU_CHK_NAME["list_reservation.php"]="고객예약정보";
                $MENU_CHK_NAME["list_report1.php"]="기간별매출";
                $MENU_CHK_NAME["list_bank.php"]="입금내역";
                $MENU_CHK_NAME["list_transfer.php"]="송금요청";
                $MENU_CHK_NAME["list_report2.php"]="고객송출현황";
                $MENU_CHK_NAME["form05.html"]="여행자보험";
                $MENU_CHK_NAME["list_sms.php"]="문자발송";
                $MENU_CHK_NAME["list_paper1.php"]="경영관리";
                $MENU_CHK_NAME["list_paper2.php"]="현황및실적";
                $MENU_CHK_NAME["list_partner_cal.php"]="정산내역";
                $MENU_CHK_NAME["list_paper_n1.php"]="통계";
                $MENU_CHK_NAME["list_group.php"]="골프투어문의";
                $MENU_CHK_NAME["list_pay.php"]="신용카드결제";
                $MENU_CHK_NAME["list_hrmcal.php"]="휴가및출장";
                $MENU_CHK_NAME["download_cell.php"]="핸드폰번호다운로드";
                $MENU_CHK_NAME["view_staff.php"]="담당자정보(권한관리)";

                $SELF = SELF;
                $proof_erp_check = $MENU_CHK_NAME[$SELF]; //체크해야 하는 파일 여부
                if($proof_erp_check){
                    $bit_erp_power = (strstr($sess_proof_erp,$proof_erp_check))? 1: 0; //허용된 파일인지 여부
                }
                // if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
                //     checkVar("sess_proof_erp",$sess_proof_erp);
                //     checkVar("SELF",SELF);
                //     checkVar("SELF2",$SELF);
                //     checkVar("proof_erp_check",$proof_erp_check);
                //     checkVar("bit_erp_power",$bit_erp_power);
                //     checkVar("staff_type",$_SESSION["sessLogin"]["staff_type"]);
                // }


                $arr_pwr = explode(",",$sess_proof_erp);
                $proof_erp_basic = array_search($arr_pwr[0], $MENU_CHK_NAME);


                if(!$bit_erp_power && SELF!="basic.php"){
                    $url = ($proof_erp_basic)? $proof_erp_basic : "/new/bkoff/cmp/basic.php";
                    if($bit_close_power){
                        echo "<script>alert('권한이 없습니다.');self.close()</script>";
                        exit;
                    }

                    redirect2($url);
                    exit;
                }
            }
            if(!$sess_proof_erp && !$_SESSION['sessLogin']['proof']){//모든 권한이 없는 경우
                if($bit_close_power){
                    echo "<script>alert('권한이 없습니다.');self.close()</script>";
                    exit;
                }            
                redirect2("/new/bkoff/cmp/basic.php");
                exit;            
            }


        }

    }


    /*권한에 따른 메뉴 감추기(경영관리등) s*/
    $sql_ = "select * from cmp_staff where id='".$_SESSION["sessLogin"]["id"]."'";
    $dbo_->query($sql_);
    $rs_=$dbo_->next_record();    
    $BIT_PARTNER_HIDE=0;
    $BIT_INSA_HIDE=0;
    $BIT_MANAGEMENT_HIDE=0;//경영관리 감추기
    $BIT_STAFF_HIDE=0;//담당자정보(권한관리) 감추기
    $BIT_CELLDOWN_HIDE=0;//핸드폰번호다운로드 감추기
    if(!strstr($rs_[power_erp],"인사관리")){
        $BIT_INSA_HIDE=1;
    }
    if(!strstr($rs_[power_erp],"경영관리")){
        $BIT_MANAGEMENT_HIDE=1;
    }
    if(!strstr($rs_[power_erp],"담당자정보(권한관리)")){
        $BIT_STAFF_HIDE=1;
    }
    if(!strstr($rs_[power_erp],"핸드폰번호다운로드")){
        $BIT_CELLDOWN_HIDE=1;
    }
    if(strstr("partner_a,partner_g",$rs_[staff_type])){
        $tmenuh1=1; //해당 메뉴만 보이기
        $BIT_PARTNER_HIDE=1;
    }

    if($rs_[staff_type]=="ceo"){
        $_SESSION["sessLogin"]["proof"]=$STAFF_POWER2;
        $BIT_MANAGEMENT_HIDE=0;
        $BIT_PARTNER_HIDE=0;
        $BIT_INSA_HIDE=0;
        $BIT_STAFF_HIDE=0;
    }
    /*권한에 따른 메뉴 감추기(경영관리등) f*/



    /*파일별 권한 검사s*/
    if($rs_[staff_type]!="ceo"){
        if(strstr(SELF,"_staff.php") && !strstr($sess_proof_erp,"인사관리") && !strstr($sess_proof_erp,"담당자정보(권한관리)")){
            error("권한이 없습니다.");exit;            
        }
    }
    /*파일별 권한 검사f*/



    /*엑셀다운로드 검사s*/
    if(strstr(SELF,"_excel.php") && !strstr($sess_proof_erp,"엑셀다운로드") ){
        error("권한이 없습니다.");
    }
    /*엑셀다운로드 검사f*/


}
/*권한체크f*/
?>