<?
/******************************************************
	사용법 : getErrorStatus($error_no);
	return value : title,message,redirect dir
*******************************************************/
function getErrorStatus($error_no){

	switch ($error_no){

 		case "payment_01" :
			$title="데이터 저장 오류.";
			$message="죄송합니다.<br><br>데이터를 저장하던 중 에러가 발생했습니다.<br>결재는 처리되지 않았습니다.<br>처음부터 다시 시도해 주세요.";
			$dir="common.php?tpl=payment";
			break;

 		case "payment_01" :
			$title="데이터 호출 오류.";
			$message="죄송합니다.<br><br>데이터를 불러오던 중 에러가 발생했습니다.<br>결재는 정상적으로 처리되었습니다.";
			$dir="common.php?tpl=payment";
			break;

 		case "payment_02" :
			$title="데이터 호출 오류.";
			$message="죄송합니다.<br><br>데이터를 불러오던 중 에러가 발생했습니다.<br>결재는 정상적으로 처리되었습니다.";
			$dir="common.php?tpl=payment";
			break;

 		case "payment_03" :
			$title="결재취소.";
			$message="결재를 취소하였습니다.<br><br>고객님께서 결재를 취소하셨습니다.";
			$dir="common.php?tpl=payment";
			break;

 		case "payment_04" :
			$title="결재실패.";
			$message="결재되지 않았습니다.";
			$dir="common.php?tpl=payment";
			break;

 		case "payment_05" :
			$title="결재취소.";
			$message="중복 결재되어 결재를 취소하였습니다.<br><br>자세한 내용은 고객센터로 문의 바랍니다.(support@easeplus.com).";
			$dir="common.php?tpl=payment";
			break;

 		case "payment_06" :
			$title="결재진행중.";
			$message="결재가 진행 중입니다.<br><br>자세한 내용은 고객센터로 문의 바랍니다.(support@easeplus.com).";
			$dir="common.php?tpl=payment";
			break;




		default :
			$title="잠시 예기치 못한 장애가 발생하였습니다.";
			$message="곧 조치 하겠습니다. Home으로 돌아가셔셔 다시 이용해 주세요.";

	}


	$result = array(title=>$title,message1=>$message1,dir=>$dir);

	return $result;

}
?>
