<?
$real_path=str_replace(strstr(realpath(__FILE__),"/api/"),"",realpath(__FILE__));
include $real_path.'/api/api_common.php';
include $real_path.'/api/Popbill/PopbillHTTaxinvoice.php';
include $real_path.'/api/HTTaxinvoice/common.php';

    /**
     * 수집된 전자세금계산서 1건의 상세내역을 인쇄하는 페이지의 URL을 반환합니다.
     * - 반환되는 URL은 보안 정책상 30초 동안 유효하며, 시간을 초과한 후에는 해당 URL을 통한 페이지 접근이 불가합니다.
     * - https://docs.popbill.com/httaxinvoice/php/api#GetPopUpURL
     */


    // 국세청 승인번호
    $NTSConfirmNum = $_GET["NTSConfirmNum"];

    try {
        $url = $HTTaxinvoiceService->getPopUpURL($bizno, $NTSConfirmNum);
        redirect2($url);exit;
    }
    catch (PopbillException $pe) {
        $code = $pe->getCode();
        $message = $pe->getMessage();
        echo "<script>alert('${message}');self.close();</script>";
    }
?>