<?
$real_path=str_replace(strstr(realpath(__FILE__),"/api/"),"",realpath(__FILE__));
include $real_path.'/api/api_common.php';
include $real_path.'/api/Popbill/PopbillKakao.php';
include $real_path.'/api/KakaoTalk/common.php';

    /**
     * 알림톡 템플릿을 신청하고 승인심사 결과를 확인하며 등록 내역을 확인하는 알림톡 템플릿 관리 페이지 팝업 URL을 반환합니다.
     * - 반환되는 URL은 보안 정책상 30초 동안 유효하며, 시간을 초과한 후에는 해당 URL을 통한 페이지 접근이 불가합니다.
     * - https://docs.popbill.com/kakao/php/api#GetATSTemplateMgtURL
     */

    try {
        $url = $KakaoService->GetATSTemplateMgtURL($bizno, $LinkID);
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
        <legend>알림톡 템플릿관리 팝업 URL</legend>
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
