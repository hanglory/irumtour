<?php
  /**
  * 팝빌 계좌조회 API PHP SDK Example
  *
  * PHP SDK 연동환경 설정방법 안내 : https://docs.popbill.com/easyfinbank/tutorial/php
  * 업데이트 일자 : 2020-10-07
  * 연동기술지원 연락처 : 1600-9854 / 070-4304-2991
  * 연동기술지원 이메일 : code@linkhub.co.kr
  */

  require_once '/home/hosting_users/irumtour/www/new/api/Popbill/PopbillEasyFinBank.php';

  // 링크 아이디
  $LinkID = 'IRUMPLACE';

  // 발급받은 비밀키. 유출에 주의하시기 바랍니다.
  $SecretKey = 'Z7cYwtvKFiKUWvkB5NdLCWfRKnrIrBr+fJTfQKH7vUw=';

  // 통신방식 기본은 CURL , curl 사용에 문제가 있을경우 STREAM 사용가능.
  // STREAM 사용시에는 php.ini 파일의 allow_url_fopen = on 으로 설정해야함.
  define('LINKHUB_COMM_MODE','CURL');

  $EasyFinBankService = new EasyFinBankService($LinkID, $SecretKey);

  // 연동환경 설정값, 개발용(true), 상업용(false)
  $EasyFinBankService->IsTest(false);

  // 인증토큰에 대한 IP제한기능 사용여부, 권장(true)
  $EasyFinBankService->IPRestrictOnOff(true);

  // 팝빌 API 서비스 고정 IP 사용여부(GA), 기본값(false)
  $EasyFinBankService->UseStaticIP(false);

  // 로컬서버 시간 사용 여부 true(기본값) - 사용, false(미사용)
  $EasyFinBankService->UseLocalTimeYN(true);

  /*irum 정보*/
  $bizno = "2208731753";
  $bankcode = "0004";
  $accno = "81303704005009";


  /*cp 개별 정보 적용 s*/
    if($_SESSION['sessLogin']['cp_id']){
        $inc_file = $_SERVER['DOCUMENT_ROOT']."/new/public/cp/config_cp_".$_SESSION['sessLogin']['cp_id'].".inc";
        @include($inc_file);

        $bizno = $CORPNUM;
        $bankcode = $BANK;
        $accno = $BANK_ACCOUNT;        

        //checkVar("bizno",$bizno);
        //checkVar("bankcode",$bankcode);
        //checkVar("accno",$accno);
    }
  /*cp 개별 정보 적용 f*/


?>
