<?
$real_path=str_replace(strstr(realpath(__FILE__),"/api/"),"",realpath(__FILE__));
include $real_path.'/api/api_common.php';
include $real_path.'/api/Popbill/PopbillKakao.php';
include $real_path.'/api/KakaoTalk/common.php';

    // 템플릿 코드
    //$templateCode = '021010000078';

    try {
        $templateInfo = $KakaoService->GetATSTemplate($bizno, $templateCode, $LinkID);
    }
    catch (PopbillException $pe) {
        $code = $pe->getCode();
        $message = $pe->getMessage();
    }
?>
<body>
    <div id="content">
        <p class="heading1">Response</p>
        <br/>
        <fieldset class="fieldset1">
            <legend>알림톡 템플릿 정보 확인</legend>
            <ul>
                <?php
                    if ( isset($code) ) {
                ?>
                        <li>Response.code : <?php echo $code ?> </li>
                        <li>Response.message : <?php echo $message ?></li>
                <?php
                    } else {
                ?>
                        <li>templateCode (템플릿 코드) : <?php echo $templateInfo->templateCode ?></li>
                        <li>templateName (템플릿 제목) : <?php echo $templateInfo->templateName ?></li>
                        <li>template (템플릿 내용) : <?php echo $templateInfo->template ?></li>
                        <li>plusFriendID (카카오톡채널 아이디) : <?php echo $templateInfo->plusFriendID ?></li>
                        <?php
                        if (isset($templateInfo->btns)) {
                            for ($j = 0; $j < Count($templateInfo->btns); $j++) {
                        ?>
                                <fieldset class="fieldset1">
                                    <legend> 버튼정보 [<?php echo $j + 1 ?>]</legend>
                                    <ul>
                                        <li>t (버튼유형) : <?php echo $templateInfo->btns[$j]->t ?></li>
                                        <li>n (버튼명) : <?php echo $templateInfo->btns[$j]->n ?></li>
                                        <li>u1 (버튼링크1) : <?php echo (isset($templateInfo->btns[$j]->u1)) ? $templateInfo->btns[$j]->u1 : ''; ?></li>
                                        <li>u2 (버튼링크2) : <?php echo (isset($templateInfo->btns[$j]->u2)) ? $templateInfo->btns[$j]->u2 : ''; ?></li>
                                    </ul>
                                </fieldset>
                <?php
                            }
                        }
                    }
                ?>
            </ul>
        </fieldset>
     </div>
</body>

</html>
