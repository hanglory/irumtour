<?
$real_path=str_replace(strstr(realpath(__FILE__),"/api/"),"",realpath(__FILE__));
include $real_path.'/api/api_common.php';
include $real_path.'/api/Popbill/PopbillCashbill.php';
include $real_path.'/api/Cashbill/common.php';

    /**
     * 국세청 전송 이전 "발행완료" 상태의 현금영수증을 "발행취소"하고 국세청 전송 대상에서 제외합니다.
     * - Delete(삭제)함수를 호출하여 "발행취소" 상태의 현금영수증을 삭제하면, 문서번호 재사용이 가능합니다.
     * - https://docs.popbill.com/cashbill/php/api#CancelIssue
     */

    // 메모
    $memo = '현금영수증 발행취소';

    try	{
        $result = $CashbillService->CancelIssue($bizno, $mgtKey, $memo);
        $code = $result->code;
        $message = $result->message;       

        if($mgtKey){
            $sql="delete from cmp_cashbill where mgtKey='$mgtKey'";
            $dbo->query($sql); 
        }

    }
    catch ( PopbillException $pe ) {
        $code = $pe->getCode();
        $message = $pe->getMessage();
    }

    echo "
        <script>
            alert(\"${message}\");
            top.location.reload();
        </script>    
    ";    
?>
