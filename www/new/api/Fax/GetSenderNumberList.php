<?
$real_path=str_replace(strstr(realpath(__FILE__),"/api/"),"",realpath(__FILE__));
include $real_path.'/api/api_common.php';
include $real_path.'/api/Popbill/PopbillFax.php';
include $real_path.'/api/Fax/common.php';
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="/Example.css" media="screen" />
    <title>팝빌 SDK PHP 5.X Example.</title>
</head>
<?php
    /**
     * 팝빌에 등록한 연동회원의 팩스 발신번호 목록을 확인합니다.
     * - https://docs.popbill.com/fax/php/api#GetSenderNumberList
     */
    try {
        $result = $FaxService->GetSenderNumberList($bizno);
    }
    catch (PopbillException $pe) {
        $code = $pe->getCode();
        $message = $pe->getMessage();
    }

    $fax_list="";
    $fax_list_no1="";

    if ( isset( $result ) ) {
        for ( $i = 0; $i < Count ( $result ) ; $i++) {
            //if(!$result[$i]->representYN && $result[$i]->state){
            if($result[$i]->representYN){
                $fax_list_no1=",".$result[$i]->number;
                if($result[$i]->memo) $fax_list_no1="(".$result[$i]->memo.")";                
            }else{
                $fax_list.=",".$result[$i]->number;
                if($result[$i]->memo) $fax_list.="(".$result[$i]->memo.")";
            }
            $fax_list = $fax_list_no1.$fax_list;
        }

        $fax_list = ",02-6008-5902";
        echo "
            <script>
            parent.
            </script>
        ";
    } else {
        echo "
            <script>
            alert('등록된 발신번호가 없습니다.');
            </script>
        ";
    }
    ?>
