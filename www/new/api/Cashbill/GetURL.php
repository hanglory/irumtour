<?
$real_path=str_replace(strstr(realpath(__FILE__),"/api/"),"",realpath(__FILE__));
include $real_path.'/api/api_common.php';
include $real_path.'/api/Popbill/PopbillCashbill.php';
include $real_path.'/api/Cashbill/common.php';


    /**
     * 팝빌 현금영수증 문서함 팝업 URL을 반환합니다.
     * - 반환되는 URL은 보안 정책상 30초 동안 유효하며, 시간을 초과한 후에는 해당 URL을 통한 페이지 접근이 불가합니다.
     * - https://docs.popbill.com/cashbill/php/api#GetURL
     */

    // TBOX(임시문서함), PBOX(발행문서함), WRITE(현금영수증 작성)
    $TOGO = 'WRITE';

    try {
        $url = $CashbillService->GetURL($bizno, $LinkID, $TOGO);
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
                <legend>현금영수증 관련 URL 확인</legend>
                <ul>
                    <?php
                        if ( isset($url) ) {
                    ?>
                          <li>url : <?php echo $url ?></li>
                    <?php
                        } else {
                    ?>
                          <li>Response.code : <?php echo $code ?> </li>
                          <li>Response.message : <?php echo $message ?></li>
                    <?php
                        }
                    ?>
                </ul>
            </fieldset>
         </div>
    </body>
</html>
