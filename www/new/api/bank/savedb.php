<?php
/*
* 계좌 거래내역 수집을 요청한다.
* - 검색기간은 현재일 기준 90일 이내로만 요청할 수 있다.
* - https://docs.popbill.com/easyfinbank/php/api#RequestJob
*/
include '/home/hosting_users/irumtour/www/new/api/api_common.php';
include '/home/hosting_users/irumtour/www/new/api/bank/common.php';
header("Content-Type: text/html; charset=UTF-8");


// 팝빌회원 사업자번호, '-'제외 10자리
$corpNum = $bizno;

// 은행코드
$BankCode = $bankcode;

// 계좌번호
$AccountNumber = $accno;


//파트너 계좌
if($cp_bank) $BankCode=$cp_bank;
if($cp_account) $AccountNumber=$cp_account;




// 시작일자, 형식(yyyyMMdd)
$SDate = rnf($date_s);

// 종료일자, 형식(yyyyMMdd)
$EDate = rnf($date_e);



try {
    $jobID = $EasyFinBankService->RequestJob($corpNum, $BankCode, $AccountNumber, $SDate, $EDate);
}
catch(PopbillException $pe) {
    $code = $pe->getCode();
    $message = $pe->getMessage();
}

// if(strstr("@14.37.242.84@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
//     checkVar("SDate",$SDate);
//     checkVar("EDate",$EDate);
//     checkVar("BankCode",$BankCode);
//     checkVar("AccountNumber",$AccountNumber);
//     checkVar("jobID",$jobID);
//     checkVar("code",$code);
//     checkVar("message",$message);
//     checkVar("corpNum",$corpNum);
//     checkVar("LinkID",$LinkID);
//     checkVar("USERID",$USERID);
//     //exit;
// }


$bank_error = $message;

//Search start
$userID =($USERID)? $USERID : $LinkID;

// 거래유형 배열, I-입금, O-출금
$TradeType = array (
    'I',
    'O'
);

// 페이지 번호
$Page = 1;

// 페이지당 목록개수
$PerPage = 100000000;

// 정렬방향, D-내림차순, A-오름차순
$Order = "A";

// 조회 검색어, 입금/출금액, 메모, 적요 like 검색
$SearchString = "";

try {
    $response = $EasyFinBankService->Search ( $corpNum, $jobID, $TradeType, $SearchString,
      $Page, $PerPage, $Order, $userID );
}
catch(PopbillException $pe) {
    $code = $pe->getCode();
    $message = $pe->getMessage();
}

$bank_error .= " ".$message;


$cp_id = $_SESSION['sessLogin']['cp_id'];
$cnt_save=0;
for ( $i = 0; $i < Count ( $response->list ); $i++ ) {

    $tid=$response->list[$i]->tid; //거래내역 아이디
    $trdate=$response->list[$i]->trdate; //거래일자
    $trserial=$response->list[$i]->trserial; //거래일련번호
    $trdt=$response->list[$i]->trdt; //거래일시
    $accIn=$response->list[$i]->accIn; //입금액
    $accOut=$response->list[$i]->accOut; //출금액
    $balance=$response->list[$i]->balance; //잔액
    $remark1=$response->list[$i]->remark1; //비고 1
    $remark2=$response->list[$i]->remark2; //비고 2
    $remark3=$response->list[$i]->remark3; //비고 3
    $remark4=$response->list[$i]->remark4; //비고 4
    $regDT=$response->list[$i]->regDT; //등록일시
    $memo=$response->list[$i]->memo; //메모

    $reg_date = date('Y/m/d');
    $reg_date2 = date('H:i:s');

    $sql="
       insert into cmp_bank (
          jobID,
          account_no,
          tid,
          trdate,
          trserial,
          trdt,
          accIn,
          accOut,
          balance,
          remark1,
          remark2,
          remark3,
          remark4,
          regDT,
          memo,
          cp_id,
          reg_date,
          reg_date2
      ) values (
          '$jobID',
          '$AccountNumber',
          '$tid',
          '$trdate',
          '$trserial',
          '$trdt',
          '$accIn',
          '$accOut',
          '$balance',
          '$remark1',
          '$remark2',
          '$remark3',
          '$remark4',
          '$regDT',
          '$memo',
          '$cp_id',
          '$reg_date',
          '$reg_date2'
    )";
    if($dbo->query($sql)){
      $cnt_save++;
    }
    // if(strstr("@14.37.241.55@221.154.110.119@","@".$_SERVER["REMOTE_ADDR"]."@")){
    //     checkVar(mysql_error(),$sql);
    //     checkVar($code,$message);
    // }
}

?>
