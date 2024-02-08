<?
$real_path=str_replace(strstr(realpath(__FILE__),"/api/"),"",realpath(__FILE__));
include $real_path.'/api/api_common.php';
include $real_path.'/api/Popbill/PopbillHTTaxinvoice.php';
include $real_path.'/api/HTTaxinvoice/common.php';

    /**
     * 홈택스에 신고된 전자세금계산서 매입/매출 내역 수집을 팝빌에 요청합니다.
     * - 수집 요청후 반환받은 작업아이디(JobID)의 유효시간은 1시간 입니다.
     * - https://docs.popbill.com/httaxinvoice/php/api#RequestJob
     */

    // 전자세금계산서 유형, SELL-매출, BUY-매입, TRUSTEE-위수탁
    $TIKeyType = KeyType::BUY;

    // 수집일자유형, W-작성일자, I-발행일자, S-전송일자
    $DType = 'S';

    // 시작일자, 형식(yyyyMMdd)
    $SDate = '20210801';

    // 종료일자, 형식(yyyyMMdd)
    $EDate = '20210826';

    try {
        $jobID = $HTTaxinvoiceService->RequestJob($bizno, $TIKeyType, $DType, $SDate, $EDate);
        redirect2("Search.php?jobID=$jobID");
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
                <legend>수집 요청</legend>
                <ul>
                    <?php
                        if ( isset($code) ) {
                    ?>
                        <li>Response.code : <?php echo $code ?> </li>
                        <li>Response.message : <?php echo $message ?></li>
                    <?php
                        } else {
                    ?>
                        <li>jobID(작업아이디) : <?php echo $jobID ?></li>
                    <?php
                        }
                    ?>
                </ul>
            </fieldset>
         </div>
    </body>
</html>
