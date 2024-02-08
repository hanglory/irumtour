<?
$real_path=str_replace(strstr(realpath(__FILE__),"/api/"),"",realpath(__FILE__));
include $real_path.'/api/api_common.php';
include $real_path.'/api/Popbill/PopbillFax.php';
include $real_path.'/api/Fax/common.php';

/**
 * 발신번호를 등록하고 내역을 확인하는 팩스 발신번호 관리 페이지 팝업 URL을 반환합니다.
 * - 반환되는 URL은 보안 정책상 30초 동안 유효하며, 시간을 초과한 후에는 해당 URL을 통한 페이지 접근이 불가합니다.
 * - https://docs.popbill.com/fax/php/api#GetSenderNumberMgtURL
 */
try {
    $url = $FaxService->GetSenderNumberMgtURL($bizno, $LinkID);
} catch (PopbillException $pe) {
    $code = $pe->getCode();
    $message = $pe->getMessage();
}

redirect2($url);
?>