<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel="stylesheet" type="text/css" href="/Example.css" media="screen" />
        <title>팝빌 SDK PHP 5.X Example</title>
    </head>
<?php
    /**
     * 팝빌 사이트를 통해 발행하였지만 문서번호가 존재하지 않는 현금영수증에 문서번호를 할당합니다.
     * - https://dev-docs.linkhub.kr/cashbill/php/api#AssignMgtKey
     */

    include 'common.php';

    // 팝빌회원 사업자번호, '-'제외 10자리
    $testCorpNum = '1234567890';

    // 현금영수증 아이템키, 문서 목록조회(Search) API의 반환항목중 ItemKey 참조
    $itemKey = '020061716421500001';

    // 할당할 문서번호, 최대 24자리, 영문, 숫자 '-', '_'를 조합하여 사업자별로 중복되지 않도록 구성
    
    $mgtKey = '20210625-001';

    try {
        $result = $CashbillService->AssignMgtKey($testCorpNum, $itemKey, $mgtKey);
        $code = $result->code;
        $message = $result->message;
    }
    catch(PopbillException $pe) {
        $code = $pe->getCode();
        $message = $pe->getMessage();
    }
?>
    <body>
    <div id="content">
        <p class="heading1">Response</p>
        <br/>
        <fieldset class="fieldset1">
            <legend>문서번호 할당</legend>
            <ul>
                <li>Response.code : <?php echo $code ?></li>
                <li>Response.message : <?php echo $message ?></li>
            </ul>
        </fieldset>
    </div>
    </body>
</html>
