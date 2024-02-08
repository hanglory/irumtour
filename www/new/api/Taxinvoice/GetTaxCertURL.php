<?
$real_path=str_replace(strstr(realpath(__FILE__),"/api/"),"",realpath(__FILE__));
include $real_path.'/api/api_common.php';
include $real_path.'/api/Popbill/PopbillTaxinvoice.php';
include $real_path.'/api/Taxinvoice/common.php';


    /**
     * 전자세금계산서 발행에 필요한 인증서를 팝빌 인증서버에 등록하기 위한 페이지의 팝업 URL을 반환합니다.
     * - 반환되는 URL은 보안 정책상 30초 동안 유효하며, 시간을 초과한 후에는 해당 URL을 통한 페이지 접근이 불가합니다.
     * - 인증서 갱신/재발급/비밀번호 변경한 경우, 변경된 인증서를 팝빌 인증서버에 재등록 해야합니다.
     * - https://docs.popbill.com/taxinvoice/php/api#GetTaxCertURL
     */


    try {
        $url = $TaxinvoiceService->GetTaxCertURL($bizno, $LinkID);
    } catch (PopbillException $pe) {
        $code = $pe->getCode();
        $message = $pe->getMessage();
    }
?>
<body>
<div id="content">
    <p class="heading1">Response</p>
    <br/>
    <fieldset class="fieldset1">
        <legend>공인인증서 등록 URL</legend>
        <ul>
            <?php
            if (isset($url)) {
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
