<?
$real_path=str_replace(strstr(realpath(__FILE__),"/api/"),"",realpath(__FILE__));
include $real_path.'/api/api_common.php';
include $real_path.'/api/Popbill/PopbillFax.php';
include $real_path.'/api/Fax/common.php';

/**
 * 팩스 1건을 전송합니다. (최대 전송파일 개수: 20개)
 * - 팩스전송 문서 파일포맷 안내 : https://docs.popbill.com/fax/format?lang=php
 * - https://docs.popbill.com/fax/php/api#SendFAX
 */


$filename_fax= "../../public/fax/fax_${user_id}.html";
$SenderName=secu($GET['SenderName']);
$Sender=secu($GET['Sender']);
$rcvnm=secu($GET['rcvnm']);
$rcv=secu($GET['rcv']);

// 팩스전송 발신번호
//$Sender = '0260085902';

// 팩스전송 발신자명
//$SenderName = '이즈플러스';

// 팩스 수신정보 배열, 최대 1000건
$Receivers[] = array(
    // 팩스 수신번호
    'rcv' => $rcv,

    // 수신자명
    'rcvnm' => $rcvnm
);

// 팩스전송파일, 해당파일에 읽기 권한이 설정되어 있어야 함. 최대 20개.
$Files = array($filename_fax);
//$Files = array('test.pdf');

// 예약전송일시(yyyyMMddHHmmss) ex) 20151212230000, null인경우 즉시전송
$reserveDT = null;

// 광고팩스 전송여부
$adsYN = false;

// 팩스제목
$title = '팩스 단건전송 제목';

// 전송요청번호
// 파트너가 전송 건에 대해 관리번호를 구성하여 관리하는 경우 사용.
// 1~36자리로 구성. 영문, 숫자, 하이픈(-), 언더바(_)를 조합하여 팝빌 회원별로 중복되지 않도록 할당.
$requestNum = '';

$url_return="../../bkoff/cmp/form12.html?resv_id_no=".rnf($resv_id_no);
try {
    $receiptNum = $FaxService->SendFAX($bizno, $Sender, $Receivers, $Files,$reserveDT, $LinkID, $SenderName, $adsYN, $title, $requestNum);
    msggo("전송되었습니다. (접수번호 : $receiptNum )",$url_return);
}
catch (PopbillException $pe) {
    $code = $pe->getCode();
    $message = $pe->getMessage();
    msggo("전송하지 못했습니다. ($message )",$url_return);
}


?>
