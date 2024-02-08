<?
@header("Content-Type: text/html; charset=UTF-8");

//기본 상수 설정
define('SELF',  basename($_SERVER["SCRIPT_NAME"]));
define('MEMBERGROUP', "누구나,회원,관리자");
define('MEMBERGROUP_ID',  "0,1,9");


//세션시간 연장
ini_set("session.cache_expire", 129600);
ini_set("session.gc_maxlifetime", 129600);  // 세션 만료시간을 36시간으로 설정


@extract($HTTP_GET_VARS);
@extract($HTTP_POST_VARS);
@extract($HTTP_SESSION_VARS);
@extract($_SERVER);


//castle
$thisfilename=basename(__FILE__);
$temp_filename=realpath(__FILE__);
if(!$temp_filename) $temp_filename=__FILE__;
$osdir=eregi_replace($thisfilename,"",$temp_filename);
unset($temp_filename);
$castle_dir = str_replace("include/","castle-php",$osdir);
define("__CASTLE_PHP_VERSION_BASE_DIR__", $castle_dir);
include_once(__CASTLE_PHP_VERSION_BASE_DIR__."/castle_referee.php");




/*AES암호화*/
$inputKey = "rf)ZY87H$8J9c2:EwH/x";
$blockSize = 128;


/*****************************
기본설정
*****************************/
$MAX_TOUR_NUM = "10";//여행인원선택


$TITLE = "맞춤형 해외골프 전문여행사 - 이룸투어";


//네이버 단축URL
$Client_ID="HAqQbgIXrPZjeVIWXjdR";
$Client_Secret = "Td6HU4TdB0";

//카카오
$KAKAO_KEY = "022495d6ee9375a4a184e5fbfba973ed";

//지역
$REGION = "서울,인천,경기,강원,충북,충남,전북,전남,광주,경북,경남,대구,울산,부산,제주,울릉도";
$REGION2 = "서울특별시,인천광역시,경기도,강원도,충청북도,충청남도,전라북도,전라남도,광주광역시,경상북도,경상남도,대구광역시,울산광역시,부산광역시,제주도,울릉도";


//여행일정
$DAYS = "당일,무박,1박,2박,3박,4박,5박,6박,7박,8박,9박,10박,11박,12박,13박,14박,15박";
$DAYS2 = "2일,3일,4일,5일,6일,7일,8일,9일,10일,11일,12일,13일,14일,15일,16일";

//요일
$WEEKS = "월요일,화요일,수요일,목요일,금요일,토요일,일요일,공휴일,공휴일전일,공휴일전전일";
$WEEKS_VAL = "1,2,3,4,5,6,7,8,9,10";


//항공사
$AIR = "아시아나,대한항공,진에어,부산에어,제주항공,티웨이";

//공항
$AIRPORT = "인천,김포,부산,제주";

//항구
$SHIPPORT = "인천,부산,목포,제주";

//숙박
$HOTEL = "호텔,펜션,콘도";


//color
$COLOR_SET = "blue,gold,green,orange,purple,wine";
$COLOR = explode(",",$COLOR_SET);
$COLOR_CODE="#ff9900,#ff3300,#ff0000,#ff6699,#ff3399,#ff0099,#669900,#666600,#663300,#3399cc,#336699,#3333cc,#3300cc,#848484,#000000";


//출발상태
$TOUR_DAY_STATUS = "출발안함,예약가능,출발확정,예약마감";
$TOUR_DAY_STATUS_VAL = "0,1,2,3";



/*****************************
관리자
*****************************/
//진열 설정
/*
$SALE_GROUP= "상품 게시,상품 감추기,관리자 로그인 시 노출";
$SALE_GROUP_VAL= "T,F,M";
*/
$SALE_GROUP= "상품 게시,상품 감추기";
$SALE_GROUP_VAL= "T,F";

//상품구분
$TOUR_GROUP= "1,2,3,4,5";


//기타 정보
$TOUR_ETC1= "포함내역,불포함내역,취소환불규정,미팅장소";
$TOUR_ETC2= "최소행사인원,참고사항,비고,여행자보험";
$TOUR_ETC3= "취소환불규정,참고사항,비고"; //렌트카

//예약상태
$ORDER_STATUS = "접수중,입금요청,결제완료,결제오류,행사완료";
$DEFAULT_STATUS = "접수중";


//결제방식
$ORDER_PAYMETHOD = "신용카드,무통장입금,ARS";
$ORDER_PAYMETHOD_VAL = "card,bank,ars";

/*****************************
CP
*****************************/
//CP 그룹
$CP_GROUP= "A,B,C";


/*****************************
골프투어문의
*****************************/
$EVENT_NATION = "동남아,일본,중국,기타해외,한국";
$EVENT_AREA1 = "태국,필리핀,베트남,인도네시아";
$EVENT_AREA2 = "훗카이도,후쿠오카,혼슈,미야자키,가고시마,히로시마,오키나와";
$EVENT_AREA3 = "위해,연태,청도,대련";
$EVENT_AREA4 = "하와이,괌&사이판";
$EVENT_AREA5 = "제주도,강원도,전남,전북,경남,경북,충청도";

$EVENT_ETC1 = "시내호텔 및 주변골프장,골프리조트,상관없음";
$EVENT_ETC2 = "최상급,상급,중상급,실속";
$EVENT_ETC3 = "기본패키지,UPGRADE";
$EVENT_ETC4 = "기본객실(트윈),UPGRADE";
$EVENT_ETC5 = "사용,미사용";
$EVENT_ETC6 = "전체 골프,골프+관광,전체 관광";
$EVENT_ETC7 = "싱글,더블,트윈";



/*****************************
상품 게시 필터링
*****************************/
//CP 그룹
$PROOF_FILTER =" and bit=1 ";
$PROOF_FILTER.=($sessMember[grade]==9)? " and sale_group in ('T','M') " : " and sale_group='T' ";


/*****************************
강조하는 상품
*****************************/

//$BEST = "시즌별추천상품(메인),테마별(메인),초특가추천상품,인기상품(서브)";
//$BEST_VAL = "c1,c2,c4,c3";
$BEST = "시즌별추천상품(메인),테마별(메인),초특가추천상품";
$BEST_VAL = "c1,c2,c4";


// $BEST .= "카테고리 베스트,Premium golf Tours,Category Best Tours,베스트 추천상품";
// $BEST_VAL .= "best,premium,category,recomm";

$HOLE = "9홀,18홀,27홀,36홀,주중36홀/주말 및 공휴일18홀,주중36홀/주말 및 공휴일27홀,무제한";


/*view_cp*/
$BANK_NAMES="국민은행,신한은행,우리은행,농협은행,KEB하나은행,기업은행,산업은행,수협은행,SC제일은행,대구은행,부산은행,광주은행,제주은행,전북은행,경남은행,새마을금고,신협은행,우체국,씨티은행";
$BANK_NAMES_VAL="0004,0088,0020,0011,0081,0003,0002,0007,0023,0031,0032,0034,0035,0037,0039,0045,0048,0071,0027";



/*임시*/
$SITE_ROOT_PATH="renew";
$PUBLIC_PATH="/new/";
?>