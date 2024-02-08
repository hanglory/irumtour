<?
$real_path=str_replace(strstr(realpath(__FILE__),"/api/"),"",realpath(__FILE__));
include $real_path.'/api/api_common.php';
include $real_path.'/api/Popbill/PopbillCashbill.php';
include $real_path.'/api/Cashbill/common.php';


    // if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
    // checkVar("LinkID",$LinkID);
    // checkVar("SecretKey",$SecretKey);
    // checkVar("UserID",$UserID);
    // checkVar("CorpNum",$CorpNum);
    // checkVar("FRANCHISECORPNAME",$FRANCHISECORPNAME);
    // checkVar("FRANCHISECEONAME",$FRANCHISECEONAME);
    // checkVar("FRANCHISEADDR",$FRANCHISEADDR);
    // checkVar("FRANCHISETEL",$FRANCHISETEL);
    // exit;
    // }


    /*독립파트너*/
    $UserID = $LinkID;
    if($_SESSION['sessLogin']['cp_id']){
        $CP_ID = $_SESSION['sessLogin']['cp_id'];
        $bizno = $CORPNUM;  
        $UserID = $USERID;
    }


    /**
     * 작성된 현금영수증 데이터를 팝빌에 저장과 동시에 발행하여 "발행완료" 상태로 처리합니다.
     * - 현금영수증 국세청 전송 정책 : https://docs.popbill.com/cashbill/ntsSendPolicy?lang=php
     * - https://docs.popbill.com/cashbill/php/api#RegistIssue
     */ 
    // 문서번호, 최대 24자리, 영문, 숫자 '-', '_'를 조합하여 사업자별로 중복되지 않도록 구성
    $mgtKey = $bizno . '_' . time();
    $memo = '현금영수증 즉시발행';

    $id_no=rnf($id_no);
    $assort=rnf($assort);
    $tradeOpt=$tradeOpt;
    $tradeUsage=$tradeUsage;
    $taxationType=$taxationType;
    $identityNum=rnf($identityNum);
    $totalAmount=rnf($totalAmount);
    $supplyCost=rnf($supplyCost);
    $tax=rnf($tax);
    $phone=rnf($phone);
    $itemName = str_replace("'","",$itemName);
    $itemName = str_replace("\"","",$itemName);


    // if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){

    //     checkVar("[필수] 가맹점 사업자번호",$bizno);
    //     checkVar("가맹점 상호",$FRANCHISECORPNAME);
    //     checkVar("가맹점 대표자 성명",$FRANCHISECEONAME);
    //     checkVar("가맹점 주소",$FRANCHISEADDR);
    //     checkVar("가맹점 전화번호",$FRANCHISETEL);
    //     checkVar("주문자명",$customerName);
    //     checkVar("주문상품명",$itemName);
    //     checkVar("주문주문번호",$orderNumber);
    //     checkVar("주문자 휴대폰",$phone);

    //     checkVar("mgtKey",$mgtKey);
    //     checkVar("LinkID",$LinkID);
    //     checkVar("UserID",$UserID);
    //     checkVar("CorpNum",$CorpNum);
    //     checkVar("bizno",$bizno);
    //     checkVar("id_no",$id_no);
    //     checkVar("assort",$assort);
    //     checkVar("tradeOpt",$tradeOpt);
    //     checkVar("tradeUsage",$tradeUsage);
    //     checkVar("taxationType",$taxationType);
    //     checkVar("identityNum",$identityNum);
    //     checkVar("totalAmount",$totalAmount);
    //     checkVar("supplyCost",$supplyCost);
    //     checkVar("tax",$tax);
    //     checkVar("phone",$phone);
    //     checkVar("FRANCHISECORPNAME",$FRANCHISECORPNAME);
    //     checkVar("FRANCHISECEONAME",$FRANCHISECEONAME);
    //     checkVar("FRANCHISEADDR",$FRANCHISEADDR);
    //     checkVar("FRANCHISETEL",$FRANCHISETEL);
    //     exit;
    // }

    // 발행안내메일 제목
    // 미기재시 기본양식으로 전송
    $emailSubject = '';

    // 현금영수증 객체 생성
    $Cashbill = new Cashbill();

    // [필수] 현금영수증 문서번호,
    $Cashbill->mgtKey = $mgtKey;

    // [필수] 문서형태, (승인거래, 취소거래) 중 기재
    $Cashbill->tradeType =($tradeType)? $tradeType :'승인거래';

    // [필수] 거래구분, (소득공제용, 지출증빙용) 중 기재
    $Cashbill->tradeUsage = $tradeUsage;

    // [필수] 거래유형, (일반, 도서공연, 대중교통) 중 기재
    $Cashbill->tradeOpt = $tradeOpt;

    // [필수] 과세형태, (과세, 비과세) 중 기재
    $Cashbill->taxationType = $taxationType;

    // [필수] 거래금액, ','콤마 불가 숫자만 가능
    $Cashbill->totalAmount = $totalAmount;

    // [필수] 공급가액, ','콤마 불가 숫자만 가능
    $Cashbill->supplyCost = $supplyCost;

    // [필수] 부가세, ','콤마 불가 숫자만 가능
    $Cashbill->tax = $tax;

    // [필수] 봉사료, ','콤마 불가 숫자만 가능
    $Cashbill->serviceFee = '0';

    // [필수] 가맹점 사업자번호
    $Cashbill->franchiseCorpNum = $bizno;

    // 가맹점 상호
    $Cashbill->franchiseCorpName = $FRANCHISECORPNAME;

    // 가맹점 대표자 성명
    $Cashbill->franchiseCEOName = $FRANCHISECEONAME;

    // 가맹점 주소
    $Cashbill->franchiseAddr = $FRANCHISEADDR;

    // 가맹점 전화번호
    $Cashbill->franchiseTEL = $FRANCHISETEL;

    // [필수] 식별번호, 거래구분에 따라 작성
    // 소득공제용 - 주민등록/휴대폰/카드번호 기재가능
    // 지출증빙용 - 사업자번호/주민등록/휴대폰/카드번호 기재가능
    $Cashbill->identityNum = $identityNum;

    // 주문자명
    if($customerName) $Cashbill->customerName = $customerName;

    // 주문상품명
    if($itemName) $Cashbill->itemName = $itemName;

    // 주문주문번호
    if($orderNumber) $Cashbill->orderNumber = $orderNumber;

    /*
    // 주문자 이메일
    // 팝빌 개발환경에서 테스트하는 경우에도 안내 메일이 전송되므로,
    // 실제 거래처의 메일주소가 기재되지 않도록 주의
    $Cashbill->email = 'test@test.com';
    */

    // 주문자 휴대폰
    $Cashbill->hp = $phone;

    // 발행시 알림문자 전송여부
    $Cashbill->smssendYN = false;

    try {
        $result = $CashbillService->RegistIssue($bizno, $Cashbill, $memo, $UserID, $emailSubject);
        $code = $result->code;
        $message = $result->message;
        $confirmNum = $result->confirmNum;
        $tradeDate = $result->tradeDate;

        $reg_date = date('Y/m/d');
        $reg_date2 = date('H:i:s');

        $sql="
           insert into cmp_cashbill (
              cp_id,
              people_id_no,
              mgtKey,
              assort,
              tradeOpt,
              tradeUsage,
              taxationType,
              identityNum,
              totalAmount,
              supplyCost,
              tax,
              phone,
              reg_date,
              reg_date2,
              customerName,
              itemName,
              orderNumber
          ) values (
              '$CP_ID',
              '$id_no',
              '$mgtKey',
              '$assort',
              '$tradeOpt',
              '$tradeUsage',
              '$taxationType',
              '$identityNum',
              '$totalAmount',
              '$supplyCost',
              '$tax',
              '$phone',
              '$reg_date',
              '$reg_date2',
              '$customerName',
              '$itemName',
              '$orderNumber'
        )";
        $dbo->query($sql);
        //checkVar(mysql_error(),$sql);exit;
        echo "
            <script>
                alert(\"${message}\");
                opener.location.reload();
                self.close();
            </script>
        ";
    }
    catch(PopbillException $pe) {
        $code = $pe->getCode();
        $message = $pe->getMessage();
        error($message);exit;
    }
?>
