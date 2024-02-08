<?
@header("Content-Type: text/html; charset=UTF-8");

$VIEW_PATH = "재방문,신규,추천,투어문의,기타";
$VIEW_PATH_ESTI = "신규,재문의,투어문의,추천";
$ROOM_TYPE = "싱글,트윈,트리플,더블룸,싱글&트윈,화실,양실,화양실,엑스트라베드,1베드룸,2베드룸빌라,3베드룸빌라";
$PAY_METHOD = "현금,카드,혼합";
$YN = "Y,N";
$SENDING_YN = "Y,N,우편,배웅";
$BASIC_ACCOUNT = "국민은행 813037-04-005009 (주)이룸플레이스";
$CUSTOMER_TYPE = "주말&연휴,장기체류,실속&특가,VIP";

$STAFF_POWER = "기본설정,문의고객관리대장	,고객별예약정보,견적서등록,기간별매출,고객송출현황,일일현황,문자발송,담당자관리,엑셀다운로드,보고서,핸드폰번호다운로드,인사관리";
$STAFF_POWER2 = "기본설정,문의고객관리대장	,고객별예약정보관리대장,견적서관리대장,기간별매출현황,고객송출현황,출력양식,문자발송,담당자관리,엑셀다운로드,보고서,핸드폰번호다운로드,인사관리";

$AIRPORTS_OUT = "인천,김포,김해,청주,대구,무안";

$PAPER_DEFAULT_DAY1= date("Y/m/d",mktime(0,0,0,date("m"),1,date("Y")));
$PAPER_DEFAULT_DAY2= date("Y/m/d",mktime(0,0,0,date("m")+1,1,date("Y"))-1);
$YEAR_PREV = date("Y/m/d",mktime(0,0,0,1,1,date("Y")-1));
$YEAR_THIS = date("Y/m/d",mktime(0,0,0,1,1,date("Y")+1)-1);

$TEN = "1000";//1000단위 자르기(레포트)

$CAR1 = "전용차량,셔틀버스,송영차량,택시,렌트카";
$CAR2 = "도보,셔틀버스,전용차량,송영차량,렌트카";
$CAR3 = "셔틀버스,전용차량,송영차량,렌트카";

/*2018-09-03*/
$CANCEL_TXT="▶여행자의 여행계약 해제 요청 시 여행약관에 의거하여 취소료가 부과됩니다◀
제15조(여행출발 전 계약해제)
- 여행개시 30일전(~30)까지 통보시 : 계약금환급
- 여행개시 20일전(29~20)까지 통보시: 총상품가격의 10% 배상
- 여행개시 10일전(19~10)까지 통보시 : 총상품가격의 15% 배상
- 여행개시 8일전(9~8)까지 통보시 : 총상품가격의 20% 배상
- 여행개시 1일전(7~1)까지 통보시 : 총상품가격의 30% 배상
- 여행 당일 통보시 : 총상품가격의 50% 배상
※ 단 상위 취소료 규정과 별도로 출발일 기준과 상관없이 미리 발권된 항공권은 항공사별 별도 취소료가 발생됩니다. 반드시 이점 반드시 숙지 부탁 드립니다.
※공정거래위원회 고시 제2011-10호 소비자분쟁해결기준에 의한 것으로 제9조,제15조의 변경사항은 2011년12월28일 여행상품예약자부터 적용
▶ 근무일(공휴일 및 토, 일요일 제외) 및 근무시간(18시 30분까지) 내에 취소요청에 한함
▶ 천재지변이나, 항공사의 사정으로 인해 여행에 지장을 끼친 경우, 본사의 법적이나 도의적인 책임은 없음을 알려드립니다.
이 점 양해부탁드립니다.
** 2017.1.1 이후 발권한 항공권은 여행상품 취소수수료와 별도로 잔여 출발 및 지역 기준으로 항공사 자체 취소 수수료가 발생되오니 참고하시기 바랍니다.
예) 대한항공 기준 출발 90일 이전은 무료, 15일 이전, 일본 중국 7만원 등 180903";

$PLAN_TYPE1= "A(조석),B(조조),C(석조),D(석석),E(동남아 석석),F(동남아 석조),G(괌/사이판),H(동남아 조석),I(하와이 석조)";
$PLAN_TYPE2= "A,B,C,D,E,F,G,H,I";

$SALT = "rf)ZY87H$8J9c2:EwH/x";
$BSP = "하나,강산,비자,기타";

?>