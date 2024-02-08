<?
$real_path=str_replace(strstr(realpath(__FILE__),"/api/"),"",realpath(__FILE__));
include $real_path.'/api/api_common.php';
include $real_path.'/api/Popbill/PopbillCashbill.php';
include $real_path.'/api/Cashbill/common.php';

    /*독립파트너*/
    $UserID = $LinkID;
    if($_SESSION['sessLogin']['cp_id']){
        $CP_ID = $_SESSION['sessLogin']['cp_id'];
        $bizno = $CORPNUM;  
        $UserID = $USERID;
    }


    /**
     * 팝빌 사이트와 동일한 현금영수증 1건의 상세 정보 페이지(사이트 상단, 좌측 메뉴 및 버튼 제외)의 URL을 반환합니다.
     * - 반환되는 URL은 보안 정책상 30초 동안 유효하며, 시간을 초과한 후에는 해당 URL을 통한 페이지 접근이 불가합니다.
     * - https://docs.popbill.com/cashbill/php/api#GetViewURL
     */


    try {
        $url = $CashbillService->GetViewURL($bizno, $mgtKey);
        redirect2($url);
    }
    catch (PopbillException $pe) {
        $code = $pe->getCode();
        $message = $pe->getMessage();
        error($message);
    }
?>